<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

final class ConstructorOptionsScriptTranslator
{
    /**
     * @return array{
     *     assertions: list<array{id: string, line: int, column: int, call: 'assert.sameValue'}>,
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
            throw new \RuntimeException('Translation gap: validScriptOptions is not in the supported form.');
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
            throw new \RuntimeException(sprintf(
                'Translation gap: expected 5 script option rows, found %d.',
                count($optionRows),
            ));
        }

        $assertionCount = preg_match_all(
            '/\b(?:assert\.[A-Za-z]+|verifyProperty|verifyEqualTo)\s*\(/',
            $source,
        );
        if ($assertionCount !== 3) {
            throw new \RuntimeException('Translation gap: the fixture contains an unsupported assertion construct.');
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
            throw new \RuntimeException(sprintf(
                'Translation gap: expected 3 supported assert.sameValue bodies, found %d.',
                count($assertionRows),
            ));
        }

        $assertions = [];
        $caseDefinitions = [];
        foreach ($assertionRows as $row) {
            $assertionOffset = $row['assertion'][1];
            $line = substr_count(substr($source, 0, $assertionOffset), "\n") + 1;
            $lineStart = strrpos(substr($source, 0, $assertionOffset), "\n");
            $column = $assertionOffset - ($lineStart === false ? -1 : $lineStart);
            $id = sprintf('%s:L%d:C%d:assert.sameValue', $fixturePath, $line, $column);
            $assertions[] = [
                'id' => $id,
                'line' => $line,
                'column' => $column,
                'call' => 'assert.sameValue',
            ];
            $caseDefinitions[] = [
                'assertionId' => $id,
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
            throw new \RuntimeException('Translation gap: unable to normalize the expected expression.');
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

        throw new \RuntimeException(sprintf(
            'Translation gap: unsupported expected expression "%s".',
            $normalized,
        ));
    }
}
