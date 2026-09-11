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

## Implementation completion and full conformance

Confirmed on 2026-09-11. This distinction supersedes earlier wording that made every implementation ticket wait for complete upstream coverage.

- An implementation issue may close when its requested runtime behavior, both public layers where applicable, data, documentation/examples, translator/harness support for available applicable upstream fixtures, and required local/CI checks are complete. The implementation must still satisfy its behavioral requirements; an upstream gap is not permission to omit or knowingly break behavior.
- Every assertion missing from upstream must be recorded in a linked issue labelled `upstream-test`, with the uncovered behavior, an illustrative Test262 draft, and the path through upstream acceptance, baseline pinning, translation, and passing evidence. Link the follow-up from the implementation issue and PR before closing the implementation issue.
- Missing upstream assertions remain missing conformance coverage. Existing upstream fixtures that need translator/harness work, failing available tests, and implementation defects are not deferred under this exception. Keep the current CI gates and reviewed mutation baseline rules; do not weaken checks or count pending work as passing coverage.
- Native dependencies on implementation issues represent implementation prerequisites and are satisfied when the implementation merges and its issue closes. Upstream acceptance blocks another implementation issue only when that particular gap makes the work unsafe or impossible; record the concrete reason for such an edge.
- Every upstream coverage follow-up must be a native blocker of #33 (full conformance). #33 cannot close until all applicable upstream gaps are accepted, pinned, translated, and passing, custom spec stop-gaps are retired, and complete coverage and mutation obligations are satisfied in every required mode. Final artifact validation (#39) must depend directly on #33; publication (#40) retains its dependency on #39. Full conformance and 1.0 release requirements are unchanged.
- Implementation PRs use closing keywords for completed implementation issues and ordinary references for outstanding upstream coverage issues. If a PR has already merged, close its implementation issue explicitly with the merge and follow-up evidence.
