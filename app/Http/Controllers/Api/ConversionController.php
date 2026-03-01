<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessConversionTaskJob;
use App\Models\ConversionTask;
use App\Models\UploadedFile;
use App\Support\ToolCatalog;
use Dedoc\Scramble\Attributes\BodyParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ConversionController extends Controller
{
    #[BodyParameter(
        'tool_key',
        'Conversion tool identifier.',
        required: true,
        example: 'pdf_to_word'
    )]
    #[BodyParameter(
        'source',
        'Conversion execution source. Must always be backend.',
        required: true,
        example: 'backend'
    )]
    #[BodyParameter(
        'options',
        'Optional tool settings. JPG to PDF accepts orientation, pageSize, margin, singleFile. Split PDF accepts split.mode, split.merge_into_one, and split.pages.',
        required: false,
        example: [
            'orientation' => 'portrait',
            'pageSize' => 'a4',
            'margin' => 0,
            'singleFile' => true,
            'split' => [
                'mode' => 'selected',
                'merge_into_one' => false,
                'pages' => [1, 2, 3],
            ],
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $request->merge([
            'source' => (string) $request->input('source', 'backend'),
        ]);

        $validated = $request->validate([
            'tool_key' => ['required', 'string', Rule::in(array_keys(ToolCatalog::all()))],
            'source' => ['required', 'string', Rule::in(['backend'])],
            'files' => ['required', 'array', 'min:1'],
            'files.*.name' => ['required', 'string', 'max:255'],
            'files.*.type' => ['nullable', 'string', 'max:120'],
            'files.*.size' => ['required', 'integer', 'min:1'],
            'files.*.data' => ['nullable', 'string', 'required_if:source,backend'],
            'files.*.rotation' => ['nullable', 'integer', Rule::in([0, 90, 180, 270])],
            'options' => ['nullable', 'array:orientation,pageSize,margin,singleFile,split'],
            'options.orientation' => ['sometimes', 'string', Rule::in(['portrait', 'landscape'])],
            'options.pageSize' => ['sometimes', 'string', Rule::in(['a4', 'letter', 'a3', 'fit'])],
            'options.margin' => ['sometimes', 'integer', Rule::in([0, 8, 16, 24])],
            'options.singleFile' => ['sometimes', 'boolean'],
            'options.split' => ['sometimes', 'array:mode,merge_into_one,pages'],
            'options.split.mode' => ['sometimes', 'string', Rule::in(['all', 'selected'])],
            'options.split.merge_into_one' => ['sometimes', 'boolean'],
            'options.split.pages' => ['sometimes', 'array'],
            'options.split.pages.*' => ['integer', 'min:1'],
            'output' => ['nullable', 'array:name,mime,size'],
            'output.name' => ['nullable', 'string', 'max:255', 'required_if:source,browser'],
            'output.mime' => ['nullable', 'string', 'max:120'],
            'output.size' => ['nullable', 'integer', 'min:0'],
        ]);

        $toolKey = $validated['tool_key'];
        $files = $validated['files'];
        $totalBytes = array_sum(array_map(static fn (array $file): int => (int) $file['size'], $files));
        $this->enforceVisitorLimits($request, count($files), $totalBytes);
        $this->validateFileTypes($toolKey, $files);

        $visitorToken = $this->visitorToken($request);
        $source = $validated['source'];

        $task = ConversionTask::create([
            'user_id' => $request->user()?->id,
            'visitor_token' => $request->user() ? null : $visitorToken,
            'tool_key' => $toolKey,
            'status' => $source === 'browser' ? ConversionTask::STATUS_COMPLETED : ConversionTask::STATUS_PENDING,
            'file_count' => count($files),
            'total_size_bytes' => $totalBytes,
            'options' => $validated['options'] ?? [],
            'completed_at' => $source === 'browser' ? now() : null,
            'output_name' => $source === 'browser' ? ($validated['output']['name'] ?? null) : null,
            'output_mime' => $source === 'browser' ? ($validated['output']['mime'] ?? null) : null,
            'output_size_bytes' => $source === 'browser' ? ($validated['output']['size'] ?? null) : null,
        ]);

        foreach ($files as $index => $file) {
            $path = null;
            $disk = $source === 'browser' ? 'browser' : 'local';

            if ($source !== 'browser') {
                $raw = $this->decodeBase64((string) $file['data']);
                $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $file['name']) ?: ('file_'.$index);
                $path = sprintf('conversions/uploads/%d/%s_%s', $task->id, Str::random(10), $safeName);
                Storage::disk('local')->put($path, $raw);
            }

            UploadedFile::create([
                'conversion_task_id' => $task->id,
                'user_id' => $request->user()?->id,
                'visitor_token' => $request->user() ? null : $visitorToken,
                'original_name' => $file['name'],
                'mime_type' => $file['type'] ?? null,
                'size_bytes' => (int) $file['size'],
                'disk' => $disk,
                'path' => $path ?? ('browser://'.$file['name']),
                'sort_order' => $index,
                'rotation_degrees' => (int) ($file['rotation'] ?? 0),
            ]);
        }

        if ($source !== 'browser') {
            ProcessConversionTaskJob::dispatch($task->id)->onQueue('conversions');
        }

        return response()->json([
            'ok' => true,
            'task' => $this->taskPayload($task->fresh()),
        ]);
    }

    public function show(Request $request, ConversionTask $task): JsonResponse
    {
        $this->authorizeTask($request, $task);

        return response()->json([
            'ok' => true,
            'task' => $this->taskPayload($task->fresh()),
        ]);
    }

    public function download(Request $request, ConversionTask $task)
    {
        $this->authorizeTask($request, $task);

        if ($task->status !== ConversionTask::STATUS_COMPLETED || ! $task->output_path) {
            return response()->json([
                'ok' => false,
                'message' => 'Output is not ready yet.',
            ], 422);
        }

        $absolutePath = Storage::disk($task->output_disk ?: 'local')->path($task->output_path);

        if (! is_file($absolutePath)) {
            return response()->json([
                'ok' => false,
                'message' => 'Output file not found.',
            ], 404);
        }

        return response()->download($absolutePath, $task->output_name ?? basename($absolutePath), [
            'Content-Type' => $task->output_mime ?: 'application/octet-stream',
        ]);
    }

    private function taskPayload(ConversionTask $task): array
    {
        return [
            'id' => $task->id,
            'tool_key' => $task->tool_key,
            'status' => $task->status,
            'file_count' => $task->file_count,
            'total_size_bytes' => $task->total_size_bytes,
            'output_name' => $task->output_name,
            'output_size_bytes' => $task->output_size_bytes,
            'error_message' => $task->error_message,
            'download_url' => $task->status === ConversionTask::STATUS_COMPLETED && $task->output_path
                ? route('api.conversions.download', $task)
                : null,
            'created_at' => optional($task->created_at)->toIso8601String(),
            'completed_at' => optional($task->completed_at)->toIso8601String(),
        ];
    }

    private function authorizeTask(Request $request, ConversionTask $task): void
    {
        $user = $request->user();

        if ($user && $task->user_id === $user->id) {
            return;
        }

        $visitorToken = $this->visitorToken($request);

        if (! $user && $task->visitor_token !== null && hash_equals($task->visitor_token, $visitorToken)) {
            return;
        }

        abort(403);
    }

    private function enforceVisitorLimits(Request $request, int $fileCount, int $totalBytes): void
    {
        if ($request->user()) {
            return;
        }

        $limits = ToolCatalog::visitorLimits();

        if ($fileCount > $limits['max_files']) {
            abort(422, "Visitor limit exceeded: maximum {$limits['max_files']} files.");
        }

        if ($totalBytes > $limits['max_bytes']) {
            $maxMb = (int) floor($limits['max_bytes'] / (1024 * 1024));
            abort(422, "Visitor limit exceeded: maximum {$maxMb} MB.");
        }
    }

    private function validateFileTypes(string $toolKey, array $files): void
    {
        $tool = ToolCatalog::tool($toolKey);
        $allowedExtensions = $tool['accept_extensions'] ?? [];
        $allowedMime = $tool['accept_mime'] ?? [];

        foreach ($files as $file) {
            $name = (string) ($file['name'] ?? 'file');
            $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $mime = strtolower((string) ($file['type'] ?? ''));

            $extensionOk = in_array($extension, $allowedExtensions, true);
            $mimeOk = $mime !== '' && in_array($mime, $allowedMime, true);

            if (! $extensionOk && ! $mimeOk) {
                abort(422, "File type is not compatible with the selected tool: {$name}.");
            }
        }
    }

    private function decodeBase64(string $payload): string
    {
        $clean = str_contains($payload, ',') ? explode(',', $payload, 2)[1] : $payload;
        $decoded = base64_decode($clean, true);

        if ($decoded === false) {
            abort(422, 'Failed to decode file data.');
        }

        return $decoded;
    }

    private function visitorToken(Request $request): string
    {
        $token = $request->session()->get('visitor_token');

        if (! is_string($token) || $token === '') {
            $token = Str::random(64);
            $request->session()->put('visitor_token', $token);
        }

        return $token;
    }
}
