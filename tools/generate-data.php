<?php

declare(strict_types=1);

use Midnight\Intl\Tools\GeneratedDataArtifact;
use Midnight\Intl\Tools\MagoFormatter;
use Midnight\Intl\Tools\NumberingSystemsProjectionGenerator;

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
$collationSourcePath = $root . '/resources/data/collations.json';
$collationSource = file_get_contents($collationSourcePath);
if ($collationSource === false) {
    fwrite(STDERR, "Unable to read the collation availability projection source.\n");
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
 * @param list<array{
 *     constant: string,
 *     payloadKey: string,
 *     type: string,
 *     value: array<array-key, mixed>
 * }> $fields
 */
function generatePreferenceClass(
    string $root,
    string $class,
    string $errorSubject,
    int $format,
    string $cldrRevision,
    string $upstreamSha512,
    string $source,
    array $fields,
): string {
    $exports = '';
    $payload = ['format' => $format];
    foreach ($fields as $field) {
        $export = preg_replace('/[ \t]+$/m', '', Midnight\Intl\Tools\PhpExporter::export($field['value']));
        if ($export === null) {
            throw new RuntimeException(sprintf('Unable to export the %s projection.', $field['payloadKey']));
        }
        $exports .= sprintf(
            "\n    /** @var %s */\n    public const %s = %s;\n",
            $field['type'],
            $field['constant'],
            $export,
        );
        $payload[$field['payloadKey']] = $field['value'];
    }
    $sourceSha256 = hash('sha256', $source);
    $payloadSha256 = hash('sha256', json_encode($payload, JSON_THROW_ON_ERROR));
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
    foreach ($fields as $field) {
        $generated .= sprintf("            '%s' => self::%s,\n", $field['payloadKey'], $field['constant']);
    }
    $generated .= <<<'PHP'
                ];
            }
        }
        PHP;
    $generated .= "\n";

    return MagoFormatter::format($root, 'src/Internal/Data/' . $class . '.php', $generated);
}

$calendarSource = file_get_contents($root . '/resources/data/calendar-preferences.json');
if ($calendarSource === false) {
    throw new RuntimeException('Unable to read the calendar-preferences projection source.');
}
/** @var array{format: int, cldrRevision: string, upstreamSha512: string, sourceEntries: array<string, string>, available: list<string>, preferences: array<array-key, list<string>>} $calendarData */
$calendarData = json_decode($calendarSource, true, flags: JSON_THROW_ON_ERROR);
if ($calendarData['format'] !== 1) {
    throw new RuntimeException('The calendar-preferences projection format is incompatible.');
}
$calendarGenerated = generatePreferenceClass(
    $root,
    'CalendarPreferences',
    'calendar preference',
    $calendarData['format'],
    $calendarData['cldrRevision'],
    $calendarData['upstreamSha512'],
    $calendarSource,
    [
        [
            'constant' => 'AVAILABLE',
            'payloadKey' => 'available',
            'type' => 'list<string>',
            'value' => $calendarData['available'],
        ],
        [
            'constant' => 'PREFERENCES',
            'payloadKey' => 'preferences',
            'type' => 'array<array-key, list<string>>',
            'value' => $calendarData['preferences'],
        ],
    ],
);
$hourCycleSource = file_get_contents($root . '/resources/data/hour-cycle-preferences.json');
if ($hourCycleSource === false) {
    throw new RuntimeException('Unable to read the hour-cycle-preferences projection source.');
}
/** @var array{format: int, cldrRevision: string, upstreamSha512: string, sourceEntries: array<string, string>, preferences: array<array-key, list<string>>} $hourCycleData */
$hourCycleData = json_decode($hourCycleSource, true, flags: JSON_THROW_ON_ERROR);
if ($hourCycleData['format'] !== 1) {
    throw new RuntimeException('The hour-cycle-preferences projection format is incompatible.');
}
$hourCycleGenerated = generatePreferenceClass(
    $root,
    'HourCyclePreferences',
    'hour-cycle preference',
    $hourCycleData['format'],
    $hourCycleData['cldrRevision'],
    $hourCycleData['upstreamSha512'],
    $hourCycleSource,
    [[
        'constant' => 'PREFERENCES',
        'payloadKey' => 'preferences',
        'type' => 'array<array-key, list<string>>',
        'value' => $hourCycleData['preferences'],
    ]],
);
$weekInfoSource = file_get_contents($root . '/resources/data/week-info.json');
if ($weekInfoSource === false) {
    throw new RuntimeException('Unable to read the week-info projection source.');
}
/** @var array{format: int, cldrRevision: string, upstreamSha512: string, sourceEntries: array<string, string>, firstDay: array<string, int>, weekendStart: array<string, int>, weekendEnd: array<string, int>} $weekInfoData */
$weekInfoData = json_decode($weekInfoSource, true, flags: JSON_THROW_ON_ERROR);
if ($weekInfoData['format'] !== 1) {
    throw new RuntimeException('The week-info projection format is incompatible.');
}
$weekInfoGenerated = generatePreferenceClass(
    $root,
    'WeekInfoData',
    'week-information',
    $weekInfoData['format'],
    $weekInfoData['cldrRevision'],
    $weekInfoData['upstreamSha512'],
    $weekInfoSource,
    [
        [
            'constant' => 'FIRST_DAY',
            'payloadKey' => 'firstDay',
            'type' => 'array<string, int<1, 7>>',
            'value' => $weekInfoData['firstDay'],
        ],
        [
            'constant' => 'WEEKEND_START',
            'payloadKey' => 'weekendStart',
            'type' => 'array<string, int<1, 7>>',
            'value' => $weekInfoData['weekendStart'],
        ],
        [
            'constant' => 'WEEKEND_END',
            'payloadKey' => 'weekendEnd',
            'type' => 'array<string, int<1, 7>>',
            'value' => $weekInfoData['weekendEnd'],
        ],
    ],
);

