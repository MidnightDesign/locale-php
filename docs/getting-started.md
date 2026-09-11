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

`maximize()` adds the language, script, and region implied by the pinned release data snapshot. `minimize()` removes only the subtags that can be recovered from that same snapshot. Both return fresh immutable values and preserve variants, extensions, and private use.

```php
$locale = new Locale('zh-Hant-u-ca-chinese-x-catalog');

echo $locale->maximize(); // zh-Hant-TW-u-ca-chinese-x-catalog
echo $locale->minimize(); // zh-TW-u-ca-chinese-x-catalog
```
