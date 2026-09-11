<?php

declare(strict_types=1);

namespace Midnight\Intl;

/** @psalm-api */
final readonly class TextInfo
{
    public function __construct(
        public ?TextDirection $direction,
    ) {}
}
