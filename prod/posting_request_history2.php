<?php

/**
 * prod/posting_request_history2.php
 * Part of: Production module
 * Filename suggests: posting request history2
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: user_detail, sys_setup_maintain, factory_detail, work_center_detail, material_request_close, scan_detail, material_request, mat_master_header.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_production_menu.php, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
session_start();
$username = $_SESSION['username'] ?? '';
date_default_timezone_set("Asia/Kuala_Lumpur");

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

$url = "posting_request_history.php";

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

<script type="text/javascript">
function printPage(iFid){
iFid.focus();
iFid.print();
}
</script>
<style>
#iframe1{
visibility:hidden;
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
		
		var strURL="findWorkcenter3.php?factory="+factory;
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
  <div id="breadcrumb"> <a href="index_production.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Material Request</a> <a href="#" class="current">Material Request History</a> </div>
  <h1>Material Request</h1>
</div>

<div class="container-fluid">
  <hr>
  <div class="row-fluid">
  <!--  <div class="span6">-->
     
   
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="frmSearch" id="frmSearch">
            <table class="table table-bordered table-striped">
            <tr>
              <th>Posting Date From :</th>
              <td><?php
    
					$dd1 = substr($_GET["date1"],8,2);
					$mm1 = substr($_GET["date1"],5,2);
					$yy1 = substr($_GET["date1"],0,4);
	
                    $myCalendar = new tc_calendar("date1", true, false);
					$myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					$myCalendar->setDate($dd1, $mm1, $yy1);
					$myCalendar->setPath("/calendar/");
					$myCalendar->setYearInterval(date('Y'), date('Y') + 10);
				    // $myCalendar->setOnChange("myChanged('test')");
					$myCalendar->writeScript();
			 ?></td>
              <th>Posting Date To :</th>
              <td><?php
                
                    $dd2 = substr($_GET['date2'],8,2);
				    $mm2 = substr($_GET['date2'],5,2);
				    $yy2 = substr($_GET['date2'],0,4);
				
                    $myCalendar = new tc_calendar("date2", true, false);
					$myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					$myCalendar->setDate($dd2, $mm2, $yy2);
					$myCalendar->setPath("/calendar/");
					$myCalendar->setYearInterval(date('Y'), date('Y') + 10);
					// $myCalendar->setOnChange("myChanged('test')");
					$myCalendar->writeScript();
                
                ?></td>
            </tr>
            <tr>
              <th>MRIN No :</th>
              <td><input name="temp_mrin" type="text" id="temp_mrin" size="25" class="span11" value="<?php echo h($_GET["temp_mrin"]); ?>" /></td>
              <th>Production Order :</th>
              <td><input name="prod_order" type="text" id="prod_order" size="25" class="span11" value="<?php echo h($_GET["prod_order"]); ?>"  /></td>
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
	               $query5 = "SELECT * FROM work_center_detail WHERE id_factory = '".db_esc($dbc, $_GET["factory"])."' ORDER BY id_work ASC";
                   $result5 = mysqli_query($dbc, $query5);
  
                   while($row5=mysqli_fetch_array($result5)) 
				    { 
				   
				   ?>
                <option value="<?php echo h($row5["id_work"]); ?>" <?php if($row5["id_work"] == $_GET["work_center"]) echo "selected"; ?>> <?php echo $row5["id_work"],' - ',stripslashes($row5["wc_desc"]); ?></option>
                <?php
                  }
				?>
                </select></div></td>
            </tr>
            <tr>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th><input name="Submit" type="submit"  class="btn btn-info" id="button" value="SEARCH" /></th>
            </tr>
            </table>
        </form>
<?php
            
			$temp_mrin = $_GET["temp_mrin"];
			$prod_order = $_GET["prod_order"];
            $dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			$work_center = $_GET["work_center"];
			$factory = $_GET["factory"];
			
				//convert material no kpd id_hdr
			
			$query_convert = "SELECT * FROM factory_detail as MH WHERE MH.factory_desc = '".db_esc($dbc, $_GET["factory"])."'";
			$result_convert = mysqli_query($dbc, $query_convert); 
			
			while ($row_convert = mysqli_fetch_array($result_convert))
			{
			
			echo h($row_convert["id_fac"]);
			
			}
			
			//-------Count all results------------------------//
			
				 $where_sql = '';
		 
		 // 1. DateT
                if($dateT == "0000-00-00") {
                     $wheresql_01 = ""; }
                else {
                      $wheresql_01 = " AND (MR.date_posting <= '".db_esc($dbc, $dateT)."')"; }
		  //2. DateF 
                if ($dateF  == "0000-00-00" ){
                     $wheresql_02 = "";}
                else {
                     $wheresql_02 = " AND (MR.date_posting >= '".db_esc($dbc, $dateF)."')";}
                                                
		 // 3. Temp MRIN
                if ($temp_mrin == "" ){
                    $wheresql_03 = ""; }
                else {
                    $wheresql_03 = " AND MR.temp_mrin = '".db_esc($dbc, $temp_mrin)."'"; }          
                                
          //4. Work Center
                if ($work_center == "NULL" ){
                    $wheresql_04 = ""; }
                else {
					$wheresql_04 = " AND SD.work_center = '".db_esc($dbc, $work_center)."'"; }
   
	       //5. Production Order
                if ($prod_order == ""){ 
                    $wheresql_05 = ""; }
                else {
                    $wheresql_05 = " AND SD.prod_order = '".db_esc($dbc, $prod_order)."'"; }  	
					
		   //6. Factory 
                if ($factory == "NULL"){ 
                    $wheresql_06 = ""; }
                else {
                    $wheresql_06 = " AND SD.factory = '".db_esc($dbc, $factory)."'"; }  			
	       	
	                                        
				
				$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04 .$wheresql_05 .$wheresql_06;	
	
	//********** END CONDITION **************
	

	 $query8 = "SELECT *, DATE_FORMAT(MR.date_mrin,'%d-%m-%Y') AS R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R2 FROM material_request_close AS MR, scan_detail AS SD WHERE MR.id_scan = SD.id_scan AND MR.status_request = 'Y' ".$where_sql."GROUP BY MR.temp_mrin";
	$result8 = mysqli_query($dbc, $query8) or trigger_error("SQL", E_USER_ERROR);
    $num_rows = mysqli_num_rows($result8);
  
   $pages = new Paginator;
   $pages->items_total = $num_rows;
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
   
   
  // echo  $num_rows;
  
$query = "SELECT *, DATE_FORMAT(MR.date_mrin,'%d-%m-%Y') AS R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R2 FROM material_request_close AS MR, scan_detail AS SD WHERE MR.id_scan = SD.id_scan AND MR.status_request = 'Y'".$where_sql."  GROUP BY MR.temp_mrin ORDER BY MR.date_posting ASC, MR.temp_mrin ASC ";
$rs = mysqli_query($dbc, $query);   //run the query.GROUP BY MR.temp_mrin

	
	 if($num_rows > 0) {	
	 
	 echo '<div align="center">There are currently  '. $num_rows.' record(s).</div>';
	
	

?>

       
      <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display Material Request History</h5>
          </div>
             
          <div class="widget-content nopadding">

            <table class="table table-bordered data-table">
             <thead>
              <tr>
                <th>MRIN No.</th>
                <th>Factory</th>
                <th>Line</th>
                <th>Request Date</th>
                <th>Request Time</th>
                <th>Requestor</th>
                <th>View</th>
                <th>Print</th>
            </tr>
           </thead>
           <tbody>
        
    <?php
		  
   $counter = 1;
   $no = 1;
   
    while ($row2 = mysqli_fetch_array($rs))
   {
	
  
   	$query_scan = "SELECT * FROM scan_detail WHERE id_scan = '".db_esc($dbc, $row2[6])."'";
   	$result_scan = mysqli_query($dbc, $query_scan);
   	$row_scan = mysqli_fetch_array($result_scan);
	
	$query_again = "SELECT * FROM material_request WHERE status_request = 'Y' and id_scan = '".db_esc($dbc, $row2[6])."' ORDER BY id_req ASC";
    $rs_again = mysqli_query($dbc, $query_again);   //run the query.
    $row = mysqli_fetch_array($rs_again);
	
	$query_u = "SELECT * FROM user_detail WHERE user_no = '".db_esc($dbc, $row['user_create'])."'";
	$result_u = mysqli_query($dbc, $query_u);   //run the query.
	$data_u = mysqli_fetch_array($result_u);   //how many records are there?    

 	$query3 = "SELECT * FROM factory_detail WHERE id_fac = '".db_esc($dbc, $row_scan["factory"])."'";
    $result3 = mysqli_query($dbc, $query3);
	$row3 = mysqli_fetch_array($result3);
	
	$query4_p = "SELECT * from mat_master_header as LD, mat_master_detail as SD WHERE SD.id_hdr = LD.id_hdr and SD.id_dtl = '".db_esc($dbc, $row2[5])."'";
  	$result4_p = mysqli_query($dbc, $query4_p);
 	$row4_p = mysqli_fetch_array($result4_p); 
 
  

//-------------------------------------------------------Transfer Posting [Traffic Light] --------------------------
// table post_detail_header --- checking traffic licht
//--------------------------------------------------------------------------------------------------

$date_post =  ($row2["date_mrin"].' '.$row2["time_mrin"]);
$date_transfer = (date("Y-m-d").' '.date("H:i:s"));
//$date_transfer = ($db_tp["date_create"].' '.$db_tp["time_create"]);

$start_date = new DateTime($date_post);
$since_start = $start_date->diff(new DateTime($date_transfer));

	  
		  ?>        
          
    <tr>
    <td>&nbsp;<?php echo h($row["temp_mrin"]); ?><?php if($row2["status_urgent"] == "Y") 
	{
	?>
	<img src="../img/icon-urgent.gif" title="URGENT" />
	<?php
     }  ?></td>
    <td width="50"><div align="center"><?php echo h($row_scan["factory"]); ?></div></td>
    <td width="50"><div align="center"><?php echo h($row_scan["work_center"]); ?></div></td>
    <td width="100"><div align="center"><?php echo h($row2["R"]); ?></div></td>
    <td width="100"><div align="center"><?php echo h($row2["time_mrin"]); ?></div></td>
    <td width="134"><?php echo h($data_u["user_fullname"]); ?></td>
    <td width="48"><a value="Details" href="detail_material_request_history.php?mrin_no=<?php echo h($row2["temp_mrin"]); ?>&&prod_order=<?php echo h($row2["prod_order"]); ?>&&TB_iframe=true&&height=400&&width=1000" class="thickbox" target="_self"><img src="../img/icon_view.jpg" width="16" height="16" alt="View">View</a></td>
    <td width="55"><a href="detail_material_request_history_printing.php?mrin_no=<?php echo h($row2["temp_mrin"]); ?>&&prod_order=<?php echo h($row2["prod_order"]); ?>&&TB_iframe=true&&height=400&&width=1000" class="thickbox" target="_self"><img src="../img/print.jpg" width="16" height="16" alt="Print">Print</a> </td>
  </tr>

   
  <?php 
		 
		 $no ++;
		  
		  $counter++; // menambah counter 
		
		    
		  } ?></tbody></table>

  <?php
   mysqli_free_result($rs); 
	?>
      
          
          
    <?php }   // free up the resources 
  else
{
?>
<table width="800" cellspacing="0" class="textboxred">
  <tr> 
    <td><div align="center"><font color="#FF0000"><strong>There are currently 
          no material request.</strong></font></div></td>
  </tr>
</table>
        <?php
		   } 
//mysqli_close($dbc)
?>


<p>Legend :</p>
<table width="70%" border="1">
  <tr>
    <td width="10%"><div align="center"><img src="../img/red_icon2.jpg" alt="" width="20" height="20" /></div></td>
    <td width="90%">MRIN Request has passed 20 minutes from the requested time.</td>
  </tr>
  <tr>
    <td><div align="center"><img src="../img/yellow_icon2.jpg" alt="" width="20" height="20" /></div></td>
    <td>MRIN Request has reached the requested time.</td>
  </tr>
  <tr>
    <td><div align="center"><img src="../img/green_icon2.jpg" alt="" width="20" height="20" /></div></td>
    <td>MRIN Request is now 20 minutes before the requested time.</td>
  </tr>
   <tr>
    <td><div align="center"><img src="../img/grey_icon.jpg" alt="" width="20" height="20" /></div></td>
    <td>New MRIN Request has been posted.</td>
  </tr>
</table>
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