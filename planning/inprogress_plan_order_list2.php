<?php

/**
 * planning/inprogress_plan_order_list2.php
 * Part of: Planning module
 * Filename suggests: inprogress plan order list2
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, work_center_detail, factory_detail, pps_detail, pps_detail_transaction, qqc_detail_transaction, qqc_transaction.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_planning_menu.php, footer.php.
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
$url = "inprogress_plan_order_list.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
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
.com_i {
	color: #FF0000;
}
</style>
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
<style>
.pagin {
  display: inline-block;
}

.pagin a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
  border: 1px solid #ddd;
}

.pagin a.active {
  background-color: #CCCCCC;
  color: white;
  border: 1px solid #CCCCCC;
}

.pagin a:hover:not(.active) {background-color: #ddd;}

.pagin a:first-child {
  border-top-left-radius: 5px;
  border-bottom-left-radius: 5px;
}

.pagin a:last-child {
  border-top-right-radius: 5px;
  border-bottom-right-radius: 5px;
}
</style>
</head>
<body>

<!--Header-part-->
<div id="header">
  <h1>&nbsp;</h1>
</div>
<?php  include "top_modal_menu.php";   ?>
<!--close-Header-part--> 

<!--sidebar-menu-->
<?php include "left_planning_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_planning.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a><a href="#" class="current">InProgress Planned Order</a> </div>
  <h1>InProgress Planned Order</h1>
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
    <?php
	
	
	
	
	        $dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			$factory = $_GET["factory"];
			$work_center = $_GET["work_center"];
			$plan_no = $_GET["plan_no"];
			$shift_ops = $_GET["shift_ops"];
	      	
			
			 //convert 
			
			$query_convert = "SELECT * FROM `work_center_detail` as SR WHERE SR.id_work = '".$_GET["work_center"]."'";
			$result_convert = mysqli_query($dbc, $query_convert); 
			$row_convert = mysqli_fetch_array($result_convert);
					
		?>       
          
     <form action="inprogress_plan_order_list2.php?date1=<?php echo $dateF; ?>&&date2=<?php echo $dateT; ?>&&factory=<?php echo $factory; ?>&&work_center=<?php echo $work_center; ?>&&plan_no=<?php echo $plan_no; ?>&&shift_ops=<?php echo $shift_ops; ?>" method="get" name="frmSearch" id="frmSearch">
             <table class="table table-bordered table-striped">
            <tr>
              <th>Plan Start Date : <span class="com_i">*</span></th>
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
              <th>Plan End Date : <span class="com_i">*</span></th>
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
  
                   while($row3=mysqli_fetch_array($result3)) 
			      {
				  
				  
				  ?>
                  <option value="<?php echo $row3["factory_desc2"]; ?>" <?php if($row3["factory_desc2"] == $_GET["factory"]) echo "selected"; ?>> <?php echo $row3["factory_desc"]; ?></option>
                  <?php
                  }
				?>
              </select></td>
              <th>Work Center :</th>
              <td><div id="work_centerdiv"> 
               <select name="work_center" id="work_center" class="span11">
                <option value="NULL" placeholder="Select Work Center"> -- Select Work Center --</option>
                 <?php
	               $query5 = "SELECT * FROM work_center_detail WHERE id_factory = '".$_GET["factory"]."' ORDER BY id_work ASC";
                   $result5 = mysqli_query($dbc, $query5);
  
                   while($row5=mysqli_fetch_array($result5)) 
				    { 
				   
				   ?>
                <option value="<?php echo $row5["id_work"]; ?>" <?php if($row5["id_work"] == $_GET["work_center"]) echo "selected"; ?>> <?php echo $row5["id_work"],' - ',stripslashes($row5["wc_desc"]); ?></option>
                <?php
                  }
				?>
                </select></div></td>
            </tr>
              <tr>
              <th>Planned Order No. :</th>
              <td><select name="plan_no" id="plan_no" class="span11">
                  <option value="NULL" placeholder="Select Planned Order No."> -- Select Planned Order No. --</option>
                  <?php
	        $query9 = "SELECT * FROM pps_detail WHERE status_pps = '".$rst_sta7["status_desc"]."' ORDER BY plan_no ASC";
            $result9 = mysqli_query($dbc, $query9);
  
                   while($row9=mysqli_fetch_array($result9)) 
			      {
				   ?>
                  <option value="<?php echo $row9["plan_no"]; ?>"<?php if($row9["plan_no"] == $_GET["plan_no"]) echo "selected"; ?>> <?php echo $row9["plan_no"]; ?></option>
                  <?php
                  }
				?>
              </select></td>
              <th>Shift :</th>
              <td><select name="shift_ops" id="shift_ops" class="span11">
                  <option value="NULL" placeholder="Select Shift"> -- Select Shift --</option>
                  <option value="D/S" <?php if($_GET["shift_ops"] == "D/S") { ?> selected="selected"<?php } ?>>D/S</option>
                  <option value="N/S" <?php if($_GET["shift_ops"] == "N/S") { ?> selected="selected"<?php } ?>>N/S</option>
                  </select></td>
            </tr>
            <tr>
              <th><span class="com_i">*</span> Compulsory&nbsp;</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th><input name="Submit2" type="submit"  class="btn btn-info" id="button" value="SEARCH" /></th>
            </tr>
            </table>
        </form>

     <?php

		
			//-------Count all results------------------------//
			
				 $where_sql = "";
		 
		 // 1. DateT
                if($dateT == "0000-00-00") {
                     $wheresql_01 = ""; }
                else {
                      $wheresql_01 = " AND (MR.date_plan <= '$dateT')"; }
		  //2. DateF 
                if ($dateF  == "0000-00-00" ){
                     $wheresql_02 = "";}
                else {
                     $wheresql_02 = " AND (MR.date_plan >= '$dateF')";}  
					 
		 //3. Factory 
                if ($factory == "NULL"){ 
                    $wheresql_03 = ""; }
                else {
                    $wheresql_03 = " AND SR.id_factory = '$factory'"; } 
					
          //4. Work Center
                if ($work_center == "NULL" ){
                    $wheresql_04 = ""; }
                else {
					$wheresql_04 = " AND MR.work_center = '$work_center'"; }
   
	       //5. Planned Order No.
                if ($plan_no == "NULL"){ 
                    $wheresql_05 = ""; }
                else {
                    $wheresql_05 = " AND MR.plan_no = '$plan_no'"; }  	
					
		 
		  //6. Shift
                if ($shift_ops == "NULL"){ 
                    $wheresql_06 = ""; }
                else {
					// $wheresql_06 = ""; }
					
                    $wheresql_06 = " AND ((MR.shift_pps1 = '$shift_ops') OR (MR.shift_pps2 = '$shift_ops')) "; }  		 			
				
				$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04 .$wheresql_05 .$wheresql_06;	
	
	//********** END CONDITION **************


    ini_set('display_errors', 1);
	error_reporting(~0);

	$strKeyword = null;

	if(isset($_POST["txtKeyword"]))
	{
		$strKeyword = $_POST["txtKeyword"];
	}
	if(isset($_GET["txtKeyword"]))
	{
		$strKeyword = $_GET["txtKeyword"];
	}
	


    //define how many result per pages
	$page  = 1;
	$per_page = 15; 
	$counter = 1;
	$sta_out = "";
	$no = 1;

   
   if(isset($_GET["Page"]))
	{
     $page = $_GET["Page"];
	// $no = (is_numeric($_GET["no"]) ? $_GET["no"] : 1);
	 
	}else {
     $page = 1;
	 $no = 1;  
    }

	$prev_page = $page-1;
	$next_page = $page+1;

  
    $query = "SELECT *, DATE_FORMAT(MR.date_plan,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2, DATE_FORMAT(MR.date_create,'%d-%m-%Y') as R3 FROM pps_detail_transaction AS MR, work_center_detail AS SR WHERE SR.id_work = MR.work_center AND MR.status_pps = '".$rst_sta7["status_desc"]."' AND MR.status = 'Y' AND ((MR.material_no LIKE '%".$strKeyword."%') OR (plan_no LIKE '%".$strKeyword."%')) ".$where_sql;
    $rs = mysqli_query($dbc, $query);   //run the query.
	$num_rows = mysqli_num_rows($rs);   //how many material are there?

//$row_start = (($page - 1)* $per_page);
	
	$row_start = (($per_page * $page) - ($per_page));
	
	if($num_rows<=$per_page)
	{
		$num_pages = 1;
	}
	elseif(($num_rows % $per_page) == 0)
	{
		$num_pages = (($num_rows) / ($per_page));
	}
	else
	{
		$num_pages = (($num_rows) / ($per_page) + 1);
		$num_pages = (int)$num_pages;
	}
	
	$row_end = ($per_page * $page);
	
	if($row_end > $num_rows)
	{
		$row_end = $num_rows;
	}
    
	//$query .= "ORDER BY material_no ASC LIMIT ".$row_start.','.$row_end; //silap
	$query .= "ORDER BY MR.plan_no ASC LIMIT  ".$row_start.','.$per_page;
	$rs = mysqli_query($dbc, $query);


    $num = mysqli_num_rows($rs);   //how many material are there?



	
	 if ($num > 0) {
	 
	 echo '<div align="center">There are currently  '. $num_rows.' record(s).</div>';
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
                <form name="frmSearch" method="post" action="inprogress_plan_order_list2.php?date1=<?php echo $dateF; ?>&&date2=<?php echo $dateT; ?>&&factory=<?php echo $factory; ?>&&work_center=<?php echo $work_center; ?>&&plan_no=<?php echo $plan_no; ?>&&shift_ops=<?php echo $shift_ops; ?>">
                <table width="350" align="right">
                <tr>
                <th>
                <input name="txtKeyword" type="text" id="txtKeyword" value="<?php echo $strKeyword;?>">
                <input type="submit" value="Search" name="submit5" class="btn btn-info"></th>
                </tr>
                </table>
                </form>
           <table class="table table-bordered data-table">
              <thead>
                <tr>
                <th rowspan="3">No.</th>
                <th rowspan="3">Posting Date</th>
                <th rowspan="3">Posting Time</th>
                <th width="77" rowspan="3">Part No.</th>
                <th rowspan="3">Planned Order No.</th>
                <th rowspan="3">Planned Date</th>
                <th rowspan="3">Work Center</th>
                <th rowspan="3">Shift</th>
                <th rowspan="3">Document No.</th>
                <th colspan="3">Prod</th>
                <th>&nbsp;</th>
                <th colspan="3">QC</th>
                </tr>
                <tr>
                  <th rowspan="2">Plan Qty</th>
                  <th rowspan="2">OK Qty</th>
                  <th rowspan="2">NG Qty</th>
                  <th rowspan="2">RCV OK</th>
                  <th colspan="3">REWORK</th>
                 </tr>
                <tr>
                  <th rowspan="3">Pending</th>
                  <th rowspan="3">QC OK</th>
                  <th rowspan="3">QC Reject</th>
                </tr>
              </thead>   
              <tbody>
           <?php 

   while ($row = mysqli_fetch_array($rs))
   {
   
   $qty_total_rec = 0.000;
   $total_qty_pending = 0.000;
   $qty_total_ok = 0.000;
   $qty_total_NG = 0.000;
   $total_qty = 0.000;
   $qty_total_ok2 = 0.000;
   $qty_rev_ok2 = 0.000;
   $qty_total_NG2 = 0.000;	
   
   
    
	 //-----checking QA/QC entering output production
	  
	   $query_qqc = "SELECT * FROM qqc_detail_transaction WHERE bflush_no = '".$row["bflush_no"]."' AND plan_no = '".$row["plan_no"]."'";
	   $result_qqc = mysqli_query($dbc, $query_qqc);
	   $data_qqc = mysqli_fetch_array($result_qqc);  
	   
	   $qty_total_ok2 = ($qty_total_ok2 + $row["qty_actual"]);  
	   $qty_rev_ok2 = ($qty_rev_ok2 + $data_qqc["qty_qc"]);  
	   $qty_total_NG2 = ($qty_total_NG2 + $row["qty_NG"]);  
	   
    
	   $query_qqc2 = "SELECT * FROM qqc_transaction WHERE bflush_no = '".$data_qqc["bflush_no"]."'";
	   $result_qqc2 = mysqli_query($dbc, $query_qqc2);
	   
	   while($data_qqc2 = mysqli_fetch_array($result_qqc2))
	   {
		 	  
		$qty_total_rec = ($qty_total_rec + $data_qqc2["qty_qc"]);  
		$qty_total_ok = ($qty_total_ok + $data_qqc2["qty_qc_ok"]);  
	    $qty_total_NG = ($qty_total_NG + $data_qqc2["qty_qc_NG"]);  
		
	  //------quantity qc pending checking OK ------------
		  
	$total_qty = ($row["qty_balance"]);   
	$total_qty_pending = ($total_qty - (($qty_total_ok) + ($qty_total_NG)));
   

    }
		
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
                <td width="80"><?php echo $row["R2"]; ?></td>
                <td width="48"><?php echo $row[31]; ?></td>
                <td><?php echo $row[9]; ?></td>
                <td width="80"><font color="#0000CC"><?php echo $row[4]; ?></font></td>
                <td width="80"><?php echo $row["R"]; ?></td> 
                <td width="60"><?php echo $row[18]; ?></td>
                <td width="40"><?php echo $sta; ?></td>
                <td width="50"><font color="#0000CC"><?php echo $row[3]; ?></font></td>
                <td width="43"><div align="center"><?php echo number_format($row["qty_plan"]); ?></div></td>
                <td width="21"><div align="center"><?php echo number_format($row["qty_actual"]); ?></div></td>
                <td width="22"><div align="center"><?php echo number_format($qty_total_NG2); ?></div></td>
                <td width="7"><div align="center"><?php echo number_format($data_qqc["qty_qc"]); ?></div></td>
                <td width="8"><div align="center"><?php if ($total_qty_pending < 0){ echo "0"; } else{
					echo number_format($total_qty_pending); } ?></div></td>
                <td width="19"><div align="center"><?php echo number_format($qty_total_ok); ?></div></td>
                <td width="40"><div align="center"><?php echo number_format($qty_total_NG); ?></div></td>
                </tr>
                  <input name="id" type="hidden" value="<?php echo $row["id"]; ?>">
          <?php 
		  
		  $no++;
		  $counter++; // menambah counter
		  } 
		  ?>

         
 </tbody>
</table>
<br>
<br>
<?php echo $num_rows;?> Record : <?php echo $num_pages;?> Page :
<?php


 $strKeyword  = urlencode($strKeyword);


if($prev_page)
{   
 
    echo "<div class='pagin'>";
	echo " <a href='$_SERVER[SCRIPT_NAME]?Page=$prev_page&txtKeyword=$strKeyword&&date1=$dateF&&date2=$dateT&&factory=$factory&&work_center=$work_center&&plan_no=$plan_no&&shift_ops=$shift_ops' class='pagin'><< Back</a> ";
	echo "</div>";
}

for($i=1; $i<=$num_pages; $i++){
	if($i != $page)
	{   
	    echo "<div class='pagin'>";
		echo "<a href='$_SERVER[SCRIPT_NAME]?Page=$i&txtKeyword=$strKeyword&&date1=$dateF&&date2=$dateT&&factory=$factory&&work_center=$work_center&&plan_no=$plan_no&&shift_ops=$shift_ops' class='pagin'> $i </a> ";
		echo "</div>";
	}
	else
	{
	    echo "<div class='pagin'>";
	 	echo "<a href='' class='pagin active'> $i </a>";
		echo "</div>";
	}
}
if($page!=$num_pages)
{
	echo "<div class='pagin'>"; 
	echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$next_page&txtKeyword=$strKeyword&&date1=$dateF&&date2=$dateT&&factory=$factory&&work_center=$work_center&&plan_no=$plan_no&&shift_ops=$shift_ops' class='pagin'>Next>></a> ";
	echo "</div>";
}


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
