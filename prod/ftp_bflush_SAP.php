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
$username = $_SESSION['username'] ?? '';
include '../include/config.php';
require_once '../include/auth.php';
require_role($dbc, 2);

$buid = preg_replace('~[^A-Za-z0-9_-]~', '', (string) ($_GET["buid"] ?? '')); // goes into a file name: no path characters
$uid = $_GET["uid"];

$qry = db_query_bind($dbc, "SELECT *, DATE_FORMAT(date_posting,'%Y-%m-%d') AS R, DATE_FORMAT(date_create,'%Y-%m-%d') AS R2, DATE_FORMAT(date_create,'%H:%i:%s') AS R3 FROM pps_detail_transaction WHERE bflush_no = ? ", [$buid]);
$data = "";
while($row = mysqli_fetch_array($qry)) {
	
	$query_iscan = "SELECT * FROM scan_prod_planning WHERE id_scan = ?"; $query_iscan_args = [$row["id_scan"]];
	$result_iscan = db_query_bind($dbc, $query_iscan, $query_iscan_args);
	$row_iscan = mysqli_fetch_array($result_iscan);
	
	
  $data .= $row['bflush_no'].";".$row['comp_code'].";".$row['work_center'].";".$row['material_no'].";131;".$row['plan_no'].";".$row['date_plan'].";".$row['shift_posting'].";".$row['qty_actual'].";".$row_iscan['scan_uom'].";".$row['ploc'].";".$row['R'].";".$row['time_posting'].";".$row['R2'].";".$row['R3'].";".$row['user_create']."\r\n";
}

$filen="BF2".$buid;
//$csv_filename = $filen."_".date("YmdHis",time());

$file = "../FromPortal/".$filen.".csv";
//chmod($file, 0777);

    $query_cftp = "SELECT * FROM ftp_bflush_detail WHERE bflush_no = ?"; $query_cftp_args = [$buid];
	$result_cftp = db_query_bind($dbc, $query_cftp, $query_cftp_args);
	$row_cftp = mysqli_num_rows($result_cftp);
	
	if($row_cftp == 0 )
	{
		
	file_put_contents($file,$data);	
	
	}else{
		
		
		
	}




   
   
    //----colect data --------------
  $query_collect = "SELECT *, DATE_FORMAT(date_posting,'%Y-%m-%d') AS M FROM pps_detail_transaction WHERE bflush_no = ?"; $query_collect_args = [$buid];
  $rst_collect = db_query_bind($dbc, $query_collect, $query_collect_args);
  $data_collect = mysqli_fetch_array($rst_collect);

 
    $query_iscan2 = "SELECT * FROM scan_prod_planning WHERE id_scan = ?"; $query_iscan2_args = [$data_collect["id_scan"]];
	$result_iscan2 = db_query_bind($dbc, $query_iscan2, $query_iscan2_args);
	$row_iscan2 = mysqli_fetch_array($result_iscan2);
     //--------insert into table ftp_bflush_detail
  
     $query_ftp_info = "INSERT INTO ftp_bflush_detail(id, file_name, bflush_no, ref_id, plan_no, material_no, material_desc, qty_ftp, uom, status_ftp, posting_date, posting_time, user_create, date_create) VALUES('',?,?,?,?,?,?,?,?,'Y',?,?,?,NOW())"; $query_ftp_info_args = [$filen, $buid, $data_collect["ref_id"], $data_collect["plan_no"], $data_collect["material_no"], $data_collect["material_desc"], $data_collect["qty_actual"], $row_iscan2["scan_uom"], $data_collect["M"], $data_collect["time_posting"], $username]; 
     $rst_ftp_info = db_query_bind($dbc, $query_ftp_info, $query_ftp_info_args);
   
     // ---update status 

		$query_ftp = "UPDATE pps_detail_transaction SET status_ftp_bflush = 'Y' WHERE bflush_no = ?"; $query_ftp_args = [$buid];
		$rst_query_ftp = db_query_bind($dbc, $query_ftp, $query_ftp_args); //or die ("Error in query: $query_ftp"); 
		
				
		if($rst_query_ftp > 0)
		{
			
		echo "<script>";
		//echo "alert('FTP Transferred to SAP.');";
		echo "window.location='confirm_backflushProc.php?buid=$uid'";
		echo "</script>";
		exit(); //quit the script
		
	      }
		
      
    
?>
