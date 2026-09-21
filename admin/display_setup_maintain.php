<?php

/**
 * admin/display_setup_maintain.php
 * Part of: Admin module
 * Filename suggests: display setup maintain
 *
 * Behavior: requires an active login session ($_SESSION['username']).
 * Database tables referenced: user_detail, sys_setup_maintain.
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
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$url = "setup_maintain_add.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
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
  <div id="breadcrumb"><a href="index_admin.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Setup System</a>  </div>
  <h1>Setup System</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
    <!--  <div class="span12">-->
        <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li><a  role="tab" href="setup_maintain_add.php">Add Setup</a></li>
              <li class="active"><a role="tab" href="display_setup_maintain.php">Display Setup</a></li>
            </ul>
          </div>
          </div>
                  
           <?php



								 
   $query8 = "SELECT COUNT(*) FROM sys_setup_maintain ORDER BY id_setup ASC";
   $result8 = mysqli_query($dbc, $query8) or die(mysqli_error($dbc));
   $num_rows = mysqli_fetch_row($result8);

   $pages = new Paginator;
   $pages->items_total = $num_rows[0];
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT * FROM sys_setup_maintain ORDER BY id_setup ASC";
$rs = mysqli_query($dbc, $query);   //run the query.
$num = mysqli_num_rows($rs);   //how many material are there?


	
	 if ($num > 0) {
	 
	 echo '<div align="center">There are currently  '. $num_rows[0].' record(s).</div>';
	 }
	

?>
          
          
      <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display Setup</h5>
          </div>
             
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                <th>No.</th>
                <th>Title Description</th>
                <th>File Name</th>
                <th>URLs</th>
                <th>Status</th>
                <th>FTP IP</th>
                </tr>
              </thead>   
              <tbody>
           <?php
   
   $counter = 1;
   $no = 1;
   
   while ($row = mysqli_fetch_array($rs))
   {
		
		$user_no = $row[0]; 
	
  
	  if($row[12] == "AC")
	  {
	     $sts = "Active";
		 }
		 else{
		 $sts = "Inactive";
		 }

	 
      ?>
           
                <tr class="gradeX">
                <td width="30"><?php echo $no; ?></td>
                <td><a href="#"><?php echo $row[1]; ?></a></td>
                <td width="150"><?php echo $row[2]; ?></td>
                <td width="200" height="28"><?php  echo $row[4]; ?></td>
                <td width="100"><font color="#FF0000"><?php echo $sts; ?></font></td>
                <td><?php echo $row[7]; ?></td>
                </tr>
          
          <?php 
		  
		  $no ++;
		  $counter++; // menambah counter
		  } ?>
                
              
              </tbody>
            </table>
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
