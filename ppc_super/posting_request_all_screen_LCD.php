<?php

/**
 * ppc_super/posting_request_all_screen_LCD.php
 * Part of: PPC module (supervisor/admin tier)
 * Filename suggests: posting request all screen LCD
 *
 * Behavior: requires an active login session ($_SESSION['username']).
 * Database tables referenced: user_detail, sys_setup_maintain, material_request, scan_detail, factory_detail, mat_master_header, post_detail_header, material_request_close.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, content.php, top_modal_menu.php, left_ppc_menu.php, footer.php.
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
require_role($dbc, 5);
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
date_default_timezone_set("Asia/Kuala_Lumpur");

$url = "posting_request_all_screen_LCD.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
    $result2 = mysqli_query($dbc, $query2) or die(db_fail($dbc));
    $res = mysqli_fetch_array($result2);
	
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
<style>
#iframe1{
visibility:hidden;
}
</style>
 <script type="text/JavaScript">

function timedRefresh(timeoutPeriod) {
     setTimeout("location.reload(true);",timeoutPeriod);
}


</script>
<?php

function _time_diff($hour_a, $hour_b){
   $y = date('Y-m-d').' ';
   return (int)((strtotime($y.$hour_b) - strtotime($y.$hour_a)) / 60);
}

include 'content.php';

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
<?php include "left_ppc_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
 <div id="content-header">
  <div id="breadcrumb"> <a href="index_ppc_super.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Board</a> <a href="#" class="current">Material Request Board</a> </div>
  <h1>Board</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
  
           <?php


 $query = "SELECT *, DATE_FORMAT(MR.date_mrin,'%d-%m-%Y') AS R FROM material_request AS MR, scan_detail AS SD WHERE MR.id_scan = SD.id_scan AND MR.status_request = 'Y' AND (MR.status != 'Close' AND MR.status  != 'Cancel') GROUP BY MR.temp_mrin ORDER BY MR.date_mrin DESC,MR.time_mrin DESC";
$rs = mysqli_query($dbc, $query) or trigger_error("SQL", E_USER_ERROR);
$num = mysqli_num_rows($rs);   //how many material are there?


	if ($num > 0) { 
	
	 echo '<div align="center">There are currently  '. $num.' record(s).</div>';
?>
          
          
      <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Material Request Board</h5>
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
  
  //---------------------------------------------------------------------------------------------------------------------
//Update listing board   - MRIN disappear from listing if all component status_posting = "Close"
//---------------------------------------------------------------------------------------------------------------------
	 $TOT = 0.000;
	 $outs_qty = 0;
	 
	$query_tp = "SELECT *,SUM(rquantity) as TOT FROM post_detail_header WHERE mrin_no = '".db_esc($dbc, $row2["temp_mrin"])."' AND prod_order = '".db_esc($dbc, $row_scan["prod_order"])."' AND mvt_type = 311 AND status_posting = 'New' GROUP BY material_no";
	$result_tp  = mysqli_query($dbc, $query_tp); 
	
	while($row_tp = mysqli_fetch_assoc($result_tp))
{
    
	//echo $row_tp["material_no"]; echo ":";
	//echo $row_tp["TOT"];
	//echo $row2["bom_qty"]; echo "<br>";
	//echo $row_tp["id_post"];
		
	$tp_quantity = $row_tp["TOT"]; 
	
	
	$outs_qty = (($row["bom_qty"]) - ($row_tp["TOT"]));
	
	
	   if(($row["bom_qty"] == $tp_quantity) || ($row["bom_qty"] < $tp_quantity) && ($outs_qty < 0))
       {  
	
 $query_upd2 = "UPDATE `post_detail_header` SET status_posting = 'Close', date_close = NOW() WHERE mrin_no = '".db_esc($dbc, $row2["temp_mrin"])."' AND material_no = '".db_esc($dbc, $row_tp["material_no"])."' ";
 $result_upd2 = mysqli_query($dbc, $query_upd2); 
	      
 $query_upd3 = "UPDATE `material_request` SET status = 'Close' WHERE temp_mrin = '".db_esc($dbc, $row2["temp_mrin"])."' AND bom_component = '".db_esc($dbc, $row_tp["material_no"])."' ";
 $result_upd3 = mysqli_query($dbc, $query_upd3); 
 
	 $query_upd4 = "UPDATE `material_request` SET status = 'Close' WHERE temp_mrin = '".db_esc($dbc, $row2["temp_mrin"])."' AND (bom_qty = '0.000' OR bom_qty = '')";
 $result_upd4 = mysqli_query($dbc, $query_upd4); 
	
	 //--------------------------------------------------------------------
       //copy yg close MRIN masuk dalam MRIN history
	   //---------------------------------------------------------------------
        if($result_upd3 || $result_upd4)
		 {
		 
		   $query_upd4 = "SELECT * FROM `material_request` WHERE temp_mrin = '".db_esc($dbc, $row2["temp_mrin"])."' AND status = 'Close'";
		   $result_upd4 = mysqli_query($dbc, $query_upd4) or trigger_error("SQL", E_USER_ERROR);
		   $row_upd4 = mysqli_num_rows($result_upd4); 
        

		   if($row_upd4 > 0 )
		   {
		  
		  $query_mm3 = "SELECT * FROM `material_request` WHERE temp_mrin = '".db_esc($dbc, $row2["temp_mrin"])."' AND status = 'Close' AND bom_component = '".db_esc($dbc, $row_tp["material_no"])."'"; 
        	$result_mm3 = mysqli_query($dbc, $query_mm3) or die(db_fail($dbc));
			$row_mm3 = mysqli_fetch_array($result_mm3); 
			
			
			$query_mm3_insert =  "INSERT INTO material_request_close(id_req, mrin_doc, mrin_year, temp_mrin, id_hdr, id_dtl, id_scan, bom_id, bom_qty, bom_oum, status_request, status_print, user_create, date_create, user_update, date_update, date_posting, time_posting, status, bom_component, date_mrin, time_mrin, reason_close, reason_close2) VALUES('".db_esc($dbc, $row_mm3["id_req"])."','".db_esc($dbc, $row_mm3["mrin_doc"])."','".db_esc($dbc, $row_mm3["mrin_year"])."','".db_esc($dbc, $row_mm3["temp_mrin"])."','".db_esc($dbc, $row_mm3["id_hdr"])."','".db_esc($dbc, $row_mm3["id_dtl"])."','".db_esc($dbc, $row_mm3["id_scan"])."','".db_esc($dbc, $row_mm3["bom_id"])."','".db_esc($dbc, $row_mm3["bom_qty"])."', '".db_esc($dbc, $row_mm3["bom_oum"])."','".db_esc($dbc, $row_mm3["status_request"])."','".db_esc($dbc, $row_mm3["status_print"])."','".db_esc($dbc, $row_mm3["user_create"])."','".db_esc($dbc, $row_mm3["date_create"])."','".db_esc($dbc, $row_mm3["user_update"])."','".db_esc($dbc, $row_mm3["date_update"])."','".db_esc($dbc, $row_mm3["date_posting"])."','".db_esc($dbc, $row_mm3["time_posting"])."','".db_esc($dbc, $row_mm3["status"])."','".db_esc($dbc, $row_mm3["bom_component"])."','".db_esc($dbc, $row_mm3["date_mrin"])."','".db_esc($dbc, $row_mm3["time_mrin"])."','6','')";
$result_mm3_insert = mysqli_query($dbc, $query_mm3_insert) or die(db_fail($dbc));
			
		  
		  
		    } // if $r4 == $r5
		  
		 
		   }// if($result_upd3)
	
	   }elseif(($row["bom_qty"] > $tp_quantity))
       {
	
	    }
	
} // end while loop $row_tp
  


		  ?>  
           <tr>
            <td>&nbsp;<?php echo $row2["temp_mrin"]; ?>&nbsp;&nbsp;<?php if($row2["status_urgent"] == "Y") 
	{
	?>
	<img src="../img/icon-urgent.gif" title="URGENT" />
	<?php
     }  ?></td>
          <td><div align="center"><?php echo $row_scan["factory"]; ?></div></td>
          <td><?php echo $row_scan["work_center"]; ?></td>
          <td><?php echo $row2["R"]; ?>&nbsp;</td>
          <td><?php echo $row2["time_mrin"]; ?></td>
          <td><?php echo $data_u["user_fullname"]; ?></td>
          <td><div align="center">
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
                echo '<img src="../img/grey_icon.jpg" width="25" height="25" /> ';
}
elseif(($curr_time >= $min_20) && ($curr_time < $request_time) )
{
                echo '<img src="../img/green_icon2.jpg" width="25" height="25" /> ';
}
elseif(($curr_time >= $request_time) && ($curr_time < $plus_20) )
{
                echo '<img src="../img/yellow_icon2.jpg" width="25" height="25" /> ';
}
elseif($curr_time >= $plus_20)
{
                echo '<img src="../img/red_icon2.jpg" width="25" height="25" /> ';
}


	?></div></td>
          
                  </tr>
        
          <?php 		 
		
		 $counter++; // menambah counter 
		 $no ++;
		 
		  } 
		 ?>
              </tbody>
            </table>
            <?php
   mysqli_free_result($rs); 
   
   ?> <?php
	}   // free up the resources 
else
{
?><center>
<table class="table table-bordered">
  <tr> 
    <td><div align="center"><font color="#FF0000"><strong>There are currently no material request.</strong></font></div></td>
  </tr>
</table></center>
        <?php
		   } 
 mysqli_close($dbc)
?>
<table width="70%" border="0">
  <tr>
    <td width="20%"><span class="style4">No. of MRIN </span></td>
    <td width="9%"><span class="style4">&nbsp; :</span></td>
    <td width="71%"><span class="style4">&nbsp;<?php echo $numrows; ?></span></td>
  </tr>
  <tr>
    <td><span class="style4">Page No. </span></td>
    <td><span class="style4">&nbsp; :</span></td>
    <td><span class="style4">&nbsp;<?php echo $currentpage.' of '.$totalpages; ?></span></td>
  </tr>
</table>
<p>&nbsp;</p>
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
<br>
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
