<?php

namespace App\Services\Conversion;

use App\Models\ConversionTask;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use ZipArchive;

class ConversionPipeline
{
    public function process(ConversionTask $task): array
    {
        $tool = $task->tool_key;
        $workDir = storage_path('app/private/conversions/work/'.Str::uuid()->toString());

        if (! is_dir($workDir)) {
            mkdir($workDir, 0775, true);
        }

        $inputFiles = $task->uploadedFiles->sortBy('sort_order')->values();
        $inputPaths = $inputFiles->map(fn ($file) => Storage::disk($file->disk)->path($file->path))->all();

        try {
            return match ($tool) {
                'merge_pdf' => $this->mergePdf($inputPaths, $workDir),
                'compress_pdf' => $this->compressPdf($inputPaths, $workDir),
                'pdf_to_jpg' => $this->pdfToJpg($inputPaths, $workDir),
                'word_to_pdf' => $this->officeConvert($inputPaths, $workDir, 'pdf'),
                'excel_to_pdf' => $this->officeConvert($inputPaths, $workDir, 'pdf'),
                'pdf_to_word' => $this->officeConvert($inputPaths, $workDir, 'docx'),
                'pdf_to_excel' => $this->officeConvert($inputPaths, $workDir, 'xlsx'),
                'jpg_to_pdf' => $this->jpgToPdf($inputPaths, $workDir),
                default => throw new \RuntimeException('Desteklenmeyen donusum araci.'),
            };
        } finally {
            $this->cleanupPath($workDir);
        }
    }

    private function mergePdf(array $inputPaths, string $workDir): array
    {
        if (count($inputPaths) < 2) {
            throw new \RuntimeException('Merge PDF icin en az 2 PDF dosyasi gerekir.');
        }

        $this->assertBinary('pdfunite', 'poppler-utils (pdfunite) kurulu olmali.');

        $output = $workDir.'/merged.pdf';
        $this->run(['pdfunite', ...$inputPaths, $output]);

        return $this->storeOutput($output, 'merged.pdf', 'application/pdf');
    }

    private function compressPdf(array $inputPaths, string $workDir): array
    {
        if (count($inputPaths) !== 1) {
            throw new \RuntimeException('Compress PDF bir adet PDF bekler.');
        }

        $this->assertBinary('gs', 'Ghostscript (gs) kurulu olmali.');

        $output = $workDir.'/compressed.pdf';

        $this->run([
            'gs',
            '-sDEVICE=pdfwrite',
            '-dCompatibilityLevel=1.4',
            '-dPDFSETTINGS=/ebook',
            '-dNOPAUSE',
            '-dQUIET',
            '-dBATCH',
            '-sOutputFile='.$output,
            $inputPaths[0],
        ]);

        return $this->storeOutput($output, 'compressed.pdf', 'application/pdf');
    }

    private function pdfToJpg(array $inputPaths, string $workDir): array
    {
        if (count($inputPaths) !== 1) {
            throw new \RuntimeException('PDF to JPG bir adet PDF bekler.');
        }

        $this->assertBinary('pdftoppm', 'poppler-utils (pdftoppm) kurulu olmali.');

        $prefix = $workDir.'/page';
        $this->run(['pdftoppm', '-jpeg', '-r', '150', $inputPaths[0], $prefix]);

        $pages = collect(glob($workDir.'/page-*.jpg') ?: [])->sort()->values();

        if ($pages->isEmpty()) {
            throw new \RuntimeException('PDF to JPG cikti uretmedi.');
        }

        if ($pages->count() === 1) {
            return $this->storeOutput((string) $pages->first(), 'page-1.jpg', 'image/jpeg');
        }

        $zipPath = $workDir.'/pdf-to-jpg.zip';
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('ZIP dosyasi olusturulamadi.');
        }

        foreach ($pages as $pagePath) {
            $zip->addFile($pagePath, basename($pagePath));
        }

        $zip->close();

