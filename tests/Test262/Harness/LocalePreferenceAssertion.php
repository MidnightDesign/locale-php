<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Test262\Harness;

use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Assert;

final class LocalePreferenceAssertion
{
    /**
     * @return list<array{status: 'passing'|'failing'|'inapplicable'|'partially_translated', failure?: string}>
     */
    public static function evaluate(string $method, string $fixture): array
    {
        return match ($fixture) {
            'branding.js' => self::branding($method),
            'name.js' => [self::result((new \ReflectionMethod(Locale::class, $method))->getName() === $method)],
            'prop-desc.js' => [
                self::result(method_exists(Locale::class, $method)),
                ['status' => 'inapplicable'],
            ],
            'output-array.js' => self::outputArray($method),
            'output-array-values.js' => self::hourCycleValues(),
            'language-priority.js' => self::languagePriority(),
            'likely-subtags-region.js' => self::likelySubtagsRegion($method),
            'region-override.js' => self::regionOverride($method),
            'region-priority.js' => self::regionPriority($method),
            'subdivision-region.js' => self::subdivisionRegion($method),
            default => throw new \InvalidArgumentException(sprintf(
                'Unsupported locale preference fixture "%s".',
                $fixture,
            )),
        };
    }

    public static function assertFixture(string $method, string $fixture): void
    {
        foreach (self::evaluate($method, $fixture) as $result) {
            Assert::assertNotSame(
                'failing',
                $result['status'],
                $result['failure'] ?? 'Locale preference assertion failed.',
            );
        }
    }

    /** @return list<array{status: 'passing'|'failing', failure?: string}> */
    private static function branding(string $method): array
    {
        $results = [self::result(method_exists(Locale::class, $method))];
        $uninitialized = (new \ReflectionClass(Locale::class))->newInstanceWithoutConstructor();
        foreach ([null, null, true, '', 'Symbol()', 1, new \stdClass(), Locale::class, $uninitialized] as $receiver) {
            try {
                if (!$receiver instanceof Locale) {
                    throw new TypeError('Locale receiver is not initialized.');
                }
                $receiver->{$method}();
                $results[] = self::result(false, 'Expected the receiver to fail its Locale brand check.');
            } catch (TypeError) {
                $results[] = self::result(true);
            }
        }

        return $results;
    }

    /** @return list<array{status: 'passing'|'failing', failure?: string}> */
    private static function outputArray(string $method): array
    {
        $output = (new Locale('en'))->{$method}();
        $results = [self::result(is_array($output))];
        if ($method === 'getCalendars') {
            $results[] = self::result($output !== [], 'Expected at least one calendar.');
        }

        return $results;
    }

    /** @return list<array{status: 'passing'|'failing', failure?: string}> */
    private static function hourCycleValues(): array
    {
        $output = (new Locale('en'))->getHourCycles();

        return [self::result(
            // @phpstan-ignore notIdentical.alwaysTrue (faithful upstream runtime assertion)
            $output !== [] && array_diff($output, ['h11', 'h12', 'h23', 'h24']) === [],
            'Expected a non-empty list containing only valid hour cycles.',
        )];
    }

    /** @return list<array{status: 'passing'|'failing', failure?: string}> */
    private static function languagePriority(): array
    {
        $found = false;
        foreach ([['CA', 'fr', 'en'], ['SY', 'ku', 'ar'], ['001', 'en', 'de'], ['001', 'ar', 'fr']] as [
            $region,
            $first,
            $second,
        ]) {
            $control = (new Locale('und-' . $region))->getHourCycles();
            if (
                (new Locale($first . '-' . $region))->getHourCycles() !== $control
                || (new Locale($second . '-' . $region))->getHourCycles() !== $control
            ) {
                $found = true;
                break;
            }
        }

        return [self::result($found, 'No language-region hour-cycle preference was observable.')];
    }

    /** @return list<array{status: 'passing'|'failing', failure?: string}> */
    private static function likelySubtagsRegion(string $method): array
    {
        $candidates = $method === 'getCalendars'
            ? ['th-TH', 'fa-IR', 'ja-JP', 'sa-IN', 'ps-AF']
            : ['am-ET', 'bn-BD', 'el-GR', 'fil-PH', 'hi-IN', 'ko-KR', 'ms-MY', 'ur-PK'];
        $selected = null;
        foreach ($candidates as $candidate) {
            $locale = new Locale($candidate);
            $expected = $locale->{$method}();
            if ((new Locale($locale->language . '-001'))->{$method}() !== $expected) {
                $selected = [$locale->language, $expected];
                break;
            }
        }

        return [
            self::result($selected !== null, 'No suitable likely-region fixture candidate was found.'),
            self::result(
                $selected !== null && (new Locale($selected[0]))->{$method}() === $selected[1],
                'The likely region did not determine the locale preference.',
            ),
        ];
    }

