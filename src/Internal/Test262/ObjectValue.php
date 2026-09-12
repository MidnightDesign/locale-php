<?php

declare(strict_types=1);

namespace Midnight\Intl\Internal\Test262;

use Midnight\Intl\Internal\UndefinedValue;

/** @internal Test262 compatibility bridge, not public API. */
interface ObjectValue
{
    /** Return UndefinedValue::Value when the property is absent. */
    public function get(string $name): mixed;
}
