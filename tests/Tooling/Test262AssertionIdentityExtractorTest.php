<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Test262\AssertionIdentityExtractor;
use PHPUnit\Framework\TestCase;

final class Test262AssertionIdentityExtractorTest extends TestCase
{
    public function testItFindsOnlyExecutableAssertionCalls(): void
    {
        $source = <<<'JS'
            // assert.sameValue('line comment');
            /* verifyProperty('block comment'); */
            const quoted = "assert.sameValue('string')";
            const pattern = /assert\.sameValue\(([^)]+)\)/u;
            const template = `assert.sameValue('template text') ${assert.sameValue(nested(value), true)}`;
            assert . sameValue(
              value,
              callWithNestedArguments(one, two),
            );
            verifyProperty(value, 'name', {value: true});
            JS;

        $assertions = (new AssertionIdentityExtractor())->extract($source, 'fixture.js');

        self::assertSame(['assert.sameValue', 'assert.sameValue', 'verifyProperty'], array_column($assertions, 'call'));
        self::assertSame([5, 6, 10], array_column($assertions, 'line'));
        foreach ($assertions as $assertion) {
            self::assertSame(64, strlen($assertion['sha256']));
        }
    }

    public function testDivisionDoesNotHideAFollowingAssertion(): void
    {
        $source = <<<'JS'
            const ratio = total / count;
            assert.sameValue(ratio, 2);
            JS;

        $assertions = (new AssertionIdentityExtractor())->extract($source, 'fixture.js');

        self::assertCount(1, $assertions);
        self::assertSame('assert.sameValue', $assertions[0]['call']);
    }
}
