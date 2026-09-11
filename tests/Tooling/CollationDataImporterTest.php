<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\CollationDataImporter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CollationDataImporter::class)]
final class CollationDataImporterTest extends TestCase
{
    public function testItProjectsCanonicalFilteredCollationsWithComponentInheritance(): void
    {
        $projection = CollationDataImporter::project(
            [
                'root' => <<<'XML'
                    <ldml><collations>
                      <collation type="standard"/><collation type="search"/>
                      <collation type="emoji"/><collation type="eor"/>
                      <collation type="private-test"/>
                    </collations></ldml>
                    XML,
                'de' => <<<'XML'
                    <ldml><collations>
                      <collation type="phonebook"/><collation type="phonebk"/>
                    </collations></ldml>
                    XML,
                'de-CH' => '<ldml><collations><collation type="dict"/></collations></ldml>',
            ],
            <<<'XML'
                <ldmlBCP47><keyword><key name="co">
                  <type name="phonebk" alias="phonebook phoneb"/>
                </key></keyword></ldmlBCP47>
                XML,
            <<<'XML'
                <supplementalData><parentLocales>
                  <parentLocale parent="fr" locales="de_CH"/>
                </parentLocales><parentLocales component="collations">
                  <parentLocale parent="de" locales="de_CH"/>
                </parentLocales></supplementalData>
                XML,
        );

        self::assertSame(['emoji', 'eor'], $projection['root']);
        self::assertSame(['emoji', 'eor', 'phonebk'], $projection['locales']['de']);
        self::assertSame(['dict', 'emoji', 'eor', 'phonebk'], $projection['locales']['de-CH']);
    }
}
