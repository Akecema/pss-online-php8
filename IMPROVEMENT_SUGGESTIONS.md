# PSS_Online: Improvement Suggestions

Compiled while migrating this codebase from PHP 5/7 to PHP 8 (2,200 files
read/touched to some degree). Organized by priority, with concrete examples
from the actual code rather than generic advice. None of these were fixed
automatically - the migration only changed what was needed to run on PHP 8;
everything below is a recommendation for follow-up work.

## Critical - security

**1. SQL injection via unescaped user input, in the authentication flow itself.**
`ckies-aut_frst.php`, `ckies-aut_scd.php`, and `rst-mail.php` build SQL
queries by directly concatenating `$_POST['username']` and the
`ID_my_site`/`Key_my_site` cookies into the query string, e.g.:

    $check = mysqli_query($dbc, "SELECT * FROM user_detail WHERE username = '$username' ...")

Because `$username` here comes straight from a cookie the visitor controls,
anyone can inject SQL by setting a crafted cookie value - no login required
to reach this code path. `change_password.php` and `forgot_password.php`,
by contrast, already escape input correctly via `mysqli_real_escape_string()`
before use, so the fix is to bring the login files up to that same standard
(better: parameterised/prepared `mysqli` statements everywhere, which also
protects against this pattern being reintroduced later). Given 919 files
contain a `SELECT *`-style raw query, a full audit for the same pattern
elsewhere is worth doing, not just these three files.

**2. The "remember me" cookie is a long-lived, stealable credential.**
On successful login, `ckies-aut_scd.php` sets:

    setcookie('Key_my_site', $_POST['pass'], $hour);  // MD5 hash of the password

with no `HttpOnly`, `Secure`, or `SameSite` attributes. `ckies-aut_frst.php`
then treats possession of this cookie as proof of identity - logging the
visitor in with no password check. Anyone who can read this cookie (via
XSS, a shared computer, or plain-HTTP network sniffing) can impersonate the
user for up to an hour with zero further authentication. Recommend
replacing this with a random, single-purpose session token stored server-side
(not the password hash), issued with `HttpOnly; Secure; SameSite=Lax`.

**3. Passwords are hashed with bare, unsalted MD5.**
`ckies-aut_scd.php`, `change_password.php`, and `forgot_password.php` all
use `md5($password)` for storage/comparison. MD5 has no per-user salt and is
fast enough to brute-force at billions of guesses/second on commodity GPUs -
if the `user_detail` table is ever exposed, every password is effectively
public. PHP has `password_hash()`/`password_verify()` built in (bcrypt by
default, salted, tunable cost) - a good candidate for a gradual migration:
detect legacy MD5 hashes at login time, verify against MD5 as today, and
transparently re-hash with `password_hash()` on that successful login.

**4. Plaintext passwords are emailed to users.**
`change_password.php` emails the user's newly-chosen password in plain
text; `forgot_password.php` emails a temporary password (better, since it's
random and short-lived, but still a password in an email). Email is not a
secure channel end-to-end, and this trains users to expect password-bearing
emails, which is exactly the pattern phishing emails imitate. A reset
*link* (single-use, time-limited token) is the standard replacement for
both flows.

**5. No CSRF protection on state-changing forms.**
Login, change-password, forgot-password, and (from a sample of other
modules) most data-entry forms across the app have no CSRF token. Any of
them can be triggered by a malicious third-party page while a user is
logged in. Worth adding a shared CSRF-token helper (generate on session
start, verify on every POST) rather than fixing forms one at a time.

