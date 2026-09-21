<?php

/**
 * qqc/cancel_qc_rework_output.php
 * Part of: QQC module (Quality)
 * Filename suggests: cancel qc rework output
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, qqc_transaction, table_material, run_count_no, ftp_goodtran_detail, qqc_transaction_cancel2, Cancel, pps_detail_transaction.
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
require_role($dbc, 10);
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

$url = "cancellation_QC_output_list_tran.php";


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

$sta = "SELECT * from request_status WHERE status_id = '1'";
$sta_res = mysqli_query($dbc, $sta);
$rst_sta = mysqli_fetch_array($sta_res);

//CR status (Released)
$sta2 = "SELECT * from request_status WHERE status_id = '2'";
$sta_res2 = mysqli_query($dbc, $sta2);
$rst_sta2 = mysqli_fetch_array($sta_res2);	

//CR status (Cancel)
$sta4 = "SELECT * from request_status WHERE status_id = '4'";
$sta_res4 = mysqli_query($dbc, $sta4);
$rst_sta4 = mysqli_fetch_array($sta_res4);	

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7'";
$sta_res7 = mysqli_query($dbc, $sta7);
$rst_sta7 = mysqli_fetch_array($sta_res7);	

//CR status (Delete)
$sta16 = "SELECT * from request_status WHERE status_id = '16'";
$sta_res16 = mysqli_query($dbc, $sta16);
$rst_sta16 = mysqli_fetch_array($sta_res16);

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
<SCRIPT LANGUAGE="JavaScript">
<!-- 

<!-- Begin
function Check(chk)
{
if(document.myform.Check_ctr.checked==true){
for (i = 0; i < chk.length; i++)
chk[i].checked = true ;
}else{

for (i = 0; i < chk.length; i++)
chk[i].checked = false ;
}
}

// End -->
</script>
<?php
//echo "the following values have been checked: ";
$checked="";
$amount ="";

$a = array();
if(isset($_POST["cancel"])) {
	foreach($_POST["cancel"] as $j=>$i) {
	   	$checked .= ($checked==""?"":",") . "checkbox" . $i;
		
		array_push($a, $i);
	//	 array_push($amount, $i);
	}
}
//echo $checked;
//echo $amount;

function was_checked($i,$a) {
if(in_array($i, $a)===true) {
return "checked='checked'";
return "";
}
}

?>
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
 
 
  if(isset($_POST["cancel_btn"])) 
  
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
   
    $uid = $_POST["uid"];
   
    $cancel = $_POST["cancel"]; 
    $how_many = count($cancel); 
	$sta_out = "";
	$data = "";
	$data2 = "";
	
						
		   for ($i=0; $i<$how_many; $i++) { 

  
		
  
  		$query_ftp = "SELECT *, DATE_FORMAT(M.date_plan,'%d-%m-%Y') as J, DATE_FORMAT(M.date_cancel,'%d-%m-%Y') as J2, DATE_FORMAT(M.date_cancel,'%H:%i:%s') as J3 FROM qqc_transaction AS M WHERE M.id_qqc = '".db_esc($dbc, $cancel[$i])."'";
		$result_ftp = mysqli_query($dbc, $query_ftp);   //run the query.
		$data_ftp = mysqli_fetch_array($result_ftp);
		
		$query_q2 = "SELECT * FROM table_material WHERE material_no = '".db_esc($dbc, $data_ftp["material_no"])."'";
        $result_q2 = mysqli_query($dbc, $query_q2) or die(db_fail($dbc));
        $ans3 = mysqli_fetch_array($result_q2);
  
    //------ftp text file for cancel from FromPortal2 to SAP ------- //
  
  	//----check string -----------
	
	$sta_out = substr($data_ftp["qqc_no"],2,3);
	
	//22421
	
	if($sta_out == "421")
	{
		
	  //------generate Rework QC List (OK) Cancellation---------------------------------
	$ref = "";
	
	$query_id = "SELECT count_max FROM run_count_no WHERE uid = '34'";
	$result_id = mysqli_query($dbc, $query_id);
	
	if ($result_id) 
{
	$nrows = mysqli_num_rows($result_id);
	$row_id = mysqli_fetch_array($result_id);
	
	$dht = 0000000; 
	$dht_OK = "22422";
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
		
	} // end if $result_id

	// echo $ref;	   			
		
		
  $query_upd_detail = "UPDATE qqc_transaction SET status_QC = '".db_esc($dbc, $rst_sta4["status_desc"])."', qqc_no_ref = '".db_esc($dbc, $ref)."', user_cancel = '".db_esc($dbc, $username)."', date_cancel = NOW() WHERE id_qqc = '".db_esc($dbc, $cancel[$i])."'";
  $result_upd_detail = mysqli_query($dbc, $query_upd_detail) or die(db_fail($dbc));
  		
	
	 $data .= $data_ftp["qqc_doc_no"].";".$ref.";".$data_ftp["qqc_no"].";".$data_ftp["comp_code"].";".$data_ftp['material_no'].";312;".$data_ftp['qty_qc_ok'].";".$ans3['BUn'].";".$data_ftp['ploc_qc'].";".$data_ftp['ploc'].";".$data_ftp['J2'].";".$data_ftp['user_cancel']."\r\n";
	 
	 	$filen = "TP4".$ref;

$file = "../FromPortal2/GT9/".$filen.".csv";
file_put_contents($file,$data);	

//----------update table ftp_goodtran_detail------------
   
    $query_ftp_info = "INSERT INTO ftp_goodtran_detail(id, file_name, qqc_no, bflush_no, id_tran, plan_no, material_no, material_desc, qty_ftp, uom, status_ftp, posting_date, posting_time, user_create, date_create) VALUES('','".db_esc($dbc, $filen)."','".db_esc($dbc, $data_ftp["qqc_no_ref"])."',".$data_ftp["bflush_no"].",'".db_esc($dbc, $data_ftp["id_tran"])."','".db_esc($dbc, $data_ftp["plan_no"])."','".db_esc($dbc, $data_ftp["material_no"])."','".db_esc($dbc, $data_ftp["material_desc"])."','".db_esc($dbc, $data_ftp["qty_qc_ok"])."','".db_esc($dbc, $ans3['BUn'])."','Y','".db_esc($dbc, $data_ftp["date_cancel"])."','".db_esc($dbc, $data_ftp["J3"])."','".db_esc($dbc, $username)."',NOW())"; 
     $rst_ftp_info = mysqli_query($dbc, $query_ftp_info);
	 
	 
	 //------insert table qqc_transaction_cancel2 -------------
 
  $query_qqc =  "SELECT * FROM qqc_transaction WHERE id_qqc = '".db_esc($dbc, $cancel[$i])."'"; 
  $result_qqc = mysqli_query($dbc, $query_qqc);
  $data_qqc = mysqli_fetch_array($result_qqc);
 
 
   $query_copy = "INSERT INTO qqc_transaction_cancel2(id_qqc, id_tran, qqc_doc_no, qqc_no, bflush_no, plan_no, material_no, material_desc, material_type, qty_plan, qty_actual, qty_balance, qty_NG, qty_qc, qty_qc_ok, qty_qc_NG, status_QC, comp_code, work_center, shift_day, date_plan, user_create, date_create, user_update, date_update, user_qc_posting, date_qc_posting, time_qc_posting, ploc_qc, ploc, delivery_loc, type_qc_reject, reason_qc_reject, user_qc_reject, date_qc_reject, time_qc_reject, status_ftp_fgtran, status, qqc_no_ref, user_cancel, date_cancel) VALUES('','".db_esc($dbc, $data_qqc["id_tran"])."','".db_esc($dbc, $data_qqc["qqc_doc_no"])."','".db_esc($dbc, $data_qqc["qqc_no"])."','".db_esc($dbc, $data_qqc["bflush_no"])."','".db_esc($dbc, $data_qqc["plan_no"])."','".db_esc($dbc, $data_qqc["material_no"])."', '".db_esc($dbc, $data_qqc["material_desc"])."','".db_esc($dbc, $data_qqc["material_type"])."','".db_esc($dbc, $data_qqc["qty_plan"])."','".db_esc($dbc, $data_qqc["qty_actual"])."','".db_esc($dbc, $data_qqc["qty_balance"])."','".db_esc($dbc, $data_qqc["qty_NG"])."','".db_esc($dbc, $data_qqc["qty_qc"])."','".db_esc($dbc, $data_qqc["qty_qc_ok"])."','".db_esc($dbc, $data_qqc["qty_qc_NG"])."','".db_esc($dbc, $rst_sta4["status_desc"])."','".db_esc($dbc, $data_qqc["comp_code"])."', '".db_esc($dbc, $data_qqc["work_center"])."', '".db_esc($dbc, $data_qqc["shift_day"])."','".db_esc($dbc, $data_qqc["date_plan"])."','".db_esc($dbc, $data_qqc["user_create"])."','".db_esc($dbc, $data_qqc["date_create"])."','".db_esc($dbc, $data_qqc["user_update"])."','".db_esc($dbc, $data_qqc["date_update"])."','".db_esc($dbc, $data_qqc["user_qc_posting"])."','".db_esc($dbc, $data_qqc["date_qc_posting"])."','".db_esc($dbc, $data_qqc["time_qc_posting"])."','".db_esc($dbc, $data_qqc["ploc_qc"])."','".db_esc($dbc, $data_qqc["ploc"])."','".db_esc($dbc, $data_qqc["delivery_loc"])."','".db_esc($dbc, $data_qqc["type_qc_reject"])."','".db_esc($dbc, $data_qqc["reason_qc_reject"])."','".db_esc($dbc, $data_qqc["user_qc_reject"])."','".db_esc($dbc, $data_qqc["date_qc_reject"])."','".db_esc($dbc, $data_qqc["time_qc_reject"])."','Y','Y','".db_esc($dbc, $data_qqc["qqc_no_ref"])."','".db_esc($dbc, $data_qqc["user_cancel"])."','".db_esc($dbc, $data_qqc["date_cancel"])."')";
   $result_copy = mysqli_query($dbc, $query_copy) or die(db_fail($dbc));
	 
	
	 //update count_max----------------------------------------
	
       $query_max_a = "UPDATE run_count_no SET count_max = '".$number."', date_updated = NOW() WHERE uid = '34'";
	   $result_max_a = mysqli_query($dbc, $query_max_a);
	 
   //end update count_max ---------------------------------		 
   
	}elseif($sta_out == "331")
	{
		
	//22331	
		  //------generate Rework QC List (NG) Cancellation---------------------------------
	
	$ref_2 = "";
	
	$query_id_2 = "SELECT count_max FROM run_count_no WHERE uid = '28'";
	$result_id_2 = mysqli_query($dbc, $query_id_2);
	
	if ($result_id_2) 
{
	$nrows_2 = mysqli_num_rows($result_id_2);
	$row_id_2 = mysqli_fetch_array($result_id_2);
	
	$dht_2 = 0000000; 
	$dht_OK_2 = "22332";
	$dg2_2 = 0;

  	if($row_id_2["count_max"] <= 0)
  	{ 
   
    	$lastID_2 = ($row_id_2["count_max"] + 1);
    	$dg_2 = ($dht_2 + ($lastID_2));
   }
   else
   {
      $lastID_2 = ($row_id_2["count_max"] + 1);
      $dg_2 =  $lastID_2;
	
    }
	$number2 = $dg_2; // Length of running no
    $number2 = sprintf('%07d', $number2);  
	
	 $ref_2 = ($dht_OK_2.($number2));
		
	} // end if $result_id


 //echo $ref_2;
		   			
		
  $query_upd_detail = "UPDATE qqc_transaction SET status_QC = '".db_esc($dbc, $rst_sta4["status_desc"])."', qqc_no_ref = '".db_esc($dbc, $ref)."', user_cancel = '".db_esc($dbc, $username)."', date_cancel = NOW() WHERE id_qqc = '".db_esc($dbc, $cancel[$i])."'";
  $result_upd_detail = mysqli_query($dbc, $query_upd_detail) or die(db_fail($dbc));
  		
				
	 $data2 .= $data_ftp["qqc_doc_no"].";".$ref_2.";".$data_ftp["qqc_no"].";".$data_ftp["comp_code"].";".$data_ftp['material_no'].";552;".$data_ftp['qty_qc_NG'].";".$ans3['BUn'].";".$data_ftp['ploc_qc'].";".$data_ftp['ploc'].";".$data_ftp['J2'].";".$data_ftp['user_cancel']."\r\n";
		
	$filen = "GI3".$ref_2;

$file = "../FromPortal2/GT9/".$filen.".csv";
file_put_contents($file,$data2);	

//----------update table ftp_goodtran_detail------------
   
    $query_ftp_info = "INSERT INTO ftp_goodtran_detail(id, file_name, qqc_no, bflush_no, id_tran, plan_no, material_no, material_desc, qty_ftp, uom, status_ftp, posting_date, posting_time, user_create, date_create) VALUES('','".db_esc($dbc, $filen)."','".db_esc($dbc, $data_ftp["qqc_no_ref"])."',".$data_ftp["bflush_no"].",'".db_esc($dbc, $data_ftp["id_tran"])."','".db_esc($dbc, $data_ftp["plan_no"])."','".db_esc($dbc, $data_ftp["material_no"])."','".db_esc($dbc, $data_ftp["material_desc"])."','".db_esc($dbc, $data_ftp["qty_qc_NG"])."','".db_esc($dbc, $ans3['BUn'])."','Y','".db_esc($dbc, $data_ftp["date_cancel"])."','".db_esc($dbc, $data_ftp["J3"])."','".db_esc($dbc, $username)."',NOW())"; 
     $rst_ftp_info = mysqli_query($dbc, $query_ftp_info);
	 
	 
	 //------insert table qqc_transaction_cancel2 -------------
 
  $query_qqc =  "SELECT * FROM qqc_transaction WHERE id_qqc = '".db_esc($dbc, $cancel[$i])."'"; 
  $result_qqc = mysqli_query($dbc, $query_qqc);
  $data_qqc = mysqli_fetch_array($result_qqc);
 
 
   $query_copy = "INSERT INTO qqc_transaction_cancel2(id_qqc, id_tran, qqc_doc_no, qqc_no, bflush_no, plan_no, material_no, material_desc, material_type, qty_plan, qty_actual, qty_balance, qty_NG, qty_qc, qty_qc_ok, qty_qc_NG, status_QC, comp_code, work_center, shift_day, date_plan, user_create, date_create, user_update, date_update, user_qc_posting, date_qc_posting, time_qc_posting, ploc_qc, ploc, delivery_loc, type_qc_reject, reason_qc_reject, user_qc_reject, date_qc_reject, time_qc_reject, status_ftp_fgtran, status, qqc_no_ref, user_cancel, date_cancel) VALUES('','".db_esc($dbc, $data_qqc["id_tran"])."','".db_esc($dbc, $data_qqc["qqc_doc_no"])."','".db_esc($dbc, $data_qqc["qqc_no"])."','".db_esc($dbc, $data_qqc["bflush_no"])."','".db_esc($dbc, $data_qqc["plan_no"])."','".db_esc($dbc, $data_qqc["material_no"])."', '".db_esc($dbc, $data_qqc["material_desc"])."','".db_esc($dbc, $data_qqc["material_type"])."','".db_esc($dbc, $data_qqc["qty_plan"])."','".db_esc($dbc, $data_qqc["qty_actual"])."','".db_esc($dbc, $data_qqc["qty_balance"])."','".db_esc($dbc, $data_qqc["qty_NG"])."','".db_esc($dbc, $data_qqc["qty_qc"])."','".db_esc($dbc, $data_qqc["qty_qc_ok"])."','".db_esc($dbc, $data_qqc["qty_qc_NG"])."','".db_esc($dbc, $rst_sta4["status_desc"])."','".db_esc($dbc, $data_qqc["comp_code"])."', '".db_esc($dbc, $data_qqc["work_center"])."', '".db_esc($dbc, $data_qqc["shift_day"])."','".db_esc($dbc, $data_qqc["date_plan"])."','".db_esc($dbc, $data_qqc["user_create"])."','".db_esc($dbc, $data_qqc["date_create"])."','".db_esc($dbc, $data_qqc["user_update"])."','".db_esc($dbc, $data_qqc["date_update"])."','".db_esc($dbc, $data_qqc["user_qc_posting"])."','".db_esc($dbc, $data_qqc["date_qc_posting"])."','".db_esc($dbc, $data_qqc["time_qc_posting"])."','".db_esc($dbc, $data_qqc["ploc_qc"])."','".db_esc($dbc, $data_qqc["ploc"])."','".db_esc($dbc, $data_qqc["delivery_loc"])."','".db_esc($dbc, $data_qqc["type_qc_reject"])."','".db_esc($dbc, $data_qqc["reason_qc_reject"])."','".db_esc($dbc, $data_qqc["user_qc_reject"])."','".db_esc($dbc, $data_qqc["date_qc_reject"])."','".db_esc($dbc, $data_qqc["time_qc_reject"])."','Y','Y','".db_esc($dbc, $data_qqc["qqc_no_ref"])."','".db_esc($dbc, $data_qqc["user_cancel"])."','".db_esc($dbc, $data_qqc["date_cancel"])."')";
   $result_copy = mysqli_query($dbc, $query_copy) or die(db_fail($dbc));
	 
	 
	  //update count_max----------------------------------------
		
	
       $query_max_b = "UPDATE run_count_no SET count_max = '".$number2."', date_updated = NOW() WHERE uid = '28'";
	   $result_max_b = mysqli_query($dbc, $query_max_b);
	 
   //end update count_max ---------------------------------		
	 


	}else{
	     $status_output = "";
	}
  
		
     } // end for loop
	
		   echo "<script>";
		   echo "alert('Cancellation has been updated');";
		   echo "parent.tb_remove(); parent.location.reload(1)";
		   echo "</script>"; 
		   exit(); //quit the script
	


   }// end submit
?>
<table class="table">
<tr>
  <td>&nbsp;</td>
  <td colspan="2"><div align="center"><span class="style4">Rework Cancellation</span></div></td>
  </tr>
</table>
<p>&nbsp;</p> 
   <form action="cancel_qc_rework_output.php?uid=<?php echo $uid; ?>" method="post" name="myform" id="myform">     
  <table class="table table-bordered data-table">
    <thead>
      <tr>
        <th>No.</th>
        <th width="140">Model</th>
        <th width="81">Document No.</th>
        <th>Part No.</th>
        <th>Storage Location</th>
        <th>Shift Day</th>
        <th>Date</th>
        <th>Quantity</th>
        <th>UOM</th>
        <th>Reason Reject</th>
        <th>Action</th>
        </tr>
    </thead>
    <tbody>
      <?php
   
   $counter = 1;
   $no = 1;
   $sta_out = "";
   $qty_final = 0.000;
   
$query_display = "SELECT *, DATE_FORMAT(MR.date_qc_posting,'%d-%m-%Y') as R2 FROM qqc_transaction AS MR WHERE MR.id_tran = '".db_esc($dbc, $uid)."' AND status_QC != '".db_esc($dbc, $rst_sta4["status_desc"])."'";
$result_display = mysqli_query($dbc, $query_display);   //run the query.
   
   while($row2 = mysqli_fetch_array($result_display))
   {
		
     $query_model =  "SELECT * FROM pps_detail_transaction WHERE plan_no = '".db_esc($dbc, $row2["plan_no"])."' ORDER BY plan_no ASC";		
	 $result_model = mysqli_query($dbc, $query_model);
     $row_model = mysqli_fetch_array($result_model); 	
	 
	//query material 
	 $query_mat =  "SELECT * FROM mat_master_header WHERE material_no = '".db_esc($dbc, $row2["material_no"])."'";		
	 $result_mat = mysqli_query($dbc, $query_mat);
     $row_mat = mysqli_fetch_array($result_mat); 
	 
	//query reason reject
	 $query_reason = "SELECT * FROM reason_ng_reject WHERE id_reject = '".db_esc($dbc, $row2["reason_qc_reject"])."'";
     $result_reason = mysqli_query($dbc, $query_reason);
	 $row_reason = mysqli_fetch_array($result_reason);	
	 
	 
	 //quantity output
	 if($row2["qty_qc_ok"] != "0.000")
	 {
		$qty_final = $row2["qty_qc_ok"]; 
	 }elseif($row2["qty_qc_NG"] != "0.000")
	 {
		$qty_final = $row2["qty_qc_NG"];  
	 }else{
		$qty_final == ""; 
	 }
	 
	 	 
	 
      ?>
      <tr class="gradeX">
        <td width="44"><?php echo $no; ?></td>
        <td><?php echo $row_model["model_code"]; ?></td>
        <td><?php echo $row2["qqc_no"]; ?></td>
        <td width="59"><font color="#0000CC"><?php echo $row2["material_no"]; ?></font></td>
        <td width="81"><?php echo $row2["ploc"]; ?></td>
        <td width="50"><?php  echo $row2["shift_day"]; ?></td>
        <td width="90"><?php echo $row2["R2"]; ?></td>
        <td width="69"><?php echo intval($qty_final); ?></td>
        <td width="80"><?php  echo $row_mat["BUn"]; ?></td>
        <td width="157"><?php echo $row_reason['reject_desc']; ?></td>
        <input name="uid" type="hidden" value="<?php echo $row2["id_tran"]; ?> ">
         <td width="50"><div align="center"><?php if(($row2["qqc_no_ref"]) == "") {  ?><input type="checkbox" name="cancel[]" value="<?php echo $row2["id_qqc"]; ?>" <?=was_checked($row2["id_qqc"],$a) ?> /><input type="hidden" name="Check_ctr" value="yes" onClick="Check(document.myform.cancel)"> <?php }  ?></div></td>
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
    <tr><td>&nbsp;<input name="cancel_btn" type="submit"  class="btn btn-danger" id="button" value="CANCELLATION" onClick="return confirm('Are you sure want to perform this activity?');"/></td>
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