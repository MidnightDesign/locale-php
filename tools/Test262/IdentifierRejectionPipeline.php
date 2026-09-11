<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Spec\Locale;
use Midnight\Intl\Tools\PhpExporter;

final class IdentifierRejectionPipeline implements FixturePipeline
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
        $tags = $this->extractTags($source);
        if ($tags === [] || !in_array('assert.throws', array_column($identities, 'call'), true)) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                ['direct'],
                $identities,
                new TranslationGap('No parameterized RangeError rejection assertion was found.'),
            );
        }

        $failures = 0;
        foreach ($tags as $tag) {
            try {
                new Locale($tag);
                ++$failures;
            } catch (RangeError) {
            } catch (\Throwable) {
                ++$failures;
            }
        }

        $assertions = array_map(
            static fn (array $identity): array => [
                ...$identity,
                'status' => $identity['call'] === 'assert.throws' && $failures > 0 ? 'failing' : 'passing',
                'adaptations' => $identity['call'] === 'assert.throws'
                    ? ['The JavaScript helper calls are expanded into fixture-local PHP checks.']
                    : ['JavaScript constructor availability is represented by direct PHP class availability.'],
            ],
            $identities,
        );

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $failures === 0 ? 'passing' : 'failing',
            ['direct'],
            $assertions,
            count($tags) + (count($identities) > 1 ? 1 : 0),
            $failures,
            [GeneratedScript::primary($fixturePath, $this->render($tags, $fixturePath))],
        );
    }

    /** @return list<string> */
    private function extractTags(string $source): array
    {
        if (preg_match('/const invalidLanguageTags = \[(?<values>.*?)\];/s', $source, $list) === 1) {
            preg_match_all('/"([^"]*)"/', $list['values'], $matches);

            return $matches[1];
        }

        preg_match_all('/^mustReject\("([^"]+)"\);/m', $source, $matches);

        return $matches[1];
    }

    /** @param list<string> $tags */
    private function render(array $tags, string $fixturePath): string
    {
        $export = preg_replace('/[ \t]+$/m', '', PhpExporter::export($tags));
        if ($export === null) {
            throw new \RuntimeException('Unable to format the generated rejection cases.');
        }
        $generated = <<<PHP
<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: {$fixturePath} at Test262 {$this->test262Revision}; notice: tests/Test262/upstream/LICENSE.
// Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

use Midnight\Intl\Exception\RangeError;
use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Assert;

foreach ({$export} as \$tag) {
    \$rejected = false;
    try {
        new Locale(\$tag);
    } catch (RangeError) {
        \$rejected = true;
    }

    Assert::assertTrue(\$rejected, 'Expected RangeError for '.\$tag.'.');
}
PHP;

        return $generated."\n";
    }
}
