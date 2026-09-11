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
$scriptDirectionsSourcePath = $root . '/resources/data/script-directions.json';
$scriptDirectionsSource = file_get_contents($scriptDirectionsSourcePath);
if ($scriptDirectionsSource === false) {
    fwrite(STDERR, "Unable to read the script-direction projection source.\n");
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

/** @var array{
 *     format: int,
 *     cldrRevision: string,
 *     upstreamSha512: string,
 *     sourceEntries: array<string, string>,
 *     scriptDirection: array<string, 'ltr'|'rtl'|null>
 * } $scriptDirectionsData */
$scriptDirectionsData = json_decode($scriptDirectionsSource, true, flags: JSON_THROW_ON_ERROR);
if ($scriptDirectionsData['format'] !== 1) {
    fwrite(STDERR, "The script-direction projection format is incompatible.\n");
    exit(1);
}

/**
 * @return array{source: string, data: array{format: int, cldrRevision: string, upstreamSha512: string, sourceEntries: array<string, string>, preferences: array<array-key, list<string>>}&array<string, mixed>}
 */
function readPreferenceProjection(string $root, string $name): array
{
    $source = file_get_contents($root . '/resources/data/' . $name . '.json');
    if ($source === false) {
        throw new RuntimeException(sprintf('Unable to read the %s projection source.', $name));
    }
    /** @var array{format: int, cldrRevision: string, upstreamSha512: string, sourceEntries: array<string, string>, preferences: array<array-key, list<string>>}&array<string, mixed> $data */
    $data = json_decode($source, true, flags: JSON_THROW_ON_ERROR);
    if ($data['format'] !== 1) {
        throw new RuntimeException(sprintf('The %s projection format is incompatible.', $name));
    }

    return ['source' => $source, 'data' => $data];
}

/**
 * @param array{format: int, cldrRevision: string, upstreamSha512: string}&array<string, mixed> $data
 * @param list<array{string, string}> $constants
 */
function generatePreferenceClass(
    string $root,
    string $class,
    string $errorSubject,
    array $data,
    string $source,
    array $constants,
): string {
    $exports = '';
    $payload = ['format' => $data['format']];
    foreach ($constants as [$constant, $field]) {
        $export = preg_replace('/[ \t]+$/m', '', Midnight\Intl\Tools\PhpExporter::export($data[$field]));
        if ($export === null) {
            throw new RuntimeException(sprintf('Unable to export the %s projection.', $field));
        }
        $type = $field === 'available' ? 'list<string>' : 'array<array-key, list<string>>';
        $exports .= sprintf("\n    /** @var %s */\n    public const %s = %s;\n", $type, $constant, $export);
        $payload[$field] = $data[$field];
    }
    $sourceSha256 = hash('sha256', $source);
    $payloadSha256 = hash('sha256', json_encode($payload, JSON_THROW_ON_ERROR));
    $generated = <<<PHP
        <?php

        declare(strict_types=1);

        namespace Midnight\Intl\Internal\Data;

        enum {$class}
        {
            public const FORMAT = {$data['format']};

            /** @var string */
            public const CLDR_REVISION = '{$data['cldrRevision']}';

            /** @var string */
            public const CLDR_CORE_SHA512 = '{$data['upstreamSha512']}';

            /** @var string */
            public const SOURCE_SHA256 = '{$sourceSha256}';

            private const PAYLOAD_SHA256 = '{$payloadSha256}';
        {$exports}
            /** @psalm-api */
            public static function assertIntegrity(): void
            {
                /** @var bool|null \$verified */
                static \$verified = null;
                if (\$verified === true) {
                    return;
                }

                \$actual = hash('sha256', json_encode(self::payload(), JSON_THROW_ON_ERROR));
                if (\$actual !== self::PAYLOAD_SHA256) {
                    throw new \UnexpectedValueException('The bundled {$errorSubject} data is corrupt or incompatible.');
                }
                \$verified = true;
            }

            /** @return array<string, mixed> */
            private static function payload(): array
            {
                return [
                    'format' => self::FORMAT,
        PHP;
    foreach ($constants as [$constant, $field]) {
        $generated .= sprintf("            '%s' => self::%s,\n", $field, $constant);
    }
    $generated .= <<<'PHP'
                ];
            }
        }
        PHP;
    $generated .= "\n";

    return MagoFormatter::format($root, 'src/Internal/Data/' . $class . '.php', $generated);
}

$calendarProjection = readPreferenceProjection($root, 'calendar-preferences');
$calendarData = $calendarProjection['data'];
$calendarSource = $calendarProjection['source'];
$calendarGenerated = generatePreferenceClass(
    $root,
    'CalendarPreferences',
    'calendar preference',
    $calendarData,
    $calendarSource,
    [
        ['AVAILABLE',   'available'],
        ['PREFERENCES', 'preferences'],
    ],
);
$calendarTarget = $root . '/src/Internal/Data/CalendarPreferences.php';

$hourCycleProjection = readPreferenceProjection($root, 'hour-cycle-preferences');
$hourCycleData = $hourCycleProjection['data'];
$hourCycleSource = $hourCycleProjection['source'];
$hourCycleGenerated = generatePreferenceClass(
    $root,
    'HourCyclePreferences',
    'hour-cycle preference',
    $hourCycleData,
    $hourCycleSource,
    [['PREFERENCES', 'preferences']],
);
$hourCycleTarget = $root . '/src/Internal/Data/HourCyclePreferences.php';

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

$mapArtifacts = [];
foreach ([
    'likelySubtags' => [
        'source' => $likelySubtagsSource,
        'data' => $likelySubtagsData,
        'field' => 'likelySubtag',
        'class' => 'LikelySubtags',
        'type' => 'string',
        'label' => 'likely-subtag',
        'target' => 'src/Internal/Data/LikelySubtags.php',
    ],
    'scriptDirections' => [
        'source' => $scriptDirectionsSource,
        'data' => $scriptDirectionsData,
        'field' => 'scriptDirection',
        'class' => 'ScriptDirections',
        'type' => "'ltr'|'rtl'|null",
        'label' => 'script-direction',
        'target' => 'src/Internal/Data/ScriptDirections.php',
    ],
] as $name => $definition) {
    $mapArtifacts[$name] = generateMapProjection($root, $definition);
}

$target = $root . '/src/Internal/Data/LocaleAliases.php';
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
    $generatedFilesMatch = is_file($target) && file_get_contents($target) === $generated;
    foreach ($mapArtifacts as $artifact) {
        $generatedFilesMatch =
            $generatedFilesMatch
            && is_file($root . '/' . $artifact['target'])
            && file_get_contents($root . '/' . $artifact['target']) === $artifact['generated'];
    }
    if (!$generatedFilesMatch) {
        fwrite(STDERR, "The generated locale data is not reproducible.\n");
        exit(1);
    }
    if (!is_file($timeZoneTarget) || file_get_contents($timeZoneTarget) !== $timeZoneGenerated) {
        fwrite(STDERR, "src/Internal/Data/PrimaryTimeZones.php is not reproducible.\n");
        exit(1);
    }
    if (!is_file($calendarTarget) || file_get_contents($calendarTarget) !== $calendarGenerated) {
        fwrite(STDERR, "src/Internal/Data/CalendarPreferences.php is not reproducible.\n");
        exit(1);
    }
    if (!is_file($hourCycleTarget) || file_get_contents($hourCycleTarget) !== $hourCycleGenerated) {
        fwrite(STDERR, "src/Internal/Data/HourCyclePreferences.php is not reproducible.\n");
        exit(1);
    }

    $manifestSource = file_get_contents($root . '/resources/data/manifest.json');
    if ($manifestSource === false) {
        fwrite(STDERR, "Unable to read the release data manifest.\n");
        exit(1);
    }

    /** @var array{format: int, releaseDataFingerprint: string, inputs: array{unicode: array{sha512: string}, cldr: array{sha512: string}, languageRegistry: array{sha256: string}, tzdb: array{sha512: string}}, projections: array{localeAliases: array{sourceSha256: string, generatedSha256: string}, likelySubtags: array{sourceSha256: string, generatedSha256: string}, scriptDirections: array{sourceSha256: string, generatedSha256: string}, calendarPreferences: array{sourceSha256: string, generatedSha256: string}, hourCyclePreferences: array{sourceSha256: string, generatedSha256: string}, primaryTimeZones: array{sourceSha256: string, generatedSha256: string}}, generators: array<string, string>} $manifest */
    $manifest = json_decode($manifestSource, true, flags: JSON_THROW_ON_ERROR);
    $fingerprint = hash('sha256', json_encode([
        'unicode' => $manifest['inputs']['unicode']['sha512'],
        'cldr' => $manifest['inputs']['cldr']['sha512'],
        'ianaLanguage' => $manifest['inputs']['languageRegistry']['sha256'],
        'tzdb' => $manifest['inputs']['tzdb']['sha512'],
        'localeAliasesProjection' => $sourceSha256,
        'likelySubtagsProjection' => $mapArtifacts['likelySubtags']['sourceSha256'],
        'scriptDirectionsProjection' => $mapArtifacts['scriptDirections']['sourceSha256'],
        'calendarPreferencesProjection' => hash('sha256', $calendarSource),
        'hourCyclePreferencesProjection' => hash('sha256', $hourCycleSource),
        'primaryTimeZonesProjection' => $timeZoneSourceSha256,
    ], JSON_THROW_ON_ERROR));
    if (
        $manifest['format'] !== 5
        || $manifest['inputs']['cldr']['sha512'] !== $data['upstreamSha512']
        || $manifest['releaseDataFingerprint'] !== $fingerprint
        || $manifest['projections']['localeAliases']['sourceSha256'] !== $sourceSha256
        || $manifest['projections']['localeAliases']['generatedSha256'] !== hash('sha256', $generated)
        || $manifest['projections']['likelySubtags']['sourceSha256'] !== $mapArtifacts['likelySubtags']['sourceSha256']
        || $manifest['projections']['likelySubtags']['generatedSha256'] !== hash(
            'sha256',
            $mapArtifacts['likelySubtags']['generated'],
        )
        || $manifest['projections']['scriptDirections']['sourceSha256']
            !== $mapArtifacts['scriptDirections']['sourceSha256']
        || $manifest['projections']['scriptDirections']['generatedSha256'] !== hash(
            'sha256',
            $mapArtifacts['scriptDirections']['generated'],
        )
        || $manifest['projections']['calendarPreferences']['sourceSha256'] !== hash('sha256', $calendarSource)
        || $manifest['projections']['calendarPreferences']['generatedSha256'] !== hash('sha256', $calendarGenerated)
        || $manifest['projections']['hourCyclePreferences']['sourceSha256'] !== hash('sha256', $hourCycleSource)
        || $manifest['projections']['hourCyclePreferences']['generatedSha256'] !== hash('sha256', $hourCycleGenerated)
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
foreach ($mapArtifacts as $artifact) {
    if (file_put_contents($root . '/' . $artifact['target'], $artifact['generated']) === false) {
        fwrite(STDERR, sprintf("Unable to write the %s projection.\n", $artifact['label']));
        exit(1);
    }
}

/**
 * @param array{
 *     source: string,
 *     data: array<string, mixed>,
 *     field: string,
 *     class: string,
 *     type: string,
 *     label: string,
 *     target: string
 * } $definition
 * @return array{sourceSha256: string, generated: string, label: string, target: string}
 */
function generateMapProjection(string $root, array $definition): array
{
    $data = $definition['data'];
    $field = $definition['field'];
    $format = $data['format'] ?? null;
    $cldrRevision = $data['cldrRevision'] ?? null;
    $upstreamSha512 = $data['upstreamSha512'] ?? null;
    $map = $data[$field] ?? null;
    if (!is_int($format) || !is_string($cldrRevision) || !is_string($upstreamSha512) || !is_array($map)) {
        throw new RuntimeException(sprintf('The %s projection has an invalid data shape.', $definition['label']));
    }

    $sourceSha256 = hash('sha256', $definition['source']);
    $payloadSha256 = hash('sha256', json_encode([
        'format' => $format,
        $field => $map,
    ], JSON_THROW_ON_ERROR));
    $export = preg_replace('/[ \t]+$/m', '', Midnight\Intl\Tools\PhpExporter::export($map));
    if ($export === null) {
        throw new RuntimeException(sprintf('Unable to export the %s projection.', $definition['label']));
    }
    $class = $definition['class'];
    $type = $definition['type'];
    $label = $definition['label'];
    $generated = <<<PHP
        <?php

        declare(strict_types=1);

        namespace Midnight\Intl\Internal\Data;

        enum {$class}
        {
            public const FORMAT = {$format};

            /** @var string */
            public const CLDR_REVISION = '{$cldrRevision}';

            /** @var string */
            public const CLDR_CORE_SHA512 = '{$upstreamSha512}';

            /** @var string */
            public const SOURCE_SHA256 = '{$sourceSha256}';

            private const PAYLOAD_SHA256 = '{$payloadSha256}';

            /** @var array<string, {$type}> */
            public const MAP = {$export};

            public static function assertIntegrity(): void
            {
                /** @var bool|null \$verified */
                static \$verified = null;
                if (\$verified === true) {
                    return;
                }

                \$actual = hash('sha256', json_encode([
                    'format' => self::FORMAT,
                    '{$field}' => self::MAP,
                ], JSON_THROW_ON_ERROR));
                if (!self::supportsFormat(self::FORMAT) || \$actual !== self::PAYLOAD_SHA256) {
                    throw new \UnexpectedValueException('The bundled {$label} data is corrupt or incompatible.');
                }
                \$verified = true;
            }

            private static function supportsFormat(int \$format): bool
            {
                return \$format === 1;
            }
        }
        PHP;
    $generated = MagoFormatter::format($root, $definition['target'], $generated . "\n");

    return [
        'sourceSha256' => $sourceSha256,
        'generated' => $generated,
        'label' => $label,
        'target' => $definition['target'],
    ];
}

if (file_put_contents($timeZoneTarget, $timeZoneGenerated) === false) {
    fwrite(STDERR, "Unable to write the primary time-zone projection.\n");
    exit(1);
}
if (file_put_contents($calendarTarget, $calendarGenerated) === false) {
    fwrite(STDERR, "Unable to write the calendar preference projection.\n");
    exit(1);
}
if (file_put_contents($hourCycleTarget, $hourCycleGenerated) === false) {
    fwrite(STDERR, "Unable to write the hour-cycle preference projection.\n");
    exit(1);
}
