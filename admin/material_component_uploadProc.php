<?php

/**
 * admin/material_component_uploadProc.php
 * Part of: Admin module
 * Filename suggests: material component uploadProc
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET); handles a file upload; generates an Excel export (PHPExcel).
 * Database tables referenced: sys_setup_maintain, mat_master_header, mat_master_detail, table_material, table_material_qc.
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
		<title>PHP Drops :: CSV to Database (BOM Compononent)</title>
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
	
$storagename = upload_safe_name($_GET["file"], ['xls', 'xlsx', 'csv']);		
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
$col22 = trim($allDataInSheet[$i]["V"]);
$col23 = trim($allDataInSheet[$i]["W"]);
$col24 = trim($allDataInSheet[$i]["X"]);
$col25 = trim($allDataInSheet[$i]["Y"]);
$col26 = trim($allDataInSheet[$i]["Z"]);
$col27 = trim($allDataInSheet[$i]["AA"]);
$col28 = trim($allDataInSheet[$i]["AB"]);
$col29 = trim($allDataInSheet[$i]["AC"]);
$col30 = trim($allDataInSheet[$i]["AD"]);
$col31 = trim($allDataInSheet[$i]["AE"]);


//change date format
$dateArray = explode('.', $col12);
$dcol12 = $dateArray[2].'-'.$dateArray[1].'-'.$dateArray[0];

$dateArray2 = explode('.', $col25 );
$dcol25 = $dateArray2[2].'-'.$dateArray2[1].'-'.$dateArray2[0];


//find header id form tbl header
$queryM = "SELECT * FROM mat_master_header WHERE material_no = '".db_esc($dbc, $col5)."' and status_BOM = 'Y' ";
$resultM = mysqli_query($dbc, $queryM) or die(db_fail($dbc));
$resM = mysqli_fetch_array($resultM);
$resrow = mysqli_num_rows($resultM);

//check if duplicate
$query_Mdt = "SELECT * FROM mat_master_detail WHERE material = '".db_esc($dbc, $col5)."' AND bill_component = '".db_esc($dbc, $col17)."' AND bom_status = 'Y' ";
$result_Mdt = mysqli_query($dbc, $query_Mdt);
$res_Mdt = mysqli_fetch_array($result_Mdt);


if($resrow == 1)//header found
{
	if($res_Mdt > 0)
	{
		
		//update bom status = 'N' for current material
		//update n insert tbl component 
		$query_upMD = "UPDATE mat_master_detail SET bom_status = 'N' WHERE material = '".db_esc($dbc, $col5)."'  AND bill_component = '".db_esc($dbc, $col17)."' ";
		$result_upMD = mysqli_query($dbc, $query_upMD);
		
		
		if($result_upMD)
		{
			
			$q_headerMdt = "INSERT INTO mat_master_detail 
							(id_dtl,id_hdr,material,bom_category,bom,alternative_bom,valid_from,plant,sloc,isloc,bill_component,
								matl_group,bom_item_category,bom_item_no,comp_unit,consumption,material_desc_c,mat_type,usage_c,
									date_create_bom,bom_status,date_uploaded,uploaded_by,date_updated,updated_by)
									VALUES('','".db_esc($dbc, $resM['id_hdr'])."','".db_esc($dbc, $col5)."','".db_esc($dbc, $col8)."','".db_esc($dbc, $col10)."','".db_esc($dbc, $col11)."','".db_esc($dbc, $dcol12)."','".db_esc($dbc, $col4)."','".db_esc($dbc, $col15)."','".db_esc($dbc, $col16)."','".db_esc($dbc, $col17)."','".db_esc($dbc, $col18)."','".db_esc($dbc, $col8)."','".db_esc($dbc, $col19)."','".db_esc($dbc, $col20)."','".db_esc($dbc, $col21)."','".db_esc($dbc, $col22)."','".db_esc($dbc, $col23)."','".db_esc($dbc, $col24)."','".db_esc($dbc, $dcol25)."','".db_esc($dbc, $col27)."',NOW(),'".db_esc($dbc, $username)."','','')";
			$rst_headerMdt = mysqli_query($dbc, $q_headerMdt)or die('Error, failed to add into table detail.');		
		}
		
	}else{

			$q_headerMdt22 = "INSERT INTO mat_master_detail 
							(id_dtl,id_hdr,material,bom_category,bom,alternative_bom,valid_from,plant,sloc,isloc,bill_component,
								matl_group,bom_item_category,bom_item_no,comp_unit,consumption,material_desc_c,mat_type,usage_c,
									date_create_bom,bom_status,date_uploaded,uploaded_by,date_updated,updated_by)
									VALUES('','".db_esc($dbc, $resM['id_hdr'])."','".db_esc($dbc, $col5)."','".db_esc($dbc, $col8)."','".db_esc($dbc, $col10)."','".db_esc($dbc, $col11)."','".db_esc($dbc, $dcol12)."','".db_esc($dbc, $col4)."','".db_esc($dbc, $col15)."','".db_esc($dbc, $col16)."','".db_esc($dbc, $col17)."','".db_esc($dbc, $col18)."','".db_esc($dbc, $col8)."','".db_esc($dbc, $col19)."','".db_esc($dbc, $col20)."','".db_esc($dbc, $col21)."','".db_esc($dbc, $col22)."','".db_esc($dbc, $col23)."','".db_esc($dbc, $col24)."','".db_esc($dbc, $dcol25)."','".db_esc($dbc, $col27)."',NOW(),'".db_esc($dbc, $username)."','','')";
			$rst_headerMdt2 = mysqli_query($dbc, $q_headerMdt22)or die('Error, failed to add into table detail 2.');		
	
	} // end $res_Mdt
	
	//select duplicate component from table material
	/*$query_Mtr = "SELECT * FROM table_material WHERE material_no = '".$col5."' AND mat_type = '".$col7."' AND bom_status = 'Y'";*/
	$query_Mtr = "SELECT * FROM table_material WHERE material_no = '".db_esc($dbc, $col17)."' AND bom_status = 'Y'";
	$result_Mtr = mysqli_query($dbc, $query_Mtr);
	$res_Mtr = mysqli_fetch_array($result_Mtr);
	
	
	if($res_Mtr > 0) //if exist
	{
		//update n insert tbl material
		$query_upMtb = "UPDATE table_material SET bom_status = 'N' WHERE material_no = '".db_esc($dbc, $col17)."' ";
		$result_upMtb = mysqli_query($dbc, $query_upMtb);
		
		if($result_upMtb)
		{
			//insert into table material
			$ist_mt = "INSERT INTO table_material						(id_mat,material_no,material_desc,mat_type,plan_code,BUn,date_create_bom,bom_status,material_group,date_uploaded,uploaded_by,date_updated,updated_by,part_side)
		VALUES('','".db_esc($dbc, $col17)."','".db_esc($dbc, $col22)."','".db_esc($dbc, $col23)."','".db_esc($dbc, $col4)."','".db_esc($dbc, $col20)."','".db_esc($dbc, $dcol25)."','".db_esc($dbc, $col27)."','".db_esc($dbc, $col18)."',NOW(),'".db_esc($dbc, $username)."','','','') ";					
			$result_mt = mysqli_query($dbc, $ist_mt) or die('Error, failed to add into table material.');	
		
		} //$result_upMtb
				
	}else{
		
		//insert into table material
		$ist_mt22 = "INSERT INTO table_material					(id_mat,material_no,material_desc,mat_type,plan_code,BUn,date_create_bom,bom_status,material_group,date_uploaded,uploaded_by,date_updated,updated_by,part_side)
		VALUES('','".db_esc($dbc, $col17)."','".db_esc($dbc, $col22)."','".db_esc($dbc, $col23)."','".db_esc($dbc, $col4)."','".db_esc($dbc, $col20)."','".db_esc($dbc, $dcol25)."','".db_esc($dbc, $col27)."','".db_esc($dbc, $col18)."',NOW(),'".db_esc($dbc, $username)."','','','') ";				
		$result_mt22 = mysqli_query($dbc, $ist_mt22) or die('Error, failed to add into table material.');		
		
		
	//if mat type Z310
	if($col23 == 'Z310')
	{
		
		$query_Mtrqc = "SELECT * FROM table_material_qc WHERE material_no = '".db_esc($dbc, $col17)."' AND bom_status = 'Y'";
		$result_Mtrqc = mysqli_query($dbc, $query_Mtrqc);
		$res_Mtrqc = mysqli_fetch_array($result_Mtrqc);
		

			$query_upMqc = "UPDATE table_material_qc SET bom_status = 'N' WHERE material_no = '".db_esc($dbc, $col17)."' ";
			$result_upMqc = mysqli_query($dbc, $query_upMqc);
			
			//insert into table material
			$ist_qc = "INSERT INTO table_material_qc						(id_mat,material_no,material_desc,mat_type,plan_code,BUn,date_create_bom,bom_status,material_group,date_uploaded,uploaded_by,date_updated,updated_by,part_side)
			VALUES('','".db_esc($dbc, $col17)."','".db_esc($dbc, $col22)."','".db_esc($dbc, $col23)."','".db_esc($dbc, $col4)."','".db_esc($dbc, $col20)."','".db_esc($dbc, $dcol25)."','".db_esc($dbc, $col27)."','".db_esc($dbc, $col18)."',NOW(),'".db_esc($dbc, $username)."','','','') ";
			$result_qc = mysqli_query($dbc, $ist_qc) or die('Error, failed to add into table material qc.');		
		
		}  // end mat type Z310
	
		
		
	}//end $res_Mtr
		
	$mesej2 = "<script language='JavaScript'>alert('Material components successfully upload.');window.location='material_component_upload.php';</script>";		
	// $resrow 
}else{
	
	    $mesej3 = "<script language='JavaScript'>alert('There is no material header found for uploaded component.');window.location='material_component_upload.php';</script>";
	
}


} // end for


