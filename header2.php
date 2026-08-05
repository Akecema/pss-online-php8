<?php

require("functions2.php");
$time_start=getmicrotime();

if(isset($username)){
	$levelArray = mysql_query("select level_id from user_detail where username='$username'");
	$level_id = mysql_fetch_array($levelArray);
	
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
