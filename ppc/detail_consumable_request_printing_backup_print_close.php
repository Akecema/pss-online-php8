<?php

/**
 * ppc/detail_consumable_request_printing_backup_print_close.php
 * Part of: PPC module (Production Planning & Control)
 * Filename suggests: detail consumable request printing backup print close
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: consumable_request, factory_detail, user_detail, work_center_detail, consumable_request_cancel, reason_req_cancel.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, tcpdf_barcodes_2d.php, tr>
        <tr>
          <th height=.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
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

 $temp_mrin = $_GET["mrin_no"];
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Ingress Autoventures Co., Ltd.</title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../css/style.css" type="text/css" media="all" />
<link href="../css/ddtabmenu.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style3 {color: #000000}
.style4 {
	font-size: 14px;
	font-weight: bold;
}
-->
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
 bottom: 0;
   
}

 @media print{
  body{  margin-top: -1.3cm;  font-family: Arial, Helvetica, sans-serif; font-size:12px; }
  #ad{ display:none;}
  #leftbar{ display:none;}
  #contentarea{ width:100%;}
  .header, .hide { visibility: hidden }
  .tfoot { display: table-footer-group; }
  @page {size: landscape}

} 
</style> 

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
$url1 = 'display_consumable_urgent.php';
?>
<script type="text/javascript">
//SYNTAX: ddtabmenu.definemenu("tab_menu_id", integer OR "auto")
ddtabmenu.definemenu("ddtabs1", 0) //initialize Tab Menu #1 with 1st tab selected
ddtabmenu.definemenu("ddtabs2", 1) //initialize Tab Menu #2 with 2nd tab selected
ddtabmenu.definemenu("ddtabs3", 1) //initialize Tab Menu #3 with 2nd tab selected
ddtabmenu.definemenu("ddtabs4", 2) //initialize Tab Menu #4 with 3rd tab selected
ddtabmenu.definemenu("ddtabs5", -1) //initialize Tab Menu #5 with NO tabs selected (-1)
</script>
<script type="text/javascript">
<!--
function getConfirmation(){
  var retVal = confirm("Are you sure you want to CLOSE MRIN No. : <?php echo $temp_mrin; ?>?")
   if( retVal == true ){
     // alert("User wants to continue!");

	window.print();
	parent.tb_remove(); parent.location.reload(1);
	
	 top.frames['mainFrame'].location.href = "detail_consumable_request_update_print.php?mrin_no=<?php echo $temp_mrin; ?>";

	
	return true;
	
	
   }else{
      alert("User does not want to continue!");
	  return false;
   }
}
//-->
</script>
</head>

<body>
<p>
  <!-- Header -->
  <!-- End Header -->
  <?php



$queryu = "SELECT * FROM consumable_request as MR, consumable_detail as SD WHERE MR.id_con = SD.id_con AND MR.temp_mrin = '$temp_mrin'";
$rs = mysqli_query($dbc, $queryu);   //run the query.


$query_2 = "SELECT *, DATE_FORMAT(MR.date_require,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2 from consumable_request as MR, consumable_detail as SD WHERE MR.id_con = SD.id_con AND MR.temp_mrin = '$temp_mrin'";
$result_2 = mysqli_query($dbc, $query_2);   //run the query.
$data_2 = mysqli_fetch_array($result_2);

 $query3 = "SELECT * FROM factory_detail WHERE id_fac = '".$data_2["factory"]."'";
    $result3 = mysqli_query($dbc, $query3);
	$row3 = mysqli_fetch_array($result3);
	
		$query_k = "SELECT * from `user_detail` as uc WHERE uc.user_no = '".$data_2["user_create"]."'";
$result_k = mysqli_query($dbc, $query_k);
$row_k = mysqli_fetch_array($result_k);

$query_k2 = "SELECT * from `user_detail` as uc2 WHERE uc2.username = '$username'";
$result_k2 = mysqli_query($dbc, $query_k2);
$row_k2 = mysqli_fetch_array($result_k2);

 ?>
</p>

<table width="1000">
<tr>
      <td width="1%">&nbsp;</td> 
      <td width="14%"><img src="../images/print2.jpg" width="48" height="48" onClick="window.print()" title="Print"/></td>
      <td width="85%"> <div class="small-nav"></div></td>
     
  </tr>

</table>
 
<table width="1000">
<tr>
  <td>&nbsp;</td>
  <td colspan="2"><div align="center"><span class="style4">MRIN LIST
    </span></div></td>
  </tr>
</table>

      <?php
		 
	 /*if(isset($_POST["submit"]) && $_POST!=="") 
		{ // handle the form.
		
		?>
      <script type="text/javascript"> 
    var retVal = window.onload=function(){self.print();
     if( retVal == true ){ 

<?php

	$query_update_print = "UPDATE consumable_request SET status_print = 'Y' WHERE temp_mrin = '$temp_mrin'";
	$result_update_print = mysqli_query($dbc, $query_update_print);
?>
    //self.parent.tb_remove();
    //parent.tb_remove(); parent.location.reload(1);
 
 
	return true;
	//  window.close();
	
	}else{
	 return false;
	
	}

 } 
window.onunload = function(){
console.log('closed!');
 window.close();
} 

</script>   

<?php
	

		
		}*/
		?>

      
