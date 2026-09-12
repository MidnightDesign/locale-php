<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Test262\Harness;

use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Internal\Test262\SymbolValue;
use Midnight\Intl\Internal\UndefinedValue;
use Midnight\Intl\Spec\Locale;

final class ConstructorTagType
{
    public static function isConstructible(): bool
    {
        return (new \ReflectionClass(Locale::class))->isInstantiable();
    }

    /** @return list<bool> */
    public static function rejects(string $kind): array
    {
        $values = match ($kind) {
            'boolean' => [true, false],
            'null' => [null],
            'number' => [0, 1, INF, NAN],
            'symbol' => [new SymbolValue()],
            'undefined' => [UndefinedValue::Value, UndefinedValue::Value],
            default => throw new \InvalidArgumentException('Unknown invalid tag kind.'),
        };

        return array_map(static function (mixed $value): bool {
            try {
                new Locale($value);
            } catch (TypeError) {
                return true;
            }
            return false;
        }, $values);
    }
}
