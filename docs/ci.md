# CI evidence

The repository contains reusable GitHub Actions workflows for runtime, quality, scheduled, and release evidence. Pull requests invoke the runtime and quality workflows through `.github/workflows/pull-request.yml`. Scheduled and release entry points remain dormant templates until separately activated.

The runtime matrix comes from `.ci/matrix.json`. It covers PHP 8.2–8.5 on `ubuntu-24.04` x64, `windows-2022` x64, and `macos-15` Arm64 in three extension modes. The nightly matrix adds Ubuntu Arm64, PHP 8.6 early-warning coverage, and a complete mutation campaign. The weekly matrix adds genuine Windows x86, Windows x64 thread-safe builds, and the selected ICU boundaries. Windows x86 remains release-blocking and uses checksum-pinned official PHP archives because the setup action cannot select x86 on a 64-bit runner. The release workflow reruns all evidence and tests the exact Composer archive.

Each runtime lane fixes UTC, the `C` process locale, and the ICU default locale. It retains JSON provenance and a branch trace. The native mode records `no-native-path-implemented` until a native path exists. Adding a native path requires adding its eligibility and execution counters to the trace; the lane fails when an eligible path was not exercised.

## Pull-request CI

The pull-request workflow runs the complete stable runtime matrix and quality suite. Do not configure a check as required until its first hosted run has completed successfully and its retained provenance has been inspected.

## Remaining activation

Activate scheduled, release, and dependency-update automation in reviewed pull requests:

1. Move the `public-nightly.yml`, `public-weekly.yml`, and `public-release.yml` templates from `.github/ci/` into `.github/workflows/`. Move and rename `public-dependabot.yaml.template` to `.github/dependabot.yml`.
2. Run every scheduled, specialized, ICU, mutation, and packed-artifact lane.
3. Inspect the retained provenance and confirm the actual runner, architecture, PHP patch, build mode, extensions, ICU version, dependency lock, baseline, and release-data fingerprint.
4. Configure every non-advisory job as a required check. PHP development builds are the only advisory lanes.

The activation pull request must not claim conformance from the prepared or locally linted workflow files. Hosted results are the evidence.
