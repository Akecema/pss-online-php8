<?php
/**
 * include/helpers.php
 * Shared output-escaping, SQL-escaping and CSRF helpers (IQIMS style:
 * small, typed, snake_case functions - no classes).
 */

declare(strict_types=1);

/** Escape a value for HTML output. Use on every value echoed into markup. */
function h(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/**
 * Escape a value for use inside a single-quoted SQL string literal.
 * Only for legacy concatenated queries whose shape must not change; new code
 * uses db_query_params() instead.
 */
function db_esc(mysqli $dbc, mixed $value): string
{
    return mysqli_real_escape_string($dbc, (string) $value);
}

/**
 * Run a prepared statement and return the result set (SELECT) or true
 * (INSERT/UPDATE/DELETE). Returns false on failure, like mysqli_query().
 *
 * A write statement is left open until the next call: closing it resets the link's
 * affected_rows to -1, and legacy pages read mysqli_affected_rows($dbc) afterwards.
 *
 * @param list<int|float|string|null> $params
 */
function db_query_params(mysqli $dbc, string $sql, string $types = '', array $params = []): mysqli_result|bool
{
    static $pending = null;
    if ($pending instanceof mysqli_stmt) {
        mysqli_stmt_close($pending);
        $pending = null;
    }
    $stmt = mysqli_prepare($dbc, $sql);
    if ($stmt === false) {
        return false;
    }
    try {
        if ($params !== []) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
    } catch (ArgumentCountError | ValueError $e) {
        error_log('PSS Online SQL bind error [' . ($_SERVER['SCRIPT_NAME'] ?? 'cli') . ']: ' . $e->getMessage());
        mysqli_stmt_close($stmt);
        return false;
    }
    if (!mysqli_stmt_execute($stmt)) {
        // Execute errors live on the statement, not the link, so db_fail($dbc) would not see them.
        error_log('PSS Online SQL error [' . ($_SERVER['SCRIPT_NAME'] ?? 'cli') . ']: ' . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return false;
    }
    $result = mysqli_stmt_get_result($stmt);
    if ($result === false) {
        $pending = $stmt;
        return true;
    }
    mysqli_stmt_close($stmt);
    return $result;
}
/** Per-session CSRF token, created on first use. */
function csrf_token(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Hidden input to drop inside any state-changing POST form. */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . h(csrf_token()) . '">';
}

/** True when the submitted token matches the session token. */
function csrf_valid(mixed $submitted): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $expected = $_SESSION['csrf_token'] ?? '';
    return $expected !== '' && is_string($submitted) && hash_equals($expected, $submitted);
}

/** Reject a POST whose CSRF token is missing or wrong. */
function csrf_require(): void
{
    if (!csrf_valid($_POST['csrf_token'] ?? null)) {
        http_response_code(403);
        exit('Forbidden: invalid or missing CSRF token.');
    }
}

/**
 * Same-origin test for state-changing requests (CSRF defence that needs no
 * per-form token, so it also covers AJAX and legacy forms). A request with no
 * Origin and no Referer (non-browser client) is allowed; a browser always sends
 * at least one on a cross-site POST, so a mismatch means a foreign page.
 *
 * @param list<string> $allowedHosts host names this app answers to (no port)
 */
function origin_matches_host(?string $origin, ?string $referer, array $allowedHosts): bool
{
    $source = ($origin !== null && $origin !== '' && $origin !== 'null') ? $origin : $referer;
    if ($source === null || $source === '') {
        return $origin !== 'null';
    }
    $host = parse_url($source, PHP_URL_HOST);
    if (!is_string($host) || $host === '') {
        return false;
    }
    return in_array(strtolower($host), array_map('strtolower', $allowedHosts), true);
}

/** Reject a cross-origin POST. Call once from config.php. */
function require_same_origin(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        return;
    }
    $hosts = [];
    foreach (['HTTP_HOST', 'HTTP_X_FORWARDED_HOST', 'SERVER_NAME'] as $key) {
        foreach (explode(',', (string) ($_SERVER[$key] ?? '')) as $part) {
            $part = trim($part);
            if ($part !== '') {
                $hosts[] = preg_replace('/:\d+$/', '', $part);
            }
        }
    }
    if (!origin_matches_host($_SERVER['HTTP_ORIGIN'] ?? null, $_SERVER['HTTP_REFERER'] ?? null, $hosts)) {
        http_response_code(403);
        exit('Forbidden: cross-site request rejected.');
    }
}

/**
 * Sanitise a client-supplied file name for use inside a fixed upload folder:
 * basename only, conservative charset, extension must be in $allowedExt.
 * Exits with 400 otherwise. The browser-supplied MIME type is not trusted.
 *
 * @param list<string> $allowedExt lower-case extensions without the dot
 */
function upload_safe_name(mixed $name, array $allowedExt): string
{
    $base = basename(str_replace(chr(92), '/', (string) $name));
    $base = str_replace('..', '_', (string) preg_replace('/[^A-Za-z0-9._() \-]/', '_', $base));
    $ext = strtolower(pathinfo($base, PATHINFO_EXTENSION));
    if ($base === '' || $base[0] === '.' || !in_array($ext, $allowedExt, true)) {
        http_response_code(400);
        exit('Invalid file name or type.');
    }
    return $base;
}

/** Validated lower-case extension of a client file name (exits with 400 if not allowed). */
function upload_safe_ext(mixed $name, array $allowedExt): string
{
    return strtolower(pathinfo(upload_safe_name($name, $allowedExt), PATHINFO_EXTENSION));
}

/**
 * For "or die(...)" on a failed query: log the real MySQL error server-side and
 * return a generic message, so SQL text, table names and paths never reach users.
 */
function db_fail(mysqli $dbc): string
{
    error_log('PSS Online SQL error [' . ($_SERVER['SCRIPT_NAME'] ?? 'cli') . ']: ' . mysqli_error($dbc));
    return 'A database error occurred. Please try again or contact the System Administrator.';
}

/** ISO-8859-1 -> UTF-8 (replacement for utf8_encode(), deprecated in PHP 8.2). */
function latin1_to_utf8(string $s): string
{
    return mb_convert_encoding($s, 'UTF-8', 'ISO-8859-1');
}

/** UTF-8 -> ISO-8859-1 (replacement for utf8_decode(), deprecated in PHP 8.2). */
function utf8_to_latin1(string $s): string
{
    return mb_convert_encoding($s, 'ISO-8859-1', 'UTF-8');
}

/** Verify a plain password against a stored hash that may be legacy MD5 or bcrypt (password_hash). */
function password_matches(string $plain, string $stored): bool
{
    if (password_get_info($stored)['algo'] !== null) {
        return password_verify($plain, $stored);
    }
    return hash_equals($stored, md5($plain));
}

/**
 * Prepared-statement runner for legacy call sites: every argument is bound as a string,
 * exactly like the quoted literal it replaces ('...'), and null becomes ''.
 * Drop-in for mysqli_query(): returns mysqli_result (SELECT), true (write) or false.
 *
 * @param list<mixed> $args
 */
function db_query_bind(mysqli $dbc, string $sql, array $args = []): mysqli_result|bool
{
    $bound = array_map(static fn($v): string => is_array($v) ? 'Array' : (string) $v, $args);
    return db_query_params($dbc, $sql, str_repeat('s', count($bound)), $bound);
}