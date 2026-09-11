<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Test262\AssertionIdentityExtractor;
use Midnight\Intl\Tools\Test262\TextInfoFixturePipeline;
use PHPUnit\Framework\TestCase;

final class TextInfoFixturePipelineTest extends TestCase
{
    public function testItClassifiesAssertionsByTheirSourceConstructRatherThanPosition(): void
    {
        $source = <<<'JS'
            verifyProperty(result, 'direction', {
              writable: true,
              enumerable: true,
              configurable: true
            });
            assert(
              direction === 'rtl' || direction === 'ltr',
              'value of the `direction` property'
            );
            assert.compareArray(Reflect.ownKeys(result), ['direction']);
            JS;
        $result = (new TextInfoFixturePipeline(
            new AssertionIdentityExtractor(),
            'keys',
            'test262-revision',
            'ecma402-revision',
        ))->run($source, 'fixture.js');
        $assertions = $result->evidence()['assertions'];

        self::assertSame(['inapplicable', 'passing', 'passing'], array_column($assertions, 'status'));
    }

    public function testItRejectsAnUnexpectedAssertionShape(): void
    {
        $source = <<<'JS'
            assert.sameValue(result.direction, 'ltr');
            verifyProperty(result, 'direction', {writable: true});
            assert.compareArray(Reflect.ownKeys(result), ['direction']);
            JS;
        $result = (new TextInfoFixturePipeline(
            new AssertionIdentityExtractor(),
            'keys',
            'test262-revision',
            'ecma402-revision',
        ))->run($source, 'fixture.js');

        self::assertFalse($result->isTranslated());
    }

    public function testItRejectsASemanticallyChangedAssertionWithTheExpectedCallName(): void
    {
        $source = <<<'JS'
            assert.sameValue(Object.getPrototypeOf(new Intl.Locale('en').getTextInfo()), null);
            JS;
        $result = (new TextInfoFixturePipeline(
            new AssertionIdentityExtractor(),
            'record',
            'test262-revision',
            'ecma402-revision',
        ))->run($source, 'fixture.js');

        self::assertFalse($result->isTranslated());
    }

    public function testItPreservesWhitespaceInsideStringLiteralsWhileMatchingConstructs(): void
    {
        $source = <<<'JS'
            assert.compareArray(Reflect.ownKeys(result), ['direction']);
            verifyProperty(result, 'direction', {
              writable: true,
              enumerable: true,
              configurable: true
            });
            assert(
              direction === 'r tl' || direction === 'ltr',
              'value of the `direction` property'
            );
            JS;
        $result = (new TextInfoFixturePipeline(
            new AssertionIdentityExtractor(),
            'keys',
            'test262-revision',
            'ecma402-revision',
        ))->run($source, 'fixture.js');

        self::assertFalse($result->isTranslated());
    }
}
