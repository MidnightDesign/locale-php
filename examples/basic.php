<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Midnight\Intl\Locale;

$locale = new Locale('EN-latn-us-fonipa-u-ca-gregory-x-shop', region: 'GB', numeric: true);

echo $locale->toString(), PHP_EOL;
echo $locale->baseName, PHP_EOL;
echo $locale->calendar, PHP_EOL;
echo json_encode($locale, JSON_THROW_ON_ERROR), PHP_EOL;
echo $locale->maximize(), PHP_EOL;
echo $locale->maximize()->minimize(), PHP_EOL;
echo $locale->getTextInfo()->direction?->value ?? 'unknown', PHP_EOL;
