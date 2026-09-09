# Choose the supported platform matrix

Type: grilling
Status: open

## Question

Which PHP versions, operating systems, `ext-intl` configurations, and ICU/CLDR versions must the library support, and may PHP 8.5 be the minimum so `Locale::addLikelySubtags()`, `Locale::minimizeSubtags()`, and `Locale::isRightToLeft()` are available?

The answer must state whether missing or old ICU data is unsupported, tolerated with documented result differences, normalized by bundled data, or detected and rejected at runtime.
