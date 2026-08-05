<?php

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
define ("DB_HOST", getenv("DB_HOST") ?: "172.18.1.21");
define ("DB_USER", getenv("DB_USER") ?: "root");
define ("DB_PASSWORD", getenv("DB_PASSWORD") ?: "!ngre55");
define ("DB_NAME", getenv("DB_NAME") ?: "mrin_project_ipsb");

// Make the connection and then select the database.
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ("Could not connect to MySQL :".mysqli_error($dbc));
$dbs = mysqli_select_db($dbc, DB_NAME) or die ("Could not select the database :".mysqli_error($dbc));
mysqli_set_charset($dbc, 'utf8');
