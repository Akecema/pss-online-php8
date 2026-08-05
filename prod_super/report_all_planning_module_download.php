<?php
session_start();
$username = $_SESSION['username'];
include '../include/config.php';

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------	

//CR status (Released)
$sta2 = "SELECT * from request_status WHERE status_id = '2' ";
$sta_res2 = mysqli_query($dbc, $sta2);
$rst_sta2 = mysqli_fetch_array($sta_res2);	

//CR status (Cancelled)
$sta4 = "SELECT * from request_status WHERE status_id = '4'";
$sta_res4 = mysqli_query($dbc, $sta4);
$rst_sta4 = mysqli_fetch_array($sta_res4);

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
 

$namaFile = "Report Planned Order_".$date_tdy.".xls";
 //convert material no kpd id_hdr
			
		
    $query8 = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') as R, DATE_FORMAT(date_posting,'%d-%m-%Y') as R2 FROM pps_detail WHERE (status_pps != '".$rst_sta2["status_desc"]."' AND status_pps != '".$rst_sta4["status_desc"]."' AND status_pps != '".$rst_sta16["status_desc"]."') ORDER BY plan_no ASC";
  $result8 = mysqli_query($dbc, $query8) or die(mysqli_error($dbc));
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
$content .= "<font size='12px'><strong>PLANNED ORDER REPORT</strong></font> ";
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
echo '<th width="5" bgcolor="#E9F58D">PLAN DATE</th>';
echo '<th width="5" bgcolor="#E9F58D">PLAN NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">PLANNING QUANTITY</th>';
echo '<th width="5" bgcolor="#E9F58D">MATERIAL TYPE</th>';
echo '<th width="5" bgcolor="#E9F58D">MATERIAL NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">MATERIAL DESC.</th>';
echo '<th width="5" bgcolor="#E9F58D">WORK CENTER</th>';
echo '<th width="5" bgcolor="#E9F58D">POSTING DATE</th>';
echo '<th width="5" bgcolor="#E9F58D">POSTING TIME</th>';
echo '<th width="5" bgcolor="#E9F58D">STORAGE LOCATION</th>';
echo '<th width="5" bgcolor="#E9F58D">DOCUMENT NO.</th>';
echo '<th width="5" bgcolor="#E9F58D">OUTPUT QUANTITY</th>';
echo '<th width="5" bgcolor="#E9F58D">OUTPUT STATUS</th>';
echo '</tr>';
echo '</table>';
 
//Display table
// query menampilkan semua data
$query = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') as R, DATE_FORMAT(date_posting,'%d-%m-%Y') as R2, DATE_FORMAT(date_posting,'%H:%i:%s') as T2 FROM pps_detail WHERE (status_pps != '".$rst_sta2["status_desc"]."' AND status_pps != '".$rst_sta4["status_desc"]."' AND status_pps != '".$rst_sta16["status_desc"]."') ORDER BY plan_no ASC";
$rs = mysqli_query($dbc, $query);   //run the query.

//count how many data
   $counter = 1;
   $no = 1;
   $i = 1;
   $variance_qty = 0;
   $bq = 0;
   $rq = 0;

  echo '<table border="1" width="100%">';
  while ($row2 = mysqli_fetch_array($rs))
   {
	
	    //---------get material header---------
	    $query_mat_h = "SELECT * FROM mat_master_header AS HD, mat_master_detail AS AD WHERE HD.material_no = AD.material AND HD.material_no = '".$row2["material_no"]."'";
		$result_mat_h = mysqli_query($dbc, $query_mat_h);
		$data_mat_h = mysqli_fetch_array($result_mat_h);	  
	
	    echo '<tr height="35">';
		echo '<td>'. $row2["R"].'</td>';   
	 	echo '<td>&nbsp;'. $row2["plan_no"].'</td>'; 	
		echo '<td>&nbsp;'. intval($row2["qty_plan"]).'</td>';  
		echo '<td>'. $data_mat_h["material_type"].'</td>';
	 	echo '<td>&nbsp;'. $row2["material_no"].'</td>';  
	 	echo '<td>&nbsp;'. $data_mat_h["material_desc"].'</td>';  
	 	echo '<td>&nbsp;'. strtoupper($row2["work_center"]).'</td>';  
	 	echo '<td>'. $row2["R2"].'</td>'; 
	    echo '<td>'. $row2["T2"].'</td>';  
        echo '<td>'. $data_mat_h["sloc"].'</td>';
        echo '<td>&nbsp;'. $row2["plan_no"].'</td>';
        echo '<td>'. intval($row2["qty_plan"]).'</td>';
        echo '<td>'. $row2["status_pps"].'</td>';
	    echo '</tr>'; 

   $query_display = "SELECT *, DATE_FORMAT(MR.date_plan,'%d-%m-%Y') as RR, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as RR2, DATE_FORMAT(MR.date_create,'%d-%m-%Y') as RR3 FROM pps_detail_transaction AS MR WHERE MR.pps_id = '".$row2["id"]."'";
   $result_display = mysqli_query($dbc, $query_display);   //run the query.
   
   while ($row_display = mysqli_fetch_array($result_display))
   {
	   
 $query_display3 = "SELECT *, DATE_FORMAT(N.date_plan,'%d-%m-%Y') as B, DATE_FORMAT(N.date_qc_posting,'%d-%m-%Y') as B2, DATE_FORMAT(N.date_create,'%d-%m-%Y') as B3 FROM qqc_detail_transaction AS N WHERE N.bflush_no = '".$row_display["bflush_no"]."'";
$result_display3 = mysqli_query($dbc, $query_display3);   //run the query.  
  
  
   $query_display2 = "SELECT *, DATE_FORMAT(M.date_plan,'%d-%m-%Y') as J, DATE_FORMAT(M.date_qc_posting,'%d-%m-%Y') as J2, DATE_FORMAT(M.date_create,'%d-%m-%Y') as J3 FROM qqc_transaction AS M WHERE M.bflush_no = '".$row_display["bflush_no"]."'";
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


