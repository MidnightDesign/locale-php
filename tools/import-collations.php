<?php

declare(strict_types=1);

use Midnight\Intl\Tools\CollationDataImporter;

const CLDR_VERSION = '48.2';

const CLDR_REVISION = '11299982335beb974c1c63c45265184e759c0f41';

const CLDR_CORE_SHA512 = 'de8660f5371e0fcfd03a42e3b4fc4c686ec6cd602b402f1e3d227844005a54eb7952873894443523837d5828c42874a1a267a19f91ded207a2d166144791fa62';

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';

if ($argc !== 2) {
    fwrite(STDERR, "Usage: php tools/import-collations.php <cldr-core.zip>\n");
    exit(1);
}

[$script, $cldrPath] = $argv;
unset($script);
if (!hash_equals(CLDR_CORE_SHA512, (string) hash_file('sha512', $cldrPath))) {
    fwrite(STDERR, "The CLDR 48.2 core archive failed its SHA-512 integrity check.\n");
    exit(1);
}

$cldr = new ZipArchive();
if ($cldr->open($cldrPath) !== true) {
    throw new RuntimeException('Unable to open the CLDR 48.2 core archive.');
}

$localeSources = [];
$sourceEntries = [];
for ($index = 0; $index < $cldr->numFiles; $index++) {
    $entry = $cldr->getNameIndex($index);
    if ($entry === false || preg_match('#\Acommon/collation/([^/]+)\.xml\z#', $entry, $match) !== 1) {
        continue;
    }
    $source = $cldr->getFromIndex($index);
    if ($source === false) {
        throw new RuntimeException(sprintf('Unable to read %s from the CLDR archive.', $entry));
    }
    $locale = str_replace('_', '-', $match[1]);
    $localeSources[$locale] = $source;
    $sourceEntries[$entry] = hash('sha256', $source);
}

$bcp47Entry = 'common/bcp47/collation.xml';
$supplementalEntry = 'common/supplemental/supplementalData.xml';
$bcp47Source = $cldr->getFromName($bcp47Entry);
$supplementalSource = $cldr->getFromName($supplementalEntry);
$cldr->close();
if ($bcp47Source === false || $supplementalSource === false) {
    throw new RuntimeException('The CLDR archive is missing required collation metadata.');
}
$sourceEntries[$bcp47Entry] = hash('sha256', $bcp47Source);
$sourceEntries[$supplementalEntry] = hash('sha256', $supplementalSource);
ksort($sourceEntries, SORT_STRING);

$data = [
    'format' => 1,
    'cldrVersion' => CLDR_VERSION,
    'cldrRevision' => CLDR_REVISION,
    'cldrCoreSha512' => CLDR_CORE_SHA512,
    'sourceEntries' => $sourceEntries,
    ...CollationDataImporter::project($localeSources, $bcp47Source, $supplementalSource),
];

$target = $root . '/resources/data/collations.json';
$encoded = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n";
if (file_put_contents($target, $encoded) === false) {
    throw new RuntimeException('Unable to write the collation availability projection.');
}
