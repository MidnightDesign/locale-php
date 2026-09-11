<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Test262\Harness;

use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Internal\Test262\SymbolValue;
use Midnight\Intl\Internal\UndefinedValue;
use Midnight\Intl\Spec\Locale;

final class ReceiverBranding
{
    /** @return list<bool> */
    public static function property(string $property): array
    {
        return self::evaluate('__get', [$property]);
    }

    /** @return list<bool> */
    public static function method(string $method): array
    {
        return self::evaluate($method, []);
    }

    /** @param list<mixed> $arguments
     * @return list<bool>
     */
    private static function evaluate(string $method, array $arguments): array
    {
        $reflection = new \ReflectionMethod(Locale::class, $method);
        $uninitialized = (new \ReflectionClass(Locale::class))->newInstanceWithoutConstructor();
        $receivers = [
            UndefinedValue::Value,
            null,
            true,
            '',
            new SymbolValue(),
            1,
            new \stdClass(),
            $uninitialized,
        ];

        return array_map(static function (mixed $receiver) use ($reflection, $arguments, $uninitialized): bool {
            try {
                $reflection->invoke(is_object($receiver) ? $receiver : null, ...$arguments);
            } catch (\Throwable $error) {
                if ($receiver === $uninitialized) {
                    return $error instanceof TypeError && $error->getMessage() === 'Locale is not initialized.';
                }

                return $error instanceof \ReflectionException || $error instanceof \TypeError;
            }

            return false;
        }, $receivers);
    }
}
