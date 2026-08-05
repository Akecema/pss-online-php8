<?php
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


$q = "INSERT INTO mat_master_detail_upload(id_dtl, id_hdr, material, bom_category, bom, alternative_bom, valid_from, plant, sloc, isloc, bill_component, matl_group, bom_item_category, bom_item_no, comp_unit, consumption, material_desc_c, mat_type, usage_c, date_create_bom, bom_status) VALUES('','','".$col3."','4','".$col5."','".$col6."','".$col9."','".$col1."','".$col18."','','".$col10."','','','".$col14."','".$col13."','".$col12."','".$col15."','".$col2."','".$col4."','".$col16."','Y')";
$rst = mysqli_query($dbc, $q);	


//----------------------------------------------------------
//update the id_hdr refering the header table.....
//----------------------------------------------------------

$query_find_id = "SELECT * from mat_master_header WHERE material_no = '".$col3."' AND bom = '".$col5."'";
$result_find_id = mysqli_query($dbc, $query_find_id);
$db_find_id = mysqli_fetch_array($result_find_id);

	 
//--------update-------------

$q2 = "UPDATE mat_master_detail_upload SET id_hdr = '".$db_find_id["id_hdr"]."' WHERE material = '".$col3."' AND bom = '".$col5."'";
$rst2 = mysqli_query($dbc, $q2);


//-----------------------------------------------------------------------------------
// Cancel previous component [mat_master_detail]
//-----------------------------------------------------------------------------------


$q3 = "UPDATE mat_master_detail SET bom_status = 'N' WHERE material = '".$col3."' AND bom = '".$col5."'";
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


$query_check = "SELECT * FROM mat_master_header WHERE material_no = '".$rst_del["material"]."'";
$result_check = mysqli_query($dbc, $query_check);		
$rst_check = mysqli_fetch_array($result_check);
	
$q_header = "INSERT INTO mat_master_detail(id_dtl, id_hdr, material, bom_category, bom, alternative_bom, valid_from, plant, sloc, isloc, bill_component, matl_group, bom_item_category, bom_item_no, comp_unit, consumption, material_desc_c, mat_type, usage_c, date_create_bom, bom_status) VALUES('','','".$rst_del["material"]."', '".$rst_del["bom_category"]."', '".$rst_del["bom"]."', '".$rst_del["alternative_bom"]."', '".$rst_del["valid_from"]."','".$rst_del["plant"]."','".$rst_del["sloc"]."', '".$rst_del["isloc"]."','".$rst_del["bill_component"]."','".$rst_del["matl_group"]."','".$rst_del["bom_item_category"]."','".$rst_del["bom_item_no"]."','".$rst_del["comp_unit"]."','".$rst_del["consumption"]."','".$rst_del["material_desc_c"]."','".$rst_del["mat_type"]."','".$rst_del["usage_c"]."','".$rst_del["date_create_bom"]."','".$rst_del["bom_status"]."')";
$rst_header = mysqli_query($dbc, $q_header) or die (mysqli_error($dbc));	
	

$query_2_b = "UPDATE mat_master_detail SET id_hdr = '".$rst_check["id_hdr"]."' WHERE material = '".$rst_check["material_no"]."'";
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