<?php

declare(strict_types=1);

use Midnight\Intl\Tools\CldrLocalePreferenceProjector;
use Midnight\Intl\Tools\CldrXml;

require __DIR__ . '/CldrXml.php';
require __DIR__ . '/CldrLocalePreferenceProjector.php';

const CLDR_REVISION = '11299982335beb974c1c63c45265184e759c0f41';

const CLDR_CORE_SHA512 = 'de8660f5371e0fcfd03a42e3b4fc4c686ec6cd602b402f1e3d227844005a54eb7952873894443523837d5828c42874a1a267a19f91ded207a2d166144791fa62';

if ($argc !== 2) {
    fwrite(STDERR, "Usage: php tools/import-cldr-aliases.php <cldr-core.zip>\n");
    exit(1);
}

$archivePath = $argv[1];
if (hash_file('sha512', $archivePath) !== CLDR_CORE_SHA512) {
    fwrite(STDERR, "The CLDR 48.2 core archive failed its SHA-512 integrity check.\n");
    exit(1);
}

$archive = new ZipArchive();
if ($archive->open($archivePath) !== true) {
    fwrite(STDERR, "Unable to open the CLDR 48.2 core archive.\n");
    exit(1);
}

$metadata = readArchiveEntry($archive, 'common/supplemental/supplementalMetadata.xml');
$metadataWithoutComments = preg_replace('/<!--.*?-->/s', '', $metadata) ?? throw new RuntimeException(
    'Unable to remove CLDR XML comments.',
);

$languageAliases = aliases(
    $metadataWithoutComments,
    'languageAlias',
    static fn(string $value): bool => (
        preg_match('/^(?:[A-Za-z]{2,3}|[A-Za-z]{5,8})(?:_(?:[A-Za-z0-9]{5,8}|[0-9][A-Za-z0-9]{3}))*$/D', $value) === 1
    ),
    static fn(string $value): string => str_replace('_', '-', strtolower($value)),
    static fn(string $value): string => str_replace('_', '-', $value),
);
$language = [];
$compoundLanguage = [];
foreach ($languageAliases as $source => $replacement) {
    $parts = explode('-', $source, 2);
    if (isset($parts[1])) {
        $compoundLanguage[$parts[0]][$parts[1]] = $replacement;
    } else {
        $language[$source] = $replacement;
    }
}
foreach ($compoundLanguage as &$aliasesByLanguage) {
    ksort($aliasesByLanguage, SORT_STRING);
}
unset($aliasesByLanguage);
$script = aliases(
    $metadataWithoutComments,
    'scriptAlias',
    static fn(string $value): bool => preg_match('/^[A-Za-z]{4}$/D', $value) === 1,
    static fn(string $value): string => ucfirst(strtolower($value)),
    static fn(string $value): string => ucfirst(strtolower(explode(' ', $value)[0])),
);
$region = aliases(
    $metadataWithoutComments,
    'territoryAlias',
    static fn(string $value): bool => preg_match('/^(?:[A-Za-z]{2}|[0-9]{3})$/D', $value) === 1,
    static fn(string $value): string => strtoupper($value),
    static fn(string $value): string => strtoupper(explode(' ', $value)[0]),
);
$regionAlternatives = [];
preg_match_all('/<territoryAlias\s+([^>]+?)\/>/', $metadataWithoutComments, $territoryMatches, PREG_SET_ORDER);
foreach ($territoryMatches as $territoryMatch) {
    $attributes = CldrXml::attributes($territoryMatch[1]);
    $source = strtoupper($attributes['type'] ?? '');
    $replacements = preg_split('/\s+/', strtoupper($attributes['replacement'] ?? ''), flags: PREG_SPLIT_NO_EMPTY) ?: [];
    if (preg_match('/^(?:[A-Z]{2}|[0-9]{3})$/D', $source) === 1 && count($replacements) > 1) {
        $regionAlternatives[$source] = $replacements;
    }
}
ksort($regionAlternatives, SORT_STRING);

