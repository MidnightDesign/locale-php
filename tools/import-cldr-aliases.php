<?php

declare(strict_types=1);

if ($argc !== 2) {
    fwrite(STDERR, "Usage: php tools/import-cldr-aliases.php <supplementalMetadata.xml>\n");
    exit(1);
}

$xml = file_get_contents($argv[1]);
if ($xml === false) {
    fwrite(STDERR, "Unable to read supplementalMetadata.xml.\n");
    exit(1);
}

/** @var array<string, string> $language */
$language = [];
/** @var array<string, string> $script */
$script = [];
/** @var array<string, string> $region */
$region = [];

preg_match_all('/<languageAlias type="([^"]+)" replacement="([^"]+)"/', $xml, $matches, PREG_SET_ORDER);
foreach ($matches as $match) {
    if (preg_match('/^(?:[A-Za-z]{2,3}|[A-Za-z]{5,8})$/D', $match[1])) {
        $language[strtolower($match[1])] = str_replace('_', '-', $match[2]);
    }
}

preg_match_all('/<scriptAlias type="([^"]+)" replacement="([^"]+)"/', $xml, $matches, PREG_SET_ORDER);
foreach ($matches as $match) {
    if (preg_match('/^[A-Za-z]{4}$/D', $match[1])) {
        $script[ucfirst(strtolower($match[1]))] = ucfirst(strtolower($match[2]));
    }
}

preg_match_all('/<territoryAlias type="([^"]+)" replacement="([^"]+)"/', $xml, $matches, PREG_SET_ORDER);
foreach ($matches as $match) {
    if (preg_match('/^(?:[A-Za-z]{2}|[0-9]{3})$/D', $match[1])) {
        $region[strtoupper($match[1])] = strtoupper(explode(' ', $match[2])[0]);
    }
}

ksort($language);
ksort($script);
ksort($region);

$projection = [
    'cldrRevision' => '11299982335beb974c1c63c45265184e759c0f41',
    'upstreamSha256' => hash('sha256', $xml),
    'language' => $language,
    'script' => $script,
    'region' => $region,
];

file_put_contents(
    dirname(__DIR__).'/resources/data/locale-aliases.json',
    json_encode($projection, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n",
);
