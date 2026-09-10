<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Ci;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

final class Workflow
{
    /** @param array<string, mixed> $data */
    private function __construct(private array $data)
    {
    }

    public static function fromFile(string $path): self
    {
        try {
            $data = Yaml::parseFile($path);
        } catch (ParseException $error) {
            throw new \RuntimeException(sprintf('Unable to parse %s: %s', $path, $error->getMessage()), previous: $error);
        }

        if (!is_array($data)) {
            throw new \RuntimeException(sprintf('%s must contain a YAML mapping.', $path));
        }

        /** @var array<string, mixed> $data */
        return new self($data);
    }

    /** @return list<string> */
    public function triggers(): array
    {
        $triggers = $this->data['on'] ?? null;
        if (!is_array($triggers)) {
            return [];
        }

        return array_values(array_filter(array_keys($triggers), 'is_string'));
    }

    /** @return array<string, array<string, mixed>> */
    public function jobs(): array
    {
        $jobs = $this->data['jobs'] ?? null;
        if (!is_array($jobs)) {
            return [];
        }

        $result = [];
        foreach ($jobs as $name => $job) {
            if (is_string($name) && is_array($job)) {
                /** @var array<string, mixed> $job */
                $result[$name] = $job;
            }
        }

        return $result;
    }

    /** @return list<array<string, mixed>> */
    public function steps(): array
    {
        $steps = [];
        foreach ($this->jobs() as $job) {
            $jobSteps = $job['steps'] ?? null;
            if (!is_array($jobSteps)) {
                continue;
            }
            foreach ($jobSteps as $step) {
                if (is_array($step)) {
                    /** @var array<string, mixed> $step */
                    $steps[] = $step;
                }
            }
        }

        return $steps;
    }

    /** @return list<string> */
    public function uses(): array
    {
        $references = [];
        foreach ($this->jobs() as $job) {
            if (is_string($job['uses'] ?? null)) {
                $references[] = $job['uses'];
            }
        }
        foreach ($this->steps() as $step) {
            if (is_string($step['uses'] ?? null)) {
                $references[] = $step['uses'];
            }
        }

        return $references;
    }

    /** @return list<string> */
    public function runs(): array
    {
        $commands = [];
        foreach ($this->steps() as $step) {
            if (is_string($step['run'] ?? null)) {
                $commands[] = $step['run'];
            }
        }

        return $commands;
    }

    public function hasSetting(string $key, mixed $expected): bool
    {
        return self::nodeHasSetting($this->data, $key, $expected);
    }

    public function hasScalarContaining(string $text): bool
    {
        return self::nodeHasScalarContaining($this->data, $text);
    }

    /** @param array<mixed> $node */
    private static function nodeHasSetting(array $node, string $key, mixed $expected): bool
    {
        foreach ($node as $name => $value) {
            if ($name === $key && $value === $expected) {
                return true;
            }
            if (is_array($value) && self::nodeHasSetting($value, $key, $expected)) {
                return true;
            }
        }

        return false;
    }

    /** @param array<mixed> $node */
    private static function nodeHasScalarContaining(array $node, string $text): bool
    {
        foreach ($node as $value) {
            if (is_string($value) && str_contains($value, $text)) {
                return true;
            }
            if (is_array($value) && self::nodeHasScalarContaining($value, $text)) {
                return true;
            }
        }

        return false;
    }
}
