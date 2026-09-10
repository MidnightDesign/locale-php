<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Ci;

/**
 * @phpstan-type BranchTrace array{mode: string, intlLoaded: bool, eligibleNativePaths: list<string>, exercisedNativePaths: list<string>, fallbackReason: string|null}
 * @phpstan-type ActionPins array{checkout: string, setupPhp: string, uploadArtifact: string, downloadArtifact: string}
 * @phpstan-type Evidence array{
 *     format: int,
 *     runner: array{label: string, image: string|null, imageVersion: string|null, osFamily: string, architecture: string},
 *     runtime: array{requestedPhp: string, actualPhp: string, versionId: int, integerSize: int, threadSafe: bool, debug: bool, extensions: list<string>},
 *     extension: array{mode: string, intlLoaded: bool, icuVersion: string|null, icuDataVersion: string|null, branchTrace: BranchTrace},
 *     processState: array{timezone: string, locale: string|false, intlDefaultLocale: string|null},
 *     dependencies: array{lockSha256: string, installed: array<string, string>},
 *     actions: ActionPins,
 *     conformanceBaseline: array{ecma402: string, test262: string},
 *     releaseDataSnapshot: array{manifest: string, fingerprint: string, cldrRevision: string}
 * }
 */
final class Provenance
{
    /**
     * @param BranchTrace $branchTrace
     * @return Evidence
     */
    public static function collect(
        string $root,
        string $requestedPhp,
        string $runnerLabel,
        string $extensionMode,
        array $branchTrace,
    ): array {
        $manifest = self::readJson($root.'/resources/data/manifest.json');
        $actionPins = self::actionPins($root.'/.ci/action-pins.json');
        $extensions = get_loaded_extensions();
        sort($extensions);

        return [
            'format' => 1,
            'runner' => [
                'label' => $runnerLabel,
                'image' => getenv('ImageOS') ?: null,
                'imageVersion' => getenv('ImageVersion') ?: null,
                'osFamily' => PHP_OS_FAMILY,
                'architecture' => php_uname('m'),
            ],
            'runtime' => [
                'requestedPhp' => $requestedPhp,
                'actualPhp' => PHP_VERSION,
                'versionId' => PHP_VERSION_ID,
                'integerSize' => PHP_INT_SIZE,
                'threadSafe' => PHP_ZTS === 1,
                'debug' => PHP_DEBUG === 1,
                'extensions' => $extensions,
            ],
            'extension' => [
                'mode' => $extensionMode,
                'intlLoaded' => extension_loaded('intl'),
                'icuVersion' => defined('INTL_ICU_VERSION') ? INTL_ICU_VERSION : null,
                'icuDataVersion' => defined('INTL_ICU_DATA_VERSION') ? INTL_ICU_DATA_VERSION : null,
                'branchTrace' => $branchTrace,
            ],
            'processState' => [
                'timezone' => date_default_timezone_get(),
                'locale' => setlocale(LC_ALL, '0'),
                'intlDefaultLocale' => extension_loaded('intl') ? \Locale::getDefault() : null,
            ],
            'dependencies' => [
                'lockSha256' => self::sha256($root.'/composer.lock'),
                'installed' => self::installedDependencies($root),
            ],
            'actions' => $actionPins,
            'conformanceBaseline' => [
                'ecma402' => self::stringAt($manifest, 'ecma402', 'revision'),
                'test262' => self::stringAt($manifest, 'test262', 'revision'),
            ],
            'releaseDataSnapshot' => [
                'manifest' => 'resources/data/manifest.json',
                'fingerprint' => self::sha256($root.'/resources/data/manifest.json'),
                'cldrRevision' => self::stringAt($manifest, 'cldr', 'revision'),
            ],
        ];
    }

    /** @return array<string, mixed> */
    private static function readJson(string $path): array
    {
        $contents = file_get_contents($path);
        if ($contents === false) {
            throw new \RuntimeException(sprintf('Unable to read %s.', $path));
        }

        $value = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
        if (!is_array($value)) {
            throw new \RuntimeException(sprintf('%s must contain a JSON object.', $path));
        }

        /** @var array<string, mixed> $value */
        return $value;
    }

    private static function sha256(string $path): string
    {
        $hash = hash_file('sha256', $path);
        if ($hash === false) {
            throw new \RuntimeException(sprintf('Unable to hash %s.', $path));
        }

        return $hash;
    }

    /** @return ActionPins */
    private static function actionPins(string $path): array
    {
        $data = self::readJson($path);
        return [
            'checkout' => self::actionPin($data, 'checkout'),
            'setupPhp' => self::actionPin($data, 'setupPhp'),
            'uploadArtifact' => self::actionPin($data, 'uploadArtifact'),
            'downloadArtifact' => self::actionPin($data, 'downloadArtifact'),
        ];
    }

    /** @param array<string, mixed> $data */
    private static function actionPin(array $data, string $name): string
    {
        $value = $data[$name] ?? null;
        if (!is_string($value) || preg_match('/^[a-f0-9]{40}$/D', $value) !== 1) {
            throw new \RuntimeException(sprintf('Action pin %s must be a full commit SHA.', $name));
        }

        return $value;
    }

    /** @param array<string, mixed> $data */
    private static function stringAt(array $data, string $section, string $key): string
    {
        $sectionData = $data[$section] ?? null;
        if (!is_array($sectionData)) {
            throw new \RuntimeException(sprintf('Missing object at %s.', $section));
        }

        $value = $sectionData[$key] ?? null;
        if (!is_string($value)) {
            throw new \RuntimeException(sprintf('Missing string at %s.%s.', $section, $key));
        }

        return $value;
    }

    /** @return array<string, string> */
    private static function installedDependencies(string $root): array
    {
        $path = $root.'/vendor/composer/installed.json';
        if (!is_file($path)) {
            return [];
        }

        $installed = self::readJson($path);
        $packages = $installed['packages'] ?? $installed;
        if (!is_array($packages)) {
            return [];
        }

        $versions = [];
        foreach ($packages as $package) {
            if (!is_array($package) || !is_string($package['name'] ?? null) || !is_string($package['version'] ?? null)) {
                continue;
            }

            $versions[$package['name']] = $package['version'];
        }
        ksort($versions);

        return $versions;
    }
}
