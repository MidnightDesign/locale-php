<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion;
use Midnight\Intl\Tools\PhpExporter;

final class ConstructorFixturePipeline implements FixturePipeline
{
    /** @param list<string> $representations */
    public function __construct(
        private readonly ConstructorFixtureTranslator $translator,
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly array $representations,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
    ) {
    }

    public function run(string $source, string $fixturePath): FixtureResult
    {
        try {
            $translation = $this->translator->translate($source, $fixturePath);
        } catch (TranslationGap $gap) {
            return FixtureResult::translationGap(
                $fixturePath,
                $source,
                $this->representations,
                $this->assertionIdentities->extract($source, $fixturePath),
                $gap,
            );
        }

        $generatedCases = [];
        $executionResults = [];
        foreach ($translation['cases'] as $case) {
            foreach ($this->representations as $representation) {
                $executionId = $case['id'].'-'.$representation;
                $generatedCases[$executionId] = [
                    $case['assertionId'],
                    $case['tag'],
                    $case['optionValue'],
                    $representation,
                    $case['expected'],
                ];
                $executionResults[] = [
                    'id' => $executionId,
                    'assertionId' => $case['assertionId'],
                    'representation' => $representation,
                    ...ConstructorOptionAssertion::evaluate(
                        $case['tag'],
                        'script',
                        $case['optionValue'],
                        $representation,
                        $case['expected'],
                    ),
                ];
            }
        }

        $adaptations = [
            'The JavaScript options object is exercised as both an associative array and a plain PHP object.',
            'The JavaScript toString method object is represented by an equivalent PHP Stringable object.',
            'The JavaScript loop is expanded into named PHPUnit data sets without multiplying upstream assertion coverage.',
        ];
        $assertions = [];
        foreach ($translation['assertions'] as $assertion) {
            $results = array_values(array_filter(
                $executionResults,
                static fn (array $result): bool => $result['assertionId'] === $assertion['id'],
            ));
            $passing = count(array_filter(
                $results,
                static fn (array $result): bool => $result['status'] === 'passing',
            )) === count($results);
            $assertions[] = [
                ...$assertion,
                'status' => $passing ? 'passing' : 'failing',
                'adaptations' => $adaptations,
                'executions' => $results,
            ];
        }
        $failureCount = count(array_filter(
            $executionResults,
            static fn (array $result): bool => $result['status'] === 'failing',
        ));

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $failureCount === 0 ? 'passing' : 'failing',
            $this->representations,
            $assertions,
            count($executionResults),
            $failureCount,
            ['tests/Test262/Generated/ConstructorOptionsScriptValidTest.php' => $this->render($generatedCases, $fixturePath)],
        );
    }

    /**
     * @param array<string, array{
     *     string,
     *     string,
     *     array{type: 'null'}|array{type: 'string'|'stringable', value: string},
     *     string,
     *     string
     * }> $cases
     */
    private function render(array $cases, string $fixturePath): string
    {
        $caseExport = preg_replace('/[ \t]+$/m', '', PhpExporter::export($cases));
        if ($caseExport === null) {
            throw new \RuntimeException('Unable to format the generated constructor cases.');
        }

        $generated = <<<PHP
<?php

declare(strict_types=1);

// Copyright 2018 André Bargull; Igalia, S.L. All rights reserved.
// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: {$fixturePath} at Test262 {$this->test262Revision}.
// Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ConstructorOptionsScriptValidTest extends TestCase
{
    /**
     * @return array<string, array{
     *     string,
     *     string,
     *     array{type: 'null'}|array{type: 'string'|'stringable', value: string},
     *     string,
     *     string
     * }>
     */
    public static function cases(): array
    {
        return {$caseExport};
    }

    /** @param array{type: 'null'}|array{type: 'string'|'stringable', value: string} \$optionValue */
    #[DataProvider('cases')]
    public function testTranslatedAssertions(
        string \$assertionId,
        string \$tag,
        array \$optionValue,
        string \$representation,
        string \$expected,
    ): void {
        \$result = ConstructorOptionAssertion::evaluate(
            \$tag,
            'script',
            \$optionValue,
            \$representation,
            \$expected,
        );

        self::assertSame(
            'passing',
            \$result['status'],
            \$assertionId.': '.(\$result['failure'] ?? 'unknown failure'),
        );
    }
}
PHP;

        return $generated."\n";
    }
}
