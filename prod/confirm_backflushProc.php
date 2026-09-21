<?php

/**
 * prod/confirm_backflushProc.php
 * Part of: Production module
 * Filename suggests: confirm backflushProc
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, scan_prod_planning, run_count_no, pps_detail, pps_detail_transaction, shift_detail, mat_master_header, delivery_tagasn.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_production_menu.php, footer.php.
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
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

date_default_timezone_set('Asia/Kuala_Lumpur');
$currentdate = (date("Y-m-d"));
$current_date = date('Y-m-d H:i:s'); 
$next_date = date('Y-m-d H:i:s', strtotime($current_date .' +1 day'));


// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "confirm_backflush_tran.php";

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

	?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo h($data_setup["title_desc"]); ?></title>
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
<?php include "left_production_menu.php";  ?>
<!--sidebar-menu-->
<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
<script language="javascript">
var seconds_left = 10;
var interval = setInterval(function() {
    document.getElementById('timer_div').innerHTML = --seconds_left;

    if (seconds_left <= 0)
    {
       document.getElementById('timer_div').innerHTML = "You are Ready!";
       clearInterval(interval);
    }
}, 1000);

</script>
<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_production.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Production</a> <a href="#" class="current">Confirmation Backflush</a> </div>
  <h1>Confirmation Backflush</h1>
</div>

<div class="container-fluid">
  <hr>
  <div class="row-fluid">
 
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>New Request</h5>
        </div>


 <?php
	   $buid = $_GET["buid"];
	
	   //echo $uid;
	
	   $query_scan = "SELECT * FROM scan_prod_planning WHERE id_scan = '".db_esc($dbc, $buid)."'";
	   $result_scan = mysqli_query($dbc, $query_scan);
	   $data_scan = mysqli_fetch_array($result_scan);
	   
	   $date_arini = date('Y-m-d'); 
	   $current_date = date('Y-m-d H:i:s'); 
	   $next_date = date('Y-m-d H:i:s', strtotime($current_date .' +2 day'));
	   
	   
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

       $qty_actual = $_POST["qty_actual"];
       $date1 = $_POST["date1"];
       $time1 = $_POST["time1"]; 
       $time2 = $_POST["time2"];
	   
	  $date_arini = date('Y-m-d'); 
    $current_date = date('Y-m-d H:i:s'); 
	  $next_date = date('Y-m-d H:i:s', strtotime($current_date .' +2 day'));
	  $next_date2 = date('Y-m-d', strtotime($date_arini .' +1 day'));
	  
      //check only deilvery date
	 
      $ddF = substr($_POST["date1"],0,2);
      $mmF = substr($_POST["date1"],3,2);
      $yyF = substr($_POST["date1"],6,4);
   
        $date1_final = ($yyF.'-'.$mmF.'-'.$ddF);
	 
					  
				 $date_date = (($_POST["date1"])." ".($_POST["time1"]).":".($_POST["time2"]).":00");
				  
				 
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
	   
	   
	   if($qty_actual && $time1 && $time2 && $date1)
	   {
		   
	   $qty_actual = $_POST["qty_actual"];
       $date1 = $_POST["date1"];
       $time1 = $_POST["time1"]; 
       $time2 = $_POST["time2"];
		
    $t_time = (($_POST["time1"]).":".($_POST["time2"]));  
	
	$ref = "";

  
  //------generate Backflush OK No.---------------------------------
	
	$query_id = "SELECT * FROM run_count_no WHERE uid = '19'";
	$result_id = mysqli_query($dbc, $query_id);
	
	if ($result_id) 
{
	$nrows = mysqli_num_rows($result_id);
	$row_id = mysqli_fetch_array($result_id);
	
	$dht = 0000000; 
	$dht_OK = "22211";
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
	
	  //$ref = (($data_setup["comp_code"]).$dht_OK.($number));
	
	} // end if $result_id
  
	
	//echo $ref;
	
	 //--------- pps detail ------------
	 
	   $query_pps = "SELECT * FROM pps_detail WHERE plan_no = '".db_esc($dbc, $data_scan["plan_no"])."'";
	   $result_pps = mysqli_query($dbc, $query_pps);
	   $data_pps = mysqli_fetch_array($result_pps);
	   
	  //----------find posting log depend material type
	  
	  if($data_scan["material_type"] == "Z310")
	  {
		$ploc = "P130";  
		  
	  }elseif($data_scan["material_type"] == "Z210")
	  {
		$ploc = "P120";
	  }else{
		
		$ploc = "";
	  }
	  
	 //insert into table pps_detail_transaction-------------
	
$query_data2 = "INSERT INTO pps_detail_transaction (id, pps_id, ref_id, bflush_no, plan_no, id_scan, upload_id, model_code, month_plan, material_no, material_desc, material_type, qty_plan, qty_actual, qty_balance, qty_NG, status_pps, comp_code, work_center, shift_pps1, shift_pps2, date_plan, status, user_upload, date_upload,user_create, date_create, user_update, date_update, user_posting, date_posting, time_posting, ploc, delivery_loc, type_reject, reason_reject, user_reject, date_reject, time_reject, status_ftp_bflush, bflush_no_ref, user_cancel, date_cancel,remark_cancel,plant_code,shift_posting) VALUES('','".db_esc($dbc, $data_pps["id"])."','".db_esc($dbc, $data_pps["ref_id"])."','".db_esc($dbc, $ref)."','".db_esc($dbc, $data_scan["plan_no"])."','".db_esc($dbc, $data_scan["id_scan"])."','".db_esc($dbc, $data_pps["upload_id"])."','".db_esc($dbc, $data_pps["model_code"])."','".db_esc($dbc, $data_pps["month_plan"])."','".db_esc($dbc, $data_pps["material_no"])."', '".db_esc($dbc, $data_scan["material_desc"])."','".db_esc($dbc, $data_scan["material_type"])."','".db_esc($dbc, $data_pps["qty_plan"])."','".db_esc($dbc, $qty_actual)."','','','".db_esc($dbc, $rst_sta7["status_desc"])."','".db_esc($dbc, $data_scan["scan_plant"])."','".db_esc($dbc, $data_scan["work_center"])."','".db_esc($dbc, $data_pps["shift_pps1"])."','".db_esc($dbc, $data_pps["shift_pps2"])."','".db_esc($dbc, $data_pps["date_plan"])."','Y','".db_esc($dbc, $data_pps["user_upload"])."','".db_esc($dbc, $data_pps["date_upload"])."','".db_esc($dbc, $username)."',NOW(),'','','".db_esc($dbc, $username)."','".db_esc($dbc, $_POST["date1"])."','".db_esc($dbc, $t_time)."','".db_esc($dbc, $ploc)."','','','','','','','Y','','','','','".db_esc($dbc, $data_scan["scan_plant"])."','')";
$result_data2 = mysqli_query($dbc, $query_data2) or die(db_fail($dbc));
 
  //-------------------update---------------------
  
    $query_all_info = "SELECT * FROM pps_detail_transaction WHERE id = '".mysqli_insert_id($dbc)."'";
	  $result_all_info = mysqli_query($dbc, $query_all_info);
	  $data_all_info = mysqli_fetch_array($result_all_info); 

//---shift detail ------
	
$query_sht = "SELECT * FROM shift_detail WHERE id_shift = '1'";
$result_sht = mysqli_query($dbc, $query_sht);
$data_sht = mysqli_fetch_array($result_sht); 

//----shift posting ----

if(($data_all_info["time_posting"] >= $data_sht["time_start"]) && ($data_all_info["time_posting"] <= $data_sht["time_end"]))
{
 
 $shif_p = "D/S"; 

}else
{

$shif_p = "N/S"; 

}

//---------update shift posting ---------------
$query_upd_detail2 = "UPDATE pps_detail_transaction SET shift_posting = '".db_esc($dbc, $shif_p)."' WHERE id = '".db_esc($dbc, $data_all_info["id"])."'";
$result_upd_detail2 = mysqli_query($dbc, $query_upd_detail2);  



		   
  //-------update status "Released" to "Inprogress" in table pps_detail	
	
	$query_upd_detail = "UPDATE pps_detail SET status_pps = '".db_esc($dbc, $rst_sta7["status_desc"])."' WHERE id = '".db_esc($dbc, $buid)."'";
	$result_upd_detail = mysqli_query($dbc, $query_upd_detail) or die(db_fail($dbc));


  //----edit by azie 17 nov 2021 night shift ------	
  
  
	$query_upd_shift = "SELECT * FROM pps_detail_transaction WHERE id = '".db_esc($dbc, $data_all_info["id"])."'";
  $result_upd_shift = mysqli_query($dbc, $query_upd_shift);
	$row_upd_shift = mysqli_fetch_array($result_upd_shift);
	
	
	if(($row_upd_shift["shift_posting"] == "N/S") && ($row_upd_shift["date_posting"] == $currentdate))
	{
	
	$prev_date = date('Y-m-d', strtotime($currentdate .' -1 day'));	
	
	if(($row_upd_shift["time_posting"] > "21:00:00" ) && ($row_upd_shift["time_posting"] < "23:59:59" ))
	{
		
	}else{
		
	$query_upd_shift2 = "UPDATE pps_detail_transaction SET date_posting = '".$prev_date."' WHERE id = '".db_esc($dbc, $row_upd_shift["id"])."'";
	$result_upd_shift2 = mysqli_query($dbc, $query_upd_shift2);  	

	}
	}
	
	
	
  //-------insert table print_tag_backflush [generate print tag] ---------------	
	
	  $query_tag = "SELECT * FROM pps_detail_transaction WHERE id = '".db_esc($dbc, $data_all_info["id"])."'";
    $result_tag = mysqli_query($dbc, $query_tag);

  
  
while ($row = mysqli_fetch_array($result_tag))
{
		
	  $dl_qty = $row["qty_actual"];






	  
	  //shift	
		if($row["shift_pps1"] != "")
	{
		$sta = "D/S";
		
	}elseif($row["shift_pps2"] != "")
	 {
		$sta = "N/S";
	 }else{
		 
		$sta = " ";
	 }	
	  
	  
	  
	  
	  //----detail standard packaging [ambil dari table mat_master_header]
	  
	   $query_pack = "SELECT std_package, type_package FROM mat_master_header WHERE material_no = '".db_esc($dbc, $row["material_no"])."'";
	   $result_pack = mysqli_query($dbc, $query_pack);
	   $data_pack = mysqli_fetch_array($result_pack);
		
		
		        if(($data_pack["std_package"] == "") || ($data_pack["std_package"] == "0"))
		        {
		
		        $st_pack = $row["qty_actual"];
	            }else{
		
                $st_pack = $data_pack["std_package"];
		        }
		 
      $bil_tag = ($dl_qty / $st_pack);
		
     $b =  intval($bil_tag);  // genapkan value yg dibahagikan utk didarabkan 
	// $b = round($bil_tag, 0, PHP_ROUND_HALF_DOWN);  // genapkan value yg dibahagikan utk didarabkan 
	 $last_tag = ($bil_tag - $b);	   // sekiranya masih ada baki utk keluarkn delivery tag yg last
	 
	 
	 $bil_tag2 = ($st_pack * $b);
	 
	 if($dl_qty < ($st_pack))
	 {
	 $bil_tag3 = ($dl_qty);
	 
	 }else{
	 $bil_tag3 =  ($dl_qty - $bil_tag2);  //quantity delivery tag yg last
      }
	// echo "last qty ".$last_tag;
	 
	/* $query_id2 = "SELECT MAX(tag_no) FROM delivery_tagasn";
     $result_id2 = mysqli_query($dbc, $query_id2);
	 $row_id2 = mysqli_fetch_row($result_id2);
	 
	 $tag_no = ($row_id[1] + 1);
	 echo $tag_no;   */
	 
	// echo "B  : ".$b;
	 
	 if($b <= 1)
	 {
	  $no_tg = 1;
	  }elseif($last_tag == 0)
	  {
	   $no_tg = $b;
	  }else{
	  
	  $no_tg = ($b + 1);
	  
	  }
	  
	$w = 1;
		   
     for($m=1; $m <= $bil_tag; $m++)
	 { 
	
	 
$query_tag3 = "INSERT INTO print_tag_backflush(id_tag,tag_no,id_tran,bflush_no,plan_no,rev_plan_no,material_no, material_desc,tag_qty, shift_tag,ploc,station_loc,posting_date,posting_time,user_create,date_create,status_tag,status_print,month_plan,year_plan,slip_no,total_slip)  VALUES('','','".db_esc($dbc, $row["id"])."','".db_esc($dbc, $row["bflush_no"])."','".db_esc($dbc, $row["plan_no"])."','','".db_esc($dbc, $row["material_no"])."','".db_esc($dbc, $row["material_desc"])."','".db_esc($dbc, $st_pack)."','".db_esc($dbc, $row["shift_posting"])."','".db_esc($dbc, $row["ploc"])."','".db_esc($dbc, $row["work_center"])."','".db_esc($dbc, $row["date_posting"])."','".db_esc($dbc, $row["time_posting"])."','".db_esc($dbc, $username)."',NOW(),'N','N','".db_esc($dbc, $row["month_plan"])."',NOW(),'".db_esc($dbc, $w)."','".db_esc($dbc, $no_tg)."')";
	   $result_tag3 = mysqli_query($dbc, $query_tag3);
	   
	     $tag_no = ($row["bflush_no"].'/'.$w.'/'.$data_pack["std_package"].'/'.$no_tg);
		
		 
		 $query_tag3_t = "UPDATE print_tag_backflush SET tag_no = '".db_esc($dbc, $tag_no)."' WHERE id_tag = '".mysqli_insert_id($dbc)."' AND bflush_no = '".db_esc($dbc, $ref)."'";
	     $result_tag3_t = mysqli_query($dbc, $query_tag3_t);
	   
     $w++; 
	 
	 } // end for loop
	 
	  if(($last_tag > 0.00) || ($dl_qty < ($st_pack))){	   // kalau qty lebih kecil drpd std packaging and baki drpd bahagi tag
	 
	 $query_tag2 = "INSERT INTO print_tag_backflush(id_tag,tag_no,id_tran,bflush_no,plan_no,rev_plan_no,material_no, material_desc,tag_qty, shift_tag,ploc,station_loc,posting_date,posting_time,user_create,date_create,status_tag,status_print,month_plan,year_plan,slip_no,total_slip)  VALUES('','','".db_esc($dbc, $row["id"])."','".db_esc($dbc, $row["bflush_no"])."','".db_esc($dbc, $row["plan_no"])."','','".db_esc($dbc, $row["material_no"])."','".db_esc($dbc, $row["material_desc"])."','".db_esc($dbc, $bil_tag3)."','".db_esc($dbc, $row["shift_posting"])."','".db_esc($dbc, $row["ploc"])."','".db_esc($dbc, $row["work_center"])."','".db_esc($dbc, $row["date_posting"])."','".db_esc($dbc, $row["time_posting"])."','".db_esc($dbc, $username)."',NOW(),'N','N','".db_esc($dbc, $row["month_plan"])."',NOW(),'".db_esc($dbc, $w)."','".db_esc($dbc, $no_tg)."')"; 
	   $result_tag2 = mysqli_query($dbc, $query_tag2);
	   

       
	     $tag_no = ($row["bflush_no"].'/'.$w.'/'.$bil_tag3.'/'.($b + 1));
		 
		 $query_tag2_t = "UPDATE print_tag_backflush SET tag_no = '".db_esc($dbc, $tag_no)."' WHERE id_tag = '".mysqli_insert_id($dbc)."' AND bflush_no = '".db_esc($dbc, $ref)."'";
	     $result_tag2_t = mysqli_query($dbc, $query_tag2_t);
	   
	   
	   
	   
		 }// end if
	
	 
	  }// end while loop	
	 	
	
	
 //------- crete text file to SAP [FromPortal] -----------
 
 
	      //if($result_data2)
		 // {
			  
			  
  //update count_max----------------------------------------
		
	
       $query_max_a = "UPDATE run_count_no SET count_max = '".$number."', date_updated = NOW() WHERE uid = '19'";
	   $result_max_a = mysqli_query($dbc, $query_max_a);
	 
   //end update count_max ---------------------------------	
			  
			  
	
	       echo "<script>";
		   echo "alert('Backflush Document No : $ref');";
		   echo "window.location='ftp_bflush_SAP.php?buid=$ref&&uid=$buid'";
	       echo "</script>"; 
		   exit(); //quit the script
	  
		 // }
	  	   
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

       $qty_NG = $_POST["qty_NG"];
       $date2 = $_POST["date2"];
       $time3 = $_POST["time3"]; 
       $time4 = $_POST["time4"];
	   $type_reject = $_POST["type_reject"];
	   $reason_reject = $_POST["reason_reject"];
	   
	  $date_arini = date('Y-m-d'); 
    $current_date = date('Y-m-d H:i:s'); 
	  $next_date = date('Y-m-d H:i:s', strtotime($current_date .' +2 day'));
	  $next_date2 = date('Y-m-d', strtotime($date_arini .' +1 day'));
	  
	    $ddF2 = substr($_POST["date2"],0,2);
      $mmF2 = substr($_POST["date2"],3,2);
      $yyF2 = substr($_POST["date2"],6,4);
   
        $date1_final2 = ($yyF2.'-'.$mmF2.'-'.$ddF2);
 
     //check only deilvery date
	 
					  
				 $date_date2 = (($_POST["date2"])." ".($_POST["time3"]).":".($_POST["time4"]).":00");
						  
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
		     
	   if($qty_NG && $time3 && $time4 && $date2 && $type_reject && $reason_reject)
	   {
		   
       $qty_NG = $_POST["qty_NG"];
       $date2 = $_POST["date2"];
       $time3 = $_POST["time3"]; 
       $time4 = $_POST["time4"];
	   $type_reject = $_POST["type_reject"];
	   $reason_reject = $_POST["reason_reject"];		   

	$t_time2 = (($_POST["time3"]).":".($_POST["time4"]));  
	
	$ref = "";
	$type_reject = $_POST["type_reject"];
	$reason_reject = $_POST["reason_reject"];
	
	 //--------- generate backflush no ---------------
	
	$query_id = "SELECT * FROM run_count_no WHERE uid = '21'";
	$result_id = mysqli_query($dbc, $query_id);
	
	if ($result_id) 
{
	$nrows = mysqli_num_rows($result_id);
	$row_id = mysqli_fetch_array($result_id);
	
	$dht = 0000000; 
	$dht_OK = "22221";
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
    $number = sprintf('%07d',$number);  
	
	
	  $ref = ($dht_OK.($number));
	
	// $ref = (($data_setup["comp_code"]).$dht_OK.($number));
	
	} // end if $result_id
  
  
	 //--------- pps detail ------------
	 
	   $query_pps = "SELECT * FROM pps_detail WHERE plan_no = '".db_esc($dbc, $data_scan["plan_no"])."'";
	   $result_pps = mysqli_query($dbc, $query_pps);
	   $data_pps = mysqli_fetch_array($result_pps);
	   
	  //----------find posting log depend material type
	  	 
		$ploc = "P1RJ";  

	  
	 //insert into table pps_detail_transaction-------------
	
$query_data2 = "INSERT INTO pps_detail_transaction (id, pps_id, ref_id, bflush_no, plan_no, id_scan, upload_id, model_code, month_plan, material_no, material_desc, material_type, qty_plan, qty_actual, qty_balance, qty_NG, status_pps, comp_code, work_center, shift_pps1, shift_pps2, date_plan, status, user_upload, date_upload,user_create, date_create, user_update, date_update, user_posting, date_posting, time_posting, ploc, delivery_loc, type_reject, reason_reject, user_reject, date_reject, time_reject, status_ftp_bflush, bflush_no_ref, user_cancel, date_cancel,remark_cancel,plant_code,shift_posting) VALUES('','".db_esc($dbc, $data_pps["id"])."','".db_esc($dbc, $data_pps["ref_id"])."','".db_esc($dbc, $ref)."','".db_esc($dbc, $data_scan["plan_no"])."','".db_esc($dbc, $data_scan["id_scan"])."','".db_esc($dbc, $data_pps["upload_id"])."','".db_esc($dbc, $data_pps["model_code"])."','".db_esc($dbc, $data_pps["month_plan"])."','".db_esc($dbc, $data_pps["material_no"])."', '".db_esc($dbc, $data_scan["material_desc"])."','".db_esc($dbc, $data_scan["material_type"])."','".db_esc($dbc, $data_pps["qty_plan"])."','','','".db_esc($dbc, $qty_NG)."','".db_esc($dbc, $rst_sta7["status_desc"])."', '".db_esc($dbc, $data_scan["scan_plant"])."', '".db_esc($dbc, $data_scan["work_center"])."', '".db_esc($dbc, $data_pps["shift_pps1"])."','".db_esc($dbc, $data_pps["shift_pps2"])."','".db_esc($dbc, $data_pps["date_plan"])."','N','".db_esc($dbc, $data_pps["user_upload"])."','".db_esc($dbc, $data_pps["date_upload"])."','".db_esc($dbc, $username)."',NOW(),'','','".db_esc($dbc, $username)."','".db_esc($dbc, $_POST["date2"])."','".db_esc($dbc, $t_time2)."','".db_esc($dbc, $ploc)."','','".db_esc($dbc, $type_reject)."',' ".db_esc($dbc, $reason_reject)."','".db_esc($dbc, $username)."',NOW(),NOW(),'Y','','','','','".db_esc($dbc, $data_scan["scan_plant"])."','')";
$result_data2 = mysqli_query($dbc, $query_data2) or die(db_fail($dbc));
 
  

	   
 //-------------------update---------------------
  
    $query_all_info = "SELECT * FROM pps_detail_transaction WHERE id = '".mysqli_insert_id($dbc)."'";
	  $result_all_info = mysqli_query($dbc, $query_all_info);
	  $data_all_info = mysqli_fetch_array($result_all_info); 

    //---shift detail ------
	
$query_sht = "SELECT * FROM shift_detail WHERE id_shift = '1'";
$result_sht = mysqli_query($dbc, $query_sht);
$data_sht = mysqli_fetch_array($result_sht); 

//----shift posting ----

if(($data_all_info["time_posting"] >= $data_sht["time_start"]) && ($data_all_info["time_posting"] <= $data_sht["time_end"]))
{
 
 $shif_p = "D/S"; 

}else
{

$shif_p = "N/S"; 

}


//---------update shift posting ---------------
$query_upd_detail2 = "UPDATE pps_detail_transaction SET shift_posting = '".db_esc($dbc, $shif_p)."' WHERE id = '".db_esc($dbc, $data_all_info["id"])."'";
$result_upd_detail2 = mysqli_query($dbc, $query_upd_detail2);  


//----edit by azie 17 nov 2021 night shift ------	
  
  
$query_upd_shift = "SELECT * FROM pps_detail_transaction WHERE id = '".db_esc($dbc, $data_all_info["id"])."'";
$result_upd_shift = mysqli_query($dbc, $query_upd_shift);
$row_upd_shift = mysqli_fetch_array($result_upd_shift);


if(($row_upd_shift["shift_posting"] == "N/S") && ($row_upd_shift["date_posting"] == $currentdate))
{

$prev_date = date('Y-m-d', strtotime($currentdate .' -1 day'));	

if(($row_upd_shift["time_posting"] > "21:00:00" ) && ($row_upd_shift["time_posting"] < "23:59:59" ))
{
  
}else{
  
$query_upd_shift2 = "UPDATE pps_detail_transaction SET date_posting = '".$prev_date."' WHERE id = '".db_esc($dbc, $row_upd_shift["id"])."'";
$result_upd_shift2 = mysqli_query($dbc, $query_upd_shift2);  	

}
}






		   
  //-------update status "Released" to "Inprogress" in table pps_detail	
	
	$query_upd_detail = "UPDATE pps_detail SET status_pps = '".db_esc($dbc, $rst_sta7["status_desc"])."' WHERE id = '".db_esc($dbc, $data_all_info["pps_id"])."'";
	$result_upd_detail = mysqli_query($dbc, $query_upd_detail) or die(db_fail($dbc));
	
 //------- crete text file to SAP [FolderPortal] -----------


 
	     // if($result_data2)
		 // {
			  
			  
  //update count_max----------------------------------------
		
	
       $query_max_b = "UPDATE run_count_no SET count_max = '".$number."', date_updated = NOW() WHERE uid = '21'";
	   $result_max_b = mysqli_query($dbc, $query_max_b);
	 
   //end update count_max ---------------------------------	

	
 	
	
	       echo "<script>";
		     echo "alert('Backflush NG Document No : $ref');";
		     echo "window.location='ftp_bflush_SAP_NG.php?buid=$ref&&uid=$buid'";
	       echo "</script>"; 
		     exit(); //quit the script
	  
		 // }
	  	   
	   }
	   
	// mysqli_close($dbc);  
	   
  //print the message if there is one.
