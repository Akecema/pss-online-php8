<?php
//counting rows...
$result = mysql_query("SELECT * FROM user_detail");
$row = mysql_num_rows($result);
$statusresult = mysql_query("SELECT level_id from user_detail");
$i=0;
while($level_id = mysql_fetch_array($statusresult)){	
	if($level_id[0] == 10 or $level_id[0] == 1){
		$i++;
	}
}
?>