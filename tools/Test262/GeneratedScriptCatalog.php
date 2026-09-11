<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

final class GeneratedScriptCatalog
{
    public function __construct(
        private readonly string $repositoryRoot,
        private readonly string $evidencePath,
    ) {
    }

    /** @return array<string, string> */
    public function scripts(): array
    {
        $scripts = [];
        foreach ($this->entries() as $entry) {
            $scripts[$entry['identity']] = $this->repositoryRoot.'/'.$entry['path'];
        }

        return $scripts;
    }

    /** @return list<array{source: string, path: string, identity: string, variant: string|null}> */
    public function entries(): array
    {
        $expectedPaths = [];
        $identities = [];
        $entries = $this->manifestEntries();
        foreach ($entries as $entry) {
            if (isset($expectedPaths[$entry['path']])) {
                throw new \RuntimeException('Generated script path is duplicated in evidence: '.$entry['path'].'.');
            }
            if (isset($identities[$entry['identity']])) {
                throw new \RuntimeException('Generated script identity is duplicated in evidence: '.$entry['identity'].'.');
            }

            $expectedPaths[$entry['path']] = true;
            $identities[$entry['identity']] = true;
        }

        $actualPaths = array_fill_keys(self::generatedPhpFiles($this->repositoryRoot), true);
        $missing = array_diff_key($expectedPaths, $actualPaths);
        $stale = array_diff_key($actualPaths, $expectedPaths);
        if ($missing !== [] || $stale !== []) {
            throw new \RuntimeException(sprintf(
                'Generated Test262 scripts do not match evidence; missing: %s; stale: %s.',
                implode(', ', array_keys($missing)),
                implode(', ', array_keys($stale)),
            ));
        }

        usort($entries, static fn (array $left, array $right): int => $left['identity'] <=> $right['identity']);

        return $entries;
    }

    /** @return list<string> */
    public static function generatedPhpFiles(string $repositoryRoot): array
    {
        $directory = $repositoryRoot.'/tests/Test262/Generated';
        if (!is_dir($directory)) {
            return [];
        }

        $paths = [];
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(
            $directory,
            \RecursiveDirectoryIterator::SKIP_DOTS,
        ));
        foreach ($files as $file) {
            /** @var \SplFileInfo $file */
            if ($file->isFile() && $file->getExtension() === 'php') {
                $paths[] = str_replace('\\', '/', substr($file->getPathname(), strlen($repositoryRoot) + 1));
            }
        }
        sort($paths);

        return $paths;
    }

    /** @return list<array{source: string, path: string, identity: string, variant: string|null}> */
    private function manifestEntries(): array
    {
        $contents = file_get_contents($this->evidencePath);
        if (!is_string($contents)) {
            throw new \RuntimeException('Unable to read Test262 evidence.');
        }

        /** @var array{fixtures: list<array{path: string, status: string, generatedScripts: list<array{path: string, identity: string, variant: string|null}>}>} $evidence */
        $evidence = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
        $entries = [];
        foreach ($evidence['fixtures'] as $fixture) {
            $primaryCount = count(array_filter(
                $fixture['generatedScripts'],
                static fn (array $entry): bool => $entry['variant'] === null,
            ));
            $expectedPrimaryCount = $fixture['status'] === 'translation_gap' ? 0 : 1;
            if ($primaryCount !== $expectedPrimaryCount) {
                throw new \RuntimeException(sprintf(
                    'Test262 fixture %s requires exactly %d primary generated script(s).',
                    $fixture['path'],
                    $expectedPrimaryCount,
                ));
            }

            foreach ($fixture['generatedScripts'] as $entry) {
                if (!GeneratedScript::isGeneratedPath($entry['path']) || $entry['identity'] === '') {
                    throw new \RuntimeException('Test262 evidence contains an invalid generated script entry.');
                }
                $canonical = $entry['variant'] === null
                    ? GeneratedScript::primary($fixture['path'], '')
                    : GeneratedScript::variant($fixture['path'], $entry['variant'], '');
                if ($entry !== $canonical->evidence()) {
                    throw new \RuntimeException(
                        'Test262 evidence contains a generated script outside its canonical fixture attribution.',
                    );
                }
                $entries[] = ['source' => $fixture['path'], ...$entry];
            }
        }

        return $entries;
    }
}
