# Getting started

Use `Midnight\Intl\Locale` for application code. It is a final, immutable value: construct a new value when a component changes.

```php
use Midnight\Intl\Locale;

$locale = new Locale('de-Latn-DE-u-ca-gregory');

$locale->baseName; // de-Latn-DE
$locale->language; // de
$locale->script;   // Latn
$locale->region;   // DE
$locale->calendar; // gregory
$locale->getTimeZones(); // ['Europe/Berlin', 'Europe/Busingen']

$overridden = new Locale(
    $locale->toString(),
    language: 'fr',
    script: 'Cyrl',
    region: 'CA',
    variants: 'spanglis-oxendict',
);

$overridden->toString(); // fr-Cyrl-CA-oxendict-spanglis-u-ca-gregory
```

The constructor accepts named `language`, `script`, `region`, `variants`, `calendar`, `collation`, `firstDayOfWeek`, `hourCycle`, `caseFirst`, `numeric`, and `numberingSystem` options. A non-null option replaces the corresponding input component or Unicode keyword. `toString()`, string conversion, and JSON serialization return the complete canonical identifier.

Identifiers may contain variants, transformed extensions, Unicode attributes and keywords, other singleton extensions, and private-use subtags. Parsing is strict ASCII and structural: syntactically valid unregistered subtags are accepted, while duplicate variants, duplicate extension singletons, and malformed extension sequences are rejected.

`getTimeZones()` returns the pinned release data snapshot's canonical primary identifiers for an explicitly present region. It returns `null` when the locale has no region and never infers one from likely subtags or `rg`/`sd` keywords. Each call returns a fresh, unique, code-unit-sorted list; a present region with no known entries returns an empty list.
