<?php

namespace App\Filament\Pages;

use App\Services\Conversion\ConversionPipeline;
use App\Support\BinaryResolver;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;

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

    public bool $isRunningAll = false;

    public int $runAllTotal = 0;

    public int $runAllDone = 0;

    public function testTool(string $toolKey): void
    {
        try {
            unset($this->testResults[$toolKey]);

            $inputPaths = [];

            if ($toolKey === 'jpg_to_pdf') {
                $inputPaths = [base_path('public/sample_file_example_JPG_100kB.jpg')];
            } elseif ($toolKey === 'word_to_pdf') {
                $inputPaths = [base_path('public/word_docx-sample_100kB.docx')];
            } elseif ($toolKey === 'excel_to_pdf') {
                $inputPaths = [base_path('public/sample_file_example_XLS_10.xls')];
            } elseif ($toolKey === 'merge_pdf') {
                $inputPaths = [base_path('public/sample.pdf'), base_path('public/sample.pdf')];
            } else {
                $inputPaths = [base_path('public/sample.pdf')];
            }

            if (! file_exists($inputPaths[0])) {
                throw new \Exception('Test sample file not found: '.basename($inputPaths[0]));
            }

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

        } catch (\Throwable $e) {
            $this->testResults[$toolKey] = [
                'status' => 'failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    public function runAllTests(): void
    {
        $tools = [
            'compress_pdf',
            'word_to_pdf',
            'excel_to_pdf',
            'pdf_to_word',
            'pdf_to_excel',
            'merge_pdf',
            'pdf_to_jpg',
            'jpg_to_pdf',
        ];

        $this->isRunningAll = true;
        $this->runAllTotal = count($tools);
        $this->runAllDone = 0;
        $this->testResults = [];

        foreach ($tools as $toolKey) {
            $this->testTool($toolKey);
            $this->runAllDone++;
        }

        $this->isRunningAll = false;
    }

    public function getTestSummary(): array
    {
        $results = collect($this->testResults);

        return [
            'total' => $results->count(),
            'passed' => $results->where('status', 'success')->count(),
            'failed' => $results->where('status', 'failed')->count(),
        ];
    }

    public function downloadResult(string $toolKey)
    {
        if (! isset($this->testResults[$toolKey]) || $this->testResults[$toolKey]['status'] !== 'success') {
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

    public function getUbuntuInstallGuide(): array
    {
        return [
            [
                'title' => 'Install required packages',
                'description' => 'Run as a sudo-enabled user on Ubuntu 22.04 or 24.04.',
                'command' => implode("\n", [
                    'sudo apt update',
                    'sudo apt install -y software-properties-common',
                    'sudo add-apt-repository -y universe',
                    'sudo apt update',
                    'sudo apt install -y ghostscript libreoffice poppler-utils imagemagick img2pdf',
                ]),
            ],
            [
                'title' => 'Verify binaries',
                'description' => 'Each line should print a valid binary path.',
                'command' => implode("\n", [
                    'command -v gs',
                    'command -v libreoffice || command -v soffice',
                    'command -v pdfunite',
                    'command -v pdftoppm',
                    'command -v magick || command -v img2pdf',
                ]),
            ],
            [
                'title' => 'Reload services and workers',
                'description' => 'Restart PHP and web services, then refresh this page.',
                'command' => implode("\n", [
                    'sudo systemctl restart php8.4-fpm || sudo systemctl restart php-fpm',
                    'sudo systemctl restart nginx || sudo systemctl restart apache2',
                    'php artisan queue:restart',
                    'php artisan horizon:terminate',
                ]),
            ],
        ];
    }

    private function binaryExists(string $binary): bool
    {
        return app(BinaryResolver::class)->exists($binary);
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
