<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Test262\BrandingFixtureMode;
use Midnight\Intl\Tools\Test262\FixturePipeline;
use Midnight\Intl\Tools\Test262\Fixtures\FixtureCatalog;

return static function (FixtureCatalog $catalog): array {
    $pipelines = [];
    foreach ([
        'branding.js' => '5bec916cc844b0ead0cb2b9035ff5d2af11509acee4305e8ace393da6945f4a0',
        'firstDay-by-id.js' => '85d774a8f38c91516ba37cefd71cae6e01cfe7ebafaf1af6cb88781fc4f4445c',
        'firstDay-by-option.js' => '7d645ee4ee6403998995240bf68780f5fac67863e3d1d87f70426fb15c0cf92e',
        'likely-subtags-region.js' => 'd0303e17310c4527d01dc7857cc365f3d41ec6ab4f7e190f8d8aa61deabbc904',
        'name.js' => '8eed9bc0a8587fae825f09da850247e939bb2102949b597eed1e716fab911a69',
        'output-object-keys.js' => 'd926a9441429d4dfac94b5ed1752079728f2bc9f041cc2566c880fa00a622089',
        'output-object.js' => 'c18271e099360fe08290b6c9621813e4c970cad30945439a396557ecaf8164fb',
        'prop-desc.js' => '1f3391f8a4f76254b7bdf7996f85e03ce3653078281a7fd570fc150656e6693e',
        'region-override.js' => 'd21ce0426c6d0a975b41d3dab0617d25f3a339e148591426fb8e799b9ccbc4c8',
        'region-priority.js' => 'f5012b67f0839c3c02b2aca2c8246cc14e275fa0b6aecf51317fe6b5d434f3fc',
        'subdivision-region.js' => '2aff1e816d23f82c2bf7147c565137fc8aa759d6d9e5de8e0684f8c136f527a4',
    ] as $fixture => $sha256) {
        $path = 'test/intl402/Locale/prototype/getWeekInfo/' . $fixture;
        $pipelines[$path] = match ($fixture) {
            'branding.js' => $catalog->branding(
                'getWeekInfo',
                BrandingFixtureMode::IndividualMethodIncludingConstructor,
                $sha256,
            ),
            'name.js' => $catalog->sourceBoundLocaleMethod('getWeekInfo', 'name', $sha256),
            'prop-desc.js' => $catalog->sourceBoundLocaleMethod('getWeekInfo', 'property', $sha256),
            default => $catalog->weekInfo($sha256),
        };
    }

    /** @var array<string, FixturePipeline> $pipelines */
    return $pipelines;
};
