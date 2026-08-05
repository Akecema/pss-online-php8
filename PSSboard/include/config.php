<?php

// This file contains the database access information. This files also 
// establishes a connection to MySQL and selects the database.

// Set the database access information as constants.
define ("DB_HOST", "172.18.1.21");
define ("DB_USER", "root");
define ("DB_PASSWORD", "!ngre55");
define ("DB_NAME", "mrin_project_ipsb");

// Make the connection and then select the database.
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ("Could not connect to MySQL :".mysqli_error($dbc));
$dbs = mysqli_select_db($dbc, DB_NAME) or die ("Could not select the database :".mysqli_error($dbc));
mysqli_set_charset($dbc, 'utf8');

?>