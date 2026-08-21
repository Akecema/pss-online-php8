<?php

/**
 * ppc_store/list_ftp_rt_subcont.php
 * Part of: PPC Store module
 * Filename suggests: list ftp rt subcont
 *
 * Behavior: requires an active login session ($_SESSION['username']).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, ftp_ret_subcont, table_material, vendor_detail, ftp_ret_cancel_subcont.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_ppc_menu.php, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");

$Cdate = date ("l, j F Y ");
set_time_limit(0);

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$url = "list_ftp_tr_store.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
    $result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
    $res = mysqli_fetch_array($result2);
//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------			

$today = getdate();
$hours = $today['hours']; 
$minutes = $today['minutes'];
$seconds = $today['seconds'];
$month = $today['mon']; 
$mday = $today['mday']; 
$year = $today['year']; 	

//CR status (New)

$sta = "SELECT * from request_status WHERE status_id = '1' ";
$sta_res = mysqli_query($dbc, $sta);
$rst_sta = mysqli_fetch_array($sta_res);

//CR status (Released)
$sta2 = "SELECT * from request_status WHERE status_id = '2' ";
$sta_res2 = mysqli_query($dbc, $sta2);
$rst_sta2 = mysqli_fetch_array($sta_res2);	

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


</head>
<body>

<!--Header-part-->
<div id="header">
  <h1>&nbsp;</h1>
</div>
<?php  include "top_modal_menu.php";   ?>
<!--close-Header-part--> 

<!--sidebar-menu-->
<?php include "left_ppc_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_ppc_store.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a><a href="#" class="#">FTP Monitoring : PPC Store</a> <a href="#" class="current">FTP Transfer</a></div>
  <h1>FTP Transfer</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
         <div class="widget-box">
          <div class="widget-title">
              <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li><a  role="tab" href="list_ftp_tr_store.php">TP to Store</a></li>
              <li><a role="tab" href="list_ftp_tr_plb.php">TP to PLB</a></li>
              <li><a role="tab" href="list_ftp_tr_subcont.php">TP to Subcont</a></li>
              <li><a role="tab" href="list_ftp_rt_plb.php">Return from PLB</a></li>
              <li class="active"><a role="tab" href="list_ftp_rt_subcont.php">Return from Subcont</a></li>
             </ul>
          </div>
          </div>
  
          
      <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display File</h5>
          </div>
             
          <div class="widget-content nopadding">
        <h3>Pending Update SAP</h3>
      -------------------------------------------------------
<?php
		
	    //date_default_timezone_set('Asia/Bangkok');
		//date_default_timezone_set('Asia/Kuala_Lumpur');
		

$root = $_SERVER['DOCUMENT_ROOT'];
$path = "../../PSS_Online/FromPortal3/RT_SUBCON/"; 

// Open the folder
 $dir_handle = @opendir($root . $path) or die("Unable to open $path");

$dir = "$root/FromPortal3/RT_SUBCON/";

$folder = "../../PSS_Online/FromPortal3/RT_SUBCON/";
$filetype = '*.*';    
$files = glob($folder.$filetype);    
$total = count($files);   

/*
$root = $_SERVER['DOCUMENT_ROOT'];
$path = "/PSS_Online/FromPortal3/RT_SUBCON/"; 

// Open the folder
 $dir_handle = @opendir($root . $path) or die("Unable to open $path");

$dir = "$root/PSS_Online/FromPortal3/RT_SUBCON/";

$folder = '../../PSS_Online/FromPortal3/RT_SUBCON/';
$filetype = '*.*';    
$files = glob($folder.$filetype);    
$total = count($files);  
*/ 
// Open a directory, and read its contents
if(is_dir($dir)){
  if($dh = opendir($dir)){
  // $total2 = count($dh); removed - count() on a directory resource/handle throws a fatal TypeError under PHP 8 (was PHP7: warning + returned 1). $total2 was assigned but never read anywhere in this file, so the line was just dropped rather than wrapped.
  
   echo "<br>";
   echo "<font color='blue'>TOTAL FILES : ".$total." </font>"; echo "<br>";
   ?>
   <table class="table">
   <tr>
    <td width="20%">File Name</td> 
    <td width="20%">Document No.</td>
    <td width="20%">Material No.</td>
    <td width="10%">Qty FTP</td>
    <td width="10%">Vendor</td>
    <td width="10%">Posting Date</td>
    <td width="10%">Posting Time</td>
   </tr>
   </table>
   
   <?php
      while (($file = readdir($dh)) !== false){
	
	            clearstatcache();
                if(is_file($dir."/".$file)) {    
				 $path_parts2 = pathinfo($file);
                 $filename2 = $path_parts2["filename"]; 
				
	  ?>
    <table class="table">
    <tr>
    <td colspan="6">
	<b>	<?php echo $filename2; ?>  </b>
        
    <?php		 			 
				 
		//---check filename from table ftp backflush --------
		
	$query = "SELECT *, DATE_FORMAT(date_create,'%d-%m-%Y %H:%i:%s') AS R2, DATE_FORMAT(posting_date,'%d-%m-%Y') AS R3 FROM ftp_ret_subcont WHERE status_ftp = 'Y' AND file_name = '".$filename2."'";
	$rs = mysqli_query($dbc, $query);
	
	while($row_rs = mysqli_fetch_array($rs))
	
	{
				 
		//---check material type in table material--------
		
	$query_mat_type = "SELECT * FROM table_material WHERE material_no = '".$row_rs["material_no"]."'";
	$rs_mat_type = mysqli_query($dbc, $query_mat_type);   //run the query.
	$row_mat_type = mysqli_fetch_array($rs_mat_type);   //how many material are there?	
	
		//---check vendor in table vendor_detail--------
		
	$query_vendor = "SELECT * FROM vendor_detail WHERE vendor_code = '".$row_rs["vendor_no"]."'";
	$rs_vendor = mysqli_query($dbc, $query_vendor);   //run the query.
	$row_vendor = mysqli_fetch_array($rs_vendor);   //how many material are there?	
					                
    ?>
  

  <table class="table">  
  
  <?php if($row_rs >  0 )
 
 {  ?> 
  <tr>
    <td width="20%"><?php echo $filename2; ?></td>
    <td width="20%"><?php echo $row_rs["doc_tp"]; ?></td>
    <td width="20%"><?php echo $row_rs["material_no"]; ?></td>
    <td width="10%"><?php echo $row_rs["qty_ftp"]; ?></td>
    <td width="10%"><?php echo $row_vendor["search_term"]; ?></td>
    <td width="10%"><?php echo $row_rs["R3"];  ?></td>
    <td width="10%"><?php echo $row_rs["posting_time"];  ?></td>
  </tr>
  <?php  }
 
 ?>
  </table>
 <?php  
 
   } // end while loop
   mysqli_free_result($rs); 
   
   
    //--------cancel------------------
	$query_cancel = "SELECT *,DATE_FORMAT(date_create,'%d-%m-%Y %H:%i:%s') AS R8, DATE_FORMAT(posting_date,'%d-%m-%Y') AS R7 FROM ftp_ret_cancel_subcont WHERE status_ftp = 'Y' AND file_name = '".$filename2."'";
	$rs_cancel = mysqli_query($dbc, $query_cancel);   //run the query.
	
	while($row_rs_cancel = mysqli_fetch_array($rs_cancel))
	
	{
		
		//---check material type in table material--------
		
	$query_mat_type_c = "SELECT * FROM table_material WHERE material_no = '".$row_rs_cancel["material_no"]."'";
	$rs_mat_type_c = mysqli_query($dbc, $query_mat_type_c);   //run the query.
	$row_mat_type_c = mysqli_fetch_array($rs_mat_type_c);   //how many material are there?
	
		//---check vendor in table vendor_detail--------
		
	$query_vendor_cancel = "SELECT * FROM vendor_detail WHERE vendor_code = '".$row_rs_cancel["vendor_no"]."'";
	$rs_vendor_cancel = mysqli_query($dbc, $query_vendor_cancel);   //run the query.
	$row_vendor_cancel = mysqli_fetch_array($rs_vendor_cancel);   //how many material are there?		
	
	?>
    
  <table class="table">  
 <?php
 if($row_rs_cancel >  0 )
 
 {  ?>
   <tr>
    <td width="20%"><?php  echo $filename2; ?></td>
    <td width="20%"><?php  echo $row_rs_cancel["doc_tp"]; ?></td>
    <td width="20%"><?php  echo $row_rs_cancel["material_no"]; ?></td>
    <td width="10%"><?php  echo $row_rs_cancel["qty_ftp"]; ?></td>
    <td width="10%"><?php  echo $row_vendor_cancel["search_term"]; ?></td>
    <td width="10%"><?php  echo $row_rs_cancel["R7"];  ?></td>
    <td width="10%"><?php  echo $row_rs_cancel["posting_time"];  ?></td>
  </tr>

<?php  
 }
    ?>         
</table>                
   <?php  
   
	}  // while loop
	
	mysqli_free_result($rs_cancel); 
       ?>
</td>
</tr></table> 
               
   <?php      
   
   
             }             
	
	
    }
	
    //closedir($dh);
  }
}




?>	



          </div>
        
      </div>
    </div>
  </div>
</div>



<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part--> 

<script src="../js/jquery.min.js"></script> 
<script src="../js/jquery.ui.custom.js"></script> 
<script src="../js/bootstrap.min.js"></script> 
<script src="../js/jquery.uniform.js"></script> 
<script src="../js/select2.min.js"></script> 
<script src="../js/jquery.dataTables.min.js"></script> 
<script src="../js/matrix.js"></script> 
<script src="../js/matrix.tables.js"></script>
</body>
</html>
