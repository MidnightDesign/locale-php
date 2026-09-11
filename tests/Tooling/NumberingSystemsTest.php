<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Internal\Data\NumberingSystems;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(NumberingSystems::class)]
final class NumberingSystemsTest extends TestCase
{
    public function testThePinnedProjectionIsCompleteAndInternallyConsistent(): void
    {
        NumberingSystems::assertIntegrity();

        self::assertSame('11299982335beb974c1c63c45265184e759c0f41', NumberingSystems::CLDR_REVISION);
        $source = file_get_contents(dirname(__DIR__, 2) . '/resources/data/numbering-systems.json');
        self::assertNotFalse($source);
        self::assertSame(NumberingSystems::SOURCE_SHA256, hash('sha256', $source));
        /** @var array{sourceEntries: array<string, string>, defaults: array<string, string>, aliases: array<string, string>, inheritance: array<string, string>} $projection */
        $projection = json_decode($source, true, flags: JSON_THROW_ON_ERROR);

        self::assertSame(1125, count($projection['sourceEntries']));
        self::assertSame(1122, count($projection['defaults']));
        self::assertSame(57, count($projection['aliases']));
        self::assertNotEmpty($projection['inheritance']);
        self::assertSame($projection['defaults'], NumberingSystems::DEFAULTS);
        self::assertSame($projection['aliases'], NumberingSystems::ALIASES);
        self::assertSame('latn', $projection['defaults']['root']);
        self::assertSame('arabext', $projection['defaults']['fa']);
        self::assertSame('beng', $projection['defaults']['bn']);
        self::assertSame('mymr', $projection['defaults']['my']);
        self::assertSame('sr-Cyrl-RS', $projection['aliases']['sr-RS']);
        self::assertSame('zh-Hans-CN', $projection['aliases']['zh-CN']);
        self::assertNotContains('native', $projection['defaults']);
        self::assertNotContains('traditio', $projection['defaults']);
        self::assertNotContains('finance', $projection['defaults']);

        foreach ($projection['aliases'] as $target) {
            self::assertArrayHasKey($target, $projection['defaults']);
        }
    }

    public function testTheRuntimeProjectionShipsOnlyDefaultsAndAliases(): void
    {
        $reflection = new \ReflectionClass(NumberingSystems::class);

        self::assertTrue($reflection->hasConstant('DEFAULTS'));
        self::assertTrue($reflection->hasConstant('ALIASES'));
        self::assertFalse($reflection->hasConstant('SOURCE_ENTRIES'));
    }
}
