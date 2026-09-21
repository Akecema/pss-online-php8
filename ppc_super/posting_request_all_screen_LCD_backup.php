<?php

/**
 * ppc_super/posting_request_all_screen_LCD_backup.php
 * Part of: PPC module (supervisor/admin tier)
 * Filename suggests: posting request all screen LCD backup
 *
 * Behavior: requires an active login session ($_SESSION['username']).
 * Database tables referenced: material_request, scan_detail, user_detail, factory_detail, mat_master_header, post_detail_header, material_request_close.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, content.php.
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
require_role($dbc, 5);
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");

//$Cdate = date ("l, j F Y ");
date_default_timezone_set('Asia/Bangkok');

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

  $today = getdate();
  $hours = $today['hours']; 
  $minutes = $today['minutes'];
  $seconds = $today['seconds'];
  $month = $today['mon']; 
  $mday = $today['mday']; 
  $year = $today['year']; 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Ingress Autoventures Co., Ltd.</title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../css/style.css" type="text/css" media="all" />
<link href="../css/ddtabmenu.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../scripts/pagination3.css" type="text/css" />
<link rel="stylesheet" href="../scripts/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="../javascript/jquery-latest.js"></script> 
<script type="text/javascript" src="../javascript/thickbox.js"></script>	
<style type="text/css">
<!--
.style3 {color: #000000}
-->
</style>
<?php

include 'content.php';

?>
</head>
<?php

function encode($ss,$ntime){
    for($i=0;$i<$ntime;$i++){
        $ss=base64_encode($ss);
    }
return $ss;
}


function decode($ss,$ntime){
    for($i=0;$i<$ntime;$i++){
        $ss=base64_decode($ss);
    }
return $ss;
}


//- First page:
$url = 'material_request_listProc.php';
$url2 = 'display_request.php';
$url3 = 'posting_request.php';


$warnaGenap = "#F4FBCA";   // warna blue grey
$warnaGanjil = "#f8f8f8";  // warna putih

?>
<script type="text/javascript">
//SYNTAX: ddtabmenu.definemenu("tab_menu_id", integer OR "auto")
ddtabmenu.definemenu("ddtabs1", 0) //initialize Tab Menu #1 with 1st tab selected
ddtabmenu.definemenu("ddtabs2", 1) //initialize Tab Menu #2 with 2nd tab selected
ddtabmenu.definemenu("ddtabs3", 1) //initialize Tab Menu #3 with 2nd tab selected
ddtabmenu.definemenu("ddtabs4", 2) //initialize Tab Menu #4 with 3rd tab selected
ddtabmenu.definemenu("ddtabs5", -1) //initialize Tab Menu #5 with NO tabs selected (-1)
</script>
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


?>
<!--<body onload="JavaScript:timedRefresh(5000);"> -->
<body>
<!-- Header -->
<!-- End Header -->
<!-- Container -->
<div id="container">
   <div class="small-nav">Material Request Board Initial Screen</div>
<div class="shell">
  <!-- Small Nav --> 
  
    <!-- End Small Nav -->
   
<!-- Message OK --><!-- End Message OK -->
    <!-- Message Error -->
    <!-- End Message Error -->
    <br />
    <!-- Main -->
    <div id="main">
      <p>

        <!-- Content -->
      </p>
      <p>&nbsp; </p>
      <div id="content">
        <!-- Box -->
        <div class="box">
          <!-- Box Head -->
          <div class="box-head">
            <h2 class="left">Material Request Board</h2>
            <div class="right">
              <label></label>
            </div>
          </div>
          <!-- End Box Head -->
          
          
          <?php
 
$query = "SELECT *, DATE_FORMAT(MR.date_mrin,'%d-%m-%Y') AS R FROM material_request AS MR, scan_detail AS SD WHERE MR.id_scan = SD.id_scan AND MR.status_request = 'Y' AND (MR.status != 'Close' AND MR.status  != 'Cancel') GROUP BY MR.temp_mrin ORDER BY MR.date_mrin DESC,MR.time_mrin DESC LIMIT $offset, $rowsperpage";
$rs = mysqli_query($dbc, $query) or trigger_error("SQL", E_USER_ERROR);
$num = mysqli_num_rows($rs);   //how many material are there?

	//echo '<br>';
	//echo '<br>';
	// echo '<div align="center">There are currently  '. $num.' record(s).</div>';
	
	
if ($num > 0) {
?>

           
<br />
       <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];   ?>">   
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:solid 1px #d5d5d5;">
              <tr>
                <th width="193" height="28" bgcolor="#E9F58D"><span class="style3">MRIN No.</span></th>
                <th width="77" height="28" bgcolor="#E9F58D"><span class="style3">Factory</span></th>
                <th width="87" height="28" bgcolor="#E9F58D"><span class="style3">Line</span></th>
                <th width="139" height="28" bgcolor="#E9F58D"><span class="style3">Request Date</span></th>
                <th width="69" bgcolor="#E9F58D"><span class="style3">Request Time</span></th>
                <th width="100" height="28" bgcolor="#E9F58D" class="ac style3">Requestor</th>
                <th width="81" bgcolor="#E9F58D" class="ac style3">Status</th>
            </tr>
          </table>
    <?php
		  
   $counter = 1;
   $no = 1;
   
   while ($row2 = mysqli_fetch_array($rs))
   {
		//$user_no = $row[0]; 

		
		if ($counter % 2 == 0)
		{ $warna = $warnaGenap;}
		else { $warna = $warnaGanjil; }	
   
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
  
  //--------------------------------------------------------------------------------------------------------------------------------------------
  //Update listing board   - MRIN disappear from listing if all component status_posting = "Close"
  //--------------------------------------------------------------------------------------------------------------------------------------------  
	  $TOT = 0.000;
	 $outs_qty = 0;
	 
	$query_tp = "SELECT *,SUM(rquantity) as TOT FROM post_detail_header WHERE mrin_no = '".db_esc($dbc, $row2['temp_mrin'])."' AND prod_order = '".db_esc($dbc, $row2['prod_order'])."' AND mvt_type = 311 AND status_posting = 'New' GROUP BY material_no";
	$result_tp  = mysqli_query($dbc, $query_tp); 
	//$row_tp = mysqli_fetch_assoc($result_tp); 
	
	while($row_tp = mysqli_fetch_assoc($result_tp))
{
    
	//echo h($row_tp["material_no"]); echo ":";
	//echo h($row_tp["TOT"]);
	//echo h($row2["bom_qty"]); echo "<br>";
	//echo h($row_tp["id_post"]);
		
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
        
		 
		 // if(mysqli_affected_rows($dbc) == 0) { //If it ran ok
		 
		   
		   // }else{
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
  

//-------------------------------------------------------Transfer Posting [Traffic Light] --------------------------
// table post_detail_header --- checking traffic licht
//--------------------------------------------------------------------------------------------------


//$date_post =  ($row2["date_mrin"].' '.$row2["time_mrin"]);
//$date_transfer = (date("Y-m-d").' '.date("H:i:s"));

//$start_date = new DateTime($date_post);
//$since_start = $start_date->diff(new DateTime($date_transfer));

//----------------------------------------------


      // $cur_time = $date_post;
      // $duration = '-20 minutes';
     //  $green_light = date($date_post, strtotime($duration, strtotime($cur_time)));   
	  // echo $green_light; 
	   
	//   $start_date2 = new DateTime($green_light);
	//   $since_start2 = $start_date2->diff(new DateTime($date_transfer));

		  ?>        
          <table width="100%" border="0" cellpadding="0" cellspacing="0"  style="border:solid 1px #d5d5d5;">
  <tr>
     <td width="113">&nbsp;<?php echo h($row2["temp_mrin"]); ?></td>
     
              <td width="80"><div align="left"><?php if($row2["status_urgent"] == "Y") 
	{
	?>
	<img src="../images/icon-urgent.gif" title="URGENT" />
	<?php
     }  ?></div></td>
    <td width="72"><div align="center"><?php echo h($row_scan["factory"]); ?></div></td>
     <td width="115" height="28"><div align="center"><?php echo h($row_scan["work_center"]); ?></div></td>
              <td width="119"><?php echo h($row2["R"]); ?>&nbsp;</td>
              <td width="70"><?php echo h($row2["time_mrin"]); ?></td>
              <td width="126"><div align="center"><?php echo h($data_u["user_fullname"]); ?></div></td>
              <td width="51">
<?php
 /*
 echo "<font color='blue'>";
 echo $since_start->d.' days<br>';
echo $since_start->h.' hours<br>';
echo $since_start->i.' minutes<br>';
echo $since_start->s.' seconds<br>';  
echo "</font>";
                */
				
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
                echo '<img src="../images/grey_icon.jpg" width="25" height="25" /> ';
}
elseif(($curr_time >= $min_20) && ($curr_time < $request_time) )
{
                echo '<img src="../images/green_icon2.jpg" width="25" height="25" /> ';
}
elseif(($curr_time >= $request_time) && ($curr_time < $plus_20) )
{
                echo '<img src="../images/yellow_icon2.jpg" width="25" height="25" /> ';
}
elseif($curr_time >= $plus_20)
{
                echo '<img src="../images/red_icon2.jpg" width="25" height="25" /> ';
}

				
/*	if(($date_post) > ($date_transfer))
	{
	?> <?php
	
	if(($since_start2->m > 0) || ($since_start2->d > 0) || ($since_start2->h > 0) || ($since_start2->i >= 20))
	{
	
	?> 
    <img src="../images/grey_icon.jpg" width="25" height="25" />
	
	<?php
	  }elseif(($since_start2->m = 0) || ($since_start2->d = 0) || ($since_start2->h = 0) || (($since_start2->i > 0) && ($since_start2->i <= 19))) 
	  {
	 ?>
    
      <img src="../images/green_icon2.jpg" width="25" height="25" />     
              
	 <?php
	 }
   
   }else{
   
    if(($since_start->m != 0) || ($since_start->d != 0) || ($since_start->h > 0) || ($since_start->i >= 20))
		
	{
   
?>
            <img src="../images/red_icon2.jpg" width="25" height="25" />
             
            <?php
	}elseif(($since_start->m = 0) || ($since_start->d = 0) || ($since_start->h = 0) || (($since_start->i >= 0) && ($since_start->i <= 19)))
	 {
	?>
          	<img src="../images/yellow_icon2.jpg" width="25" height="25" />

<?php	}elseif(($since_start2->m = 0) || ($since_start2->d = 0) || ($since_start2->h = 0) || (($since_start2->i > 0) && ($since_start2->i <= 19)))
   {


?>
			<img src="../images/green_icon2.jpg" width="25" height="25" />
    <?php
	
	}
	}//end elseif traffic
	*/
	
	?></td>
  </tr>
