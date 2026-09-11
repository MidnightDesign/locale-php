<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Test262\Harness;

use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Internal\Test262\SymbolValue;
use Midnight\Intl\Internal\UndefinedValue;
use Midnight\Intl\Spec\Locale;

final class ReceiverBranding
{
    /** @return list<array{id: string, representation: string, passing: bool}> */
    public static function property(string $property): array
    {
        return self::evaluate('__get', [$property]);
    }

    /** @return list<array{id: string, representation: string, passing: bool}> */
    public static function method(string $method, bool $includeConstructor = false): array
    {
        return self::evaluate($method, [], $includeConstructor);
    }

    public static function methodIsAvailable(string $method): bool
    {
        try {
            $reflection = new \ReflectionMethod(Locale::class, $method);
        } catch (\ReflectionException) {
            return false;
        }

        return (
            $reflection->isPublic()
            && $reflection->getName() === $method
            && $reflection->getNumberOfRequiredParameters() === 0
        );
    }

    /**
     * @param list<mixed> $arguments
     * @return list<array{id: string, representation: string, passing: bool}>
     */
    private static function evaluate(string $method, array $arguments, bool $includeConstructor = false): array
    {
        $uninitialized = (new \ReflectionClass(Locale::class))->newInstanceWithoutConstructor();
        $receivers = [
            ['id' => 'undefined', 'value' => UndefinedValue::Value, 'representation' => 'native_receiver_binding'],
            ['id' => 'null', 'value' => null, 'representation' => 'native_receiver_binding'],
            ['id' => 'true', 'value' => true, 'representation' => 'native_receiver_binding'],
            ['id' => 'empty-string', 'value' => '', 'representation' => 'native_receiver_binding'],
            ['id' => 'symbol', 'value' => new SymbolValue(), 'representation' => 'native_receiver_binding'],
            ['id' => 'number', 'value' => 1, 'representation' => 'native_receiver_binding'],
            ['id' => 'plain-object', 'value' => new \stdClass(), 'representation' => 'native_receiver_binding'],
        ];
        if ($includeConstructor) {
            $receivers[] = [
                'id' => 'constructor',
                'value' => Locale::class,
                'representation' => 'native_receiver_binding',
            ];
        }
        $receivers[] = [
            'id' => 'uninitialized-locale',
            'value' => $uninitialized,
            'representation' => 'uninitialized_locale',
        ];

        try {
            $reflection = new \ReflectionMethod(Locale::class, $method);
        } catch (\ReflectionException) {
            return array_map(static fn(array $receiver): array => [
                'id' => $receiver['id'],
                'representation' => $receiver['representation'],
                'passing' => false,
            ], $receivers);
        }

        return array_map(static function (array $receiver) use ($reflection, $arguments, $uninitialized): array {
            $passing = false;
            try {
                $reflection->invoke(is_object($receiver['value']) ? $receiver['value'] : null, ...$arguments);
            } catch (\Throwable $error) {
                if ($receiver['value'] === $uninitialized) {
                    $passing = $error instanceof TypeError && $error->getMessage() === 'Locale is not initialized.';
                } else {
                    $passing = $error instanceof \ReflectionException || $error instanceof \TypeError;
                }
            }

            return [
                'id' => $receiver['id'],
                'representation' => $receiver['representation'],
                'passing' => $passing,
            ];
        }, $receivers);
    }
}
