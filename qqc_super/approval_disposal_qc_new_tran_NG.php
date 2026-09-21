<?php

/**
 * qqc_super/approval_disposal_qc_new_tran_NG.php
 * Part of: QQC module (supervisor/admin tier)
 * Filename suggests: approval disposal qc new tran NG
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, reject_detail_disposal, type_reject_detail, reason_ng_reject, pps_detail, mat_master_header.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, tcpdf_barcodes_2d.php, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
error_reporting(E_ALL &~ E_NOTICE &~ E_DEPRECATED);
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
require_once('/tcpdf_barcodes_2d.php');

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "disposal_backflush_tran_NG.php";


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

 $extension = explode ('.', $data_setup["logo_name"]);
 $filename = $data_setup["logo_comp"].'.'.$extension[1];

//CR status (New)

$sta = "SELECT * from request_status WHERE status_id = '1' ";
$sta_res = mysqli_query($dbc, $sta);
$rst_sta = mysqli_fetch_array($sta_res);

//CR status (Approved)
$sta3 = "SELECT * from request_status WHERE status_id = '3' ";
$sta_res3 = mysqli_query($dbc, $sta3);
$rst_sta3 = mysqli_fetch_array($sta_res3);	

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
	    $amount .= $_POST["remark_approve"][$i]."|";
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
  
$queryu = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') as R, DATE_FORMAT(date_posting,'%d-%m-%Y') as R2 FROM reject_detail_disposal WHERE doc_disposal_no = '".db_esc($dbc, $doc_disposal)."'";
$rs = mysqli_query($dbc, $queryu);   //run the query.

//detail info disposal 

$query_disposal = "SELECT *, DATE_FORMAT(date_disposal,'%d-%m-%Y %H:%i:%s') as W, DATE_FORMAT(date_posting,'%d-%m-%Y') as W2 FROM reject_detail_disposal WHERE doc_disposal_no = '".db_esc($dbc, $doc_disposal)."'";
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

    $cancel = $_POST["cancel"]; 
    $how_many = count($cancel); 
	$remark_approve = $_POST["remark_approve"]; 
	$string = "";
       
	   
	   foreach($_POST["cancel"] as $j=>$i) {
	    
		$amount .= $_POST["remark_approve"][$i];
	    $string = explode("|",($amount));	
		
			}
			
       for ($i=0; $i<$how_many; $i++) { 
		   			
	
	    // echo $cancel[$i]; echo $string[$i];
		
//------------update remarks reject detail disposal-------------------
      
 $query_upd3 = "UPDATE reject_detail_disposal SET remark_approve = '".db_esc($dbc, $string[$i])."', approve_by = '".db_esc($dbc, $username)."', date_approve = NOW(), status_disposal = '".db_esc($dbc, $rst_sta3["status_desc"])."' WHERE id_disposal = '".db_esc($dbc, $cancel[$i])."'";
 $result_upd3 = mysqli_query($dbc, $query_upd3); 
 
 
       }

			 
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
<table width="98%" border="0" cellpadding="2">
  <tr>
    <td width="62%"><p><img src="../set_upload/<?php echo $filename;  ?>"width="267" height="27" hspace="2" vspace="2"/></p>
      <p>PT 2475-2476, Kawasan Perindustrian Nilai, P.O. Box 45,<br>
      71807 Nilai, Negeri Sembilan Darul Khusus, Malaysia. <br>
      Tel :+606-799 5599 Fax :+606-799 5597 / 8</p></td>
    <td width="38%"><table width="98%" border="0" cellpadding="2">
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
<p align="center" class="style4">&nbsp;</p>
<p align="center" class="style4">&nbsp;</p>
      <p align="center" class="style4">&nbsp;</p>
      <!-- Content -->
   
          
              <!-- End Box Head -->
          <form name="myform" action="approval_disposal_qc_new_tran_NG.php?doc_disposal=<?php echo h($doc_disposal); ?>" method="post" >
            <!-- Form -->
            <div class="form">
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
                 <th bgcolor="#E9F58D"><span class="style3">Approver Remarks </span></th>
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
		
		$query_type = "SELECT * FROM type_reject_detail WHERE id_type = '".db_esc($dbc, $row['type_reject'])."' ORDER BY id_type ASC";
		$result_type = mysqli_query($dbc, $query_type);
		$row_type = mysqli_fetch_array($result_type); 
		
		$query_reason = "SELECT * FROM reason_ng_reject WHERE id_reject = '".db_esc($dbc, $row['reason_reject'])."' ORDER BY id_reject ASC";
		$result_reason = mysqli_query($dbc, $query_reason);
		$row_reason = mysqli_fetch_array($result_reason);
		
		$query_model = "SELECT * FROM pps_detail WHERE plan_no = '".db_esc($dbc, $row['plan_no'])."'";
		$result_model = mysqli_query($dbc, $query_model);
		$data_model = mysqli_fetch_array($result_model);	
		
		$query_mat = "SELECT * FROM mat_master_header WHERE material_no = '".db_esc($dbc, $row['material_no'])."'";
		$result_mat = mysqli_query($dbc, $query_mat);
		$data_mat = mysqli_fetch_array($result_mat);	
		

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
                <td width="40" height="28"><input type="hidden" name="cancel[]" value="<?php echo $row["id_disposal"]; ?>" <?=was_checked($row["id_disposal"],$a) ?> /><input type="hidden" name="Check_ctr" value="yes" 
onClick="Check(document.myform.cancel)"> <?php  echo $row["id_disposal"]; ?></td>
                <td width="80"><?php  echo $data_model["model_code"]; ?></td>
                <td width="100"><?php echo $row["material_no"]; ?>&nbsp;</td>
                <td width="100"><?php echo $row_type["type_desc"]; ?></td>
                <td width="100"><?php echo $row["R2"]; ?></td>
                <td width="100"><div align="center"><input name="dis_quantity" type="text" readonly value="<?php echo $qty_asal; ?>"  class="span2"/></div></td>
                <td width="80"><?php echo $data_mat["BUn"]; ?></td>
                <td width="80"><?php echo $loc_asal; ?></td>   
                <td width="60"><div align="center"><?php echo $row["cost_center"]; ?></div></td>             
                <td width="150"><?php echo $row_reason["reject_desc"]; ?></td>
                <td width="200"><?php echo $row["remarks"]; ?></td>
                <td width="200"><textarea name="remark_approve[<?php echo $row["id_disposal"]; ?>]2" id="remark_approve[<?php echo $row["id_disposal"]; ?>]" rows="2" cols="10"></textarea> <input name="id_disposal[<?php echo $row["id_disposal"]; ?>]" type="hidden" value="<?php echo $row["id_disposal"]; ?>"></td>
      </tr>
    </tbody>        
     
         <?php 
		   
		
		  
		  $counter++; // menambah counter 
		  $no ++;  
		  $k ++; 
			   
			   
		}
			   
			   ?>   </table> 
        
            <p>&nbsp;</p> 
<table width="1000" border="0" cellpadding="1" cellspacing="2">
               <tr>
                 <th height="28" class="ac style3">&nbsp;</th>
                 <th width="101" height="28" class="ac style3">
                 
         <input type="submit" onClick="return confirm('Are you sure you want to approve this disposal? : <?php echo h($doc_disposal); ?>?');" name="Submit2" id="button" value="Approve" class="btn btn-success"/>
           
    </th>
                 <th width="61" class="ac style3"><input  name="btnback" type="button" id="btnCancel" class="btn btn-warning" value="BACK" onclick="javascript:parent.tb_remove();" /></th>
                 <th height="28" class="ac style3">&nbsp;</th>
               </tr>
               <tr>
                 <th width="14" height="28" class="ac style3">&nbsp;</th>
                 <th height="28" colspan="2">&nbsp;</th>
                 <th width="806" height="28">&nbsp;</th>
               </tr>
             </table>
           </div>
           <!-- End Form -->
        
</form>

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