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
    ) {
        if ($this->spec !== null) {
            throw new TypeError('Locale is already initialized.');
        }

        $options = array_filter([
            'language' => $language,
            'script' => $script,
            'region' => $region,
        ], static fn (?string $value): bool => $value !== null);

        $this->spec = $options === []
            ? new SpecLocale($tag)
            : new SpecLocale($tag, $options);
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
