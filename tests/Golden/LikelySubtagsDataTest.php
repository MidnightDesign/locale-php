<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Golden;

use Midnight\Intl\Internal\Data\LocaleAliases;
use PHPUnit\Framework\TestCase;

final class LikelySubtagsDataTest extends TestCase
{
    public function testPinnedLikelySubtagProjectionIntegrity(): void
    {
        LocaleAliases::assertIntegrity();

        self::assertSame('11299982335beb974c1c63c45265184e759c0f41', LocaleAliases::CLDR_REVISION);
        self::assertCount(7788, LocaleAliases::LIKELY_SUBTAG);
        self::assertSame('en-Latn-US', LocaleAliases::LIKELY_SUBTAG['und']);
        self::assertSame('bg-Cyrl-RO', LocaleAliases::LIKELY_SUBTAG['und-cyrl-ro']);
        self::assertSame('aae-Latn-IT', LocaleAliases::LIKELY_SUBTAG['aae']);
    }
}
