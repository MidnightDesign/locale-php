<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tests\Test262\RunnerTest;
use PHPUnit\Framework\TestCase;

final class Test262RunnerContractTest extends TestCase
{
    public function testGeneratedFixturesHaveDistinctSourceRelativeCliAndJunitIdentities(): void
    {
        $junit = tempnam(sys_get_temp_dir(), 'locale-test262-junit-');
        self::assertIsString($junit);

        try {
            [$exitCode, $output] = self::runPhpUnit([
                '--testsuite',
                'test262-upstream',
                '--no-progress',
                '--testdox',
                '--log-junit',
                $junit,
            ]);

            self::assertSame(0, $exitCode, $output);
            $xml = (string) file_get_contents($junit);
            foreach ([
                'test/intl402/Locale/constructor-unicode-ext-invalid.js',
                'test/intl402/Locale/reject-duplicate-variants.js',
            ] as $fixturePath) {
                self::assertStringContainsString($fixturePath, $output);
                self::assertStringContainsString($fixturePath, $xml);
            }
        } finally {
            @unlink($junit);
        }
    }

    public function testOneGeneratedFixtureCanBeSelectedWithoutExecutingItsSiblings(): void
    {
        $junit = tempnam(sys_get_temp_dir(), 'locale-test262-filter-');
        self::assertIsString($junit);

        try {
            $selected = 'test/intl402/Locale/constructor-unicode-ext-invalid.js';
            [$exitCode, $output] = self::runPhpUnit([
                '--testsuite',
                'test262-upstream',
                '--filter',
                $selected,
                '--no-progress',
                '--log-junit',
                $junit,
            ]);

            self::assertSame(0, $exitCode, $output);
            self::assertStringContainsString('OK (1 test, 6 assertions)', $output);
            $xml = (string) file_get_contents($junit);
            self::assertSame(1, substr_count($xml, '<testcase '));
            self::assertStringContainsString($selected, $xml);
            self::assertStringNotContainsString('reject-duplicate-variants.js', $xml);
        } finally {
            @unlink($junit);
        }
    }

    public function testStaleGeneratedScriptFailsDiscovery(): void
    {
        $path = dirname(__DIR__).'/Test262/Generated/test/intl402/Locale/zz-stale.php';
        file_put_contents($path, <<<'PHP'
<?php

// Source: test/intl402/Locale/zz-stale.js at Test262 injected.
PHP);

        try {
            iterator_to_array(RunnerTest::scripts());
            self::fail('Expected stale generated script discovery to fail.');
        } catch (\RuntimeException $error) {
            self::assertStringContainsString('stale: test/intl402/Locale/zz-stale.js', $error->getMessage());
        } finally {
            @unlink($path);
        }
    }

    public function testDuplicateGeneratedSourceIdentityFailsDiscovery(): void
    {
        $generated = dirname(__DIR__).'/Test262/Generated';
        $source = $generated.'/test/intl402/Locale/reject-duplicate-variants.php';
        $duplicate = $generated.'/zz-duplicate.php';
        self::assertTrue(copy($source, $duplicate));

        try {
            iterator_to_array(RunnerTest::scripts());
            self::fail('Expected duplicate generated script discovery to fail.');
        } catch (\RuntimeException $error) {
            self::assertStringContainsString(
                'Multiple generated scripts claim Test262 fixture test/intl402/Locale/reject-duplicate-variants.js',
                $error->getMessage(),
            );
        } finally {
            @unlink($duplicate);
        }
    }

    public function testMisplacedGeneratedScriptFailsDiscovery(): void
    {
        $generated = dirname(__DIR__).'/Test262/Generated';
        $source = $generated.'/test/intl402/Locale/reject-duplicate-variants.php';
        $misplaced = $generated.'/zz-misplaced.php';
        self::assertTrue(rename($source, $misplaced));

        try {
            iterator_to_array(RunnerTest::scripts());
            self::fail('Expected misplaced generated script discovery to fail.');
        } catch (\RuntimeException $error) {
            self::assertStringContainsString('is misplaced', $error->getMessage());
        } finally {
            @rename($misplaced, $source);
        }
    }

