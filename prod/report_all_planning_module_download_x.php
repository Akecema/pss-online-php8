<?php

/**
 * prod/report_all_planning_module_download_x.php
 * Part of: Production module
 * Filename suggests: report all planning module download x
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: sys_setup_maintain, pps_detail, pps_detail_transaction, qqc_transaction.
 * Includes: config.php.
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

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $data_setup["title_desc"]; ?></title>
 
</head>

<body>

<?php

//if(isset($_POST['download'])) 
//{ // handle the form.
//date_default_timezone_set('Asia/Kuala Lumpur');
$date_tdy = date('d-m-Y H:i:s');
set_time_limit(0);
 

$namaFile = "Report Planned Order_".$date_tdy.".xls";
 //convert material no kpd id_hdr
			
		
    $query8 = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') as R, DATE_FORMAT(date_posting,'%d-%m-%Y') as R2 FROM pps_detail ORDER BY plan_no ASC";
  $result8 = mysqli_query($dbc, $query8) or die(db_fail($dbc));
  $num_rows = mysqli_num_rows($result8);


//---------------------------end count




//header("Content-type: application/octet-stream"); 
header('Content-type: application/excel');                                  
header('Content-Disposition: attachment; filename='.$namaFile.'');
header('Content-Type: image/jpeg');
header("Pragma: no-cache");
header("Expires: 0");

$content = "";
$data = "";	

//Create report header 

$content .= "<p><font size='12px'><strong> ".$data_setup["title_desc"] ."</strong></font></p>";
$content .= "<font size='12px'><strong>PLANNED ORDER REPORT</strong></font> ";
$content .= "<br>";
/*$content .= "<font size='12px'><strong>FROM : ".$dateF." </strong></font>&nbsp;&nbsp;&nbsp; ";
$content .= "<font size='12px'><strong>TO : ".$dateT."</strong></font> ";*/
$content .= "<br>";
$content .= "<br>";
$content .= "Date : " .$date_tdy."&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; ";
$content .= "Record Count : ".$num_rows;
$content .= "<br>";

echo $content;
echo '<br>';
echo "<br>";  
 //-------Count all results------------------------//
	

echo '<table border="1" width="100%">';
echo '<tr height="35">';
echo '<th width="5" bgcolor="#E9F58D">PLAN NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">WORK CENTER</th>';
echo '<th width="5" bgcolor="#E9F58D">STATUS</th>';
echo '<th width="5" bgcolor="#E9F58D">LOCATION</th>';
echo '<th width="5" bgcolor="#E9F58D">QC LOCATION</th>';
echo '<th width="5" bgcolor="#E9F58D">&nbsp;</th>';
echo '<th width="5" bgcolor="#E9F58D">DOCUMENT NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">POSTING DATE</th>';
echo '<th width="5" bgcolor="#E9F58D">DOCUMENT DATE</th>';
echo '<th width="5" bgcolor="#E9F58D">OUTPUT QUANTITY</th>';
echo '<th width="5" bgcolor="#E9F58D">OUTPUT STATUS</th>';
echo '</tr>';
echo '</table>';
 
//Display table
// query menampilkan semua data
$query = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') as R, DATE_FORMAT(date_posting,'%d-%m-%Y') as R2 FROM pps_detail ORDER BY plan_no ASC";
$rs = mysqli_query($dbc, $query);   //run the query.

