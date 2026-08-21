<?php


/**
 * include/config.php
 * Part of: Shared includes/config
 * Filename suggests: config
 *
 * Behavior: no form submission, file upload, or export detected (likely a display/listing page, utility, or bootstrap/include file).
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
// This file contains the database access information. This file also
// establishes a connection to MySQL and selects the database.
//
// Values can be overridden via environment variables (e.g. from docker-compose)
// so the same code runs unmodified in Docker and on the original server.

// Restore legacy mysqli error-handling: PHP 8.1+ defaults to throwing
// mysqli_sql_exception on error. This app expects mysqli_*() to return
// false and to be checked with "or die(...)", as it did under mysql_*.
if (function_exists("mysqli_report")) {
    mysqli_report(MYSQLI_REPORT_OFF);
}

// Set the database access information as constants.
// DB_PASSWORD has no hardcoded fallback on purpose: this app shares its local
// MySQL/MariaDB root account with the sibling i-CHARM project, whose old
// hardcoded password ("!ngre55") leaked into that project's git history and
// was rotated on 2026-08-07 (see i-CHARM's "DB password + SSO JWT secret
// rotation" note). The old value that used to sit here is now stale and was
// never safe to fall back to silently in the first place - failing loudly if
// DB_PASSWORD isn't set is safer than reusing a known-leaked password.
define ("DB_HOST", getenv("DB_HOST") ?: "172.18.1.21");
define ("DB_USER", getenv("DB_USER") ?: "root");
if (!getenv("DB_PASSWORD")) {
    // Covers both "unset" (getenv() returns false) and "set but empty"
    // (e.g. docker-compose substituting a blank string for a missing .env
    // value) - either way, don't proceed without a real password.
    die("Configuration error: DB_PASSWORD environment variable is not set. See .env.example.");
}
define ("DB_PASSWORD", getenv("DB_PASSWORD"));
define ("DB_NAME", getenv("DB_NAME") ?: "mrin_project_ipsb");

// Make the connection and then select the database.
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ("Could not connect to MySQL :".mysqli_error($dbc));
$dbs = mysqli_select_db($dbc, DB_NAME) or die ("Could not select the database :".mysqli_error($dbc));
mysqli_set_charset($dbc, 'utf8');
