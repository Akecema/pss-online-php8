<?php
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
$url = "list_ftp_pending_sap.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
    $result2 = mysql_query($query2) or die (mysql_error());
    $res = mysql_fetch_array($result2);
	
//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysql_query($query_setup);   //run the query.
$num_setup = mysql_num_rows($rs_setup);   //how many material are there?
$data_setup = mysql_fetch_array($rs_setup);
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
$sta_res = mysql_query($sta);
$rst_sta = mysql_fetch_array($sta_res);

//CR status (Released)
$sta2 = "SELECT * from request_status WHERE status_id = '2' ";
$sta_res2 = mysql_query($sta2);
$rst_sta2 = mysql_fetch_array($sta_res2);	

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7' ";
$sta_res7 = mysql_query($sta7);
$rst_sta7 = mysql_fetch_array($sta_res7);	
	
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
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

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
  <div id="breadcrumb"> <a href="index_ppc.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a><a href="#" class="#">FTP Monitoring</a> <a href="#" class="current">Backflush FTP Monitor</a></div>
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
        <h3>Pending Update SAP</h3>
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


/*
$root = $_SERVER['DOCUMENT_ROOT'];
$path = "/PSS_Online/FromPortal/"; 

// Open the folder
 $dir_handle = @opendir($root . $path) or die("Unable to open $path");

$dir = "$root/PSS_Online/FromPortal/";

$folder = '../../PSS_Online/FromPortal/';
$filetype = '*.*';    
$files = glob($folder.$filetype);    
$total = count($files); */
// Open a directory, and read its contents
if(is_dir($dir)){
  if($dh = opendir($dir)){
  $total2 = count($dh);  
  
   echo "<br>";
   echo "<font color='blue'>TOTAL FILES : ".$total." </font>"; echo "<br>";
   ?>
   <table class="table">
   <tr>
    <td width="20%">File Name</td> 
    <td width="20%">Planned Order</td>
    <td width="20%">Material No.</td>
    <td width="10%">Qty FTP</td>
    <td width="10%">Material Type</td>
    <td width="10%">Posting Date</td>
    <td width="10%">Posting Time</td>
   </tr>
   </table>
   
   <?php
      while (($file = readdir($dh)) !== false){
	
	            clearstatcache();
                if(is_file($dir."/".$file)) {    
				 $path_parts2 = pathinfo($file);
                 $filename2 = $path_parts2['filename']; 
				 
				 
		//---check filename from table ftp backflush --------
		
	$query = "SELECT *,DATE_FORMAT(date_create,'%d-%m-%Y %H:%i:%s') AS R2, DATE_FORMAT(posting_date,'%d-%m-%Y') AS R3 FROM ftp_bflush_detail WHERE status_ftp = 'Y' AND file_name = '".$filename2."'";
	$rs = mysql_query($query);   //run the query.
	$row_rs = mysql_fetch_array($rs);   //how many material are there?
				 
		//---check material type in table material--------
		
	$query_mat_type = "SELECT * FROM table_material WHERE material_no = '".$row_rs["material_no"]."'";
	$rs_mat_type = mysql_query($query_mat_type);   //run the query.
	$row_mat_type = mysql_fetch_array($rs_mat_type);   //how many material are there?			 
				                
    ?>
   <table class="table">
   <tr>
    <td width="20%"><?php echo $filename2; ?></td>
    <td width="20%"><?php echo $row_rs["plan_no"]; ?></td>
    <td width="20%"><?php echo $row_rs["material_no"]; ?></td>
    <td width="10%"><?php echo $row_rs["qty_ftp"]; ?></td>
    <td width="10%"><?php echo $row_mat_type["mat_type"]; ?></td>
    <td width="20%"><?php echo $row_rs["R3"];  ?></td>
    <td width="20%"><?php echo $row_rs["posting_time"];  ?></td>
   </tr>
   </table>
<?php  
             
                 /*   echo $filename2;
                    echo " - ";  echo "&nbsp;&nbsp;";                  
                    echo "Date modified: " . date ("d-m-Y H:i:s", filemtime(utf8_decode($dir."/".$file)));
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
