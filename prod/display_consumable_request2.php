<?php

/**
 * prod/display_consumable_request2.php
 * Part of: Production module
 * Filename suggests: display consumable request2
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: user_detail, sys_setup_maintain, factory_detail, consumable_request, scan_detail, material_request.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_production_menu.php, td>
    <td width=, footer.php.
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

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "display_consumable_request.php";

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
  <div id="breadcrumb"> <a href="index_production.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Consumable Request</a> <a href="#" class="current">Consumable Request</a> </div>
  <h1>Consumable Request</h1>
</div>




<div class="container-fluid">
  <hr>
  <div class="row-fluid">
  <!--  <div class="span6">-->
          <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li><a  role="tab" href="material_consumable_request.php">New Request</a></li>
              <li class="active" ><a role="tab" href="display_consumable_request.php">Display Request</a></li>
              <li><a role="tab" href="posting_consumable_request.php">Posting Request</a></li>
            </ul>
          </div>
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
              <td><input name="temp_mrin" type="text" id="temp_mrin" size="25" class="span11" value="<?php echo h($_GET["temp_mrin"]); ?>" /></td>
              <th>&nbsp;</th>
              <td>&nbsp;</td>
             </tr>
            <tr>
              <th>Factory : </th>
              <td><select name="factory" id="factory">
                <option value="NULL" placeholder="Select Factory"> -- Select Factory --</option>
                <?php
	       $query3 = "SELECT * FROM factory_detail GROUP BY factory_desc2 ORDER BY id_fac ASC";
                   $result3 = mysqli_query($dbc, $query3);
  
                   while($row3=mysqli_fetch_array($result3, MYSQLI_NUM)) 
			      {
				  
				  
				  ?>
                <option value="<?php echo $row3[2]; ?>" <?php if($row3[2] == $_GET["factory"]) echo "selected"; ?>> <?php echo $row3[1]; ?></option>
                <?php
                  }
				?>
              </select></td>
             <th>&nbsp;</th>
              <th>&nbsp;</th>
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
			$dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			$factory = $_GET["factory"];
			
				//convert material no kpd id_hdr
			
			$query_convert = "SELECT * FROM `factory_detail` as MH WHERE MH.factory_desc = '".db_esc($dbc, $_GET["factory"])."'";
			$result_convert = mysqli_query($dbc, $query_convert); 
			
			while ($row_convert = mysqli_fetch_array($result_convert))
			{
			
			echo $row_convert["id_fac"];
			
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
                                
       				
		   //4. Factory 
                if ($factory == "NULL"){ 
                    $wheresql_04 = ""; }
                else {
                    $wheresql_04 = " AND MR.factory = '".db_esc($dbc, $factory)."'"; }  			
	       	
	                                        
				
				$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04;	
	
	//********** END CONDITION **************
	

	 $query8 = "SELECT * FROM consumable_request AS MR WHERE MR.status_request = 'Y' AND (MR.status != 'Close' AND MR.status  != 'Cancel') AND MR.status_print != 'Y' ".$where_sql." GROUP BY MR.temp_mrin";
   $result8 = mysqli_query($dbc, $query8) or trigger_error("SQL", E_USER_ERROR);
   
   $num_rows = mysqli_num_rows($result8);
	 
   $pages = new Paginator;
   $pages->items_total = $num_rows;
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT *, DATE_FORMAT(MR.date_require,'%d-%m-%Y') AS R FROM consumable_request AS MR WHERE MR.status_request = 'Y' AND (MR.status != 'Close' AND MR.status  != 'Cancel') AND MR.status_print != 'Y' ".$where_sql." GROUP BY MR.temp_mrin ORDER BY MR.date_posting DESC, MR.temp_mrin ASC";
$rs = mysqli_query($dbc, $query);   //run the query.


	
	 if($num_rows > 0) {	
	 
	 echo '<div align="center">There are currently  '. $num_rows.' record(s).</div>';
	
	

?>

       
      <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display Cosumable Request</h5>
          </div>
             
          <div class="widget-content nopadding">
          
            <table class="table table-bordered data-table">
             <thead>
              <tr>
                <th>MRIN No.</th>
                <th>Factory</th>
                <th>Request Date</th>
                <th>Request Time</th>
                <th>Requestor</th>
                <th>Cancel</th>
                <th>View</th>
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
	
	$query3 = "SELECT * FROM factory_detail WHERE id_fac = '".db_esc($dbc, $row_scan["factory"])."'";
    $result3 = mysqli_query($dbc, $query3);
	$row3 = mysqli_fetch_array($result3);
	
	$query_u = "SELECT * FROM user_detail WHERE user_no = '".db_esc($dbc, $row2['user_create'])."'";
    $result_u = mysqli_query($dbc, $query_u);   //run the query.
     $data_u = mysqli_fetch_array($result_u);   //how many records are there? 
	 
		  ?>         
         
    <tr>
    <td width="250">&nbsp;<?php echo $row2["temp_mrin"]; ?></td>
    <td width="105"><?php echo $row2["factory"]; ?></td>
    <td width="100"><?php echo $row2["R"]; ?> </td>
    <td width="115"><?php echo $row2["time_require"]; ?></td>
    <td width="134"><?php echo $data_u["user_fullname"]; ?></td>
    <td width="80"><a href="cancel_consumable_request.php?mrin_no=<?php echo $row2["temp_mrin"]; ?>&&user_no=<?php echo $data_u["user_no"]; ?>&amp;&amp;TB_iframe=true&amp;height=400&amp;width=1000" class="thickbox" target="_self"><img src="../img/delete.png" width="16" height="16" alt="Delete">Cancel</a></td>
    <td width="86" ><a value="Details" href="detail_consumable_request.php?mrin_no=<?php echo $row2["temp_mrin"]; ?>&&TB_iframe=true&height=400&width=800" class="thickbox" target="_self"><img src="../img/icon_view.jpg" width="16" height="16" alt="View">View</a></td>
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