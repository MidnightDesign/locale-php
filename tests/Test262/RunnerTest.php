<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Test262;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final class RunnerTest extends TestCase
{
    /** @return iterable<string, array{string}> */
    public static function scripts(): iterable
    {
        $generatedRoot = __DIR__.'/Generated';
        $expected = self::evidenceFixturePaths();
        /** @var array<string, string> $discovered */
        $discovered = [];
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(
            $generatedRoot,
            RecursiveDirectoryIterator::SKIP_DOTS,
        ));

        foreach ($files as $file) {
            /** @var \SplFileInfo $file */
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $contents = file_get_contents($file->getPathname());
            if (!is_string($contents)
                || preg_match('/^\/\/ Source: (?<path>test\/.*\.js) at Test262 /m', $contents, $match) !== 1) {
                throw new \RuntimeException('Generated script has no Test262 source identity: '.$file->getPathname());
            }
            $fixturePath = $match['path'];
            if (isset($discovered[$fixturePath])) {
                throw new \RuntimeException('Multiple generated scripts claim Test262 fixture '.$fixturePath.'.');
            }
            $discovered[$fixturePath] = $file->getPathname();
        }

        ksort($expected);
        ksort($discovered);
        if (array_keys($expected) !== array_keys($discovered)) {
            throw new \RuntimeException(sprintf(
                'Generated Test262 scripts do not match evidence; missing: %s; stale: %s.',
                implode(', ', array_diff(array_keys($expected), array_keys($discovered))),
                implode(', ', array_diff(array_keys($discovered), array_keys($expected))),
            ));
        }

        foreach ($discovered as $fixturePath => $scriptPath) {
            yield $fixturePath => [$scriptPath];
        }
    }

    #[DataProvider('scripts')]
    public function testScript(string $scriptPath): void
    {
        require $scriptPath;
    }

    /** @return array<string, true> */
    private static function evidenceFixturePaths(): array
    {
        $contents = file_get_contents(__DIR__.'/evidence.json');
        if (!is_string($contents)) {
            throw new \RuntimeException('Unable to read Test262 evidence.');
        }

        /** @var array{fixtures: list<array{path: string, status: string}>} $evidence */
        $evidence = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
        /** @var array<string, true> $paths */
        $paths = [];
        foreach ($evidence['fixtures'] as $fixture) {
            if (in_array($fixture['status'], ['passing', 'partially_translated'], true)) {
                $paths[$fixture['path']] = true;
            }
        }

        return $paths;
    }
}
