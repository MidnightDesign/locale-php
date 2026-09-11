<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Test262\Harness;

final readonly class LocaleStateExpectation
{
    public function __construct(
        public string $assertionId,
        public string $property,
        public string|bool|null $expected,
    ) {}

    /** @param array{string, string, string|bool|null} $tuple */
    public static function fromTuple(array $tuple): self
    {
        return new self($tuple[0], $tuple[1], $tuple[2]);
    }

    /** @return array{string, string, string|bool|null} */
    public function toTuple(): array
    {
        return [$this->assertionId, $this->property, $this->expected];
    }
}
