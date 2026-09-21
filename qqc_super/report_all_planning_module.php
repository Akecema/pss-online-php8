<?php

/**
 * qqc_super/report_all_planning_module.php
 * Part of: QQC module (supervisor/admin tier)
 * Filename suggests: report all planning module
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, factory_detail, pps_detail, mat_master_header, level_detail, pps_detail_transaction, qqc_detail_transaction, qqc_transaction.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_qqc_super_menu.php, footer.php.
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
require_role($dbc, 11);
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");

$Cdate = date ("l, j F Y ");
set_time_limit(0);

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$url = "report_all_planning_module.php";

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

//CR status (Delete)
$sta16 = "SELECT * from request_status WHERE status_id = '16' ";
$sta_res16 = mysqli_query($dbc, $sta16);
$rst_sta16 = mysqli_fetch_array($sta_res16);
		
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
</head>
<body>

<!--Header-part-->
<div id="header">
  <h1>&nbsp;</h1>
</div>
<?php  include "top_modal_menu.php";   ?>
<!--close-Header-part--> 

<!--sidebar-menu-->
<?php include "left_qqc_super_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_qqc_super.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a><a href="#" class="#">QA/QC</a> <a href="#" class="current">Report Planned Order</a> </div>
  <h1>Report Planned Order</h1>
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
              <th>Date From :</th>
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
									$myCalendar->setYearInterval(date('Y') - 1, date('Y') + 10);
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
										$myCalendar->setYearInterval(date('Y') - 1, date('Y') + 10);
										// $myCalendar->setOnChange("myChanged('test')");
										$myCalendar->writeScript();
				
									}
			 ?></td>
              <th>Date To :</th>
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
									$myCalendar->setYearInterval(date('Y') - 1, date('Y') + 10);
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
										$myCalendar->setYearInterval(date('Y') - 1, date('Y') + 10);
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
	             $query9 = "SELECT * FROM pps_detail ORDER BY plan_no ASC";
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
              <th>Part No. :</th>
              <td><select name="material_no" id="material_no">
                  <option value="NULL" placeholder="Select Material No."> -- Select Material No. --</option>
                  <?php
	               $query10 = "SELECT * FROM mat_master_header WHERE status_BOM = 'Y' ORDER BY material_no ASC";
                   $result10 = mysqli_query($dbc, $query10);
  
                   while($row10 = mysqli_fetch_array($result10)) 
			      {
				   ?>
                  <option value="<?php echo $row10["material_no"]; ?>"> <?php echo $row10["material_no"]; ?></option>
                  <?php
                  }
				?>
              </select></td>
              <th>&nbsp;</th>
              <th></th>
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
			$material_no = $_POST["material_no"]; 
						
            echo "<script>";
echo "window.location='report_all_planning_module2.php?date1=$dateF&&date2=$dateT&&factory=$factory&&work_center=$work_center&&plan_no=$plan_no&&shift_ops=$shift_ops&&material_no=$material_no'";
            echo "</script>";
            exit(); //quit the script
        }
        
 
								 
   $query8 = "SELECT COUNT(*) FROM pps_detail WHERE (status_pps != '".db_esc($dbc, $rst_sta4["status_desc"])."' AND status_pps != '".db_esc($dbc, $rst_sta16["status_desc"])."')";
   $result8 = mysqli_query($dbc, $query8) or die(mysqli_error($dbc));
   $num_rows = mysqli_fetch_row($result8);

   $pages = new Paginator;
   $pages->items_total = $num_rows[0];
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') as R, DATE_FORMAT(date_posting,'%d-%m-%Y') as R2, DATE_FORMAT(date_posting,'%H:%i:%s') as T2 FROM pps_detail WHERE (status_pps != '".db_esc($dbc, $rst_sta4["status_desc"])."' AND status_pps != '".db_esc($dbc, $rst_sta16["status_desc"])."') ORDER BY plan_no ASC";
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
      <td width="7%"><a href="report_all_planning_module_download.php" ><img src="../img/dload_excel.jpg" width="48" height="48" title="Download" /></a></td>
     <td width="7%"><img src="../img/print2.jpg" width="48" height="48" onClick="window.print()" title="Print"/></td>
   
  </tr>
</table>        
          
      <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display Request</h5>
          </div>
             
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                <th>Planned date</th>
                <th>Planned Order No.</th>
                <th>Plan Quantity</th>
                <th>Mat. Type</th>
                <th>Part No.</th>
                <th>Work Center</th>
                <th>Posting Date</th>
                <th>Posting Time</th>
                <th>Storage Loc.</th>
                <th>Document No.</th>
                <th>Output Quantity</th> 
                <th>Output Status</th>
                </tr>
              </thead>   
              <tbody>
           <?php
   
   $counter = 1;
   $no = 1;
   
   while ($row = mysqli_fetch_array($rs))
   {
		
		
	
		if($row["shift_pps1"] != "")
	{
		$sta = "D/S";
		
	}elseif($row["shift_pps2"] != "")
	 {
		$sta = "N/S";
	 }else{
		 
		$sta = " ";
	 }	
		 
  $query4_p ="SELECT * from level_detail as LD, user_detail as SD where SD.level_id = LD.id_level and LD.id_level = '.".db_esc($dbc, $row[16]).".'";
  $result4_p = mysqli_query($dbc, $query4_p);
  $row4_p = mysqli_fetch_array($result4_p);
  
     //---------get material header---------
	    $query_mat_h = "SELECT * FROM mat_master_header AS HD, mat_master_detail AS AD WHERE HD.material_no = AD.material AND HD.material_no = '".db_esc($dbc, $row['material_no'])."'";
		$result_mat_h = mysqli_query($dbc, $query_mat_h);
		$data_mat_h = mysqli_fetch_array($result_mat_h);
		
	?>	
		        <tr class="gradeX">
                <td width="90"><?php echo $row["R"]; ?></td>
                <td width="100" height="28"><?php echo $row["plan_no"]; ?></td>
                <td width="60"><?php echo intval($row["qty_plan"]); ?></td> 
                <td width="60"><?php echo $data_mat_h["material_type"]; ?></td>
                <td width="100"><?php echo $row["material_no"]; ?></td>
               <!-- <td width="120"><?php //echo $data_mat_h["material_desc"]; ?></td>-->
                <td width="60"><?php echo $row["work_center"]; ?></td>
                <td width="60"><?php echo $row["R2"]; ?></td>
                <td width="60"><?php echo $row["T2"]; ?></td>
                <td width="60"><?php echo $data_mat_h["sloc"]; ?></td>
                <td width="60"><?php echo $row["plan_no"]; ?></td>
                <td width="60"><?php echo intval($row["qty_plan"]); ?></td>
                <td width="60"><?php echo $row["status_pps"]; ?></td>
                </tr>
		
<?php		

   $query_display = "SELECT *, DATE_FORMAT(MR.date_plan,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2, DATE_FORMAT(MR.date_create,'%d-%m-%Y') as R3 FROM pps_detail_transaction AS MR WHERE MR.pps_id = '".db_esc($dbc, $row["id"])."' ORDER BY plan_no ASC";
   $result_display = mysqli_query($dbc, $query_display);   //run the query.
   
   $no2 = 1;
   
   while ($row_display = mysqli_fetch_array($result_display))
   {
  
   //---------get material header---------
	    $query_mat_h = "SELECT * FROM mat_master_header AS HD, mat_master_detail AS AD WHERE HD.material_no = AD.material AND HD.material_no = '".db_esc($dbc, $row['material_no'])."'";
		$result_mat_h = mysqli_query($dbc, $query_mat_h);
		$data_mat_h = mysqli_fetch_array($result_mat_h);	  
		
		$query_display3 = "SELECT *, DATE_FORMAT(N.date_plan,'%d-%m-%Y') as B, DATE_FORMAT(N.date_qc_posting,'%d-%m-%Y') as B2, DATE_FORMAT(N.date_create,'%d-%m-%Y') as B3 FROM qqc_detail_transaction AS N WHERE N.bflush_no = '".db_esc($dbc, $row_display["bflush_no"])."' ORDER BY plan_no ASC";
		$result_display3 = mysqli_query($dbc, $query_display3);   //run the query.  
		
  		$query_display2 = "SELECT *, DATE_FORMAT(M.date_plan,'%d-%m-%Y') as J, DATE_FORMAT(M.date_qc_posting,'%d-%m-%Y') as J2, DATE_FORMAT(M.date_create,'%d-%m-%Y') as J3 FROM qqc_transaction AS M WHERE M.bflush_no = '".db_esc($dbc, $row_display["bflush_no"])."' ORDER BY plan_no ASC";
		$result_display2 = mysqli_query($dbc, $query_display2);   //run the query.


	//quantity output
		if($row_display["qty_actual"] != "0.000")
	{
		$qty_final = $row_display["qty_actual"];
	}elseif($row_display["qty_NG"] != "0.000")
	{
		$qty_final = $row_display["qty_NG"];
	}else{
		$qty_final == " ";
	}
	
	//status output
	//----check string -----------
	
	$sta_out = substr($row_display["bflush_no"],4,1);
	
	if($sta_out == "1")
	{
		$status_output = "OK";
	}elseif($sta_out == "3")
	{
	    $status_output = "NG";
	}else{
	     $status_output = "";
	}
	
//----------------------------------------------------------------------------    
            if ($row_display > 0)
            {
            
             ?>
                <tr class="gradeX">
                <td width="90"><?php echo $row_display["R"]; ?></td>
                <td width="100">&nbsp;<?php echo $row_display["plan_no"]; ?></td>  
                <td width="60">&nbsp;<?php echo intval($row["qty_plan"]); ?></td>
                <td width="60"><?php echo $data_mat_h["material_type"]; ?></td>
                <td width="100">&nbsp;<?php echo $row_display["material_no"]; ?></td>
               <!-- <td width="120">&nbsp;<?php //echo $data_mat_h["material_desc"]; ?></td>-->
                <td width="60">&nbsp;<?php echo strtoupper($row["work_center"]); ?></td>
                <td width="60"><?php echo $row_display["R2"]; ?></td> 
                <td width="60"><?php echo $row_display["time_posting"]; ?></td>
                <td width="60"><?php echo $row_display["ploc"]; ?></td>
                <td width="60"><font color="#0000CC"><?php echo $row_display["bflush_no"]; ?></font></td>
                <td width="60"><?php echo intval($qty_final); ?></td>
                <td width="60"><?php echo $status_output; ?><input name="uid" type="hidden" value="<?php echo $row_display["bflush_no"]; ?> "></td>
               </tr>  
	
	
       <?php  
	   $no3 = 1;
	    
           while ($row3 = mysqli_fetch_array($result_display3))
        {
	   
	   ?> 
     	<tr class="gradeX">
        <td width="90"><?php echo $row3["B"]; ?></td>
        <td width="100">&nbsp;<?php echo $row3["plan_no"]; ?></td> 
        <td width="60">&nbsp;<?php echo intval($row["qty_plan"]); ?></td>
        <td width="60"><?php echo $data_mat_h["material_type"]; ?></td>
        <td width="100">&nbsp;<?php echo $row3["material_no"]; ?></td>
        <!--  <td width="60">&nbsp;<?php //echo $data_mat_h["material_desc"]; ?></td> -->
        <td width="60">&nbsp;<?php echo strtoupper($row["work_center"]); ?></td>
        <td width="60"><?php echo $row3["B2"]; ?></td>
        <td width="60"><?php echo $row3["time_qc_posting"]; ?></td>
        <td width="60"><?php echo $row3["ploc_qc"]; ?></td>
        <td width="60"><font color="#0000CC"><?php echo $row3["bflush_no"]; ?></font>
            <br><?php echo $row3["qqc_doc_no"]; ?></td>
        <td width="60"><?php echo intval($row3["qty_balance"]); ?></td>
        <td width="60"><?php echo $row3["status_QC"]; ?></td>
        </tr>   
       
        <?php 
		
		$no3++;
		
           }  ?>
    
    
	<?php
	} // end $row_display
	
	 $no7 = "a"; 
     $sta_out2 = "";
		   
     while($row_rst_display2 = mysqli_fetch_array($result_display2))
   {
	   
//quantity output
		if($row_rst_display2["qty_qc_ok"] != "0.000")
	{
		$qty_final2 = $row_rst_display2["qty_qc_ok"];
	}elseif($row_rst_display2["qty_qc_NG"] != "0.000")
	{
		$qty_final2 = $row_rst_display2["qty_qc_NG"];
	}else{
		$qty_final2 == " ";
	}
	
     //status output
	//----check string -----------
	
	$sta_out2 = substr($row_rst_display2["qqc_no"],4,1);
	
	if($sta_out2 == "5")
	{
		$status_output2 = "QC OK";
	}elseif($sta_out2 == "7")
	{
	    $status_output2 = "QC NG";
	}else{
	     $status_output2 = "";
	}
	
	
	if ($row_rst_display2 > 0)
	{
	?>
	
 <tr class="gradeX">  
        <td width="90"><?php echo $row_rst_display2["J"]; ?></td>
        <td width="100">&nbsp;<?php echo $row_rst_display2["plan_no"]; ?></td>   
        <td width="60">&nbsp;<?php echo intval($row["qty_plan"]); ?></td>
        <td width="60"><?php echo $data_mat_h["material_type"]; ?></td>
        <td width="100">&nbsp;<?php echo $row_rst_display2["material_no"]; ?></td>
     	<!--    <td width="60">&nbsp;<?php //echo $data_mat_h["material_desc"]; ?></td>-->
        <td width="60">&nbsp;<?php echo strtoupper($row["work_center"]); ?></td>
        <td width="60"><?php echo $row_rst_display2["J2"]; ?></td>
        <td width="60"><?php echo $row_rst_display2["time_qc_posting"]; ?></td>
        <td width="60"><?php echo $row_rst_display2["ploc"]; ?></td>
	<!-- <td width="60"><?php //echo $row_rst_display2["ploc_qc"]; ?></td>--> 
        <td width="60"><font color="#669999"><?php echo $row_rst_display2["qqc_no"]; ?></font></td>
        <td width="60"><?php echo intval($qty_final2); ?></td>
        <td width="60"><?php echo $status_output2; ?></td>
      </tr> 
      
      <?php 
	      $no7 ++;
	   }// end $row_rst_display2
		//$no2++; 
	}	// while $row_rst_display2  
	$no ++;
	$counter++; // menambah counter 
		   
		   //}// end if

		
} // while $row_display  
              
?>
              
   
			
			
  <?php
			}  // end while loop $row2
    echo '</tbody>';
    echo '</table>';  	  
	
	mysqli_free_result($rs); 
?>
    
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
</body>
</html>
