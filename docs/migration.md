# Migration

## From PHP's global `Locale`

This package does not wrap or modify PHP's static `\Locale` class. Replace static calls with immutable porcelain values and read their properties or call `toString()`.

```php
use Midnight\Intl\Locale;

$locale = new Locale('de-AT');
$canonical = $locale->toString();
```

Results come from the package's release data snapshot, not the host's ICU version or global default locale. Do not carry over assumptions about ICU fallback, global error state, or underscore-form identifiers.

## From direct ICU use

- Remove checks that make supported behavior depend on the host ICU version.
- Pass a Unicode locale identifier explicitly instead of relying on a process default.
- Handle the package's `TypeError` and `RangeError` taxonomy at the spec boundary.
- Pin the package version when data-dependent results must remain unchanged.
