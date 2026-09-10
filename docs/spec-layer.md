# Spec layer

`Midnight\Intl\Spec\Locale` is the public layer for ECMAScript-compatible behavior. Unlike the final porcelain class, it is extensible. Its constructor accepts a string, an initialized spec-layer `Locale`, or a PHP `Stringable` object. Options may be an associative array or plain object.

The spec constructor distinguishes omitted options from explicit `null`. Explicit `null` throws `Midnight\Intl\Exception\TypeError`. Invalid locale syntax and invalid option values throw `Midnight\Intl\Exception\RangeError`.

The constructor implements the complete Unicode locale-identifier grammar and all eleven constructor options. `baseName`, `language`, `script`, `region`, `variants`, `calendar`, `caseFirst`, `collation`, `firstDayOfWeek`, `hourCycle`, `numberingSystem`, `numeric`, and `toString()` expose the canonical result. Locale-information and likely-subtag methods remain unfinished, so this package does not claim ECMA-402 conformance.

Use `Midnight\Intl\Locale::fromSpec()` and `Locale::toSpec()` to cross layers explicitly.
