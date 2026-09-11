<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Tooling;

use PHPUnit\Framework\TestCase;

final class Test262EvidenceTest extends TestCase
{
    /**
     * @return array{
     *     baseline: array{
     *         ecma402: array{revision: string, localeSource: array{sha256: string}, license: array{sha256: string}},
     *         test262: array{revision: string, license: array{upstreamSha256: string, committedSha256: string}}
     *     },
     *     inventory: array{fixtureCount: int, complete: bool},
     *     summary: array{
     *         translatedFixtures: int,
     *         passingFixtures: int,
     *         partiallyTranslatedFixtures: int,
     *         translationGaps: int,
     *         executionFailures: int
     *     },
     *     conformanceClaim: array{eligible: bool},
     *     fixtures: list<array{
     *         path: string,
     *         status: string,
     *         generatedScripts: list<array{path: string, identity: string, variant: string|null}>,
     *         sourceAssertionCount: int,
     *         executionCount: int,
     *         executionFailures: int,
     *         phpRepresentations?: list<string>,
     *         assertions: list<array{id: string, adaptations?: list<string>, status: string}>
     *     }>
     * }
     */
    private static function evidence(): array
    {
        $contents = file_get_contents(dirname(__DIR__) . '/Test262/evidence.json');
        self::assertNotFalse($contents);

        /** @var array{
         *     baseline: array{
         *         ecma402: array{revision: string, localeSource: array{sha256: string}, license: array{sha256: string}},
         *         test262: array{revision: string, license: array{upstreamSha256: string, committedSha256: string}}
         *     },
         *     inventory: array{fixtureCount: int, complete: bool},
         *     summary: array{
         *         translatedFixtures: int,
         *         passingFixtures: int,
         *         partiallyTranslatedFixtures: int,
         *         translationGaps: int,
         *         executionFailures: int
         *     },
         *     conformanceClaim: array{eligible: bool},
         *     fixtures: list<array{
         *         path: string,
         *         status: string,
         *         generatedScripts: list<array{path: string, identity: string, variant: string|null}>,
         *         sourceAssertionCount: int,
         *         executionCount: int,
         *         executionFailures: int,
         *         phpRepresentations?: list<string>,
         *         assertions: list<array{id: string, adaptations?: list<string>, status: string}>
         *     }>
         * } $evidence
         */
        $evidence = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);

