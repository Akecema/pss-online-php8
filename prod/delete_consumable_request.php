<?php
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

set_time_limit(0);
// include 2D barcode class (search for installation path)
require_once('/tcpdf_barcodes_2d.php');


// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$warnaGenap = "#F4FBCA";   // warna blue grey
$warnaGanjil = "#f8f8f8";  // warna putih

$query_u = "SELECT * FROM user_detail WHERE username = '$username'";
$result_u = mysqli_query($dbc, $query_u);   //run the query.
$data_u = mysqli_fetch_array($result_u);   //how many records are there? 

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
<title><?php echo $data_setup["title_desc"]; ?></title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../css/style.css" type="text/css" media="all" />
<link href="../css/ddtabmenu.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../javascript/multiValue1.js"></script>
<script src="../javascript/checking_cancel_reason.js" type="text/javascript"></script>

<style type="text/css">
<!--
.style3 {color: #000000}
@media print{
  body{ background-color:#FFFFFF; background-image:none; color:#000000 }
  #ad{ display:none;}
  #leftbar{ display:none;}
  #contentarea{ width:100%;}
}
.style4 {
	font-size: 14px;
	font-weight: bold;
}
-->
</style>
</head>

<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>

<?php

function encode($ss,$ntime){
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
}


//- First page:
$url = 'material_consumable_request.php';
$url2 = 'display_consumable_request.php';
$url3 = 'posting_consumable_request.php';



?>

<body>
<p>
  <!-- Header -->
  <!-- End Header -->
  <?php
  $lastID  = $_GET["lastID"];
  $id_req_con = $_GET["id_req_con"];
   $date1 = $_GET["date1"];
   $t_time = $_GET["t_time"]; 
   $factory = $_GET["factory"];

   //echo $id_req_con;

   //-------------------------update delete consumable request from table------------------------------
   //--------------------------------------------------------------------------------------------------
   
    $query_delete = "DELETE FROM consumable_request WHERE id_req_con = '$id_req_con'";
	$result_delete = mysqli_query($dbc, $query_delete);
	

     if($result_delete)
	 { 
	 
	//  echo '<script type="text/javascript"> alert ("Please go back and fill in all required lines"); window.history.back()<//script>';
			 
			 echo "<script>";
		    //echo "alert('MRIN No. $temp_mrin is successfully close.');";
			  echo "window.location='material_consumable_request2.php?lastID=$lastID&&date1=$date1&&t_time=$t_time&&factory=$factory'";
	 		    // echo "parent.tb_remove(); parent.location.reload(1)";
		     echo "</script>"; 
		     exit(); //quit the script
		 
    }
?>
</p>


         </body>
</html>
