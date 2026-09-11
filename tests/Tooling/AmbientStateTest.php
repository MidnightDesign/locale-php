<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Locale;
use Midnight\Intl\Spec\Locale as SpecLocale;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Locale::class)]
#[CoversClass(SpecLocale::class)]
final class AmbientStateTest extends TestCase
{
    public function testPublicResultsDoNotDependOnPoisonedProcessState(): void
    {
        $timezone = date_default_timezone_get();
        $processLocale = setlocale(LC_ALL, '0');
        $intlLocale = extension_loaded('intl') ? \Locale::getDefault() : null;

        try {
            date_default_timezone_set('Pacific/Chatham');
            setlocale(LC_ALL, 'C.UTF-8', 'en_US.UTF-8', 'C');
            if (extension_loaded('intl')) {
                \Locale::setDefault('ar_EG');
            }

            self::assertSame('he-Zinh-NZ', (new Locale('iw-Qaai-554'))->toString());
            self::assertSame(
                'fr-Cyrl-CA',
                (new SpecLocale('en-US', [
                    'language' => 'fr',
                    'script' => 'cyrl',
                    'region' => 'ca',
                ]))->toString(),
            );
        } finally {
            date_default_timezone_set($timezone);
            if ($processLocale !== false) {
                setlocale(LC_ALL, $processLocale);
            }
            if ($intlLocale !== null) {
                \Locale::setDefault($intlLocale);
            }
        }
    }
}
