# Intl.Locale for PHP

A pure-PHP implementation of ECMAScript `Intl.Locale`, beginning with an intentionally incomplete language/script/region slice. It requires PHP 8.2 or newer and does not require `ext-intl`.

The package is not yet ECMA-402 conformant. The current slice accepts a language with an optional script and region, applies those three constructor options, exposes the canonical base components, and supports explicit conversion between the porcelain and spec layers. Variants, extensions, locale-information methods, and likely-subtag methods remain unfinished.

## Install

The package name is provisional until its first public release.

```bash
composer require midnight/intl-locale
```

## Use the porcelain layer

```php
use Midnight\Intl\Locale;

$locale = new Locale('EN-latn-us', region: 'GB');

echo $locale;             // en-Latn-GB
echo $locale->language;   // en
echo $locale->script;     // Latn
echo $locale->region;     // GB
echo json_encode($locale); // "en-Latn-GB"
```

The porcelain layer is the normal application API. See [Getting started](docs/getting-started.md), [the spec layer](docs/spec-layer.md), [conformance and release data](docs/conformance.md), and [migration guidance](docs/migration.md).

## Develop

```bash
docker compose build php
docker compose run --rm php composer install
docker compose run --rm php composer test
docker compose run --rm php composer analyse
docker compose run --rm php composer test262:check
docker compose run --rm php composer test:package
```
