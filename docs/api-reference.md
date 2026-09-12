# API reference

This is the complete supported public contract. `Midnight\Intl\Locale` is the PHP-native porcelain tier for ordinary application code. `Midnight\Intl\Spec\Locale` is the ECMAScript-compatible tier for consumers that need spec coercion and object behavior. Classes under `Midnight\Intl\Internal` are not public API.

## Porcelain locale

`Midnight\Intl\Locale` is final and immutable. It implements `Stringable` and `JsonSerializable`.

Constructor:

`new Locale(string $tag, ?string $language = null, ?string $script = null, ?string $region = null, ?string $variants = null, ?string $calendar = null, ?string $collation = null, ?string $firstDayOfWeek = null, HourCycle|string|null $hourCycle = null, CaseFirst|string|null $caseFirst = null, ?bool $numeric = null, ?string $numberingSystem = null)`

`$tag` must be a non-empty, structurally valid Unicode locale identifier. Non-null named options replace the corresponding language-identifier component or Unicode extension keyword. `hourCycle` accepts an `HourCycle` case or `h11`, `h12`, `h23`, or `h24`; `caseFirst` accepts a `CaseFirst` case or `upper`, `lower`, or `false`. Open or data-versioned vocabularies remain strings.

Readable, non-writable properties:

| Property | Type |
| --- | --- |
| `baseName`, `language` | `string` |
| `script`, `region`, `variants` | `string|null` |
| `calendar`, `collation`, `firstDayOfWeek`, `numberingSystem` | `string|null` |
| `caseFirst` | `CaseFirst|string|null` |
| `hourCycle` | `HourCycle|string|null` |
| `numeric` | `bool` |

Methods:

| Method | Result and contract |
| --- | --- |
| `Locale::fromSpec(Spec\Locale $locale): Locale` | Creates a porcelain value with the same canonical identifier. |
| `toSpec(): Spec\Locale` | Returns the wrapped initialized spec value. |
| `toString(): string` | Returns the complete canonical Unicode locale identifier. |
| `maximize(): Locale` | Returns a fresh porcelain value with likely subtags added. |
| `minimize(): Locale` | Returns a fresh porcelain value with removable likely subtags omitted. |
| `getCalendars(): non-empty-list<string>` | Returns a fresh preference-ordered calendar list. |
| `getCollations(): list<string>` | Returns a fresh code-unit-sorted collation list. |
| `getHourCycles(): non-empty-list<HourCycle>` | Returns a fresh preference-ordered enum list. |
| `getNumberingSystems(): list<string>` | Returns a fresh one-element numbering-system list. |
| `getTimeZones(): list<string>|null` | Returns fresh sorted primary IDs for an explicit region, or `null` without one. |
| `getTextInfo(): TextInfo` | Returns a fresh immutable text-direction value. |
| `getWeekInfo(): WeekInfo` | Returns fresh immutable first-day and weekend data. |
| `__toString(): string` | Returns the same value as `toString()`. |
| `jsonSerialize(): string` | Serializes as the same canonical string. |

Closed-value and result types:

| Type | Cases or properties |
| --- | --- |
| `CaseFirst: string` | `Upper = 'upper'`, `Lower = 'lower'`, `False = 'false'` |
| `HourCycle: string` | `H11 = 'h11'`, `H12 = 'h12'`, `H23 = 'h23'`, `H24 = 'h24'` |
| `TextDirection: string` | `LeftToRight = 'ltr'`, `RightToLeft = 'rtl'` |
| `final readonly TextInfo` | `public ?TextDirection $direction` |
| `final readonly WeekInfo` | `public int<1, 7> $firstDay`; `public non-empty-list<int<1, 7>> $weekend` |

The identifier may contain a syntactically valid value outside the enums' closed vocabularies. In that case the `caseFirst` or `hourCycle` property preserves the string so explicit tier crossing remains lossless. `getHourCycles()` returns only `HourCycle` cases.

## Spec locale

`Midnight\Intl\Spec\Locale` is extensible and does not implement `Stringable` or `JsonSerializable`.

Constructor:

