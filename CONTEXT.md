# Intl.Locale for PHP

This context defines the language used to describe locale semantics and the library's compatibility promises.

## Language

**Spec layer**:
The PHP-facing expression of ECMAScript `Intl.Locale` semantics wherever PHP can expose an equivalent observable result.
_Avoid_: Compatibility layer, JavaScript layer

**Porcelain layer**:
A PHP interface that mirrors the spec layer's vocabulary and capabilities while expressing them with stronger PHP-native types and representations. Names diverge from the spec layer only when there is a compelling, documented reason.
_Avoid_: Convenience layer, simplified API, wrapper

**Conformance baseline**:
The exact ECMA-402 and Test262 revisions used to define and reproduce a release's compatibility claims. It records what was verified without limiting development from tracking newer revisions.
_Avoid_: Supported edition, compatibility ceiling
