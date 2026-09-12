<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Test262\AssertionIdentityExtractor;
use Midnight\Intl\Tools\Test262\LocalePreferenceFixturePipeline;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LocalePreferenceFixturePipeline::class)]
final class LocalePreferenceFixturePipelineTest extends TestCase
{
    public function testItTranslatesCalendarRegionPriorityThroughTheSharedHarness(): void
    {
        $root = dirname(__DIR__, 2);
        $path = 'test/intl402/Locale/prototype/getCalendars/region-priority.js';
        $source = file_get_contents($root . '/tests/Test262/upstream/' . $path);
        self::assertNotFalse($source);

        $result = (new LocalePreferenceFixturePipeline(
            new AssertionIdentityExtractor(),
            'test262-revision',
            'ecma402-revision',
        ))->run($source, $path);

        self::assertTrue($result->isPassing());
        self::assertSame(2, $result->evidence()['executionCount']);
        self::assertStringContainsString('LocalePreferenceAssertion', $result->generatedScripts()[0]->contents());
        self::assertStringContainsString("'getCalendars'", $result->generatedScripts()[0]->contents());
    }

    public function testItPreservesHourCycleValueValidation(): void
    {
        $root = dirname(__DIR__, 2);
        $path = 'test/intl402/Locale/prototype/getHourCycles/output-array-values.js';
        $source = file_get_contents($root . '/tests/Test262/upstream/' . $path);
        self::assertNotFalse($source);

        $result = (new LocalePreferenceFixturePipeline(
            new AssertionIdentityExtractor(),
            'test262-revision',
            'ecma402-revision',
        ))->run($source, $path);

        self::assertTrue($result->isPassing());
        self::assertSame(1, $result->evidence()['executionCount']);
    }
}
