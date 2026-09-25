<?php

/**
 * planning/detail_pps_sheet_print_by_id.php
 * Part of: Planning module
 * Filename suggests: detail pps sheet print by id
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: sys_setup_maintain, request_status, pps_detail, work_center_detail, ftp_pps, mat_master_header, mat_master_detail.
 * Includes: config.php, tcpdf_barcodes_2d.php.
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
require_role($dbc, 8);

$Cdate = date ("l, j F Y ");

set_time_limit(0);
// include 2D barcode class (search for installation path)
require_once(__DIR__ . '/tcpdf_barcodes_2d.php');


// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------	

//CR status (New)

$sta = "SELECT * from request_status WHERE status_id = '1' ";
$sta_res = mysqli_query($dbc, $sta);
$rst_sta = mysqli_fetch_array($sta_res);

//CR status (Released)
$sta2 = "SELECT * from request_status WHERE status_id = '2' ";
$sta_res2 = mysqli_query($dbc, $sta2);
$rst_sta2 = mysqli_fetch_array($sta_res2);

//CR status (Cancel)
$sta4 = "SELECT * from request_status WHERE status_id = '4' ";
$sta_res4 = mysqli_query($dbc, $sta4);
$rst_sta4 = mysqli_fetch_array($sta_res4);	

//CR status (Delete)
$sta16 = "SELECT * from request_status WHERE status_id = '16' ";
$sta_res16 = mysqli_query($dbc, $sta16);
$rst_sta16 = mysqli_fetch_array($sta_res16);	
	
?>
<!--<!DOCTYPE html>
<html lang="en">
<head>
<title><?php //echo h($data_setup["title_desc"]); ?></title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="shortcut icon" href="../img/favicon.ico">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />-->
<link rel="stylesheet" href="../css/bootstrap.min.css" />
<link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="../css/uniform.css" />
<link rel="stylesheet" href="../css/select2.css" />
<link rel="stylesheet" href="../css/matrix-style.css" />
<link rel="stylesheet" href="../css/matrix-media.css" />
<link href="../font-awesome/css/font-awesome.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/jquery.gritter.css" />
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

<!----------------->
<link rel="stylesheet" href="../scripts/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="../javascript/jquery-latest.js"></script> 
<script type="text/javascript" src="../javascript/thickbox.js"></script>	
<!--<link href="../calendar/calendar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../calendar/calendar.js"></script> -->
<style type="text/css">

.style3 {color: #000000}

.style4 {
	font-size: 14px;
	font-weight: bold;
}

</style>
<style type="text/css" media="print"> 
	  
.breakAfter{ 
page-break-after: always; 
}
.breakBefore{
page-break-before: always ;
}
.tfoot { display: table-footer-group; }
.page {
 size:landscape;
   
}

 @media print{
  body{  margin-top: -1.8cm;  font-family: Arial, Helvetica, sans-serif; font-size:12px; }
  #ad{ display:none;}
  #leftbar{ display:none;}
  #contentarea{ width:100%;}
  .header, .hide { visibility: hidden }
  .tfoot { display: table-footer-group; }
  @page {size: landscape}
 /* tr.page-break  { display: block; page-break-before: always; }  */
  .breakAfter{ 
page-break-after: always; 
}
.breakBefore{
page-break-before: always ;
}
  
} 

</style>

 <script type="text/javascript">
        function print_page() {
            var ButtonControl = document.getElementById("btnprint");
            ButtonControl.style.visibility = "hidden";
            window.print();
        }
    </script>
<!--</head>-->

<?php

/*function encode($ss,$ntime){
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
}*/

//- First page:
$url = 'upload_pps_month.php';

?>
<!--<script type="text/javascript">
//SYNTAX: ddtabmenu.definemenu("tab_menu_id", integer OR "auto")
ddtabmenu.definemenu("ddtabs1", 0) //initialize Tab Menu #1 with 1st tab selected
ddtabmenu.definemenu("ddtabs2", 1) //initialize Tab Menu #2 with 2nd tab selected
ddtabmenu.definemenu("ddtabs3", 1) //initialize Tab Menu #3 with 2nd tab selected
ddtabmenu.definemenu("ddtabs4", 2) //initialize Tab Menu #4 with 3rd tab selected
ddtabmenu.definemenu("ddtabs5", -1) //initialize Tab Menu #5 with NO tabs selected (-1)
</script>-->
<!--<body onload="window.print()">
--> 
<body><!--<div class="breakAfter">-->
<div class="widget-box">
 <?php
  
  $bulan_text ="";
  //$id = $_GET["id"];
  
            $dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			$factory = $_GET["factory"];
			$work_center = $_GET["work_center"];
			$plan_no = $_GET["plan_no"];
			$shift_ops = $_GET["shift_ops"];
	        $status = $_GET["status"];
			$name_file = $_GET["name_file"];

