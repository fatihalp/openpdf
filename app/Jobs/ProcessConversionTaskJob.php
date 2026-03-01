<?php

namespace App\Jobs;

use App\Models\ConversionTask;
use App\Services\Conversion\ConversionPipeline;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessConversionTaskJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 1;

    public int $timeout = 900;

    public int $maxExceptions = 1;

    public function __construct(public int $taskId)
    {
        $this->onQueue('conversions');
    }

    public function handle(ConversionPipeline $pipeline): void
    {
        $task = ConversionTask::query()->with('uploadedFiles')->find($this->taskId);

        if (! $task || $task->status !== ConversionTask::STATUS_PENDING) {
            return;
        }

        $task->update([
            'status' => ConversionTask::STATUS_PROCESSING,
            'started_at' => now(),
        ]);

        try {
            $result = $pipeline->process($task->fresh('uploadedFiles'));

            $task->update([
                'status' => ConversionTask::STATUS_COMPLETED,
                'completed_at' => now(),
                'output_disk' => $result['disk'],
                'output_path' => $result['path'],
                'output_name' => $result['name'],
                'output_mime' => $result['mime'],
                'output_size_bytes' => $result['size_bytes'],
                'error_message' => null,
            ]);
        } catch (\Throwable $exception) {
            $task->update([
                'status' => ConversionTask::STATUS_FAILED,
                'completed_at' => now(),
                'error_message' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }
}
