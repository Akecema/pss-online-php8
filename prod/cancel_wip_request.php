<?php

/**
 * prod/cancel_wip_request.php
 * Part of: Production module
 * Filename suggests: cancel wip request
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, wip_request, factory_detail, mat_master_header, wip_request_cancel, scan_detail_wip, reason_req_cancel.
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

$url = "cancel_wip_request.php";


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
<script type="text/javascript" src="../javascript/multiValue1.js"></script>
<!----------------->
<link rel="stylesheet" href="../scripts/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="../javascript/jquery-latest.js"></script> 
<script type="text/javascript" src="../javascript/thickbox.js"></script>	
<link href="../calendar/calendar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../calendar/calendar.js"></script>

<?php

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
 $prod_order = $_GET["prod_order"];
 
$queryu = "SELECT * from wip_request WHERE temp_mrin_wip = '".db_esc($dbc, $temp_mrin)."'";
$rs = mysqli_query($dbc, $queryu);   //run the query.


$query_2 = "SELECT *, DATE_FORMAT(MR.date_mrin,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2 FROM wip_request as MR, scan_detail_wip as SD WHERE MR.id_scan_wip = SD.id_scan AND MR.temp_mrin_wip = '".db_esc($dbc, $temp_mrin)."'";
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
<table width="1000">
<tr>
      <td width="1%">&nbsp;</td>
      <td width="7%"><img src="../img/print2.jpg" width="48" height="48" onClick="window.print()" title="Print"/></td>
        <td width="7%"><a href="javascript:parent.tb_remove();" ><img src="../img/back3.jpg" width="48" height="48" /></a></td>
      <td width="85%"> <div class="small-nav"></div></td>
      
  </tr>

</table>
<table width="1000">
<tr>
  <td>&nbsp;</td>
  <td colspan="2"><div align="center"><span class="style4">MRIN LIST
    <?php if($data_2["status_urgent"] == "Y"){ echo "[Urgent]"; }     ?>
  </span></div></td>
  </tr>
</table>

<?php
	   
if(isset($_POST["submit"])) 
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


 $temp_mrin = $_GET["mrin_no"];
 $prod_order = $_GET["prod_order"];
 $user_no = $_POST["user_no"];
 $reason_cancel = $_POST["reason_cancel"];
 $reason_cancel2 = $_POST["reason_cancel2"];
 
 			if(($_POST["reason_cancel"]) == "NULL")
				{
				  $reason_cancel = FALSE;
				  $message.= '<p align="center">Please select your reason.</p>';
				  }else{
				  $reason_cancel = TRUE;
				  }
				  
				  
			 if(($_POST["reason_cancel"]) == "1")
				{
				    $reason_cancel2 = TRUE;
				    $reason_cancel = TRUE; 
				}	
				
				 if(($_POST["reason_cancel"]) == "2")
				{
				    $reason_cancel2 = TRUE;
				    $reason_cancel = TRUE; 
				}  
				  
			if(($_POST["reason_cancel"]) == "3")
				{
				
				if(($_POST["reason_cancel2"]) == "")
				{
				  $reason_cancel2 = FALSE;
				  $message.= '<p align="center">Please enter your reason.</p>';
				  }
				 } 
				 
				
				 // else{
				  //$reason_cancel2 = TRUE;
				//  $reason_cancel = TRUE; 
				//  }
				  
				  
    if($reason_cancel && $reason_cancel2)// everything OK
	{
$queryu = "SELECT * from wip_request WHERE temp_mrin_wip = '".db_esc($dbc, $temp_mrin)."'";
$rs = mysqli_query($dbc, $queryu);   //run the query.


$query_2 = "SELECT *, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R from wip_request as MR, scan_detail_wip as SD WHERE MR.id_scan_wip = SD.id_scan AND MR.temp_mrin_wip = '".db_esc($dbc, $temp_mrin)."'";
$result_2 = mysqli_query($dbc, $query_2);   //run the query.
$data_2 = mysqli_fetch_array($result_2);

    $query3 = "SELECT * FROM factory_detail WHERE id_fac = '".db_esc($dbc, $data_2["factory"])."'";
    $result3 = mysqli_query($dbc, $query3);
	$row3 = mysqli_fetch_array($result3);


$query_tp = "SELECT *, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R FROM wip_request AS MR, scan_detail_wip AS SD WHERE MR.id_scan_wip = SD.id_scan AND MR.temp_mrin_wip = '".db_esc($dbc, $temp_mrin)."' AND SD.prod_order = '".db_esc($dbc, $prod_order)."' AND MR.status = 'New'";

	$result_tp  = mysqli_query($dbc, $query_tp); 
		
	   while ($row2 = mysqli_fetch_array($result_tp))
{


  $query4_p = "SELECT * from mat_master_header as LD, mat_master_detail as SD WHERE SD.id_hdr = LD.id_hdr and SD.id_dtl = '".db_esc($dbc, $row2[5])."'";
  	$result4_p = mysqli_query($dbc, $query4_p);
 	$row4_p = mysqli_fetch_array($result4_p); 
	
      
 $query_upd3 = "UPDATE `wip_request` SET status = 'Cancel', user_update = '".db_esc($dbc, $user_no)."', date_update = NOW() WHERE temp_mrin_wip = '".db_esc($dbc, $temp_mrin)."' AND bom_component = '".db_esc($dbc, $row4_p["bill_component"])."' ";
 $result_upd3 = mysqli_query($dbc, $query_upd3); 
 
      
	      //-----------------------------------------------------------------------------------------------------------------------
		  //-insert material request with status "Cancel" into table material request cancel
		  //-----------------------------------------------------------------------------------------------------------------------
		  
		  	$query_mm3 = "SELECT * FROM `wip_request` WHERE temp_mrin_wip = '".db_esc($dbc, $temp_mrin)."' AND status = 'Cancel' AND id_dtl_wip = '".db_esc($dbc, $row2["id_dtl_wip"])."'"; 
        	$result_mm3 = mysqli_query($dbc, $query_mm3) or die(db_fail($dbc));
			$row_mm3 = mysqli_fetch_array($result_mm3); 
			
		
		$query_mm3_insert =  "INSERT INTO wip_request_cancel(id_req_wip, mrin_doc_wip, mrin_year_wip, temp_mrin_wip, id_hdr_wip, id_dtl_wip, id_scan_wip, bom_id_wip, bom_qty_wip, bom_oum_wip, status_request, status_print, user_create, date_create, user_update, date_update, date_posting, time_posting, status, bom_component, date_mrin, time_mrin, reason_cancel, reason_cancel2) VALUES('".db_esc($dbc, $row_mm3["id_req_wip"])."','".db_esc($dbc, $row_mm3["mrin_doc_wip"])."','".db_esc($dbc, $row_mm3["mrin_year_wip"])."','".db_esc($dbc, $row_mm3["temp_mrin_wip"])."','".db_esc($dbc, $row_mm3["id_hdr_wip"])."','".db_esc($dbc, $row_mm3["id_dtl_wip"])."','".db_esc($dbc, $row_mm3["id_scan_wip"])."','".db_esc($dbc, $row_mm3["bom_id_wip"])."','".db_esc($dbc, $row_mm3["bom_qty_wip"])."', '".db_esc($dbc, $row_mm3["bom_oum_wip"])."','".db_esc($dbc, $row_mm3["status_request"])."','".db_esc($dbc, $row_mm3["status_print"])."','".db_esc($dbc, $row_mm3["user_create"])."','".db_esc($dbc, $row_mm3["date_create"])."','".db_esc($dbc, $row_mm3["user_update"])."','".db_esc($dbc, $row_mm3["date_update"])."','".db_esc($dbc, $row_mm3["date_posting"])."','".db_esc($dbc, $row_mm3["time_posting"])."','".db_esc($dbc, $row_mm3["status"])."','".db_esc($dbc, $row_mm3["bom_component"])."','".db_esc($dbc, $row_mm3["date_mrin"])."','".db_esc($dbc, $row_mm3["time_mrin"])."','".db_esc($dbc, $_POST["reason_cancel"])."','".db_esc($dbc, $_POST["reason_cancel2"])."')";
$result_mm3_insert = mysqli_query($dbc, $query_mm3_insert) or die(db_fail($dbc));
	  
 
}//end while loop


       //--------------------------------------------------------------------
       //copy yg cancel MRIN 
	  // ---------------------------------------------------------------------
       
			 
			 echo "<script>";
		    //echo "alert('MRIN No. $temp_mrin is successfully close.');";
			// echo "window.location='posting_request_cancel.php'";
		     echo "parent.tb_remove(); parent.location.reload(1)";
		     echo "</script>"; 
		     exit(); //quit the script


 } //print the message if there is one.
if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}



}




?>
          <table class="table table-bordered">
          <tr>
          <th>MRIN No</th>
          <th>:</th>
          <td><?php echo $temp_mrin; ?></td>
          <th>Factory</th>
          <th>:</th>
          <td><?php echo $data_2["factory"];  ?></td>
        </tr>
        <tr>
          <th>Date &amp; Time</th>
          <th>:</th>
          <td><?php echo $data_2["R2"]; ?>&nbsp;<?php echo $data_2["time_posting"]; ?></td>
          <th>Required Date &amp; Time</th>
          <th>:</th>
          <td>&nbsp;<?php echo $data_2["R"]; ?>&nbsp;<?php echo $data_2["time_mrin"]; ?></td>
        </tr>
        <tr>
          <th>Requested by</th>
          <th>:</th>
          <td><?php echo $row_k["user_fullname"]; ?></td>
          <th>Prepared by (PPC)</th>
          <th>:</th>
          <td><?php //echo $row_k2["user_fullname"]; ?></td>
        </tr>
      </table>
<br>          
              <!-- End Box Head -->
          
              <table class="table table-bordered">
              <thead>
               <tr>
                 <th>Material Number</th>
                 <th>Material Description</th>
                 <th>Quantity</th>
                 <th>Uom</th>
                 <th>Production Order</th>
                 <th>Line</th>
                 <th>Transfer Location</th>
                 <th>Transfer Quantity</th>
                 <th>Received Sloc</th>
                 <th>Barcode</th>
               </tr>
               </thead><tbody>
            
             <?php
      $counter = 1;
   $no = 1;
   
   while ($row = mysqli_fetch_array($rs))
   {
		//$user_no = $row[0]; 
   $no = sprintf('%03d', $no);

   
   $query_scan = "SELECT * FROM scan_detail_wip WHERE id_scan = '".db_esc($dbc, $row[6])."'";
   $result_scan = mysqli_query($dbc, $query_scan);
   $row_scan = mysqli_fetch_array($result_scan);

		 
   $query1_p = "SELECT * FROM scan_detail_wip WHERE id_scan = '".db_esc($dbc, $row[6])."' GROUP BY id_scan";
   $result1_p = mysqli_query($dbc, $query1_p);
   $row1_p = mysqli_fetch_array($result1_p);
	
	
		 
  $query4_p = "SELECT * from mat_master_header as LD, mat_master_detail as SD WHERE SD.id_hdr = LD.id_hdr and SD.id_dtl = '".db_esc($dbc, $row[5])."'";
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
	  $sta = "S320";
	  }elseif($row4_p["mat_type"] == "Z200")
	 {
	  $sta = "";
	  }
	  else{
	  $sta = "Invalid";
	  }
	  
		 
		if(($row["bom_qty_wip"] != "") && ($row["bom_qty_wip"] != "0.000"))
		{
		 
		 ?>

<tr>
                <td width="100"><?php  echo $row4_p["bill_component"]; ?></td>
                <td width="122"><?php  echo $row4_p["material_desc_c"]; ?></td>
                <td width="100"><div align="right"><?php echo $row["bom_qty_wip"]; ?>&nbsp;</div></td>
                <td width="45"><div align="center"><?php echo $row["bom_oum_wip"]; ?></div></td>
                <td width="146"><div align="center"><?php echo $row_scan["prod_order"]; ?></div></td>
                <td width="42"><div align="center"><font color="#FF0000"><?php echo $row1_p["work_center"]; ?></font></div></td>
                <td width="90"><div align="center"><?php echo $sta; ?></div></td>
                <td width="90">&nbsp;</td>
                <td width="65"><div align="center"><font color="#FF0000"><?php echo $row4_p["sloc"]; ?></font></div></td>
                <td height="28">
                  <div align="left">
                    <?php

// set the barcode content and type

$bar_text = ($row4_p["bill_component"].'|'.$row["bom_qty_wip"].'|'.$row["bom_oum_wip"].'|'.$row_scan["prod_order"].'|'.$temp_mrin.'|'.$row4_p["sloc"].'|'.$sta);



$barcodeobj = new TCPDF2DBarcode($bar_text, 'PDF417');
echo $barcodeobj->getBarcodeSVGcode(1.0, 0.8, 'black');

?>
                   </div></td>
      </tr>
            
        
         <?php 
		   
		   }// end if else
		  
		  $counter++; // menambah counter 
  		  $no ++;   
			   
			   }
			   
			   ?></tbody></table> 
         <p>&nbsp;</p>
         
         <form action="cancel_wip_request.php?mrin_no=<?php echo $temp_mrin; ?>&&prod_order=<?php echo $prod_order; ?>" method="post" >
           <table width="80%" border="0" cellpadding="2" cellspacing="2" style="border:solid 1px #d5d5d5;">
             <tr>
               <th width="16%">Reason </th>
               <td width="2%">:</td>
               <td width="82%">
<select name="reason_cancel" id="reason_cancel" onchange="ShowReg(this.selectedIndex);">
<?php 

          if(isset($_POST["reason_cancel"])) { 
                  
				  $query_r_1 = "SELECT * FROM reason_req_cancel WHERE id_cancel = '".db_esc($dbc, $_POST["reason_cancel"])."'";
                   $result_r_1 = mysqli_query($dbc, $query_r_1);
				   $row_r_1 = mysqli_fetch_array($result_r_1);
   
   
   
   ?>
  <option value="<?php echo h($_POST["reason_cancel"]); ?>"><?php echo $row_r_1["id_cancel"].' - '.$row_r_1["reason_desc_cancel"]; ?></option>
  <?php
  
  }else{
  
  ?>
    <option value="NULL" placeholder="Select Reason"> -- Select Reason --</option>
  <?php
  
     }
	 
	               $query_reason = "SELECT * FROM reason_req_cancel ORDER BY id_cancel ASC";
                   $result_reason = mysqli_query($dbc, $query_reason);
  
                   while($row_reason = mysqli_fetch_array($result_reason, MYSQLI_NUM)) 
			      {
                  echo'<option value="',$row_reason[0],'">',stripslashes($row_reason[0]),' - ',stripslashes($row_reason[1]),'</option>';
                  }
				   
				?>
</select> *                   </td>
             </tr>
             <tr>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
               <td>
               <div id="3" style="display:none" >
                            <p></p>
                            
                            <span>
                           <textarea name="reason_cancel2" cols="60" rows="3" id="reason_cancel2" placeholder="Enter reason here" ><?php echo prepopulate('reason_cancel2');?></textarea>* </span></div>   </td>
             </tr>
             <tr>
               <td> 
                 <input name="user_no" type="hidden" value="<?php echo $res["user_no"]; ?>"> 
                 <input name="mrin_no" type="hidden" value="<?php echo $temp_mrin; ?> ">
                 <input name="prod_order" type="hidden" value="<?php echo $prod_order; ?> "></td>
               <td>&nbsp;</td>
               <td>* Compulsory field</td>
             </tr>
           </table>
         
	
              <p>&nbsp;</p>
             <input type="submit" onClick="return confirm('Are you sure you want to CANCEL MRIN No. : <?php echo $temp_mrin; ?>?');" name="submit" id="button" value="CANCEL MRIN" class="btn btn-success" />&nbsp;&nbsp; 
     <input  name="btnback" type="button" id="btnCancel"  class="btn btn-info" value="BACK" onclick="javascript:parent.tb_remove();" />       
</form>


<br> 

<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part--> 
</div>
</body>
</html>