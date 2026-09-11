<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/prototype/getCollations/collation-keyword.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93; notice: tests/Test262/upstream/LICENSE.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Tools\Test262\CollationsFixtureAssertions;
use PHPUnit\Framework\Assert;

$evaluation = CollationsFixtureAssertions::evaluate('collation-keyword.js');
Assert::assertNotNull($evaluation);
foreach ($evaluation['executions'] as $execution) {
    Assert::assertFalse($execution['failed']);
}