        return $this->storeOutput($zipPath, 'pdf-to-jpg.zip', 'application/zip');
    }

    private function officeConvert(array $inputPaths, string $workDir, string $targetExt): array
    {
        if (count($inputPaths) !== 1) {
            throw new \RuntimeException('Bu arac tek dosya ile calisir.');
        }

        $officeBinary = $this->officeBinary();
        $command = [
            $officeBinary,
            '--headless',
            '--convert-to',
            $targetExt,
            '--outdir',
            $workDir,
            $inputPaths[0],
        ];

        $process = $this->run($command, 'LibreOffice donusumu basarisiz.');

        $expected = pathinfo($inputPaths[0], PATHINFO_FILENAME).'.'.$targetExt;
        $output = $workDir.'/'.$expected;

        if (! is_file($output)) {
            $candidate = collect(glob($workDir.'/*.'.$targetExt) ?: [])->first();
            $output = $candidate ?: $output;
        }

        if (! is_file($output)) {
            $diagnostics = $this->commandOutput($process);

            Log::warning('LibreOffice output missing after successful process', [
                'binary' => $officeBinary,
                'target_ext' => $targetExt,
                'input' => basename($inputPaths[0]),
                'command' => $this->commandToString($command),
                'stdout' => trim($process->getOutput()),
                'stderr' => trim($process->getErrorOutput()),
            ]);

            $message = 'LibreOffice cikti uretmedi.';

            if ($diagnostics !== '') {
                $message .= ' Detay: '.$diagnostics;
            }

            $message .= ' Olasi nedenler: PDF tarama/gorsel tabanli olabilir, dosya korumali olabilir veya LibreOffice PDF importu bu dosya icin DOCX/XLSX cikti olusturamamis olabilir.';
            $message .= ' Komut: '.$this->commandToString($command);

            throw new \RuntimeException($message);
        }

        $mime = match ($targetExt) {
            'pdf' => 'application/pdf',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            default => 'application/octet-stream',
        };

        return $this->storeOutput($output, basename($output), $mime);
    }

    private function jpgToPdf(array $inputPaths, string $workDir): array
    {
        $output = $workDir.'/images.pdf';

        if ($this->binaryExists('img2pdf')) {
            $this->run(['img2pdf', ...$inputPaths, '-o', $output]);
        } elseif ($this->binaryExists('magick')) {
            $this->run(['magick', ...$inputPaths, $output]);
        } else {
            throw new \RuntimeException('JPG to PDF backend icin img2pdf veya ImageMagick gerekli.');
        }

        return $this->storeOutput($output, 'images.pdf', 'application/pdf');
    }

    private function storeOutput(string $sourcePath, string $name, string $mime): array
    {
        if (! is_file($sourcePath)) {
            throw new \RuntimeException('Donusum cikti dosyasi bulunamadi.');
        }

        $outputPath = 'conversions/outputs/'.date('Y/m/d').'/'.Str::uuid()->toString().'_'.$name;
        Storage::disk('local')->put($outputPath, file_get_contents($sourcePath));

        return [
            'disk' => 'local',
            'path' => $outputPath,
            'name' => $name,
            'mime' => $mime,
            'size_bytes' => Storage::disk('local')->size($outputPath),
        ];
    }

    private function run(array $command, ?string $context = null): Process
    {
        $process = new Process($command);
        $process->setTimeout(900);
        $process->run();

        if (! $process->isSuccessful()) {
            $message = ($context ? $context.' ' : '').'Komut basarisiz oldu.';
            $message .= ' Exit code: '.($process->getExitCode() ?? 'n/a').'.';

            $details = $this->commandOutput($process);
            if ($details !== '') {
                $message .= ' Detay: '.$details;
            }

            $message .= ' Komut: '.$this->commandToString($command);

            throw new \RuntimeException($message);
        }

        return $process;
    }

    private function assertBinary(string $binary, string $hint): void
    {
        if (! $this->binaryExists($binary)) {
            throw new \RuntimeException($hint);
        }
    }

    private function officeBinary(): string
    {
        if ($this->binaryExists('libreoffice')) {
            return 'libreoffice';
        }

        if ($this->binaryExists('soffice')) {
            return 'soffice';
        }

        throw new \RuntimeException('LibreOffice headless kurulu olmali (libreoffice veya soffice).');
    }

    private function binaryExists(string $binary): bool
    {
        $process = new Process(['which', $binary]);
        $process->run();

        return $process->isSuccessful();
    }

    private function cleanupPath(string $path): void
    {
        if (! is_dir($path)) {
            return;
        }

        $items = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($items as $item) {
            if ($item->isDir()) {
                rmdir($item->getPathname());
            } else {
                unlink($item->getPathname());
            }
        }

        rmdir($path);
    }

    private function commandOutput(Process $process, int $limit = 800): string
    {
        $output = trim($process->getErrorOutput());

        if ($output === '') {
            $output = trim($process->getOutput());
        }

        if ($output === '') {
            return '';
        }

        if (mb_strlen($output) <= $limit) {
            return $output;
        }

        return mb_substr($output, 0, $limit).'...';
    }

    private function commandToString(array $command): string
    {
        return implode(' ', array_map(static fn ($part) => escapeshellarg((string) $part), $command));
    }
}