if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}
?> 

<?php
  
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
		   //echo "alert('Please scan for next PPS');";
		   echo "window.location='confirm_backflush_tran.php'";
	       echo "</script>"; 
		   exit(); //quit the script


}

		 $no = 1; 
		  
		  ?>
      
          
         <form name="myformA" method="post" action="confirm_backflushProc.php?buid=<?php echo h($buid); ?>">
   <table width="100%" border="0" cellpadding="2">
     <tr>
       <td width="52%" height="234">
         <table width="95%" border="1" align="right" cellpadding="2" class="table-condensed">
           <tr>
             <th width="33%"><div align="left">Planned Order No.</div></th>
             <td width="5%"> :</td>
             <td width="62%"><?php echo h($data_scan["plan_no"]); ?></td>
             </tr>
           <tr>
             <th scope="row"><div align="left">Work Center</div></th>
             <td>:</td>
             <td><?php echo h($data_scan["work_center"]); ?></td>
             </tr>
           <tr>
             <th scope="row"><div align="left">Shift</div></th>
             <td>:</td>
             <td><?php echo h($data_scan["scan_shift"]); ?></td>
             </tr>
           <tr>
             <th scope="row"><div align="left">Planned Date</div></th>
             <td>:</td>
             <td><?php echo h($data_scan["scan_date_plan"]); ?></td>
             </tr>
           <tr>
             <th scope="row"><div align="left">Planned Quantity</div></th>
             <td>:</td>
             <td><?php echo h($data_scan["scan_qty"]); ?></td>
             </tr>
           <tr>
             <th scope="row"><div align="left">UOM</div></th>
             <td>:</td>
             <td><?php echo h($data_scan["scan_uom"]); ?></td>
             </tr>
           <tr>
             <th scope="row"><div align="left">Part No.</div></th>
             <td>:</td>
             <td><?php echo h($data_scan["material_no"]); ?></td>
             </tr>
           <tr>
             <th scope="row"><div align="left">Part Name</div></th>
             <td>:</td>
             <td><?php echo h($data_scan["material_desc"]); ?></td>
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
                 <td><p>Please enter backflush output quantity for OK</p>
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
                       <option value="<?php echo h($_POST["time1"]); ?>"><?php echo sprintf('%02d', $_POST["time1"]);	 ?></option>
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
                       <option value="<?php echo h($_POST["time2"]); ?>"><?php echo sprintf('%02d', $_POST["time2"]);	 ?></option>
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
                     <td><input name="qty_actual" type="number" min="1" value="<?php if(isset($_POST["qty_actual"])) { echo h($_POST["qty_actual"]); } ?>" /></td>
                     <td colspan="4"><input name="confirm1" type="submit" id="confirm1" value="CONFIRM" class="btn-mini btn-success" onClick="return confirm('Confirm to save OK request?');"></td>
                     </tr>
                   <tr>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                     <td colspan="5">&nbsp;</td>
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
               <td><p>Please enter backflush output quantity for NG</p>
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
                       <option value="<?php echo h($_POST["time3"]); ?>"><?php echo sprintf('%02d', $_POST["time3"]);	 ?></option>
                       <?php
	 }else{
	 ?>
                       <option value="<?php echo sprintf('%02d', date('H'));	 ?>" placeholder="HOURS"><?php echo sprintf('%02d', date('H'));	 ?></option>
                       <?php
	  }
	  
      for($b2 = 0; $b2 <= 23; $b2++): ?>
                       <option value="<?= $b2; ?>"> <?php echo sprintf('%02d', $b2); ?></option>
                       <?php endfor; ?>
                     </select>
                       :</td>
                     <td><select name="time4" id="time4">
                       <?php if($_POST["confirm2"] == true)  
		{  
		?>
                       <option value="<?php echo h($_POST["time4"]); ?>"><?php echo sprintf('%02d', $_POST["time4"]);	 ?></option>
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
                     <td><input name="qty_NG" type="number" min="1" value="<?php if(isset($_POST["qty_NG"])) { echo h($_POST["qty_NG"]); } ?>"  /></td>
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
                    <option value="<?php echo h($row_type["id_type"]); ?>"<?php if($row_type["id_type"] == $_POST["type_reject"]) echo "selected"; ?>> <?php echo h($row_type["type_desc"]); ?></option>
                     
                  <?php
				 }else{
				  
				  ?> 
                  <option value="<?php echo h($row_type["id_type"]); ?>"> <?php echo h($row_type["type_desc"]); ?></option>
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
                  <option value="NULL" placeholder="Select Reason of Reject"> -- Select Reason of Reject --</option>
                  <?php
	               $query_reason = "SELECT * FROM reason_ng_reject WHERE status_reject = 'Y' ORDER BY id_reject ASC";
                   $result_reason = mysqli_query($dbc, $query_reason);
  
                   while($row_reason = mysqli_fetch_array($result_reason)) 
			      {
					   if($_POST["confirm2"] == true)  
		         {   ?>
                    <option value="<?php echo h($row_reason["id_reject"]); ?>"<?php if($row_reason["id_reject"] == $_POST["reason_reject"]) echo "selected"; ?>> <?php echo h($row_reason["reject_desc"]); ?></option>
                     
                  <?php
				 }else{
				  
				  ?> 
		    <option value="<?php echo h($row_reason["id_reject"]); ?>"> <?php echo h($row_reason["reject_desc"]); ?></option>
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
                     <td colspan="4"><input name="confirm2" type="submit" id="confirm2" value="CONFIRM" class="btn-mini btn-danger" onClick="return confirm('Confirm to save NG request?');"></td>
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
           <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="save_btn" type="submit" id="submit" value="NEXT" class="btn btn-warning" >
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
