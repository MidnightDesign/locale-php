<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Golden;

use Midnight\Intl\Internal\Data\LikelySubtags;
use Midnight\Intl\Internal\Data\LocaleAliases;
use PHPUnit\Framework\TestCase;

final class LikelySubtagsDataTest extends TestCase
{
    public function testPinnedLikelySubtagProjectionIntegrity(): void
    {
        LikelySubtags::assertIntegrity();

        self::assertSame('11299982335beb974c1c63c45265184e759c0f41', LikelySubtags::CLDR_REVISION);
        self::assertCount(7788, LikelySubtags::MAP);
        self::assertSame('en-Latn-US', LikelySubtags::MAP['und']);
        self::assertSame('bg-Cyrl-RO', LikelySubtags::MAP['und-cyrl-ro']);
        self::assertSame('aae-Latn-IT', LikelySubtags::MAP['aae']);
        self::assertFalse(defined(LocaleAliases::class . '::LIKELY_SUBTAG'));
    }
}
