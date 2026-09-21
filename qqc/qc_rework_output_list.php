<?php

/**
 * qqc/qc_rework_output_list.php
 * Part of: QQC module (Quality)
 * Filename suggests: qc rework output list
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, qqc_detail_transaction, qqc_transaction, run_count_no, mat_master_header, storage2_tbl, type_reject_detail, reason_ng_reject.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_qqc_menu.php, footer.php.
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
require_role($dbc, 10);
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

//date_default_timezone_set('Asia/Bangkok');

      $current_date = date('Y-m-d H:i:s'); 
	  $next_date = date('Y-m-d H:i:s', strtotime($current_date .' +1 day'));


// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "rework_output_list_tran.php";

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

//CR status (New)

$sta = "SELECT * from request_status WHERE status_id = '1' ";
$sta_res = mysqli_query($dbc, $sta);
$rst_sta = mysqli_fetch_array($sta_res);

//CR status (Released)
$sta2 = "SELECT * from request_status WHERE status_id = '2' ";
$sta_res2 = mysqli_query($dbc, $sta2);
$rst_sta2 = mysqli_fetch_array($sta_res2);	

//CR status (Cancel)
$sta4 = "SELECT * from request_status WHERE status_id = '4' ";
$sta_res4 = mysqli_query($dbc, $sta4);
$rst_sta4 = mysqli_fetch_array($sta_res4);	

//CR status (In Progress)
$sta7 = "SELECT * from request_status WHERE status_id = '7' ";
$sta_res7 = mysqli_query($dbc, $sta7);
$rst_sta7 = mysqli_fetch_array($sta_res7);	

//CR status (Pending)
$sta8 = "SELECT * from request_status WHERE status_id = '8' ";
$sta_res8 = mysqli_query($dbc, $sta8);
$rst_sta8 = mysqli_fetch_array($sta_res8);	

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
  <div id="breadcrumb"> <a href="index_qqc.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">QA/QC</a> <a href="#" class="current">Rework List</a> </div>
  <h1>Rework List</h1>
</div>

<div class="container-fluid">
  <hr>
  <div class="row-fluid">
  <!--  <div class="span6">-->
          <!--<div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li class="active"><a  role="tab" href="confirm_backflush_tran.php">New Request</a></li>
              <li><a role="tab" href="display_request.php">Display Request</a></li>
              <li><a role="tab" href="posting_request.php">Posting Request</a></li>
            </ul>
          </div>
          </div>-->
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Rework</h5>
        </div>


 <?php
	   $uid = $_GET["uid"];
	
	   $query_qqc = "SELECT * FROM qqc_detail_transaction WHERE id_qqc = '$uid'";
	   $result_qqc = mysqli_query($dbc, $query_qqc);
	   $data_qqc = mysqli_fetch_array($result_qqc);
	   
	   $date_arini = date('Y-m-d'); 
	   $current_date = date('Y-m-d H:i:s'); 
	   $next_date = date('Y-m-d H:i:s', strtotime($current_date .' +2 day'));
	   
	   
	    //--------- qc detail (calculate total qty_qc receive from production) ------------
	 
	   $query_qqc_cal = "SELECT * FROM qqc_transaction WHERE qqc_doc_no = '".$data_qqc["qqc_doc_no"]."' AND bflush_no = '".$data_qqc["bflush_no"]."' AND status_QC != '".$rst_sta4["status_desc"]."'";
	   $result_qqc_cal = mysqli_query($dbc, $query_qqc_cal);
	   
	    $qty_total_rec = 0.000;
   		$total_qty_pending = 0.000;
  		$qty_total_ok = 0.000;
   		$qty_total_NG = 0.000;
		$total_ok = 0.000;
		$total_qty = 0.000;
   
	   
	   while($data_qqc_cal = mysqli_fetch_array($result_qqc_cal))
	   {
		   
		$qty_total_ok = ($qty_total_ok + $data_qqc_cal["qty_qc_ok"]);  
	    $qty_total_NG = ($qty_total_NG + $data_qqc_cal["qty_qc_NG"]);  
		   
	   }
	 
	  //------quantity qc pending checking OK ------------
	$total_qty = ($data_qqc["qty_balance"]);
	$total_qty_pending = ($total_qty - (($qty_total_ok) + ($qty_total_NG)));
		
	/*echo "PENDING :".$total_qty_pending; echo "<br>";
	echo "OK :".$qty_total_ok; echo "<br>";
	echo "NG :".$qty_total_NG; echo "<br>";*/
	   
   //--------------------------------------------------------------------------
	   if(isset($_POST["confirm1"])) 
  
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

       $qty_qc_ok = $_POST["qty_qc_ok"];
       $date1 = $_POST["date1"];
       $time1 = $_POST["time1"]; 
       $time2 = $_POST["time2"];
	   $status_new = "";
	   $sloc = $_POST["sloc"];
	   
	  $date_arini = date('Y-m-d'); 
      $current_date = date('Y-m-d H:i:s'); 
	  $next_date = date('Y-m-d H:i:s', strtotime($current_date .' +2 day'));
	  $next_date2 = date('Y-m-d', strtotime($date_arini .' +1 day'));
	  
	  
	  //check qty
	  if($total_qty_pending >= ($_POST["qty_qc_ok"]))
	  {
		
		$qty_qc_ok = TRUE;
		  
	  }else{
		  
	       echo "<script>";
		   echo "alert('Quantity entering is overlimit!');";
		   echo "window.location='qc_rework_output_list.php?uid=$uid'";
		   echo "</script>"; 
		   exit(); //quit the script
		   
	    $qty_qc_ok = FALSE;  
	  }
	  
	  
     //check only deilvery date
	 
					  
				 $date_date = (($_POST["date1"])." ".($_POST["time1"]).":".($_POST["time2"]).":00");
				  
				/*  if($date_date >= $current_date)
				  {
				     			
				  if($date_date < $current_date)
						{
						
						  $message.= '<p align="center">You are not allowed to request backdated date and time!</p>';
						  $date1 = FALSE;
						}
					
			      elseif($date_date > ($next_date))
					 {
					      $message.= '<p align="center">You are not allowed to request for more than 2 days advance!</p>';
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
						  
						  }*/
						  
				if(($_POST["date1"]) == "NULL")
				{
				  $date1 = FALSE;
				  $message.= '<p align="center">You are required to select Date!</p>';
				  }else{
				  $date1 = TRUE;
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
				  
				   if(($_POST["sloc"]) == "NULL")
				{
				  $sloc = FALSE;
				  $message.= '<p align="center">You are required to select Storage Location!</p>';
				  } else{
				  $sloc = TRUE;
				  }
	   
	   
	   if($qty_qc_ok && $time1 && $time2 && $date1 && $sloc)
	   {
		
    $t_time = (($_POST["time1"]).":".($_POST["time2"]));  
	
	
	$ref = "";
	
	//----------Generate Rework List QC2 (OK)
    $query_id = "SELECT count_max FROM run_count_no WHERE uid = '33'";
	$result_id = mysqli_query($dbc, $query_id);
	
	if ($result_id) 
{
	$nrows = mysqli_num_rows($result_id);
	$row_id = mysqli_fetch_array($result_id);
	
	$dht = 0000000; 
	$dht_OK = "22421";
	$dg2 = 0;

  	if($row_id["count_max"] <= 0)
  	{ 
   
    	$lastID = ($row_id["count_max"] + 1);
    	$dg = ($dht + ($lastID));
   }
   else
   {
      $lastID = ($row_id["count_max"] + 1);
      $dg =  $lastID;
	
    }
	$number = $dg; // Length of running no
    $number = sprintf('%07d', $number);  
	
	 
	  $ref = ($dht_OK.($number));
	
	
	} // end if $result_id
	 
	

	
	 	
  //----------find posting log depend material type
	  
	  if($data_qqc["material_type"] == "Z310")
	  {
		$ploc_qc = "P130";  
		  
	  }elseif($data_qqc["material_type"] == "Z210")
	  {
		$ploc_qc = "P120";
	  }else{
		
		$ploc_qc = "";
	  }
	  
	
 //insert into table qqc_transaction-------------
	
$query_data2 = "INSERT INTO qqc_transaction (id_qqc, id_tran, qqc_doc_no, qqc_no, bflush_no, plan_no, material_no, material_desc, material_type, qty_plan, qty_actual, qty_balance, qty_NG, qty_qc, qty_qc_ok, qty_qc_NG, status_QC, comp_code, work_center, shift_day, date_plan, user_create, date_create, user_update, date_update, user_qc_posting, date_qc_posting, time_qc_posting, ploc_qc, ploc, delivery_loc, type_qc_reject, reason_qc_reject, user_qc_reject, date_qc_reject, time_qc_reject, status_ftp_fgtran, status, qqc_no_ref, user_cancel, date_cancel) VALUES('','".$data_qqc["id_tran"]."','".$data_qqc["qqc_doc_no"]."','".$ref."','".$data_qqc["bflush_no"]."','".$data_qqc["plan_no"]."','".$data_qqc["material_no"]."', '".$data_qqc["material_desc"]."','".$data_qqc["material_type"]."','".$data_qqc["qty_plan"]."','".$data_qqc["qty_actual"]."','".$data_qqc["qty_balance"]."','','".$data_qqc["qty_qc"]."','".$_POST["qty_qc_ok"]."','','".$rst_sta8["status_desc"]."','".$data_qqc["comp_code"]."', '".$data_qqc["work_center"]."', '".$data_qqc["shift_day"]."','".$data_qqc["date_plan"]."','$username',NOW(),'','','$username','".$_POST["date1"]."','".$t_time."','".$data_qqc["ploc_qc"]."','".$_POST["sloc"]."','".$ploc_qc."','','','','','','N','Y','','','')";
$result_data2 = mysqli_query($dbc, $query_data2) or die (mysqli_error($dbc));
 
 
	//----check qqc_transaction total pending "0" ----------	
	
	
	
	   $query_qqc_cal2 = "SELECT * FROM qqc_transaction WHERE bflush_no = '".$data_qqc["bflush_no"]."' AND status_QC != '".$rst_sta4["status_desc"]."'";
	   $result_qqc_cal2 = mysqli_query($dbc, $query_qqc_cal2);
	   
	    $qty_total_rec2 = 0.000;
   		$total_qty_pending2 = 0.000;
  		$qty_total_ok2 = 0.000;
   		$qty_total_NG2 = 0.000;
		$total_ok2 = 0.000;
		$total_qty2 = 0.000;
   
	   
	   while($data_qqc_cal2 = mysqli_fetch_array($result_qqc_cal2))
	   {
		   
		$qty_total_ok2 = ($qty_total_ok2 + $data_qqc_cal2["qty_qc_ok"]);  
	    $qty_total_NG2 = ($qty_total_NG2 + $data_qqc_cal2["qty_qc_NG"]);  
		   
	   }
	 
	  //------quantity qc pending checking OK ------------
	$total_qty2 = ($data_qqc["qty_balance"]);
	$total_qty_pending2 = ($total_qty2 - (($qty_total_ok2) + ($qty_total_NG2)));
		
	/*echo "PENDING :".$total_qty_pending2; echo "<br>";
	echo "OK :".$qty_total_ok2; echo "<br>";
	echo "NG :".$qty_total_NG2; echo "<br>";
	*/
	//----------checking status_QC [Pending -> QC Done]----------
	  
	  if($total_qty_pending2 == "0")
	  {
		  $status_new = "QC OK";
	  }else{
		  
		  $status_new = "Pending";
	  }
	  
	//---update status_QC at qqc_detail_transaction--------
	$query_upd_sta = "UPDATE qqc_detail_transaction SET status_QC = '".$status_new."' WHERE bflush_no = '".$data_qqc["bflush_no"]."'";
	$result_upd_sta = mysqli_query($dbc, $query_upd_sta); 
	
	
 //------- crete text file to SAP [FromPortal] -----------
 
  //update count_max----------------------------------------
		
	
       $query_max_a = "UPDATE run_count_no SET count_max = '".$number."', date_updated = NOW() WHERE uid = '33'";
	   $result_max_a = mysqli_query($dbc, $query_max_a);
	 
   //end update count_max ---------------------------------	
 
 
	      if($result_data2)
		  {
	
	       echo "<script>";
		   echo "alert('Rework OK Document No : $ref');";
		  // echo "window.location='qc_rework_output_list.php?uid=$uid'";
		   echo "window.location='ftp_GT_SAP.php?buid=$ref&&uid=$uid'";
	       echo "</script>"; 
		   exit(); //quit the script
	  
		  }
	  	   
	   }
	   
	// mysqli_close($dbc);  
	   
  //print the message if there is one.
if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}
  
} // end if

   //------------------------------------------------------------------------------
	   


 //--------------------------------------------------------------------------
	   if(isset($_POST["confirm2"])) 
  
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

       $qty_qc_NG = $_POST["qty_qc_NG"];
       $date2 = $_POST["date2"];
       $time3 = $_POST["time3"]; 
       $time4 = $_POST["time4"];
	   $type_reject = $_POST["type_reject"];
	   $reason_reject = $_POST["reason_reject"];
	   $status_new = "";
	   
	  $date_arini = date('Y-m-d'); 
      $current_date = date('Y-m-d H:i:s'); 
	  $next_date = date('Y-m-d H:i:s', strtotime($current_date .' +2 day'));
	  $next_date2 = date('Y-m-d', strtotime($date_arini .' +1 day'));
	  
	  
	  
	   
	  //check qty
	  if($total_qty_pending >= ($_POST["qty_qc_NG"]))
	  {
		
		$qty_qc_NG = TRUE;
		  
	  }else{
		  
		//$message.= '<p align="center">Quantity overlimit!</p>';
		   echo "<script>";
		   echo "alert('Quantity entering is overlimit!');";
		   echo "window.location='qc_rework_output_list.php?uid=$uid'";
		   echo "</script>"; 
		   exit(); //quit the script
		
	    $qty_qc_NG = FALSE;  
	  }
 
     //check only deilvery date
	 
					  
				 $date_date2 = (($_POST["date2"])." ".($_POST["time3"]).":".($_POST["time4"]).":00");
				  
				 /* if($date_date2 >= $current_date)
				  {
				     			
				  if($date_date2 < $current_date)
						{
						
						  $message.= '<p align="center">You are not allowed to request backdated date and time!</p>';
						  $date1 = FALSE;
						}
					
			      elseif($date_date2 > ($next_date))
					 {
					      $message.= '<p align="center">You are not allowed to request for more than 2 days advance!</p>';
						  $date1 = FALSE;
						  
					   
						}
												
					 else{
					   
					     if(isset($_POST["time3"]) < ($hours))
						 {
						  $time3 = FALSE;
					      $message.= '<p align="center">You are not allowed to request backdated time!</p>';
						  $date2 = FALSE;
					      }
					  
					       $date2 = TRUE;
						
						}
	                 }else{
						$message.= '<p align="center">You are not allowed to request backdated date and time!</p>';
						  $date2 = FALSE;
						  
						  }*/
				
				if(($_POST["date2"]) == "NULL")
				{
				  $date2 = FALSE;
				  $message.= '<p align="center">You are required to select Date!</p>';
				  }else{
				  $date2 = TRUE;
				  }		  
				      
					
				if(($_POST["time3"]) == "NULL")
				{
				  $time3 = FALSE;
				  $message.= '<p align="center">You are required to select Hours!</p>';
				  }else{
				  $time3 = TRUE;
				  }
				  
				  if(($_POST["time4"]) == "NULL")
				{
				  $time4 = FALSE;
				  $message.= '<p align="center">You are required to select Minutes!</p>';
				  } else{
				  $time4 = TRUE;
				  }
				  
		//---check type of reject
		       if(($_POST["type_reject"]) == "NULL")
				{
				  $type_reject = FALSE;
				  $message.= '<p align="center">You are required to select Type of Reject!</p>';
				  } else{
				  $type_reject = TRUE;
				  }		  
	   
	   //---check reason reject
		       if(($_POST["reason_reject"]) == "NULL")
				{
				  $reason_reject = FALSE;
				  $message.= '<p align="center">You are required to select Reason Reject!</p>';
				  } else{
				  $reason_reject = TRUE;
				  }		  
	   
	      // echo $type_reject; echo "<br>";
		  // echo $reason_reject; echo "<br>"; 
		     
	   if($qty_qc_NG && $time3 && $time4 && $date2 && $type_reject && $reason_reject)
	   {

	$t_time2 = (($_POST["time3"]).":".($_POST["time4"]));  
	
	$ref = "";
	$type_reject = $_POST["type_reject"];
	$reason_reject = $_POST["reason_reject"];
	
	
	//-----------generate Rework List QC(NG) ---------------------
	
    $query_id = "SELECT count_max FROM run_count_no WHERE uid = '27'";
	$result_id = mysqli_query($dbc, $query_id);
	
	if ($result_id) 
{
	$nrows = mysqli_num_rows($result_id);
	$row_id = mysqli_fetch_array($result_id);
	
	$dht = 0000000; 
	$dht_OK = "22331";
	$dg2 = 0;

  	if($row_id["count_max"] <= 0)
  	{ 
   
    	$lastID = ($row_id["count_max"] + 1);
    	$dg = ($dht + ($lastID));
   }
   else
   {
      $lastID = ($row_id["count_max"] + 1);
      $dg =  $lastID;
	
    }
	$number = $dg; // Length of running no
    $number = sprintf('%07d', $number);  
	
	 
	  $ref = ($dht_OK.($number));
	
	
	} // end if $result_id
	 
		   
	  //----------find posting log depend material type
	  
	 
		$ploc_qc = "P1RW";  
		  
	//insert into table qqc_transaction-------------
	
$query_data2 = "INSERT INTO qqc_transaction (id_qqc, id_tran, qqc_doc_no, qqc_no, bflush_no, plan_no, material_no, material_desc, material_type, qty_plan, qty_actual, qty_balance, qty_NG, qty_qc, qty_qc_ok, qty_qc_NG, status_QC, comp_code, work_center, shift_day, date_plan, user_create, date_create, user_update, date_update, user_qc_posting, date_qc_posting, time_qc_posting, ploc_qc, ploc, delivery_loc, type_qc_reject, reason_qc_reject, user_qc_reject, date_qc_reject, time_qc_reject, status_ftp_fgtran, status, qqc_no_ref, user_cancel, date_cancel) VALUES('','".$data_qqc["id_tran"]."','".$data_qqc["qqc_doc_no"]."','".$ref."','".$data_qqc["bflush_no"]."','".$data_qqc["plan_no"]."','".$data_qqc["material_no"]."', '".$data_qqc["material_desc"]."','".$data_qqc["material_type"]."','".$data_qqc["qty_plan"]."','".$data_qqc["qty_actual"]."','".$data_qqc["qty_balance"]."','','".$data_qqc["qty_qc"]."','','".$_POST["qty_qc_NG"]."','".$rst_sta8["status_desc"]."','".$data_qqc["comp_code"]."', '".$data_qqc["work_center"]."', '".$data_qqc["shift_day"]."','".$data_qqc["date_plan"]."','$username',NOW(),'','','$username','".$_POST["date2"]."','".$t_time2."','".$ploc_qc."','".$data_qqc["ploc"]."','','".$type_reject."','".$reason_reject."','$username',NOW(),NOW(),'N','N','','','')";
$result_data2 = mysqli_query($dbc, $query_data2) or die (mysqli_error($dbc));
 
	
	
	//----check qqc_transaction total pending "0" ----------	
	
	   $query_qqc_cal2 = "SELECT * FROM qqc_transaction WHERE bflush_no = '".$data_qqc["bflush_no"]."' AND status_QC != '".$rst_sta4["status_desc"]."'";
	   $result_qqc_cal2 = mysqli_query($dbc, $query_qqc_cal2);
	   
	    $qty_total_rec2 = 0.000;
   		$total_qty_pending2 = 0.000;
  		$qty_total_ok2 = 0.000;
   		$qty_total_NG2 = 0.000;
		$total_ok2 = 0.000;
		$total_qty2 = 0.000;
   
	   
	   while($data_qqc_cal2 = mysqli_fetch_array($result_qqc_cal2))
	   {
		   
		$qty_total_ok2 = ($qty_total_ok2 + $data_qqc_cal2["qty_qc_ok"]);  
	    $qty_total_NG2 = ($qty_total_NG2 + $data_qqc_cal2["qty_qc_NG"]);  
		   
	   }
	 
	  //------quantity qc pending checking OK ------------
	$total_qty2 = ($data_qqc["qty_balance"]);
	$total_qty_pending2 = ($total_qty2 - (($qty_total_ok2) + ($qty_total_NG2)));
		
	/*echo "PENDING :".$total_qty_pending2; echo "<br>";
	echo "OK :".$qty_total_ok2; echo "<br>";
	echo "NG :".$qty_total_NG2; echo "<br>";
	*/
	//----------checking status_QC [Pending -> QC Done]----------
	  
	  if($total_qty_pending2 == "0")
	  {
		  $status_new = "QC OK";
	  }else{
		  
		  $status_new = "Pending";
	  }
	  
	//---update status_QC at qqc_detail_transaction--------
	$query_upd_sta = "UPDATE qqc_detail_transaction SET status_QC = '".$status_new."' WHERE bflush_no = '".$data_qqc["bflush_no"]."'";
	$result_upd_sta = mysqli_query($dbc, $query_upd_sta);
	
	
 //------- crete text file to SAP [FolderPortal] -----------
 
 	 //update count_max----------------------------------------
		
	
       $query_max_b = "UPDATE run_count_no SET count_max = '".$number."', date_updated = NOW() WHERE uid = '27'";
	   $result_max_b = mysqli_query($dbc, $query_max_b);
	 
   //end update count_max ---------------------------------	
	
 
	      if($result_data2)
		  {
	
	       echo "<script>";
		   echo "alert('Rework NG Document No : $ref');";
		   echo "window.location='ftp_GT_SAP_NG.php?buid=$ref&&uid=$uid'";
	       echo "</script>"; 
		   exit(); //quit the script
	  
		  }
	  	   
	   }
	   
	// mysqli_close($dbc);  
	   
  //print the message if there is one.
