<?php

/**
 * admin/display_user2.php
 * Part of: Admin module
 * Filename suggests: display user2
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: user_detail, sys_setup_maintain, supplier_detail, level_detail.
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
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$url = "add_user.php";

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
  <div id="breadcrumb"> <a href="index_admin.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">User Maintenance</a> <a href="#" class="current">Display User</a> </div>
  <h1>User Maintenance</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
    <!--  <div class="span12">-->
        <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li><a  role="tab" href="add_user.php">Add User</a></li>
              <li class="active"><a role="tab" href="display_user.php">Display User</a></li>
              <li><a role="tab" href="reset_password_user.php">Reset Password</a></li>
            </ul>
          </div>
          </div>
          
          
       <form name="frmSearch" method="get" action="<?=$_SERVER['SCRIPT_NAME'];?>">
              <table class="table table-bordered table-striped">
                <tr>
                  <th>User Id /Name
                      <input name="txtKeyword" type="text" id="txtKeyword" size="60">
                      <input type="submit" value="Search" class="btn btn-info">
                  </th>
                </tr>
              </table>
            </form>

          
           <?php


$query8 = "SELECT COUNT(*) FROM user_detail where (staff_ID LIKE '%".$_GET["txtKeyword"]."%' or user_fullname LIKE '%".$_GET["txtKeyword"]."%' ) ORDER BY user_no ASC";

$result8 = mysqli_query($dbc, $query8)or die(mysqli_error($dbc));
$num_rows = mysqli_fetch_row($result8);

$pages = new Paginator;
$pages->items_total = $num_rows[0];
$pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
$pages->paginate();
 

$msg="";
$msg2="";

if(isset($_GET['txtKeyword']) != "")
{
	
	$query = "SELECT * FROM user_detail WHERE (staff_ID LIKE '%".$_GET["txtKeyword"]."%' or user_fullname LIKE '%".$_GET["txtKeyword"]."%' )";
  
	//$query = "SELECT * FROM supplier_detail where (level_id != '1' or level_id != '2') and (vendor_no LIKE '%".$_GET["txtKeyword"]."%' or user_name LIKE '%".$_GET["txtKeyword"]."%' )";
	
	$rs = mysqli_query($dbc, $query)or die ("Error Query [".$query."]");  //run the query.
	$num = mysqli_num_rows($rs);   //how many material are there?

	
	$query .="order by user_no ASC $pages->limit";
	$rs   = mysqli_query($dbc, $query);
	

	   if ($rs > 0) 
  {
	 
	 echo '<div align="center">There are currently'. $num_rows[0].' record(s).</div>';

?>
          
          
      <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display Account</h5>
          </div>
             
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Staff ID</th>
                <th>Status</th>
                <th>Options</th>
                <th>Options</th>
                <th>Options</th>
                </tr>
              </thead>   
              <tbody>
           <?php
   
   $counter = 1;
   $no = 1;
   
   while ($row = mysqli_fetch_array($rs))
   {
		
		$user_no = $row[0]; 
	
  
	  if($row[15] == "AC")
	  {
	     $sts = "Active";
		 }
		 else{
		 $sts = "Inactive";
		 }
		 
	  $query4_p = "SELECT * from level_detail as LD, user_detail as SD where SD.level_id = LD.id_level and LD.id_level = '.$row[16].'";
  $result4_p = mysqli_query($dbc, $query4_p);
  $row4_p = mysqli_fetch_array($result4_p);
	 
      ?>
           
                <tr class="gradeX">
                <td width="80"><?php echo $no; ?></td>
                <td><?php echo $row[5]; ?></td>
                <td width="96" height="28"><?php echo $row[2]; ?></td>
                <!--<td width="124" height="28"><?php  echo $row4_p["desc_level"]; ?></td>-->
                <td width="140"><font color="#FF0000"><?php echo $sts; ?></font></td>
                <td width="100"><a value="Details" href="detail_user.php?user_no=<?php echo $user_no; ?>&&TB_iframe=true&height=400&width=650" class="thickbox" target="_self"><img src="../img/icon_view.jpg" width="16" height="16" alt="View">View</a></td>
                <td width="100">&nbsp;<a value="Edit" href="user_edit.php?user_no=<?php echo $user_no; ?>&&TB_iframe=true&height=400&width=650" class="thickbox" target="_self"> <img src="../img/edit.gif" width="16" height="16" alt="Edit">Edit</a></td>
                 <?php
				 if($row[20] == "Y")
	  {
				?>
                 <td width="100">&nbsp; <a value="Unlock Password" href="unlock_pass_account.php?user_no=<?php echo $user_no; ?>&&TB_iframe=true&height=400&width=650" class="thickbox" target="_self"> <img src="../img/lock3.png" width="20" height="20" alt="Unlock Password">Unlock</a></td>
                 <?php
	  }else{
		 
				 ?>
             <td width="100">&nbsp;&nbsp;<img src="../img/unlock3.png" width="20" height="20" alt="Unlock Password"></td>		 
				<?php
	  }
	  ?>
                </tr>
                 <input name="user_no" type="hidden" value="<?php echo $user_no; ?>">
          <?php 
		  
		  $no ++;
		  
		  $counter++; // menambah counter
		  } ?>
    <?php
   mysqli_free_result($rs); 
   ?>            
              
              </tbody>
            </table>
            <?php
	}   // free up the resources 
else
{
?>
<table width="800" cellspacing="0" class="textboxred">
  <tr> 
    <td><div align="center"><font color="#FF0000"><strong>There are currently 
          no registered user</strong></font></div></td>
  </tr>
</table>
        <?php
		   } 
		   }
//mysqli_close($dbc)
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
