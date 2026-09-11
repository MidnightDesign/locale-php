<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

final class JavaScriptDataExtractor
{
    /** @return array<string, string> */
    public static function objectMap(string $source, string $name): array
    {
        if (preg_match('/(?:const|var)\s+'.preg_quote($name, '/').'\s*=\s*\{(?<body>.*?)\};/s', $source, $block) !== 1) {
            return [];
        }
        preg_match_all('/"(?<tag>[^"]+)"\s*:\s*"(?<expected>[^"]+)"/', $block['body'], $matches, PREG_SET_ORDER);
        $result = [];
        foreach ($matches as $match) {
            $result[$match['tag']] = $match['expected'];
        }

        return $result;
    }

    /** @return list<string> */
    public static function stringArray(string $source, string $name): array
    {
        if (preg_match('/(?:const|var)\s+'.preg_quote($name, '/').'\s*=\s*\[(?<body>.*?)\];/s', $source, $block) !== 1) {
            return [];
        }
        preg_match_all('/"([^"]*)"/', $block['body'], $matches);

        return $matches[1];
    }
}
