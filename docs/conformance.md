# Conformance and release data

This package is not yet ECMA-402 conformant. Its initial vertical slice is intentionally incomplete.

The current conformance baseline pins ECMA-402 at `b1c961988b9a07894b1dc3dc2b5626ea48387d61` and Test262 at `419d3e0a2273ba01a3bfcbec423f2801425b8e93`. [The Test262 baseline manifest](../tests/Test262/baseline.json) separates these immutable initial revisions from the active tracked inputs. Advancing the active inputs compares them with [the immutable initial inventory](../tests/Test262/initial-inventory.json) and generates a stable record of added, changed, and removed fixtures and assertion identities; repeated generation does not erase that comparison. The initial alias projection pins CLDR at `11299982335beb974c1c63c45265184e759c0f41`. [The release data manifest](../resources/data/manifest.json) records the data inputs.

Pinned Test262 originals used by generated tests live under `tests/Test262/upstream`. The directory also preserves the pinned Test262 and ECMA-402 license notices. Generated PHP translations retain both source revisions and paths, and `composer test262:check` proves byte-for-byte regeneration.

[The translated evidence](../tests/Test262/evidence.json) records the verified upstream source and license identities, assertion adaptations, required PHP representations, and execution results. The original `constructor-options-script-valid.js` fixture runs 30 generated checks: its three source assertions expand across five upstream cases and both associative-array and plain-object option bags without being counted as 30 upstream assertions. Each check remains tied to its original source assertion.

[The complete pinned Locale corpus inventory](../tests/Test262/corpus.json) keeps all 168 upstream fixtures and all 485 standard Test262 assertion expressions visible with stable source locations and expression hashes. A fixture-level gap also covers parameterized executions and custom helper behavior until translated evidence accounts for them. Translation gaps, incomplete inventory entries, and execution failures make the machine-readable conformance claim ineligible. Every generator run also writes the current result to `build/test262-results.json`, including failures that differ from the committed evidence. A translation gap remains unfinished work and does not count as a pass.

All public results come from bundled PHP code and static data. Host ICU, ambient locale and time-zone defaults, network access, and writable storage do not define results.
