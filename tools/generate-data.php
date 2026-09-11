<?php

declare(strict_types=1);

use Midnight\Intl\Tools\MagoFormatter;

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';

$sourcePath = $root . '/resources/data/locale-aliases.json';
$source = file_get_contents($sourcePath);
if ($source === false) {
    fwrite(STDERR, "Unable to read the locale alias projection source.\n");
    exit(1);
}
$likelySubtagsSourcePath = $root . '/resources/data/likely-subtags.json';
$likelySubtagsSource = file_get_contents($likelySubtagsSourcePath);
if ($likelySubtagsSource === false) {
    fwrite(STDERR, "Unable to read the likely-subtag projection source.\n");
    exit(1);
}

/** @var array{
 *     format: int,
 *     cldrRevision: string,
 *     upstreamSha512: string,
 *     sourceEntries: array<string, string>,
 *     language: array<string, string>,
 *     compoundLanguage: array<string, array<string, string>>,
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
if ($data['format'] !== 3) {
    fwrite(STDERR, "The locale alias projection format is incompatible.\n");
    exit(1);
}

/** @var array{
 *     format: int,
 *     cldrRevision: string,
 *     upstreamSha512: string,
 *     sourceEntries: array<string, string>,
 *     likelySubtag: array<string, string>
 * } $likelySubtagsData */
$likelySubtagsData = json_decode($likelySubtagsSource, true, flags: JSON_THROW_ON_ERROR);
if ($likelySubtagsData['format'] !== 1) {
    fwrite(STDERR, "The likely-subtag projection format is incompatible.\n");
    exit(1);
}

$constants = '';
foreach ([
    'LANGUAGE' => ['language', 'array<string, string>'],
    'COMPOUND_LANGUAGE' => ['compoundLanguage', 'array<string, array<string, string>>'],
    'SCRIPT' => ['script', 'array<string, string>'],
    'REGION' => ['region', 'array<int|string, string>'],
    'REGION_ALTERNATIVES' => ['regionAlternatives', 'array<int|string, list<string>>'],
    'LIKELY_REGION' => ['likelyRegion', 'array<string, string>'],
    'VARIANT' => ['variant', 'array<string, string>'],
    'SUBDIVISION' => ['subdivision', 'array<string, string>'],
    'KEY' => ['key', 'array<string, string>'],
    'TYPE' => ['type', 'array<string, array<string, string>>'],
] as $constant => [$field, $type]) {
    $export = preg_replace('/[ \t]+$/m', '', Midnight\Intl\Tools\PhpExporter::export($data[$field]));
    if ($export === null) {
        throw new RuntimeException(sprintf('Unable to export the %s projection.', $field));
    }
    $constants .= sprintf("\n    /** @var %s */\n    public const %s = %s;\n", $type, $constant, $export);
}

