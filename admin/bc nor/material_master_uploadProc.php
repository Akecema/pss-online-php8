<?php

/**
 * admin/bc nor/material_master_uploadProc.php
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
session_start();

$username = $_SESSION['username'];
include '../include/config.php';
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
$col13 = trim($allDataInSheet[$i]["M"]);
$col14 = trim($allDataInSheet[$i]["N"]);
$col15 = trim($allDataInSheet[$i]["O"]);
$col16 = trim($allDataInSheet[$i]["P"]);
$col17 = trim($allDataInSheet[$i]["Q"]);
$col18 = trim($allDataInSheet[$i]["R"]);
$col19 = trim($allDataInSheet[$i]["S"]);
$col20 = trim($allDataInSheet[$i]["T"]);
$col21 = trim($allDataInSheet[$i]["U"]);


//change date format	
$dateArray = explode('.', $col11 );
$dcol11 = $dateArray[2].'-'.$dateArray[1].'-'.$dateArray[0];

$dateArray = explode('.', $col12 );
$dcol12 = $dateArray[2].'-'.$dateArray[1].'-'.$dateArray[0];


//1.INSERT INTO TABLE MAT HEADER
//select duplicate material from header
/*$query_Ms = "SELECT * FROM mat_master_header WHERE material_no = '".$col2."' AND material_type = '".$col4."' AND bom = '".$col8."' AND status_BOM = 'Y'";*/
$query_Ms = "SELECT * FROM mat_master_header WHERE material_no = '".$col2."' AND status_BOM = 'Y'";
$result_Ms = mysqli_query($dbc, $query_Ms)or die(mysqli_error($dbc));
$res_Ms = mysqli_fetch_array($result_Ms);


if($res_Ms > 0)
{
	//update bom status = 'N' for current material 
	/*$query_upMh = "UPDATE mat_master_header SET status_BOM = 'N' WHERE material_no = '".$col2."' AND material_type = '".$col4."' AND bom = '".$col8."' AND status_BOM = 'Y'";*/
	$query_upMh = "UPDATE mat_master_header SET status_BOM = 'N' WHERE material_no = '".$col2."' AND status_BOM = 'Y'";
	$result_upMh = mysqli_query($dbc, $query_upMh);	
	
	
	//insert
	$ist_hd = "INSERT INTO mat_master_header(id_hdr,material_no,material_desc,material_type,material_group,plant,bom_usage,bom,alternative_bom,BUn,date_create,date_bom_create,status_BOM,std_package,type_package,location_deliver,station_deliver,rcv_point,part_side,date_uploaded,uploaded_by)
					VALUES('','".$col2."','".$col3."','".$col4."','".$col5."','".$col6."','".$col7."','".$col8."','".$col9."','".$col10."','".$dcol11."','".$dcol12."','".$col13."','".$col14."','".$col15."','".$col16."','".$col17."','".$col18."','".$col19."',NOW(),'".$username."')";
						
	$result_hd = mysqli_query($dbc, $ist_hd) or die('Error, failed to add into material.');		
	
	
}
else
{
	$ist_hd22 = "INSERT INTO mat_master_header(id_hdr,material_no,material_desc,material_type,material_group,plant,bom_usage,bom,alternative_bom,BUn,date_create,date_bom_create,status_BOM,std_package,type_package,location_deliver,station_deliver,rcv_point,part_side,date_uploaded,uploaded_by)
					VALUES('','".$col2."','".$col3."','".$col4."','".$col5."','".$col6."','".$col7."','".$col8."','".$col9."','".$col10."','".$dcol11."','".$dcol12."','".$col13."','".$col14."','".$col15."','".$col16."','".$col17."','".$col18."','".$col19."',NOW(),'".$username."')";
						
	$result_hd22 = mysqli_query($dbc, $ist_hd22) or die('Error, failed to add into header material 2.');		
}


