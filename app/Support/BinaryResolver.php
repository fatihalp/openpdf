<?php

namespace App\Support;

use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;
use Throwable;

class BinaryResolver
{
    public function exists(string $binary): bool
    {
        return $this->resolve($binary) !== null;
    }

    public function resolve(string $binary): ?string
    {
        $configured = $this->configuredCandidate($binary);
        if ($configured !== null) {
            $resolved = $this->resolveCandidate($configured);
            if ($resolved !== null) {
                return $resolved;
            }
        }

        return $this->resolveCandidate($binary);
    }

    public function environment(): array
    {
        $env = $_ENV;
        $configuredPath = (string) ($env['PATH'] ?? '');
        $currentPath = (string) getenv('PATH');

        $segments = [
            '/opt/homebrew/bin',
            '/usr/local/bin',
            '/usr/bin',
            '/bin',
            '/usr/sbin',
            '/sbin',
            $configuredPath,
            $currentPath,
        ];

        $env['PATH'] = implode(':', array_values(array_unique(array_filter($segments))));

        return $env;
    }

    private function configuredCandidate(string $binary): ?string
    {
        $candidate = config("openpdf.binaries.$binary");

        if (! is_string($candidate) || trim($candidate) === '') {
            return null;
        }

        return trim($candidate);
    }

    private function resolveCandidate(string $candidate): ?string
    {
        if (str_contains($candidate, '/')) {
            return $candidate;
        }

        $finder = new ExecutableFinder;
        $path = $this->environment()['PATH'] ?? '';
        $extraDirs = $path === '' ? [] : explode(':', $path);

        $found = $finder->find($candidate, null, $extraDirs);
        if (is_string($found) && $found !== '') {
            return $found;
        }

        $fromShell = $this->resolveWithShell($candidate);
        if ($fromShell !== null) {
            return $fromShell;
        }

        $fromProcess = $this->resolveWithProcess($candidate);
        if ($fromProcess !== null) {
            return $fromProcess;
        }

        return $this->searchAbsoluteDirectories($candidate);
    }

    private function searchAbsoluteDirectories(string $binary): ?string
    {
        foreach (['/usr/bin', '/usr/local/bin', '/bin', '/usr/sbin', '/sbin'] as $directory) {
            $path = $directory.'/'.$binary;

            if ($this->isPathExecutable($path)) {
                return $path;
            }
        }

        return null;
    }

    private function resolveWithShell(string $binary): ?string
    {
        if (! $this->shellCommandAvailable()) {
            return null;
        }

        $escapedPath = escapeshellarg((string) ($this->environment()['PATH'] ?? ''));
        $escapedBinary = escapeshellarg($binary);
        $output = shell_exec("PATH={$escapedPath} command -v {$escapedBinary} 2>/dev/null");
        $resolved = trim((string) $output);

        if ($resolved === '') {
            return null;
        }

        return strtok($resolved, "\n") ?: null;
    }

    private function resolveWithProcess(string $binary): ?string
    {
        try {
            $process = new Process(['/bin/sh', '-lc', 'command -v '.escapeshellarg($binary)]);
            $process->setEnv($this->environment());
            $process->setTimeout(5);
            $process->run();

            if (! $process->isSuccessful()) {
                return null;
            }

            $resolved = trim($process->getOutput());
            if ($resolved === '') {
                return null;
            }

            return strtok($resolved, "\n") ?: null;
        } catch (Throwable) {
            return null;
        }
    }

    private function isPathExecutable(string $path): bool
    {
        if (! $this->isPathAllowedByOpenBaseDir($path)) {
            return false;
        }

        return @is_executable($path);
    }

    private function isPathAllowedByOpenBaseDir(string $path): bool
    {
        $openBaseDir = trim((string) ini_get('open_basedir'));
        if ($openBaseDir === '') {
            return true;
        }

        $normalized = rtrim(str_replace('\\', '/', $path), '/');
        $allowed = array_filter(array_map(
            static fn (string $value): string => rtrim(str_replace('\\', '/', trim($value)), '/'),
            explode(PATH_SEPARATOR, $openBaseDir)
        ));

        foreach ($allowed as $base) {
            if ($base === '') {
                continue;
            }

            if ($normalized === $base || str_starts_with($normalized.'/', $base.'/')) {
                return true;
            }
        }

        return false;
    }

    private function shellCommandAvailable(): bool
    {
        if (! function_exists('shell_exec')) {
            return false;
        }

        $disabled = array_map(
            static fn (string $value): string => trim($value),
            explode(',', (string) ini_get('disable_functions'))
        );

        return ! in_array('shell_exec', $disabled, true);
    }
}
