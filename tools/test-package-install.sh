#!/usr/bin/env sh

set -eu

project_root=$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)
work_dir=$(mktemp -d)
trap 'rm -rf -- "$work_dir"' EXIT

mkdir "$work_dir/consumer"
cd "$work_dir/consumer"
composer init --name=midnight/intl-locale-smoke --no-interaction
composer config repositories.package \
    "{\"type\":\"path\",\"url\":\"$project_root\",\"options\":{\"symlink\":false}}" \
    --json
composer require midnight/intl-locale:@dev --no-interaction --no-plugins --no-scripts

php -r 'require "vendor/autoload.php"; $locale = new Midnight\Intl\Locale("EN-latn-us"); if ($locale->toString() !== "en-Latn-US") { exit(1); }'
