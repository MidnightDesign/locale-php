<?php

declare(strict_types=1);

use Midnight\Intl\Tools\NumberingSystemDataImporter;

const CLDR_REVISION = '11299982335beb974c1c63c45265184e759c0f41';

const CLDR_CORE_SHA512 = 'de8660f5371e0fcfd03a42e3b4fc4c686ec6cd602b402f1e3d227844005a54eb7952873894443523837d5828c42874a1a267a19f91ded207a2d166144791fa62';

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';

if ($argc !== 2) {
    fwrite(STDERR, "Usage: php tools/import-numbering-systems.php <cldr-core.zip>\n");
    exit(1);
}

$archivePath = $argv[1];
if (!hash_equals(CLDR_CORE_SHA512, (string) hash_file('sha512', $archivePath))) {
    fwrite(STDERR, "The CLDR 48.2 core archive failed its SHA-512 integrity check.\n");
    exit(1);
}

$archive = new ZipArchive();
if ($archive->open($archivePath) !== true) {
    fwrite(STDERR, "Unable to open the CLDR 48.2 core archive.\n");
    exit(1);
}

$localeSources = [];
$sourceEntries = [];
for ($index = 0; $index < $archive->numFiles; ++$index) {
    $path = $archive->getNameIndex($index);
    if ($path === false || preg_match('#^common/main/([^/]+)\.xml$#D', $path, $match) !== 1) {
        continue;
    }
    $source = readArchiveEntry($archive, $path);
    $localeSources[$match[1]] = $source;
    $sourceEntries[$path] = hash('sha256', $source);
}
$supplementalPath = 'common/supplemental/supplementalData.xml';
$supplementalData = readArchiveEntry($archive, $supplementalPath);
$sourceEntries[$supplementalPath] = hash('sha256', $supplementalData);
$numberingSystemsPath = 'common/supplemental/numberingSystems.xml';
$numberingSystems = readArchiveEntry($archive, $numberingSystemsPath);
$sourceEntries[$numberingSystemsPath] = hash('sha256', $numberingSystems);
$likelySubtagsPath = 'common/supplemental/likelySubtags.xml';
$likelySubtags = readArchiveEntry($archive, $likelySubtagsPath);
$sourceEntries[$likelySubtagsPath] = hash('sha256', $likelySubtags);
$archive->close();

ksort($localeSources, SORT_STRING);
ksort($sourceEntries, SORT_STRING);
$projection = NumberingSystemDataImporter::project(
    $localeSources,
    $supplementalData,
    $likelySubtags,
    $numberingSystems,
);
$data = [
    'format' => 1,
    'cldrRevision' => CLDR_REVISION,
    'upstreamSha512' => CLDR_CORE_SHA512,
    'sourceEntries' => $sourceEntries,
    ...$projection,
];

$encoded = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n";
if (file_put_contents($root . '/resources/data/numbering-systems.json', $encoded) === false) {
    throw new RuntimeException('Unable to write the numbering-system projection.');
}

function readArchiveEntry(ZipArchive $archive, string $path): string
{
    $contents = $archive->getFromName($path);
    if ($contents === false) {
        throw new RuntimeException(sprintf('The CLDR archive is missing %s.', $path));
    }

    return $contents;
}
