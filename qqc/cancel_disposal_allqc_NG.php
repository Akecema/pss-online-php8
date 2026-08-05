<?php

/**
 * qqc/cancel_disposal_allqc_NG.php
 * Part of: QQC module (Quality)
 * Filename suggests: cancel disposal allqc NG
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, reject_detail_disposal, run_count_no, pps_detail_transaction, reject_detail_disposal_cancel, type_reject_detail, reason_ng_reject, pps_detail.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, tcpdf_barcodes_2d.php, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
error_reporting(E_ALL &~ E_NOTICE &~ E_DEPRECATED);
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

$url = "disposal_backflush_tran_NG.php";


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
 $extension = explode ('.', $data_setup["logo_name"]);
 $filename = $data_setup["logo_comp"].'.'.$extension[1];
 
 //CR status (Cancelled)
$sta4 = "SELECT * from request_status WHERE status_id = '4'";
$sta_res4 = mysqli_query($dbc, $sta4);
$rst_sta4 = mysqli_fetch_array($sta_res4);	

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
body { background-color:#FFFFFF }
@media print{
  body{
	margin-top: -1.8cm;
	margin-left:10px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	background-color: #FFFFFF;

}
 a[href]:after {
    content: none !important;
  }
  #ad{ display:none;}
  #leftbar{ display:none;}
  #contentarea{ width:100%;}
  .header, .hide { visibility: hidden }
  .tfoot { display: table-footer-group; }
    @page {size: landscape;
	margin-left:10px;
	margin-right:10px;
	margin-bottom:0px;
	}
	  
  .bottom-left2{ visibility: hidden }
  .footer_bawah{ margin-bottom:5px; }
}
.style4 {
	font-size: 14px;
	font-weight: bold;
}
-->
</style>

<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>
<?php
//echo "the following values have been checked: ";
$checked="";
$amount ="";
$amount2 ="";

$a = array();
if(isset($_POST["cancel"])) {
	foreach($_POST["cancel"] as $j=>$i) {
	    $amount .= $_POST["remark_reject"][$i]."|";
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
<body>
<?php

 $doc_disposal = $_GET["doc_disposal"];
  
$queryu = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') as R, DATE_FORMAT(date_posting,'%d-%m-%Y') as R2 FROM reject_detail_disposal WHERE doc_disposal_no = '".$doc_disposal."'";
$rs = mysqli_query($dbc, $queryu);   //run the query.

//detail info disposal 

$query_disposal = "SELECT *, DATE_FORMAT(date_disposal,'%d-%m-%Y %H:%i:%s') as W, DATE_FORMAT(date_posting,'%d-%m-%Y') as W2 FROM reject_detail_disposal WHERE doc_disposal_no = '".$doc_disposal."'";
$result_disposal = mysqli_query($dbc, $query_disposal);   //run the query.
$row_disposal = mysqli_fetch_array($result_disposal);


//FUNCTION RETAIN TEXTBOX VALUE
function prepopulate($name) 
{ 
	if(isset($_POST[$name])) 
	{ 
		return $_POST[$name]; 
	} 
	else 
	{ 
		return ""; 
	} 
} 

?>
<br>
<table width="1000">
<tr>
      <td width="1%">&nbsp;</td>
      <td width="7%"><img src="../img/print2.jpg" width="48" height="48" onClick="window.print()" title="Print"/></td>
        <td width="7%"><a href="javascript:parent.tb_remove();" ><img src="../img/back3.jpg" width="48" height="48" /></a></td>
      <td width="85%"> <div class="small-nav"></div></td>
      
  </tr>

</table>
<?php
	   
if(isset($_POST["Submit2"])) 
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
 
   if(isset($_POST["cancel"])) 
  {
   
	$doc_disposal = $_POST["doc_disposal"];  
	
	$cancel = $_POST["cancel"]; 
    $how_many = count($cancel); 
	$string = "";
	
	
	    //-------------------generate disposal doc no. [Backflush NG Cancellation]---------------
	 
	 $query_id = "SELECT count_max FROM run_count_no WHERE uid = ''";
	$result_id = mysqli_query($dbc, $query_id);
	
	if ($result_id) 
{
	$nrows = mysqli_num_rows($result_id);
	$row_id = mysqli_fetch_row($result_id);
	
	$dht = 0000000; 
	$dht_OK = "";
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
	 
	 
	 
	
       
	   
	  /* foreach($_POST["cancel"] as $j=>$i) {
	    
		//$amount .= $_POST["remark_reject"][$i];
	   // $string = explode("|",($amount));	
		
			}*/
			
       for ($i=0; $i<$how_many; $i++) { 
		   			
	   //echo $cancel[$i];  echo "<br>";
	   
	
   //------------update status reject detail disposal-------------------
      
 $query_upd3 = "UPDATE reject_detail_disposal SET status_disposal = '".$rst_sta4["status_desc"]."', disposal_no_ref = '".$ref."', user_cancel = '".$username."', date_cancel = NOW() WHERE id_disposal = '".$cancel[$i]."' AND doc_disposal_no = '".$doc_disposal."'";
 $result_upd3 = mysqli_query($dbc, $query_upd3); 

 
  //----------baca data reject detail disposal -------------------
  
  $query_data_return = "SELECT * FROM reject_detail_disposal WHERE id_disposal = '".$cancel[$i]."' AND doc_disposal_no = '".$doc_disposal."'";
  $result_data_return = mysqli_query($dbc, $query_data_return); 
  $row_data_return = mysqli_fetch_array($result_data_return);
  
  
	  // echo  $row_data_return["bflush_qqc_no"];
	
	  //----------update status = "N" pps_detail_transaction -------------
  
 $query_upd4 = "UPDATE pps_detail_transaction SET status = 'N', user_update = '".$username."', date_update = NOW() WHERE id = '".$row_data_return["uid"]."' AND bflush_no = '".$row_data_return["bflush_qqc_no"]."'";
 $result_upd4 = mysqli_query($dbc, $query_upd4); 
 
     //-----insert at table reject_detail_disposal_cancel -------
	 
	 		$query_move_tbl = "INSERT INTO reject_detail_disposal_cancel (id_disposal, doc_dis, doc_disposal_no, bflush_qqc_no, plan_no, uid, material_no, material_desc, material_type, model_code, qty_plan, qty_actual, qty_balance, qty_NG, qty_qc, qty_qc_ok, qty_qc_NG, UOM_unit, comp_code, work_center, shift_day, date_plan, user_posting, date_posting, time_posting, status_disposal, ploc, ploc_prod_reject, ploc_qc_reject, type_reject, reason_reject, user_reject, date_reject, time_reject, qty_wastage, type_wastage, reason_wastage, user_wastage, date_wastage, time_wastage, user_disposal, date_disposal, remarks, approve_by, date_approve, remark_approve, status_part, user_update, date_update, approve_by2, date_approve2, remark_approve2, cost_center, id_factory, disposal_no_ref, user_cancel, date_cancel) VALUES('','".$row_data_return["doc_dis"]."','".$row_data_return["doc_disposal_no"]."','".$row_data_return["bflush_qqc_no"]."','".$row_data_return["plan_no"]."','".$row_data_return["uid"]."','".$row_data_return["material_no"]."', '".$row_data_return["material_desc"]."','".$row_data_return["material_type"]."','".$row_data_return["model_code"]."','".$row_data_return["qty_plan"]."','".$row_data_return["qty_actual"]."','".$row_data_return["qty_balance"]."','".$row_data_return["qty_NG"]."','".$row_data_return["qty_qc"]."','".$row_data_return["qty_qc_ok"]."','".$row_data_return["qty_qc_NG"]."','".$row_data_return["UOM_unit"]."','".$row_data_return["comp_code"]."','".$row_data_return["work_center"]."','".$row_data_return["shift_day"]."','".$row_data_return["date_plan"]."','".$row_data_return["user_posting"]."','".$row_data_return["date_posting"]."','".$row_data_return["time_posting"]."','".$row_data_return["status_disposal"]."','".$row_data_return["ploc"]."','".$row_data_return["ploc_prod_reject"]."','".$row_data_return["ploc_qc_reject"]."','".$row_data_return["type_reject"]."','".$row_data_return["reason_reject"]."','".$row_data_return["user_reject"]."','".$row_data_return["date_reject"]."','".$row_data_return["time_reject"]."','".$row_data_return["qty_wastage"]."','".$row_data_return["type_wastage"]."','".$row_data_return["reason_wastage"]."','".$row_data_return["user_wastage"]."','".$row_data_return["date_wastage"]."','".$row_data_return["time_wastage"]."','".$row_data_return["user_disposal"]."','".$row_data_return["date_disposal"]."','".$row_data_return["remarks"]."','".$row_data_return["approve_by"]."','".$row_data_return["date_approve"]."','".$row_data_return["remark_approve"]."','".$row_data_return["status_part"]."','".$row_data_return["user_update"]."','".$row_data_return["date_update"]."','".$row_data_return["approve_by2"]."','".$row_data_return["date_approve2"]."','".$row_data_return["remark_approve2"]."','".$row_data_return["cost_center"]."','".$row_data_return["id_factory"]."','".$ref."','".$res["staff_ID"]."',NOW())";
           $result_move_tbl= mysqli_query($dbc, $query_move_tbl) or die (mysqli_error($dbc));
  
       }// end for loop
	   
	   
    //update count_max----------------------------------------
		
	
       $query_max_a = "UPDATE run_count_no SET count_max = '".$number."', date_updated = NOW() WHERE uid = '24'";
	   $result_max_a = mysqli_query($dbc, $query_max_a);
	 
   //end update count_max ---------------------------------	

			 echo "<script>";
		     echo "parent.tb_remove(); parent.location.reload(1)";
		     echo "</script>"; 
		     exit(); //quit the script


 } //print the message if there is one.
