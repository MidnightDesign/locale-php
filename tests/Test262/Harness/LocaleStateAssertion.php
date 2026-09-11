<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Test262\Harness;

use Midnight\Intl\Spec\Locale;

final class LocaleStateAssertion
{
    /**
     * @param array<string, mixed>|null $options
     * @param list<LocaleStateExpectation> $expectations
     * @return list<array{status: 'passing', actual: mixed}|array{status: 'failing', actual?: mixed, failure: string}>
     */
    public static function evaluate(string $tag, ?array $options, string $representation, array $expectations): array
    {
        try {
            $locale = match ($representation) {
                'direct' => new Locale($tag),
                'associative_array' => new Locale($tag, $options),
                'plain_object' => new Locale($tag, (object) $options),
                default => throw new \InvalidArgumentException('Unsupported representation.'),
            };
        } catch (\Throwable $error) {
            return array_map(static fn(LocaleStateExpectation $_expectation): array => [
                'status' => 'failing',
                'failure' => sprintf('%s: %s', $error::class, $error->getMessage()),
            ], $expectations);
        }

        return array_map(static function (LocaleStateExpectation $expectation) use ($locale): array {
            try {
                $actual = $expectation->property === 'toString'
                    ? $locale->toString()
                    : $locale->{$expectation->property};
            } catch (\Throwable $error) {
                return [
                    'status' => 'failing',
                    'failure' => sprintf('%s: %s', $error::class, $error->getMessage()),
                ];
            }

            if ($actual === $expectation->expected) {
                return ['status' => 'passing', 'actual' => $actual];
            }

            return [
                'status' => 'failing',
                'actual' => $actual,
                'failure' => sprintf(
                    'Expected %s but received %s.',
                    var_export($expectation->expected, true),
                    var_export($actual, true),
                ),
            ];
        }, $expectations);
    }
}