        return $evidence;
    }

    public function testThePinnedBaselineRecordsVerifiedSourceAndLicenseIdentities(): void
    {
        $evidence = self::evidence();

        self::assertSame('b1c961988b9a07894b1dc3dc2b5626ea48387d61', $evidence['baseline']['ecma402']['revision']);
        self::assertSame('419d3e0a2273ba01a3bfcbec423f2801425b8e93', $evidence['baseline']['test262']['revision']);
        self::assertSame(168, $evidence['inventory']['fixtureCount']);
        self::assertTrue($evidence['inventory']['complete']);
        self::assertNotEmpty($evidence['baseline']['ecma402']['localeSource']['sha256']);
        self::assertNotEmpty($evidence['baseline']['ecma402']['license']['sha256']);
        self::assertNotEmpty($evidence['baseline']['test262']['license']['upstreamSha256']);
        self::assertNotEmpty($evidence['baseline']['test262']['license']['committedSha256']);
    }

    public function testTheConstructorFixtureReportsBothRequiredRepresentations(): void
    {
        $evidence = self::evidence();
        $fixture = null;
        foreach ($evidence['fixtures'] as $candidate) {
            if ($candidate['path'] === 'test/intl402/Locale/constructor-options-script-valid.js') {
                $fixture = $candidate;
            }
        }
        self::assertNotNull($fixture);
        /** @var array{
         *     path: string,
         *     status: string,
         *     sourceAssertionCount: int,
         *     executionCount: int,
         *     phpRepresentations: list<string>,
         *     assertions: list<array{id: string, adaptations: list<string>, status: string}>,
         *     generatedScripts: list<array{path: string, identity: string, variant: string|null}>
         * } $fixture
         */

        self::assertSame('passing', $fixture['status']);
        self::assertSame(3, $fixture['sourceAssertionCount']);
        self::assertSame(30, $fixture['executionCount']);
        self::assertSame(['associative_array', 'plain_object'], $fixture['phpRepresentations']);

        foreach ($fixture['assertions'] as $assertion) {
            self::assertNotEmpty($assertion['id']);
            self::assertNotEmpty($assertion['adaptations']);
            self::assertSame('passing', $assertion['status']);
        }

        self::assertSame(
            [[
                'path' => 'tests/Test262/Generated/test/intl402/Locale/constructor-options-script-valid.php',
                'identity' => 'test/intl402/Locale/constructor-options-script-valid.js',
                'variant' => null,
            ]],
            $fixture['generatedScripts'],
        );
    }

    public function testIncompleteTranslationsPreventAConformanceClaim(): void
    {
        $evidence = self::evidence();

        self::assertFalse($evidence['conformanceClaim']['eligible']);
        self::assertGreaterThan(0, $evidence['summary']['translationGaps']);
    }

    public function testSummaryCountsAreDerivedFromFixtureEvidence(): void
    {
        $evidence = self::evidence();
        $passing = 0;
        $partiallyTranslated = 0;
        $translated = 0;
        $executionFailures = 0;
        foreach ($evidence['fixtures'] as $fixture) {
            $passing += $fixture['status'] === 'passing' ? 1 : 0;
            $partiallyTranslated += $fixture['status'] === 'partially_translated' ? 1 : 0;
            $translated += $fixture['status'] !== 'translation_gap' ? 1 : 0;
            $executionFailures += $fixture['executionFailures'];
        }

        self::assertSame($passing, $evidence['summary']['passingFixtures']);
        self::assertSame($partiallyTranslated, $evidence['summary']['partiallyTranslatedFixtures']);
        self::assertSame($translated, $evidence['summary']['translatedFixtures']);
        self::assertSame($executionFailures, $evidence['summary']['executionFailures']);
    }

    public function testLikelySubtagFixturesRetainCompleteSourceEvidence(): void
    {
        $evidence = self::evidence();
        $fixtures = [];
        foreach ($evidence['fixtures'] as $fixture) {
            $fixtures[$fixture['path']] = $fixture;
        }

        foreach ([
            'test/intl402/Locale/likely-subtags-grandfathered.js',
            'test/intl402/Locale/likely-subtags.js',
            'test/intl402/Locale/prototype/maximize/branding.js',
            'test/intl402/Locale/prototype/maximize/length.js',
            'test/intl402/Locale/prototype/maximize/name.js',
            'test/intl402/Locale/prototype/maximize/prop-desc.js',
            'test/intl402/Locale/prototype/minimize/branding.js',
            'test/intl402/Locale/prototype/minimize/length.js',
            'test/intl402/Locale/prototype/minimize/name.js',
            'test/intl402/Locale/prototype/minimize/prop-desc.js',
            'test/intl402/Locale/prototype/minimize/removing-likely-subtags-first-adds-likely-subtags.js',
        ] as $path) {
            self::assertArrayHasKey($path, $fixtures);
            self::assertNotSame('translation_gap', $fixtures[$path]['status']);
            self::assertGreaterThan(0, $fixtures[$path]['sourceAssertionCount']);
            self::assertGreaterThan(0, $fixtures[$path]['executionCount']);
            self::assertSame(0, $fixtures[$path]['executionFailures']);
            foreach ($fixtures[$path]['assertions'] as $assertion) {
                self::assertContains($assertion['status'], ['passing', 'partially_translated', 'inapplicable']);
                self::assertNotEmpty($assertion['adaptations'] ?? []);
            }
        }

        foreach (['maximize', 'minimize'] as $method) {
            self::assertSame(
                ['partially_translated'],
                array_column($fixtures["test/intl402/Locale/prototype/{$method}/length.js"]['assertions'], 'status'),
            );
            self::assertSame(
                ['partially_translated'],
                array_column($fixtures["test/intl402/Locale/prototype/{$method}/name.js"]['assertions'], 'status'),
            );
            self::assertSame(
                ['passing', 'inapplicable'],
                array_column($fixtures["test/intl402/Locale/prototype/{$method}/prop-desc.js"]['assertions'], 'status'),
            );
        }
    }
}
