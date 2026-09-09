# Choose the semantic engine and data boundary

Type: grilling
Status: open
Blocked by: 01, 02, 03, 04

## Question

Which responsibilities belong to a userland Unicode locale-identifier engine, which may delegate to `ext-intl`/ICU, and which—if any—require versioned bundled CLDR/IANA data so the selected conformance and reproducibility promises can be met?

Compare at least a thin ICU wrapper, a hybrid parser plus ICU queries, and a self-contained data-backed implementation. Decide ownership of validation, canonicalization, option/extension merging, likely subtags, locale-information ordering, data upgrades, and failure handling.
