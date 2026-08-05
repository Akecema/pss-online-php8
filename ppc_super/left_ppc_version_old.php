<?php

/**
 * ppc_super/left_ppc_version_old.php
 * Part of: PPC module (supervisor/admin tier)
 * Filename suggests: left ppc version old
 *
 * Behavior: requires an active login session ($_SESSION['username']).
 * Database tables referenced: user_detail, level_detail, consumable_request.
 * Includes: config.php, paginator.class2.php, tc_calendar.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
    $result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
    $res = mysqli_fetch_array($result2);
	
	
   $query = "SELECT * from level_detail WHERE id_level = '$res[level_id]'";
   $result = mysqli_query($dbc, $query); // Run the query
   $deb = mysqli_fetch_array($result);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Ingress Autoventures Co., Ltd.</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../css/style.css" type="text/css" media="all" />
<link rel="stylesheet" href="../css/left_menu.css" type="text/css" media="all" />

<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
-->
</style></head>
<body>
<p>
  <!-- Header --><!-- End Header -->
</p>
<table width="23%" border="0" cellspacing="5">
  <tr>
    <td><table width="238" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td><div align="left">User :</div></td>
        <td><div align="left"><?php echo $res['user_fullname']; ?> </div></td>
      </tr>
      <tr>
        <td colspan="2"><div align="right"></div></td>
      </tr>
      <tr>
        <td width="44" class="Tcontent"><div align="left">Level :</div></td>
        <td width="171" class="Tcontent"><?php echo $deb["desc_level"]; ?> </td>
      </tr>
    </table>
     <?php
	  
	  $query = "SELECT *, DATE_FORMAT(MR.date_require,'%d-%m-%Y') AS R FROM consumable_request AS MR WHERE MR.status_request = 'Y' AND MR.status_view = 'N' AND (MR.status != 'Close' AND MR.status  != 'Cancel') GROUP BY MR.temp_mrin";
$rs = mysqli_query($dbc, $query);   //run the query.
	    $num_rows = mysqli_num_rows($rs); 

	  ?>  
    <p>&nbsp;</p>
<p>
<div class="menu_simple">
<ul>
<li><a href="main.php" target="mainFrame">Home</a></p></li>
<li><a href="posting_request_all_screen_LCD.php" target="mainFrame">Material Request Board</a></li>
<li><a href="posting_request_all_screen_LCD_consumable.php" target="mainFrame" >Consumable Request Board</a></li>
<li><a href="posting_request_all.php" target="mainFrame">Material Request</a></li>
 <li><a href="display_consumable_request.php" target="mainFrame">Consumable Request <font color="#FFFF00">[<?php  echo $num_rows; ?>] </font></a></li>
<li><a href="report_PPC.php" target="mainFrame">Material Request Report</a></li>
<li><a href="posting_request_scan_+_urgent.php" target="mainFrame" >Display Material Request</a></li>
<li><a href="posting_request_consumable.php" target="mainFrame" >Display Consumable Request</a></li>
<li><a href="close_MRIN_ppc.php" target="mainFrame">Material Request Maintenance</a></li>
<li><a href="posting_request_history.php" target="mainFrame">Material Request History</a></li>
<li><a href="history_consumable_request.php" target="mainFrame">Consumable Request History</a></li>
<li><a href="material_request_analysis.php" target="mainFrame">Material Request Analysis</a></li>
<li><a href="consumable_request_analysis.php" target="mainFrame">Consumable Request Analysis</a></li>
<li><a href="TP_manual_upload.php" target="mainFrame">Uploaded MIGO TR</a></li>
<li><a href="manual_upload_file.php" target="mainFrame">Manually Upload File Transfer (TP)</a></li>
<li><a href="../logout.php" target="_parent">Logout</a></li>
</ul>
</div>  
  
    </td>
  </tr>
</table>


</body>
</html>
