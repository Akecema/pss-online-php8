<?php

/**
 * admin/upd_mat_uploadProc.php
 * Part of: Admin module
 * Filename suggests: upd mat uploadProc
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET); handles a file upload; generates an Excel export (PHPExcel).
 * Database tables referenced: sys_setup_maintain, mat_master_header, table_material, mat_master_header_upload.
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
		
   $storagename = $_GET["file"];		
   $storagename2 = "../BOM_upload/$storagename";
  
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

$mesej2 = "";
$mesej3 = "";

//if($i>=0)
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

$query_Ms = "SELECT * FROM mat_master_header as mh WHERE mh.material_type ='Z310' and material_no = '".$col3."'";
$result_Ms = mysqli_query($dbc, $query_Ms);
$res_Ms = mysqli_fetch_array($result_Ms);


if($res_Ms > 0)
{
	$query_upMh = "UPDATE mat_master_header SET material_desc ='".$col4."' WHERE material_no = '".$col3."' ";
	$result_upMh = mysqli_query($dbc, $query_upMh);
	
	//mm
	$query_upMh2 = "UPDATE table_material SET material_desc ='".$col4."' WHERE material_no = '".$col3."' ";
	$result_upMh2 = mysqli_query($dbc, $query_upMh2);
}















/*while($res_Ms = mysqli_fetch_array($result_Ms))
{
	
	$query_upMh = "UPDATE mat_master_header SET material_desc ='".$col4."' WHERE material_no = '".$col3."' ";
	$result_upMh = mysqli_query($dbc, $query_upMh);
	
	
	//
	$query_upMh2 = "UPDATE table_material SET material_desc ='".$col4."' WHERE material_no = '".$col3."' ";
	$result_upMh2 = mysqli_query($dbc, $query_upMh2);
	
	
	if($result_upMh)
	{
		$mesej2 = "<script language='JavaScript'>alert('Material Master successfully upload.');window.location='upd_mat_upload.php';</script>";
	}
		
	
}*/














}//end for



//echo $arrayCount;
//----------------------kena buat move file to another folder

$handle2 = $storagename2;
$destination = "../BOM_update/BOM_upload/".$storagename;

$data = file_get_contents($handle2);

$handle2 = fopen($destination, "w");
fwrite($handle2, $data);
fclose($handle2);


$mv = move_uploaded_file($storagename2, "../BOM_update/BOM_upload/$storagename");
$un = unlink($storagename2);

if($un)
{
	$mesej2 = "<script language='JavaScript'>alert('Material Master successfully upload.');window.location='material_master_upload.php';</script>";
}

echo $mesej2; echo $mesej3;

//-------------------------------delete table mat_master_header_upload -------------------------------------
/*$query_hsekeeping = "DELETE FROM mat_master_header_upload";
$result_hsekeeping =  mysqli_query($dbc, $query_hsekeeping);
*/

//------------------------end delete upload mat_master_header_upload ---------------------------------						

/*echo "<script>";
echo "alert('Material Master successfully upload.');";
echo "window.location='material_master_upload.php'";
echo "parent.tb_remove(); parent.location.reload(1)";
echo "</script>"; 
exit(); //quit the script
*/
		   
?>
</table>
               
</body>
</html>