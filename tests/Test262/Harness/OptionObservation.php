<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Test262\Harness;

use Midnight\Intl\Internal\Test262\OptionBag;
use Midnight\Intl\Spec\Locale;

final class OptionObservation
{
    /** @return list<string> */
    public static function getterOrder(): array
    {
        $log = new OptionObservationLog();
        $tag = new class($log) implements \Stringable {
            public function __construct(
                private OptionObservationLog $log,
            ) {}

            public function __toString(): string
            {
                $this->log->entries[] = 'tag toString';
                return 'en';
            }
        };
        $values = [
            'language' => 'de',
            'script' => 'Latn',
            'region' => 'DE',
            'variants' => 'fonipa-1996',
            'calendar' => 'gregory',
            'collation' => 'zhuyin',
            'hourCycle' => 'h24',
            'caseFirst' => 'upper',
            'numeric' => false,
            'numberingSystem' => 'latn',
        ];
        $options = new class($log, $values) implements OptionBag {
            /** @param array<string, string|bool> $values */
            public function __construct(
                private OptionObservationLog $log,
                private array $values,
            ) {}

            public function get(string $name): mixed
            {
                if (!array_key_exists($name, $this->values)) {
                    return \Midnight\Intl\Internal\UndefinedValue::Value;
                }
                $this->log->entries[] = 'get ' . $name;
                if ($name === 'numeric') {
                    return false;
                }
                return new class($name, $this->values[$name], $this->log) implements \Stringable {
                    public function __construct(
                        private string $name,
                        private string|bool $value,
                        private OptionObservationLog $log,
                    ) {}

                    public function __toString(): string
                    {
                        $this->log->entries[] = 'toString ' . $this->name;
                        return (string) $this->value;
                    }
                };
            }
        };

        new Locale($tag, $options);

        return $log->entries;
    }

    public static function propagates(string $option): bool
    {
        $failure = new OptionObservationError();
        $options = new class($option, $failure) implements OptionBag {
            public function __construct(
                private string $option,
                private OptionObservationError $failure,
            ) {}

            public function get(string $name): mixed
            {
                if ($name !== $this->option) {
                    return \Midnight\Intl\Internal\UndefinedValue::Value;
                }
                throw $this->failure;
            }
        };
        try {
            new Locale('en', $options);
        } catch (OptionObservationError $caught) {
            return $caught === $failure;
        }
        return false;
    }
}

final class OptionObservationLog
{
    /** @var list<string> */
    public array $entries = [];
}

final class OptionObservationError extends \Exception {}
