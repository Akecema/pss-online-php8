<?php

/**
 * admin/material_master_uploadProc.php
 * Part of: Admin module
 * Filename suggests: material master uploadProc
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET); handles a file upload; generates an Excel export (PHPExcel).
 * Database tables referenced: sys_setup_maintain, mat_master_header, table_material, table_material_qc, mat_master_header_upload.
 * Includes: config.php, config_mail.php, IOFactory.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
error_reporting(E_ALL &~ E_NOTICE &~ E_DEPRECATED);
session_start();

$username = $_SESSION['username'] ?? '';
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
		<title>PHP Drops :: CSV to Database (Material Master Header Upload)</title>
        <script type="text/JavaScript">

function timedRefresh(timeoutPeriod) {
     setTimeout(function () { location.reload(true); }, timeoutPeriod);
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
		
   $storagename = upload_safe_name($_GET["file"], ['xls', 'xlsx', 'csv']);		
   $storagename2 = "../BOM_upload/$storagename";
  
  //echo $storagename2. "<br>"; 


//---------------------------------------------------------------------------------

// PhpSpreadsheet (composer.json) replaces PHPExcel 1.7.8, which was abandoned in 2017. Only real
// spreadsheet formats are accepted, whatever the uploaded file is called.
require_once __DIR__ . '/../vendor/autoload.php';

// This is the file path to be uploaded.
$inputFileName = $storagename2; 

try {
	$objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName, 0, [\PhpOffice\PhpSpreadsheet\IOFactory::READER_XLSX, \PhpOffice\PhpSpreadsheet\IOFactory::READER_XLS, \PhpOffice\PhpSpreadsheet\IOFactory::READER_CSV]);
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
$col13 = trim($allDataInSheet[$i]["M"]);
$col14 = trim($allDataInSheet[$i]["N"]);
$col15 = trim($allDataInSheet[$i]["O"]);
$col16 = trim($allDataInSheet[$i]["P"]);
$col17 = trim($allDataInSheet[$i]["Q"]);
$col18 = trim($allDataInSheet[$i]["R"]);
$col19 = trim($allDataInSheet[$i]["S"]);
$col20 = trim($allDataInSheet[$i]["T"]);
$col21 = trim($allDataInSheet[$i]["U"]);
$col22 = trim($allDataInSheet[$i]["V"]);



//change date format	
$dateArray = explode('.', $col11 );
$dcol11 = $dateArray[2].'-'.$dateArray[1].'-'.$dateArray[0];

$dateArray = explode('.', $col12 );
$dcol12 = $dateArray[2].'-'.$dateArray[1].'-'.$dateArray[0];


//1.INSERT INTO TABLE MAT HEADER
//select duplicate material from header
/*$query_Ms = "SELECT * FROM mat_master_header WHERE material_no = '".$col2."' AND material_type = '".$col4."' AND bom = '".$col8."' AND status_BOM = 'Y'";*/
$query_Ms = "SELECT * FROM mat_master_header WHERE material_no = ? AND status_BOM = 'Y'"; $query_Ms_args = [$col2];
$result_Ms = db_query_bind($dbc, $query_Ms, $query_Ms_args)or die(db_fail($dbc));
$res_Ms = mysqli_fetch_array($result_Ms);


if($res_Ms > 0)
{
	//update bom status = 'N' for current material 
	/*$query_upMh = "UPDATE mat_master_header SET status_BOM = 'N' WHERE material_no = '".$col2."' AND material_type = '".$col4."' AND bom = '".$col8."' AND status_BOM = 'Y'";*/
	$query_upMh = "UPDATE mat_master_header SET status_BOM = 'N' WHERE material_no = ? AND status_BOM = 'Y'"; $query_upMh_args = [$col2];
	$result_upMh = db_query_bind($dbc, $query_upMh, $query_upMh_args);	
	
	
	//insert
	$ist_hd = "INSERT INTO mat_master_header(id_hdr,material_no,material_desc,material_type,material_group,plant,bom_usage,bom,alternative_bom,BUn,date_create,date_bom_create,status_BOM,std_package,type_package,location_deliver,station_deliver,rcv_point,part_side,date_uploaded,uploaded_by,date_updated,updated_by)
		VALUES('',?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW(),?,'','')"; $ist_hd_args = [$col2, $col3, $col4, $col5, $col6, $col7, $col8, $col9, $col10, $dcol11, $dcol12, $col13, $col14, $col15, $col16, $col17, $col18, $col19, $username];
	$result_hd = db_query_bind($dbc, $ist_hd, $ist_hd_args) or die('Error, failed to add into material.');		
	
	
}
else
{
	$ist_hd22 = "INSERT INTO mat_master_header(id_hdr,material_no,material_desc,material_type,material_group,plant,bom_usage,bom,alternative_bom,BUn,date_create,date_bom_create,status_BOM,std_package,type_package,location_deliver,station_deliver,rcv_point,part_side,date_uploaded,uploaded_by,date_updated,updated_by)
					VALUES('',?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW(),?,'','')"; $ist_hd22_args = [$col2, $col3, $col4, $col5, $col6, $col7, $col8, $col9, $col10, $dcol11, $dcol12, $col13, $col14, $col15, $col16, $col17, $col18, $col19, $username];
	$result_hd22 = db_query_bind($dbc, $ist_hd22, $ist_hd22_args) or die('Error, failed to add into header material 2.');		
}


