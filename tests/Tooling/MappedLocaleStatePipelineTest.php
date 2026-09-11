<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tests\Test262\Harness\LocaleStateAssertion;
use Midnight\Intl\Tests\Test262\Harness\LocaleStateExpectation;
use Midnight\Intl\Tools\Test262\AssertionIdentityExtractor;
use Midnight\Intl\Tools\Test262\MappedLocaleStatePipeline;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LocaleStateAssertion::class)]
#[CoversClass(LocaleStateExpectation::class)]
#[CoversClass(MappedLocaleStatePipeline::class)]
final class MappedLocaleStatePipelineTest extends TestCase
{
    public function testItUsesTheSharedEvaluatorForAllStateExpectations(): void
    {
        $source = "assert.sameValue(new Intl.Locale('en').toString(), 'en');";
        $pipeline = $this->pipeline([[
            'tag' => 'en',
            'expectations' => [
                ['assertion' => 0, 'property' => 'toString', 'expected' => 'en'],
                ['assertion' => 0, 'property' => 'language', 'expected' => 'en'],
            ],
        ]]);

        $result = $pipeline->run($source, 'fixture.js');

        self::assertTrue($result->isPassing());
        self::assertSame(2, $result->evidence()['executionCount']);
        $generated = $result->generatedScripts()[0]->contents();
        self::assertStringContainsString('LocaleStateAssertion::evaluate', $generated);
        self::assertStringNotContainsString('new Locale', $generated);
    }

    public function testItBlocksGenerationWhenASourceAssertionHasNoExpectation(): void
    {
        $source = <<<'JS'
            assert.sameValue(new Intl.Locale('en').toString(), 'en');
            assert.sameValue(new Intl.Locale('de').toString(), 'de');
            JS;
        $pipeline = $this->pipeline([[
            'tag' => 'en',
            'expectations' => [['assertion' => 0, 'property' => 'toString', 'expected' => 'en']],
        ]]);

        self::assertTrue($pipeline->run($source, 'fixture.js')->blocksGeneration());
    }

    /**
     * @param list<array{
     *     tag: string,
     *     options?: array<string, mixed>,
     *     expectations: list<array{assertion: int, property: string, expected: string|bool|null}>
     * }> $scenarios
     */
    private function pipeline(array $scenarios): MappedLocaleStatePipeline
    {
        return new MappedLocaleStatePipeline(
            new AssertionIdentityExtractor(),
            'test262-revision',
            'ecma402-revision',
            $scenarios,
        );
    }
}
