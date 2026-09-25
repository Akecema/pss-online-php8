<?php

/**
 * ppc/report_FTP_backflush_downloadProc.php
 * Part of: PPC module (Production Planning & Control)
 * Filename suggests: report FTP backflush downloadProc
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: sys_setup_maintain, ftp_bflush_detail, user_detail.
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
require_role($dbc, 4);

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
date_default_timezone_set('Asia/Kuala_Lumpur');
$date_tdy = date('d-m-Y H:i:s');
set_time_limit(0);

$dateF = $_GET["date1"];
$dateT = $_GET["date2"];

$namaFile = "Backflush Report.xls";

//-------Count all results------------------------//

//********* CONDITION *************

// 1. dateF
if ($dateF == "0000-00-00" ){
	$wheresql_01 = ""; $wheresql_01_args = []; }
else {
	$wheresql_01 = " AND posting_date >= ?"; $wheresql_01_args = [$dateF]; }      
								
// 2. dateT
if ($dateT == "0000-00-00" ){
	$wheresql_02 = ""; $wheresql_02_args = []; }
else {
	$wheresql_02 = " AND posting_date <= ? "; $wheresql_02_args = [$dateT]; }          
				

$where_sql =  $wheresql_01 .$wheresql_02 ; $where_args = array_merge($wheresql_01_args, $wheresql_02_args);

//********** END CONDITION **************

 
//------------------------------------count-------------------\\

$query8 = "SELECT *,DATE_FORMAT(posting_date,'%d-%m-%Y') AS R2 FROM ftp_bflush_detail WHERE status_ftp = 'Y' " .$where_sql; $query8_args = [...$where_args];
$result8 = db_query_bind($dbc, $query8, $query8_args) or die(db_fail($dbc));
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

$content .= "<p><font size='12px'><strong>".$data_setup["title_desc"] . "</strong></font></p>";
$content .= "<font size='12px'><strong>BACKFLUSH REPORT</strong></font> ";
$content .= "<br>";
$content .= "<font size='12px'><strong>FROM : ".date('d-m-Y',strtotime($dateF))." </strong></font>&nbsp;&nbsp;&nbsp; ";
$content .= "<font size='12px'><strong>TO : ".date('d-m-Y',strtotime($dateT))."</strong></font> ";
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
echo '<th width="5" bgcolor="#E9F58D">FILENAME</th>';
echo '<th width="5" bgcolor="#E9F58D">BACKFLUSH NO.</th>';
echo '<th width="10" bgcolor="#E9F58D">PLAN NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">MATERIAL NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">MATERIAL DESCRIPTION</th>';
echo '<th width="5" bgcolor="#E9F58D">QUANTITY</th>';
echo '<th width="5" bgcolor="#E9F58D">UOM</th>';
echo '<th width="5" bgcolor="#E9F58D">POSTING DATE</th>';
echo '<th width="5" bgcolor="#E9F58D">POSTING TIME</th>'; 
echo '</tr>';
echo '</table>';

 
//Display table
// query menampilkan semua data
$query = "SELECT *,DATE_FORMAT(posting_date,'%d-%m-%Y') AS R2 FROM ftp_bflush_detail WHERE status_ftp = 'Y' " .$where_sql; $query_args = [...$where_args];
$rs = db_query_bind($dbc, $query, $query_args);   //run the query.

//count how many data
$counter = 1;
$no = 1;
$i = 1;
  

while ($row2 = mysqli_fetch_array($rs))
{

	$query_u = "SELECT * FROM user_detail WHERE user_no = ?"; $query_u_args = [$row2["user_create"]];
	$result_u = db_query_bind($dbc, $query_u, $query_u_args);   //run the query.
	$data_u = mysqli_fetch_array($result_u);   //how many records are there?    


	//Display data
	echo '<table border="1" width="100%">';
	echo '<tr height="35">';
	echo '<td>'. $row2["file_name"].'</td>';
	echo '<td>'. $row2["bflush_no"].'</td>';
	echo '<td>&nbsp;'. $row2["plan_no"].'</td>';
	echo '<td>'. $row2["material_no"].'</td>';
	echo '<td>'. strtoupper($row2["material_desc"]).'</td>';
	echo '<td align="center">'. $row2["qty_ftp"].'</td>';
	echo '<td align="center">'. $row2["uom"].'</td>';
	echo '<td align="center">'. $row2["R2"].'</td>';
	echo '<td align="center">'. $row2["posting_time"].'</td>';
	
	echo '</tr>';
    echo '</table>';  
	
	$no ++;
    $counter++; 
		
}  // end while loop
 mysqli_free_result($rs); 
?>

<?php

echo iconv('utf-8', 'cp1251', "$data"); 
?>
</body>
</html>


