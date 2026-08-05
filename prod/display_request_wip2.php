<?php
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "wip_request_list.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
    $result2 = mysql_query($query2) or die (mysql_error());
    $res = mysql_fetch_array($result2);
	
$today = getdate();
$hours = $today['hours']; 
$minutes = $today['minutes'];
$seconds = $today['seconds'];
$month = $today['mon']; 
$mday = $today['mday']; 
$year = $today['year']; 	
		
//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysql_query($query_setup);   //run the query.
$num_setup = mysql_num_rows($rs_setup);   //how many material are there?
$data_setup = mysql_fetch_array($rs_setup);
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
  <div id="breadcrumb"> <a href="index_production.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">WIP Request</a> <a href="#" class="current">WIP Request</a> </div>
  <h1>WIP Request</h1>
</div>

<div class="container-fluid">
  <hr>
  <div class="row-fluid">
  <!--  <div class="span6">-->
          <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li><a  role="tab" href="wip_request_list.php">New Request</a></li>
              <li><a role="tab" href="draft_request_wip.php">Draft Request</a></li>
              <li class="active"><a role="tab" href="display_request_wip.php">Display Request</a></li>
            </ul>
          </div>
          </div>
            <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Display Request - WIP</h5>
        </div>
   
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
					  $myCalendar->setYearInterval(2010, 2030);
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
					$myCalendar->setYearInterval(2010, 2030);
					// $myCalendar->setOnChange("myChanged('test')");
					$myCalendar->writeScript();
					
                ?></td>
            </tr>
            <tr>
              <th>MRIN No :</th>
              <td><input name="temp_mrin" type="text" id="temp_mrin" size="25" class="span11"  value="<?php echo $_GET["temp_mrin"]; ?>" /></td>
              <th>Production Order :</th>
              <td><input name="prod_order" type="text" id="prod_order" size="25" class="span11"  value="<?php echo $_GET["prod_order"]; ?>" /></td>
            </tr>
            <tr>
              <th>Factory : </th>
              <td><select name="factory" id="factory" onChange="getFactory(this.value)">
                <option value="NULL" placeholder="Select Factory"> -- Select Factory --</option>
                <?php
	               $query3 = "SELECT * FROM factory_detail GROUP BY factory_desc2 ORDER BY id_fac ASC";
                   $result3 = mysql_query($query3);
  
                   while($row3=mysql_fetch_array($result3, MYSQL_NUM)) 
			      {
				  
				  
				  ?>
                <option value="<?php echo $row3[2]; ?>" <?php if($row3[2] == $_GET["factory"]) echo "selected"; ?>> <?php echo $row3[1]; ?></option>
                <?php
                  }
				?>
              </select></td>
              <th>Work Center :</th>
              <td>
              <div id="work_centerdiv"> 
               <select name="work_center" id="work_center">
                <option value="NULL" placeholder="Select Work Center"> -- Select Work Center --</option>
                 <?php
	               $query5 = "SELECT * FROM work_center_detail WHERE id_factory = '".$_GET["factory"]."' ORDER BY id_work ASC";
                   $result5 = mysql_query($query5);
  
                   while($row5=mysql_fetch_array($result5)) 
				    { 
				   
				   ?>
                <option value="<?php echo $row5["id_work"]; ?>" <?php if($row5["id_work"] == $_GET["work_center"]) echo "selected"; ?>> <?php echo $row5["id_work"],' - ',stripslashes($row5["wc_desc"]); ?></option>
                <?php
                  }
				?>
                </select></div>
              
              </td>
            </tr>
            <tr>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th><input name="Submit" type="submit"  class="btn btn-info" id="button" value="SEARCH" /></th>
            </tr>
            </table>
        </form></div>
