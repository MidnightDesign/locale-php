<?php

declare(strict_types=1);

// Copyright 2018 André Bargull; Igalia, S.L. All rights reserved.
// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/getters-missing.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93; notice: tests/Test262/upstream/LICENSE.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class GettersMissingTest extends TestCase
{
    /** @return list<array{string, array<string, string|null>}> */
    public static function cases(): array
    {
        return array_map(
            static fn (array $expected, string $tag): array => [$tag, $expected],
            array(
  'sv' =>
  array(
    'baseName' => 'sv',
    'language' => 'sv',
    'script' => null,
    'region' => null,
  ),
  'sv-Latn' =>
  array(
    'baseName' => 'sv-Latn',
    'language' => 'sv',
    'script' => 'Latn',
    'region' => null,
  ),
  'sv-SE' =>
  array(
    'baseName' => 'sv-SE',
    'language' => 'sv',
    'script' => null,
    'region' => 'SE',
  ),
),
            array_keys(array(
  'sv' =>
  array(
    'baseName' => 'sv',
    'language' => 'sv',
    'script' => null,
    'region' => null,
  ),
  'sv-Latn' =>
  array(
    'baseName' => 'sv-Latn',
    'language' => 'sv',
    'script' => 'Latn',
    'region' => null,
  ),
  'sv-SE' =>
  array(
    'baseName' => 'sv-SE',
    'language' => 'sv',
    'script' => null,
    'region' => 'SE',
  ),
)),
        );
    }

    /** @param array<string, string|null> $expected */
    #[DataProvider('cases')]
    public function testTranslatedGetterAssertions(string $tag, array $expected): void
    {
        $locale = new Locale($tag);

        foreach ($expected as $property => $value) {
            self::assertSame($value, $locale->{$property});
        }
    }
}
