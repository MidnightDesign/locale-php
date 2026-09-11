<?php

declare(strict_types=1);

namespace Midnight\Intl\Internal\Test262;

/**
 * Test262 bridge for JavaScript object-to-primitive results PHP cannot express
 * through Stringable, such as an object whose toString method returns false.
 *
 * @internal
 */
final readonly class PrimitiveValue
{
    /** @psalm-api */
    public function __construct(public string|bool|int|float|null $value)
    {
    }
}