if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}

}


?>
<br>
<div style="page-break-inside:auto">
<br>

<table width="98%" class="table table-condensed">
  <tr>
    <td width="62%"><p><img src="../set_upload/<?php echo $filename;  ?>"width="267" height="27" hspace="2" vspace="2"/></p>
      <p>PT 2475-2476, Kawasan Perindustrian Nilai, P.O. Box 45,<br>
      71807 Nilai, Negeri Sembilan Darul Khusus, Malaysia. <br>
      Tel :+606-799 5599 Fax :+606-799 5597 / 8</p></td>
    <td width="38%">
    <table width="98%"  class="table table-bordered">
      <tr>
        <td width="35%">Disposal No.</td>
        <td width="35%"><?php echo $row_disposal["doc_disposal_no"];  ?></td>
        </tr>
      <tr>
        <td>Document Date</td>
        <td><?php echo $row_disposal["W"];  ?></td>
        </tr>
      <tr>
        <td>Status</td>
        <td><?php echo $row_disposal["status_disposal"];  ?></td>
      </tr>
      </table></td>
  </tr>
</table>
 <!-- Form -->
            <div class="form" align="center">
              <!-- End Box Head -->
          <form name="myform" action="cancel_disposal_allproc.php?doc_disposal=<?php echo $doc_disposal; ?>" method="post" >
           
            <!-- <table width="1100" border="1" cellpadding="0" cellspacing="2" bordercolor="#666666" style="border:solid 1px #d5d5d5;">-->
              <table width="1100" class="table table-bordered">
             <thead>
               <tr>
                 <th width="100" height="28" bgcolor="#E9F58D"><span class="style3">No.</span></th>
                 <th width="122" height="28" bgcolor="#E9F58D"><span class="style3">Model</span></th>
                 <th width="100" bgcolor="#E9F58D"><span class="style3">Part No.</span></th>
                 <th width="45" bgcolor="#E9F58D"><span class="style3">Type of Reject/Wastage</span></th>
                 <th width="146" bgcolor="#E9F58D"><span class="style3">Date</span></th>
                 <th width="42" bgcolor="#E9F58D"><span class="style3">Quantity</span></th>
                 <th width="90" bgcolor="#E9F58D"><span class="style3">UOM</span></th>
                 <th width="90" height="28" bgcolor="#E9F58D"><span class="style3">Location</span></th>
                 <th width="90" height="28" bgcolor="#E9F58D"><span class="style3">Cost Center</span></th>
                 <th width="65" height="28" bgcolor="#E9F58D"><span class="style3">Reason</span></th>
                 <th height="28" bgcolor="#E9F58D"><span class="style3">Reason Remarks</span></th>
               </tr>
             </thead>  
             <?php
      $counter = 1;
      $no = 1;
      $qty_asal = 0.000;
	  $loc_asal = "";
	  $k= 1;
	   
   while ($row = mysqli_fetch_array($rs))
   {
		
		$query_type = "SELECT * FROM type_reject_detail WHERE id_type = '".$row['type_reject']."' ORDER BY id_type ASC";
		$result_type = mysqli_query($dbc, $query_type);
		$row_type = mysqli_fetch_array($result_type); 
		
		$query_reason = "SELECT * FROM reason_ng_reject WHERE id_reject = '".$row['reason_reject']."' ORDER BY id_reject ASC";
		$result_reason = mysqli_query($dbc, $query_reason);
		$row_reason = mysqli_fetch_array($result_reason);
		
		$query_model = "SELECT * FROM pps_detail WHERE plan_no = '".$row['plan_no']."'";
		$result_model = mysqli_query($dbc, $query_model);
		$data_model = mysqli_fetch_array($result_model);	
		
		$query_mat = "SELECT * FROM mat_master_header WHERE material_no = '".$row['material_no']."'";
		$result_mat = mysqli_query($dbc, $query_mat);
		$data_mat = mysqli_fetch_array($result_mat);	
		
		$query_disposal2 = "SELECT * FROM user_detail WHERE username = '".$row["user_disposal"]."'";
        $result_disposal2 = mysqli_query($dbc, $query_disposal2) or die (mysqli_error($dbc));
        $res_disposal2 = mysqli_fetch_array($result_disposal2);
		
		$query_approve = "SELECT * FROM user_detail WHERE username = '".$row["approve_by"]."'";
        $result_approve = mysqli_query($dbc, $query_approve) or die (mysqli_error($dbc));
        $res_approve = mysqli_fetch_array($result_approve);
		
		$query_approve2 = "SELECT * FROM user_detail WHERE username = '".$row["approve_by2"]."'";
        $result_approve2 = mysqli_query($dbc, $query_approve2) or die (mysqli_error($dbc));
        $res_approve2 = mysqli_fetch_array($result_approve2);
		
  //-------get quantity reject ---------
	if($row["qty_NG"] != "0.000")
	{
	 
	 $qty_asal = $row["qty_NG"];
	 
	 }elseif($row["qty_qc_NG"] != "0.000")
	 {
		 
     $qty_asal = $row["qty_qc_NG"]; 
		 
	 }else{
		 
	  $qty_asal = "";	 
	 }
	
	//-------get location ---------
	if($row["ploc_prod_reject"] != "")
	{
	 
	 $loc_asal = $row["ploc_prod_reject"];
	 
	 }elseif($row["ploc_qc_reject"] != "")
	 {
		 
     $loc_asal = $row["ploc_qc_reject"]; 
		 
	 }else{
		 
	  $loc_asal = "";	 
	 }
 
	   
		 ?>
<tbody>

<tr>
                <td width="40" height="28"><div align="center">
        <input type="hidden" name="cancel[]" value="<?php echo $row["id_disposal"]; ?>" <?=was_checked($row["id_disposal"],$a) ?> /><input type="hidden" name="Check_ctr" value="yes" 
onClick="Check(document.myform.cancel)"> <?php  echo $row["id_disposal"]; ?></div></td>
                <td width="80"><?php  echo $row["model_code"]; ?></td>
                <td width="100"><?php echo $row["material_no"]; ?>&nbsp;</td>
                <td width="100"><?php echo $row_type["type_desc"]; ?></td>
                <td width="100"><?php echo $row["R2"]; ?></td>
                <td width="100"><input name="qty_NG" type="text" readonly value="<?php echo $row["qty_NG"]; ?>"  class="span2"/></td>
                <td width="80"><?php echo $row["UOM_unit"]; ?></td>
                <td width="80"><div align="center"><?php echo $loc_asal; ?></div></td>
                 <td width="80"><?php echo $row["cost_center"]; ?></td>                  
                <td width="150"><?php echo $row_reason["reject_desc"]; ?></td>
                <td width="200"><?php echo $row["remarks"]; ?>
               <input name="id_disposal[<?php echo $row["id_disposal"]; ?>]" type="hidden" value="<?php echo $row["id_disposal"]; ?>">
                <input name="doc_disposal" type="hidden" value="<?php echo $row["doc_disposal_no"]; ?>">
          </td>
      </tr>
    </tbody>        
     
         <?php 
		   
		
		  
		  $counter++; // menambah counter 
		  $no ++;  
		  $k ++; 
			   
			   
		}
			   
			   ?>   </table> 
        
            <p>&nbsp;</p> 
  <table width="1100">
               <tr>
                 <th width="120" class="ac style3">                 
         <input type="submit" onClick="return confirm('Are you sure you want to cancel this disposal? : <?php echo $doc_disposal; ?>?');" name="Submit2" id="button" value="CANCEL DISPOSAL" class="btn btn-success"/>
               </th>
                 <th width="80" class="ac style3"><input  name="btnback" type="button" id="btnCancel" class="btn btn-warning" value="BACK" onclick="javascript:parent.tb_remove();" /></th>
                 <th height="28" class="ac style3">&nbsp;</th> 
                 <th height="28" class="ac style3">&nbsp;</th>
               </tr>
               </table></form>
           </div>
           <!-- End Form -->
        


  <div class="footer_bawah"><?php include "footer.php";   ?></div>
<!--Footer-part-->        
    <!--<div class="bottom-left2">
  <input  name="btnback" type="button" id="btnCancel" class="btn btn-warning" value="BACK" onclick="javascript:parent.tb_remove();" />
</div>
-->
   </div></div> 
<!--end-Footer-part--> 

</body>
</html>