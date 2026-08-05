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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
<!-- div.page
    <!--  {
     <!--    page-break-after: always;
     <!--    page-break-inside: avoid;
   <!--    }
	  
<!-- .breakAfter{ 
<!-- page-break-after: always; 
<!-- }
<!-- .breakBefore{
<!-- page-break-before: always ;
<!-- } -->
	  
.tfoot { display: table-footer-group; }
.page {
    width: 21cm;
    min-height: 29.7cm;
	page-break-after: always ;
	bottom: 0;
}
 @media print{
  body{  margin-top: -1.3cm;  font-family: Arial, Helvetica, sans-serif; font-size:12px; }
  #ad{ display:none;}
  #leftbar{ display:none;}
  #contentarea{ width:100%;}
  .header, .hide { visibility: hidden }
  .tfoot { display: table-footer-group; }
   tr.page-break  { display: block; page-break-before: always; }
} 

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
$url = 'material_request_listProc.php';
$url2 = 'display_request.php';
$url3 = 'posting_request.php';
?>
<script type="text/javascript">
//SYNTAX: ddtabmenu.definemenu("tab_menu_id", integer OR "auto")
ddtabmenu.definemenu("ddtabs1", 0) //initialize Tab Menu #1 with 1st tab selected
ddtabmenu.definemenu("ddtabs2", 1) //initialize Tab Menu #2 with 2nd tab selected
ddtabmenu.definemenu("ddtabs3", 1) //initialize Tab Menu #3 with 2nd tab selected
ddtabmenu.definemenu("ddtabs4", 2) //initialize Tab Menu #4 with 3rd tab selected
ddtabmenu.definemenu("ddtabs5", -1) //initialize Tab Menu #5 with NO tabs selected (-1)
</script>
<body>
<p>
  <!-- Header -->
  <!-- End Header -->
  <?php

 $temp_mrin = $_GET["mrin_no"];

$queryu = "SELECT * from material_request WHERE temp_mrin = '$temp_mrin'";
$rs = mysql_query($queryu);   //run the query.


$query_2 = "SELECT *, DATE_FORMAT(MR.date_mrin,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2 from material_request as MR, scan_detail as SD WHERE MR.id_scan = SD.id_scan AND MR.temp_mrin = '$temp_mrin'";
$result_2 = mysql_query($query_2);   //run the query.
$data_2 = mysql_fetch_array($result_2);

 $query3 = "SELECT * FROM factory_detail WHERE id_fac = '".$data_2["factory"]."'";
    $result3 = mysql_query($query3);
	$row3 = mysql_fetch_array($result3);
	
	$query_k = "SELECT * from `user_detail` as uc WHERE uc.user_no = '".$data_2["user_create"]."'";
$result_k = mysql_query($query_k);
$row_k = mysql_fetch_array($result_k);

$query_k2 = "SELECT * from `user_detail` as uc2 WHERE uc2.username = '$username'";
$result_k2 = mysql_query($query_k2);
$row_k2 = mysql_fetch_array($result_k2);

 ?>
</p>

<table width="1000">
<tr>
      <td width="1%">&nbsp;</td>
      <td width="85%"> <div class="small-nav"></div></td>
      <td width="14%"><img src="../images/print2.jpg" width="48" height="48" onClick="window.print()" title="Print"/></td>
  </tr>

</table>
 <div class="page">
<table width="1000">
<tr>
  <td>&nbsp;</td>
  <td colspan="2"><div align="center"><span class="style4">MRIN LIST
    <?php if($data_2["status_urgent"] == "Y"){ echo "[Urgent]"; }     ?>
  </span></div></td>
  </tr>
