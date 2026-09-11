<?php

declare(strict_types=1);

namespace Midnight\Intl\Internal\Data;

enum NumberingSystems
{
    public const FORMAT = 1;

    /** @var string */
    public const CLDR_REVISION = '11299982335beb974c1c63c45265184e759c0f41';

    /** @var string */
    public const CLDR_CORE_SHA512 = 'de8660f5371e0fcfd03a42e3b4fc4c686ec6cd602b402f1e3d227844005a54eb7952873894443523837d5828c42874a1a267a19f91ded207a2d166144791fa62';

    /** @var string */
    public const SOURCE_SHA256 = '73bda7d0396a805c5e38b248d8fdf6fc6a82c83708525fff5aa2de8e2be781d2';

    private const PAYLOAD_SHA256 = 'bc0f344896ff87df678b7523470726e9637771197f2377609647fbe1ef292e9a';

    /** @var array<string, string> */
    public const DEFAULTS = array(
        'ar-BH' => 'arab',
        'ar-DJ' => 'arab',
        'ar-EG' => 'arab',
        'ar-ER' => 'arab',
        'ar-IL' => 'arab',
        'ar-IQ' => 'arab',
        'ar-JO' => 'arab',
        'ar-KM' => 'arab',
        'ar-KW' => 'arab',
        'ar-LB' => 'arab',
        'ar-MR' => 'arab',
        'ar-OM' => 'arab',
        'ar-PS' => 'arab',
        'ar-QA' => 'arab',
        'ar-SA' => 'arab',
        'ar-SD' => 'arab',
        'ar-SO' => 'arab',
        'ar-SS' => 'arab',
        'ar-SY' => 'arab',
        'ar-TD' => 'arab',
        'ar-YE' => 'arab',
        'as' => 'beng',
        'bgc' => 'deva',
        'bho' => 'deva',
        'bn' => 'beng',
        'ccp' => 'cakm',
        'ckb' => 'arab',
        'dz' => 'tibt',
        'fa' => 'arabext',
        'ff-Adlm' => 'adlm',
        'hnj' => 'hmnp',
        'ks' => 'arabext',
        'ks-Deva' => 'latn',
        'lrc' => 'arabext',
        'mni' => 'beng',
        'mni-Mtei' => 'mtei',
        'mr' => 'deva',
        'mww' => 'hmnp',
        'my' => 'mymr',
        'ne' => 'deva',
        'nqo' => 'nkoo',
        'pa-Arab' => 'arabext',
        'ps' => 'arabext',
        'raj' => 'deva',
        'root' => 'latn',
        'sa' => 'deva',
        'sat' => 'olck',
        'sat-Deva' => 'deva',
        'sd' => 'arab',
        'sd-Deva' => 'latn',
        'sdh' => 'arab',
        'ur-IN' => 'arabext',
        'uz-Arab' => 'arabext',
    );

    /** @var array<string, string> */
    public const ALIASES = array(
        'az-AZ' => 'az-Latn-AZ',
        'az-IQ' => 'az-Arab-IQ',
        'az-IR' => 'az-Arab-IR',
        'bal-PK' => 'bal-Arab-PK',
        'bs-BA' => 'bs-Latn-BA',
        'ff-BF' => 'ff-Latn-BF',
        'ff-CM' => 'ff-Latn-CM',
        'ff-GH' => 'ff-Latn-GH',
        'ff-GM' => 'ff-Latn-GM',
        'ff-GN' => 'ff-Latn-GN',
        'ff-GW' => 'ff-Latn-GW',
        'ff-LR' => 'ff-Latn-LR',
        'ff-MR' => 'ff-Latn-MR',
        'ff-NE' => 'ff-Latn-NE',
        'ff-NG' => 'ff-Latn-NG',
        'ff-SL' => 'ff-Latn-SL',
        'ff-SN' => 'ff-Latn-SN',
        'ha-SD' => 'ha-Arab-SD',
        'hnj-US' => 'hnj-Hmnp-US',
        'kaa-UZ' => 'kaa-Cyrl-UZ',
        'kk-CN' => 'kk-Arab-CN',
        'kok-IN' => 'kok-Deva-IN',
        'ks-IN' => 'ks-Arab-IN',
        'ku-IQ' => 'ku-Arab-IQ',
        'ku-IR' => 'ku-Arab-IR',
        'ku-SY' => 'ku-Latn-SY',
        'kxv-IN' => 'kxv-Latn-IN',
        'mn-CN' => 'mn-Mong-CN',
        'mni-IN' => 'mni-Beng-IN',
        'mww-US' => 'mww-Hmnp-US',
        'pa-IN' => 'pa-Guru-IN',
        'pa-PK' => 'pa-Arab-PK',
        'pi-GB' => 'pi-Latn-GB',
        'rhg-BD' => 'rhg-Rohg-BD',
        'rhg-MM' => 'rhg-Rohg-MM',
        'sat-IN' => 'sat-Olck-IN',
        'sd-IN' => 'sd-Deva-IN',
        'sd-PK' => 'sd-Arab-PK',
        'shi-MA' => 'shi-Tfng-MA',
        'sr-BA' => 'sr-Cyrl-BA',
        'sr-ME' => 'sr-Latn-ME',
        'sr-RS' => 'sr-Cyrl-RS',
        'sr-XK' => 'sr-Cyrl-XK',
        'su-ID' => 'su-Latn-ID',
        'suz-NP' => 'suz-Deva-NP',
        'uz-AF' => 'uz-Arab-AF',
        'uz-UZ' => 'uz-Latn-UZ',
        'vai-LR' => 'vai-Vaii-LR',
        'yue-CN' => 'yue-Hans-CN',
        'yue-HK' => 'yue-Hant-HK',
        'yue-MO' => 'yue-Hant-MO',
        'zh-CN' => 'zh-Hans-CN',
        'zh-HK' => 'zh-Hant-HK',
        'zh-MO' => 'zh-Hant-MO',
        'zh-MY' => 'zh-Hans-MY',
        'zh-SG' => 'zh-Hans-SG',
        'zh-TW' => 'zh-Hant-TW',
    );

    public static function defaultFor(string $locale): string
    {
        self::assertIntegrity();

        while ($locale !== '') {
            if (isset(self::DEFAULTS[$locale])) {
                return self::DEFAULTS[$locale];
            }
            if (isset(self::ALIASES[$locale])) {
                $locale = self::ALIASES[$locale];
                continue;
            }
            $position = strrpos($locale, '-');
            $locale = $position === false ? '' : substr($locale, 0, $position);
        }

        return 'latn';
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
            'defaults' => self::DEFAULTS,
            'aliases' => self::ALIASES,
        ], JSON_THROW_ON_ERROR));
        if (!self::supportsFormat(self::FORMAT) || $actual !== self::PAYLOAD_SHA256) {
            throw new \UnexpectedValueException('The bundled numbering-system data is corrupt or incompatible.');
        }
        $verified = true;
    }

    private static function supportsFormat(int $format): bool
    {
        return $format === 1;
    }
}
