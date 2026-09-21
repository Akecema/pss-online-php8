<?php

/**
 * prod/wip_request_urgentProc.php
 * Part of: Production module
 * Filename suggests: wip request urgentProc
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, scan_detail_wip, wip_request, mat_master_detail, mat_master_header.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, tcpdf_barcodes_2d.php, top_modal_menu.php, left_production_menu.php, footer.php.
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

$url = "wip_request_urgent.php";


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

<style type="text/css" media="print"> 
/* div.page
      {
         page-break-after: always;
         page-break-inside: avoid;
       }
	   
	   */
	  
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
	   // $amount .= $_POST["comp_quantity"][$i]."|";
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
              <li class="active"><a  role="tab" href="wip_request_urgent.php">New Request</a></li>
              <li><a role="tab" href="draft_request_wip_urgent.php">Draft Request</a></li>
              <li><a role="tab" href="display_request_wip_urgent.php">Display Request</a></li>
            </ul>
          </div>
          </div>
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>New Request Urgent - WIP</h5>
        </div>
 <?php
	   $uid = $_GET["uid"];
	
	   $query_scan = "SELECT * FROM scan_detail_wip WHERE id_scan = '".db_esc($dbc, $uid)."'";
	   $result_scan = mysqli_query($dbc, $query_scan);
	   $data_scan = mysqli_fetch_array($result_scan);
	   
	  $current_date = date('Y-m-d H:i:s'); 
	  $next_date = date('Y-m-d H:i:s', strtotime($current_date .' +2 day'));
	   
if(isset($_POST["save"]) && $_POST!=="") 
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

 $size = count($_POST['id_dtl']) + 1;

 $k = 0;
 
 if(isset($_POST["cancel"])) {
     $cancel = $_POST["cancel"]; 
     $how_many = count($cancel); 
	
	}
	
       $comp_quantity = $_POST["comp_quantity"];
       $date1 = $_POST["date1"];
       $time1 = $_POST["time1"]; 
       $time2 = $_POST["time2"];
	   


      $date_arini = date('Y-m-d'); 
      $current_date = date('Y-m-d H:i:s'); 
	  $next_date = date('Y-m-d H:i:s', strtotime($current_date .' +2 day'));
 
     //check only deilvery date
	 
					  
				 $date_date = (($_POST["date1"])." ".($_POST["time1"]).":".($_POST["time2"]).":00");
				  
				  if($date_date >= $current_date)
				  {
				     if($date_date > $next_date)
					 {
					    $message.= '<p align="center">You are not allowed to request for more than 2 days advance!</p>';
						  $date1 = FALSE;
						  
					   
						}
						
					 elseif($date_date < $current_date)
						{
						
						   $message.= '<p align="center">You are not allowed to request backdated date and time!</p>';
						  $date1 = FALSE;
						}
			
					   else{
					  
					      if(isset($_POST["time1"]) < ($hours))
						 {
						  $time1 = FALSE;
					      $message.= '<p align="center">You are not allowed to request backdated time!</p>';
						  $date1 = FALSE;
					      }
					  
					     $date1 = TRUE;
						
						}
	                 }else{
						$message.= '<p align="center">You are not allowed to request backdated date and time!</p>';
						  $date1 = FALSE;
						  
						  }
						  
				      
					
				if(($_POST["time1"]) == "NULL")
				{
				  $time1 = FALSE;
				  $message.= '<p align="center">You are required to select Hours!</p>';
				  }else{
				  $time1 = TRUE;
				  }
				  
				  if(($_POST["time2"]) == "NULL")
				{
				  $time2 = FALSE;
				  $message.= '<p align="center">You are required to select Minutes!</p>';
				  } else{
				  $time2 = TRUE;
				  }
				  
				   
				  if(empty($_POST["cancel"]))
				{
				   $cancel = FALSE;
				  $message.= '<p align="center">You didn\'t choose any of the checkboxes!</p>';
				  } else{
				  $cancel = TRUE;
				  }
				
 //--------------------------------create (temporary MRIN)-----------------------------
