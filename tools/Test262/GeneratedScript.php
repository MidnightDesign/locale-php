<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

final class GeneratedScript
{
    private function __construct(
        private readonly string $path,
        private readonly string $identity,
        private readonly ?string $variant,
        private readonly string $contents,
    ) {
    }

    public static function primary(string $fixturePath, string $contents): self
    {
        return new self(self::pathFor($fixturePath), $fixturePath, null, $contents);
    }

    public static function variant(string $fixturePath, string $variant, string $contents): self
    {
        self::pathFor($fixturePath);
        if (preg_match('/\A[a-z0-9][a-z0-9-]*\z/D', $variant) !== 1) {
            throw new \InvalidArgumentException('A generated Test262 script variant requires a stable kebab-case name.');
        }

        $primaryPath = self::pathFor($fixturePath);

        return new self(
            substr($primaryPath, 0, -4).'.'.$variant.'.php',
            $fixturePath.' ['.$variant.']',
            $variant,
            $contents,
        );
    }

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

    public static function isGeneratedPath(string $path): bool
    {
        return str_starts_with($path, 'tests/Test262/Generated/')
            && str_ends_with($path, '.php')
            && !str_contains($path, '\\')
            && preg_match('#(^|/)\.\.(/|$)#', $path) !== 1;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function contents(): string
    {
        return $this->contents;
    }

    /** @return array{path: string, identity: string, variant: string|null} */
    public function evidence(): array
    {
        return [
            'path' => $this->path,
            'identity' => $this->identity,
            'variant' => $this->variant,
        ];
    }
}
