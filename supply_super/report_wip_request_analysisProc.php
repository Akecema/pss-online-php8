<?php

/**
 * supply_super/report_wip_request_analysisProc.php
 * Part of: Supply Chain module (supervisor/admin tier)
 * Filename suggests: report wip request analysisProc
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: sys_setup_maintain, mat_master_header, wip_request, scan_detail_wip, user_detail, factory_detail, post_detail_header_wip, wip_request_close.
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
require_role($dbc, 7);

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

            $factory = $_GET["factory"];
			$work_center = $_GET["work_center"];
	        $status = $_GET["status"];
			$dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			$material_no = $_GET["material_no"];

$namaFile = "WIP Request Analysis Report.xls";
 //convert material no kpd id_hdr
			
			$query_convert = "SELECT * FROM `mat_master_header` as MH WHERE MH.material_no = ?"; $query_convert_args = [$_GET["material_no"]];
			$result_convert = db_query_bind($dbc, $query_convert, $query_convert_args); 
			$row_convert = mysqli_fetch_array($result_convert);
		
			//-------Count all results------------------------//
			
				 $where_sql = ''; $where_args = [];
		 
		 // 1. DateT
                if($dateT == "0000-00-00") {
                     $wheresql_01 = ""; $wheresql_01_args = []; }
                else {
                      $wheresql_01 = " AND (MR.date_mrin <= ?)"; $wheresql_01_args = [$dateT]; }
		  //2. DateF 
                if ($dateF  == "0000-00-00" ){
                     $wheresql_02 = ""; $wheresql_02_args = [];}
                else {
                     $wheresql_02 = " AND (MR.date_mrin >= ?)"; $wheresql_02_args = [$dateF];}
                                                
		 // 3. Status
                if ($status == "NULL" ){
                    $wheresql_03 = ""; $wheresql_03_args = []; }
                else {
                    $wheresql_03 = " AND MR.status = ?"; $wheresql_03_args = [$status]; }          
                                
          //4. Work Center
                if ($work_center == "NULL" ){
                    $wheresql_04 = ""; $wheresql_04_args = []; }
                else {
					$wheresql_04 = " AND SD.work_center = ?"; $wheresql_04_args = [$work_center]; }
   
	       //5. Material No.
                if ($material_no == "NULL"){ 
                    $wheresql_05 = ""; $wheresql_05_args = []; }
                else {
                    $wheresql_05 = " AND MR.bom_component = ?"; $wheresql_05_args = [$material_no]; }  	
					
		   //6. Factory 
                if ($factory == "NULL"){ 
                    $wheresql_06 = ""; $wheresql_06_args = []; }
                else {
                    $wheresql_06 = " AND SD.factory = ?"; $wheresql_06_args = [$factory]; }  			
	       	
	                                        
				
				$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04 .$wheresql_05 .$wheresql_06; $where_args = array_merge($wheresql_01_args, $wheresql_02_args, $wheresql_03_args, $wheresql_04_args, $wheresql_05_args, $wheresql_06_args);	
	
	//********** END CONDITION **************


    $query8 = "SELECT *,DATE_FORMAT(MR.date_mrin,'%d-%m-%Y') AS R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R2 FROM wip_request AS MR, scan_detail_wip AS SD WHERE MR.id_scan_wip = SD.id_scan AND MR.status_request = 'Y' AND MR.status != 'New'" .$where_sql; $query8_args = [...$where_args];
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

$content .= "<p><font size='12px'><strong>INGRESS AUTOVENTURES CO,.LTD</strong></font></p>";
$content .= "<font size='12px'><strong>WIP REQUEST REPORT</strong></font> ";
$content .= "<br>";
$content .= "<font size='12px'><strong>FROM : ".$dateF." </strong></font>&nbsp;&nbsp;&nbsp; ";
$content .= "<font size='12px'><strong>TO : ".$dateT."</strong></font> ";
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
echo '<th width="5" bgcolor="#E9F58D">FACTORY</th>';
echo '<th width="5" bgcolor="#E9F58D">LINE</th>';
echo '<th width="5" bgcolor="#E9F58D">MRIN NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">PRODUCTION ORDER</th>';
echo '<th width="5" bgcolor="#E9F58D">MATERIAL NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">MATERIAL DESCRIPTION</th>';
echo '<th width="5" bgcolor="#E9F58D">REQUIRED DATE</th>';
echo '<th width="5" bgcolor="#E9F58D">REQUIRED TIME</th>';
echo '<th width="5" bgcolor="#E9F58D">DURATION</th>';
echo '<th width="5" bgcolor="#E9F58D">REQUESTED QUANTITY</th>';
echo '<th width="5" bgcolor="#E9F58D">TRANSFERRED QUANTITY</th>'; 
echo '<th width="5" bgcolor="#E9F58D">VARIANCE QUANTITY</th>';
echo '<th width="5" bgcolor="#E9F58D">UOM</th>';
echo '<th width="5" bgcolor="#E9F58D">TRANSFERRED DATE</th>';
echo '<th width="5" bgcolor="#E9F58D">TRANSFERRED TIME</th>';
echo '<th width="5" bgcolor="#E9F58D">POSTING DATE</th>';
echo '<th width="5" bgcolor="#E9F58D">TP DOC. NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">STATUS</th>';
echo '<th width="5" bgcolor="#E9F58D">REASON</th>';
echo '<th width="5" bgcolor="#E9F58D">OTHERS REASON</th>';
echo '</tr>';
echo '</table>';
 
//Display table
// query menampilkan semua data
$query = "SELECT *,DATE_FORMAT(MR.date_mrin,'%d-%m-%Y') AS R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R2 FROM wip_request AS MR, scan_detail_wip AS SD WHERE MR.id_scan_wip = SD.id_scan AND MR.status_request = 'Y' AND MR.status != 'New'".$where_sql; $query_args = [...$where_args];
$rs = db_query_bind($dbc, $query, $query_args);   //run the query.

//count how many data
   $counter = 1;
   $no = 1;
   $i = 1;
   $variance_qty = 0;
   $bq = 0;
   $rq = 0;

 while ($row2 = mysqli_fetch_array($rs))
   {
   

   	$query_scan = "SELECT * FROM scan_detail_wip WHERE id_scan = ?"; $query_scan_args = [$row2[6]];
   	$result_scan = db_query_bind($dbc, $query_scan, $query_scan_args);
   	$row_scan = mysqli_fetch_array($result_scan);
	
	$query_again = "SELECT * FROM wip_request WHERE status_request = 'Y' and id_scan_wip = ? ORDER BY id_req_wip ASC"; $query_again_args = [$row2[6]];
    $rs_again = db_query_bind($dbc, $query_again, $query_again_args);   //run the query.
    $row = mysqli_fetch_array($rs_again);
	
	$query_u = "SELECT * FROM user_detail WHERE user_no = ?"; $query_u_args = [$row['user_create']];
	$result_u = db_query_bind($dbc, $query_u, $query_u_args);   //run the query.
	$data_u = mysqli_fetch_array($result_u);   //how many records are there?    

 	$query3 = "SELECT * FROM factory_detail WHERE id_fac = ?"; $query3_args = [$row_scan["factory"]];
    $result3 = db_query_bind($dbc, $query3, $query3_args);
	$row3 = mysqli_fetch_array($result3);
	
	$query4_p = "SELECT * from mat_master_header as LD, mat_master_detail as SD WHERE SD.id_hdr = LD.id_hdr and SD.id_dtl = ?"; $query4_p_args = [$row2[5]];
  	$result4_p = db_query_bind($dbc, $query4_p, $query4_p_args);
 	$row4_p = mysqli_fetch_array($result4_p); 
 
    $query5 = "SELECT * FROM post_detail_header_wip WHERE mrin_no = ? AND material_no = ? AND mvt_type = 311 AND prod_order = ?"; $query5_args = [$row2["temp_mrin_wip"], $row2["bom_component"], $row_scan["prod_order"]];
    $result5 = db_query_bind($dbc, $query5, $query5_args);
	$row5 = mysqli_fetch_array($result5);
	
	$query6 = "SELECT *,DATE_FORMAT(PD.date_create,'%d-%m-%Y') AS T, DATE_FORMAT(PD.date_posting,'%d-%m-%Y') AS T2 FROM post_detail_header_wip AS PD, wip_request AS MR WHERE PD.mrin_no = MR.temp_mrin_wip AND PD.material_no = MR.bom_component AND PD.mrin_no = ? AND PD.material_no = ? AND PD.mvt_type = 311"; $query6_args = [$row2["temp_mrin_wip"], $row2["bom_component"]];
    $result6 = db_query_bind($dbc, $query6, $query6_args);
	$row6 = mysqli_fetch_array($result6);
	
	$query7 = "SELECT * FROM wip_request_close AS MC, reason_req_close AS MRC WHERE MC.reason_close = MRC.id_close AND MC.id_req_wip = ? AND MC.temp_mrin_wip = ? AND MC.bom_component = ?"; $query7_args = [$row2["id_req_wip"], $row2["temp_mrin_wip"], $row2["bom_component"]];
    $result7 = db_query_bind($dbc, $query7, $query7_args);
	$row7 = mysqli_fetch_array($result7);

//-------------------------------------------------------Transfer Posting [Traffic Light] --------------------------
// table post_detail_header --- checking traffic licht
//-----------------------------------------------------------------------------------------------------------------

$date_post =  ($row2["date_mrin"].' '.$row2["time_mrin"]);
//$date_transfer = (date("Y-m-d").' '.date("H:i:s"));
$date_transfer = ($row5["date_create"].' '.$row5["time_create"]);

$start_date = new DateTime($date_post);
$since_start = $start_date->diff(new DateTime($date_transfer));

/*echo $since_start->m.' month<br>';
 echo $since_start->d.' days<br>';
echo $since_start->h.' hours<br>';
echo $since_start->i.' minutes<br>';
echo $since_start->s.' seconds<br>';  */ 

