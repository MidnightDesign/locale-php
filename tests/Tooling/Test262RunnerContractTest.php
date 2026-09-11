<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use Midnight\Intl\Tools\Ci\PackageSmoke;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class Test262RunnerContractTest extends TestCase
{
    public function testGeneratedFixturesHaveDistinctSourceRelativeCliAndJunitIdentities(): void
    {
        $junit = tempnam(sys_get_temp_dir(), 'locale-test262-junit-');
        self::assertIsString($junit);

        try {
            [$exitCode, $output] = self::runPhpUnit([
                '--testsuite', 'test262-upstream', '--no-progress', '--testdox', '--log-junit', $junit,
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
                '--testsuite', 'test262-upstream', '--filter', $selected, '--no-progress', '--log-junit', $junit,
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

    /** @return iterable<string, array{string, string}> */
    public static function failingScripts(): iterable
    {
        yield 'assertion failure' => [
            "<?php\n\nPHPUnit\\Framework\\Assert::fail('Injected assertion failure.');\n",
            'Injected assertion failure.',
        ];
        yield 'representation failure' => [
            <<<'PHP'
<?php

$result = Midnight\Intl\Tests\Test262\Harness\ConstructorOptionAssertion::evaluate(
    'en',
    'script',
    ['type' => 'string', 'value' => 'Latn'],
    'unsupported-representation',
    'en-Latn',
);
PHPUnit\Framework\Assert::assertSame('passing', $result['status'], $result['failure'] ?? 'unknown failure');
PHP,
            'Unsupported PHP representation',
        ];
        yield 'malformed PHP' => [
            "<?php\n\nthis is not valid PHP\n",
            'ParseError',
        ];
    }

    #[DataProvider('failingScripts')]
    public function testFailingGeneratedScriptRetainsItsCliAndJunitIdentity(string $script, string $failure): void
    {
        $root = PackageSmoke::temporaryDirectory('locale-test262-runner');
        $junit = tempnam(sys_get_temp_dir(), 'locale-test262-failure-');
        self::assertIsString($junit);

        try {
            $fixturePath = 'test/intl402/Locale/injected.js';
            $scriptPath = 'tests/Test262/Generated/test/intl402/Locale/injected.php';
            self::write($root.'/'.$scriptPath, $script);
            self::write(
                $root.'/tests/Test262/evidence.json',
                json_encode(['fixtures' => [[
                    'path' => $fixturePath,
                    'status' => 'failing',
                    'generatedScripts' => [[
                        'path' => $scriptPath,
                        'identity' => $fixturePath,
                        'variant' => null,
                    ]],
                ]]], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n",
            );
            $runner = $root.'/TemporaryTest262Runner.php';
            self::write($runner, self::temporaryRunner($root));

            [$exitCode, $output] = self::runPhpUnit([
                '--no-configuration', '--bootstrap', dirname(__DIR__).'/bootstrap.php',
                '--filter', $fixturePath, '--no-progress', '--log-junit', $junit, $runner,
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
            PackageSmoke::removeDirectory($root);
        }
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

    private static function temporaryRunner(string $root): string
    {
        $root = var_export(str_replace('\\', '/', $root), true);

        return <<<PHP
<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Test262\GeneratedScriptCatalog;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class TemporaryTest262Runner extends TestCase
{
    public static function scripts(): iterable
    {
        foreach ((new GeneratedScriptCatalog({$root}, {$root}.'/tests/Test262/evidence.json'))->scripts() as \$identity => \$path) {
            yield \$identity => [\$path];
        }
    }

    #[DataProvider('scripts')]
    public function testScript(string \$path): void
    {
        require \$path;
    }
}
PHP;
    }

    private static function write(string $path, string $contents): void
    {
        if (!is_dir(dirname($path))) {
            self::assertTrue(mkdir(dirname($path), 0700, true));
        }
        self::assertNotFalse(file_put_contents($path, $contents));
    }
}
