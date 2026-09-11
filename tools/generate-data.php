<?php

declare(strict_types=1);

$root = dirname(__DIR__);
require $root.'/vendor/autoload.php';

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
 *     likelySubtag: array<string, string>,
 *     likelyRegion: array<string, string>,
 *     variant: array<string, string>,
 *     subdivision: array<string, string>,
 *     key: array<string, string>,
 *     type: array<string, array<string, string>>
 * } $data */
$data = json_decode($source, true, flags: JSON_THROW_ON_ERROR);
if ($data['format'] !== 3) {
    fwrite(STDERR, "The locale alias projection format is incompatible.\n");
    exit(1);
}

$constants = '';
foreach ([
    'LANGUAGE' => ['language', 'array<string, string>'],
    'SCRIPT' => ['script', 'array<string, string>'],
    'REGION' => ['region', 'array<int|string, string>'],
    'REGION_ALTERNATIVES' => ['regionAlternatives', 'array<int|string, list<string>>'],
    'LIKELY_SUBTAG' => ['likelySubtag', 'array<string, string>'],
    'LIKELY_REGION' => ['likelyRegion', 'array<string, string>'],
    'VARIANT' => ['variant', 'array<string, string>'],
    'SUBDIVISION' => ['subdivision', 'array<string, string>'],
    'KEY' => ['key', 'array<string, string>'],
    'TYPE' => ['type', 'array<string, array<string, string>>'],
] as $constant => [$field, $type]) {
    $export = preg_replace(
        '/[ \t]+$/m',
        '',
        Midnight\Intl\Tools\PhpExporter::export($data[$field]),
    );
    if ($export === null) {
        throw new RuntimeException(sprintf('Unable to export the %s projection.', $field));
    }
    $constants .= sprintf("\n    /** @var %s */\n    public const %s = %s;\n", $type, $constant, $export);
}

$sourceSha256 = hash('sha256', $source);
$payloadSha256 = hash('sha256', json_encode([
    'format' => $data['format'],
    'language' => $data['language'],
    'script' => $data['script'],
    'region' => $data['region'],
    'regionAlternatives' => $data['regionAlternatives'],
    'likelySubtag' => $data['likelySubtag'],
    'likelyRegion' => $data['likelyRegion'],
    'variant' => $data['variant'],
    'subdivision' => $data['subdivision'],
    'key' => $data['key'],
    'type' => $data['type'],
], JSON_THROW_ON_ERROR));
$generated = <<<PHP
<?php

declare(strict_types=1);

namespace Midnight\\Intl\\Internal\\Data;

enum LocaleAliases
{
    public const FORMAT = {$data['format']};

    /** @var string */
    public const CLDR_REVISION = '{$data['cldrRevision']}';

    /** @var string */
    public const CLDR_CORE_SHA512 = '{$data['upstreamSha512']}';

    /** @var string */
    public const SOURCE_SHA256 = '{$sourceSha256}';

    private const PAYLOAD_SHA256 = '{$payloadSha256}';
{$constants}
    public static function assertIntegrity(): void
    {
        /** @var bool|null \$verified */
        static \$verified = null;
        if (\$verified === true) {
            return;
        }

        if (!self::supportsFormat(self::FORMAT)) {
            throw new \\UnexpectedValueException('The bundled locale data is corrupt or incompatible.');
        }

        \$actual = hash('sha256', json_encode([
            'format' => self::FORMAT,
            'language' => self::LANGUAGE,
            'script' => self::SCRIPT,
            'region' => self::REGION,
            'regionAlternatives' => self::REGION_ALTERNATIVES,
            'likelySubtag' => self::LIKELY_SUBTAG,
            'likelyRegion' => self::LIKELY_REGION,
            'variant' => self::VARIANT,
            'subdivision' => self::SUBDIVISION,
            'key' => self::KEY,
            'type' => self::TYPE,
        ], JSON_THROW_ON_ERROR));
        if (\$actual !== self::PAYLOAD_SHA256) {
            throw new \\UnexpectedValueException('The bundled locale data is corrupt or incompatible.');
        }
        \$verified = true;
    }

    private static function supportsFormat(int \$format): bool
    {
        return \$format === 3;
    }
}
PHP;
$generated .= "\n";

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

    /** @var array{format: int, releaseDataFingerprint: string, inputs: array{unicode: array{sha512: string}, cldr: array{sha512: string}, languageRegistry: array{sha256: string}, tzdb: array{sha512: string}}, projections: array{localeAliases: array{sourceSha256: string, generatedSha256: string}}, generators: array<string, string>} $manifest */
    $manifest = json_decode($manifestSource, true, flags: JSON_THROW_ON_ERROR);
    $fingerprint = hash('sha256', json_encode([
        'unicode' => $manifest['inputs']['unicode']['sha512'],
        'cldr' => $manifest['inputs']['cldr']['sha512'],
        'ianaLanguage' => $manifest['inputs']['languageRegistry']['sha256'],
        'tzdb' => $manifest['inputs']['tzdb']['sha512'],
        'projection' => $sourceSha256,
    ], JSON_THROW_ON_ERROR));
    if ($manifest['format'] !== 3
        || $manifest['inputs']['cldr']['sha512'] !== $data['upstreamSha512']
        || $manifest['releaseDataFingerprint'] !== $fingerprint
        || $manifest['projections']['localeAliases']['sourceSha256'] !== $sourceSha256
        || $manifest['projections']['localeAliases']['generatedSha256'] !== hash('sha256', $generated)) {
        fwrite(STDERR, "The release data manifest fingerprints do not match.\n");
        exit(1);
    }
    foreach ($manifest['generators'] as $path => $expectedHash) {
        if (!is_file($root.'/'.$path) || hash_file('sha256', $root.'/'.$path) !== $expectedHash) {
            fwrite(STDERR, sprintf("The release data generator fingerprint does not match for %s.\n", $path));
            exit(1);
        }
    }

    exit(0);
}

if (file_put_contents($target, $generated) === false) {
    fwrite(STDERR, "Unable to write the locale alias projection.\n");
    exit(1);
}
