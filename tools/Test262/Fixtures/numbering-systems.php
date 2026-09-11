<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Test262\FixturePipeline;
use Midnight\Intl\Tools\Test262\Fixtures\FixtureCatalog;

/** @return array<string, FixturePipeline> */
return static fn(FixtureCatalog $catalog): array => [
    'test/intl402/Locale/prototype/getNumberingSystems/branding.js' => $catalog->numberingSystems(
        '530307c9dbee32509652923d38e224bfe1d4afb43858be32ddc7f6bcf54bb303',
    ),
    'test/intl402/Locale/prototype/getNumberingSystems/name.js' => $catalog->numberingSystems(
        'f3591c1be5c56bbcbc1acafb179d67dbfac6ce93d7629839df198ae90bd0b02c',
    ),
    'test/intl402/Locale/prototype/getNumberingSystems/output-array.js' => $catalog->numberingSystems(
        '76789a13dba8b82b03e2a0aefc963111042aa5f8c8e4656899a707a73570b534',
    ),
    'test/intl402/Locale/prototype/getNumberingSystems/prop-desc.js' => $catalog->numberingSystems(
        '0cd175aae9f0a27c10544c4577ff32e750b1563f63144b9b0bf11d35d7b21120',
    ),
];
