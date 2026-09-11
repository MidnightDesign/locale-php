<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Test262\AssertionIdentityExtractor;
use Midnight\Intl\Tools\Test262\TimeZonesFixturePipeline;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(TimeZonesFixturePipeline::class)]
final class TimeZonesFixturePipelineTest extends TestCase
{
    public function testItTranslatesTheExplicitlyRegionlessResultToPhpNull(): void
    {
        $source = <<<'JS'
            assert.sameValue(new Intl.Locale('en').getTimeZones(), undefined);
            JS;
        $result = (new TimeZonesFixturePipeline(
            new AssertionIdentityExtractor(),
            'test262-revision',
            'ecma402-revision',
        ))->run($source, 'test/intl402/Locale/prototype/getTimeZones/output-array-undefined.js');

        self::assertTrue($result->isPassing());
        self::assertSame(1, $result->evidence()['executionCount']);
        self::assertStringContainsString("new Locale('en')", $result->generatedScripts()[0]->contents());
        self::assertStringContainsString('assertNull', $result->generatedScripts()[0]->contents());
    }
}
