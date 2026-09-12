<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262\Fixtures;

use Midnight\Intl\Tools\Test262\AssertionIdentityExtractor;
use Midnight\Intl\Tools\Test262\CollationsFixturePipeline;
use Midnight\Intl\Tools\Test262\ConstructorFixturePipeline;
use Midnight\Intl\Tools\Test262\ConstructorOptionsScriptTranslator;
use Midnight\Intl\Tools\Test262\FixturePipeline;
use Midnight\Intl\Tools\Test262\GetterFixturePipeline;
use Midnight\Intl\Tools\Test262\GrandfatheredLikelySubtagsPipeline;
use Midnight\Intl\Tools\Test262\IdentifierCanonicalizationPipeline;
use Midnight\Intl\Tools\Test262\IdentifierRejectionPipeline;
use Midnight\Intl\Tools\Test262\LikelySubtagsPipeline;
use Midnight\Intl\Tools\Test262\LocaleMethodFixturePipeline;
use Midnight\Intl\Tools\Test262\LocaleObjectPipeline;
use Midnight\Intl\Tools\Test262\LocalePreferenceFixturePipeline;
use Midnight\Intl\Tools\Test262\MappedConstructorOptionPipeline;
use Midnight\Intl\Tools\Test262\MappedLocaleStatePipeline;
use Midnight\Intl\Tools\Test262\NumberingSystemsFixturePipeline;
use Midnight\Intl\Tools\Test262\OptionObservationPipeline;
use Midnight\Intl\Tools\Test262\RemoveLikelySubtagsPipeline;
use Midnight\Intl\Tools\Test262\SourceBoundFixturePipeline;
use Midnight\Intl\Tools\Test262\TextInfoFixturePipeline;
use Midnight\Intl\Tools\Test262\TimeZonesFixturePipeline;
use Midnight\Intl\Tools\Test262\UndefinedConstructorOptionPipeline;
use Midnight\Intl\Tools\Test262\WeekInfoFixturePipeline;