**6. The app connects to MySQL as `root`.**
`include/config.php` (now parameterised, but the original/fallback value is
`root`) uses the database's superuser account for ordinary application
queries. If the app is ever compromised (e.g. via #1 above), the attacker
gets full control of the MySQL instance, not just this app's data. A
dedicated account with grants limited to the `mrin_project_ipsb` database
(and only the privileges actually needed - no `DROP`/`GRANT`, etc.) would
contain the blast radius significantly.

**7. Outdated bundled front-end libraries.**
`js/jquery.min.js` is jQuery **1.7.2**, from 2012. Versions before 3.5.0
have known XSS vulnerabilities in HTML-parsing methods (`.html()`, `.append()`,
etc. with untrusted input) and the whole 1.x line is over a decade past any
security support. The fancyBox/thickbox plugins bundled alongside are from
the same era. These are worth updating independently of the PHP migration.

## High - architecture & maintainability

**8. Whole modules are duplicated wholesale for permission tiers.**
Every functional area ships two near-complete copies of itself - `planning`
/ `planning_super`, `ppc` / `ppc_super`, `prod` / `prod_super`, `qqc` /
`qqc_super`, `supply` / `supply_super` (plus `ppc_store`). A quick diff of
`planning` vs `planning_super` shows dozens of files that are identical or
near-identical apart from a permission check. This means every bug fix,
security patch, or feature has to be applied N times, and it's very easy
for the copies to drift (as the different `tcpdf.php`/`each()` bugs found
during this migration suggest already happened). A single module driven by
a role/permission check (which `user_detail.level_id` already provides)
would cut the codebase size dramatically and remove an entire class of
"fixed it in prod but not prod_super" bugs.

**9. Vendored libraries (TCPDF, FPDF, PHPExcel, PCLZip) are copy-pasted into
every module folder** - 11-12 near-identical copies of `tcpdf.php` alone
were found and fixed individually during this migration (the `each()` /
`mcrypt_*` bugs, for instance, existed in every copy). None of these are
managed by Composer, so there's no way to tell which copy is patched and
which isn't, or to pull in a security update. Moving to a single shared
`vendor/` directory (ideally via Composer - TCPDF, and PhpSpreadsheet as
PHPExcel's replacement, are both available as packages) would fix this at
the root instead of file-by-file.

**10. PHPExcel is an abandoned project.** It was superseded by
[PhpSpreadsheet](https://phpspreadsheet.readthedocs.io/) in 2017 and hasn't
been maintained since; the version bundled here still has the `{}`
string-offset syntax this migration had to fix just to make it parse on
PHP 8, which is a reasonable proxy for how old it is. Worth budgeting a
migration to PhpSpreadsheet (its API is intentionally close to PHPExcel's).

**11. Business logic, SQL, and HTML markup are interleaved in the same
files** throughout the app (e.g. `change_password.php` mixes query
construction, validation, email sending, and the HTML form in one 250-line
file). This makes files hard to test and easy to accidentally break with an
unrelated change. Even a lightweight separation (move DB access into
small per-entity functions/classes, keep templates focused on markup) would
help - a full framework migration isn't necessary to get most of the benefit.

**12. Dead/backup/test files are committed alongside the live app** - 33
files matching `*backup*`, `*_old*`, `*test*` were found (e.g.
`admin/main_backup.php`, `ppc_super/detail_material_request_printing_backup.php`,
`ckies-aut_scd_backup.php`, `index_old.php`, `xindex_v0.php`). Since this
migration set up git for the first time, these are now safely recoverable
from history - they can be deleted from the working tree with much less
risk than before.

**13. No automated tests anywhere in the codebase**, and no CI. Given the
size of this app, even a thin layer of smoke tests (does each module's
entry point return 200 for a logged-in user of the right role?) would catch
a large class of regressions cheaply, and is a natural next step now that
the code runs in Docker and can be scripted against.

## Medium - performance & data access

**14. `SELECT *` is used in 919 files.** Pulling every column when only a
few are used wastes bandwidth/memory at scale and makes it harder to
reason about what a piece of code actually depends on. Not urgent to fix
everywhere, but worth doing opportunistically as files are touched.

**15. Queries inside loops (N+1 pattern)** appear in several files touched
during this migration (e.g. `backjob_clean.php` runs a `DELETE` per row
inside a `while` loop over a `SELECT *` result). For the row counts this
app likely deals with this is probably fine today, but it's worth watching
if data volume grows - these are straightforward to batch into a single
`WHERE id IN (...)` statement.

**16. `sys_setup_maintain` is queried fresh on nearly every single page
load** (via `con-dbcIPSB.php`, `login_lock.php`, `forgot_password.php`,
and others all issuing the same `SELECT * FROM sys_setup_maintain WHERE
status_system = 'AC'`). This table appears to hold rarely-changing site
config (title, logo, base URL) - a good candidate for a short-lived cache
(APCu or even a static in-memory value for the request) rather than a query
per page.

## Low - polish

**17. A handful of files use functions deprecated (but not yet removed) in
PHP 8.1/8.2**: `utf8_encode()`/`utf8_decode()` (23 files), `strftime()` (1
file). These still work today but will need replacing eventually
(`mb_convert_encoding()` and `IntlDateFormatter`/`DateTime::format()`
respectively) - listed in detail in `MIGRATION_NOTES.md`.

**18. Mixed `http://` and `https://` external resource links** (e.g.
Google Fonts loaded over `http://` in `forgot_password.php`) will be
blocked or flagged as mixed content by browsers once the app is served
over HTTPS - worth a global find-and-replace to `https://`.

---

None of the above blocks the PHP 8 migration or the Docker setup from
working - they're prioritized for a follow-up pass, roughly in the order
listed (security items first).
