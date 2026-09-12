<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Internal\Data\CalendarPreferences;
use Midnight\Intl\Internal\Data\HourCyclePreferences;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CalendarPreferences::class)]
#[CoversClass(HourCyclePreferences::class)]
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
}