final readonly class FixtureCatalog
{
    /** @param list<string> $representations */
    public function __construct(
        private AssertionIdentityExtractor $assertionIdentities,
        private array $representations,
        private string $test262Revision,
        private string $ecma402Revision,
    ) {}

    public function grandfatheredLikelySubtags(): FixturePipeline
    {
        return new GrandfatheredLikelySubtagsPipeline(
            $this->assertionIdentities,
            $this->test262Revision,
            $this->ecma402Revision,
        );
    }

    public function likelySubtags(): FixturePipeline
    {
        return new LikelySubtagsPipeline($this->assertionIdentities, $this->test262Revision, $this->ecma402Revision);
    }

    public function identifierRejection(): FixturePipeline
    {
        return new IdentifierRejectionPipeline(
            $this->assertionIdentities,
            $this->test262Revision,
            $this->ecma402Revision,
        );
    }

    public function identifierCanonicalization(): FixturePipeline
    {
        return new IdentifierCanonicalizationPipeline(
            $this->assertionIdentities,
            $this->test262Revision,
            $this->ecma402Revision,
        );
    }

    public function getters(): FixturePipeline
    {
        return new GetterFixturePipeline($this->assertionIdentities, $this->test262Revision, $this->ecma402Revision);
    }

    public function scriptConstructor(): FixturePipeline
    {
        return new ConstructorFixturePipeline(
            new ConstructorOptionsScriptTranslator($this->assertionIdentities),
            $this->assertionIdentities,
            $this->representations,
            $this->test262Revision,
            $this->ecma402Revision,
        );
    }

    public function localeMethod(string $method, string $mode): FixturePipeline
    {
        return new LocaleMethodFixturePipeline(
            $this->assertionIdentities,
            $method,
            $mode,
            $this->test262Revision,
            $this->ecma402Revision,
        );
    }

    public function sourceBoundLocaleMethod(string $method, string $mode, string $sourceSha256): FixturePipeline
    {
        return $this->sourceBound($this->localeMethod($method, $mode), ['php_reflection'], $sourceSha256);
    }

    public function removeLikelySubtags(): FixturePipeline
    {
        return new RemoveLikelySubtagsPipeline(
            $this->assertionIdentities,
            $this->test262Revision,
            $this->ecma402Revision,
        );
    }

    public function undefinedConstructorOption(string $optionName): FixturePipeline
    {
        return new UndefinedConstructorOptionPipeline(
            $this->assertionIdentities,
            $this->representations,
            $this->test262Revision,
            $this->ecma402Revision,
            $optionName,
        );
    }

    /**
     * @param list<array{assertion: int, tag: string, value: array<string, mixed>, expected: string|bool, property?: string}> $cases
     */
    public function mappedOption(string $optionName, string $sourceSha256, array $cases): FixturePipeline
    {
        return $this->sourceBound(
            new MappedConstructorOptionPipeline(
                $this->assertionIdentities,
                $this->representations,
                $this->test262Revision,
                $this->ecma402Revision,
                $optionName,
                $cases,
            ),
            $this->representations,
            $sourceSha256,
        );
    }

    /**
     * @param list<array{tag: string, options?: array<string, mixed>, expectations: list<array{assertion: int, property: string, expected: string|bool|null}>}> $scenarios
     */
    public function mappedState(string $sourceSha256, array $scenarios): FixturePipeline
    {
        $pipeline = new MappedLocaleStatePipeline(
            $this->assertionIdentities,
            $this->test262Revision,
            $this->ecma402Revision,
            $scenarios,
        );

        return $this->sourceBound($pipeline, $pipeline->phpRepresentations(), $sourceSha256);
    }

    /**
     * @param 'order'|'throws' $mode
     * @param list<string>     $options
     */
    public function optionObservation(string $mode, array $options, string $sourceSha256): FixturePipeline
    {
        return $this->sourceBound(
            new OptionObservationPipeline(
                $this->assertionIdentities,
                $this->test262Revision,
                $this->ecma402Revision,
                $mode,
                $options,
            ),
            ['behavioral_object'],
            $sourceSha256,
        );
    }

    public function localeObject(string $sourceSha256): FixturePipeline
    {
        return $this->sourceBound(
            new LocaleObjectPipeline($this->assertionIdentities, $this->test262Revision, $this->ecma402Revision),
            $this->representations,
            $sourceSha256,
        );
    }

    public function timeZones(string $sourceSha256): FixturePipeline
    {
        return $this->sourceBound(
            new TimeZonesFixturePipeline($this->assertionIdentities, $this->test262Revision, $this->ecma402Revision),
            ['direct'],
            $sourceSha256,
        );
    }

    public function numberingSystems(string $sourceSha256): FixturePipeline
    {
        return $this->sourceBound(
            new NumberingSystemsFixturePipeline(
                $this->assertionIdentities,
                $this->test262Revision,
                $this->ecma402Revision,
            ),
            ['direct'],
            $sourceSha256,
        );
    }

    /** @param 'keys'|'record' $kind */
    public function textInfo(string $kind): FixturePipeline
    {
        return new TextInfoFixturePipeline(
            $this->assertionIdentities,
            $kind,
            $this->test262Revision,
            $this->ecma402Revision,
        );
    }

    public function localePreference(string $sourceSha256): FixturePipeline
    {
        return $this->sourceBound(
            new LocalePreferenceFixturePipeline(
                $this->assertionIdentities,
                $this->test262Revision,
                $this->ecma402Revision,
            ),
            ['direct'],
            $sourceSha256,
        );
    }

    public function collations(string $sourceSha256): FixturePipeline
    {
        return $this->sourceBound(
            new CollationsFixturePipeline($this->assertionIdentities, $this->test262Revision, $this->ecma402Revision),
            ['direct'],
            $sourceSha256,
        );
    }

    public function weekInfo(string $sourceSha256): FixturePipeline
    {
        return $this->sourceBound(
            new WeekInfoFixturePipeline($this->assertionIdentities, $this->test262Revision, $this->ecma402Revision),
            ['direct'],
            $sourceSha256,
        );
    }

    /** @param list<string> $representations */
    private function sourceBound(
        FixturePipeline $pipeline,
        array $representations,
        string $sourceSha256,
    ): SourceBoundFixturePipeline {
        return new SourceBoundFixturePipeline($pipeline, $this->assertionIdentities, $representations, $sourceSha256);
    }
}
