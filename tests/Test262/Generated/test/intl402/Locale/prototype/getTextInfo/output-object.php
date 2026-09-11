<?php

declare(strict_types=1);

// Copyright 2021 Igalia, S.L. All rights reserved.
// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: test/intl402/Locale/prototype/getTextInfo/output-object.js at Test262 419d3e0a2273ba01a3bfcbec423f2801425b8e93; notice: tests/Test262/upstream/LICENSE.
// Spec baseline: ECMA-402 b1c961988b9a07894b1dc3dc2b5626ea48387d61; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Assert;

$result = (new Locale('en'))->getTextInfo();

Assert::assertIsArray($result);
