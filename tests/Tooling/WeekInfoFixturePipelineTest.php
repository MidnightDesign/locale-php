<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Test262\AssertionIdentityExtractor;
use Midnight\Intl\Tools\Test262\FixtureResult;
use Midnight\Intl\Tools\Test262\WeekInfoFixturePipeline;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(WeekInfoFixturePipeline::class)]
final class WeekInfoFixturePipelineTest extends TestCase
{
    public function testItTranslatesFirstDayIdentifiersThroughTheSharedHarness(): void
    {
        $result = self::runFixture('firstDay-by-id.js');

        self::assertTrue($result->isPassing());
        self::assertSame(7, $result->evidence()['executionCount']);
        self::assertStringContainsString('WeekInfoAssertion', $result->generatedScripts()[0]->contents());
    }

    public function testItPreservesEveryRegionPriorityAssertion(): void
    {
        $result = self::runFixture('region-priority.js');

        self::assertTrue($result->isPassing());
        self::assertSame(14, $result->evidence()['executionCount']);
        self::assertCount(3, $result->evidence()['assertions']);
    }

    public function testItCountsEveryFirstDayOptionAssertionExecution(): void
    {
        $result = self::runFixture('firstDay-by-option.js');

        self::assertTrue($result->isPassing());
        self::assertSame(46, $result->evidence()['executionCount']);
    }

    private static function runFixture(string $fixture): FixtureResult
    {
        $path = 'test/intl402/Locale/prototype/getWeekInfo/' . $fixture;
        $root = dirname(__DIR__, 2);
        $source = file_get_contents($root . '/tests/Test262/upstream/' . $path);
        self::assertNotFalse($source);

        return (new WeekInfoFixturePipeline(
            new AssertionIdentityExtractor(),
            'test262-revision',
            'ecma402-revision',
        ))->run($source, $path);
    }
}
