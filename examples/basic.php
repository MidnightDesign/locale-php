<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Midnight\Intl\CaseFirst;
use Midnight\Intl\HourCycle;
use Midnight\Intl\Locale;

$locale = new Locale(
    'EN-latn-us-fonipa-u-ca-gregory-x-shop',
    region: 'GB',
    calendar: 'islamicc',
    collation: 'phonebk',
    firstDayOfWeek: '1',
    hourCycle: HourCycle::H23,
    caseFirst: CaseFirst::Upper,
    numeric: true,
    numberingSystem: 'latn',
);

echo $locale->toString(), PHP_EOL;
echo $locale->baseName, PHP_EOL;
echo $locale->calendar, PHP_EOL;
echo $locale->firstDayOfWeek, PHP_EOL;
echo $locale->hourCycle instanceof HourCycle ? $locale->hourCycle->value : $locale->hourCycle ?? '', PHP_EOL;
echo $locale->caseFirst instanceof CaseFirst ? $locale->caseFirst->value : $locale->caseFirst ?? '', PHP_EOL;
echo json_encode($locale, JSON_THROW_ON_ERROR), PHP_EOL;
echo $locale->maximize(), PHP_EOL;
echo $locale->maximize()->minimize(), PHP_EOL;
