# Define the conformance promise

Type: grilling
Status: closed
Assignee: r.gottesheim@midnight-design.at

## Question

What must “an `Intl.Locale` for PHP” promise: a byte-for-byte ECMA-402 spec layer, an idiomatic PHP adaptation with documented deviations, both as separate layers like Calendrics, or a narrower initial milestone—and against which edition or pinned revision of ECMA-402 should that promise be evaluated?

The decision should explicitly define what may be called “conformant,” how JavaScript-only observable behavior is described, and whether newer surface such as `variants` and `firstDayOfWeek` belongs in the initial release.

## Resolution

**Adopt two mirrored API tiers.** The spec layer is the TC39-faithful implementation. The porcelain layer exposes the same locale capabilities and keeps class, method, property, parameter, and other symbol names identical or very close to the spec layer. A naming deviation needs a compelling, documented reason. The porcelain layer may strengthen the contract with native PHP types, narrower docblock types such as `non-empty-string`, backed enums alongside string unions, and similar PHP-native representations.

**Track latest; pin releases.** Development continuously targets the latest ECMA-402 draft and Test262 corpus. Every library release records the exact ECMA-402 and Test262 revisions used for its conformance results so the tested semantics and denominator remain reproducible. These pins record evidence; they are not a compatibility ceiling.

**Require the complete surface for 1.0.** Pre-1.0 releases may deliver explicitly incomplete vertical slices. Version 1.0 must cover the complete current `Intl.Locale` semantic surface in both tiers, including newer members such as `variants` and `firstDayOfWeek` when they are present in the release's conformance baseline. An incomplete release must not call the library conformant.

**Reserve “ECMA-402 conformant” for the spec layer.** The claim requires a complete semantic surface for the pinned baseline and all applicable faithfully translated behavior to pass. Reports must expose adapted, inapplicable, unsupported, and failing cases separately; unsupported or failing behavior must not disappear from a percentage. The porcelain layer is described as backed by the conformant spec layer because its deliberately stronger PHP contract can differ from JavaScript coercion semantics.

**Classify Test262 by test intent.** A fixture is inapplicable only when the observable behavior it intends to assert has no meaningful PHP equivalent. JavaScript-only machinery used incidentally to arrange or assert other behavior should be translated or replaced with equivalent PHP scaffolding. An unsupported translator construct is a translation gap, not evidence that the fixture is inapplicable. Subclassing is not categorically JavaScript-only and must be assessed by the behavior under test. Detailed fixture categories and review controls remain for [Define the Test262 transpilation policy](07-define-test262-transpilation-policy.md).
