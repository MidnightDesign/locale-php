<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/reject-duplicate-variants.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93; notice: tests/Test262/upstream/LICENSE.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Assert;

foreach (array(
    0 => 'en-emodeng-emodeng',
    1 => 'en-Emodeng-emodeng',
    2 => 'en-emodeng-Emodeng',
    3 => 'en-variant-emodeng-emodeng',
    4 => 'en-variant-Emodeng-emodeng',
    5 => 'en-variant-emodeng-Emodeng',
    6 => 'en-emodeng-variant-emodeng',
    7 => 'en-Emodeng-variant-emodeng',
    8 => 'en-emodeng-variant-Emodeng',
    9 => 'en-emodeng-emodeng-variant',
    10 => 'en-Emodeng-emodeng-variant',
    11 => 'en-emodeng-Emodeng-variant',
) as $tag) {
    $rejected = false;
    try {
        new Locale($tag);
    } catch (RangeError) {
        $rejected = true;
    }

    Assert::assertTrue($rejected, 'Expected RangeError for ' . $tag . '.');
}