//2.INSERT INTO TABLE MAT 
//select duplicate material from table material
/*$query_Mtr = "SELECT * FROM table_material WHERE material_no = '".$col2."' AND mat_type = '".$col4."' AND bom_status = 'Y'";*/
$query_Mtr = "SELECT * FROM table_material WHERE material_no = '".$col2."' AND bom_status = 'Y'";
$result_Mtr = mysqli_query($dbc, $query_Mtr)or die(mysqli_error($dbc));
$res_Mtr = mysqli_fetch_array($result_Mtr);


if($res_Mtr > 0) //if exist
{
	$query_upMtb = "UPDATE table_material SET bom_status = 'N' WHERE material_no = '".$col2."'  ";
	$result_upMtb = mysqli_query($dbc, $query_upMtb);	
	
	if($result_upMtb)
	{
		//insert into table material
		$ist_mt = "INSERT INTO table_material
					(id_mat,material_no,material_desc,mat_type,plan_code,BUn,date_create_bom,bom_status,date_uploaded,uploaded_by)
						VALUES('','".$col2."','".$col3."','".$col4."','".$col6."','".$col10."','".$dcol11."','".$col13."',NOW(),'".$username."') ";
							
		$result_mt = mysqli_query($dbc, $ist_mt) or die('Error, failed to add into table material.');	
	}	
	
}
else
{
	//insert into table material
	$ist_mt22 = "INSERT INTO table_material
					(id_mat,material_no,material_desc,mat_type,plan_code,BUn,date_create_bom,bom_status,date_uploaded,uploaded_by)
						VALUES('','".$col2."','".$col3."','".$col4."','".$col6."','".$col10."','".$dcol11."','".$col13."',NOW(),'".$username."') ";
						
	$result_mt22 = mysqli_query($dbc, $ist_mt22) or die('Error, failed to add into table material 2.');	
}


//3.FOR MAT TYPE='Z310',INSERT INTO TABLE MAT QC
if($col4 == 'Z310')
{
	/*$query_Mtrqc = "SELECT * FROM table_material_qc WHERE material_no = '".$col2."' AND mat_type = 'Z310' AND material_group = '".$col5."' AND bom_status = 'Y'";*/
	$query_Mtrqc = "SELECT * FROM table_material_qc WHERE material_no = '".$col2."' AND bom_status = 'Y'";
	$result_Mtrqc = mysqli_query($dbc, $query_Mtrqc)or die(mysqli_error($dbc));
	$res_Mtrqc = mysqli_fetch_array($result_Mtrqc);
	
	if($res_Mtrqc > 0)
	{
		
		$query_upMqc = "UPDATE table_material_qc SET bom_status = 'N' WHERE material_no = '".$col2."' ";
		$result_upMqc = mysqli_query($dbc, $query_upMqc);
		
		//insert into table material
		$ist_qc = "INSERT INTO table_material_qc
					(id_mat,material_no,material_desc,mat_type,plan_code,BUn,date_create_bom,bom_status,material_group,date_uploaded,uploaded_by)
						VALUES('','".$col2."','".$col3."','".$col4."','".$col6."','".$col10."','".$dcol11."','".$col13."','".$col5."',NOW(),'".$username."') ";
						
		$result_qc = mysqli_query($dbc, $ist_qc) or die('Error, failed to add into table material qc.');		
	}
	else
	{
		//insert into table material
		$ist_mtqc22 = "INSERT INTO table_material_qc
						(id_mat,material_no,material_desc,mat_type,plan_code,BUn,date_create_bom,bom_status,material_group,date_uploaded,uploaded_by)
							VALUES('','".$col2."','".$col3."','".$col4."','".$col6."','".$col10."','".$dcol11."','".$col13."','".$col5."',NOW(),'".$username."') ";
							
		$result_mtqc22 = mysqli_query($dbc, $ist_mtqc22) or die('Error, failed to add into table material qc 2.');		
	
	}
	
}

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