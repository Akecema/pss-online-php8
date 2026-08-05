<?php

/**
 * prod/ftp_bflush_SAP.php
 * Part of: Production module
 * Filename suggests: ftp bflush SAP
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: pps_detail_transaction, scan_prod_planning, ftp_bflush_detail.
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

$buid = $_GET["buid"];
$uid = $_GET["uid"];

$qry = mysqli_query($dbc, "SELECT *, DATE_FORMAT(date_posting,'%Y-%m-%d') AS R, DATE_FORMAT(date_create,'%Y-%m-%d') AS R2, DATE_FORMAT(date_create,'%H:%i:%s') AS R3 FROM pps_detail_transaction WHERE bflush_no = '".$buid."' ");
$data = "";
while($row = mysqli_fetch_array($qry)) {
	
	$query_iscan = "SELECT * FROM scan_prod_planning WHERE id_scan = '".$row["id_scan"]."'";
	$result_iscan = mysqli_query($dbc, $query_iscan);
	$row_iscan = mysqli_fetch_array($result_iscan);
	
	
  $data .= $row['bflush_no'].";".$row['comp_code'].";".$row['work_center'].";".$row['material_no'].";131;".$row['plan_no'].";".$row['date_plan'].";".$row['shift_posting'].";".$row['qty_actual'].";".$row_iscan['scan_uom'].";".$row['ploc'].";".$row['R'].";".$row['time_posting'].";".$row['R2'].";".$row['R3'].";".$row['user_create']."\r\n";
}

$filen="BF2".$buid;
//$csv_filename = $filen."_".date("YmdHis",time());

$file = "../FromPortal/".$filen.".csv";
//chmod($file, 0777);

    $query_cftp = "SELECT * FROM ftp_bflush_detail WHERE bflush_no = '".$buid."'";
	$result_cftp = mysqli_query($dbc, $query_cftp);
	$row_cftp = mysqli_num_rows($result_cftp);
	
	if($row_cftp == 0 )
	{
		
	file_put_contents($file,$data);	
	
	}else{
		
		
		
	}




   
   
    //----colect data --------------
  $query_collect = "SELECT *, DATE_FORMAT(date_posting,'%Y-%m-%d') AS M FROM pps_detail_transaction WHERE bflush_no = '".$buid."'";
  $rst_collect = mysqli_query($dbc, $query_collect);
  $data_collect = mysqli_fetch_array($rst_collect);

 
    $query_iscan2 = "SELECT * FROM scan_prod_planning WHERE id_scan = '".$data_collect["id_scan"]."'";
	$result_iscan2 = mysqli_query($dbc, $query_iscan2);
	$row_iscan2 = mysqli_fetch_array($result_iscan2);
     //--------insert into table ftp_bflush_detail
  
     $query_ftp_info = "INSERT INTO ftp_bflush_detail(id, file_name, bflush_no, ref_id, plan_no, material_no, material_desc, qty_ftp, uom, status_ftp, posting_date, posting_time, user_create, date_create) VALUES('','".$filen."','".$buid."','".$data_collect["ref_id"]."','".$data_collect["plan_no"]."','".$data_collect["material_no"]."','".$data_collect["material_desc"]."','".$data_collect["qty_actual"]."','".$row_iscan2["scan_uom"]."','Y','".$data_collect["M"]."','".$data_collect["time_posting"]."','".$username."',NOW())"; 
     $rst_ftp_info = mysqli_query($dbc, $query_ftp_info);
   
     // ---update status 

		$query_ftp = "UPDATE pps_detail_transaction SET status_ftp_bflush = 'Y' WHERE bflush_no = '".$buid."'";
		$rst_query_ftp = mysqli_query($dbc, $query_ftp); //or die ("Error in query: $query_ftp"); 
		
				
		if($rst_query_ftp > 0)
		{
			
		echo "<script>";
		//echo "alert('FTP Transferred to SAP.');";
		echo "window.location='confirm_backflushProc.php?buid=$uid'";
		echo "</script>";
		exit(); //quit the script
		
	      }
		
      
    
?>
