<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Spec\Locale;

final class LocaleMethodFixturePipeline implements FixturePipeline
{
    public function __construct(
        private readonly AssertionIdentityExtractor $assertionIdentities,
        private readonly string $method,
        private readonly string $kind,
        private readonly string $test262Revision,
        private readonly string $ecma402Revision,
    ) {
    }

    public function run(string $source, string $fixturePath): FixtureResult
    {
        $identities = $this->assertionIdentities->extract($source, $fixturePath);
        $reflection = new \ReflectionMethod(Locale::class, $this->method);
        $failure = !$reflection->isPublic()
            || $reflection->getName() !== $this->method
            || $reflection->getNumberOfRequiredParameters() !== 0;
        if ($this->kind === 'branding') {
            $uninitialized = (new \ReflectionClass(Locale::class))->newInstanceWithoutConstructor();
            try {
                $uninitialized->{$this->method}();
                $failure = true;
            } catch (TypeError) {
            }
        }

        $partiallyTranslated = in_array($this->kind, ['length', 'name', 'property'], true);
        $status = $failure ? 'failing' : ($partiallyTranslated ? 'partially_translated' : 'passing');
        $assertions = array_map(fn (array $identity): array => [
            ...$identity,
            'status' => $failure ? 'failing' : 'passing',
            'adaptations' => [$this->adaptation()],
        ], $identities);

        return new FixtureResult(
            $fixturePath,
            hash('sha256', $source),
            $status,
            ['php_reflection'],
            $assertions,
            count($identities),
            $failure ? 1 : 0,
            [$this->generatedPath() => $this->render($fixturePath)],
            $partiallyTranslated
                ? 'JavaScript property descriptor flags have no faithful ordinary PHP equivalent; the method name, visibility, and arity assertions run.'
                : null,
        );
    }

    private function adaptation(): string
    {
        return match ($this->kind) {
            'branding' => 'PHP method dispatch rejects non-objects; an uninitialized Locale verifies the observable branded-state failure.',
            'length' => 'The JavaScript function length is represented by zero required PHP parameters; descriptor flags are inapplicable.',
            'name' => 'The JavaScript function name is represented by the PHP reflection method name; descriptor flags are inapplicable.',
            default => 'Public PHP method visibility represents method availability; JavaScript descriptor flags are inapplicable.',
        };
    }

    private function generatedPath(): string
    {
        return sprintf(
            'tests/Test262/Generated/%s%sTest.php',
            ucfirst($this->method),
            ucfirst($this->kind),
        );
    }

    private function render(string $fixturePath): string
    {
        $class = ucfirst($this->method).ucfirst($this->kind).'Test';
        $method = $this->method;
        $branding = $this->kind === 'branding'
            ? <<<'PHP'

    public function testTranslatedBrandCheck(): void
    {
        $locale = (new \ReflectionClass(Locale::class))->newInstanceWithoutConstructor();

        $this->expectException(TypeError::class);
        $locale->METHOD();
    }
PHP
            : '';
        $branding = str_replace('METHOD', $method, $branding);

        return <<<PHP
<?php

declare(strict_types=1);

// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: {$fixturePath} at Test262 {$this->test262Revision}; notice: tests/Test262/upstream/LICENSE.
// Spec baseline: ECMA-402 {$this->ecma402Revision}; notice: tests/Test262/upstream/ECMA-402-LICENSE.md.

namespace Midnight\Intl\Tests\Test262\Generated;

use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Spec\Locale;
use PHPUnit\Framework\TestCase;

final class {$class} extends TestCase
{
    public function testTranslatedMethodMetadata(): void
    {
        \$method = new \ReflectionMethod(Locale::class, '{$method}');

        self::assertTrue(\$method->isPublic());
        self::assertSame('{$method}', \$method->getName());
        self::assertSame(0, \$method->getNumberOfRequiredParameters());
    }
{$branding}
}
PHP."\n";
    }
}
