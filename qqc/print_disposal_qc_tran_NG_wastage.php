<?php

/**
 * qqc/print_disposal_qc_tran_NG_wastage.php
 * Part of: QQC module (Quality)
 * Filename suggests: print disposal qc tran NG wastage
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, reject_detail_disposal, type_wastage_detail, reason_wastage, mat_master_header.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, tcpdf_barcodes_2d.php, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
error_reporting(E_ALL &~ E_NOTICE &~ E_DEPRECATED);
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

$url = "disposal_backflush_tran_NG.php";


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
 $extension = explode ('.', $data_setup["logo_name"]);
 $filename = $data_setup["logo_comp"].'.'.$extension[1];
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
body { background-color:#FFFFFF }
@media print{
  body{
	margin-top: -2.0cm;
	margin-left:10px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	background-color: #FFFFFF;
	
}
  a[href]:after {
    content: none !important;
  }
  #ad{ display:none;}
  #leftbar{ display:none;}
  #contentarea{ width:100%;}
  .header, .hide { visibility: hidden }
  .tfoot { display: table-footer-group; }
    @page {size: landscape;
	margin-left:10px;
	margin-right:10px;
	margin-bottom:0px;
	}
	  
  .bottom-left2{ visibility: hidden }
  .footer_bawah{ margin-bottom:5px; }
  .page {
		page-break-after:always;
		position: relative;
		counter-increment: page;
	    /*counter-reset: page 1;*/ }
    .footer { position: fixed; bottom: 0px; }
    /*.pagenum:before { content: counter(page); }*/
}
.style4 {
	font-size: 14px;
	font-weight: bold;
}
.widget-box table tr td table tr td {
	text-align: center;
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
<div class="page">
<?php

 $doc_disposal = $_GET["doc_disposal"];
  
$queryu = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') as R, DATE_FORMAT(date_posting,'%d-%m-%Y') as R2 FROM reject_detail_disposal WHERE doc_disposal_no = '".db_esc($dbc, $doc_disposal)."'";
$rs = mysqli_query($dbc, $queryu);   //run the query.

//detail info disposal 

$query_disposal = "SELECT *, DATE_FORMAT(date_disposal,'%d-%m-%Y %H:%i:%s') as W, DATE_FORMAT(date_posting,'%d-%m-%Y') as W2, DATE_FORMAT(date_approve,'%d-%m-%Y') as W3, DATE_FORMAT(date_approve2,'%d-%m-%Y') as W4 FROM reject_detail_disposal WHERE doc_disposal_no = '".db_esc($dbc, $doc_disposal)."'";
$result_disposal = mysqli_query($dbc, $query_disposal);   //run the query.
$row_disposal = mysqli_fetch_array($result_disposal);


//FUNCTION RETAIN TEXTBOX VALUE
function prepopulate($name) 
{ 
	if(isset($_POST[$name])) 
	{ 
		return $_POST[$name]; 
	} 
	else 
	{ 
		return ""; 
	} 
} 

?>
<br>
<table width="1000">
<tr>
      <td width="1%">&nbsp;</td>
      <td width="7%"><img src="../img/print2.jpg" width="48" height="48" onClick="window.print()" title="Print"/></td>
        <td width="7%">&nbsp;</td>
      <td width="85%"> <div class="small-nav"></div></td>
      
  </tr>
</table>
<br>
<div style="page-break-inside:auto">
<br>
<table width="98%" border="0" cellpadding="2">
  <tr>
   <td width="35%" valign="top"><img src="../set_upload/<?php echo $filename;  ?>"width="267" height="27" hspace="2" vspace="2"/>
      <p>PT 2475-2476, Kawasan Perindustrian Nilai, P.O. Box 45,<br>
      71807 Nilai, Negeri Sembilan Darul Khusus, Malaysia. <br>
      Tel :+606-799 5599 Fax :+606-799 5597 / 8</p></td>
   <td width="7%">&nbsp;</td>
    <td>
      <table width="98%" class="table table-bordered">
        <tr>
          <td width="35%">DOC. NO.</td>
          <td>PRD/QR/P014-REV0</td>
        </tr>
        <tr>
          <td>REV. NO.</td>
          <td>0</td>
        </tr>
      </table>
      <table width="98%" class="table table-bordered">
        <tr>
          <td width="35%">Disposal No.</td>
          <td><?php echo $row_disposal["doc_disposal_no"];  ?></td>
          </tr>
        <tr>
          <td>Document Date</td>
          <td><?php echo $row_disposal["W"];  ?></td>
          </tr>
        <!--<tr>
          <td>Status</td>
          <td><?php //echo $row_disposal["status_disposal"];  ?></td>
          </tr>-->
      </table>
      </td>
  </tr>
   <tr>
    <td width="62%" colspan="2">&nbsp;</td>
    <td width="25%">
      </td>
  </tr>
</table>
      <!-- Content -->
   
          
              <!-- End Box Head -->
       
             <!-- <div class="footer">Page: <span class="pagenum"></span></div>	-->
            <div class="form">
             <p>We confirm that the following items are no longer usable and to be disposed off.</p>
             <table width="1100" class="table table-bordered">
             <thead>
               <tr>
                 <th width="100" height="28" bgcolor="#E9F58D"><span class="style3">No.</span></th>
                 <th width="122" height="28" bgcolor="#E9F58D"><span class="style3">Stock Code</span></th>
                 <th width="100" bgcolor="#E9F58D"><span class="style3">Part No.</span></th>
                 <th width="45" bgcolor="#E9F58D"><span class="style3">Type of Reject/Wastage</span></th>
                 <th width="146" bgcolor="#E9F58D"><span class="style3">Date</span></th>
                 <th width="42" bgcolor="#E9F58D"><span class="style3">Quantity</span></th>
                 <th width="90" bgcolor="#E9F58D"><span class="style3">UOM</span></th>
                 <th width="90" height="28" bgcolor="#E9F58D"><span class="style3">Location</span></th>
                 <th width="90" height="28" bgcolor="#E9F58D"><span class="style3">Cost Center</span></th>
                 <th width="65" height="28" bgcolor="#E9F58D"><span class="style3">Reason</span></th>
                 <th height="28" bgcolor="#E9F58D"><span class="style3">Remarks</span></th>
               </tr>
             </thead>  
             <?php
      $counter = 1;
      $no = 1;
      $qty_asal = 0.000;
	  $loc_asal = "";
	  $k= 1;
	   
   while ($row = mysqli_fetch_array($rs))
   {
		
		$query_type = "SELECT * FROM type_wastage_detail WHERE id_wastage = '".db_esc($dbc, $row['type_wastage'])."' ORDER BY id_wastage ASC";
		$result_type = mysqli_query($dbc, $query_type);
		$row_type = mysqli_fetch_array($result_type); 
		
		$query_reason = "SELECT * FROM reason_wastage WHERE id_reason_wastage = '".db_esc($dbc, $row['reason_wastage'])."' ORDER BY id_reason_wastage ASC";
		$result_reason = mysqli_query($dbc, $query_reason);
		$row_reason = mysqli_fetch_array($result_reason);
			
		$query_mat = "SELECT * FROM mat_master_header WHERE material_no = '".db_esc($dbc, $row['material_no'])."'";
		$result_mat = mysqli_query($dbc, $query_mat);
		$data_mat = mysqli_fetch_array($result_mat);	
		

 
	   
		 ?>
<tbody>

<tr>
                <td width="30" height="28"><div align="center"><?php  echo $no; ?></div></td>
                <td width="80"><?php  echo $row["model_code"]; ?></td>
                <td width="100"><?php echo $row["material_no"]; ?>&nbsp;</td>
                <td width="100"><?php echo $row_type["wastage_desc"]; ?></td>
                <td width="100"><?php echo $row["R2"]; ?></td>
                <td width="100"><div align="center"><?php echo $row["qty_wastage"]; ?></div></td>
                <td width="60"><div align="center"><?php echo $row["UOM_unit"]; ?></div></td>
                <td width="60"><div align="center"><?php echo $row["ploc_prod_reject"]; ?></div></td>   
                <td width="60"><div align="center"><?php echo $row["cost_center"]; ?></div></td>             
                <td width="150"><?php echo $row_reason["reason_wastage_desc"]; ?></td>
                <td width="250"><?php echo $row["remarks"]; ?><br>&nbsp;
               <input name="id_disposal" type="hidden" value="<?php echo $row["id_disposal"]; ?>">
          </td>
      </tr>
    </tbody>        
     
         <?php 
		   
		
		  
		  $counter++; // menambah counter 
		  $no ++;  
		  $k ++; 
			   
			   
		}
			   
			   ?>   </table> 
  <div class="footer_bawah"><?php include "footer.php";   ?></div>
<!--Footer-part-->        
    <!--<div class="bottom-left2">
  <input  name="btnback" type="button" id="btnCancel" class="btn btn-warning" value="BACK" onclick="javascript:parent.tb_remove();" />
</div>
-->
   </div></div> </div>
<!--end-Footer-part--> 
</body>
</html>