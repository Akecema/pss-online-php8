<?php


/**
 * header2.php
 * Part of: Core / entry-point script
 * Filename suggests: header2
 *
 * Behavior: no form submission, file upload, or export detected (likely a display/listing page, utility, or bootstrap/include file).
 * Database tables referenced: user_detail.
 * Includes: functions2.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
require("functions2.php");
$time_start=getmicrotime();

if(isset($username)){
	$levelArray = mysqli_query($dbc, "select level_id from user_detail where username='$username'");
	$level_id = mysqli_fetch_array($levelArray);
	
	$displayName = "[ $username | <a href='logout.php' class='top' target='_parent'>";
	$displayName .= "Logout</a>";
	if($level_id[0]== 0 or $level_id[0]== 1 or $level_id[0] == 2 or $level_id[0] == 3 or $level_id[0] == 4)
		$displayName .= " | <a href='change_password.php' class='top'>Change Password</a> ]";
	else
		$displayName .= " ]";
	//endif
}
else{
	session_destroy();
	$displayName = "";
}//endif
?>
