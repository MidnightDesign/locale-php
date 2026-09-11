<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262\Fixtures;

use Midnight\Intl\Tools\Test262\AssertionIdentityExtractor;

final readonly class FixtureCatalogContext
{
    public function __construct(
        public \Closure $mappedStatePipeline,
        public \Closure $stateExpectations,
        public \Closure $mappedOptionPipeline,
        public \Closure $invalidCases,
        public \Closure $stringValue,
        public \Closure $optionCases,
        public \Closure $forKeyword,
        public AssertionIdentityExtractor $assertionIdentities,
        /** @var list<string> */
        public array $representations,
        public string $test262Revision,
        public string $ecma402Revision,
        public \Closure $sourceBoundPipeline,
    ) {}
}
