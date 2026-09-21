<?php

/**
 * prod/cancel_backflush_tran_proc.php
 * Part of: Production module
 * Filename suggests: cancel backflush tran proc
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, run_count_no, pps_detail_transaction, pps_detail_cancellation, pps_detail.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, tcpdf_barcodes_2d.php, footer.php.
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

$url = "cancellation_list_backflush_tran.php";


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

 $buid = $_GET["buid"];
 
 
  if(isset($_POST["cancel_btnA"])) 
  
   { // handle the form.

// create a function for escaping the data.
function escape_data ($data) {
global $dbc;   // need the connection.
if(ini_get('magic_quotes_gpc')) {
    $data = stripslashes($data);
	}
	return mysqli_real_escape_string($dbc, $data);
	}   // end function.
$message = NULL; // create an empty new variable.

   
   $buid = $_POST["buid"];
   
   
  
  
  //-------create Cancellation-Backflush OK No.---------------------------------
	
	$query_id = "SELECT count_max FROM run_count_no WHERE uid = '20'";
	$result_id = mysqli_query($dbc, $query_id);
	
	if ($result_id) 
{
	$nrows = mysqli_num_rows($result_id);
	$row_id = mysqli_fetch_array($result_id);
	
	$dht = 0000000; 
	$dht_OK = "22212";
	$dg2 = 0;

  	if($row_id["count_max"] <= 0)
  	{ 
   
    	$lastID = ($row_id["count_max"] + 1);
    	$dg = ($dht + ($lastID));
   }
   else
   {
      $lastID = ($row_id["count_max"] + 1);
      $dg =  $lastID;
	
    }
	$number = $dg; // Length of running no
    $number = sprintf('%07d', $number);  
	
	  $ref = ($dht_OK.($number));
	 // $ref = (($data_setup["comp_code"]).$dht_OK.($number));
	
	} // end if $result_id
  
  
  
 // echo $ref;
  
  
   
    //--------- pps detail ---------
	 
	   $query_pps = "SELECT * FROM pps_detail_transaction WHERE id = '".db_esc($dbc, $buid)."'";
	   $result_pps = mysqli_query($dbc, $query_pps);
	   $data_pps = mysqli_fetch_array($result_pps);
	   
	 //insert into table pps_detail_cancellation-------------

  
	
$query_data2 = "INSERT INTO pps_detail_cancellation(id,pps_id,ref_id,bflush_no,plan_no,id_scan,upload_id,model_code,month_plan,material_no,material_desc,material_type,qty_plan,qty_actual,qty_balance,qty_NG,status_pps,comp_code,work_center,shift_pps1,shift_pps2,date_plan,status,user_upload,date_upload,user_create,date_create,user_update,date_update,user_posting,date_posting,time_posting,ploc,delivery_loc,type_reject,reason_reject,user_reject,date_reject,time_reject,status_ftp_bflush,bflush_no_ref,user_cancel,date_cancel,remark_cancel,plant_code,shift_posting) VALUES('".db_esc($dbc, $data_pps["id"])."','".db_esc($dbc, $data_pps["pps_id"])."','".db_esc($dbc, $data_pps["ref_id"])."','".db_esc($dbc, $data_pps["bflush_no"])."','".db_esc($dbc, $data_pps["plan_no"])."','".db_esc($dbc, $data_pps["id_scan"])."','".db_esc($dbc, $data_pps["upload_id"])."','".db_esc($dbc, $data_pps["model_code"])."','".db_esc($dbc, $data_pps["month_plan"])."','".db_esc($dbc, $data_pps["material_no"])."','".db_esc($dbc, $data_pps["material_desc"])."','".db_esc($dbc, $data_pps["material_type"])."','".db_esc($dbc, $data_pps["qty_plan"])."','".db_esc($dbc, $data_pps["qty_actual"])."','".db_esc($dbc, $data_pps["qty_balance"])."','".db_esc($dbc, $data_pps["qty_NG"])."','".db_esc($dbc, $rst_sta4["status_desc"])."','".db_esc($dbc, $data_pps["comp_code"])."','".db_esc($dbc, $data_pps["work_center"])."','".db_esc($dbc, $data_pps["shift_pps1"])."','".db_esc($dbc, $data_pps["shift_pps2"])."','".db_esc($dbc, $data_pps["date_plan"])."','N','".db_esc($dbc, $data_pps["user_upload"])."','".db_esc($dbc, $data_pps["date_upload"])."','".db_esc($dbc, $data_pps["user_create"])."','".db_esc($dbc, $data_pps["date_create"])."','".db_esc($dbc, $data_pps["user_update"])."','".db_esc($dbc, $data_pps["date_update"])."','".db_esc($dbc, $data_pps["user_posting"])."','".db_esc($dbc, $data_pps["date_posting"])."','".db_esc($dbc, $data_pps["time_posting"])."','".db_esc($dbc, $data_pps["ploc"])."','".db_esc($dbc, $data_pps["delivery_loc"])."','".db_esc($dbc, $data_pps["type_reject"])."','".db_esc($dbc, $data_pps["reason_reject"])."','".db_esc($dbc, $data_pps["user_reject"])."','".db_esc($dbc, $data_pps["date_reject"])."','".db_esc($dbc, $data_pps["time_reject"])."','Y','".db_esc($dbc, $ref)."','".db_esc($dbc, $username)."',NOW(),'".db_esc($dbc, $data_pps["remark_cancel"])."','".db_esc($dbc, $data_pps["plant_code"])."','".db_esc($dbc, $data_pps["shift_posting"])."')";
$result_data2 = mysqli_query($dbc, $query_data2) or die(db_fail($dbc));   

   //---------update cancellation--------------------------
	 
	  $query_cancel = "UPDATE pps_detail_transaction SET bflush_no_ref = '".db_esc($dbc, $ref)."', status_pps = '".db_esc($dbc, $rst_sta4["status_desc"])."', status = 'N', user_cancel = '".db_esc($dbc, $username)."', date_cancel = NOW() WHERE id = '".db_esc($dbc, $buid)."'";
	$result_cancel = mysqli_query($dbc, $query_cancel);
	
  
  //--update status "Inprogress" to "Release" in table pps_detail	
	  
	  $query_all_info = "SELECT * FROM pps_detail_transaction WHERE status_pps != '".db_esc($dbc, $rst_sta4["status_desc"])."' AND plan_no = '".db_esc($dbc, $data_pps["plan_no"])."'";
	  $result_all_info = mysqli_query($dbc, $query_all_info);
	  $data_all_info = mysqli_fetch_array($result_all_info); 
	  	
	  if($data_all_info < 1)
	  {
		  
	$query_upd_detail = "UPDATE pps_detail SET status_pps = '".db_esc($dbc, $rst_sta2["status_desc"])."' WHERE plan_no = '".db_esc($dbc, $data_pps["plan_no"])."'";
	$result_upd_detail = mysqli_query($dbc, $query_upd_detail) or die(db_fail($dbc));
	
	  }
		  
	
 //------- crete text file to SAP [FolderPortal] -----------
 	 
	 // if($result_cancel)
	 //{ 
	 
	 
	 //update count_max----------------------------------------
		
	
       $query_max_a = "UPDATE run_count_no SET count_max = '".$number."', date_updated = NOW() WHERE uid = '20'";
	   $result_max_a = mysqli_query($dbc, $query_max_a);
	 
   //end update count_max ---------------------------------	
	
	
		   echo "<script>";
		   echo "alert('Backflush Document No : $ref');";
		   echo "window.location='ftp_bflush_SAP_cancel.php?buid=$buid&&buid2A=$ref'";
	       echo "</script>"; 
		   exit(); //quit the script
		 
	
   // }
	

   }// end submit
?>
<table class="table">
<tr>
  <td>&nbsp;</td>
  <td colspan="2"><div align="center"><span class="style4">Backflush Cancellation</span></div></td>
  </tr>
</table>
<p>&nbsp;</p> 
   <form action="cancel_backflush_tran_proc.php?buid=<?php echo $buid; ?>" method="post" name="frmSearch" id="frmSearch">    <table class="table table-bordered data-table">
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
        <th>Document Date</th>
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
   
   $query_display = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') as R, DATE_FORMAT(date_posting,'%d-%m-%Y') as R2, DATE_FORMAT(date_create,'%d-%m-%Y') as R3 FROM pps_detail_transaction WHERE id = '".db_esc($dbc, $buid)."' AND status_pps = '".db_esc($dbc, $rst_sta7["status_desc"])."'";
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
	
	/*$sta_out = substr($row2["bflush_no"],4,1);
	
	if($sta_out == "1")
	{
		$status_output = "OK";
	}elseif($sta_out == "3")
	{
	    $status_output = "NG";
	}else{
	     $status_output = "";
	}*/
	
	
	$sta_out = substr($row2["bflush_no"],2,3);
	
	
	if($sta_out == "211")
	{
		$status_output = "OK";
	}elseif($sta_out == "221")
	{
	    $status_output = "NG";
	}else{
	     $status_output = "";
	}
	 
      ?>
      <tr class="gradeX">
        <td width="30"><?php echo $no; ?></td>
        <td><?php echo $row2["material_no"]; ?></td>
        <td width="80"><font color="#0000CC"><?php echo $row2["plan_no"]; ?></font></td>
        <td width="80"><?php echo $row2["R"]; ?></td>
        <td width="60"><?php echo $row2["work_center"]; ?></td>
        <td width="40"><?php echo $sta; ?></td>
        <td width="50"><?php echo $row2["ploc"]; ?></td>
        <td width="90"><font color="#0000CC"><?php echo $row2["bflush_no"]; ?></font></td>
        <td width="80"><?php echo $row2["R2"]; ?></td>
        <td width="48"><?php echo $row2["time_posting"]; ?></td>
        <td width="80"><?php echo $row2["R3"]; ?></td>
        <td width="50"><font color="#FF9900"><?php echo $status_output; ?></font></td>
        <td width="60"><?php echo $qty_final; ?></td>
        <td width="99"><?php echo $row2["status_pps"]; ?></td>
        <input name="buid" type="hidden" value="<?php echo $row2["id"]; ?> ">
      </tr> 
      
      <?php 
		  
		  $no++;
		  $counter++; // menambah counter
		  } 
		  ?>
        
      </tr>  
     
    </tbody>
  </table> 
  <table>
    <tr><td>&nbsp;<input name="cancel_btnA" type="submit"  class="btn btn-danger" id="button" value="CANCELLATION" onClick="return confirm('Are you sure want to perform this activity?');"/></td>
</tr>
       </table>
  </form>
  <p>&nbsp;</p>
  <p><br> 
    
    <!--Footer-part-->
  </p>
  <?php include "footer.php";   ?>
<!--end-Footer-part--> 
</div>
</body>
</html>