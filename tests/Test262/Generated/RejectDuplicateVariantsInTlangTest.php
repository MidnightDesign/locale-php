<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/reject-duplicate-variants-in-tlang.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93; notice: tests/Test262/upstream/LICENSE.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class RejectDuplicateVariantsInTlangTest extends TestCase
{
    /** @return iterable<string, array{string}> */
    public static function invalidTags(): iterable
    {
        foreach (array(
  0 => 'de-t-en-emodeng-emodeng',
  1 => 'de-t-en-Emodeng-emodeng',
  2 => 'de-t-en-emodeng-Emodeng',
  3 => 'de-t-en-variant-emodeng-emodeng',
  4 => 'de-t-en-variant-Emodeng-emodeng',
  5 => 'de-t-en-variant-emodeng-Emodeng',
  6 => 'de-t-en-emodeng-variant-emodeng',
  7 => 'de-t-en-Emodeng-variant-emodeng',
  8 => 'de-t-en-emodeng-variant-Emodeng',
  9 => 'de-t-en-emodeng-emodeng-variant',
  10 => 'de-t-en-Emodeng-emodeng-variant',
  11 => 'de-t-en-emodeng-Emodeng-variant',
) as $tag) {
            yield $tag => [$tag];
        }
    }

    #[DataProvider('invalidTags')]
    public function testTranslatedRangeErrorAssertion(string $tag): void
    {
        $this->expectException(RangeError::class);

        new Locale($tag);
    }
}