<p align="center" class="style4">&nbsp;</p>
<p align="center" class="style4">&nbsp;</p>
      <table width="900" border="0" cellpadding="1" cellspacing="2" style="border:solid 1px #d5d5d5;">
        <tr>
          <th width="204" height="28"><div align="left"><span class="style3">MRIN No</span></div></th>
          <th width="9" height="28"><span class="style3">:</span></th>
          <td width="257"><span class="style3"><?php echo $temp_mrin; ?></span></td>
          <th width="173" height="28"><div align="left"><span class="style3">Factory</span></div></th>
          <th width="11" height="28"><span class="style3">:</span></th>
          <td width="218" height="28"><span class="style3"><?php echo $data_2["factory"];  ?></span></td>
        </tr>
        <tr>
          <th width="204" height="28"><div align="left"><span class="style3">Date &amp; Time</span></div></th>
          <th height="28"><span class="style3">:</span></th>
          <td><span class="style3"><?php echo $data_2["R2"]; ?>&nbsp;<?php echo $data_2["time_posting"]; ?></span></td>
          <th width="173" height="28"><div align="left"><span class="style3">Required Date &amp; Time</span></div></th>
          <th height="28"><span class="style3">:</span></th>
          <td height="28"><span class="style3">&nbsp;<?php echo $data_2["R"]; ?>&nbsp;<?php echo $data_2["time_require"]; ?></span></td>
        </tr>
        <tr>
          <th height="28"><div align="left"><span class="style3">Requested by</span></div></th>
          <th height="28"><span class="style3">:</span></th>
          <td><span class="style3"><?php echo $row_k["user_fullname"]; ?></span></td>
          <th height="28"><div align="left"><span class="style3">Prepared by (PPC)</span></div></th>
          <th height="28"><span class="style3">:</span></th>
          <td height="28"><span class="style3"><?php echo $row_k2["user_fullname"]; ?></span></td>
        </tr>
      </table>
