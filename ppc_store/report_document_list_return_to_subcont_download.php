<?php

/**
 * ppc_store/report_document_list_return_to_subcont_download.php
 * Part of: PPC Store module
 * Filename suggests: report document list return to subcont download
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: sys_setup_maintain, request_status, ret_subcont_detail, ret_subcont_cancel.
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
require_role($dbc, 12);

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------	

//CR status (Cancelled Posting)
$sta4 = "SELECT * from request_status WHERE status_id = '4'";
$sta_res4 = mysqli_query($dbc, $sta4);
$rst_sta4 = mysqli_fetch_array($sta_res4);	
			 
//CR status (Return Posting)
$sta20 = "SELECT * from request_status WHERE status_id = '20'";
$sta_res20 = mysqli_query($dbc, $sta20);
$rst_sta20 = mysqli_fetch_array($sta_res20);	

//---------------------------------------------------------

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
		    $doc_tp_from = $_GET["doc_tp_from"];
			$doc_tp_to = $_GET["doc_tp_to"];
			$shift_day = $_GET["shift_day"];
			$material_no = $_GET["material_no"];
			$vendor_no = $_GET["vendor_no"];
	      	
			   //-------Count all results------------------------//
			
				 $where_sql = '';  
				 
		 //1. DateF 
                if ($dateF  == "0000-00-00" ){
                     $wheresql_01 = "";}
                else {
                     $wheresql_01 = " AND (posting_date >= '".db_esc($dbc, $dateF)."')";}  
		 
		 // 2. DateT
                if($dateT == "0000-00-00") {
                     $wheresql_02 = ""; }
                else {
                      $wheresql_02 = " AND (posting_date <= '".db_esc($dbc, $dateT)."')"; }
		
		 //3. doc tp from
                if ($doc_tp_from == ""){ 
                    $wheresql_03 = ""; }
                else {
                    $wheresql_03 = " AND doc_tp >= '".db_esc($dbc, $doc_tp_from)."'"; } 
					
          //4. doc tp to
                if ($doc_tp_to  == "" ){
                    $wheresql_04 = ""; }
                else {
					$wheresql_04 = " AND doc_tp <= '".db_esc($dbc, $doc_tp_tp)."'"; }
   
		  //5. Shift
                if ($shift_day == "NULL"){ 
                    $wheresql_05 = ""; }
                else {
				
                    $wheresql_05 = " AND shift_day = '".db_esc($dbc, $shift_day)."'"; }  
					
		  //6. Vendor No.
                if ($vendor_no == "NULL"){ 
                    $wheresql_06 = ""; }
                else {
                    $wheresql_06 = " AND vendor_no = '".db_esc($dbc, $vendor_no)."'"; }  
					
		  //7. Material No.
                if ($material_no == ""){ 
                    $wheresql_07 = ""; }
                else {
                    $wheresql_07 = " AND material_no = '".db_esc($dbc, $material_no)."'"; }  			 			
				
		$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04 .$wheresql_05 .$wheresql_06 .$wheresql_07;	
	
	//********** END CONDITION **************

 

$namaFile = "Return from Subcont_".$date_tdy.".xls";

 $count_record = "";			
		
  $query8 = "SELECT * FROM ret_subcont_detail WHERE status_tran = 'Y' ".$where_sql."GROUP BY doc_tp";
  $result8 = mysqli_query($dbc, $query8) or die(db_fail($dbc));
  $num_rows = mysqli_num_rows($result8);

  $query8a = "SELECT * FROM ret_subcont_cancel WHERE status_tran = 'Y' ".$where_sql."GROUP BY doc_tp";
  $result8a = mysqli_query($dbc, $query8a) or die(db_fail($dbc));
  $num_rows_8a = mysqli_num_rows($result8a);

//---------------------------end count
  $count_record =  ($num_rows + $num_rows_8a);



//header("Content-type: application/octet-stream"); 
header('Content-type: application/excel');                                  
header('Content-Disposition: attachment; filename='.$namaFile.'');
header('Content-Type: image/jpeg');
header("Pragma: no-cache");
header("Expires: 0");

$content = "";
$data = "";	




//Create report header 

$content .= "<p><font size='12px'><strong> ".strtoupper($data_setup["title_desc"])."</strong></font></p>";
$content .= "<font size='12px'><strong>DOCUMENT LIST - RETURN FROM SUBCONT</strong></font> ";
$content .= "<br>";
/*$content .= "<font size='12px'><strong>FROM : ".$dateF." </strong></font>&nbsp;&nbsp;&nbsp; ";
$content .= "<font size='12px'><strong>TO : ".$dateT."</strong></font> ";*/
$content .= "<br>";
$content .= "<br>";
$content .= "Date : " .$date_tdy."&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; ";
$content .= "Record Count : ".$count_record;
$content .= "<br>";

echo $content;
echo '<br>';
echo "<br>";  
 //-------Count all results------------------------//	
	