if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}
  
} // end if

   //------------------------------------------------------------------------------
	   
	   
	if(isset($_POST["save_btn"]) && $_POST!=="") 
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


           echo "<script>";
		   echo "window.location='rework_output_list_tran.php'";
	       echo "</script>"; 
		   exit(); //quit the script


}

		 $no = 1; 
		  
		  ?>
         <form name="myform" method="post" action="qc_rework_output_list.php?uid=<?php echo $uid; ?>">
   <table width="100%" border="0" cellpadding="2">
     <tr>
       <td width="52%" height="234">
         <table width="95%" border="1" align="right" cellpadding="2" class="table-condensed">
           <tr>
             <th width="33%"><div align="left">Planned Order No.</div></th>
             <td width="5%"> :</td>
             <td width="62%"><?php echo $data_qqc["plan_no"]; ?></td>
             </tr>
           <tr>
             <th scope="row"><div align="left">Work Center</div></th>
             <td>:</td>
             <td><?php echo $data_qqc["work_center"]; ?></td>
             </tr>
           <tr>
             <th scope="row"><div align="left">Shift</div></th>
             <td>:</td>
             <td><?php echo $data_qqc["shift_day"]; ?></td>
             </tr>
           <tr>
             <th scope="row"><div align="left">Part No.</div></th>
             <td>:</td>
             <td><?php echo $data_qqc["material_no"]; ?></td>
           </tr>
           <tr>
             <th scope="row"><div align="left">Part Name</div></th>
             <td>:</td>
             <td><?php echo $data_qqc["material_desc"]; ?></td>
             </tr>
           <tr>
             <th scope="row"><div align="left">Pending Qty</div></th>
             <td>:</td>
             <td><?php echo $data_qqc["qty_balance"]; ?></td>
           </tr>
           <tr>
             <th scope="row"><div align="left">Cummulative QC OK</div></th>
             <td>:</td>
             <td><?php echo $qty_total_ok; ?></td>
           </tr>
           <tr>
             <th scope="row"><div align="left">Cummulative QC NG</div></th>
             <td>:</td>
             <td><?php echo $qty_total_NG; ?></td>
           </tr>
           <tr>
             <th scope="row"><div align="left">Pending balance</div></th>
             <td>:</td>
             <td><?php echo $total_qty_pending; ?></td>
           </tr>
           </table></td>
       <td width="48%">
         <table width="95%" border="0" align="center" cellpadding="2">
           <tr>
             <td><span class="style4">&nbsp;<?php echo date("D M d, Y");   ?></span>&nbsp;&nbsp;<span class="style5"><?php echo date("H:i:s");  ?></span></td>
             </tr>
           <tr>
             <td><p>&nbsp;</p>
               <p>&nbsp;</p>
               <p>&nbsp;</p>
               <p>&nbsp;</p>
               <p>&nbsp;</p>
               <p>&nbsp;</p></td>
             </tr>
           
           </table></td>
     </tr>
     </table>
           <p align="center">--------------------------------------------------------------------------------------------------------------------------------------------- </p>
             <?php
			 
	 /*  $query_component = "SELECT * FROM mat_master_header AS h, mat_master_detail AS s WHERE h.id_hdr = s.id_hdr AND s.material = '".$data_scan["material_no"]."' AND s.bom_status = 'Y'";
	   $result_component = mysqli_query($dbc, $query_component);*/
	  
			 ?>
             
             <table width="98%" align="right" >
               <tr>
                 <td><p>Please enter quantity for OK</p>
                   <table width="99%" align="right" class="table table-bordered">
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
                     <td>Posting Date</td>
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
                     <td>Posting Time </td>
                     <td>:</td>
                     <td><select name="time1" id="time1">
                       <?php if($_POST["confirm1"] == true)
		{  
		?>
                       <option value="<?php echo $_POST["time1"]; ?>"><?php echo sprintf('%02d', $_POST["time1"]);	 ?></option>
                       <?php
	 }else{
	 ?>
                       <option value="<?php echo sprintf('%02d', date('H'));	 ?>" placeholder="HOURS"><?php echo sprintf('%02d', date('H'));	 ?></option>
                       <?php
	  }
	  
      for($i2 = 0; $i2 <= 23; $i2++): ?>
                       <option value="<?= $i2; ?>"> <?php echo sprintf('%02d', $i2); ?></option>
                       <?php endfor; ?>
                     </select>
                       :</td>
                     <td><select name="time2" id="time2">
                       <?php if($_POST["confirm1"] == true)  
		{  
		?>
                       <option value="<?php echo $_POST["time2"]; ?>"><?php echo sprintf('%02d', $_POST["time2"]);	 ?></option>
                       <?php
	 }else{
	 ?>
                       <option value="<?php echo sprintf('%02d', date('i'));	 ?>" placeholder="MINUTES"><?php echo sprintf('%02d', date('i'));	 ?></option>
                       <?php
	  }
      for($j = 0; $j <= 59; $j++): ?>
                       <option value="<?= $j; ?>"> <?php echo sprintf('%02d', $j); ?></option>
                       <?php endfor; ?>
                     </select></td>
                   </tr>
                   <tr>
                     <td>OK</td>
                     <td>:</td>
                     <td><input name="qty_qc_ok" type="number" min="0" max="<?php echo $total_qty_pending; ?>" value="<?php if(isset($_POST["qty_qc_ok"])) { echo $_POST["qty_qc_ok"]; } ?>" /></td>
                     <td colspan="4"></td>
                     </tr>
                   <tr>
                     <td>Storage Location</td>
                     <td>:</td>
                     <td><select name="sloc" id="sloc" class="span11">
                  <option value="NULL" placeholder="Select Storage Location"> -- Select Storage Location --</option>
                  <?php
	               $query_storage = "SELECT * FROM storage2_tbl ORDER BY qc_sloc_code ASC";
                   $result_storage = mysqli_query($dbc, $query_storage);
  
                   while($row_storage = mysqli_fetch_array($result_storage)) 
			      {
					  
				   ?>
                     <?php if($_POST["confirm1"] == true)  
		         {   ?>
                    <option value="<?php echo $row_storage["qc_sloc_code"]; ?>"<?php if($row_storage["qc_sloc_code"] == $_POST["sloc"]) echo "selected"; ?>> <?php echo $row_storage["qc_sloc_code"]; ?></option>
                     
                  <?php
				 }else{
				  
				  ?> 
                  <option value="<?php echo $row_storage["qc_sloc_code"]; ?>"> <?php echo $row_storage["qc_sloc_code"]; ?></option>
                  <?php
				    }  // else
				  
                  }
				?>
              </select></td>
                     <td colspan="4"></td>
                     </tr>
                   <tr>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                     <td colspan="4"><input name="confirm1" type="submit" id="confirm1" value="CONFIRM" class="btn-mini btn-success" onClick="return confirm('Confirm to save OK request?');"></td>
                   </tr>
                 </table></td>
               </tr>
             </table>
           <p>&nbsp;</p>
           <p>&nbsp;</p>
           <p>&nbsp;</p>
           <p>&nbsp;</p>
           <p>&nbsp;</p>
           <p>&nbsp;</p>
           <p>&nbsp;</p>
           <p>&nbsp;</p>
           <p>&nbsp;</p>
           <table width="98%" align="right" >
             <tr>
               <td><p>Please enter quantity for NG</p>
                 <table width="99%" align="right" class="table table-bordered">
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
                     <td>Posting Date</td>
                     <td>:</td>
                     <td><?php
    
						 $dt2 = $today['mday'];
						 $mt2 = $today['mon'];
						 $yr2 = $today['year'];
	 
	                  $myCalendar = new tc_calendar("date2", true, false);
					  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					  $myCalendar->setDate($dt2,$mt2,$yr2);
					  $myCalendar->setPath("/calendar/");
					  $myCalendar->setYearInterval(2010, 2030);
					  // $myCalendar->setOnChange("myChanged('test')");
					  $myCalendar->writeScript();
					  
