<?php
session_start();
$username = $_SESSION['username'];
include '../include/config.php';

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysql_query($query_setup);   //run the query.
$num_setup = mysql_num_rows($rs_setup);   //how many material are there?
$data_setup = mysql_fetch_array($rs_setup);
//----------------------------------------------------

//CR status (New)

$sta = "SELECT * from request_status WHERE status_id = '1'";
$sta_res = mysql_query($sta);
$rst_sta = mysql_fetch_array($sta_res);

//CR status (Released)
$sta2 = "SELECT * from request_status WHERE status_id = '2'";
$sta_res2 = mysql_query($sta2);
$rst_sta2 = mysql_fetch_array($sta_res2);	

//CR status (Approved)
$sta3 = "SELECT * from request_status WHERE status_id = '3'";
$sta_res3 = mysql_query($sta3);
$rst_sta3 = mysql_fetch_array($sta_res3);	

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7'";
$sta_res7 = mysql_query($sta7);
$rst_sta7 = mysql_fetch_array($sta_res7);	

//CR status (Delete)
$sta16 = "SELECT * from request_status WHERE status_id = '16'";
$sta_res16 = mysql_query($sta16);
$rst_sta16 = mysql_fetch_array($sta_res16);	

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

$namaFile = "QA/QC Disposal Report.xls";

//-------Count all results------------------------//



  $query8 = "SELECT COUNT(*) FROM reject_detail_disposal WHERE (status_part = 'QC' OR status_part = 'WQ') AND doc_disposal_no != '' AND status_disposal = '".$rst_sta3["status_desc"]."'";
   $result8 = mysql_query($query8) or die(mysql_error());
   $num_rows = mysql_fetch_row($result8);


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
$content .= "<font size='12px'><strong>QA/QC DISPOSAL REPORT</strong></font> ";
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
echo '<th width="5" bgcolor="#E9F58D">APPROVED BY</th>';
echo '<th width="5" bgcolor="#E9F58D">DATE APPROVED BY</th>';
echo '<th width="5" bgcolor="#E9F58D">REMARKS APPROVED</th>';
echo '</tr>';
echo '</table>';
 
//Display table
// query menampilkan semua data

  
$query = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') as R, DATE_FORMAT(date_posting,'%d-%m-%Y') as R2, DATE_FORMAT(date_reject,'%d-%m-%Y') as R3, DATE_FORMAT(date_wastage,'%d-%m-%Y') as R4, DATE_FORMAT(date_disposal,'%d-%m-%Y') as R5, DATE_FORMAT(date_approve,'%d-%m-%Y') as R6, DATE_FORMAT(date_approve2,'%d-%m-%Y') as R7 FROM reject_detail_disposal WHERE (status_part = 'QC' OR status_part = 'WQ') AND doc_disposal_no != '' AND status_disposal = '".$rst_sta3["status_desc"]."' ORDER BY doc_disposal_no ASC ";
$rs = mysql_query($query);   //run the query.
//$num = mysql_num_rows($rs);   //how many material are there?

//count how many data
$counter = 1;
$no = 1;
$i = 1;
$qty_final = 0;
  

while($row2 = mysql_fetch_array($rs))
{

	$query_u = "SELECT * FROM user_detail WHERE username = '".$row2["user_reject"]."'";
	$result_u = mysql_query($query_u);   //run the query.
	$data_u = mysql_fetch_array($result_u);   //how many records are there?    
	
	$query_u2 = "SELECT * FROM user_detail WHERE username = '".$row2["user_wastage"]."'";
	$result_u2 = mysql_query($query_u2);   //run the query.
	$data_u2 = mysql_fetch_array($result_u2);   //how many records are there?   
	
	$query_u3 = "SELECT * FROM user_detail WHERE username = '".$row2["user_disposal"]."'";
	$result_u3 = mysql_query($query_u3);   //run the query.
	$data_u3 = mysql_fetch_array($result_u3);   //how many records are there?     
	
	$query_u4 = "SELECT * FROM user_detail WHERE username = '".$row2["approve_by"]."'";
	$result_u4 = mysql_query($query_u4);   //run the query.
	$data_u4 = mysql_fetch_array($result_u4);   //how many records are there?  
	
	
	$query_type = "SELECT * FROM type_reject_detail WHERE id_type = '".$row2['type_reject']."' ORDER BY id_type ASC";
    $result_type = mysql_query($query_type);
    $row_type = mysql_fetch_array($result_type); 
	
	$query_reason = "SELECT * FROM reason_ng_reject WHERE id_reject = '".$row2['reason_reject']."' ORDER BY id_reject ASC";
    $result_reason = mysql_query($query_reason);
    $row_reason = mysql_fetch_array($result_reason);
	
	$query_type_wastage = "SELECT * FROM type_wastage_detail WHERE id_wastage = '".$row2['type_wastage']."' ORDER BY id_wastage ASC";
    $result_type_wastage = mysql_query($query_type_wastage);
    $row_type_wastage = mysql_fetch_array($result_type_wastage); 
	
	$query_reason_wastage = "SELECT * FROM reason_wastage WHERE id_reason_wastage = '".$row2['reason_wastage']."' ORDER BY id_reason_wastage ASC";
    $result_reason_wastage = mysql_query($query_reason_wastage);
    $row_reason_wastage = mysql_fetch_array($result_reason_wastage);
	
	
	//quantity output
/*		if($row2["qty_actual"] != "0.000")
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
*/	
	
	//quantity
	
	if($row2["status_part"] == "QC")
	{
	
	$qty_final = $row2["qty_qc_NG"];	
		
	}elseif($row2["status_part"] == "WQ")
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
	echo '</tr>';
    echo '</table>';  
	
	$no ++;
    $counter++; 
		
}  // end while loop
 mysql_free_result($rs); 
?>

<?php

echo iconv('utf-8', 'cp1251', "$data"); 
?>
</body>
</html>


