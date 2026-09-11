<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262\Fixtures;

use Midnight\Intl\Exception\RangeError;

final class FixtureCases
{
    /**
     * @param list<array<string, mixed>> $values
     * @return list<array{assertion: int, tag: string, value: array<string, mixed>, expected: string}>
     */
    public static function invalid(array $values): array
    {
        return array_map(static fn(array $value): array => [
            'assertion' => 0,
            'tag' => 'en',
            'value' => $value,
            'expected' => RangeError::class,
        ], $values);
    }

    /** @return array{type: 'string', value: string} */
    public static function string(string $value): array
    {
        return ['type' => 'string', 'value' => $value];
    }

    /**
     * @param list<array{array<string, mixed>, string}> $values
     * @param list<array{int, string, ?string}>         $observations
     * @return list<array{assertion: int, tag: string, value: array<string, mixed>, expected: string|bool, property?: string}>
     */
    public static function options(array $values, array $observations): array
    {
        $cases = [];
        foreach ($values as [$value, $expected]) {
            foreach ($observations as [$assertion, $tag, $property]) {
                $case = [
                    'assertion' => $assertion,
                    'tag' => $tag,
                    'value' => $value,
                    'expected' => $expected,
                ];
                if ($property !== null) {
                    $case['property'] = $property;
                }
                $cases[] = $case;
            }
        }

        return $cases;
    }

    /**
     * @param non-empty-string $key
     * @return list<array{array<string, mixed>, string}>
     */
    public static function forKeyword(string $key): array
    {
        return array_map(static fn(array $row): array => [
            self::string($row[0]),
            sprintf($row[1], $key),
        ], [
            ['abc',             'en-u-%s-abc'],
            ['abcd',            'en-u-%s-abcd'],
            ['abcde',           'en-u-%s-abcde'],
            ['abcdef',          'en-u-%s-abcdef'],
            ['abcdefg',         'en-u-%s-abcdefg'],
            ['abcdefgh',        'en-u-%s-abcdefgh'],
            ['12345678',        'en-u-%s-12345678'],
            ['1234abcd',        'en-u-%s-1234abcd'],
            ['1234abcd-abc123', 'en-u-%s-1234abcd-abc123'],
        ]);
    }

    /**
     * @param list<array{string, string|bool|null}> $values
     * @return list<array{assertion: int, property: string, expected: string|bool|null}>
     */
    public static function state(int $firstAssertion, array $values): array
    {
        $expectations = [];
        foreach ($values as $offset => [$property, $expected]) {
            $expectations[] = [
                'assertion' => $firstAssertion + $offset,
                'property' => $property,
                'expected' => $expected,
            ];
        }

        return $expectations;
    }
}
