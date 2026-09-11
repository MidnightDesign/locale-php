<?php

declare(strict_types=1);

namespace Midnight\Intl\Tests\Test262\Harness;

use Midnight\Intl\Spec\Locale;

final class LocaleObjectModel
{
    /** @return list<bool> */
    public static function subclassing(): array
    {
        $locale = new class('de') extends Locale {
            public bool $isCustom = true;
        };
        $parent = (new \ReflectionClass($locale))->getParentClass();
        $baseToString = new \ReflectionMethod(Locale::class, 'toString');

        return [
            $locale->isCustom,
            $locale->toString() === 'de',
            $baseToString->invoke($locale) === 'de',
            $parent !== false && $parent->getName() === Locale::class,
        ];
    }

    public static function isExtensible(): bool
    {
        $locale = new Locale('en');
        $locale->__set('custom', true);

        return $locale->__get('custom') === true;
    }

    public static function hasBasePrototype(): bool
    {
        return (new Locale('en'))::class === Locale::class;
    }
}
