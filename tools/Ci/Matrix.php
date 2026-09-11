<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Ci;

/**
 * @phpstan-type RuntimeLane array{runner: string, php: string, extensionMode: string, threadSafe: bool, integerSize: int, osFamily: string, architecture: string}
 * @phpstan-type RuntimeBaseLane array{runner: string, php: string, threadSafe: bool, integerSize: int, osFamily: string, architecture: string}
 * @phpstan-type InstallLane array{runner: string, php: string}
 * @phpstan-type StableRunner array{runner: string, osFamily: string, architecture: string, integerSize: int, excludedExtensionModes: list<string>}
 * @phpstan-type ArmLane array{runner: string, php: string, architecture: string}
 * @phpstan-type WindowsX86Lane array{runner: string, php: string, architecture: string, threadSafe: bool, runtimeVersion: string, runtimeUrl: string, runtimeSha256: string}
 * @phpstan-type WindowsThreadSafeLane array{runner: string, php: string, architecture: string, threadSafe: bool}
 * @phpstan-type IcuLane array{boundary: string, php: string, icu: string, extension: string}
 * @phpstan-type ArmRuntimeLane array{runner: string, php: string, architecture: string, extensionMode: string}
 * @phpstan-type WindowsX86RuntimeLane array{runner: string, php: string, architecture: string, threadSafe: bool, runtimeVersion: string, runtimeUrl: string, runtimeSha256: string, extensionMode: string}
 * @phpstan-type WindowsThreadSafeRuntimeLane array{runner: string, php: string, architecture: string, threadSafe: bool, extensionMode: string}
 * @phpstan-type IcuRuntimeLane array{boundary: string, php: string, icu: string, extension: string, runner: string, extensionMode: string}
 */
final class Matrix
{
    /**
     * @param list<string> $stablePhp
     * @param list<string> $advisoryPhp
     * @param list<StableRunner> $stableRunners
     * @param list<string> $extensionModes
     * @param list<ArmLane> $armLanes
     * @param list<WindowsX86Lane> $windowsX86Lanes
     * @param list<WindowsThreadSafeLane> $windowsThreadSafeLanes
     * @param list<IcuLane> $icuLanes
     */
    private function __construct(
        private array $stablePhp,
        private array $advisoryPhp,
        private array $stableRunners,
        private array $extensionModes,
        private array $armLanes,
        private array $windowsX86Lanes,
        private array $windowsThreadSafeLanes,
        private array $icuLanes,
    ) {
    }

    public static function fromFile(string $path): self
    {
        $contents = file_get_contents($path);
        if ($contents === false) {
            throw new \RuntimeException(sprintf('Unable to read CI matrix at %s.', $path));
        }

        $data = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
        if (!is_array($data)) {
            throw new \RuntimeException('The CI matrix must be a JSON object.');
        }

        $extensionModes = self::stringList($data, 'extensionModes');

        return new self(
            self::stringList($data, 'stablePhp'),
            self::stringList($data, 'advisoryPhp'),
            self::stableRunnersFromData($data, $extensionModes),
            $extensionModes,
            self::armLanesFromData($data),
            self::windowsX86LanesFromData($data),
            self::windowsThreadSafeLanesFromData($data),
            self::icuLanesFromData($data),
        );
    }

    /** @return list<RuntimeLane> */
    public function runtimeLanes(): array
    {
        $lanes = [];
        foreach ($this->stableRunners as $runner) {
            foreach ($this->stablePhp as $php) {
                $lanes[] = [
                    'runner' => $runner['runner'],
                    'php' => $php,
                    'threadSafe' => false,
                    'integerSize' => $runner['integerSize'],
                    'osFamily' => $runner['osFamily'],
                    'architecture' => $runner['architecture'],
                ];
            }
        }

        $result = [];
        foreach ($lanes as $lane) {
            $runner = $this->stableRunner($lane['runner']);
            $extensionModes = array_values(array_diff($this->extensionModes, $runner['excludedExtensionModes']));
            foreach ($extensionModes as $mode) {
                $result[] = [...$lane, 'extensionMode' => $mode];
            }
        }

        return $result;
    }

    /** @return list<InstallLane> */
    public function installLanes(): array
    {
        $minimumPhp = $this->stablePhp[0] ?? throw new \LogicException('Stable PHP matrix is empty.');
        $maximumPhp = end($this->stablePhp);

        $lanes = [];
        foreach ($this->stableRunners as $runner) {
            $lanes[] = ['runner' => $runner['runner'], 'php' => $minimumPhp];
            $lanes[] = ['runner' => $runner['runner'], 'php' => $maximumPhp];
        }

        return $lanes;
    }

    /** @return list<ArmRuntimeLane> */
    public function armRuntimeLanes(): array
    {
        /** @phpstan-var list<ArmRuntimeLane> $result */
        $result = self::withExtensionModes($this->armLanes, $this->extensionModes);

        return $result;
    }

