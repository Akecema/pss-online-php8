<?php
error_reporting(E_ALL &~ E_NOTICE &~ E_DEPRECATED);
session_start();

$username = $_SESSION['username'];
include '../include/config.php';
include '../include/config_mail.php';

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysql_query($query_setup);   //run the query.
$num_setup = mysql_num_rows($rs_setup);   //how many material are there?
$data_setup = mysql_fetch_array($rs_setup);
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
$queryM = "SELECT * FROM mat_master_header WHERE material_no = '".$col5."' and status_BOM = 'Y' ";
$resultM = mysql_query($queryM) or die (mysql_error());
$resM = mysql_fetch_array($resultM);
$resrow = mysql_num_rows($resultM);

//check if duplicate
$query_Mdt = "SELECT * FROM mat_master_detail WHERE material = '".$col5."' AND bill_component = '".$col17."' AND bom_status = 'Y' ";
$result_Mdt = mysql_query($query_Mdt);
$res_Mdt = mysql_fetch_array($result_Mdt);


if($resrow == 1)//header found
{
	if($res_Mdt > 0)
	{
		
		//update bom status = 'N' for current material
		//update n insert tbl component 
		$query_upMD = "UPDATE mat_master_detail SET bom_status = 'N' WHERE material = '".$col5."'  AND bill_component = '".$col17."' ";
		$result_upMD = mysql_query($query_upMD);
		
		
		if($result_upMD)
		{
			
			$q_headerMdt = "INSERT INTO mat_master_detail 
							(id_dtl,id_hdr,material,bom_category,bom,alternative_bom,valid_from,plant,sloc,isloc,bill_component,
								matl_group,bom_item_category,bom_item_no,comp_unit,consumption,material_desc_c,mat_type,usage_c,
									date_create_bom,bom_status,date_uploaded,uploaded_by,date_updated,updated_by)
									VALUES('','".$resM['id_hdr']."','".$col5."','".$col8."','".$col10."','".$col11."','".$dcol12."','".$col4."','".$col15."','".$col16."','".$col17."','".$col18."','".$col8."','".$col19."','".$col20."','".$col21."','".$col22."','".$col23."','".$col24."','".$dcol25."','".$col27."',NOW(),'".$username."','','')";
			$rst_headerMdt = mysql_query($q_headerMdt)or die('Error, failed to add into table detail.');		
		}
		
	}else{

			$q_headerMdt22 = "INSERT INTO mat_master_detail 
							(id_dtl,id_hdr,material,bom_category,bom,alternative_bom,valid_from,plant,sloc,isloc,bill_component,
								matl_group,bom_item_category,bom_item_no,comp_unit,consumption,material_desc_c,mat_type,usage_c,
									date_create_bom,bom_status,date_uploaded,uploaded_by,date_updated,updated_by)
									VALUES('','".$resM['id_hdr']."','".$col5."','".$col8."','".$col10."','".$col11."','".$dcol12."','".$col4."','".$col15."','".$col16."','".$col17."','".$col18."','".$col8."','".$col19."','".$col20."','".$col21."','".$col22."','".$col23."','".$col24."','".$dcol25."','".$col27."',NOW(),'".$username."','','')";
			$rst_headerMdt2 = mysql_query($q_headerMdt22)or die('Error, failed to add into table detail 2.');		
	
	} // end $res_Mdt
	
	//select duplicate component from table material
	/*$query_Mtr = "SELECT * FROM table_material WHERE material_no = '".$col5."' AND mat_type = '".$col7."' AND bom_status = 'Y'";*/
	$query_Mtr = "SELECT * FROM table_material WHERE material_no = '".$col17."' AND bom_status = 'Y'";
	$result_Mtr = mysql_query($query_Mtr);
	$res_Mtr = mysql_fetch_array($result_Mtr);
	
	
	if($res_Mtr > 0) //if exist
	{
		//update n insert tbl material
		$query_upMtb = "UPDATE table_material SET bom_status = 'N' WHERE material_no = '".$col17."' ";
		$result_upMtb = mysql_query($query_upMtb);
		
		if($result_upMtb)
		{
			//insert into table material
			$ist_mt = "INSERT INTO table_material						(id_mat,material_no,material_desc,mat_type,plan_code,BUn,date_create_bom,bom_status,material_group,date_uploaded,uploaded_by,date_updated,updated_by,part_side)
		VALUES('','".$col17."','".$col22."','".$col23."','".$col4."','".$col20."','".$dcol25."','".$col27."','".$col18."',NOW(),'".$username."','','','') ";					
			$result_mt = mysql_query($ist_mt) or die('Error, failed to add into table material.');	
		
		} //$result_upMtb
				
	}else{
		
		//insert into table material
		$ist_mt22 = "INSERT INTO table_material					(id_mat,material_no,material_desc,mat_type,plan_code,BUn,date_create_bom,bom_status,material_group,date_uploaded,uploaded_by,date_updated,updated_by,part_side)
		VALUES('','".$col17."','".$col22."','".$col23."','".$col4."','".$col20."','".$dcol25."','".$col27."','".$col18."',NOW(),'".$username."','','','') ";				
		$result_mt22 = mysql_query($ist_mt22) or die('Error, failed to add into table material.');		
		
		
	//if mat type Z310
	if($col23 == 'Z310')
	{
		
		$query_Mtrqc = "SELECT * FROM table_material_qc WHERE material_no = '".$col17."' AND bom_status = 'Y'";
		$result_Mtrqc = mysql_query($query_Mtrqc);
		$res_Mtrqc = mysql_fetch_array($result_Mtrqc);
		

			$query_upMqc = "UPDATE table_material_qc SET bom_status = 'N' WHERE material_no = '".$col17."' ";
			$result_upMqc = mysql_query($query_upMqc);
			
			//insert into table material
			$ist_qc = "INSERT INTO table_material_qc						(id_mat,material_no,material_desc,mat_type,plan_code,BUn,date_create_bom,bom_status,material_group,date_uploaded,uploaded_by,date_updated,updated_by,part_side)
			VALUES('','".$col17."','".$col22."','".$col23."','".$col4."','".$col20."','".$dcol25."','".$col27."','".$col18."',NOW(),'".$username."','','','') ";
			$result_qc = mysql_query($ist_qc) or die('Error, failed to add into table material qc.');		
		
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
$result_Mtr = mysql_query($query_Mtr)or die(mysql_error());
$res_Mtr = mysql_fetch_array($result_Mtr);


if($res_Mtr > 0) //if exist
{
	$query_upMtb = "UPDATE table_material SET bom_status = 'N' WHERE material_no = '".$col5."' AND mat_type = '".$col7."' ";
	$result_upMtb = mysql_query($query_upMtb);
	
	if($result_upMtb)
	{
		//insert into table material
		$ist_mt = "INSERT INTO table_material
					(id_mat,material_no,material_desc,mat_type,plan_code,BUn,date_create_bom,bom_status,date_uploaded,uploaded_by)
						VALUES('','".$col5."','".$col22."','".$col7."','".$col13."','".$col20."','".$dcol25."','".$col27."',NOW(),'".$username."') ";
							
		$result_mt = mysql_query($ist_mt) or die('Error, failed to add into table material.');	
	}	
	
	
}
else
{
	//insert into table material
	$ist_mt22 = "INSERT INTO table_material
				(id_mat,material_no,material_desc,mat_type,plan_code,BUn,date_create_bom,bom_status,date_uploaded,uploaded_by)
					VALUES('','".$col5."','".$col22."','".$col7."','".$col13."','".$col20."','".$dcol25."','".$col27."',NOW(),'".$username."') ";
						
	$result_mt22 = mysql_query($ist_mt22) or die('Error, failed to add into table material.');		

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