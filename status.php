<?php

/**
 * status.php
 * Part of: Core / entry-point script
 * Filename suggests: status
 *
 * Behavior: no form submission, file upload, or export detected (likely a display/listing page, utility, or bootstrap/include file).
 * Database tables referenced: user_detail.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
//counting rows...
$result = mysqli_query($dbc, "SELECT * FROM user_detail");
$row = mysqli_num_rows($result);
$statusresult = mysqli_query($dbc, "SELECT level_id from user_detail");
$i=0;
while($level_id = mysqli_fetch_array($statusresult)){	
	if($level_id[0] == 10 or $level_id[0] == 1){
		$i++;
	}
}
?>