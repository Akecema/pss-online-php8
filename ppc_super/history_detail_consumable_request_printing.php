<?php

/**
 * ppc_super/history_detail_consumable_request_printing.php
 * Part of: PPC module (supervisor/admin tier)
 * Filename suggests: history detail consumable request printing
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: sys_setup_maintain, consumable_request, factory_detail, user_detail, work_center_detail, consumable_request_cancel, reason_req_cancel, consumable_request_close, reason_req_close.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, tcpdf_barcodes_2d.php, th>
          <th><?php echo $row_k[, footer.php.
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
require_role($dbc, 5);
include_once ('../classes/paginator.class2.php');
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

$temp_mrin = $_GET["mrin_no"];
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
<meta charset="utf-8" />
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
.style4 {
	font-size: 14px;
	font-weight: bold;
}
-->
</style>
<style type="text/css" media="print"> 
.breakAfter{ 
page-break-after: always; 
}
.breakBefore{
page-break-before: always ;
}
.tfoot { display: table-footer-group; }
.page {
 size:landscape;
 bottom: 0;
   
}

 @media print{
  body{  margin-top: -1.3cm;  font-family: Arial, Helvetica, sans-serif; font-size:12px; }
  #ad{ display:none;}
  #leftbar{ display:none;}
  #contentarea{ width:100%;}
  .header, .hide { visibility: hidden }
  .tfoot { display: table-footer-group; }
  @page {size: landscape}

} 
</style> 

<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>
<script>
function myFunction() {
    window.print();
}
</script>
<?php

$url = 'history_consumable_request.php';

?>

</head>

<body onload="window.print()">
<div class="widget-box">

  <?php



$queryu = "SELECT * FROM consumable_request as MR, consumable_detail as SD WHERE MR.id_con = SD.id_con AND MR.temp_mrin = '".db_esc($dbc, $temp_mrin)."'";
$rs = mysqli_query($dbc, $queryu);   //run the query.


$query_2 = "SELECT *, DATE_FORMAT(MR.date_require,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2 from consumable_request as MR, consumable_detail as SD WHERE MR.id_con = SD.id_con AND MR.temp_mrin = '".db_esc($dbc, $temp_mrin)."'";
$result_2 = mysqli_query($dbc, $query_2);   //run the query.
$data_2 = mysqli_fetch_array($result_2);

 $query3 = "SELECT * FROM factory_detail WHERE id_fac = '".db_esc($dbc, $data_2["factory"])."'";
    $result3 = mysqli_query($dbc, $query3);
	$row3 = mysqli_fetch_array($result3);
	
		$query_k = "SELECT * from `user_detail` as uc WHERE uc.user_no = '".db_esc($dbc, $data_2["user_create"])."'";
$result_k = mysqli_query($dbc, $query_k);
$row_k = mysqli_fetch_array($result_k);

$query_k2 = "SELECT * from `user_detail` as uc2 WHERE uc2.username = '".db_esc($dbc, $username)."'";
$result_k2 = mysqli_query($dbc, $query_k2);
$row_k2 = mysqli_fetch_array($result_k2);

 ?>


<table class="table table-condensed">
<tr>
      <td width="1%">&nbsp;</td>
      <td width="14%"><img src="../img/print2.jpg" width="48" height="48" onClick="window.print()" title="Print"/></td>
      <td width="85%">&nbsp;</td>
      
  </tr>

</table>

<br>
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>MRIN LIST</h5>
          </div>


        <table class="table table-bordered" >
        <tr>
          <th>MRIN No </th>
          <th>:</th>
          <th><?php echo h($temp_mrin); ?></th>
          <th>Factory</th>
          <th>:</th>
          <th><?php echo $data_2["factory"];  ?></th>
        </tr>
        <tr>
          <th>Date &amp; Time</th>
          <th>:</th>
          <th><?php echo $data_2["R2"]; ?>&nbsp;<?php echo $data_2["time_posting"]; ?></th>
          <th>Required Date &amp; Time</th>
          <th>:</th>
          <th>&nbsp;<?php echo $data_2["R"]; ?>&nbsp;<?php echo $data_2["time_require"]; ?></th>
        </tr>
        <tr>
          <th>Requested by</th>
          <th>:</th>
          <th><?php echo $row_k["user_fullname"]; ?></th>
          <th>Prepared by (PPC)</th>
          <th>:</th>
          <th><?php echo $row_k2["user_fullname"]; ?></th>
        </tr>
      </table>
<p>&nbsp;</p>
      <br>
         <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
              <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Material Number</th>
                  <th>Material Description</th>
                  <th>Quantity</th>
                  <th>Uom</th>
                  <th>Line</th>
                  <th>Transfer Location</th>
                  <th>Transfer Quantity</th>
                  <th>Cost Center</th>
                  <th>Received by</th>
                  <th>Barcode</th>
                  <th>SAP TP Mat. Doc.</th>
                </tr>
              </thead><tbody>
              
         
           <?php
      $counter = 1;
   $no = 1;
    $k = 0;
   while ($row = mysqli_fetch_array($rs))
   {
		//$user_no = $row[0]; 
 $no = sprintf('%03d', $no);
		
     
  //------------------cost center --------------------//
   $query_cost_center = "SELECT * FROM work_center_detail WHERE id_work = '".db_esc($dbc, $row["id_work"])."'";
   $result_cost_center = mysqli_query($dbc, $query_cost_center) or die(db_fail($dbc));
   $row_cost_center = mysqli_fetch_array($result_cost_center);
   
	 
			 
			  if ($k && $k % 7 == 0)  
		echo '<tr style="page-break-before:always">';  
	else if ($k)  
		echo '<tr>';  
	++$k; 
	
	?>
  <tr>
    <td width="73"><?php  echo $row["material_no"]; ?></td>
    <td width="220"><?php  echo $row["mat_desc"]; ?></td>
    <td width="80"><div align="right"><?php echo $row["con_qty"]; ?>&nbsp;</div></td>
    <td width="63" ><div align="center"><?php echo $row["con_uom"]; ?></div></td>
    <td width="80"><div align="center"><?php echo $row["id_work"]; ?></div></td>
    <td width="80"><div align="center"><?php echo $row["cost_center"]; ?></div></td>
    <td width="80">&nbsp;</td>
    <td width="80"><div align="center"><?php echo $row_cost_center["cost_center"]; ?></div></td>
    <td width="80">&nbsp;</td>
    <td width="200"><?php

// set the barcode content and type

$bar_text = ($row["material_no"].'|'.$row["con_qty"].'|'.$row["con_uom"].'|'.$temp_mrin.'|'.$row_cost_center["cost_center"].'|'.$row["cost_center"]);

$barcodeobj = new TCPDF2DBarcode($bar_text, 'PDF417');
echo $barcodeobj->getBarcodeSVGcode(1.0, 0.8, 'black');

?></td>
    <td width="90">&nbsp;</td> </tr>
    <?php 
		  	  
		  $counter++; // menambah counter 
 		  $no ++;   
			   
			   
			   }
			  
			   ?>
         </tbody>
         </table>

                <p>&nbsp;</p> 
         <?php
		
	$query_display_reason = "SELECT * from `consumable_request_cancel` WHERE temp_mrin = '".db_esc($dbc, $temp_mrin)."' AND status = 'Cancel'";
    $result_display_reason = mysqli_query($dbc, $query_display_reason);
    $row_display_reason = mysqli_fetch_array($result_display_reason);
	
	$query_reason_tbl = "SELECT * from `reason_req_cancel` WHERE id_cancel = '".db_esc($dbc, $row_display_reason["reason_cancel"])."'";
	 $result_reason_tbl = mysqli_query($dbc, $query_reason_tbl);
    $row_reason_tbl = mysqli_fetch_array($result_reason_tbl);
	
	if($row_display_reason	> 0)
	{   
		?>
        <table class="table">
  <tr>
    <th width="13%">Reason</th>
    <th width="3%">:</th>
    <td width="84%"><?php echo $row_reason_tbl["reason_desc_cancel"].' - ' . $row_display_reason["reason_cancel2"]; ?></td>
  </tr>
</table>
<?php
  }

		
	$query_display_reason2 = "SELECT * from `consumable_request_close` WHERE temp_mrin = '".db_esc($dbc, $temp_mrin)."' AND status = 'Close'";
    $result_display_reason2 = mysqli_query($dbc, $query_display_reason2);
    $row_display_reason2 = mysqli_fetch_array($result_display_reason2);
	
	$query_reason_tbl2 = "SELECT * from `reason_req_close` WHERE id_close = '".db_esc($dbc, $row_display_reason2["reason_close"])."'";
	 $result_reason_tbl2 = mysqli_query($dbc, $query_reason_tbl2);
    $row_reason_tbl2 = mysqli_fetch_array($result_reason_tbl2);
	
	if($row_display_reason2	> 0)
	{   
		?>
   <table class="table">
   <tr>
    <th width="13%">Reason</th>
    <th width="3%">:</th>
    <td width="84%"><?php echo $row_reason_tbl2["reason_desc"].' - ' . $row_display_reason2["reason_close2"]; ?></td>
  </tr>
</table>
<?php
  }

?>

</form>
           
<br>   
<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part-->      
</div>

</body>
</html>
