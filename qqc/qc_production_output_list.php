<?php

/**
 * qqc/qc_production_output_list.php
 * Part of: QQC module (Quality)
 * Filename suggests: qc production output list
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, pps_detail_transaction, qqc_detail_transaction, run_count_no, ftp_qc_received_detail, qqc_transaction, mat_master_header, ftp_goodtran_detail.
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

$url = "production_output_list_tran.php";


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

//CR status (Pending)
$sta8 = "SELECT * from request_status WHERE status_id = '8' ";
$sta_res8 = mysqli_query($dbc, $sta8);
$rst_sta8 = mysqli_fetch_array($sta_res8);	

//CR status (QC Done)
$sta11 = "SELECT * from request_status WHERE status_id = '11' ";
$sta_res11 = mysqli_query($dbc, $sta11);
$rst_sta11 = mysqli_fetch_array($sta_res11);

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
 $qty_final = "";
 $qty_total_rec = 0.000;
 $total_qty_pending = 0.000;
 $status_new = "";

 
  //--------- pps detail ------------
	 
	   $query_pps = "SELECT * FROM pps_detail_transaction WHERE id = '".db_esc($dbc, $uid)."'";
	   $result_pps = mysqli_query($dbc, $query_pps);
	   $data_pps = mysqli_fetch_array($result_pps);
	   
   //quantity output
		if($data_pps["qty_actual"] != "0.000")
	{
		$qty_final = $data_pps["qty_actual"];
	}elseif($data_pps["qty_NG"] != "0.000")
	{
		$qty_final = $data_pps["qty_NG"];
	}else{
		$qty_final == " ";
	}
	
	
   //shift	
		if($data_pps["shift_pps1"] != "")
	{
		$sta = "D/S";
		
	}elseif($data_pps["shift_pps2"] != "")
	 {
		$sta = "N/S";
	 }else{
		 
		$sta = " ";
	 }	
	 
	   //--------- qc detail (calculate total qty_qc receive from production) ------------
	 
	   $query_qqc = "SELECT * FROM qqc_detail_transaction WHERE id_tran = '".db_esc($dbc, $uid)."' AND bflush_no = '".db_esc($dbc, $data_pps["bflush_no"])."'";
	   $result_qqc = mysqli_query($dbc, $query_qqc);
	   
	   while($data_qqc = mysqli_fetch_array($result_qqc))
	   {
		   
		$qty_total_rec = ($qty_total_rec + $data_qqc["qty_qc"]);   
		   
	   }
	 
	  //------quantity qc pending checking OK ------------
	$total_qty_pending = ($data_pps["qty_actual"] - ($qty_total_rec));
	
	//echo $total_qty_pending;
	
	 
	 
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
	

  $uid = $_POST["uid"];
  $qty_qc = $_POST["qty_qc"];
  $qty_bal = 0.000;

  
    if(($_POST["qty_qc"]) == "")
	{
	 $qty_qc = FALSE;
	 $message.= '<p align="center">You are required to enter Quantity!</p>';
	} else{
	 $qty_qc = TRUE;
	  }
	   
	   
	   if($qty_qc)
	   {
  
  
    //------generate QC Backflush No. [Prod Output List]---------------------------------
	$ref = "";
 
	 $query_id = "SELECT count_max FROM run_count_no WHERE uid = '31'";
	$result_id = mysqli_query($dbc, $query_id);
	
	if ($result_id) 
{
	$nrows = mysqli_num_rows($result_id);
	$row_id = mysqli_fetch_array($result_id);
	
	$dht = 0000000; 
	$dht_OK = "22411";
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
	 
	

     $qty_qc = $_POST["qty_qc"];

    //----calculation quantity balance -------
	$qty_bal = ($qty_final - ($qty_qc));
	
	//echo "balance".$qty_bal;
	
	//----update status_QC at qqc_detail_transaction---------
	
	if($qty_bal == "0.000")
	{
		  $status_new = "QC OK";
		  
	  }else{
		  
		  $status_new = "Pending";
	  }
	
	$ploc_qc = "P1RW"; 
	
 //insert into table qqc_detail_transaction-------------
	
$query_data2 = "INSERT INTO qqc_detail_transaction (id_qqc, id_tran, qqc_doc_no, bflush_no, plan_no, material_no, material_desc, material_type, qty_plan, qty_actual, qty_balance, qty_NG, qty_qc, qty_qc_ok, qty_qc_NG, status_QC, comp_code, work_center, shift_day, date_plan, user_create, date_create, user_update, date_update, user_qc_posting, date_qc_posting, time_qc_posting, ploc_qc, ploc, delivery_loc, type_qc_reject, reason_qc_reject, user_qc_reject, date_qc_reject, time_qc_reject, status_ftp_fgtran, qqc_doc_no_ref, user_cancel, date_cancel) VALUES('','".db_esc($dbc, $uid)."','".db_esc($dbc, $ref)."','".db_esc($dbc, $data_pps["bflush_no"])."','".db_esc($dbc, $data_pps["plan_no"])."','".db_esc($dbc, $data_pps["material_no"])."', '".db_esc($dbc, $data_pps["material_desc"])."','".db_esc($dbc, $data_pps["material_type"])."','".db_esc($dbc, $data_pps["qty_plan"])."','".db_esc($dbc, $qty_final)."','".db_esc($dbc, $qty_bal)."','','".db_esc($dbc, $_POST["qty_qc"])."','','','".db_esc($dbc, $status_new)."','".db_esc($dbc, $data_pps["comp_code"])."', '".db_esc($dbc, $data_pps["work_center"])."', '".$sta."','".db_esc($dbc, $data_pps["date_plan"])."','".db_esc($dbc, $username)."',NOW(),'','','".db_esc($dbc, $username)."',NOW(),NOW(),'".db_esc($dbc, $ploc_qc)."','".db_esc($dbc, $data_pps["ploc"])."','','','','','','','N','','','')";
$result_data2 = mysqli_query($dbc, $query_data2) or die(db_fail($dbc));
 

       $query_qqc = "SELECT * FROM qqc_detail_transaction WHERE id_qqc = '".mysqli_insert_id($dbc)."'";
	   $result_qqc = mysqli_query($dbc, $query_qqc);
	   $data_qqc = mysqli_fetch_array($result_qqc);
	   



   //----------create text file sent ftp to SAP[QC running no] hantar value Qty QC received --------------
    $data_rcv = "";
   

   $query_rcv_ftp = "SELECT *, DATE_FORMAT(date_qc_posting,'%Y-%m-%d') AS R, DATE_FORMAT(date_create,'%Y-%m-%d') AS R2, DATE_FORMAT(date_create,'%H:%i:%s') AS R3, DATE_FORMAT(date_plan,'%Y-%m-%d') AS J1 FROM qqc_detail_transaction WHERE id_tran = '".db_esc($dbc, $uid)."' AND qqc_doc_no = '".db_esc($dbc, $ref)."'";
   $result_rcv_ftp = mysqli_query($dbc, $query_rcv_ftp);
   $data_rcv_ftp = mysqli_fetch_array($result_rcv_ftp);
   
   
   
$data_rcv .= $ref.";".$data_rcv_ftp['bflush_no'].";".$data_rcv_ftp['comp_code'].";".$data_rcv_ftp['work_center'].";".$data_rcv_ftp['material_no'].";311;".$data_rcv_ftp['plan_no'].";".$data_rcv_ftp['J1'].";".$data_rcv_ftp['shift_day'].";".$data_rcv_ftp['qty_balance'].";PCS;".$data_rcv_ftp['ploc'].";".$data_rcv_ftp['ploc_qc'].";".$data_rcv_ftp['R'].";".$data_rcv_ftp['time_qc_posting'].";".$data_rcv_ftp['R2'].";".$data_rcv_ftp['R3'].";".$data_rcv_ftp['user_create']."\r\n";

$filen_rcv = "TP4".$ref;

$file_rcv = "../FromPortal2/GT/".$filen_rcv.".csv";
file_put_contents($file_rcv,$data_rcv);

   //----------update table ftp_qc_received_detail------------
   
    $query_rcv_ftp_info = "INSERT INTO ftp_qc_received_detail(id, file_name, qqc_doc_no, bflush_no, id_tran, plan_no, material_no, material_desc, qty_ftp, uom, status_ftp, posting_date, posting_time, user_create, date_create) VALUES('','".db_esc($dbc, $filen_rcv)."','".db_esc($dbc, $data_rcv_ftp["qqc_doc_no"])."',".$data_rcv_ftp["bflush_no"].",'".db_esc($dbc, $data_rcv_ftp["id_tran"])."','".db_esc($dbc, $data_rcv_ftp["plan_no"])."','".db_esc($dbc, $data_rcv_ftp["material_no"])."','".db_esc($dbc, $data_rcv_ftp["material_desc"])."','".db_esc($dbc, $data_rcv_ftp["qty_qc"])."','PCS','Y','".db_esc($dbc, $data_rcv_ftp["R"])."','".db_esc($dbc, $data_rcv_ftp["time_qc_posting"])."','".db_esc($dbc, $username)."',NOW())"; 
     $rst_rcv_ftp_info = mysqli_query($dbc, $query_rcv_ftp_info);
	 
	   // ---update status 

		$query_rcv_ftp2 = "UPDATE qqc_detail_transaction SET status_ftp_fgtran = 'Y' WHERE bflush_no = '".db_esc($dbc, $data_rcv_ftp["bflush_no"])."'";
		$rst_query_rcv_ftp2 = mysqli_query($dbc, $query_rcv_ftp2); //or die ("Error in query: $query_ftp"); 
		
				
  //---------------------------------------end ftp -------------------------------------------------   
   
     //update count_max----------------------------------------
		
	
       $query_max_a = "UPDATE run_count_no SET count_max = '".$number."', date_updated = NOW() WHERE uid = '31'";
	   $result_max_a = mysqli_query($dbc, $query_max_a);
	 
   //end update count_max ---------------------------------	
   
   
   
		
	 /*  $query_qqc = "SELECT * FROM qqc_detail_transaction WHERE id_qqc = '".mysqli_insert_id($dbc)."'";
	   $result_qqc = mysqli_query($dbc, $query_qqc);
	   $data_qqc = mysqli_fetch_array($result_qqc);*/
	   
	if($data_qqc["qty_balance"] == "0.000")
	{   
	   $ref_2 = "";
	   $data = "";
	   
	  //------generate Backflush No. [Rework List QC2]---------------------------------
 
	$query_id_2 = "SELECT count_max FROM run_count_no WHERE uid = '33'";
	$result_id_2 = mysqli_query($dbc, $query_id_2);
	
	if ($result_id_2) 
{
	$nrows_2 = mysqli_num_rows($result_id_2);
	$row_id_2 = mysqli_fetch_array($result_id_2);
	
	$dht_2 = 0000000; 
	$dht_OK_2 = "22421";
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
	$number_2 = $dg_2; // Length of running no
    $number_2 = sprintf('%07d', $number_2);  
	
	 
	  $ref_2 = ($dht_OK_2.($number_2));
	
	
	} // end if $result_id
	 
		
        //-------------insert data into table qqc_transaction-------------
		  
		$query_data3 = "INSERT INTO qqc_transaction (id_qqc, id_tran, qqc_doc_no, qqc_no, bflush_no, plan_no, material_no, material_desc, material_type, qty_plan, qty_actual, qty_balance, qty_NG, qty_qc, qty_qc_ok, qty_qc_NG, status_QC, comp_code, work_center, shift_day, date_plan, user_create, date_create, user_update, date_update, user_qc_posting, date_qc_posting, time_qc_posting, ploc_qc, ploc, delivery_loc, type_qc_reject, reason_qc_reject, user_qc_reject, date_qc_reject, time_qc_reject, status_ftp_fgtran, status, qqc_no_ref, user_cancel, date_cancel) VALUES('','".db_esc($dbc, $data_qqc["id_tran"])."','".db_esc($dbc, $data_qqc["qqc_doc_no"])."','".db_esc($dbc, $ref_2)."','".db_esc($dbc, $data_qqc["bflush_no"])."','".db_esc($dbc, $data_qqc["plan_no"])."','".db_esc($dbc, $data_qqc["material_no"])."', '".db_esc($dbc, $data_qqc["material_desc"])."','".db_esc($dbc, $data_qqc["material_type"])."','".db_esc($dbc, $data_qqc["qty_plan"])."','".db_esc($dbc, $data_qqc["qty_actual"])."','".db_esc($dbc, $data_qqc["qty_balance"])."','','".db_esc($dbc, $data_qqc["qty_qc"])."','".db_esc($dbc, $data_qqc["qty_qc"])."','','".db_esc($dbc, $rst_sta8["status_desc"])."','".db_esc($dbc, $data_qqc["comp_code"])."', '".db_esc($dbc, $data_qqc["work_center"])."', '".db_esc($dbc, $data_qqc["shift_day"])."','".db_esc($dbc, $data_qqc["date_plan"])."','".db_esc($dbc, $username)."',NOW(),'','','".db_esc($dbc, $username)."',NOW(),NOW(),'".db_esc($dbc, $ploc_qc)."','".db_esc($dbc, $data_qqc["ploc"])."','','','','','','','N','Y','','','')";
$result_data3 = mysqli_query($dbc, $query_data3) or die(db_fail($dbc));  

        
        //----------create text file sent ftp to SAP[comp code][5][running no]--------------
   $query_ftp = "SELECT *, DATE_FORMAT(date_qc_posting,'%Y-%m-%d') AS P, DATE_FORMAT(date_create,'%Y-%m-%d') AS P2, DATE_FORMAT(date_create,'%H:%i:%s') AS P3 FROM qqc_transaction WHERE id_tran = '".db_esc($dbc, $data_qqc["id_tran"])."' AND bflush_no = '".db_esc($dbc, $data_qqc["bflush_no"])."'";
   $result_ftp = mysqli_query($dbc, $query_ftp);
   $data_ftp = mysqli_fetch_array($result_ftp);
   
   $query_q2 = "SELECT * FROM mat_master_header WHERE material_no = '".db_esc($dbc, $data_ftp["material_no"])."'";
   $result_q2 = mysqli_query($dbc, $query_q2) or die(db_fail($dbc));
   $ans3 = mysqli_fetch_array($result_q2);


 $data .= $ref_2.";".$data_ftp['comp_code'].";".$data_ftp['material_no'].";311;".$data_ftp['qty_qc_ok'].";".$ans3['BUn'].";".$data_ftp['ploc_qc'].";".$data_ftp['ploc'].";".$data_ftp['P'].";".$data_ftp['time_qc_posting'].";".$data_ftp['P2'].";".$data_ftp['P3'].";".$data_ftp['user_create']."\r\n";

$filen = "TP4".$ref_2;

$file = "../FromPortal2/MT5/".$filen.".csv";
//chmod($file, 0777);
file_put_contents($file,$data);

   //----------update table ftp_goodtran_detail------------
   
    $query_ftp_info = "INSERT INTO ftp_goodtran_detail(id, file_name, qqc_no, bflush_no, id_tran, plan_no, material_no, material_desc, qty_ftp, uom, status_ftp, posting_date, posting_time, user_create, date_create) VALUES('','".db_esc($dbc, $filen)."','".db_esc($dbc, $data_ftp["qqc_no"])."',".$data_ftp["bflush_no"].",'".db_esc($dbc, $data_ftp["id_tran"])."','".db_esc($dbc, $data_ftp["plan_no"])."','".db_esc($dbc, $data_ftp["material_no"])."','".db_esc($dbc, $data_ftp["material_desc"])."','".db_esc($dbc, $data_ftp["qty_qc"])."','PCS','Y','".db_esc($dbc, $data_ftp["P"])."','".db_esc($dbc, $data_ftp["time_qc_posting"])."','".db_esc($dbc, $username)."',NOW())"; 
     $rst_ftp_info = mysqli_query($dbc, $query_ftp_info);
	 
	   // ---update status 

		$query_ftp2 = "UPDATE qqc_detail_transaction SET status_ftp_fgtran = 'Y' WHERE bflush_no = '".db_esc($dbc, $data_ftp["bflush_no"])."'";
		$rst_query_ftp2 = mysqli_query($dbc, $query_ftp2); //or die ("Error in query: $query_ftp"); 



       //update count_max----------------------------------------
		
	
       $query_max_b = "UPDATE run_count_no SET count_max = '".$number_2."', date_updated = NOW() WHERE uid = '33'";
	   $result_max_b = mysqli_query($dbc, $query_max_b);
	 
       //end update count_max ---------------------------------	
   

	  	  
	} // end if
	
		   echo "<script>";
		   echo "alert('QC Document Number(s) : $ref.');";
		   echo "parent.tb_remove(); parent.location.reload(1)";
	       echo "</script>"; 
		   exit(); //quit the script
	
}
	
 
  //print the message if there is one.