/*$queryu = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') AS T, DATE_FORMAT(date_upload,'%d-%m-%Y %h:%m:%s') AS K from pps_detail WHERE upload_id = '".$id."' GROUP BY work_center";
$rs = mysqli_query($dbc, $queryu);   //run the query.
*/

 //convert 
			
			$query_convert = "SELECT * FROM work_center_detail WHERE id_work = ?"; $query_convert_args = [$_GET["work_center"]];
			$result_convert = db_query_bind($dbc, $query_convert, $query_convert_args); 
			$row_convert = mysqli_fetch_array($result_convert);
			
			$query_convert2 = "SELECT * FROM ftp_pps WHERE file_name = ?"; $query_convert2_args = [$_GET["name_file"]];
			$result_convert2 = db_query_bind($dbc, $query_convert2, $query_convert2_args); 
			$row_convert2 = mysqli_fetch_array($result_convert2);
			
		   // echo h($row_convert2["upload_id"]);
		  
		  		
			//-------Count all results------------------------//
			
				 $where_sql = ''; $where_args = [];
		 
		 // 1. DateT
                if($dateT == "0000-00-00") {
                     $wheresql_01 = ""; $wheresql_01_args = []; }
                else {
                      $wheresql_01 = " AND (date_plan <= ?)"; $wheresql_01_args = [$dateT]; }
		  //2. DateF 
                if ($dateF  == "0000-00-00" ){
                     $wheresql_02 = ""; $wheresql_02_args = [];}
                else {
                     $wheresql_02 = " AND (date_plan >= ?)"; $wheresql_02_args = [$dateF];}  
					 
		 //3. Factory 
                if ($factory == "NULL"){ 
                    $wheresql_03 = ""; $wheresql_03_args = []; }
                else {
                    $wheresql_03 = " AND id_factory_pps = ?"; $wheresql_03_args = [$factory]; } 
					
          //4. Work Center
                if ($work_center == "NULL" ){
                    $wheresql_04 = ""; $wheresql_04_args = []; }
                else {
					$wheresql_04 = " AND work_center = ?"; $wheresql_04_args = [$work_center]; }
   
	       //5. Planned Order No.
                if ($plan_no == "NULL"){ 
                    $wheresql_05 = ""; $wheresql_05_args = []; }
                else {
                    $wheresql_05 = " AND plan_no = ?"; $wheresql_05_args = [$plan_no]; }  	
					
		 
		  //6. Shift
                if ($shift_ops == "NULL"){ 
                    $wheresql_06 = ""; $wheresql_06_args = []; }
                else {
					// $wheresql_06 = ""; }
					
                    $wheresql_06 = " AND ((shift_pps1 = ?) OR (shift_pps2 = ?)) "; $wheresql_06_args = [$shift_ops, $shift_ops]; }  		 				                                             
		 // 7. Status
                if ($status == "NULL" ){
                    $wheresql_07 = ""; $wheresql_07_args = []; }
                else {
                    $wheresql_07 = " AND status_pps = ?"; $wheresql_07_args = [$status]; }      
					
					
		 // 8. File name
                if ($name_file == "NULL" ){
                    $wheresql_08 = ""; $wheresql_08_args = []; }
                else {
                    $wheresql_08 = " AND upload_id = ?"; $wheresql_08_args = [$row_convert2["upload_id"]]; }        	
	                                        
				
				$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04 .$wheresql_05 .$wheresql_06 .$wheresql_07 .$wheresql_08; $where_args = array_merge($wheresql_01_args, $wheresql_02_args, $wheresql_03_args, $wheresql_04_args, $wheresql_05_args, $wheresql_06_args, $wheresql_07_args, $wheresql_08_args);	
	
	//********** END CONDITION **************




