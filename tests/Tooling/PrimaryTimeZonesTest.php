<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Internal\Data\PrimaryTimeZones;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PrimaryTimeZones::class)]
final class PrimaryTimeZonesTest extends TestCase
{
    public function testThePinnedProjectionIsCompleteAndInternallyConsistent(): void
    {
        PrimaryTimeZones::assertIntegrity();

        self::assertSame('2026c', PrimaryTimeZones::TZDB_VERSION);
        self::assertSame(341, count(PrimaryTimeZones::ZONES));
        self::assertSame(257, count(PrimaryTimeZones::LINKS));
        self::assertSame(598, count(PrimaryTimeZones::PRIMARY_IDENTIFIERS));
        self::assertSame(247, count(PrimaryTimeZones::REGIONS));
        self::assertSame(418, array_sum(array_map('count', PrimaryTimeZones::REGIONS)));

        self::assertSame('UTC', PrimaryTimeZones::PRIMARY_IDENTIFIERS['Etc/UTC']);
        self::assertSame('UTC', PrimaryTimeZones::PRIMARY_IDENTIFIERS['UTC']);
        self::assertSame('Europe/Bratislava', PrimaryTimeZones::PRIMARY_IDENTIFIERS['Europe/Bratislava']);
        self::assertSame('Arctic/Longyearbyen', PrimaryTimeZones::PRIMARY_IDENTIFIERS['Atlantic/Jan_Mayen']);
        self::assertSame('Europe/Kyiv', PrimaryTimeZones::PRIMARY_IDENTIFIERS['Europe/Kiev']);
        self::assertSame(['Atlantic/Reykjavik'], PrimaryTimeZones::REGIONS['IS']);
        self::assertSame(['Europe/Bratislava'], PrimaryTimeZones::REGIONS['SK']);

        foreach (PrimaryTimeZones::REGIONS as $region => $identifiers) {
            self::assertMatchesRegularExpression('/^[A-Z]{2}$/D', (string) $region);
            self::assertSame(array_values(array_unique($identifiers)), $identifiers);
            $sorted = $identifiers;
            sort($sorted, SORT_STRING);
            self::assertSame($sorted, $identifiers);
            foreach ($identifiers as $identifier) {
                self::assertSame($identifier, PrimaryTimeZones::PRIMARY_IDENTIFIERS[$identifier]);
            }
        }
    }
}
