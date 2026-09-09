# Audit PHP and ICU capabilities

Type: research
Status: open

## Question

For each required `Intl.Locale` algorithm and locale-information method, what can supported PHP versions implement faithfully through `ext-intl`, what can be derived through other exposed ICU APIs, what differs from ECMA-402 semantics, and what requires userland parsing or packaged data?

Produce a linked Markdown capability matrix with runnable probes where useful. Cover BCP-47 validation and canonicalization, Unicode extensions, likely subtags, text direction, calendars, collations, hour cycles, numbering systems, time zones, week information, ICU error behavior, and cross-version determinism.
