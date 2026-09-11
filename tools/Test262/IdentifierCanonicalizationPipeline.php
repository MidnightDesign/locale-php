<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Spec\Locale;
use Midnight\Intl\Tools\PhpExporter;

final class IdentifierCanonicalizationPipeline implements FixturePipeline
{
    public function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
    ) {
    }

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $identities = $this->assertionIdentities->extract($source, $fixturePath);
        if (count($identities) !== 1 || $identities[0]['call'] !== 'assert.sameValue') {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['direct'],
                $identities,
                new TranslationGap('Expected one parameterized canonicalization assertion.'),
            );
        }

        preg_match_all('/^\s*"(?<tag>[^"]+)":\s*"(?<expected>[^"]+)",?$/m', $source, $matches, PREG_SET_ORDER);
        if ($matches === []) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['direct'],
                $identities,
                new TranslationGap('No canonicalization vectors were found.'),
            );
        }

        $cases = [];
        $failures = 0;
        foreach ($matches as $match) {
            $cases[$match['tag']] = $match['expected'];
            if ((new Locale($match['tag']))->toString() !== $match['expected']) {
                ++$failures;
            }
        }

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $failures === 0 ? 'passing' : 'failing',
            ['direct'],
            [[
                ...$identities[0],
                'status' => $failures === 0 ? 'passing' : 'failing',
                'adaptations' => [
                    'The JavaScript object iteration is expanded into fixture-local PHP checks.',
                ],
            ]],
            count($cases),
            $failures,
            [GeneratedScript::primary($fixturePath, $this->render($cases, $fixturePath))],
        );
    }

    /** @param array<string, string> $cases */
    private function render(array $cases, string $fixturePath): string
    {
        $export = preg_replace('/[ \t]+$/m', '', PhpExporter::export($cases));
        if ($export === null) {
            throw new \RuntimeException('Unable to format the generated identifier cases.');
        }

        $generated = <<<PHP
<?php

declare(strict_types=1);

// Copyright 2018 André Bargull; Igalia, S.L. All rights reserved.
// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: {$fixturePath} at Test262 {$this->test262Revision}; notice: tests/Test262/upstream/LICENSE.
// Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Assert;

foreach ({$export} as \$tag => \$expected) {
    Assert::assertSame(\$expected, (new Locale(\$tag))->toString());
}
PHP;

        return $generated."\n";
    }
}
