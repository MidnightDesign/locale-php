<?php

declare(strict_types=1);

namespace Midnight\Intl\Internal;

use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Internal\Test262\OptionBag;

/** @internal */
final readonly class LocaleOptions
{
    /** @param array<array-key, mixed>|object|null $values */
    private function __construct(
        private array|object|null $values,
    ) {}

    public static function from(mixed $value): self
    {
        if ($value === null) {
            throw new TypeError('The locale options must not be null.');
        }

        return new self(is_array($value) || is_object($value) ? $value : null);
    }

    public function read(string $name): OptionValue
    {
        if ($this->values instanceof OptionBag) {
            return self::optionValue($this->values->get($name));
        }

        if (is_array($this->values)) {
            return array_key_exists($name, $this->values)
                ? self::optionValue($this->values[$name])
                : OptionValue::missing();
        }

        if ($this->values === null) {
            return OptionValue::missing();
        }

        $properties = get_object_vars($this->values);

        return array_key_exists($name, $properties) ? self::optionValue($properties[$name]) : OptionValue::missing();
    }

    private static function optionValue(mixed $value): OptionValue
    {
        return $value === UndefinedValue::Value ? OptionValue::missing() : OptionValue::present($value);
    }
}
