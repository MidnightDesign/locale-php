<?php

declare(strict_types=1);

namespace Midnight\Intl\Internal;

final readonly class OptionValue
{
    private function __construct(
        public bool $present,
        public mixed $value,
    ) {
    }

    public static function missing(): self
    {
        return new self(false, null);
    }

    public static function present(mixed $value): self
    {
        return new self(true, $value);
    }
}
