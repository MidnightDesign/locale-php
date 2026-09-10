<?php

declare(strict_types=1);

require dirname(__DIR__).'/vendor/autoload.php';

use Midnight\Intl\Locale;

$locale = new Locale('EN-latn-us', region: 'GB');

echo $locale->toString(), PHP_EOL;
echo json_encode($locale, JSON_THROW_ON_ERROR), PHP_EOL;
