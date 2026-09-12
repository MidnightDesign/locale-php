<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Test262\Harness;

use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Assert;

final class WeekInfoAssertion
{
    /**
     * @return array{
     *     results: list<array{status: 'passing'|'failing'|'inapplicable', failure?: string}>,
     *     executionCount: int
     * }
     */
    public static function evaluate(string $fixture): array
    {
        return match ($fixture) {
            'firstDay-by-id.js' => ['results' => self::firstDayById(), 'executionCount' => 7],
            'firstDay-by-option.js' => ['results' => self::firstDayByOption(), 'executionCount' => 46],
            'likely-subtags-region.js' => ['results' => self::likelySubtagsRegion(), 'executionCount' => 3],
            'output-object-keys.js' => ['results' => self::outputObjectKeys(), 'executionCount' => 6],
            'output-object.js' => [
                // @phpstan-ignore function.alreadyNarrowedType (faithful upstream runtime assertion)
                'results' => [self::result(is_array((new Locale('en'))->getWeekInfo()))],
                'executionCount' => 1,
            ],
            'region-override.js' => ['results' => self::regionOverride(), 'executionCount' => 3],
            'region-priority.js' => ['results' => self::regionPriority(), 'executionCount' => 14],
            'subdivision-region.js' => ['results' => self::subdivisionRegion(), 'executionCount' => 3],
            default => throw new \InvalidArgumentException(sprintf('Unsupported week-info fixture "%s".', $fixture)),
        };
    }

    public static function assertFixture(string $fixture): void
    {
        foreach (self::evaluate($fixture)['results'] as $result) {
            Assert::assertNotSame('failing', $result['status'], $result['failure'] ?? 'Week-info assertion failed.');
        }
    }

    /** @return list<array{status: 'passing'|'failing', failure?: string}> */
    private static function firstDayById(): array
    {
        $passing = true;
        foreach ([
            'mon' => 1,
            'tue' => 2,
            'wed' => 3,
            'thu' => 4,
            'fri' => 5,
            'sat' => 6,
            'sun' => 7,
        ] as $day => $expected) {
            $actual = (new Locale('en-u-fw-' . $day))->getWeekInfo()['firstDay'];
            $passing = $actual === $expected && $passing;
        }

        return [self::result($passing, 'A first-day Unicode keyword did not determine firstDay.')];
    }

    /** @return list<array{status: 'passing'|'failing', failure?: string}> */
    private static function firstDayByOption(): array
    {
        $withoutKeyword = true;
        $withKeyword = true;
        foreach ([
            ['mon', 1],
            ['tue', 2],
            ['wed', 3],
            ['thu', 4],
            ['fri', 5],
            ['sat', 6],
            ['sun', 7],
            ['1', 1],
            ['2', 2],
            ['3', 3],
            ['4', 4],
            ['5', 5],
            ['6', 6],
            ['7', 7],
            ['0', 7],
            [1, 1],
            [2, 2],
            [3, 3],
            [4, 4],
            [5, 5],
            [6, 6],
            [7, 7],
            [0, 7],
        ] as [$option, $expected]) {
            $options = ['firstDayOfWeek' => $option];
            $withoutKeywordActual = (new Locale('en', $options))->getWeekInfo()['firstDay'];
            $withKeywordActual = (new Locale('en-u-fw-WED', $options))->getWeekInfo()['firstDay'];
            $withoutKeyword = $withoutKeywordActual === $expected && $withoutKeyword;
            $withKeyword = $withKeywordActual === $expected && $withKeyword;
        }

        return [
            self::result($withoutKeyword, 'A first-day option did not determine firstDay.'),
            self::result($withKeyword, 'A first-day option did not override the Unicode keyword.'),
        ];
    }

    /** @return list<array{status: 'passing'|'failing', failure?: string}> */
    private static function likelySubtagsRegion(): array
    {
        $selected = null;
        foreach (['th-TH', 'fa-IR', 'ja-JP', 'sa-IN', 'ps-AF'] as $regionTag) {
            $expected = (new Locale($regionTag))->getWeekInfo();
            $language = explode('-', $regionTag, 2)[0];
            if (!self::equal((new Locale($language . '-001'))->getWeekInfo(), $expected)) {
                $selected = [$language, $expected];
                break;
            }
        }
        if ($selected === null) {
            return [
                self::result(false, 'No suitable likely-region fixture candidate was found.'),
                self::result(false, 'The likely region did not determine firstDay.'),
                self::result(false, 'The likely region did not determine weekend.'),
            ];
        }
        $actual = (new Locale($selected[0]))->getWeekInfo();

        return [
            self::result(true),
            self::result(
                $actual['firstDay'] === $selected[1]['firstDay'],
                'The likely region did not determine firstDay.',
            ),
            self::result(
                $actual['weekend'] === $selected[1]['weekend'],
                'The likely region did not determine weekend.',
            ),
        ];
    }

    /** @return list<array{status: 'passing'|'failing'|'inapplicable', failure?: string}> */
    private static function outputObjectKeys(): array
    {
        $info = (new Locale('en'))->getWeekInfo();
        $weekend = $info['weekend'];

        return [
            // @phpstan-ignore identical.alwaysTrue (faithful upstream runtime assertion)
            self::result(array_keys($info) === ['firstDay', 'weekend'], 'Week info returned the wrong keys.'),
            ['status' => 'inapplicable'],
            self::result(self::isDay($info['firstDay']), 'firstDay was outside the ISO weekday range.'),
            ['status' => 'inapplicable'],
            self::result(
                array_reduce($weekend, static fn(bool $valid, int $day): bool => $valid && self::isDay($day), true),
                'weekend contained an invalid ISO weekday.',
            ),
            self::result($weekend === self::sorted($weekend), 'weekend was not ascending.'),
        ];
    }

