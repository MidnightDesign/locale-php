<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Test262\GeneratedScriptCatalog;
use PHPUnit\Framework\TestCase;

final class Test262ScriptArchitectureTest extends TestCase
{
    public function testEachTranslatedFixtureHasOneSourceRelativePlainScript(): void
    {
        $root = dirname(__DIR__, 2);
        $catalog = new GeneratedScriptCatalog($root, $root . '/tests/Test262/evidence.json');
        foreach ($catalog->entries() as $script) {
            $contents = (string) file_get_contents($root . '/' . $script['path']);
            self::assertStringNotContainsString('extends TestCase', $contents);
            self::assertStringNotContainsString('DataProvider', $contents);
        }
    }

    public function testStaticAnalysisExcludesOnlyGeneratedTest262Scripts(): void
    {
        $config = (string) file_get_contents(dirname(__DIR__, 2) . '/phpstan.neon.dist');

        self::assertStringContainsString('excludePaths:', $config);
        self::assertStringContainsString('- tests/Test262/Generated', $config);
        self::assertStringNotContainsString('- tests/Test262/RunnerTest.php', $config);
    }
}
