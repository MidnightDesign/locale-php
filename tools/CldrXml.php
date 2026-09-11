<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools;

final class CldrXml
{
    /** @return array<string, string> */
    public static function attributes(string $source): array
    {
        preg_match_all('/([A-Za-z][A-Za-z0-9]*)="([^"]*)"/', $source, $matches, PREG_SET_ORDER);
        $attributes = [];
        foreach ($matches as $match) {
            $attributes[$match[1]] = html_entity_decode($match[2], ENT_QUOTES | ENT_XML1);
        }

        return $attributes;
    }

    public static function isUnicodeType(string $value): bool
    {
        return preg_match('/^[a-z0-9]{3,8}(?:-[a-z0-9]{3,8})*$/D', $value) === 1;
    }
}
