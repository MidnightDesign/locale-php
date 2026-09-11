<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Spec\Locale;
use Midnight\Intl\Tools\PhpExporter;

final class RemoveLikelySubtagsPipeline implements FixturePipeline
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
        $cases = LikelySubtagsPipeline::objectMap($source, 'testDataMinimal');
        if (count($identities) !== 2 || $cases === []) {
            return FixtureResult::translationGap($fixturePath, $source, ['direct'], $identities, new TranslationGap(
                'Expected one minimal map and two parameterized assertions.',
            ));
        }

        $failures = 0;
        foreach ($cases as $tag => $minimal) {
            $failures += (new Locale($minimal))->minimize()->toString() === $minimal ? 0 : 1;
            $failures += (new Locale($tag))->minimize()->toString() === $minimal ? 0 : 1;
        }
        $status = $failures === 0 ? 'passing' : 'failing';

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $status,
            ['direct'],
            array_map(static fn (array $identity): array => [
                ...$identity,
                'status' => $status,
                'adaptations' => ['JavaScript object iteration is expanded into named PHPUnit data sets.'],
            ], $identities),
            count($cases) * 2,
            $failures,
            ['tests/Test262/Generated/RemoveLikelySubtagsTest.php' => $this->render($cases, $fixturePath)],
        );
    }

    /** @param array<string, string> $cases */
    private function render(array $cases, string $fixturePath): string
    {
        $export = preg_replace('/[ \t]+$/m', '', PhpExporter::export($cases))
            ?? throw new \RuntimeException('Unable to format remove-likely-subtag cases.');

        return <<<PHP
<?php

declare(strict_types=1);

// Copyright 2020 André Bargull. All rights reserved.
// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: {$fixturePath} at Test262 {$this->test262Revision}; notice: tests/Test262/upstream/LICENSE.
// Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class RemoveLikelySubtagsTest extends TestCase
{
    /** @return iterable<string, array{string, string}> */
    public static function cases(): iterable
    {
        foreach ({$export} as \$tag => \$minimal) {
            yield \$tag.' fixed point' => [\$minimal, \$minimal];
            yield \$tag => [\$tag, \$minimal];
        }
    }

    #[DataProvider('cases')]
    public function testTranslatedRemoveLikelySubtagsAssertions(string \$tag, string \$expected): void
    {
        self::assertSame(\$expected, (new Locale(\$tag))->minimize()->toString());
    }
}
PHP."\n";
    }
}
