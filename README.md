# Intl.Locale for PHP

A pure-PHP implementation of ECMAScript `Intl.Locale`. It requires PHP 8.2 or newer and does not require `ext-intl`.

The package is not yet ECMA-402 conformant. Locale identifier construction and likely-subtag operations are complete: the public layers validate the full Unicode locale-identifier grammar, apply all eleven constructor options, preserve extensions, expose the canonical identifier properties, and provide deterministic `maximize()` and `minimize()` results. Locale-information methods remain unfinished.

## Install

The package name is provisional until its first public release.

```bash
composer require midnight/intl-locale
```

## Use the porcelain layer

```php
use Midnight\Intl\Locale;

$locale = new Locale('EN-latn-us-u-ca-gregory', region: 'GB', numeric: true);

echo $locale;              // en-Latn-GB-u-ca-gregory-kn
echo $locale->language;    // en
echo $locale->calendar;    // gregory
echo $locale->numeric;     // 1
echo $locale->maximize();  // en-Latn-GB-u-ca-gregory-kn
echo $locale->minimize();  // en-GB-u-ca-gregory-kn
echo json_encode($locale); // "en-Latn-GB-u-ca-gregory-kn"
```

The porcelain layer is the normal application API. See [Getting started](docs/getting-started.md), [the spec layer](docs/spec-layer.md), [conformance and release data](docs/conformance.md), and [migration guidance](docs/migration.md).

## Develop

```bash
docker compose build php
docker compose run --rm php composer install
docker compose run --rm php composer test
docker compose run --rm php composer analyse
docker compose run --rm php composer data:check
docker compose run --rm php composer test262:check
docker compose run --rm php composer test:package
```
