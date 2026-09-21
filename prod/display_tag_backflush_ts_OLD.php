<?php

/**
 * prod/display_tag_backflush_ts_OLD.php
 * Part of: Production module
 * Filename suggests: display tag backflush ts OLD
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, print_tag_backflush, pps_detail, pps_detail_transaction, mat_master_header, mat_master_detail.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, tcpdf_barcodes_2d.php.
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

$url = "print_tag_backflush_tran.php";

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
<!--<style type="text/css">
<!--
.style3 {color: #000000; border-color:#FFF;}
@media print{
body{  margin-top: -0.45cm;  font-family: Arial, Helvetica, sans-serif; font-size:12px; }
#ad{ display:none;}
#leftbar{ display:none;}
#contentarea{ width:95%;}
.header, .hide { visibility: hidden }
.tfoot { display:: none; }
@page { size:portrait; }
a[href]:after {
	content: none !important;
}
.hide-print {
	display: none;
}
}
.style_bg{background-color:#999999; }
.style4 {
	font-size: 14px;
	color: #000000;
	font-family: Arial, Helvetica, sans-serif;
}
.style1 {	
	font-size: 16px;
	font-weight: bold;
	color: #000000;
	font-family: Arial, Helvetica, sans-serif;
}
.style10 {	
	font-size: 10px;
	color: #000000; 
	font-family: Arial, Helvetica, sans-serif;
	padding-bottom: 0.5cm;
}
.style11 {	
	font-family: Arial, Helvetica, sans-serif;
	font-size: 22px;
	color: #000000;
	font-weight: bold;
}
.style7 {	
	font-size: 24px;
	font-weight: bold;
	color: #000000;
	font-family: Arial, Helvetica, sans-serif;
}
.style8 {	
	font-size: 13px;
	color: #000000; 
	font-family: Arial, Helvetica, sans-serif;
}
.style9 {	
	font-size: 26px;
	font-weight: bold;
	color: #000000; 
	font-family: Arial, Helvetica, sans-serif;
}


</style>
-->
<!--https://stackoverflow.com/questions/3341485/how-to-make-a-html-page-in-a4-paper-size-pages-->
<style>
Size : 8.27in and 11.69 inches


@media print{
}

@page Section1 {
size:5.8in 8.3in; 
/*margin:.10in .5in .10in .5in; 
mso-header-margin:.10in; 
mso-footer-margin:.10in; */
mso-paper-source:0;
}

body
{
	background-color:#FFF;
}

div.Section1 {
page:Section1;
/*position: fixed;*/
left: 50px;
right: 50px;
} 

div.printBt {
page:printBt ;
position: fixed;
bottom: 20px;
margin-left: 75px;
margin-right: 50px;
}

.style_bg{
	background-color:#999999; 
}
.style4 {
	font-size: 14px;
	color: #000000;
	font-family: Arial, Helvetica, sans-serif;
}
.style1 {	
	font-size: 16px;
	font-weight: bold;
	color: #000000;
	font-family: Arial, Helvetica, sans-serif;
}
.style10 {	
	font-size: 10px;
	color: #000000; 
	font-family: Arial, Helvetica, sans-serif;
	padding-bottom: 0.5cm;
}
.style11 {	
	font-family: Arial, Helvetica, sans-serif;
	font-size: 22px;
	color: #000000;
	font-weight: bold;
}
.style7 {	
	font-size: 24px;
	font-weight: bold;
	color: #000000;
	font-family: Arial, Helvetica, sans-serif;
}
.style8 {	
	font-size: 13px;
	color: #000000; 
	font-family: Arial, Helvetica, sans-serif;
}
.style9 {	
	font-size: 26px;
	font-weight: bold;
	color: #000000; 
	font-family: Arial, Helvetica, sans-serif;
}
</style>


<script type="text/javascript">
	function print_page() {
		var ButtonControl = document.getElementById("btnprint");
		ButtonControl.style.visibility = "hidden";
		window.print();
	}
</script>
    
</head>
<!--<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
<!--</script>
<script>
function myFunction() {
    window.print();
}
</script>-->
</head>
<body>



<?php

$uid = $_GET["uid"];

//--------- pps detail ------------

$query_pps = "SELECT *,DATE_FORMAT(posting_date,'%d-%m-%Y') as R FROM print_tag_backflush WHERE id_tran = '".db_esc($dbc, $uid)."'";
$result_pps = mysqli_query($dbc, $query_pps);

while($row = mysqli_fetch_array($result_pps))

