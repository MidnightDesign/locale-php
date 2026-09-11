<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

final class GeneratedOutputPublisher
{
    public function __construct(private readonly string $repositoryRoot)
    {
    }

    /** @param array<string, string> $outputs */
    public function publish(array $outputs): void
    {
        $this->validate($outputs);

        $test262Root = $this->repositoryRoot.'/tests/Test262';
        if (!is_dir($test262Root) && !mkdir($test262Root, 0755, true)) {
            throw new \RuntimeException('Unable to create Test262 output directory.');
        }

        $transaction = '.publish-'.bin2hex(random_bytes(8));
        $stagingRoot = $test262Root.'/'.$transaction.'-staging';
        $backupRoot = $test262Root.'/'.$transaction.'-backup';
        $published = false;
        $generatedBackedUp = false;
        $evidenceBackedUp = false;
        $generatedPublished = false;

        try {
            $this->stage($stagingRoot, $outputs);
            $this->validateStaging($stagingRoot);
            if (!mkdir($backupRoot, 0755)) {
                throw new \RuntimeException('Unable to create Test262 publication backup.');
            }

            try {
                $generatedBackedUp = $this->moveIfPresent(
                    $test262Root.'/Generated',
                    $backupRoot.'/Generated',
                );
                $evidenceBackedUp = $this->moveIfPresent(
                    $test262Root.'/evidence.json',
                    $backupRoot.'/evidence.json',
                );
                $this->moveRequired($stagingRoot.'/tests/Test262/Generated', $test262Root.'/Generated');
                $generatedPublished = true;
                $this->moveRequired($stagingRoot.'/tests/Test262/evidence.json', $test262Root.'/evidence.json');
                $published = true;
            } catch (\Throwable $error) {
                if ($generatedPublished) {
                    self::remove($test262Root.'/Generated');
                }
                if ($generatedBackedUp) {
                    $this->moveRequired($backupRoot.'/Generated', $test262Root.'/Generated');
                }
                if ($evidenceBackedUp) {
                    $this->moveRequired($backupRoot.'/evidence.json', $test262Root.'/evidence.json');
                }
                throw $error;
            }
        } finally {
            self::remove($stagingRoot);
            if ($published) {
                self::remove($backupRoot);
            } elseif (is_dir($backupRoot) && self::isEmptyDirectory($backupRoot)) {
                rmdir($backupRoot);
            }
        }
    }

    /** @param array<string, string> $outputs */
    private function validate(array $outputs): void
    {
        if (!isset($outputs['tests/Test262/evidence.json'])) {
            throw new \InvalidArgumentException('Test262 publication requires conformance evidence.');
        }
        foreach (array_keys($outputs) as $path) {
            if ($path !== 'tests/Test262/evidence.json'
                && !GeneratedScript::isGeneratedPath($path)) {
                throw new \InvalidArgumentException('Test262 publication contains an invalid output path: '.$path.'.');
            }
        }
    }

    /** @param array<string, string> $outputs */
    private function stage(string $stagingRoot, array $outputs): void
    {
        if (!mkdir($stagingRoot.'/tests/Test262/Generated', 0755, true)) {
            throw new \RuntimeException('Unable to create Test262 publication staging directory.');
        }
        foreach ($outputs as $path => $contents) {
            $target = $stagingRoot.'/'.$path;
            if (!is_dir(dirname($target)) && !mkdir(dirname($target), 0755, true)) {
                throw new \RuntimeException('Unable to create staged Test262 output directory.');
            }
            if (file_put_contents($target, $contents) === false) {
                throw new \RuntimeException('Unable to stage Test262 output '.$path.'.');
            }
        }
    }

    private function validateStaging(string $stagingRoot): void
    {
        $evidencePath = $stagingRoot.'/tests/Test262/evidence.json';
        (new GeneratedScriptCatalog($stagingRoot, $evidencePath))->scripts();

        foreach (GeneratedScriptCatalog::generatedPhpFiles($stagingRoot) as $path) {
            $command = escapeshellarg(PHP_BINARY).' -l '.escapeshellarg($stagingRoot.'/'.$path).' 2>&1';
            $output = [];
            exec($command, $output, $exitCode);
            if ($exitCode !== 0) {
                throw new \RuntimeException(sprintf(
                    'Generated Test262 script failed syntax validation: %s: %s',
                    $path,
                    implode("\n", $output),
                ));
            }
        }
    }

    private function moveIfPresent(string $source, string $target): bool
    {
        if (!file_exists($source)) {
            return false;
        }
        if (!@rename($source, $target)) {
            throw new \RuntimeException('Unable to move Test262 output '.$source.'.');
        }

        return true;
    }

    private function moveRequired(string $source, string $target): void
    {
        if (!@rename($source, $target)) {
            throw new \RuntimeException('Unable to publish Test262 output '.$target.'.');
        }
    }

    private static function remove(string $path): void
    {
        if (is_file($path)) {
            unlink($path);

            return;
        }
        if (!is_dir($path)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
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
        rmdir($path);
    }

    private static function isEmptyDirectory(string $path): bool
    {
        return iterator_count(new \FilesystemIterator($path, \FilesystemIterator::SKIP_DOTS)) === 0;
    }
}
