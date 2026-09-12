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
$releaseDataSnapshotLocale = new SpecLocale($locale);
$releaseDataSnapshotWeekInfo = $releaseDataSnapshotLocale->getWeekInfo();
$releaseDataSnapshotFirstDay = $releaseDataSnapshotWeekInfo['firstDay'];
$intlLoaded = extension_loaded('intl');
$capabilityDefinitions = [
    'intl-calendar-week-info' => [
        'available' => $intlLoaded && class_exists(IntlCalendar::class),
        'fallbackEvidence' => [
            'operation' => 'getWeekInfo',
            'locale' => $locale,
            'result' => $releaseDataSnapshotWeekInfo,
        ],
    ],
    'intl-time-zone-iana-id' => [
        'available' => $intlLoaded && method_exists(IntlTimeZone::class, 'getIanaID'),
        'fallbackEvidence' => [
            'operation' => 'getTimeZones',
            'locale' => $locale,
            'result' => $releaseDataSnapshotLocale->getTimeZones(),
        ],
    ],
    'locale-likely-subtags' => [
        'available' => $intlLoaded && method_exists(Locale::class, 'addLikelySubtags'),
        'fallbackEvidence' => [
            'operation' => 'maximize',
            'locale' => 'zh-TW',
            'result' => (new SpecLocale('zh-TW'))->maximize()->toString(),
        ],
    ],
    'locale-text-direction' => [
        'available' => $intlLoaded && method_exists(Locale::class, 'isRightToLeft'),
        'fallbackEvidence' => [
            'operation' => 'getTextInfo',
            'locale' => 'ar',
            'result' => (new SpecLocale('ar'))->getTextInfo(),
        ],
    ],
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

$hostCapabilities = [];
$fallbackEvidence = [];
foreach ($capabilityDefinitions as $capability => $definition) {
    $hostCapabilities[$capability] = $definition['available'];
    if (!$definition['available']) {
        $fallbackEvidence[$capability] = $definition['fallbackEvidence'];
    }
}

$evidence = IcuComparison::evidence(
    $locale,
    $releaseDataSnapshotFirstDay,
    $hostIcuVersion,
    $hostIcuFirstDay,
    $hostCapabilities,
    $fallbackEvidence,
);
$path = $argv[1];
$directory = dirname($path);
if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
    throw new RuntimeException(sprintf('Unable to create ICU comparison directory %s.', $directory));
}
if (file_put_contents($path, json_encode($evidence, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR) . "\n") === false) {
    throw new RuntimeException(sprintf('Unable to write ICU comparison evidence to %s.', $path));
}
