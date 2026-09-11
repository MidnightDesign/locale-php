# Spec layer

`Midnight\Intl\Spec\Locale` is the public layer for ECMAScript-compatible behavior. Unlike the final porcelain class, it is extensible. Its constructor accepts a string, an initialized spec-layer `Locale`, or a PHP `Stringable` object. Options may be an associative array or plain object.

The spec constructor distinguishes omitted options from explicit `null`. Explicit `null` throws `Midnight\Intl\Exception\TypeError`. Invalid locale syntax and invalid option values throw `Midnight\Intl\Exception\RangeError`.

The constructor implements the complete Unicode locale-identifier grammar and all eleven constructor options. `baseName`, `language`, `script`, `region`, `variants`, `calendar`, `caseFirst`, `collation`, `firstDayOfWeek`, `hourCycle`, `numberingSystem`, `numeric`, and `toString()` expose the canonical result.

`maximize()` and `minimize()` implement Add and Remove Likely Subtags with the pinned CLDR projection. Both preserve variants and every extension, return a fresh base spec-layer `Locale`, and fall back to the original canonical identifier when no likely-subtag mapping applies.

`getTextInfo()` returns a fresh associative record with exactly one `direction` key. The value is `ltr`, `rtl`, or `null` when direction is unknown. An explicit script is used directly; otherwise the script is inferred with Add Likely Subtags before the pinned CLDR script metadata is queried.

`getCalendars()` and `getHourCycles()` return non-empty, preference-ordered string lists. Explicit `ca` and `hc` keywords return singleton lists. Otherwise, both methods apply the shared ECMA-402 region preference order, language-region lookup, canonicalization, deduplication, and deterministic `gregory` or `h23` fallback against the pinned CLDR projection.

Other locale-information methods remain unfinished, so this package does not claim ECMA-402 conformance.

`getTimeZones()` returns a list of canonical primary IANA identifiers for the locale's explicit region, or `null` when the language identifier has no region. The result uses the pinned release data snapshot and does not consult host ICU or infer a region from likely subtags, `rg`, or `sd`.

Use `Midnight\Intl\Locale::fromSpec()` and `Locale::toSpec()` to cross layers explicitly.
