<?php

/**
 * prod/detail_cancel_backflush_tran_proc.php
 * Part of: Production module
 * Filename suggests: detail cancel backflush tran proc
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, pps_detail_cancellation, pps_detail_transaction.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, tcpdf_barcodes_2d.php, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
session_start();
$username = $_SESSION['username'] ?? '';
include '../include/config.php';
require_once '../include/auth.php';
require_role($dbc, 2);
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

set_time_limit(0);
// include 2D barcode class (search for installation path)
require_once(__DIR__ . '/tcpdf_barcodes_2d.php');

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "display_cancel_backflush_tran_proc.php";


    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
    $result2 = mysqli_query($dbc, $query2) or die(db_fail($dbc));
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
//CR status (New)

$sta = "SELECT * from request_status WHERE status_id = '1' ";
$sta_res = mysqli_query($dbc, $sta);
$rst_sta = mysqli_fetch_array($sta_res);

//CR status (Released)
$sta2 = "SELECT * from request_status WHERE status_id = '2' ";
$sta_res2 = mysqli_query($dbc, $sta2);
$rst_sta2 = mysqli_fetch_array($sta_res2);	

//CR status (Cancel)
$sta4 = "SELECT * from request_status WHERE status_id = '4' ";
$sta_res4 = mysqli_query($dbc, $sta4);
$rst_sta4 = mysqli_fetch_array($sta_res4);	

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7' ";
$sta_res7 = mysqli_query($dbc, $sta7);
$rst_sta7 = mysqli_fetch_array($sta_res7);	

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
<style type="text/css">
<!--
.style3 {color: #000000}
@media print{
  body{  margin-top: -2.2cm;  font-family: Arial, Helvetica, sans-serif; font-size:12px; }
  #ad{ display:none;}
  #leftbar{ display:none;}
  #contentarea{ width:100%;}
  .header, .hide { visibility: hidden }
  .tfoot { display: table-footer-group; }
    @page {size: landscape}
}
.style4 {
	font-size: 14px;
	font-weight: bold;
}
-->
</style>
</head>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>
</head>
<body>
<div class="widget-box">
  <!-- Header -->
  <!-- End Header -->
  <?php

 $uid = $_GET["uid"];
 
  //--------- pps detail ------------
	 
	   $query_pps = "SELECT * FROM pps_detail_cancellation WHERE id = '".db_esc($dbc, $uid)."'";
	   $result_pps = mysqli_query($dbc, $query_pps);
	   $data_pps = mysqli_fetch_array($result_pps);
 

?>
<table class="table">
<tr>
  <td>&nbsp;</td>
  <td colspan="2"><div align="center"><span class="style4">Detail Backflush Cancellation</span></div></td>
  </tr>
</table>
<p>&nbsp;</p> 
  <table class="table table-bordered data-table">
    <thead>
      <tr>
        <th>No.</th>
        <th width="77">Part No.</th>
        <th>Planned Order No.</th>
        <th>Planned Date</th>
        <th>Work Center</th>
        <th>Shift</th>
        <th>To Location</th>
        <th>Document No.</th>
        <th>Posting Date</th>
        <th>Posting Time</th>
        <th>Cancellation Doc. No.</th>
        <th>Cancel Date</th>
        <th>Output Status</th>
        <th>Output Qty.</th>
        <th>Planned Order Status</th>
      </tr>
    </thead>
    <tbody>
      <?php
   
   $counter = 1;
   $no = 1;
   $sta_out = "";
   
   $query_display = "SELECT *, DATE_FORMAT(MR.date_plan,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2, DATE_FORMAT(MR.date_cancel,'%d-%m-%Y') as R3 FROM pps_detail_transaction AS MR, work_center_detail AS SR WHERE SR.id_work = MR.work_center AND MR.status_pps = '".db_esc($dbc, $rst_sta4["status_desc"])."' AND MR.id = '".db_esc($dbc, $uid)."'";
$result_display = mysqli_query($dbc, $query_display);   //run the query.
   
   while ($row2 = mysqli_fetch_array($result_display))
   {
		
	//shift	
		if($row2["shift_pps1"] != "")
	{
		$sta = "D/S";
		
	}elseif($row2["shift_pps2"] != "")
	 {
		$sta = "N/S";
	 }else{
		 
		$sta = " ";
	 }	
	
	//quantity output
		if($row2["qty_actual"] != "0.000")
	{
		$qty_final = $row2["qty_actual"];
	}elseif($row2["qty_NG"] != "0.000")
	{
		$qty_final = $row2["qty_NG"];
	}else{
		$qty_final == " ";
	}
	
	//status output
	//----check string -----------
	
	$sta_out = substr($row2["bflush_no"],4,1);
	
	if($sta_out == "1")
	{
		$status_output = "OK";
	}elseif($sta_out == "3")
	{
	    $status_output = "NG";
	}else{
	     $status_output = "";
	}
	 
      ?>
      <tr class="gradeX">
        <td width="30"><?php echo $no; ?></td>
        <td><?php echo $row2[9]; ?></td>
        <td width="80"><font color="#0000CC"><?php echo $row2[4]; ?></font></td>
        <td width="80"><?php echo $row2["R"]; ?></td>
        <td width="60"><?php echo $row2[18]; ?></td>
        <td width="40"><?php echo $sta; ?></td>
        <td width="50"><?php echo $row2[32]; ?></td>
        <td width="90"><font color="#0000CC"><?php echo $row2[3]; ?></font></td>
        <td width="80"><?php echo $row2["R2"]; ?></td>
        <td width="48"><?php echo $row2[31]; ?></td>
         <td width="90"><font color="#FF0000"><?php echo $row2[40]; ?></font></td>
        <td width="80"><?php echo $row2["R3"]; ?></td>
        <td width="50"><font color="#FF9900"><?php echo $status_output; ?></font></td>
        <td width="60"><?php echo $qty_final; ?></td>
        <td width="99"><?php echo $row2[16]; ?></td>
        <input name="uid" type="hidden" value="<?php echo $row2["id"]; ?> ">
      </tr> 
      
      <?php 
		  
		  $no ++;
		  $counter++; // menambah counter
		  } 
		  ?>
        
      </tr>  
     
    </tbody>
  </table> 
  <table>
    <tr><td>&nbsp;</td>
</tr>
       </table>

  <p>&nbsp;</p>
  <p><br> 
    
    <!--Footer-part-->
  </p>
  <?php include "footer.php";   ?>
<!--end-Footer-part--> 
</div>
</body>
</html>