<?php

declare(strict_types=1);

$root = dirname(__DIR__);
if ($argc !== 5) {
    fwrite(
        STDERR,
        "Usage: php tools/verify-data-sources.php <UCD.zip> <cldr-core.zip> <language-subtag-registry> <tzdata.tar.gz>\n",
    );
    exit(1);
}

$manifestSource = file_get_contents($root.'/resources/data/manifest.json');
if ($manifestSource === false) {
    fwrite(STDERR, "Unable to read the release data manifest.\n");
    exit(1);
}

/** @var array{inputs: array{unicode: array{sha512: string}, cldr: array{sha512: string}, languageRegistry: array{fileDate: string, sha256: string}, tzdb: array{sha512: string}}} $manifest */
$manifest = json_decode($manifestSource, true, flags: JSON_THROW_ON_ERROR);
$checks = [
    ['Unicode 17.0.0 UCD', 'sha512', $argv[1], $manifest['inputs']['unicode']['sha512']],
    ['CLDR 48.2 core', 'sha512', $argv[2], $manifest['inputs']['cldr']['sha512']],
    ['IANA language subtag registry', 'sha256', $argv[3], $manifest['inputs']['languageRegistry']['sha256']],
    ['IANA tzdb 2026c', 'sha512', $argv[4], $manifest['inputs']['tzdb']['sha512']],
];
foreach ($checks as [$name, $algorithm, $path, $expected]) {
    $actual = is_file($path) ? hash_file($algorithm, $path) : false;
    if ($actual !== $expected) {
        fwrite(STDERR, sprintf("%s failed its %s integrity check.\n", $name, strtoupper($algorithm)));
        exit(1);
    }
}

$registry = file_get_contents($argv[3]);
$fileDate = $manifest['inputs']['languageRegistry']['fileDate'];
if ($registry === false || !str_starts_with($registry, 'File-Date: '.$fileDate."\n")) {
    fwrite(STDERR, "The IANA language subtag registry has the wrong File-Date.\n");
    exit(1);
}

fwrite(STDOUT, "Verified the complete release data source snapshot.\n");
