<?php
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");
$currentdate = (date("Y-m-d"));

set_time_limit(0);

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
    $result2 = mysql_query($query2) or die (mysql_error());
    $res = mysql_fetch_array($result2);
	
	$url = "index_qqc_super.php"; 
	
//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysql_query($query_setup);   //run the query.
$num_setup = mysql_num_rows($rs_setup);   //how many material are there?
$data_setup = mysql_fetch_array($rs_setup);
//----------------------------------------------------	

//CR status (New)
$sta = "SELECT * from request_status WHERE status_id = '1'";
$sta_res = mysql_query($sta);
$rst_sta = mysql_fetch_array($sta_res);

//CR status (Release)
$sta2 = "SELECT * from request_status WHERE status_id = '2'";
$sta_res2 = mysql_query($sta2);
$rst_sta2 = mysql_fetch_array($sta_res2);

//CR status (Approved)
$sta3 = "SELECT * from request_status WHERE status_id = '3'";
$sta_res3 = mysql_query($sta3);
$rst_sta3 = mysql_fetch_array($sta_res3);

//CR status (In Progress)
$sta7 = "SELECT * from request_status WHERE status_id = '7'";
$sta_res7 = mysql_query($sta7);
$rst_sta7 = mysql_fetch_array($sta_res7);

//CR status (Pending)
$sta8 = "SELECT * from request_status WHERE status_id = '8'";
$sta_res8 = mysql_query($sta8);
$rst_sta8 = mysql_fetch_array($sta_res8);
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
<link rel="stylesheet" href="../css/fullcalendar.css" />
<link rel="stylesheet" href="../css/matrix-style.css" />
<link rel="stylesheet" href="../css/matrix-media.css" />
<link href="../font-awesome/css/font-awesome.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/jquery.gritter.css" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

   <!-- Add jQuery basic library -->
<script type="text/javascript" src="../fancybox/jquery-lib.js"></script>
		
<!-- Add required fancyBox files -->
<link rel="stylesheet" href="../fancybox/fancybox/source/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="../fancybox/fancybox/source/jquery.fancybox.pack.js"></script>

<!-- Optional, Add fancyBox for media, buttons, thumbs -->
<link rel="stylesheet" href="../fancybox/fancybox/source/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
<script type="text/javascript" src="../fancybox/fancybox/source/helpers/jquery.fancybox-buttons.js"></script>
<script type="text/javascript" src="../fancybox/fancybox/source/helpers/jquery.fancybox-media.js"></script>
<link rel="stylesheet" href="../fancybox/fancybox/source/helpers/jquery.fancybox-thumbs.css" type="text/css" media="screen" />
<script type="text/javascript" src="../fancybox/fancybox/source/helpers/jquery.fancybox-thumbs.js"></script>

<?php

  $query_sql = "SELECT * FROM login_detail WHERE username = '$username' and status = 'AC'";
   $result_sql = mysql_query($query_sql);
   $info = mysql_fetch_array($result_sql);
    
 
    if(($info['status_pass'] == 'N'))
         {
	?>
   
    <script type="text/javascript">
jQuery(document).ready(function ($) {
    $.fancybox({
        href: "backjob_initial_pass.php?username=<?php echo $username; ?>",
        type: "iframe" // <-- whatever content image, inline, swf, etc
    });
}); // ready
</script>
   
    <?php
	 }
	  elseif(($info['expired_pass_date'] <=  $currentdate )) 
	  {

	?>
    
    <script type="text/javascript">
jQuery(document).ready(function ($) {
    $.fancybox({
        href: "backjob_reminder_pass.php?username=<?php echo $username; ?>",
        type: "iframe" // <-- whatever content image, inline, swf, etc
    });
}); // ready
</script>
  
   
    <?php
	 }
	 ?>
</head>

<body>

<!--Header-part-->
<div id="header">
  <h1>MRIN Online</h1>
</div>
<!--close-Header-part--> 

<!----- start modal ------------------------------------------->
<?php  include "top_modal_menu.php";   ?>
 <!-----------end modal ---------------------------------------->           
   
