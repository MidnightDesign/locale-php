<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$sourcePath = $root.'/resources/data/locale-aliases.json';
$source = file_get_contents($sourcePath);

if ($source === false) {
    fwrite(STDERR, "Unable to read the locale alias projection source.\n");
    exit(1);
}

/** @var array{cldrRevision: string, upstreamSha256: string, language: array<string, string>, script: array<string, string>, region: array<string, string>} $data */
$data = json_decode($source, true, flags: JSON_THROW_ON_ERROR);

$language = var_export($data['language'], true);
$script = var_export($data['script'], true);
$region = var_export($data['region'], true);
$generated = <<<PHP
<?php

declare(strict_types=1);

namespace Midnight\\Intl\\Internal\\Data;

enum LocaleAliases
{
    /** @var string */
    public const CLDR_REVISION = '{$data['cldrRevision']}';

    /** @var array<string, string> */
    public const LANGUAGE = {$language};

    /** @var array<string, string> */
    public const SCRIPT = {$script};

    /** @var array<int|string, string> */
    public const REGION = {$region};
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

    /** @var array{cldr: array{upstreamSha256: string, sourceSha256: string, projectionSha256: string}} $manifest */
    $manifest = json_decode($manifestSource, true, flags: JSON_THROW_ON_ERROR);
    if ($manifest['cldr']['upstreamSha256'] !== $data['upstreamSha256']
        || $manifest['cldr']['sourceSha256'] !== hash('sha256', $source)
        || $manifest['cldr']['projectionSha256'] !== hash('sha256', $generated)) {
        fwrite(STDERR, "The release data manifest fingerprints do not match.\n");
        exit(1);
    }

    exit(0);
}

file_put_contents($target, $generated);
