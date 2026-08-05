<?php
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

set_time_limit(0);
// include 2D barcode class (search for installation path)
require_once('/tcpdf_barcodes_2d.php');

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "display_cancel_backflush_tran_proc.php";


    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
    $result2 = mysql_query($query2) or die (mysql_error());
    $res = mysql_fetch_array($result2);
	
$today = getdate();
$hours = $today['hours']; 
$minutes = $today['minutes'];
$seconds = $today['seconds'];
$month = $today['mon']; 
$mday = $today['mday']; 
$year = $today['year']; 	

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysql_query($query_setup);   //run the query.
$num_setup = mysql_num_rows($rs_setup);   //how many material are there?
$data_setup = mysql_fetch_array($rs_setup);
//----------------------------------------------------	
//CR status (New)

$sta = "SELECT * from request_status WHERE status_id = '1' ";
$sta_res = mysql_query($sta);
$rst_sta = mysql_fetch_array($sta_res);

//CR status (Released)
$sta2 = "SELECT * from request_status WHERE status_id = '2' ";
$sta_res2 = mysql_query($sta2);
$rst_sta2 = mysql_fetch_array($sta_res2);	

//CR status (Cancel)
$sta4 = "SELECT * from request_status WHERE status_id = '4' ";
$sta_res4 = mysql_query($sta4);
$rst_sta4 = mysql_fetch_array($sta_res4);	

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7' ";
$sta_res7 = mysql_query($sta7);
$rst_sta7 = mysql_fetch_array($sta_res7);	

//CR status (Pending)
$sta8 = "SELECT * from request_status WHERE status_id = '8' ";
$sta_res8 = mysql_query($sta8);
$rst_sta8 = mysql_fetch_array($sta_res8);	

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
 
  //--------- qqc transaction detail ------------
	 
	   $query_pps = "SELECT * FROM qqc_transaction WHERE id_tran = '".$uid."'";
	   $result_pps = mysql_query($query_pps);
	   $data_pps = mysql_fetch_array($result_pps);
 

?>
<table class="table">
<tr>
  <td>&nbsp;</td>
  <td colspan="2"><div align="center"><span class="style4">Detail QC Output</span></div></td>
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
        <th>Location</th>
        <th>QC Location</th>
        <th>Backflush Doc. No.</th>
        <th>QC Doc. No.</th>
        <th>Posting Date</th>
        <th>Posting Time</th>
        <th>Pending Qty.</th>
        <th>Qty OK</th>
        <th>Qty NG</th>
      </tr>
    </thead>
    <tbody>
      <?php
   
   $counter = 1;
   $no = 1;
   $sta_out = "";
   
   $query_display = "SELECT *, DATE_FORMAT(MR.date_plan,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_qc_posting,'%d-%m-%Y') as R2, DATE_FORMAT(MR.date_create,'%d-%m-%Y') as R3 FROM qqc_transaction AS MR WHERE MR.status_QC = '".$rst_sta8["status_desc"]."' AND MR.id_tran = '$uid'";
$result_display = mysql_query($query_display);   //run the query.
   
   while ($row2 = mysql_fetch_array($result_display))
   {
	


      ?>
      <tr class="gradeX">
        <td width="30"><?php echo $no; ?><input name="uid" type="hidden" value="<?php echo $row2["id_qqc"]; ?> "></td>
        <td><?php echo $row2["material_no"]; ?></td>
        <td width="80"><font color="#0000CC"><?php echo $row2["plan_no"]; ?></font></td>
        <td width="80"><?php echo $row2["R"]; ?></td>
        <td width="60"><?php echo $row2["work_center"]; ?></td>
        <td width="40"><?php echo $row2["shift_day"]; ?></td>
        <td width="50"><?php echo $row2["ploc"]; ?></td>
        <td width="50"><?php echo $row2["ploc_qc"]; ?></td>
        <td width="90"><font color="#0000CC"><?php echo $row2["bflush_no"]; ?></font></td> 
        <td width="90"><font color="#FF0000"><?php echo $row2["qqc_no"]; ?></font></td>
        <td width="80"><?php echo $row2["R2"]; ?></td>
        <td width="48"><?php echo $row2["time_qc_posting"]; ?></td>
        <td width="80"><?php echo $row2["qty_balance"]; ?></td>
        <td width="50" bgcolor="#B9ECFD"><?php echo $row2["qty_qc_ok"]; ?></td>
        <td width="60" bgcolor="#FDD7FC"><?php echo $row2["qty_qc_NG"]; ?></td>
        
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