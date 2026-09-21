<?php

/**
 * qqc/wastage_qc_tran_NG.php
 * Part of: QQC module (Quality)
 * Filename suggests: wastage qc tran NG
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, factory_detail, model_detail, storage_tbl, type_wastage_detail, reason_wastage, uom_con, table_material.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_qqc_menu.php, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
//ini_set("error_reporting", E_ALL & ~E_DEPRECATED);
error_reporting(E_ALL &~ E_NOTICE &~ E_DEPRECATED);
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
require_once '../include/auth.php';
require_role($dbc, 10);
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");

$Cdate = date ("l, j F Y ");
set_time_limit(0);

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$url = "wastage_qc_tran_NG.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
    $result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
    $res = mysqli_fetch_array($result2);
//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------			

$today = getdate();
$hours = $today['hours']; 
$minutes = $today['minutes'];
$seconds = $today['seconds'];
$month = $today['mon']; 
$mday = $today['mday']; 
$year = $today['year']; 	

//CR status (New)

$sta = "SELECT * from request_status WHERE status_id = '1' ";
$sta_res = mysqli_query($dbc, $sta);
$rst_sta = mysqli_fetch_array($sta_res);

//CR status (Released)
$sta2 = "SELECT * from request_status WHERE status_id = '2' ";
$sta_res2 = mysqli_query($dbc, $sta2);
$rst_sta2 = mysqli_fetch_array($sta_res2);	

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7' ";
$sta_res7 = mysqli_query($dbc, $sta7);
$rst_sta7 = mysqli_fetch_array($sta_res7);	

//CR status (Pending Approve)
$sta15 = "SELECT * from request_status WHERE status_id = '15' ";
$sta_res15 = mysqli_query($dbc, $sta15);
$rst_sta15 = mysqli_fetch_array($sta_res15);	
	
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

<script language="javascript" type="text/javascript">

function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
    }
	
	function getFactory(factory) {		
		
		var strURL="findWorkcenter2.php?factory="+factory;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('work_centerdiv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
//-----------------------------------------
	
	function getModel(model_code) {		
		
		var strURL="findModelType.php?model_code="+model_code;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('material_nodiv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP in Model Code:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
		
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

<!--Header-part-->
<div id="header">
  <h1>&nbsp;</h1>
</div>
<?php  include "top_modal_menu.php";   ?>
<!--close-Header-part--> 

<!--sidebar-menu-->
<?php include "left_qqc_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_qqc.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a><a href="#" class="#">QA/QC</a> <a href="#" class="current">Wastage &amp; Reject</a></div>
  <h1>Wastage &amp; Reject</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
     <!-- <div class="span12">-->        
          
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmSearch" id="frmSearch">
            <table class="table table-bordered table-striped">
              <tr>
              <th>Factory : </th>
              <td colspan="2"><!--<select name="factory" id="factory" onChange="getFactory(this.value)">-->
              <select name="factory" id="factory" class="span5">
                <option value="NULL" placeholder="Select Factory"> -- Select Factory --</option>
                <?php
	       $query3 = "SELECT * FROM factory_detail GROUP BY factory_desc2 ORDER BY id_fac ASC";
                   $result3 = mysqli_query($dbc, $query3);
  
                   while($row3=mysqli_fetch_array($result3)) 
			      {  ?>
			 <option value="<?php echo $row3["factory_desc2"]; ?>" <?php if($row3["factory_desc2"] == "1") echo "selected"; ?>> <?php echo $row3["factory_desc"]; ?> </option>  
                <!--  echo'<option value="',$row3[2],'">',stripslashes($row3[1]),'</option>';-->
             <?php     
			 }
				?>
              </select></td>
              
            </tr>
            <tr>
              <th width="23%">Model :</th>
              <td colspan="2"><select name="model_code" id="model_code" class="span5" onChange="getModel(this.value)">
                  <option value="NULL" placeholder="Select Model"> -- Select Model --</option>
                  <?php
	               $query7 = "SELECT * FROM model_detail ORDER BY code_model ASC";
                   $result7 = mysqli_query($dbc, $query7);
  
                   while($row7=mysqli_fetch_array($result7)) 
			      {
				   ?>
                  <option value="<?php echo $row7["model_name"]; ?>"> <?php echo $row7["model_name"]; ?></option>
                  
                  <?php
                  }
				?>
              </select></td>
            </tr>
            <tr>
              <th>Part No. :</th>
              <td colspan="2"><div id="material_nodiv"> 
              <select name="material_no" id="material_no" class="span5">
              <option value="NULL" placeholder="Select Part No."> -- Select Part No. --</option>
                </select>
              </div></td>
              </tr>
            <tr>
              <th>Storage Location :</th>
              <td colspan="2"><select name="ploc" id="ploc" class="span5">
                  <option value="NULL" placeholder="Select Storage Location "> -- Select Storage Location --</option>
                  <?php
	               $query10 = "SELECT * FROM storage_tbl ORDER BY sloc_code ASC";
                   $result10 = mysqli_query($dbc, $query10);
  
                   while($row10=mysqli_fetch_array($result10)) 
			      {
				   ?>
                  <option value="<?php echo $row10["sloc_code"]; ?>"> <?php echo $row10["sloc_code"]; ?></option>
                  <?php
                  }
				?>
              </select></td>
              </tr>
            <tr>
              <th>Type of Wastage :</th>
              <td colspan="2">
              <select name="type_wastage" id="type_wastage" class="span5">
                   <option value="NULL" placeholder="Select Type of Wastage "> -- Select Type of Wastage --</option>
                  <?php
	               $query_type = "SELECT * FROM type_wastage_detail WHERE status_wastage = 'Y' ORDER BY id_wastage ASC";;
                   $result_type = mysqli_query($dbc, $query_type);
  
                   while($row_type = mysqli_fetch_array($result_type)) 
			      {
					  
				   ?>
                     <?php if($_POST["Submit2"] == true)  
		         {   ?>
                    <option value="<?php echo $row_type["id_wastage"]; ?>"<?php if($row_type["id_wastage"] == $_POST["type_wastage"]) echo "selected"; ?>> <?php echo $row_type["wastage_desc"]; ?></option>
                     
                  <?php
				 }else{
				  
				  ?> 
                  <option value="<?php echo $row_type["id_wastage"]; ?>"> <?php echo $row_type["wastage_desc"]; ?></option>
                  <?php
				    }  // else
				  
                  }
				?>
              </select>
              </td>
              </tr>
            <tr>
              <th>Reason :</th>
              <td colspan="2"><select name="reason_wastage" id="reason_wastage" class="span5">
                  <option value="NULL" placeholder="Select Reason of Wastage"> -- Select Reason of Wastage --</option>
                  <?php
	               $query_reason = "SELECT * FROM reason_wastage WHERE status_reason_wastage = 'Y' ORDER BY id_reason_wastage ASC";
                   $result_reason = mysqli_query($dbc, $query_reason);
  
                   while($row_reason = mysqli_fetch_array($result_reason)) 
			      {
					   if($_POST["Submit2"] == true)  
		         {   ?>
                    <option value="<?php echo $row_reason["id_reason_wastage"]; ?>"<?php if($row_reason["id_reason_wastage"] == $_POST["reason_wastage"]) echo "selected"; ?>> <?php echo $row_reason["reason_wastage_desc"]; ?></option>
                     
                  <?php
				 }else{
				  
				  ?> 
		    <option value="<?php echo $row_reason["id_reason_wastage"]; ?>"> <?php echo $row_reason["reason_wastage_desc"]; ?></option>
                  <?php
				    }//else
                  }
				?>
              </select></td>
              </tr>
            <tr>
              <th>Quantity :</th>
              <td colspan="2"><?php  if($_POST["Submit2"] == true) { ?><input name="qty_wastage" id="qty_wastage" type="number" step=".01" min="1" class="span5" value="<?php echo h($_POST["qty_wastage"]); ?>" /> <?php }else{ ?><input name="qty_wastage" id="qty_wastage" type="number" step=".01" min="1" class="span5" /><?php } ?></td>
              </tr>
            <tr>
              <th>UOM :</th>
              <td colspan="2">
               <select name="UOM_unit" id="UOM_unit" class="span5">
                   <option value="NULL" placeholder="Select UOM"> -- Select UOM --</option>
                  <?php
	               $query_uom = "SELECT * FROM uom_con ORDER BY UOM ASC";;
                   $result_uom = mysqli_query($dbc, $query_uom);
  
                   while($row_uom = mysqli_fetch_array($result_uom)) 
			      {
					  
				   ?>
                     <?php if($_POST["Submit2"] == true)  
		         {   ?>
                    <option value="<?php echo $row_uom["UOM"]; ?>"<?php if($row_uom["UOM"] == $_POST["UOM_unit"]) echo "selected"; ?>> <?php echo $row_uom["UOM"]; ?></option>
                     
                  <?php
				 }else{
				  
				  ?> 
                  <option value="<?php echo $row_uom["UOM"]; ?>"> <?php echo $row_uom["UOM"]; ?></option>
                  <?php
				    }  // else
				  
                  }
				?>
              </select>
              
              </td>
              </tr>
            <tr>
              <th>Date Wastage :</th>
              <td colspan="2"><?php
    
				 if(isset($_POST['date1']))
								{ 
									
									//GET value
									$dd1 = substr($_POST['date1'],8,2);
									$mm1 = substr($_POST['date1'],5,2);
									$yy1 = substr($_POST['date1'],0,4);
									
									$myCalendar = new tc_calendar("date1", true, false);
									$myCalendar->setIcon("../calendar/images/iconCalendar.gif");
									$myCalendar->setDate($dd1, $mm1, $yy1);
									$myCalendar->setPath("/calendar/");
									$myCalendar->setYearInterval(2010, 2030);
									// $myCalendar->setOnChange("myChanged('test')");
									$myCalendar->writeScript();
									
								}
								else	 
								{
                                      
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
				
									}
			 ?></td>
              </tr>
            <tr>
              <th>&nbsp;</th>
              <th width="45%">&nbsp;</th>
              <th width="32%"><input name="Submit2" type="submit"  class="btn btn-success" id="button" value="Add Item" onclick="return confirm('Confirm to add request?');" /></th>
            </tr>
            </table>
        </form>
<?php
       //--------------------------------------------------------------------------
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
   
      // $material_type = $_POST["material_type"]; 
	   $model_code = $_POST["model_code"]; 
       $material_no = $_POST["material_no"]; 
	   $factory = $_POST["factory"]; 
	  // $work_center = $_POST["work_center"]; 
       $ploc = $_POST["ploc"];
	   $type_wastage = $_POST["type_wastage"];
	   $reason_wastage = $_POST["reason_wastage"]; 
	   $qty_wastage = $_POST["qty_wastage"];
	   $UOM_unit = $_POST["UOM_unit"];
	   //$cost_center = $_POST["cost_center"];
	   $date1 = $_POST["date1"];
	 			
				  
				   if(($_POST["factory"]) == "NULL")
				{
				  $factory = FALSE;
				  $message.= '<p align="center">You are required to select Factory!</p>';
				  }else{
				  $factory = TRUE;
				  }		
				  		  
				  if(($_POST["model_code"]) == "NULL")
				{
				  $model_code = FALSE;
				  $message.= '<p align="center">You are required to select Model!</p>';
				  }else{
				  $model_code = TRUE;
				  }
				  
				   if(($_POST["material_no"]) == "NULL")
				{
				  $material_no = FALSE;
				  $message.= '<p align="center">You are required to select Part No.!</p>';
				  }else{
				  $material_no = TRUE;
				  }
				  
				   if(($_POST["ploc"]) == "NULL")
				{
				  $ploc = FALSE;
				  $message.= '<p align="center">You are required to select Storage Location!</p>';
				  }else{
				  $ploc = TRUE;
				  }
				  
				   if(($_POST["type_wastage"]) == "NULL")
				{
				  $type_wastage = FALSE;
				  $message.= '<p align="center">You are required to select Type of Wastage!</p>';
				  }else{
				  $type_wastage = TRUE;
				  }
				  
				   if(($_POST["reason_wastage"]) == "NULL")
				{
				  $reason_wastage = FALSE;
				  $message.= '<p align="center">You are required to select Reason!</p>';
				  }else{
				  $reason_wastage = TRUE;
				  }
				  
				     if((($_POST["qty_wastage"]) == "") || (($_POST["qty_wastage"]) == "0"))
				{
				  $qty_wastage = FALSE;
				  $message.= '<p align="center">You are required to enter Quantity!</p>';
				  }else{
				  $qty_wastage = TRUE;
				  }
				  
				   if(($_POST["UOM_unit"]) == "NULL")
				{
				  $UOM_unit = FALSE;
				  $message.= '<p align="center">You are required to select UOM!</p>';
				  }else{
				  $UOM_unit = TRUE;
				  }
				  			  
				  if(($_POST["date1"]) == "0000-00-00")
				{
				  $date1 = FALSE;
				  $message.= '<p align="center">You are required to select Posting Date!</p>';
				  }else{
				  $date1 = TRUE;
				  }
	  
	   if($factory && $model_code && $material_no && $ploc && $type_wastage && $reason_wastage && $qty_wastage && $UOM_unit && $date1)
	   
	   {
		   
	   //get data table mat_master_header
	  $query_info2 = "SELECT * FROM table_material WHERE material_no = '".db_esc($dbc, $_POST["material_no"])."'";
	  $result_info2 = mysqli_query($dbc, $query_info2);
	  $data_info2 = mysqli_fetch_array($result_info2);  
	  
	  $query_info3 = "SELECT * FROM mat_master_detail WHERE material = '".db_esc($dbc, $_POST["material_no"])."' OR bill_component = '".db_esc($dbc, $_POST["material_no"])."'";
	  $result_info3 = mysqli_query($dbc, $query_info3);
	  $data_info3 = mysqli_fetch_array($result_info3);
	
	  
	  //insert table wastage_transaction
		
		$query_wastage = "INSERT INTO wastage_transaction (id_wastage_tran, material_no, material_desc, material_type, model_code, UOM_unit, comp_code, work_center, shift_day, date_plan, user_posting, date_posting, time_posting, status_disposal, ploc, ploc_prod_reject, ploc_qc_reject, qty_wastage, type_wastage, reason_wastage, user_wastage, date_wastage, time_wastage, remark_wastage, user_disposal, date_disposal, remarks, approve_by, date_approve, remark_approve, status_part, user_update, date_update, approve_by2, date_approve2, remark_approve2, cost_center) VALUES('','".db_esc($dbc, $_POST["material_no"])."','".db_esc($dbc, $data_info2["material_desc"])."','Z310','".db_esc($dbc, $_POST["model_code"])."','".db_esc($dbc, $_POST["UOM_unit"])."','2200','','','','".db_esc($dbc, $username)."',NOW(),'','".db_esc($dbc, $rst_sta["status_desc"])."','".db_esc($dbc, $data_info3["sloc"])."','".db_esc($dbc, $_POST["ploc"])."','','".db_esc($dbc, $_POST["qty_wastage"])."','".db_esc($dbc, $_POST["type_wastage"])."','".db_esc($dbc, $_POST["reason_wastage"])."', '".db_esc($dbc, $username)."','".db_esc($dbc, $_POST["date1"])."',NOW(),'','','','','','','','WQ','','','','','','IPS12203')";
		$result_wastage = mysqli_query($dbc, $query_wastage) or die (mysqli_error($dbc));   
		   
		//insert table reject_detail_disposal
		
		$query_insert2 = "INSERT INTO reject_detail_disposal (id_disposal, doc_dis, doc_disposal_no, bflush_qqc_no, plan_no, uid, material_no, material_desc, material_type, model_code, qty_plan, qty_actual, qty_balance, qty_NG, qty_qc, qty_qc_ok, qty_qc_NG, UOM_unit, comp_code, work_center, shift_day, date_plan, user_posting, date_posting, time_posting, status_disposal, ploc, ploc_prod_reject, ploc_qc_reject, type_reject, reason_reject, user_reject, date_reject, time_reject, qty_wastage, type_wastage, reason_wastage, user_wastage, date_wastage, time_wastage, user_disposal, date_disposal, remarks, approve_by, date_approve, remark_approve, status_part, user_update, date_update, approve_by2, date_approve2, remark_approve2, cost_center, id_factory) VALUES('','','','','','".mysqli_insert_id($dbc)."','".db_esc($dbc, $_POST["material_no"])."', '".db_esc($dbc, $data_info2["material_desc"])."','Z310','".db_esc($dbc, $_POST["model_code"])."','','','','','','','','".db_esc($dbc, $_POST["UOM_unit"])."','2200','','','','".db_esc($dbc, $username)."',NOW(),'','".db_esc($dbc, $rst_sta["status_desc"])."','".db_esc($dbc, $data_info3["sloc"])."','".db_esc($dbc, $_POST["ploc"])."','','','','','','','".db_esc($dbc, $_POST["qty_wastage"])."','".db_esc($dbc, $_POST["type_wastage"])."','".db_esc($dbc, $_POST["reason_wastage"])."', '".db_esc($dbc, $username)."','".db_esc($dbc, $_POST["date1"])."',NOW(),'','','','','','','WQ','','','','','','IPS12203','".db_esc($dbc, $_POST["factory"])."')";
$result_insert2 = mysqli_query($dbc, $query_insert2) or die (mysqli_error($dbc));
		   
	   }
	   
	    //print the message if there is one.
if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}
	  
   }
//----------------------------------------------------------------------------------- 
 
 ?>       
<?php
   //--------------------------------------------------------------------------------
		
		if(isset($_POST["Submit3"])) 
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


	
	$ref = "";
	
	//----------Generate Wastage QC------------
    $query_id = "SELECT count_max FROM run_count_no WHERE uid = '29'";
	$result_id = mysqli_query($dbc, $query_id);
	
	if ($result_id) 
{
	$nrows = mysqli_num_rows($result_id);
	$row_id = mysqli_fetch_row($result_id);
	
	$dht = 0000000; 
	$dht_OK = "22341";
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
	 


   

  if(isset($_POST["cancel"])) 
  {
 
    $cancel = $_POST["cancel"]; 
    $how_many = count($cancel); 
	$remark_reject = $_POST["remark_reject"]; 
	$string = "";
       
	   
	   foreach($_POST["cancel"] as $j=>$i) {
	    
		$amount .= $_POST["remark_reject"][$i];
	    $string = explode("|",($amount));	
			}
						
		   for ($i=0; $i<$how_many; $i++) { 
		   			
		//echo ($i+1).'-'.$cancel[$i]; echo $string[$i];
		//echo "</br>";
		
			  
	  //----------------------update table reject_detail_disposal
	  $query_disposal = "UPDATE reject_detail_disposal SET doc_dis = '".db_esc($dbc, $ref)."', doc_disposal_no = '".db_esc($dbc, $ref)."', user_disposal = '".db_esc($dbc, $username)."', date_disposal = NOW(), remarks = '".db_esc($dbc, $string[$i])."' WHERE id_disposal = '".db_esc($dbc, $cancel[$i])."'";
	  $result_disposal = mysqli_query($dbc, $query_disposal);
	   
	    //---------------------get data table reject_detail_disposal-------
	  $query_all = "SELECT * FROM reject_detail_disposal WHERE id_disposal = '".db_esc($dbc, $cancel[$i])."'";
	  $result_all = mysqli_query($dbc, $query_all);
	  
	  while($row_all = mysqli_fetch_array($result_all))
	  {
	
	  // //----------------------update table wastage_disposal
	  $query_wastage_update = "UPDATE wastage_transaction SET user_disposal = '".db_esc($dbc, $username)."', date_disposal = NOW(), remarks = '".db_esc($dbc, $row_all["remarks"])."' WHERE id_wastage_tran = '".db_esc($dbc, $row_all["uid"])."'";
	  $result_wastage_update = mysqli_query($dbc, $query_wastage_update);		
	  
				
	  }// end while loop
			
			}// end for loop
			
			 //--------ftp to SAP after generate disposal doc no.---------------	
		  
	  $query_generate = "SELECT *, DATE_FORMAT(date_wastage,'%Y-%m-%d') AS P, DATE_FORMAT(date_disposal,'%Y-%m-%d') AS P2, DATE_FORMAT(date_disposal,'%H:%i:%s') AS P3 FROM reject_detail_disposal WHERE doc_disposal_no = '".db_esc($dbc, $ref)."'";
	  $result_generate = mysqli_query($dbc, $query_generate);
	  
	  while($data_generate = mysqli_fetch_array($result_generate))
   {  
	$query_type_w = "SELECT * FROM type_wastage_detail WHERE id_wastage = '".db_esc($dbc, $data_generate['type_wastage'])."' ORDER BY id_wastage ASC";
    $result_type_w = mysqli_query($dbc, $query_type_w);
    $row_type_w = mysqli_fetch_array($result_type_w); 
	
	$query_reason_w = "SELECT * FROM reason_wastage WHERE id_reason_wastage = '".db_esc($dbc, $data_generate['reason_wastage'])."' ORDER BY id_reason_wastage ASC";
    $result_reason_w = mysqli_query($dbc, $query_reason_w);
    $row_reason_w = mysqli_fetch_array($result_reason_w);
	
			  
 $data .= $data_generate['doc_disposal_no'].";".$data_generate['comp_code'].";".$data_generate['work_center'].";".$data_generate['material_no'].";551;".$data_generate['shift_day'].";".$data_generate['qty_wastage'].";".$data_generate['UOM_unit'].";".$row_type['wastage_desc'].";".$row_reason['reason_wastage_desc'].";".$data_generate['ploc'].";Scrap;".$data_generate['P'].";".$data_generate['time_wastage'].";".$data_generate['P2'].";".$data_generate['P3'].";".$data_generate['user_disposal'].";".$data_generate['cost_center']."\r\n";
 
 $filen="GI3".$ref;
 
  //----------update table ftp_wastage------------
   
    $query_ftp_info = "INSERT INTO ftp_wastage(id, file_name, doc_disposal_no, id_disposal, material_no, material_desc, qty_ftp, uom, status_ftp, posting_date, posting_time, user_create, date_create, status_part) VALUES('','".db_esc($dbc, $filen)."','".db_esc($dbc, $data_generate['doc_disposal_no'])."','".db_esc($dbc, $data_generate["id_disposal"])."','".db_esc($dbc, $data_generate["material_no"])."','".db_esc($dbc, $data_generate["material_desc"])."','".db_esc($dbc, $data_generate["qty_wastage"])."','".db_esc($dbc, $data_generate["UOM_unit"])."','Y','".db_esc($dbc, $data_generate["P"])."','".db_esc($dbc, $data_generate["time_wastage"])."','".db_esc($dbc, $username)."',NOW(),'WQ')"; 
     $rst_ftp_info = mysqli_query($dbc, $query_ftp_info);

	  }

$file = "../FromPortal2/MTD9/".$filen.".csv";
//chmod($file, 0777);
file_put_contents($file,$data);
		
		
		
   //update count_max----------------------------------------
		
	
       $query_max_a = "UPDATE run_count_no SET count_max = '".$number."', date_updated = NOW() WHERE uid = '29'";
	   $result_max_a = mysqli_query($dbc, $query_max_a);
	 
   //end update count_max ---------------------------------		
	
	       echo "<script>";
		   echo "alert('Disposal Document No : $ref');";
		   echo "window.location='wastage_qc_tran_NG.php'";
	       echo "</script>"; 
		   exit(); //quit the script
	  
					
   

  }// end if
   else{
	   
	       echo "<script>";
		   echo "alert('Please tick the check box for proceed the transaction.');";
		   echo "window.location='wastage_qc_tran_NG.php'";
		   echo "</script>"; 
		   exit(); //quit the script
	   
   }
   
  


}	
   //-----------------------------------------------------------------

 
								 
   $query8 = "SELECT COUNT(*) FROM reject_detail_disposal WHERE status_disposal = '".db_esc($dbc, $rst_sta["status_desc"])."' AND status_part = 'WQ' AND doc_disposal_no = ''";
   $result8 = mysqli_query($dbc, $query8) or die(mysqli_error($dbc));
   $num_rows = mysqli_fetch_row($result8);

   $pages = new Paginator;
   $pages->items_total = $num_rows[0];
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') as R, DATE_FORMAT(date_posting,'%d-%m-%Y') as R2 FROM reject_detail_disposal WHERE status_disposal = '".db_esc($dbc, $rst_sta["status_desc"])."' AND status_part = 'WQ' AND doc_disposal_no = '' order by plan_no ASC";
$rs = mysqli_query($dbc, $query) or die(mysqli_error($dbc));  //run the query.
$num = mysqli_num_rows($rs);   //how many material are there?


	
	 if ($num > 0) {
	 
	 echo '<div align="center">There are currently  '. $num_rows[0].' record(s).</div>';
	 }
	

?>
  <table class="table">
<tr>
    <td width="1%">&nbsp;</td> 
    <td width="85%"> <div class="small-nav"></div></td> 
      <td width="14%"><!--<a href="upload_pps_month.php"><img src="../img/upload_file2.png" width="48" height="48" title="Upload File" />Upload File</a>--></td> 
  </tr>
</table>        
     <form name="myform" method="post" action="wastage_qc_tran_NG.php">
      <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display Request</h5>
          </div>
             
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                <th>No.</th> 
                <th>Model</th>
                <th width="77">Part No.</th>
                <!--<th>Storage Location</th>-->
                <th>Cost Center</th>
                <th>Type of Reject</th>
                <th>Date</th> 
                <th>Quantity</th> 
                <th>UOM</th>  
                <th>Reason</th>
                <th>Remarks</th>
                <th>Remove Item</th>
                </tr>
              </thead>   
              <tbody>
           <?php
   
   $counter = 1;
   $no = 1;
   $sta_out = "";
   $k= 1;
   
   while ($row = mysqli_fetch_array($rs))
   {	
	
	$query_type = "SELECT * FROM type_wastage_detail WHERE id_wastage = '".db_esc($dbc, $row['type_wastage'])."' ORDER BY id_wastage ASC";
    $result_type = mysqli_query($dbc, $query_type);
    $row_type = mysqli_fetch_array($result_type); 
	
	$query_reason = "SELECT * FROM reason_wastage WHERE id_reason_wastage = '".db_esc($dbc, $row['reason_wastage'])."' ORDER BY id_reason_wastage ASC";
    $result_reason = mysqli_query($dbc, $query_reason);
    $row_reason = mysqli_fetch_array($result_reason);
	
	 
      ?>
           
                <tr class="gradeX">
                <td width="30"><div align="center"><input type="checkbox" name="cancel[]" value="<?php echo $row["id_disposal"]; ?>" <?=was_checked($row["id_disposal"],$a) ?> /><input type="hidden" name="Check_ctr" value="yes" 
onClick="Check(document.myform.cancel)">  </div><?php echo $no; ?></td>
                <td width="80"><?php echo $row["model_code"]; ?></td>
                <td><?php echo $row["material_no"]; ?></td>
               <!-- <td width="60"><?php //echo $row["ploc_prod_reject"]; ?></td>-->
                <td width="60"><?php echo $row["cost_center"]; ?></td>
                <td width="80"><?php echo $row_type['wastage_desc']; ?></td>
                <td width="80"><?php echo $row["R2"]; ?></td>  
                <td width="60"><?php echo $row["qty_wastage"]; ?></td>
                <td width="60"><?php echo $row["UOM_unit"]; ?></td> 
                <td width="80"><?php echo $row_reason['reason_wastage_desc']; ?></td>
                <td width="140"><textarea name="remark_reject[<?php echo $row["id_disposal"]; ?>]" id="textarea" rows="2" cols="10" maxlength="250" ><?php if (isset($_POST['remark_reject'][($row["id_disposal"])])) { echo $_POST['remark_reject'][($row["id_disposal"])]; } ?></textarea>
               <input name="id_disposal[<?php echo $k; ?>]" type="hidden" value="<?php echo $row["id_disposal"]; ?>">
          </td><td><a value="Remove Item" href="cancel_wastage_tran_proc.php?uid=<?php echo $row["id_disposal"]; ?>&&TB_iframe=true&height=400&width=1000" class="thickbox" target="_self"><img src="../img/delete.png" width="16" height="16" alt="Remove Item">Remove Item</a>
               </td>
                </tr>
                
          <?php 
		  
		  $no ++;
		  $counter++; // menambah counter
		  $k ++;
		  } 
		  ?>
                
              
              </tbody>
            </table>
            <table class="table">
  <tr>
    <td>&nbsp;  <input name="Submit3" type="submit"  class="btn btn-success" id="button" value="Generate Disposal Document" onClick="return confirm('Confirm to generate disposal request?');"/></td>
  </tr>
</table>
          </div>
        
      </div></form>
    </div>
  </div>
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
