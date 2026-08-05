<?php
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

date_default_timezone_set('Asia/Bangkok');

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "material_request_urgent.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
    $result2 = mysql_query($query2) or die (mysql_error());
    $res = mysql_fetch_array($result2);
	
$today = getdate();
$hours = $today['hours']; 
$minutes = $today['minutes'];
$seconds = $today['seconds'];
$month = $today['mon']; 
$mday = $today['mday']; 
$year = $today['year']; 	

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysql_query($query_setup);   //run the query.
$num_setup = mysql_num_rows($rs_setup);   //how many material are there?
$data_setup = mysql_fetch_array($rs_setup);
//----------------------------------------------------		
	?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $data_setup["title_desc"]; ?></title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="shortcut icon" href="../img/favicon.ico">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="../css/bootstrap.min.css" />
<link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="../css/uniform.css" />
<link rel="stylesheet" href="../css/select2.css" />
<link rel="stylesheet" href="../css/matrix-style.css" />
<link rel="stylesheet" href="../css/matrix-media.css" />
<link href="../font-awesome/css/font-awesome.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/jquery.gritter.css" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

<!----------------->
<link rel="stylesheet" href="../scripts/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="../javascript/jquery-latest.js"></script> 
<script type="text/javascript" src="../javascript/thickbox.js"></script>	
<style type="text/css">
<!--
.style3 {color: #000000}
@media print{
  body{
	background-color: #EEEEEE;
	background-image: none;
	color: #000000
}
  #ad{ display:none;}
  #leftbar{ display:none;}
  #widget-box{ width:80%;}
}
body,td,th {
	font-family: "Open Sans", sans-serif;
}
-->
</style>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>
</head>
<body>

  <?php

//$id_scan = $_GET["id_scan"];

/*$queryu = "SELECT * from material_request where id_scan = '$id_scan'";
$resultu = mysql_query($queryu);   //run the query.
$row = mysql_fetch_row($resultu);   //how many records are there?


*/
//--------------------function escape data from form ------------------------
function escape_data ($data) {
global $dbc;   // need the connection.
if (ini_get('magic_quotes_gpc')) 
{
    $data = stripslashes($data);
	}
	return mysql_real_escape_string($data,$dbc);
	}   // end function.
$message = NULL; // create an empty new variable.
//------------------------------end function --------------------------------

 ?>
<div class="widget-box">
<table class="table table-condensed">
<tr>
      <td width="1%">&nbsp;</td>  
      <td width="7%"><a href="javascript:parent.tb_remove(); parent.location.reload(1)" ><img src="../img/back3.jpg" width="48" height="48" /></a></td>  
      <td width="7%"></td>
      <td width="85%"></td>
   
  </tr>
</table>
 <h5>MRIN LIST  <?php /*if($data_2["status_urgent"] == "Y"){ echo "[Urgent]"; } */    ?></h5>
          
          <?php
		  
    $query = "SELECT * FROM material_request AS MR, scan_detail AS SD WHERE MR.id_scan = SD.id_scan AND MR.status_request = 'N' and SD.status_urgent = 'Y' GROUP BY MR.id_scan ORDER BY MR.id_req ASC";
	$rs = mysql_query($query);   //run the query. 
		  
		  
		  ?>
               <table class="table table-bordered">
               <thead>
               <tr>
                 <th width="63">Item</th>
                 <th width="144">Material</th>
                 <th width="144">Production Order Number</th>
                 <th width="100">Required Quantity</th>
                 <th width="80">UoM </th>
                 <th width="67">Work Center</th>
               </tr>
         
             <?php
      $counter = 1;
   $no = 1;
   
   while ($row2 = mysql_fetch_array($rs))
   {
		
 $no = sprintf('%03d', $no);
		
	
   $query_scan = "SELECT * FROM scan_detail WHERE id_scan = '".$row2[6]."' GROUP BY id_scan";
   $result_scan = mysql_query($query_scan);
   $row_scan = mysql_fetch_array($result_scan);
	
	
		  
		  ?>     
          
          <tr bgcolor="#FFB591">
          <td width="62" height="28"><?php echo $no; ?></td>
          <td width="144">&nbsp;<?php echo $row_scan["material_no"]; ?>   </td>
          <td width="391">&nbsp;<?php echo $row_scan["prod_order"]; ?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          </tr></thead><tbody>
               <?php
		
	$query_again = "SELECT * FROM material_request WHERE status_request = 'N' and id_scan = '".$row2[6]."' ORDER BY id_req ASC";
    $rs_again = mysql_query($query_again);   //run the query.
	 while ($row = mysql_fetch_array($rs_again))
   {
		 
   $query1_p = "SELECT * FROM scan_detail WHERE id_scan = '".$row2[6]."' GROUP BY id_scan";
   $result1_p = mysql_query($query1_p);
   $row1_p = mysql_fetch_array($result1_p);
	
	
		 
  $query4_p = "SELECT * from mat_master_header as LD, mat_master_detail as SD WHERE SD.id_hdr = LD.id_hdr and SD.id_dtl = '".$row[5]."'";
  $result4_p = mysql_query($query4_p);
  $row4_p = mysql_fetch_array($result4_p); 
		  	 
		 
		 
		 
		 
		 ?>

              <tr>
              <td width="63" height="28"></td>
              <td width="100" >&nbsp;<?php  echo $row4_p["bill_component"]; ?></td>
			  <td width="187"><?php  echo $row4_p["material_desc_c"]; ?></td>
              <td width="100"><div align="right"><?php echo $row["bom_qty"]; ?>&nbsp;</div></td>
              <td width="80"><div align="center"><?php echo $row["bom_oum"]; ?></div></td>
              <td width="67">&nbsp;<font color="#FF0000"><?php echo $row1_p["work_center"]; ?></font></td>
          </tr>
            <input name="user_no" type="hidden" value="<?php echo $user_no; ?>">
         
         <?php 
		 
		
		  
		  $counter++; // menambah counter 
		 }
			   
			 $no ++;   
			   
			   
			   }
			   
			   
			   ?></tbody>
             </table>
             
 <!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part--> 


<script src="js/jquery.min.js"></script> 
<script src="js/jquery.ui.custom.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/jquery.uniform.js"></script> 
<script src="js/select2.min.js"></script> 
<script src="js/jquery.dataTables.min.js"></script> 
<script src="js/matrix.js"></script> 
<script src="js/matrix.tables.js"></script>

</div>

</body>
</html>