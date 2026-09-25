<?php

/**
 * functions2.php
 * Part of: Core / entry-point script
 * Filename suggests: functions2
 *
 * Behavior: no form submission, file upload, or export detected (likely a display/listing page, utility, or bootstrap/include file).
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
//Get time
function getmicrotime(){ 
    list($usec, $sec) = explode(" ",microtime());
	$time = $usec + $sec;
    return $time; 
}//END
?>