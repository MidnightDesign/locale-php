<?php

declare(strict_types=1);

namespace Midnight\Intl\Internal\Data;

enum PrimaryTimeZones
{
    public const FORMAT = 1;

    /** @var string */
    public const TZDB_VERSION = '2026c';

    /** @var string */
    public const TZDB_SHA512 = 'e0b4b7044b66fbc27bc21d13d18063abcdf78ab58d5ba5fd64bd1a88d86e9d495f45add4d8e65bb6c40249f9c94ca29b72c8ebba8d0e4c468f2965ac77932ef0';

    /** @var string */
    public const CLDR_REVISION = '11299982335beb974c1c63c45265184e759c0f41';

    /** @var string */
    public const CLDR_CORE_SHA512 = 'de8660f5371e0fcfd03a42e3b4fc4c686ec6cd602b402f1e3d227844005a54eb7952873894443523837d5828c42874a1a267a19f91ded207a2d166144791fa62';

    /** @var string */
    public const SOURCE_SHA256 = '282aba05517c6a88023f18ff16770757a336b2697c1c5c9ce79756c70104b08e';

    private const PAYLOAD_SHA256 = '399e6f6504cc39b644d11d43610be50d56e49e4de5a57c20a0afed552a2d0a03';
    /** @var array<string, list<string>> */
    public const REGIONS = array(
        'AD' => array(
            0 => 'Europe/Andorra',
        ),
        'AE' => array(
            0 => 'Asia/Dubai',
        ),
        'AF' => array(
            0 => 'Asia/Kabul',
        ),
        'AG' => array(
            0 => 'America/Antigua',
        ),
        'AI' => array(
            0 => 'America/Anguilla',
        ),
        'AL' => array(
            0 => 'Europe/Tirane',
        ),
        'AM' => array(
            0 => 'Asia/Yerevan',
        ),
        'AO' => array(
            0 => 'Africa/Luanda',
        ),
        'AQ' => array(
            0 => 'Antarctica/Casey',
            1 => 'Antarctica/Davis',
            2 => 'Antarctica/DumontDUrville',
            3 => 'Antarctica/Mawson',
            4 => 'Antarctica/McMurdo',
            5 => 'Antarctica/Palmer',
            6 => 'Antarctica/Rothera',
            7 => 'Antarctica/Syowa',
            8 => 'Antarctica/Troll',
            9 => 'Antarctica/Vostok',
        ),
        'AR' => array(
            0 => 'America/Argentina/Buenos_Aires',
            1 => 'America/Argentina/Catamarca',
            2 => 'America/Argentina/Cordoba',
            3 => 'America/Argentina/Jujuy',
            4 => 'America/Argentina/La_Rioja',
            5 => 'America/Argentina/Mendoza',
            6 => 'America/Argentina/Rio_Gallegos',
            7 => 'America/Argentina/Salta',
            8 => 'America/Argentina/San_Juan',
            9 => 'America/Argentina/San_Luis',
            10 => 'America/Argentina/Tucuman',
            11 => 'America/Argentina/Ushuaia',
        ),
        'AS' => array(
            0 => 'Pacific/Pago_Pago',
        ),
        'AT' => array(
            0 => 'Europe/Vienna',
        ),
        'AU' => array(
            0 => 'Antarctica/Macquarie',
            1 => 'Australia/Adelaide',
            2 => 'Australia/Brisbane',
            3 => 'Australia/Broken_Hill',
            4 => 'Australia/Darwin',
            5 => 'Australia/Eucla',
            6 => 'Australia/Hobart',
            7 => 'Australia/Lindeman',
            8 => 'Australia/Lord_Howe',
            9 => 'Australia/Melbourne',
            10 => 'Australia/Perth',
            11 => 'Australia/Sydney',
        ),
        'AW' => array(
            0 => 'America/Aruba',
        ),
        'AX' => array(
            0 => 'Europe/Mariehamn',
        ),
        'AZ' => array(
            0 => 'Asia/Baku',
        ),
        'BA' => array(
            0 => 'Europe/Sarajevo',
        ),
        'BB' => array(
            0 => 'America/Barbados',
        ),
        'BD' => array(
            0 => 'Asia/Dhaka',
        ),
        'BE' => array(
            0 => 'Europe/Brussels',
        ),
        'BF' => array(
            0 => 'Africa/Ouagadougou',
        ),
        'BG' => array(
            0 => 'Europe/Sofia',
        ),
        'BH' => array(
            0 => 'Asia/Bahrain',
        ),
        'BI' => array(
            0 => 'Africa/Bujumbura',
        ),
        'BJ' => array(
            0 => 'Africa/Porto-Novo',
        ),
        'BL' => array(
            0 => 'America/St_Barthelemy',
        ),
        'BM' => array(
            0 => 'Atlantic/Bermuda',
        ),
        'BN' => array(
            0 => 'Asia/Brunei',
        ),
        'BO' => array(
            0 => 'America/La_Paz',
        ),
        'BQ' => array(
            0 => 'America/Kralendijk',
        ),
        'BR' => array(
            0 => 'America/Araguaina',
            1 => 'America/Bahia',
            2 => 'America/Belem',
            3 => 'America/Boa_Vista',
            4 => 'America/Campo_Grande',
            5 => 'America/Cuiaba',
            6 => 'America/Eirunepe',
            7 => 'America/Fortaleza',
            8 => 'America/Maceio',
            9 => 'America/Manaus',
            10 => 'America/Noronha',
            11 => 'America/Porto_Velho',
            12 => 'America/Recife',
            13 => 'America/Rio_Branco',
            14 => 'America/Santarem',
            15 => 'America/Sao_Paulo',
        ),
        'BS' => array(
            0 => 'America/Nassau',
        ),
        'BT' => array(
            0 => 'Asia/Thimphu',
        ),
        'BW' => array(
            0 => 'Africa/Gaborone',
        ),
        'BY' => array(
            0 => 'Europe/Minsk',
        ),
        'BZ' => array(
            0 => 'America/Belize',
        ),
        'CA' => array(
            0 => 'America/Atikokan',
            1 => 'America/Blanc-Sablon',
            2 => 'America/Cambridge_Bay',
            3 => 'America/Creston',
            4 => 'America/Dawson',
            5 => 'America/Dawson_Creek',
            6 => 'America/Edmonton',
            7 => 'America/Fort_Nelson',
            8 => 'America/Glace_Bay',
            9 => 'America/Goose_Bay',
            10 => 'America/Halifax',
            11 => 'America/Inuvik',
            12 => 'America/Iqaluit',
            13 => 'America/Moncton',
            14 => 'America/Rankin_Inlet',
            15 => 'America/Regina',
            16 => 'America/Resolute',
            17 => 'America/St_Johns',
            18 => 'America/Swift_Current',
            19 => 'America/Toronto',
            20 => 'America/Vancouver',
            21 => 'America/Whitehorse',
            22 => 'America/Winnipeg',
        ),
        'CC' => array(
            0 => 'Indian/Cocos',
        ),
        'CD' => array(
            0 => 'Africa/Kinshasa',
            1 => 'Africa/Lubumbashi',
        ),
        'CF' => array(
            0 => 'Africa/Bangui',
        ),
        'CG' => array(
            0 => 'Africa/Brazzaville',
        ),
        'CH' => array(
            0 => 'Europe/Zurich',
        ),
        'CI' => array(
            0 => 'Africa/Abidjan',
        ),
        'CK' => array(
            0 => 'Pacific/Rarotonga',
        ),
        'CL' => array(
            0 => 'America/Coyhaique',
            1 => 'America/Punta_Arenas',
            2 => 'America/Santiago',
            3 => 'Pacific/Easter',
        ),
        'CM' => array(
            0 => 'Africa/Douala',
        ),
        'CN' => array(
            0 => 'Asia/Shanghai',
            1 => 'Asia/Urumqi',
        ),
        'CO' => array(
            0 => 'America/Bogota',
        ),
        'CR' => array(
            0 => 'America/Costa_Rica',
        ),
        'CU' => array(
            0 => 'America/Havana',
        ),
        'CV' => array(
            0 => 'Atlantic/Cape_Verde',
        ),
        'CW' => array(
            0 => 'America/Curacao',
        ),
        'CX' => array(
            0 => 'Indian/Christmas',
        ),
        'CY' => array(
            0 => 'Asia/Famagusta',
            1 => 'Asia/Nicosia',
        ),
        'CZ' => array(
            0 => 'Europe/Prague',
        ),
        'DE' => array(
            0 => 'Europe/Berlin',
            1 => 'Europe/Busingen',
        ),
        'DJ' => array(
            0 => 'Africa/Djibouti',
        ),
        'DK' => array(
            0 => 'Europe/Copenhagen',
        ),
        'DM' => array(
            0 => 'America/Dominica',
        ),
        'DO' => array(
            0 => 'America/Santo_Domingo',
        ),
        'DZ' => array(
            0 => 'Africa/Algiers',
        ),
        'EC' => array(
            0 => 'America/Guayaquil',
            1 => 'Pacific/Galapagos',
        ),
        'EE' => array(
            0 => 'Europe/Tallinn',
        ),
        'EG' => array(
            0 => 'Africa/Cairo',
        ),
        'EH' => array(
            0 => 'Africa/El_Aaiun',
        ),
        'ER' => array(
            0 => 'Africa/Asmara',
        ),
        'ES' => array(
            0 => 'Africa/Ceuta',
            1 => 'Atlantic/Canary',
            2 => 'Europe/Madrid',
        ),
        'ET' => array(
            0 => 'Africa/Addis_Ababa',
        ),
        'FI' => array(
            0 => 'Europe/Helsinki',
        ),
        'FJ' => array(
            0 => 'Pacific/Fiji',
        ),
        'FK' => array(
            0 => 'Atlantic/Stanley',
        ),
        'FM' => array(
            0 => 'Pacific/Chuuk',
            1 => 'Pacific/Kosrae',
            2 => 'Pacific/Pohnpei',
        ),
        'FO' => array(
            0 => 'Atlantic/Faroe',
        ),
        'FR' => array(
            0 => 'Europe/Paris',
        ),
        'GA' => array(
            0 => 'Africa/Libreville',
        ),
        'GB' => array(
            0 => 'Europe/London',
        ),
        'GD' => array(
            0 => 'America/Grenada',
        ),
        'GE' => array(
            0 => 'Asia/Tbilisi',
        ),
        'GF' => array(
            0 => 'America/Cayenne',
        ),
        'GG' => array(
            0 => 'Europe/Guernsey',
        ),
        'GH' => array(
            0 => 'Africa/Accra',
        ),
        'GI' => array(
            0 => 'Europe/Gibraltar',
        ),
        'GL' => array(
            0 => 'America/Danmarkshavn',
            1 => 'America/Nuuk',
            2 => 'America/Scoresbysund',
            3 => 'America/Thule',
        ),
        'GM' => array(
            0 => 'Africa/Banjul',
        ),
        'GN' => array(
            0 => 'Africa/Conakry',
        ),
        'GP' => array(
            0 => 'America/Guadeloupe',
        ),
        'GQ' => array(
            0 => 'Africa/Malabo',
        ),
        'GR' => array(
            0 => 'Europe/Athens',
        ),
        'GS' => array(
            0 => 'Atlantic/South_Georgia',
        ),
        'GT' => array(
            0 => 'America/Guatemala',
        ),
        'GU' => array(
            0 => 'Pacific/Guam',
        ),
        'GW' => array(
            0 => 'Africa/Bissau',
        ),
        'GY' => array(
            0 => 'America/Guyana',
        ),
        'HK' => array(
            0 => 'Asia/Hong_Kong',
        ),
        'HN' => array(
            0 => 'America/Tegucigalpa',
        ),
        'HR' => array(
            0 => 'Europe/Zagreb',
        ),
        'HT' => array(
            0 => 'America/Port-au-Prince',
        ),
        'HU' => array(
            0 => 'Europe/Budapest',
        ),
        'ID' => array(
            0 => 'Asia/Jakarta',
            1 => 'Asia/Jayapura',
            2 => 'Asia/Makassar',
            3 => 'Asia/Pontianak',
        ),
        'IE' => array(
            0 => 'Europe/Dublin',
        ),
        'IL' => array(
            0 => 'Asia/Jerusalem',
        ),
        'IM' => array(
            0 => 'Europe/Isle_of_Man',
        ),
        'IN' => array(
            0 => 'Asia/Kolkata',
        ),
        'IO' => array(
            0 => 'Indian/Chagos',
        ),
        'IQ' => array(
            0 => 'Asia/Baghdad',
        ),
        'IR' => array(
            0 => 'Asia/Tehran',
        ),
        'IS' => array(
            0 => 'Atlantic/Reykjavik',
        ),
        'IT' => array(
            0 => 'Europe/Rome',
        ),
        'JE' => array(
            0 => 'Europe/Jersey',
        ),
        'JM' => array(
            0 => 'America/Jamaica',
        ),
        'JO' => array(
            0 => 'Asia/Amman',
        ),
        'JP' => array(
            0 => 'Asia/Tokyo',
        ),
        'KE' => array(
            0 => 'Africa/Nairobi',
        ),
        'KG' => array(
            0 => 'Asia/Bishkek',
        ),
        'KH' => array(
            0 => 'Asia/Phnom_Penh',
        ),
        'KI' => array(
            0 => 'Pacific/Kanton',
            1 => 'Pacific/Kiritimati',
            2 => 'Pacific/Tarawa',
        ),
        'KM' => array(
            0 => 'Indian/Comoro',
        ),
        'KN' => array(
            0 => 'America/St_Kitts',
        ),
        'KP' => array(
            0 => 'Asia/Pyongyang',
        ),
        'KR' => array(
            0 => 'Asia/Seoul',
        ),
        'KW' => array(
            0 => 'Asia/Kuwait',
        ),
        'KY' => array(
            0 => 'America/Cayman',
        ),
        'KZ' => array(
            0 => 'Asia/Almaty',
            1 => 'Asia/Aqtau',
            2 => 'Asia/Aqtobe',
            3 => 'Asia/Atyrau',
            4 => 'Asia/Oral',
            5 => 'Asia/Qostanay',
            6 => 'Asia/Qyzylorda',
        ),
        'LA' => array(
            0 => 'Asia/Vientiane',
        ),
        'LB' => array(
            0 => 'Asia/Beirut',
        ),
        'LC' => array(
            0 => 'America/St_Lucia',
        ),
        'LI' => array(
            0 => 'Europe/Vaduz',
        ),
        'LK' => array(
            0 => 'Asia/Colombo',
        ),
        'LR' => array(
            0 => 'Africa/Monrovia',
        ),
        'LS' => array(
            0 => 'Africa/Maseru',
        ),
        'LT' => array(
            0 => 'Europe/Vilnius',
        ),
        'LU' => array(
            0 => 'Europe/Luxembourg',
        ),
        'LV' => array(
            0 => 'Europe/Riga',
        ),
        'LY' => array(
            0 => 'Africa/Tripoli',
        ),
        'MA' => array(
            0 => 'Africa/Casablanca',
        ),
        'MC' => array(
            0 => 'Europe/Monaco',
        ),
        'MD' => array(
            0 => 'Europe/Chisinau',
        ),
        'ME' => array(
            0 => 'Europe/Podgorica',
        ),
        'MF' => array(
            0 => 'America/Marigot',
        ),
        'MG' => array(
            0 => 'Indian/Antananarivo',
        ),
        'MH' => array(
            0 => 'Pacific/Kwajalein',
            1 => 'Pacific/Majuro',
        ),
        'MK' => array(
            0 => 'Europe/Skopje',
        ),
        'ML' => array(
            0 => 'Africa/Bamako',
        ),
        'MM' => array(
            0 => 'Asia/Yangon',
        ),
        'MN' => array(
            0 => 'Asia/Hovd',
            1 => 'Asia/Ulaanbaatar',
        ),
        'MO' => array(
            0 => 'Asia/Macau',
        ),
        'MP' => array(
            0 => 'Pacific/Saipan',
        ),
        'MQ' => array(
            0 => 'America/Martinique',
        ),
        'MR' => array(
            0 => 'Africa/Nouakchott',
        ),
        'MS' => array(
            0 => 'America/Montserrat',
        ),
        'MT' => array(
            0 => 'Europe/Malta',
        ),
        'MU' => array(
            0 => 'Indian/Mauritius',
        ),
        'MV' => array(
            0 => 'Indian/Maldives',
        ),
        'MW' => array(
            0 => 'Africa/Blantyre',
        ),
        'MX' => array(
            0 => 'America/Bahia_Banderas',
            1 => 'America/Cancun',
            2 => 'America/Chihuahua',
            3 => 'America/Ciudad_Juarez',
            4 => 'America/Hermosillo',
            5 => 'America/Matamoros',
            6 => 'America/Mazatlan',
            7 => 'America/Merida',
            8 => 'America/Mexico_City',
            9 => 'America/Monterrey',
            10 => 'America/Ojinaga',
            11 => 'America/Tijuana',
        ),
        'MY' => array(
            0 => 'Asia/Kuala_Lumpur',
            1 => 'Asia/Kuching',
        ),
        'MZ' => array(
            0 => 'Africa/Maputo',
        ),
        'NA' => array(
            0 => 'Africa/Windhoek',
        ),
        'NC' => array(
            0 => 'Pacific/Noumea',
        ),
        'NE' => array(
            0 => 'Africa/Niamey',
        ),
        'NF' => array(
            0 => 'Pacific/Norfolk',
        ),
        'NG' => array(
            0 => 'Africa/Lagos',
        ),
        'NI' => array(
            0 => 'America/Managua',
        ),
        'NL' => array(
            0 => 'Europe/Amsterdam',
        ),
        'NO' => array(
            0 => 'Europe/Oslo',
        ),
        'NP' => array(
            0 => 'Asia/Kathmandu',
        ),
        'NR' => array(
            0 => 'Pacific/Nauru',
        ),
        'NU' => array(
            0 => 'Pacific/Niue',
        ),
        'NZ' => array(
            0 => 'Pacific/Auckland',
            1 => 'Pacific/Chatham',
        ),
        'OM' => array(
            0 => 'Asia/Muscat',
        ),
        'PA' => array(
            0 => 'America/Panama',
        ),
        'PE' => array(
            0 => 'America/Lima',
        ),
        'PF' => array(
            0 => 'Pacific/Gambier',
            1 => 'Pacific/Marquesas',
            2 => 'Pacific/Tahiti',
        ),
        'PG' => array(
            0 => 'Pacific/Bougainville',
            1 => 'Pacific/Port_Moresby',
        ),
        'PH' => array(
            0 => 'Asia/Manila',
        ),
        'PK' => array(
            0 => 'Asia/Karachi',
        ),
        'PL' => array(
            0 => 'Europe/Warsaw',
        ),
        'PM' => array(
            0 => 'America/Miquelon',
        ),
        'PN' => array(
            0 => 'Pacific/Pitcairn',
        ),
        'PR' => array(
            0 => 'America/Puerto_Rico',
        ),
        'PS' => array(
            0 => 'Asia/Gaza',
            1 => 'Asia/Hebron',
        ),
        'PT' => array(
            0 => 'Atlantic/Azores',
            1 => 'Atlantic/Madeira',
            2 => 'Europe/Lisbon',
        ),
        'PW' => array(
            0 => 'Pacific/Palau',
        ),
        'PY' => array(
            0 => 'America/Asuncion',
        ),
        'QA' => array(
            0 => 'Asia/Qatar',
        ),
        'RE' => array(
            0 => 'Indian/Reunion',
        ),
        'RO' => array(
            0 => 'Europe/Bucharest',
        ),
        'RS' => array(
            0 => 'Europe/Belgrade',
        ),
        'RU' => array(
            0 => 'Asia/Anadyr',
            1 => 'Asia/Barnaul',
            2 => 'Asia/Chita',
            3 => 'Asia/Irkutsk',
            4 => 'Asia/Kamchatka',
            5 => 'Asia/Khandyga',
            6 => 'Asia/Krasnoyarsk',
            7 => 'Asia/Magadan',
            8 => 'Asia/Novokuznetsk',
            9 => 'Asia/Novosibirsk',
            10 => 'Asia/Omsk',
            11 => 'Asia/Sakhalin',
            12 => 'Asia/Srednekolymsk',
            13 => 'Asia/Tomsk',
            14 => 'Asia/Ust-Nera',
            15 => 'Asia/Vladivostok',
            16 => 'Asia/Yakutsk',
            17 => 'Asia/Yekaterinburg',
            18 => 'Europe/Astrakhan',
            19 => 'Europe/Kaliningrad',
            20 => 'Europe/Kirov',
            21 => 'Europe/Moscow',
            22 => 'Europe/Samara',
            23 => 'Europe/Saratov',
            24 => 'Europe/Ulyanovsk',
            25 => 'Europe/Volgograd',
        ),
        'RW' => array(
            0 => 'Africa/Kigali',
        ),
        'SA' => array(
            0 => 'Asia/Riyadh',
        ),
        'SB' => array(
            0 => 'Pacific/Guadalcanal',
        ),
        'SC' => array(
            0 => 'Indian/Mahe',
        ),
        'SD' => array(
            0 => 'Africa/Khartoum',
        ),
        'SE' => array(
            0 => 'Europe/Stockholm',
        ),
        'SG' => array(
            0 => 'Asia/Singapore',
        ),
        'SH' => array(
            0 => 'Atlantic/St_Helena',
        ),
        'SI' => array(
            0 => 'Europe/Ljubljana',
        ),
        'SJ' => array(
            0 => 'Arctic/Longyearbyen',
        ),
        'SK' => array(
            0 => 'Europe/Bratislava',
        ),
        'SL' => array(
            0 => 'Africa/Freetown',
        ),
        'SM' => array(
            0 => 'Europe/San_Marino',
        ),
        'SN' => array(
            0 => 'Africa/Dakar',
        ),
        'SO' => array(
            0 => 'Africa/Mogadishu',
        ),
        'SR' => array(
            0 => 'America/Paramaribo',
        ),
        'SS' => array(
            0 => 'Africa/Juba',
        ),
        'ST' => array(
            0 => 'Africa/Sao_Tome',
        ),
        'SV' => array(
            0 => 'America/El_Salvador',
        ),
        'SX' => array(
            0 => 'America/Lower_Princes',
        ),
        'SY' => array(
            0 => 'Asia/Damascus',
        ),
        'SZ' => array(
            0 => 'Africa/Mbabane',
        ),
        'TC' => array(
            0 => 'America/Grand_Turk',
        ),
        'TD' => array(
            0 => 'Africa/Ndjamena',
        ),
        'TF' => array(
            0 => 'Indian/Kerguelen',
        ),
        'TG' => array(
            0 => 'Africa/Lome',
        ),
        'TH' => array(
            0 => 'Asia/Bangkok',
        ),
        'TJ' => array(
            0 => 'Asia/Dushanbe',
        ),
        'TK' => array(
            0 => 'Pacific/Fakaofo',
        ),
        'TL' => array(
            0 => 'Asia/Dili',
        ),
        'TM' => array(
            0 => 'Asia/Ashgabat',
        ),
        'TN' => array(
            0 => 'Africa/Tunis',
        ),
        'TO' => array(
            0 => 'Pacific/Tongatapu',
        ),
        'TR' => array(
            0 => 'Europe/Istanbul',
        ),
        'TT' => array(
            0 => 'America/Port_of_Spain',
        ),
        'TV' => array(
            0 => 'Pacific/Funafuti',
        ),
        'TW' => array(
            0 => 'Asia/Taipei',
        ),
        'TZ' => array(
            0 => 'Africa/Dar_es_Salaam',
        ),
        'UA' => array(
            0 => 'Europe/Kyiv',
            1 => 'Europe/Simferopol',
        ),
        'UG' => array(
            0 => 'Africa/Kampala',
        ),
        'UM' => array(
            0 => 'Pacific/Midway',
            1 => 'Pacific/Wake',
        ),
        'US' => array(
            0 => 'America/Adak',
            1 => 'America/Anchorage',
            2 => 'America/Boise',
            3 => 'America/Chicago',
            4 => 'America/Denver',
            5 => 'America/Detroit',
            6 => 'America/Indiana/Indianapolis',
            7 => 'America/Indiana/Knox',
            8 => 'America/Indiana/Marengo',
            9 => 'America/Indiana/Petersburg',
            10 => 'America/Indiana/Tell_City',
            11 => 'America/Indiana/Vevay',
            12 => 'America/Indiana/Vincennes',
            13 => 'America/Indiana/Winamac',
            14 => 'America/Juneau',
            15 => 'America/Kentucky/Louisville',
            16 => 'America/Kentucky/Monticello',
            17 => 'America/Los_Angeles',
            18 => 'America/Menominee',
            19 => 'America/Metlakatla',
            20 => 'America/New_York',
            21 => 'America/Nome',
            22 => 'America/North_Dakota/Beulah',
            23 => 'America/North_Dakota/Center',
            24 => 'America/North_Dakota/New_Salem',
            25 => 'America/Phoenix',
            26 => 'America/Sitka',
            27 => 'America/Yakutat',
            28 => 'Pacific/Honolulu',
        ),
        'UY' => array(
            0 => 'America/Montevideo',
        ),
        'UZ' => array(
            0 => 'Asia/Samarkand',
            1 => 'Asia/Tashkent',
        ),
        'VA' => array(
            0 => 'Europe/Vatican',
        ),
        'VC' => array(
            0 => 'America/St_Vincent',
        ),
        'VE' => array(
            0 => 'America/Caracas',
        ),
        'VG' => array(
            0 => 'America/Tortola',
        ),
        'VI' => array(
            0 => 'America/St_Thomas',
        ),
        'VN' => array(
            0 => 'Asia/Ho_Chi_Minh',
        ),
        'VU' => array(
            0 => 'Pacific/Efate',
        ),
        'WF' => array(
            0 => 'Pacific/Wallis',
        ),
        'WS' => array(
            0 => 'Pacific/Apia',
        ),
        'YE' => array(
            0 => 'Asia/Aden',
        ),
        'YT' => array(
            0 => 'Indian/Mayotte',
        ),
        'ZA' => array(
            0 => 'Africa/Johannesburg',
        ),
        'ZM' => array(
            0 => 'Africa/Lusaka',
        ),
        'ZW' => array(
            0 => 'Africa/Harare',
        ),
    );

    /** @return list<string>|null */
    public static function forRegion(?string $region): ?array
    {
        self::assertIntegrity();

        return $region === null ? null : self::REGIONS[$region] ?? [];
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
            'regions' => self::REGIONS,
        ], JSON_THROW_ON_ERROR));
        if (!self::supportsFormat(self::FORMAT) || $actual !== self::PAYLOAD_SHA256) {
            throw new \UnexpectedValueException('The bundled time-zone data is corrupt or incompatible.');
        }
        $verified = true;
    }

    private static function supportsFormat(int $format): bool
    {
        return $format === 1;
    }
}
