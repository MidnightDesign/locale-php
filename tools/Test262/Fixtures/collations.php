<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262\Fixtures;

use Midnight\Intl\Tools\Test262\FixturePipeline;

/** @return array<string, FixturePipeline> */
return static fn(FixtureCatalog $catalog): array => [
    'test/intl402/Locale/prototype/getCollations/branding.js' => $catalog->sourceBoundLocaleMethod(
        'getCollations',
        'branding',
        'fafbdfbfe51877fd46cd1b3b5adda382dbcfb9f129c547b7486b8f9a474f8813',
    ),
    'test/intl402/Locale/prototype/getCollations/collation-keyword.js' => $catalog->collations(
        'bbd0fd6ca30dabbef8fb1190199a49aeacf485f3c3cc0b2a70296bc088ad334c',
    ),
    'test/intl402/Locale/prototype/getCollations/name.js' => $catalog->sourceBoundLocaleMethod(
        'getCollations',
        'name',
        'e03e578281f9842482da2821d8f4c92a4bfc0731ea4ac27919ad89d2447611bf',
    ),
    'test/intl402/Locale/prototype/getCollations/output-array-sorted.js' => $catalog->collations(
        'b58dd2a9608611c8b7ff66adcdb42f325636023bfada67457490242903c8e2c4',
    ),
    'test/intl402/Locale/prototype/getCollations/output-array-values.js' => $catalog->collations(
        'b8b2927b0c519222f3d6150318884567f0aa0f60dd37f902613147857fa9980a',
    ),
    'test/intl402/Locale/prototype/getCollations/output-array.js' => $catalog->collations(
        '12cc114bae185be464d6879fbf213af07718784a6365ae4770763214a5552529',
    ),
    'test/intl402/Locale/prototype/getCollations/prop-desc.js' => $catalog->sourceBoundLocaleMethod(
        'getCollations',
        'property',
        'e8192ddb54381113c2863db3ebfc5511e3cc009620a98e167fb9f0567c633b3b',
    ),
    'test/intl402/Locale/prototype/getCollations/und-language.js' => $catalog->collations(
        'f757f210ee7da9b01199c28f6d659108e397cce48df4b70f4a40bcd156442384',
    ),
];
