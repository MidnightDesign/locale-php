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

**Conformant**:
A claim reserved for a complete spec layer whose applicable semantic behavior passes against its conformance baseline. The porcelain layer is backed by the conformant spec layer but is not itself called conformant because its stronger PHP contract intentionally differs from JavaScript coercion behavior.
_Avoid_: Mostly conformant, porcelain conformance

**Test intent**:
The specific observable behavior a Test262 fixture is designed to verify, distinguished from JavaScript mechanisms used incidentally to arrange or assert that behavior.
_Avoid_: Test syntax, every JavaScript feature used by a fixture

**Inapplicable test**:
A Test262 fixture whose test intent has no meaningful observable equivalent in PHP. A fixture is not inapplicable merely because its scaffolding uses JavaScript-only machinery or the translator does not yet support it.
_Avoid_: Untranslatable test, skipped test

**Translation gap**:
An applicable Test262 check that the current translator or PHP test scaffolding cannot yet express without losing its test intent. It is unfinished conformance work, not an inapplicable test.
_Avoid_: Unsupported test, skipped test

**Conformance evidence**:
The reproducible results and provenance that support a release's conformance claim against its exact conformance baseline and release data snapshot.
_Avoid_: Release report, coverage percentage

**Release data snapshot**:
The exact versioned Unicode, CLDR, and IANA inputs and derived data projections shipped by one package release. It is fingerprinted independently of the package version.
_Avoid_: Host ICU data, data version

**Package version**:
The Semantic Versioning identifier for a published Composer release. It identifies the library release without encoding its conformance baseline or release data snapshot.
_Avoid_: Conformance version, CLDR version

**Public API**:
The supported PHP contract of both the spec layer and porcelain layer. The `Internal` namespace is not part of this contract.
_Avoid_: Porcelain API only
