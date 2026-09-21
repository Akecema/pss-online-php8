<?php

/**
 * prod/edit_prod_order.php
 * Part of: Production module
 * Filename suggests: edit prod order
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, material_request, MATERIAL, scan_detail, mat_master_header.
 * Includes: config.php, paginator.class2.php, tc_calendar.php.
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
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$warnaGenap = "#F4FBCA";   // warna blue grey
$warnaGanjil = "#f8f8f8";  // warna putih

$query_u = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
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
<style type="text/css">
<!--
.style3 {color: #000000}
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
if(isset($_POST["save"])) 
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

 $size2 = count($_POST["id_scan"]) + 1;

 $i = 1;
 
     //check only deilvery quantity in number
	 
               if(empty($_POST['bom_qty'][$i]))
				{
				  $bom_qty = FALSE;
				  $message.= '<p align="center">You are required to enter REQUIRED QUANTITY!</p>';
				  }
				  elseif(!is_numeric($_POST['bom_qty'][$i]))
				  { $bom_qty = FALSE;
				   $message.= '<p align="center">You are required to enter NUMBERS only for REQUIRED QUANTITY!</p>';
				  }  
				  else
				  { 
				  $bom_qty= escape_data($_POST['bom_qty'][$i]);
				  }	             
	
	if($bom_qty) //everything ok
{  	
  //----------------------------------------------------------------------------//-
  //baca asn yg ada kt database, check total asn, open balance                 //
  //                                                                           //
  //---------------------------------------------------------------------------//
  
  while ($i < $size2) {
    
//update table material request with new quantity

$query_update = "UPDATE material_request SET bom_qty = '".db_esc($dbc, $_POST["bom_qty"][$i])."', user_update = '".db_esc($dbc, $data_u["user_no"])."', date_update = NOW() WHERE id_req = '".db_esc($dbc, $_POST["id_req"][$i])."' ";

$result_update = mysqli_query($dbc, $query_update);

	 $i++;
   } // end while loop	
   	
     if($result_update)
           {
		   
		  echo "<script language='JavaScript'>alert('Material Request successfully update.');top.tb_remove();top.window.location='display_request.php'; </script>";
		 // exit(); //quit the script
		   			  
             }
             else 
			 {
             $message = '<p> CANNOT UPDATE MATERIAL REQUEST!!!. </p>';
              mysqli_close($dbc); //close db
             }  
   
		

}  

  //print the message if there is one.
if (isset($message))
{ 
echo '<font color="red" class ="error_entry">', $message, '</font>';

} 


}



 ?>
</p>
<p>&nbsp;</p>
<table width="650">
    <tr>
      <td width="1%">&nbsp;</td>
      <td width="85%"> <div class="small-nav"> Material Request&nbsp;&gt;Edit Request</div></td>
      <td width="14%">&nbsp;</td>
  </tr>
  </table>

<div id="main2">
      
      <div class="cl">&nbsp;</div>
      <!-- Content -->
      <div id="subcontent">
        <!-- Box -->
        <div class="box">
          <!-- Box Head -->
          <div class="box-head">
            <h2>Edit Request</h2>
          </div>
          
          <?php
		  
    $query = "SELECT * FROM material_request WHERE status_request = 'N' GROUP BY id_scan ORDER BY id_req ASC";
	$rs = mysqli_query($dbc, $query);   //run the query. 
		  
		  
		  ?>
          <!-- End Box Head -->
         <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <!-- Form -->
            <div class="form">
             <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#666666" style="border:solid 1px #d5d5d5;">
               <tr>
                 <th height="28" bgcolor="#E9F58D" class="ac style3">Item</th>
                 <th width="287" height="28" bgcolor="#E9F58D"><span class="style3">Material &amp; Production Order Number</span></th>
                 <th width="87" height="28" bgcolor="#E9F58D"><span class="style3">Required Quantity</span></th>
                 <th width="100" height="28" bgcolor="#E9F58D"><span class="style3">UoM </span></th>
                 <th width="60" height="28" bgcolor="#E9F58D"><span class="style3">Work Center</span></th>
                 
               </tr>
             </table>  <?php
      $counter = 1;
   $no = 1;
   $i = 1;
   
   while ($row2 = mysqli_fetch_array($rs))
   {
		//$user_no = $row[0]; 

		
		if ($counter % 2 == 0)
		{ $warna = $warnaGenap;}
		else { $warna = $warnaGanjil; }	
   
   $query_scan = "SELECT * FROM scan_detail WHERE id_scan = '".db_esc($dbc, $row2[6])."' GROUP BY id_scan";
   $result_scan = mysqli_query($dbc, $query_scan);
   $row_scan = mysqli_fetch_array($result_scan);
	
	
		  
		  ?>     
           <table width="100%" border="1" cellpadding="0" cellspacing="1" bordercolor="#CCCCCC">
            <tr>
                <td height="28"><?php echo $no; ?></td>
       <td width="527">&nbsp;<?php echo $row_scan["material_no"]; ?>
                 &nbsp;&nbsp;&nbsp;<?php echo $row_scan["prod_order"]; ?></td>
               
             </tr>
               <?php
		
	$query_again = "SELECT * FROM material_request WHERE status_request = 'N' and id_scan = '".db_esc($dbc, $row2[6])."' ORDER BY id_req ASC";
    $rs_again = mysqli_query($dbc, $query_again);   //run the query.
	 while ($row = mysqli_fetch_array($rs_again))
   {
		 
   $query1_p = "SELECT * FROM scan_detail WHERE id_scan = '".db_esc($dbc, $row2[6])."' GROUP BY id_scan";
   $result1_p = mysqli_query($dbc, $query1_p);
   $row1_p = mysqli_fetch_array($result1_p);
	
	
		 
  $query4_p = "SELECT * from mat_master_header as LD, mat_master_detail as SD WHERE SD.id_hdr = LD.id_hdr and SD.id_dtl = '".db_esc($dbc, $row[5])."'";
  $result4_p = mysqli_query($dbc, $query4_p);
  $row4_p = mysqli_fetch_array($result4_p); 
		  	 
		 
		 
		 
		 
		 ?>

<table width="100%" border="1" cellpadding="0" cellspacing="1" bordercolor="#CCCCCC">
              <tr bgcolor="<?php echo $warna; ?>" >
               <td height="28">&nbsp;</td>
               <td width="287" height="28">&nbsp;<?php  echo $row4_p["bill_component"]; ?></td>
                <td width="87" height="28"><div align="right">
                <input name="bom_qty[<?php echo $i; ?>]" type="text" value="<?php if(isset($_POST["bom_qty"])) { echo h($_POST["bom_qty"][$i]); }else{  echo $row["bom_qty"];   } ?>" size="10"/>
              &nbsp;</div></td>
                <td width="99" height="28"><div align="center"><?php echo $row["bom_oum"]; ?></div></td>
                <td width="60" height="28">&nbsp;<font color="#FF0000"><?php echo $row1_p["work_center"]; ?></font>
                <input name="id_req[<?php echo $i; ?>]" type="hidden" value="<?php echo $row["id_req"]; ?>">
                 <input name="id_scan[<?php echo $i; ?>]" type="hidden" value="<?php echo $row["id_scan"]; ?>">
                 </td>
               
          </tr>
          
       
        </table>  
         <?php 
		 
		  $i++;
		  
		  $counter++; // menambah counter 
		 }
			   
			 $no ++;   
			   
			   
			   }
			   
			   
			   ?>
             </table>
             <p>&nbsp;</p>
             <p>&nbsp;</p>
             
           </div>
           <!-- End Form -->
         <div class="buttons">
           <input name="save" type="submit" id="submit" value="SAVE" class="button">
         </div>
          </form>
        </div>
        <!-- End Box -->
      </div>
      <!-- End Content -->
    
      <div class="cl">&nbsp;</div>
</div>


</body>
</html>
