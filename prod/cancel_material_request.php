<?php

/**
 * prod/cancel_material_request.php
 * Part of: Production module
 * Filename suggests: cancel material request
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, material_request, factory_detail, mat_master_header, material_request_cancel, scan_detail, reason_req_cancel.
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

$url = "material_request_list.php";


    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
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
 $prod_order = $_GET["prod_order"];
 
$queryu = "SELECT * from material_request WHERE temp_mrin = '".db_esc($dbc, $temp_mrin)."'";
$rs = mysqli_query($dbc, $queryu);   //run the query.


$query_2 = "SELECT *, DATE_FORMAT(MR.date_mrin,'%d-%m-%Y') as R,DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2 from material_request as MR, scan_detail as SD WHERE MR.id_scan = SD.id_scan AND MR.temp_mrin = '".db_esc($dbc, $temp_mrin)."'";
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
<!--
<script type="text/javascript">

function confirmation() {
    var answer = confirm("Are you sure you want to CANCEL MRIN No. : <?php echo $temp_mrin; ?>?")
    if (answer){
        alert("MRIN Mo. <?php echo $temp_mrin; ?> is CANCEL !")
		
	top.frames['mainFrame'].location.href = "cancel_material_requestProc.php?mrin_no=<?php echo $temp_mrin; ?>&&prod_order=<?php echo $prod_order; ?>&&user_no=<?php echo $data_u["user_no"]; ?>";
    }
    else{
        alert("MRIN is failed.")
		//top.window.location = "close_MRIN_ppc.php";
		top.frames['mainFrame'].location.href = 'posting_request_cancel.php';
		
    }
}
</script>   -->


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
$queryu = "SELECT * from material_request WHERE temp_mrin = '".db_esc($dbc, $temp_mrin)."'";
$rs = mysqli_query($dbc, $queryu);   //run the query.


$query_2 = "SELECT *, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R from material_request as MR, scan_detail as SD WHERE MR.id_scan = SD.id_scan AND MR.temp_mrin = '".db_esc($dbc, $temp_mrin)."'";
$result_2 = mysqli_query($dbc, $query_2);   //run the query.
$data_2 = mysqli_fetch_array($result_2);

    $query3 = "SELECT * FROM factory_detail WHERE id_fac = '".db_esc($dbc, $data_2["factory"])."'";
    $result3 = mysqli_query($dbc, $query3);
	$row3 = mysqli_fetch_array($result3);


$query_tp = "SELECT *, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R FROM material_request AS MR, scan_detail AS SD WHERE MR.id_scan = SD.id_scan AND MR.temp_mrin = '".db_esc($dbc, $temp_mrin)."' AND SD.prod_order = '".db_esc($dbc, $prod_order)."' AND MR.status = 'New'";

	$result_tp  = mysqli_query($dbc, $query_tp); 
		
	   while ($row2 = mysqli_fetch_array($result_tp))
{


  $query4_p = "SELECT * from mat_master_header as LD, mat_master_detail as SD WHERE SD.id_hdr = LD.id_hdr and SD.id_dtl = '".db_esc($dbc, $row2[5])."'";
  	$result4_p = mysqli_query($dbc, $query4_p);
 	$row4_p = mysqli_fetch_array($result4_p); 
	
      
 $query_upd3 = "UPDATE `material_request` SET status = 'Cancel', user_update = '".db_esc($dbc, $user_no)."', date_update = NOW() WHERE temp_mrin = '".db_esc($dbc, $temp_mrin)."' AND bom_component = '".db_esc($dbc, $row4_p["bill_component"])."' ";
 $result_upd3 = mysqli_query($dbc, $query_upd3); 
 
      
	      //-----------------------------------------------------------------------------------------------------------------------
		  //-insert material request with status "Cancel" into table material request cancel
		  //-----------------------------------------------------------------------------------------------------------------------
		  
		  	$query_mm3 = "SELECT * FROM `material_request` WHERE temp_mrin = '".db_esc($dbc, $temp_mrin)."' AND status = 'Cancel' AND bom_component = '".db_esc($dbc, $row4_p["bill_component"])."'"; 
        	$result_mm3 = mysqli_query($dbc, $query_mm3) or die (mysqli_error($dbc));
			$row_mm3 = mysqli_fetch_array($result_mm3); 
			
		
		$query_mm3_insert =  "INSERT INTO material_request_cancel(id_req, mrin_doc, mrin_year, temp_mrin, id_hdr, id_dtl, id_scan, bom_id, bom_qty, bom_oum, status_request, status_print, user_create, date_create, user_update, date_update, date_posting, time_posting, status, bom_component, date_mrin, time_mrin, reason_cancel, reason_cancel2) VALUES('".db_esc($dbc, $row_mm3["id_req"])."','".db_esc($dbc, $row_mm3["mrin_doc"])."','".db_esc($dbc, $row_mm3["mrin_year"])."','".db_esc($dbc, $row_mm3["temp_mrin"])."','".db_esc($dbc, $row_mm3["id_hdr"])."','".db_esc($dbc, $row_mm3["id_dtl"])."','".db_esc($dbc, $row_mm3["id_scan"])."','".db_esc($dbc, $row_mm3["bom_id"])."','".db_esc($dbc, $row_mm3["bom_qty"])."', '".db_esc($dbc, $row_mm3["bom_oum"])."','".db_esc($dbc, $row_mm3["status_request"])."','".db_esc($dbc, $row_mm3["status_print"])."','".db_esc($dbc, $row_mm3["user_create"])."','".db_esc($dbc, $row_mm3["date_create"])."','".db_esc($dbc, $row_mm3["user_update"])."','".db_esc($dbc, $row_mm3["date_update"])."','".db_esc($dbc, $row_mm3["date_posting"])."','".db_esc($dbc, $row_mm3["time_posting"])."','".db_esc($dbc, $row_mm3["status"])."','".db_esc($dbc, $row_mm3["bom_component"])."','".db_esc($dbc, $row_mm3["date_mrin"])."','".db_esc($dbc, $row_mm3["time_mrin"])."','".db_esc($dbc, $_POST["reason_cancel"])."','".db_esc($dbc, $_POST["reason_cancel2"])."')";
$result_mm3_insert = mysqli_query($dbc, $query_mm3_insert) or die (mysqli_error($dbc));
	  
 
}//end while loop


 //--------------------------------------------------------------------
       //copy yg cancel MRIN 
	   //---------------------------------------------------------------------
       
			 
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
<p align="center" class="style4">&nbsp;</p>
       <table class="table table-bordered">
        <tr>
          <th width="204" height="28"><div align="left"><span class="style3">MRIN No</span></div></th>
          <th width="9" height="28"><span class="style3">:</span></th>
          <td width="322"><span class="style3"><?php echo $temp_mrin; ?></span></td>
          <th width="204" height="28"><div align="left"><span class="style3">Factory</span></div></th>
          <th width="11" height="28"><span class="style3">:</span></th>
          <td width="218" height="28"><span class="style3"><?php echo $data_2["factory"];  ?></span></td>
        </tr>
        <tr>
          <th width="204" height="28"><div align="left"><span class="style3">Date &amp; Time</span></div></th>
          <th height="28"><span class="style3">:</span></th>
          <td><span class="style3"><?php echo $data_2["R2"]; ?>&nbsp;<?php echo $data_2["time_posting"]; ?></span></td>
          <th width="208" height="28"><div align="left"><span class="style3">Required Date &amp; Time</span></div></th>
          <th height="28"><span class="style3">:</span></th>
          <td height="28"><span class="style3">&nbsp;<?php echo $data_2["R"]; ?>&nbsp;<?php echo $data_2["time_mrin"]; ?></span></td>
        </tr>
        <tr>
          <th height="28"><div align="left"><span class="style3">Requested by</span></div></th>
          <th height="28"><span class="style3">:</span></th>
          <td><span class="style3"><?php echo $row_k["user_fullname"]; ?></span></td>
          <th height="28"><div align="left"><span class="style3">Prepared by (PPC)</span></div></th>
          <th height="28"><span class="style3">:</span></th>
          <td height="28"><span class="style3"><?php //echo $row_k2["user_fullname"]; ?></span></td>
        </tr>
      </table>
<p align="center" class="style4">&nbsp;</p>
      <p align="center" class="style4">&nbsp;</p>
      <!-- Content -->
   
          
              <!-- End Box Head -->
          <form action="cancel_material_request.php?mrin_no=<?php echo $temp_mrin; ?>&&prod_order=<?php echo $prod_order; ?>" method="post" >
            <!-- Form -->
            <div class="form">
             <table width="1000" border="1" cellpadding="0" cellspacing="0" bordercolor="#666666" style="border:solid 1px #d5d5d5;">
               <tr>
                 <th width="100" height="28" bgcolor="#E9F58D" class="ac style3">Material Number</th>
                 <th width="122" height="28" bgcolor="#E9F58D"><span class="style3">Material Description</span></th>
                 <th width="100" bgcolor="#E9F58D"><span class="style3">Quantity</span></th>
                 <th width="45" bgcolor="#E9F58D"><span class="style3">Uom</span></th>
                 <th width="146" bgcolor="#E9F58D"><span class="style3">Prod Order</span></th>
                 <th width="42" bgcolor="#E9F58D"><span class="style3">Line</span></th>
                 <th width="90" bgcolor="#E9F58D"><span class="style3">Transfer Location</span></th>
                 <th width="90" height="28" bgcolor="#E9F58D"><span class="style3">Transfer Quantity</span></th>
                 <th width="65" height="28" bgcolor="#E9F58D"><span class="style3">Received Sloc</span></th>
                 <th height="28" bgcolor="#E9F58D"><span class="style3">Barcode</span></th>
               </tr>
             </table>  
             <?php
      $counter = 1;
   $no = 1;
   
   while ($row = mysqli_fetch_array($rs))
   {
		//$user_no = $row[0]; 
 $no = sprintf('%03d', $no);
		
		if ($counter % 2 == 0)
		{ $warna = $warnaGenap;}
		else { $warna = $warnaGanjil; }	
   
   $query_scan = "SELECT * FROM scan_detail WHERE id_scan = '".db_esc($dbc, $row[6])."'";
   $result_scan = mysqli_query($dbc, $query_scan);
   $row_scan = mysqli_fetch_array($result_scan);

		 
   $query1_p = "SELECT * FROM scan_detail WHERE id_scan = '".db_esc($dbc, $row[6])."' GROUP BY id_scan";
   $result1_p = mysqli_query($dbc, $query1_p);
   $row1_p = mysqli_fetch_array($result1_p);
	
	
		 
  $query4_p = "SELECT * from mat_master_header as LD, mat_master_detail as SD WHERE SD.id_hdr = LD.id_hdr and SD.id_dtl = '".db_esc($dbc, $row[5])."'";
  $result4_p = mysqli_query($dbc, $query4_p);
  $row4_p = mysqli_fetch_array($result4_p); 
		  	 
	if($row4_p["mat_type"] == "Z100")
	{
	  $sta = "W210";
	} elseif($row4_p["mat_type"] == "Z200")	 
	{
	  $sta = "W221";
	} elseif($row4_p["mat_type"] == "Z300")
	 {
	  $sta = "NO";
	  }else{
	  $sta = "Invalid";
	  }
	  
		 
		if(($row["bom_qty"] != "") && ($row["bom_qty"] != "0.000"))
		{
		 
		 ?>

<table width="1000" border="1" cellpadding="2" cellspacing="0" bordercolor="#CCCCCC">
<tr>
               <td width="100" height="28"><?php  echo $row4_p["bill_component"]; ?></td>
               <td width="122" height="28"><?php  echo $row4_p["material_desc_c"]; ?></td>
                <td width="100" height="28"><div align="right"><?php echo $row["bom_qty"]; ?>&nbsp;</div></td>
                <td width="45" height="28"><div align="center"><?php echo $row["bom_oum"]; ?></div></td>
                <td width="146" height="28"><div align="center"><?php echo $row_scan["prod_order"]; ?></div></td>
                <td width="42" height="28"><div align="center"><font color="#FF0000"><?php echo $row1_p["work_center"]; ?></font></div></td>
                <td width="90" height="28"><div align="center"><?php echo $sta; ?></div></td>
                <td width="90" height="28">&nbsp;</td>
                <td width="65" height="28"><div align="center"><font color="#FF0000"><?php echo $row4_p["sloc"]; ?></font></div></td>
                <td height="28">
                  <div align="left">
                    <?php

// set the barcode content and type

$bar_text = ($row4_p["bill_component"].'|'.$row["bom_qty"].'|'.$row["bom_oum"].'|'.$row_scan["prod_order"].'|'.$temp_mrin.'|'.$row4_p["sloc"].'|'.$sta);

$barcodeobj = new TCPDF2DBarcode($bar_text, 'PDF417');
echo $barcodeobj->getBarcodeSVGcode(1.0, 0.8, 'black');

?>
                   </div></td>

			  <input name="user_no" type="hidden" value="<?php echo $data_u["user_no"]; ?>">    
                  
      </tr>
            
        </table> 
         <?php 
		   
		   }// end if else
		
		  
		  $counter++; // menambah counter 
		 
			   
			 $no ++;   
			   
			   
			   }
			   
			   ?>
         <p>&nbsp;</p>
           <table width="80%" border="0" cellpadding="2" cellspacing="2" style="border:solid 1px #d5d5d5;">
             <tr>
               <th width="16%" height="30" class="style3"><div align="right">Reason </div></th>
               <td width="2%" height="30" class="style3">:</td>
   <td width="82%" height="30">
   
   
   
<select name="reason_cancel" id="reason_cancel" onchange="ShowReg(this.selectedIndex);">
<?php if(isset($_POST["reason_cancel"])) { 
                  
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
               <td height="30">&nbsp;</td>
               <td height="30">&nbsp;</td>
               <td height="30">
               <div id="3" style="display:none" >
                            <p></p>
                            
                            <span>
                           <textarea name="reason_cancel2" cols="60" rows="3" id="reason_cancel2" placeholder="Enter reason here" style="background:#E9F58D"><?php echo prepopulate('reason_cancel2');?></textarea>*                            </span></div>               </td>
             </tr>
             <tr>
               <td height="30">&nbsp;</td>
               <td height="30">&nbsp;</td>
               <td height="30">* Compulsary field</td>
             </tr>
           </table>
            <p>&nbsp;</p> 
<table width="1000" border="0" cellpadding="1" cellspacing="2">
               <tr>
                 <th height="28" class="ac style3">&nbsp;</th>
                 <th width="101" height="28" class="ac style3">
                 
         <input type="submit" onClick="return confirm('Are you sure you want to CANCEL MRIN No. : <?php echo $temp_mrin; ?>?');" name="submit" id="button" value="CANCEL MRIN" class="button"/>
              <!--   <input type="submit" name="confirm" value="CANCEL MRIN" id="submit3" class="button" onClick="return confirmation()" /> -->
    </th>
                 <th width="61" class="ac style3"><input  name="btnback" type="button" id="btnCancel" class="button" value="BACK" onclick="javascript:parent.tb_remove();" /></th>
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


<br> 

<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part--> 
</div>
</body>
</html>