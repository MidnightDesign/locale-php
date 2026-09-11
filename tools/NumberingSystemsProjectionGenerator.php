<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools;

final class NumberingSystemsProjectionGenerator
{
    /**
     * @return array{
     *     sourceSha256: string,
     *     upstreamSha512: string,
     *     generated: string,
     *     label: string,
     *     target: string
     * }
     */
    public static function generate(string $root, string $source): array
    {
        /** @var array{format: int, cldrRevision: string, upstreamSha512: string, defaults: array<string, string>, aliases: array<string, string>} $data */
        $data = json_decode($source, true, flags: JSON_THROW_ON_ERROR);
        if ($data['format'] !== 1) {
            throw new \UnexpectedValueException('The numbering-system projection format is incompatible.');
        }

        $defaults = self::export($data['defaults']);
        $aliases = self::export($data['aliases']);
        $sourceSha256 = hash('sha256', $source);
        $payloadSha256 = hash('sha256', json_encode([
            'format' => $data['format'],
            'defaults' => $data['defaults'],
            'aliases' => $data['aliases'],
        ], JSON_THROW_ON_ERROR));
        $generated = <<<PHP
            <?php

            declare(strict_types=1);

            namespace Midnight\Intl\Internal\Data;

            enum NumberingSystems
            {
                public const FORMAT = {$data['format']};

                /** @var string */
                public const CLDR_REVISION = '{$data['cldrRevision']}';

                /** @var string */
                public const CLDR_CORE_SHA512 = '{$data['upstreamSha512']}';

                /** @var string */
                public const SOURCE_SHA256 = '{$sourceSha256}';

                private const PAYLOAD_SHA256 = '{$payloadSha256}';

                /** @var array<string, string> */
                public const DEFAULTS = {$defaults};

                /** @var array<string, string> */
                public const ALIASES = {$aliases};

                public static function defaultFor(string \$locale): string
                {
                    self::assertIntegrity();

                    while (\$locale !== '') {
                        if (isset(self::DEFAULTS[\$locale])) {
                            return self::DEFAULTS[\$locale];
                        }
                        if (isset(self::ALIASES[\$locale])) {
                            \$locale = self::ALIASES[\$locale];
                            continue;
                        }
                        \$position = strrpos(\$locale, '-');
                        \$locale = \$position === false ? '' : substr(\$locale, 0, \$position);
                    }

                    return 'latn';
                }

                public static function assertIntegrity(): void
                {
                    /** @var bool|null \$verified */
                    static \$verified = null;
                    if (\$verified === true) {
                        return;
                    }

                    \$actual = hash('sha256', json_encode([
                        'format' => self::FORMAT,
                        'defaults' => self::DEFAULTS,
                        'aliases' => self::ALIASES,
                    ], JSON_THROW_ON_ERROR));
                    if (!self::supportsFormat(self::FORMAT) || \$actual !== self::PAYLOAD_SHA256) {
                        throw new \UnexpectedValueException('The bundled numbering-system data is corrupt or incompatible.');
                    }
                    \$verified = true;
                }

                private static function supportsFormat(int \$format): bool
                {
                    return \$format === 1;
                }
            }
            PHP;

        return [
            'sourceSha256' => $sourceSha256,
            'upstreamSha512' => $data['upstreamSha512'],
            'generated' => MagoFormatter::format($root, 'src/Internal/Data/NumberingSystems.php', $generated . "\n"),
            'label' => 'numbering-system',
            'target' => 'src/Internal/Data/NumberingSystems.php',
        ];
    }

    /** @param array<string, string> $values */
    private static function export(array $values): string
    {
        $export = preg_replace('/[ \t]+$/m', '', PhpExporter::export($values));
        if ($export === null) {
            throw new \RuntimeException('Unable to export the numbering-system projection.');
        }

        return $export;
    }
}
