<?php

declare(strict_types=1);

namespace Midnight\Intl\Internal\Data;

enum CalendarPreferences
{
    public const FORMAT = 1;

    /** @var string */
    public const CLDR_REVISION = '11299982335beb974c1c63c45265184e759c0f41';

    /** @var string */
    public const CLDR_CORE_SHA512 = 'de8660f5371e0fcfd03a42e3b4fc4c686ec6cd602b402f1e3d227844005a54eb7952873894443523837d5828c42874a1a267a19f91ded207a2d166144791fa62';

    /** @var string */
    public const SOURCE_SHA256 = '4dbd69185741c9e6dae89e1fec0d5d922864f2c17033f3ffd3822fc3e8afcb7f';

    private const PAYLOAD_SHA256 = 'acab9f86c38e0ee690b19db73732e0373489bc507798f0f9409dd88eeb559334';

    /** @var list<string> */
    public const AVAILABLE = array(
        0 => 'buddhist',
        1 => 'chinese',
        2 => 'coptic',
        3 => 'dangi',
        4 => 'ethioaa',
        5 => 'ethiopic',
        6 => 'gregory',
        7 => 'hebrew',
        8 => 'indian',
        9 => 'islamic',
        10 => 'islamic-umalqura',
        11 => 'islamic-tbla',
        12 => 'islamic-civil',
        13 => 'islamic-rgsa',
        14 => 'iso8601',
        15 => 'japanese',
        16 => 'persian',
        17 => 'roc',
    );

    /** @var array<array-key, list<string>> */
    public const PREFERENCES = array(
        '001' => array(
            0 => 'gregory',
        ),
        'AE' => array(
            0 => 'gregory',
            1 => 'islamic-umalqura',
            2 => 'islamic',
            3 => 'islamic-civil',
            4 => 'islamic-tbla',
        ),
        'AF' => array(
            0 => 'persian',
            1 => 'gregory',
            2 => 'islamic',
            3 => 'islamic-civil',
            4 => 'islamic-tbla',
        ),
        'AL' => array(
            0 => 'gregory',
            1 => 'islamic-civil',
            2 => 'islamic-tbla',
        ),
        'AZ' => array(
            0 => 'gregory',
            1 => 'islamic-civil',
            2 => 'islamic-tbla',
        ),
        'BD' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'BH' => array(
            0 => 'gregory',
            1 => 'islamic-umalqura',
            2 => 'islamic',
            3 => 'islamic-civil',
            4 => 'islamic-tbla',
        ),
        'CN' => array(
            0 => 'gregory',
            1 => 'chinese',
        ),
        'CX' => array(
            0 => 'gregory',
            1 => 'chinese',
        ),
        'DJ' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'DZ' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'EG' => array(
            0 => 'gregory',
            1 => 'coptic',
            2 => 'islamic',
            3 => 'islamic-civil',
            4 => 'islamic-tbla',
        ),
        'EH' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'ER' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'ET' => array(
            0 => 'gregory',
            1 => 'ethiopic',
        ),
        'HK' => array(
            0 => 'gregory',
            1 => 'chinese',
        ),
        'ID' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'IL' => array(
            0 => 'gregory',
            1 => 'hebrew',
            2 => 'islamic',
            3 => 'islamic-civil',
            4 => 'islamic-tbla',
        ),
        'IN' => array(
            0 => 'gregory',
            1 => 'indian',
        ),
        'IQ' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'IR' => array(
            0 => 'persian',
            1 => 'gregory',
            2 => 'islamic',
            3 => 'islamic-civil',
            4 => 'islamic-tbla',
        ),
        'JO' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'JP' => array(
            0 => 'gregory',
            1 => 'japanese',
        ),
        'KM' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'KR' => array(
            0 => 'gregory',
            1 => 'dangi',
        ),
        'KW' => array(
            0 => 'gregory',
            1 => 'islamic-umalqura',
            2 => 'islamic',
            3 => 'islamic-civil',
            4 => 'islamic-tbla',
        ),
        'LB' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'LY' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'MA' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'MO' => array(
            0 => 'gregory',
            1 => 'chinese',
        ),
        'MR' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'MV' => array(
            0 => 'gregory',
            1 => 'islamic-civil',
            2 => 'islamic-tbla',
        ),
        'MY' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'NE' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'OM' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'PK' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'PS' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'QA' => array(
            0 => 'gregory',
            1 => 'islamic-umalqura',
            2 => 'islamic',
            3 => 'islamic-civil',
            4 => 'islamic-tbla',
        ),
        'SA' => array(
            0 => 'gregory',
            1 => 'islamic-umalqura',
            2 => 'islamic',
            3 => 'islamic-rgsa',
        ),
        'SD' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'SG' => array(
            0 => 'gregory',
            1 => 'chinese',
        ),
        'SY' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'TD' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'TH' => array(
            0 => 'buddhist',
            1 => 'gregory',
        ),
        'TJ' => array(
            0 => 'gregory',
            1 => 'islamic-civil',
            2 => 'islamic-tbla',
        ),
        'TM' => array(
            0 => 'gregory',
            1 => 'islamic-civil',
            2 => 'islamic-tbla',
        ),
        'TN' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
        'TR' => array(
            0 => 'gregory',
            1 => 'islamic-civil',
            2 => 'islamic-tbla',
        ),
        'TW' => array(
            0 => 'gregory',
            1 => 'roc',
            2 => 'chinese',
        ),
        'UZ' => array(
            0 => 'gregory',
            1 => 'islamic-civil',
            2 => 'islamic-tbla',
        ),
        'XK' => array(
            0 => 'gregory',
            1 => 'islamic-civil',
            2 => 'islamic-tbla',
        ),
        'YE' => array(
            0 => 'gregory',
            1 => 'islamic',
            2 => 'islamic-civil',
            3 => 'islamic-tbla',
        ),
    );

    /** @psalm-api */
    public static function assertIntegrity(): void
    {
        /** @var bool|null $verified */
        static $verified = null;
        if ($verified === true) {
            return;
        }

        $actual = hash('sha256', json_encode(self::payload(), JSON_THROW_ON_ERROR));
        if ($actual !== self::PAYLOAD_SHA256) {
            throw new \UnexpectedValueException('The bundled calendar preference data is corrupt or incompatible.');
        }
        $verified = true;
    }

    /** @return array<string, mixed> */
    private static function payload(): array
    {
        return [
            'format' => self::FORMAT,
            'available' => self::AVAILABLE,
            'preferences' => self::PREFERENCES,
        ];
    }
}
