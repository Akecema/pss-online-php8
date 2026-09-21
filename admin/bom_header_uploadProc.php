<?php

/**
 * admin/bom_header_uploadProc.php
 * Part of: Admin module
 * Filename suggests: bom header uploadProc
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET); handles a file upload; generates an Excel export (PHPExcel).
 * Database tables referenced: sys_setup_maintain, mat_master_header_upload, mat_master_header.
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


  $q = "INSERT INTO mat_master_header_upload(id_hdr,material_no,material_desc,material_type,material_group,plant,bom_usage,bom,alternative_bom,BUn,date_create,date_bom_create) VALUES('','".db_esc($dbc, $col3)."','".db_esc($dbc, $col4)."','".db_esc($dbc, $col2)."','','".db_esc($dbc, $col1)."','".db_esc($dbc, $col5)."','".db_esc($dbc, $col6)."','".db_esc($dbc, $col7)."','".db_esc($dbc, $col9)."','".db_esc($dbc, $col8)."','".db_esc($dbc, $col10)."')";
	$rst = mysqli_query($dbc, $q);	
	
	//----------------------------------------------------------------------------------------	  
//checking duplicate data transfer from SAP
//----------------------------------------------------------------------------------------

         $query_del = "SELECT * FROM mat_master_header_upload";
		 $result_del = mysqli_query($dbc, $query_del);
		 
		 
	     while($rst_del = mysqli_fetch_array($result_del))
		 {
		 
		$query_check = "SELECT * FROM mat_master_header WHERE material_no = '".db_esc($dbc, $rst_del["material_no"])."' AND bom = '".db_esc($dbc, $rst_del["bom"])."'";
		$result_check = mysqli_query($dbc, $query_check);		
		$rst_check = mysqli_fetch_array($result_check);
		
		
		if($rst_check > 0)
		
		{
		//skip for duplicate data or Update alternative BOM
		
		 $query_2_b = "UPDATE mat_master_header SET material_group = '".db_esc($dbc, $rst_del["material_group"])."', material_desc = '".db_esc($dbc, $rst_del["material_desc"])."', bom_usage = '".db_esc($dbc, $rst_del["bom_usage"])."', alternative_bom = '".db_esc($dbc, $rst_del["alternative_bom"])."', BUn = '".db_esc($dbc, $rst_del["BUn"])."', date_create = '".db_esc($dbc, $rst_del["date_create"])."', date_bom_create = '".db_esc($dbc, $rst_del["date_bom_create"])."' WHERE material_no = '".db_esc($dbc, $rst_del["material_no"])."' AND bom = '".db_esc($dbc, $rst_del["bom"])."'";
	  $result_2_b = mysqli_query($dbc, $query_2_b); 
		
	
		}else{
	/*if($rst_check < 0) {*/
			
    $q_header = "INSERT INTO mat_master_header(id_hdr,material_no,material_desc,material_type,material_group,plant,bom_usage,bom,alternative_bom,BUn,date_create,date_bom_create) VALUES('','".db_esc($dbc, $rst_del["material_no"])."','".db_esc($dbc, $rst_del["material_desc"])."','".db_esc($dbc, $rst_del["material_type"])."','".db_esc($dbc, $rst_del["material_group"])."','".db_esc($dbc, $rst_del["plant"])."','".db_esc($dbc, $rst_del["bom_usage"])."','".db_esc($dbc, $rst_del["bom"])."','".db_esc($dbc, $rst_del["alternative_bom"])."','".db_esc($dbc, $rst_del["BUn"])."','".db_esc($dbc, $rst_del["date_create"])."','".db_esc($dbc, $rst_del["date_bom_create"])."')";
	$rst_header = mysqli_query($dbc, $q_header);		
		
		
		}// end else $rst_chceck

  }// end for loop
  

   } // end while loop

//echo $arrayCount;
//----------------------kena buat move file to another folder

 $handle2 = $storagename2;
$destination = "../BOM_update/BOM_upload/".$storagename;

$data = file_get_contents($handle2);

$handle2 = fopen($destination, "w");
fwrite($handle2, $data);
fclose($handle2);

 
 
  
   move_uploaded_file($storagename2, "../BOM_update/BOM_upload/$storagename");
   unlink($storagename2);


      //-------------------------------delete table mat_master_header_upload -------------------------------------
	  $query_hsekeeping = "DELETE FROM mat_master_header_upload";
	  $result_hsekeeping =  mysqli_query($dbc, $query_hsekeeping);
	  
	  //------------------------end delete upload mat_master_header_upload ---------------------------------						
	
	  
	         echo "<script>";
		     echo "alert('Material Master successfully update.');";
			 echo "window.location='bom_header_upload.php'";
		    // echo "parent.tb_remove(); parent.location.reload(1)";
	         echo "</script>"; 
		     exit(); //quit the script
	  
	  
	
		   
		?>
		</table>
               
	</body>
</html>