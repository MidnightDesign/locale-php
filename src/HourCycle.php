<?php

declare(strict_types=1);

namespace Midnight\Intl;

enum HourCycle: string
{
    case H11 = 'h11';
    case H12 = 'h12';
    case H23 = 'h23';
    case H24 = 'h24';
}
