<?php

$factory =intval($_GET['factory']);
include '../include/config.php';

$query4 = "SELECT * FROM work_center_detail WHERE id_factory = '$factory' ORDER BY id_work ASC";
$result4 =mysql_query($query4);

?>


  <select name="work_center" id="work_center" class="span11">
   <option value="NULL" placeholder="Select Production Line/ Work Center"> -- Select Production Line/ Work Center --</option>
<?php
                while($row4=mysql_fetch_array($result4, MYSQL_NUM)) 
			      {
                  echo'<option value="',$row4[0],'">',stripslashes($row4[0]),' - ',stripslashes($row4[2]),'</option>';
                  }
				  
				  ?>
</select>
