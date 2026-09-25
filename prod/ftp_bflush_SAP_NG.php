<?php

/**
 * prod/ftp_bflush_SAP_NG.php
 * Part of: Production module
 * Filename suggests: ftp bflush SAP NG
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: pps_detail_transaction, scan_prod_planning, type_reject_detail, reason_ng_reject, ftp_bflush_detail.
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
	
	$query_iscanA = "SELECT * FROM scan_prod_planning WHERE id_scan = ?"; $query_iscanA_args = [$row["id_scan"]];
	$result_iscanA = db_query_bind($dbc, $query_iscanA, $query_iscanA_args);
	$row_iscanA = mysqli_fetch_array($result_iscanA);
	
	$query_type = "SELECT * FROM type_reject_detail WHERE id_type = ? ORDER BY id_type ASC"; $query_type_args = [$row['type_reject']];
    $result_type = db_query_bind($dbc, $query_type, $query_type_args);
    $row_type = mysqli_fetch_array($result_type); 
	
	$query_reason = "SELECT * FROM reason_ng_reject WHERE id_reject = ? ORDER BY id_reject ASC"; $query_reason_args = [$row['reason_reject']];
    $result_reason = db_query_bind($dbc, $query_reason, $query_reason_args);
    $row_reason = mysqli_fetch_array($result_reason);
			  
	
	 $data .= $row['bflush_no'].";".$row['comp_code'].";".$row['work_center'].";".$row['material_no'].";131;".$row['plan_no'].";".$row['date_plan'].";".$row['shift_posting'].";".$row['qty_NG'].";".$row_iscanA['scan_uom'].";".$row['ploc'].";".$row['R'].";;;;;;\r\n";
	
			
	/* $data .= $row['bflush_no'].";".$row['comp_code'].";".$row['work_center'].";".$row['material_no'].";131;".$row['plan_no'].";".$row['date_plan'].";".$row['scan_shift'].";".$row['qty_NG'].";".$row['scan_uom'].";".$row['ploc'].";".$row['R'].";;".$row['R2'].";".$row['R3'].";".$row['user_create'].";".$row_type['type_desc'].";".$row_reason['reject_desc']."\r\n";*/

}

$filen="BF2".$buid;
//$csv_filename = $filen."_".date("YmdHis",time());

$file = "../FromPortal/".$filen.".csv";
//chmod($file, 0777);
file_put_contents($file,$data);
   
   
    //----colect data --------------
  $query_collect = "SELECT *, DATE_FORMAT(date_posting,'%Y-%m-%d') AS R FROM pps_detail_transaction WHERE bflush_no = ?"; $query_collect_args = [$buid];
  $rst_collect = db_query_bind($dbc, $query_collect, $query_collect_args);
  $data_collect = mysqli_fetch_array($rst_collect);
  
    $query_iscanA2 = "SELECT * FROM scan_prod_planning WHERE id_scan = ?"; $query_iscanA2_args = [$data_collect["id_scan"]];
	$result_iscanA2 = db_query_bind($dbc, $query_iscanA2, $query_iscanA2_args);
	$row_iscanA2 = mysqli_fetch_array($result_iscanA2);

 
     //--------insert into table ftp_bflush_detail
  
     $query_ftp_info = "INSERT INTO ftp_bflush_detail(id, file_name, bflush_no, ref_id, plan_no, material_no, material_desc, qty_ftp, uom, status_ftp, posting_date, posting_time, user_create, date_create) VALUES('',?,?,?,?,?,?,?,?,'Y',?,?,?,NOW())"; $query_ftp_info_args = [$filen, $buid, $data_collect["ref_id"], $data_collect["plan_no"], $data_collect["material_no"], $data_collect["material_desc"], $data_collect["qty_NG"], $row_iscanA2["scan_uom"], $data_collect["R"], $data_collect["time_posting"], $username]; 
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
