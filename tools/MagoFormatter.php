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

        $command = [
            PHP_BINARY,
            $root . '/vendor/bin/mago',
            '--workspace',
            $root,
            '--colors=never',
            'format',
            '--stdin-input',
            '--stdin-filepath',
            $path,
        ];
        $pipes = [];
        $process = proc_open(
            $command,
            [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ],
            $pipes,
            $root,
        );
        if (
            !is_resource($process)
            || !isset($pipes[0], $pipes[1], $pipes[2])
            || !is_resource($pipes[0])
            || !is_resource($pipes[1])
            || !is_resource($pipes[2])
        ) {
            throw new \RuntimeException('Unable to start Mago.');
        }

        [$input, $output, $errors] = $pipes;
        $remaining = $source;
        while ($remaining !== '') {
            $written = fwrite($input, $remaining);
            if ($written === false || $written === 0) {
                fclose($input);
                fclose($output);
                fclose($errors);
                proc_terminate($process);
                proc_close($process);

                throw new \RuntimeException(sprintf('Unable to send %s to Mago.', $path));
            }
            $remaining = substr($remaining, $written);
        }
        fclose($input);

        $formatted = stream_get_contents($output);
        fclose($output);
        $error = stream_get_contents($errors);
        fclose($errors);

        $exitCode = proc_close($process);
        if ($exitCode !== 0) {
            $message = $error === false ? 'Unable to read Mago error output.' : trim($error);
            throw new \RuntimeException(sprintf('Mago failed to format %s: %s', $path, $message));
        }
        if ($formatted === false) {
            throw new \RuntimeException(sprintf('Unable to read Mago output for %s.', $path));
        }

        return $formatted;
    }
}
