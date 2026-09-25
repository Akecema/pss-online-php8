<?php

/**
 * prod/display_cancel_list_backflush_tran2.php
 * Part of: Production module
 * Filename suggests: display cancel list backflush tran2
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, factory_detail, work_center_detail, pps_detail_transaction.
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
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");

$Cdate = date ("l, j F Y ");
set_time_limit(0);

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$url = "cancellation_list_backflush_tran.php";

    $query2 = "SELECT * FROM user_detail WHERE username = ?"; $query2_args = [$username];
    $result2 = db_query_bind($dbc, $query2, $query2_args) or die(db_fail($dbc));
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
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

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
<?php include "left_production_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_production.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a><a href="#" class="#">Production</a> <a href="#" class="current">Cancellation</a></div>
  <h1>Backflush Cancellation</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
    <!--  <div class="span12">-->
        <div class="widget-box">
          <div class="widget-title">
             <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li><a  role="tab" href="cancellation_list_backflush_tran.php">New Request</a></li>
              <li class="active"><a role="tab" href="display_cancel_list_backflush_tran.php">Display Request</a></li>
             </ul>
          </div>
          </div>
          
          
     <form action="<?php echo h($_SERVER['PHP_SELF']); ?>" method="get" name="frmSearch" id="frmSearch">
            <table class="table table-bordered table-striped">
            <tr>
              <th>Planned Start Date :</th>
              <td><?php
    
				      $dd1 = substr($_GET["date1"],8,2);
					  $mm1 = substr($_GET["date1"],5,2);
					  $yy1 = substr($_GET["date1"],0,4);
	
	
                      $myCalendar = new tc_calendar("date1", true, false);
					  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					  $myCalendar->setDate($dd1, $mm1, $yy1);
					  $myCalendar->setPath("/calendar/");
					  $myCalendar->setYearInterval(date('Y') - 1, date('Y') + 10);
					  // $myCalendar->setOnChange("myChanged('test')");
		    		  $myCalendar->writeScript();
			 ?></td>
              <th>Planned End Date :</th>
              <td><?php
                
                      $dd2 = substr($_GET['date2'],8,2);
				      $mm2 = substr($_GET['date2'],5,2);
				      $yy2 = substr($_GET['date2'],0,4);
				
                      $myCalendar = new tc_calendar("date2", true, false);
					  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					  $myCalendar->setDate($dd2, $mm2, $yy2);
					  $myCalendar->setPath("/calendar/");
					  $myCalendar->setYearInterval(date('Y') - 1, date('Y') + 10);
					  // $myCalendar->setOnChange("myChanged('test')");
					  $myCalendar->writeScript();
					  
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
				  
				  
				  ?>
                  <option value="<?php echo h($row3[2]); ?>" <?php if($row3[2] == $_GET["factory"]) echo "selected"; ?>> <?php echo h($row3[1]); ?></option>
                  <?php
                  }
				?>
              </select></td>
              <th>Work Center :</th>
              <td><div id="work_centerdiv"> 
               <select name="work_center" id="work_center" class="span11">
                <option value="NULL" placeholder="Select Work Center"> -- Select Work Center --</option>
                 <?php
	               $query5 = "SELECT * FROM work_center_detail WHERE id_factory = ? ORDER BY id_work ASC"; $query5_args = [$_GET["factory"]];
                   $result5 = db_query_bind($dbc, $query5, $query5_args);
  
                   while($row5=mysqli_fetch_array($result5)) 
				    { 
				   
				   ?>
                <option value="<?php echo h($row5["id_work"]); ?>" <?php if($row5["id_work"] == $_GET["work_center"]) echo "selected"; ?>> <?php echo h($row5["id_work"]),' - ',stripslashes($row5["wc_desc"]); ?></option>
                <?php
                  }
				?>
                </select></div></td>
            </tr>
              <tr>
              <th>Backflush Doc. No. :</th>
              <td><select name="bflush_no" id="bflush_no" class="span11">
                  <option value="NULL" placeholder="Select Backflush Doc. No."> -- Select Backflush Doc. No. --</option>
                  <?php
	        $query9 = "SELECT * FROM pps_detail_transaction WHERE status_pps = ? ORDER BY plan_no ASC"; $query9_args = [$rst_sta4["status_desc"]];
            $result9 = db_query_bind($dbc, $query9, $query9_args);
  
                   while($row9=mysqli_fetch_array($result9)) 
			      {
				   ?>
                  <option value="<?php echo h($row9["bflush_no"]); ?>"<?php if($row9["bflush_no"] == $_GET["bflush_no"]) echo "selected"; ?>> <?php echo h($row9["bflush_no"]); ?></option>
                  <?php
                  }
				?>
              </select>
              </td>
              <th>Shift :</th>
              <td><select name="shift_ops" id="shift_ops" class="span11">
                  <option value="NULL" placeholder="Select Shift"> -- Select Shift --</option>
                  <option value="D/S" <?php if($_GET["shift_ops"] == "D/S") { ?> selected="selected"<?php } ?>>D/S</option>
                  <option value="N/S" <?php if($_GET["shift_ops"] == "N/S") { ?> selected="selected"<?php } ?>>N/S</option>
                  </select></td>
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
            
			$dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			$factory = $_GET["factory"];
			$work_center = $_GET["work_center"];
			$bflush_no = $_GET["bflush_no"];
			$shift_ops = $_GET["shift_ops"];
	      	
			
			 //convert 
			
			$query_convert = "SELECT * FROM `work_center_detail` as SR WHERE SR.id_work = ?"; $query_convert_args = [$_GET["work_center"]];
			$result_convert = db_query_bind($dbc, $query_convert, $query_convert_args); 
			$row_convert = mysqli_fetch_array($result_convert);
			
		  
		
			//-------Count all results------------------------//
			
				 $where_sql = ''; $where_args = [];
		 
		 // 1. DateT
                if($dateT == "0000-00-00") {
                     $wheresql_01 = ""; $wheresql_01_args = []; }
                else {
                      $wheresql_01 = " AND (MR.date_plan <= ?)"; $wheresql_01_args = [$dateT]; }
		  //2. DateF 
                if ($dateF  == "0000-00-00" ){
                     $wheresql_02 = ""; $wheresql_02_args = [];}
                else {
                     $wheresql_02 = " AND (MR.date_plan >= ?)"; $wheresql_02_args = [$dateF];}  
					 
		 //3. Factory 
                if ($factory == "NULL"){ 
                    $wheresql_03 = ""; $wheresql_03_args = []; }
                else {
                    $wheresql_03 = " AND SR.id_factory = ?"; $wheresql_03_args = [$factory]; } 
					
          //4. Work Center
                if ($work_center == "NULL" ){
                    $wheresql_04 = ""; $wheresql_04_args = []; }
                else {
					$wheresql_04 = " AND MR.work_center = ?"; $wheresql_04_args = [$work_center]; }
   
	       //5. Backflush Doc. No.
                if ($bflush_no == "NULL"){ 
                    $wheresql_05 = ""; $wheresql_05_args = []; }
                else {
                    $wheresql_05 = " AND MR.bflush_no = ?"; $wheresql_05_args = [$bflush_no]; }  	
					
		 
		  //6. Shift
                if ($shift_ops == "NULL"){ 
                    $wheresql_06 = ""; $wheresql_06_args = []; }
                else {
					// $wheresql_06 = ""; }
					
                    $wheresql_06 = " AND ((MR.shift_pps1 = ?) OR (MR.shift_pps2 = ?)) "; $wheresql_06_args = [$shift_ops, $shift_ops]; }  		 			
				
				$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04 .$wheresql_05 .$wheresql_06; $where_args = array_merge($wheresql_01_args, $wheresql_02_args, $wheresql_03_args, $wheresql_04_args, $wheresql_05_args, $wheresql_06_args);	
	
	//********** END CONDITION **************

								 
   $query8 = "SELECT COUNT(*) FROM pps_detail_transaction AS MR, work_center_detail AS SR WHERE SR.id_work = MR.work_center AND MR.status_pps = ?".$where_sql; $query8_args = [$rst_sta4["status_desc"], ...$where_args];
   $result8 = db_query_bind($dbc, $query8, $query8_args) or die(db_fail($dbc));
   $num_rows = mysqli_fetch_row($result8);

   $pages = new Paginator;
   $pages->items_total = $num_rows[0];
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT *, DATE_FORMAT(MR.date_plan,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2, DATE_FORMAT(MR.date_create,'%d-%m-%Y') as R3 FROM pps_detail_transaction AS MR, work_center_detail AS SR WHERE SR.id_work = MR.work_center AND MR.status_pps = ?".$where_sql." ORDER BY MR.plan_no ASC"; $query_args = [$rst_sta4["status_desc"], ...$where_args];
$rs = db_query_bind($dbc, $query, $query_args);   //run the query.
$num = mysqli_num_rows($rs);   //how many material are there?


	
	 if ($num > 0) {
	 
	 echo '<div align="center">There are currently  '. h($num_rows[0]).' record(s).</div>';
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
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                <th>No.</th>
                <th width="77">Part No.</th>
                <th>Planned Order No.</th>
                <th>Planned Date</th>
                <th>Work Center</th>
                <th>Shift</th>
                <th>To Location</th>
                <th>Document No.</th>
                <th>Cancellation Doc. No.</th>
                <th>Output Qty.</th>
                <th>Action</th>
                </tr>
              </thead>   
              <tbody>
           <?php
   
   $counter = 1;
   $no = 1;
   $sta_out = "";
   
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
	
	//quantity output
		if($row["qty_actual"] != "0.000")
	{
		$qty_final = $row["qty_actual"];
	}elseif($row["qty_NG"] != "0.000")
	{
		$qty_final = $row["qty_NG"];
	}else{
		$qty_final == " ";
	}
	
	//status output
	//----check string -----------
	
	$sta_out = substr($row["bflush_no"],4,1);
	
	if($sta_out == "1")
	{
		$status_output = "OK";
	}elseif($sta_out == "3")
	{
	    $status_output = "NG";
	}else{
	     $status_output = "";
	}
	 
      ?>
           
                <tr class="gradeX">
                <td width="30"><?php echo $no; ?></td>
                <td><?php echo h($row[9]); ?></td>
                <td width="80"><font color="#0000CC"><?php echo h($row[4]); ?></font></td>
                <td width="80"><?php echo h($row["R"]); ?></td> 
                <td width="60"><?php echo h($row[18]); ?></td>
                <td width="40"><?php echo $sta; ?></td>
                <td width="50"><?php echo h($row[32]); ?></td>
                <td width="90"><font color="#0000CC"><?php echo h($row[3]); ?></font></td>
                <td width="90"><font color="#FF0000"><?php echo h($row[40]); ?></font></td>
                <td width="60"><?php echo h($qty_final); ?></td>
                <td width="99"><a value="Cancellation Details" href="detail_cancel_backflush_tran_proc.php?uid=<?php echo h($row["id"]); ?>&&TB_iframe=true&height=400&width=1000" class="thickbox" target="_self"><img src="../img/icon_view.jpg" width="16" height="16" alt="Cancellation Details">View</a></td>
                </tr>
                
          <?php 
		  
		  $no ++;
		  $counter++; // menambah counter
		  } 
		  ?>
                
              
              </tbody>
            </table>
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