$likelySubtags = readArchiveEntry($archive, 'common/supplemental/likelySubtags.xml');
$likelySubtag = [];
$candidateRegions = array_fill_keys(array_merge(...array_values($regionAlternatives)), true);
$likelyRegion = [];
preg_match_all('/<likelySubtag\s+from="([^"]+)"\s+to="([^"]+)"/', $likelySubtags, $likelyMatches, PREG_SET_ORDER);
foreach ($likelyMatches as $likelyMatch) {
    $target = explode('_', $likelyMatch[2]);
    $target[0] = strtolower($target[0]);
    if (isset($target[1])) {
        $target[1] = ucfirst(strtolower($target[1]));
    }
    if (isset($target[2])) {
        $target[2] = strtoupper($target[2]);
    }
    $likelySubtag[str_replace('_', '-', strtolower($likelyMatch[1]))] = implode('-', $target);
    $from = explode('_', $likelyMatch[1]);
    $to = explode('_', $likelyMatch[2]);
    $regionPart = end($to);
    if (!isset($candidateRegions[$regionPart])) {
        continue;
    }
    if (count($from) === 1 || count($from) === 2 && strlen($from[1]) === 4) {
        $likelyRegion[strtolower(implode('-', $from))] = $regionPart;
    }
}
ksort($likelySubtag, SORT_STRING);
ksort($likelyRegion, SORT_STRING);
$scriptMetadata = readArchiveEntry($archive, 'common/properties/scriptMetadata.txt');
$scriptDirection = [];
foreach (preg_split('/\R/', $scriptMetadata) ?: [] as $line) {
    $dataLine = trim(explode('#', $line, 2)[0]);
    if ($dataLine === '') {
        continue;
    }

    $fields = array_map('trim', explode(';', $dataLine));
    $scriptIdentifier = $fields[0];
    $rtl = $fields[6] ?? '';
    if (preg_match('/^[A-Z][a-z]{3}$/D', $scriptIdentifier) !== 1 || !in_array($rtl, ['YES', 'NO', 'UNKNOWN'], true)) {
        throw new RuntimeException('The CLDR script metadata has an unsupported direction record.');
    }

    $scriptDirection[$scriptIdentifier] = match ($rtl) {
        'YES' => 'rtl',
        'NO' => 'ltr',
        'UNKNOWN' => null,
    };
}
ksort($scriptDirection, SORT_STRING);
$variant = aliases(
    $metadataWithoutComments,
    'variantAlias',
    static fn(string $value): bool => preg_match('/^(?:[A-Za-z0-9]{5,8}|[0-9][A-Za-z0-9]{3})$/D', $value) === 1,
    static fn(string $value): string => strtolower($value),
    static fn(string $value): string => strtolower(str_replace('_', '-', explode(' ', $value)[0])),
);
$subdivision = aliases(
    $metadataWithoutComments,
    'subdivisionAlias',
    static fn(string $value): bool => preg_match('/^[A-Za-z0-9]{3,8}$/D', $value) === 1,
    static fn(string $value): string => strtolower($value),
    static fn(string $value): string => strtolower(explode(' ', $value)[0]),
);

$key = [];
$type = [];
for ($index = 0; $index < $archive->numFiles; ++$index) {
    $name = $archive->getNameIndex($index);
    if ($name === false || preg_match('#^common/bcp47/[^/]+\.xml$#D', $name) !== 1) {
        continue;
    }
    $xml = preg_replace('/<!--.*?-->/s', '', readArchiveEntry($archive, $name)) ?? throw new RuntimeException(sprintf(
        'Unable to remove comments from %s.',
        $name,
    ));
    preg_match_all('/<key\s+([^>]+)>/', $xml, $keyMatches, PREG_SET_ORDER);
    foreach ($keyMatches as $keyMatch) {
        $attributes = CldrXml::attributes($keyMatch[1]);
        $canonicalKey = strtolower($attributes['name'] ?? '');
        if (preg_match('/^[a-z0-9][a-z]$/D', $canonicalKey) !== 1) {
            continue;
        }
        foreach (preg_split('/\s+/', $attributes['alias'] ?? '', flags: PREG_SPLIT_NO_EMPTY) ?: [] as $alias) {
            $alias = strtolower($alias);
            if (preg_match('/^[a-z0-9][a-z]$/D', $alias) === 1) {
                $key[$alias] = $canonicalKey;
            }
        }

        $keyStart = strpos($xml, $keyMatch[0]);
        $keyEnd = $keyStart === false ? false : strpos($xml, '</key>', $keyStart);
        if ($keyStart === false || $keyEnd === false) {
            continue;
        }
        $keyBody = substr($xml, $keyStart + strlen($keyMatch[0]), $keyEnd - $keyStart - strlen($keyMatch[0]));
        preg_match_all('/<type\s+([^>]+?)(?:\/>|>)/', $keyBody, $typeMatches, PREG_SET_ORDER);
        foreach ($typeMatches as $typeMatch) {
            $typeAttributes = CldrXml::attributes($typeMatch[1]);
            $canonicalType = strtolower($typeAttributes['preferred'] ?? $typeAttributes['name'] ?? '');
            if (!CldrXml::isUnicodeType($canonicalType)) {
                continue;
            }
            foreach (preg_split('/\s+/', $typeAttributes['alias'] ?? '', flags: PREG_SPLIT_NO_EMPTY) ?: [] as $alias) {
                $alias = strtolower($alias);
                if (CldrXml::isUnicodeType($alias)) {
                    $type[$canonicalKey][$alias] = $canonicalType;
                }
            }
            $nameAlias = strtolower($typeAttributes['name'] ?? '');
            if ($nameAlias !== $canonicalType && CldrXml::isUnicodeType($nameAlias)) {
                $type[$canonicalKey][$nameAlias] = $canonicalType;
            }
        }
    }
}

