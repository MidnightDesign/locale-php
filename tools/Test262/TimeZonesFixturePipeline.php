<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Spec\Locale;

final class TimeZonesFixturePipeline extends LocaleListMethodFixturePipeline
{
    public function __construct(
        AssertionIdentityExtractor $assertionIdentities,
        string $test262Revision,
        string $ecma402Revision,
    ) {
        parent::__construct($assertionIdentities, $test262Revision, $ecma402Revision, 'getTimeZones');
    }

    /** @return list<bool> */
    protected function operationFailuresByAssertion(string $name): array
    {
        return match ($name) {
            'output-array-sorted.js' => [!self::isSorted((new Locale('en-US'))->getTimeZones())],
            'output-array-undefined.js' => [(new Locale('en'))->getTimeZones() !== null],
            'output-array.js' => $this->outputArrayFailuresByAssertion(),
            default => [],
        };
    }

    /** @return list<string> */
    protected function operationAdaptations(string $name, int $index): array
    {
        return (
            $name === 'output-array-undefined.js'
                ? ['ECMAScript undefined is represented by PHP null.']
                : parent::operationAdaptations($name, $index)
        );
    }

    protected function renderOperationBody(string $name): ?string
    {
        return match ($name) {
            'output-array-sorted.js' => <<<'PHP'
                $output = (new Locale('en-US'))->getTimeZones();
                $sorted = $output;
                sort($sorted, SORT_STRING);
                Assert::assertSame($sorted, $output);
                PHP,
            'output-array-undefined.js' => "Assert::assertNull((new Locale('en'))->getTimeZones());",
            'output-array.js' => <<<'PHP'
                $output = (new Locale('en-US'))->getTimeZones();
                Assert::assertIsArray($output);
                Assert::assertNotEmpty($output);
                PHP,
            default => null,
        };
    }

    /** @return list<bool> */
    private function outputArrayFailuresByAssertion(): array
    {
        $output = (new Locale('en-US'))->getTimeZones();

        return [!is_array($output), $output === []];
    }

    /** @param list<string>|null $identifiers */
    private static function isSorted(?array $identifiers): bool
    {
        if ($identifiers === null) {
            return false;
        }
        $sorted = $identifiers;
        sort($sorted, SORT_STRING);

        return $identifiers === $sorted;
    }
}
