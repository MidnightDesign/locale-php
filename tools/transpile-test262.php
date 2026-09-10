<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$revision = '419d3e0a2273ba01a3bfcbec423f2801425b8e93';
$fixturePath = 'test/intl402/Locale/getters-missing.js';
$fixture = file_get_contents($root.'/tests/Test262/upstream/'.$fixturePath);

if ($fixture === false) {
    fwrite(STDERR, "Unable to read the pinned Test262 fixture.\n");
    exit(1);
}

preg_match_all(
    '/var loc = new Intl\\.Locale\\("(?<tag>[^"]+)"\\);(?<body>.*?)(?=\\nvar loc =|\\z)/s',
    $fixture,
    $cases,
    PREG_SET_ORDER,
);

$rows = [];
$inventory = [];
$assertionNumber = 0;
foreach ($cases as $case) {
    preg_match_all(
        '/assert\\.sameValue\\(loc\\.(?<property>baseName|language|script|region|variants),\\s*(?<expected>undefined|"[^"]*"|\'[^\']*\')\\);/',
        $case['body'],
        $assertions,
        PREG_SET_ORDER,
    );

    $expected = [];
    foreach ($assertions as $assertion) {
        ++$assertionNumber;
        $property = $assertion['property'];
        $value = $assertion['expected'] === 'undefined'
            ? null
            : substr($assertion['expected'], 1, -1);

        if ($property === 'variants') {
            $inventory[] = [
                'id' => $fixturePath.'#'.$assertionNumber,
                'status' => 'translation_gap',
                'reason' => 'The variants property is outside the initial language/script/region slice.',
            ];
            continue;
        }

        $expected[$property] = $value;
        $inventory[] = [
            'id' => $fixturePath.'#'.$assertionNumber,
            'status' => 'applicable',
            'phpRepresentations' => ['direct'],
        ];
    }

    if (!str_contains($case['tag'], '-1901')) {
        $rows[$case['tag']] = $expected;
    } else {
        foreach ($inventory as &$item) {
            if (str_starts_with($item['id'], $fixturePath.'#') && (int) substr($item['id'], strrpos($item['id'], '#') + 1) > $assertionNumber - 5) {
                $item['status'] = 'translation_gap';
                unset($item['phpRepresentations']);
                $item['reason'] = 'Variants in the input identifier are outside the initial slice.';
            }
        }
        unset($item);
    }
}

$export = var_export($rows, true);
$generated = <<<PHP
<?php

declare(strict_types=1);

// Copyright 2018 André Bargull; Igalia, S.L. All rights reserved.
// This generated translation is governed by tests/Test262/upstream/LICENSE.
// Source: {$fixturePath} at Test262 {$revision}.

namespace Midnight\\Intl\\Tests\\Test262\\Generated;

use Midnight\\Intl\\Spec\\Locale;
use PHPUnit\\Framework\\Attributes\\DataProvider;
use PHPUnit\\Framework\\TestCase;

final class GettersMissingTest extends TestCase
{
    /** @return list<array{string, array<string, string|null>}> */
    public static function cases(): array
    {
        return array_map(
            static fn (array \$expected, string \$tag): array => [\$tag, \$expected],
            {$export},
            array_keys({$export}),
        );
    }

    /** @param array<string, string|null> \$expected */
    #[DataProvider('cases')]
    public function testTranslatedGetterAssertions(string \$tag, array \$expected): void
    {
        \$locale = new Locale(\$tag);

        foreach (\$expected as \$property => \$value) {
            self::assertSame(\$value, \$locale->{\$property});
        }
    }
}
PHP;

$evidence = json_encode([
    'test262Revision' => $revision,
    'fixtures' => [[
        'path' => $fixturePath,
        'sha256' => hash('sha256', $fixture),
        'assertions' => $inventory,
    ]],
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n";

$outputs = [
    $root.'/tests/Test262/Generated/GettersMissingTest.php' => $generated,
    $root.'/tests/Test262/evidence.json' => $evidence,
];

$check = in_array('--check', $argv, true);
foreach ($outputs as $path => $contents) {
    if ($check) {
        if (!is_file($path) || file_get_contents($path) !== $contents) {
            fwrite(STDERR, str_replace($root.'/', '', $path)." is not reproducible.\n");
            exit(1);
        }
        continue;
    }

    if (!is_dir(dirname($path))) {
        mkdir(dirname($path), 0777, true);
    }
    file_put_contents($path, $contents);
}
