<?php

/**
 * prod/cancel_consumable_request.php
 * Part of: Production module
 * Filename suggests: cancel consumable request
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, consumable_request, factory_detail, consumable_request_cancel, work_center_detail, reason_req_cancel.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, tcpdf_barcodes_2d.php, ]., th>
          <th><?php echo $row_k[, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
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

$url = "display_consumable_request.php";


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
<script type="text/javascript" src="../javascript/multiValue1.js"></script>

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
 $user_no = $_GET["user_no"];
 
$queryu = "SELECT * from consumable_request as MR, consumable_detail as SD WHERE MR.id_con = SD.id_con AND MR.temp_mrin = '$temp_mrin'";
$rs = mysqli_query($dbc, $queryu);   //run the query.


$query_2 = "SELECT *, DATE_FORMAT(MR.date_require,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2 from consumable_request as MR, consumable_detail as SD WHERE MR.id_con = SD.id_con AND MR.temp_mrin = '$temp_mrin'";
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
  <td colspan="2"><div align="center"><span class="style4">MRIN LIST</span></div></td>
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


$temp_mrin = $_POST["mrin_no"];
// $prod_order = $_GET["prod_order"];
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
$queryu2 = "SELECT * from consumable_request WHERE temp_mrin = '$temp_mrin'";
$rs2 = mysqli_query($dbc, $queryu2);   //run the query.


$query_2a = "SELECT *, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R from consumable_request as MR, consumable_detail as SD WHERE MR.id_con = SD.id_con AND MR.temp_mrin = '$temp_mrin'";
$result_2a = mysqli_query($dbc, $query_2a);   //run the query.
$data_2a = mysqli_fetch_array($result_2a);

    $query3a = "SELECT * FROM factory_detail WHERE id_fac = '".$data_2a["factory"]."'";
    $result3a = mysqli_query($dbc, $query3a);
	$row3a = mysqli_fetch_array($result3a);


$query_tp = "SELECT *, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R FROM consumable_request AS MR, consumable_detail AS SD WHERE MR.id_con = SD.id_con AND MR.temp_mrin = '$temp_mrin' AND MR.status = 'New'";

	$result_tp  = mysqli_query($dbc, $query_tp); 
		
	   while ($row2 = mysqli_fetch_array($result_tp))
{


      
 $query_upd3 = "UPDATE consumable_request SET status = 'Cancel', user_update = '$user_no', date_update = NOW() WHERE temp_mrin = '$temp_mrin' AND id_con = '".$row2["id_con"]."' ";
 $result_upd3 = mysqli_query($dbc, $query_upd3); 
 
      
	      //-----------------------------------------------------------------------------------------------------------------------
		  //-insert consumable request with status "Cancel" into table material request cancel
		  //-----------------------------------------------------------------------------------------------------------------------
		  
		  	$query_mm3 = "SELECT * FROM consumable_request WHERE temp_mrin = '$temp_mrin' AND status = 'Cancel' AND id_con = '".$row2["id_con"]."'"; 
        	$result_mm3 = mysqli_query($dbc, $query_mm3) or die (mysqli_error($dbc));
			$row_mm3 = mysqli_fetch_array($result_mm3); 
			
		
		$query_mm3_insert =  "INSERT INTO consumable_request_cancel(id_req_con, mrin_doc, mrin_year, temp_mrin, id_con, id_scan, material_no, con_qty, con_uom, status_request, status_print, status_view, factory, user_create, date_create, user_update, date_update, date_posting, time_posting, status, date_require, time_require, reason_cancel, reason_cancel2, id_work) VALUES('".$row_mm3["id_req_con"]."','".$row_mm3["mrin_doc"]."','".$row_mm3["mrin_year"]."','".$row_mm3["temp_mrin"]."','".$row_mm3["id_con"]."','".$row_mm3["id_scan"]."','".$row_mm3["material_no"]."','".$row_mm3["con_qty"]."','".$row_mm3["con_uom"]."', '".$row_mm3["status_request"]."','".$row_mm3["status_print"]."','".$row_mm3["status_view"]."','".$row_mm3["factory"]."','".$row_mm3["user_create"]."','".$row_mm3["date_create"]."','".$row_mm3["user_update"]."','".$row_mm3["date_update"]."','".$row_mm3["date_posting"]."','".$row_mm3["time_posting"]."','".$row_mm3["status"]."','".$row_mm3["date_require"]."','".$row_mm3["time_require"]."','".$_POST["reason_cancel"]."','".$_POST["reason_cancel2"]."','".$row_mm3["id_work"]."')";
$result_mm3_insert = mysqli_query($dbc, $query_mm3_insert) or die (mysqli_error($dbc));
	    
 
}//end while loop


       //--------------------------------------------------------------------
       //copy yg cancel MRIN 
	   //---------------------------------------------------------------------
       
			 
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
      
       <table class="table table-bordered">
        <tr>
          <th>MRIN No. </th>
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
          <th>&nbsp;<?php echo $data_2["R"]; ?>&nbsp;<?php echo $data_2["time_require"]; ?></th>
        </tr>
        <tr>
          <th>Requested by</th>
          <th>:</th>
          <th><?php echo $row_k["user_fullname"]; ?></th>
          <th>Prepared by (PPC)</th>
          <th>:</th>
          <th><?php //echo $row_k2["user_fullname"]; ?></th>
        </tr>
      </table>
		<br>
              <!-- End Box Head -->
          <form action="cancel_consumable_request.php" method="post" >
             <table class="table table-bordered">
             <thead>
               <tr>
                 <th>Material Number</th>
                 <th>Material Description</th>
                 <th>Quantity</th>
                 <th>Uom</th>
                 <th>Line</th>
                 <th>Transfer Location</th>
                 <th>Transfer Quantity</th>
                 <th>Cost Center</th>
               </tr>
             </thead><tbody>  
             <?php
      $counter = 1;
      $no = 1;
   
   while ($row = mysqli_fetch_array($rs))
   {
		
 $no = sprintf('%03d', $no);
		
 
 //------------------cost center --------------------//
   $query_cost_center = "SELECT * FROM work_center_detail WHERE id_work = '".$row["id_work"]."'";
   $result_cost_center = mysqli_query($dbc, $query_cost_center) or die (mysqli_error($dbc));
   $row_cost_center = mysqli_fetch_array($result_cost_center);
		
		 
		 ?>

        <tr>
        <td width="100"><?php  echo $row["material_no"]; ?></td>
        <td width="200"><?php  echo $row["mat_desc"]; ?></td>
        <td width="100"><div align="right"><?php echo $row["con_qty"]; ?>&nbsp;</div></td>
        <td width="65" ><div align="center"><?php echo $row["con_uom"]; ?></div></td>
        <td width="80" ><div align="center"><?php echo $row["id_work"]; ?></div></td>
	    <td width="80"><div align="center"><?php echo $row["cost_center"]; ?></div></td>
	    <td width="100">&nbsp;</td>
	    <td width="80"><div align="center"><?php echo $row_cost_center["cost_center"]; ?></div></td>
         <input name="user_no" type="hidden" value="<?php echo $res["user_no"]; ?>"> 
      </tr>
         <?php 
		  
		  $counter++; // menambah counter 			   
	      $no ++;   
			
			   
			   }
			   
			   ?>
        </tbody>
        </table> 
         <br>
        <table class="table table-bordered">
        <thead>
        <tr>
        <th width="16%">Reason</th>
        <td width="2%" height="30" class="style3">:</td>
        <td width="82%" height="30">
        <select name="reason_cancel" id="reason_cancel" onchange="ShowReg(this.selectedIndex);">
         <?php if(isset($_POST["reason_cancel"])) { 
                  
				  $query_r_1 = "SELECT * FROM reason_req_cancel WHERE id_cancel = '".$_POST["reason_cancel"]."'";
                   $result_r_1 = mysqli_query($dbc, $query_r_1);
				   $row_r_1 = mysqli_fetch_array($result_r_1);
   
   
   
         ?>
         <option value="<?php echo $_POST["reason_cancel"]; ?>"><?php echo $row_r_1["id_cancel"].' - '.$row_r_1["reason_desc_cancel"]; ?></option>
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
</select> *    </td>
             </tr>
             <tr>
               <th>&nbsp;</th>
               <td>&nbsp;</td>
                <td>&nbsp;
               <div id="3" style="display:none" >
                            <p></p>
                            
                            <span>
                           <textarea name="reason_cancel2" cols="60" rows="3" id="reason_cancel2" placeholder="Enter reason here"><?php echo prepopulate('reason_cancel2');?></textarea>*                            </span></div>               </td>
             </tr>
             <tr>
               <th>&nbsp;</th>
               <td>&nbsp;</td>
               <td>* Compulsary field</td>
             </tr>
           </thead>
           </table>
           
            <p>&nbsp;</p> 
           
           <br>
            <input type="submit" onClick="return confirm('Are you sure you want to CANCEL MRIN No. : <?php echo $temp_mrin; ?>?');" name="submit" id="button" value="CANCEL MRIN" class="btn btn-danger"/>
            &nbsp;
            <input  name="btnback" type="button" id="btnCancel" class="btn btn-warning" value="BACK" onclick="javascript:parent.tb_remove();" />
           
    <input name="mrin_no" type="hidden" value="<?php echo $temp_mrin; ?>" />
</form>

<br> 

<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part--> 
</div>
</body>
</html>