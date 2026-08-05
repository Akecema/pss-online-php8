<?php
//Get time
function getmicrotime(){ 
    list($usec, $sec) = explode(" ",microtime());
	$time = $usec + $sec;
    return $time; 
}//END
?>