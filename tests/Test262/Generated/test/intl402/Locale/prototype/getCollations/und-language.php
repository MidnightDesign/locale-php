<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/prototype/getCollations/und-language.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93; notice: tests/Test262/upstream/LICENSE.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Assert;

foreach (['und', 'und-US', 'und-Latn', 'und-Latn-US', 'und-u-ca-gregory', 'und-US-u-nu-latn'] as $tag) {
    $locale = new Locale($tag);
    Assert::assertSame('und', $locale->language);
    Assert::assertSame(['emoji', 'eor'], $locale->getCollations());
}
foreach (['qfz', 'qga-DE', 'qgb-ES', 'qgc-KR', 'qtz-CN'] as $tag) {
    Assert::assertSame(['emoji', 'eor'], (new Locale($tag))->getCollations());
}
