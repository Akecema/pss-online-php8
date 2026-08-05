# PSS_Online: PHP 5/7 -> PHP 8 Migration Notes

## Summary

- 2,200 PHP files scanned; **923 files** used the `mysql_*` extension
  (removed entirely in PHP 7) - all converted to `mysqli_*`.
- **84 files** used the curly-brace `{}` string/array offset syntax
  (removed in PHP 8.0, e.g. `$str{0}`) - all converted to `[]`.
- **19 files** (vendored TCPDF copies) used `mcrypt_*` (extension removed in
  PHP 7.2) for an optional PDF AES-256 password-protection feature that this
  app does not appear to call anywhere - converted to OpenSSL equivalents.
- **11 files** (vendored TCPDF copies) used `each()` (removed in PHP 8.0) -
  converted to `foreach`.
- 4 files had unguarded `get_magic_quotes_runtime()` / `set_magic_quotes_runtime()`
  calls (removed in PHP 8.0) inside FPDF - removed (these functions have been
  no-ops since PHP 5.4 in practice).
- 1 dead `break;` statement outside any loop/switch in a vendored PHPExcel
  file, which is a hard parse error in PHP 8 - removed.
- Verified: **all 2,200 PHP files pass `php -l` under a real PHP 8.1
  interpreter** (installed in the environment that did this migration
  specifically to check this - see "How this was verified" below).

Everything above is committed to git in this folder, one commit per category,
so you can review each change in isolation (`git log`, `git show <hash>`).

## What was NOT touched

The following folders were excluded from this migrated copy - they hold
uploaded/operational data files, not application code, and had zero `.php`
files in them:

    BOM_detail_update, BOM_detail_upload, BOM_update, BOM_upload,
    FromPortal, FromPortal2, FromPortal3, ToIPOS, set_upload,
    upload_master_MRIN

If the running app writes into any of these at runtime, recreate them
(empty, with appropriate permissions) or mount your originals as Docker
volumes.

## Things flagged but found to be non-issues (false positives / already safe)

- `parse_str()` single-argument, `session_register()`, `ereg()`/`split()`:
  the audit's regex scanner initially flagged these, but on inspection they
  were either already called correctly, in commented-out code, or in
  code paths that are never reached on PHP 8 (guarded by
  `function_exists()`/version checks). No changes were needed.
- `money_format()`: PHPExcel's `Calculation/Functions.php` already ships a
  user-land polyfill (`if (!function_exists('money_format')) { ... }`) for
  exactly this situation - it activates automatically on PHP 8.

## Things worth a second look (deprecation warnings, not fatal errors)

These will still run on PHP 8, but log deprecation notices and may be
removed in a future PHP version:

- `utf8_encode()` / `utf8_decode()` - 23 files (deprecated PHP 8.2)
- `strftime()` / `gmstrftime()` - 1 file (deprecated PHP 8.1)
- Short open tags (`<?` instead of `<?php`) - 14 files (relies on the
  `short_open_tag` ini setting, which most modern hosting defaults to Off)

None of these were fixed automatically since they don't break anything
today; fix opportunistically when you're next in those files.

## How this was verified

The environment used to do this migration had no PHP or Docker installed by
default. To actually verify the fixes (rather than guess), PHP 8.1 was
downloaded and extracted locally (via `apt-get download` + `dpkg-deb -x`,
without root) purely for `php -l` syntax linting - this is real PHP 8.1
parsing every file, not a regex guess. All 2,200 files pass.

What could **not** be verified here, because there was no Docker and no
database access from that environment:

- That the app actually **boots and renders pages** end-to-end (would
  require a running MySQL with your real schema/data plus a browser/HTTP
  client).
- That `mysqli_report(MYSQLI_REPORT_OFF)` plus the `or die(...)` error
  pattern behaves exactly like the old `mysql_*` calls once real queries run
  against real data.
- That the OpenSSL replacement for TCPDF's AES-256 PDF encryption produces
  byte-correct output (this feature does not appear to be used anywhere in
  the app's own code, so it was low-risk to fix "blind", but flag it if you
  ever turn on PDF password protection).

**Recommended next step:** run `docker compose up -d --build` (see
`README-DOCKER.md`) with a copy of your real database, then click through
the main workflows (login, a BOM upload, a PDF/Excel export in each of
planning/prod/supply/qqc/ppc) before treating this as production-ready.
