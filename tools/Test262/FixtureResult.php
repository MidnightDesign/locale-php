<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

final class FixtureResult
{
    /**
     * @param list<string>                                                       $phpRepresentations
     * @param list<array{id: string, line: int, column: int, call: string, sha256: string}> $identities
     */
    public static function translationGap(
        string $path,
        string $source,
        array $phpRepresentations,
        array $identities,
        TranslationGap $gap,
    ): self {
        $reason = 'Translation gap: '.$gap->getMessage();
        $assertions = array_map(
            static fn (array $assertion): array => [
                ...$assertion,
                'status' => 'translation_gap',
                'reason' => $reason,
            ],
            $identities,
        );

        return new self(
            $path,
            hash('sha256', $source),
            'translation_gap',
            $phpRepresentations,
            $assertions,
            0,
            0,
            [],
            $reason,
        );
    }

    /**
     * @param 'passing'|'failing'|'partially_translated'|'translation_gap' $status
     * @param list<string>                                                $phpRepresentations
     * @param list<array<string, mixed>>                                  $assertions
     * @param array<string, string>                                       $generatedFiles
     */
    public function __construct(
        private readonly string $path,
        private readonly string $sourceHash,
        private readonly string $status,
        private readonly array $phpRepresentations,
        private readonly array $assertions,
        private readonly int $executionCount,
        private readonly int $executionFailures,
        private readonly array $generatedFiles,
        private readonly ?string $reason = null,
    ) {
    }

    /** @return array<string, string> */
    public function generatedFiles(): array
    {
        return $this->generatedFiles;
    }

    /** @return list<string> */
    public function assertionIds(): array
    {
        $ids = [];
        foreach ($this->assertions as $assertion) {
            if (is_string($assertion['id'] ?? null)) {
                $ids[] = $assertion['id'];
            }
        }

        return $ids;
    }

    public function isPassing(): bool
    {
        return $this->status === 'passing';
    }

    public function isTranslated(): bool
    {
        return $this->status !== 'translation_gap';
    }

    public function isPartiallyTranslated(): bool
    {
        return $this->status === 'partially_translated';
    }

    public function blocksGeneration(): bool
    {
        return in_array($this->status, ['failing', 'translation_gap'], true);
    }

    public function executionFailures(): int
    {
        return $this->executionFailures;
    }

    public function translationGapCount(): int
    {
        return count(array_filter(
            $this->assertions,
            static fn (array $assertion): bool => ($assertion['status'] ?? null) === 'translation_gap',
        ));
    }

    /**
     * @return array{
     *     path: string,
     *     sha256: string,
     *     status: string,
     *     reason: string|null,
     *     sourceAssertionCount: int,
     *     executionCount: int,
     *     executionFailures: int,
     *     phpRepresentations: list<string>,
     *     assertions: list<array<string, mixed>>
     * }
     */
    public function evidence(): array
    {
        return [
            'path' => $this->path,
            'sha256' => $this->sourceHash,
            'status' => $this->status,
            'reason' => $this->reason,
            'sourceAssertionCount' => count($this->assertions),
            'executionCount' => $this->executionCount,
            'executionFailures' => $this->executionFailures,
            'phpRepresentations' => $this->phpRepresentations,
            'assertions' => $this->assertions,
        ];
    }
}
