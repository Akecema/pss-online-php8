<?php


/**
 * planning/findWorkcenter4.php
 * Part of: Planning module
 * Filename suggests: findWorkcenter4
 *
 * Behavior: processes submitted form data ($_POST).
 * Database tables referenced: sys_setup_maintain, work_center_detail.
 * Includes: config.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
$factory = $_GET['factory'];
include '../include/config.php';

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------	

$query4 = "SELECT * FROM work_center_detail WHERE id_factory = '".$factory."' ORDER BY id_work ASC";
$result4 = mysqli_query($dbc, $query4);

?>



<div id="work_centerdiv">
  <select name="work_center" id="work_center" class="span11">
   <option value="NULL" placeholder="Select Work Center"> -- Select Work Center -- </option>
<?php
                while($row4=mysqli_fetch_array($result4)) 
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
