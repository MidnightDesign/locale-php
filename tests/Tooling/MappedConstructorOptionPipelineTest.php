<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Test262\AssertionIdentityExtractor;
use Midnight\Intl\Tools\Test262\MappedConstructorOptionPipeline;
use Midnight\Intl\Tools\Test262\SourceBoundFixturePipeline;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MappedConstructorOptionPipeline::class)]
#[CoversClass(SourceBoundFixturePipeline::class)]
final class MappedConstructorOptionPipelineTest extends TestCase
{
    public function testItBlocksGenerationWhenASourceAssertionHasNoExecution(): void
    {
        $source = <<<'JS'
            assert.sameValue(new Intl.Locale('en').toString(), 'en');
            assert.sameValue(new Intl.Locale('de').toString(), 'de');
            JS;

        $result = $this->pipeline([
            ['assertion' => 0, 'tag' => 'en', 'value' => ['type' => 'undefined'], 'expected' => 'en'],
        ])->run($source, 'fixture.js');

        self::assertTrue($result->blocksGeneration());
    }

    public function testItRejectsAMappingWhenThePinnedSourceChanges(): void
    {
        $source = "assert.sameValue(new Intl.Locale('en').toString(), 'en');";
        $changedSource = str_replace("'en');", "'de');", $source);

        $result = (new SourceBoundFixturePipeline(
            $this->pipeline([
                ['assertion' => 0, 'tag' => 'en', 'value' => ['type' => 'undefined'], 'expected' => 'en'],
            ]),
            new AssertionIdentityExtractor(),
            ['associative_array'],
            hash('sha256', $source),
        ))->run($changedSource, 'fixture.js');

        self::assertTrue($result->blocksGeneration());
    }

    /**
     * @param list<array{
     *     assertion: int,
     *     tag: string,
     *     value: array{type: 'undefined'},
     *     expected: string
     * }> $cases
     */
    private function pipeline(array $cases): MappedConstructorOptionPipeline
    {
        return new MappedConstructorOptionPipeline(
            new AssertionIdentityExtractor(),
            ['associative_array'],
            'test262-revision',
            'ecma402-revision',
            'language',
            'Generated.php',
            'GeneratedTest',
            $cases,
        );
    }
}
