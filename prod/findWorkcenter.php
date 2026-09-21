<?php


/**
 * prod/findWorkcenter.php
 * Part of: Production module
 * Filename suggests: findWorkcenter
 *
 * Behavior: reads parameters from the query string ($_GET).
 * Database tables referenced: work_center_detail.
 * Includes: config.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
$factory =intval($_GET['factory']);
include '../include/config.php';
require_once '../include/auth.php';
require_role($dbc, 2);

$query4 = "SELECT * FROM work_center_detail WHERE id_factory = '$factory' ORDER BY id_work ASC";
$result4 =mysqli_query($dbc, $query4);

?>


  <select name="work_center" id="work_center" class="span11">
   <option value="NULL" placeholder="Select Production Line/ Work Center"> -- Select Production Line/ Work Center --</option>
<?php
                while($row4=mysqli_fetch_array($result4, MYSQLI_NUM)) 
			      {
                  echo'<option value="',$row4[0],'">',stripslashes($row4[0]),' - ',stripslashes($row4[2]),'</option>';
                  }
				  
				  ?>
</select>
