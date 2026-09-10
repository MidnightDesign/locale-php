<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Ci\ExtensionMode;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ExtensionMode::class)]
final class CiExtensionModeTest extends TestCase
{
    public function testItProvesIntlIsAbsent(): void
    {
        self::assertSame([
            'mode' => 'absent',
            'intlLoaded' => false,
            'eligibleNativePaths' => [],
            'exercisedNativePaths' => [],
            'fallbackReason' => 'ext-intl-absent',
        ], ExtensionMode::trace('absent', false, [], []));
    }

    public function testItProvesNativeAccelerationIsDisabled(): void
    {
        self::assertSame([
            'mode' => 'disabled',
            'intlLoaded' => true,
            'eligibleNativePaths' => [],
            'exercisedNativePaths' => [],
            'fallbackReason' => 'native-acceleration-disabled',
        ], ExtensionMode::trace('disabled', true, [], []));
    }

    public function testItRecordsWhenNoNativePathIsEligible(): void
    {
        self::assertSame([
            'mode' => 'native',
            'intlLoaded' => true,
            'eligibleNativePaths' => [],
            'exercisedNativePaths' => [],
            'fallbackReason' => 'no-native-path-implemented',
        ], ExtensionMode::trace('native', true, [], []));
    }

    public function testItRejectsADeclaredModeThatDidNotActuallyRun(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('requires ext-intl to be absent');

        ExtensionMode::trace('absent', true, [], []);
    }

    public function testItRejectsAnEligibleNativePathThatWasNotExercised(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Eligible native paths were not exercised: canonicalize');

        ExtensionMode::trace('native', true, ['canonicalize'], []);
    }
}
