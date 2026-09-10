<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Ci;

/**
 * @phpstan-type RuntimeLane array{runner: string, php: string, extensionMode: string, threadSafe: bool, integerSize: int, osFamily: string, architecture: string}
 * @phpstan-type InstallLane array{runner: string, php: string}
 * @phpstan-type ArmLane array{runner: string, php: string, architecture: string}
 * @phpstan-type WindowsLane array{runner: string, php: string, architecture: string, threadSafe: bool}
 * @phpstan-type IcuLane array{boundary: string, php: string, icu: string, extension: string}
 * @phpstan-type ArmRuntimeLane array{runner: string, php: string, architecture: string, extensionMode: string}
 * @phpstan-type WindowsRuntimeLane array{runner: string, php: string, architecture: string, threadSafe: bool, extensionMode: string}
 * @phpstan-type IcuRuntimeLane array{boundary: string, php: string, icu: string, extension: string, runner: string, extensionMode: string}
 * @phpstan-type SpecializedRuntimeLane array{runner: string, php: string, architecture: string, threadSafe: bool, integerSize: int, extensionMode: string}
 */
final class Matrix
{
    /**
     * @param list<string> $stablePhp
     * @param list<string> $advisoryPhp
     * @param list<string> $runners
     * @param list<string> $extensionModes
     * @param list<ArmLane> $armLanes
     * @param list<WindowsLane> $windowsX86Lanes
     * @param list<WindowsLane> $windowsThreadSafeLanes
     * @param list<IcuLane> $icuLanes
     */
    private function __construct(
        private array $stablePhp,
        private array $advisoryPhp,
        private array $runners,
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

        return new self(
            self::stringList($data, 'stablePhp'),
            self::stringList($data, 'advisoryPhp'),
            self::stringList($data, 'runners'),
            self::stringList($data, 'extensionModes'),
            self::armLanesFromData($data),
            self::windowsLanes($data, 'windowsX86'),
            self::windowsLanes($data, 'windowsThreadSafe'),
            self::icuLanesFromData($data),
        );
    }

    /** @return list<string> */
    public function stablePhp(): array
    {
        return $this->stablePhp;
    }

    /** @return list<string> */
    public function advisoryPhp(): array
    {
        return $this->advisoryPhp;
    }

    /** @return list<string> */
    public function extensionModes(): array
    {
        return $this->extensionModes;
    }

    /** @return list<RuntimeLane> */
    public function runtimeLanes(): array
    {
        $lanes = [];
        foreach ($this->runners as $runner) {
            foreach ($this->stablePhp as $php) {
                foreach ($this->extensionModes as $extensionMode) {
                    $runtime = self::runnerRuntime($runner);
                    $lanes[] = [
                        'runner' => $runner,
                        'php' => $php,
                        'extensionMode' => $extensionMode,
                        'threadSafe' => false,
                        'integerSize' => 8,
                        'osFamily' => $runtime['osFamily'],
                        'architecture' => $runtime['architecture'],
                    ];
                }
            }
        }

        return $lanes;
    }

    /** @return list<InstallLane> */
    public function installLanes(): array
    {
        $minimumPhp = $this->stablePhp[0] ?? throw new \LogicException('Stable PHP matrix is empty.');
        $maximumPhp = end($this->stablePhp);

        $lanes = [];
        foreach ($this->runners as $runner) {
            $lanes[] = ['runner' => $runner, 'php' => $minimumPhp];
            $lanes[] = ['runner' => $runner, 'php' => $maximumPhp];
        }

        return $lanes;
    }

    /** @return list<ArmLane> */
    public function armLanes(): array
    {
        return $this->armLanes;
    }

    /** @return list<WindowsLane> */
    public function windowsX86Lanes(): array
    {
        return $this->windowsX86Lanes;
    }

    /** @return list<WindowsLane> */
    public function windowsThreadSafeLanes(): array
    {
        return $this->windowsThreadSafeLanes;
    }

    /** @return list<IcuLane> */
    public function icuLanes(): array
    {
        return $this->icuLanes;
    }

