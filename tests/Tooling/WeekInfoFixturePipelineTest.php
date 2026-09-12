<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Test262\AssertionIdentityExtractor;
use Midnight\Intl\Tools\Test262\WeekInfoFixturePipeline;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(WeekInfoFixturePipeline::class)]
final class WeekInfoFixturePipelineTest extends TestCase
{
    public function testItTranslatesFirstDayIdentifiersThroughTheSharedHarness(): void
    {
        $root = dirname(__DIR__, 2);
        $path = 'test/intl402/Locale/prototype/getWeekInfo/firstDay-by-id.js';
        $source = file_get_contents($root . '/tests/Test262/upstream/' . $path);
        self::assertNotFalse($source);

        $result = (new WeekInfoFixturePipeline(
            new AssertionIdentityExtractor(),
            'test262-revision',
            'ecma402-revision',
        ))->run($source, $path);

        self::assertTrue($result->isPassing());
        self::assertSame(7, $result->evidence()['executionCount']);
        self::assertStringContainsString('WeekInfoAssertion', $result->generatedScripts()[0]->contents());
    }

    public function testItPreservesEveryRegionPriorityAssertion(): void
    {
        $root = dirname(__DIR__, 2);
        $path = 'test/intl402/Locale/prototype/getWeekInfo/region-priority.js';
        $source = file_get_contents($root . '/tests/Test262/upstream/' . $path);
        self::assertNotFalse($source);

        $result = (new WeekInfoFixturePipeline(
            new AssertionIdentityExtractor(),
            'test262-revision',
            'ecma402-revision',
        ))->run($source, $path);

        self::assertTrue($result->isPassing());
        self::assertSame(14, $result->evidence()['executionCount']);
        self::assertCount(3, $result->evidence()['assertions']);
    }
}