<?php
       
			$temp_mrin = $_GET["temp_mrin"];
			$prod_order = $_GET["prod_order"];
            $dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			$work_center = $_GET["work_center"];
			$factory = $_GET["factory"];
   
				//convert material no kpd id_hdr
			
			$query_convert = "SELECT * FROM `factory_detail` as MH WHERE MH.factory_desc = '".$_GET["factory"]."'";
			$result_convert = mysql_query($query_convert); 
			
			while ($row_convert = mysql_fetch_array($result_convert))
			{
			
			echo $row_convert["id_fac"];
			
			}
			
			
			//-------Count all results------------------------//
			
				 $where_sql = '';
		 
		 // 1. DateT
                if($dateT == "0000-00-00") {
                     $wheresql_01 = ""; }
                else {
                      $wheresql_01 = " AND (MR.date_posting <= '$dateT')"; }
		  //2. DateF 
                if ($dateF  == "0000-00-00" ){
                     $wheresql_02 = "";}
                else {
                     $wheresql_02 = " AND (MR.date_posting >= '$dateF')";}
                                                
		 // 3. Temp MRIN
                if ($temp_mrin == "" ){
                    $wheresql_03 = ""; }
                else {
                    $wheresql_03 = " AND MR.temp_mrin_wip = '$temp_mrin'"; }          
                                
          //4. Work Center
                if ($work_center == "NULL" ){
                    $wheresql_04 = ""; }
                else {
					$wheresql_04 = " AND SD.work_center = '$work_center'"; }
   
	       //5. Production Order
                if ($prod_order == ""){ 
                    $wheresql_05 = ""; }
                else {
                    $wheresql_05 = " AND SD.prod_order = '$prod_order'"; }  	
					
		   //6. Factory 
                if ($factory == "NULL"){ 
                    $wheresql_06 = ""; }
                else {
                    $wheresql_06 = " AND SD.factory = '$factory'"; }  			
	       	
	                                        
				
				$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04 .$wheresql_05 .$wheresql_06;	
	
	//********** END CONDITION **************

								 
   $query8 = "SELECT *, DATE_FORMAT(MR.date_mrin,'%d-%m-%Y') AS R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R2 FROM wip_request AS MR, scan_detail_wip AS SD WHERE MR.id_scan_wip = SD.id_scan AND SD.status_urgent = 'N' AND MR.status_request = 'Y' AND (MR.status != 'Close' AND MR.status  != 'Cancel')".$where_sql." GROUP BY MR.temp_mrin_wip ";
     $result8 = mysql_query($query8) or trigger_error("SQL", E_USER_ERROR);
     //$num_8 = mysql_fetch_row($result8);
     $num_rows = mysql_num_rows($result8);

   $pages = new Paginator;
   $pages->items_total = $num_rows;
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT *, DATE_FORMAT(MR.date_mrin,'%d-%m-%Y') AS R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R2 FROM wip_request AS MR, scan_detail_wip AS SD WHERE MR.id_scan_wip = SD.id_scan AND SD.status_urgent = 'N' AND MR.status_request = 'Y' AND (MR.status != 'Close' AND MR.status  != 'Cancel')".$where_sql." GROUP BY MR.temp_mrin_wip ORDER BY MR.date_mrin DESC,MR.time_mrin DESC";
$rs = mysql_query($query);   //run the query.
//$num = mysql_num_rows($rs);   //how many material are there?


	
	 if($num_rows > 0) {	
	 
	 echo '<div align="center">There are currently  '. $num_rows.' record(s).</div>';
	
	

?>

       
      <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display Posting Request</h5>
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
                <th>Status</th>
                <th>View</th>
                <th>Print</th>
            </tr>
           </thead>
           <tbody>
    <?php
		  
   $counter = 1;
   $no = 1;
   
    while ($row2 = mysql_fetch_array($rs))
   {
	
   
   $query_scan = "SELECT * FROM scan_detail_wip WHERE id_scan = '".$row2[6]."'";
   $result_scan = mysql_query($query_scan);
   $row_scan = mysql_fetch_array($result_scan);
	
	$query_again = "SELECT * FROM wip_request WHERE status_request = 'Y' and id_scan_wip = '".$row2[6]."' ORDER BY id_req_wip ASC";
    $rs_again = mysql_query($query_again);   //run the query.
    $row = mysql_fetch_array($rs_again);
	
	$query3 = "SELECT * FROM factory_detail WHERE id_fac = '".$row_scan["factory"]."'";
    $result3 = mysql_query($query3);
	$row3 = mysql_fetch_array($result3);
	
	$query_u = "SELECT * FROM user_detail WHERE user_no = '$row[user_create]'";
$result_u = mysql_query($query_u);   //run the query.
$data_u = mysql_fetch_array($result_u);   //how many records are there?       


		  ?>         
         
     <tr>
     <td width="143">&nbsp;<?php echo $row2["temp_mrin_wip"]; ?></td>
     <td width="70"><?php echo $row_scan["factory"]; ?></td>
     <td width="70"><?php echo $row_scan["work_center"]; ?></td>
     <td width="70"><?php echo $row2["R"]; ?>&nbsp;</td>
     <td width="57"><?php echo $row2["time_mrin"]; ?></td>
     <td width="110"><?php echo $data_u["user_fullname"]; ?></td>
     <td width="80"><div align="center">                
  <?php


	//------------------------   Traffic light-------------------------------------------------------------
		//- edit date : 5/11/2014    by : azie
		//-----------------------------------------------------------------------------------------------------
		
		
$curr_time = date("Y-m-d H:i:s"); 
$request_time = ($row2["date_mrin"].' '.$row2["time_mrin"]);

$min_20 = date("Y-m-d H:i:s", strtotime("$request_time - 20 minutes"));
$plus_20 = date("Y-m-d H:i:s", strtotime("$request_time + 20 minutes"));

   $start_date = new DateTime($min_20);
   $diff_time = $start_date->diff(new DateTime($request_time));


if ($curr_time < $min_20)
{
                echo '<img src="../img/grey_icon.jpg" width="25" height="25" />';
}
elseif(($curr_time >= $min_20) && ($curr_time < $request_time) )
{
                echo '<img src="../img/green_icon2.jpg" width="25" height="25" />';
}
elseif(($curr_time >= $request_time) && ($curr_time < $plus_20) )
{
                echo '<img src="../img/yellow_icon2.jpg" width="25" height="25" />';
}
elseif($curr_time >= $plus_20)
{
                echo '<img src="../img/red_icon2.jpg" width="25" height="25" />';
}


	?></div></td>
            
              <td width="48"><a value="Details" href="detail_wip_request.php?mrin_no=<?php echo $row2["temp_mrin_wip"]; ?>&&TB_iframe=true&height=400&width=1000" class="thickbox" target="_self"><img src="../img/icon_view.jpg" width="16" height="16" alt="View">View</a></td>
                <td width="55"><a href="detail_wip_request_printing.php?mrin_no=<?php echo $row2["temp_mrin_wip"]; ?>&&TB_iframe=true&height=400&width=1000" class="thickbox" target="_self"><img src="../img/print.jpg" width="16" height="16" alt="Print">Print</a> </td>
  </tr>
   
  <?php 
		 
		 $no ++;
		  
		  $counter++; // menambah counter 
		
		    
		  } ?></tbody></table> 

  <?php
   mysql_free_result($rs); 
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
mysql_close()
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