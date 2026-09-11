<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Ci;

final class PackageSmoke
{
    public static function installFromDirectory(string $packageDirectory): void
    {
        $workDirectory = self::temporaryDirectory('intl-locale-smoke');
        $consumerDirectory = $workDirectory . DIRECTORY_SEPARATOR . 'consumer';
        if (!mkdir($consumerDirectory, 0700, true)) {
            throw new \RuntimeException(sprintf('Unable to create temporary consumer at %s.', $consumerDirectory));
        }

        try {
            $composer = [
                'name' => 'midnight/intl-locale-smoke',
                'repositories' => [[
                    'type' => 'path',
                    'url' => $packageDirectory,
                    'options' => ['symlink' => false],
                ]],
                'require' => ['midnight/intl-locale' => '@dev'],
                'minimum-stability' => 'dev',
                'prefer-stable' => true,
            ];
            file_put_contents(
                $consumerDirectory . DIRECTORY_SEPARATOR . 'composer.json',
                json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n",
            );

            self::run([
                ...self::composerCommand(),
                'install',
                '--no-interaction',
                '--no-plugins',
                '--no-scripts',
            ], $consumerDirectory);
            self::run([
                PHP_BINARY,
                '-r',
                'require "vendor/autoload.php"; $locale = new Midnight\\Intl\\Locale("EN-latn-at"); if ($locale->toString() !== "en-Latn-AT" || $locale->getTimeZones() !== ["Europe/Vienna"] || (new Midnight\\Intl\\Locale("en"))->getTimeZones() !== null) { exit(1); } $locale->getNumberingSystems();',
            ], $consumerDirectory);
        } finally {
            self::removeDirectory($workDirectory);
        }
    }

    /** @return non-empty-list<string> */
    public static function composerCommand(): array
    {
        $composerBinary = getenv('COMPOSER_BINARY');
        if (is_string($composerBinary) && $composerBinary !== '') {
            return [PHP_BINARY, $composerBinary];
        }

        return ['composer'];
    }

    /** @param list<string> $command */
    private static function run(array $command, string $workingDirectory): void
    {
        $process = proc_open($command, [STDIN, STDOUT, STDERR], $pipes, $workingDirectory);
        if (!is_resource($process)) {
            throw new \RuntimeException(sprintf('Unable to start %s.', $command[0]));
        }
        $exitCode = proc_close($process);
        if ($exitCode !== 0) {
            throw new \RuntimeException(sprintf('%s exited with code %d.', $command[0], $exitCode));
        }
    }

    public static function temporaryDirectory(string $prefix): string
    {
        $directory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $prefix . '-' . bin2hex(random_bytes(8));
        if (!mkdir($directory, 0700)) {
            throw new \RuntimeException(sprintf('Unable to create temporary directory %s.', $directory));
        }

        return $directory;
    }

    public static function removeDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST,
        );
        foreach ($iterator as $entry) {
            /** @var \SplFileInfo $entry */
            if ($entry->isDir()) {
                rmdir($entry->getPathname());
            } else {
                unlink($entry->getPathname());
            }
        }
        rmdir($directory);
    }
}
