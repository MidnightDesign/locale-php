<?php

declare(strict_types=1);

namespace Midnight\Intl\Internal\Data;

enum ScriptDirections
{
    public const FORMAT = 1;

    /** @var string */
    public const CLDR_REVISION = '11299982335beb974c1c63c45265184e759c0f41';

    /** @var string */
    public const CLDR_CORE_SHA512 = 'de8660f5371e0fcfd03a42e3b4fc4c686ec6cd602b402f1e3d227844005a54eb7952873894443523837d5828c42874a1a267a19f91ded207a2d166144791fa62';

    /** @var string */
    public const SOURCE_SHA256 = 'e71dc41671b2a3d15807e4dcda0bd38735000bc947fcf8bfbe6f7754d1e27215';

    private const PAYLOAD_SHA256 = 'c970b92e8fde085296d251c929633924f8147ffa0b91a3b0ac6f5bd40d9fe93c';

    /** @var array<string, 'ltr'|'rtl'|null> */
    public const MAP = array(
        'Adlm' => 'rtl',
        'Aghb' => 'ltr',
        'Ahom' => 'ltr',
        'Arab' => 'rtl',
        'Armi' => 'rtl',
        'Armn' => 'ltr',
        'Avst' => 'rtl',
        'Bali' => 'ltr',
        'Bamu' => 'ltr',
        'Bass' => 'ltr',
        'Batk' => 'ltr',
        'Beng' => 'ltr',
        'Berf' => 'ltr',
        'Bhks' => 'ltr',
        'Bopo' => 'ltr',
        'Brah' => 'ltr',
        'Brai' => null,
        'Bugi' => 'ltr',
        'Buhd' => 'ltr',
        'Cakm' => 'ltr',
        'Cans' => 'ltr',
        'Cari' => 'ltr',
        'Cham' => 'ltr',
        'Cher' => 'ltr',
        'Chrs' => 'rtl',
        'Copt' => 'ltr',
        'Cpmn' => 'ltr',
        'Cprt' => 'rtl',
        'Cyrl' => 'ltr',
        'Deva' => 'ltr',
        'Diak' => 'ltr',
        'Dogr' => 'ltr',
        'Dsrt' => 'ltr',
        'Dupl' => 'ltr',
        'Egyp' => 'ltr',
        'Elba' => 'ltr',
        'Elym' => 'rtl',
        'Ethi' => 'ltr',
        'Gara' => 'rtl',
        'Geor' => 'ltr',
        'Glag' => 'ltr',
        'Gong' => 'ltr',
        'Gonm' => 'ltr',
        'Goth' => 'ltr',
        'Gran' => 'ltr',
        'Grek' => 'ltr',
        'Gujr' => 'ltr',
        'Gukh' => 'ltr',
        'Guru' => 'ltr',
        'Hanb' => 'ltr',
        'Hang' => 'ltr',
        'Hani' => 'ltr',
        'Hano' => 'ltr',
        'Hans' => 'ltr',
        'Hant' => 'ltr',
        'Hatr' => 'rtl',
        'Hebr' => 'rtl',
        'Hira' => 'ltr',
        'Hluw' => 'ltr',
        'Hmng' => 'ltr',
        'Hmnp' => 'ltr',
        'Hung' => 'rtl',
        'Ital' => 'ltr',
        'Jamo' => 'ltr',
        'Java' => 'ltr',
        'Jpan' => 'ltr',
        'Kali' => 'ltr',
        'Kana' => 'ltr',
        'Kawi' => 'ltr',
        'Khar' => 'rtl',
        'Khmr' => 'ltr',
        'Khoj' => 'ltr',
        'Kits' => 'ltr',
        'Knda' => 'ltr',
        'Kore' => 'ltr',
        'Krai' => 'ltr',
        'Kthi' => 'ltr',
        'Lana' => 'ltr',
        'Laoo' => 'ltr',
        'Latn' => 'ltr',
        'Lepc' => 'ltr',
        'Limb' => 'ltr',
        'Lina' => 'ltr',
        'Linb' => 'ltr',
        'Lisu' => 'ltr',
        'Lyci' => 'ltr',
        'Lydi' => 'rtl',
        'Mahj' => 'ltr',
        'Maka' => 'ltr',
        'Mand' => 'rtl',
        'Mani' => 'rtl',
        'Marc' => 'ltr',
        'Medf' => 'ltr',
        'Mend' => 'rtl',
        'Merc' => 'rtl',
        'Mero' => 'rtl',
        'Mlym' => 'ltr',
        'Modi' => 'ltr',
        'Mong' => 'ltr',
        'Mroo' => 'ltr',
        'Mtei' => 'ltr',
        'Mult' => 'ltr',
        'Mymr' => 'ltr',
        'Nagm' => 'ltr',
        'Nand' => 'ltr',
        'Narb' => 'rtl',
        'Nbat' => 'rtl',
        'Newa' => 'ltr',
        'Nkoo' => 'rtl',
        'Nshu' => 'ltr',
        'Ogam' => 'ltr',
        'Olck' => 'ltr',
        'Onao' => 'ltr',
        'Orkh' => 'rtl',
        'Orya' => 'ltr',
        'Osge' => 'ltr',
        'Osma' => 'ltr',
        'Ougr' => 'rtl',
        'Palm' => 'rtl',
        'Pauc' => 'ltr',
        'Perm' => 'ltr',
        'Phag' => 'ltr',
        'Phli' => 'rtl',
        'Phlp' => 'rtl',
        'Phnx' => 'rtl',
        'Plrd' => 'ltr',
        'Prti' => 'rtl',
        'Rjng' => 'ltr',
        'Rohg' => 'rtl',
        'Runr' => 'ltr',
        'Samr' => 'rtl',
        'Sarb' => 'rtl',
        'Saur' => 'ltr',
        'Sgnw' => 'ltr',
        'Shaw' => 'ltr',
        'Shrd' => 'ltr',
        'Sidd' => 'ltr',
        'Sidt' => 'rtl',
        'Sind' => 'ltr',
        'Sinh' => 'ltr',
        'Sogd' => 'rtl',
        'Sogo' => 'rtl',
        'Sora' => 'ltr',
        'Soyo' => 'ltr',
        'Sund' => 'ltr',
        'Sunu' => 'ltr',
        'Sylo' => 'ltr',
        'Syrc' => 'rtl',
        'Tagb' => 'ltr',
        'Takr' => 'ltr',
        'Tale' => 'ltr',
        'Talu' => 'ltr',
        'Taml' => 'ltr',
        'Tang' => 'ltr',
        'Tavt' => 'ltr',
        'Tayo' => 'ltr',
        'Telu' => 'ltr',
        'Tfng' => 'ltr',
        'Tglg' => 'ltr',
        'Thaa' => 'rtl',
        'Thai' => 'ltr',
        'Tibt' => 'ltr',
        'Tirh' => 'ltr',
        'Tnsa' => 'ltr',
        'Todr' => 'ltr',
        'Tols' => 'ltr',
        'Toto' => 'ltr',
        'Tutg' => 'ltr',
        'Ugar' => 'ltr',
        'Vaii' => 'ltr',
        'Vith' => 'ltr',
        'Wara' => 'ltr',
        'Wcho' => 'ltr',
        'Xpeo' => 'ltr',
        'Xsux' => 'ltr',
        'Yezi' => 'rtl',
        'Yiii' => 'ltr',
        'Zanb' => 'ltr',
        'Zinh' => null,
        'Zyyy' => null,
        'Zzzz' => null,
    );

    public static function assertIntegrity(): void
    {
        /** @var bool|null $verified */
        static $verified = null;
        if ($verified === true) {
            return;
        }

        $actual = hash('sha256', json_encode([
            'format' => self::FORMAT,
            'scriptDirection' => self::MAP,
        ], JSON_THROW_ON_ERROR));
        if (!self::supportsFormat(self::FORMAT) || $actual !== self::PAYLOAD_SHA256) {
            throw new \UnexpectedValueException('The bundled script-direction data is corrupt or incompatible.');
        }
        $verified = true;
    }

    private static function supportsFormat(int $format): bool
    {
        return $format === 1;
    }
}