// edit by Azie 23/07/2014	 


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
	
 
if($comp_quantity && $time1 && $time2 && $date1 && $cancel) //everything ok
{  	
  //----------------------------------------------------------------------------//-
  //baca asn yg ada kt database, check total asn, open balance                 //
  //                                                                           //
  //---------------------------------------------------------------------------//
   //$i = 1;
   
    $string = "";
	 
	 $cancel2 = $_POST["cancel"]; 
     $how_many2 = count($cancel2); 
	 
     foreach($_POST["cancel"] as $j=>$i) {
	    
		$amount .= $_POST["comp_quantity"][$i]."|";
	    $string = explode("|",($amount));	
			}
		
        for ($i=0; $i<$how_many2; $i++) { 
		
	
		//echo ($i+1) . '- ' . $cancel2[$i] . ' ------ '; echo $string[$i]."</br>";
   
    $t_time = (($_POST["time1"]).":".($_POST["time2"]));
   
 
  $query_q2 = "SELECT * FROM mat_master_detail WHERE id_dtl = '".db_esc($dbc, $cancel2[$i])."'";
  $result_q2 = mysqli_query($dbc, $query_q2) or die (mysqli_error($dbc));
  $ans3 = mysqli_fetch_array($result_q2);
  
//register the user in the db.
$query_db2 = "INSERT INTO wip_request (id_req_wip, mrin_doc_wip, mrin_year_wip, temp_mrin_wip, id_hdr_wip, id_dtl_wip, id_scan_wip, bom_id_wip, bom_qty_wip, bom_oum_wip, status_request, status_print, user_create, date_create, user_update, date_update, date_posting, time_posting, status, bom_component, date_mrin, time_mrin) VALUES('','','','','".db_esc($dbc, $ans3['id_hdr'])."','".db_esc($dbc, $ans3['id_dtl'])."','".db_esc($dbc, $uid)."','".db_esc($dbc, $cancel2[$i])."','".db_esc($dbc, $string[$i])."', '".db_esc($dbc, $ans3['comp_unit'])."','N','N','".db_esc($dbc, $res['user_no'])."',NOW(),'','','','','New','".db_esc($dbc, $ans3['bill_component'])."','".db_esc($dbc, $_POST["date1"])."','".db_esc($dbc, $t_time)."')";
$result_db2 = mysqli_query($dbc, $query_db2) or die (mysqli_error($dbc));
 


} // for loop


		   
		   echo "<script>";
		  // echo "alert('MRIN NO :$ref generated.');";
		   echo "window.location='draft_request_wip_urgent.php'";
	       echo "</script>"; 
			 exit(); //quit the script
			  

}  

  //print the message if there is one.
if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}

} // end if(POST button)

