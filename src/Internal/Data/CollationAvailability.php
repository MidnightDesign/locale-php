<?php

declare(strict_types=1);

namespace Midnight\Intl\Internal\Data;

enum CollationAvailability
{
    public const FORMAT = 1;

    /** @var string */
    public const CLDR_VERSION = '48.2';

    /** @var string */
    public const CLDR_REVISION = '11299982335beb974c1c63c45265184e759c0f41';

    /** @var string */
    public const CLDR_CORE_SHA512 = 'de8660f5371e0fcfd03a42e3b4fc4c686ec6cd602b402f1e3d227844005a54eb7952873894443523837d5828c42874a1a267a19f91ded207a2d166144791fa62';

    /** @var string */
    public const SOURCE_SHA256 = '9338192991e4810cbb0586395897d9a43e141c85d9793d73b0a573a88e1fd9f9';

    private const PAYLOAD_SHA256 = '984712bb591d312e73f596f634e27698858708393e2ddd789d2e5d8ee7068e77';

    /** @var list<string> */
    public const ROOT = array(
        0 => 'emoji',
        1 => 'eor',
    );

    /** @var array<string, list<string>> */
    public const LOCALES = array(
        'ar' => array(
            0 => 'compat',
            1 => 'emoji',
            2 => 'eor',
        ),
        'bn' => array(
            0 => 'emoji',
            1 => 'eor',
            2 => 'trad',
        ),
        'cs' => array(
            0 => 'digits-after',
            1 => 'emoji',
            2 => 'eor',
        ),
        'de' => array(
            0 => 'emoji',
            1 => 'eor',
            2 => 'phonebk',
        ),
        'de-AT' => array(
            0 => 'emoji',
            1 => 'eor',
            2 => 'phonebk',
        ),
        'es' => array(
            0 => 'emoji',
            1 => 'eor',
            2 => 'trad',
        ),
        'fi' => array(
            0 => 'emoji',
            1 => 'eor',
            2 => 'trad',
        ),
        'ja' => array(
            0 => 'emoji',
            1 => 'eor',
            2 => 'unihan',
        ),
        'kn' => array(
            0 => 'emoji',
            1 => 'eor',
            2 => 'trad',
        ),
        'ko' => array(
            0 => 'emoji',
            1 => 'eor',
            2 => 'searchjl',
            3 => 'unihan',
        ),
        'ln' => array(
            0 => 'emoji',
            1 => 'eor',
            2 => 'phonetic',
        ),
        'sa' => array(
            0 => 'emoji',
            1 => 'eor',
            2 => 'trad',
        ),
        'si' => array(
            0 => 'dict',
            1 => 'emoji',
            2 => 'eor',
        ),
        'sv' => array(
            0 => 'emoji',
            1 => 'eor',
            2 => 'trad',
        ),
        'vi' => array(
            0 => 'emoji',
            1 => 'eor',
            2 => 'trad',
        ),
        'zh' => array(
            0 => 'emoji',
            1 => 'eor',
            2 => 'pinyin',
            3 => 'stroke',
            4 => 'unihan',
            5 => 'zhuyin',
        ),
    );

    /** @return list<string> */
    public static function forLocale(string $locale): array
    {
        self::assertIntegrity();
        while ($locale !== '') {
            if (isset(self::LOCALES[$locale])) {
                return self::LOCALES[$locale];
            }
            $separator = strrpos($locale, '-');
            $locale = $separator === false ? '' : substr($locale, 0, $separator);
        }

        return self::ROOT;
    }

    public static function assertIntegrity(): void
    {
        /** @var bool|null $verified */
        static $verified = null;
        if ($verified === true) {
            return;
        }

        $actual = hash('sha256', json_encode([
            'format' => self::FORMAT,
            'root' => self::ROOT,
            'locales' => self::LOCALES,
        ], JSON_THROW_ON_ERROR));
        if (!self::supportsFormat(self::FORMAT) || $actual !== self::PAYLOAD_SHA256) {
            throw new \UnexpectedValueException('The bundled collation data is corrupt or incompatible.');
        }
        $verified = true;
    }

    private static function supportsFormat(int $format): bool
    {
        return $format === 1;
    }
}
