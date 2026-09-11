<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/constructor-unicode-ext-invalid.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93; notice: tests/Test262/upstream/LICENSE.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Assert;

foreach (array(
    0 => 'da-u',
    1 => 'da-u-',
    2 => 'da-u--',
    3 => 'da-u-t-latn',
    4 => 'da-u-x-priv',
    5 => 'da-u-ca-gregory-u-ca-buddhist',
) as $tag) {
    $rejected = false;
    try {
        new Locale($tag);
    } catch (RangeError) {
        $rejected = true;
    }

    Assert::assertTrue($rejected, 'Expected RangeError for ' . $tag . '.');
}
