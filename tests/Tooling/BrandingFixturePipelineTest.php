<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Test262\AssertionIdentityExtractor;
use Midnight\Intl\Tools\Test262\BrandingFixtureMode;
use Midnight\Intl\Tools\Test262\BrandingFixturePipeline;
use PHPUnit\Framework\TestCase;

final class BrandingFixturePipelineTest extends TestCase
{
    public function testItRecordsEachIndividualReceiverCheckWithoutPositionalAssumptions(): void
    {
        $path = 'test/intl402/Locale/prototype/getTextInfo/branding.js';
        $source = file_get_contents(dirname(__DIR__) . '/Test262/upstream/' . $path);
        self::assertNotFalse($source);

        $result = (new BrandingFixturePipeline(
            new AssertionIdentityExtractor(),
            'getTextInfo',
            BrandingFixtureMode::IndividualMethodIncludingConstructor,
            'test262-revision',
            'ecma402-revision',
        ))->run($source, $path);
        $evidence = $result->evidence();
        /** @var list<array{executions?: list<array{id: string}>}> $assertions */
        $assertions = $evidence['assertions'];
        $executionCounts = [];
        $executionIds = [];
        foreach (array_slice($assertions, 1) as $assertion) {
            $executions = $assertion['executions'] ?? [];
            $executionCounts[] = count($executions);
            foreach ($executions as $execution) {
                $executionIds[] = $execution['id'];
            }
        }

        self::assertTrue($result->isTranslated());
        self::assertSame(10, $evidence['executionCount']);
        self::assertSame(array_fill(0, 9, 1), $executionCounts);
        self::assertSame(
            [
                'undefined',
                'null',
                'true',
                'empty-string',
                'symbol',
                'number',
                'plain-object',
                'constructor',
                'uninitialized-locale',
            ],
            $executionIds,
        );
        self::assertSame(0, $result->executionFailures());
    }

    public function testItCountsAnUnavailableMethodAlongsideItsReceiverFailures(): void
    {
        $path = 'test/intl402/Locale/prototype/toString/branding.js';
        $source = file_get_contents(dirname(__DIR__) . '/Test262/upstream/' . $path);
        self::assertNotFalse($source);

        $result = (new BrandingFixturePipeline(
            new AssertionIdentityExtractor(),
            'missingMethod',
            BrandingFixtureMode::Method,
            'test262-revision',
            'ecma402-revision',
        ))->run($source, $path);
        $evidence = $result->evidence();

        self::assertSame('failing', $evidence['status']);
        self::assertSame('failing', $evidence['assertions'][0]['status']);
        self::assertSame(9, $result->executionFailures());
    }
}
