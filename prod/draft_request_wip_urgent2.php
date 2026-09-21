<?php

/**
 * prod/draft_request_wip_urgent2.php
 * Part of: Production module
 * Filename suggests: draft request wip urgent2
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, scan_detail_wip, wip_request, WIP, scan_detail, mat_master_header.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_production_menu.php, footer.php.
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

date_default_timezone_set('Asia/Bangkok');
//$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
      $url = "wip_request_urgent.php";
	  
      $current_date = date('Y-m-d H:i:s'); 
	  $next_date = date('Y-m-d H:i:s', strtotime($current_date .' +1 day'));

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

<!----------------->
<link rel="stylesheet" href="../scripts/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="../javascript/jquery-latest.js"></script> 
<script type="text/javascript" src="../javascript/thickbox.js"></script>	
<link href="../calendar/calendar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../calendar/calendar.js"></script>

<style type="text/css" media="print"> 
	  
.breakAfter{ 
page-break-after: always; 
}
.breakBefore{
page-break-before: always ;
}
.tfoot { display: table-footer-group; }
.page {
 size:landscape;
   
}

 @media print{
  body{  margin-top: -1.8cm;  font-family: Arial, Helvetica, sans-serif; font-size:12px; }
  #ad{ display:none;}
  #leftbar{ display:none;}
  #contentarea{ width:100%;}
  .header, .hide { visibility: hidden }
  .tfoot { display: table-footer-group; }
  @page {size: landscape}
  /*tr.page-break  { display: block; page-break-before: always; }  */
  
} 

</style> 
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>

<body>


<!--Header-part-->
<div id="header">
  <h1>&nbsp;</h1>
</div>
<?php  include "top_modal_menu.php";   ?>
<!--close-Header-part--> 

<!--sidebar-menu-->
<?php include "left_production_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_production.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">WIP Request</a> <a href="#" class="current">WIP Request Urgent</a> </div>
  <h1>WIP Request</h1>
</div>

<div class="container-fluid">
  <hr>
  <div class="row-fluid">
  <!--  <div class="span6">-->
          <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li><a  role="tab" href="wip_request_urgent.php">New Request</a></li>
              <li class="active"><a role="tab" href="draft_request_wip_urgent.php">Draft Request</a></li>
              <li><a role="tab" href="display_request_wip_urgent.php">Display Request</a></li>
            </ul>
          </div>
          </div>
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Draft Request Urgent - WIP</h5>
        </div>
        
        <table width="930">
          <tr>
            <td width="2%" height="45"></td>
            <td width="98%"><form name="frmSearch" method="get" action="<?=$_SERVER['SCRIPT_NAME'];?>">
              <table width="724">
                <tr>
                  <th width="882" height="41"><div align="center">Material No :
                      <input name="txtKeyword" type="text" id="txtKeyword" size="60" value="<?php echo $_GET["txtKeyword"]; ?>">                      
                      <input type="submit" value="Search" class="btn btn-small">
                  </div></th>
                </tr>
              </table>
            </form></td>
        </tr>
      </table>
        
  
       <?php
	  if (isset($_POST['confirm'])) { 
    $cancel = $_POST["cancel"]; 
    $how_many = count($cancel); 
  
    if ($how_many > 0) { 
     
    } 
    for ($i=0; $i<$how_many; $i++) { 
      //  echo ($i+1) . '- ' . $cancel[$i] . '<br>'; 
		 
		  $query_m23 = "DELETE FROM `scan_detail_wip` WHERE id_scan = '$cancel[$i]'";
		  $result_m23 = mysqli_query($dbc, $query_m23) or die (mysqli_error($dbc));
		 
		  $query_m24 = "DELETE FROM `wip_request` WHERE id_scan_wip = '$cancel[$i]'";
		  $result_m24 = mysqli_query($dbc, $query_m24) or die (mysqli_error($dbc));
	  
	    }
		
		
		   echo "<script>";
		   echo "alert('Material Request successfully deleted.');";
		   echo "window.location='display_request_wip_urgent.php'";
	       echo "</script>"; 
		   exit(); //quit the script
		  	  
	}  
	  ?>
      <?php
	 if(isset($_POST["nextsave"])) 
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

 $size2 = count($_POST["id_scan"]) + 1;

 $i = 1;
 
     //check only deilvery quantity in number
	 
               if(empty($_POST['bom_qty'][$i]))
				{
				  $bom_qty = FALSE;
				  $message.= '<p align="center">You are required to enter REQUIRED QUANTITY!</p>';
				  }
				  elseif(!is_numeric($_POST['bom_qty'][$i]))
				  { $bom_qty = FALSE;
				   $message.= '<p align="center">You are required to enter NUMBERS only for REQUIRED QUANTITY!</p>';
				  }  
				  else
				  { 
				  $bom_qty= escape_data($_POST['bom_qty'][$i]);
				  }	             
	
	if($bom_qty) //everything ok
{  	
  //----------------------------------------------------------------------------//-
  //baca asn yg ada kt database, check total asn, open balance                 //
  //                                                                           //
  //---------------------------------------------------------------------------//
  
  while ($i < $size2) {
    
//update table material request with new quantity

$query_update = "UPDATE wip_request SET bom_qty_wip = '".$_POST["bom_qty"][$i]."', user_update = '".$res["user_no"]."', date_update = NOW() WHERE id_req_wip = '".$_POST["id_req"][$i]."' ";

$result_update = mysqli_query($dbc, $query_update);

	 $i++;
   } // end while loop	
   	
     if($result_update)
           {
		   
		   echo "<script>";
		   echo "alert('Please scan next material.');";
		   echo "window.location='wip_request_urgent.php'";
	       echo "</script>"; 
		   exit(); //quit the script
			 
		   
		 // echo "<script language='JavaScript'>alert('Material Request successfully update.');top.tb_remove();top.window.location='material_request_list.php'; <//script>";
	
		 
		   			  
             }
             else 
			 {
             $message = '<p> CANNOT UPDATE WIP REQUEST!!!. </p>';
              mysqli_close($dbc); //close db
             }  
   
		

}  

  //print the message if there is one.
