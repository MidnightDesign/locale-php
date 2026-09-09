# Define the Test262 transpilation policy

Type: grilling
Status: open
Blocked by: 01, 03

## Question

What rules must the Test262-to-PHP pipeline follow so its results are honest, reviewable evidence for the selected conformance promise?

Decide how fixtures are categorized as faithful, semantically adapted, inapplicable, unsupported, or failing; which JavaScript object-model assertions may become PHP reflection assertions; how generated files preserve provenance and license notices; how incomplete translations are prevented from inflating conformance claims; and whether Calendrics’ Acorn emitter should be extracted, forked, or replaced.
