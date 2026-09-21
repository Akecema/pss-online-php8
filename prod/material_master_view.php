<?php

/**
 * prod/material_master_view.php
 * Part of: Production module
 * Filename suggests: material master view
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: user_detail, sys_setup_maintain, mat_master_header.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, footer.php.
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
require_role($dbc, 2);
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
    $result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
    $res = mysqli_fetch_array($result2);
	
//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
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
<link rel="stylesheet" href="../css/style.css" type="text/css" media="all" />
<link href="../css/ddtabmenu.css" rel="stylesheet" type="text/css" />
<style type="text/css">
body,td,th {
	font-family: "Open Sans", sans-serif;
}
body {
	background-color: #EEEEEE;
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
$url = 'material_master_list.php';


?>
<body>
<div id="content">
  <h4>Display Material Master</h4>     

 <?php
	   $id_hdr = $_GET["id_hdr"];
	
	   $query_scan = "SELECT *,DATE_FORMAT(date_bom_create, '%d-%m-%Y') AS R2 FROM mat_master_header AS HD WHERE HD.id_hdr = '".db_esc($dbc, $id_hdr)."'";
	   $result_scan = mysqli_query($dbc, $query_scan);
	   $data_scan = mysqli_fetch_array($result_scan);
	   

		 $no = 1; 
		  
		  ?>
       
          
              <table class="table table-bordered table-condensed">
               <tr bgcolor="#E29225">
                 <td><div align="center">Item No.</div></td>
                 <td><div align="center">Material No.</div></td>
                 <td><div align="center">Material Description</div></td>
                 <td><div align="center">Material Type</div></td>
                 <td><div align="center">UoM</div></td>
                 <td><div align="center">Plant</div></td>
                 <td><div align="center">BOM</div></td>
                 <td><div align="center">Date BOM Created</div></td>
               </tr>
               <tr bgcolor="#FFFFFF">
                 <td height="35"><?php echo $no; ?></td>
                 <td height="35"><?php echo $data_scan["material_no"]; ?></td>
                 <td height="35"><?php echo $data_scan["material_desc"]; ?></td>
                 <td><?php echo $data_scan["material_type"]; ?></td>
                 <td><?php echo $data_scan["BUn"]; ?></td>
                 <td><?php echo $data_scan["plant"]; ?></td>
                 <td><?php echo $data_scan["bom"]; ?></td>
                 <td><?php echo $data_scan["R2"]; ?></td>
               </tr>
            </table>
             <p>--------------------------------------------------------------------------------------------------------------------------------------------- </p>
             <?php
			 
	  $query_component = "SELECT *, DATE_FORMAT(valid_from, '%d-%m-%Y') AS R FROM mat_master_header AS h, mat_master_detail AS s WHERE h.id_hdr = s.id_hdr AND s.material = '".db_esc($dbc, $data_scan["material_no"])."' AND s.bom_status != 'N'";
	   $result_component = mysqli_query($dbc, $query_component);
	  
			 ?>
            <table class="table table-condensed">
            <thead>
               <tr bgcolor="#9DB3CC">
                 <td><div align="center">Item No.</div></td>
                 <td><div align="center">Component</div></td>
                 <td><div align="center">Component Description</div></td>
                 <td><div align="center">Valid From</div></td>
                 <td><div align="center">SLoc</div></td>
                 <td><div align="center">IsLoc</div></td>
                 <td><div align="center">Mat. Type</div></td>
                 <td><div align="center">UoM</div></td>
                 <td><div align="center">Consumption</div></td>
               </tr>
               </thead>
               <tbody>
               <?php
			   
		 $counter = 1;
		 $i = 1;
		 $no2 = 1;
				 
			while($row = mysqli_fetch_array($result_component))
			{  

		
			   ?>
              
               <tr>
                 <td><?php echo $no2; ?></td>
                 <td><?php echo $row["bill_component"];   ?></td>
                 <td><?php echo $row["material_desc_c"];   ?></td>
                 <td><?php echo $row["R"];   ?></td>
                 <td><?php echo $row["sloc"];   ?></td>
                 <td><?php echo $row["isloc"];   ?></td>
                 <td><?php echo $row["mat_type"];   ?></td>
                 <td><?php echo $row["comp_unit"];   ?></td>
                 <td><?php echo $row["consumption"];   ?></td> 
                 <input name="id_dtl[<?php echo $i; ?>]" type="hidden" value="<?php echo $row["id_dtl"]; ?>">
                  <input name="id_hdr" type="hidden" value="<?php echo h($id_hdr); ?>">
               </tr>
              <?php
			
      $i++;
	  $no++;
	  $no2++;		 
			  } // end while loop
			  
			   ?></tbody>
             </table>  
</div>
<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part--> 
</body>
</html>
