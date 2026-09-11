<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

final class GeneratedScript
{
    public static function pathFor(string $fixturePath): string
    {
        if ($fixturePath === ''
            || str_starts_with($fixturePath, '/')
            || str_contains($fixturePath, '\\')
            || str_contains($fixturePath, ':')
            || preg_match('#(^|/)\.\.(/|$)#', $fixturePath) === 1
            || !str_ends_with($fixturePath, '.js')) {
            throw new \InvalidArgumentException('A generated Test262 script requires a safe relative .js fixture path.');
        }

        return 'tests/Test262/Generated/'.substr($fixturePath, 0, -3).'.php';
    }
}
