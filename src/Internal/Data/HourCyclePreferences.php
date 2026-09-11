<?php

declare(strict_types=1);

namespace Midnight\Intl\Internal\Data;

enum HourCyclePreferences
{
    public const FORMAT = 1;

    /** @var string */
    public const CLDR_REVISION = '11299982335beb974c1c63c45265184e759c0f41';

    /** @var string */
    public const CLDR_CORE_SHA512 = 'de8660f5371e0fcfd03a42e3b4fc4c686ec6cd602b402f1e3d227844005a54eb7952873894443523837d5828c42874a1a267a19f91ded207a2d166144791fa62';

    /** @var string */
    public const SOURCE_SHA256 = '61a93adca4c25d9f821f62208a38b3bf8b71fa097c7faf308128a7b40e009389';

    private const PAYLOAD_SHA256 = '90849a9869d6c33640ceda3969a4fc86238f4b62bfa3d4daf4b160b409624552';

    /** @var array<array-key, list<string>> */
    public const PREFERENCES = array(
        '001' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        419 => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'AC' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'AD' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'AE' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'AF' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'AG' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'AI' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'AL' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'AM' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'AO' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'AR' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'AS' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'AT' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'AU' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'AW' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'AX' => array(
            0 => 'h23',
        ),
        'AZ' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'BA' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'BB' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'BD' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'BE' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'BF' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'BG' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'BH' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'BI' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'BJ' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'BL' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'BM' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'BN' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'BO' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'BQ' => array(
            0 => 'h23',
        ),
        'BR' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'BS' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'BT' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'BW' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'BY' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'BZ' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'CA' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'CC' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'CD' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'CF' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'CG' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'CH' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'CI' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'CK' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'CL' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'CM' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'CN' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'CO' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'CP' => array(
            0 => 'h23',
        ),
        'CR' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'CU' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'CV' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'CW' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'CX' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'CY' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'CZ' => array(
            0 => 'h23',
        ),
        'DE' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'DG' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'DJ' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'DK' => array(
            0 => 'h23',
        ),
        'DM' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'DO' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'DZ' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'EA' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'EC' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'EE' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'EG' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'EH' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'ER' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'ES' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'ET' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'FI' => array(
            0 => 'h23',
        ),
        'FJ' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'FK' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'FM' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'FO' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'FR' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'GA' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'GB' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'GD' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'GE' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'GF' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'GG' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'GH' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'GI' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'GL' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'GM' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'GN' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'GP' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'GQ' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'GR' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'GS' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'GT' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'GU' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'GW' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'GY' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'HK' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'HN' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'HR' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'HU' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'IC' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'ID' => array(
            0 => 'h23',
        ),
        'IE' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'IL' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'IM' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'IN' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'IO' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'IQ' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'IR' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'IS' => array(
            0 => 'h23',
        ),
        'IT' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'JE' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'JM' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'JO' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'JP' => array(
            0 => 'h23',
            1 => 'h11',
            2 => 'h12',
        ),
        'KE' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'KG' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'KH' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'KI' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'KM' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'KN' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'KP' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'KR' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'KW' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'KY' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'KZ' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'LA' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'LB' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'LC' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'LI' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'LK' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'LR' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'LS' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'LT' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'LU' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'LV' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'LY' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'MA' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'MC' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'MD' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'ME' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'MF' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'MG' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'MH' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'MK' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'ML' => array(
            0 => 'h23',
        ),
        'MM' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'MN' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'MO' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'MP' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'MQ' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'MR' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'MS' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'MT' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'MU' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'MV' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'MW' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'MX' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'MY' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'MZ' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'NA' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'NC' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'NE' => array(
            0 => 'h23',
        ),
        'NF' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'NG' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'NI' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'NL' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'NO' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'NP' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'NR' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'NU' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'NZ' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'OM' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'PA' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'PE' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'PF' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'PG' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'PH' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'PK' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'PL' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'PM' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'PN' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'PR' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'PS' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'PT' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'PW' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'PY' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'QA' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'RE' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'RO' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'RS' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'RU' => array(
            0 => 'h23',
        ),
        'RW' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'SA' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'SB' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'SC' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'SD' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'SE' => array(
            0 => 'h23',
        ),
        'SG' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'SH' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'SI' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'SJ' => array(
            0 => 'h23',
        ),
        'SK' => array(
            0 => 'h23',
        ),
        'SL' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'SM' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'SN' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'SO' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'SR' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'SS' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'ST' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'SV' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'SX' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'SY' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'SZ' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'TA' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'TC' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'TD' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'TF' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'TG' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'TH' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'TJ' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'TL' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'TM' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'TN' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'TO' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'TR' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'TT' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'TW' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'TZ' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'UA' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'UG' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'UM' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'US' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'UY' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'UZ' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'VA' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'VC' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'VE' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'VG' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'VI' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'VN' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'VU' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'WF' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'WS' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'XK' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'YE' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'YT' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'ZA' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'ZM' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'ZW' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'af-ZA' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'ar-001' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'ca-ES' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'en-001' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'en-HK' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'en-IL' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'en-MY' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'es-BR' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'es-ES' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'es-GQ' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'fr-CA' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'gl-ES' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'gu-IN' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'hi-IN' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'it-CH' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'it-IT' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'kn-IN' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'ku-SY' => array(
            0 => 'h23',
            1 => 'h12',
        ),
        'ml-IN' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'mr-IN' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'pa-IN' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'ta-IN' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'te-IN' => array(
            0 => 'h12',
            1 => 'h23',
        ),
        'zu-ZA' => array(
            0 => 'h23',
            1 => 'h12',
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
            throw new \UnexpectedValueException('The bundled hour-cycle preference data is corrupt or incompatible.');
        }
        $verified = true;
    }

    /** @return array<string, mixed> */
    private static function payload(): array
    {
        return [
            'format' => self::FORMAT,
            'preferences' => self::PREFERENCES,
        ];
    }
}
