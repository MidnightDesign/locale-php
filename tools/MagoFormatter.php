<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools;

final class MagoFormatter
{
    public static function format(string $root, string $path, string $source): string
    {
        return self::formatAll($root, [$path => $source])[$path];
    }

    /**
     * @param array<string, string> $sources
     *
     * @return array<string, string>
     */
    public static function formatAll(string $root, array $sources): array
    {
        if ($root === '') {
            throw new \InvalidArgumentException('The workspace root cannot be empty.');
        }
        if ($sources === []) {
            return [];
        }

        $temporaryPaths = [];

        try {
            foreach ($sources as $path => $source) {
                $temporaryPath = tempnam(sys_get_temp_dir(), 'mago-');
                if ($temporaryPath === false) {
                    throw new \RuntimeException(sprintf('Unable to create a temporary file for %s.', $path));
                }

                $phpPath = $temporaryPath . '.php';
                if (!rename($temporaryPath, $phpPath)) {
                    unlink($temporaryPath);
                    throw new \RuntimeException(sprintf('Unable to stage %s for Mago.', $path));
                }
                if (file_put_contents($phpPath, $source) === false) {
                    unlink($phpPath);
                    throw new \RuntimeException(sprintf('Unable to stage %s for Mago.', $path));
                }
                $temporaryPaths[$path] = $phpPath;
            }

            $command = [
                self::binary($root),
                '--workspace',
                $root,
                '--colors=never',
                'format',
                ...array_values($temporaryPaths),
            ];
            $pipes = [];
            $process = proc_open(
                $command,
                [
                    0 => ['file', PHP_OS_FAMILY === 'Windows' ? 'NUL' : '/dev/null', 'r'],
                    1 => ['pipe', 'w'],
                    2 => ['pipe', 'w'],
                ],
                $pipes,
                $root,
            );
            if (
                !is_resource($process)
                || !isset($pipes[1], $pipes[2])
                || !is_resource($pipes[1])
                || !is_resource($pipes[2])
            ) {
                throw new \RuntimeException('Unable to start Mago.');
            }

            stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            $error = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            $exitCode = proc_close($process);
            if ($exitCode !== 0) {
                $message = $error === false ? 'Unable to read Mago error output.' : trim($error);
                throw new \RuntimeException(sprintf('Mago failed to format generated PHP: %s', $message));
            }

            $formatted = [];
            foreach ($temporaryPaths as $path => $phpPath) {
                $contents = file_get_contents($phpPath);
                if ($contents === false) {
                    throw new \RuntimeException(sprintf('Unable to read Mago output for %s.', $path));
                }
                $formatted[$path] = $contents;
            }

            return $formatted;
        } finally {
            foreach ($temporaryPaths as $phpPath) {
                if (is_file($phpPath)) {
                    unlink($phpPath);
                }
            }
        }
    }

    private static function binary(string $root): string
    {
        $candidates = glob($root . '/vendor/carthage-software/mago/composer/bin/*/mago-*/mago*', GLOB_NOSORT);
        if ($candidates === false) {
            throw new \RuntimeException('Unable to locate the installed Mago binary.');
        }

        $binaries = array_values(array_filter(
            $candidates,
            static fn(string $candidate): bool => (
                is_file($candidate) && in_array(basename($candidate), ['mago', 'mago.exe'], true)
            ),
        ));
        if (count($binaries) !== 1) {
            throw new \RuntimeException('Expected exactly one installed Mago binary.');
        }

        return $binaries[0];
    }
}