echo '<table border="1" width="100%">';
echo '<tr height="35">';
//echo '<th width="5" bgcolor="#E9F58D">NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">PART NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">PART NAME</th>';
echo '<th width="5" bgcolor="#E9F58D">POSTING DATE</th>';
echo '<th width="5" bgcolor="#E9F58D">POSTING TIME</th>';
echo '<th width="5" bgcolor="#E9F58D">QUANTITY</th>';
echo '<th width="5" bgcolor="#E9F58D">UOM</th>';
echo '<th width="5" bgcolor="#E9F58D">SHIFT</th>';
echo '<th width="5" bgcolor="#E9F58D">REFERENCE DOCUMENT NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">VENDOR</th>';
echo '<th width="5" bgcolor="#E9F58D">DOCUMENT NO.</th>';
echo '</tr>';
echo '</table>';

 
//Display table
// query menampilkan semua data

$query_sql2 = "SELECT *, DATE_FORMAT(posting_date,'%d-%m-%Y') as R2 FROM ret_subcont_detail WHERE status_tran = 'Y' ".$where_sql. "GROUP BY doc_tp ORDER BY posting_date DESC, posting_time DESC";
$result_sql2 = mysqli_query($dbc, $query_sql2);   //run the query.

//count how many data
   $counter = 1;
   $no = 1;
   $i = 1;

   
 echo '<table border="1" width="100%">';
  while ($data_sql2 = mysqli_fetch_array($result_sql2))
   {
	 
	 
	 $query_sql3 = "SELECT *, DATE_FORMAT(posting_date,'%d-%m-%Y') as R3 FROM ret_subcont_detail WHERE status_tran = 'Y' AND doc_tp = ? ORDER BY doc_tp ASC"; $query_sql3_args = [$data_sql2["doc_tp"]];
     $result_sql3 = db_query_bind($dbc, $query_sql3, $query_sql3_args);   //run the query.
	 
   $no2 = 1;
	   while ($data_sql3 = mysqli_fetch_array($result_sql3))
   {
	 
	 if($data_sql3["shift_day"] == "D/S")
	 {
		 $shift_desc = "Day";
		 
	 }else{
		 $shift_desc = "Night";
		 
	 }
		echo '<tr height="35">';
		//echo '<td>'. $no.'</td>';   
	 	echo '<td>&nbsp;'. $data_sql3["material_no"].'</td>'; 	
		echo '<td>&nbsp;'. $data_sql3["material_desc"].'</td>';  
		echo '<td>&nbsp;'. $data_sql3["R3"].'</td>';
	 	echo '<td>&nbsp;'. $data_sql3["posting_time"].'</td>';  
	 	echo '<td align="right">&nbsp;'. intval($data_sql3["qty_tp"]).'</td>';  
	 	echo '<td>&nbsp;'. strtoupper($data_sql3["uom"]).'</td>';  
	    echo '<td>&nbsp;'. strtoupper($shift_desc).'</td>'; 
		echo '<td>&nbsp;'. $data_sql3["ref_doc_no_return"].'</td>';  
	    echo '<td>&nbsp;'. strtoupper($data_sql3["vendor_no"]).'</td>';   
        echo '<td>&nbsp;'. $data_sql3["doc_tp"].'</td>';
        echo '</tr>'; 
		
		//---- record cancellation ------ //
		$query_cancel_plb = "SELECT *, DATE_FORMAT(date_cancel,'%d-%m-%Y') as R4, DATE_FORMAT(date_cancel,'%H:%i:%s') as R5 FROM ret_subcont_detail WHERE id_tp = ? AND status_tp = ?"; $query_cancel_plb_args = [$data_sql3["id_tp"], $rst_sta4["status_desc"]];
		$result_cancel_plb = db_query_bind($dbc, $query_cancel_plb, $query_cancel_plb_args);
	    $row_cancel = mysqli_fetch_array($result_cancel_plb);
		
	if(($data_sql3["id_tp"]) == ($row_cancel["id_tp"]))  
	  {
			
	  
		//echo '<td><font color="#FF0000">'. $no.'</font></td>';   
	 	echo '<td>&nbsp;'. $row_cancel["material_no"].'</td>'; 	
		echo '<td>&nbsp;'. $row_cancel["material_desc"].'</td>';  
		echo '<td>&nbsp;'. $row_cancel["R4"].'</td>';
	 	echo '<td>&nbsp;'. $row_cancel["R5"].'</td>';  
	 	echo '<td align="right">&nbsp;<font color="#FF0000">'. intval(-($row_cancel["qty_tp"])).'</font></td>';  
	 	echo '<td>&nbsp;'. strtoupper($row_cancel["uom"]).'</td>';  
	    echo '<td>&nbsp;'. strtoupper($shift_desc).'</td>';  
		echo '<td>&nbsp;'. $row_cancel["ref_doc_no_return"].'</td>';  
		echo '<td>&nbsp;'. strtoupper($row_cancel["vendor_no"]).'</td>'; 
        echo '<td>&nbsp;<font color="#FF0000">'. $row_cancel["ref_doc_tp"].'</font></td>';
      
	   }else{
		 
	    echo '</tr>'; 
	   
	  }
  ?><?php $no++;
   }
    
    
  mysqli_free_result($result_sql3);   
 }  
  ?></tbody></table> 

 
<?php

echo iconv('utf-8', 'cp1251', "$data"); 
?>
</body>
</html>