$sourceSha256 = hash('sha256', $source);
$payloadSha256 = hash('sha256', json_encode([
    'format' => $data['format'],
    'language' => $data['language'],
    'compoundLanguage' => $data['compoundLanguage'],
    'script' => $data['script'],
    'region' => $data['region'],
    'regionAlternatives' => $data['regionAlternatives'],
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
                'compoundLanguage' => self::COMPOUND_LANGUAGE,
                'script' => self::SCRIPT,
                'region' => self::REGION,
                'regionAlternatives' => self::REGION_ALTERNATIVES,
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
$generated = MagoFormatter::format($root, 'src/Internal/Data/LocaleAliases.php', $generated);

$likelySubtagsSourceSha256 = hash('sha256', $likelySubtagsSource);
$likelySubtagsPayloadSha256 = hash('sha256', json_encode([
    'format' => $likelySubtagsData['format'],
    'likelySubtag' => $likelySubtagsData['likelySubtag'],
], JSON_THROW_ON_ERROR));
$likelySubtagsExport = preg_replace(
    '/[ \t]+$/m',
    '',
    Midnight\Intl\Tools\PhpExporter::export($likelySubtagsData['likelySubtag']),
);
if ($likelySubtagsExport === null) {
    throw new RuntimeException('Unable to export the likely-subtag projection.');
}
$likelySubtagsGenerated = <<<PHP
    <?php

    declare(strict_types=1);

    namespace Midnight\Intl\Internal\Data;

    enum LikelySubtags
    {
        public const FORMAT = {$likelySubtagsData['format']};

        /** @var string */
        public const CLDR_REVISION = '{$likelySubtagsData['cldrRevision']}';

        /** @var string */
        public const CLDR_CORE_SHA512 = '{$likelySubtagsData['upstreamSha512']}';

        /** @var string */
        public const SOURCE_SHA256 = '{$likelySubtagsSourceSha256}';

        private const PAYLOAD_SHA256 = '{$likelySubtagsPayloadSha256}';

        /** @var array<string, string> */
        public const MAP = {$likelySubtagsExport};

        public static function assertIntegrity(): void
        {
            /** @var bool|null \$verified */
            static \$verified = null;
            if (\$verified === true) {
                return;
            }

            \$actual = hash('sha256', json_encode([
                'format' => self::FORMAT,
                'likelySubtag' => self::MAP,
            ], JSON_THROW_ON_ERROR));
            if (!self::supportsFormat(self::FORMAT) || \$actual !== self::PAYLOAD_SHA256) {
                throw new \UnexpectedValueException('The bundled likely-subtag data is corrupt or incompatible.');
            }
            \$verified = true;
        }

        private static function supportsFormat(int \$format): bool
        {
            return \$format === 1;
        }
    }
    PHP;
$likelySubtagsGenerated .= "\n";
$likelySubtagsGenerated = MagoFormatter::format($root, 'src/Internal/Data/LikelySubtags.php', $likelySubtagsGenerated);

$target = $root . '/src/Internal/Data/LocaleAliases.php';
$likelySubtagsTarget = $root . '/src/Internal/Data/LikelySubtags.php';
$timeZoneSourcePath = $root . '/resources/data/primary-time-zones.json';
$timeZoneSource = file_get_contents($timeZoneSourcePath);
if ($timeZoneSource === false) {
    fwrite(STDERR, "Unable to read the primary time-zone projection source.\n");
    exit(1);
}
/** @var array{
 *     format: int,
 *     tzdbVersion: string,
 *     tzdbSha512: string,
 *     cldrRevision: string,
 *     cldrCoreSha512: string,
 *     zones: list<string>,
 *     links: array<string, string>,
 *     primaryIdentifiers: array<string, string>,
 *     regions: array<string, list<string>>
 * } $timeZoneData */
$timeZoneData = json_decode($timeZoneSource, true, flags: JSON_THROW_ON_ERROR);
if ($timeZoneData['format'] !== 1) {
    fwrite(STDERR, "The primary time-zone projection format is incompatible.\n");
    exit(1);
}
$timeZoneRegions = preg_replace('/[ \t]+$/m', '', Midnight\Intl\Tools\PhpExporter::export($timeZoneData['regions']));
if ($timeZoneRegions === null) {
    throw new RuntimeException('Unable to export the primary time-zone region projection.');
}
$timeZoneSourceSha256 = hash('sha256', $timeZoneSource);
$timeZonePayloadSha256 = hash('sha256', json_encode([
    'format' => $timeZoneData['format'],
    'regions' => $timeZoneData['regions'],
], JSON_THROW_ON_ERROR));
$timeZoneGenerated = <<<PHP
    <?php

    declare(strict_types=1);

    namespace Midnight\Intl\Internal\Data;

    enum PrimaryTimeZones
    {
        public const FORMAT = {$timeZoneData['format']};

        /** @var string */
        public const TZDB_VERSION = '{$timeZoneData['tzdbVersion']}';

        /** @var string */
        public const TZDB_SHA512 = '{$timeZoneData['tzdbSha512']}';

        /** @var string */
        public const CLDR_REVISION = '{$timeZoneData['cldrRevision']}';

        /** @var string */
        public const CLDR_CORE_SHA512 = '{$timeZoneData['cldrCoreSha512']}';

        /** @var string */
        public const SOURCE_SHA256 = '{$timeZoneSourceSha256}';

        private const PAYLOAD_SHA256 = '{$timeZonePayloadSha256}';
        /** @var array<string, list<string>> */
        public const REGIONS = {$timeZoneRegions};

        /** @return list<string>|null */
        public static function forRegion(?string \$region): ?array
        {
            self::assertIntegrity();

            return \$region === null ? null : (self::REGIONS[\$region] ?? []);
        }

        public static function assertIntegrity(): void
        {
            /** @var bool|null \$verified */
            static \$verified = null;
            if (\$verified === true) {
                return;
            }

            \$actual = hash('sha256', json_encode([
                'format' => self::FORMAT,
                'regions' => self::REGIONS,
            ], JSON_THROW_ON_ERROR));
            if (!self::supportsFormat(self::FORMAT) || \$actual !== self::PAYLOAD_SHA256) {
                throw new \UnexpectedValueException('The bundled time-zone data is corrupt or incompatible.');
            }
            \$verified = true;
        }

        private static function supportsFormat(int \$format): bool
        {
            return \$format === 1;
        }
    }
    PHP;
$timeZoneGenerated .= "\n";
$timeZoneGenerated = MagoFormatter::format($root, 'src/Internal/Data/PrimaryTimeZones.php', $timeZoneGenerated);
$timeZoneTarget = $root . '/src/Internal/Data/PrimaryTimeZones.php';

if (in_array('--check', $argv, true)) {
    if (
        !is_file($target)
        || file_get_contents($target) !== $generated
        || !is_file($likelySubtagsTarget)
        || file_get_contents($likelySubtagsTarget) !== $likelySubtagsGenerated
    ) {
        fwrite(STDERR, "The generated locale data is not reproducible.\n");
        exit(1);
    }
    if (!is_file($timeZoneTarget) || file_get_contents($timeZoneTarget) !== $timeZoneGenerated) {
        fwrite(STDERR, "src/Internal/Data/PrimaryTimeZones.php is not reproducible.\n");
        exit(1);
    }

    $manifestSource = file_get_contents($root . '/resources/data/manifest.json');
    if ($manifestSource === false) {
        fwrite(STDERR, "Unable to read the release data manifest.\n");
        exit(1);
    }

    /** @var array{format: int, releaseDataFingerprint: string, inputs: array{unicode: array{sha512: string}, cldr: array{sha512: string}, languageRegistry: array{sha256: string}, tzdb: array{sha512: string}}, projections: array{localeAliases: array{sourceSha256: string, generatedSha256: string}, likelySubtags: array{sourceSha256: string, generatedSha256: string}, primaryTimeZones: array{sourceSha256: string, generatedSha256: string}}, generators: array<string, string>} $manifest */
    $manifest = json_decode($manifestSource, true, flags: JSON_THROW_ON_ERROR);
    $fingerprint = hash('sha256', json_encode([
        'unicode' => $manifest['inputs']['unicode']['sha512'],
        'cldr' => $manifest['inputs']['cldr']['sha512'],
        'ianaLanguage' => $manifest['inputs']['languageRegistry']['sha256'],
        'tzdb' => $manifest['inputs']['tzdb']['sha512'],
        'localeAliasesProjection' => $sourceSha256,
        'likelySubtagsProjection' => $likelySubtagsSourceSha256,
        'primaryTimeZonesProjection' => $timeZoneSourceSha256,
    ], JSON_THROW_ON_ERROR));
    if (
        $manifest['format'] !== 4
        || $manifest['inputs']['cldr']['sha512'] !== $data['upstreamSha512']
        || $manifest['releaseDataFingerprint'] !== $fingerprint
        || $manifest['projections']['localeAliases']['sourceSha256'] !== $sourceSha256
        || $manifest['projections']['localeAliases']['generatedSha256'] !== hash('sha256', $generated)
        || $manifest['projections']['likelySubtags']['sourceSha256'] !== $likelySubtagsSourceSha256
        || $manifest['projections']['likelySubtags']['generatedSha256'] !== hash('sha256', $likelySubtagsGenerated)
        || $manifest['projections']['primaryTimeZones']['sourceSha256'] !== $timeZoneSourceSha256
        || $manifest['projections']['primaryTimeZones']['generatedSha256'] !== hash('sha256', $timeZoneGenerated)
    ) {
        fwrite(STDERR, "The release data manifest fingerprints do not match.\n");
        exit(1);
    }
    foreach ($manifest['generators'] as $path => $expectedHash) {
        if (!is_file($root . '/' . $path) || hash_file('sha256', $root . '/' . $path) !== $expectedHash) {
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
if (file_put_contents($likelySubtagsTarget, $likelySubtagsGenerated) === false) {
    fwrite(STDERR, "Unable to write the likely-subtag projection.\n");
    exit(1);
}
if (file_put_contents($timeZoneTarget, $timeZoneGenerated) === false) {
    fwrite(STDERR, "Unable to write the primary time-zone projection.\n");
    exit(1);
}