//------------------------------------------------------ Variance Quantity-----------------------------------------
//
//------------------------------------------------------------------------------------------------------------------	

					
    $query_tp = "SELECT *,SUM(rquantity) as TOT FROM post_detail_header_wip WHERE mrin_no = ? AND mvt_type = 311 AND material_no = ?"; $query_tp_args = [$row2["temp_mrin_wip"], $row4_p["bill_component"]];
	$result_tp  = db_query_bind($dbc, $query_tp, $query_tp_args); 

    $outs_qty = 0;
					
	while($row_tp = mysqli_fetch_assoc($result_tp))
   {
	
	$tp_quantity = $row_tp["TOT"]; 
	
	$outs_qty = ((($row2["bom_qty_wip"])) - (($row_tp["TOT"])));
	
	 }//end while $row_tp	
	 
	$outs_qty1 = number_format($outs_qty,3);	
	
	  $bq = ($row2["bom_qty_wip"]);	
	  $rq = ($row5["rquantity"]);
	
      $variance_qty = ($bq - $rq);
	  $variance_qty2 =  number_format($variance_qty, 3, '.', '');
	  


	
	//Display data
	echo '<table border="1" width="100%">';
	echo '<tr height="35">';
	echo '<td>'. $row_scan["factory"].'</td>';
	echo '<td>'. strtoupper($row_scan["work_center"]).'</td>';
	echo '<td>'. strtoupper($row2["temp_mrin_wip"]).'</td>';
	echo '<td>'. strtoupper($row_scan["prod_order"]).'</td>';
	echo '<td>'. strtoupper($row2["bom_component"]).'</td>';
	echo '<td>'. strtoupper($row4_p["material_desc_c"]).'</td>';
	echo '<td align="center">'. $row2["R"].'</td>';
	echo '<td align="center">'. $row2["time_mrin"].'</td>';
	
	if ($row6 > 0)
	{
	
     echo '<td align="center">'.$since_start->h." : ".$since_start->i.'</td>';
	}else{
	
	echo '<td align="center">&nbsp;</td>'; 
	}
	echo '<td align="center">'. $row2["bom_qty_wip"].'</td>';
	echo '<td align="center">'. $row5["rquantity"].'</td>';
	echo '<td align="center">'; if($variance_qty2 < 0 ) { echo "<font color='red'>"; echo $variance_qty2; echo "</font>"; } else { echo $variance_qty2; } echo '</td>';
	echo '<td align="center">'. $row2["bom_oum_wip"].'</td>';
	echo '<td align="center">'. $row6["T"].'</td>';
	echo '<td align="center">'. $row6["time_create"].'</td>';
	echo '<td align="center">'. $row6["T2"].'</td>';
	
	echo '<td>'. $row6["mat_doc"].'</td>';
	
	if($row2["status"] == "New") { 
	
	echo '<td align="center">Open</td>'; 
	
	} else{  
	
	echo '<td align="center">Close</td>'; } 
	
	if ($row7 > 0)
	{
	
     echo '<td align="center">'. $row7["reason_desc"].'</td>';
	 echo '<td align="center">'. $row7["reason_close2"].'</td>';
	}
	else{
	
	echo '<td align="center">&nbsp;</td>'; 
	echo '<td align="center">&nbsp;</td>'; 
	}
	
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


