<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Test262\Harness;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Internal\Test262\PrimitiveValue;
use Midnight\Intl\Internal\UndefinedValue;
use Midnight\Intl\Spec\Locale;

final class ConstructorOptionAssertion
{
    /**
     * @param array<string, mixed> $optionValue
     *
     * @return array{status: 'passing', actual: mixed}|array{status: 'failing', actual?: mixed, failure: string}
     */
    public static function evaluate(
        string $tag,
        string $optionName,
        array $optionValue,
        string $representation,
        string|bool $expected,
        ?string $property = null,
    ): array {
        try {
            $value = self::optionValue($optionValue);
            $options = match ($representation) {
                'associative_array' => [$optionName => $value],
                'plain_object' => (object) [$optionName => $value],
                default => throw new \InvalidArgumentException(sprintf(
                    'Unsupported PHP representation: "%s".',
                    $representation,
                )),
            };
            $locale = new Locale($tag, $options);
            $actual = $property === null ? $locale->toString() : $locale->{$property};
        } catch (\Throwable $error) {
            return [
                'status' => 'failing',
                'failure' => sprintf('%s: %s', $error::class, $error->getMessage()),
            ];
        }

        if ($actual !== $expected) {
            return [
                'status' => 'failing',
                'actual' => $actual,
                'failure' => sprintf('Expected %s but received %s.', var_export($expected, true), var_export($actual, true)),
            ];
        }

        return ['status' => 'passing', 'actual' => $actual];
    }

    /**
     * @param array<string, mixed> $optionValue
     * @return array{status: 'passing'}|array{status: 'failing', failure: string}
     */
    public static function evaluateRangeError(
        string $tag,
        string $optionName,
        array $optionValue,
        string $representation,
    ): array {
        try {
            $value = self::optionValue($optionValue);
            $values = [$optionName => $value];
            new Locale($tag, $representation === 'associative_array' ? $values : (object) $values);
        } catch (RangeError) {
            return ['status' => 'passing'];
        } catch (\Throwable $error) {
            return ['status' => 'failing', 'failure' => sprintf('%s: %s', $error::class, $error->getMessage())];
        }

        return ['status' => 'failing', 'failure' => 'Expected RangeError, but construction succeeded.'];
    }

    /** @param array<string, mixed> $optionValue */
    private static function optionValue(array $optionValue): mixed
    {
        return match ($optionValue['type']) {
            'null' => null,
            'undefined' => UndefinedValue::Value,
            'int' => $optionValue['value'],
            'float' => $optionValue['value'],
            'bool' => $optionValue['value'],
            'string' => $optionValue['value'],
            'object' => new \stdClass(),
            'primitive' => new PrimitiveValue(self::primitivePayload($optionValue)),
            'stringable' => new class (self::stringPayload($optionValue)) implements \Stringable {
                public function __construct(private readonly string $value)
                {
                }

                public function __toString(): string
                {
                    return $this->value;
                }
            },
            default => throw new \InvalidArgumentException('Unsupported option value representation.'),
        };
    }

    /** @param array<string, mixed> $optionValue */
    private static function stringPayload(array $optionValue): string
    {
        $value = $optionValue['value'] ?? null;
        if (!is_string($value)) {
            throw new \InvalidArgumentException('The stringable option representation requires a string value.');
        }

        return $value;
    }

    /** @param array<string, mixed> $optionValue */
    private static function primitivePayload(array $optionValue): string|bool|int|float|null
    {
        $value = $optionValue['value'] ?? null;
        if (!is_string($value) && !is_bool($value) && !is_int($value) && !is_float($value) && $value !== null) {
            throw new \InvalidArgumentException('Unsupported primitive option value.');
        }

        return $value;
    }
}
