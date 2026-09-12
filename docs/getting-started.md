# Getting started

Use `Midnight\Intl\Locale` for application code. It is a final, immutable value: construct a new value when a component changes.

For exact signatures, return types, exceptions, serialization, and subclassing rules, see the [API reference](api-reference.md).

```php
use Midnight\Intl\CaseFirst;
use Midnight\Intl\HourCycle;
use Midnight\Intl\Locale;

$locale = new Locale('de-Latn-DE-u-ca-gregory');

assert($locale->baseName === 'de-Latn-DE');
assert($locale->language === 'de');
assert($locale->script === 'Latn');
assert($locale->region === 'DE');
assert($locale->calendar === 'gregory');
assert($locale->getCalendars() === ['gregory']);
assert($locale->getHourCycles() === [HourCycle::H23, HourCycle::H12]);
assert($locale->getCollations() === ['emoji', 'eor', 'phonebk']);
assert($locale->getTimeZones() === ['Europe/Berlin', 'Europe/Busingen']);
assert($locale->getNumberingSystems() === ['latn']);
assert($locale->getWeekInfo()->firstDay === 1);
assert($locale->getWeekInfo()->weekend === [6, 7]);

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

assert($overridden->toString() === 'fr-Cyrl-CA-oxendict-spanglis-u-ca-islamic-civil-co-phonebk-fw-mon-hc-h23-kf-upper-kn-nu-latn');
assert($overridden->hourCycle === HourCycle::H23);
assert($overridden->caseFirst === CaseFirst::Upper);
```

The constructor accepts named `language`, `script`, `region`, `variants`, `calendar`, `collation`, `firstDayOfWeek`, `hourCycle`, `caseFirst`, `numeric`, and `numberingSystem` options. `hourCycle` and `caseFirst` accept either their backed enums or backing strings. Their getters return the corresponding enum for a recognized closed-vocabulary value. A syntactically valid identifier can still carry another keyword value; the getter preserves that value as a string so `toSpec()` and `fromSpec()` remain lossless. A non-null option replaces the corresponding input component or Unicode keyword. `toString()`, string conversion, and JSON serialization return the complete canonical identifier.

Identifiers may contain variants, transformed extensions, Unicode attributes and keywords, other singleton extensions, and private-use subtags. Parsing is strict ASCII and structural: syntactically valid unregistered subtags are accepted, while duplicate variants, duplicate extension singletons, and malformed extension sequences are rejected.

`getTimeZones()` returns the pinned release data snapshot's canonical primary identifiers for an explicitly present region. It returns `null` when the locale has no region and never infers one from likely subtags or `rg`/`sd` keywords. Each call returns a fresh, unique, code-unit-sorted list; a present region with no known entries returns an empty list.

`getCalendars()` and `getHourCycles()` return fresh preference-ordered lists from the pinned CLDR snapshot. An explicit `ca` or `hc` keyword produces a singleton list. Otherwise, an `rg` override is tried before the region selected from the explicit region, canonical `sd` subdivision, likely subtags, or world fallback. Language-region data takes priority over region-only data. Calendar values are canonical strings; porcelain hour cycles are `HourCycle` enum cases.

`getNumberingSystems()` returns a fresh, one-element list. An explicit `nu` keyword is returned as-is; otherwise the element is the matched NumberFormat locale's default from the pinned CLDR projection, falling back to `latn` when no projected locale matches.

```php
use Midnight\Intl\Locale;

assert((new Locale('fa'))->getNumberingSystems() === ['arabext']);
assert((new Locale('en-u-nu-thai'))->getNumberingSystems() === ['thai']);
```

`getWeekInfo()` returns a fresh immutable `WeekInfo` value with an ISO `firstDay` (`1` for Monday through `7` for Sunday) and a non-empty ascending `weekend` list. The `rg` region override, explicit region, `sd` subdivision, likely-subtag region, and world fallback use the shared region preference order; `fw` overrides only `firstDay`. Results come from the pinned CLDR projection and intentionally omit the obsolete `minimalDays` field.

```php
use Midnight\Intl\Locale;

$week = (new Locale('en-AE'))->getWeekInfo();

assert($week->firstDay === 1);
assert($week->weekend === [6, 7]);
```

`getCollations()` returns the pinned release data snapshot's canonical collation identifiers for the matched locale, excluding the `standard` and `search` defaults. An explicit `co` keyword or constructor option produces a singleton list even when the value is unavailable for that locale. Unmatched locales return `['emoji', 'eor']`. Each call returns a fresh, code-unit-sorted list and does not consult host ICU or ambient locale defaults.

`maximize()` adds the language, script, and region implied by the pinned release data snapshot. `minimize()` removes only the subtags that can be recovered from that same snapshot. Both return fresh immutable values and preserve variants, extensions, and private use.

```php
use Midnight\Intl\Locale;

$locale = new Locale('zh-Hant-u-ca-chinese-x-catalog');

assert($locale->maximize()->toString() === 'zh-Hant-TW-u-ca-chinese-x-catalog');
assert($locale->minimize()->toString() === 'zh-TW-u-ca-chinese-x-catalog');
```

`getTextInfo()` returns a fresh immutable `TextInfo` value. Its nullable `TextDirection` enum is `LeftToRight` (`ltr`) or `RightToLeft` (`rtl`); it is `null` only when the pinned release data snapshot cannot determine a direction. An explicit script takes precedence, otherwise the script is inferred from likely subtags.

```php
use Midnight\Intl\Locale;
use Midnight\Intl\TextDirection;

$direction = (new Locale('ar'))->getTextInfo()->direction;

assert($direction === TextDirection::RightToLeft);
```
