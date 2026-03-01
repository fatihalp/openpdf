<?php

namespace App\Filament\Pages;

use App\Services\Conversion\ConversionPipeline;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class SystemCheck extends Page
{
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-cpu-chip';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Settings';
    }

    protected string $view = 'filament.pages.system-check';

    protected static bool $isFullWidth = true;

    public array $testResults = [];

    public function testTool(string $toolKey): void
    {
        try {
            // Reset previous specific test outcome
            unset($this->testResults[$toolKey]);

            // Determine sample file logic based on toolKey type.
            $inputPaths = [];

            if ($toolKey === 'jpg_to_pdf') {
                $inputPaths = [base_path('public/sample_file_example_JPG_100kB.jpg')];
            }
            elseif ($toolKey === 'word_to_pdf') {
                $inputPaths = [base_path('public/word_docx-sample_100kB.docx')];
            }
            elseif ($toolKey === 'excel_to_pdf') {
                $inputPaths = [base_path('public/sample_file_example_XLS_10.xls')];
            }
            elseif ($toolKey === 'merge_pdf') {
                // Requires 2 PDF files
                $inputPaths = [base_path('public/sample.pdf'), base_path('public/sample.pdf')];
            }
            else {
                // PDF input: compress_pdf, pdf_to_jpg, pdf_to_word, pdf_to_excel
                $inputPaths = [base_path('public/sample.pdf')];
            }

            // Ensure source file actually exists to provide better error messaging
            if (!file_exists($inputPaths[0])) {
                throw new \Exception('Test sample file not found: ' . basename($inputPaths[0]));
            }

            // Calculate Total Input Size
            $inputSize = 0;
            $inputNames = [];
            foreach ($inputPaths as $path) {
                $inputSize += filesize($path);
                $inputNames[] = basename($path);
            }

            $output = app(ConversionPipeline::class)->executePipeline($toolKey, $inputPaths);

            $this->testResults[$toolKey] = [
                'status' => 'success',
                'input_name' => implode(', ', array_unique($inputNames)),
                'input_size' => $inputSize,
                'output_name' => $output['name'],
                'output_size' => $output['size_bytes'],
                'output_path' => $output['path'],
            ];

            Notification::make()
                ->title('Test Successful')
                ->body("Tool '{$toolKey}' completed successfully.")
                ->success()
                ->send();

        }
        catch (\Throwable $e) {
            Notification::make()
                ->title('Test Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->testResults[$toolKey] = [
                'status' => 'failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    public function downloadResult(string $toolKey)
    {
        if (!isset($this->testResults[$toolKey]) || $this->testResults[$toolKey]['status'] !== 'success') {
            Notification::make()->title('File not found')->danger()->send();

            return;
        }

        return Storage::disk('local')->download($this->testResults[$toolKey]['output_path']);
    }

    public function getSystemChecks(): array
    {
        return [
            [
                'name' => 'Ghostscript (gs)',
                'description' => 'Required for Compress PDF',
                'installed' => $this->binaryExists('gs'),
            ],
            [
                'name' => 'LibreOffice (libreoffice/soffice)',
                'description' => 'Required for Word/Excel to PDF and PDF to Word/Excel',
                'installed' => $this->checkLibreOffice(),
            ],
            [
                'name' => 'Poppler Utils (pdfunite)',
                'description' => 'Required for Merge PDF',
                'installed' => $this->binaryExists('pdfunite'),
            ],
            [
                'name' => 'Poppler Utils (pdftoppm)',
                'description' => 'Required for PDF to JPG',
                'installed' => $this->binaryExists('pdftoppm'),
            ],
            [
                'name' => 'ImageMagick (magick) or img2pdf',
                'description' => 'Required for JPG to PDF',
                'installed' => $this->binaryExists('magick') || $this->binaryExists('img2pdf'),
            ],
        ];
    }

    private function binaryExists(string $binary): bool
    {
        $process = new Process(['which', $binary]);

        // Ensure Homebrew paths are in the environment when running through a web server on macOS.
        $env = $_ENV;
        $env['PATH'] = '/opt/homebrew/bin:/usr/local/bin:' . getenv('PATH');
        $process->setEnv($env);

        $process->run();

        return $process->isSuccessful();
    }

    private function checkLibreOffice(): bool
    {
        if ($this->binaryExists('libreoffice') || $this->binaryExists('soffice')) {
            return true;
        }

        if (PHP_OS_FAMILY === 'Darwin' && is_executable('/Applications/LibreOffice.app/Contents/MacOS/soffice')) {
            return true;
        }

        return false;
    }
}