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

$url = "wip_request_list.php";


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

  <?php

 $temp_mrin = $_GET["mrin_no"];

$queryu = "SELECT * from wip_request WHERE temp_mrin_wip = '$temp_mrin'";
$rs = mysqli_query($dbc, $queryu);   //run the query.


$query_2 = "SELECT *, DATE_FORMAT(MR.date_mrin,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2 from wip_request as MR, scan_detail_wip as SD WHERE MR.id_scan_wip = SD.id_scan AND MR.temp_mrin_wip = '$temp_mrin'";
$result_2 = mysqli_query($dbc, $query_2);   //run the query.
$data_2 = mysqli_fetch_array($result_2);

    $query3 = "SELECT * FROM factory_detail WHERE id_fac = '".$data_2["factory"]."'";
    $result3 = mysqli_query($dbc, $query3);
	$row3 = mysqli_fetch_array($result3);
	
    $query_k = "SELECT * from `user_detail` as uc WHERE uc.user_no = '".$data_2["user_create"]."'";
	$result_k = mysqli_query($dbc, $query_k);
	$row_k = mysqli_fetch_array($result_k);
	
	$query_k2 = "SELECT * from `user_detail` as uc2 WHERE uc2.username = '$username'";
	$result_k2 = mysqli_query($dbc, $query_k2);
	$row_k2 = mysqli_fetch_array($result_k2);

 ?>

<table class="table table-condensed">
<tr>
      <td width="1%">&nbsp;</td> 
      <td width="7%"><img src="../img/print2.jpg" width="48" height="48" onClick="window.print()" title="Print"/></td>
    <td width="7%"><a href="javascript:parent.tb_remove();" ><img src="../img/back3.jpg" width="48" height="48" /></a></td>
      <td width="85%"> <div class="small-nav"></div></td>
     
  </tr>

</table>

<table class="table">
<tr>
  <td>&nbsp;</td>
  <td colspan="2"><div align="center"><span class="style4">MRIN LIST
    <?php if($data_2["status_urgent"] == "Y"){ echo "[Urgent]"; }     ?>
  </span></div></td>
  </tr>
