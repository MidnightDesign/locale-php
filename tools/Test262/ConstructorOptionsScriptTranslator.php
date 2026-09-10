<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

final class ConstructorOptionsScriptTranslator implements ConstructorFixtureTranslator
{
    public function __construct(private readonly AssertionIdentityExtractor $assertionIdentities)
    {
    }

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
    public function translate(string $source, string $fixturePath): array
    {
        if (!preg_match('/const validScriptOptions = \[(?<options>.*?)\n\];/s', $source, $optionBlock)) {
            throw new TranslationGap('validScriptOptions is not in the supported form.');
        }

        preg_match_all(
            <<<'REGEX'
~\[\s*(?:(?<null>null)|'(?<string>[^']*)'|\{\s*toString\(\)\s*\{\s*return\s*'(?<stringable>[^']*)'\s*\}\s*\})\s*,\s*'(?<expected>[^']*)'\s*\],~
REGEX,
            $optionBlock['options'],
            $optionRows,
            PREG_SET_ORDER,
        );
        if (count($optionRows) !== 5) {
            throw new TranslationGap(sprintf(
                'Expected 5 script option rows, found %d.',
                count($optionRows),
            ));
        }

        $assertions = $this->assertionIdentities->extract($source, $fixturePath);
        if (count($assertions) !== 3
            || array_filter(
                $assertions,
                static fn (array $assertion): bool => $assertion['call'] !== 'assert.sameValue',
            ) !== []) {
            throw new TranslationGap('The fixture contains an unsupported assertion construct.');
        }

        preg_match_all(
            <<<'REGEX'
~(?:let\s+)?expect\s*=\s*(?<expression>.*?);\s*
(?<assertion>assert\.sameValue)\(\s*
new\s+Intl\.Locale\(\s*'(?<tag>[^']+)'\s*,\s*\{\s*script\s*\}\s*\)\.toString\(\)\s*,\s*
expect\s*,\s*`(?<message>(?:[^`\\]|\\.)*)`\s*
\);~sx
REGEX,
            $source,
            $assertionRows,
            PREG_SET_ORDER | PREG_OFFSET_CAPTURE,
        );
        if (count($assertionRows) !== 3) {
            throw new TranslationGap(sprintf(
                'Expected 3 supported assert.sameValue bodies, found %d.',
                count($assertionRows),
            ));
        }

        $caseDefinitions = [];
        foreach ($assertionRows as $assertionIndex => $row) {
            $caseDefinitions[] = [
                'assertionId' => $assertions[$assertionIndex]['id'],
                'tag' => $row['tag'][0],
                'expression' => $this->parseExpectedExpression($row['expression'][0]),
            ];
        }

        $cases = [];
        foreach ($optionRows as $optionIndex => $row) {
            $optionValue = match (true) {
                $row['null'] === 'null' => ['type' => 'null'],
                $row['string'] !== '' => ['type' => 'string', 'value' => $row['string']],
                default => ['type' => 'stringable', 'value' => $row['stringable']],
            };

            foreach ($caseDefinitions as $assertionIndex => $definition) {
                $expected = $row['expected'] !== ''
                    ? $definition['expression']['prefix'].$row['expected'].$definition['expression']['suffix']
                    : $definition['expression']['fallback'];
                $cases[] = [
                    'id' => sprintf('option-%d-assertion-%d', $optionIndex + 1, $assertionIndex + 1),
                    'assertionId' => $definition['assertionId'],
                    'tag' => $definition['tag'],
                    'optionValue' => $optionValue,
                    'expected' => $expected,
                ];
            }
        }

        return ['assertions' => $assertions, 'cases' => $cases];
    }

    /** @return array{prefix: string, suffix: string, fallback: string} */
    private function parseExpectedExpression(string $expression): array
    {
        $normalized = preg_replace('/\s+/', ' ', trim($expression));
        if ($normalized === null) {
            throw new TranslationGap('Unable to normalize the expected expression.');
        }

        if (preg_match(
            "/^expected \? '([^']*)' \+ expected : '([^']*)'$/",
            $normalized,
            $parts,
        )) {
            return ['prefix' => $parts[1], 'suffix' => '', 'fallback' => $parts[2]];
        }
        if (preg_match(
            "/^\(expected \? \('([^']*)' \+ expected\) : '([^']*)'\) \+ '([^']*)'$/",
            $normalized,
            $parts,
        )) {
            return ['prefix' => $parts[1], 'suffix' => $parts[3], 'fallback' => $parts[2].$parts[3]];
        }
        if (preg_match(
            "/^expected \? \('([^']*)' \+ expected\) : '([^']*)'$/",
            $normalized,
            $parts,
        )) {
            return ['prefix' => $parts[1], 'suffix' => '', 'fallback' => $parts[2]];
        }

        throw new TranslationGap(sprintf(
            'Unsupported expected expression "%s".',
            $normalized,
        ));
    }
}
