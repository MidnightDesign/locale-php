<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$sourcePath = $root.'/resources/data/locale-aliases.json';
$source = file_get_contents($sourcePath);
if ($source === false) {
    fwrite(STDERR, "Unable to read the locale alias projection source.\n");
    exit(1);
}

/** @var array{
 *     format: int,
 *     cldrRevision: string,
 *     upstreamSha512: string,
 *     sourceEntries: array<string, string>,
 *     language: array<string, string>,
 *     script: array<string, string>,
 *     region: array<string, string>,
 *     regionAlternatives: array<string, list<string>>,
 *     likelyRegion: array<string, string>,
 *     variant: array<string, string>,
 *     subdivision: array<string, string>,
 *     key: array<string, string>,
 *     type: array<string, array<string, string>>
 * } $data */
$data = json_decode($source, true, flags: JSON_THROW_ON_ERROR);
if ($data['format'] !== 2) {
    fwrite(STDERR, "The locale alias projection format is incompatible.\n");
    exit(1);
}

$constants = '';
foreach ([
    'LANGUAGE' => 'language',
    'SCRIPT' => 'script',
    'REGION' => 'region',
    'REGION_ALTERNATIVES' => 'regionAlternatives',
    'LIKELY_REGION' => 'likelyRegion',
    'VARIANT' => 'variant',
    'SUBDIVISION' => 'subdivision',
    'KEY' => 'key',
    'TYPE' => 'type',
] as $constant => $field) {
    $export = preg_replace('/[ \t]+$/m', '', var_export($data[$field], true));
    if ($export === null) {
        throw new RuntimeException(sprintf('Unable to export the %s projection.', $field));
    }
    $constants .= sprintf("\n    public const %s = %s;\n", $constant, $export);
}

$sourceSha256 = hash('sha256', $source);
$generated = <<<PHP
<?php

declare(strict_types=1);

namespace Midnight\\Intl\\Internal\\Data;

final class LocaleAliases
{
    public const FORMAT = {$data['format']};

    public const CLDR_REVISION = '{$data['cldrRevision']}';

    public const CLDR_CORE_SHA512 = '{$data['upstreamSha512']}';

    public const SOURCE_SHA256 = '{$sourceSha256}';
{$constants}
    private function __construct()
    {
    }
}
PHP;

$target = $root.'/src/Internal/Data/LocaleAliases.php';
if (in_array('--check', $argv, true)) {
    if (!is_file($target) || file_get_contents($target) !== $generated) {
        fwrite(STDERR, "src/Internal/Data/LocaleAliases.php is not reproducible.\n");
        exit(1);
    }

    $manifestSource = file_get_contents($root.'/resources/data/manifest.json');
    if ($manifestSource === false) {
        fwrite(STDERR, "Unable to read the release data manifest.\n");
        exit(1);
    }

    /** @var array{format: int, releaseDataFingerprint: string, inputs: array{unicode: array{sha512: string}, cldr: array{sha512: string}, languageRegistry: array{sha256: string}, tzdb: array{sha512: string}}, projections: array{localeAliases: array{sourceSha256: string, generatedSha256: string}}} $manifest */
    $manifest = json_decode($manifestSource, true, flags: JSON_THROW_ON_ERROR);
    $fingerprint = hash('sha256', json_encode([
        'unicode' => $manifest['inputs']['unicode']['sha512'],
        'cldr' => $manifest['inputs']['cldr']['sha512'],
        'ianaLanguage' => $manifest['inputs']['languageRegistry']['sha256'],
        'tzdb' => $manifest['inputs']['tzdb']['sha512'],
        'projection' => $sourceSha256,
    ], JSON_THROW_ON_ERROR));
    if ($manifest['format'] !== 2
        || $manifest['inputs']['cldr']['sha512'] !== $data['upstreamSha512']
        || $manifest['releaseDataFingerprint'] !== $fingerprint
        || $manifest['projections']['localeAliases']['sourceSha256'] !== $sourceSha256
        || $manifest['projections']['localeAliases']['generatedSha256'] !== hash('sha256', $generated)) {
        fwrite(STDERR, "The release data manifest fingerprints do not match.\n");
        exit(1);
    }

    exit(0);
}

if (file_put_contents($target, $generated) === false) {
    fwrite(STDERR, "Unable to write the locale alias projection.\n");
    exit(1);
}