</table>

      <table class="table table-bordered">
        <tr>
          <th>MRIN No</th>
          <th>:</th>
          <th><?php echo $temp_mrin; ?></th>
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
          <th>&nbsp;<?php echo $data_2["R"]; ?>&nbsp;<?php echo $data_2["time_mrin"]; ?></th>
        </tr>
        <tr>
          <th>Requested by</th>
          <th>:</th>
          <th><?php echo $row_k["user_fullname"]; ?></th>
          <th>Prepared by (WIP Supply)</th>
          <th>:</th>
          <th><?php echo $row_k2["user_fullname"]; ?></th>
        </tr>
      </table>

             <table class="table table-bordered">
             <thead>
               <tr>
                 <th>Material Number</th>
                 <th>Material Description</th>
                 <th>Quantity</th>
                 <th>Uom</th>
                 <th>Prod Order</th>
                 <th>Material Produce</th>
                 <th>Line</th>
                 <th>Transfer Location</th>
                 <th>Transfer Quantity</th>
                 <th>Received Sloc</th>
                 <th>Barcode</th>
               </tr></thead></tbody>
             
             <?php
     	 $counter = 1;
  		 $no = 1;
    	 $k = 0;
  
   while ($row = mysqli_fetch_array($rs))
   {
		//$user_no = $row[0]; 
 $no = sprintf('%03d', $no);
		
   $query_scan = "SELECT * FROM scan_detail_wip WHERE id_scan = '".$row[6]."'";
   $result_scan = mysqli_query($dbc, $query_scan);
   $row_scan = mysqli_fetch_array($result_scan);

		 
   $query1_p = "SELECT * FROM scan_detail_wip WHERE id_scan = '".$row[6]."' GROUP BY id_scan";
   $result1_p = mysqli_query($dbc, $query1_p);
   $row1_p = mysqli_fetch_array($result1_p);
	
	
		 
  $query4_p = "SELECT * from mat_master_header as LD, mat_master_detail as SD WHERE SD.id_hdr = LD.id_hdr and SD.id_dtl = '".$row[5]."'";
  $result4_p = mysqli_query($dbc, $query4_p);
  $row4_p = mysqli_fetch_array($result4_p); 
		  	 
	if(($row4_p["mat_type"] == "Z200") && ($data_2["factory"] == "1"))
	{
	  $sta = "S120";
	} elseif(($row4_p["mat_type"] == "Z200") && ($data_2["factory"] == "2"))
	{
	  $sta = "S220";
	} elseif(($row4_p["mat_type"] == "Z200") && ($data_2["factory"] == "3"))
	 {
	  $sta = "S220";
	  }elseif($row4_p["mat_type"] == "Z200")
	 {
	  $sta = "";
	  }
	  else{
	  $sta = "Invalid";
	  }
	  
		 
		if(($row["bom_qty_wip"] != "") && ($row["bom_qty_wip"] != "0.000"))
		{
		 
	
			 
			 
			  if ($k && $k % 7 == 0)  
		echo '<tr style="page-break-before:always">';  
	else if ($k)  
		echo '<tr>';  
	++$k; 
	
	?>
                <td width="100"><?php  echo $row4_p["bill_component"]; ?></td>
                <td width="122"><?php  echo $row4_p["material_desc_c"]; ?></td>
                <td width="100"><div align="right"><?php echo $row["bom_qty_wip"]; ?>&nbsp;</div></td>
                <td width="45"><div align="center"><?php echo $row["bom_oum_wip"]; ?></div></td>
                <td width="120"><div align="center"><?php echo $row_scan["prod_order"]; ?></div></td>
                <td width="93"><?php  echo $row4_p["material"]; ?></td>
                <td width="42"><div align="center"><font color="#FF0000"><?php echo $row1_p["work_center"]; ?></font></div></td>
                <td width="90"><div align="center"><?php echo $sta; ?></div></td>
                <td width="90">&nbsp;</td>
                <td width="65"><div align="center"><font color="#FF0000"><?php //echo $row4_p["sloc"]; ?></font></div></td>
                <td>
                  <div align="left">
                    <?php

// set the barcode content and type

$bar_text = ($row4_p["bill_component"].'|'.$row["bom_qty_wip"].'|'.$row["bom_oum_wip"].'|'.$row_scan["prod_order"].'|'.$temp_mrin.'|'.$sta);

$barcodeobj = new TCPDF2DBarcode($bar_text, 'PDF417');
echo $barcodeobj->getBarcodeSVGcode(1.0, 0.8, 'black');

?>
                   </div></td> </tr>
     
         <?php 
		   
		   }// end if else
		
		  
		  $counter++; // menambah counter 
		 
			   
			 $no ++;   
			   
			   
			   }
	
			   ?>         
        </tbody>
        </table> 
         
         <?php
		
	$query_display_reason = "SELECT * from `wip_request_cancel` WHERE temp_mrin_wip = '$temp_mrin' AND status = 'Cancel'";
    $result_display_reason = mysqli_query($dbc, $query_display_reason);
    $row_display_reason = mysqli_fetch_array($result_display_reason);
	
	$query_reason_tbl = "SELECT * from `reason_req_cancel` WHERE id_cancel = '".$row_display_reason["reason_cancel"]."'";
	 $result_reason_tbl = mysqli_query($dbc, $query_reason_tbl);
    $row_reason_tbl = mysqli_fetch_array($result_reason_tbl);
	
	if($row_display_reason	> 0)
	{   
		?>
    <table class="table table-bordered">
    <thead>
    <tr>
    <th width="13%">Reason</th>
    <th width="3%" >:</th>
    <th width="84%" ><?php echo $row_reason_tbl["reason_desc_cancel"].' - ' . $row_display_reason["reason_cancel2"]; ?><br></th>
  </tr></thead>
</table>
<?php
  }

		
	$query_display_reason2 = "SELECT * from `wip_request_close` WHERE temp_mrin_wip = '$temp_mrin' AND status = 'Close'";
    $result_display_reason2 = mysqli_query($dbc, $query_display_reason2);
    $row_display_reason2 = mysqli_fetch_array($result_display_reason2);
	
	$query_reason_tbl2 = "SELECT * from `reason_req_close` WHERE id_close = '".$row_display_reason2["reason_close"]."'";
	 $result_reason_tbl2 = mysqli_query($dbc, $query_reason_tbl2);
    $row_reason_tbl2 = mysqli_fetch_array($result_reason_tbl2);
	
	if($row_display_reason2	> 0)
	{   
		?>
   <table class="table table-bordered">
   <thead>
   <tr>
    <th width="13%">Reason</th>
    <th width="3%">:</th>
    <th width="84%"><?php echo $row_reason_tbl2["reason_desc"].' - ' . $row_display_reason2["reason_close2"]; ?><br></th>
  </tr></thead>
</table>
<?php
  }


?>
<br> 

<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part--> 
</div>
</body>
</html>