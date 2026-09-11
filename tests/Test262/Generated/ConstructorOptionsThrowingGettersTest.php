<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/constructor-options-throwing-getters.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Tests\Test262\Harness\OptionObservation;
use PHPUnit\Framework\TestCase;

final class ConstructorOptionsThrowingGettersTest extends TestCase
{
    /** @return list<array{string}> */
    public static function options(): array
    {
        return array(
            0 => array(
                0 => 'language',
            ),
            1 => array(
                0 => 'script',
            ),
            2 => array(
                0 => 'region',
            ),
            3 => array(
                0 => 'variants',
            ),
            4 => array(
                0 => 'calendar',
            ),
            5 => array(
                0 => 'collation',
            ),
            6 => array(
                0 => 'firstDayOfWeek',
            ),
            7 => array(
                0 => 'hourCycle',
            ),
            8 => array(
                0 => 'caseFirst',
            ),
            9 => array(
                0 => 'numeric',
            ),
            10 => array(
                0 => 'numberingSystem',
            ),
        );
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('options')]
    public function testTranslatedAssertion(string $option): void
    {
        self::assertTrue(OptionObservation::propagates($option));
    }
}
