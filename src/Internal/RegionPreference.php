<?php

declare(strict_types=1);

namespace Midnight\Intl\Internal;

final readonly class RegionPreference
{
    private function __construct(
        public string $region,
        public ?string $regionOverride,
    ) {}

    public static function fromLocale(LocaleIdentifier $locale): self
    {
        $region = $locale->region;
        $region ??= self::canonicalUnicodeSubdivision($locale, 'sd');
        $region ??= $locale->maximize()->region;
        $region ??= '001';

        return new self($region, self::canonicalUnicodeSubdivision($locale, 'rg'));
    }

    /** @return list<string> */
    public function preferredRegions(): array
    {
        return $this->regionOverride === null ? [$this->region] : [$this->regionOverride, $this->region];
    }

    private static function canonicalUnicodeSubdivision(LocaleIdentifier $locale, string $key): ?string
    {
        $subdivision = $locale->keyword($key);
        $matches = [];
        if (
            $subdivision === null
            || preg_match('/^(?<region>[a-z]{2}|[0-9]{3})[a-z0-9]{1,6}$/D', $subdivision, $matches) !== 1
        ) {
            return null;
        }

        return LocaleIdentifier::parse('und-' . $matches['region'])->region;
    }
}
