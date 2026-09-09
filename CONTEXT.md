# Intl.Locale for PHP

This context defines the language used to describe locale semantics and the library's compatibility promises.

## Language

**Spec layer**:
The PHP-facing expression of ECMAScript `Intl.Locale` semantics wherever PHP can expose an equivalent observable result.
_Avoid_: Compatibility layer, JavaScript layer

**Porcelain layer**:
An idiomatic PHP interface built on the spec layer without redefining its locale semantics.
_Avoid_: Convenience layer, wrapper