<!--sidebar-menu-->
<?php include "left_qqc_super_menu.php";  ?>
<!--sidebar-menu-->

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index_qqc_super.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->
<?php 
 
 // ------------------------------  display dashboard ------------------------
 //status pps in progress
$query_in_progress = "SELECT *, DATE_FORMAT(MR.date_plan,'%d-%m-%Y') AS R, DATE_FORMAT(MR.date_qc_posting,'%d-%m-%Y') AS R2 FROM qqc_detail_transaction AS MR, pps_detail AS SD WHERE MR.plan_no = SD.plan_no AND MR.status_QC = '".$rst_sta8["status_desc"]."' AND (SD.status_pps != 'Closed' AND SD.status_pps != 'Cancel') GROUP BY MR.plan_no";
$rs_in_progress = mysql_query($query_in_progress);   //run the query.
$num_in_progress = mysql_num_rows($rs_in_progress);   //how many material are there?

// approval disposal for QQC
$query_con_req = "SELECT * FROM reject_detail_disposal WHERE status_disposal = '".$rst_sta["status_desc"]."' AND (status_part = 'QC' OR status_part = 'WQ') AND doc_disposal_no != '' GROUP BY doc_disposal_no ORDER BY date_posting DESC";
$rs_con_req = mysql_query($query_con_req);   //run the query.
$num_con_req = mysql_num_rows($rs_con_req);   //how many material are there?

 //release planned order
$query_plan_req = "SELECT * FROM pps_detail WHERE status_pps = '".$rst_sta2["status_desc"]."' ORDER BY date_plan DESC";
$rs_plan_req = mysql_query($query_plan_req);   //run the query.
$num_plan_req = mysql_num_rows($rs_plan_req);   //how many material are there?

//pending approval disposal for Prod
$query_disposal_req = "SELECT * FROM reject_detail_disposal WHERE status_disposal = '".$rst_sta3["status_desc"]."' AND (status_part = 'PR' OR status_part = 'WS') AND doc_disposal_no != '' GROUP BY doc_disposal_no ORDER BY date_plan DESC";
$rs_disposal_req = mysql_query($query_disposal_req);   //run the query.
$num_disposal_req = mysql_num_rows($rs_disposal_req);   //how many material are there?


?>
<!--Action boxes-->
  <div class="container-fluid"><!--End-Action boxes-->    

	<img src="../img/ban1.png">
    <hr/>
  </div>
</div>

<!--end-main-container-part-->

<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part--> 

<script src="../js/excanvas.min.js"></script> 
<script src="../js/jquery.min.js"></script> 
<script src="../js/jquery.ui.custom.js"></script> 
<script src="../js/bootstrap.min.js"></script> 
<script src="../js/jquery.flot.min.js"></script> 
<script src="../js/jquery.flot.resize.min.js"></script> 
<script src="../js/jquery.peity.min.js"></script> 
<script src="../js/fullcalendar.min.js"></script> 
<script src="../js/matrix.js"></script> 
<script src="../js/matrix.dashboard.js"></script> 
<script src="../js/jquery.gritter.min.js"></script> 
<script src="../js/matrix.interface.js"></script> 
<script src="../js/matrix.chat.js"></script> 
<script src="../js/jquery.validate.js"></script> 
<script src="../js/matrix.form_validation.js"></script> 
<script src="../js/jquery.wizard.js"></script> 
<script src="../js/jquery.uniform.js"></script> 
<script src="../js/select2.min.js"></script> 
<script src="../js/matrix.popover.js"></script> 
<script src="../js/jquery.dataTables.min.js"></script> 
<script src="../js/matrix.tables.js"></script> 

<script type="text/javascript">
  // This function is called from the pop-up menus to transfer to
  // a different page. Ignore if the value returned is a null string:
  function goPage (newURL) {

      // if url is empty, skip the menu dividers and reset the menu selection to default
      if (newURL != "") {
      
          // if url is "-", it is this page -- reset the menu:
          if (newURL == "-" ) {
              resetMenu();            
          } 
          // else, send page to designated URL            
          else {  
            document.location.href = newURL;
          }
      }
  }

// resets the menu selection upon entry to this page:
function resetMenu() {
   document.gomenu.selector.selectedIndex = 2;
}
</script>
</body>
</html>
