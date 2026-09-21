<?php

/**
 * prod/technical_complete_tran.php
 * Part of: Production module
 * Filename suggests: technical complete tran
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, factory_detail, pps_detail, plan_cat_pps, pps_detail_transaction, pps_detail_close, qqc_detail_transaction, qqc_transaction.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_production_menu.php, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
error_reporting(E_ALL &~ E_NOTICE &~ E_DEPRECATED);
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
require_once '../include/auth.php';
require_role($dbc, 2);
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");


$Cdate = date ("l, j F Y ");
set_time_limit(0);

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$url = "technical_complete_tran.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
    $result2 = mysqli_query($dbc, $query2) or die(db_fail($dbc));
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

//CR status (Cancelled)
$sta4 = "SELECT * from request_status WHERE status_id = '4' ";
$sta_res4 = mysqli_query($dbc, $sta4);
$rst_sta4 = mysqli_fetch_array($sta_res4);	

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7' ";
$sta_res7 = mysqli_query($dbc, $sta7);
$rst_sta7 = mysqli_fetch_array($sta_res7);	

//CR status (Closed)
$sta13 = "SELECT * from request_status WHERE status_id = '13' ";
$sta_res13 = mysqli_query($dbc, $sta13);
$rst_sta13 = mysqli_fetch_array($sta_res13);

//CR status (Completed)
$sta14 = "SELECT * from request_status WHERE status_id = '14' ";
$sta_res14 = mysqli_query($dbc, $sta14);
$rst_sta14 = mysqli_fetch_array($sta_res14);

//CR status (Transfer QC)
$sta18 = "SELECT * from request_status WHERE status_id = '18'";
$sta_res18 = mysqli_query($dbc, $sta18);
$rst_sta18 = mysqli_fetch_array($sta_res18);	

//CR status (Approved QC)
$sta17 = "SELECT * from request_status WHERE status_id = '17'";
$sta_res17 = mysqli_query($dbc, $sta17);
$rst_sta17 = mysqli_fetch_array($sta_res17);	
		
	
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
	    $amount .= $_POST["remark_closed"][$i]."|";
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
<?php include "left_production_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_production.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a><a href="#" class="#">Production</a> <a href="#" class="current">Technical Complete</a></div>
  <h1>Technical Complete</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
    <!--  <div class="span12">-->
        <!--<div class="widget-box">
          <div class="widget-title">
             <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li><a  role="tab" href="upload_pps_month.php">New Request</a></li>
              <li class="active"><a role="tab" href="display_pps_month_reprint.php">Searching Record</a></li>
             </ul>
          </div>
          </div>-->
          
          
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmSearch" id="frmSearch">
            <table class="table table-bordered table-striped">
            <tr>
              <th>Planned Start Date :</th>
              <td><?php
    
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
										$myCalendar->setDate(0,0,0);
										$myCalendar->setPath("/calendar/");
										$myCalendar->setYearInterval(2010, 2030);
										// $myCalendar->setOnChange("myChanged('test')");
										$myCalendar->writeScript();
				
									}
			 ?></td>
              <th>Planned End Date :</th>
              <td><?php
                
               if(isset($_POST['date2']))
								{ 
									
									//GET value
									$dd2 = substr($_POST['date2'],8,2);
									$mm2 = substr($_POST['date2'],5,2);
									$yy2 = substr($_POST['date2'],0,4);
									
									$myCalendar = new tc_calendar("date2", true, false);
									$myCalendar->setIcon("../calendar/images/iconCalendar.gif");
									$myCalendar->setDate($dd2, $mm2, $yy2);
									$myCalendar->setPath("/calendar/");
									$myCalendar->setYearInterval(2010, 2030);
									// $myCalendar->setOnChange("myChanged('test')");
									$myCalendar->writeScript();
									
								}
								else	 
								{
                                      
										$dt2 = $today['mday'];
										$mt2 = $today['mon'];
										$yr2 = $today['year'];
									 
										$myCalendar = new tc_calendar("date2", true, false);
										$myCalendar->setIcon("../calendar/images/iconCalendar.gif");
								        $myCalendar->setDate(0,0,0);
										$myCalendar->setPath("/calendar/");
										$myCalendar->setYearInterval(2010, 2030);
										// $myCalendar->setOnChange("myChanged('test')");
										$myCalendar->writeScript();
				
									}
                
                ?></td>
            </tr>
          
            <tr>
              <th>Factory : </th>
              <td><select name="factory" id="factory" onChange="getFactory(this.value)">
                <option value="NULL" placeholder="Select Factory"> -- Select Factory --</option>
                <?php
	       $query3 = "SELECT * FROM factory_detail GROUP BY factory_desc2 ORDER BY id_fac ASC";
                   $result3 = mysqli_query($dbc, $query3);
  
                   while($row3=mysqli_fetch_array($result3, MYSQLI_NUM)) 
			      {
                  echo'<option value="',$row3[2],'">',stripslashes($row3[1]),'</option>';
                  }
				?>
              </select></td>
              <th>Work Center :</th>
              <td><div id="work_centerdiv"> 
               <select name="work_center" id="work_center" class="span11">
                <option value="NULL" placeholder="Select Work Center"> -- Select Work Center --</option>
                </select></div></td>
            </tr>
              <tr>
              <th>Planned Order No. :</th>
              <td><select name="plan_no" id="plan_no" class="span11">
                  <option value="NULL" placeholder="Select Planned Order No."> -- Select Planned Order No. --</option>
                  <?php
	             $query9 = "SELECT * FROM pps_detail WHERE status_pps = '".db_esc($dbc, $rst_sta7["status_desc"])."' ORDER BY plan_no ASC";
                   $result9 = mysqli_query($dbc, $query9);
  
                   while($row9=mysqli_fetch_array($result9)) 
			      {
				   ?>
                  <option value="<?php echo $row9["plan_no"]; ?>"> <?php echo $row9["plan_no"]; ?></option>
                  <?php
                  }
				?>
              </select></td>
              <th>Shift :</th>
              <td><select name="shift_ops" id="shift_ops" class="span11">
                  <option value="NULL" placeholder="Select Shift"> -- Select Shift --</option>
                  <option value="D/S">D/S</option>
                  <option value="N/S">N/S</option>
                  </select></td>
            </tr>
            <tr> 
            <th>Plan Category :</th>
              <td><select name="plan_category" id="plan_category">
                  <option value="NULL" placeholder="Select Plan Category"> -- Select Plan Category --</option>
                  <?php
	               $query29 = "SELECT * FROM plan_cat_pps ORDER BY id_plan ASC";
                   $result29 = mysqli_query($dbc, $query29);
  
                   while($row29=mysqli_fetch_array($result29)) 
			      {
				   ?>
                     <option value="<?php echo $row29["id_plan"]; ?>"> <?php echo $row29["plan_category_desc"]; ?></option>
                 
                  <?php
                  }
				?>
              </select></td>
    
              <th>Status :</th>
              <td><select name="status" id="status" class="span11">
                  <option value="NULL" placeholder="Select Status"> -- Select Status --</option>
                  <option value="<?php echo $rst_sta2["status_desc"]; ?>"><?php echo $rst_sta2["status_desc"]; ?></option>
                  <option value="<?php echo $rst_sta7["status_desc"]; ?>"><?php echo $rst_sta7["status_desc"]; ?></option>
                  </select></td>
                     </tr>
            <tr>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
            </tr>
            <tr>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th><input name="Submit2" type="submit"  class="btn btn-info" id="button" value="SEARCH" /></th>
            </tr>
            </table>
        </form>
<?php
        if(isset($_POST["Submit2"]))
        {
            
            $dateF = $_POST["date1"];
            $dateT = $_POST["date2"];
			$factory = $_POST["factory"];
			$work_center = $_POST["work_center"];
			$plan_no = $_POST["plan_no"]; 
			$shift_ops = $_POST["shift_ops"]; 
			$plan_category = $_POST["plan_category"]; 
			$status = $_POST["status"]; 
			
						
            echo "<script>";
echo "window.location='technical_complete_tran2.php?date1=$dateF&&date2=$dateT&&factory=$factory&&work_center=$work_center&&plan_no=$plan_no&&shift_ops=$shift_ops&&plan_category=$plan_category&&status=$status'";
            echo "</script>";
            exit(); //quit the script
        }
		
		//-----------------------------------------------------------------
		
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
 
// $i = 0;
// $size2 = count($_POST["id"]) + 1;
// $cancel = $_POST["cancel"]; 
 
  if(isset($_POST["cancel"])) 
  {
 
    $cancel = $_POST["cancel"]; 
    $how_many = count($cancel); 
	$remark_closed = $_POST["remark_closed"]; 
	$string = "";
	
	     foreach($_POST["cancel"] as $j=>$i) {
	    
		$amount .= $_POST["remark_closed"][$i];
	    $string = explode("|",($amount));	
			}
	

	    
		   for ($i=0; $i<$how_many; $i++) { 
		
	
		//echo ($i+1).'-'.$cancel[$i]; echo $string[$i]; echo "</br>";
		
		
		//update table pps_detail
		
		$query_close = "UPDATE pps_detail SET status_pps = '".db_esc($dbc, $rst_sta18["status_desc"])."', user_update = '".db_esc($dbc, $username)."', date_update = NOW(), user_closed = '".db_esc($dbc, $username)."', date_closed = NOW() WHERE id = '".db_esc($dbc, $cancel[$i])."'";
        $result_close = mysqli_query($dbc, $query_close) or die(db_fail($dbc));
		
		//update table pps_detail_transaction
		/*$query_close2 = "UPDATE pps_detail_transaction SET status_pps = '".$rst_sta14["status_desc"]."' WHERE pps_id = '$cancel[$i]'";
        $result_close2 = mysqli_query($dbc, $query_close2) or die(db_fail($dbc));*/
		
		//insert table pps_detail_close
		
		$query_info = "SELECT * FROM pps_detail WHERE id = '".db_esc($dbc, $cancel[$i])."'";
		$result_info = mysqli_query($dbc, $query_info);
		$row_info = mysqli_fetch_array($result_info);
		
	   
    $query_pps_closed = "INSERT INTO pps_detail_close(id_closing, id, ref_id, plan_no, upload_id, model_code, month_plan, material_no, qty_plan, qty_actual, status_pps, comp_code, work_center, shift_pps1, shift_pps2, date_plan, status, user_upload, date_upload, user_create, date_create, user_update, date_update, user_posting, date_posting, user_closed, date_closed, plan_category, id_factory_pps, rev_pps, seq_pps, man_hours, work_hours, remark_closed, type_closed, remark_closed_plan) VALUES('','".db_esc($dbc, $row_info["id"])."','".db_esc($dbc, $row_info["ref_id"])."','".db_esc($dbc, $row_info['plan_no'])."','".db_esc($dbc, $row_info["upload_id"])."','".db_esc($dbc, $row_info["model_code"])."','".db_esc($dbc, $row_info["month_plan"])."','".db_esc($dbc, $row_info["material_no"])."','".db_esc($dbc, $row_info["qty_plan"])."','".db_esc($dbc, $row_info["qty_actual"])."','".db_esc($dbc, $row_info["status_pps"])."','".db_esc($dbc, $row_info["comp_code"])."','".db_esc($dbc, $row_info["work_center"])."','".db_esc($dbc, $row_info["shift_pps1"])."','".db_esc($dbc, $row_info["shift_pps2"])."','".db_esc($dbc, $row_info["date_plan"])."','".db_esc($dbc, $row_info["status"])."','".db_esc($dbc, $row_info["user_upload"])."','".db_esc($dbc, $row_info["date_upload"])."','".db_esc($dbc, $row_info["user_create"])."','".db_esc($dbc, $row_info["date_create"])."','".db_esc($dbc, $row_info["user_update"])."','".db_esc($dbc, $row_info["date_update"])."','".db_esc($dbc, $row_info["user_posting"])."','".db_esc($dbc, $row_info["date_posting"])."','".db_esc($dbc, $row_info["user_closed"])."','".db_esc($dbc, $row_info["date_closed"])."','".db_esc($dbc, $row_info["plan_category"])."','".db_esc($dbc, $row_info["id_factory_pps"])."','".db_esc($dbc, $row_info["rev_pps"])."','".db_esc($dbc, $row_info["seq_pps"])."','".db_esc($dbc, $row_info["man_hours"])."','".db_esc($dbc, $row_info["work_hours"])."','".db_esc($dbc, $string[$i])."','','')"; 
     $rst_pps_closed = mysqli_query($dbc, $query_pps_closed);
     
	 		
		  
			}// end for loop
			
   

  }// end if
   else{
	   
	       echo "<script>";
		   echo "alert('Please tick the check box for proceed the transaction.');";
		   echo "window.location='technical_complete_tran.php'";
		   echo "</script>"; 
		   exit(); //quit the script
	   
   }

}	
        
    	?>

          
           <?php

 //------range date for 7 days----------------------------
 $start_date_check = date('Y-m-d', strtotime("-7 days"));
 $end_date_check = date('Y-m-d', strtotime("0 days"));
 
