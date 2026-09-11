<?php

declare(strict_types=1);

namespace Midnight\Intl\Internal;

use Midnight\Intl\Internal\Data\CalendarPreferences;
use Midnight\Intl\Internal\Data\HourCyclePreferences;

final class LocalePreferences
{
    /** @return non-empty-list<string> */
    public static function calendars(LocaleIdentifier $locale): array
    {
        $calendar = $locale->keyword('ca');
        if ($calendar !== null) {
            return [$calendar];
        }

        CalendarPreferences::assertIntegrity();
        $calendars = self::lookup($locale, CalendarPreferences::PREFERENCES);

        return $calendars === [] ? ['gregory'] : $calendars;
    }

    /** @return non-empty-list<string> */
    public static function hourCycles(LocaleIdentifier $locale): array
    {
        $hourCycle = $locale->keyword('hc');
        if ($hourCycle !== null) {
            return [$hourCycle];
        }

        HourCyclePreferences::assertIntegrity();
        $hourCycles = self::lookup($locale, HourCyclePreferences::PREFERENCES);

        return $hourCycles === [] ? ['h23'] : $hourCycles;
    }

    /**
     * @param array<array-key, list<string>> $preferences
     * @return list<string>
     */
    private static function lookup(LocaleIdentifier $locale, array $preferences): array
    {
        foreach (RegionPreference::fromLocale($locale)->preferredRegions() as $region) {
            $values = $preferences[$locale->language . '-' . $region] ?? [];
            $values = $values === [] ? $preferences[$region] ?? [] : $values;
            if ($values !== []) {
                return $values;
            }
        }

        return [];
    }
}