    /** @return list<WindowsX86RuntimeLane> */
    public function windowsX86RuntimeLanes(): array
    {
        /** @phpstan-var list<WindowsX86RuntimeLane> $result */
        $result = self::withExtensionModes($this->windowsX86Lanes, $this->extensionModes);

        return $result;
    }

    /** @return list<WindowsThreadSafeRuntimeLane> */
    public function windowsThreadSafeRuntimeLanes(): array
    {
        /** @phpstan-var list<WindowsThreadSafeRuntimeLane> $result */
        $result = self::withExtensionModes($this->windowsThreadSafeLanes, $this->extensionModes);

        return $result;
    }

    /** @return list<IcuRuntimeLane> */
    public function icuRuntimeLanes(): array
    {
        $lanes = [];
        foreach ($this->icuLanes as $lane) {
            $lanes[] = [...$lane, 'runner' => 'ubuntu-24.04'];
        }

        /** @phpstan-var list<IcuRuntimeLane> $result */
        $result = self::withExtensionModes($lanes, ['disabled', 'native']);

        return $result;
    }

    /** @return list<RuntimeLane> */
    public function advisoryRuntimeLanes(): array
    {
        $runner = $this->stableRunner('ubuntu-24.04');
        $lanes = [];
        foreach ($this->advisoryPhp as $php) {
            $lanes[] = [
                'runner' => $runner['runner'],
                'php' => $php,
                'threadSafe' => false,
                'integerSize' => $runner['integerSize'],
                'osFamily' => $runner['osFamily'],
                'architecture' => $runner['architecture'],
            ];
        }

        /** @phpstan-var list<RuntimeLane> $result */
        $result = self::withExtensionModes($lanes, $this->extensionModes);

        return $result;
    }

    /** @param array<mixed> $data
     * @return list<string>
     */
    private static function stringList(array $data, string $key): array
    {
        $values = $data[$key] ?? null;
        if (!is_array($values) || $values === []) {
            throw new \RuntimeException(sprintf('CI matrix key %s must be a non-empty list.', $key));
        }

        $result = [];
        foreach ($values as $value) {
            if (!is_string($value) || $value === '') {
                throw new \RuntimeException(sprintf('CI matrix key %s must contain strings.', $key));
            }
            $result[] = $value;
        }

        return $result;
    }

    /**
     * @param array<mixed> $data
     * @param list<string> $extensionModes
     * @return list<StableRunner>
     */
    private static function stableRunnersFromData(array $data, array $extensionModes): array
    {
        $records = $data['stableRunners'] ?? null;
        if (!is_array($records) || $records === []) {
            throw new \RuntimeException('CI matrix key stableRunners must be a non-empty list.');
        }

        $result = [];
        foreach ($records as $record) {
            if (!is_array($record)
                || !is_string($record['runner'] ?? null)
                || !is_string($record['osFamily'] ?? null)
                || !is_string($record['architecture'] ?? null)
                || !is_int($record['integerSize'] ?? null)) {
                throw new \RuntimeException('CI matrix stable runner is invalid.');
            }
            $excludedExtensionModes = $record['excludedExtensionModes'] ?? [];
            if (!is_array($excludedExtensionModes)) {
                throw new \RuntimeException('CI matrix stable runner extension mode exclusions must be a list.');
            }
            foreach ($excludedExtensionModes as $mode) {
                if (!is_string($mode) || !in_array($mode, $extensionModes, true)) {
                    throw new \RuntimeException('CI matrix stable runner contains an unknown extension mode exclusion.');
                }
            }
            /** @var list<string> $excludedExtensionModes */
            if (count(array_unique($excludedExtensionModes)) !== count($excludedExtensionModes)) {
                throw new \RuntimeException('CI matrix stable runner contains duplicate extension mode exclusions.');
            }
            if (array_diff($extensionModes, $excludedExtensionModes) === []) {
                throw new \RuntimeException('CI matrix stable runner must retain at least one extension mode.');
            }
            $result[] = [
                'runner' => $record['runner'],
                'osFamily' => $record['osFamily'],
                'architecture' => $record['architecture'],
                'integerSize' => $record['integerSize'],
                'excludedExtensionModes' => $excludedExtensionModes,
            ];
        }

        return $result;
    }

    /** @param array<mixed> $data
     * @return list<ArmLane>
     */
    private static function armLanesFromData(array $data): array
    {
        $specialized = $data['specialized'] ?? null;
        $records = is_array($specialized) ? ($specialized['arm'] ?? null) : null;
        if (!is_array($records)) {
            throw new \RuntimeException('CI matrix key arm must be a list.');
        }

        $result = [];
        foreach ($records as $record) {
            if (!is_array($record)
                || !is_string($record['runner'] ?? null)
                || !is_string($record['php'] ?? null)
                || !is_string($record['architecture'] ?? null)) {
                throw new \RuntimeException('CI matrix arm lane is invalid.');
            }
            $result[] = [
                'runner' => $record['runner'],
                'php' => $record['php'],
                'architecture' => $record['architecture'],
            ];
        }

        return $result;
    }

