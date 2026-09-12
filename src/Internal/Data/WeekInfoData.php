<?php

declare(strict_types=1);

namespace Midnight\Intl\Internal\Data;

enum WeekInfoData
{
    public const FORMAT = 1;

    /** @var string */
    public const CLDR_REVISION = '11299982335beb974c1c63c45265184e759c0f41';

    /** @var string */
    public const CLDR_CORE_SHA512 = 'de8660f5371e0fcfd03a42e3b4fc4c686ec6cd602b402f1e3d227844005a54eb7952873894443523837d5828c42874a1a267a19f91ded207a2d166144791fa62';

    /** @var string */
    public const SOURCE_SHA256 = 'b61801b78d6d5a675118ce422d6ea6ab7f3b8d427524b02f92a026680061a0c5';

    private const PAYLOAD_SHA256 = 'c75be2015f0b65d9b3f764286c6d352baca953070c9d885bda03ace45852a216';

    /** @var array<string, int<1, 7>> */
    public const FIRST_DAY = array(
        '001' => 1,
        'AD' => 1,
        'AE' => 1,
        'AF' => 6,
        'AG' => 7,
        'AI' => 1,
        'AL' => 1,
        'AM' => 1,
        'AN' => 1,
        'AR' => 1,
        'AS' => 7,
        'AT' => 1,
        'AU' => 1,
        'AX' => 1,
        'AZ' => 1,
        'BA' => 1,
        'BD' => 7,
        'BE' => 1,
        'BG' => 1,
        'BH' => 6,
        'BM' => 1,
        'BN' => 1,
        'BR' => 7,
        'BS' => 7,
        'BT' => 7,
        'BW' => 7,
        'BY' => 1,
        'BZ' => 7,
        'CA' => 7,
        'CH' => 1,
        'CL' => 1,
        'CM' => 1,
        'CN' => 1,
        'CO' => 7,
        'CR' => 1,
        'CY' => 1,
        'CZ' => 1,
        'DE' => 1,
        'DJ' => 6,
        'DK' => 1,
        'DM' => 7,
        'DO' => 7,
        'DZ' => 6,
        'EC' => 1,
        'EE' => 1,
        'EG' => 6,
        'ES' => 1,
        'ET' => 7,
        'FI' => 1,
        'FJ' => 1,
        'FO' => 1,
        'FR' => 1,
        'GB' => 1,
        'GE' => 1,
        'GF' => 1,
        'GP' => 1,
        'GR' => 1,
        'GT' => 7,
        'GU' => 7,
        'HK' => 7,
        'HN' => 7,
        'HR' => 1,
        'HU' => 1,
        'ID' => 7,
        'IE' => 1,
        'IL' => 7,
        'IN' => 7,
        'IQ' => 6,
        'IR' => 6,
        'IS' => 7,
        'IT' => 1,
        'JM' => 7,
        'JO' => 6,
        'JP' => 7,
        'KE' => 7,
        'KG' => 1,
        'KH' => 7,
        'KR' => 7,
        'KW' => 6,
        'KZ' => 1,
        'LA' => 7,
        'LB' => 1,
        'LI' => 1,
        'LK' => 1,
        'LT' => 1,
        'LU' => 1,
        'LV' => 1,
        'LY' => 6,
        'MC' => 1,
        'MD' => 1,
        'ME' => 1,
        'MH' => 7,
        'MK' => 1,
        'MM' => 7,
        'MN' => 1,
        'MO' => 7,
        'MQ' => 1,
        'MT' => 7,
        'MV' => 5,
        'MX' => 7,
        'MY' => 1,
        'MZ' => 7,
        'NI' => 7,
        'NL' => 1,
        'NO' => 1,
        'NP' => 7,
        'NZ' => 1,
        'OM' => 6,
        'PA' => 7,
        'PE' => 7,
        'PH' => 7,
        'PK' => 7,
        'PL' => 1,
        'PR' => 7,
        'PT' => 7,
        'PY' => 7,
        'QA' => 6,
        'RE' => 1,
        'RO' => 1,
        'RS' => 1,
        'RU' => 1,
        'SA' => 7,
        'SD' => 6,
        'SE' => 1,
        'SG' => 7,
        'SI' => 1,
        'SK' => 1,
        'SM' => 1,
        'SV' => 7,
        'SY' => 6,
        'TH' => 7,
        'TJ' => 1,
        'TM' => 1,
        'TR' => 1,
        'TT' => 7,
        'TW' => 7,
        'UA' => 1,
        'UM' => 7,
        'US' => 7,
        'UY' => 1,
        'UZ' => 1,
        'VA' => 1,
        'VE' => 7,
        'VI' => 7,
        'VN' => 1,
        'WS' => 7,
        'XK' => 1,
        'YE' => 7,
        'ZA' => 7,
        'ZW' => 7,
    );

    /** @var array<string, int<1, 7>> */
    public const WEEKEND_START = array(
        '001' => 6,
        'AF' => 4,
        'BH' => 5,
        'DZ' => 5,
        'EG' => 5,
        'IL' => 5,
        'IN' => 7,
        'IQ' => 5,
        'IR' => 5,
        'JO' => 5,
        'KW' => 5,
        'LY' => 5,
        'OM' => 5,
        'QA' => 5,
        'SA' => 5,
        'SD' => 5,
        'SY' => 5,
        'UG' => 7,
        'YE' => 5,
    );

    /** @var array<string, int<1, 7>> */
    public const WEEKEND_END = array(
        '001' => 7,
        'AF' => 5,
        'BH' => 6,
        'DZ' => 6,
        'EG' => 6,
        'IL' => 6,
        'IQ' => 6,
        'IR' => 5,
        'JO' => 6,
        'KW' => 6,
        'LY' => 6,
        'OM' => 6,
        'QA' => 6,
        'SA' => 6,
        'SD' => 6,
        'SY' => 6,
        'YE' => 6,
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
            throw new \UnexpectedValueException('The bundled week-information data is corrupt or incompatible.');
        }
        $verified = true;
    }

    /** @return array<string, mixed> */
    private static function payload(): array
    {
        return [
            'format' => self::FORMAT,
            'firstDay' => self::FIRST_DAY,
            'weekendStart' => self::WEEKEND_START,
            'weekendEnd' => self::WEEKEND_END,
        ];
    }
}
