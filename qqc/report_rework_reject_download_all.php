<?php

/**
 * qqc/report_rework_reject_download_all.php
 * Part of: QQC module (Quality)
 * Filename suggests: report rework reject download all
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: sys_setup_maintain, reject_detail_disposal, user_detail, type_reject_detail, reason_ng_reject.
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
require_role($dbc, 10);

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

$namaFile = "Rework Reject Report.xls";

//-------Count all results------------------------//



  $query8 = "SELECT COUNT(*) FROM reject_detail_disposal AS MR, work_center_detail AS SR WHERE SR.id_work = MR.work_center AND MR.status_part = 'QC' AND MR.qty_qc_NG != ''";
   $result8 = mysqli_query($dbc, $query8) or die(db_fail($dbc));
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
$content .= "<font size='12px'><strong>REWORK REJECT REPORT</strong></font> ";
$content .= "<br>";
$content .= "Date : " .$date_tdy."&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; ";
$content .= "Record Count : ".$num_rows[0];
$content .= "<br>";

echo $content;
echo '<br>';
echo "<br>";  

 //-------Count all results------------------------//
	
echo '<table border="1" width="100%">';
echo '<tr height="35">';
echo '<th width="5" bgcolor="#E9F58D">NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">MODEL</th>';
echo '<th width="5" bgcolor="#E9F58D">DISPOSAL DOC. NO.</th>'; 
echo '<th width="10" bgcolor="#E9F58D">DOCUMENT NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">PLANNED ORDER NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">PART NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">PART DESCRIPTION</th>';
echo '<th width="5" bgcolor="#E9F58D">POSTING DATE</th>';
echo '<th width="5" bgcolor="#E9F58D">QUANTITY</th>';
echo '<th width="5" bgcolor="#E9F58D">UOM</th>';
echo '<th width="5" bgcolor="#E9F58D">FROM LOCATION</th>';
echo '<th width="5" bgcolor="#E9F58D">WORK CENTER</th>'; 
echo '<th width="5" bgcolor="#E9F58D">TYPE OF REJECT</th>';
echo '<th width="5" bgcolor="#E9F58D">REASON</th>';
echo '<th width="5" bgcolor="#E9F58D">REMARKS</th>';
echo '<th width="5" bgcolor="#E9F58D">REJECT BY</th>'; 
echo '<th width="5" bgcolor="#E9F58D">REJECT DATE</th>'; 
echo '</tr>';
echo '</table>';
 
//Display table
// query menampilkan semua data

  
$query = "SELECT *, DATE_FORMAT(MR.date_plan,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2, DATE_FORMAT(MR.date_reject,'%d-%m-%Y %H:%i:%s') as R3 FROM reject_detail_disposal AS MR, work_center_detail AS SR WHERE SR.id_work = MR.work_center AND MR.status_part = 'QC' AND MR.qty_qc_NG != '' ORDER BY MR.plan_no ASC";
$rs = mysqli_query($dbc, $query);   //run the query.
//$num = mysqli_num_rows($rs);   //how many material are there?

//count how many data
$counter = 1;
$no = 1;
$i = 1;
  

while ($row2 = mysqli_fetch_array($rs))
{

	$query_u = "SELECT * FROM user_detail WHERE username = ?"; $query_u_args = [$row2["user_reject"]];
	$result_u = db_query_bind($dbc, $query_u, $query_u_args);   //run the query.
	$data_u = mysqli_fetch_array($result_u);   //how many records are there?    
	
		$query_type = "SELECT * FROM type_reject_detail WHERE id_type = ? ORDER BY id_type ASC"; $query_type_args = [$row2['type_reject']];
    $result_type = db_query_bind($dbc, $query_type, $query_type_args);
    $row_type = mysqli_fetch_array($result_type); 
	
	$query_reason = "SELECT * FROM reason_ng_reject WHERE id_reject = ? ORDER BY id_reject ASC"; $query_reason_args = [$row2['reason_reject']];
    $result_reason = db_query_bind($dbc, $query_reason, $query_reason_args);
    $row_reason = mysqli_fetch_array($result_reason);
	
	
	//quantity output
		if($row2["qty_qc_ok"] != "0.000")
	{
		$qty_final = $row2["qty_qc_ok"];
	}elseif($row2["qty_qc_NG"] != "0.000")
	{
		$qty_final = $row2["qty_qc_NG"];
	}else{
		$qty_final == " ";
	}

	//Display data
	echo '<table border="1" width="100%">';
	echo '<tr height="35">';
    echo '<td>'. $no.'</td>';
	echo '<td width="80">'. $row2["model_code"].'</td>';
	echo '<td>&nbsp;'. $row2["doc_disposal_no"].'</td>';
	echo '<td>&nbsp;'. $row2["bflush_qqc_no"].'</td>';
	echo '<td>&nbsp;'. $row2["plan_no"].'</td>';
	echo '<td>'. $row2["material_no"].'</td>';
	echo '<td>'. strtoupper($row2["material_desc"]).'</td>';
	echo '<td>&nbsp;'. $row2["R2"].'</td>';
	echo '<td align="center">'.intval($qty_final).'</td>';
	echo '<td align="center">'. $row2["UOM_unit"].'</td>';
	echo '<td width="60">'. $row2["ploc_qc_reject"]. '</td>';
    echo '<td width="60">'. $row2["work_center"]. '</td>'; 
	echo '<td width="80">'. $row_type["type_desc"].'</td>';
    echo '<td width="80">'. $row_reason["reject_desc"].'</td>';
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


