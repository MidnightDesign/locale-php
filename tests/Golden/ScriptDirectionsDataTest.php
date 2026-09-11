<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Golden;

use Midnight\Intl\Internal\Data\ScriptDirections;
use PHPUnit\Framework\TestCase;

final class ScriptDirectionsDataTest extends TestCase
{
    public function testPinnedScriptDirectionProjectionIntegrity(): void
    {
        ScriptDirections::assertIntegrity();

        self::assertSame('11299982335beb974c1c63c45265184e759c0f41', ScriptDirections::CLDR_REVISION);
        self::assertCount(181, ScriptDirections::MAP);
        self::assertSame('ltr', ScriptDirections::MAP['Latn']);
        self::assertSame('rtl', ScriptDirections::MAP['Arab']);
        self::assertSame('rtl', ScriptDirections::MAP['Adlm']);
        self::assertSame(
            ['Zyyy' => null, 'Zzzz' => null],
            array_intersect_key(ScriptDirections::MAP, ['Zyyy' => true, 'Zzzz' => true]),
        );
    }
}
