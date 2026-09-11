<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Spec;

use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Locale::class)]
final class LocaleTest extends TestCase
{
    public function testExplicitNullOptionsUseTheSpecTypeError(): void
    {
        $this->expectException(TypeError::class);
        new Locale('en', null);
    }

    public function testItAcceptsStringableObjects(): void
    {
        $fromObject = new Locale(new class() implements \Stringable {
            public function __toString(): string
            {
                return 'de-latn-de';
            }
        });

        self::assertSame('de-Latn-DE', $fromObject->toString());
    }
}
