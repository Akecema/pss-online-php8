<?php


/**
 * ppc_super/test_board.php
 * Part of: PPC module (supervisor/admin tier)
 * Filename suggests: test board
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: material_request.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, config.inc.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
require_once '../include/auth.php';
require_role($dbc, 5);
//include_once ('../classes/paginator.class2.php');
//require_once("../calendar/classes/tc_calendar.php");

$Cdate = date ("l, j F Y ");



// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
?>
<?php
//include "config.inc";

$sql = "SELECT COUNT(*) FROM material_request";
$result = mysqli_query($dbc, $sql) or trigger_error("SQL", E_USER_ERROR);
$r = mysqli_fetch_row($result);
$numrows = $r[0];


$rowsperpage = 10;

$totalpages = ceil($numrows / $rowsperpage);


if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {

$currentpage = (int) $_GET['currentpage'];
} else {

$currentpage = 1;
}


if ($currentpage > $totalpages) {

$currentpage = $totalpages;
} 
if ($currentpage < 1) {

$currentpage = 1;
} 


$offset = ($currentpage - 1) * $rowsperpage;


$sql = "SELECT * FROM material_request LIMIT $offset, $rowsperpage";
$result = mysqli_query($dbc, $sql) or trigger_error("SQL", E_USER_ERROR);


while ($list = mysqli_fetch_array($result)) {

echo $list['id_req'] . " : " . $list['temp_mrin'] . "<br />";
} 


$range = 3;


if ($currentpage > 1) {

echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=1'><<</a> ";

$prevpage = $currentpage - 1;

echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage'><</a> ";

//$next_page_count = ++$currentpage % $totalpages;
//$next_page = $_SERVER['PHP_SELF'] . '?currentpage=' . $next_page_count;

}  // end if $currentpage





for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {

if (($x > 0) && ($x <= $totalpages)) {

if ($x == $currentpage) {

 echo " [<b>$x</b>] ";

} else {

 echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$x'>$x</a> ";
} 


} // end if
} // end for


if ($currentpage != $totalpages) {

$nextpage = $currentpage + 1;

echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage'>Next></a> ";

echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages'>Last>></a> ";
}  // end if

?>
<?php

//$nextpage = "";

header('Refresh: 10; URL='.$_SERVER['PHP_SELF'].'?currentpage='.$nextpage);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

</head>

<body>

</body>
</html>
