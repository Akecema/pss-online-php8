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

$url = "cancellation_QC_output_list_tran.php";


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

$sta = "SELECT * from request_status WHERE status_id = '1'";
$sta_res = mysql_query($sta);
$rst_sta = mysql_fetch_array($sta_res);

//CR status (Released)
$sta2 = "SELECT * from request_status WHERE status_id = '2'";
$sta_res2 = mysql_query($sta2);
$rst_sta2 = mysql_fetch_array($sta_res2);	

//CR status (Cancel)
$sta4 = "SELECT * from request_status WHERE status_id = '4'";
$sta_res4 = mysql_query($sta4);
$rst_sta4 = mysql_fetch_array($sta_res4);	

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7'";
$sta_res7 = mysql_query($sta7);
$rst_sta7 = mysql_fetch_array($sta_res7);	

//CR status (Pending)
$sta8 = "SELECT * from request_status WHERE status_id = '8'";
$sta_res8 = mysql_query($sta8);
$rst_sta8 = mysql_fetch_array($sta_res8);	

//CR status (Delete)
$sta16 = "SELECT * from request_status WHERE status_id = '16'";
$sta_res16 = mysql_query($sta16);
$rst_sta16 = mysql_fetch_array($sta_res16);

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
	return mysql_real_escape_string($data,$dbc);
	}   // end function.