if(isset($_POST["nextsave"]) && $_POST!=="") 
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

 $size2 = count($_POST["id_dtl"]) + 1;

 $k = 0;
 
 if(isset($_POST["cancel"])) {
 
    $cancel = $_POST["cancel"]; 
    $how_many = count($cancel); 
	}
	
      $comp_quantity = $_POST["comp_quantity"];
       $date1 = $_POST["date1"];
       $time1 = $_POST["time1"]; 
       $time2 = $_POST["time2"];
 

 $date_arini = date('Y-m-d'); 
      $current_date = date('Y-m-d H:i:s'); 
	   $next_date = date('Y-m-d H:i:s', strtotime($current_date .' +2 day'));
 
     //check only deilvery date
	 
					  
				 $date_date = (($_POST["date1"])." ".($_POST["time1"]).":".($_POST["time2"]).":00");
				  
				  if($date_date >= $current_date)
				  {
				     if($date_date > $next_date)
					 {
					    $message.= '<p align="center">You are not allowed to request for more than 2 days advance!</p>';
						  $date1 = FALSE;
						  
					   
						}
						
					 elseif($date_date < $current_date)
						{
						
						   $message.= '<p align="center">You are not allowed to request backdated date and time!</p>';
						  $date1 = FALSE;
						}
			
					   else{
					  
					      if(isset($_POST["time1"]) < ($hours))
						 {
						  $time1 = FALSE;
					      $message.= '<p align="center">You are not allowed to request backdated time!</p>';
						  $date1 = FALSE;
					      }
					  
					     $date1 = TRUE;
						
						}
	                 }else{
						$message.= '<p align="center">You are not allowed to request backdated date and time!</p>';
						  $date1 = FALSE;
						  
						  }
						  
				      
					
				if(($_POST["time1"]) == "NULL")
				{
				  $time1 = FALSE;
				  $message.= '<p align="center">You are required to select Hours!</p>';
				  }else{
				  $time1 = TRUE;
				  }
				  
				  if(($_POST["time2"]) == "NULL")
				{
				  $time2 = FALSE;
				  $message.= '<p align="center">You are required to select Minutes!</p>';
				  } else{
				  $time2 = TRUE;
				  }
				  
				   
				  if(empty($_POST["cancel"]))
				{
				   $cancel = FALSE;
				  $message.= '<p align="center">You didn\'t choose any of the checkboxes!</p>';
				  } else{
				  $cancel = TRUE;
				  }
				
	
	if($comp_quantity && $time1 && $time2 && $date1 && $cancel) //everything ok
{  	
  //----------------------------------------------------------------------------//-
  //baca asn yg ada kt database, check total asn, open balance                 //
  //                                                                           //
  //---------------------------------------------------------------------------//
 // $i = 0;
  

  	 $string = "";
	 
	 $cancel2 = $_POST["cancel"]; 
     $how_many2 = count($cancel2); 
	 
     foreach($_POST["cancel"] as $j=>$i) {
	    
		$amount .= $_POST["comp_quantity"][$i]."|";
	    $string = explode("|",($amount));	
			}
		
        for ($i=0; $i<$how_many2; $i++) { 
		
	
		//echo ($i+1) . '- ' . $cancel2[$i] . ' ------ '; echo $string[$i]."</br>";
   
    $t_time = (($_POST["time1"]).":".($_POST["time2"]));
   
 
  $query_q2 = "SELECT * FROM mat_master_detail WHERE id_dtl = '".db_esc($dbc, $cancel2[$i])."'";
  $result_q2 = mysqli_query($dbc, $query_q2) or die (mysqli_error($dbc));
  $ans3 = mysqli_fetch_array($result_q2);
  

//register the user in the db.
$query_db2 = "INSERT INTO wip_request (id_req_wip, mrin_doc_wip, mrin_year_wip, temp_mrin_wip, id_hdr_wip, id_dtl_wip, id_scan_wip, bom_id_wip, bom_qty_wip, bom_oum_wip, status_request, status_print, user_create, date_create, user_update, date_update, date_posting, time_posting, status, bom_component, date_mrin, time_mrin) VALUES('','','','','".db_esc($dbc, $ans3['id_hdr'])."','".db_esc($dbc, $ans3['id_dtl'])."','".db_esc($dbc, $uid)."','".db_esc($dbc, $cancel2[$i])."','".db_esc($dbc, $string[$i])."', '".db_esc($dbc, $ans3['comp_unit'])."','N','N','".db_esc($dbc, $res['user_no'])."',NOW(),'','','','','New','".db_esc($dbc, $ans3['bill_component'])."','".db_esc($dbc, $_POST["date1"])."','".db_esc($dbc, $t_time)."')";
$result_db2 = mysqli_query($dbc, $query_db2) or die (mysqli_error($dbc));

   } // end while loop	
   
		   echo "<script>";
		   echo "alert('Please enter for next material requisition.');";
		   echo "window.location='wip_request_urgent.php'";
	       echo "</script>"; 
			 exit(); //quit the script
			  
       } // end if 

//print the message if there is one.
if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}
	
  } // end IF {button scan next 

		
    $no = 1; 
		  
		  ?>
  <form name="myform" method="post" action="wip_request_urgentProc.php?uid=<?php echo $uid; ?>">
  <table width="99%" border="0">
  <tr>
    <td width="14%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
    <td width="40%">&nbsp;</td>
    <td width="12%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
    <td width="12%">Hours</td>
    <td width="18%">Minutes</td>
  </tr>
  <tr>
   <td>Required Date</td>
    <td>:</td>
    <td><?php
    
					 $dt = $today['mday'];
					 $mt = $today['mon'];
					 $yr = $today['year'];
	 
	                  $myCalendar = new tc_calendar("date1", true, false);
					  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					  $myCalendar->setDate($dt,$mt,$yr);
					  $myCalendar->setPath("/calendar/");
					  $myCalendar->setYearInterval(2010, 2030);
					  // $myCalendar->setOnChange("myChanged('test')");
					  $myCalendar->writeScript();
					  
 ?></td>
    <td>Required Time </td>
    <td>:</td>
    <td>
    <select name="time1" id="time1">
	 <?php if(($_POST["save"] == true) || ($_POST["nextsave"] == true)) 
		{  
		?> 
		<option value="<?php echo $_POST["time1"]; ?>"><?php echo sprintf('%02d', $_POST["time1"]);	 ?></option>			
     <?php
	 }else{
	 ?><option value="NULL" placeholder="HOURS">&nbsp;</option> 
      <?php
	  }
	  
      for($i2 = 0; $i2 <= 23; $i2++): ?>
      <option value="<?= $i2; ?>">
        <?php echo sprintf('%02d', $i2); ?>        </option>
      <?php endfor; ?>
    </select> 
    :</td>
    <td><select name="time2" id="time2">
     <?php if(($_POST["save"] == true) || ($_POST["nextsave"] == true)) 
		{  
		?> 
		<option value="<?php echo $_POST["time2"]; ?>"><?php echo sprintf('%02d', $_POST["time2"]);	 ?></option>			
     <?php
	 }else{
	 ?><option value="NULL" placeholder="MINUTES">&nbsp;</option> 
      <?php
	  }
      for($j = 0; $j <= 59; $j++): ?>
      <option value="<?= $j; ?>">
   <?php echo sprintf('%02d', $j); ?>      </option>
      <?php endfor; ?>
    </select></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="5">&nbsp;</td>
  </tr>
