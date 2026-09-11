# Getting started

Use `Midnight\Intl\Locale` for application code. It is a final, immutable value: construct a new value when a component changes.

```php
use Midnight\Intl\CaseFirst;
use Midnight\Intl\HourCycle;
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
    calendar: 'islamicc',
    collation: 'phonebk',
    firstDayOfWeek: '1',
    hourCycle: HourCycle::H23,
    caseFirst: CaseFirst::Upper,
    numeric: true,
    numberingSystem: 'latn',
);

$overridden->toString(); // fr-Cyrl-CA-oxendict-spanglis-u-ca-islamic-civil-co-phonebk-fw-mon-hc-h23-kf-upper-kn-nu-latn
$overridden->hourCycle;  // HourCycle::H23
$overridden->caseFirst;  // CaseFirst::Upper
```

The constructor accepts named `language`, `script`, `region`, `variants`, `calendar`, `collation`, `firstDayOfWeek`, `hourCycle`, `caseFirst`, `numeric`, and `numberingSystem` options. `hourCycle` and `caseFirst` accept either their backed enums or backing strings. Their getters return the corresponding enum for a recognized closed-vocabulary value. A syntactically valid identifier can still carry another keyword value; the getter preserves that value as a string so `toSpec()` and `fromSpec()` remain lossless. A non-null option replaces the corresponding input component or Unicode keyword. `toString()`, string conversion, and JSON serialization return the complete canonical identifier.

Identifiers may contain variants, transformed extensions, Unicode attributes and keywords, other singleton extensions, and private-use subtags. Parsing is strict ASCII and structural: syntactically valid unregistered subtags are accepted, while duplicate variants, duplicate extension singletons, and malformed extension sequences are rejected.

`maximize()` adds the language, script, and region implied by the pinned release data snapshot. `minimize()` removes only the subtags that can be recovered from that same snapshot. Both return fresh immutable values and preserve variants, extensions, and private use.

```php
$locale = new Locale('zh-Hant-u-ca-chinese-x-catalog');

echo $locale->maximize(); // zh-Hant-TW-u-ca-chinese-x-catalog
echo $locale->minimize(); // zh-TW-u-ca-chinese-x-catalog
```
