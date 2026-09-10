# Spec layer

`Midnight\Intl\Spec\Locale` is the public tier for ECMAScript-compatible behavior. Unlike the final porcelain class, it is extensible. Its constructor accepts a string, an initialized spec-layer `Locale`, or a PHP `Stringable` object. Options may be an associative array or plain object.

The spec constructor distinguishes omitted options from explicit `null`. Explicit `null` throws `Midnight\Intl\Exception\TypeError`. Invalid locale syntax and invalid option values throw `Midnight\Intl\Exception\RangeError`.

This initial slice implements `baseName`, `language`, `script`, `region`, and `toString()`. The remaining `Intl.Locale` properties and methods are unfinished, so this package does not claim ECMA-402 conformance.

Use `Midnight\Intl\Locale::fromSpec()` and `Locale::toSpec()` to cross tiers explicitly.
