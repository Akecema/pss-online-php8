<?php
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");
$currentdate = (date("Y-m-d"));

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
    $result2 = mysql_query($query2) or die (mysql_error());
    $res = mysql_fetch_array($result2);
	
	$url = "index_supply.php"; 
	
	//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysql_query($query_setup);   //run the query.
$num_setup = mysql_num_rows($rs_setup);   //how many material are there?
$data_setup = mysql_fetch_array($rs_setup);
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
<?php include "left_supply_menu.php";  ?>
<!--sidebar-menu-->

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index_supply.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->
<?php 
 
 // ------------------------------  display dashboard ------------------------
 //new wip material request 
$query_mat_req = "SELECT *, DATE_FORMAT(MR.date_mrin,'%d-%m-%Y') AS R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R2 FROM wip_request AS MR, scan_detail_wip AS SD WHERE MR.id_scan_wip = SD.id_scan AND MR.status_request = 'Y' AND (MR.status != 'Close' AND MR.status != 'Cancel') GROUP BY MR.temp_mrin_wip ORDER BY MR.date_mrin DESC,MR.time_mrin DESC";
$rs_mat_req = mysql_query($query_mat_req);   //run the query.
$num_mat_req = mysql_num_rows($rs_mat_req);   //how many material are there?




?>
<!--Action boxes-->
  <div class="container-fluid">
    <div class="quick-actions_homepage">
       <ul class="quick-actions">
        <li class="bg_ly"> <a href="posting_request_all_screen_LCD_WIP.php"> <i class="icon-bar-chart"></i> <span class="label label-important"><?php echo $num_mat_req;   ?></span> Open WIP Request </a> </li>
        
       </ul>
    </div>
<!--End-Action boxes-->    


    <hr/>
    <div class="row-fluid">
      <div class="span6">
        <?php
		//Progress Material Request
		
		$total_month = 0.00;
		$percent_open = 0.00;
		$percent_close = 0.00;
		$percent_cancel = 0.00;
		
		//1. - open
	$query_mat_prog_open = "SELECT * FROM wip_request AS MR, scan_detail_wip AS SD WHERE MR.id_scan_wip = SD.id_scan AND MR.status_request = 'Y' AND (MR.status != 'Close' AND MR.status != 'Cancel') GROUP BY MR.temp_mrin_wip ORDER BY MR.date_mrin DESC,MR.time_mrin DESC";
	$rs_mat_prog_open = mysql_query($query_mat_prog_open);   
	$num_mat_prog_open = mysql_num_rows($rs_mat_prog_open);   	
		
		
		//2.  - close
	$query_mat_prog_close = "SELECT * FROM wip_request AS MR, scan_detail_wip AS SD WHERE MR.id_scan_wip = SD.id_scan AND MR.status_request = 'Y' AND (MR.status != 'New' AND MR.status != 'Cancel') GROUP BY MR.temp_mrin_wip ORDER BY MR.date_mrin DESC,MR.time_mrin DESC";
	$rs_mat_prog_close = mysql_query($query_mat_prog_close);   
	$num_mat_prog_close = mysql_num_rows($rs_mat_prog_close);   		
		
		
		//3. - cancel
		
	$query_mat_prog_cancel = "SELECT * FROM wip_request AS MR, scan_detail_wip AS SD WHERE MR.id_scan_wip = SD.id_scan AND MR.status_request = 'Y' AND (MR.status = 'Cancel') GROUP BY MR.temp_mrin_wip ORDER BY MR.date_mrin DESC,MR.time_mrin DESC";
	$rs_mat_prog_cancel = mysql_query($query_mat_prog_cancel);   
	$num_mat_prog_cancel = mysql_num_rows($rs_mat_prog_cancel);  
	
	
	//-------calculation percentage--------------------------
	if(($num_mat_prog_open > 0) || ($num_mat_prog_close > 0) || ($num_mat_prog_cancel > 0))
	{	
	$total_month = 	($num_mat_prog_open + $num_mat_prog_close + $num_mat_prog_cancel);
	
	$percent_open =  (($num_mat_prog_open / $total_month) * 100);
	$percent_open = number_format($percent_open, 2);
	
	$percent_close =  (($num_mat_prog_close / $total_month) * 100);
	$percent_close = number_format($percent_close, 2);
	
	$percent_cancel =  (($num_mat_prog_cancel / $total_month) * 100);
	$percent_cancel = number_format($percent_cancel, 2);
				
     }else{
		
		
		$percent_open = 0;
		$percent_close = 0;
		$percent_cancel = 0;
		
	}
		?>
                
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-ok"></i></span>
            <h5>Progress WIP Request       </h5>
          </div>
          <div class="widget-content">
            <ul class="unstyled">
              <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span>Open <span class="pull-right strong"><?php echo $num_mat_prog_open;   ?></span>
                <div class="progress progress-striped ">
                  <div style="width: <?php  echo  $percent_open;  ?>%;" class="bar"></div>
                </div>
              </li>
              <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span>Close <span class="pull-right strong"><?php echo $num_mat_prog_close;   ?></span>
                <div class="progress progress-success progress-striped ">
                  <div style="width: <?php  echo  $percent_close;  ?>%;" class="bar"></div>
                </div>
              </li>
              <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span>Cancel <span class="pull-right strong"><?php echo $num_mat_prog_cancel;   ?></span>
                <div class="progress progress-danger progress-striped ">
                  <div style="width: <?php  echo  $percent_cancel;  ?>%;" class="bar"></div>
                </div>
              </li>
            </ul>
          </div>
        </div>
             
      </div>
     <!--  <div class="span6">

       <div class="widget-box">
          <div class="widget-title"><span class="icon"><i class="icon-user"></i></span>
            <h5>Our Partner (Box with Fix height)</h5>
          </div>
          <div class="widget-content nopadding fix_hgt">
            <ul class="recent-posts">
              <li>
                <div class="user-thumb"> <img width="40" height="40" alt="User" src="img/demo/av1.jpg"> </div>
                <div class="article-post"> <span class="user-info">John Deo</span>
                  <p>Web Desginer &amp; creative Front end developer</p>
                </div>
              </li>
              <li>
                <div class="user-thumb"> <img width="40" height="40" alt="User" src="img/demo/av2.jpg"> </div>
                <div class="article-post"> <span class="user-info">John Deo</span>
                  <p>Web Desginer &amp; creative Front end developer</p>
                </div>
              </li>
              <li>
                <div class="user-thumb"> <img width="40" height="40" alt="User" src="img/demo/av4.jpg"> </div>
                <div class="article-post"> <span class="user-info">John Deo</span>
                  <p>Web Desginer &amp; creative Front end developer</p>
                </div>
            </ul>
          </div>
        </div>
   

       
      </div>-->
    </div>
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
