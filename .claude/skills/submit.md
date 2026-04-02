Run the full pre-submit quality workflow for the current changes before committing. Follow these steps in order:

## Step 1 — Identify changes

Run `git diff HEAD` and `git status` to see what has changed. Classify:
- **Backend** — any `.php` file changed
- **Frontend** — any `.twig`, `assets/controllers/*.js`, or `assets/styles/` file changed
- **Both** — mixed changes

Summarise what changed in 2-3 sentences for context.

## Step 2 — Run the tester

Invoke the **tester** agent to run PHPStan, PHPCS, and unit tests.

- If **FAIL**: show the failures, fix them using the **backend-engineer** agent, then **restart from Step 2**.
- If **PASS**: continue.

## Step 3 — Run the appropriate reviewer(s)

Based on what changed in Step 1 — launch in parallel where applicable:
- Backend changes → invoke **backend-reviewer**
- Frontend changes → invoke **frontend-reviewer**
- Both → invoke **backend-reviewer** and **frontend-reviewer** in parallel

Pass each reviewer the full `git diff HEAD` output and the change summary from Step 1.

If any reviewer returns **FAIL** (CRITICAL or MAJOR issues):
- List all issues clearly
- Fix them using the **backend-engineer** or **frontend-engineer** agent as appropriate
- **Restart from Step 2** (re-run tests and reviewers after fixes)

## Step 4 — Show combined verdict and ask to commit

Print a summary:

```
## Submit Report

### Changes
<brief summary>

### Tests: PASS
### Backend Review: PASS | PASS WITH NOTES | N/A
### Frontend Review: PASS | PASS WITH NOTES | N/A

### Non-blocking notes
<MINOR/NOTE items from reviewers, or "None">

### Overall: READY TO COMMIT

Shall I proceed with the commit? (yes / no / show diff)
```

Wait for explicit user confirmation. Do not commit automatically.

## Step 5 — Commit (only after user says yes)

Stage the relevant files and create a commit following the project convention:
- Prefix: `[FTR]` feature, `[FIX]` bug fix, `[REF]` refactor, `[TST]` tests, `[CHO]` chore
- Message should explain *why*, not just *what*
- Co-Authored-By: Claude Sonnet 4.6 <noreply@anthropic.com>

Do not push — leave that to the user.
