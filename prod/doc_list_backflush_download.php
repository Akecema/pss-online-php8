<?php

/**
 * prod/doc_list_backflush_download.php
 * Part of: Production module
 * Filename suggests: doc list backflush download
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: sys_setup_maintain, request_status, pps_detail_transaction, mat_master_header, qqc_detail_transaction, qqc_transaction.
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
require_role($dbc, 2);

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------	

//CR status (New)

$sta = "SELECT * from request_status WHERE status_id = '1' ";
$sta_res = mysqli_query($dbc, $sta);
$rst_sta = mysqli_fetch_array($sta_res);

//CR status (Released)
$sta2 = "SELECT * from request_status WHERE status_id = '2' ";
$sta_res2 = mysqli_query($dbc, $sta2);
$rst_sta2 = mysqli_fetch_array($sta_res2);

//CR status (Cancelled)
$sta4 = "SELECT * from request_status WHERE status_id = '4'";
$sta_res4 = mysqli_query($dbc, $sta4);
$rst_sta4 = mysqli_fetch_array($sta_res4);		

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7' ";
$sta_res7 = mysqli_query($dbc, $sta7);
$rst_sta7 = mysqli_fetch_array($sta_res7);	

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
set_time_limit(0);

		   
   //------range date for 30 days----------------------------
 $start_date_check = date('Y-m-01', strtotime("0 month"));
 $end_date_check = date('Y-m-31', strtotime("0 month"));
  

$namaFile = "Backflush Document List_".$date_tdy2.".xls";
 //convert material no kpd id_hdr
			
		
    $query8 = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') as R, DATE_FORMAT(date_posting,'%d-%m-%Y') as R2, DATE_FORMAT(date_create,'%d-%m-%Y') as R3 FROM pps_detail_transaction WHERE status_pps = '".db_esc($dbc, $rst_sta7["status_desc"])."' AND (date_plan >= '$start_date_check' AND date_plan <= '$end_date_check') order by plan_no ASC";
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
$content .= "<font size='12px'><strong>BACKFLUSH DOCUMENT LIST REPORT</strong></font> ";
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
$query = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') as R, DATE_FORMAT(date_posting,'%d-%m-%Y') as R2, DATE_FORMAT(date_create,'%d-%m-%Y') as R3 FROM pps_detail_transaction WHERE status_pps = '".db_esc($dbc, $rst_sta7["status_desc"])."' AND (date_plan >= '$start_date_check' AND date_plan <= '$end_date_check') order by plan_no ASC";
$rs = mysqli_query($dbc, $query);   //run the query.

//count how many data
   $counter = 1;
   $no = 1;
   $i = 1;
   $variance_qty = 0;
   $bq = 0;
   $rq = 0;
   $sta_out = "";
   $sta_out3 = "";

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
	
	 $sta_out3 = substr($row2["bflush_no"],2,3);
			
	if($sta_out3 == "211")
	{
	    $status_output = "OK";
	}elseif($sta_out3 == "221")
	 {
	    $status_output = "NG";
	}else{
	 // $status_output = "";
	}
		

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

   $query_display = "SELECT *, DATE_FORMAT(MR.date_plan,'%d-%m-%Y') as RR, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as RR2, DATE_FORMAT(MR.date_create,'%d-%m-%Y') as RR3 FROM pps_detail_transaction AS MR WHERE MR.pps_id = '".db_esc($dbc, $row2["id"])."'";
   $result_display = mysqli_query($dbc, $query_display);   //run the query.
   
   while ($row_display = mysqli_fetch_array($result_display))
   {
	   
 $query_display3 = "SELECT *, DATE_FORMAT(N.date_plan,'%d-%m-%Y') as B, DATE_FORMAT(N.date_qc_posting,'%d-%m-%Y') as B2, DATE_FORMAT(N.date_create,'%d-%m-%Y') as B3 FROM qqc_detail_transaction AS N WHERE N.bflush_no = '".db_esc($dbc, $row_display["bflush_no"])."'";
$result_display3 = mysqli_query($dbc, $query_display3);   //run the query.  
  
  
   $query_display2 = "SELECT *, DATE_FORMAT(M.date_plan,'%d-%m-%Y') as J, DATE_FORMAT(M.date_qc_posting,'%d-%m-%Y') as J2, DATE_FORMAT(M.date_create,'%d-%m-%Y') as J3 FROM qqc_transaction AS M WHERE M.bflush_no = '".db_esc($dbc, $row_display["bflush_no"])."'";
$result_display2 = mysqli_query($dbc, $query_display2);   //run the query.


	//quantity output
		if($row_display["qty_actual"] != "0.000")
	{
		$qty_final = $row_display["qty_actual"];
	}elseif($row_display["qty_NG"] != "0.000")
	{
		$qty_final = $row_display["qty_NG"];
	}else{
		$qty_final == " ";
	}
	
	//status output
	//----check string -----------
	
	$sta_out = substr($row_display["bflush_no"],4,1);
	
	if($sta_out == "1")
	{
		$status_output = "OK";
	}elseif($sta_out == "3")
	{
	    $status_output = "NG";
	}else{
	     $status_output = "";
	}
	
	$sta_out3 = substr($row2["bflush_no"],2,3);
			
	if($sta_out3 == "211")
	{
	    $status_output = "OK";
	}elseif($sta_out3 == "221")
	 {
	    $status_output = "NG";
	}else{
	 // $status_output = "";
	}
	
	?>
	
<?php	
	//Display data
	/*echo '<table border="1" width="100%">';
	echo '<tr height="35">';
	echo '<td>';*/
	
	if ($row_display > 0)
	{
	
	 ?>
	  <tr class="gradeX">
        <td width="90"><?php echo $row_display["RR"]; ?></td>
        <td width="100">&nbsp;<?php echo $row_display["plan_no"]; ?></td>
        <td width="60">&nbsp;<?php echo intval($row2["qty_plan"]); ?></td>
        <td width="60"><?php echo $data_mat_h["material_type"]; ?></td>
        <td width="100">&nbsp;<?php echo $row_display["material_no"]; ?></td>
        <td width="120">&nbsp;<?php echo $data_mat_h["material_desc"]; ?></td>
        <td width="60">&nbsp;<?php echo strtoupper($row2["work_center"]); ?></td>
        <td width="60"><?php echo $row_display["RR2"]; ?></td>
        <td width="60"><?php echo $row_display["time_posting"]; ?></td>
        <td width="60"><?php echo $row_display["ploc"]; ?></td>
        <td width="60"><font color="#0000CC">&nbsp;<?php echo $row_display["bflush_no"]; ?></font></td>
        <td width="60"><?php echo intval($qty_final); ?></td>
        <td width="60"><?php echo $status_output; ?></td>
      </tr>  
	<?php  
	   $no3 = 1;
	    
           while ($row3 = mysqli_fetch_array($result_display3))
        {
	   
	  
	   ?> 
        <tr class="gradeX"> 
        <td width="90"><?php echo $row3["B"]; ?></td>
        <td width="100">&nbsp;<?php echo $row3["plan_no"]; ?></td>
        <td width="60">&nbsp;<?php echo intval($row2["qty_plan"]); ?></td>
        <td width="60"><?php echo $data_mat_h["material_type"]; ?></td>
        <td width="100">&nbsp;<?php echo $row3["material_no"]; ?></td>
        <td width="120">&nbsp;<?php echo $data_mat_h["material_desc"]; ?></td>
        <td width="60">&nbsp;<?php echo strtoupper($row2["work_center"]); ?></td>
        <td width="60"><?php echo $row3["B2"]; ?></td>
         <td width="60"><?php echo $row3["time_qc_posting"]; ?></td>
        <td width="60"><?php echo $row3["ploc_qc"]; ?></td>
        <td width="60"><font color="#0000CC">&nbsp;<?php echo $row3["bflush_no"]; ?></font>
        <br><?php echo $row3["qqc_doc_no"]; ?></td>
        <td width="60"><?php echo intval($row3["qty_balance"]); ?></td>
        <td width="60"><?php echo $row3["status_QC"]; ?></td>
        </tr>   
       
        <?php 
		
		$no3++;
		
           }  ?>
	
	<?php
	} // end $row_display
	
	 $no2 = "a"; 
     $sta_out2 = "";
	 $sta_out4 = "";
		   
     while($row_rst_display2 = mysqli_fetch_array($result_display2))
   {
	   
//quantity output
		if($row_rst_display2["qty_qc_ok"] != "0.000")
	{
		$qty_final2 = $row_rst_display2["qty_qc_ok"];
	}elseif($row_rst_display2["qty_qc_NG"] != "0.000")
	{
		$qty_final2 = $row_rst_display2["qty_qc_NG"];
	}else{
		$qty_final2 == " ";
	}
	
     //status output
	//----check string -----------
	
	$sta_out2 = substr($row_rst_display2["qqc_no"],4,1);
	
	if($sta_out2 == "5")
	{
		$status_output2 = "QC OK";
	}elseif($sta_out2 == "7")
	{
	    $status_output2 = "QC NG";
	}else{
	     $status_output2 = "";
	}
	
	 $sta_out4 = substr($row_rst_display2["qqc_no"],2,3);
	
	if($sta_out4 == "421")
	{
		$status_output2 = "QC OK";
	}elseif($sta_out4 == "331")
	{
	    $status_output2 = "QC NG";
	}else{
	     $status_output2 = "";
	}
	
	
	if ($row_rst_display2 > 0)
	{
	?>
	
	   <tr class="gradeX">
        <td width="90"><?php echo $row_rst_display2["J"]; ?></td>
        <td width="100">&nbsp;<?php echo $row_rst_display2["plan_no"]; ?></td>
        <td width="60">&nbsp;<?php echo intval($row2["qty_plan"]); ?></td>
        <td width="60"><?php echo $data_mat_h["material_type"]; ?></td>
        <td width="100">&nbsp;<?php echo $row_rst_display2["material_no"]; ?></td>
        <td width="120">&nbsp;<?php echo $data_mat_h["material_desc"]; ?></td>
        <td width="60">&nbsp;<?php echo strtoupper($row2["work_center"]); ?></td>
        <td width="60"><?php echo $row_rst_display2["J2"]; ?></td>
        <td width="60"><?php echo $row_rst_display2["time_qc_posting"]; ?></td>
        <td width="60"><?php echo $row_rst_display2["ploc"]; ?></td>
        <td width="60"><font color="#669999">&nbsp;<?php echo $row_rst_display2["qqc_no"]; ?></font></td>
        <td width="60"><?php echo intval($qty_final2); ?></td>
        <td width="60"><?php echo $status_output2; ?></td>
      </tr> 
      
      <?php 
	      $no2 ++;
	   }// end $row_rst_display2
		 
	}	// while $row_rst_display2  
	$no ++;
	$counter++; // menambah counter 
		   
		   //}// end if

		
} // while $row_display  
	  
    	  

}  // end while loop $row2
    echo '</table>';   
	mysqli_free_result($rs); 
?>

<?php

echo iconv('utf-8', 'cp1251', "$data"); 
?>
</body>
</html>