/** @var array{
 *     format: int,
 *     cldrVersion: string,
 *     cldrRevision: string,
 *     cldrCoreSha512: string,
 *     root: list<string>,
 *     locales: array<string, list<string>>
 * } $collationData */
$collationData = json_decode($collationSource, true, flags: JSON_THROW_ON_ERROR);
if ($collationData['format'] !== 1) {
    fwrite(STDERR, "The collation availability projection format is incompatible.\n");
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

$collationRoot = preg_replace('/[ \t]+$/m', '', Midnight\Intl\Tools\PhpExporter::export($collationData['root']));
$collationLocales = preg_replace('/[ \t]+$/m', '', Midnight\Intl\Tools\PhpExporter::export($collationData['locales']));
if ($collationRoot === null || $collationLocales === null) {
    throw new RuntimeException('Unable to export the collation availability projection.');
}
$collationSourceSha256 = hash('sha256', $collationSource);
$collationPayloadSha256 = hash('sha256', json_encode([
    'format' => $collationData['format'],
    'root' => $collationData['root'],
    'locales' => $collationData['locales'],
], JSON_THROW_ON_ERROR));
$collationGenerated = <<<PHP
    <?php

    declare(strict_types=1);

    namespace Midnight\Intl\Internal\Data;

    enum CollationAvailability
    {
        public const FORMAT = {$collationData['format']};

        /** @var string */
        public const CLDR_VERSION = '{$collationData['cldrVersion']}';

        /** @var string */
        public const CLDR_REVISION = '{$collationData['cldrRevision']}';

        /** @var string */
        public const CLDR_CORE_SHA512 = '{$collationData['cldrCoreSha512']}';

        /** @var string */
        public const SOURCE_SHA256 = '{$collationSourceSha256}';

        private const PAYLOAD_SHA256 = '{$collationPayloadSha256}';

        /** @var list<string> */
        public const ROOT = {$collationRoot};

        /** @var array<string, list<string>> */
        public const LOCALES = {$collationLocales};

        /** @return list<string> */
        public static function forLocale(string \$locale): array
        {
            self::assertIntegrity();
            while (\$locale !== '') {
                if (isset(self::LOCALES[\$locale])) {
                    return self::LOCALES[\$locale];
                }
                \$separator = strrpos(\$locale, '-');
                \$locale = \$separator === false ? '' : substr(\$locale, 0, \$separator);
            }

            return self::ROOT;
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
                'root' => self::ROOT,
                'locales' => self::LOCALES,
            ], JSON_THROW_ON_ERROR));
            if (!self::supportsFormat(self::FORMAT) || \$actual !== self::PAYLOAD_SHA256) {
                throw new \UnexpectedValueException('The bundled collation data is corrupt or incompatible.');
            }
            \$verified = true;
        }

        private static function supportsFormat(int \$format): bool
        {
            return \$format === 1;
        }
    }
    PHP;
