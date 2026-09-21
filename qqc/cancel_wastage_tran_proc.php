<?php

/**
 * qqc/cancel_wastage_tran_proc.php
 * Part of: QQC module (Quality)
 * Filename suggests: cancel wastage tran proc
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, reject_detail_disposal, wastage_transaction, type_wastage_detail, reason_wastage.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, tcpdf_barcodes_2d.php, footer.php.
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
require_role($dbc, 10);
include_once ("../classes/paginator.class2.php");
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

$url = "wastage_qc_tran_NG.php";


    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
    $result2 = mysqli_query($dbc, $query2) or die(db_fail($dbc));
    $res = mysqli_fetch_array($result2);
	
$today = getdate();
$hours = $today['hours']; 
$minutes = $today['minutes'];
$seconds = $today['seconds'];
$month = $today['mon']; 
$mday = $today['mday']; 
$year = $today['year']; 	

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

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7' ";
$sta_res7 = mysqli_query($dbc, $sta7);
$rst_sta7 = mysqli_fetch_array($sta_res7);	

//CR status (Delete)
$sta16 = "SELECT * from request_status WHERE status_id = '16'";
$sta_res16 = mysqli_query($dbc, $sta16);
$rst_sta16 = mysqli_fetch_array($sta_res16);

	?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $data_setup["title_desc"]; ?></title>
<meta charset="UTF-8" />
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
<link href="../calendar/calendar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../calendar/calendar.js"></script>
<style type="text/css">
<!--
.style3 {color: #000000}
@media print{
  body{  margin-top: -2.2cm;  font-family: Arial, Helvetica, sans-serif; font-size:12px; }
  #ad{ display:none;}
  #leftbar{ display:none;}
  #contentarea{ width:100%;}
  .header, .hide { visibility: hidden }
  .tfoot { display: table-footer-group; }
    @page {size: landscape}
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
</head>
<body>
<div class="widget-box">
  <!-- Header -->
  <!-- End Header -->
  <?php

 $uid = $_GET["uid"];
 
 
  if(isset($_POST["cancel_btn"])) 
  
   { // handle the form.

// create a function for escaping the data.
function escape_data ($data) {
global $dbc;   // need the connection.
if(ini_get('magic_quotes_gpc')) {
    $data = stripslashes($data);
	}
	return mysqli_real_escape_string($dbc, $data);
	}   // end function.
$message = NULL; // create an empty new variable.

   
   $uid = $_POST["uid"];
    // echo $uid;
   
    $query_upd_detail = "UPDATE reject_detail_disposal SET status_disposal = '".db_esc($dbc, $rst_sta16["status_desc"])."' WHERE id_disposal = '".db_esc($dbc, $uid)."'";
	$result_upd_detail = mysqli_query($dbc, $query_upd_detail) or die(db_fail($dbc));
	
	//-----------select statement--------------
	$query_state = "SELECT * FROM reject_detail_disposal WHERE id_disposal = '".db_esc($dbc, $uid)."' ORDER BY id_disposal ASC";
    $result_state = mysqli_query($dbc, $query_state);
    $row_state = mysqli_fetch_array($result_state);
	

	//---------------
	  $query_upd_detail2 = "UPDATE wastage_transaction SET status_disposal = '".db_esc($dbc, $rst_sta16["status_desc"])."' WHERE id_wastage_tran = '".db_esc($dbc, $row_state["uid"])."'";
	  $result_upd_detail2 = mysqli_query($dbc, $query_upd_detail2) or die(db_fail($dbc));
		
	  if(($result_upd_detail > 0) && ($result_upd_detail2 > 0))
	  {	
		   echo "<script>";
		   echo "alert('Cancellation has been updated');";
		   echo "parent.tb_remove(); parent.location.reload(1)";
		   echo "</script>"; 
		   exit(); //quit the script
		 
	  }

	


   }// end submit
?>
<table class="table">
<tr>
  <td>&nbsp;</td>
  <td colspan="2"><div align="center"><span class="style4">Wastage Remove Item</span></div></td>
  </tr>
</table>
<p>&nbsp;</p> 
   <form action="cancel_wastage_tran_proc.php?uid=<?php echo $uid; ?>" method="post" name="frmSearch" id="frmSearch">     
  <table class="table table-bordered data-table">
    <thead>
      <tr>
        <th>No.</th>
        <th width="104">Model</th>
        <th>Part No.</th>
        <th>Storage Location</th>
        <th>Type of Reject</th>
        <th>Date</th>
        <th>Quantity</th>
        <th>UOM</th>
        <th>Reason</th>
        </tr>
    </thead>
    <tbody>
      <?php
   
   $counter = 1;
   $no = 1;
   $sta_out = "";
   
   $query_display = "SELECT *, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2 FROM reject_detail_disposal AS MR WHERE MR.id_disposal = '".db_esc($dbc, $uid)."'";
$result_display = mysqli_query($dbc, $query_display);   //run the query.
   
   while ($row2 = mysqli_fetch_array($result_display))
   {
		
    $query_type = "SELECT * FROM type_wastage_detail WHERE id_wastage = '".db_esc($dbc, $row2['type_wastage'])."' ORDER BY id_wastage ASC";
    $result_type = mysqli_query($dbc, $query_type);
    $row_type = mysqli_fetch_array($result_type); 
	
	$query_reason = "SELECT * FROM reason_wastage WHERE id_reason_wastage = '".db_esc($dbc, $row2['reason_wastage'])."' ORDER BY id_reason_wastage ASC";
    $result_reason = mysqli_query($dbc, $query_reason);
    $row_reason = mysqli_fetch_array($result_reason);
	 
      ?>
      <tr class="gradeX">
        <td width="49"><?php echo $no; ?></td>
        <td><?php echo $row2[9]; ?></td>
        <td width="103"><font color="#0000CC"><?php echo $row2[6]; ?></font></td>
        <td width="124"><?php echo $row2["ploc_prod_reject"]; ?></td>
        <td width="149"><?php echo $row_type['wastage_desc']; ?></td>
        <td width="59"><?php echo $row2["R2"]; ?></td>
        <td width="81"><?php echo $row2["qty_wastage"]; ?></td>
        <td width="64"><?php echo $row2["UOM_unit"]; ?></td>
        <td width="269"><?php echo $row_reason['reason_wastage_desc']; ?></td>
        <input name="uid" type="hidden" value="<?php echo $row2["id_disposal"]; ?> ">
      </tr> 
      
      <?php 
		  
		  $no ++;
		  $counter++; // menambah counter
		  } 
		  ?>
        
      </tr>  
     
    </tbody>
  </table> 
  <table>
    <tr><td>&nbsp;<input name="cancel_btn" type="submit"  class="btn btn-danger" id="button" value="REMOVE ITEM" onClick="return confirm('Are you sure want to perform this activity?');"/></td>
</tr>
       </table>
  </form>
  <p>&nbsp;</p>
  <p><br> 
    
    <!--Footer-part-->
  </p>
  <?php include "footer.php";   ?>
<!--end-Footer-part--> 
</div>
</body>
</html>