# Issue tracker: GitHub

Issues and planning artifacts for this repository live in the private GitHub repository `MidnightDesign/locale-php`. Use the `gh` CLI for all operations and pass `--repo MidnightDesign/locale-php` when the remote cannot be inferred.

## Conventions

- Create issues with `gh issue create`.
- Read issues with `gh issue view <number> --comments`, including labels when relevant.
- List issues with `gh issue list` and JSON output for filtering.
- Comment with `gh issue comment <number>`.
- Apply or remove labels with `gh issue edit`.
- Close resolved issues with `gh issue close`.

GitHub shares one number space across issues and pull requests. Resolve an ambiguous number with `gh pr view <number>` and fall back to `gh issue view <number>`.

## Pull requests as a triage surface

External pull requests are not a request surface for triage.

## Wayfinding operations

- The canonical map is the issue labelled `wayfinder:map`.
- Tickets are GitHub sub-issues of the map and carry one of `wayfinder:research`, `wayfinder:prototype`, `wayfinder:grilling`, or `wayfinder:task`.
- Add a sub-issue with `POST repos/MidnightDesign/locale-php/issues/<map>/sub_issues`, passing the child issue's numeric database ID as `sub_issue_id`.
- Represent blocking with GitHub issue dependencies. Add an edge with `POST repos/MidnightDesign/locale-php/issues/<child>/dependencies/blocked_by`, passing the blocker's numeric database ID as `issue_id`.
- The frontier is the first open, unassigned child whose `issue_dependencies_summary.blocked_by` count is zero.
- Claim a frontier ticket before work with `gh issue edit <number> --add-assignee @me`.
- Resolve a ticket by posting the answer as a comment, closing the issue, and appending a linked one-line gist to the map's **Decisions so far** section.
