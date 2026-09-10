<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Test262\Harness;

use Midnight\Intl\Spec\Locale;

final class ConstructorOptionAssertion
{
    /**
     * @param array{type: 'null'}|array{type: 'string'|'stringable', value: string} $optionValue
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
            $value = match ($optionValue['type']) {
                'null' => null,
                'string' => $optionValue['value'],
                'stringable' => new class ($optionValue['value']) implements \Stringable {
                    public function __construct(private readonly string $value)
                    {
                    }

                    public function __toString(): string
                    {
                        return $this->value;
                    }
                },
            };
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
}
