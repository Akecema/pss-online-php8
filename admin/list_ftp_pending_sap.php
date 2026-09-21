<?php

/**
 * admin/list_ftp_pending_sap.php
 * Part of: Admin module
 * Filename suggests: list ftp pending sap
 *
 * Behavior: requires an active login session ($_SESSION['username']).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, ftp_bflush_detail, table_material.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_admin_menu.php, footer.php.
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
require_role($dbc, 1);
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");

$Cdate = date ("l, j F Y ");
set_time_limit(0);

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$url = "list_ftp_pending_sap.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
    $result2 = mysqli_query($dbc, $query2) or die(db_fail($dbc));
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
<?php include "left_admin_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_admin.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a><a href="#" class="#">FTP Monitoring</a> <a href="#" class="current">Backflush FTP Monitor</a></div>
  <h1>Backflush FTP Monitor</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
  
          
      <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display File</h5>
          </div>
             
          <div class="widget-content nopadding">
        <h3>Pending Update SAP Backflush</h3>
      -------------------------------------------------------
<?php
		
	    //date_default_timezone_set('Asia/Bangkok');
		//date_default_timezone_set('Asia/Kuala Lumpur');
		

$root = $_SERVER['DOCUMENT_ROOT'];
$path = "../../PSS_Online/FromPortal/"; 

// Open the folder
 $dir_handle = @opendir($root . $path) or die("Unable to open $path");

$dir = "$root/FromPortal/";

$folder = '../../PSS_Online/FromPortal/';
$filetype = '*.*';    
$files = glob($folder.$filetype);    
$total = count($files);    

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
    <td width="20%">Material No.</td>
    <td width="20%">Qty FTP</td>
    <td width="20%">Material Type</td>
    <td width="20%">Date Modified</td>
   </tr>
   </table>
   
   <?php
      while (($file = readdir($dh)) !== false){
	
	            clearstatcache();
                if(is_file($dir."/".$file)) {    
				 $path_parts2 = pathinfo($file);
                 $filename2 = $path_parts2['filename']; 
				 
				 
		//---check filename from table ftp backflush --------
		
	$query = "SELECT *,DATE_FORMAT(posting_date,'%d-%m-%Y') AS R2 FROM ftp_bflush_detail WHERE status_ftp = 'Y' AND file_name = '".db_esc($dbc, $filename2)."'";
	$rs = mysqli_query($dbc, $query);   //run the query.
	$row_rs = mysqli_fetch_array($rs);   //how many material are there?
				 
		//---check material type in table material--------
		
	$query_mat_type = "SELECT * FROM table_material WHERE material_no = '".db_esc($dbc, $row_rs["material_no"])."'";
	$rs_mat_type = mysqli_query($dbc, $query_mat_type);   //run the query.
	$row_mat_type = mysqli_fetch_array($rs_mat_type);   //how many material are there?			 
				                
    ?>
   <table class="table">
  <tr>
    <td width="20%"><?php echo $filename2; ?></td>
    <td width="20%"><?php echo $row_rs["material_no"]; ?></td>
    <td width="20%"><?php echo $row_rs["qty_ftp"]; ?></td>
    <td width="20%"><?php echo $row_mat_type["mat_type"]; ?></td>
    <td width="20%"><?php echo date ("d-m-Y H:i:s", filemtime(utf8_to_latin1($dir."/".$file))); ?></td>
  </tr>
</table>
<?php  
             
                 /*   echo $filename2;
                    echo " - ";  echo "&nbsp;&nbsp;";                  
                    echo "Date modified: " . date ("d-m-Y H:i:s", filemtime(utf8_to_latin1($dir."/".$file)));
                    echo "<br>";    */
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
