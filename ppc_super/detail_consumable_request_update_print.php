<?php

/**
 * ppc_super/detail_consumable_request_update_print.php
 * Part of: PPC module (supervisor/admin tier)
 * Filename suggests: detail consumable request update print
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: user_detail, sys_setup_maintain, consumable_request, consumable_request_close.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, tcpdf_barcodes_2d.php, ]..
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
require_role($dbc, 5);
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

set_time_limit(0);
// include 2D barcode class (search for installation path)
require_once(__DIR__ . '/tcpdf_barcodes_2d.php');


// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$warnaGenap = "#F4FBCA";   // warna blue grey
$warnaGanjil = "#f8f8f8";  // warna putih

$query_u = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
$result_u = mysqli_query($dbc, $query_u);   //run the query.
$data_u = mysqli_fetch_array($result_u);   //how many records are there? 

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo h($data_setup["title_desc"]); ?></title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../css/style.css" type="text/css" media="all" />
<link href="../css/ddtabmenu.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style3 {color: #000000}
@media print{
  body{ background-color:#FFFFFF; background-image:none; color:#000000 }
  #ad{ display:none;}
  #leftbar{ display:none;}
  #contentarea{ width:100%;}
}
.style4 {
	font-size: 14px;
	font-weight: bold;
}
-->
</style>
</head>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>

<?php

function encode($ss,$ntime){
    for($i=0;$i<$ntime;$i++){
        $ss=base64_encode($ss);
    }
return $ss;
}


function decode($ss,$ntime){
    for($i=0;$i<$ntime;$i++){
        $ss=base64_decode($ss);
    }
return $ss;
}


?>

<body>
<p>
  <!-- Header -->
  <!-- End Header -->
  <?php

    $temp_mrin = $_GET["mrin_no"];
	
	
 
    $query_update_print = "UPDATE consumable_request SET status_print = 'Y', status = 'Close', user_update = '".db_esc($dbc, $data_u["user_no"])."', date_update = NOW() WHERE temp_mrin = '".db_esc($dbc, $temp_mrin)."'";
	$result_update_print = mysqli_query($dbc, $query_update_print);
	
	
 $query_tp = "SELECT *, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R FROM consumable_request AS MR, consumable_detail AS SD WHERE MR.id_con = SD.id_con AND MR.temp_mrin = '".db_esc($dbc, $temp_mrin)."' AND MR.status_print = 'Y'";

	$result_tp  = mysqli_query($dbc, $query_tp); 
		
	   while ($row2 = mysqli_fetch_array($result_tp))
{

    //---------------copy from consumable request table to consumable request close table
	
	 	    $query_mm3 = "SELECT * FROM `consumable_request` WHERE temp_mrin = '".db_esc($dbc, $temp_mrin)."' AND status = 'Close' AND id_req_con = '".db_esc($dbc, $row2["id_req_con"])."'"; 
        	$result_mm3 = mysqli_query($dbc, $query_mm3) or die(db_fail($dbc));
			$row_mm3 = mysqli_fetch_array($result_mm3); 
			
		
		$query_mm3_insert =  "INSERT INTO consumable_request_close(id_req_con, mrin_doc, mrin_year, temp_mrin, id_con, id_scan, material_no, con_qty, con_uom, status_request, status_print, status_view, factory, user_create, date_create, user_update, date_update, date_posting, time_posting, status, date_require, time_require, reason_close, reason_close2) VALUES('".db_esc($dbc, $row_mm3["id_req_con"])."','".db_esc($dbc, $row_mm3["mrin_doc"])."','".db_esc($dbc, $row_mm3["mrin_year"])."','".db_esc($dbc, $row_mm3["temp_mrin"])."','".db_esc($dbc, $row_mm3["id_con"])."','".db_esc($dbc, $row_mm3["id_scan"])."','".db_esc($dbc, $row_mm3["material_no"])."','".db_esc($dbc, $row_mm3["con_qty"])."','".db_esc($dbc, $row_mm3["con_uom"])."', '".db_esc($dbc, $row_mm3["status_request"])."','".db_esc($dbc, $row_mm3["status_print"])."','".db_esc($dbc, $row_mm3["status_view"])."','".db_esc($dbc, $row_mm3["factory"])."','".db_esc($dbc, $row_mm3["user_create"])."','".db_esc($dbc, $row_mm3["date_create"])."','".db_esc($dbc, $row_mm3["user_update"])."','".db_esc($dbc, $row_mm3["date_update"])."','".db_esc($dbc, $row_mm3["date_posting"])."','".db_esc($dbc, $row_mm3["time_posting"])."','".db_esc($dbc, $row_mm3["status"])."','".db_esc($dbc, $row_mm3["date_require"])."','".db_esc($dbc, $row_mm3["time_require"])."','','')";
$result_mm3_insert = mysqli_query($dbc, $query_mm3_insert) or die(db_fail($dbc));

} // end while loop

?>
</p>


         </body>
</html>
