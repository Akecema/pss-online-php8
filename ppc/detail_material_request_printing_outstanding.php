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

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysql_query($query_setup);   //run the query.
$num_setup = mysql_num_rows($rs_setup);   //how many material are there?
$data_setup = mysql_fetch_array($rs_setup);
//----------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<title><?php echo $data_setup["title_desc"]; ?></title>
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
</head>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>
<style type="text/css" media="print"> 
.breakAfter{ 
page-break-after: always; 
}
.breakBefore{
page-break-before: always ;
}
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
} 

</style> 
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
<body onload="window.print()">
<p>
  <!-- Header -->
  <!-- End Header -->
  <?php

 $temp_mrin = $_GET["mrin_no"];
 $prod_order = $_GET["prod_order"];

$queryu = "SELECT * from material_request WHERE temp_mrin = '$temp_mrin'";
$rs = mysql_query($queryu);   //run the query.


$query_2 = "SELECT *, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R from material_request as MR, scan_detail as SD WHERE MR.id_scan = SD.id_scan AND MR.temp_mrin = '$temp_mrin'";
$result_2 = mysql_query($query_2);   //run the query.
$data_2 = mysql_fetch_array($result_2);

    $query3 = "SELECT * FROM factory_detail WHERE id_fac = '".$data_2["factory"]."'";
    $result3 = mysql_query($query3);
	$row3 = mysql_fetch_array($result3);

 ?>
</p>
<p>&nbsp;</p>
<table width="1000">
<tr>
      <td width="1%">&nbsp;</td>
      <td width="85%"> <div class="small-nav"></div></td>
      <td width="14%"><a href="javascript:printWindow(); return false;" target="_blank"><img src="../images/print2.jpg" width="48" height="48" /></a></td>
  </tr>