    /** @return list<array{status: 'passing'|'failing', failure?: string}> */
    private static function regionOverride(): array
    {
        $selected = null;
        foreach ([
            ['en-US-u-rg-dezzzz', 'en-DE'],
            ['en-US-u-rg-inzzzz', 'en-IN'],
            ['en-US-u-rg-irzzzz', 'en-IR'],
            ['en-US-u-rg-afzzzz', 'en-AF'],
        ] as [$overrideTag, $regionTag]) {
            $expected = (new Locale($regionTag))->getWeekInfo();
            $baseName = (new Locale($overrideTag))->baseName;
            if (!self::equal((new Locale($baseName))->getWeekInfo(), $expected)) {
                $selected = [$overrideTag, $expected];
                break;
            }
        }
        $actual = $selected === null ? null : (new Locale($selected[0]))->getWeekInfo();

        return self::selectionResults($selected, $actual, 'region override');
    }

    /** @return list<array{status: 'passing'|'failing', failure?: string}> */
    private static function regionPriority(): array
    {
        $levels = [
            ['fa-JP-u-sd-inka-rg-afzzzz', 'fa-AF'],
            ['fa-JP-u-sd-inka',           'fa-JP'],
            ['fa-u-sd-inka',              'fa-IN'],
            ['fa',                        'fa-IR'],
            ['eo',                        'eo-001'],
        ];
        $distinct = true;
        $firstDays = true;
        $weekends = true;
        foreach ($levels as $index => [$tag, $regionTag]) {
            $expected = (new Locale($regionTag))->getWeekInfo();
            $actual = (new Locale($tag))->getWeekInfo();
            $firstDays = $actual['firstDay'] === $expected['firstDay'] && $firstDays;
            $weekends = $actual['weekend'] === $expected['weekend'] && $weekends;
            if (isset($levels[$index + 1])) {
                $next = (new Locale($levels[$index + 1][1]))->getWeekInfo();
                $distinct = !self::equal($expected, $next) && $distinct;
            }
        }

        return [
            self::result($distinct, 'Adjacent region-priority levels were not observable.'),
            self::result($firstDays, 'A locale used the wrong region-priority level for firstDay.'),
            self::result($weekends, 'A locale used the wrong region-priority level for weekend.'),
        ];
    }

    /** @return list<array{status: 'passing'|'failing', failure?: string}> */
    private static function subdivisionRegion(): array
    {
        $selected = null;
        foreach ([
            ['en-u-sd-inka',  'en-IN'],
            ['en-u-sd-irthr', 'en-IR'],
            ['en-u-sd-afgh',  'en-AF'],
        ] as [$subdivisionTag, $regionTag]) {
            $expected = (new Locale($regionTag))->getWeekInfo();
            $fallback = (new Locale((new Locale($subdivisionTag))->baseName))->maximize()->getWeekInfo();
            if (!self::equal($fallback, $expected)) {
                $selected = [$subdivisionTag, $expected];
                break;
            }
        }
        $actual = $selected === null ? null : (new Locale($selected[0]))->getWeekInfo();

        return self::selectionResults($selected, $actual, 'subdivision region');
    }

    /**
     * @param array{0: string, 1: array{firstDay: int, weekend: list<int>}}|null $selected
     * @param array{firstDay: int, weekend: list<int>}|null                  $actual
     * @return list<array{status: 'passing'|'failing', failure?: string}>
     */
    private static function selectionResults(?array $selected, ?array $actual, string $subject): array
    {
        if ($selected === null || $actual === null) {
            return [
                self::result(false, sprintf('No suitable %s fixture candidate was found.', $subject)),
                self::result(false, sprintf('The %s did not determine firstDay.', $subject)),
                self::result(false, sprintf('The %s did not determine weekend.', $subject)),
            ];
        }

        return [
            self::result(true),
            self::result($actual['firstDay'] === $selected[1]['firstDay'], sprintf(
                'The %s did not determine firstDay.',
                $subject,
            )),
            self::result($actual['weekend'] === $selected[1]['weekend'], sprintf(
                'The %s did not determine weekend.',
                $subject,
            )),
        ];
    }

    /**
     * @param array{firstDay: int, weekend: list<int>} $first
     * @param array{firstDay: int, weekend: list<int>} $second
     */
    private static function equal(array $first, array $second): bool
    {
        return $first['firstDay'] === $second['firstDay'] && $first['weekend'] === $second['weekend'];
    }

    private static function isDay(int $day): bool
    {
        return $day >= 1 && $day <= 7;
    }

    /**
     * @param list<int> $values
     * @return list<int>
     */
    private static function sorted(array $values): array
    {
        sort($values, SORT_NUMERIC);

        return $values;
    }

    /** @return array{status: 'passing'|'failing', failure?: string} */
    private static function result(bool $passing, string $failure = 'Assertion failed.'): array
    {
        return $passing ? ['status' => 'passing'] : ['status' => 'failing', 'failure' => $failure];
    }
}