    /** @return list<ArmRuntimeLane> */
    public function armRuntimeLanes(): array
    {
        $result = [];
        foreach ($this->armLanes as $lane) {
            foreach ($this->extensionModes as $mode) {
                $result[] = [...$lane, 'extensionMode' => $mode];
            }
        }

        return $result;
    }

    /** @return list<WindowsRuntimeLane> */
    public function windowsX86RuntimeLanes(): array
    {
        return $this->windowsRuntimeLanes($this->windowsX86Lanes);
    }

    /** @return list<WindowsRuntimeLane> */
    public function windowsThreadSafeRuntimeLanes(): array
    {
        return $this->windowsRuntimeLanes($this->windowsThreadSafeLanes);
    }

    /** @return list<IcuRuntimeLane> */
    public function icuRuntimeLanes(): array
    {
        $result = [];
        foreach ($this->icuLanes as $lane) {
            foreach (['disabled', 'native'] as $mode) {
                $result[] = [...$lane, 'runner' => 'ubuntu-24.04', 'extensionMode' => $mode];
            }
        }

        return $result;
    }

    /** @return list<RuntimeLane> */
    public function advisoryRuntimeLanes(): array
    {
        $result = [];
        foreach ($this->advisoryPhp as $php) {
            foreach ($this->extensionModes as $mode) {
                $result[] = [
                    'runner' => 'ubuntu-24.04',
                    'php' => $php,
                    'extensionMode' => $mode,
                    'threadSafe' => false,
                    'integerSize' => 8,
                    'osFamily' => 'Linux',
                    'architecture' => 'x64',
                ];
            }
        }

        return $result;
    }

    /** @return list<SpecializedRuntimeLane> */
    public function specializedRuntimeLanes(): array
    {
        $result = [];
        foreach ($this->armLanes as $lane) {
            foreach ($this->extensionModes as $mode) {
                $result[] = [
                    ...$lane,
                    'threadSafe' => false,
                    'integerSize' => 8,
                    'extensionMode' => $mode,
                ];
            }
        }
        foreach ([$this->windowsX86Lanes, $this->windowsThreadSafeLanes] as $lanes) {
            foreach ($this->windowsRuntimeLanes($lanes) as $lane) {
                $result[] = [
                    ...$lane,
                    'integerSize' => $lane['architecture'] === 'x86' ? 4 : 8,
                ];
            }
        }

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

    /** @param array<mixed> $data
     * @return list<WindowsLane>
     */
    private static function windowsLanes(array $data, string $key): array
    {
        $specialized = $data['specialized'] ?? null;
        $records = is_array($specialized) ? ($specialized[$key] ?? null) : null;
        if (!is_array($records)) {
            throw new \RuntimeException(sprintf('CI matrix key %s must be a list.', $key));
        }

        $result = [];
        foreach ($records as $record) {
            if (!is_array($record)
                || !is_string($record['runner'] ?? null)
                || !is_string($record['php'] ?? null)
                || !is_string($record['architecture'] ?? null)
                || !is_bool($record['threadSafe'] ?? null)) {
                throw new \RuntimeException(sprintf('CI matrix lane %s is invalid.', $key));
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

    /**
     * @param list<WindowsLane> $lanes
     * @return list<WindowsRuntimeLane>
     */
    private function windowsRuntimeLanes(array $lanes): array
    {
        $result = [];
        foreach ($lanes as $lane) {
            foreach ($this->extensionModes as $mode) {
                $result[] = [...$lane, 'extensionMode' => $mode];
            }
        }

        return $result;
    }

    /** @return array{osFamily: string, architecture: string} */
    private static function runnerRuntime(string $runner): array
    {
        return match ($runner) {
            'ubuntu-24.04' => ['osFamily' => 'Linux', 'architecture' => 'x64'],
            'windows-2022' => ['osFamily' => 'Windows', 'architecture' => 'x64'],
            'macos-15' => ['osFamily' => 'Darwin', 'architecture' => 'arm64'],
            default => throw new \RuntimeException(sprintf('Stable runner %s has no runtime identity.', $runner)),
        };
    }
}
