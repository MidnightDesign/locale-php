<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools;

final class CldrLocalePreferenceProjector
{
    /**
     * @return array{
     *     available: list<string>,
     *     preferences: array<string, list<string>>
     * }
     */
    public static function calendars(string $calendarBcp47, string $supplementalData): array
    {
        $calendarXml = preg_replace('/<!--.*?-->/s', '', $calendarBcp47) ?? throw new \RuntimeException(
            'Unable to remove CLDR calendar XML comments.',
        );
        preg_match_all('#<key\s+([^>]+)>(.*?)</key>#s', $calendarXml, $keyMatches, PREG_SET_ORDER);
        $calendarKey = null;
        foreach ($keyMatches as $keyMatch) {
            if ((CldrXml::attributes($keyMatch[1])['name'] ?? null) === 'ca') {
                $calendarKey = $keyMatch[2];
                break;
            }
        }
        if ($calendarKey === null) {
            throw new \RuntimeException('The CLDR archive is missing calendar BCP 47 data.');
        }

        $available = [];
        $aliases = [];
        preg_match_all('/<type\s+([^>]+?)\/>/s', $calendarKey, $typeMatches, PREG_SET_ORDER);
        foreach ($typeMatches as $typeMatch) {
            $attributes = CldrXml::attributes($typeMatch[1]);
            $name = strtolower($attributes['name'] ?? '');
            $calendar = strtolower($attributes['preferred'] ?? $name);
            foreach (self::words($attributes['alias'] ?? '') as $alias) {
                $aliases[strtolower($alias)] = $calendar;
            }
            if ($name !== $calendar) {
                $aliases[$name] = $calendar;
            }
            if (($attributes['deprecated'] ?? '') !== 'true' && CldrXml::isUnicodeType($calendar)) {
                $available[] = $calendar;
            }
        }
        $availableSet = array_fill_keys($available, true);

        $preferences = [];
        preg_match_all('/<calendarPreference\s+([^>]+?)\/>/s', $supplementalData, $preferenceMatches, PREG_SET_ORDER);
        foreach ($preferenceMatches as $preferenceMatch) {
            $attributes = CldrXml::attributes($preferenceMatch[1]);
            if (!isset($attributes['territories'], $attributes['ordering'])) {
                throw new \RuntimeException('A CLDR calendar preference is missing territories or ordering.');
            }
            $calendars = [];
            foreach (self::words($attributes['ordering']) as $calendar) {
                $canonical = $aliases[strtolower($calendar)] ?? strtolower($calendar);
                if (isset($availableSet[$canonical]) && !in_array($canonical, $calendars, true)) {
                    $calendars[] = $canonical;
                }
            }
            foreach (self::words($attributes['territories']) as $territory) {
                $preferences[strtoupper($territory)] = $calendars;
            }
        }
        ksort($preferences, SORT_STRING);

        return ['available' => $available, 'preferences' => $preferences];
    }

    /** @return array<string, list<string>> */
    public static function hourCycles(string $supplementalData): array
    {
        $preferences = [];
        preg_match_all('/<hours\s+([^>]+?)\/>/s', $supplementalData, $hoursMatches, PREG_SET_ORDER);
        foreach ($hoursMatches as $hoursMatch) {
            $attributes = CldrXml::attributes($hoursMatch[1]);
            if (!isset($attributes['allowed'], $attributes['regions'])) {
                continue;
            }
            $hourCycles = [];
            foreach ([
                ...self::words($attributes['preferred'] ?? ''),
                ...self::words($attributes['allowed']),
            ] as $pattern) {
                $hourCycle = match ($pattern[0]) {
                    'K' => 'h11',
                    'h' => 'h12',
                    'H' => 'h23',
                    'k' => 'h24',
                    default => throw new \RuntimeException(sprintf('Unsupported CLDR hour pattern "%s".', $pattern)),
                };
                if (!in_array($hourCycle, $hourCycles, true)) {
                    $hourCycles[] = $hourCycle;
                }
            }
            foreach (self::words($attributes['regions']) as $locale) {
                $parts = explode('_', $locale, 2);
                $key = isset($parts[1]) ? strtolower($parts[0]) . '-' . strtoupper($parts[1]) : strtoupper($parts[0]);
                $preferences[$key] = $hourCycles;
            }
        }
        ksort($preferences, SORT_STRING);

        return $preferences;
    }

    /**
     * @return array{
     *     firstDay: array<string, int<1, 7>>,
     *     weekendStart: array<string, int<1, 7>>,
     *     weekendEnd: array<string, int<1, 7>>
     * }
     */
    public static function weekInfo(string $supplementalData): array
    {
        $days = [
            'mon' => 1,
            'tue' => 2,
            'wed' => 3,
            'thu' => 4,
            'fri' => 5,
            'sat' => 6,
            'sun' => 7,
        ];
        $projection = ['firstDay' => [], 'weekendStart' => [], 'weekendEnd' => []];
        foreach (array_keys($projection) as $field) {
            preg_match_all(sprintf('/<%s\s+([^>]+?)\/>/s', $field), $supplementalData, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $attributes = CldrXml::attributes($match[1]);
                if (isset($attributes['alt'])) {
                    continue;
                }
                $day = $days[$attributes['day'] ?? ''] ?? null;
                if ($day === null || !isset($attributes['territories'])) {
                    throw new \RuntimeException(sprintf(
                        'A CLDR %s row is missing a valid day or territories.',
                        $field,
                    ));
                }
                foreach (self::words($attributes['territories']) as $territory) {
                    $projection[$field][strtoupper($territory)] = $day;
                }
            }
            ksort($projection[$field], SORT_STRING);
        }

        return $projection;
    }

    /** @return list<string> */
    private static function words(string $value): array
    {
        return preg_split('/\s+/', $value, flags: PREG_SPLIT_NO_EMPTY) ?: [];
    }
}