//count how many data
   $counter = 1;
   $no = 1;
   $i = 1;
   $variance_qty = 0;
   $bq = 0;
   $rq = 0;

 while ($row2 = mysqli_fetch_array($rs))
   {
	   
	 echo '<table border="1" width="100%">';
	 echo '<tr height="35">';
	 echo '<td>'. $row2["plan_no"].'</td>';  
	 echo '<td>'. strtoupper($row2["work_center"]).'</td>';  
	 echo '<td>'. strtoupper($row2["status_pps"]).'</td>';  
	 echo '<td>';  

   $query_display = "SELECT *, DATE_FORMAT(MR.date_plan,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2, DATE_FORMAT(MR.date_create,'%d-%m-%Y') as R3 FROM pps_detail_transaction AS MR WHERE MR.pps_id = ?"; $query_display_args = [$row2["id"]];
   $result_display = db_query_bind($dbc, $query_display, $query_display_args);   //run the query.
   
   while ($row_display = mysqli_fetch_array($result_display))
   {
  
  
   $query_display2 = "SELECT *, DATE_FORMAT(M.date_plan,'%d-%m-%Y') as J, DATE_FORMAT(M.date_qc_posting,'%d-%m-%Y') as J2, DATE_FORMAT(M.date_create,'%d-%m-%Y') as J3 FROM qqc_transaction AS M WHERE M.bflush_no = ?"; $query_display2_args = [$row_display["bflush_no"]];
$result_display2 = db_query_bind($dbc, $query_display2, $query_display2_args);   //run the query.


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
	
	?>
	
<?php	
	//Display data
	/*echo '<table border="1" width="100%">';
	echo '<tr height="35">';
	echo '<td>';*/
	
	if ($row_display > 0)
	{
	
	 ?>
	  <tr class="gradeX">
        <td width="60">&nbsp;</td>
        <td width="60">&nbsp;</td>
        <td width="60">&nbsp;</td>
        <td width="60"><?php echo $row_display["ploc"]; ?></td>
        <td width="60">&nbsp;</td>
        <td width="60"><?php echo $no; ?><input name="uid" type="hidden" value="<?php echo $row_display["bflush_no"]; ?> "></td>
        <td><font color="#0000CC"><?php echo $row_display["bflush_no"]; ?></font></td>
        <td width="188"><?php echo $row_display["R2"]; ?></td>
        <td width="188"><?php echo $row_display["R3"]; ?></td>
        <td width="167"><?php echo $qty_final; ?></td>
        <td width="161"><?php echo $status_output; ?></td>
      </tr>  
	
	
	<?php
	} // end $row_display
	
	 $no2 = "a"; 
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
        <td width="60">&nbsp;</td>
        <td width="60">&nbsp;</td>
        <td width="60">&nbsp;</td>
        <td width="60"><?php echo $row_rst_display2["ploc"]; ?></td>
        <td width="60"><?php echo $row_rst_display2["ploc_qc"]; ?></td>
        <td width="60"><?php echo $no2; ?><input name="uid2" type="hidden" value="<?php echo $row_rst_display2["qqc_no"]; ?> "></td>
        <td><font color="#669999"><?php echo $row_rst_display2["qqc_no"]; ?></font></td>
        <td width="188"><?php echo $row_rst_display2["J2"]; ?></td>
        <td width="188"><?php echo $row_rst_display2["J3"]; ?></td>
        <td width="167"><?php echo $qty_final2; ?></td>
        <td width="161"><?php echo $status_output2; ?></td>
      </tr> 
      
      <?php 
	      $no2 ++;
	   }// end $row_rst_display2
		 
	}	// while $row_rst_display2  
	$no ++;
	$counter++; // menambah counter 
		   
		   //}// end if
	 echo '</td>'; 	
		
} // while $row_display  
	  	echo '<td align="center">&nbsp;</td>';  
		echo '<td align="center">&nbsp;</td>';
		echo '<td align="center">&nbsp;</td>';	
		echo '<td align="center">&nbsp;</td>';
		echo '<td align="center">&nbsp;</td>';
		echo '<td align="center">&nbsp;</td>'; 
		echo '<td align="center">&nbsp;</td>'; 
	    echo '</tr>';
        echo '</table>';  	  

}  // end while loop $row2
 mysqli_free_result($rs); 
?>

<?php

echo iconv('utf-8', 'cp1251', "$data"); 
?>
</body>
</html>


