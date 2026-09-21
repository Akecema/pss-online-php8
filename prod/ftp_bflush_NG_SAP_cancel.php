<?php

/**
 * prod/ftp_bflush_NG_SAP_cancel.php
 * Part of: Production module
 * Filename suggests: ftp bflush NG SAP cancel
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
$username = $_SESSION['username'];
include '../include/config.php';
require_once '../include/auth.php';
require_role($dbc, 2);

$uid2 = $_GET["buid"];
$buid = $_GET["buid2A"];

$qry = mysqli_query($dbc, "SELECT *, DATE_FORMAT(A1.date_posting,'%Y-%m-%d') AS R, DATE_FORMAT(A1.date_create,'%Y-%m-%d') AS R2, DATE_FORMAT(A1.date_create,'%H:%i:%s') AS R3 FROM pps_detail_transaction AS A1 WHERE A1.id = '".db_esc($dbc, $uid2)."'");
$data = "";
while($row = mysqli_fetch_array($qry)) {
	
	//$sta_out = substr($row["bflush_no"],4,1);
	
	$sta_out = substr($row["bflush_no"],2,3);
	
	$filen="BF2".$buid;
	
	 //------get data UOM ----------
	 
	  $query_uom = "SELECT * FROM scan_prod_planning WHERE id_scan = '".db_esc($dbc, $row["id_scan"])."'";
	  $rst_uom = mysqli_query($dbc, $query_uom);
	  $data_uom = mysqli_fetch_array($rst_uom);
	
	if($sta_out == "221")
	{
		
	$query_type = "SELECT * FROM type_reject_detail WHERE id_type = '".db_esc($dbc, $row['type_reject'])."' ORDER BY id_type ASC";
    $result_type = mysqli_query($dbc, $query_type);
    $row_type = mysqli_fetch_array($result_type); 
	
	$query_reason = "SELECT * FROM reason_ng_reject WHERE id_reject = '".db_esc($dbc, $row['reason_reject'])."' ORDER BY id_reject ASC";
    $result_reason = mysqli_query($dbc, $query_reason);
    $row_reason = mysqli_fetch_array($result_reason);
	
	 $data .= $row['bflush_no_ref'].";".$row['bflush_no'].";".$row['comp_code'].";".$row['work_center'].";".$row['material_no'].";132;".$row['plan_no'].";".$row['date_plan'].";".$data_uom['scan_shift'].";".$row['qty_NG'].";".$data_uom['scan_uom'].";".$row['ploc'].";".$row['R'].";".$row['time_posting'].";".$row['R2'].";".$row['R3'].";".$row['user_cancel'].";".$row['date_cancel']."\r\n";
	
  
   //----colect data --------------
  $query_collect = "SELECT *, DATE_FORMAT(A1.date_posting,'%Y-%m-%d') AS R FROM pps_detail_transaction AS A1 WHERE A1.id = '".db_esc($dbc, $uid2)."'";
  $rst_collect = mysqli_query($dbc, $query_collect);
  $data_collect = mysqli_fetch_array($rst_collect);
  
   //------get data UOM ----------
	 
  $query_collectA = "SELECT * FROM scan_prod_planning WHERE id_scan = '".db_esc($dbc, $data_collect["id_scan"])."'";
  $rst_collectA = mysqli_query($dbc, $query_collectA);
  $data_collectA = mysqli_fetch_array($rst_collectA);

 
     //--------insert into table ftp_bflush_detail
  
     $query_ftp_info = "INSERT INTO ftp_bflush_detail(id,file_name,bflush_no,ref_id,plan_no,material_no,material_desc,qty_ftp,uom,status_ftp,posting_date,posting_time,user_create,date_create) VALUES('','".db_esc($dbc, $filen)."','".db_esc($dbc, $data_collect["bflush_no"])."','".db_esc($dbc, $data_collect["ref_id"])."','".db_esc($dbc, $data_collect["plan_no"])."','".db_esc($dbc, $data_collect["material_no"])."','".db_esc($dbc, $data_collect["material_desc"])."','".db_esc($dbc, $data_collect["qty_NG"])."','".db_esc($dbc, $data_collectA["scan_uom"])."','Y','".db_esc($dbc, $data_collect["R"])."','".db_esc($dbc, $data_collect["time_posting"])."','".db_esc($dbc, $username)."',NOW())"; 
     $rst_ftp_info = mysqli_query($dbc, $query_ftp_info);
	 
	}elseif($sta_out == "211")
	{
	
	 $data .= $row['bflush_no_ref'].";".$row['bflush_no'].";".$row['comp_code'].";".$row['work_center'].";".$row['material_no'].";132;".$row['plan_no'].";".$row['date_plan'].";".$data_uom['scan_shift'].";".$row['qty_actual'].";".$data_uom['scan_uom'].";".$row['ploc'].";".$row['R'].";".$row['time_posting'].";".$row['R2'].";".$row['R3'].";".$row['user_cancel'].";".$row['date_cancel']."\r\n";	
		
	
	
	  //----colect data --------------
  $query_collect = "SELECT *, DATE_FORMAT(A1.date_posting,'%Y-%m-%d') AS R FROM pps_detail_transaction AS A1 WHERE A1.id = '".db_esc($dbc, $uid2)."'";
  $rst_collect = mysqli_query($dbc, $query_collect);
  $data_collect = mysqli_fetch_array($rst_collect);
  
   //------get data UOM ----------
	 
  $query_collectA = "SELECT * FROM scan_prod_planning WHERE id_scan = '".db_esc($dbc, $data_collect["id_scan"])."'";
  $rst_collectA = mysqli_query($dbc, $query_collectA);
  $data_collectA = mysqli_fetch_array($rst_collectA);

 
     //--------insert into table ftp_bflush_detail
  
     $query_ftp_info = "INSERT INTO ftp_bflush_detail(id,file_name,bflush_no,ref_id,plan_no,material_no,material_desc,qty_ftp,uom,status_ftp,posting_date,posting_time,user_create,date_create) VALUES('','".db_esc($dbc, $filen)."','".db_esc($dbc, $data_collect["bflush_no"])."','".db_esc($dbc, $data_collect["ref_id"])."','".db_esc($dbc, $data_collect["plan_no"])."','".db_esc($dbc, $data_collect["material_no"])."','".db_esc($dbc, $data_collect["material_desc"])."','".db_esc($dbc, $data_collect["qty_actual"])."','".db_esc($dbc, $data_collectA["scan_uom"])."','Y','".db_esc($dbc, $data_collect["R"])."','".db_esc($dbc, $data_collect["time_posting"])."','".db_esc($dbc, $username)."',NOW())"; 
     $rst_ftp_info = mysqli_query($dbc, $query_ftp_info);
		
	}else{
		
	
	/* $data .= $row['bflush_no_ref'].";".$row['bflush_no'].";".$row['comp_code'].";".$row['work_center'].";".$row['material_no'].";132;".$row['plan_no'].";".$row['date_plan'].";".$data_uom['scan_shift'].";".$row['qty_actual'].";".$data_uom['scan_uom'].";".$row['ploc'].";".$row['R'].";".$row['time_posting'].";".$row['R2'].";".$row['R3'].";".$row['user_cancel'].";".$row['date_cancel']."\r\n";	
		
	
	
	  //----colect data --------------
  $query_collect = "SELECT *, DATE_FORMAT(A1.date_posting,'%Y-%m-%d') AS R FROM pps_detail_transaction AS A1, scan_prod_planning AS A2 WHERE A2.id_scan = A1.id_scan AND A1.id = '$uid2'";
  $rst_collect = mysqli_query($dbc, $query_collect);
  $data_collect = mysqli_fetch_array($rst_collect);

 
  //------get data UOM ----------
	 
  $query_collectA = "SELECT * FROM scan_prod_planning WHERE id_scan = '".$data_collect["id_scan"]."'";
  $rst_collectA = mysqli_query($dbc, $query_collectA);
  $data_collectA = mysqli_fetch_array($rst_collectA);
  
     //--------insert into table ftp_bflush_detail
  
     $query_ftp_info = "INSERT INTO ftp_bflush_detail(id, file_name, bflush_no, ref_id, plan_no, material_no, material_desc, qty_ftp, uom, status_ftp, posting_date, posting_time, user_create, date_create) VALUES('','".$filen."','".$data_collect["bflush_no"]."','".$data_collect["ref_id"]."','".$data_collect["plan_no"]."','".$data_collect["material_no"]."','".$data_collect["material_desc"]."','".$data_collect["qty_actual"]."','".$data_collectA["scan_uom"]."','Y','".$data_collect["R"]."','".$data_collect["time_posting"]."','".$username."',NOW())"; 
     $rst_ftp_info = mysqli_query($dbc, $query_ftp_info);	*/
		
		
	}
  
  
  
  
}


$filen="BF2".$buid;
//$csv_filename = $filen."_".date("YmdHis",time());

$file = "../FromPortal/".$filen.".csv";
//chmod($file, 0777);
file_put_contents($file,$data);
   
   
   
   
     // ---update status 

		$query_ftp = "UPDATE pps_detail_transaction SET status_ftp_bflush = 'Y' WHERE id = '".db_esc($dbc, $uid2)."'";
		$rst_query_ftp = mysqli_query($dbc, $query_ftp); //or die ("Error in query: $query_ftp"); 
		
				
		if($rst_query_ftp > 0)
		{
			
		echo "<script>";
		echo "parent.tb_remove(); parent.location.reload(1)";
		echo "</script>";
		exit(); //quit the script
		
	      }
		
      
    
?>
