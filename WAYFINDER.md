# Intl.Locale for PHP

The active Wayfinder map is the canonical GitHub issue:

- [Intl.Locale for PHP](https://github.com/MidnightDesign/locale-php/issues/1)

Its tickets are native sub-issues, and their blocking relationships use GitHub issue dependencies. Ticket resolutions live as comments on their original issues.

## Spec-layer testing policy

Confirmed on 2026-09-10; applies to the canonical map and every implementation ticket, including earlier validation decisions.

- Cover the spec layer completely and exclusively with original upstream TC39 Test262 tests, faithfully transpiled to PHP and run directly against the spec layer. Preserve assertion intent, provenance, and every meaningful representation variant.
- Do not write custom spec-layer tests, including golden tests, regression tests, or tests of spec behavior through the porcelain layer. Porcelain tests cover only its distinct PHP contracts; their incidental execution of spec code supplies no spec coverage or mutation credit.
- If an upstream test exists but cannot yet run, extend the transpiler or harness. If Test262 lacks a needed test, create a ticket in `MidnightDesign/locale-php` to open a PR against `tc39/test262`, describing the missing behavior and linking the upstream PR and eventual fixture/revision. Incorporate the accepted test through the normal baseline-update pipeline. A ticket or pending PR is not passing coverage.
- Existing custom spec tests are temporary stop-gaps only. Inventory them against upstream fixtures and translation gaps, and delete each as soon as the corresponding upstream coverage runs. Do not add new custom tests or preserve duplicates; complete coverage requires their retirement.
- Spec-layer coverage and applicable mutation obligations must be satisfied by the upstream-derived suite alone in every required extension mode. Porcelain tests cannot conceal gaps or kill spec mutants for this gate. Keep the existing complete-coverage and 100% matrix mutation requirements.
- Custom tests may verify porcelain-specific typing/representation, the translator/harness, data-generation integrity, packaging, and CI tooling. Golden data checks and ICU diagnostics must not become custom assertions of spec behavior, directly or through porcelain. Run upstream tests under required environment/ambient-state variations to verify spec behavior there.

The retained high-risk prototype results are historical planning evidence, not an exception to this policy or production conformance coverage. Its disposable code and tests have already been removed from this checkout.