    /** @param array<mixed> $data
     * @return list<IcuLane>
     */
    private static function icuLanesFromData(array $data): array
    {
        $records = $data['icu'] ?? null;
        if (!is_array($records)) {
            throw new \RuntimeException('CI matrix key icu must be a list.');
        }

        $result = [];
        foreach ($records as $record) {
            if (!is_array($record)
                || !is_string($record['boundary'] ?? null)
                || !is_string($record['php'] ?? null)
                || !is_string($record['icu'] ?? null)
                || !is_string($record['extension'] ?? null)) {
                throw new \RuntimeException('CI matrix ICU lane is invalid.');
            }
            $result[] = [
                'boundary' => $record['boundary'],
                'php' => $record['php'],
                'icu' => $record['icu'],
                'extension' => $record['extension'],
            ];
        }

        return $result;
    }

    /**
     * @param array<mixed> $data
     * @return list<WindowsX86Lane>
     */
    private static function windowsX86LanesFromData(array $data): array
    {
        $specialized = $data['specialized'] ?? null;
        $records = is_array($specialized) ? ($specialized['windowsX86'] ?? null) : null;
        if (!is_array($records)) {
            throw new \RuntimeException('CI matrix key windowsX86 must be a list.');
        }

        $result = [];
        foreach ($records as $record) {
            if (!is_array($record)
                || !is_string($record['runner'] ?? null)
                || !is_string($record['php'] ?? null)
                || !is_string($record['architecture'] ?? null)
                || !is_bool($record['threadSafe'] ?? null)) {
                throw new \RuntimeException('CI matrix Windows x86 lane is invalid.');
            }
            $runtimeVersion = $record['runtimeVersion'] ?? null;
            $runtimeUrl = $record['runtimeUrl'] ?? null;
            $runtimeSha256 = $record['runtimeSha256'] ?? null;
            if (!is_string($runtimeVersion) || !is_string($runtimeUrl) || !is_string($runtimeSha256)) {
                throw new \RuntimeException('CI matrix Windows x86 runtime pin is incomplete.');
            }
            if (!str_starts_with($runtimeVersion, $record['php'].'.')
                || !str_starts_with($runtimeUrl, 'https://downloads.php.net/~windows/releases/php-')
                || preg_match('/^[a-f0-9]{64}$/D', $runtimeSha256) !== 1) {
                throw new \RuntimeException('CI matrix Windows x86 runtime pin is invalid.');
            }
            $result[] = [
                'runner' => $record['runner'],
                'php' => $record['php'],
                'architecture' => $record['architecture'],
                'threadSafe' => $record['threadSafe'],
                'runtimeVersion' => $runtimeVersion,
                'runtimeUrl' => $runtimeUrl,
                'runtimeSha256' => $runtimeSha256,
            ];
        }

        return $result;
    }

    /**
     * @param array<mixed> $data
     * @return list<WindowsThreadSafeLane>
     */
    private static function windowsThreadSafeLanesFromData(array $data): array
    {
        $specialized = $data['specialized'] ?? null;
        $records = is_array($specialized) ? ($specialized['windowsThreadSafe'] ?? null) : null;
        if (!is_array($records)) {
            throw new \RuntimeException('CI matrix key windowsThreadSafe must be a list.');
        }

        $result = [];
        foreach ($records as $record) {
            if (!is_array($record)
                || !is_string($record['runner'] ?? null)
                || !is_string($record['php'] ?? null)
                || !is_string($record['architecture'] ?? null)
                || !is_bool($record['threadSafe'] ?? null)) {
                throw new \RuntimeException('CI matrix Windows thread-safe lane is invalid.');
            }
            $result[] = [
                'runner' => $record['runner'],
                'php' => $record['php'],
                'architecture' => $record['architecture'],
                'threadSafe' => $record['threadSafe'],
            ];
        }

        return $result;
    }

    /** @return StableRunner */
    private function stableRunner(string $label): array
    {
        foreach ($this->stableRunners as $runner) {
            if ($runner['runner'] === $label) {
                return $runner;
            }
        }

        throw new \LogicException(sprintf('Stable runner %s is not configured.', $label));
    }

    /**
     * @param list<array<string, mixed>> $lanes
     * @param list<string> $modes
     * @return list<array<string, mixed>>
     */
    private static function withExtensionModes(array $lanes, array $modes): array
    {
        $result = [];
        foreach ($lanes as $lane) {
            foreach ($modes as $mode) {
                $result[] = [...$lane, 'extensionMode' => $mode];
            }
        }

        return $result;
    }
}
