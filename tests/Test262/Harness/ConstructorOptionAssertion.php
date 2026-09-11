<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Test262\Harness;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Internal\UndefinedValue;
use Midnight\Intl\Spec\Locale;

final class ConstructorOptionAssertion
{
    /**
     * @param array{type: 'null'|'undefined'}|array{type: 'string'|'stringable', value: string}|array{type: 'int', value: int} $optionValue
     *
     * @return array{status: 'passing', actual: string}|array{status: 'failing', actual?: string, failure: string}
     */
    public static function evaluate(
        string $tag,
        string $optionName,
        array $optionValue,
        string $representation,
        string $expected,
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
            $actual = (new Locale($tag, $options))->toString();
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
                'failure' => sprintf('Expected "%s" but received "%s".', $expected, $actual),
            ];
        }

        return ['status' => 'passing', 'actual' => $actual];
    }

    /**
     * @param array{type: 'null'|'undefined'}|array{type: 'string'|'stringable', value: string}|array{type: 'int', value: int} $optionValue
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

    /** @param array{type: 'null'|'undefined'}|array{type: 'string'|'stringable', value: string}|array{type: 'int', value: int} $optionValue */
    private static function optionValue(array $optionValue): mixed
    {
        return match ($optionValue['type']) {
            'null' => null,
            'undefined' => UndefinedValue::Value,
            'int' => $optionValue['value'],
            'string' => $optionValue['value'],
            'stringable' => new class($optionValue['value']) implements \Stringable {
                public function __construct(
                    private readonly string $value,
                ) {}

                public function __toString(): string
                {
                    return $this->value;
                }
            },
        };
    }
}
