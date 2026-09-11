<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

final class AssertionIdentityExtractor
{
    /**
     * @return list<array{id: string, line: int, column: int, call: string, sha256: string}>
     */
    public function extract(string $source, string $fixturePath): array
    {
        return array_column($this->extractConstructs($source, $fixturePath), 'identity');
    }

    /**
     * @return list<array{
     *     identity: array{id: string, line: int, column: int, call: string, sha256: string},
     *     source: string
     * }>
     */
    public function extractConstructs(string $source, string $fixturePath): array
    {
        $mask = $source;
        $offset = 0;
        $this->maskCode($source, $mask, $offset);

        preg_match_all(
            '/\b(assert(?:\s*\.\s*[A-Za-z_$][A-Za-z0-9_$]*)?|verifyProperty|verifyEqualTo)\s*(?=\()/',
            $mask,
            $matches,
            PREG_OFFSET_CAPTURE,
        );

        $assertions = [];
        foreach ($matches[1] as [$matchedCall, $callOffset]) {
            $openParenthesis = strpos($mask, '(', $callOffset + strlen($matchedCall));
            if ($openParenthesis === false) {
                throw new \RuntimeException('Unable to locate an assertion argument list.');
            }

            $closeParenthesis = $this->closingParenthesis($mask, $openParenthesis);
            $line = substr_count(substr($source, 0, $callOffset), "\n") + 1;
            $lineStart = strrpos(substr($source, 0, $callOffset), "\n");
            $column = $callOffset - ($lineStart === false ? -1 : $lineStart);
            $call = preg_replace('/\s+/', '', $matchedCall);
            if ($call === null) {
                throw new \RuntimeException('Unable to normalize an assertion call.');
            }

            $assertionSource = substr($source, $callOffset, $closeParenthesis - $callOffset + 1);
            $assertions[] = [
                'identity' => [
                    'id' => sprintf('%s:L%d:C%d:%s', $fixturePath, $line, $column, $call),
                    'line' => $line,
                    'column' => $column,
                    'call' => $call,
                    'sha256' => hash('sha256', $assertionSource),
                ],
                'source' => $assertionSource,
            ];
        }

        return $assertions;
    }

    private function closingParenthesis(string $mask, int $openParenthesis): int
    {
        $depth = 0;
        $length = strlen($mask);
        for ($offset = $openParenthesis; $offset < $length; ++$offset) {
            if ($mask[$offset] === '(') {
                ++$depth;
            } elseif ($mask[$offset] === ')' && --$depth === 0) {
                return $offset;
            }
        }

        throw new \RuntimeException('Unable to locate the end of an assertion argument list.');
    }

    private function maskCode(string $source, string &$mask, int &$offset, bool $stopAtBrace = false): void
    {
        $braceDepth = 0;
        $length = strlen($source);
        while ($offset < $length) {
            $character = $source[$offset];
            $next = $source[$offset + 1] ?? '';

            if ($stopAtBrace && $character === '}' && $braceDepth === 0) {
                return;
            }
            if ($character === '{') {
                ++$braceDepth;
                ++$offset;
                continue;
            }
            if ($character === '}') {
                --$braceDepth;
                ++$offset;
                continue;
            }
            if ($character === '/' && $next === '/') {
                $this->maskLineComment($source, $mask, $offset);
                continue;
            }
            if ($character === '/' && $next === '*') {
                $this->maskBlockComment($source, $mask, $offset);
                continue;
            }
            if ($character === "'" || $character === '"') {
                $this->maskQuotedString($source, $mask, $offset, $character);
                continue;
            }
            if ($character === '`') {
                $this->maskTemplate($source, $mask, $offset);
                continue;
            }
            if ($character === '/' && $this->canStartRegex($mask, $offset)) {
                $this->maskRegex($source, $mask, $offset);
                continue;
            }

            ++$offset;
        }
    }

    private function maskLineComment(string $source, string &$mask, int &$offset): void
    {
        $length = strlen($source);
        while ($offset < $length && $source[$offset] !== "\n") {
            $mask[$offset] = ' ';
            ++$offset;
        }
    }

