<?php

/**
 * ppc_super/close_MRIN_manualProc.php
 * Part of: PPC module (supervisor/admin tier)
 * Filename suggests: close MRIN manualProc
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, material_request, factory_detail, mat_master_header, post_detail_header, material_request_close.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, tcpdf_barcodes_2d.php.
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
require_role($dbc, 5);
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

set_time_limit(0);
// include 2D barcode class (search for installation path)
//require_once('/tcpdf_barcodes_2d.php');


// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$warnaGenap = "#F4FBCA";   // warna blue grey
$warnaGanjil = "#f8f8f8";  // warna putih

$query_u = "SELECT * FROM user_detail WHERE username = '$username'";
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
<title><?php echo $data_setup["title_desc"]; ?></title>
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
 $prod_order = $_GET["prod_order"];
 $user_no = $_GET["user_no"];
 $reason_close = $_POST["reason_close"];
 $reason_close2 = $_POST["reason_close2"];

$queryu = "SELECT * from material_request WHERE temp_mrin = '$temp_mrin'";
$rs = mysqli_query($dbc, $queryu);   //run the query.


$query_2 = "SELECT *, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R from material_request as MR, scan_detail as SD WHERE MR.id_scan = SD.id_scan AND MR.temp_mrin = '$temp_mrin'";
$result_2 = mysqli_query($dbc, $query_2);   //run the query.
$data_2 = mysqli_fetch_array($result_2);

    $query3 = "SELECT * FROM factory_detail WHERE id_fac = '".$data_2["factory"]."'";
    $result3 = mysqli_query($dbc, $query3);
	$row3 = mysqli_fetch_array($result3);


$query_tp = "SELECT *, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R FROM material_request AS MR, scan_detail AS SD WHERE MR.id_scan = SD.id_scan AND MR.temp_mrin = '$temp_mrin' AND SD.prod_order = '$prod_order' AND MR.status = 'New'";

	$result_tp  = mysqli_query($dbc, $query_tp); 
		
	   while ($row2 = mysqli_fetch_array($result_tp))
{


  $query4_p = "SELECT * from mat_master_header as LD, mat_master_detail as SD WHERE SD.id_hdr = LD.id_hdr and SD.id_dtl = '".$row2[5]."'";
  	$result4_p = mysqli_query($dbc, $query4_p);
 	$row4_p = mysqli_fetch_array($result4_p); 
	

 $query_upd2 = "UPDATE post_detail_header SET status_posting = 'Close', date_close = NOW() WHERE mrin_no = '".$temp_mrin."'";
 $result_upd2 = mysqli_query($dbc, $query_upd2); 
	      
 $query_upd3 = "UPDATE material_request SET status = 'Close', user_update = '".$user_no."', date_update = NOW() WHERE temp_mrin = '".$temp_mrin."' AND bom_component = '".$row4_p["bill_component"]."' ";
 $result_upd3 = mysqli_query($dbc, $query_upd3); 
 
      
	   //-----------------------------------------------------------------------------------------------------------------------
		  //-insert material request with status "Cancel" into table material request cancel
		  //-----------------------------------------------------------------------------------------------------------------------
		  
		  	$query_mm3 = "SELECT * FROM material_request WHERE temp_mrin = '".$temp_mrin."' AND status = 'Close'"; 
        	$result_mm3 = mysqli_query($dbc, $query_mm3) or die (mysqli_error($dbc));
			$row_mm3 = mysqli_fetch_array($result_mm3); 
			
		
		$query_mm3_insert =  "INSERT INTO material_request_close(id_req, mrin_doc, mrin_year, temp_mrin, id_hdr, id_dtl, id_scan, bom_id, bom_qty, bom_oum, status_request, status_print, user_create, date_create, user_update, date_update, date_posting, time_posting, status, bom_component, date_mrin, time_mrin, reason_close, reason_close2) VALUES('".$row_mm3["id_req"]."','".$row_mm3["mrin_doc"]."','".$row_mm3["mrin_year"]."','".$row_mm3["temp_mrin"]."','".$row_mm3["id_hdr"]."','".$row_mm3["id_dtl"]."','".$row_mm3["id_scan"]."','".$row_mm3["bom_id"]."','".$row_mm3["bom_qty"]."', '".$row_mm3["bom_oum"]."','".$row_mm3["status_request"]."','".$row_mm3["status_print"]."','".$row_mm3["user_create"]."','".$row_mm3["date_create"]."','".$row_mm3["user_update"]."','".$row_mm3["date_update"]."','".$row_mm3["date_posting"]."','".$row_mm3["time_posting"]."','".$row_mm3["status"]."','".$row_mm3["bom_component"]."','".$row_mm3["date_mrin"]."','".$row_mm3["time_mrin"]."','$reason_close','$reason_close2')";
$result_mm3_insert = mysqli_query($dbc, $query_mm3_insert) or die (mysqli_error($dbc));
	  
	  
	  
	  
	  
	  
	  

}//end while loop


 //--------------------------------------------------------------------
       //copy yg close MRIN masuk dalam MRIN history
	   //---------------------------------------------------------------------
       
			
			 echo "<script>";
		    //echo "alert('MRIN No. $temp_mrin is successfully close.');";
			// echo "window.location='close_MRIN_ppc.php'";
		     echo "parent.tb_remove(); parent.location.reload(1)";
		     echo "</script>"; 
		     exit(); //quit the script
		 
	
	



?>
</p>


         </body>
</html>
