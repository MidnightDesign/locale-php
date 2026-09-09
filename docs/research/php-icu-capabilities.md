# PHP and ICU capability audit for `Intl.Locale`

Status: research finding for [Audit PHP and ICU capabilities](https://github.com/MidnightDesign/locale-php/issues/5), researched 2026-09-09. This is planning evidence, not an implementation specification. ECMA-402 and Test262 references are therefore live references; a release must pin exact commits as its conformance baseline.

## Decision

The conformance promise can survive a PHP 8.2 minimum and an optional `ext-intl`, but only if the spec layer owns locale parsing, canonicalization, Unicode-extension handling, error normalization, and a generated, versioned data snapshot. `ext-intl` may accelerate or cross-check operations; it cannot be the semantic authority because its surface differs from ECMA-402 and its ICU/CLDR/tzdata versions are chosen by the host.

PHP 8.5 is more convenient than 8.4, not materially more capable. It adds `Locale::addLikelySubtags()`, `Locale::minimizeSubtags()`, and `Locale::isRightToLeft()`; PHP 8.2–8.4 already expose the calendar, time-zone, and date-pattern primitives needed for approximate host-data answers. PHP 8.4 adds typed intl constants and `IntlTimeZone::getIanaID()`, but that helper additionally requires ICU >=74 and does not supply the Locale-info operation. PHP 8.2 and 8.3 do not materially change this result. [PHP 8.5 new features](https://www.php.net/manual/en/migration85.new-features.php), [`Locale::isRightToLeft()` changelog](https://www.php.net/manual/en/locale.isrighttoleft.php), [PHP 8.4 new functions](https://www.php.net/manual/en/migration84.new-functions.php), [`IntlTimeZone::getIanaID()` requirements](https://www.php.net/manual/en/intltimezone.getianaid.php), [`IntlDatePatternGenerator` availability](https://www.php.net/manual/en/class.intldatepatterngenerator.php), [`Locale` changelog](https://www.php.net/manual/en/class.locale.php)

Making `ext-intl` optional is viable if the package always ships the pinned data needed by its advertised spec layer. Making it optional while silently returning host-derived data when installed and a smaller/different answer when absent is not viable: the same release would have observably different semantics. A reduced public feature set is viable only for the porcelain layer; it cannot be called a complete, conformant spec layer because Test262 explicitly groups the seven Locale-info methods as `Intl.Locale-info`. [Test262 feature mapping](https://github.com/tc39/test262/blob/main/WEB_FEATURES.yml), [Test262's conformance-suite scope](https://github.com/tc39/test262)

## Constraints that drive the design

- ECMA-402 requires a well-formed Unicode BCP-47 locale identifier, canonicalizes syntax and aliases, preserves and orders extensions, and throws on invalid input. It deliberately leaves much locale data implementation-defined, within stated constraints. [ECMA-402 language tags and implementation dependencies](https://tc39.es/ecma402/#sec-identification-of-locales-currencies-time-zones-measurement-units-numbering-systems-collations-and-calendars), [UTS #35 locale grammar and canonical form](https://unicode.org/reports/tr35/#Unicode_Language_and_Locale_Identifiers)
- ICU locale IDs are a related but different representation. ICU's locale C API conventionally uses underscores and ICU keywords; its BCP-47 converter ignores the first ill-formed subtag and everything after it unless the caller verifies `parsedLength`, while strict conversion back to BCP-47 is a separate option. PHP does not expose that strict round-trip as one operation. [ICU `uloc_forLanguageTag()` and `uloc_toLanguageTag()`](https://unicode-org.github.io/icu-docs/apidoc/released/icu4c/uloc_8h.html), [ICU locale-ID model](https://unicode-org.github.io/icu-docs/apidoc/released/icu4c/uloc_8h.html#details)
- `Locale::canonicalize()` is therefore not a validator. A local PHP 8.3.20/ICU 72.1 probe accepted `en--US` as `en__US` and `en-US-garbage!` as `en_US_GARBAGE!`, both with `U_ZERO_ERROR`; the same inputs throw `RangeError` in an ECMA-402 implementation. The PHP manual also documents a PHP-side locale length ceiling of 80, which is not an ECMA-402 limit. [`Locale::canonicalize()`](https://www.php.net/manual/en/locale.canonicalize.php), [intl constants including `INTL_MAX_LOCALE_LEN`](https://www.php.net/manual/en/intl.constants.php)
- PHP is linked to whatever ICU a build supplies. PHP 8.4 accepts ICU >=50.1; PHP 8.5 raises that floor to ICU >=57.1. Neither pins one ICU version, and PHP exposes `INTL_ICU_VERSION`, `INTL_ICU_DATA_VERSION`, the Unicode version, and ICU's tzdata version precisely because those versions vary. [PHP 8.4 build macro](https://github.com/php/php-src/blob/PHP-8.4/build/php.m4#L1681-L1694), [PHP 8.5 build macro](https://github.com/php/php-src/blob/PHP-8.5/build/php.m4#L1638-L1651), [intl constants](https://www.php.net/manual/en/intl.constants.php), [`IntlChar::getUnicodeVersion()`](https://www.php.net/manual/en/intlchar.getunicodeversion.php), [`IntlTimeZone::getTZDataVersion()`](https://www.php.net/manual/en/intltimezone.gettzdataversion.php)
- ICU releases update Unicode and CLDR data (for example ICU 72 uses Unicode 15 and CLDR 42, while later releases carry later data). Consequently, accepting a broad ICU range makes data-dependent outputs non-deterministic even when all hosts are conformant. [ICU release table](https://unicode-org.github.io/icu/download/)
- The compact source data exist upstream as CLDR supplemental and per-locale files. For scale, current unminified CLDR JSON shows roughly 215 KB for likely subtags and 11 KB for week data; generated PHP tables can discard JSON structure and unsupported fields. [CLDR likely-subtags JSON](https://github.com/unicode-org/cldr-json/blob/main/cldr-json/cldr-core/supplemental/likelySubtags.json), [CLDR week-data JSON](https://github.com/unicode-org/cldr-json/blob/main/cldr-json/cldr-core/supplemental/weekData.json)

## Capability matrix

"Pinned" below means generated from the exact Unicode/CLDR/IANA inputs named by a release. Footprint is a planning class, not a package-size commitment: tiny (<50 KB source JSON), small (<250 KB), medium (<1 MB), and large (multi-MB or a full ICU-data dependency) before project-specific generation/compression.

| Capability | Available backend by runtime | Spec-layer fidelity and checks | Fallback complexity / footprint | Maintenance burden |
|---|---|---|---|---|
| BCP-47 structural validation | All PHP versions: userland ASCII tokenizer/parser. `ext-intl` has no faithful single-call validator. | **Faithful only in userland** against the pinned ECMA-402/UTS #35 grammar. Reject `_`, duplicate variants/singletons, malformed `-u-`/`-t-`, private-use-only input, and trailing garbage before calling ICU. Do not use `Locale::canonicalize()` as validation. | Medium algorithm; no locale data, only grammar/constants. | Medium: track grammar/spec changes and translated Test262 invalid-tag cases. |
| Canonicalization | All: userland syntax ordering plus pinned CLDR alias and BCP-47 key/type data. `Locale::canonicalize()` is a useful differential oracle only. | **Faithful with pinned tables.** Must case subtags, sort variants/extensions/attributes/fields, remove `true` Unicode values, apply language/script/territory/variant and key/type aliases, and emit hyphens. | High algorithm; small-to-medium alias/key/type tables. Current CLDR publishes `aliases.json` plus BCP-47 data. | High: regenerate and diff at baseline upgrades; run canonicalization Test262. |
| Unicode extensions and option precedence | All: userland parsed value object. `Locale::getKeywords()` exposes ICU's transformed keyword view, not the original ordered `u`/`t`/other/private-use structure. | **Faithful only in userland.** Preserve all extension sequences, canonicalize ordering, expose `ca`, `co`, `hc`, `kf`, `kn`, `nu`, `fw`, `rg`, `sd`, and `tz` according to the baseline, and apply constructor-option precedence. | High algorithm; tiny key metadata plus canonicalization tables. | High but stable; this is shared infrastructure for every method. |
| Add/remove likely subtags (`maximize`/`minimize`) | PHP 8.5 + intl: `Locale::addLikelySubtags()` / `minimizeSubtags()`; PHP 8.2–8.4 or no intl: userland algorithm + table. | **Host-snapshot fidelity** through PHP 8.5 after converting ICU syntax; **release fidelity** only with pinned table/algorithm. Check `extension_loaded('intl') && method_exists(Locale::class, 'addLikelySubtags')`, but do not switch observable output on that check. | Medium algorithm; small current source table (~215 KB JSON). | Medium: regenerate on baseline change and differential-test ICU when present. |
| Text direction (`getTextInfo`) | PHP 8.5 + intl: `Locale::isRightToLeft()`; older/no intl: maximize with pinned likely-subtags, then pinned direction/script metadata or CLDR layout data. | PHP helper follows the dominant/likely script and CLDR script metadata, matching the intended result closely, but remains host-data-dependent. **Release fidelity** requires pinned data and `{direction: 'ltr'\|'rtl'}` shaping. Check `method_exists(Locale::class, 'isRightToLeft')`. | Low algorithm once maximize exists; tiny direction table, or larger per-locale layout extraction. | Low-medium. ICU documents unknown likely script as LTR; lock that fallback to the chosen spec baseline. |
| Calendars (`getCalendars`) | All supported PHP + intl: `IntlCalendar::getKeywordValuesForLocale('calendar', ..., true)`; no intl: pinned CLDR calendar preferences and supported-calendar policy. | **Close host-snapshot backend**, not drop-in: honor explicit `ca` first, map ICU legacy values such as `gregorian` to canonical BCP-47 values, enforce ECMA availability/alias constraints, and sort/shape exactly. Use `class_exists(IntlCalendar::class)`; its API is present throughout 8.2–8.5. | Low-medium algorithm; tiny `calendarPreferenceData` plus supported-value set. | Medium: keep the library's declared supported calendars consistent with its spec-layer result. |
| Collations (`getCollations`) | No direct PHP enumeration API. `Collator` can instantiate a requested collation; `ResourceBundle` access would depend on ICU internal bundle layout. No intl: pinned generated availability table. | **Not faithful from public ext-intl alone.** Implement from pinned per-locale Collator `co` data and canonical supported values; explicit `co` returns its singleton. Avoid undocumented ResourceBundle paths as a release contract. | High generation; medium-to-large if every locale tailoring is shipped, smaller if generated availability lists only. | High: collations and aliases change, and the answer must match the library's declared Collator availability policy. |
| Hour cycles (`getHourCycles`) | All + intl: `IntlDatePatternGenerator::getBestPattern('j')` can infer one preferred cycle; no public PHP API returns the full preference list. No intl: pinned CLDR `timeData`. | **Partial** via pattern inference; it cannot recover the full preference ordering. Faithful path uses explicit `hc` singleton or pinned region/language time data and ECMA fallback (`h23`). Check `class_exists(IntlDatePatternGenerator::class)` only for diagnostics. | Low algorithm; tiny `timeData` extraction. | Low-medium. |
| Numbering systems (`getNumberingSystems`) | All + intl: `NumberFormatter` can reveal formatting behavior but has no direct Locale-info operation. `ResourceBundle` paths are internal. No intl: pinned locale default-numbering-system data plus BCP-47 supported values. | **Do not infer from digits**: several numbering systems can share digits. Faithful result is explicit `nu`, otherwise the first supported `nu` entry for the matched NumberFormat locale, with ECMA fallback `latn`. | Medium lookup; small global metadata plus medium per-locale defaults. | Medium: coordinate with the library's NumberFormat availability policy. |
| Time zones (`getTimeZones`) | All + intl: `IntlTimeZone::createTimeZoneIDEnumeration(TYPE_CANONICAL, $region)` and canonical-ID helpers; PHP 8.4+ with ICU >=74 also has `getIanaID()`; all PHP without intl: `DateTimeZone::listIdentifiers(PER_COUNTRY, $region)` from PHP's tzdb. Pinned IANA/CLDR mapping is the deterministic backend. | **Close but not sufficient from either host list.** The Locale-info algorithm requires an explicit region and returns `undefined` without one; it does not infer one through likely subtags. Current ECMA-402 also has precise primary/link exceptions, casing, country association, `UTC`, and rename behavior. Apply lexicographic ordering from the pinned baseline. Feature-check `IntlTimeZone` and `getIanaID` separately; record `getTZDataVersion()` when used diagnostically. | Medium-high algorithm; medium zone/country/link tables, not transition data because this method returns IDs only. | High: IANA updates frequently and ECMA's primary-ID policy evolves. |
| Week information (`getWeekInfo`) | All + intl: `IntlCalendar` exposes first day, minimal days, and weekday/weekend/transition classification; no intl: pinned CLDR `weekData`. | **Close host-snapshot backend.** The current Locale-info result uses `firstDay` and `weekend`; `getMinimalDaysInFirstWeek()` is a useful diagnostic/older-proposal primitive but is not part of that result. Convert ICU Sunday=1…Saturday=7 to ISO Monday=1…Sunday=7, enumerate weekend days, and apply ECMA `rg`/`fw` precedence yourself. The PHP APIs predate 8.2. | Low-medium algorithm; tiny current source (~11 KB JSON). | Low-medium: regenerate and test region/override cases. |
| ICU errors and fallback | All + intl: nullable/`false` returns, global/object error codes, optional warnings, optional `IntlException`; PHP 8.5 modernizes call-site attribution, wraps some date exceptions, rejects NUL in Locale arguments, and deprecates `intl.error_level`. No intl: library exceptions only. | **Never expose backend behavior directly.** Validate first, translate backend failure to the spec layer's defined exception, and ignore ICU fallback warnings as errors only when the algorithm requires exact availability. Feature checks must happen at backend construction, not leak into public semantics. | Medium adapter; no data. | Medium: test with `intl.use_exceptions` both on/off and with PHP 8.4/8.5 because error details changed. |
| Cross-version determinism | Pinned generated data + userland algorithms on every runtime. ICU/version constants are diagnostics and differential-test labels. | **Deterministic only when pinned.** ECMA-402 allows implementation-dependent locale data, so two ICU-backed answers may each conform while differing. The project's release reproducibility is stricter: one package release should produce one answer. | Generator/build cost medium-high; runtime data is the sum above. | High at baseline upgrades, low between them. Store upstream versions/checksums and golden vectors. |

The normative method shapes and precedence rules above come from the current Locale abstract operations: [calendars](https://tc39.es/ecma402/#sec-calendars-of-locale), [collations](https://tc39.es/ecma402/#sec-collations-of-locale), [hour cycles](https://tc39.es/ecma402/#sec-hour-cycles-of-locale), [numbering systems](https://tc39.es/ecma402/#sec-numbering-systems-of-locale), [time zones](https://tc39.es/ecma402/#sec-time-zones-of-locale), [text info](https://tc39.es/ecma402/#sec-text-info-of-locale), and [week info](https://tc39.es/ecma402/#sec-week-info-of-locale). ECMA-402 explicitly lists those per-locale values among its implementation dependencies. [ECMA-402 implementation dependencies](https://tc39.es/ecma402/#implementation-dependencies)

The PHP host primitives cited in the matrix are documented here: [`IntlCalendar::getKeywordValuesForLocale()`](https://www.php.net/manual/en/intlcalendar.getkeywordvaluesforlocale.php), [`IntlCalendar::getFirstDayOfWeek()`](https://www.php.net/manual/en/intlcalendar.getfirstdayofweek.php), [`IntlCalendar::getDayOfWeekType()`](https://www.php.net/manual/en/intlcalendar.getdayofweektype.php), [`IntlCalendar::getWeekendTransition()`](https://www.php.net/manual/en/intlcalendar.getweekendtransition.php), [`IntlDatePatternGenerator::getBestPattern()`](https://www.php.net/manual/en/intldatepatterngenerator.getbestpattern.php), [`IntlTimeZone::createTimeZoneIDEnumeration()`](https://www.php.net/manual/en/intltimezone.createtimezoneidenumeration.php), and [`IntlTimeZone::getCanonicalID()`](https://www.php.net/manual/en/intltimezone.getcanonicalid.php). ICU's corresponding APIs confirm these are views over ICU data. [ICU calendar/time-zone C API](https://unicode-org.github.io/icu-docs/apidoc/released/icu4c/ucal_8h.html)

PHP's configurable intl error surface and the 8.5 changes are documented in [`intl` runtime configuration](https://www.php.net/manual/en/intl.configuration.php), [PHP 8.5 incompatible changes](https://www.php.net/manual/en/migration85.incompatible.php), [PHP 8.5 deprecations](https://www.php.net/manual/en/migration85.deprecated.php), and [PHP 8.5 other changes](https://www.php.net/manual/en/migration85.other-changes.php). ICU warning codes pass `U_SUCCESS()` and are explicitly discouraged as a durable information channel. [ICU error-code guidance](https://unicode-org.github.io/icu/userguide/dev/codingguidelines#details-about-icu-error-codes)

Relevant pinned-data inputs include CLDR [aliases](https://github.com/unicode-org/cldr-json/blob/main/cldr-json/cldr-core/supplemental/aliases.json), [likely subtags](https://github.com/unicode-org/cldr-json/blob/main/cldr-json/cldr-core/supplemental/likelySubtags.json), [calendar preferences](https://github.com/unicode-org/cldr-json/blob/main/cldr-json/cldr-core/supplemental/calendarPreferenceData.json), [hour-cycle preferences](https://github.com/unicode-org/cldr-json/blob/main/cldr-json/cldr-core/supplemental/timeData.json), [numbering systems](https://github.com/unicode-org/cldr-json/blob/main/cldr-json/cldr-core/supplemental/numberingSystems.json), [week data](https://github.com/unicode-org/cldr-json/blob/main/cldr-json/cldr-core/supplemental/weekData.json), per-locale layout/number/collation data, CLDR BCP-47 key/type data, and IANA tzdb. UTS #35 defines locale orientation and the meaning of `characterOrder`. [UTS #35 layout elements](https://unicode.org/reports/tr35/tr35-general.html#Layout_Elements)

## Version matrix

| Runtime | Relevant direct helpers | What still must be owned by the library |
|---|---|---|
| PHP 8.2 + intl | Existing `Locale` parser/canonicalizer facade; `IntlCalendar`, `IntlTimeZone`, `IntlDatePatternGenerator`, `Collator`, `NumberFormatter`, `ResourceBundle`; ICU >=50.1 | Strict grammar, spec canonicalization/extensions, maximize/minimize, direction, every Locale-info method's shaping/precedence, pinned data, error normalization. |
| PHP 8.3 + intl | Same relevant surface as 8.2. | Same. No material capability difference found. |
| PHP 8.4 + intl | Same algorithms; typed intl class constants; adds `IntlTimeZone::getIanaID()` when built with ICU >=74. ICU floor remains 50.1, so the new method itself needs a feature check. | Same semantic core. The IANA-ID helper is useful normalization glue on qualifying builds, not a Locale-info backend. |
| PHP 8.5 + intl | Adds likely-subtag and direction helpers; ICU minimum becomes 57.1; Locale methods reject NUL; intl error attribution changes and `intl.error_level` is deprecated. | Still owns strict grammar/canonicalization/extensions, all seven information-method contracts, pinned data/determinism, and error normalization. |
| PHP 8.2–8.5 without intl | PHP strings/arrays/JSON and core `DateTimeZone` only. | Entire locale semantic core and data. Core tzdb is useful only if the chosen release contract permits host-dependent time-zone lists; otherwise ship the ID mapping too. |

## Recommended backend contract

1. Make the reference backend pure PHP 8.2 with generated immutable tables tied to the conformance baseline. It defines every observable result.
2. Load `ext-intl` behind an internal capability object. Use explicit checks (`extension_loaded`, `class_exists`, `method_exists`) rather than `PHP_VERSION_ID`, because extensions can be omitted and distribution builds vary.
3. Permit ICU acceleration only where the result is data-independent or verified against the pinned answer. Never select host ICU merely because PHP 8.5 exposes a convenient method.
4. Expose a diagnostic fingerprint—not semantic branching—containing PHP version, intl presence, `INTL_ICU_VERSION`, `INTL_ICU_DATA_VERSION`, Unicode version, and ICU tzdata version.
5. Generate compact, operation-specific PHP maps rather than ship full CLDR/ICU. Collation availability and time-zone primary-ID policy are the two likely largest/most maintenance-heavy tables; prototype their generated sizes before final package budgeting.
6. Test the same golden vectors in a matrix of PHP 8.2, 8.3, 8.4, and 8.5, each with intl on and off. Add differential jobs against at least the minimum and a recent ICU, but treat differences as review signals rather than automatic changes to released semantics.

## Runnable probe

Run this unchanged on each target image with `php probe.php`. It is deliberately observational: it does not declare ICU's output to be the conformance oracle.

```php
<?php
declare(strict_types=1);

$hasIntl = extension_loaded('intl');
$row = [
    'php' => PHP_VERSION,
    'intl' => $hasIntl,
    'icu' => $hasIntl && defined('INTL_ICU_VERSION') ? INTL_ICU_VERSION : null,
    'icuData' => $hasIntl && defined('INTL_ICU_DATA_VERSION') ? INTL_ICU_DATA_VERSION : null,
    'coreTzData' => function_exists('timezone_version_get') ? timezone_version_get() : null,
    'unicode' => $hasIntl && class_exists(IntlChar::class)
        ? implode('.', IntlChar::getUnicodeVersion())
        : null,
    'tzData' => $hasIntl && class_exists(IntlTimeZone::class)
        ? IntlTimeZone::getTZDataVersion()
        : null,
];

foreach ([
    'canonicalize' => [Locale::class, 'canonicalize'],
    'likely' => [Locale::class, 'addLikelySubtags'],
    'direction' => [Locale::class, 'isRightToLeft'],
    'calendarInfo' => [IntlCalendar::class, 'getKeywordValuesForLocale'],
    'timeZoneInfo' => [IntlTimeZone::class, 'createTimeZoneIDEnumeration'],
    'ianaId' => [IntlTimeZone::class, 'getIanaID'],
    'hourPattern' => [IntlDatePatternGenerator::class, 'getBestPattern'],
] as $name => $callable) {
    $row['features'][$name] = $hasIntl
        && class_exists($callable[0])
        && method_exists($callable[0], $callable[1]);
}

if ($hasIntl) {
    foreach (['en-US', 'EN_us', 'i-klingon', 'x-private', 'en--US',
              'en-US-garbage!', 'de-u-kn-true-ca-gregory'] as $tag) {
        $row['canonicalize'][$tag] = Locale::canonicalize($tag);
    }

    if ($row['features']['likely']) {
        $row['likely']['zh-TW'] = Locale::addLikelySubtags('zh-TW');
        $row['likely']['sr'] = Locale::addLikelySubtags('sr');
    }
    if ($row['features']['direction']) {
        $row['direction'] = ['ar' => Locale::isRightToLeft('ar'),
                             'en-Hebr' => Locale::isRightToLeft('en-Hebr'),
                             'fa-Cyrl' => Locale::isRightToLeft('fa-Cyrl')];
    }
    if ($row['features']['calendarInfo']) {
        $row['calendars']['fa-IR'] = iterator_to_array(
            IntlCalendar::getKeywordValuesForLocale('calendar', 'fa-IR', true),
            false
        );
        foreach (['en-US', 'en-US-u-fw-mon', 'en-US-u-rg-gbzzzz'] as $locale) {
            $calendar = IntlCalendar::createInstance('UTC', $locale);
            $row['week'][$locale]['first'] = $calendar->getFirstDayOfWeek();
            $row['week'][$locale]['minimalDays'] = $calendar->getMinimalDaysInFirstWeek();
            foreach (range(IntlCalendar::DOW_SUNDAY, IntlCalendar::DOW_SATURDAY) as $day) {
                $row['week'][$locale]['dayTypes'][$day] = $calendar->getDayOfWeekType($day);
            }
        }
    }
    if ($row['features']['timeZoneInfo']) {
        $zones = IntlTimeZone::createTimeZoneIDEnumeration(IntlTimeZone::TYPE_CANONICAL, 'AT');
        $row['timeZones']['AT'] = $zones === false ? false : iterator_to_array($zones, false);
    }
    if ($row['features']['hourPattern']) {
        $row['hourPattern']['en-US'] = (new IntlDatePatternGenerator('en-US'))->getBestPattern('j');
        $row['hourPattern']['de-AT'] = (new IntlDatePatternGenerator('de-AT'))->getBestPattern('j');
    }
}

echo json_encode($row, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), PHP_EOL;
```

For the validation gap, compare the `canonicalize` rows with this ECMA-402 probe in Node or a browser console:

```js
for (const tag of ['en-US', 'EN_us', 'i-klingon', 'x-private', 'en--US',
                   'en-US-garbage!', 'de-u-kn-true-ca-gregory']) {
  try { console.log(tag, '=>', new Intl.Locale(tag).toString()); }
  catch (error) { console.log(tag, '=>', error.name); }
}
```

On the research host, PHP 8.3.20/ICU 72.1 accepted the two malformed tags noted above and converted `de-u-kn-true-ca-gregory` to ICU form `de@calendar=gregorian;colnumeric=yes`. That ICU build ignored `u-fw-mon` when constructing `IntlCalendar`, while `u-rg-gbzzzz` changed the first day/minimal-days result to Great Britain's values. Node 22.12.0/ICU 76.1 rejected the malformed and non-Unicode-locale inputs and returned `de-u-ca-gregory-kn` for the valid extension example. These observations demonstrate the adapter requirement; they are not substitutes for the pinned Test262 corpus.

## Final viability answers

- **Lower PHP minimum to 8.2:** yes. Nothing introduced in PHP 8.3, 8.4, or 8.5 eliminates the need for the userland semantic core, and the new 8.5 helpers are reproducible from small pinned data. Choose a higher minimum only for unrelated language/tooling ergonomics.
- **Make `ext-intl` optional:** yes, if packaged pinned data are unconditional and intl is an invisible optimization/cross-check. No, if absence removes methods, changes successful values, or changes public error behavior.
- **Expose a reduced feature set:** only as a deliberately non-conformant or porcelain-only product. It cannot retain the repository's defined claim that the complete applicable spec layer is conformant.
- **Use host ICU as the only backend:** no for a reproducible release. It gives broad functionality at low implementation cost, but validation/canonicalization mismatch, missing direct information APIs, error-mode variation, and moving data prevent one stable cross-version contract.

This recommendation does not require bundling all ICU data. A focused generated snapshot is reasonable in cost for validation, aliases, extensions, likely subtags, direction, calendars, hour cycles, numbering defaults, and week data. The next planning uncertainty is quantitative: generate the collation-availability and primary-time-zone tables from one candidate baseline and measure compressed Composer-package size before fixing the final data budget.
