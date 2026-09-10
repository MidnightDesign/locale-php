<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

interface ConstructorFixtureTranslator
{
    /**
     * @return array{
     *     assertions: list<array{id: string, line: int, column: int, call: string, sha256: string}>,
     *     cases: list<array{
     *         id: string,
     *         assertionId: string,
     *         tag: string,
     *         optionValue: array{type: 'null'}|array{type: 'string'|'stringable', value: string},
     *         expected: string
     *     }>
     * }
     */
    public function translate(string $source, string $fixturePath): array;
}
