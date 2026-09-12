<?php

declare(strict_types=1);

namespace Midnight\Intl;

use Midnight\Intl\Exception\TypeError;
use Midnight\Intl\Spec\Locale as SpecLocale;

/**
 * @property-read string $baseName
 * @property-read string $language
 * @property-read string|null $script
 * @property-read string|null $region
 * @property-read string|null $variants
 * @property-read string|null $calendar
 * @property-read CaseFirst|string|null $caseFirst
 * @property-read string|null $collation
 * @property-read string|null $firstDayOfWeek
 * @property-read HourCycle|string|null $hourCycle
 * @property-read string|null $numberingSystem
 * @property-read bool $numeric
 * @psalm-api
 */
final class Locale implements \Stringable, \JsonSerializable
{
    private ?SpecLocale $spec = null;

    /**
     * @param HourCycle|value-of<HourCycle>|null $hourCycle
     * @param CaseFirst|value-of<CaseFirst>|null   $caseFirst
     */
    public function __construct(
        string $tag,
        ?string $language = null,
        ?string $script = null,
        ?string $region = null,
        ?string $variants = null,
        ?string $calendar = null,
        ?string $collation = null,
        ?string $firstDayOfWeek = null,
        HourCycle|string|null $hourCycle = null,
        CaseFirst|string|null $caseFirst = null,
        ?bool $numeric = null,
        ?string $numberingSystem = null,
    ) {
        if ($this->spec !== null) {
            throw new TypeError('Locale is already initialized.');
        }

        $options = array_filter(
            [
                'language' => $language,
                'script' => $script,
                'region' => $region,
                'variants' => $variants,
                'calendar' => $calendar,
                'collation' => $collation,
                'firstDayOfWeek' => $firstDayOfWeek,
                'hourCycle' => $hourCycle instanceof HourCycle ? $hourCycle->value : $hourCycle,
                'caseFirst' => $caseFirst instanceof CaseFirst ? $caseFirst->value : $caseFirst,
                'numeric' => $numeric,
                'numberingSystem' => $numberingSystem,
            ],
            static fn(string|bool|null $value): bool => $value !== null,
        );

        $this->spec = $options === [] ? new SpecLocale($tag) : new SpecLocale($tag, $options);
    }

    public static function fromSpec(SpecLocale $locale): self
    {
        return new self($locale->toString());
    }

    public function toSpec(): SpecLocale
    {
        return $this->spec ?? throw new TypeError('Locale is not initialized.');
    }

    public function __get(string $name): mixed
    {
        /** @var string|bool|null $value */
        $value = $this->toSpec()->__get($name);

        return match ($name) {
            'hourCycle' => is_string($value) ? HourCycle::tryFrom($value) ?? $value : null,
            'caseFirst' => is_string($value) ? CaseFirst::tryFrom($value) ?? $value : null,
            default => $value,
        };
    }

    public function __set(string $name, mixed $_value): void
    {
        throw new TypeError(sprintf('Locale property "%s" is read-only.', $name));
    }

    public function __isset(string $name): bool
    {
        return $this->toSpec()->__isset($name);
    }

    public function toString(): string
    {
        return $this->toSpec()->toString();
    }

    /** @return list<string>|null */
    public function getTimeZones(): ?array
    {
        return $this->toSpec()->getTimeZones();
    }

    /** @return non-empty-list<string> */
    public function getCalendars(): array
    {
        return $this->toSpec()->getCalendars();
    }

    /** @return non-empty-list<HourCycle> */
    public function getHourCycles(): array
    {
        return array_map(static fn(string $hourCycle): HourCycle => HourCycle::from(
            $hourCycle,
        ), $this->toSpec()->getHourCycles());
    }

    /** @return list<string> */
    public function getNumberingSystems(): array
    {
        return $this->toSpec()->getNumberingSystems();
    }

    public function maximize(): self
    {
        return self::fromSpec($this->toSpec()->maximize());
    }

    public function minimize(): self
    {
        return self::fromSpec($this->toSpec()->minimize());
    }

    public function getTextInfo(): TextInfo
    {
        $direction = $this->toSpec()->getTextInfo()['direction'];

        return new TextInfo($direction === null ? null : TextDirection::from($direction));
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->toString();
    }

    #[\Override]
    public function jsonSerialize(): string
    {
        return $this->toString();
    }
}
