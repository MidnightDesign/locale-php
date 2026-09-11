<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Test262\AssertionIdentityExtractor;
use Midnight\Intl\Tools\Test262\ConstructorFixturePipeline;
use Midnight\Intl\Tools\Test262\ConstructorFixtureTranslator;
use Midnight\Intl\Tools\Test262\ConstructorOptionsScriptTranslator;
use PHPUnit\Framework\TestCase;

final class Test262FailureEvidenceTest extends TestCase
{
    public function testAnInjectedAssertionFailureIsRecorded(): void
    {
        $source = self::fixtureSource();
        $source = str_replace("expected ? 'en-' + expected : 'en'", "expected ? 'zz-' + expected : 'zz'", $source);

        $result = self::pipeline()->run($source, 'injected-fixture.js');

        self::assertSame('failing', $result->evidence()['status']);
        self::assertSame(10, $result->executionFailures());
    }

    public function testAnInjectedRepresentationFailureIsRecorded(): void
    {
        $result = self::pipeline(null, ['unsupported-representation'])->run(
            self::fixtureSource(),
            'injected-fixture.js',
        );

        self::assertSame('failing', $result->evidence()['status']);
        self::assertSame(15, $result->executionFailures());
    }

    public function testAnInjectedTranslationFailureIsRecordedAsAGap(): void
    {
        $source = self::fixtureSource();
        $source = str_replace('assert.sameValue(', 'assert.notSame(', $source);

        $result = self::pipeline()->run($source, 'injected-fixture.js');

        $evidence = $result->evidence();

        self::assertSame('translation_gap', $evidence['status']);
        self::assertStringContainsString('Translation gap', $evidence['reason'] ?? '');
        self::assertSame(3, $evidence['sourceAssertionCount']);
        self::assertCount(3, $evidence['assertions']);
        foreach ($evidence['assertions'] as $assertion) {
            self::assertSame('translation_gap', $assertion['status']);
            self::assertNotEmpty($assertion['id']);
            self::assertNotEmpty($assertion['sha256']);
        }
    }

    public function testAnUnexpectedTranslatorDefectIsNotReportedAsATranslationGap(): void
    {
        $translator = new class() implements ConstructorFixtureTranslator {
            public function translate(string $source, string $fixturePath): array
            {
                throw new \LogicException('Injected implementation defect.');
            }
        };

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Injected implementation defect.');

        self::pipeline($translator)->run(self::fixtureSource(), 'injected-fixture.js');
    }

    private static function fixtureSource(): string
    {
        $source = file_get_contents(
            dirname(__DIR__) . '/Test262/upstream/test/intl402/Locale/constructor-options-script-valid.js',
        );
        self::assertNotFalse($source);

        return $source;
    }

    /** @param list<string> $representations */
    private static function pipeline(
        ?ConstructorFixtureTranslator $translator = null,
        array $representations = ['associative_array', 'plain_object'],
    ): ConstructorFixturePipeline {
        $assertionIdentities = new AssertionIdentityExtractor();

        return new ConstructorFixturePipeline(
            $translator ?? new ConstructorOptionsScriptTranslator($assertionIdentities),
            $assertionIdentities,
            $representations,
            'test262-revision',
            'ecma402-revision',
        );
    }
}
