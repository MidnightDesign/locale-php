# Intl.Locale for PHP

A pure-PHP implementation of ECMAScript `Intl.Locale`. It requires PHP 8.2 or newer and does not require `ext-intl`.

The package is not yet ECMA-402 conformant. The current implementation covers locale construction and properties, likely-subtag operations, and all locale-information methods; upstream-only conformance work remains incomplete. See [conformance and release data](docs/conformance.md) for the exact evidence and open gaps.

## Install

The package name is provisional until its first public release.

```bash
composer require midnight/intl-locale
```

## Use the porcelain layer

```php
use Midnight\Intl\Locale;

$locale = new Locale('EN-latn-us-u-ca-gregory', region: 'GB', numeric: true);

assert((string) $locale === 'en-Latn-GB-u-ca-gregory-kn');
assert($locale->language === 'en');
assert($locale->calendar === 'gregory');
assert($locale->numeric === true);
assert($locale->maximize()->toString() === 'en-Latn-GB-u-ca-gregory-kn');
assert($locale->minimize()->toString() === 'en-GB-u-ca-gregory-kn');
assert($locale->getTextInfo()->direction?->value === 'ltr');
assert(json_encode($locale) === '"en-Latn-GB-u-ca-gregory-kn"');
assert($locale->getCollations() === ['emoji', 'eor']);
assert($locale->getTimeZones() === ['Europe/London']);
assert($locale->getCalendars() === ['gregory']);
assert(array_map(static fn($cycle): string => $cycle->value, $locale->getHourCycles()) === ['h23', 'h12']);
assert($locale->getNumberingSystems() === ['latn']);
assert($locale->getWeekInfo()->firstDay === 1);
```

The porcelain layer is the normal application API. PHP 8.2 and newer are supported on Linux, macOS, and Windows. `ext-intl` is optional: results do not depend on whether it is installed, the host ICU version, or process locale defaults.

See [Getting started](docs/getting-started.md), [the complete API reference](docs/api-reference.md), [the spec layer](docs/spec-layer.md), [conformance and release data](docs/conformance.md), and [migration guidance](docs/migration.md).

## Develop

```bash
docker compose build php
docker compose run --rm php composer install
docker compose run --rm php composer test
docker compose run --rm php composer analyse
docker compose run --rm php composer docs:check
docker compose run --rm php composer data:check
docker compose run --rm php composer test262:check
docker compose run --rm php composer test:package
```

### Codex local environment

The shared `locale-php` environment is configured in
[`.codex/environments/environment.toml`](.codex/environments/environment.toml).
Start Docker Desktop (or a Docker engine with Compose) before using it. Select
this environment when creating a Codex worktree; setup builds the PHP 8.2 image
and installs the locked Composer dependencies, including development tools.
The Test, Analyse, Style, and Verify CI actions run inside that container.

To initialize the current checkout manually, run:

```bash
docker compose -f compose.yaml -f .codex/compose.yaml build php
docker compose -f compose.yaml -f .codex/compose.yaml run --rm php composer install --no-interaction --no-progress --prefer-dist
```

The Codex Compose override keeps dependencies and the Composer download cache
in named Docker volumes. These volumes are shared between worktrees, so rerun
setup after switching dependency versions and avoid simultaneous installs from
worktrees with different lockfiles.