/*//select duplicate component from table material
$query_Mtr = "SELECT * FROM table_material WHERE material_no = '".$col5."' AND mat_type = '".$col7."' AND bom_status = 'Y'";
$result_Mtr = mysqli_query($dbc, $query_Mtr)or die(db_fail($dbc));
$res_Mtr = mysqli_fetch_array($result_Mtr);


if($res_Mtr > 0) //if exist
{
	$query_upMtb = "UPDATE table_material SET bom_status = 'N' WHERE material_no = '".$col5."' AND mat_type = '".$col7."' ";
	$result_upMtb = mysqli_query($dbc, $query_upMtb);
	
	if($result_upMtb)
	{
		//insert into table material
		$ist_mt = "INSERT INTO table_material
					(id_mat,material_no,material_desc,mat_type,plan_code,BUn,date_create_bom,bom_status,date_uploaded,uploaded_by)
						VALUES('','".$col5."','".$col22."','".$col7."','".$col13."','".$col20."','".$dcol25."','".$col27."',NOW(),'".$username."') ";
							
		$result_mt = mysqli_query($dbc, $ist_mt) or die('Error, failed to add into table material.');	
	}	
	
	
}
else
{
	//insert into table material
	$ist_mt22 = "INSERT INTO table_material
				(id_mat,material_no,material_desc,mat_type,plan_code,BUn,date_create_bom,bom_status,date_uploaded,uploaded_by)
					VALUES('','".$col5."','".$col22."','".$col7."','".$col13."','".$col20."','".$dcol25."','".$col27."',NOW(),'".$username."') ";
						
	$result_mt22 = mysqli_query($dbc, $ist_mt22) or die('Error, failed to add into table material.');		

}*/






$handle2 = $storagename2;
$destination = "../BOM_update/BOM_upload/".$storagename;

$data = file_get_contents($handle2);

$handle2 = fopen($destination, "w");
fwrite($handle2, $data);
fclose($handle2);


$mv = move_uploaded_file($storagename2, "../BOM_update/BOM_upload/$storagename");
$un = unlink($storagename2);

echo $mesej2; echo $mesej3;


?>
</table>
               
</body>
</html>