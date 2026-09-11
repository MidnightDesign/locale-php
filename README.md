# Intl.Locale for PHP

A pure-PHP implementation of ECMAScript `Intl.Locale`. It requires PHP 8.2 or newer and does not require `ext-intl`.

The package is not yet ECMA-402 conformant. Locale identifier construction is complete, and both public layers provide pinned, deterministic primary time-zone identifiers for explicit regions through `getTimeZones()`. Other locale-information and likely-subtag methods remain unfinished.

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
echo json_encode($locale); // "en-Latn-GB-u-ca-gregory-kn"
$locale->getTimeZones();   // ['Europe/London']
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
