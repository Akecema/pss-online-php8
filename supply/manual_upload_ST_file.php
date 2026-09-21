<?php

/**
 * supply/manual_upload_ST_file.php
 * Part of: Supply Chain module
 * Filename suggests: manual upload ST file
 *
 * Behavior: requires an active login session ($_SESSION['username']).
 * Database tables referenced: user_detail, sys_setup_maintain.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_supply_menu.php, footer.php.
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
require_role($dbc, 6);
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

//date_default_timezone_set('Asia/Bangkok');

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

  $today = getdate();
  $hours = $today['hours']; 
  $minutes = $today['minutes'];
  $seconds = $today['seconds'];
  $month = $today['mon']; 
  $mday = $today['mday']; 
  $year = $today['year']; 

$url = "manual_upload_file.php";


    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
    $result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
    $res = mysqli_fetch_array($result2);
	
$today = getdate();
$hours = $today['hours']; 
$minutes = $today['minutes'];
$seconds = $today['seconds'];
$month = $today['mon']; 
$mday = $today['mday']; 
$year = $today['year']; 	

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
<link rel="stylesheet" href="css/bootstrap-datepicker.css" />
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

<style type="text/css">
<!--
.style3 {color: #000000}
-->
</style>
<script type="text/javascript">
function printPage(iFid){
iFid.focus();
iFid.print();
}
</script>
<style>
#iframe1{
visibility:hidden;
}
</style>
</head>
<body>

<!--Header-part-->
<div id="header">
  <h1>&nbsp;</h1>
</div>
<?php  include "top_modal_menu.php";   ?>
<!--close-Header-part--> 

<!--sidebar-menu-->
<?php include "left_supply_menu.php";  ?>
<!--sidebar-menu-->
<div id="content">
 <div id="content-header">
  <div id="breadcrumb"> <a href="index_supply.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a><a href="#" class="tip-bottom">Manually Upload File Transfer (TP)</a><a href="#" class="current">Uploaded ST</a></div>
  <h1>Uploaded MIGO TR</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
         <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li><a  role="tab" href="manual_upload_file.php">Uploaded TP</a></li>
              <li class="active"><a role="tab" href="manual_upload_ST_file.php">Uploaded ST</a></li>
              <li><a role="tab" href="manual_upload_TPWIP_file.php">Uploaded WIP</a></li>
            </ul>
          </div>
          </div>


        
            <table width="600" style="margin:40px auto; background:#f8f8f8; border:1px solid #eee; padding:10px;">
<tr><td colspan="2" style="font:bold 21px arial; text-align:center; border-bottom:1px solid #eee; padding:5px 0 10px 0;">&nbsp;</td>
</tr>

<tr>
  <td colspan="2" style="font:bold 15px arial; text-align:center; padding:0 0 5px 0;">Manually Uploading ST File </td>
</tr>





<tr>

<td width="50%" style="font:bold 12px tahoma, arial, sans-serif; text-align:right; padding:5px 10px 5px 0px; border-right:1px solid #eee;">Please check Auto Upload Schedule job on the server.</td>

<td width="50%" style=" padding:5px;"><input name="submit" type="button" value=" Upload ST File" onclick="window.open('http://10.10.20.115/PSS_upload/upload_ST_ipsb.php','_blank')" class="btn btn-success" /></td>
</tr>
</table>
 
 
          </div>
        
      </div>
    </div>
  </div>
</div>

<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part--> 

<script src="../js/jquery.min.js"></script> 
<script src="../js/jquery.ui.custom.js"></script> 
<script src="../js/bootstrap.min.js"></script> 
<script src="../js/bootstrap-datepicker.js"></script> 
<script src="../js/bootstrap-datepicker.min.js"></script> 
<script src="../js/jquery.uniform.js"></script> 
<script src="../js/select2.min.js"></script> 
<script src="../js/jquery.dataTables.min.js"></script> 
<script src="../js/matrix.js"></script> 
<script src="../js/matrix.tables.js"></script>
</body>
</html>