<?php

declare(strict_types=1);

// Copyright 2018 André Bargull; Igalia, S.L. All rights reserved.
// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/getters-missing.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93; notice: tests/Test262/upstream/LICENSE.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Assert;

foreach (array(
  'sv' =>
  array(
    'baseName' => 'sv',
    'language' => 'sv',
    'script' => null,
    'region' => null,
    'variants' => null,
  ),
  'sv-Latn' =>
  array(
    'baseName' => 'sv-Latn',
    'language' => 'sv',
    'script' => 'Latn',
    'region' => null,
    'variants' => null,
  ),
  'sv-SE' =>
  array(
    'baseName' => 'sv-SE',
    'language' => 'sv',
    'script' => null,
    'region' => 'SE',
    'variants' => null,
  ),
  'de-1901' =>
  array(
    'baseName' => 'de-1901',
    'language' => 'de',
    'script' => null,
    'region' => null,
    'variants' => '1901',
  ),
) as $tag => $expected) {
    $locale = new Locale($tag);

    foreach ($expected as $property => $value) {
        Assert::assertSame($value, $locale->{$property});
    }
}
