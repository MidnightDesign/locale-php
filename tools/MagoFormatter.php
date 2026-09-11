<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools;

final class MagoFormatter
{
    public static function format(string $root, string $path, string $source): string
    {
        if ($root === '') {
            throw new \InvalidArgumentException('The workspace root cannot be empty.');
        }

        $temporaryPath = tempnam(sys_get_temp_dir(), 'mago-');
        if ($temporaryPath === false) {
            throw new \RuntimeException(sprintf('Unable to create a temporary file for %s.', $path));
        }

        $phpPath = $temporaryPath . '.php';
        if (!rename($temporaryPath, $phpPath)) {
            unlink($temporaryPath);
            throw new \RuntimeException(sprintf('Unable to stage %s for Mago.', $path));
        }

        try {
            if (file_put_contents($phpPath, $source) === false) {
                throw new \RuntimeException(sprintf('Unable to stage %s for Mago.', $path));
            }

            $command = [
                PHP_BINARY,
                $root . '/vendor/bin/mago',
                '--workspace',
                $root,
                '--colors=never',
                'format',
                $phpPath,
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
                throw new \RuntimeException(sprintf('Mago failed to format %s: %s', $path, $message));
            }

            $formatted = file_get_contents($phpPath);
            if ($formatted === false) {
                throw new \RuntimeException(sprintf('Unable to read Mago output for %s.', $path));
            }

            return $formatted;
        } finally {
            if (is_file($phpPath)) {
                unlink($phpPath);
            }
        }
    }
}
