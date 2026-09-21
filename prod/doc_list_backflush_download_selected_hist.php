<?php

/**
 * prod/doc_list_backflush_download_selected_hist.php
 * Part of: Production module
 * Filename suggests: doc list backflush download selected hist
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: sys_setup_maintain, request_status, work_center_detail, pps_detail_transaction, mat_master_header.
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
require_role($dbc, 2);

set_time_limit(0);

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

//CR status (Cancelled)
$sta4 = "SELECT * from request_status WHERE status_id = '4'";
$sta_res4 = mysqli_query($dbc, $sta4);
$rst_sta4 = mysqli_fetch_array($sta_res4);	

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7'";
$sta_res7 = mysqli_query($dbc, $sta7);
$rst_sta7 = mysqli_fetch_array($sta_res7);	

//CR status (Completed)
$sta14 = "SELECT * from request_status WHERE status_id = '14'";
$sta_res14 = mysqli_query($dbc, $sta14);
$rst_sta14 = mysqli_fetch_array($sta_res14);	
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
$date_tdy2 = date('d-m-Y');

		   
            $dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			$factory = $_GET["factory"];
			$work_center = $_GET["work_center"];
			$plan_no = $_GET["plan_no"];
			$shift_ops = $_GET["shift_ops"];
	      	
			
			 //convert 
			
			$query_convert = "SELECT * FROM `work_center_detail` as SR WHERE SR.id_work = '".db_esc($dbc, $_GET["work_center"])."'";
			$result_convert = mysqli_query($dbc, $query_convert); 
			$row_convert = mysqli_fetch_array($result_convert);
			
		  
		
			//-------Count all results------------------------//
			
				 $where_sql = ''; 
				 
		//1. DateF 
                if ($dateF  == "0000-00-00" ){
                     $wheresql_01 = "";}
                else {
                     $wheresql_01 = " AND (MR.date_posting > '".db_esc($dbc, $dateF)." 00:00:00')";}  
		 
		 // 2. DateT
                if($dateT == "0000-00-00") {
                     $wheresql_02 = ""; }
                else {
                      $wheresql_02 = " AND (MR.date_posting < '".db_esc($dbc, $dateT)." 23:59:59')"; }
		 
					 
		 //3. Factory 
                if ($factory == "NULL"){ 
                    $wheresql_03 = ""; }
                else {
                    $wheresql_03 = " AND SR.id_factory = '".db_esc($dbc, $factory)."'"; } 
					
          //4. Work Center
                if ($work_center == "NULL" ){
                    $wheresql_04 = ""; }
                else {
					$wheresql_04 = " AND MR.work_center = '".db_esc($dbc, $work_center)."'"; }
   
	       //5. Planned Order No.
                if ($plan_no == "NULL"){ 
                    $wheresql_05 = ""; }
                else {
                    $wheresql_05 = " AND MR.plan_no = '".db_esc($dbc, $plan_no)."'"; }  	
					
		 
		  //6. Shift
                if ($shift_ops == "NULL"){ 
                    $wheresql_06 = ""; }
                else {
					// $wheresql_06 = ""; }
					
                    $wheresql_06 = " AND ((MR.shift_pps1 = '".db_esc($dbc, $shift_ops)."') OR (MR.shift_pps2 = '".db_esc($dbc, $shift_ops)."')) "; }  		 			
				
				$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04 .$wheresql_05 .$wheresql_06;	
	
	//********** END CONDITION **************
  

$namaFile = "Backflush Document List_".$date_tdy2.".xls";
 //convert material no kpd id_hdr
			
		
    $query8 = "SELECT * FROM pps_detail_transaction AS MR, work_center_detail AS SR WHERE MR.work_center = SR.id_work AND (MR.status_pps = '".db_esc($dbc, $rst_sta7["status_desc"])."' OR MR.status_pps = '".db_esc($dbc, $rst_sta14["status_desc"])."' OR MR.status_pps = '".db_esc($dbc, $rst_sta4["status_desc"])."')".$where_sql;
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
$content .= "<font size='12px'><strong>BACKFLUSH HISTORY DOCUMENT LIST REPORT</strong></font> ";
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
echo '<th width="5" bgcolor="#E9F58D">POSTING DATE</th>';
echo '<th width="5" bgcolor="#E9F58D">POSTING TIME</th>';
echo '<th width="5" bgcolor="#E9F58D">MATERIAL NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">MATERIAL DESC.</th>';
echo '<th width="5" bgcolor="#E9F58D">PLAN NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">PLAN DATE</th>';
echo '<th width="5" bgcolor="#E9F58D">WORK CENTER</th>';
echo '<th width="5" bgcolor="#E9F58D">SHIFT</th>';
echo '<th width="5" bgcolor="#E9F58D">STORAGE LOCATION</th>';
echo '<th width="5" bgcolor="#E9F58D">DOCUMENT NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">OUTPUT STATUS</th>';
echo '<th width="5" bgcolor="#E9F58D">OUTPUT QUANTITY</th>';
echo '</tr>';
echo '</table>';
 
//Display table
// query menampilkan semua data
$query = "SELECT *, DATE_FORMAT(MR.date_plan,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2, DATE_FORMAT(MR.date_create,'%d-%m-%Y') as R3 FROM pps_detail_transaction AS MR, work_center_detail AS SR WHERE MR.work_center = SR.id_work AND (MR.status_pps = '".db_esc($dbc, $rst_sta7["status_desc"])."' OR MR.status_pps = '".db_esc($dbc, $rst_sta14["status_desc"])."' OR MR.status_pps = '".db_esc($dbc, $rst_sta4["status_desc"])."')".$where_sql."ORDER BY MR.plan_no ASC";
$rs = mysqli_query($dbc, $query);   //run the query.

//count how many data
   $counter = 1;
   $no = 1;
   $i = 1;
   $variance_qty = 0;
   $bq = 0;
   $rq = 0;
   $sta_out = "";
   $sta_out2 = "";
   $sta_out_1 = "";
   $sta_out_2 = "";

  echo '<table border="1" width="100%">';
  while ($row2 = mysqli_fetch_array($rs))
   {
	
	    //---------get material header---------
	    $query_mat_h = "SELECT * FROM mat_master_header AS HD, mat_master_detail AS AD WHERE HD.material_no = AD.material AND HD.material_no = '".db_esc($dbc, $row2["material_no"])."'";
		$result_mat_h = mysqli_query($dbc, $query_mat_h);
		$data_mat_h = mysqli_fetch_array($result_mat_h);	  
		
		
		//shift	
		if($row2["shift_pps1"] != "")
	{
		$sta = "D/S";
		
	}elseif($row2["shift_pps2"] != "")
	 {
		$sta = "N/S";
	 }else{
		 
		$sta = " ";
	 }	
	
	//quantity output
		if($row2["qty_actual"] != "0.000")
	{
		$qty_final = $row2["qty_actual"];
	}elseif($row2["qty_NG"] != "0.000")
	{
		$qty_final = $row2["qty_NG"];
	}else{
		$qty_final == " ";
	}
	
	//status output
	//----check string -----------
	
	$sta_out = substr($row2["bflush_no"],4,1);
	
	if($sta_out == "1")
	{
		$status_output = "OK";
	}elseif($sta_out == "3")
	{
	    $status_output = "NG";
	}else{
	     $status_output = "";
	}
	
	
	 $sta_out_1 = substr($row2["bflush_no"],2,3);
			
	if($sta_out_1 == "211")
	{
	    $status_output = "OK";
	}elseif($sta_out_1 == "221")
	 {
	    $status_output = "NG";
	}else{
	 // $status_output = "";
	}
		
	
	$sta_out2 = substr($row2["bflush_no_ref"],4,1);
	
	if($sta_out2 == "9")
	{
		 $status_output2 = "312";
	}else{
	     $status_output2 = "";
	}
		
	
	$sta_out_2 = substr($row2["bflush_no_ref"],2,3);
	
	if($sta_out_2 == "212")
	{
		 $status_output2 = "312";
	}else{
	     $status_output2 = "";
	}	
		
		if($row2["bflush_no_ref"] != "")
		{
			
			
	    echo '<tr height="35">';
		echo '<td>'. $row2["R2"].'</td>';  
		echo '<td>'. $row2["time_posting"].'</td>'; 
		echo '<td>&nbsp;'. $row2["material_no"].'</td>';  
	 	echo '<td>&nbsp;'. $data_mat_h["material_desc"].'</td>';  
	 	echo '<td>&nbsp;'. $row2["plan_no"].'</td>';
		echo '<td>&nbsp;'. $row2["R"].'</td>';  
		echo '<td>&nbsp;'. strtoupper($row2["work_center"]).'</td>';  
		echo '<td>'. $sta.'</td>';
		echo '<td>'. $data_mat_h["sloc"].'</td>'; 
		echo '<td>&nbsp;'. $row2["bflush_no"].'</td>';
	    echo '<td>'. $status_output.'</td>';
        echo '<td>'. intval($qty_final).'</td>';
	    echo '</tr>'; 
			
		echo '<tr height="35">';
		echo '<td>'. $row2["R2"].'</td>';  
		echo '<td>'. $row2["time_posting"].'</td>'; 
		echo '<td>&nbsp;'. $row2["material_no"].'</td>';  
	 	echo '<td>&nbsp;'. $data_mat_h["material_desc"].'</td>';  
	 	echo '<td>&nbsp;'. $row2["plan_no"].'</td>';
		echo '<td>&nbsp;'. $row2["R"].'</td>';  
		echo '<td>&nbsp;'. strtoupper($row2["work_center"]).'</td>';  
		echo '<td>'. $sta.'</td>';
		echo '<td>'. $data_mat_h["sloc"].'</td>'; 
		echo '<td>&nbsp;<font color=red>'. $row2["bflush_no_ref"].'</font></td>';
	    echo '<td>'. $status_output2.'</td>';
        echo '<td><font color=red>'. -(intval($qty_final)).'</font></td>';
	    echo '</tr>'; 	
			
			
		}else{

	    echo '<tr height="35">';
		echo '<td>'. $row2["R2"].'</td>';  
		echo '<td>'. $row2["time_posting"].'</td>'; 
		echo '<td>&nbsp;'. $row2["material_no"].'</td>';  
	 	echo '<td>&nbsp;'. $data_mat_h["material_desc"].'</td>';  
	 	echo '<td>&nbsp;'. $row2["plan_no"].'</td>';
		echo '<td>&nbsp;'. $row2["R"].'</td>';  
		echo '<td>&nbsp;'. strtoupper($row2["work_center"]).'</td>';  
		echo '<td>'. $sta.'</td>';
		echo '<td>'. $data_mat_h["sloc"].'</td>'; 
		echo '<td>&nbsp;'. $row2["bflush_no"].'</td>';
	    echo '<td>'. $status_output.'</td>';
        echo '<td>'. intval($qty_final).'</td>';
	    echo '</tr>'; 
          
		}
 
	$no ++;
	$counter++; // menambah counter 
		   
		   //}// end if
    	  

}  // end while loop $row2
    echo '</table>';   
	mysqli_free_result($rs); 
?>

<?php

echo iconv('utf-8', 'cp1251', "$data"); 
?>
</body>
</html>