$supplementalData = readArchiveEntry($archive, 'common/supplemental/supplementalData.xml');
$calendarBcp47 = readArchiveEntry($archive, 'common/bcp47/calendar.xml');
$calendarProjection = CldrLocalePreferenceProjector::calendars($calendarBcp47, $supplementalData);
$availableCalendars = $calendarProjection['available'];
$calendarPreferences = $calendarProjection['preferences'];
$hourCyclePreferences = CldrLocalePreferenceProjector::hourCycles($supplementalData);

$archive->close();

ksort($key, SORT_STRING);
ksort($type, SORT_STRING);
foreach ($type as &$aliasesByKey) {
    ksort($aliasesByKey, SORT_STRING);
}
unset($aliasesByKey);

$projection = [
    'format' => 3,
    'cldrRevision' => CLDR_REVISION,
    'upstreamSha512' => CLDR_CORE_SHA512,
    'sourceEntries' => [
        'common/supplemental/supplementalMetadata.xml' => hash('sha256', $metadata),
        'common/supplemental/likelySubtags.xml' => hash('sha256', $likelySubtags),
    ],
    'language' => $language,
    'script' => $script,
    'compoundLanguage' => $compoundLanguage,
    'region' => $region,
    'regionAlternatives' => $regionAlternatives,
    'likelyRegion' => $likelyRegion,
    'variant' => $variant,
    'subdivision' => $subdivision,
    'key' => $key,
    'type' => $type,
];
$likelySubtagsProjection = [
    'format' => 1,
    'cldrRevision' => CLDR_REVISION,
    'upstreamSha512' => CLDR_CORE_SHA512,
    'sourceEntries' => [
        'common/supplemental/likelySubtags.xml' => hash('sha256', $likelySubtags),
    ],
    'likelySubtag' => $likelySubtag,
];
$scriptDirectionsProjection = [
    'format' => 1,
    'cldrRevision' => CLDR_REVISION,
    'upstreamSha512' => CLDR_CORE_SHA512,
    'sourceEntries' => [
        'common/properties/scriptMetadata.txt' => hash('sha256', $scriptMetadata),
    ],
    'scriptDirection' => $scriptDirection,
];
$calendarPreferencesProjection = [
    'format' => 1,
    'cldrRevision' => CLDR_REVISION,
    'upstreamSha512' => CLDR_CORE_SHA512,
    'sourceEntries' => [
        'common/bcp47/calendar.xml' => hash('sha256', $calendarBcp47),
        'common/supplemental/supplementalData.xml' => hash('sha256', $supplementalData),
    ],
    'available' => $availableCalendars,
    'preferences' => $calendarPreferences,
];
$hourCyclePreferencesProjection = [
    'format' => 1,
    'cldrRevision' => CLDR_REVISION,
    'upstreamSha512' => CLDR_CORE_SHA512,
    'sourceEntries' => [
        'common/supplemental/supplementalData.xml' => hash('sha256', $supplementalData),
    ],
    'preferences' => $hourCyclePreferences,
];

file_put_contents(
    dirname(__DIR__) . '/resources/data/locale-aliases.json',
    json_encode($projection, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n",
);
file_put_contents(
    dirname(__DIR__) . '/resources/data/likely-subtags.json',
    json_encode($likelySubtagsProjection, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n",
);
file_put_contents(
    dirname(__DIR__) . '/resources/data/script-directions.json',
    json_encode($scriptDirectionsProjection, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n",
);
file_put_contents(
    dirname(__DIR__) . '/resources/data/calendar-preferences.json',
    json_encode($calendarPreferencesProjection, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)
        . "\n",
);
file_put_contents(
    dirname(__DIR__) . '/resources/data/hour-cycle-preferences.json',
    json_encode($hourCyclePreferencesProjection, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)
        . "\n",
);

function readArchiveEntry(ZipArchive $archive, string $name): string
{
    $contents = $archive->getFromName($name);
    if ($contents === false) {
        throw new RuntimeException(sprintf('The CLDR archive is missing %s.', $name));
    }

    return $contents;
}

/**
 * @param Closure(string): bool $accept
 * @param Closure(string): string $normalizeKey
 * @param Closure(string): string $normalizeReplacement
 * @return array<string, string>
 */
function aliases(
    string $xml,
    string $element,
    Closure $accept,
    Closure $normalizeKey,
    Closure $normalizeReplacement,
): array {
    preg_match_all(sprintf('/<%s\s+([^>]+?)\/>/', preg_quote($element, '/')), $xml, $matches, PREG_SET_ORDER);
    $aliases = [];
    foreach ($matches as $match) {
        $attributes = CldrXml::attributes($match[1]);
        $source = $attributes['type'] ?? '';
        $replacement = $attributes['replacement'] ?? '';
        if ($accept($source) && $replacement !== '') {
            $aliases[$normalizeKey($source)] = $normalizeReplacement($replacement);
        }
    }
    ksort($aliases, SORT_STRING);

    return $aliases;
}
