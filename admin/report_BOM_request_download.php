<?php

/**
 * admin/report_BOM_request_download.php
 * Part of: Admin module
 * Filename suggests: report BOM request download
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: sys_setup_maintain, mat_master_detail.
 * Includes: config.php.
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
require_role($dbc, 1);

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
date_default_timezone_set('Asia/Bangkok');
$date_tdy = date('d-m-Y H:i:s');
set_time_limit(0);
$date_filename = date('dmY');

            

$namaFile = "BOM_".$date_filename."Download.xls";
 //convert material no kpd id_hdr


    $query8 = "SELECT * FROM mat_master_detail ORDER BY material ASC";
    $result8 = mysqli_query($dbc, $query8) or die(mysqli_error($dbc));
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

$content .= "<p><font size='12px'><strong>INGRESS AUTOVENTURES CO,.LTD</strong></font></p>";
$content .= "<font size='12px'><strong>BOM Data Download</strong></font> ";
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
echo '<th width="5" bgcolor="#E9F58D">NO./th>';
echo '<th width="5" bgcolor="#E9F58D">MATERIAL NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">BOM CATEGORY</th>';
echo '<th width="5" bgcolor="#E9F58D">BOM ID</th>';
echo '<th width="5" bgcolor="#E9F58D">ALTERNATIVE BOM</th>';
echo '<th width="5" bgcolor="#E9F58D">PLANT</th>';
echo '<th width="5" bgcolor="#E9F58D">SLOC</th>';
echo '<th width="5" bgcolor="#E9F58D">BOM COMPONENT</th>';
echo '<th width="5" bgcolor="#E9F58D">BOM COMPONENT DESC</th>';
echo '<th width="5" bgcolor="#E9F58D">MATERIAL GROUP</th>';
echo '<th width="5" bgcolor="#E9F58D">BOM ITEM CATEGORY</th>'; 
echo '<th width="5" bgcolor="#E9F58D">BOM ITEM NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">COMPONENT UOM</th>';
echo '<th width="5" bgcolor="#E9F58D">CONSUMPTION</th>';
echo '<th width="5" bgcolor="#E9F58D">MATERIAL TYPE</th>';
echo '<th width="5" bgcolor="#E9F58D">COMPONENT USAGE</th>';
echo '<th width="5" bgcolor="#E9F58D">DATE VALID FROM</th>';
echo '<th width="5" bgcolor="#E9F58D">DATE CREATE BOM</th>';
echo '<th width="5" bgcolor="#E9F58D">STATUS BOM</th>';
echo '</tr>';
echo '</table>';
 
//Display table
// query menampilkan semua data
$query = "SELECT *,DATE_FORMAT(valid_from,'%d-%m-%Y') AS R, DATE_FORMAT(date_create_bom,'%d-%m-%Y') AS R2 FROM mat_master_detail ORDER BY material ASC";
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
   
	
	//Display data
	echo '<table border="1" width="100%">';
	echo '<tr height="35">';
	echo '<td align="center">'. $no .'</td>';
	echo '<td>'. strtoupper($row2["material"]).'</td>';
	echo '<td align="center">'. strtoupper($row2["bom_category"]).'</td>';
	echo '<td>'. strtoupper($row2["bom"]).'</td>';
	echo '<td align="center">'. strtoupper($row2["alternative_bom"]).'</td>';
	echo '<td>'. strtoupper($row2["plant"]).'</td>';
    echo '<td>'. $row2["sloc"].'</td>';
	echo '<td>'. strtoupper($row2["bill_component"]).'</td>';
	echo '<td>'. strtoupper($row2["material_desc_c"]).'</td>';
    echo '<td>'. $row2["matl_group"].'</td>';
	echo '<td align="center">'. $row2["bom_item_category"].'</td>';
	echo '<td align="center">'. $row2["bom_item_no"].'</td>';
    echo '<td align="center">'. strtoupper($row2["comp_unit"]).'</td>';
	echo '<td align="center">'. $row2["consumption"].'</td>';
	echo '<td align="center">'. strtoupper($row2["mat_type"]).'</td>';
	echo '<td align="center">'. $row2["usage_c"].'</td>';
	echo '<td align="center">'. $row2["R"].'</td>';
	echo '<td align="center">'. $row2["R2"].'</td>';
	echo '<td align="center">'. $row2["bom_status"].'</td>';
	echo '</tr>';
	
    echo '</table>';  
	
	$no ++;
    $counter++; // menambah counter 
		   
		   //}// end if
		
		    
		
		  

}  // end while loop
 mysqli_free_result($rs); 
?>

<?php

echo iconv('utf-8', 'cp1251', "$data"); 
?>
</body>
</html>


