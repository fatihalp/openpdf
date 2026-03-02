<?php

namespace App\Support;

use Symfony\Component\Process\ExecutableFinder;

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
            return is_executable($candidate) ? $candidate : null;
        }

        $finder = new ExecutableFinder;
        $path = $this->environment()['PATH'] ?? '';
        $extraDirs = $path === '' ? [] : explode(':', $path);

        $found = $finder->find($candidate, null, $extraDirs);
        if (is_string($found) && $found !== '') {
            return $found;
        }

        $absolute = $this->searchAbsoluteDirectories($candidate);
        if ($absolute !== null) {
            return $absolute;
        }

        return $this->resolveWithShell($candidate);
    }

    private function searchAbsoluteDirectories(string $binary): ?string
    {
        foreach (['/usr/bin', '/usr/local/bin', '/bin', '/usr/sbin', '/sbin'] as $directory) {
            $path = $directory.'/'.$binary;

            if (is_executable($path)) {
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

        if ($resolved === '' || ! is_executable($resolved)) {
            return null;
        }

        return $resolved;
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
