<?php

declare(strict_types=1);

namespace Midnight\Intl\Internal;

use Midnight\Intl\Internal\Data\WeekInfoData;

final class WeekInformation
{
    /** @return array{firstDay: int<1, 7>, weekend: non-empty-list<int<1, 7>>} */
    public static function forLocale(LocaleIdentifier $locale): array
    {
        WeekInfoData::assertIntegrity();
        $preference = RegionPreference::fromLocale($locale);
        $region = $preference->region;
        if ($preference->regionOverride !== null && self::hasData($preference->regionOverride)) {
            $region = $preference->regionOverride;
        }

        $firstDay =
            self::firstDayOverride($locale) ?? WeekInfoData::FIRST_DAY[$region] ?? WeekInfoData::FIRST_DAY['001'];
        $weekendStart = WeekInfoData::WEEKEND_START[$region] ?? WeekInfoData::WEEKEND_START['001'];
        $weekendEnd = WeekInfoData::WEEKEND_END[$region] ?? WeekInfoData::WEEKEND_END['001'];
        $weekend = [];
        for ($day = $weekendStart;; $day = ($day % 7) + 1) {
            $weekend[] = $day;
            if ($day === $weekendEnd) {
                break;
            }
        }
        sort($weekend, SORT_NUMERIC);
        /** @var non-empty-list<int<1, 7>> $weekend */

        return ['firstDay' => $firstDay, 'weekend' => $weekend];
    }

    private static function hasData(string $region): bool
    {
        return (
            isset(WeekInfoData::FIRST_DAY[$region])
            || isset(WeekInfoData::WEEKEND_START[$region])
            || isset(WeekInfoData::WEEKEND_END[$region])
        );
    }

    /** @return int<1, 7>|null */
    private static function firstDayOverride(LocaleIdentifier $locale): ?int
    {
        return match ($locale->keyword('fw')) {
            'mon' => 1,
            'tue' => 2,
            'wed' => 3,
            'thu' => 4,
            'fri' => 5,
            'sat' => 6,
            'sun' => 7,
            default => null,
        };
    }
}