if (isset($message))
{ 
echo '<font color="red" class ="error_entry">', $message, '</font>';

} 
}



if(isset($_POST["save"])) 
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

 $size = count($_POST["id_scan"]) + 1;

 $i = 1;
 
    //check only deilvery quantity in number
	 
               if(empty($_POST['bom_qty'][$i]))
				{
				  $bom_qty = FALSE;
				  $message.= '<p align="center">You are required to enter REQUIRED QUANTITY!</p>';
				  }
				  elseif(!is_numeric($_POST['bom_qty'][$i]))
				  { $bom_qty = FALSE;
				   $message.= '<p align="center">You are required to enter NUMBERS only for REQUIRED QUANTITY!</p>';
				  }  
				  else
				  { 
				  $bom_qty= escape_data($_POST['bom_qty'][$i]);
				  }	             
	

 //--------------------------------create (temporary MRIN)-----------------------------
$query_id_2 = "SELECT MAX(mrin_doc_wip) FROM wip_request";
$result_id_2 = mysqli_query($dbc, $query_id_2);

if ($result_id_2) {
$nrows_2 = mysqli_num_rows($result_id_2);
$row_id_2 = mysqli_fetch_row($result_id_2);

 $dht_2 = "0000000";

  if($row_id_2[0] <= 0)
  { 
   
    $lastID_2 = ($row_id_2[0] + 1);
    $dg_2 = ($dht_2 + ($lastID_2));

   }else{
      $lastID_2 = ($row_id_2[0] + 1);
      $dg_2 =  $lastID_2;
    }

	 $number = $dg_2; // Length of the supplied number is 3
	 $number = sprintf('%08d', $number);

  $ref = ("W".($year.$number));
		  
  } // end if $result_id
	
 
	if($bom_qty) //everything ok
{  	

  //----------------------------------------------------------------------------//-
  //baca asn yg ada kt database, check total asn, open balance                 //
  //                                                                           //
  //---------------------------------------------------------------------------//
   $i = 1;
   
  while ($i < $size) {
   
  
$query_update2 = "UPDATE wip_request SET mrin_doc_wip = '$number', mrin_year_wip = '$year', temp_mrin_wip = '$ref', bom_qty_wip = '".$_POST["bom_qty"][$i]."', status_request = 'Y', user_update = '".$res["user_no"]."', date_update = NOW(), date_posting = NOW(), time_posting = NOW() WHERE id_req_wip = '".$_POST["id_req"][$i]."' ";

$result_update2 = mysqli_query($dbc, $query_update2);


	 $i++;
   } // end while loop		
      if($result_update2)
           {
		   
		   echo "<script>";
		   echo "alert('MRIN NO :$ref generated.');";
		   echo "window.location='display_request_wip_urgent.php'";
	       echo "</script>"; 
			 exit(); //quit the script
			  
             }
             else 
			 {
             $message = '<p> CANNOT CREATE WIP REQUEST!!!. </p>';
              mysqli_close($dbc); //close db
             }  

		

}  

  //print the message if there is one.
