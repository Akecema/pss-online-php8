<?php

/**
 * prod/wip_request_list.php
 * Part of: Production module
 * Filename suggests: wip request list
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, factory_detail, scan_detail_wip.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_production_menu.php, header.inc, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
require_once '../include/auth.php';
require_role($dbc, 2);
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "wip_request_list.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
    $result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
    $res = mysqli_fetch_array($result2);
//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------			

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $data_setup["title_desc"]; ?></title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="shortcut icon" href="../img/favicon.ico">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="../css/bootstrap.min.css" />
<link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="../css/uniform.css" />
<link rel="stylesheet" href="../css/select2.css" />
<link rel="stylesheet" href="../css/matrix-style.css" />
<link rel="stylesheet" href="../css/matrix-media.css" />
<link href="../font-awesome/css/font-awesome.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/jquery.gritter.css" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

<!----------------->
<link rel="stylesheet" href="../scripts/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="../javascript/jquery-latest.js"></script> 
<script type="text/javascript" src="../javascript/thickbox.js"></script>	
<link href="../calendar/calendar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../calendar/calendar.js"></script>

</head>
<body>

<!--Header-part-->
<div id="header">
  <h1>&nbsp;</h1>
</div>
<?php  include "top_modal_menu.php";   ?>
<!--close-Header-part--> 

<!--sidebar-menu-->
<?php include "left_production_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_production.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">WIP Request</a> <a href="#" class="current">WIP Request</a> </div>
  <h1>WIP Request</h1>
</div>

<div class="container-fluid">
  <hr>
  <div class="row-fluid">
  <!--  <div class="span6">-->
          <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li class="active"><a  role="tab" href="wip_request_list.php">New Request</a></li>
              <li><a role="tab" href="draft_request_wip.php">Draft Request</a></li>
              <li><a role="tab" href="display_request_wip.php">Display Request</a></li>
            </ul>
          </div>
          </div>
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>New Request - WIP </h5>
        </div>



   
       <?php
// Set the page title and include the HTML header.
//include ('templates/header.inc');

if(isset($_POST['submit3'])) 
{ // handle the form.

require_once('../include/config.php');   //connect to the db.


// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
// create a function for escaping the data.
function escape_data ($data) {
global $dbc;   // need the connection.
if (ini_get('magic_quotes_gpc')) {
    $data = stripslashes($data);
	}
	return mysqli_real_escape_string($dbc, $data);
	}   // end function.
$message = NULL; // create an empty new variable.
   
   $pps_ref = $_POST["pps_ref"];
  // $work_center = $_POST["work_center"];
   $user_no = $_POST["user_no"];
  
  
// check for a pps ref (scan from pps)
if(empty($_POST["pps_ref"]))
{ 
  $pps_ref = FALSE;
  $message.= '<p>You are required to scanning PPS!</p>';
  }
  

if($pps_ref) //everything ok
{  

//checking delete space semasa scanning

$pps_ref2 =trim($pps_ref);
			
 
//split dulu pps ref kpd prod_order, material,uom, plant, sloc, qty
$str = $pps_ref2;


list($part1, $part2, $part3, $part4, $part5, $part6, $part7) = (explode('|', $str, 7));

/*echo "no 1 ".$part1;
echo "<br>";
echo "no 2 ".$part2;
echo "<br>";
echo "no 3 ".$part3;
echo "<br>";
echo "no 4 ".$part4;
echo "<br>";
echo "no 5 ".$part5;
echo "<br>";
echo "no 6 ".$part6;
echo "<br>"; 
echo "no 7 ".$part7;
echo "<br>";
*/

// negative limit (since PHP 5.1)
//print_r(explode('|', $str, -1));
  
                   $query3 = "SELECT * FROM factory_detail WHERE id_fac = '".db_esc($dbc, $part5)."' ORDER BY id_fac ASC";
                   $result3 = mysqli_query($dbc, $query3);
				   $row3 = mysqli_fetch_array($result3, MYSQLI_NUM); 
				   
 
//insert to scan_detail
$query_db = "INSERT INTO `scan_detail_wip` (id_scan, pps_ref, factory, work_center, prod_order, material_no, scan_oum, scan_plant, scan_sloc, scan_qty, user_create, date_create, user_update, date_update, status_urgent) VALUES ('".mysqli_insert_id($dbc)."', '".db_esc($dbc, $pps_ref2)."', '".db_esc($dbc, $row3[2])."', '".db_esc($dbc, $part7)."', '".db_esc($dbc, $part1)."', '".db_esc($dbc, $part2)."', '".db_esc($dbc, $part3)."', '".db_esc($dbc, $part4)."', '".db_esc($dbc, $part5)."', '".db_esc($dbc, $part6)."', '".db_esc($dbc, $user_no)."', NOW(),'','','N')";
$result = mysqli_query($dbc, $query_db) or die (mysqli_error($dbc));


             if($result)
             {
			 
			 $query_sql = "SELECT * FROM `scan_detail_wip` WHERE id_scan = '".mysqli_insert_id($dbc)."'";
			 $result_sql = mysqli_query($dbc, $query_sql);
			 $data_sql = mysqli_fetch_array($result_sql);
			 
echo "<script>";
//echo "alert('Congratulations! Material Request successfully created');";
echo "window.location='wip_request_listProc.php?uid=$data_sql[id_scan]'";
echo "</script>";
			  exit(); //quit the script
             }
             else 
			 {
             $message = '<p> Cannot request Material Request. </p>';
              mysqli_close($dbc); //close db
             }  
}
//print the message if there is one.
	 
	  
if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}
}
?>


 <div class="widget-content nopadding">
         <form name="form1" method="post" action="wip_request_list.php" class="form-horizontal">
           <div class="control-group">
              <label class="control-label">Material No. [Scan PPS] : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
               <input name="pps_ref" type="text" id="pps_ref" size="60" maxlength="200" value="<?php if(isset($_POST['pps_ref'])) echo h($_POST['pps_ref']); ?>" class="span11" />
                 <img src="../img/scan_barcode.jpg" width="32" height="32" /></font>
              </div>
            </div>
             <div class="control-group">
              <div class="controls">
             <input name="user_no" type="hidden" value="<?php echo $res["user_no"]; ?>" />
              </div>
            </div>
             <div class="control-group">
              <label class="control-label"><font color="#FF0000"><b>  * Compulsory field</b></font></label>
             <div class="controls">
            </div>
            </div>
            <div class="form-actions">
               <input name="submit3" type="submit" id="submit" value="NEXT" class="btn btn-success">
               <input name="Reset" type="reset" id="Reset" class="btn btn-warning" value="CANCEL">
           </div>
            </form>
            </div>



</div></div></div></div>
</div>
<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part--> 

<script src="../js/jquery.min.js"></script> 
<script src="../js/jquery.ui.custom.js"></script> 
<script src="../js/bootstrap.min.js"></script> 
<script src="../js/jquery.uniform.js"></script> 
<script src="../js/select2.min.js"></script> 
<script src="../js/jquery.dataTables.min.js"></script> 
<script src="../js/matrix.js"></script> 
<script src="../js/matrix.tables.js"></script>



</body>
</html>
