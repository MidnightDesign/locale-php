<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

final class EvidenceBuilder
{
    /**
     * @param array{
     *     initial: array{
     *         ecma402: array{revision: string, localeSource: array{sha256: string}},
     *         test262: array<string, mixed>
     *     },
     *     active: array{
     *         ecma402: array{revision: string, localeSource: array{sha256: string}}&array<string, mixed>,
     *         test262: array<string, mixed>
     *     },
     *     trackingPolicy: string
     * } $baseline
     * @param array{
     *     comparison: array<string, mixed>,
     *     fixtureCount: int,
     *     aggregateSha256: string,
     *     fixtures: list<array{status: string, detectedAssertions: list<array<string, mixed>>}>
     * } $corpus
     * @param array{complete: bool, reasons: list<string>} $inventoryAudit
     * @param list<FixtureResult>                   $fixtureResults
     *
     * @return array<string, mixed>
     */
    public static function build(array $baseline, array $corpus, array $inventoryAudit, array $fixtureResults): array
    {
        $translationGaps = 0;
        foreach ($corpus['fixtures'] as $fixture) {
            if ($fixture['status'] === 'translation_gap') {
                $translationGaps += count($fixture['detectedAssertions']) + 1;
            }
        }
        foreach ($fixtureResults as $result) {
            $translationGaps += $result->translationGapCount();
        }

        $translatedFixtures = count(array_filter($fixtureResults, static fn(FixtureResult $result): bool => $result->isTranslated()));
        $passingFixtures = count(array_filter($fixtureResults, static fn(FixtureResult $result): bool => $result->isPassing()));
        $partiallyTranslatedFixtures = count(array_filter($fixtureResults, static fn(FixtureResult $result): bool => $result->isPartiallyTranslated()));
        $executionFailures = array_sum(array_map(
            static fn(FixtureResult $result): int => $result->executionFailures(),
            $fixtureResults,
        ));
        $allFixturesPass = count($fixtureResults) === $passingFixtures;
        $conformanceEligible =
            $inventoryAudit['complete'] && $translationGaps === 0 && $executionFailures === 0 && $allFixturesPass;

        return [
            'baseline' => $baseline['initial'],
            'trackedInputs' => [
                'ecma402' => [
                    ...$baseline['active']['ecma402'],
                    'comparisonAgainstInitial' => [
                        'fromRevision' => $baseline['initial']['ecma402']['revision'],
                        'toRevision' => $baseline['active']['ecma402']['revision'],
                        'localeSourceChanged' =>
                            $baseline['initial']['ecma402']['localeSource']['sha256']
                                !== $baseline['active']['ecma402']['localeSource']['sha256'],
                    ],
                ],
                'test262' => [
                    ...$baseline['active']['test262'],
                    'comparison' => $corpus['comparison'],
                ],
                'policy' => $baseline['trackingPolicy'],
            ],
            'inventory' => [
                'path' => 'tests/Test262/corpus.json',
                'fixtureCount' => $corpus['fixtureCount'],
                'aggregateSha256' => $corpus['aggregateSha256'],
                'complete' => $inventoryAudit['complete'],
                'auditFailures' => $inventoryAudit['reasons'],
            ],
            'summary' => [
                'translatedFixtures' => $translatedFixtures,
                'passingFixtures' => $passingFixtures,
                'partiallyTranslatedFixtures' => $partiallyTranslatedFixtures,
                'translationGaps' => $translationGaps,
                'executionFailures' => $executionFailures,
            ],
            'conformanceClaim' => [
                'eligible' => $conformanceEligible,
                'reason' => $conformanceEligible
                    ? 'Every inventoried applicable assertion and required representation passes.'
                    : 'The initial slice has an incomplete inventory, translation gaps, incomplete applicable translations, or execution failures.',
            ],
            'fixtures' => array_map(static fn(FixtureResult $result): array => $result->evidence(), $fixtureResults),
        ];
    }
}
