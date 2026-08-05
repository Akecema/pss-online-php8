

<?php 
$factory =intval($_GET['factory']);
include '../include/config.php';

$query="SELECT id,statename FROM state WHERE countryid='$country'";
$result=mysqli_query($dbc, $query);

?>
 <select name="work_center" id="work_center" class="span11">
                    <option value="NULL" placeholder="Select Production Line/ Work Center"> -- Select Production Line/ Work Center --</option>
                    <?php
	               $query4 = "SELECT * FROM work_center_detail WHERE id_factory ='$factory' ORDER BY id_work ASC";
                   $result4 = mysqli_query($dbc, $query4);
  
                   while($row4=mysqli_fetch_array($result4, MYSQLI_NUM)) 
			      {
                  echo'<option value="',$row4[0],'">',stripslashes($row4[0]), ' - ',stripslashes($row4[2]),'</option>';
                  }
				?>
                  </select>