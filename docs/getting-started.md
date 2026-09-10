# Getting started

Use `Midnight\Intl\Locale` for application code. It is a final, immutable value: construct a new value when a component changes.

```php
use Midnight\Intl\Locale;

$locale = new Locale('de-Latn-DE');

$locale->baseName; // de-Latn-DE
$locale->language; // de
$locale->script;   // Latn
$locale->region;   // DE
```

The constructor also accepts named `language`, `script`, and `region` options. A non-null option replaces the corresponding input component. `toString()`, string conversion, and JSON serialization return the canonical identifier.

The initial slice accepts only a language followed by an optional script and region. It rejects other locale syntax until those semantics and their pinned data are delivered.
