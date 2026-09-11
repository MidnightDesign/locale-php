<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final class Test262ScriptArchitectureTest extends TestCase
{
    public function testEachTranslatedFixtureHasOneSourceRelativePlainScript(): void
    {
        $root = dirname(__DIR__, 2);
        $generatedRoot = $root.'/tests/Test262/Generated';
        /** @var array{fixtures: list<array{path: string, status: string}>} $evidence */
        $evidence = json_decode(
            (string) file_get_contents($root.'/tests/Test262/evidence.json'),
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        $expected = [];
        foreach ($evidence['fixtures'] as $fixture) {
            if (!in_array($fixture['status'], ['passing', 'failing', 'partially_translated'], true)) {
                continue;
            }

            $relativePath = preg_replace('/\.js$/D', '.php', $fixture['path']);
            self::assertIsString($relativePath);
            $expected[] = $relativePath;
        }
        sort($expected);

        $actual = [];
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(
            $generatedRoot,
            RecursiveDirectoryIterator::SKIP_DOTS,
        ));
        foreach ($files as $file) {
            /** @var \SplFileInfo $file */
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $relativePath = str_replace('\\', '/', substr($file->getPathname(), strlen($generatedRoot) + 1));
            $actual[] = $relativePath;

            $contents = (string) file_get_contents($file->getPathname());
            self::assertStringContainsString(
                '// Source: '.preg_replace('/\.php$/D', '.js', $relativePath).' at Test262 ',
                $contents,
            );
            self::assertStringNotContainsString('extends TestCase', $contents);
            self::assertStringNotContainsString('DataProvider', $contents);
        }
        sort($actual);

        self::assertSame($expected, $actual);
    }

    public function testStaticAnalysisExcludesOnlyGeneratedTest262Scripts(): void
    {
        $config = (string) file_get_contents(dirname(__DIR__, 2).'/phpstan.neon.dist');

        self::assertStringContainsString('excludePaths:', $config);
        self::assertStringContainsString('- tests/Test262/Generated', $config);
        self::assertStringNotContainsString('- tests/Test262/RunnerTest.php', $config);
    }
}
