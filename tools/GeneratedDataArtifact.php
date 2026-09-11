<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools;

final readonly class GeneratedDataArtifact
{
    public string $sourceSha256;

    public string $generatedSha256;

    public function __construct(
        string $source,
        public string $generated,
        public string $label,
        public string $target,
    ) {
        $this->sourceSha256 = hash('sha256', $source);
        $this->generatedSha256 = hash('sha256', $generated);
    }

    public function isReproducible(string $root): bool
    {
        $target = $root . '/' . $this->target;

        return is_file($target) && file_get_contents($target) === $this->generated;
    }

    /** @param array{sourceSha256: string, generatedSha256: string} $manifest */
    public function matchesManifest(array $manifest): bool
    {
        return (
            $manifest['sourceSha256'] === $this->sourceSha256
            && $manifest['generatedSha256'] === $this->generatedSha256
        );
    }

    public function write(string $root): bool
    {
        return file_put_contents($root . '/' . $this->target, $this->generated) !== false;
    }
}