if (isset($message))
{ 
echo '<font color="red" class ="error_entry">', $message, '</font>';

} 

} // end if(POST button)  
	
	
		  $current_date = (date("Y-m-d"));

  $query8 = "SELECT COUNT(*) FROM wip_request AS MR, mat_master_detail as MD, scan_detail_wip AS SD WHERE MR.id_scan_wip = SD.id_scan AND MD.id_dtl = MR.id_dtl_wip AND SD.status_urgent = 'Y' AND MR.status_request = 'N' AND ((MD.material LIKE '%".$_GET["txtKeyword"]."%') OR (MD.bill_component LIKE '%".$_GET["txtKeyword"]."%')) AND MR.user_create = '".$res["user_no"]."' AND ((MR.date_mrin >= '$current_date') AND (MR.date_mrin <= '$next_date')) ORDER BY MR.id_req_wip ASC";
   $result8 = mysqli_query($dbc, $query8) or die(mysqli_error($dbc));
   $num_rows = mysqli_fetch_row($result8); 


   $pages = new Paginator;
   $pages->items_total = $num_rows[0];
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
  
$query = "SELECT *, DATE_FORMAT(MR.date_mrin,'%d-%m-%Y') AS R FROM wip_request AS MR, mat_master_detail as MD, scan_detail_wip AS SD WHERE MR.id_scan_wip = SD.id_scan AND MD.id_dtl = MR.id_dtl_wip AND SD.status_urgent = 'Y' AND MR.status_request = 'N' AND ((MD.material LIKE '%".$_GET["txtKeyword"]."%') OR (MD.bill_component LIKE '%".$_GET["txtKeyword"]."%')) AND MR.user_create = '".$res["user_no"]."' AND ((MR.date_mrin >= '$current_date') AND (MR.date_mrin <= '$next_date')) GROUP BY MR.id_scan_wip ORDER BY MR.id_req_wip ASC";
$rs = mysqli_query($dbc, $query);   //run the query.
$num = mysqli_num_rows($rs);   //how many material are there?

	 
	 echo '<div align="center">There are currently  '. $num.' record(s).</div>';
	
	
