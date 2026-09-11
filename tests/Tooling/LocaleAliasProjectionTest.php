<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Internal\Data\LocaleAliases;
use PHPUnit\Framework\TestCase;

final class LocaleAliasProjectionTest extends TestCase
{
    public function testGeneratedUnicodeKeywordAliasesMatchTheirPinnedProjection(): void
    {
        $source = file_get_contents(dirname(__DIR__, 2) . '/resources/data/locale-aliases.json');
        self::assertNotFalse($source);
        /** @var array{type: array<string, array<string, string>>} $projection */
        $projection = json_decode($source, true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('islamic-civil', $projection['type']['ca']['islamicc']);
        self::assertSame($projection['type'], LocaleAliases::TYPE);
    }
}
