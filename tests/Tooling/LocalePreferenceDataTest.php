<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Internal\Data\CalendarPreferences;
use Midnight\Intl\Internal\Data\HourCyclePreferences;
use Midnight\Intl\Internal\Data\WeekInfoData;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CalendarPreferences::class)]
#[CoversClass(HourCyclePreferences::class)]
#[CoversClass(WeekInfoData::class)]
final class LocalePreferenceDataTest extends TestCase
{
    public function testPinnedCalendarPreferenceProjectionIsCompleteAndInternallyConsistent(): void
    {
        CalendarPreferences::assertIntegrity();

        self::assertSame('11299982335beb974c1c63c45265184e759c0f41', CalendarPreferences::CLDR_REVISION);
        self::assertCount(18, CalendarPreferences::AVAILABLE);
        self::assertCount(52, CalendarPreferences::PREFERENCES);
        self::assertSame(['gregory'], CalendarPreferences::PREFERENCES['001']);
        self::assertSame(['buddhist', 'gregory'], CalendarPreferences::PREFERENCES['TH']);
        self::assertSame(['gregory', 'japanese'], CalendarPreferences::PREFERENCES['JP']);
        foreach (CalendarPreferences::PREFERENCES as $calendars) {
            self::assertSame([], array_values(array_diff($calendars, CalendarPreferences::AVAILABLE)));
        }

        $source = file_get_contents(dirname(__DIR__, 2) . '/resources/data/calendar-preferences.json');
        self::assertNotFalse($source);
        self::assertSame(CalendarPreferences::SOURCE_SHA256, hash('sha256', $source));
    }

    public function testPinnedHourCycleProjectionPreservesRegionalAndLanguagePreferences(): void
    {
        HourCyclePreferences::assertIntegrity();

        self::assertSame('11299982335beb974c1c63c45265184e759c0f41', HourCyclePreferences::CLDR_REVISION);
        self::assertCount(276, HourCyclePreferences::PREFERENCES);
        self::assertSame(['h23', 'h12'], HourCyclePreferences::PREFERENCES['001']);
        self::assertSame(['h12', 'h23'], HourCyclePreferences::PREFERENCES['CA']);
        self::assertSame(['h23', 'h12'], HourCyclePreferences::PREFERENCES['fr-CA']);
        self::assertSame(['h23', 'h12'], HourCyclePreferences::PREFERENCES['CD']);
        self::assertSame(['h23', 'h12'], HourCyclePreferences::PREFERENCES['IR']);
        self::assertSame(['h23', 'h11', 'h12'], HourCyclePreferences::PREFERENCES['JP']);

        $source = file_get_contents(dirname(__DIR__, 2) . '/resources/data/hour-cycle-preferences.json');
        self::assertNotFalse($source);
        self::assertSame(HourCyclePreferences::SOURCE_SHA256, hash('sha256', $source));
    }

    public function testPinnedWeekInfoProjectionIsCompleteAndInternallyConsistent(): void
    {
        WeekInfoData::assertIntegrity();

        self::assertSame('11299982335beb974c1c63c45265184e759c0f41', WeekInfoData::CLDR_REVISION);
        self::assertCount(150, WeekInfoData::FIRST_DAY);
        self::assertCount(19, WeekInfoData::WEEKEND_START);
        self::assertCount(17, WeekInfoData::WEEKEND_END);
        self::assertSame(1, WeekInfoData::FIRST_DAY['001']);
        self::assertSame(1, WeekInfoData::FIRST_DAY['AE']);
        self::assertSame(4, WeekInfoData::WEEKEND_START['AF']);
        self::assertSame(5, WeekInfoData::WEEKEND_START['IR']);
        self::assertSame(5, WeekInfoData::WEEKEND_END['IR']);
        self::assertSame(7, WeekInfoData::WEEKEND_START['UG']);

        $root = dirname(__DIR__, 2);
        $source = file_get_contents($root . '/resources/data/week-info.json');
        self::assertNotFalse($source);
        self::assertSame(WeekInfoData::SOURCE_SHA256, hash('sha256', $source));

        /** @var array{projections: array{weekInfo: array{source: string}}} $manifest */
        $manifest = json_decode(
            (string) file_get_contents($root . '/resources/data/manifest.json'),
            true,
            flags: JSON_THROW_ON_ERROR,
        );
        self::assertSame('resources/data/week-info.json', $manifest['projections']['weekInfo']['source']);
    }
}