    public function testFailingTranslatedFixtureRetainsItsCliAndJunitIdentity(): void
    {
        $fixturePath = 'test/intl402/Locale/constructor-unicode-ext-invalid.js';
        self::withInjectedFixture(
            $fixturePath,
            <<<'PHP'
<?php

// Source: test/intl402/Locale/constructor-unicode-ext-invalid.js at Test262 injected.

PHPUnit\Framework\Assert::fail('Injected assertion failure.');
PHP,
            static function () use ($fixturePath): void {
                self::assertSelectedFixtureFails($fixturePath, 'Injected assertion failure.');
            },
            'failing',
        );
    }

    public function testRepresentationFailureRetainsItsCliAndJunitIdentity(): void
    {
        $fixturePath = 'test/intl402/Locale/constructor-options-script-valid.js';
        self::withInjectedFixture(
            $fixturePath,
            <<<'PHP'
<?php

// Source: test/intl402/Locale/constructor-options-script-valid.js at Test262 injected.

$result = Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion::evaluate(
    'en',
    'script',
    ['type' => 'string', 'value' => 'Latn'],
    'unsupported-representation',
    'en-Latn',
);
PHPUnit\Framework\Assert::assertSame('passing', $result['status'], $result['failure'] ?? 'unknown failure');
PHP,
            static function () use ($fixturePath): void {
                self::assertSelectedFixtureFails($fixturePath, 'Unsupported PHP representation');
            },
            'failing',
        );
    }

    public function testMalformedGeneratedPhpRetainsItsCliAndJunitIdentity(): void
    {
        $fixturePath = 'test/intl402/Locale/getters-missing.js';
        self::withInjectedFixture(
            $fixturePath,
            <<<'PHP'
<?php

// Source: test/intl402/Locale/getters-missing.js at Test262 injected.

this is not valid PHP
PHP,
            static function () use ($fixturePath): void {
                self::assertSelectedFixtureFails($fixturePath, 'ParseError');
            },
        );
    }

    /** @param list<string> $arguments
     *  @return array{int, string}
     */
    private static function runPhpUnit(array $arguments): array
    {
        $root = dirname(__DIR__, 2);
        $command = escapeshellarg(PHP_BINARY).' '.escapeshellarg($root.'/vendor/bin/phpunit');
        foreach ($arguments as $argument) {
            $command .= ' '.escapeshellarg($argument);
        }
        $command .= ' 2>&1';

        exec($command, $lines, $exitCode);

        return [$exitCode, implode("\n", $lines)];
    }

    private static function assertSelectedFixtureFails(string $fixturePath, string $failure): void
    {
        $junit = tempnam(sys_get_temp_dir(), 'locale-test262-failure-');
        self::assertIsString($junit);

        try {
            [$exitCode, $output] = self::runPhpUnit([
                '--testsuite',
                'test262-upstream',
                '--filter',
                $fixturePath,
                '--no-progress',
                '--log-junit',
                $junit,
            ]);

            self::assertNotSame(0, $exitCode, $output);
            self::assertStringContainsString($fixturePath, $output);
            self::assertStringContainsString($failure, $output);
            $xml = (string) file_get_contents($junit);
            self::assertSame(1, substr_count($xml, '<testcase '));
            self::assertStringContainsString($fixturePath, $xml);
            self::assertStringContainsString($failure, $xml);
        } finally {
            @unlink($junit);
        }
    }

    /** @param callable(): void $assertion */
    private static function withInjectedFixture(
        string $fixturePath,
        string $script,
        callable $assertion,
        ?string $evidenceStatus = null,
    ): void {
        $root = dirname(__DIR__, 2);
        $scriptPath = $root.'/tests/Test262/Generated/'.preg_replace('/\.js$/D', '.php', $fixturePath);
        $evidencePath = $root.'/tests/Test262/evidence.json';
        $originalScript = (string) file_get_contents($scriptPath);
        $originalEvidence = (string) file_get_contents($evidencePath);

        try {
            file_put_contents($scriptPath, $script."\n");
            if ($evidenceStatus !== null) {
                /** @var array{fixtures: list<array{path: string, status: string}>} $evidence */
                $evidence = json_decode($originalEvidence, true, flags: JSON_THROW_ON_ERROR);
                foreach ($evidence['fixtures'] as &$fixture) {
                    if ($fixture['path'] === $fixturePath) {
                        $fixture['status'] = $evidenceStatus;
                        break;
                    }
                }
                unset($fixture);
                file_put_contents(
                    $evidencePath,
                    json_encode($evidence, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n",
                );
            }

            $assertion();
        } finally {
            file_put_contents($scriptPath, $originalScript);
            file_put_contents($evidencePath, $originalEvidence);
        }
    }
}
