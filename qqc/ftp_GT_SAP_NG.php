<?php

/**
 * qqc/ftp_GT_SAP_NG.php
 * Part of: QQC module (Quality)
 * Filename suggests: ftp GT SAP NG
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: qqc_detail_transaction, qqc_transaction, type_reject_detail, reason_ng_reject, table_material, ftp_goodtran_detail.
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
require_role($dbc, 10);

$buid = preg_replace('~[^A-Za-z0-9_-]~', '', (string) ($_GET["buid"] ?? '')); // goes into a file name: no path characters
$uid = $_GET["uid"];
$data = "";

   
   //----------qqc_detail_transaction ------------------
   
   $query_qc = "SELECT * FROM qqc_detail_transaction WHERE id_qqc = ?"; $query_qc_args = [$uid];
   $result_qc = db_query_bind($dbc, $query_qc, $query_qc_args);
   $data_qc = mysqli_fetch_array($result_qc);

 //----------create text file sent ftp to SAP[comp code][5][running no]--------------
   $query_ftp = "SELECT *, DATE_FORMAT(date_qc_posting,'%Y-%m-%d') AS P, DATE_FORMAT(date_create,'%Y-%m-%d') AS P2, DATE_FORMAT(date_create,'%H:%i:%s') AS P3 FROM qqc_transaction WHERE id_tran = ? AND qqc_no = ?"; $query_ftp_args = [$data_qc["id_tran"], $buid];
   $result_ftp = db_query_bind($dbc, $query_ftp, $query_ftp_args);
   $data_ftp = mysqli_fetch_array($result_ftp);
	
	$query_type = "SELECT * FROM type_reject_detail WHERE id_type = ? ORDER BY id_type ASC"; $query_type_args = [$data_ftp["type_qc_reject"]];
    $result_type = db_query_bind($dbc, $query_type, $query_type_args);
    $row_type = mysqli_fetch_array($result_type); 
	
	$query_reason = "SELECT * FROM reason_ng_reject WHERE id_reject = ? ORDER BY id_reject ASC"; $query_reason_args = [$data_ftp["reason_qc_reject"]];
    $result_reason = db_query_bind($dbc, $query_reason, $query_reason_args);
    $row_reason = mysqli_fetch_array($result_reason);
	
	$query_q2 = "SELECT * FROM table_material WHERE material_no = ?"; $query_q2_args = [$data_ftp["material_no"]];
    $result_q2 = db_query_bind($dbc, $query_q2, $query_q2_args) or die(db_fail($dbc));
    $ans3 = mysqli_fetch_array($result_q2);

	
			  
 $data .= $buid.";".$data_ftp["comp_code"].";".$data_ftp["work_center"].";".$data_ftp["material_no"].";551;".$data_ftp["shift_day"].";".$data_ftp["qty_qc_NG"].";".$ans3["BUn"].";".$row_type["type_desc"].";".$row_reason["reject_desc"].";".$data_ftp["ploc_qc"].";Scrap;".$data_ftp["P"].";".$data_ftp['time_qc_posting'].";".$data_ftp["P2"].";".$data_ftp["P3"].";".$data_ftp["user_create"]."\r\n";

$filen="GI3".$buid;

$file = "../FromPortal2/MT7/".$filen.".csv";
//chmod($file, 0777);
file_put_contents($file,$data);
   
  //----------update table ftp_goodtran_detail------------
   
    $query_ftp_info = "INSERT INTO ftp_goodtran_detail(id,file_name,qqc_no,bflush_no,id_tran,plan_no,material_no,material_desc,qty_ftp,uom,status_ftp,posting_date,posting_time,user_create,date_create) VALUES('',?,?,".$data_ftp["bflush_no"].",?,?,?,?,?,?,'Y',?,?,?,NOW())"; $query_ftp_info_args = [$filen, $data_ftp["qqc_no"], $data_ftp["id_tran"], $data_ftp["plan_no"], $data_ftp["material_no"], $data_ftp["material_desc"], $data_ftp["qty_qc_NG"], $ans3["BUn"], $data_ftp["P"], $data_ftp["time_qc_posting"], $username]; 
     $rst_ftp_info = db_query_bind($dbc, $query_ftp_info, $query_ftp_info_args);
	 
	   // ---update status 

		$query_ftp2 = "UPDATE qqc_detail_transaction SET status_ftp_fgtran = 'Y' WHERE bflush_no = ?"; $query_ftp2_args = [$data_ftp["bflush_no"]];
		$rst_query_ftp2 = db_query_bind($dbc, $query_ftp2, $query_ftp2_args); //or die ("Error in query: $query_ftp"); 
		
				
		if($rst_query_ftp2 > 0)
		{
			
		echo "<script>";
		//echo "alert('FTP Transferred to SAP.');";
		echo "window.location='qc_rework_output_list.php?uid=$uid'";
		echo "</script>";
		exit(); //quit the script
		
	      }
		
      
    
?>
