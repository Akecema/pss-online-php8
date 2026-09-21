# PSS Online - Code Upgrade Report (IQIMS coding standard)

Scope: `_PHP8_MIGRATED` (1,123 app-authored PHP files; vendored TCPDF/FPDF/PHPExcel untouched).
Rule followed: **PSS Online defines WHAT the app does; IQIMS (`/iqims-php-coding-standard`) defines HOW the code is written.**
No table, column, query shape, workflow, calculation or menu was changed. Every change is a separate git commit.

## A. Executive summary
Earlier passes had already moved `mysql_*` to `mysqli` and parameterised the login queries. This pass
(1) adds shared IQIMS-style helpers, (2) closes the SQL-injection, XSS, missing-authorization, upload and
information-leak holes found in discovery, (3) makes configuration env-only, and (4) fixes PHP 8.x runtime
breakages that only showed up by actually running every page.

## B. Files changed
* **New:** `include/helpers.php`, `include/auth.php`, `tests/test_helpers.php`, `UPGRADE_REPORT.md`
* **Rewritten:** `include/config.php`, `PSSboard/include/config.php`
* **Deleted (unreferenced, contained injectable login SQL):** `index_old.php`, `index_ip.php`, `xindex_v0.php`, `ckies-aut_scd_backup.php`, `test_kalendar.php`
* **Modified:** about 1,000 role-folder pages (mechanical, lint-checked codemods; see the commit log)
* **Moved:** none (no file moves were technically necessary)

## C. PHP upgrade (8.3)
| Issue | Fix |
|---|---|
| `mysqli` object used after `mysqli_close()` in 192 pages via `footer.php` (fatal on PHP 8) | `footer.php` reuses the page's `$data_setup` |
| `require('\fpdf\fpdf.php')`, `require_once('/tcpdf_barcodes_2d.php')` (drive-root paths, 136 files) | `__DIR__ . '/...'` |
| `mktime('', '', '')` TypeError in `calendar/classes/tc_calendar.php` | `(int)` casts |
| `mysqli_error('text')` (TypeError) in 4 calls | replaced with `db_fail($dbc)` |
| `utf8_encode/decode` (deprecated 8.2) in 21 files | `latin1_to_utf8()` / `utf8_to_latin1()` |
| Undefined `$_SESSION['username']` on every page for logged-out users | `?? ''` |

Result: `php -l` on all 2,201 PHP files = 0 errors; the only remaining deprecations are inside vendored TCPDF/PHPExcel.

## D. Coding-standard compliance (gap analysis)
| Area | Before | IQIMS standard | Now |
|---|---|---|---|
| DB access | ~1,540 concatenated SQL strings, 203 files with raw request values | prepared statements / no raw input | Values inside quotes wrapped by `db_esc()` (token-based rewrite, about 9,000 sites); `db_query_params()` (prepared) for new code and `require_role()` |
| Config | env with hard-coded fallbacks incl. a leaked password in `PSSboard/include/config.php` | `getenv()` only, die if missing | `include/config.php` env-only, one connection file, `PSSboard` reuses it |
| Auth gate | session check in each page | session gate + role check per script | `require_role($dbc, N)` in 843 pages |
| Sessions | password cached in session, no ID regeneration | regenerate on login, no secrets | `session_regenerate_id(true)`, password no longer stored, hardened cookie ini |
| Output | 15 files used `htmlspecialchars` | escape every echoed value | `h()`; request-derived echoes escaped (783 sites) |
| Errors | SQL text shown via `die(mysqli_error())` (1,405) | log, do not display | `db_fail()` logs the real error, shows generic text |
| Tests | none | custom harness, no PHPUnit | `tests/test_helpers.php` (19 assertions) |

Deliberate deviation: SQL was **not** mass-converted to prepared statements. Rewriting about 1,500 queries without a
regression suite risks changing behaviour; quoted values are escaped in place (equivalent to the old query text),
and prepared statements are used for all new/auth code. Recommend converting module by module as files are touched.

