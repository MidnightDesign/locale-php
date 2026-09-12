<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Spec\Locale;

final class CollationsFixtureAssertions
{
    private const KEYWORD_CASES = [
        ['en', 'phonebk'],
        ['de', 'phonebk'],
        ['zh', 'stroke'],
        ['und', 'pinyin'],
        ['und', 'emoji'],
    ];

    private const REPRESENTATIVE_TAGS = ['ar', 'de', 'en', 'ja', 'ko', 'sv', 'tr', 'zh'];

    private const UNDEFINED_LANGUAGE_TAGS = [
        'und',
        'und-US',
        'und-Latn',
        'und-Latn-US',
        'und-u-ca-gregory',
        'und-US-u-nu-latn',
    ];

    private const PRIVATE_USE_TAGS = ['qfz', 'qga-DE', 'qgb-ES', 'qgc-KR', 'qtz-CN'];

    /**
     * @return array{
     *     failuresByAssertion: list<bool>,
     *     executions: list<array{assertion: int, failed: bool}>
     * }|null
     */
    public static function evaluate(string $name): ?array
    {
        $executions = match ($name) {
            'collation-keyword.js' => self::keywordExecutions(),
            'output-array-sorted.js' => self::sortedOutputExecutions(),
            'output-array-values.js' => self::outputValueExecutions(),
            'output-array.js' => self::outputArrayExecutions(),
            'und-language.js' => self::unmatchedLocaleExecutions(),
            default => null,
        };
        if ($executions === null) {
            return null;
        }

        $assertionCount = match ($name) {
            'collation-keyword.js' => 2,
            'output-array-values.js', 'und-language.js' => 3,
            default => 1,
        };
        $failures = array_fill(0, $assertionCount, false);
        foreach ($executions as $execution) {
            $failures[$execution['assertion']] = $failures[$execution['assertion']] || $execution['failed'];
        }

        return ['failuresByAssertion' => array_values($failures), 'executions' => $executions];
    }

    /** @return list<array{assertion: int, failed: bool}> */
    private static function keywordExecutions(): array
    {
        $executions = [];
        foreach (self::KEYWORD_CASES as [$base, $collation]) {
            $executions[] = [
                'assertion' => 0,
                'failed' => (new Locale($base . '-u-co-' . $collation))->getCollations() !== [$collation],
            ];
            $executions[] = [
                'assertion' => 1,
                'failed' => (new Locale($base, ['collation' => $collation]))->getCollations() !== [$collation],
            ];
        }

        return $executions;
    }

    /** @return list<array{assertion: int, failed: bool}> */
    private static function sortedOutputExecutions(): array
    {
        $executions = [];
        foreach (self::REPRESENTATIVE_TAGS as $tag) {
            $collations = (new Locale($tag))->getCollations();
            $sorted = $collations;
            sort($sorted, SORT_STRING);
            $executions[] = ['assertion' => 0, 'failed' => $collations !== $sorted];
        }

        return $executions;
    }

    /** @return list<array{assertion: int, failed: bool}> */
    private static function outputValueExecutions(): array
    {
        $executions = [];
        foreach (self::REPRESENTATIVE_TAGS as $tag) {
            $collations = (new Locale($tag))->getCollations();
            $executions[] = ['assertion' => 0, 'failed' => $collations === []];
            foreach ($collations as $collation) {
                $executions[] = ['assertion' => 1, 'failed' => $collation === 'standard'];
                $executions[] = ['assertion' => 2, 'failed' => $collation === 'search'];
            }
        }

        return $executions;
    }

    /** @return list<array{assertion: int, failed: bool}> */
    private static function outputArrayExecutions(): array
    {
        return array_map(static function (string $tag): array {
            $output = (new Locale($tag))->getCollations();

            return ['assertion' => 0, 'failed' => !self::isArray($output)];
        }, self::REPRESENTATIVE_TAGS);
    }

    private static function isArray(mixed $value): bool
    {
        return is_array($value);
    }

    /** @return list<array{assertion: int, failed: bool}> */
    private static function unmatchedLocaleExecutions(): array
    {
        $root = ['emoji', 'eor'];
        $executions = [];
        foreach (self::UNDEFINED_LANGUAGE_TAGS as $tag) {
            $locale = new Locale($tag);
            $executions[] = ['assertion' => 0, 'failed' => $locale->language !== 'und'];
            $executions[] = ['assertion' => 1, 'failed' => $locale->getCollations() !== $root];
        }
        foreach (self::PRIVATE_USE_TAGS as $tag) {
            $executions[] = [
                'assertion' => 2,
                'failed' => (new Locale($tag))->getCollations() !== $root,
            ];
        }

        return $executions;
    }
}
