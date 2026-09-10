# CI evidence

The repository contains prepared, reusable GitHub Actions workflows for runtime, quality, scheduled, and release evidence. They use `workflow_call` only. GitHub therefore cannot start them directly while this repository is private.

The runtime matrix comes from `.ci/matrix.json`. It covers PHP 8.2–8.5 on `ubuntu-24.04` x64, `windows-2022` x64, and `macos-15` Arm64 in three extension modes. The nightly matrix adds Ubuntu Arm64 and PHP 8.6 early-warning coverage. The weekly matrix adds Windows x64 thread-safe builds, the selected ICU boundaries, and a complete mutation campaign. Genuine Windows x86 remains release-blocking and uses checksum-pinned official PHP archives because the setup action cannot select x86 on a 64-bit runner. The release workflow reruns all evidence and tests the exact Composer archive.

Each runtime lane fixes UTC, the `C` process locale, and the ICU default locale. It retains JSON provenance and a branch trace. The native mode records `no-native-path-implemented` until a native path exists. Adding a native path requires adding its eligibility and execution counters to the trace; the lane fails when an eligible path was not exercised.

## Activation

Do not treat these workflows as passing until GitHub has run them. Local unit tests, analyzers, workflow linting, and package smoke tests verify their structure and supporting commands only.

After the repository becomes public, activate CI in a reviewed pull request:

1. Move the `public-pull-request.yml`, `public-nightly.yml`, `public-weekly.yml`, and `public-release.yml` templates from `.github/ci/` into `.github/workflows/`. Move and rename `public-dependabot.yaml.template` to `.github/dependabot.yml`.
2. Run every pull-request, scheduled, specialized, ICU, mutation, and packed-artifact lane.
3. Inspect the retained provenance and confirm the actual runner, architecture, PHP patch, build mode, extensions, ICU version, dependency lock, baseline, and release-data fingerprint.
4. Configure every non-advisory job as a required check. PHP development builds are the only advisory lanes.

The activation pull request must not claim conformance from the prepared or locally linted workflow files. Hosted results are the evidence.
