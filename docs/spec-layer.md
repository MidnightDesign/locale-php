# Spec layer

`Midnight\Intl\Spec\Locale` is the public layer for ECMAScript-compatible behavior. Unlike the final porcelain class, it is extensible. Its constructor accepts a string, an initialized spec-layer `Locale`, or a PHP `Stringable` object. Options may be an associative array or plain object.

The spec constructor distinguishes omitted options from explicit `null`. Explicit `null` throws `Midnight\Intl\Exception\TypeError`. Invalid locale syntax and invalid option values throw `Midnight\Intl\Exception\RangeError`.

The constructor implements the complete Unicode locale-identifier grammar and all eleven constructor options. `baseName`, `language`, `script`, `region`, `variants`, `calendar`, `caseFirst`, `collation`, `firstDayOfWeek`, `hourCycle`, `numberingSystem`, `numeric`, and `toString()` expose the canonical result.

`getTimeZones()` returns a list of canonical primary IANA identifiers for the locale's explicit region, or `null` when the language identifier has no region. The result uses the pinned release data snapshot and does not consult host ICU or infer a region from likely subtags, `rg`, or `sd`. Other locale-information and likely-subtag methods remain unfinished, so this package does not claim ECMA-402 conformance.

Use `Midnight\Intl\Locale::fromSpec()` and `Locale::toSpec()` to cross layers explicitly.
