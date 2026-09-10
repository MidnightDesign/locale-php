<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Test262\ConstructorFixturePipeline;
use Midnight\Intl\Tools\Test262\ConstructorOptionsScriptTranslator;
use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__, 2).'/tools/Test262/ConstructorOptionsScriptTranslator.php';

final class Test262FailureEvidenceTest extends TestCase
{
    public function testAnInjectedAssertionFailureIsRecorded(): void
    {
        $source = self::fixtureSource();
        $source = str_replace(
            "expected ? 'en-' + expected : 'en'",
            "expected ? 'zz-' + expected : 'zz'",
            $source,
        );

        $result = self::pipeline()->run(
            $source,
            'injected-fixture.js',
            ['associative_array', 'plain_object'],
        );

        self::assertSame('failing', $result['status']);
        self::assertSame(10, $result['executionFailures']);
    }

    public function testAnInjectedRepresentationFailureIsRecorded(): void
    {
        $result = self::pipeline()->run(
            self::fixtureSource(),
            'injected-fixture.js',
            ['unsupported-representation'],
        );

        self::assertSame('failing', $result['status']);
        self::assertSame(15, $result['executionFailures']);
    }

    public function testAnInjectedTranslationFailureIsRecordedAsAGap(): void
    {
        $source = self::fixtureSource();
        $source = str_replace('assert.sameValue(', 'assert.notSame(', $source);

        $result = self::pipeline()->run(
            $source,
            'injected-fixture.js',
            ['associative_array', 'plain_object'],
        );

        self::assertSame('translation_gap', $result['status']);
        self::assertStringContainsString('Translation gap', $result['reason']);
    }

    private static function fixtureSource(): string
    {
        $source = file_get_contents(
            dirname(__DIR__).'/Test262/upstream/test/intl402/Locale/constructor-options-script-valid.js',
        );
        self::assertNotFalse($source);

        return $source;
    }

    private static function pipeline(): ConstructorFixturePipeline
    {
        $pipelinePath = dirname(__DIR__, 2).'/tools/Test262/ConstructorFixturePipeline.php';
        self::assertFileExists($pipelinePath);
        require_once $pipelinePath;

        return new ConstructorFixturePipeline(new ConstructorOptionsScriptTranslator());
    }
}