    private function maskBlockComment(string $source, string &$mask, int &$offset): void
    {
        $length = strlen($source);
        while ($offset < $length) {
            $character = $source[$offset];
            $next = $source[$offset + 1] ?? '';
            $this->blank($mask, $offset);
            ++$offset;
            if ($character === '*' && $next === '/') {
                $this->blank($mask, $offset);
                ++$offset;
                return;
            }
        }

        throw new \RuntimeException('Unterminated JavaScript block comment.');
    }

    private function maskQuotedString(string $source, string &$mask, int &$offset, string $quote): void
    {
        $length = strlen($source);
        $this->blank($mask, $offset++);
        while ($offset < $length) {
            $character = $source[$offset];
            $this->blank($mask, $offset++);
            if ($character === '\\' && $offset < $length) {
                $this->blank($mask, $offset++);
                continue;
            }
            if ($character === $quote) {
                return;
            }
        }

        throw new \RuntimeException('Unterminated JavaScript string literal.');
    }

    private function maskTemplate(string $source, string &$mask, int &$offset): void
    {
        $length = strlen($source);
        $this->blank($mask, $offset++);
        while ($offset < $length) {
            $character = $source[$offset];
            $next = $source[$offset + 1] ?? '';
            if ($character === '\\') {
                $this->blank($mask, $offset++);
                if ($offset < $length) {
                    $this->blank($mask, $offset++);
                }
                continue;
            }
            if ($character === '`') {
                $this->blank($mask, $offset++);

                return;
            }
            if ($character === '$' && $next === '{') {
                $this->blank($mask, $offset++);
                $this->blank($mask, $offset++);
                $this->maskCode($source, $mask, $offset, true);
                if (($source[$offset] ?? null) !== '}') {
                    throw new \RuntimeException('Unterminated JavaScript template interpolation.');
                }
                $this->blank($mask, $offset++);
                continue;
            }

            $this->blank($mask, $offset++);
        }

        throw new \RuntimeException('Unterminated JavaScript template literal.');
    }

    private function maskRegex(string $source, string &$mask, int &$offset): void
    {
        $length = strlen($source);
        $inCharacterClass = false;
        $this->blank($mask, $offset++);
        while ($offset < $length) {
            $character = $source[$offset];
            $this->blank($mask, $offset++);
            if ($character === '\\' && $offset < $length) {
                $this->blank($mask, $offset++);
                continue;
            }
            if ($character === '[') {
                $inCharacterClass = true;
            } elseif ($character === ']') {
                $inCharacterClass = false;
            } elseif ($character === '/' && !$inCharacterClass) {
                while ($offset < $length && ctype_alpha($source[$offset])) {
                    $this->blank($mask, $offset++);
                }

                return;
            } elseif ($character === "\n") {
                throw new \RuntimeException('Unterminated JavaScript regular expression literal.');
            }
        }

        throw new \RuntimeException('Unterminated JavaScript regular expression literal.');
    }

    private function canStartRegex(string $mask, int $offset): bool
    {
        $previous = $offset - 1;
        while ($previous >= 0 && ctype_space($mask[$previous])) {
            --$previous;
        }
        if ($previous < 0 || str_contains('([{,;:=!?&|+-*%^~<>', $mask[$previous])) {
            return true;
        }

        if (!preg_match('/[A-Za-z0-9_$]/', $mask[$previous])) {
            return false;
        }

        $end = $previous;
        while ($previous >= 0 && preg_match('/[A-Za-z0-9_$]/', $mask[$previous])) {
            --$previous;
        }
        $word = substr($mask, $previous + 1, $end - $previous);

        return in_array(
            $word,
            [
                'await',
                'case',
                'delete',
                'do',
                'else',
                'in',
                'instanceof',
                'new',
                'of',
                'return',
                'throw',
                'typeof',
                'void',
                'yield',
            ],
            true,
        );
    }

    private function blank(string &$mask, int $offset): void
    {
        if ($mask[$offset] !== "\n") {
            $mask[$offset] = ' ';
        }
    }
}
