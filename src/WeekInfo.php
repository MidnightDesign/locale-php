<?php

declare(strict_types=1);

namespace Midnight\Intl;

/** @psalm-api */
final readonly class WeekInfo
{
    /**
     * @param int<1, 7>                 $firstDay
     * @param non-empty-list<int<1, 7>> $weekend
     */
    public function __construct(
        public int $firstDay,
        public array $weekend,
    ) {}
}
