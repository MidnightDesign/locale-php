<?php

declare(strict_types=1);

namespace Midnight\Intl\Internal\Test262;

interface OptionBag
{
    public function has(string $name): bool;

    public function get(string $name): mixed;
}