$queryu = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') AS T, DATE_FORMAT(date_upload,'%d-%m-%Y %H:%i:%s') AS K FROM pps_detail WHERE (status_pps != ? OR status_pps != ?) AND status_pps != ?".$where_sql. " GROUP BY work_center"; $queryu_args = [$rst_sta4["status_desc"], $rst_sta16["status_desc"], $rst_sta["status_desc"], ...$where_args];
$rs = db_query_bind($dbc, $queryu, $queryu_args);   //run the query.

 while ($db_rs = mysqli_fetch_array($rs))
   {
	
	if($db_rs["month_plan"] == "01")
	{
      $bulan_text = "January";
	}elseif($db_rs["month_plan"] == "02")
	{
	  $bulan_text = "February";
	  
    }elseif($db_rs["month_plan"] == "03")
	{
	  $bulan_text = "March";
	}elseif($db_rs["month_plan"] == "04")
	{
	  $bulan_text = "April";
	}elseif($db_rs["month_plan"] == "05")
	{
	  $bulan_text = "May";
	}elseif($db_rs["month_plan"] == "06")
	{
	  $bulan_text = "June";
	}elseif($db_rs["month_plan"] == "07")
	{
	  $bulan_text = "July";
	}elseif($db_rs["month_plan"] == "08")
	{
	  $bulan_text = "August";
	}elseif($db_rs["month_plan"] == "09")
	{
	  $bulan_text = "September";
	}elseif($db_rs["month_plan"] == "10")
	{
	  $bulan_text = "October";
	}elseif($db_rs["month_plan"] == "11")
	{
	  $bulan_text = "November";
	}elseif($db_rs["month_plan"] == "12")
	{
	  $bulan_text = "December";
	}else{
		$bulan_text = "Others";
	}
 
 $extension = explode ('.', $data_setup["logo_name"]);
 $filename = $data_setup["logo_comp"].'.'.$extension[1];
	
	 //--------------get filename from table ftp_pps
 
 $query_ftp_pps = "SELECT * FROM ftp_pps WHERE upload_id = ?"; $query_ftp_pps_args = [$db_rs["upload_id"]];
 $result_ftp_pps = db_query_bind($dbc, $query_ftp_pps, $query_ftp_pps_args);
 $data_ftp_pps = mysqli_fetch_array($result_ftp_pps); 	
	   
 ?>
<div class="page">  
<br>

<table width="1050" border="0" cellpadding="2" cellspacing="2">
  <tr>
    <td width="30%" height="93"> <img src="../set_upload/<?php echo h($filename);  ?>" width="300" height="100"/></td>
    <td width="40%"><h3>Production Planning Sheet</h3></td>
    <td width="30%"><table width="280" border="1" align="right" cellpadding="0" cellspacing="0">
      <tr>
        <td width="89"><div align="left">Doc No. </div></td>
        <td width="185">&nbsp;</td>
      </tr>
      <tr>
        <td><div align="left">Rev. No.</div></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><div align="left">Effective Date </div></td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table class="table table-bordered" >
      <tr>
        <th><div align="left"><span class="style3">Production Plant</span></div></th>
        <th><span class="style3">:</span></th>
        <th><span class="style3">Nilai Plant</span></th>
        </tr>
      <tr>
        <th><div align="left"><span class="style3">Factory</span></div></th>
        <th><span class="style3">:</span></th>
        <th><span class="style3">&nbsp;Nilai</span></th>
        </tr>
      <tr>
        <th><div align="left"><span class="style3">Production Line</span></div></th>
        <th><span class="style3">:</span></th>
        <th><span class="style4">
          <?php echo h($db_rs["work_center"]); ?>
        </span></th>
        </tr>
    </table></td>
    <td>&nbsp;</td>
    <td><table class="table table-bordered" >
      <tr>
        <th width="98" height="28"><div align="left"><span class="style3">Month/Year</span></div></th>
        <th width="23" height="28"><span class="style3">:</span></th>
        <th width="170" height="28"><span class="style3">
          <?php echo $bulan_text; ?>
        </span></th>
      </tr>
      <tr>
        <th width="98" height="28"><div align="left"><span class="style3"> Date</span></div></th>
        <th height="28"><span class="style3">:</span></th>
        <th height="28"><span class="style3"><?php echo h($db_rs["K"]); ?>
       
        </span></th>
      </tr>
      <tr>
        <th height="28"><div align="left"><span class="style3">Filename</span></div></th>
        <th height="28"><span class="style3">:</span></th>
        <th height="28"><span class="style3"><?php echo h($data_ftp_pps["file_name"]); ?></span></th>
      </tr>
    </table></td>
    </tr>
  <tr>
    <td colspan="3">
  <!--  <div class="page"> -->
   </td>
    </tr>
</table> </div><table border="1" width="1050" style="page-break-inside:inherit">
      <thead>
        <tr>
          <th>No.</th>
          <th>Model</th>
          <th>Part No. / Part Name</th>
          <th>Planned Order No.</th>
          <th>Planned Start Date</th>
          <th>Shift</th>
          <th>Seq #</th>
          <th>Planned Order Quantity</th>
          <th>UOM</th>
          <th>2D Barcode</th>
          <th>Status</th>
          <th colspan="3">Production Timing</th>
          </tr>
      </thead>
      <tbody>
        <?php
		
	  $query_by_group = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') AS T, DATE_FORMAT(date_upload,'%d-%m-%Y %H:%i:%s') AS K from pps_detail WHERE work_center = ? ".$where_sql; $query_by_group_args = [$db_rs["work_center"], ...$where_args];
      $result_by_group = db_query_bind($dbc, $query_by_group, $query_by_group_args);   //run the query.
		
		
      $counter = 1;
      $no = 1;
 	  $i = 1; 
	
	/*echo '<tbody><tr >';  */ 
  
   while ($rowA = mysqli_fetch_array($result_by_group))
   {
		//$user_no = $row[0]; 
    $no = sprintf('%03d', $no);
	if($rowA["shift_pps1"] != "")
	{
		$staA = "D/S";
		
	}elseif($rowA["shift_pps2"] != "")
	 {
		$staA = "N/S";
	 }else{
		 
		$staA = " ";
	 }
	 
	 
	   
	     //---------get material header---------
	    $query_mat_h = "SELECT * FROM mat_master_header AS HD, mat_master_detail AS AD WHERE HD.material_no = AD.material AND HD.material_no = ?"; $query_mat_h_args = [$rowA['material_no']];
		$result_mat_h = db_query_bind($dbc, $query_mat_h, $query_mat_h_args);
		$data_mat_h = mysqli_fetch_array($result_mat_h);	
		
		  //---------get material detail---------
	    $query_mat_d = "SELECT * FROM mat_master_detail WHERE (material = ? OR bill_component = ?)"; $query_mat_d_args = [$rowA['material_no'], $rowA['material_no']];
		$result_mat_d = db_query_bind($dbc, $query_mat_d, $query_mat_d_args);
		$data_mat_d = mysqli_fetch_array($result_mat_d);	
		
		
      if ($i && $i % 4 == 0)  
		echo '<tr style="page-break-after:always">';  
	    else if ($i)  
		echo '<tr>';  
	    ++$i; 
		?>  
      <!--  <tr>-->
          <td width="60"><?php  echo $no; ?></td>
          <td width="60" height="28"><?php echo h($rowA["model_code"]); ?></td>
          <td width="384"><b><h5><?php  echo h($rowA["material_no"]); ?></h5></b><?php  echo h($data_mat_h["material_desc"]); ?></td>
          <td width="151"><div align="center"><?php echo h($rowA["plan_no"]); ?></div></td>
          <td width="93"><?php  echo h($rowA["T"]); ?></td>
          <td width="46"><div align="center"><font color="#FF0000"><?php echo $staA; ?></font></div></td>
          <td width="46"><div align="center"><?php echo h($rowA["seq_pps"]); ?></div></td>
          <td width="69"><div align="center"><?php  echo intval($rowA["qty_plan"]); ?></div></td>
          <td width="55"><div align="center"><?php  echo h($data_mat_h["BUn"]); ?></div></td>
          <td width="90"> <div align="left">
       <?php

// set the barcode content and type

$bar_textA = ($rowA["material_no"].'|'.$rowA["plan_no"].'|'.$rowA["date_plan"].'|'.$rowA["work_center"].'|'.$staA.'|'.$rowA["qty_plan"].'|'.$data_mat_h["BUn"]);

$barcodeobj = new TCPDF2DBarcode($bar_textA, 'PDF417');
echo $barcodeobj->getBarcodeSVGcode(1.0, 1.0, 'black');

?>
                  </div></td>
          <td width="90">&nbsp;</td>
          <td width="44">&nbsp;</td>
          <td width="44">&nbsp;</td>
          <td width="44">&nbsp;</td>
         </tr>
        <?php 
		   
	   
		     $counter++; // menambah counter 
			 $no ++;   
			   
			   }	
			   		   
			   ?>
      </tbody>
    </table>
<div class="breakBefore">

<?php    } // end while loop main   ?>  

 </div>
<input type="hidden" name="upload_id" value="<?php echo $upload_id; ?>">
<!--<button onclick="window.print()">Print this page</button>
--><input type="button" id="btnprint" value="Print this Page" onClick="print_page()" class="btn btn-success"/>

<!--</body>
</html>-->