if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}
  
} // end if
 
 
 
 

?>
<table class="table">
<tr>
  <td>&nbsp;</td>
  <td colspan="2"><div align="center"><span class="style4">Production Output</span></div></td>
  </tr>
</table>
<p>&nbsp;</p> 
<form name="form1" action="qc_production_output_list.php?uid=<?php echo $uid; ?>" method="post" class="form-horizontal">
<table width="98%" class="table table-bordered">
  <tr>
    <td colspan="2">Please enter quantity</td>
    </tr>
  <tr>
    <th width="25%">OK</th>
    <td width="75%"><input name="qty_qc" id="qty_qc" type="number" min="0" max="<?php echo $total_qty_pending; ?>" value="<?php echo $total_qty_pending; ?>"></td>
  </tr>
  <tr>
    <th>Pending</th>
    <td><input type="text" name="qty_balance" id="qty_balance" readonly value="<?php echo $total_qty_pending; ?>" >
   <!-- <input type="text" name="qty_balance" id="qty_balance" readonly value="<?php echo $qty_final; ?>" >--></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td> <input type="hidden" name="uid" value="<?php echo $uid; ?>">
             <div class="form-actions">
               <input name="submit3" type="submit" id="submit" value="SUBMIT" class="btn btn-success">
              <!-- <input name="Reset" type="reset" id="Reset" class="btn btn-warning" value="CANCEL">-->
             </div>
             </td>
  </tr>
</table>
</form>


   
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