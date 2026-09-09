# Prototype the high-risk conformance path

Type: prototype
Status: open
Blocked by: 04, 06, 07

## Question

Does the proposed semantic/data boundary and transpilation policy survive a deliberately difficult vertical slice containing canonicalization and extension merging, likely-subtag behavior, at least one locale-information method, a version-sensitive ICU result, a JavaScript-only metadata fixture, and an untranslatable observer/coercion fixture?

Build only enough disposable code and generated tests to expose incorrect assumptions. Present the prototype and its result classifications for human review; do not evolve it into the production implementation.
