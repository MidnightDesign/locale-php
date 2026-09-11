<?php

declare(strict_types=1);

use Midnight\Intl\Tools\Test262\FixturePipeline;
use Midnight\Intl\Tools\Test262\Fixtures\FixtureCatalog;

/** @return array<string, FixturePipeline> */
return static fn(FixtureCatalog $catalog): array => [
    'test/intl402/Locale/prototype/getTimeZones/branding.js' => $catalog->timeZones(
        '0760c5c934c886e66f1ad7960f5e568e0883e8da562adbf0f700ba934798bccf',
    ),
    'test/intl402/Locale/prototype/getTimeZones/name.js' => $catalog->timeZones(
        '661bb136b4d3e252c6ef29f65e6ca7e9fb9be08a5bc34e435c58e3d6fc913260',
    ),
    'test/intl402/Locale/prototype/getTimeZones/output-array-sorted.js' => $catalog->timeZones(
        '9da185ea867c9b003be4a2186151186e07d1a60a68d200a5640a3eeca3176616',
    ),
    'test/intl402/Locale/prototype/getTimeZones/output-array-undefined.js' => $catalog->timeZones(
        '051f651eaa1127afa99f0064d855e3f6be1b46acb3404a06abe4d3f4570a7d60',
    ),
    'test/intl402/Locale/prototype/getTimeZones/output-array.js' => $catalog->timeZones(
        '86873684eed707e05dea5090fcfc661d1edc77a79d120b96370383682377755f',
    ),
    'test/intl402/Locale/prototype/getTimeZones/prop-desc.js' => $catalog->timeZones(
        '94d0dad6fac17c3daca60bd971de27e0424cb5ffd7c6c8b1cb84fb801063de86',
    ),
];