`new Spec\Locale(mixed $tag, mixed $options = omitted)`

The supported public tag forms are a string, an initialized spec `Locale`, or an object with supported string conversion such as `Stringable`. The options value may be an associative array or plain object. Other non-null scalar values undergo the PHP adaptation of ECMAScript `ToObject`; because they expose none of the named options, they behave as an empty bag. Omitted options are distinct from explicit `null`: omitted options act as an empty bag, while `null` throws the library `TypeError`. Internal Test262 bridge values are test infrastructure, not supported consumer types.

The same twelve property names are readable and non-writable. Their values are direct spec representations: `baseName` and `language` are `string`; `numeric` is `bool`; every other property is `string|null`. String options use ECMAScript `ToString`, `numeric` uses ECMAScript `ToBoolean`, and properties are read lazily in this order: `language`, `script`, `region`, `variants`, `calendar`, `collation`, `firstDayOfWeek`, `hourCycle`, `caseFirst`, `numeric`, `numberingSystem`.

| Method | Spec-tier result |
| --- | --- |
| `toString(): string` | Complete canonical identifier. |
| `maximize(): Spec\Locale` | Fresh base spec `Locale`, even when called on a subclass. |
| `minimize(): Spec\Locale` | Fresh base spec `Locale`, even when called on a subclass. |
| `getCalendars(): non-empty-list<string>` | Fresh string list. |
| `getCollations(): list<string>` | Fresh string list. |
| `getHourCycles(): non-empty-list<string>` | Fresh string list. |
| `getNumberingSystems(): list<string>` | Fresh one-element string list. |
| `getTimeZones(): list<string>|null` | Fresh string list or the PHP adaptation `null` for ECMAScript `undefined`. |
| `getTextInfo(): array{direction: 'ltr'|'rtl'|null}` | Fresh associative record with exactly `direction`. |
| `getWeekInfo(): array{firstDay: int<1, 7>, weekend: non-empty-list<int<1, 7>>}` | Fresh associative record with exactly `firstDay` and `weekend`. |

A subclass must complete the inherited constructor before any locale operation. An uninitialized subclass instance fails brand checks with the library `TypeError`. Subclasses may add declared or dynamic consumer state. Inherited locale property access remains non-writable, but PHP permits a subclass to declare a public property with the same name; that property shadows magic access and can diverge from the private locale state used by inherited operations. Calling `__construct()` again on an initialized object throws and does not mutate it.

## Exceptions

`Midnight\Intl\Exception\LocaleException` is the marker interface for library-originated public failures.

- `Midnight\Intl\Exception\TypeError` extends native `\TypeError`. The spec tier uses it for ECMA type/coercion and receiver-branding failures; both tiers use it for attempts to write locale properties or reinitialize a value.
- `Midnight\Intl\Exception\RangeError` extends native `\RangeException`. It represents structurally invalid locale identifiers and invalid option values.
- Porcelain arguments rejected by PHP's declared parameter types throw native `\TypeError` before library validation.
- Exceptions thrown by consumer property access or conversion hooks in the spec tier propagate unchanged.
- Reading an unknown property raises native `\Error`.

## Tier crossing, comparison, and persistence

Cross explicitly with `Locale::fromSpec()` and `$locale->toSpec()`. The canonical identifier and observable locale value survive a porcelain → spec → porcelain round trip. Crossing from a spec subclass preserves the locale value, not the subclass identity or consumer-added state. The porcelain constructor's `string` parameter rejects a spec object. The spec constructor follows its general object-to-string rules, so it can coerce the porcelain tier because porcelain implements `Stringable`; use `toSpec()` when intentional tier crossing should be explicit and preserve the already initialized spec value.

There is no `equals()` method. PHP `===` compares object identity; compare `toString()` results when canonical locale values must be compared.

Only the porcelain tier supports string casting and JSON serialization, both as the canonical identifier. Native PHP `serialize()` output is not a stable persistence contract. Persist `toString()` and reconstruct the value instead.

Both tiers are supported public API under the same Semantic Versioning boundary. `Midnight\Intl\Internal` has no compatibility promise.