</table>
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
          <th width="204" height="28" class="ac style3"><div align="right">MRIN No</div></th>
          <th width="9" height="28">:</th>
          <td width="322"><?php echo $temp_mrin; ?></td>
          <th width="204" height="28"><span class="style3">Request Date &amp; Time </span></th>
          <th width="11" height="28"><span class="style3">:</span></th>
          <td width="218" height="28"><?php echo $data_2["R"]; ?>&nbsp;&nbsp;<?php echo $data_2["time_posting"]; ?></td>
        </tr>
        <tr>
          <th width="204" height="28" class="ac style3"><div align="right">Factory</div></th>
          <th height="28">:</th>
          <td><?php echo $data_2["factory"];  ?></td>
          <th width="208" height="28">&nbsp;</th>
          <th height="28">&nbsp;</th>
          <td height="28">&nbsp;</td>
        </tr>
        <tr>
          <th height="28" class="ac style3">&nbsp;</th>
          <th height="28">&nbsp;</th>
          <th>&nbsp;</th>
          <th height="28">&nbsp;</th>
          <th height="28">&nbsp;</th>
          <th height="28">&nbsp;</th>
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
             <table width="1100" border="1" cellpadding="0" cellspacing="0" bordercolor="#666666" style="border:solid 1px #d5d5d5;">
               <tr>
                 <th width="100" height="28" bgcolor="#E9F58D" class="ac style3">Material Number</th>
                 <th width="122" height="28" bgcolor="#E9F58D"><span class="style3">Material Description</span></th>
                 <th width="45" bgcolor="#E9F58D"><span class="style3">Uom</span></th>
                 <th width="120" bgcolor="#E9F58D"><span class="style3">Prod Order</span></th>
                 <th width="93" bgcolor="#E9F58D"><span class="style3">Material Produce</span></th>
                 <th width="45" bgcolor="#E9F58D"><span class="style3">Line</span></th>
                 <th width="55" bgcolor="#E9F58D"><span class="style3">Transfer Location</span></th> 
                  <th width="55" height="28" bgcolor="#E9F58D"><span class="style3">Received Sloc</span></th>
                  <th width="90" bgcolor="#E9F58D"><span class="style3">Requested Quantity</span></th>
                 <th width="90" height="28" bgcolor="#E9F58D"><span class="style3">Transfer Quantity</span></th>
                 <th width="90" height="28" bgcolor="#E9F58D"><span class="style3">Outstanding Quantity</span></th>
               
                 <th width="90" height="28" bgcolor="#E9F58D">&nbsp;</th>
               </tr>
             </table>  
             <?php
      $counter = 1;
   $no = 1;
   
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
		  	 
	if($row4_p["mat_type"] == "Z110")
	{
	  $sta = "W110";
	} elseif($row4_p["mat_type"] == "Z210")	 
	{
	  $sta = "W110";
	} elseif($row4_p["mat_type"] == "Z310")
	 {
	  $sta = "";
	  }else{
	  $sta = "Invalid";
	  }
	  
		 
		if(($row["bom_qty"] != "") && ($row["bom_qty"] != "0.000"))
		{
		 
	
	
	

		 ?>

<table width="1100" border="1" cellpadding="2" cellspacing="0" bordercolor="#CCCCCC">
              <tr>
               <td width="100" height="28"><?php  echo $row4_p["bill_component"]; ?></td>
               <td width="122" height="28"><?php  echo $row4_p["material_desc_c"]; ?></td>
                <td width="45" height="28"><div align="center"><?php echo $row["bom_oum"]; ?></div></td>
                <td width="120" height="28"><div align="center"><?php echo $row_scan["prod_order"]; ?></div></td>
                <td width="93" height="28"><?php  echo $row4_p["material"]; ?></td>
                <td width="45" height="28"><div align="center"><font color="#FF0000"><?php echo $row1_p["work_center"]; ?></font></div></td>
                <td width="55" height="28"><div align="center"><?php echo $sta; ?></div></td>
                <td width="55" height="28"><div align="center"><font color="#FF0000"><?php echo $row4_p["isloc"]; ?></font></div></td> 
                <td width="90" height="28"><div align="right"><?php echo $row["bom_qty"]; ?>&nbsp;</div></td>
                <td width="90" height="28">
 
	
	              <div align="right">
	                <?php 
					
		$query_tp = "SELECT *,SUM(rquantity) as TOT FROM post_detail_header WHERE mrin_no = '$row[temp_mrin]' AND prod_order = '$prod_order' AND mvt_type = 311 AND material_no = '$row4_p[bill_component]' AND status_posting != 'Cancel'";
	$result_tp  = mysql_query($query_tp); 
	//$row_tp = mysql_fetch_assoc($result_tp); 
		
					
		$outs_qty = 0;
					
	while($row_tp = mysql_fetch_assoc($result_tp))
   {
	echo $row_tp["TOT"]; 
	
	$tp_quantity = $row_tp["TOT"]; 
	
	$outs_qty = (($row["bom_qty"]) - ($row_tp["TOT"]));
	
	 }//end while $row_tp	
	 
	$outs_qty1 = number_format($outs_qty,3);
	

	 ?>
                </div></td>
    <td width="90" height="28" bgcolor="#FFCC99"><div align="right"><?php echo $outs_qty1; ?></div></td>
                <td width="90" height="28"><div align="center">
  <?php                 
                	//-------------------------------------------------------
					// tick and cross icon for update status
					//------------------------------------------------------------
   								
        if(($row["bom_qty"] == $tp_quantity) || ($row["bom_qty"] < $tp_quantity))
{        

?>
              <img src="../images/tick.png" width="25" height="25" title="OK"/>
             
            <?php
	 }elseif(($row["bom_qty"] > $tp_quantity))
       {

?>
              <img src="../images/cross.png" width="25" height="25" title="Not OK"/>
    <?php
	
	} else{
	
	
	echo "invalid";  }   
                
     ?> 
    </div>           
</td>
                
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


         <table width="1000" border="0" cellpadding="1" cellspacing="2">
           <tr>
             <th width="204" height="28" class="ac style3"><div align="right">Prepared by (PPC)</div></th>
             <th width="9" height="28">:</th>
             <th height="28">&nbsp;</th>
           </tr>
           <tr>
             <th width="204" height="28" class="ac style3"><div align="right">Received by (Requestor)</div></th>
             <th height="28">:</th>
             <th height="28">&nbsp;</th>
           </tr>
         </table>
</body>
</html>
