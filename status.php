<?php
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