$message = NULL; // create an empty new variable.
   
    $uid2 = $_POST["uid2"];
    $cancel = $_POST["cancel"]; 
    $how_many = count($cancel); 
	$sta_out = "";
	$data = "";
	$data2 = "";
	
						
		   for ($i=0; $i<$how_many; $i++) { 

   
		  //-----listing ----------
  
  $query_info = "SELECT * FROM qqc_transaction WHERE id_qqc = '".$cancel[$i]."'"; 
  $result_info = mysql_query($query_info);
  $data_info = mysql_fetch_array($result_info);
  
  //echo $data_info["qqc_doc_no"];
  
  $query_upd_detail2 = "UPDATE qqc_detail_transaction SET status_QC = '".$rst_sta8["status_desc"]."' WHERE id_qqc = '".$data_info["id_tran"]."' AND qqc_doc_no = '".$data_info["qqc_doc_no"]."'";
  $result_upd_detail2 = mysql_query($query_upd_detail2) or die (mysql_error());
  
  		$query_ftp = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') as J, DATE_FORMAT(date_cancel,'%Y-%m-%d') as J2, DATE_FORMAT(date_cancel,'%H:%i:%s') as J3 FROM qqc_transaction WHERE id_qqc = '".$cancel[$i]."' AND qqc_doc_no = '".$data_info["qqc_doc_no"]."'";
		$result_ftp = mysql_query($query_ftp);   //run the query.
		$data_ftp = mysql_fetch_array($result_ftp);
		
		$query_q2 = "SELECT * FROM table_material WHERE material_no = '".$data_ftp["material_no"]."'";
        $result_q2 = mysql_query($query_q2);
        $ans3 = mysql_fetch_array($result_q2);
		
  
  	//----check string -----------
	
	$sta_out = substr($data_ftp["qqc_no"],2,3);
	
  if($sta_out == "421")
	{
	
	 //------generate Rework QC List (OK) Cancellation---------------------------------
	$ref = "";
	
	$query_id = "SELECT count_max FROM run_count_no WHERE uid = '34'";
	$result_id = mysql_query($query_id);
	
	if ($result_id) 
{
	$nrows = mysql_num_rows($result_id);
	$row_id = mysql_fetch_row($result_id);
	
	$dht = 0000000; 
	$dht_OK = "22422";
	$dg2 = 0;

  	if($row_id[0] <= 0)
  	{ 
   
    	$lastID = ($row_id[0] + 1);
    	$dg = ($dht + ($lastID));
   }
   else
   {
      $lastID = ($row_id[0] + 1);
      $dg =  $lastID;
	
    }
	$number = $dg; // Length of running no
    $number = sprintf('%07d', $number);  
	
	 $ref = ($dht_OK.($number));
		
	} // end if $result_id

	// echo $ref;	
		
		
  $query_upd_detail = "UPDATE qqc_transaction SET status_QC = '".$rst_sta4["status_desc"]."', qqc_no_ref = '".$ref."', user_cancel = '".$username."', date_cancel = NOW() WHERE id_qqc = '".$cancel[$i]."'";
  $result_upd_detail = mysql_query($query_upd_detail) or die (mysql_error());
  

	
	 $data .= $data_ftp["qqc_doc_no"].";".$ref.";".$data_ftp["qqc_no"].";".$data_ftp["comp_code"].";".$data_ftp['material_no'].";312;".$data_ftp['qty_qc_ok'].";".$ans3['BUn'].";".$data_ftp['ploc_qc'].";".$data_ftp['ploc'].";".$data_ftp['J2'].";".$data_ftp['user_cancel']."\r\n";
	 
	 	$filen = "TP4".$ref;

$file = "../FromPortal2/GT9/".$filen.".csv";
file_put_contents($file,$data);	

//----------update table ftp_goodtran_detail------------
   
    $query_ftp_info = "INSERT INTO ftp_goodtran_detail(id, file_name, qqc_no, bflush_no, id_tran, plan_no, material_no, material_desc, qty_ftp, uom, status_ftp, posting_date, posting_time, user_create, date_create) VALUES('','".$filen."','".$data_ftp["qqc_no_ref"]."',".$data_ftp["bflush_no"].",'".$data_ftp["id_tran"]."','".$data_ftp["plan_no"]."','".$data_ftp["material_no"]."','".$data_ftp["material_desc"]."','".$data_ftp["qty_qc_ok"]."','".$ans3['BUn']."','Y','".$data_ftp["date_cancel"]."','".$data_ftp["J3"]."','".$username."',NOW())"; 
     $rst_ftp_info = mysql_query($query_ftp_info);
	 
	 
 //------insert table qqc_transaction_cancel2 -------------
 
  $query_qqc =  "SELECT * FROM qqc_transaction WHERE id_qqc = '".$cancel[$i]."'"; 
  $result_qqc = mysql_query($query_qqc);
  $data_qqc = mysql_fetch_array($result_qqc);
 
 
   $query_copy = "INSERT INTO qqc_transaction_cancel2(id_qqc, id_tran, qqc_doc_no, qqc_no, bflush_no, plan_no, material_no, material_desc, material_type, qty_plan, qty_actual, qty_balance, qty_NG, qty_qc, qty_qc_ok, qty_qc_NG, status_QC, comp_code, work_center, shift_day, date_plan, user_create, date_create, user_update, date_update, user_qc_posting, date_qc_posting, time_qc_posting, ploc_qc, ploc, delivery_loc, type_qc_reject, reason_qc_reject, user_qc_reject, date_qc_reject, time_qc_reject, status_ftp_fgtran, status, qqc_no_ref, user_cancel, date_cancel) VALUES('','".$data_qqc["id_tran"]."','".$data_qqc["qqc_doc_no"]."','".$data_qqc["qqc_no"]."','".$data_qqc["bflush_no"]."','".$data_qqc["plan_no"]."','".$data_qqc["material_no"]."', '".$data_qqc["material_desc"]."','".$data_qqc["material_type"]."','".$data_qqc["qty_plan"]."','".$data_qqc["qty_actual"]."','".$data_qqc["qty_balance"]."','".$data_qqc["qty_NG"]."','".$data_qqc["qty_qc"]."','".$data_qqc["qty_qc_ok"]."','".$data_qqc["qty_qc_NG"]."','".$rst_sta4["status_desc"]."','".$data_qqc["comp_code"]."', '".$data_qqc["work_center"]."', '".$data_qqc["shift_day"]."','".$data_qqc["date_plan"]."','".$data_qqc["user_create"]."','".$data_qqc["date_create"]."','".$data_qqc["user_update"]."','".$data_qqc["date_update"]."','".$data_qqc["user_qc_posting"]."','".$data_qqc["date_qc_posting"]."','".$data_qqc["time_qc_posting"]."','".$data_qqc["ploc_qc"]."','".$data_qqc["ploc"]."','".$data_qqc["delivery_loc"]."','".$data_qqc["type_qc_reject"]."','".$data_qqc["reason_qc_reject"]."','".$data_qqc["user_qc_reject"]."','".$data_qqc["date_qc_reject"]."','".$data_qqc["time_qc_reject"]."','Y','Y','".$data_qqc["qqc_no_ref"]."','".$data_qqc["user_cancel"]."','".$data_qqc["date_cancel"]."')";
   $result_copy = mysql_query($query_copy) or die (mysql_error('Update Cancel Transaction'));
 
 
 
 
 	 
	 
	  //update count_max----------------------------------------
	
       $query_max_a = "UPDATE run_count_no SET count_max = '".$number."', date_updated = NOW() WHERE uid = '34'";
	   $result_max_a = mysql_query($query_max_a);
	 
   //end update count_max ---------------------------------		 
	 
	 
	}elseif($sta_out == "331")
	{
	
	//22331	
		  //------generate Rework QC List (NG) Cancellation---------------------------------
	
	$ref_2 = "";
	
	$query_id_2 = "SELECT count_max FROM run_count_no WHERE uid = '28'";
	$result_id_2 = mysql_query($query_id_2);
	
	if ($result_id_2) 
{
	$nrows_2 = mysql_num_rows($result_id_2);
	$row_id_2 = mysql_fetch_row($result_id_2);
	
	$dht_2 = 0000000; 
	$dht_OK_2 = "22332";
	$dg2_2 = 0;

  	if($row_id_2[0] <= 0)
  	{ 
   
    	$lastID_2 = ($row_id_2[0] + 1);
    	$dg_2 = ($dht_2 + ($lastID_2));
   }
   else
   {
      $lastID_2 = ($row_id_2[0] + 1);
      $dg_2 =  $lastID_2;
	
    }
	$number2 = $dg_2; // Length of running no
    $number2 = sprintf('%07d', $number2);  
	
	 $ref_2 = ($dht_OK_2.($number2));
		
	} // end if $result_id


 //echo $ref_2;
	
	
	  $query_upd_detail = "UPDATE qqc_transaction SET status_QC = '".$rst_sta4["status_desc"]."', qqc_no_ref = '".$ref_2."', user_cancel = '".$username."', date_cancel = NOW() WHERE id_qqc = '".$cancel[$i]."'";
  $result_upd_detail = mysql_query($query_upd_detail) or die (mysql_error());
  
		
	 $data2 .= $data_ftp["qqc_doc_no"].";".$ref_2.";".$data_ftp["qqc_no"].";".$data_ftp["comp_code"].";".$data_ftp['material_no'].";552;".$data_ftp['qty_qc_NG'].";".$ans3['BUn'].";".$data_ftp['ploc_qc'].";".$data_ftp['ploc'].";".$data_ftp['J2'].";".$data_ftp['user_cancel']."\r\n";
		
	$filen = "GI3".$ref_2;

$file = "../FromPortal2/GT9/".$filen.".csv";
file_put_contents($file,$data2);	

//----------update table ftp_goodtran_detail------------
   
    $query_ftp_info = "INSERT INTO ftp_goodtran_detail(id, file_name, qqc_no, bflush_no, id_tran, plan_no, material_no, material_desc, qty_ftp, uom, status_ftp, posting_date, posting_time, user_create, date_create) VALUES('','".$filen."','".$data_ftp["qqc_no_ref"]."',".$data_ftp["bflush_no"].",'".$data_ftp["id_tran"]."','".$data_ftp["plan_no"]."','".$data_ftp["material_no"]."','".$data_ftp["material_desc"]."','".$data_ftp["qty_qc_NG"]."','".$ans3['BUn']."','Y','".$data_ftp["date_cancel"]."','".$data_ftp["J3"]."','".$username."',NOW())"; 
     $rst_ftp_info = mysql_query($query_ftp_info);
	 
	 
	 //------insert table qqc_transaction_cancel2 -------------
 
  $query_qqc =  "SELECT * FROM qqc_transaction WHERE id_qqc = '".$cancel[$i]."'"; 
  $result_qqc = mysql_query($query_qqc);
  $data_qqc = mysql_fetch_array($result_qqc);
 
 
   $query_copy = "INSERT INTO qqc_transaction_cancel2(id_qqc, id_tran, qqc_doc_no, qqc_no, bflush_no, plan_no, material_no, material_desc, material_type, qty_plan, qty_actual, qty_balance, qty_NG, qty_qc, qty_qc_ok, qty_qc_NG, status_QC, comp_code, work_center, shift_day, date_plan, user_create, date_create, user_update, date_update, user_qc_posting, date_qc_posting, time_qc_posting, ploc_qc, ploc, delivery_loc, type_qc_reject, reason_qc_reject, user_qc_reject, date_qc_reject, time_qc_reject, status_ftp_fgtran, status, qqc_no_ref, user_cancel, date_cancel) VALUES('','".$data_qqc["id_tran"]."','".$data_qqc["qqc_doc_no"]."','".$data_qqc["qqc_no"]."','".$data_qqc["bflush_no"]."','".$data_qqc["plan_no"]."','".$data_qqc["material_no"]."', '".$data_qqc["material_desc"]."','".$data_qqc["material_type"]."','".$data_qqc["qty_plan"]."','".$data_qqc["qty_actual"]."','".$data_qqc["qty_balance"]."','".$data_qqc["qty_NG"]."','".$data_qqc["qty_qc"]."','".$data_qqc["qty_qc_ok"]."','".$data_qqc["qty_qc_NG"]."','".$rst_sta4["status_desc"]."','".$data_qqc["comp_code"]."', '".$data_qqc["work_center"]."', '".$data_qqc["shift_day"]."','".$data_qqc["date_plan"]."','".$data_qqc["user_create"]."','".$data_qqc["date_create"]."','".$data_qqc["user_update"]."','".$data_qqc["date_update"]."','".$data_qqc["user_qc_posting"]."','".$data_qqc["date_qc_posting"]."','".$data_qqc["time_qc_posting"]."','".$data_qqc["ploc_qc"]."','".$data_qqc["ploc"]."','".$data_qqc["delivery_loc"]."','".$data_qqc["type_qc_reject"]."','".$data_qqc["reason_qc_reject"]."','".$data_qqc["user_qc_reject"]."','".$data_qqc["date_qc_reject"]."','".$data_qqc["time_qc_reject"]."','Y','Y','".$data_qqc["qqc_no_ref"]."','".$data_qqc["user_cancel"]."','".$data_qqc["date_cancel"]."')";
   $result_copy = mysql_query($query_copy) or die (mysql_error('Update Cancel Transaction'));

     //update count_max----------------------------------------
		
	
       $query_max_b = "UPDATE run_count_no SET count_max = '".$number2."', date_updated = NOW() WHERE uid = '28'";
	   $result_max_b = mysql_query($query_max_b);
	 
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
  <td colspan="2"><div align="center"><span class="style4">Rework QC OK Cancellation</span></div></td>
  </tr>
</table>
<p>&nbsp;</p> 
   <form action="cancel_qc_rework_output_QC_ok.php?uid=<?php echo $uid; ?>" method="post" name="myform" id="myform">     
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
        <th>Quantity QC</th>
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
   
$query_display = "SELECT *, DATE_FORMAT(date_qc_posting,'%d-%m-%Y') as R2 FROM qqc_transaction WHERE id_tran = '".$uid."' AND status_QC != '".$rst_sta4["status_desc"]."'";
$result_display = mysql_query($query_display);   //run the query.
   
   while($row2 = mysql_fetch_array($result_display))
   {
		
     $query_model =  "SELECT * FROM pps_detail_transaction WHERE plan_no = '".$row2["plan_no"]."' ORDER BY plan_no ASC";		
	 $result_model = mysql_query($query_model);
     $row_model = mysql_fetch_array($result_model); 	
	 
	//query material 
	 $query_mat =  "SELECT * FROM table_material WHERE material_no = '".$row2["material_no"]."'";		
	 $result_mat = mysql_query($query_mat);
     $row_mat = mysql_fetch_array($result_mat); 
	 
	//query reason reject
	 $query_reason = "SELECT * FROM reason_ng_reject WHERE id_reject = '".$row2["reason_qc_reject"]."'";
     $result_reason = mysql_query($query_reason);
	 $row_reason = mysql_fetch_array($result_reason);	
	 
	 
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
        <td width="157"><?php echo $row_reason["reject_desc"]; ?></td>
        <input name="uid2" type="hidden" value="<?php echo $row2["id_qqc"]; ?> ">
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
    <tr><td>&nbsp;
    <input name="cancel_btn" type="submit"  class="btn btn-danger" id="button" value="CANCELLATION" onClick="return confirm('Are you sure want to perform this activity?');"/>
    </td>
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