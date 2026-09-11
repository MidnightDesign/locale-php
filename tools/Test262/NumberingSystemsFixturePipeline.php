<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

use Midnight\Intl\Spec\Locale;

final class NumberingSystemsFixturePipeline extends LocaleListMethodFixturePipeline
{
    public function __construct(
        AssertionIdentityExtractor $assertionIdentities,
        string $test262Revision,
        string $ecma402Revision,
    ) {
        parent::__construct($assertionIdentities, $test262Revision, $ecma402Revision, 'getNumberingSystems');
    }

    /** @return list<bool> */
    protected function operationFailuresByAssertion(string $name): array
    {
        if ($name !== 'output-array.js') {
            return [];
        }

        $output = (new Locale('en'))->getNumberingSystems();
        /** @phpstan-ignore function.alreadyNarrowedType (Original Test262 Array.isArray assertion.) */
        $arrayFailure = !is_array($output);

        return [$arrayFailure, $output === []];
    }

    protected function renderOperationBody(string $name): ?string
    {
        return $name === 'output-array.js' ? <<<'PHP'
                $output = (new Locale('en'))->getNumberingSystems();
                Assert::assertIsArray($output);
                Assert::assertNotEmpty($output);
                PHP : null;
    }
}
