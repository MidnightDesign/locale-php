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
        $source = file_get_contents(dirname(__DIR__, 2) . '/resources/data/primary-time-zones.json');
        self::assertNotFalse($source);
        self::assertSame(PrimaryTimeZones::SOURCE_SHA256, hash('sha256', $source));
        /** @var array{
         *     zones: list<string>,
         *     links: array<string, string>,
         *     primaryIdentifiers: array<string, string>,
         *     regions: array<string, list<string>>
         * } $projection
         */
        $projection = json_decode($source, true, flags: JSON_THROW_ON_ERROR);

        self::assertSame(341, count($projection['zones']));
        self::assertSame(257, count($projection['links']));
        self::assertSame(598, count($projection['primaryIdentifiers']));
        self::assertSame(247, count($projection['regions']));
        self::assertSame(418, array_sum(array_map('count', $projection['regions'])));

        self::assertSame('UTC', $projection['primaryIdentifiers']['Etc/UTC']);
        self::assertSame('UTC', $projection['primaryIdentifiers']['UTC']);
        self::assertSame('Europe/Bratislava', $projection['primaryIdentifiers']['Europe/Bratislava']);
        self::assertSame('Arctic/Longyearbyen', $projection['primaryIdentifiers']['Atlantic/Jan_Mayen']);
        self::assertSame('Europe/Kyiv', $projection['primaryIdentifiers']['Europe/Kiev']);
        self::assertSame(['Atlantic/Reykjavik'], $projection['regions']['IS']);
        self::assertSame(['Europe/Bratislava'], $projection['regions']['SK']);

        foreach ($projection['regions'] as $region => $identifiers) {
            self::assertMatchesRegularExpression('/^[A-Z]{2}$/D', (string) $region);
            self::assertSame(array_values(array_unique($identifiers)), $identifiers);
            $sorted = $identifiers;
            sort($sorted, SORT_STRING);
            self::assertSame($sorted, $identifiers);
            foreach ($identifiers as $identifier) {
                self::assertSame($identifier, $projection['primaryIdentifiers'][$identifier]);
            }
        }
    }

    public function testTheRuntimeProjectionShipsOnlyTheRegionLookup(): void
    {
        $reflection = new \ReflectionClass(PrimaryTimeZones::class);

        self::assertFalse($reflection->hasConstant('ZONES'));
        self::assertFalse($reflection->hasConstant('LINKS'));
        self::assertFalse($reflection->hasConstant('PRIMARY_IDENTIFIERS'));
        self::assertTrue($reflection->hasConstant('REGIONS'));

        $source = file(dirname(__DIR__, 2) . '/src/Internal/Data/PrimaryTimeZones.php');
        self::assertNotFalse($source);
        self::assertLessThan(1000, count($source));
    }
}
