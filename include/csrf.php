<?php
/**
 * include/csrf.php
 * Site-wide CSRF tokens, applied from config.php so every page is covered without editing 500+ forms.
 *
 * - Token = HMAC of the browser's session id, so it needs no session_start() and works with the
 *   pages that start their own session late.
 * - Output filter: every <form method=post> in an HTML response gets a hidden csrf_token input, and a
 *   small script adds an X-CSRF-Token header to same-origin XHR/fetch and a token to forms built in JS.
 * - Every POST that carries a session cookie must present the token (field or header) or gets 403.
 *   A POST with no session cookie has no logged-in session to abuse and is let through (server-to-server calls).
 */

const CSRF_FIELD = 'csrf_token';

/** Secret for the HMAC; CSRF_SECRET if set, otherwise derived from the database password. */
function csrf_secret(): string
{
    $secret = getenv('CSRF_SECRET');
    if (is_string($secret) && $secret !== '') {
        return $secret;
    }
    return hash('sha256', 'csrf-v1|' . (string) (defined('DB_PASSWORD') ? DB_PASSWORD : getenv('DB_PASSWORD')));
}

/** Token that belongs to one session id. Empty when there is no session yet. */
function csrf_token_for_session(string $sessionId): string
{
    return $sessionId === '' ? '' : hash_hmac('sha256', $sessionId, csrf_secret());
}

/** True when $token was issued for $sessionId. */
function csrf_token_matches(string $sessionId, mixed $token): bool
{
    $expected = csrf_token_for_session($sessionId);
    return $expected !== '' && is_string($token) && hash_equals($expected, $token);
}

/**
 * Add the hidden token input to every POST form outside <script> blocks. Forms that post to another
 * site are skipped so the token is never sent there. The input has no quotes so it is safe anywhere.
 */
function csrf_inject_forms(string $html, string $token, string $host = ''): string
{
    if ($token === '' || stripos($html, '<form') === false) {
        return $html;
    }
    $input = '<input type=hidden name=' . CSRF_FIELD . ' value=' . $token . '>';
    $parts = preg_split('#(<script\b.*?</script>)#is', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
    if ($parts === false) {
        return $html;
    }
    foreach ($parts as $i => $part) {
        if ($i % 2 === 1) {
            continue; // a <script> block
        }
        $parts[$i] = preg_replace_callback('#<form\b[^>]*>#i', static function (array $m) use ($input, $host): string {
            $tag = $m[0];
            if (!preg_match('#\smethod\s*=\s*["\']?\s*post\b#i', $tag)) {
                return $tag;
            }
            if (preg_match('#\saction\s*=\s*["\']?\s*(?:https?:)?//([^/"\'\s>?:]+)#i', $tag, $a)
                && strcasecmp($a[1], $host) !== 0) {
                return $tag;
            }
            return $tag . $input;
        }, $part) ?? $part;
    }
    return implode('', $parts);
}

/** Script that sends the token with same-origin XHR/fetch and with forms created or altered in JS. */
function csrf_client_script(string $token): string
{
    return '<script>(function(){var T="' . $token . '",F="' . CSRF_FIELD . '";'
        . 'function same(u){try{return new URL(u,location.href).origin===location.origin}catch(e){return true}}'
        . 'function unsafe(m){return !/^(GET|HEAD|OPTIONS)$/i.test(m||"GET")}'
        . 'var o=XMLHttpRequest.prototype.open,s=XMLHttpRequest.prototype.send;'
        . 'XMLHttpRequest.prototype.open=function(m,u){this._c=[m,u];return o.apply(this,arguments)};'
        . 'XMLHttpRequest.prototype.send=function(){var c=this._c;'
        . 'if(c&&unsafe(c[0])&&same(c[1])){try{this.setRequestHeader("X-CSRF-Token",T)}catch(e){}}return s.apply(this,arguments)};'
        . 'if(window.fetch){var f=window.fetch;window.fetch=function(i,n){n=n||{};'
        . 'var u=typeof i==="string"?i:(i&&i.url),m=n.method||(i&&i.method)||"GET";'
        . 'if(unsafe(m)&&same(u)){n.headers=new Headers(n.headers||(i&&i.headers)||{});n.headers.set("X-CSRF-Token",T)}return f.call(this,i,n)}}'
        . 'function fix(fm){if(!fm||!/^post$/i.test(fm.getAttribute("method")||""))return;'
        . 'var a=fm.getAttribute("action")||"";if(a&&!same(a))return;'
        . 'if(fm.querySelector("input[name="+F+"]"))return;'
        . 'var i=document.createElement("input");i.type="hidden";i.name=F;i.value=T;fm.appendChild(i)}'
        . 'document.addEventListener("submit",function(e){fix(e.target)},true);'
        . 'var sub=HTMLFormElement.prototype.submit;HTMLFormElement.prototype.submit=function(){fix(this);return sub.call(this)};'
        . '})();</script>';
}

/** Insert the client script once, in <head> (or right after <body>). Fragments are left alone. */
function csrf_inject_script(string $html, string $token): string
{
    if ($token === '') {
        return $html;
    }
    $script = csrf_client_script($token);
    if (preg_match('#</head>#i', $html, $m, PREG_OFFSET_CAPTURE)) {
        return substr_replace($html, $script, $m[0][1], 0);
    }
    if (preg_match('#<body\b[^>]*>#i', $html, $m, PREG_OFFSET_CAPTURE)) {
        return substr_replace($html, $script, $m[0][1] + strlen($m[0][0]), 0);
    }
    return $html;
}

/** Session id the request is using: the live session if one was started, else the cookie. */
function csrf_current_session_id(): string
{
    if (session_status() === PHP_SESSION_ACTIVE && session_id() !== '') {
        return session_id();
    }
    $name = session_name();
    return isset($_COOKIE[$name]) && is_string($_COOKIE[$name]) ? $_COOKIE[$name] : '';
}

/** Output filter: only touches HTML responses. */
function csrf_output_filter(string $buffer, int $phase = 0): string|false
{
    foreach (headers_list() as $header) {
        if (stripos($header, 'content-type:') === 0 && stripos($header, 'text/html') === false) {
            return false; // JSON, PDF, CSV ... pass through unchanged
        }
    }
    if ($buffer === '' || str_starts_with($buffer, '%PDF') || str_starts_with($buffer, 'PK')) {
        return false;
    }
    $token = csrf_token_for_session(csrf_current_session_id());
    if ($token === '') {
        return false;
    }
    $host = preg_replace('/:\d+$/', '', (string) ($_SERVER['HTTP_HOST'] ?? ''));
    return csrf_inject_script(csrf_inject_forms($buffer, $token, (string) $host), $token);
}

/** Reject a browser POST whose token is missing or wrong. Call once from config.php. */
function csrf_enforce(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        return;
    }
    $name = session_name();
    $sid = isset($_COOKIE[$name]) && is_string($_COOKIE[$name]) ? $_COOKIE[$name] : '';
    if ($sid === '') {
        return; // no session cookie, nothing a forged request could act on
    }
    $sent = $_POST[CSRF_FIELD] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
    if (!csrf_token_matches($sid, $sent)) {
        http_response_code(403);
        exit('Forbidden: invalid or missing CSRF token. Reload the page and try again.');
    }
}

/** Turn the whole mechanism on. */
function csrf_bootstrap(): void
{
    csrf_enforce();
    ob_start('csrf_output_filter');
}