// echo $start_date_check;

								 
   $query8 = "SELECT COUNT(*) FROM pps_detail AS PD WHERE (PD.status_pps = '".db_esc($dbc, $rst_sta2["status_desc"])."' OR PD.status_pps = '".db_esc($dbc, $rst_sta7["status_desc"])."') AND PD.status = 'Y' AND (PD.date_posting >= '$start_date_check' AND PD.date_posting <= '$end_date_check')";
   $result8 = mysqli_query($dbc, $query8) or die(db_fail($dbc));
   $num_rows = mysqli_fetch_row($result8);

   $pages = new Paginator;
   $pages->items_total = $num_rows[0];
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT *, DATE_FORMAT(PD.date_plan,'%d-%m-%Y') as R, DATE_FORMAT(PD.date_posting,'%d-%m-%Y') as R2, DATE_FORMAT(PD.date_create,'%d-%m-%Y') as R3 FROM pps_detail AS PD WHERE (PD.status_pps = '".db_esc($dbc, $rst_sta2["status_desc"])."' OR PD.status_pps = '".db_esc($dbc, $rst_sta7["status_desc"])."') AND PD.status = 'Y' AND (PD.date_posting >= '$start_date_check' AND PD.date_posting <= '$end_date_check') ORDER BY PD.plan_no ASC";
$rs = mysqli_query($dbc, $query);   //run the query.
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
          
      <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display Request</h5>
          </div>
             
          <div class="widget-content nopadding">
         <form name="myform" method="post" action="technical_complete_tran.php">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                <th rowspan="3">No. </br> <input type="checkbox" name="chkDel" class="selectall"/> </br> </th>
                <th rowspan="3">Part No.</th>
                <th rowspan="3">Planned Order No.</th>
                <th rowspan="3">Planned Date</th>
                <th rowspan="3">Work Center</th>
                <th rowspan="3">Shift</th>
                <th rowspan="3">Planned Order Status</th>
                <th colspan="3">Prod</th>
                <th>&nbsp;</th>
                <th colspan="3">QC</th>
                <th rowspan="3">Remark</th>
                </tr>
                <tr>
                  <th rowspan="2">Plan Qty</th>
                  <th rowspan="2">OK Qty</th>
                  <th rowspan="2">NG Qty</th>
                  <th rowspan="2">RCV OK</th>
                  <th colspan="3">REWORK</th>
                 </tr>
                <tr>
                  <th rowspan="3">PEN</th>
                  <th rowspan="3">QC OK</th>
                  <th rowspan="3">QC Reject</th>
                </tr>
                 </thead>   
              <tbody>
           <?php
   
   $counter = 1;
   $no = 1;
   $sta_out = "";
   $k = 1;
   
   while ($row = mysqli_fetch_array($rs))
   {
 	   
		
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
	
	
	
	
   $qty_total_rec = 0.000;
   $total_qty_pending = 0.000;
   $qty_total_ok = 0.000;
   $qty_total_NG = 0.000;
   $total_qty = 0.000;	
   $qty_total_ok2 = 0.000;
   $qty_rev_ok2 = 0.000;
   $qty_total_NG2 = 0.000;
   
    //-----checking pps_trans entering output production
	   $query_plan_tran = "SELECT * FROM pps_detail_transaction WHERE plan_no = '".db_esc($dbc, $row["plan_no"])."' AND status_pps != '".db_esc($dbc, $rst_sta4["status_desc"])."'";
	   $result_plan_tran = mysqli_query($dbc, $query_plan_tran);
       
	   while($data_plan_tran = mysqli_fetch_array($result_plan_tran))
    {

  	 //-----checking QA/QC entering output production
	   $query_qqc = "SELECT * FROM qqc_detail_transaction WHERE bflush_no = '".db_esc($dbc, $data_plan_tran["bflush_no"])."' AND plan_no = '".db_esc($dbc, $data_plan_tran["plan_no"])."'";
	   $result_qqc = mysqli_query($dbc, $query_qqc);
	   $data_qqc = mysqli_fetch_array($result_qqc);  
	   
	   $qty_total_ok2 = ($qty_total_ok2 + $data_plan_tran["qty_actual"]);  
	   $qty_rev_ok2 = ($qty_rev_ok2 + $data_qqc["qty_qc"]);  
	   $qty_total_NG2 = ($qty_total_NG2 + $data_plan_tran["qty_NG"]);  
	   
	     
	   
	 
	   $query_qqc2 = "SELECT * FROM qqc_transaction WHERE bflush_no = '".db_esc($dbc, $data_qqc["bflush_no"])."' AND plan_no = '".db_esc($dbc, $data_qqc["plan_no"])."'";
	   $result_qqc2 = mysqli_query($dbc, $query_qqc2);
	
	  
	   while($data_qqc2 = mysqli_fetch_array($result_qqc2))
	   {
		 	  
		$qty_total_rec = ($qty_total_rec + $data_qqc2["qty_qc"]);  
		$qty_total_ok = ($qty_total_ok + $data_qqc2["qty_qc_ok"]);  
	    $qty_total_NG = ($qty_total_NG + $data_qqc2["qty_qc_NG"]);  
		
	  //------quantity qc pending checking OK ------------
		  
	$total_qty = ($data_qqc["qty_balance"]);   
	$total_qty_pending = ($total_qty - (($qty_total_ok) + ($qty_total_NG)));
   

    }	

	
	}
	 
      ?>
           
                <tr class="gradeX">
                <td width="30"><div align="center"><input type="checkbox" name="cancel[]" value="<?php echo $row["id"]; ?>" <?=was_checked($row["id"],$a) ?> /><input type="hidden" name="Check_ctr" value="yes" 
onClick="Check(document.myform.cancel)">  </div>    <?php echo $no; ?></td>
                <td width="48"><?php echo $row["material_no"]; ?></td>
                <td width="120"><?php echo $row["plan_no"]; ?></td>
                <td width="80"><?php echo $row["R"]; ?></td>
                <td width="60"><?php  echo $row["work_center"]; ?></td> 
                <td width="40"><?php echo $sta; ?></td>
                <td width="90"><div align="center"><?php echo $row["status_pps"]; ?></div></td>
                <td width="43"><div align="center"><?php echo number_format($row["qty_plan"]); ?></div></td>
                <td width="45">
                <div align="center">
				<?php if(($row["qty_plan"]) < ($qty_total_ok2)) {  ?>
                <font color="#FF0000"><?php echo number_format($qty_total_ok2); ?></font>
				<?php }elseif(($row["qty_plan"]) == ($qty_total_ok2)){ ?>
				 <font color="#00FF00"><?php echo number_format($qty_total_ok2); ?></font>
				 <?php }elseif(($row["qty_plan"]) > ($qty_total_ok2)){ ?>
				 <font color="#0000FF"><?php echo number_format($qty_total_ok2); ?></font>
                  <?php }else{ ?>
				 <font color="#CC9900"><?php echo number_format($qty_total_ok2); ?></font><?php }  ?>
                 </div></td>
                <td width="7"><div align="center"><?php echo number_format($qty_total_NG2); ?></div></td>
                <td width="7"><div align="center"><?php echo number_format($qty_rev_ok2); ?></div></td>
                <td width="8"><div align="center"><?php if ($total_qty_pending < 0){ echo "0"; } else{
					echo number_format($total_qty_pending); } ?></div></td>
                <td width="19"><div align="center"><?php echo number_format($qty_total_ok); ?></div></td>
                <td width="40"><div align="center"><?php echo number_format($qty_total_NG); ?></div></td>
           	    <td width="100"><textarea name="remark_closed[<?php echo $row["id"]; ?>]" id="textarea" rows="2" cols="10" maxlength="250" ><?php if (isset($_POST['remark_closed'][($row["id"])])) { echo $_POST['remark_closed'][($row["id"])]; } ?></textarea>
               <input name="id[<?php echo $k; ?>]" type="hidden" value="<?php echo $row["id"]; ?>">
          </td>
          
          
               <!-- <td width="80"><a value="View Details" href="display_plan_order_detail.php?uid=<?php echo $row["id"]; ?>&&TB_iframe=true&height=400&width=1000" class="thickbox" target="_self"><img src="../img/folder.png" width="16" height="16" alt="View Details"><br>View Details</a></td>-->
                </tr>
               <!--   <input name="uid" type="hidden" value="<?php echo $row["id"]; ?>">-->
          <?php 
		  
		  $no ++;
		  $counter++; // menambah counter
		  $k ++;
		  } 
		  ?>

         
 </tbody>
</table><table class="table">
  <tr>
    <td>&nbsp;  <input name="Submit3" type="submit"  class="btn btn-success" id="button" value="CLOSE PLANNED ORDER" /></td>
  </tr>
</table>

 
            
            </form>
          </div>
        
      </div>
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

<!--checkbox-->
<script type="text/javascript">
$('.selectall').click(function() {
    if ($(this).is(':checked')) {
        $('div input').attr('checked', true);
    } else {
        $('div input').attr('checked', false);
    }
});
</script>

</body>
</html>
