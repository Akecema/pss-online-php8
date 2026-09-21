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
 * @param list<int|float|string|null> $params
 */
function db_query_params(mysqli $dbc, string $sql, string $types = '', array $params = []): mysqli_result|bool
{
    $stmt = mysqli_prepare($dbc, $sql);
    if ($stmt === false) {
        return false;
    }
    if ($params !== []) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return false;
    }
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    return $result === false ? true : $result;
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
