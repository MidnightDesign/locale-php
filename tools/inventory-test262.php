<?php

declare(strict_types=1);

if ($argc !== 2 || !is_dir($argv[1])) {
    fwrite(STDERR, "Usage: php tools/inventory-test262.php <pinned-test262-checkout>\n");
    exit(1);
}

$checkout = rtrim($argv[1], '/');
$localeRoot = $checkout.'/test/intl402/Locale';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($localeRoot));
$paths = [];
foreach ($iterator as $file) {
    if (!$file instanceof SplFileInfo) {
        continue;
    }

    if ($file->isFile() && $file->getExtension() === 'js') {
        $paths[] = substr($file->getPathname(), strlen($checkout) + 1);
    }
}
sort($paths);

$fixtures = [];
$hashManifest = '';
foreach ($paths as $path) {
    $source = file_get_contents($checkout.'/'.$path);
    if ($source === false) {
        throw new RuntimeException('Unable to read '.$path);
    }

    $sha256 = hash('sha256', $source);
    $hashManifest .= strtoupper($sha256).'  '.$path."\n";
    $assertions = [];
    foreach (preg_split('/\R/', $source) ?: [] as $index => $line) {
        if (preg_match_all('/\b(assert\.[A-Za-z]+|verifyProperty|verifyEqualTo)\s*\(/', $line, $calls)) {
            foreach ($calls[1] as $call) {
                $assertions[] = [
                    'line' => $index + 1,
                    'call' => $call,
                    'status' => $path === 'test/intl402/Locale/getters-missing.js'
                        ? 'see-translated-evidence'
                        : 'translation_gap',
                ];
            }
        }
    }

    $translated = $path === 'test/intl402/Locale/getters-missing.js';
    $fixtures[] = [
        'path' => $path,
        'sha256' => $sha256,
        'status' => $translated ? 'partially_translated' : 'translation_gap',
        'reason' => $translated
            ? 'Applicable assertions and out-of-slice gaps are detailed in tests/Test262/evidence.json.'
            : 'The fixture remains visible as unfinished work for the incomplete initial slice.',
        'detectedAssertions' => $assertions,
    ];
}

$inventory = [
    'test262Revision' => '419d3e0a2273ba01a3bfcbec423f2801425b8e93',
    'localeTree' => 'e46f95ccfbe3d202e15e0f9dce594f04fe9c6205',
    'fixtureCount' => count($fixtures),
    'aggregateSha256' => hash('sha256', rtrim($hashManifest, "\n")),
    'assertionDetection' => 'Direct Test262 assert.* calls and standard verifyProperty/verifyEqualTo helpers. Fixture-level translation_gap covers any assertion expressed through other helpers.',
    'fixtures' => $fixtures,
];

file_put_contents(
    dirname(__DIR__).'/tests/Test262/corpus.json',
    json_encode($inventory, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n",
);
