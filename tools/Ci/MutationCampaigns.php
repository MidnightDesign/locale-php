<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Ci;

final class MutationCampaigns
{
    private const CAMPAIGNS = [
        'spec' => [
            'suite' => 'test262-upstream',
            'excludes' => [
                '/^Locale\\.php$/',
                '/^CaseFirst\\.php$/',
                '/^HourCycle\\.php$/',
                '/^Internal\\/Data\\/LocaleAliases\\.php$/',
            ],
        ],
        'porcelain' => [
            'suite' => 'porcelain',
            'excludes' => ['Spec', 'Internal', 'Exception'],
        ],
    ];

    private const EXTENSION_MODES = ['absent', 'disabled', 'native'];

    /** @return list<string> */
    public static function names(): array
    {
        return array_keys(self::CAMPAIGNS);
    }

    /** @return list<string> */
    public static function extensionModes(): array
    {
        return self::EXTENSION_MODES;
    }

    public static function suite(string $campaign): string
    {
        return self::definition($campaign)['suite'];
    }

    /** @return list<string> */
    public static function excludes(string $campaign): array
    {
        return self::definition($campaign)['excludes'];
    }

    public static function forSource(string $source): ?string
    {
        if ($source === 'src/Internal/Data/LocaleAliases.php') {
            return null;
        }
        if (in_array($source, ['src/Locale.php', 'src/CaseFirst.php', 'src/HourCycle.php'], true)) {
            return 'porcelain';
        }
        foreach (['src/Spec/', 'src/Internal/', 'src/Exception/'] as $prefix) {
            if (str_starts_with($source, $prefix)) {
                return 'spec';
            }
        }

        throw new \RuntimeException(sprintf('Mutation source %s has no campaign ownership.', $source));
    }

    /** @return array{suite: string, excludes: list<string>} */
    private static function definition(string $campaign): array
    {
        return self::CAMPAIGNS[$campaign]
            ?? throw new \RuntimeException(sprintf('Unknown mutation campaign %s.', $campaign));
    }
}
