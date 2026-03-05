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
        $env = array_merge($_SERVER, $_ENV);
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

        return $this->withRuntimeDirectories($env);
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

    private function withRuntimeDirectories(array $env): array
    {
        $runtimeRoot = $this->ensureDirectory($this->runtimeRoot());
        if ($runtimeRoot === null) {
            return $env;
        }

        $home = $this->preferredWritableDirectory(
            $this->envValue($env, 'HOME'),
            (string) getenv('HOME'),
            $runtimeRoot.'/home'
        );
        if ($home !== null) {
            $env['HOME'] = $home;
        }

        $cacheHome = $this->preferredWritableDirectory(
            $this->envValue($env, 'XDG_CACHE_HOME'),
            (string) getenv('XDG_CACHE_HOME'),
            $runtimeRoot.'/.cache'
        );
        if ($cacheHome !== null) {
            $env['XDG_CACHE_HOME'] = $cacheHome;
        }

        $configHome = $this->preferredWritableDirectory(
            $this->envValue($env, 'XDG_CONFIG_HOME'),
            (string) getenv('XDG_CONFIG_HOME'),
            $runtimeRoot.'/.config'
        );
        if ($configHome !== null) {
            $env['XDG_CONFIG_HOME'] = $configHome;
        }

        $dataHome = $this->preferredWritableDirectory(
            $this->envValue($env, 'XDG_DATA_HOME'),
            (string) getenv('XDG_DATA_HOME'),
            $runtimeRoot.'/.local/share'
        );
        if ($dataHome !== null) {
            $env['XDG_DATA_HOME'] = $dataHome;
        }

        $tempDir = $this->preferredWritableDirectory(
            $this->envValue($env, 'TMPDIR'),
            (string) getenv('TMPDIR'),
            $runtimeRoot.'/tmp'
        );
        if ($tempDir !== null) {
            $env['TMPDIR'] = $tempDir;
            $env['TMP'] = $tempDir;
            $env['TEMP'] = $tempDir;
        }

        return $env;
    }

    private function runtimeRoot(): string
    {
        $configured = config('openpdf.runtime_dir');
        if (is_string($configured) && trim($configured) !== '') {
            return rtrim(trim($configured), '/\\');
        }

        if (function_exists('storage_path')) {
            return storage_path('app/private/runtime');
        }

        return rtrim(sys_get_temp_dir(), '/\\').'/openpdf-runtime';
    }

    private function preferredWritableDirectory(?string ...$candidates): ?string
    {
        foreach ($candidates as $candidate) {
            if (! is_string($candidate) || trim($candidate) === '') {
                continue;
            }

            $directory = $this->ensureDirectory($candidate);
            if ($directory !== null) {
                return $directory;
            }
        }

        return null;
    }

    private function ensureDirectory(string $path): ?string
    {
        $normalizedPath = rtrim(trim($path), '/\\');
        if ($normalizedPath === '') {
            return null;
        }

        if (! $this->isPathAllowedByOpenBaseDir($normalizedPath)) {
            return null;
        }

        if (! is_dir($normalizedPath) && ! @mkdir($normalizedPath, 0775, true) && ! is_dir($normalizedPath)) {
            return null;
        }

        if (! is_writable($normalizedPath)) {
            return null;
        }

        return $normalizedPath;
    }

    private function envValue(array $env, string $key): ?string
    {
        $value = $env[$key] ?? null;

        return is_string($value) ? $value : null;
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
