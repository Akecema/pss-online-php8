<?php

/**
 * ppc/report_consumable_request_analysisProc.php
 * Part of: PPC module (Production Planning & Control)
 * Filename suggests: report consumable request analysisProc
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: sys_setup_maintain, consumable_request, user_detail, factory_detail, post_consumable_detail_header, consumable_request_close.
 * Includes: config.php, ]., $date_transfer = (date(.
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
require_role($dbc, 4);

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
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
			$status = $_GET["status"];
			$dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			$material_no = $_GET["material_no"];

$namaFile = "Consumable Request Analysis Report.xls";

			//-------Count all results------------------------//

	//********* CONDITION *************
	
	  $where_sql = "";
		 
		 // 1. material_no
                if($material_no == "NULL") {
                     $wheresql_01 = ""; }
                else {
                      $wheresql_01 = " AND MR.material_no = '".db_esc($dbc, $material_no)."'"; }
		  // 2. dateF
                if ($dateF == "0000-00-00" ){
                    $wheresql_02 = ""; }
                else {
                    $wheresql_02 = " AND (MR.date_require >= '".db_esc($dbc, $dateF)."')"; }      
                                                
		 // 3. dateT
                if ($dateT == "0000-00-00" ){
                    $wheresql_03 = ""; }
                else {
                    $wheresql_03 = " AND (MR.date_require <= '".db_esc($dbc, $dateT)."')"; }          
                                
          //4. factory
                if ($factory == "NULL" ){
                    $wheresql_04 = ""; }
                else {
					$wheresql_04 = " AND MR.factory = '".db_esc($dbc, $factory)."'"; }
					
		 //5. status
                if ($status == "NULL" ){
                    $wheresql_05 = ""; }
                else {
					$wheresql_05 = " AND MR.status = '".db_esc($dbc, $status)."'"; }
   
	   
			$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04 .$wheresql_05;
	
	//********** END CONDITION **************
 
 
//------------------------------------count-------------------\\


  $query8 = "SELECT *,DATE_FORMAT(MR.date_require,'%d-%m-%Y') AS R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R2 FROM consumable_request AS MR, consumable_detail AS SD WHERE MR.id_con = SD.id_con AND MR.status != 'Cancel'" .$where_sql;
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

$content .= "<p><font size='12px'><strong>INGRESS AUTOVENTURES CO,.LTD</strong></font></p>";
$content .= "<font size='12px'><strong>CONSUMABLE REQUEST ANALYSIS REPORT</strong></font> ";
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
echo '<th width="5" bgcolor="#E9F58D">MATERIAL NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">MATERIAL DESCRIPTION</th>';
echo '<th width="5" bgcolor="#E9F58D">UOM</th>';
echo '<th width="5" bgcolor="#E9F58D">REQUESTED QUANTITY</th>';
echo '<th width="5" bgcolor="#E9F58D">TRANSFERRED QUANTITY</th>'; 
echo '<th width="5" bgcolor="#E9F58D">VARIANCE QUANTITY</th>';
echo '<th width="5" bgcolor="#E9F58D">REQUIRED DATE</th>';
echo '<th width="5" bgcolor="#E9F58D">REQUIRED TIME</th>';
echo '<th width="5" bgcolor="#E9F58D">TRANSFERRED DATE</th>';
echo '<th width="5" bgcolor="#E9F58D">TRANSFERRED TIME</th>';
echo '<th width="5" bgcolor="#E9F58D">DURATION</th>';
echo '<th width="5" bgcolor="#E9F58D">TP DOC. NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">STATUS</th>';
echo '<th width="5" bgcolor="#E9F58D">REASON</th>';
echo '<th width="5" bgcolor="#E9F58D">OTHERS REASON</th>';
echo '</tr>';
echo '</table>';

 
//Display table
// query menampilkan semua data
$query = "SELECT *,DATE_FORMAT(MR.date_require,'%d-%m-%Y') AS R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R2 FROM consumable_request AS MR, consumable_detail AS SD WHERE MR.id_con = SD.id_con AND MR.status != 'Cancel'".$where_sql;
$rs = mysqli_query($dbc, $query);   //run the query.

//count how many data
   $counter = 1;
   $no = 1;
   $i = 1;
  

 while ($row2 = mysqli_fetch_array($rs))
   {
   	
	$query_u = "SELECT * FROM user_detail WHERE user_no = '".db_esc($dbc, $row2["user_create"])."'";
	$result_u = mysqli_query($dbc, $query_u);   //run the query.
	$data_u = mysqli_fetch_array($result_u);   //how many records are there?    

 	$query3 = "SELECT * FROM factory_detail WHERE id_fac = '".db_esc($dbc, $row2["factory"])."'";
    $result3 = mysqli_query($dbc, $query3);
	$row3 = mysqli_fetch_array($result3);
	
	$query5 = "SELECT *,DATE_FORMAT(date_create,'%d-%m-%Y') AS T FROM post_consumable_detail_header WHERE mrin_no = '".db_esc($dbc, $row2["temp_mrin"])."' AND material_no = '".db_esc($dbc, $row2["material_no"])."' AND mvt_type = 201";
    $result5 = mysqli_query($dbc, $query5);
	$row5 = mysqli_fetch_array($result5);
	
	$query7 = "SELECT *  FROM consumable_request_close AS MC, reason_req_close AS MRC WHERE MC.reason_close = MRC.id_close AND MC.temp_mrin = '".db_esc($dbc, $row2["temp_mrin"])."' AND MC.material_no = '".db_esc($dbc, $row2["material_no"])."'";
    $result7 = mysqli_query($dbc, $query7);
	$row7 = mysqli_fetch_array($result7);


	
//-------------------------------------------------------Transfer Posting [Traffic Light] --------------------------
// table post_consumable_detail_header --- checking traffic licht
//-----------------------------------------------------------------------------------------------------------------

$date_post =  ($row2["date_require"].' '.$row2["time_require"]);
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

					
    $query_tp = "SELECT *,SUM(rquantity) as TOT FROM post_consumable_detail_header WHERE mrin_no = '".db_esc($dbc, $row2["temp_mrin"])."' AND mvt_type = 201 AND material_no = '".db_esc($dbc, $row2["material_no"])."'";
	$result_tp  = mysqli_query($dbc, $query_tp); 

    $outs_qty = 0;
					
	while($row_tp = mysqli_fetch_assoc($result_tp))
   {
	
	$tp_quantity = $row_tp["TOT"]; 
	
	$outs_qty = ((($row2["con_qty"])) - (($row_tp["TOT"])));
	
	 }//end while $row_tp	
	 
	$outs_qty1 = number_format($outs_qty,3);	
	
	  $bq = ($row2["con_qty"]);	
	  $rq = ($row5["rquantity"]);
	
      $variance_qty = ($bq - $rq);
	  $variance_qty2 =  number_format($variance_qty, 3, '.', '');
	  

	//Display data
	echo '<table border="1" width="100%">';
	echo '<tr height="35">';
	echo '<td>'. $row2["factory"].'</td>';
	echo '<td>'. strtoupper($row2["id_work"]).'</td>';
	echo '<td>'. strtoupper($row2["temp_mrin"]).'</td>';
	echo '<td>'. strtoupper($row2["material_no"]).'</td>';
	echo '<td>'. strtoupper($row2["mat_desc"]).'</td>';
	echo '<td align="center">'. $row2["con_uom"].'</td>';
	echo '<td align="center">'. $row2["con_qty"].'</td>';
	echo '<td align="center">'. $row5["rquantity"].'</td>';
	echo '<td align="center">'; if($variance_qty2 < 0 ) { echo "<font color='red'>"; echo $variance_qty2; echo "</font>"; } else { echo $variance_qty2; } echo '</td>';
	echo '<td align="center">'. $row2["R"].'</td>';
	echo '<td align="center">'. $row2["time_require"].'</td>';
	echo '<td align="center">'. $row5["T"].'</td>';
	echo '<td align="center">'. $row5["time_create"].'</td>';
	if ($row5 > 0)
	{
	
     echo '<td align="center">'.$since_start->h." : ".$since_start->i.'</td>';
	}else{
	
	echo '<td align="center">&nbsp;</td>'; 
	}
	
	echo '<td>'. $row5["mat_doc"].'</td>';
	
	  
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


