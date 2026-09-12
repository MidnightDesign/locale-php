<?php

declare(strict_types=1);

use Midnight\Intl\Spec\Locale as SpecLocale;
use Midnight\Intl\Tools\Ci\IcuComparison;

require dirname(__DIR__) . '/vendor/autoload.php';

if ($argc !== 2) {
    fwrite(STDERR, "Usage: php tools/record-icu-comparison.php <output-path>\n");
    exit(2);
}

$locale = 'en-AE';
$releaseDataSnapshotFirstDay = (new SpecLocale($locale))->getWeekInfo()['firstDay'];
$intlLoaded = extension_loaded('intl');
$hostCapabilities = [
    'intl-calendar-week-info' => $intlLoaded && class_exists(IntlCalendar::class),
    'intl-time-zone-iana-id' => $intlLoaded && method_exists(IntlTimeZone::class, 'getIanaID'),
    'locale-likely-subtags' => $intlLoaded && method_exists(Locale::class, 'addLikelySubtags'),
    'locale-text-direction' => $intlLoaded && method_exists(Locale::class, 'isRightToLeft'),
];
$hostIcuVersion = null;
$hostIcuFirstDay = null;
if ($intlLoaded && defined('INTL_ICU_VERSION')) {
    $calendar = IntlCalendar::createInstance(null, str_replace('-', '_', $locale));
    $hostIcuVersion = INTL_ICU_VERSION;
    $hostIcuFirstDay = (($calendar->getFirstDayOfWeek() + 5) % 7) + 1;
    if ($hostIcuFirstDay < 1) {
        throw new RuntimeException(sprintf('ICU returned an invalid first day for %s.', $locale));
    }
}

$evidence = IcuComparison::evidence(
    $locale,
    $releaseDataSnapshotFirstDay,
    $hostIcuVersion,
    $hostIcuFirstDay,
    $hostCapabilities,
);
$path = $argv[1];
$directory = dirname($path);
if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
    throw new RuntimeException(sprintf('Unable to create ICU comparison directory %s.', $directory));
}
if (file_put_contents($path, json_encode($evidence, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR) . "\n") === false) {
    throw new RuntimeException(sprintf('Unable to write ICU comparison evidence to %s.', $path));
}
