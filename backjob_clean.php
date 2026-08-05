<?php

$sql = "SELECT * FROM material_request AS MR, scan_detail AS SD, user_detail AS UD WHERE MR.id_scan = SD.id_scan AND MR.user_create = UD.user_no AND MR.status_request = 'N' AND (MR.status != 'Close' AND MR.status != 'Cancel') AND UD.username = '$username'";
$result = mysqli_query($dbc, $sql) or trigger_error("SQL", E_USER_ERROR);
$r = mysqli_num_rows($result);

$numrows = $r;
//echo $numrows;
//echo "<br>";
while ($r2 = mysqli_fetch_array($result))
{

  $sql_delete_request = "DELETE FROM material_request WHERE id_req = '".$r2["id_req"]."'";
  $result_delete_request =mysqli_query($dbc, $sql_delete_request);
 
  $sql_delete_scanning = "DELETE FROM scan_detail WHERE id_scan = '".$r2["id_scan"]."'";
   $result_delete_scanning =mysqli_query($dbc, $sql_delete_scanning);


}

//-------------------------------------------------------------------------------------------------------
// delete consumable after logout
//-------------------------------------------------------------------------------------------------------

$sql_c = "SELECT * FROM consumable_request AS CR, user_detail AS UD WHERE CR.user_create = UD.user_no AND CR.status_request = 'N' AND (CR.status != 'Close' AND CR.status != 'Cancel') AND UD.username = '$username'";
$result_c = mysqli_query($dbc, $sql_c) or trigger_error("SQL", E_USER_ERROR);
$r_c = mysqli_num_rows($result_c);

//$numrows_c = $r_c;
//echo $numrows_c;
//echo "<br>";
while ($r2_c = mysqli_fetch_array($result_c))
{

  $sql_delete_consumable = "DELETE FROM consumable_request WHERE id_req_con = '".$r2_c["id_req_con"]."'";
  $result_delete_consumable =mysqli_query($dbc, $sql_delete_consumable);
 
 
}

//------------------------------------------------------------------------------------------------
//  delete wip after logout
//------------------------------------------------------------------------------------------------

$sql_wip = "SELECT * FROM wip_request AS WR, scan_detail_wip AS WD, user_detail AS UD WHERE WR.id_scan_wip = WD.id_scan AND WR.user_create = UD.user_no AND WR.status_request = 'N' AND (WR.status != 'Close' AND WR.status != 'Cancel') AND UD.username = '$username'";
$result_wip = mysqli_query($dbc, $sql_wip) or trigger_error("SQL", E_USER_ERROR);
$r_wip = mysqli_num_rows($result_wip);

$numrows = $r_wip;
//echo $numrows;
//echo "<br>";
while ($r2_wip = mysqli_fetch_array($result_wip))
{

  $sql_delete_request_wip = "DELETE FROM wip_request WHERE id_req_wip = '".$r2_wip["id_req_wip"]."'";
  $result_delete_request_wip =mysqli_query($dbc, $sql_delete_request_wip);
 
  $sql_delete_scanning_wip = "DELETE FROM scan_detail_wip WHERE id_scan = '".$r2_wip["id_scan_wip"]."'";
  $result_delete_scanning_wip =mysqli_query($dbc, $sql_delete_scanning);


}



//------------------------------------------------------------------------------------------------
//  delete pps after logout
//------------------------------------------------------------------------------------------------

$sql_pps = "SELECT * FROM pps_detail AS PD, user_detail AS UD2 WHERE PD.user_create = UD2.username AND PD.status_pps = 'New' AND PD.plan_no = '' AND UD2.username = '$username'";
$result_pps = mysqli_query($dbc, $sql_pps) or trigger_error("SQL", E_USER_ERROR);
$r_pps = mysqli_num_rows($result_pps);

$numrows2 = $r_pps;
//echo $numrows;
//echo "<br>";
while ($r2_pps = mysqli_fetch_array($result_pps))
{
	
 /* $sql_delete_request_pps = "UPDATE pps_detail SET status_pps = 'Delete' WHERE id = '".$r2_pps["id"]."'";
  $result_delete_request_pps =mysqli_query($dbc, $sql_delete_request_pps);*/

  $sql_delete_request_pps = "DELETE FROM pps_detail WHERE id = '".$r2_pps["id"]."'";
  $result_delete_request_pps =mysqli_query($dbc, $sql_delete_request_pps);


}


	      
?>