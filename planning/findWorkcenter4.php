<?php

$factory = $_GET['factory'];
include '../include/config.php';

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysql_query($query_setup);   //run the query.
$num_setup = mysql_num_rows($rs_setup);   //how many material are there?
$data_setup = mysql_fetch_array($rs_setup);
//----------------------------------------------------	

$query4 = "SELECT * FROM work_center_detail WHERE id_factory = '".$factory."' ORDER BY id_work ASC";
$result4 = mysql_query($query4);

?>



<div id="work_centerdiv">
  <select name="work_center" id="work_center" class="span11">
   <option value="NULL" placeholder="Select Work Center"> -- Select Work Center -- </option>
<?php
                while($row4=mysql_fetch_array($result4)) 
			      {
					  
					  
					  if($_POST['Submit2'] == true){ ?>
                       <!--RETAIN VALUE-->
                       <option value="<?php echo $row4["id_work"]; ?>" <?php if($row4["id_work"] == ($_POST["work_center"])) echo "selected"; ?>> <?php echo stripslashes($row4["id_work"]),' - ',stripslashes($row4["wc_desc"]); ?></option>
                       <?php }else{ ?>
                       <option value="<?php echo $row4["id_work"]; ?>" > <?php echo stripslashes($row4["id_work"]),' - ',stripslashes($row4["wc_desc"]); ?></option>
                       <?php } 
					  
                  }
				  
				  ?>
</select></div>
