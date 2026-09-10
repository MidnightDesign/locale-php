<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Golden;

use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LocaleIdentifierTest extends TestCase
{
    /** @return iterable<string, array{string, string}> */
    public static function canonicalIdentifiers(): iterable
    {
        yield 'compound language and region aliases' => [
            'sh-BU-polytoni',
            'sr-Latn-MM-polyton',
        ];
        yield 'territory alias follows the language likely region' => [
            'hy-SU',
            'hy-AM',
        ];
        yield 'variant alias' => [
            'en-heploc',
            'en-alalc97',
        ];
        yield 'subdivision, region override, and type aliases' => [
            'en-u-sd-cn11-rg-buzzzz-ca-ethiopic-amete-alem',
            'en-u-ca-ethioaa-rg-mmzzzz-sd-cnbj',
        ];
        yield 'transformed fields and singleton extensions' => [
            'en-t-i0-handwrit-h0-hybrid-z-foobar-a-names',
            'en-a-names-t-h0-hybrid-i0-handwrit-z-foobar',
        ];
        yield 'true Unicode keyword type is omitted' => [
            'en-u-kn-true',
            'en-u-kn',
        ];
        yield 'syntactically valid unregistered subtags' => [
            'ZZZZZZ-Qaaa-QZ-abcde',
            'zzzzzz-Qaaa-QZ-abcde',
        ];
    }

    #[DataProvider('canonicalIdentifiers')]
    public function testPinnedGoldenCanonicalization(string $input, string $expected): void
    {
        self::assertSame($expected, (new Locale($input))->toString());
    }
}
