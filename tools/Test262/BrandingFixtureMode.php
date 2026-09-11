<?php

declare(strict_types=1);

namespace Midnight\Intl\Tools\Test262;

enum BrandingFixtureMode
{
    case Property;
    case Method;
    case IndividualMethodIncludingConstructor;

    public function isProperty(): bool
    {
        return $this === self::Property;
    }

    public function includesConstructor(): bool
    {
        return $this === self::IndividualMethodIncludingConstructor;
    }

    public function hasIndividualAssertions(): bool
    {
        return $this === self::IndividualMethodIncludingConstructor;
    }
}
