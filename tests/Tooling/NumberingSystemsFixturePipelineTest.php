<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Test262\AssertionIdentityExtractor;
use Midnight\Intl\Tools\Test262\NumberingSystemsFixturePipeline;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(NumberingSystemsFixturePipeline::class)]
final class NumberingSystemsFixturePipelineTest extends TestCase
{
    public function testItTranslatesTheOriginalArrayAssertions(): void
    {
        $source = <<<'JS'
            assert(Array.isArray(new Intl.Locale('en').getNumberingSystems()));
            assert(new Intl.Locale('en').getNumberingSystems().length > 0);
            JS;
        $result = (new NumberingSystemsFixturePipeline(
            new AssertionIdentityExtractor(),
            'test262-revision',
            'ecma402-revision',
        ))->run($source, 'test/intl402/Locale/prototype/getNumberingSystems/output-array.js');

        self::assertTrue($result->isPassing());
        self::assertSame(2, $result->evidence()['executionCount']);
        self::assertStringContainsString("new Locale('en')", $result->generatedScripts()[0]->contents());
        self::assertStringContainsString('assertNotEmpty', $result->generatedScripts()[0]->contents());
    }
}
