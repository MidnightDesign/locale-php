<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Test262\AssertionIdentityExtractor;
use Midnight\Intl\Tools\Test262\CollationsFixturePipeline;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CollationsFixturePipeline::class)]
final class CollationsFixturePipelineTest extends TestCase
{
    public function testItPreservesBothExplicitCollationAssertionSites(): void
    {
        $source = <<<'JS'
            assert.compareArray(new Intl.Locale(fullTag).getCollations(), [collation]);
            assert.compareArray(new Intl.Locale(baseName, { collation: collation }).getCollations(), [collation]);
            JS;
        $result = (new CollationsFixturePipeline(
            new AssertionIdentityExtractor(),
            'test262-revision',
            'ecma402-revision',
        ))->run($source, 'test/intl402/Locale/prototype/getCollations/collation-keyword.js');

        self::assertTrue($result->isPassing());
        self::assertSame(2, $result->evidence()['sourceAssertionCount']);
        self::assertSame(10, $result->evidence()['executionCount']);
        self::assertStringContainsString(
            "CollationsFixtureAssertions::evaluate('collation-keyword.js')",
            $result->generatedScripts()[0]->contents(),
        );
    }
}
