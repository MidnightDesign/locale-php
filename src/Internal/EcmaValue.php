<?php

declare(strict_types=1);

namespace Midnight\Intl\Internal;

use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Internal\Test262\ObjectValue;
use Midnight\Intl\Internal\Test262\SymbolValue;

/** @internal */
final class EcmaValue
{
    public static function toLocaleTag(mixed $value): string
    {
        return match (true) {
            $value instanceof ObjectValue => self::toString(self::toPrimitive($value)),
            $value instanceof \Stringable => (string) $value,
            default => throw new TypeError('The locale tag must be a string or an object.'),
        };
    }

    public static function toString(mixed $value): string
    {
        if ($value instanceof SymbolValue) {
            throw new TypeError('A Symbol value cannot be converted to a string.');
        }

        if ($value === UndefinedValue::Value) {
            return 'undefined';
        }

        if (is_string($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value)) {
            return self::numberToString($value);
        }

        if ($value === null) {
            return 'null';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if ($value instanceof ObjectValue) {
            return self::toString(self::toPrimitive($value));
        }

        if ($value instanceof \Stringable) {
            return (string) $value;
        }

        throw new TypeError('Locale option cannot be converted to a string.');
    }

    public static function toBoolean(mixed $value): bool
    {
        if (is_float($value) && is_nan($value)) {
            return false;
        }

        return match (true) {
            $value === null, $value === UndefinedValue::Value => false,
            is_bool($value) => $value,
            is_int($value), is_float($value) => $value != 0,
            is_string($value) => $value !== '',
            default => true,
        };
    }

    private static function toPrimitive(ObjectValue $value): mixed
    {
        $exoticResult = self::callExoticToPrimitive($value->get('@@toPrimitive'));
        if ($exoticResult->present) {
            return $exoticResult->value;
        }

        foreach (['toString', 'valueOf'] as $name) {
            $ordinaryResult = self::callOrdinaryToPrimitiveMethod($value->get($name));
            if ($ordinaryResult->present) {
                return $ordinaryResult->value;
            }
        }

        throw new TypeError('Locale object cannot be converted to a primitive value.');
    }

    private static function numberToString(int|float $value): string
    {
        $number = (float) $value;
        if (is_nan($number)) {
            return 'NaN';
        }
        if ($number === INF) {
            return 'Infinity';
        }
        if ($number === -INF) {
            return '-Infinity';
        }
        if ($number == 0.0) {
            return '0';
        }

        $previousPrecision = ini_set('serialize_precision', '-1');
        if ($previousPrecision === false) {
            throw new \RuntimeException('Unable to select deterministic number serialization.');
        }
        try {
            $encoded = json_encode($number, JSON_THROW_ON_ERROR);
        } finally {
            ini_set('serialize_precision', $previousPrecision);
        }
        $negative = str_starts_with($encoded, '-');
        $unsigned = $negative ? substr($encoded, 1) : $encoded;
        [$significand, $exponent] = array_pad(explode('e', strtolower($unsigned), 2), 2, '0');
        [$integer, $fraction] = array_pad(explode('.', $significand, 2), 2, '');
        $digits = $integer . $fraction;
        $decimalPosition = strlen($integer) + (int) $exponent;

        $leadingZeroes = strspn($digits, '0');
        $digits = substr($digits, $leadingZeroes);
        $decimalPosition -= $leadingZeroes;
        $digits = rtrim($digits, '0');

        if ($decimalPosition > 0 && $decimalPosition <= 21) {
            $result = strlen($digits) <= $decimalPosition
                ? $digits . str_repeat('0', max(0, $decimalPosition - strlen($digits)))
                : substr($digits, 0, $decimalPosition) . '.' . substr($digits, $decimalPosition);
        } elseif ($decimalPosition <= 0 && $decimalPosition > -6) {
            $result = '0.' . str_repeat('0', -$decimalPosition) . $digits;
        } else {
            $rest = substr($digits, 1);
            $scientificExponent = $decimalPosition - 1;
            $result =
                $digits[0]
                . ($rest === '' ? '' : '.' . $rest)
                . 'e'
                . ($scientificExponent >= 0 ? '+' : '-')
                . abs($scientificExponent);
        }

        return $negative ? '-' . $result : $result;
    }

    private static function callExoticToPrimitive(mixed $method): OptionValue
    {
        if ($method === UndefinedValue::Value) {
            return OptionValue::missing();
        }
        if (!is_callable($method)) {
            throw new TypeError('Symbol.toPrimitive must be callable.');
        }

        return OptionValue::present(self::requirePrimitive(
            $method('string'),
            'Symbol.toPrimitive must return a primitive value.',
        ));
    }

    private static function callOrdinaryToPrimitiveMethod(mixed $method): OptionValue
    {
        if (!is_callable($method)) {
            return OptionValue::missing();
        }

        return self::primitiveOption($method());
    }

    private static function primitiveOption(mixed $value): OptionValue
    {
        return self::isPrimitive($value) ? OptionValue::present($value) : OptionValue::missing();
    }

    private static function requirePrimitive(mixed $value, string $message): mixed
    {
        if (!self::isPrimitive($value)) {
            throw new TypeError($message);
        }

        return $value;
    }

    private static function isPrimitive(mixed $value): bool
    {
        return (
            $value === null
            || is_scalar($value)
            || $value === UndefinedValue::Value
            || $value instanceof SymbolValue
        );
    }
}
