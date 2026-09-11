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
 * @property-read string|null $caseFirst
 * @property-read string|null $collation
 * @property-read string|null $firstDayOfWeek
 * @property-read string|null $hourCycle
 * @property-read string|null $numberingSystem
 * @property-read bool $numeric
 * @psalm-api
 */
final class Locale implements \Stringable, \JsonSerializable
{
    private ?SpecLocale $spec = null;

    public function __construct(
        string $tag,
        ?string $language = null,
        ?string $script = null,
        ?string $region = null,
        ?string $variants = null,
        ?string $calendar = null,
        ?string $collation = null,
        ?string $firstDayOfWeek = null,
        ?string $hourCycle = null,
        ?string $caseFirst = null,
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
                'hourCycle' => $hourCycle,
                'caseFirst' => $caseFirst,
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
        return $this->toSpec()->__get($name);
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