{
  //----------display model 
	$query_info = "SELECT * FROM pps_detail WHERE plan_no = '".db_esc($dbc, $row["plan_no"])."'";
	$result_info = mysqli_query($dbc, $query_info);
	$row_info = mysqli_fetch_array($result_info);
	
	//----------display mat_type 
	$query_info2 = "SELECT * FROM pps_detail_transaction WHERE plan_no = '".db_esc($dbc, $row_info["plan_no"])."' AND ref_id = '".db_esc($dbc, $row_info["ref_id"])."'";
	$result_info2 = mysqli_query($dbc, $query_info2);
	$row_info2 = mysqli_fetch_array($result_info2);
	
	//----------display material header
	$query_info3 = "SELECT * FROM mat_master_header WHERE material_no = '".db_esc($dbc, $row["material_no"])."'";
	$result_info3 = mysqli_query($dbc, $query_info3);
	$row_info3 = mysqli_fetch_array($result_info3);


?>

<p>&nbsp;</p>

<center>
<div class=Section1> 
<table width="880" height="458" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td height="45" colspan="2" valign="top"><p><img src="../set_upload/<?php echo $filename;  ?>"width="267" height="27" hspace="2" vspace="2"/></p>
   </td>
    <td colspan="4" valign="top"><table width="75%" border="0" align="right" cellpadding="2">
      <tr>
        <td width="14%"> <span class="style8"> Doc No.</span></td>
        <td width="86%"> <span class="style8">QRP-PN 7.5.3-A01</span></td>
      </tr>
      <tr>  
        <td>&nbsp;</td>
        <td><div class="style_bg"><span class="style1"><?php if($row_info2["material_type"] == "Z310") { echo "FGS / PRODUCT TAG"; }elseif($row_info2["material_type"] == "Z210"){   echo "WIP / PRODUCT TAG"; }else{  echo "OTH /PRODUCT TAG";  } ?> </span></div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;<span class="style8">SUB ASSY / ROLL FORMING</span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;<span class="style8">SEQ. NO :  &nbsp;&nbsp;&nbsp;AS /SA /RF</span></td>
      </tr>
    </table></td>
    </tr>
   <tr>
    <td width="161" height="15" valign="top">&nbsp;<span class="style8">Material Document No.</span></td>
    <td width="196" height="15" valign="top">&nbsp;<span class="style1"><?php echo $row["bflush_no"];  ?></span></td>
    <td colspan="3" rowspan="3" valign="top"><?php

$query = "SELECT *,DATE_FORMAT(date_create,'%d-%m-%Y') as Q, DATE_FORMAT(date_posting,'%d-%m-%Y') as Q2 FROM pps_detail_transaction WHERE id = '".db_esc($dbc, $uid)."'";
$hasil = mysqli_query($dbc, $query);

// setting banyaknya kolom
$kolom = 2;

// membuat tabel berisi label barcode
echo " <center><br /><table border='0'>";
$counter = 1;
while ($data = mysqli_fetch_array($hasil))
{
	
	$query_sloc = "SELECT * FROM mat_master_detail WHERE material = '".db_esc($dbc, $data["material_no"])."' OR bill_component = '".db_esc($dbc, $data["material_no"])."'";
$result_sloc = mysqli_query($dbc, $query_sloc);
$data_sloc = mysqli_fetch_array($result_sloc);
	
	
	
	if (($counter-1) % $kolom == 0) echo "<tr>";
	?>
      <?php
	echo "<td align='center' style='padding: 0.5px'>";
	// set the barcode content and type
$barcodeobj = new TCPDF2DBarcode($data['bflush_no'].'|'.$data['comp_code'].'|'.$row['ploc'].'|'.$data['material_no'].'|'.$data['plan_no'].'|'.$row['shift_tag'].'|'.$row['tag_qty'].'|'.$row_info3['BUn'].'|'.$data_sloc['sloc'].'|'.$data['Q2'].'|'.$data['Q'].'|'.$row['slip_no'], 'PDF417');

// output the barcode as HTML object
// echo $barcodeobj->getBarcodeHTML(2, 2, 'black');
//echo $barcodeobj->getBarcodePNG(2, 2, array(0,0,0));
//$barcodeobj->getBarcodeSVG(2, 2, 'black');
 echo $barcodeobj->getBarcodeSVGcode(2, 1, 'black');
	
	//echo "<br>";
	//echo "<font color='#000000' font face='Arial, Helvetica, sans-serif'>".$data['plan_no'].'|'.$data['material_no'].'|'.$row['tag_no'].'|'.$row['tag_qty']."</font>";
	echo "</td>";
	
	if ($counter % $kolom == 0) echo "</tr>";
	$counter++;

}
echo "</table></center>";

?></td>
    <td width="146" rowspan="3" valign="center">&nbsp;<span class="style9"><?php echo $row_info3["part_side"];  ?></span></td>
  </tr>
   <tr>
     <td height="15" valign="top">&nbsp;<span class="style8">Production Date</span></td>
     <td height="15" valign="top">&nbsp;<span class="style1"><?php echo $row["R"];  ?></span></td>
   </tr>
   <tr>
     <td height="15" valign="top">&nbsp;<span class="style8">Production Time</span></td>
     <td height="15" valign="top">&nbsp;<span class="style1"><?php echo $row["posting_time"];  ?><?php if($row["posting_time"] <= "12:00:00") { echo " AM"; }else { echo " PM"; } ?> </span></td>
   </tr>
  <tr>
    <td height="30" rowspan="2" valign="top">&nbsp;<span class="style8">Part No.</span></td>
    <td height="30" colspan="3" rowspan="2" valign="top">&nbsp;<span class="style7"><?php echo $row["material_no"];     ?></span>
      </td>
    <td width="105" height="15" valign="top">&nbsp;<span class="style8">Model</span></td>
    <td width="146" height="15" valign="top">&nbsp;<span class="style1"><?php echo $row_info["model_code"];  ?></span></td>
  </tr>
  <tr>
    <td height="15" valign="top">&nbsp;<span class="style8">Production Line</span></td>
    <td width="146" height="15" valign="top">&nbsp;<span class="style1"><?php echo $row["station_loc"];  ?></span></td>
  </tr>
  <tr>
    <td height="45" rowspan="3" valign="top">&nbsp;<span class="style8">Part Name</span></td>
    <td height="45" colspan="3" rowspan="3" valign="top">&nbsp;<span class="style7"><?php echo $row["material_desc"];  ?></span></td>
    <td height="15" valign="top">&nbsp;<span class="style8">Shift</span></td>
    <td height="15" valign="top">&nbsp;<span class="style1"><?php if($row["shift_tag"] == "D/S"){ echo "Day"; }else{ echo "Night"; } ?></span></td>
  </tr>
  <tr>
    <td height="15" valign="top">&nbsp;<span class="style8">Supv/Ldr</span></td>
    <td height="15" valign="top">&nbsp;<span class="style1"><?php echo $res["user_fullname"]; ?></span></td>
  </tr>
  <tr>
    <td height="15" valign="top">&nbsp;<span class="style8">Location</span></td>
    <td height="15" valign="top">&nbsp;<span class="style1"><?php echo $row_info3["location_deliver"];  ?></span></td>
  </tr>
  <tr>
    <td height="15" valign="top">&nbsp;<span class="style8">Planned Order No.</span></td>
    <td height="15" colspan="3" valign="top">&nbsp;<span class="style1"><?php echo $row["plan_no"];  ?></span></td>
    <td height="15" valign="top">&nbsp;<span class="style8">Station</span></td>
    <td height="15" valign="top">&nbsp;<span class="style1"><?php echo $row_info3["station_deliver"];  ?></span></td>
  </tr>
  <tr>
    <td height="30" rowspan="2" valign="top">&nbsp;<span class="style8">Qty (PCS)</span></td>
    <td height="30" rowspan="2" valign="top">&nbsp;<span class="style7"><?php echo intval($row["tag_qty"]);  ?></span></td>
    <td width="124" height="30" rowspan="2" valign="top">&nbsp;<span class="style8">Slip No.</span></td>
    <td width="161" height="30" rowspan="2" valign="top">&nbsp;<span class="style1"><?php echo $row["slip_no"];  ?> of <?php echo $row["total_slip"];  ?> </span></td>
    <td height="15" valign="top">&nbsp;<span class="style8">Pack Type</span></td>
    <td height="15" valign="top">&nbsp;<span class="style1"><?php echo $row_info3["type_package"];  ?></span></td>
  </tr>
  <tr>
    <td height="15" valign="top">&nbsp;<span class="style8">Pack  No.</span></td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td height="15" colspan="2" valign="top">&nbsp;<span class="style8">Prepared by Production</span></td>
    <td height="15" colspan="2" valign="top">&nbsp;<span class="style8">QA/QC Confirmed</span></td>
    <td height="15" colspan="2" valign="top">&nbsp;<span class="style8">Received By</span></td>
    </tr>
  <tr>
    <td height="40" colspan="2" valign="top">&nbsp;</td>
    <td height="40" colspan="2" valign="top">&nbsp;</td>
    <td height="40" colspan="2" valign="top">&nbsp;</td>
    </tr>
</table>

<p>&nbsp;</p>

<?php

	  } // end while loop main

?>


</div>

</center>


<div class="printBt">
<input type="button" id="btnprint" value="Print this Page" onclick="print_page()" class="btn btn-success"/>
</div>

  <!--<table width="98%" border="0" cellpadding="2">
  <tr>
    <td>&nbsp;<input type="button" value="Print" onclick="myFunction()" class="btn btn-success" id="btnprint" /></td>
  </tr>
</table>-->  
    <!--Footer-part-->


<!--end-Footer-part--> 

</body>
</html>