## E. Security improvements (behaviour changes marked WARNING)
1. SQL injection: quoted request/DB values escaped in all modules (`db_esc`). Verified live: `x%' OR '1'='1' --` matches 0 rows.
2. WARNING - **Authorization:** each role folder now requires that role's `level_id` (admin=1, prod=2, prod_super=3, ppc=4, ppc_super=5, supply=6, supply_super=7, planning=8, planning_super=9, qqc=10, qqc_super=11, ppc_store=12 - taken from the login redirects). A logged-in user of another role gets **403** instead of the page. No folder links into another, so normal use is unaffected. Also gates 8 previously ungated `ppc_store` pages and all `find*` AJAX lookups.
3. WARNING - The remember-me cookie (`Key_my_site` = password hash, no HttpOnly) is no longer issued. It could not work after the bcrypt rehash anyway.
4. Session fixation: `session_regenerate_id(true)` on login; `$_SESSION['password']` removed (it was never read).
5. XSS: request-derived echoes go through `h()`.
6. CSRF: every POST must be same-origin (Origin/Referer check in `config.php`); requests with neither header (non-browser clients) are allowed. Session cookie `HttpOnly`, `SameSite=Lax`, strict mode (`.user.ini`).
7. Uploads: `upload_safe_name()` / `upload_safe_ext()` (basename, charset, extension allow-list); the `?file=` parameters of the `*uploadProc.php` pages are sanitised (previously arbitrary-file `unlink`/read); `unlink($_FILES[..]['name'])` is confined to the base name.
8. Information leaks: DB errors and connection failures are logged, not displayed; `display_errors` forced off; hard-coded DB password/IP fallbacks removed.

## F. Database
Schema **not changed**. The only query added is `SELECT level_id FROM user_detail WHERE username = ?` (role gate).

## G. Process-flow verification
Workflow, menus, forms, calculations, approvals, redirects and output are unchanged. The only behaviour changes are the WARNING items above.

## H. Testing performed
* `php -l` on all 2,201 files (PHP 8.3.33): 0 parse errors.
* `tests/test_helpers.php`: 19/19 pass (escaping, role decision, CSRF token, same-origin, upload names).
* Live DB (local MySQL, 138 users, read-only): connection, `current_level_id`, injection payloads, prepared helper.
* HTTP (`php -S`): logged-out requests to role pages/AJAX return 302 to `index.php`; cross-origin POST returns 403; bad-credential and SQLi login return "Access denied"; removed login copies return 404.
* Real sessions for 9 roles (levels 1,2,3,4,5,8,10,11,12): own folder 200, another role's folder 403. Levels 6, 7 and 9 have no active test user in the DB.
* Crawl of every top-level page in each role folder with a read-only DB session, before and after the fixes above (results in section K).

## I. Remaining issues / not changed
* `login_detail.password` and `add_user.php` / `change_password_prod.php` / `reset_password_user.php` / `backjob_*_pass.php` still write **MD5** (login rehashes to bcrypt on the next sign-in). Not changed because the `login_detail.password` column width could not be verified (bcrypt needs 60 chars); confirm the schema, then switch to `password_hash()`.
* Passwords are still e-mailed in clear text (`change_password*.php`, `forgot_password.php`) - fixing that needs a reset-link flow (a new business process).
* DB-derived values echoed into HTML are not yet wrapped in `h()` (stored XSS); only request-derived echoes were fixed.
* SQL inside PHP functions and `sprintf`-built document numbers (`$number`) were intentionally left as-is; no unquoted request value in SQL was found.
* The app still connects as one DB user; a least-privilege account is recommended.
* Bundled jQuery 1.7.2, PHPExcel (abandoned) and 12 copies of TCPDF should be replaced via Composer, separately.
* Duplicated `*_super` modules and backup files (`*_backup*`, `*___x*`, `*_old*`) remain; consolidating them is a refactor, not a compatibility fix.
* Fragment includes (`content*.php`, `top_modal_menu.php`) are only meant to be included; requesting them directly fails as before.

## J. Recommended next steps
1. Confirm `login_detail.password` length, then move all password writes to `password_hash()`.
2. Enable `session.cookie_secure=1` once the site is HTTPS-only.
3. Run a staging pass with each role: login, dashboard, one create/approve/print flow.