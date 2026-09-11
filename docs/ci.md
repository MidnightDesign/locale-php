# CI evidence

The repository contains reusable GitHub Actions workflows for runtime, quality, scheduled, and release evidence. Pull requests invoke the runtime and quality workflows through `.github/workflows/pull-request.yml`. Scheduled and release entry points remain dormant templates until separately activated.

The runtime matrix comes from `.ci/matrix.json`. It covers PHP 8.2–8.5 on `ubuntu-24.04` x64 and `windows-2022` x64 in absent, disabled, and native extension modes. Because the Homebrew PHP builds on `macos-15` Arm64 link `intl` statically, macOS runs disabled and native modes; it cannot provide honest absent-extension evidence. Every pull request, nightly run, and release runs complete mutation campaigns in all three modes on Ubuntu. The nightly matrix additionally adds Ubuntu Arm64 and PHP 8.6 early-warning coverage. The weekly matrix adds genuine Windows x86, Windows x64 thread-safe builds, and the selected ICU boundaries. Windows x86 remains release-blocking and uses checksum-pinned official PHP archives because the setup action cannot select x86 on a 64-bit runner. The release workflow reruns all evidence and tests the exact Composer archive.

Stable macOS runtime jobs share PHP provisioning and Composer installation between disabled and native modes. Each mode runs in a separate PHP process and retains its original artifact name, JUnit report, branch trace, and provenance. The PHP 8.2 and 8.5 jobs also run the package-install smoke check. Native and package checks still execute after an earlier test failure once runtime setup has succeeded; any failed check fails the job. PR and release callers disable duplicate standalone macOS install jobs with `separate-macos-install: false`; quality-only callers such as nightly retain them by default. This reduces PR macOS jobs from ten to four without reducing runtime or install coverage.

Matrix generation uses the PHP already present on the pinned Ubuntu runner and directly loads `tools/Ci/Matrix.php`, without installing Composer dependencies. `ci-matrix.php runtime` still lists all logical runtime lanes; `runtime-jobs` groups the macOS modes for execution.

Each runtime lane fixes UTC, the `C` process locale, and the ICU default locale. It retains JSON provenance and a branch trace. The native mode records `no-native-path-implemented` until a native path exists. Adding a native path requires adding its eligibility and execution counters to the trace; the lane fails when an eligible path was not exercised.

Mutation evidence is split by source ownership. `infection.spec.json5` mutates the spec implementation, its internal support, and shared exceptions using only the generated upstream Test262 suite. `infection.porcelain.json5` mutates the porcelain entry point using only porcelain contract tests. Generated release data is excluded from both campaigns. Each campaign retains its own coverage XML, JUnit report, Infection JSON, and text log.

`tools/merge-mutation-reports.php` compares the complete mutant identity multiset across absent, disabled, and native extension modes. Every current mutant must be killed in all three modes; there is no unchecked applicability or suppression mechanism. It records every per-mode obligation in `build/matrix-mutation-score.json` and fails unless every obligation is killed by its attributed test suite. Static-analysis detections do not receive test-kill credit. Escaped, uncovered, timed-out, errored, syntax-error, ignored, skipped, missing, malformed, or inconsistent evidence fails the gate; aggregation failures are themselves retained as machine-readable evidence.

Until the upstream-derived Test262 roadmap supplies complete spec coverage, pull-request CI treats only the reviewed mutant identities and per-mode results in `.ci/spec-mutation-expected-failure.json` as expected failures. The aggregate command requires every other campaign to pass, rejects new or changed failures and missing baseline identities, and permits a baseline failure only to become killed. Missing or malformed evidence, a mutation regression, or a fully passing spec campaign makes CI red; the latter is the canary requiring removal of the `--expect-failing` option and step-level `continue-on-error`.

## Pull-request CI

The pull-request workflow runs the complete stable runtime matrix and quality suite. Do not configure a check as required until its first hosted run has completed successfully and its retained provenance has been inspected.

## Remaining activation

Activate scheduled, release, and dependency-update automation in reviewed pull requests:

1. Move the `public-nightly.yml`, `public-weekly.yml`, and `public-release.yml` templates from `.github/ci/` into `.github/workflows/`. Move and rename `public-dependabot.yaml.template` to `.github/dependabot.yml`.
2. Run every scheduled, specialized, ICU, mutation, and packed-artifact lane.
3. Inspect the retained provenance and confirm the actual runner, architecture, PHP patch, build mode, extensions, ICU version, dependency lock, baseline, and release-data fingerprint.
4. Configure every non-advisory job as a required check. PHP development builds are the only advisory lanes.

The activation pull request must not claim conformance from the prepared or locally linted workflow files. Hosted results are the evidence.
