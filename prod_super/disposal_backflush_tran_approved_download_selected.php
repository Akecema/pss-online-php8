<?php

/**
 * prod_super/disposal_backflush_tran_approved_download_selected.php
 * Part of: Production module (supervisor/admin tier)
 * Filename suggests: disposal backflush tran approved download selected
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: sys_setup_maintain, request_status, reject_detail_disposal, user_detail, type_reject_detail, reason_ng_reject, type_wastage_detail, reason_wastage.
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
require_role($dbc, 3);

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------

//CR status (New)

$sta = "SELECT * from request_status WHERE status_id = '1'";
$sta_res = mysqli_query($dbc, $sta);
$rst_sta = mysqli_fetch_array($sta_res);

//CR status (Released)
$sta2 = "SELECT * from request_status WHERE status_id = '2'";
$sta_res2 = mysqli_query($dbc, $sta2);
$rst_sta2 = mysqli_fetch_array($sta_res2);	

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7'";
$sta_res7 = mysqli_query($dbc, $sta7);
$rst_sta7 = mysqli_fetch_array($sta_res7);	

//CR status (Delete)
$sta16 = "SELECT * from request_status WHERE status_id = '16'";
$sta_res16 = mysqli_query($dbc, $sta16);
$rst_sta16 = mysqli_fetch_array($sta_res16);	

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

$namaFile = "Production Disposal Report.xls";

//-------Count all results------------------------//

            $dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			$doc_disposal_no = $_GET["doc_disposal_no"];		
	
			$where_sql = '';
		 
		 // 1. DateT
                if($dateT == "0000-00-00") {
                     $wheresql_01 = ""; }
                else {
                      $wheresql_01 = " AND (date_posting <= '".db_esc($dbc, $dateT)."')"; }
		  //2. DateF 
                if ($dateF  == "0000-00-00" ){
                     $wheresql_02 = "";}
                else {
                     $wheresql_02 = " AND (date_posting >= '".db_esc($dbc, $dateF)."')";}  
					 
		 //3. Doc. Disposal No. 
                if ($doc_disposal_no == "NULL"){ 
                    $wheresql_03 = ""; }
                else {
                    $wheresql_03 = " AND doc_disposal_no = '".db_esc($dbc, $doc_disposal_no)."'"; } 
		
				
				$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03;	
	
	//********** END CONDITION **************

  $query8 = "SELECT COUNT(*) FROM reject_detail_disposal WHERE (status_part = 'PR' OR status_part = 'WS') AND doc_disposal_no != ''".$where_sql;
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
$content .= "<font size='12px'><strong>PRODUCTION DISPOSAL REPORT</strong></font> ";
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
echo '<th width="5" bgcolor="#E9F58D">COST CENTER</th>'; 
echo '<th width="5" bgcolor="#E9F58D">TYPE OF REJECT</th>';
echo '<th width="5" bgcolor="#E9F58D">REASON</th>';
echo '<th width="5" bgcolor="#E9F58D">REJECT BY</th>'; 
echo '<th width="5" bgcolor="#E9F58D">REJECT DATE</th>';
echo '<th width="5" bgcolor="#E9F58D">REMARKS REJECT</th>';
echo '<th width="5" bgcolor="#E9F58D">TYPE OF WASTAGE</th>';
echo '<th width="5" bgcolor="#E9F58D">REASON WASTAGE</th>';
echo '<th width="5" bgcolor="#E9F58D">WASTAGE BY</th>'; 
echo '<th width="5" bgcolor="#E9F58D">WASTAGE DATE</th>'; 
echo '<th width="5" bgcolor="#E9F58D">DISPOSAL BY</th>';
echo '<th width="5" bgcolor="#E9F58D">DATE DISPOSAL</th>';
echo '<th width="5" bgcolor="#E9F58D">APPROVED BY 1</th>';
echo '<th width="5" bgcolor="#E9F58D">DATE APPROVED BY 1</th>';
echo '<th width="5" bgcolor="#E9F58D">REMARKS APPROVED 1</th>';
echo '<th width="5" bgcolor="#E9F58D">APPROVED BY 2</th>';
echo '<th width="5" bgcolor="#E9F58D">DATE APPROVED BY 2</th>';
echo '<th width="5" bgcolor="#E9F58D">REMARKS APPROVED 2</th>';

echo '</tr>';
echo '</table>';
 
//Display table
// query menampilkan semua data

  
$query = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') as R, DATE_FORMAT(date_posting,'%d-%m-%Y') as R2, DATE_FORMAT(date_reject,'%d-%m-%Y') as R3, DATE_FORMAT(date_wastage,'%d-%m-%Y') as R4, DATE_FORMAT(date_disposal,'%d-%m-%Y') as R5, DATE_FORMAT(date_approve,'%d-%m-%Y') as R6, DATE_FORMAT(date_approve2,'%d-%m-%Y') as R7 FROM reject_detail_disposal WHERE (status_part = 'PR' OR status_part = 'WS') AND doc_disposal_no != '' AND status_disposal != '".db_esc($dbc, $rst_sta["status_desc"])."' AND status_disposal != '".db_esc($dbc, $rst_sta16["status_desc"])."'".$where_sql." ORDER BY doc_disposal_no ASC";
$rs = mysqli_query($dbc, $query);   //run the query.
//$num = mysqli_num_rows($rs);   //how many material are there?

//count how many data
$counter = 1;
$no = 1;
$i = 1;
$qty_final = 0;
  

while ($row2 = mysqli_fetch_array($rs))
{

	$query_u = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $row2["user_reject"])."'";
	$result_u = mysqli_query($dbc, $query_u);   //run the query.
	$data_u = mysqli_fetch_array($result_u);   //how many records are there?    
	
	$query_u2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $row2["user_wastage"])."'";
	$result_u2 = mysqli_query($dbc, $query_u2);   //run the query.
	$data_u2 = mysqli_fetch_array($result_u2);   //how many records are there?   
	
	$query_u3 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $row2["user_disposal"])."'";
	$result_u3 = mysqli_query($dbc, $query_u3);   //run the query.
	$data_u3 = mysqli_fetch_array($result_u3);   //how many records are there?     
	
	$query_u4 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $row2["approve_by"])."'";
	$result_u4 = mysqli_query($dbc, $query_u4);   //run the query.
	$data_u4 = mysqli_fetch_array($result_u4);   //how many records are there?  
	
	$query_u5 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $row2["approve_by2"])."'";
	$result_u5 = mysqli_query($dbc, $query_u5);   //run the query.
	$data_u5 = mysqli_fetch_array($result_u5);   //how many records are there?  
	
	$query_type = "SELECT * FROM type_reject_detail WHERE id_type = '".db_esc($dbc, $row2['type_reject'])."' ORDER BY id_type ASC";
    $result_type = mysqli_query($dbc, $query_type);
    $row_type = mysqli_fetch_array($result_type); 
	
	$query_reason = "SELECT * FROM reason_ng_reject WHERE id_reject = '".db_esc($dbc, $row2['reason_reject'])."' ORDER BY id_reject ASC";
    $result_reason = mysqli_query($dbc, $query_reason);
    $row_reason = mysqli_fetch_array($result_reason);
	
	$query_type_wastage = "SELECT * FROM type_wastage_detail WHERE id_wastage = '".db_esc($dbc, $row2['type_wastage'])."' ORDER BY id_wastage ASC";
    $result_type_wastage = mysqli_query($dbc, $query_type_wastage);
    $row_type_wastage = mysqli_fetch_array($result_type_wastage); 
	
	$query_reason_wastage = "SELECT * FROM reason_wastage WHERE id_reason_wastage = '".db_esc($dbc, $row2['reason_wastage'])."' ORDER BY id_reason_wastage ASC";
    $result_reason_wastage = mysqli_query($dbc, $query_reason_wastage);
    $row_reason_wastage = mysqli_fetch_array($result_reason_wastage);
	
	
	
	
	//quantity output
		if($row2["qty_actual"] != "0.000")
	{
		$qty_final = $row2["qty_actual"];
	}elseif($row2["qty_NG"] != "0.000")
	{
		$qty_final = $row2["qty_NG"];
	}elseif($row2["qty_qc_ok"] != "0.000")
	{
		$qty_final = $row2["qty_qc_ok"];
	}elseif($row2["qty_qc_NG"] != "0.000")
	{
		$qty_final = $row2["qty_qc_NG"];
	}elseif($row2["qty_wastage"] != "0.000")
	{
		$qty_final = $row2["qty_wastage"];
		
	}else{
		$qty_final == " ";
	}

	//Display data
	echo '<table border="1" width="100%">';
	echo '<tr height="35">';
    echo '<td>'. $no.'</td>';
	echo '<td width="80">'. $row2["model_code"].'</td>';
	echo '<td>&nbsp;<font color="#0000FF">'. $row2["doc_disposal_no"].'</font></td>';
	echo '<td>&nbsp;'. $row2["bflush_qqc_no"].'</td>';
	echo '<td>&nbsp;'. $row2["plan_no"].'</td>';
	echo '<td>'. $row2["material_no"].'</td>';
	echo '<td>'. strtoupper($row2["material_desc"]).'</td>';
	echo '<td>&nbsp;'. $row2["R2"].'</td>';
	echo '<td align="center">'. $qty_final.'</td>';
	echo '<td align="center">'. $row2["UOM_unit"].'</td>';
	echo '<td width="60">'. $row2["ploc_prod_reject"]. '</td>';
    echo '<td width="60">'. $row2["work_center"]. '</td>'; 
	echo '<td width="60">'. $row2["cost_center"]. '</td>'; 
	echo '<td width="80">'. $row_type["type_desc"].'</td>';
    echo '<td width="80">'. $row_reason["reject_desc"].'</td>';
	echo '<td width="140">'. $data_u["user_fullname"].'</td>';
	echo '<td width="140">&nbsp;'. $row2["R3"].'</td>';
	echo '<td width="140">'. $row2["remarks"].'</td>';
	echo '<td width="80">'. $row_type_wastage["wastage_desc"].'</td>';
	echo '<td width="80">'. $row_reason_wastage["reason_wastage_desc"].'</td>';
    echo '<td width="140">'. $data_u2["user_fullname"].'</td>';
	echo '<td width="140">&nbsp;'. $row2["R4"].'</td>';
	echo '<td width="140">'. $data_u3["user_fullname"].'</td>';
	echo '<td width="140">&nbsp;'. $row2["R5"].'</td>';
	echo '<td width="140">'. $data_u4["user_fullname"].'</td>';
	echo '<td width="140">&nbsp;'. $row2["R6"].'</td>';
	echo '<td width="140">'. $row2["remark_approve"].'</td>';
	echo '<td width="140">'. $data_u5["user_fullname"].'</td>';
	echo '<td width="140">&nbsp;'. $row2["R7"].'</td>';
	echo '<td width="140">'. $row2["remark_approve2"].'</td>';
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