</table>


      
<p align="center" class="style4">&nbsp;</p>
<p align="center" class="style4">&nbsp;</p>
      <table width="1000" border="0" cellpadding="1" cellspacing="2" style="border:solid 1px #d5d5d5;">
        <tr>
          <th width="204" height="28"><div align="left"><span class="style3">MRIN No</span></div></th>
          <th width="9" height="28"><span class="style3">:</span></th>
          <td width="322"><span class="style3"><?php echo $temp_mrin; ?></span></td>
          
          <th width="204" height="28"><div align="left"><span class="style3">Factory</span></div></th>
          <th width="11" height="28"><span class="style3">:</span></th>
          <td width="218" height="28"><span class="style3"><?php echo $data_2["factory"];  ?></span></td>
        </tr>
        <tr>
          <th width="204" height="28"><div align="left"><span class="style3">Date &amp; Time</span></div></th>
          <th height="28"><span class="style3">:</span></th>
          <td><span class="style3"><?php echo $data_2["R2"]; ?>&nbsp;<?php echo $data_2["time_posting"]; ?></span></td>
          <th width="208" height="28"><div align="left"><span class="style3">Required Date &amp; Time</span></div></th>
          <th height="28"><span class="style3">:</span></th>
          <td height="28"><span class="style3">&nbsp;<?php echo $data_2["R"]; ?>&nbsp;<?php echo $data_2["time_mrin"]; ?></span></td>
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
<p align="center" class="style4">&nbsp;</p>
      <p align="center" class="style4">&nbsp;</p>
  <div class="cl">&nbsp;</div>
      <!-- Content -->
   
          
              <!-- End Box Head -->
         <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <!-- Form -->
            <div class="form">
             <table width="1050" border="1" cellpadding="0" cellspacing="0" bordercolor="#666666" style="border:solid 1px #d5d5d5;">
               <tr>
                 <th width="100" height="28" bgcolor="#E9F58D" class="ac style3">Material Number</th>
                 <th width="122" height="28" bgcolor="#E9F58D"><span class="style3">Material Description</span></th>
                 <th width="100" bgcolor="#E9F58D"><span class="style3">Quantity</span></th>
                 <th width="45" bgcolor="#E9F58D"><span class="style3">Uom</span></th>
                 <th width="146" bgcolor="#E9F58D"><span class="style3">Prod Order</span></th>
                 <th width="42" bgcolor="#E9F58D"><span class="style3">Line</span></th>
                 <th width="90" bgcolor="#E9F58D"><span class="style3">Transfer Location</span></th>
                 <th width="90" height="28" bgcolor="#E9F58D"><span class="style3">Transfer Quantity</span></th>
                 <th width="65" height="28" bgcolor="#E9F58D"><span class="style3">Received Sloc</span></th>
                 <th width="60" bgcolor="#E9F58D"><span class="style3">Received by</span></th>
                 <th height="28" bgcolor="#E9F58D"><span class="style3">Barcode</span></th>
                 
               </tr>
             </table>  
             <?php
      $counter = 1;
   $no = 1;
    $k = 0;
   while ($row = mysql_fetch_array($rs))
   {
		//$user_no = $row[0]; 
 $no = sprintf('%03d', $no);
		
		if ($counter % 2 == 0)
		{ $warna = $warnaGenap;}
		else { $warna = $warnaGanjil; }	
   
   $query_scan = "SELECT * FROM scan_detail WHERE id_scan = '".$row[6]."'";
   $result_scan = mysql_query($query_scan);
   $row_scan = mysql_fetch_array($result_scan);

		 
   $query1_p = "SELECT * FROM scan_detail WHERE id_scan = '".$row[6]."' GROUP BY id_scan";
   $result1_p = mysql_query($query1_p);
   $row1_p = mysql_fetch_array($result1_p);
	
		 
  $query4_p = "SELECT * from mat_master_header as LD, mat_master_detail as SD WHERE SD.id_hdr = LD.id_hdr and SD.id_dtl = '".$row[5]."'";
  $result4_p = mysql_query($query4_p);
  $row4_p = mysql_fetch_array($result4_p); 
		  	 
	if($row4_p["mat_type"] == "Z100")
	{
	  $sta = "W210";
	} elseif($row4_p["mat_type"] == "Z200")	 
	{
	  $sta = "W221";
	} elseif($row4_p["mat_type"] == "Z300")
	 {
	  $sta = "NO";
	  }else{
	  $sta = "Invalid";
	  }
	  
		 
		if(($row["bom_qty"] != "") && ($row["bom_qty"] != "0.000"))
		{
		 
		 
		
		 ?>

<table width="1050" border="1" cellpadding="2" cellspacing="0" bordercolor="#CCCCCC">
          <?php
			 
			 
			  if ($k && $k % 3 == 0)  
		echo '<tr style="page-break-before:always">';  
	else if ($k)  
		echo '<tr>';  
	++$k; 
	?>
               <td width="100" height="28"><?php  echo $row4_p["bill_component"]; ?></td>
               <td width="122" height="28"><?php  echo $row4_p["material_desc_c"]; ?></td>
                <td width="100" height="28"><div align="right"><?php echo $row["bom_qty"]; ?>&nbsp;</div></td>
                <td width="45" height="28"><div align="center"><?php echo $row["bom_oum"]; ?></div></td>
                <td width="146" height="28"><div align="center"><?php echo $row_scan["prod_order"]; ?></div></td>
                <td width="42" height="28"><div align="center"><font color="#FF0000"><?php echo $row1_p["work_center"]; ?></font></div></td>
                <td width="90" height="28"><div align="center"><?php echo $sta; ?></div></td>
                <td width="90" height="28">&nbsp;</td>
                <td width="65" height="28"><div align="center"><font color="#FF0000"><?php echo $row4_p["sloc"]; ?></font></div></td>
                 <td width="60">&nbsp;</td>
                <td height="28">
                  
                  <div align="right">
                    <?php

// set the barcode content and type

$bar_text = ($row4_p["bill_component"].'|'.$row["bom_qty"].'|'.$row["bom_oum"].'|'.$row_scan["prod_order"].'|'.$temp_mrin.'|'.$row4_p["sloc"].'|'.$sta);

$barcodeobj = new TCPDF2DBarcode($bar_text, 'PDF417');
echo $barcodeobj->getBarcodeSVGcode(1.0, 0.8, 'black');

?>
                  </div></td>
               
      </tr>
            <input name="user_no" type="hidden" value="<?php echo $user_no; ?>">
        </table>  
         <?php 
		   
		   }// end if else
		
		  
		  $counter++; // menambah counter 
		 
			   
			 $no ++;   
			   
			   
			   }
			   
			   
			   ?>
         <p>&nbsp;</p>
             <p>&nbsp;</p>
           </div>
           <!-- End Form -->
        
</form>

</div>
</body>
</html>
