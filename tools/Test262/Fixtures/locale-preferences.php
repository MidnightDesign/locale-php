<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Test262\BrandingFixtureMode;
use Midnight\Intl\Tools\Test262\FixturePipeline;
use Midnight\Intl\Tools\Test262\Fixtures\FixtureCatalog;

return static function (FixtureCatalog $catalog): array {
    $pipelines = [];
    foreach ([
        'getCalendars' => [
            'branding.js' => '8a3af42b69b62a5cb6e891e3a97dab167a68aa3440fd83f0d68879ed2499aed4',
            'likely-subtags-region.js' => '2a1e27d4df1d5e26f71483130d14e818fe963b009c94719d3858c8233d0623e5',
            'name.js' => 'c81f04344e5b4c2d8bb7512f3d96ff625152d0c77abe6a188f770c8ea94202f9',
            'output-array.js' => 'cb00645af3bc647f136e4f45e9c5c66069c028d48eb18e0048b1347b431dde97',
            'prop-desc.js' => '855e1c07c3d795c4220ddd2482bbf3aec6a21f5546586e6216ae62f3868c6186',
            'region-override.js' => 'f8fb896aac15ccfaac09c66e224859d84d4a80f5ab8e7c00b9e86e8341784cb5',
            'region-priority.js' => '4c68c9a62d5ea22c829eaa76348e82a9fe5934edd1ff792797c8555ed1dcc832',
            'subdivision-region.js' => '2015c7ea6e1b1468a3d0897b1187a3da5ac7519ec79bb8a1618becfb9a88eb53',
        ],
        'getHourCycles' => [
            'branding.js' => '4bf91687756566975243776d123bf6281cf193e2089ee58d97be3566b262429f',
            'language-priority.js' => '491ac894ab3dd2f442b63a4d36bc74906768f8fe0292a2b97fda10fa2a787bd8',
            'likely-subtags-region.js' => '4abad5f1e6a23c03b7687ca8fb6466be6514a6f9a03231da6fc6c6c068b71cb8',
            'name.js' => 'e523247da516d1c315690f71657fa18f1690cde6b5453afc4c66a9b7b6dc6870',
            'output-array-values.js' => '74eafc3bce26dbd6681cf8d4aa7e39efaccb4ca2c59d9f94cd864cca0feced29',
            'output-array.js' => 'af4ab21cfd891ddcd902818750f0a9505d18261b9758dd0b527b4973c3fefaba',
            'prop-desc.js' => '9ecb4f92b687569b2477839976a75841f4e89682d71a3461228eae4bdf2b0699',
            'region-override.js' => 'f8ba94a5472a3ed134915be3893c2cf4538cde813795f6750c78da029368bb1f',
            'region-priority.js' => '9d49da1acfee4a7fc86ad72a562e583ac9f9bf089bec7ae3e593d73e10afdb45',
            'subdivision-region.js' => '97664a5ba052bc6d39e1fa1a6189c06447e34c5235710ff91d01ba156e20112d',
        ],
    ] as $method => $fixtures) {
        foreach ($fixtures as $fixture => $sha256) {
            $path = 'test/intl402/Locale/prototype/' . $method . '/' . $fixture;
            if ($fixture === 'branding.js') {
                $pipelines[$path] = $catalog->branding(
                    $method,
                    BrandingFixtureMode::IndividualMethodIncludingConstructor,
                    $sha256,
                );

                continue;
            }
            $pipelines[$path] = $catalog->localePreference($sha256);
        }
    }

    /** @var array<string, FixturePipeline> $pipelines */
    return $pipelines;
};
