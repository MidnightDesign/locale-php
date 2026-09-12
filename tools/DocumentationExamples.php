<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools;

use Midnight\Intl\Tools\Ci\PackageSmoke;

final class DocumentationExamples
{
    private const PUBLIC_DOCUMENTS = [
        'README.md',
        'docs/getting-started.md',
        'docs/api-reference.md',
        'docs/spec-layer.md',
        'docs/migration.md',
    ];

    public static function checkPublic(string $repositoryRoot): void
    {
        $temporaryDirectory = PackageSmoke::temporaryDirectory('intl-locale-documentation');
        $checkedExamples = 0;

        try {
            foreach (self::PUBLIC_DOCUMENTS as $documentIndex => $relativePath) {
                $checkedExamples += self::checkDocument(
                    $repositoryRoot,
                    $relativePath,
                    $temporaryDirectory,
                    $documentIndex,
                );
            }
        } finally {
            PackageSmoke::removeDirectory($temporaryDirectory);
        }

        if ($checkedExamples === 0) {
            throw new \RuntimeException('No runnable PHP documentation examples were found.');
        }
    }

    private static function checkDocument(
        string $repositoryRoot,
        string $relativePath,
        string $temporaryDirectory,
        int $documentIndex,
    ): int {
        $path = $repositoryRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        $markdown = file_get_contents($path);
        if ($markdown === false) {
            throw new \RuntimeException(sprintf('Unable to read documentation file %s.', $relativePath));
        }

        preg_match_all('/```php[^\r\n]*\R(.*?)\R```/s', $markdown, $matches);
        foreach ($matches[1] as $exampleIndex => $body) {
            self::checkExample(
                $repositoryRoot,
                $relativePath,
                $temporaryDirectory,
                $documentIndex,
                $exampleIndex,
                $body,
            );
        }

        return count($matches[1]);
    }

    private static function checkExample(
        string $repositoryRoot,
        string $relativePath,
        string $temporaryDirectory,
        int $documentIndex,
        int $exampleIndex,
        string $body,
    ): void {
        $script = sprintf(
            "<?php\n\ndeclare(strict_types=1);\n\nrequire %s;\n\n%s\n",
            var_export($repositoryRoot . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php', true),
            $body,
        );
        $scriptPath =
            $temporaryDirectory . DIRECTORY_SEPARATOR . sprintf('example-%d-%d.php', $documentIndex, $exampleIndex);
        if (file_put_contents($scriptPath, $script) === false) {
            throw new \RuntimeException(sprintf('Unable to prepare documentation examples from %s.', $relativePath));
        }

        $process = proc_open(
            [PHP_BINARY, '-d', 'zend.assertions=1', '-d', 'assert.exception=1', $scriptPath],
            [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes,
            $repositoryRoot,
        );
        if (!is_resource($process)) {
            throw new \RuntimeException(sprintf('Unable to run documentation examples from %s.', $relativePath));
        }

        $standardOutput = stream_get_contents($pipes[1]);
        $standardError = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        if ($exitCode !== 0) {
            throw new \RuntimeException(sprintf(
                "Documentation examples from %s failed with exit code %d.\n%s%s",
                $relativePath,
                $exitCode,
                $standardOutput,
                $standardError,
            ));
        }
    }
}
