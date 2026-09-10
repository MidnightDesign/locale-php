<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools;

final class PhpExporter
{
    public static function export(mixed $value): string
    {
        $tokens = token_get_all('<?php '.var_export($value, true));
        $output = '';
        $afterArray = false;

        foreach ($tokens as $token) {
            if (is_array($token)) {
                [$type, $text] = $token;
                if ($type === T_OPEN_TAG) {
                    continue;
                }
                if ($afterArray && $type === T_WHITESPACE) {
                    continue;
                }
                if ($type === T_ARRAY) {
                    $output .= 'array';
                    $afterArray = true;
                    continue;
                }
                if ($type === T_STRING && strtoupper($text) === 'NULL') {
                    $output .= 'null';
                    $afterArray = false;
                    continue;
                }

                $output .= $text;
                $afterArray = false;
                continue;
            }

            $output .= $token;
            $afterArray = false;
        }

        return $output;
    }
}
