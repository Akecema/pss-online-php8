<?php

/**
 * admin/bom_detail_uploadProc.php
 * Part of: Admin module
 * Filename suggests: bom detail uploadProc
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET); handles a file upload; generates an Excel export (PHPExcel).
 * Database tables referenced: sys_setup_maintain, mat_master_detail_upload, mat_master_header, mat_master_detail.
 * Includes: config.php, config_mail.php, IOFactory.php.
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
require_role($dbc, 1);
include '../include/config_mail.php';

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
		<title>PHP Drops :: CSV to Database (Transfer Posting)</title>
        <script type="text/JavaScript">

function timedRefresh(timeoutPeriod) {
     setTimeout("location.reload(true);",timeoutPeriod);
}

</script>
	</head> 
    <!-- refresh page every 5 second auto....  <body> 	
    
    <!-- Codes by Quackit.com -->
<body>

		<?php

   
	
	set_time_limit(0);
		?>
		<table width="400px" border=1>
		<?php
		
	//	date_default_timezone_set('Asia/Bangkok');
		//date_default_timezone_set('Asia/Kuala Lumpur');
$storagename = $_GET["file2"];		


$storagename2 = "../BOM_detail_upload/$storagename";
//echo $storagename2. "<br>"; 


//---------------------------------------------------------------------------------

set_include_path(get_include_path() . PATH_SEPARATOR . '../classes/');
include 'PHPExcel/IOFactory.php';

// This is the file path to be uploaded.
$inputFileName = $storagename2; 

try {
	$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
} catch(Exception $e) {
	die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}


$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet


for($i=2;$i<=$arrayCount;$i++){

$col1 = trim($allDataInSheet[$i]["A"]);
$col2 = trim($allDataInSheet[$i]["B"]);
$col3 = trim($allDataInSheet[$i]["C"]);
$col4 = trim($allDataInSheet[$i]["D"]);
$col5 = trim($allDataInSheet[$i]["E"]);
$col6 = trim($allDataInSheet[$i]["F"]);
$col7 = trim($allDataInSheet[$i]["G"]);
$col8 = trim($allDataInSheet[$i]["H"]);
$col9 = trim($allDataInSheet[$i]["I"]);
$col10 = trim($allDataInSheet[$i]["J"]);
$col11 = trim($allDataInSheet[$i]["K"]);
$col12 = trim($allDataInSheet[$i]["L"]);
$col13 = trim($allDataInSheet[$i]["M"]);
$col14 = trim($allDataInSheet[$i]["N"]);
$col15 = trim($allDataInSheet[$i]["O"]);
$col16 = trim($allDataInSheet[$i]["P"]);
$col17 = trim($allDataInSheet[$i]["Q"]);
$col18 = trim($allDataInSheet[$i]["R"]);


$q = "INSERT INTO mat_master_detail_upload(id_dtl, id_hdr, material, bom_category, bom, alternative_bom, valid_from, plant, sloc, isloc, bill_component, matl_group, bom_item_category, bom_item_no, comp_unit, consumption, material_desc_c, mat_type, usage_c, date_create_bom, bom_status) VALUES('','','".db_esc($dbc, $col3)."','4','".db_esc($dbc, $col5)."','".db_esc($dbc, $col6)."','".db_esc($dbc, $col9)."','".db_esc($dbc, $col1)."','".db_esc($dbc, $col18)."','','".db_esc($dbc, $col10)."','','','".db_esc($dbc, $col14)."','".db_esc($dbc, $col13)."','".db_esc($dbc, $col12)."','".db_esc($dbc, $col15)."','".db_esc($dbc, $col2)."','".db_esc($dbc, $col4)."','".db_esc($dbc, $col16)."','Y')";
$rst = mysqli_query($dbc, $q);	


//----------------------------------------------------------
//update the id_hdr refering the header table.....
//----------------------------------------------------------

$query_find_id = "SELECT * from mat_master_header WHERE material_no = '".db_esc($dbc, $col3)."' AND bom = '".db_esc($dbc, $col5)."'";
$result_find_id = mysqli_query($dbc, $query_find_id);
$db_find_id = mysqli_fetch_array($result_find_id);

	 
//--------update-------------

$q2 = "UPDATE mat_master_detail_upload SET id_hdr = '".db_esc($dbc, $db_find_id["id_hdr"])."' WHERE material = '".db_esc($dbc, $col3)."' AND bom = '".db_esc($dbc, $col5)."'";
$rst2 = mysqli_query($dbc, $q2);


//-----------------------------------------------------------------------------------
// Cancel previous component [mat_master_detail]
//-----------------------------------------------------------------------------------


$q3 = "UPDATE mat_master_detail SET bom_status = 'N' WHERE material = '".db_esc($dbc, $col3)."' AND bom = '".db_esc($dbc, $col5)."'";
$rst3 = mysqli_query($dbc, $q3);


}// end for loop


if($rst > 0) {

//----------------------------------------------------------------------------------------------------------------------------------------------		  
//checking duplicate data transfer from SAP
//-----------------------------------------------------------------------------------------------------------------------------------------------
$query_del = "SELECT * from mat_master_detail_upload ";
$result_del = mysqli_query($dbc, $query_del);


while ($rst_del = mysqli_fetch_array($result_del))
{


$query_check = "SELECT * FROM mat_master_header WHERE material_no = '".db_esc($dbc, $rst_del["material"])."'";
$result_check = mysqli_query($dbc, $query_check);		
$rst_check = mysqli_fetch_array($result_check);
	
$q_header = "INSERT INTO mat_master_detail(id_dtl, id_hdr, material, bom_category, bom, alternative_bom, valid_from, plant, sloc, isloc, bill_component, matl_group, bom_item_category, bom_item_no, comp_unit, consumption, material_desc_c, mat_type, usage_c, date_create_bom, bom_status) VALUES('','','".db_esc($dbc, $rst_del["material"])."', '".db_esc($dbc, $rst_del["bom_category"])."', '".db_esc($dbc, $rst_del["bom"])."', '".db_esc($dbc, $rst_del["alternative_bom"])."', '".db_esc($dbc, $rst_del["valid_from"])."','".db_esc($dbc, $rst_del["plant"])."','".db_esc($dbc, $rst_del["sloc"])."', '".db_esc($dbc, $rst_del["isloc"])."','".db_esc($dbc, $rst_del["bill_component"])."','".db_esc($dbc, $rst_del["matl_group"])."','".db_esc($dbc, $rst_del["bom_item_category"])."','".db_esc($dbc, $rst_del["bom_item_no"])."','".db_esc($dbc, $rst_del["comp_unit"])."','".db_esc($dbc, $rst_del["consumption"])."','".db_esc($dbc, $rst_del["material_desc_c"])."','".db_esc($dbc, $rst_del["mat_type"])."','".db_esc($dbc, $rst_del["usage_c"])."','".db_esc($dbc, $rst_del["date_create_bom"])."','".db_esc($dbc, $rst_del["bom_status"])."')";
$rst_header = mysqli_query($dbc, $q_header) or die (mysqli_error($dbc));	
	

$query_2_b = "UPDATE mat_master_detail SET id_hdr = '".db_esc($dbc, $rst_check["id_hdr"])."' WHERE material = '".db_esc($dbc, $rst_check["material_no"])."'";
$result_2_b = mysqli_query($dbc, $query_2_b); 

} // end while loop

}// end if $rst


//----------------------kena buat move file to another folder

$handle2 = $storagename2;
$destination = "../BOM_detail_update/BOM_detail_upload/".$storagename;

$data = file_get_contents($handle2);

$handle2 = fopen($destination, "w");
fwrite($handle2, $data);
fclose($handle2);

 
 
  
   move_uploaded_file($storagename2, "../BOM_detail_update/BOM_detail_upload/$storagename");
   unlink($storagename2);


      //-------------------------------delete table mat_master_header_upload -------------------------------------
	  $query_hsekeeping = "DELETE FROM mat_master_detail_upload";
	  $result_hsekeeping =  mysqli_query($dbc, $query_hsekeeping);
	  
	  //------------------------end delete upload mat_master_header_upload ---------------------------------						
	
	  if($result_hsekeeping)
	  {
	  
	         echo "<script>";
		     echo "alert('BOM details successfully update.');";
		      echo "window.location='bom_detail_upload.php'";
	         echo "</script>"; 
		     exit(); //quit the script
	  
	  
	   }
		 	  
		?>
		</table>
               
	</body>
</html>