# Choose the public API and package boundary

Type: grilling
Status: open
Blocked by: 01

## Question

What package identity and public API should expose the chosen promise: class and namespace names, constructor/factory shape, immutable properties or accessor methods, spec versus porcelain layers, exception taxonomy, serialization, equality, and integration with PHP’s existing static `Locale` class?

The decision should also establish the initial ubiquitous language so `CONTEXT.md` can distinguish concepts such as locale identifier, language tag, locale object, canonical form, extension keyword, host ICU data, and bundled normative data.