</table> 

   
  <?php 
		 
		 $no ++;
		  
		  $counter++; // menambah counter 
		
		    
		  } ?>
<p>&nbsp;</p>



  
   
  </center>
  <?php
   mysqli_free_result($rs); 
	}   // free up the resources 
else
{
?><center>
<table width="800" cellspacing="0" class="textboxred">
  <tr> 
    <td><div align="center"><font color="#FF0000"><strong>There are currently 
          no material request.</strong></font></div></td>
  </tr>
</table></center>
        <?php
		   } 
mysqli_close($dbc)
?>

</form>
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
    <td width="10%"><div align="center"><img src="../images/red_icon2.jpg" alt="" width="20" height="20" /></div></td>
    <td width="90%">MRIN Request has passed 20 minutes from the requested time.</td>
  </tr>
  <tr>
    <td><div align="center"><img src="../images/yellow_icon2.jpg" alt="" width="20" height="20" /></div></td>
    <td>MRIN Request has reached the requested time.</td>
  </tr>
  <tr>
    <td><div align="center"><img src="../images/green_icon2.jpg" alt="" width="20" height="20" /></div></td>
    <td>MRIN Request is now 20 minutes before the requested time.</td>
  </tr>
  <tr>
    <td><div align="center"><img src="../images/grey_icon.jpg" alt="" width="20" height="20" /></div></td>
    <td>New MRIN Request has been posted.</td>
  </tr>
</table>
<p>&nbsp;</p>           
          <!-- Table 
        </div>-->
        <!-- End Box -->
       
      </div>
      <!-- End Content -->
      <!-- Sidebar -->
      <!-- End Sidebar -->
      <div class="cl">&nbsp;</div>
    </div>
    <!-- Main -->
  </div>
</div>
<!-- End Container -->

</body>
</html>
