<?php

/**
 * qqc_super/report_wastage_reject_download.php
 * Part of: QQC module (supervisor/admin tier)
 * Filename suggests: report wastage reject download
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: sys_setup_maintain, reject_detail_disposal, user_detail, type_wastage_detail, reason_wastage.
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
require_role($dbc, 11);

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

$namaFile = "Wastage Reject Report.xls";

//-------Count all results------------------------//



    $dateF = $_GET["date1"];
	$dateT = $_GET["date2"];
	$disposal_doc = $_GET["disposal_doc"];
	$disposal_doc2 = $_GET["disposal_doc2"];
	
	//********* CONDITION *************
	
	$where_sql = ''; $where_args = [];
	
	// 1. DateF
	if($dateF == "0000-00-00") {
		 $wheresql_01 = ""; $wheresql_01_args = []; }
	else {
		  $wheresql_01 = " AND (MR.date_posting >= ? )"; $wheresql_01_args = ['' . $dateF . '% 00:00:00']; }
		  
	// 2. DateT
	if($dateT == "0000-00-00") {
		 $wheresql_02 = ""; $wheresql_02_args = []; }
	else {
		  $wheresql_02 = " AND (MR.date_posting <= ? )"; $wheresql_02_args = ['' . $dateT . ' 00:00:00']; }
			
	//3. Disposal Doc. No.
	if ($disposal_doc == "NULL"){ 
		$wheresql_03 = ""; $wheresql_03_args = []; }
	else {
		$wheresql_03 = " AND MR.doc_disposal_no >= ?"; $wheresql_03_args = [$disposal_doc]; } 
		
	
	//4. Disposal Doc. No. To
	if ($disposal_doc2 == "NULL"){ 
		$wheresql_04 = ""; $wheresql_04_args = []; }
	else {
		$wheresql_04 = " AND MR.doc_disposal_no <= ?"; $wheresql_04_args = [$disposal_doc2]; } 
		
			

	$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04; $where_args = array_merge($wheresql_01_args, $wheresql_02_args, $wheresql_03_args, $wheresql_04_args);	
	
	
	//********** END CONDITION **************

  $query8 = "SELECT COUNT(*) FROM reject_detail_disposal AS MR WHERE MR.status_part = 'WQ' AND MR.qty_wastage != '' AND MR.doc_disposal_no != '' ".$where_sql; $query8_args = [...$where_args];
   $result8 = db_query_bind($dbc, $query8, $query8_args) or die(db_fail($dbc));
   $num_rows = mysqli_fetch_row($result8);


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
$content .= "<font size='12px'><strong>WASTAGE REJECT REPORT</strong></font> ";
$content .= "<br>";
$content .= "Date : " .$date_tdy."&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; ";
$content .= "Record Count : ".$num_rows[0];
$content .= "<br>";

echo $content;
echo "<br>";
echo "<br>";  

 //-------Count all results------------------------//
	
echo '<table border="1" width="100%">';
echo '<tr height="35">';
echo '<th width="5" bgcolor="#E9F58D">NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">DISPOSAL DOC. NO.</th>'; 
echo '<th width="5" bgcolor="#E9F58D">POSTING DATE</th>';
echo '<th width="5" bgcolor="#E9F58D">MODEL</th>';
echo '<th width="5" bgcolor="#E9F58D">PART NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">PART DESCRIPTION</th>';
echo '<th width="5" bgcolor="#E9F58D">QUANTITY</th>';
echo '<th width="5" bgcolor="#E9F58D">UOM</th>';
echo '<th width="5" bgcolor="#E9F58D">FROM LOCATION</th>';
echo '<th width="5" bgcolor="#E9F58D">TYPE OF WASTAGE</th>';
echo '<th width="5" bgcolor="#E9F58D">REASON</th>';
echo '<th width="5" bgcolor="#E9F58D">REMARKS</th>';
echo '<th width="5" bgcolor="#E9F58D">WASTAGE &amp; REJECT BY</th>'; 
echo '<th width="5" bgcolor="#E9F58D">WASTAGE &amp; REJECT DATE</th>'; 
echo '</tr>';
echo '</table>';
 
//Display table
// query menampilkan semua data

  
$query = "SELECT *, DATE_FORMAT(MR.date_plan,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2, DATE_FORMAT(MR.date_wastage,'%d-%m-%Y %H:%i:%s') as R3 FROM reject_detail_disposal AS MR WHERE MR.status_part = 'WQ' AND MR.qty_wastage != '' AND MR.doc_disposal_no != ''".$where_sql." ORDER BY MR.plan_no ASC"; $query_args = [...$where_args];
$rs = db_query_bind($dbc, $query, $query_args);   //run the query.
//$num = mysqli_num_rows($rs);   //how many material are there?

//count how many data
$counter = 1;
$no = 1;
$i = 1;
  

while ($row2 = mysqli_fetch_array($rs))
{

	$query_u = "SELECT * FROM user_detail WHERE username = ?"; $query_u_args = [$row2["user_wastage"]];
	$result_u = db_query_bind($dbc, $query_u, $query_u_args);   //run the query.
	$data_u = mysqli_fetch_array($result_u);   //how many records are there?    
	
	$query_type = "SELECT * FROM type_wastage_detail WHERE id_wastage = ? ORDER BY id_wastage ASC"; $query_type_args = [$row2['type_wastage']];
    $result_type = db_query_bind($dbc, $query_type, $query_type_args);
    $row_type = mysqli_fetch_array($result_type); 
	
	$query_reason = "SELECT * FROM reason_wastage WHERE id_reason_wastage = ? ORDER BY id_reason_wastage ASC"; $query_reason_args = [$row2['reason_wastage']];
    $result_reason = db_query_bind($dbc, $query_reason, $query_reason_args);
    $row_reason = mysqli_fetch_array($result_reason);
	

	//Display data
    echo '<table border="1" width="100%">';
	echo '<tr height="35">';
    echo '<td>'. $no.'</td>';
	echo '<td>&nbsp;'. $row2["doc_disposal_no"].'</td>';
	echo '<td>&nbsp;'. $row2["R2"].'</td>';
	echo '<td width="80">'. $row2["model_code"].'</td>';
	echo '<td>'. $row2["material_no"].'</td>';
	echo '<td>'. strtoupper($row2["material_desc"]).'</td>';
	echo '<td align="center">'. $row2["qty_wastage"].'</td>';
	echo '<td align="center">'. $row2["UOM_unit"].'</td>';
	echo '<td width="60">'. $row2["ploc"]. '</td>';
	echo '<td width="80">'. $row_type['wastage_desc'].'</td>';
    echo '<td width="80">'. $row_reason['reason_wastage_desc'].'</td>';
    echo '<td width="140">'. $row2["remarks"].'</td>';
	echo '<td width="140">'. $data_u["user_fullname"].'</td>';
	echo '<td width="140">&nbsp;'. $row2["R3"].'</td>';
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