    /** @return list<array{status: 'passing'|'failing', failure?: string}> */
    private static function regionOverride(string $method): array
    {
        $candidates = $method === 'getCalendars'
            ? [
                ['en-US-u-rg-thzzzz', 'en-TH'],
                ['en-US-u-rg-jpzzzz', 'en-JP'],
                ['en-US-u-rg-inzzzz', 'en-IN'],
                ['en-US-u-rg-irzzzz', 'en-IR'],
            ]
            : [
                ['en-US-u-rg-gbzzzz', 'en-GB'],
                ['en-US-u-rg-dezzzz', 'en-DE'],
                ['en-US-u-rg-frzzzz', 'en-FR'],
            ];
        $selected = null;
        foreach ($candidates as [$overrideTag, $regionTag]) {
            $expected = (new Locale($regionTag))->{$method}();
            $baseName = (new Locale($overrideTag))->baseName;
            if ((new Locale($baseName))->{$method}() !== $expected) {
                $selected = [$overrideTag, $expected];
                break;
            }
        }

        return [
            self::result($selected !== null, 'No suitable region-override fixture candidate was found.'),
            self::result(
                $selected !== null && (new Locale($selected[0]))->{$method}() === $selected[1],
                'The region override did not determine the locale preference.',
            ),
        ];
    }

    /** @return list<array{status: 'passing'|'failing', failure?: string}> */
    private static function regionPriority(string $method): array
    {
        $levels = $method === 'getCalendars'
            ? [
                ['fa-JP-u-sd-inka-rg-thzzzz', 'fa-TH'],
                ['fa-JP-u-sd-inka',           'fa-JP'],
                ['fa-u-sd-inka',              'fa-IN'],
                ['fa',                        'fa-IR'],
                ['eo',                        'eo-001'],
            ]
            : [
                ['en-US-u-sd-gbeng-rg-gbzzzz', 'en-GB'],
                ['en-US-u-sd-gbeng',           'en-US'],
                ['en-u-sd-gbeng',              'en-GB'],
                ['en',                         'en-US'],
                ['eo',                         'eo-001'],
            ];
        $distinct = true;
        $matching = true;
        for ($index = 0; $index < count($levels); ++$index) {
            [$tag, $regionTag] = $levels[$index];
            $expected = (new Locale($regionTag))->{$method}();
            $matching = $matching && (new Locale($tag))->{$method}() === $expected;
            if (isset($levels[$index + 1])) {
                $next = (new Locale($levels[$index + 1][1]))->{$method}();
                $distinct = $distinct && $expected !== $next;
            }
        }

        return [
            self::result($distinct, 'Adjacent region-priority levels were not observable.'),
            self::result($matching, 'A locale preference used the wrong region-priority level.'),
        ];
    }

    /** @return list<array{status: 'passing'|'failing', failure?: string}> */
    private static function subdivisionRegion(string $method): array
    {
        $candidates = $method === 'getCalendars'
            ? [
                ['en-u-sd-th10',  'en-TH'],
                ['en-u-sd-jp13',  'en-JP'],
                ['en-u-sd-inka',  'en-IN'],
                ['en-u-sd-irthr', 'en-IR'],
            ]
            : [
                ['en-u-sd-gbeng', 'en-GB'],
                ['en-u-sd-fridf', 'en-FR'],
                ['en-u-sd-debe',  'en-DE'],
            ];
        $selected = null;
        foreach ($candidates as [$subdivisionTag, $regionTag]) {
            $expected = (new Locale($regionTag))->{$method}();
            $fallback = (new Locale((new Locale($subdivisionTag))->baseName))->maximize();
            if ($fallback->{$method}() !== $expected) {
                $selected = [$subdivisionTag, $expected];
                break;
            }
        }

        return [
            self::result($selected !== null, 'No suitable subdivision-region fixture candidate was found.'),
            self::result(
                $selected !== null && (new Locale($selected[0]))->{$method}() === $selected[1],
                'The subdivision region did not determine the locale preference.',
            ),
        ];
    }

    /** @return array{status: 'passing'|'failing', failure?: string} */
    private static function result(bool $passing, string $failure = 'Assertion failed.'): array
    {
        return $passing ? ['status' => 'passing'] : ['status' => 'failing', 'failure' => $failure];
    }
}
