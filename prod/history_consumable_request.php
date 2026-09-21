<?php

/**
 * prod/history_consumable_request.php
 * Part of: Production module
 * Filename suggests: history consumable request
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, factory_detail, consumable_request, mat_master_header.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_production_menu.php, td>
    <td width=, footer.php.
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

date_default_timezone_set('Asia/Kuala_Lumpur');
$Cdate = date ("l, j F Y ");
$currentdate = (date("Y-m-d"));
// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "history_consumable_request.php";

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
<script type="text/javascript">
function printPage(iFid){
iFid.focus();
iFid.print();
 return true;
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
  <div id="breadcrumb"> <a href="index_production.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Consumable Request</a> <a href="#" class="current">Consumable Request History</a> </div>
  <h1>Consumable Request</h1>
</div>

<div class="container-fluid">
  <hr>
  <div class="row-fluid">

          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmSearch" id="frmSearch">
           <table class="table table-bordered table-striped">
            <tr>
              <th>Posting Date From :</th>
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
										$myCalendar->setDate($dt,$mt,$yr);
										$myCalendar->setPath("/calendar/");
										$myCalendar->setYearInterval(2010, 2030);
										// $myCalendar->setOnChange("myChanged('test')");
										$myCalendar->writeScript();
				
									}
			 ?></td>
              <th>Posting Date To :</th>
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
								        $myCalendar->setDate($dt2,$mt2,$yr2);
										$myCalendar->setPath("/calendar/");
										$myCalendar->setYearInterval(2010, 2030);
										// $myCalendar->setOnChange("myChanged('test')");
										$myCalendar->writeScript();
				
									}
                
                ?></td>
            </tr>
             <tr>
              <th>MRIN No :</th>
              <td><input name="temp_mrin" type="text" id="temp_mrin" size="25" class="span11" /></td>
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
  
                   while($row3=mysqli_fetch_array($result3)) 
			      {
                  echo'<option value="',$row3["factory_desc2"],'">',stripslashes($row3["factory_desc"]),'</option>';
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
              <th><input name="Submit2" type="submit"  class="btn btn-info" id="button" value="SEARCH" /></th>
            </tr>
            </table>
        </form>
     <?php
        if(isset($_POST["Submit2"]))
        {
            $temp_mrin = $_POST["temp_mrin"];
			$factory = $_POST["factory"];
			$dateF = $_POST["date1"];
            $dateT = $_POST["date2"];
			
            echo "<script>";
			echo "window.location='history_consumable_request2.php?temp_mrin=$temp_mrin&&date1=$dateF&&date2=$dateT&&factory=$factory'";
            //echo "window.location='posting_request_all2.php'";
            echo "</script>";
            exit(); //quit the script
        }
        
    	?>

          
          <?php
								 
    $query8 = "SELECT *, DATE_FORMAT(MR.date_require,'%d-%m-%Y') AS R FROM consumable_request AS MR, consumable_detail AS SD WHERE MR.id_con = SD.id_con AND MR.status_request = 'Y' AND MR.status_print = 'Y' AND MR.date_require = '".$currentdate."' GROUP BY MR.temp_mrin";
    $result8 = mysqli_query($dbc, $query8);
    $num_8 = mysqli_fetch_row($result8);
    $num_rows = mysqli_num_rows($result8);
 
   $pages = new Paginator;
   $pages->items_total = $num_rows;
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
   
  
$query = "SELECT *, DATE_FORMAT(MR.date_require,'%d-%m-%Y') AS R FROM consumable_request AS MR, consumable_detail AS SD WHERE MR.id_con = SD.id_con AND MR.status_request = 'Y' AND MR.status_print = 'Y' AND MR.date_require = '".$currentdate."' GROUP BY MR.temp_mrin ORDER BY MR.date_posting ASC, MR.temp_mrin ASC ";
$rs = mysqli_query($dbc, $query);   //run the query.GROUP BY MR.temp_mrin

	
	
	 if($num_rows > 0) {
	 
	 echo '<div align="center">There are currently  '. $num_rows.' record(s).</div>';

?>

         <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display Cosumable Request History</h5>
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

	
	$query_u = "SELECT * FROM user_detail WHERE user_no = '".db_esc($dbc, $row2["user_create"])."'";
	$result_u = mysqli_query($dbc, $query_u);   //run the query.
	$data_u = mysqli_fetch_array($result_u);   //how many records are there?    

 	
	$query4_p = "SELECT * from mat_master_header as LD, mat_master_detail as SD WHERE SD.id_hdr = LD.id_hdr and SD.id_dtl = '".db_esc($dbc, $row2["id_dtl"])."'";
  	$result4_p = mysqli_query($dbc, $query4_p);
 	$row4_p = mysqli_fetch_array($result4_p); 
 
    $query_u2 = "SELECT * FROM user_detail WHERE user_no = '".db_esc($dbc, $row2["user_update"])."'";
	$result_u2 = mysqli_query($dbc, $query_u2);   //run the query.
	$data_u2 = mysqli_fetch_array($result_u2);   //how many records are there?    
	  
		  ?>
  <tr>
    <td width="192">&nbsp;
        <?php if($row2["status_view"] == "N") { echo "<b>";  echo $row2["temp_mrin"]."</b>"; } else {  echo $row2["temp_mrin"];  } ?></td>
    <td width="77"><?php echo $row2["factory"]; ?></td>
    <td width="160"><?php echo $row2["R"]; ?> &nbsp;&nbsp;</td>
    <td width="160"><?php echo $row2["time_require"]; ?></td>
    <td width="100"><?php echo $data_u["user_fullname"]; ?></td>
    <td width="60"><a value="Details" href="detail_consumable_request.php?mrin_no=<?php echo $row2["temp_mrin"]; ?>&amp;&amp;TB_iframe=true&amp;height=400&amp;width=800" class="thickbox" target="_self"><img src="../img/icon_view.jpg" width="16" height="16" alt="View" />View</a></td>
    <td width="60"><a href="detail_consumable_request_reprinting.php?mrin_no=<?php echo $row2["temp_mrin"]; ?>&amp;&amp;TB_iframe=true&amp;height=400&amp;width=800" class="thickbox" target="_self"><img src="../img/print.jpg" width="16" height="16" alt="Print" />Print</a> </td>
  </tr>

<?php 
		 
		  $no ++;
		  $counter++; // menambah counter 
		   
		   //}// end if
		
		    
		  } ?>
          </tbody>
          </table>   
  <?php
   mysqli_free_result($rs); 
   

	}   // free up the resources 
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