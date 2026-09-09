# Intl.Locale for PHP

Label: `wayfinder:map`

This is the repository's active Wayfinder map. When `/wayfinder` is invoked without another map or destination, resume this map and select its first open, unblocked, unclaimed child ticket.

## Destination

Reach an implementation-ready specification for a standalone PHP library that provides a deliberately defined subset or faithful adaptation of ECMAScript `Intl.Locale`, with its public API, compatibility promise, locale-data strategy, Test262 transpilation policy, validation approach, and delivery boundaries decided.

The map is complete when an implementation team can create execution tickets without making additional product or architecture decisions.

## Notes

- Domain: PHP internationalization, Unicode locale identifiers, ECMA-402, ICU/CLDR, and Test262 conformance.
- Planning only. Do not implement the production library while resolving this map unless the destination is explicitly redrawn.
- Use `grilling` for product and architecture decisions, `domain-modeling` to establish precise terminology as decisions settle, `research` for external standards/runtime facts, and `prototype` only where a small executable experiment is necessary to make a decision.
- The user asked not to be questioned while this map was being charted. Questions that require user judgment are preserved as `grilling` tickets rather than answered by assumption.
- Treat [MidnightDesign/calendrics](https://github.com/MidnightDesign/calendrics) as the reference for the two-tier PHP/spec architecture and its Acorn-based Test262 transpilation approach, not as a requirement to copy every design choice.
- Treat ECMA-402 as normative, Test262 as the conformance corpus, CLDR/ICU as versioned data dependencies, and MDN as explanatory documentation.
- Local tracker convention: this root file is canonical; child tickets live in `.scratch/intl-locale-for-php/issues/`; `Blocked by` records dependencies; the first open, unblocked, unclaimed ticket is the frontier.

## Decisions so far

<!-- One linked gist per resolved child ticket. The full answer lives in that ticket. -->

## Not yet specified

- The exact PHP object/value model, immutability rules, namespaces, method names, and relationship between a TC39-faithful spec layer and an idiomatic porcelain layer cannot be finalized until the conformance promise is chosen.
- The precise division between a userland BCP-47 engine, PHP `ext-intl`, ICU-backed queries, and possibly packaged CLDR data depends on the supported platform matrix and capability research.
- Error and coercion semantics—including which JavaScript behaviors have faithful PHP equivalents and which become documented deviations—depend on the conformance and API decisions.
- The exact treatment of JavaScript-only Test262 cases such as branding, property descriptors, realms, symbols, constructor metadata, subclassing, observers, and coercion needs a fidelity policy before individual translations can be specified.
- CI dimensions, golden data, ICU-version expectations, reproducibility controls, and cross-platform coverage depend on the runtime and locale-data strategies.
- Upstream synchronization, pinned revisions, generated-test review, licensing notices, drift detection, and release/versioning policy depend on the test and data designs.
- Performance targets, caching, memory budgets, and deployment constraints are not yet sharp enough to ticket before the semantic/data boundary is known.
- Documentation structure, migration guidance, package naming, and the implementation ticket graph should be charted after the core product and architecture decisions settle.

## Out of scope

- Implementing production classes, generating the complete test corpus, or publishing a Composer package during this planning map.
- Implementing unrelated ECMA-402 constructors such as `Intl.Collator`, `Intl.DateTimeFormat`, or `Intl.NumberFormat`, except where their ICU facilities are investigated as data sources for `Intl.Locale`.
- Patching PHP or ICU itself.
