# Spec layer

`Midnight\Intl\Spec\Locale` is the public layer for ECMAScript-compatible behavior. Unlike the final porcelain class, it is extensible. Its constructor accepts a string, an initialized spec-layer `Locale`, or a PHP `Stringable` object. Options may be an associative array or plain object. Internal Test262-only values reproduce otherwise unrepresentable ECMAScript `undefined`, Symbol, conversion-hook, observer, and proxy-like behavior; they are not supported public value types.

The spec constructor distinguishes omitted options and internal `undefined` from explicit `null`. Omitted or undefined options act as an empty object, other non-null primitive options follow ECMAScript `ToObject`, and explicit `null` throws `Midnight\Intl\Exception\TypeError`. String-valued options use ECMAScript `ToString`; `numeric` uses ECMAScript `ToBoolean`, including treating the string `"0"` as true. Invalid locale syntax and invalid option values throw `Midnight\Intl\Exception\RangeError`.

Tag conversion finishes before options coercion and locale validation. The eleven options are then read lazily in specification order, one property `Get` followed immediately by that option's conversion and validation. Exceptions raised by consumer conversion hooks or property access propagate unchanged.

The constructor implements the complete Unicode locale-identifier grammar and all eleven constructor options. `baseName`, `language`, `script`, `region`, `variants`, `calendar`, `caseFirst`, `collation`, `firstDayOfWeek`, `hourCycle`, `numberingSystem`, `numeric`, and `toString()` expose the canonical result.

`maximize()` and `minimize()` implement Add and Remove Likely Subtags with the pinned CLDR projection. Both preserve variants and every extension, return a fresh base spec-layer `Locale`, and fall back to the original canonical identifier when no likely-subtag mapping applies.

`getTextInfo()` returns a fresh associative record with exactly one `direction` key. The value is `ltr`, `rtl`, or `null` when direction is unknown. An explicit script is used directly; otherwise the script is inferred with Add Likely Subtags before the pinned CLDR script metadata is queried.

`getCalendars()` and `getHourCycles()` return non-empty, preference-ordered string lists. Explicit `ca` and `hc` keywords return singleton lists. Otherwise, both methods apply the shared ECMA-402 region preference order, language-region lookup, canonicalization, deduplication, and deterministic `gregory` or `h23` fallback against the pinned CLDR projection.

`getNumberingSystems()` returns the explicit `nu` Unicode keyword when present. Otherwise it uses prefix matching against the pinned NumberFormat locale availability projection and returns that locale's inherited CLDR default, with the specified `latn` fallback. The result is always a fresh one-element list.

Other locale-information methods remain unfinished, so this package does not claim ECMA-402 conformance.

`getTimeZones()` returns a list of canonical primary IANA identifiers for the locale's explicit region, or `null` when the language identifier has no region. The result uses the pinned release data snapshot and does not consult host ICU or infer a region from likely subtags, `rg`, or `sd`.

Use `Midnight\Intl\Locale::fromSpec()` and `Locale::toSpec()` to cross layers explicitly.
