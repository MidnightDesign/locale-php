# Migration

## From PHP's global `Locale`

This package does not wrap, inherit from, or modify PHP's static `\Locale` class. Replace static calls with explicit immutable porcelain values. The closest mappings for common operations are:

| Global `\Locale` use | `Midnight\Intl\Locale` |
| --- | --- |
| `\Locale::canonicalize($tag)` | `(new Locale($tag))->toString()` |
| `\Locale::getPrimaryLanguage($tag)` | `(new Locale($tag))->language` |
| `\Locale::getScript($tag)` | `(new Locale($tag))->script` |
| `\Locale::getRegion($tag)` | `(new Locale($tag))->region` |
| `\Locale::parseLocale($tag)` | Read the immutable locale properties |
| `\Locale::composeLocale($parts)` | Pass a tag and named constructor options |
| `\Locale::getDefault()` / `setDefault()` | No equivalent; pass a locale explicitly |
| display names, lookup, and HTTP negotiation | No equivalent in this package |

```php
use Midnight\Intl\Locale;

$locale = new Locale('de-AT');
$canonical = $locale->toString();
$timeZones = $locale->getTimeZones();

assert($canonical === 'de-AT');
assert($timeZones === ['Europe/Vienna']);
```

Use well-formed Unicode locale identifiers such as `de-AT`, not ICU underscore forms such as `de_AT`. Constructor options replace identifier components or Unicode keywords; they are not an array compatible with `composeLocale()`.

Results come from the package's release data snapshot, not the host's ICU version or global default locale. The API never reads or changes the process default locale. Invalid identifiers and option values throw exceptions instead of setting ICU's global error state or returning an ICU sentinel. At the porcelain boundary, PHP signature mismatches are native `\TypeError`; library validation failures use `Midnight\Intl\Exception\RangeError` or `Midnight\Intl\Exception\TypeError` as detailed in the [API reference](api-reference.md).

## From direct ICU use

- Remove checks that make supported behavior depend on the host ICU version.
- Pass a Unicode locale identifier explicitly instead of relying on a process default.
- Remove fallback branches based on ICU's global error code or warning behavior; handle exceptions at the call site.
- Compare expected values with the package's pinned release data snapshot, not the deployed ICU build.
- Do not assume that installing or upgrading `ext-intl` changes package results.
- Pin the package version when data-dependent results must remain unchanged.

## Persistence and upgrades

Persist the canonical string returned by `toString()` and reconstruct a `Locale` from it. Native PHP `serialize()` output is not a stable storage format. Persisting a canonical string keeps the value portable across processes and makes the chosen identifier explicit.

The package follows Semantic Versioning independently of its ECMA-402/Test262 baseline and release data snapshot. Both the porcelain and spec tiers are supported public API: after 1.0, an incompatible change to either tier requires a major release. Compatible conformance corrections and release-data updates may ship in a patch release, and compatible new `Intl.Locale` capabilities may ship in a minor release. During `0.x`, minor releases may contain incompatible API changes; patch releases remain compatible within their minor line.

Pin an exact package version when data-dependent output must remain stable. Otherwise, review the [release data fingerprint and conformance evidence](conformance.md) during upgrades. A changed CLDR, Unicode, IANA, or tzdb value is not by itself a breaking API change.

There is no change-specific migration note yet because the package has not published a breaking public release. When a real breaking API or observable-behavior change occurs, its release notes must identify the affected contract and show the required migration, using the porcelain layer by default.
