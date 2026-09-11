<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Test262\Harness;

use Midnight\Intl\Internal\Test262\ObjectValue;
use Midnight\Intl\Internal\UndefinedValue;
use Midnight\Intl\Spec\Locale;

final class TagStringConversion
{
    /** @return array{hint: bool, exceptions: list<bool>} */
    public static function evaluate(): array
    {
        $log = new TagStringConversionLog();
        $scenarios = [
            ['toPrimitive',     'get'],
            ['toPrimitive',     'call'],
            ['toString',        'get'],
            ['toString',        'call'],
            ['valueOf',         'get'],
            ['valueOf',         'call'],
            ['fallbackValueOf', 'get'],
            ['fallbackValueOf', 'call'],
        ];
        $exceptions = [];
        foreach ($scenarios as [$target, $mode]) {
            $failure = new TagStringConversionError();
            $wrongFailure = new WrongTagStringConversionError();
            $input = new TagConversionObject($target, $mode, $failure, $wrongFailure, $log);
            try {
                new Locale($input);
                $exceptions[] = false;
            } catch (TagStringConversionError $caught) {
                $exceptions[] = $caught === $failure;
            } catch (\Throwable) {
                $exceptions[] = false;
            }
        }

        return ['hint' => $log->hintWasString, 'exceptions' => $exceptions];
    }
}

final class TagConversionObject implements ObjectValue, \Stringable
{
    public function __construct(
        private readonly string $target,
        private readonly string $mode,
        private readonly TagStringConversionError $failure,
        private readonly WrongTagStringConversionError $wrongFailure,
        private readonly TagStringConversionLog $log,
    ) {}

    public function __toString(): string
    {
        throw $this->wrongFailure;
    }

    public function get(string $name): mixed
    {
        if ($name === '@@toPrimitive') {
            if ($this->target !== 'toPrimitive') {
                return UndefinedValue::Value;
            }
            if ($this->mode === 'get') {
                throw $this->failure;
            }

            return function (string $hint): never {
                $this->log->hintWasString = $hint === 'string';
                throw $this->failure;
            };
        }
        if ($name === 'toString') {
            if (in_array($this->target, ['valueOf'], true)) {
                return UndefinedValue::Value;
            }
            if ($this->target === 'toString') {
                if ($this->mode === 'get') {
                    throw $this->failure;
                }

                return fn(): never => throw $this->failure;
            }
            if ($this->target === 'fallbackValueOf') {
                return static fn(): object => new \stdClass();
            }
        }
        if ($name === 'valueOf') {
            if ($this->target === 'toString') {
                throw $this->wrongFailure;
            }
            if (in_array($this->target, ['valueOf', 'fallbackValueOf'], true)) {
                if ($this->mode === 'get') {
                    throw $this->failure;
                }

                return fn(): never => throw $this->failure;
            }
        }

        return UndefinedValue::Value;
    }
}

final class TagStringConversionError extends \Exception {}

final class WrongTagStringConversionError extends \Exception {}

final class TagStringConversionLog
{
    public bool $hintWasString = false;
}
