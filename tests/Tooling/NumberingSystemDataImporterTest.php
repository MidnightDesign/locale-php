<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\NumberingSystemDataImporter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(NumberingSystemDataImporter::class)]
final class NumberingSystemDataImporterTest extends TestCase
{
    public function testItProjectsInheritedDefaultsAndRequiredAvailabilityAliases(): void
    {
        $projection = NumberingSystemDataImporter::project([
            'root' => '<numbers><defaultNumberingSystem>latn</defaultNumberingSystem></numbers>',
            'fa' => '<numbers><defaultNumberingSystem>arabext</defaultNumberingSystem></numbers>',
            'fa_Arab' => '<numbers/>',
            'fa_Arab_IR' => '<numbers/>',
            'es_419' => '<numbers><defaultNumberingSystem>latn</defaultNumberingSystem></numbers>',
            'es_AR' => '<numbers/>',
        ], '<parentLocale parent="es_419" locales="es_AR"/>');

        self::assertSame('arabext', $projection['defaults']['fa-Arab-IR']);
        self::assertSame('latn', $projection['defaults']['es-AR']);
        self::assertSame('fa-Arab-IR', $projection['aliases']['fa-IR']);
        self::assertSame('arabext', $projection['defaults'][$projection['aliases']['fa-IR']]);
        self::assertSame('fa', $projection['inheritance']['fa-Arab']);
    }
}
