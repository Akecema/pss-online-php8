<?php

/**
 * prod/view_disposal_approve_tran_NG.php
 * Part of: Production module
 * Filename suggests: view disposal approve tran NG
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, reject_detail_disposal, type_reject_detail, reason_ng_reject, pps_detail, mat_master_header.
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
require_role($dbc, 2);
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


    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
    $result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
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
<?php

 $doc_disposal = $_GET["doc_disposal"];
  
$queryu = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') as R, DATE_FORMAT(date_posting,'%d-%m-%Y') as R2 FROM reject_detail_disposal WHERE doc_disposal_no = '".$doc_disposal."'";
$rs = mysqli_query($dbc, $queryu);   //run the query.

//detail info disposal 

$query_disposal = "SELECT *, DATE_FORMAT(date_disposal,'%d-%m-%Y %H:%i:%s') as W, DATE_FORMAT(date_posting,'%d-%m-%Y') as W2, DATE_FORMAT(date_approve,'%d-%m-%Y') as W3, DATE_FORMAT(date_approve2,'%d-%m-%Y') as W4 FROM reject_detail_disposal WHERE doc_disposal_no = '".$doc_disposal."'";
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
        <tr>
          <td>Status</td>
          <td><?php echo $row_disposal["status_disposal"];  ?></td>
          </tr>
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
                 <th width="122" height="28" bgcolor="#E9F58D"><span class="style3">Model</span></th>
                 <th width="100" bgcolor="#E9F58D"><span class="style3">Part No.</span></th>
                 <th width="45" bgcolor="#E9F58D"><span class="style3">Type of Reject/Wastage</span></th>
                 <th width="146" bgcolor="#E9F58D"><span class="style3">Date</span></th>
                 <th width="42" bgcolor="#E9F58D"><span class="style3">Quantity</span></th>
                 <th width="90" bgcolor="#E9F58D"><span class="style3">UOM</span></th>
                 <th width="90" height="28" bgcolor="#E9F58D"><span class="style3">Location</span></th>
                  <th width="90" height="28" bgcolor="#E9F58D"><span class="style3">Cost Center</span></th>
                 <th width="65" height="28" bgcolor="#E9F58D"><span class="style3">Reason</span></th>
                 <th height="28" bgcolor="#E9F58D"><span class="style3">Reason Remarks</span></th>
                 <th bgcolor="#E9F58D"><span class="style3">Production Remarks </span></th>
                 <th bgcolor="#E9F58D"><span class="style3">Approver Remarks</span></th>
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
		
		$query_type = "SELECT * FROM type_reject_detail WHERE id_type = '".$row['type_reject']."' ORDER BY id_type ASC";
		$result_type = mysqli_query($dbc, $query_type);
		$row_type = mysqli_fetch_array($result_type); 
		
		$query_reason = "SELECT * FROM reason_ng_reject WHERE id_reject = '".$row['reason_reject']."' ORDER BY id_reject ASC";
		$result_reason = mysqli_query($dbc, $query_reason);
		$row_reason = mysqli_fetch_array($result_reason);
		
		$query_model = "SELECT * FROM pps_detail WHERE plan_no = '".$row['plan_no']."'";
		$result_model = mysqli_query($dbc, $query_model);
		$data_model = mysqli_fetch_array($result_model);	
		
		$query_mat = "SELECT * FROM mat_master_header WHERE material_no = '".$row['material_no']."'";
		$result_mat = mysqli_query($dbc, $query_mat);
		$data_mat = mysqli_fetch_array($result_mat);	
		
		$query_disposal2 = "SELECT * FROM user_detail WHERE username = '".$row["user_disposal"]."'";
        $result_disposal2 = mysqli_query($dbc, $query_disposal2) or die (mysqli_error($dbc));
        $res_disposal2 = mysqli_fetch_array($result_disposal2);
		
		$query_approve = "SELECT * FROM user_detail WHERE username = '".$row["approve_by"]."'";
        $result_approve = mysqli_query($dbc, $query_approve) or die (mysqli_error($dbc));
        $res_approve = mysqli_fetch_array($result_approve);
		
		$query_approve2 = "SELECT * FROM user_detail WHERE username = '".$row["approve_by2"]."'";
        $result_approve2 = mysqli_query($dbc, $query_approve2) or die (mysqli_error($dbc));
        $res_approve2 = mysqli_fetch_array($result_approve2);
		

    //-------get quantity reject ---------
	if($row["qty_NG"] != "0.000")
	{
	 
	 $qty_asal = $row["qty_NG"];
	 
	 }elseif($row["qty_qc_NG"] != "0.000")
	 {
		 
     $qty_asal = $row["qty_qc_NG"]; 
		 
	 }else{
		 
	  $qty_asal = "";	 
	 }
	
	//-------get location ---------
	if($row["ploc_prod_reject"] != "")
	{
	 
	 $loc_asal = $row["ploc_prod_reject"];
	 
	 }elseif($row["ploc_qc_reject"] != "")
	 {
		 
     $loc_asal = $row["ploc_qc_reject"]; 
		 
	 }else{
		 
	  $loc_asal = "";	 
	 }
	   
		 ?>
<tbody>

<tr>
                <td width="30" height="28"><div align="center"><?php  echo $no; ?></div></td>
                <td width="80"><?php  echo $data_model["model_code"]; ?></td>
                <td width="100"><?php echo $row["material_no"]; ?>&nbsp;</td>
                <td width="100"><?php echo $row_type["type_desc"]; ?></td>
                <td width="100"><?php echo $row["R2"]; ?></td>
                <td width="100"><div align="center"><?php echo $qty_asal; ?></div></td>
                <td width="60"><div align="center"><?php echo $data_mat["BUn"]; ?></div></td>
                <td width="60"><div align="center"><?php echo $loc_asal; ?></div></td> 
                <td width="60"><?php echo $row["cost_center"]; ?></td>                
                <td width="150"><?php echo $row_reason["reject_desc"]; ?></td>
                <td width="250"><?php echo $row["remarks"]; ?><br>&nbsp;
               <input name="id_disposal[<?php echo $row["id_disposal"]; ?>]" type="hidden" value="<?php echo $row["id_disposal"]; ?>">
          </td>
            <td width="250"><?php echo $row["remark_approve"]; ?><br>&nbsp;
               <input name="id_disposal[<?php echo $row["id_disposal"]; ?>]" type="hidden" value="<?php echo $row["id_disposal"]; ?>"></td>
            <td width="250"><?php echo $row["remark_approve2"]; ?></td>
            </tr>
    </tbody>    
     
         <?php 
		   		  
		  $counter++; // menambah counter 
		  $no ++;  
		  $k ++; 
			   
			   
		}
			   
			   ?>   </table> 
        
             <p>&nbsp;</p>
             <table width="100%" border="0" cellpadding="2">
               <tr>
                 <td width="24%">&nbsp;</td>
                 <td width="16%">&nbsp;</td>
                 <td width="56%"><table width="100%" class="table table-bordered">
                   <tr>
                     <td width="25%">Prepared by</td>
                     <td width="25%">Checked by</td>
                     <td width="25%">Approved by</td>
                     <td width="25%">Approved by</td>
                   </tr>
                    <tr>
                     <td><p><b><?php echo $res_disposal2["user_fullname"];   ?></b>
                     <br><?php echo $row_disposal["W2"];   ?></p>
                     </td>
                     <td>&nbsp;</td>
                     <td><p><b><?php echo $res_approve["user_fullname"];   ?></b>
                     <br><?php if(($res_approve["user_fullname"]) != "") {  echo $row_disposal["W3"]; }  ?></p></td>
                     <td><p><b><?php echo $res_approve2["user_fullname"];   ?></b>
                      <br><?php if(($res_approve2["user_fullname"]) != "") { echo $row_disposal["W4"]; }  ?></p></td>
                   </tr>
                   <tr>
                     <td>Unit Leader/Supervisor</td>
                     <td>QC Engineer/ Executive</td>
                     <td>Manager/Head of Department</td>
                     <td>QA Manager</td>
                   </tr>
                 </table></td>
                 <td width="2%">&nbsp;</td>
               </tr>
             </table>
              <div class="footer_bawah"><?php include "footer.php";   ?></div>
<!--Footer-part-->        
    <!--<div class="bottom-left2">
  <input  name="btnback" type="button" id="btnCancel" class="btn btn-warning" value="BACK" onclick="javascript:parent.tb_remove();" />
</div>
-->
   </div></div> 
<!--end-Footer-part--> 

</body>
</html>