//2.INSERT INTO TABLE MAT 
//select duplicate material from table material
/*$query_Mtr = "SELECT * FROM table_material WHERE material_no = '".$col2."' AND mat_type = '".$col4."' AND bom_status = 'Y'";*/
$query_Mtr = "SELECT * FROM table_material WHERE material_no = ? AND bom_status = 'Y'"; $query_Mtr_args = [$col2];
$result_Mtr = db_query_bind($dbc, $query_Mtr, $query_Mtr_args)or die(db_fail($dbc));
$res_Mtr = mysqli_fetch_array($result_Mtr);


if($res_Mtr > 0) //if exist
{
	$query_upMtb = "UPDATE table_material SET bom_status = 'N' WHERE material_no = ?  "; $query_upMtb_args = [$col2];
	$result_upMtb = db_query_bind($dbc, $query_upMtb, $query_upMtb_args);	
	
	if($result_upMtb)
	{
		//insert into table material
		$ist_mt = "INSERT INTO table_material		(id_mat,material_no,material_desc,mat_type,plan_code,BUn,date_create_bom,bom_status,material_group,date_uploaded,uploaded_by,date_updated,updated_by,part_side)
	VALUES('',?,?,?,?,?,?,?,?,NOW(),?,'','',?) "; $ist_mt_args = [$col2, $col3, $col4, $col6, $col10, $dcol11, $col13, $col5, $username, $col19];
		$result_mt = db_query_bind($dbc, $ist_mt, $ist_mt_args) or die('Error, failed to add into table material.');	
	}	
	
}
else
{
	//insert into table material
	$ist_mt22 = "INSERT INTO table_material					(id_mat,material_no,material_desc,mat_type,plan_code,BUn,date_create_bom,bom_status,material_group,date_uploaded,uploaded_by,date_updated,updated_by,part_side) VALUES('',?,?,?,?,?,?,?,?,NOW(),?,'','',?) "; $ist_mt22_args = [$col2, $col3, $col4, $col6, $col10, $dcol11, $col13, $col5, $username, $col19];
	$result_mt22 = db_query_bind($dbc, $ist_mt22, $ist_mt22_args) or die('Error, failed to add into table material 2.');	
	
	
	
	
	//3.FOR MAT TYPE='Z310',INSERT INTO TABLE MAT QC
if($col4 == 'Z310')
{
	
	$query_Mtrqc = "SELECT * FROM table_material_qc WHERE material_no = ? AND bom_status = 'Y'"; $query_Mtrqc_args = [$col2];
	$result_Mtrqc = db_query_bind($dbc, $query_Mtrqc, $query_Mtrqc_args);
	$res_Mtrqc = mysqli_fetch_array($result_Mtrqc);
	
	
		$query_upMqc = "UPDATE table_material_qc SET bom_status = 'N' WHERE material_no = ? "; $query_upMqc_args = [$col2];
		$result_upMqc = db_query_bind($dbc, $query_upMqc, $query_upMqc_args);
		
		//insert into table material QC
		$ist_qc = "INSERT INTO table_material_qc				(id_mat,material_no,material_desc,mat_type,plan_code,BUn,date_create_bom,bom_status,material_group,date_uploaded,uploaded_by,date_updated,updated_by,part_side)	VALUES('',?,?,?,?,?,?,?,?,NOW(),?,'','',?) "; $ist_qc_args = [$col2, $col3, $col4, $col6, $col10, $dcol11, $col13, $col5, $username, $col19];
		$result_qc = db_query_bind($dbc, $ist_qc, $ist_qc_args) or die('Error, failed to add into table material qc.');		
	
		
} // if $res_MTR



}// end if 


 

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