<p>&nbsp;</p>
      <!-- Content -->
   
          
              <!-- End Box Head -->
              <!-- Form <div class="form">   detail_consumable_request_printing.php?mrin_no=<?php echo $temp_mrin;  ?> -->
            
         <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
           <table width="1000" border="1" cellpadding="0" cellspacing="0" bordercolor="#666666" style="border:solid 1px #d5d5d5;">
             <tr>
               <th width="73" height="28" bgcolor="#E9F58D" class="ac style3">Material Number</th>
               <th width="200" height="28" bgcolor="#E9F58D"><span class="style3">Material Description</span></th>
               <th width="100" bgcolor="#E9F58D"><span class="style3">Quantity</span></th>
               <th width="63" bgcolor="#E9F58D"><span class="style3">Uom</span></th>
               <th width="80" bgcolor="#E9F58D"><span class="style3">Line</span></th>
               <th width="80" bgcolor="#E9F58D"><span class="style3">Transfer Location</span></th>
               <th width="100" bgcolor="#E9F58D"><span class="style3">Transfer Quantity</span></th>
               <th width="80" bgcolor="#E9F58D"><span class="style3">Cost Center</span></th>
               <th width="200" bgcolor="#E9F58D"><span class="style3">Barcode</span></th>
             </tr>
           </table>
           <?php
      $counter = 1;
   $no = 1;
    $k = 0;
   while ($row = mysqli_fetch_array($rs))
   {
		//$user_no = $row[0]; 
 $no = sprintf('%03d', $no);
		
		if ($counter % 2 == 0)
		{ $warna = $warnaGenap;}
		else { $warna = $warnaGanjil; }	
   
     
  //------------------cost center --------------------//
   $query_cost_center = "SELECT * FROM work_center_detail WHERE id_work = '".$row["id_work"]."'";
   $result_cost_center = mysqli_query($dbc, $query_cost_center) or die (mysqli_error($dbc));
   $row_cost_center = mysqli_fetch_array($result_cost_center);
   
		 ?>
<table width="1000" border="1" cellpadding="2" cellspacing="0" bordercolor="#CCCCCC">
  <?php
			 
			 
			  if ($k && $k % 7 == 0)  
		echo '<tr style="page-break-before:always">';  
	else if ($k)  
		echo '<tr>';  
	++$k; 
	
	?>
  <tr>
    <td width="73" height="28"><?php  echo $row["material_no"]; ?></td>
    <td width="200" height="28"><?php  echo $row["mat_desc"]; ?></td>
    <td width="100" height="28"><div align="right"><?php echo $row["con_qty"]; ?>&nbsp;</div></td>
    <td width="63" height="28"><div align="center"><?php echo $row["con_uom"]; ?></div></td>
    <td width="80" height="28"><div align="center"><?php echo $row["id_work"]; ?></div></td>
    <td width="80"><div align="center"><?php echo $row["cost_center"]; ?></div></td>
    <td width="100">&nbsp;</td>
    <td width="80"><div align="center"><?php echo $row_cost_center["cost_center"]; ?></div></td>
    <td width="200"><?php

// set the barcode content and type

$bar_text = ($row["material_no"].'|'.$row["con_qty"].'|'.$row["con_uom"].'|'.$temp_mrin.'|'.$row_cost_center["cost_center"].'|'.$row["cost_center"]);

$barcodeobj = new TCPDF2DBarcode($bar_text, 'PDF417');
echo $barcodeobj->getBarcodeSVGcode(1.0, 0.8, 'black');

?></td>
    <input name="user_no" type="hidden" value="<?php echo $data_u["user_no"]; ?>" />
    <?php 
		  	  
		  $counter++; // menambah counter 
		 
			   
			 $no ++;   
			   
			   
			   }
	
	
			   
			  
			   ?>
  </tr>
</table>

                          <p>&nbsp;</p> 
                 <?php
		
/*	$query_display_reason = "SELECT * from `consumable_request_cancel` WHERE temp_mrin = '$temp_mrin' AND status = 'Cancel'";
    $result_display_reason = mysqli_query($dbc, $query_display_reason);
    $row_display_reason = mysqli_fetch_array($result_display_reason);
	
	$query_reason_tbl = "SELECT * from `reason_req_cancel` WHERE id_cancel = '".$row_display_reason["reason_cancel"]."'";
	 $result_reason_tbl = mysqli_query($dbc, $query_reason_tbl);
    $row_reason_tbl = mysqli_fetch_array($result_reason_tbl);
	
	if($row_display_reason	> 0)
	{   */
		?>
  <!--      <table width="80%" border="0" cellpadding="2" cellspacing="2" style="border:solid 1px #d5d5d5;">
  <tr>
    <th width="13%" height="40"><div align="right">Reason</div></th>
    <th width="3%" height="40">:</th>
    <td width="84%" height="40"><?php //echo $row_reason_tbl["reason_desc_cancel"].' - ' . $row_display_reason["reason_cancel2"]; ?></td>
  </tr>
</table> -->
<?php
 // }


?>
<!-- <input name="submit" type="submit" value="PRINT" class="button"  onclick="return confirm('Printing for MRIN No. <?php echo $temp_mrin; ?> ');" /> -->
<p>&nbsp;</p>
<input type="button" value="Print & Close" onclick="getConfirmation();" class="button"/>
             <p>&nbsp;</p> 
                
</form>
           
           <!-- End Form </div>-->
        


</body>
</html>
