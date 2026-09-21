<?php

	
/**
 * logout.php
 * Part of: Core / entry-point script
 * Filename suggests: logout
 *
 * Behavior: requires an active login session ($_SESSION['username']).
 * Database tables referenced: sys_setup_maintain, user_detail, login_detail.
 * Includes: config.php, status.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
require("include/config.php");
	require("status.php");
	
	session_start();
    $username = $_SESSION["username"];
    header("Cache-control: private");

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
	
	
	//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);


?>

<meta http-equiv="refresh" content="1;URL=index.php">
<title><?php echo $data_setup["title_desc"]; ?></title>
<link href="css/templatemo_style.css" rel="stylesheet" type="text/css" />
<style type="text/css">

body {
	background-color: #FFFFFF;
	background-image: url();
}
.style1 {color: #FFFFFF}
.reflectBelow	{ 
    -webkit-box-reflect: below 0px -webkit-gradient(linear, left top, left bottom, from(transparent), to(rgba(250, 250, 250, 0.1)));

	
}
.page_center {
	width: 1000px;
	margin: 0 auto;
	padding: 70px 0;
}

</style>
<body >
<div class="page_center">

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height="500">
  <tr> 
    <td align="center" style="font-family:verdana;font-size:11px" height="14">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" style="font-family:verdana;font-size:11px" height="346"> 
    
      <?php


	
    $query = "SELECT * FROM user_detail WHERE username='".db_esc($dbc, $username)."'";
	$result = mysqli_query($dbc, $query); 
	$db_rst = mysqli_fetch_array($result);
	
	if (mysqli_query($dbc, $query)){
	
?><fieldset>
<table width="50%" >
<tr> 
    <td align="center" style="font-family:verdana;font-size:11px" height="14">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" style="font-family:verdana;font-size:11px" height="14">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" style="font-family:verdana;font-size:11px" height="14">&nbsp;<img src="img/logout2.jpg" alt="EXIT" width="80" height="80"></td>
  </tr>
  <tr>
    <td style="font-family:verdana;font-size:11px">&nbsp;<?php
		
		echo "<center>Logout  successfully, <b>$username.</b> Please click <a href='index.php'>here</a> to proceed.</center>";
		//session_unregister('name');
		//session_unregister("username");
		
		unset($_SESSION["username"]);
		session_unset();
		session_destroy();   
		
		$past = time() - 100; 
   //this makes the time in the past to destroy the cookie 
     setcookie("ID_my_site", $past); 
     setcookie("Key_my_site", $past); 
	
	
   //-------------------upldate last login in table user_detail
	$query_upd = "UPDATE user_detail SET last_login = NOW() WHERE user_no = '".db_esc($dbc, $db_rst["user_no"])."'";
	$result_upd = mysqli_query($dbc, $query_upd); 
	
	//-------------------upldate last login in table user_detail
	$query_upd2 = "UPDATE login_detail SET last_login = NOW() WHERE staff_ID = '".db_esc($dbc, $db_rst["staff_ID"])."'";
	$result_upd2 = mysqli_query($dbc, $query_upd2); 
    //header("Location: index.php"); 
		
	}
	else
	{
		echo "Technical Errors. Please click <a href='javascript:history.go(-1)'>here</a> to proceed.";
	}//endif
	?></td>
  </tr>
</table></fieldset>
    </td>
  </tr>
</table>
</div>