?></td>
                     <td>Posting Time </td>
                     <td>:</td>
                     <td><select name="time3" id="time3">
                       <?php if($_POST["confirm2"] == true)
		{  
		?>
                       <option value="<?php echo $_POST["time3"]; ?>"><?php echo sprintf('%02d', $_POST["time3"]);	 ?></option>
                       <?php
	 }else{
	 ?>
                       <option value="<?php echo sprintf('%02d', date('H'));	 ?>" placeholder="HOURS"><?php echo sprintf('%02d', date('H'));	 ?></option>
                       <?php
	  }
	  
      for($b2 = 0; $b2 <= 23; $b2++): ?>
                       <option value="<?= $i2; ?>"> <?php echo sprintf('%02d', $b2); ?></option>
                       <?php endfor; ?>
                     </select>
                       :</td>
                     <td><select name="time4" id="time4">
                       <?php if($_POST["confirm2"] == true)  
		{  
		?>
                       <option value="<?php echo $_POST["time4"]; ?>"><?php echo sprintf('%02d', $_POST["time4"]);	 ?></option>
                       <?php
	 }else{
	 ?>
                       <option value="<?php echo sprintf('%02d', date('i'));	 ?>" placeholder="MINUTES"><?php echo sprintf('%02d', date('i'));	 ?></option>
                       <?php
	  }
      for($w = 0; $w <= 59; $w++): ?>
                       <option value="<?= $w; ?>"> <?php echo sprintf('%02d', $w); ?></option>
                       <?php endfor; ?>
                     </select></td>
                   </tr>
                   <tr>
                     <td>NG</td>
                     <td>:</td>
                     <td><input name="qty_qc_NG" type="number" min="0" max="<?php echo $total_qty_pending; ?>" value="<?php if(isset($_POST["qty_qc_NG"])) { echo $_POST["qty_qc_NG"]; } ?>" /></td>
                     <td colspan="4">&nbsp;</td>
                   </tr>
                   <tr>
                     <td>Type of Reject</td>
                     <td>:</td>
                     <td><select name="type_reject" id="type_reject" class="span11">
                  <option value="NULL" placeholder="Select Type of Reject"> -- Select Type of Reject --</option>
                  <?php
	               $query_type = "SELECT * FROM type_reject_detail WHERE status_type = 'Y' ORDER BY id_type ASC";
                   $result_type = mysqli_query($dbc, $query_type);
  
                   while($row_type = mysqli_fetch_array($result_type)) 
			      {
					  
				   ?>
                     <?php if($_POST["confirm2"] == true)  
		         {   ?>
                    <option value="<?php echo $row_type["id_type"]; ?>"<?php if($row_type["id_type"] == $_POST["type_reject"]) echo "selected"; ?>> <?php echo $row_type["type_desc"]; ?></option>
                     
                  <?php
				 }else{
				  
				  ?> 
                  <option value="<?php echo $row_type["id_type"]; ?>"> <?php echo $row_type["type_desc"]; ?></option>
                  <?php
				    }  // else
				  
                  }
				?>
              </select></td>
                     <td colspan="4">&nbsp;</td>
                   </tr>
                   <tr>
                     <td>Reason</td>
                     <td>:</td>
                     <td><select name="reason_reject" id="reason_reject" class="span11">
                  <option value="NULL" placeholder="Select Type of Reject"> -- Select Type of Reject --</option>
                  <?php
	               $query_reason = "SELECT * FROM reason_ng_reject WHERE status_reject = 'Y' ORDER BY id_reject ASC";
                   $result_reason = mysqli_query($dbc, $query_reason);
  
                   while($row_reason = mysqli_fetch_array($result_reason)) 
			      {
					   if($_POST["confirm2"] == true)  
		         {   ?>
                    <option value="<?php echo $row_reason["id_reject"]; ?>"<?php if($row_reason["id_reject"] == $_POST["reason_reject"]) echo "selected"; ?>> <?php echo $row_reason["reject_desc"]; ?></option>
                     
                  <?php
				 }else{
				  
				  ?> 
		    <option value="<?php echo $row_reason["id_reject"]; ?>"> <?php echo $row_reason["reject_desc"]; ?></option>
                  <?php
				    }//else
                  }
				?>
              </select></td>
                     <td colspan="4">&nbsp;</td>
                   </tr>
                   <tr>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                     <td colspan="4"><input name="confirm2" type="submit" id="confirm2" value="CONFIRM" class="btn-mini btn-success" onClick="return confirm('Confirm to save NG request?');"></td>
                   </tr>
                 </table></td>
             </tr>
           </table>
           <p>&nbsp;</p>
           <p>&nbsp;</p>
           <p>&nbsp;</p>
           <p>&nbsp;</p>
           <p>&nbsp;</p>
           <p>
             <!-- <input name="nextsave" type="submit" id="submit2" value="SCAN NEXT" class="btn btn-warning">-->           </p>
           <p>&nbsp;           </p>
           <p>&nbsp;           </p>
           <p>&nbsp;&nbsp;&nbsp;&nbsp;<input name="save_btn" type="submit" id="submit" value="NEXT" class="btn btn-success" >
           </p>
           <p>&nbsp;</p>
              
         
          </form>
          
          
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
