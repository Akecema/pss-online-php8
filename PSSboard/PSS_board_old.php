<?php

/**
 * PSSboard/PSS_board_old.php
 * Part of: PSS Board module
 * Filename suggests: PSS board old
 *
 * Behavior: no form submission, file upload, or export detected (likely a display/listing page, utility, or bootstrap/include file).
 * Database tables referenced: sys_setup_maintain, material_request, scan_detail, user_detail, factory_detail, mat_master_header, post_detail_header, material_request_close.
 * Includes: config.php, content.php, config.inc.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
include 'include/config.php';
date_default_timezone_set("Asia/Kuala_Lumpur");


//$Cdate = date ("l, j F Y ");
set_time_limit(0);

$nextpage = 1;

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------			

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $data_setup["title_desc"]; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
<link rel="stylesheet" href="scripts/pagination3.css" type="text/css" />
<link rel="stylesheet" href="scripts/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="javascript/jquery-latest.js"></script> 
<script type="text/javascript" src="javascript/thickbox.js"></script>	
<?php

include 'content.php';

$today = getdate();
$hours = $today['hours']; 
$minutes = $today['minutes'];
$seconds = $today['seconds'];
$month = $today['mon']; 
$mday = $today['mday']; 
$year = $today['year']; 
?>
<style type="text/css">
<!--
.style3 {color: #000000; font-size:18px}
.style4 {color: #FFFF00; font-size:14px}
.style5 {color: #00FF00; font-size:14px}

body {
	background-color: #000000;
}
.style6 {color: #FFFFFF; font-size: 18px; }
.style7 {color: #FFFFFF}
.style9 {color: #FFFFFF; font-size: 14px; }
.style10 {color: #FFFFFF; font-size: 9px; }

-->
</style>
</head>

<?php
//include "config.inc";


$sql2 = "SELECT *, DATE_FORMAT(MR.date_mrin,'%d-%m-%Y') AS R FROM material_request AS MR WHERE MR.status_request = 'Y' AND (MR.status != 'Close' AND MR.status != 'Cancel') GROUP BY MR.temp_mrin ORDER BY MR.date_mrin DESC,MR.time_mrin DESC LIMIT $offset, $rowsperpage ";
$result2 = mysqli_query($dbc, $sql2);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:solid 1px #141414;">
              <tr>
               
                <th width="263" height="28" bgcolor="#000066"><span class="style6">MRIN No.</span></th> 
                <th width="104" bgcolor="#000066" class="ac style3 style7"><div align="left">Status</div></th>
                <th width="121" height="28" bgcolor="#000066"><span class="style6">Factory</span></th>
                <th width="120" height="28" bgcolor="#000066"><span class="style6">Line</span></th>
                <th width="157" height="28" bgcolor="#000066"><span class="style6">Request Date</span></th>
                <th width="173" bgcolor="#000066"><span class="style6">Request Time</span></th>
                <th width="202" bgcolor="#000066"><span class="style9">Duration</span><br /> 
                <span class="style10">[Days:Hours:Minutes]</span></th>
                <th width="195" height="28" bgcolor="#000066" class="ac style3 style7">Requestor</th> 
               
  </tr>
          </table>


<?php

while ($list = mysqli_fetch_array($result2)) {


	$query_scan = "SELECT * FROM scan_detail WHERE id_scan = '".db_esc($dbc, $list["id_scan"])."'";
   	$result_scan = mysqli_query($dbc, $query_scan);
   	$row_scan = mysqli_fetch_array($result_scan);
	
	$query_again = "SELECT * FROM material_request WHERE status_request = 'Y' and id_scan = '".db_esc($dbc, $list["id_scan"])."' ORDER BY id_req ASC";
    $rs_again = mysqli_query($dbc, $query_again);   //run the query.
    $row = mysqli_fetch_array($rs_again);
	
	$query_u = "SELECT * FROM user_detail WHERE user_no = '".db_esc($dbc, $row['user_create'])."'";
	$result_u = mysqli_query($dbc, $query_u);   //run the query.
	$data_u = mysqli_fetch_array($result_u);   //how many records are there?    


 	$query3 = "SELECT * FROM factory_detail WHERE id_fac = '".db_esc($dbc, $row_scan["factory"])."'";
    $result3 = mysqli_query($dbc, $query3);
	$row3 = mysqli_fetch_array($result3);
	
	$query4_p = "SELECT * from mat_master_header as LD, mat_master_detail as SD WHERE SD.id_hdr = LD.id_hdr and SD.id_dtl = '".db_esc($dbc, $list["id_dtl"])."'";
  	$result4_p = mysqli_query($dbc, $query4_p);
 	$row4_p = mysqli_fetch_array($result4_p); 
  
  //--------------------------------------------------------------------------------------------------------------------------------------------
  //Update listing board   - MRIN disappear from listing if all component status_posting = "Close"
  //--------------------------------------------------------------------------------------------------------------------------------------------  
	  $TOT = 0.000;
	 $outs_qty = 0;
	 
	$query_tp = "SELECT *,SUM(rquantity) as TOT FROM post_detail_header WHERE mrin_no = '".db_esc($dbc, $list["temp_mrin"])."' AND prod_order = '".db_esc($dbc, $row_scan["prod_order"])."' AND mvt_type = 311 AND status_posting = 'New' GROUP BY material_no";
	$result_tp  = mysqli_query($dbc, $query_tp); 
	//$row_tp = mysqli_fetch_assoc($result_tp); 

	$outs_qty = 0;

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
	   
 $query_upd2 = "UPDATE `post_detail_header` SET status_posting = 'Close', date_close = NOW() WHERE mrin_no = '".db_esc($dbc, $list["temp_mrin"])."' AND material_no = '".db_esc($dbc, $row_tp["material_no"])."' ";
 $result_upd2 = mysqli_query($dbc, $query_upd2); 
	      
 $query_upd3 = "UPDATE `material_request` SET status = 'Close' WHERE temp_mrin = '".db_esc($dbc, $list["temp_mrin"])."' AND bom_component = '".db_esc($dbc, $row_tp["material_no"])."' ";
 $result_upd3 = mysqli_query($dbc, $query_upd3); 
 
  $query_upd4 = "UPDATE `material_request` SET status = 'Close' WHERE temp_mrin = '".db_esc($dbc, $list["temp_mrin"])."' AND (bom_qty = '0.000' OR bom_qty = '')";
 $result_upd4 = mysqli_query($dbc, $query_upd4); 
	
	 //--------------------------------------------------------------------
       //copy yg close MRIN masuk dalam MRIN history
	   //---------------------------------------------------------------------
        if($result_upd3 || $result_upd4)
		 {
		 
		   $query_upd4 = "SELECT * FROM `material_request` WHERE temp_mrin = '".db_esc($dbc, $list["temp_mrin"])."' AND status = 'Close'";
		   $result_upd4 = mysqli_query($dbc, $query_upd4);
		   $row_upd4 = mysqli_num_rows($result_upd4); 
        // $r4 = mysqli_num_rows($result_upd4);
		 
		 // if(mysqli_affected_rows($dbc) == 0) { //If it ran ok
		 
		   
		   // }else{
		   if($row_upd4 > 0 )
		   {
			
			$query_mm3 = "SELECT * FROM `material_request` WHERE temp_mrin = '".db_esc($dbc, $list["temp_mrin"])."' AND status = 'Close' AND bom_component = '".db_esc($dbc, $row_tp["material_no"])."'"; 
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
// Duration - display board
//--------------------------------------------------------------------------------------------------


$date_post =  ($list["date_mrin"].' '.$list["time_mrin"]);
$date_transfer = (date("Y-m-d").' '.date("H:i:s"));

$start_date = new DateTime($date_post);
$since_start = $start_date->diff(new DateTime($date_transfer));

//----------------------------------------------

  ?>
  
  
<table width="100%" border="0" cellpadding="0" cellspacing="0"  style="border:solid 1px #141414;">
  <tr>
    <td width="270" height="36">
    <div align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="#00CC00" size="+1"><?php echo $list["temp_mrin"]; ?></font>&nbsp;&nbsp;<?php if($row_scan["status_urgent"] == "Y") 
	{
	?>
	<img src="images/urgent_postAd.gif" title="URGENT" />
	<?php
     }  ?></div></td>
   
     <td width="101">
    <?php

        //------------------------   Traffic light-------------------------------------------------------------
		//- edit date : 5/11/2014    by : azie
		//-----------------------------------------------------------------------------------------------------
		
		
$curr_time = date("Y-m-d H:i:s"); 
$request_time = ($list["date_mrin"].' '.$list["time_mrin"]);

$min_20 = date("Y-m-d H:i:s", strtotime("$request_time - 20 minutes"));
$plus_20 = date("Y-m-d H:i:s", strtotime("$request_time + 20 minutes"));

   $start_date2 = new DateTime($min_20);
   $diff_time = $start_date2->diff(new DateTime($request_time));


if ($curr_time < $min_20)
{
                echo '<img src="images/grey.png" width="30" height="25" />  ';
}
elseif(($curr_time >= $min_20) && ($curr_time < $request_time) )
{
                echo '<img src="images/green.png" width="30" height="25" />  ';
}
elseif(($curr_time >= $request_time) && ($curr_time < $plus_20) )
{
                echo ' <img src="images/yellow.png" width="30" height="25" />  ';
}
elseif($curr_time >= $plus_20)
{
                echo ' <img src="images/red.png" width="30" height="25" />  ';
}

	?>
  </td>
    <td width="118"><div align="center"><font color="#FFFFFF" size="+1"><?php echo $row_scan["factory"]; ?></font></div></td>
    <td width="120"><div align="center"><font color="#FFFFFF" size="+1"><?php echo $row_scan["work_center"]; ?></font></div></td>
    <td width="163"><div align="center"><font color="#FFFFFF" size="+1"><?php echo $list["R"]; ?>&nbsp;</font></div></td>
    <td width="175"><div align="center"><font color="#FFFFFF"  size="+1"><?php echo $list["time_mrin"]; ?></font></div></td>
    <td width="184"><div align="right"><font color="#FFFFFF"  size="+1"><?php 
				 if(($date_post) > ($date_transfer))
	{ echo "0:0:0";    }else{  echo $since_start->d.':'.$since_start->h.':'.$since_start->i;  } ?></font></div></td>
    <td width="196"><div align="center"><font color="#FFFFFF"  size="+1"><?php echo $data_u["user_fullname"]; ?></font></div></td>
	
  </tr>
</table>    
 <?php
  
 
} 
 

?>
<!-- <div style="position:fixed;bottom:0;height:auto;margin-top:60px;width:100%;" > -->
<table width="70%" border="0" style="position:fixed;bottom:0;height:auto;margin-top:60px;width:100%; ">
  <tr>
    <td width="20%"><span class="style4">No. of MRIN </span></td>
    <td width="9%"><span class="style4">&nbsp; :</span></td>
    <td width="21%"><span class="style4">&nbsp;<?php echo $numrows; ?></span></td>
    <td width="13%"><span class="style4">Date & Time</span></td>
    <td width="9%"><span class="style4">&nbsp; :</span></td>
    <td width="27%"><span class="style4">&nbsp;<?php echo date("D M d, Y");   ?></span>&nbsp;&nbsp;<span class="style5"><?php echo date("H:i:s");  ?></span></td>
  </tr>
  <tr>
    <td><span class="style4">Page No. </span></td>
    <td><span class="style4">&nbsp; :</span></td>
    <td colspan="4"><span class="style4">&nbsp;<?php echo $currentpage.' of '.$totalpages; ?></span></td>
  </tr>
</table>
<!-- </div> -->

   