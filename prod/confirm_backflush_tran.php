<?php

/**
 * prod/confirm_backflush_tran.php
 * Part of: Production module
 * Filename suggests: confirm backflush tran
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, pps_detail, work_center_detail, mat_master_header, scan_prod_planning.
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

$url = "confirm_backflush_tran.php";

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
  <div id="breadcrumb"> <a href="index_production.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Production</a> <a href="#" class="current">Confirmation Backflush</a> </div>
  <h1>Confirmation Backflush</h1>
</div>

<div class="container-fluid">
  <hr>
  <div class="row-fluid">
  <!--  <div class="span6">-->
          <!--<div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li class="active"><a  role="tab" href="confirm_backflush_tran.php">New Request</a></li>
              <li><a role="tab" href="display_request.php">Display Request</a></li>
              <li><a role="tab" href="posting_request.php">Posting Request</a></li>
            </ul>
          </div>
          </div>-->
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>New Request</h5>
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
echo "<br>"; */

// negative limit (since PHP 5.1)
//print_r(explode('|', $str, -1));

  //--------- check for closed planning order x bleh scan pps------------- 
    $query_check_pps = "SELECT * FROM pps_detail WHERE plan_no = '".db_esc($dbc, $part2)."'";
	$result_check_pps = mysqli_query($dbc, $query_check_pps) or die (mysqli_error($dbc));
    $data_check_pps = mysqli_fetch_array($result_check_pps);
	
	if(($data_check_pps["status_pps"] == "Closed") || ($data_check_pps["status_pps"] == "Transfer QC"))
	{
		
			  echo "<script>";
			  echo "alert('PPS is already Closed. Please scan the others pps.');";
			  echo "window.location='confirm_backflush_tran.php'";
			  echo "</script>";
			  exit(); //quit the script
		
	}else{
  
  
  
  $query3 = "SELECT * FROM work_center_detail WHERE id_work = '".db_esc($dbc, $part4)."' ORDER BY id_work ASC";
  $result3 = mysqli_query($dbc, $query3);
  $row3 = mysqli_fetch_array($result3); 
				   
  $query_q2 = "SELECT * FROM mat_master_header WHERE material_no = '".db_esc($dbc, $part1)."'";
  $result_q2 = mysqli_query($dbc, $query_q2) or die (mysqli_error($dbc));
  $ans3 = mysqli_fetch_array($result_q2);
				   

//insert to scan_detail
$query_db = "INSERT INTO scan_prod_planning (id_scan, pps_ref, factory, work_center, plan_no, material_no, material_desc, material_type, scan_date_plan, scan_plant, scan_shift, scan_qty, scan_uom, user_create, date_create, user_update, date_update, status_urgent) VALUES ('', '".db_esc($dbc, $pps_ref2)."', '".db_esc($dbc, $row3["id_factory"])."', '".db_esc($dbc, $part4)."', '".db_esc($dbc, $part2)."', '".db_esc($dbc, $part1)."', '".db_esc($dbc, $ans3["material_desc"])."', '".db_esc($dbc, $ans3["material_type"])."', '".db_esc($dbc, $part3)."', '".db_esc($dbc, $ans3["plant"])."', '".db_esc($dbc, $part5)."', '".db_esc($dbc, $part6)."', '".db_esc($dbc, $part7)."', '".db_esc($dbc, $user_no)."', NOW(),'','','N')";
$result = mysqli_query($dbc, $query_db) or die (mysqli_error($dbc));


             if($result)
             {
			 
			 $query_sql = "SELECT * FROM scan_prod_planning WHERE id_scan = '".mysqli_insert_id($dbc)."'";
			 $result_sql = mysqli_query($dbc, $query_sql);
			 $data_sql = mysqli_fetch_array($result_sql);
			 
			  echo "<script>";
			  //echo "alert('Congratulations! Material Request successfully created');";
			  echo "window.location='confirm_backflushProc.php?buid=$data_sql[id_scan]'";
			  echo "</script>";
			  exit(); //quit the script
             }
             else 
			 {
             $message = '<p> Confirmation Backflush is failed. </p>';
              mysqli_close($dbc); //close db
             }   
} // end else

	 
}//print the message if there is one.
	  
if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}
}
?>


 <div class="widget-content nopadding">
         <form name="form1" method="post" action="confirm_backflush_tran.php" class="form-horizontal">
          <div class="control-group">
          <div class="controls"><span class="style4">&nbsp;<?php echo date("D M d, Y");   ?></span>&nbsp;&nbsp;<span class="style5"><?php echo date("H:i:s");  ?></span>
          </div>
          </div>
           <div class="control-group">
              <label class="control-label">Planned Order No. : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
               <input name="pps_ref" type="text" id="pps_ref" size="60" maxlength="200" value="<?php if(isset($_POST['pps_ref'])) echo $_POST['pps_ref']; ?>" class="span11" />
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