$collationGenerated .= "\n";
$collationGenerated = MagoFormatter::format($root, 'src/Internal/Data/CollationAvailability.php', $collationGenerated);

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
$numberingSystemSourcePath = $root . '/resources/data/numbering-systems.json';
$numberingSystemSource = file_get_contents($numberingSystemSourcePath);
if ($numberingSystemSource === false) {
    fwrite(STDERR, "Unable to read the numbering-system projection source.\n");
    exit(1);
}
$numberingSystemProjection = NumberingSystemsProjectionGenerator::generate($root, $numberingSystemSource);
$numberingSystemArtifact = new GeneratedDataArtifact(
    $numberingSystemSource,
    $numberingSystemProjection['generated'],
    $numberingSystemProjection['label'],
    $numberingSystemProjection['target'],
);
$artifacts = [
    'localeAliases' => new GeneratedDataArtifact(
        $source,
        $generated,
        'locale alias',
        'src/Internal/Data/LocaleAliases.php',
    ),
    ...$mapArtifacts,
    'collations' => new GeneratedDataArtifact(
        $collationSource,
        $collationGenerated,
        'collation availability',
        'src/Internal/Data/CollationAvailability.php',
    ),
    'calendarPreferences' => new GeneratedDataArtifact(
        $calendarSource,
        $calendarGenerated,
        'calendar preference',
        'src/Internal/Data/CalendarPreferences.php',
    ),
    'hourCyclePreferences' => new GeneratedDataArtifact(
        $hourCycleSource,
        $hourCycleGenerated,
        'hour-cycle preference',
        'src/Internal/Data/HourCyclePreferences.php',
    ),
    'weekInfo' => new GeneratedDataArtifact(
        $weekInfoSource,
        $weekInfoGenerated,
        'week-information',
        'src/Internal/Data/WeekInfoData.php',
    ),
    'primaryTimeZones' => new GeneratedDataArtifact(
        $timeZoneSource,
        $timeZoneGenerated,
        'primary time-zone',
        'src/Internal/Data/PrimaryTimeZones.php',
    ),
    'numberingSystems' => $numberingSystemArtifact,
];
if (in_array('--check', $argv, true)) {
    foreach ($artifacts as $artifact) {
        if (!$artifact->isReproducible($root)) {
            fwrite(STDERR, sprintf("The generated %s projection is not reproducible.\n", $artifact->label));
            exit(1);
        }
    }
    $manifestSource = file_get_contents($root . '/resources/data/manifest.json');
    if ($manifestSource === false) {
        fwrite(STDERR, "Unable to read the release data manifest.\n");
        exit(1);
    }

    /** @var array{format: int, releaseDataFingerprint: string, inputs: array{unicode: array{sha512: string}, cldr: array{sha512: string}, languageRegistry: array{sha256: string}, tzdb: array{sha512: string}}, projections: array{localeAliases: array{sourceSha256: string, generatedSha256: string}, likelySubtags: array{sourceSha256: string, generatedSha256: string}, scriptDirections: array{sourceSha256: string, generatedSha256: string}, calendarPreferences: array{sourceSha256: string, generatedSha256: string}, hourCyclePreferences: array{sourceSha256: string, generatedSha256: string}, weekInfo: array{sourceSha256: string, generatedSha256: string}, collations: array{sourceSha256: string, generatedSha256: string}, primaryTimeZones: array{sourceSha256: string, generatedSha256: string}, numberingSystems: array{sourceSha256: string, generatedSha256: string}}, generators: array<string, string>} $manifest */
    $manifest = json_decode($manifestSource, true, flags: JSON_THROW_ON_ERROR);
    $fingerprint = hash('sha256', json_encode([
        'unicode' => $manifest['inputs']['unicode']['sha512'],
        'cldr' => $manifest['inputs']['cldr']['sha512'],
        'ianaLanguage' => $manifest['inputs']['languageRegistry']['sha256'],
        'tzdb' => $manifest['inputs']['tzdb']['sha512'],
        'localeAliasesProjection' => $sourceSha256,
        'likelySubtagsProjection' => $mapArtifacts['likelySubtags']->sourceSha256,
        'scriptDirectionsProjection' => $mapArtifacts['scriptDirections']->sourceSha256,
        'calendarPreferencesProjection' => $artifacts['calendarPreferences']->sourceSha256,
        'hourCyclePreferencesProjection' => $artifacts['hourCyclePreferences']->sourceSha256,
        'weekInfoProjection' => $artifacts['weekInfo']->sourceSha256,
        'collationsProjection' => $artifacts['collations']->sourceSha256,
        'primaryTimeZonesProjection' => $artifacts['primaryTimeZones']->sourceSha256,
        'numberingSystemsProjection' => $numberingSystemArtifact->sourceSha256,
    ], JSON_THROW_ON_ERROR));
    if (
        $manifest['format'] !== 7
        || $manifest['inputs']['cldr']['sha512'] !== $data['upstreamSha512']
        || $manifest['inputs']['cldr']['sha512'] !== $numberingSystemProjection['upstreamSha512']
        || $manifest['releaseDataFingerprint'] !== $fingerprint
    ) {
        fwrite(STDERR, "The release data manifest fingerprints do not match.\n");
        exit(1);
    }
    foreach ($artifacts as $name => $artifact) {
        if (!$artifact->matchesManifest($manifest['projections'][$name])) {
            fwrite(STDERR, sprintf("The release data manifest fingerprint does not match for %s.\n", $name));
            exit(1);
        }
    }
    foreach ($manifest['generators'] as $path => $expectedHash) {
        if (!is_file($root . '/' . $path) || hash_file('sha256', $root . '/' . $path) !== $expectedHash) {
            fwrite(STDERR, sprintf("The release data generator fingerprint does not match for %s.\n", $path));
            exit(1);
        }
    }

    exit(0);
}

foreach ($artifacts as $artifact) {
    if (!$artifact->write($root)) {
        fwrite(STDERR, sprintf("Unable to write the %s projection.\n", $artifact->label));
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
 */
function generateMapProjection(string $root, array $definition): GeneratedDataArtifact
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

    return new GeneratedDataArtifact($definition['source'], $generated, $label, $definition['target']);
}
