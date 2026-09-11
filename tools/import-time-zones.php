<?php

declare(strict_types=1);

use Midnight\Intl\Tools\TimeZoneDataImporter;

const TZDB_VERSION = '2026c';

const TZDB_SHA512 = 'e0b4b7044b66fbc27bc21d13d18063abcdf78ab58d5ba5fd64bd1a88d86e9d495f45add4d8e65bb6c40249f9c94ca29b72c8ebba8d0e4c468f2965ac77932ef0';

const CLDR_REVISION = '11299982335beb974c1c63c45265184e759c0f41';

const CLDR_CORE_SHA512 = 'de8660f5371e0fcfd03a42e3b4fc4c686ec6cd602b402f1e3d227844005a54eb7952873894443523837d5828c42874a1a267a19f91ded207a2d166144791fa62';

const TZDB_SOURCE_FILES = [
    'africa',
    'antarctica',
    'asia',
    'australasia',
    'europe',
    'northamerica',
    'southamerica',
    'etcetera',
    'factory',
    'backward',
];

// No replacement in tzdb 2026c remains inside the selected two-year waiting period.
const PENDING_RENAMES = [];

const PROMOTED_RENAMES = [
    'America/Godthab' => 'America/Nuuk',
    'Europe/Kiev' => 'Europe/Kyiv',
    'Pacific/Enderbury' => 'Pacific/Kanton',
];

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';

if ($argc !== 3) {
    fwrite(STDERR, "Usage: php tools/import-time-zones.php <tzdata.tar.gz> <cldr-core.zip>\n");
    exit(1);
}

[$script, $tzdbPath, $cldrPath] = $argv;
unset($script);
if (!hash_equals(TZDB_SHA512, (string) hash_file('sha512', $tzdbPath))) {
    fwrite(STDERR, "The tzdb 2026c archive failed its SHA-512 integrity check.\n");
    exit(1);
}
if (!hash_equals(CLDR_CORE_SHA512, (string) hash_file('sha512', $cldrPath))) {
    fwrite(STDERR, "The CLDR 48.2 core archive failed its SHA-512 integrity check.\n");
    exit(1);
}

$tzdb = new PharData($tzdbPath);
$sourceEntries = [];
$sources = [];
foreach (TZDB_SOURCE_FILES as $name) {
    $source = $tzdb[$name]->getContent();
    $sources[] = $source;
    $sourceEntries[$name] = hash('sha256', $source);
}
$zoneTab = $tzdb['zone.tab']->getContent();
$backzone = $tzdb['backzone']->getContent();
$sourceEntries['zone.tab'] = hash('sha256', $zoneTab);
$sourceEntries['backzone'] = hash('sha256', $backzone);

$cldr = new ZipArchive();
if ($cldr->open($cldrPath) !== true) {
    throw new RuntimeException('Unable to open the CLDR 48.2 core archive.');
}
$cldrEntry = 'common/bcp47/timezone.xml';
$cldrTimeZones = $cldr->getFromName($cldrEntry);
$cldr->close();
if ($cldrTimeZones === false) {
    throw new RuntimeException('The CLDR archive is missing common/bcp47/timezone.xml.');
}

$projection = TimeZoneDataImporter::project($sources, $zoneTab, $backzone, $cldrTimeZones, PENDING_RENAMES);
$data = [
    'format' => 1,
    'tzdbVersion' => TZDB_VERSION,
    'tzdbSha512' => TZDB_SHA512,
    'cldrRevision' => CLDR_REVISION,
    'cldrCoreSha512' => CLDR_CORE_SHA512,
    'sourceEntries' => [
        'tzdb' => $sourceEntries,
        'cldr' => [$cldrEntry => hash('sha256', $cldrTimeZones)],
    ],
    'renameWaitingPeriod' => [
        'recommendedYears' => 2,
        'reviewedAt' => '2026-09-11',
        'pending' => PENDING_RENAMES,
        'promoted' => PROMOTED_RENAMES,
    ],
    ...$projection,
];

$target = $root . '/resources/data/primary-time-zones.json';
$encoded = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n";
if (file_put_contents($target, $encoded) === false) {
    throw new RuntimeException('Unable to write the primary time-zone projection.');
}
