# Conformance and release data

This package is not yet ECMA-402 conformant. Its initial vertical slice is intentionally incomplete.

The current conformance baseline pins ECMA-402 at `b1c961988b9a07894b1dc3dc2b5626ea48387d61` and Test262 at `419d3e0a2273ba01a3bfcbec423f2801425b8e93`. The initial alias projection pins CLDR at `11299982335beb974c1c63c45265184e759c0f41`. [The machine-readable manifest](../resources/data/manifest.json) records these inputs.

Pinned Test262 originals live under `tests/Test262/upstream`. Generated PHP translations retain source provenance, and `composer test262:check` proves byte-for-byte regeneration. [The evidence inventory](../tests/Test262/evidence.json) lists applicable assertions and translation gaps separately. A translation gap remains unfinished work and does not count as a pass.

All public results come from bundled PHP code and static data. Host ICU, ambient locale and time-zone defaults, network access, and writable storage do not define results.