</table>
                 <table class="table table-bordered">
                 <thead>
                 <tr>
                 <td width="7%">Item No.</td>
                 <td width="21%">Material No.</td>
                 <td width="22%">Production Order</td>
                 <td width="16%">Required Quantity</td>
                 <td width="17%">UoM</td>
                 <td width="17%">Work Center</td>
               </tr></thead>
               <tbody>
               <tr>
                 <td><?php echo $no; ?></td>
                 <td><?php echo $data_scan["material_no"]; ?></td>
                 <td><?php echo $data_scan["prod_order"]; ?></td>
                 <td><?php echo $data_scan["scan_qty"]; ?></td>
                 <td><?php echo $data_scan["scan_oum"]; ?></td>
                 <td><?php echo $data_scan["work_center"]; ?></td>
               </tr></tbody>
            </table>
              <p align="center">--------------------------------------------------------------------------------------------------------------------------------------------- </p>
             <?php
			 
	   $query_component = "SELECT * FROM mat_master_header AS h, mat_master_detail AS s WHERE h.id_hdr = s.id_hdr AND s.material = '".db_esc($dbc, $data_scan["material_no"])."' AND s.bom_status = 'Y' AND s.mat_type = 'Z200'";
	   $result_component = mysqli_query($dbc, $query_component);
	  
			 ?>
           <table class="table table-striped">
             <thead>
               <tr>
               
               
                 <th width="7%" >&nbsp;</th>
                 <th width="7%" height="35" bgcolor="#EFF8B1">Item No.</th>
                 <th height="35%" bgcolor="#EFF8B1">Component</th>
                 <th width="30%" bgcolor="#EFF8B1">Quantity</th>
                 <th width="16%" bgcolor="#EFF8B1">UoM</th>
                 <th width="16%" bgcolor="#EFF8B1">Work Center</th>
                 
          <!--     <th>&nbsp;</th>
               <th>Item No.</th>
               <th>Component</th>
               <th>Quantity</th>
               <th>UoM</th>
               <th>Work Center</th>-->
               </tr>
              </thead><tbody>
               <?php
			   
		 $counter = 1;
		 $k = 1;
		 $no2 = 1;
				 
			while($row = mysqli_fetch_array($result_component))
			{  
			
		
		$formula_qty = ($row["consumption"] * $data_scan["scan_qty"]);
		
		$num_convert = number_format($formula_qty, 3, '.', '');
				   
			   ?>
             
               <tr>
               <td><div align="center"><input type="checkbox" name="cancel[]" value="<?php echo $row["id_dtl"]; ?>" <?=was_checked($row["id_dtl"],$a) ?> />
             <input type="hidden" name="Check_ctr" value="yes" 
onClick="Check(document.myform.cancel)">  </div></td>
               <td><?php echo $no2; ?></td>
               <td><?php echo $row["bill_component"];   ?></td>
               <td><input name="comp_quantity[<?php echo $row["id_dtl"]; ?>]" type="text" value="<?php if(isset($_POST["comp_quantity"])) { echo $_POST["comp_quantity"][($row["id_dtl"])]; }else{  echo $num_convert;   } ?>" /></td>
               <td><div align="center"><?php echo $row["comp_unit"];   ?></div></td>
               <td><div align="center"><?php echo $data_scan["work_center"]; ?></div>
                  <input name="id_dtl[<?php echo $k; ?>]" type="hidden" value="<?php echo $row["id_dtl"]; ?>">
                  <input name="uid" type="hidden" value="<?php echo $uid; ?>"></td>
               </tr><?php
			
      $k++;
	  $no++;
	  $no2++;		 
			  } // end while loop
			  
			   ?></tbody>
             </table>  
              <br>
               
            <input name="nextsave" type="submit" id="submit2" value="SCAN NEXT" class="btn btn-warning">
             <input name="save" type="submit" id="submit" value="VIEW" class="btn btn-success" onClick="return confirm('View request?');">
             <input  name="btnback" type="button" id="btnCancel" class="btn btn-danger" value="BACK" onclick="window.history.back()" />
               
           
    
          </form>
          <br>
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

