# PHPUnit integration for generated Test262 scripts

Issue #48 requires every translated fixture to remain an independently addressable PHPUnit result without generating one `TestCase` subclass per fixture. The repository uses PHPUnit 11.5.

## APIs investigated

PHPUnit's documented extension facade registers event subscribers and tracers and can replace output or require coverage collection. It does not expose an API for an extension to add tests or suites during discovery. The documented test model remains `TestCase` methods and attributes. Although PHPUnit has suite objects internally, using runner internals to inject dynamic tests would not be a documented, stable extension point. See [Extending PHPUnit 11.5](https://docs.phpunit.de/en/11.5/extending-phpunit.html) and [Writing Tests for PHPUnit 11.5](https://docs.phpunit.de/en/11.5/writing-tests-for-phpunit.html).

Named data providers are public and documented. Each named data set is counted and reported as its own test, and the name is retained in normal CLI output. PHPUnit's `--filter` option selects a named data set through its full test identity, while `--log-junit` records that same identity for machine-readable reporting. See [the PHPUnit 11.5 command-line runner](https://docs.phpunit.de/en/11.5/textui.html).

Calendrics uses the same basic boundary: one handwritten data-provider runner recursively discovers generated PHP scripts and requires one script per invocation. See [Calendrics `RunnerTest.php`](https://github.com/MidnightDesign/calendrics/blob/master/tests/Test262/RunnerTest.php).

## Decision

Use one handwritten `RunnerTest` with a named data provider. Its data-set key is the stable upstream-relative `.js` fixture path, and its scalar value is the generated `.php` path. This is the simplest documented PHPUnit mechanism that provides distinct CLI and JUnit identities, direct filtering, failure isolation, and normal spec-layer coverage collection.

Locale's runner is stricter than the Calendrics prior art. Before yielding any tests, it compares discovered scripts with translated conformance evidence, rejects duplicate source identities, and rejects missing or stale scripts. Generated syntax errors and escaping spec-layer errors remain PHPUnit errors rather than becoming incomplete evidence.