if ($num > 0) {
?>
     <table width="100%" border="0" cellpadding="0" cellspacing="2">
  	 <tr>
     <td width="177">&nbsp;</td>
     <td width="431">&nbsp;</td>
     <td width="65">&nbsp;</td>
     <td width="65">&nbsp;<a value="Details" href="detail_prod_order_urgent_wip.php?&&TB_iframe=true&height=400&width=650" class="thickbox" target="_self"><img src="../img/icon_view.jpg" width="16" height="16" alt="View">View</a></td>
     </tr>
     </table>
   <br> 
         <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];   ?>">   
         <table class="table table-bordered">
         <thead>
         <tr>
         <th width="52"><img src="../img/delete.png" width="16" height="16" title="Delete" /></th>
         <th width="83">Item </th>
         <th width="154">Material No.</th>
         <th width="119">Production Order Number</th>
         <th width="144">Required Quantity</th>
         <th width="80">UoM</th>
         <th width="100">Work Center</th>
         </tr>
         </thead><tbody>
         <?php
		  
   $counter = 1;
   $no = 1;
    $i = 1;
   
   while ($row2 = mysqli_fetch_array($rs))
   {
	
	$no = sprintf('%03d', $no);
   
   $query_scan = "SELECT * FROM scan_detail WHERE id_scan = '".$row2[6]."' GROUP BY id_scan";
   $result_scan = mysqli_query($dbc, $query_scan);
   $row_scan = mysqli_fetch_array($result_scan);
	
		  ?>        
       
  <tr>
   <td><div align="center">
    <input type="checkbox" name="cancel[]" value="<?php echo $row_scan['id_scan']; ?>" />
    <input name="id" type="hidden" value="<?php echo $row_scan['id_scan']; ?>" />
  </div></td>
    <td><?php echo $no; ?></td>
    <td>&nbsp;<?php echo $row_scan["material_no"]; ?></td>
    <td>&nbsp;<font color="#0000CC"><b><?php echo $row_scan["prod_order"]; ?></b></font></td>
    <td ><font color="#FF0000"><?php echo $row2["R"]; ?>&nbsp;<?php echo $row2["time_mrin"]; ?></font></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr></thead><tbody>

         <?php
	$query_again = "SELECT * FROM wip_request WHERE status_request = 'N' and id_scan_wip = '".$row2[6]."'";
    $rs_again = mysqli_query($dbc, $query_again);   //run the query.
	 while ($row = mysqli_fetch_array($rs_again))
   {
		 
   $query1_p = "SELECT * FROM scan_detail_wip WHERE id_scan = '".$row2[6]."' GROUP BY id_scan";
   $result1_p = mysqli_query($dbc, $query1_p);
   $row1_p = mysqli_fetch_array($result1_p);
	
  $query4_p = "SELECT * from mat_master_header as LD, mat_master_detail as SD WHERE SD.id_hdr = LD.id_hdr and SD.id_dtl = '".$row[5]."'";
  $result4_p = mysqli_query($dbc, $query4_p);
  $row4_p = mysqli_fetch_array($result4_p); 
  

		  	 
		 ?>

         <tr>
         <td width="52">&nbsp;</td>
         <td width="60">&nbsp;</td>
         <td width="154">&nbsp;<?php  echo $row4_p["bill_component"]; ?></td>
         <td><?php  echo $row4_p["material_desc_c"]; ?></td>
         <td width="144"><div align="right">
	 <input name="bom_qty[<?php echo $i; ?>]" type="text" value="<?php if(isset($_POST["bom_qty"])) { echo $_POST["bom_qty"][$i]; }else{  echo $row["bom_qty_wip"];   } ?>" class="span8" />&nbsp;</div></td>
         <td width="80"><div align="center"><?php echo $row["bom_oum_wip"]; ?></div></td>
         <td width="100"><div align="center"><font color="#FF0000"><?php echo $row1_p["work_center"]; ?></font></div></td>
          </tr>
          <input name="user_no" type="hidden" value="<?php echo $res["user_no"]; ?>">
          <input name="id_req[<?php echo $i; ?>]" type="hidden" value="<?php echo $row["id_req_wip"]; ?>">
          <input name="id_scan[<?php echo $i; ?>]" type="hidden" value="<?php echo $row["id_scan_wip"]; ?>">
        
         <?php 
		 
		
		 $i++;
		  $counter++; // menambah counter 
		 }
		 $no ++;
		  
		    
		  } ?></tbody></table>  
 
  <?php
   mysqli_free_result($rs); 
	}   // free up the resources 
else
{
?><center>
<table width="800" cellspacing="0" class="textboxred">
  <tr> 
    <td><div align="center"><font color="#FF0000"><strong>There are currently 
          no material request.</strong></font></div></td>
  </tr>
</table></center>
        <?php
		   } 
mysqli_close($dbc)
?>
<br>
             <input name="confirm" type="submit" class="btn btn-danger" id="confirm" value="CONFIRM DELETE">
            <input name="nextsave" type="submit" id="submit2" value="SCAN NEXT" class="btn btn-warning">
             <input name="save" type="submit" id="submit" value="POST" class="btn btn-success" onClick="return confirm('View request?');">
  
</form>

</div>




</div></div></div></div>
</div>
<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part--> 

<script src="../js/jquery.min.js"></script> 
<script src="../js/jquery.ui.custom.js"></script> 
<script src="../js/bootstrap.min.js"></script> 
<script src="../js/jquery.uniform.js"></script> 
<script src="../js/select2.min.js"></script> 
<script src="../js/jquery.dataTables.min.js"></script> 
<script src="../js/matrix.js"></script> 
<script src="../js/matrix.tables.js"></script>



</body>
</html>
