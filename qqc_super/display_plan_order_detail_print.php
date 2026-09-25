<?php

/**
 * qqc_super/display_plan_order_detail_print.php
 * Part of: QQC module (supervisor/admin tier)
 * Filename suggests: display plan order detail print
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, pps_detail, pps_detail_transaction, qqc_detail_transaction, qqc_transaction.
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
require_role($dbc, 11);
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

$url = "report_all_planning_module.php";


    $query2 = "SELECT * FROM user_detail WHERE username = ?"; $query2_args = [$username];
    $result2 = db_query_bind($dbc, $query2, $query2_args) or die(db_fail($dbc));
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

//CR status (Pending)
$sta8 = "SELECT * from request_status WHERE status_id = '8' ";
$sta_res8 = mysqli_query($dbc, $sta8);
$rst_sta8 = mysqli_fetch_array($sta_res8);	

	?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo h($data_setup["title_desc"]); ?></title>
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
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

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
	 
	   $query_pps = "SELECT * FROM pps_detail WHERE id = ?"; $query_pps_args = [$uid];
	   $result_pps = db_query_bind($dbc, $query_pps, $query_pps_args);
	   $data_pps = mysqli_fetch_array($result_pps);
 

?>
<table class="table table-condensed">
<tr>
      <td width="1%">&nbsp;</td>
       <td width="14%"><img src="../img/print2.jpg" width="48" height="48" onClick="window.print()" title="Print"/></td>
      <td width="85%"> <div class="small-nav"></div></td>
     
  </tr>

</table>
<table class="table">
<tr>
  <td><div align="center"><span class="style4">Planned Order Details</span></div></td>
  </tr>
<tr>
  <td>Planned Order No. : <?php echo h($data_pps["plan_no"]);  ?></td>
</tr>
</table> 
  <table class="table table-bordered data-table">
    <thead>
      <tr>
        <th>No.</th>
        <th width="241">Document No.</th>
        <th>Posting Date</th>
        <th>Document Date</th>
        <th>Output Quantity</th>
        <th>Output Status</th>
      </tr>
    </thead>
    <tbody>
      <?php
   
   $counter = 1;
   $no = 1;
   $sta_out = "";
   
   $query_display = "SELECT *, DATE_FORMAT(MR.date_plan,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2, DATE_FORMAT(MR.date_create,'%d-%m-%Y') as R3 FROM pps_detail_transaction AS MR WHERE MR.pps_id = ?"; $query_display_args = [$uid];
   $result_display = db_query_bind($dbc, $query_display, $query_display_args);   //run the query.
   
   while ($row2 = mysqli_fetch_array($result_display))
   {
	
	 $query_display3 = "SELECT *, DATE_FORMAT(N.date_plan,'%d-%m-%Y') as B, DATE_FORMAT(N.date_qc_posting,'%d-%m-%Y') as B2, DATE_FORMAT(N.date_create,'%d-%m-%Y') as B3 FROM qqc_detail_transaction AS N WHERE N.bflush_no = ?"; $query_display3_args = [$row2["bflush_no"]];
$result_display3 = db_query_bind($dbc, $query_display3, $query_display3_args);   //run the query. 	
	
	
     $query_display2 = "SELECT *, DATE_FORMAT(M.date_plan,'%d-%m-%Y') as J, DATE_FORMAT(M.date_qc_posting,'%d-%m-%Y') as J2, DATE_FORMAT(M.date_create,'%d-%m-%Y') as J3 FROM qqc_transaction AS M WHERE M.bflush_no = ?"; $query_display2_args = [$row2["bflush_no"]];
$result_display2 = db_query_bind($dbc, $query_display2, $query_display2_args);   //run the query.


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
        <td width="60"><?php echo $no; ?><input name="uid" type="hidden" value="<?php echo h($row2["bflush_no"]); ?> "></td>
        <td><font color="#0000CC"><?php echo h($row2["bflush_no"]); ?></font></td>
        <td width="188"><?php echo h($row2["R2"]); ?></td>
        <td width="188"><?php echo h($row2["R3"]); ?></td>
        <td width="167"><?php echo h($qty_final); ?></td>
        <td width="161"><?php echo $status_output; ?></td>
      </tr>  
      
        <?php  
	   $no3 = 1;
	    
           while ($row3 = mysqli_fetch_array($result_display3))
        {
	   
	  
	   ?> 
         <tr class="gradeX">
        <td width="60"><?php //echo $no3; ?><input name="uid3" type="hidden" value="<?php echo h($row3["qqc_doc_no"]); ?> "></td>
        <td><font color="#0000CC"><?php echo h($row3["bflush_no"]); ?></font><br><?php echo h($row3["qqc_doc_no"]); ?></td>
        <td width="188"><?php echo h($row3["B2"]); ?></td>
        <td width="188"><?php echo h($row3["B3"]); ?></td>
        <td width="167"><?php echo h($row3["qty_balance"]); ?></td>
        <td width="161"><?php echo h($row3["status_QC"]); ?></td>
        </tr>   
       
        <?php 
		
		$no3++;
		
           }  ?>
       
        <?php 
		 $no2 = "a"; 
	     $sta_out2 = "";
		   
     while($row_rst_display2 = mysqli_fetch_array($result_display2))
   {
	   
//quantity output
		if($row_rst_display2["qty_qc_ok"] != "0.000")
	{
		$qty_final2 = $row_rst_display2["qty_qc_ok"];
	}elseif($row_rst_display2["qty_qc_NG"] != "0.000")
	{
		$qty_final2 = $row_rst_display2["qty_qc_NG"];
	}else{
		$qty_final2 == " ";
	}
	
//status output
	//----check string -----------
	
	$sta_out2 = substr($row_rst_display2["qqc_no"],4,1);
	
	if($sta_out2 == "5")
	{
		$status_output2 = "QC OK";
	}elseif($sta_out2 == "7")
	{
	    $status_output2 = "QC NG";
	}else{
	     $status_output2 = "";
	}
	
  
     ?>   
        <tr class="gradeX">
        <td width="60"><?php echo $no2; ?><input name="uid2" type="hidden" value="<?php echo h($row_rst_display2["qqc_no"]); ?> "></td>
        <td><font color="#669999"><?php echo h($row_rst_display2["qqc_no"]); ?></font></td>
        <td width="188"><?php echo h($row_rst_display2["J2"]); ?></td>
        <td width="188"><?php echo h($row_rst_display2["J3"]); ?></td>
        <td width="167"><?php echo h($qty_final2); ?></td>
        <td width="161"><?php echo $status_output2; ?></td>
      </tr> 
      
      <?php 
	      $no2 ++;
		  
            }
		  
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