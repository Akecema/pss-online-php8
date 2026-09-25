<?php
/**
 * include/config.php
 * Database connection + shared bootstrap, included by every page.
 *
 * All settings come from environment variables (see .env.example); nothing is
 * hard-coded and a missing required value stops the request loudly.
 */

// PHP 8.1+ throws mysqli_sql_exception by default. The whole app checks
// mysqli_*() results with "or die(...)" (legacy mysql_* behaviour), so keep
// errors as return values.
mysqli_report(MYSQLI_REPORT_OFF);

// PHP_SELF carries whatever an attacker appends after the script name (page.php/"><script>...), and the
// pages echo it into form actions and links. SCRIPT_NAME is always just the script's own path.
if (isset($_SERVER['SCRIPT_NAME'])) {
    $_SERVER['PHP_SELF'] = $_SERVER['SCRIPT_NAME'];
}

foreach (['DB_HOST', 'DB_USER', 'DB_PASSWORD', 'DB_NAME'] as $required) {
    if (getenv($required) === false || getenv($required) === '') {
        error_log("PSS Online configuration error: environment variable $required is not set.");
        http_response_code(500);
        exit("Configuration error: $required is not set. See .env.example.");
    }
}

define('DB_HOST', getenv('DB_HOST'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_NAME', getenv('DB_NAME'));
define('DB_PORT', (int) (getenv('DB_PORT') ?: 3306));

$dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
if (!$dbc) {
    // Real reason goes to the server log only, never to the browser.
    error_log('PSS Online database connection failed: ' . mysqli_connect_error());
    http_response_code(500);
    exit('The system is temporarily unavailable. Please contact the System Administrator.');
}
$dbs = true; // kept for pages that still reference it (database is selected by mysqli_connect above)
mysqli_set_charset($dbc, 'utf8');
// The app was written for MySQL 5 and inserts '' into AUTO_INCREMENT ids (55 files); MariaDB's default strict mode
// rejects that. Session-only, so the server setting is untouched.  NO_ENGINE_SUBSTITUTION keeps the one safety net.
mysqli_query($dbc, "SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");

// Shared helpers (h(), db_esc(), db_query_params(), db_fail(), CSRF, uploads).
require_once __DIR__ . '/helpers.php';

if (PHP_SAPI !== 'cli') {
    require_same_origin(); // POSTs must come from this site (CSRF defence)
    require_once __DIR__ . '/csrf.php';
    csrf_bootstrap(); // POSTs need the session-bound token; forms and XHR get it automatically
}
