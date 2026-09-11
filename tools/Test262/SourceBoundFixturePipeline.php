<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

final class SourceBoundFixturePipeline implements FixturePipeline
{
    /** @param list<string> $representations */
    public function __construct(
        private readonly FixturePipeline $pipeline,
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly array $representations,
        private readonly string $sourceSha256,
    ) {
    }

    public function run(string $source, string $fixturePath): FixtureResult
    {
        if (!hash_equals($this->sourceSha256, hash('sha256', $source))) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                $this->representations,
                $this->assertionIdentities->extract($source, $fixturePath),
                new TranslationGap('The source-bound translation must be reviewed for this fixture revision.'),
            );
        }

        return $this->pipeline->run($source, $fixturePath);
    }
}
