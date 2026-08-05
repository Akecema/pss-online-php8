<?php

/**
 * ppc_super/close_MRIN_consumable_manual.php
 * Part of: PPC module (supervisor/admin tier)
 * Filename suggests: close MRIN consumable manual
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, consumable_request, factory_detail, sys_setup_maintain, post_consumable_detail_header, consumable_request_close, work_center_detail, reason_req_close.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, ]., th>
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

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
date_default_timezone_set('Asia/Kuala_Lumpur');


$url = "close_MRIN_ppc.php";

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
		
$temp_mrin = $_GET["mrin_no"];
 $user_no = $_GET["user_no"];
 
 
$query_u = "SELECT * FROM user_detail WHERE username = '$username'";
$result_u = mysqli_query($dbc, $query_u);   //run the query.
$data_u = mysqli_fetch_array($result_u);   //how many records are there? 



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
  body{ background-color:#FFFFFF; background-image:none; color:#000000 }
  #ad{ display:none;}
  #leftbar{ display:none;}
  #contentarea{ width:100%;}
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



<table class="table table-condensed">
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
 $user_no = $_POST["user_no"];
 $reason_close = $_POST["reason_close"];
 $reason_close2 = $_POST["reason_close2"];
 
 
           if(($_POST["reason_close"]) == "NULL")
				{
				  $reason_close = FALSE;
				  $message.= '<p align="center">Please select your reason.</p>';
				  }else{
				  $reason_close = TRUE;
				  }
			
				  
			 if(($_POST["reason_close"]) == "1")
				{
				    $reason_close2 = TRUE;
				    $reason_close = TRUE; 
				}	
				
				 if(($_POST["reason_close"]) == "2")
				{
				    $reason_close2 = TRUE;
				    $reason_close = TRUE; 
				}  
			
			 if(($_POST["reason_close"]) == "3")
				{
				    $reason_close2 = TRUE;
				    $reason_close = TRUE; 
				}  
			
			 if(($_POST["reason_close"]) == "4")
				{
				    $reason_close2 = TRUE;
				    $reason_close = TRUE; 
				}  	  
				  
			if(($_POST["reason_close"]) == "5")
				{
				
				if(($_POST["reason_close2"]) == "")
				{
				  $reason_close2 = FALSE;
				  $message.= '<p align="center">Please enter your reason.</p>';
				  }
				 } 
				  
			
    if($reason_close && $reason_close2)// everything OK
	{

$queryu = "SELECT * from consumable_request WHERE temp_mrin = '$temp_mrin'";
$rs = mysqli_query($dbc, $queryu);   //run the query.


	$query_2 = "SELECT *, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R from consumable_request as MR WHERE MR.temp_mrin = '$temp_mrin'";
	$result_2 = mysqli_query($dbc, $query_2);   //run the query.
	$data_2 = mysqli_fetch_array($result_2);

    $query3 = "SELECT * FROM factory_detail WHERE id_fac = '".$data_2["factory"]."'";
    $result3 = mysqli_query($dbc, $query3);
	$row3 = mysqli_fetch_array($result3);


	$query_tp = "SELECT *, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R FROM consumable_request AS MR WHERE MR.temp_mrin = '$temp_mrin' AND MR.status = 'New'";
	$result_tp  = mysqli_query($dbc, $query_tp); 
		
	   while ($row2 = mysqli_fetch_array($result_tp))
{


 $query_upd2 = "UPDATE `post_consumable_detail_header` SET status_posting = 'Close', date_close = NOW() WHERE mrin_no = '$temp_mrin'";
 $result_upd2 = mysqli_query($dbc, $query_upd2); 
	      
 $query_upd3 = "UPDATE `consumable_request` SET status = 'Close', user_update = '$user_no', date_update = NOW() WHERE temp_mrin = '$temp_mrin' AND material_no = '".$row2["material_no"]."' ";
 $result_upd3 = mysqli_query($dbc, $query_upd3); 
 
      
	   //-----------------------------------------------------------------------------------------------------------------------
		  //-insert consumable request with status "Close" into table consumable request close
		  //-----------------------------------------------------------------------------------------------------------------------
		  
		  	$query_mm3 = "SELECT * FROM `consumable_request` WHERE temp_mrin = '$temp_mrin' AND status = 'Close' AND material_no = '".$row2["material_no"]."'"; 
        	$result_mm3 = mysqli_query($dbc, $query_mm3) or die (mysqli_error($dbc));
			$row_mm3 = mysqli_fetch_array($result_mm3); 
			
		
		$query_mm3_insert =  "INSERT INTO consumable_request_close(id_req_con, mrin_doc, mrin_year, temp_mrin, id_con, id_scan, material_no, con_qty, con_uom, status_request, status_print, status_view, factory, user_create, date_create, user_update, date_update, date_posting, time_posting, status, date_require, time_require, reason_close, reason_close2, id_work) VALUES('".$row_mm3["id_req_con"]."','".$row_mm3["mrin_doc"]."','".$row_mm3["mrin_year"]."','".$row_mm3["temp_mrin"]."','".$row_mm3["id_con"]."','".$row_mm3["id_scan"]."','".$row_mm3["material_no"]."','".$row_mm3["con_qty"]."', '".$row_mm3["con_uom"]."','".$row_mm3["status_request"]."','".$row_mm3["status_print"]."','".$row_mm3["status_view"]."','".$row_mm3["factory"]."','".$row_mm3["user_create"]."','".$row_mm3["date_create"]."','".$row_mm3["user_update"]."','".$row_mm3["date_update"]."','".$row_mm3["date_posting"]."','".$row_mm3["time_posting"]."','".$row_mm3["status"]."','".$row_mm3["date_require"]."','".$row_mm3["time_require"]."','".$_POST["reason_close"]."','".$_POST["reason_close2"]."','".$row_mm3["id_work"]."')";
$result_mm3_insert = mysqli_query($dbc, $query_mm3_insert) or die (mysqli_error($dbc));
	  

}//end while loop
          

       //--------------------------------------------------------------------
       //copy yg close MRIN masuk dalam MRIN history
	   //---------------------------------------------------------------------
       
			 echo "<script>";
			 echo "self.parent.location.reload()";
		     //echo "parent.tb_remove(); parent.location.reload(1)";
		     echo "</script>"; 
		     exit(); //quit the script
			 
			 
			 
 } //print the message if there is one.
if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}

	}



?>    
    
        <br>
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>MRIN LIST </h5>
          </div>


        <table class="table table-bordered" >
        <tr>
          <th>MRIN No </th>
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
          <th><?php echo $row_k2["user_fullname"]; ?></th>
        </tr>
      </table>

        <br>
      
      

 <table class="table table-bordered">
               <thead>
               <tr>
                 <th>Material Number</th>
                 <th>Material Description</th>
                 <th>Quantity</th>
                 <th>Uom</th>
                 <th>Line</th>
                 <th>Transfer Location</th> 
                 <th>Prod Order</th>
                 <th>Cost Center</th> 
                 <th>Requested Quantity</th>
                 <th>Transfer Quantity</th>
                 <th>Outstanding Quantity</th>
                 </tr>
             	</thead>
             	<tbody> 

             <?php
      $counter = 1;
      $no = 1;
   
   while ($row = mysqli_fetch_array($rs))
   {
		//$user_no = $row[0]; 
   $no = sprintf('%03d', $no);
		
  //------------------cost center --------------------//
   $query_cost_center = "SELECT * FROM work_center_detail WHERE id_work = '".$row["id_work"]."'";
   $result_cost_center = mysqli_query($dbc, $query_cost_center) or die (mysqli_error($dbc));
   $row_cost_center = mysqli_fetch_array($result_cost_center);
		
		 ?>

                <tr>
                 <td width="73"><?php  echo $row["material_no"]; ?></td>
                 <td width="122"><?php  echo $row["mat_desc"]; ?></td>
                 <td width="90"><div align="right"><?php echo $row["con_qty"]; ?>&nbsp;</div></td>
                 <td width="63"><div align="center"><?php echo $row["con_uom"]; ?></div></td>
                 <td width="80"><div align="center"><?php echo $row["id_work"]; ?></div></td>
                 <td width="80"><div align="center"><?php echo $row["cost_center"]; ?></div></td>
                 <td width="80"><div align="center"><?php echo $row_cost_center["cost_center"]; ?></div></td>
                 <td width="90"><div align="center"><?php echo $row["con_qty"]; ?></div></td>
                 <td width="90"><div align="right">
	               <?php 
					
		$query_tp = "SELECT *,SUM(rquantity) as TOT FROM post_consumable_detail_header WHERE mrin_no = '".$row["temp_mrin"]."' AND mvt_type = 201 AND material_no = '".$row["material_no"]."' AND status_posting != 'Cancel'";
	    $result_tp  = mysqli_query($dbc, $query_tp); 
	    //$row_tp = mysqli_fetch_assoc($result_tp); 
		
					
		$outs_qty = 0;
					
	while($row_tp = mysqli_fetch_assoc($result_tp))
   {
	echo $row_tp["TOT"]; 
	
	$tp_quantity = $row_tp["TOT"]; 
	
	$outs_qty = (($row["con_qty"]) - ($row_tp["TOT"]));
	
	 }//end while $row_tp	
	 
	$outs_qty1 = number_format($outs_qty,3);
	

	 ?>
                </div></td>
    <td width="90" height="28" bgcolor="#FFCC99"><div align="right"><?php echo $outs_qty1; ?></div></td>
                <td width="90" height="28"><div align="center">
  <?php                 
                	//-------------------------------------------------------
					// tick and cross icon for update status
					//------------------------------------------------------------
   								
        if(($row["con_qty"] == $tp_quantity) || ($row["con_qty"] < $tp_quantity))
{        

?>
              <img src="../img/tick.png" width="25" height="25" title="OK"/>
             
            <?php
	 }elseif(($row["con_qty"] > $tp_quantity))
       {

?>
              <img src="../img/cross.png" width="25" height="25" title="Not OK"/>
    <?php
	
	} else{
	
	
	echo "invalid";  }   
                
     ?> 
    </div>           
</td>
                
      </tr>
          
       
         <?php 
		 
		  $counter++; // menambah counter 
		  $no ++;   
		  			   
			   }
			   
			   ?> </tbody></table>  
         <p>&nbsp;</p>
             <form method="post" action="close_MRIN_consumable_manual.php?mrin_no=<?php echo $temp_mrin; ?>&&user_no=<?php echo $user_no; ?>" >  
             <table width="80%" border="0" cellpadding="2" cellspacing="2" style="border:solid 1px #d5d5d5;">
               <tr>
                 <th width="16%">Reason</th>
                 <td width="2%">:</td>
                 <td width="82%"><select name="reason_close" id="reason_close" onchange="ShowReg2(this.selectedIndex);">
                 <?php 
				 
				 if(isset($_POST["reason_close"])) { 
                  
				  $query_r_1 = "SELECT * FROM reason_req_close WHERE id_close = '".$_POST["reason_close"]."'";
                   $result_r_1 = mysqli_query($dbc, $query_r_1);
				   $row_r_1 = mysqli_fetch_array($result_r_1);
   
   
   
   ?>
  <option value="<?php echo $_POST["reason_close"]; ?>"><?php echo $row_r_1["id_close"].' - '.$row_r_1["reason_desc"]; ?></option>
  <?php
  
  }else{
  
  ?>
    <option value="NULL" placeholder="Select Reason"> -- Select Reason --</option>
  <?php
  
     }
                    
	               $query_reason = "SELECT * FROM reason_req_close ORDER BY id_close ASC";
                   $result_reason = mysqli_query($dbc, $query_reason);
  
                   while($row_reason = mysqli_fetch_array($result_reason, MYSQLI_NUM)) 
			      {
                  echo'<option value="',$row_reason[0],'">',stripslashes($row_reason[0]),' - ',stripslashes($row_reason[1]),'</option>';
                  }
				   
				?>
                 </select>
                   * </td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
                 <td>
                 <div id="5" style="display:none" >
                     <p></p>
                   <span>
         <textarea name="reason_close2" cols="60" rows="3" id="reason_close2" placeholder="Enter reason here" ><?php echo prepopulate('reason_close2');?></textarea>
                     * </span></div></td>
               </tr>
               <tr>
                 <td>&nbsp; </td>
                 <td>&nbsp;
                 <input name="user_no" type="hidden" value="<?php echo $res["user_no"]; ?>"> 
                 <input name="mrin_no" type="hidden" value="<?php echo $temp_mrin; ?> ">
                 <input name="prod_order" type="hidden" value="<?php echo $prod_order; ?> "></td>
                 <td >* Compulsory field</td>
               </tr>
             </table>
            
              <p>&nbsp;</p>
         <input type="submit" onClick="return confirm('Are you sure you want to CLOSE MRIN No. : <?php echo $temp_mrin; ?>?');" name="submit" id="button" value="CLOSE MRIN" class="btn btn-success" /> &nbsp;&nbsp; 
         <input  name="btnback" type="button" id="btnCancel" class="btn btn-info" value="BACK" onclick="javascript:parent.tb_remove();" />
</form>

<br>
<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part--> 

</div>

</body>
</html>