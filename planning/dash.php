<?php

/**
 * planning/dash.php
 * Part of: Planning module
 * Filename suggests: dash
 *
 * Behavior: requires an active login session ($_SESSION['username']).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, login_detail, pps_detail_transaction, pps_detail.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_planning_menu.php, footer.php.
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
require_role($dbc, 8);
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

    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
    $result2 = mysqli_query($dbc, $query2) or die(db_fail($dbc));
    $res = mysqli_fetch_array($result2);
	
	$url = "dash.php"; 
	
//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------	

//CR status (New)

$sta = "SELECT * from request_status WHERE status_id = '1'";
$sta_res = mysqli_query($dbc, $sta);
$rst_sta = mysqli_fetch_array($sta_res);

//CR status (Released)
$sta2 = "SELECT * from request_status WHERE status_id = '2'";
$sta_res2 = mysqli_query($dbc, $sta2);
$rst_sta2 = mysqli_fetch_array($sta_res2);

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7'";
$sta_res7 = mysqli_query($dbc, $sta7);
$rst_sta7 = mysqli_fetch_array($sta_res7);	

//CR status (Closed)
$sta13 = "SELECT * from request_status WHERE status_id = '13'";
$sta_res13 = mysqli_query($dbc, $sta13);
$rst_sta13 = mysqli_fetch_array($sta_res13);

//CR status (Completed)
$sta14 = "SELECT * from request_status WHERE status_id = '14'";
$sta_res14 = mysqli_query($dbc, $sta14);
$rst_sta14 = mysqli_fetch_array($sta_res14);	

//CR status (Transfer QC)
$sta18 = "SELECT * from request_status WHERE status_id = '18'";
$sta_res18 = mysqli_query($dbc, $sta18);
$rst_sta18 = mysqli_fetch_array($sta_res18);
	
	?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $data_setup["title_desc"]; ?></title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="shortcut icon" href="../img/favicon.ico">
<link rel="stylesheet" href="../css/bootstrap.min.css" />
<link rel="stylesheet" href="../css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="../css/fullcalendar.css" />
<link rel="stylesheet" href="../css/matrix-style.css" />
<link rel="stylesheet" href="../css/matrix-media.css" />
<link href="../font-awesome/css/font-awesome.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/jquery.gritter.css" />
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

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

 /* $query_sql = "SELECT * FROM login_detail WHERE username = '$username' and status = 'AC'";
   $result_sql = mysqli_query($dbc, $query_sql);
   $info = mysqli_fetch_array($result_sql);
    
 
    if(($info['status_pass'] == 'N'))
         {*/
	?>
   <!--
    <script type="text/javascript">
jQuery(document).ready(function ($) {
    $.fancybox({
        href: "backjob_initial_pass.php?username=<?php echo $username; ?>",
        type: "iframe" // <-- whatever content image, inline, swf, etc
    });
}); // ready
</script>-->
   
    <?php
	 /*}
	  elseif(($info['expired_pass_date'] <=  $currentdate )) 
	  {*/

	?>
    
    <!--<script type="text/javascript">
jQuery(document).ready(function ($) {
    $.fancybox({
        href: "backjob_reminder_pass.php?username=<?php echo $username; ?>",
        type: "iframe" // <-- whatever content image, inline, swf, etc
    });
}); // ready
</script>-->
  
   
    <?php
	 //}
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
<?php include "left_planning_menu.php";  ?>
<!--sidebar-menu-->

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index_planning.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->
<?php 
 
 // ------------------------------  display dashboard ------------------------
 //new material request 
$query_mat_req = "SELECT COUNT(DISTINCT plan_no) AS cnt FROM pps_detail_transaction WHERE status_pps = '".db_esc($dbc, $rst_sta7["status_desc"])."' AND status = 'Y' AND qty_actual != ''";
$rs_mat_req = mysqli_query($dbc, $query_mat_req);   //run the query.
$num_mat_req = mysqli_fetch_assoc($rs_mat_req)['cnt'];   //how many material are there?

//In Progress Plan Order request
$query_con_req = "SELECT COUNT(*) AS cnt FROM pps_detail WHERE status_pps = '".db_esc($dbc, $rst_sta2["status_desc"])."'";
$rs_con_req = mysqli_query($dbc, $query_con_req);   //run the query.
$num_con_req = mysqli_fetch_assoc($rs_con_req)['cnt'];   //how many material are there?

 //in progress planned order
$query_plan_req = "SELECT COUNT(*) AS cnt FROM pps_detail WHERE status_pps = '".db_esc($dbc, $rst_sta18["status_desc"])."'";
$rs_plan_req = mysqli_query($dbc, $query_plan_req);   //run the query.
$num_plan_req = mysqli_fetch_assoc($rs_plan_req)['cnt'];   //how many material are there?

?>
<!--Action boxes-->
  <div class="container-fluid">
    <div class="quick-actions_homepage">
       <ul class="quick-actions">
        <li class="bg_lb"> <a href="inprogress_plan_order_list.php"> <i class="icon-retweet"></i> <span class="label label-important"><?php echo $num_mat_req;   ?></span> In Progress Planning No.</a> </li>
        <li class="bg_ls"> <a href="release_plan_order_list.php"> <i class="icon-list-ul"></i><span class="label label-important"><?php echo $num_con_req;   ?></span> Released PPS</a> </li>
         <li class="bg_lo"> <a href="technical_complete_tran.php"> <i class="icon-lock"></i><span class="label label-important"><?php echo $num_plan_req;  ?></span> Pending PPS Close</a> </li>
        </ul>
    </div>
<!--End-Action boxes-->    


    <hr/>
    <div class="row-fluid">
      <div class="span6">
        <?php
		//Progress Planning
		
		$total_month = 0.00;
		$percent_open = 0.00;
		$percent_close = 0.00;
		$percent_cancel = 0.00;
		
		//1. - Released Plan Order (same query as $query_con_req above — reuse instead of re-running)
	$num_mat_prog_open = $num_con_req;


		//2.  - In Progress Plan Order
	$query_mat_prog_close = "SELECT COUNT(*) AS cnt FROM pps_detail_transaction WHERE status_pps = '".db_esc($dbc, $rst_sta7["status_desc"])."' AND status = 'Y' AND qty_actual != ''";
	$rs_mat_prog_close = mysqli_query($dbc, $query_mat_prog_close);
	$num_mat_prog_close = mysqli_fetch_assoc($rs_mat_prog_close)['cnt'];


		//3. - Closed Plan Order

	$query_mat_prog_cancel = "SELECT COUNT(*) AS cnt FROM pps_detail WHERE status_pps = '".db_esc($dbc, $rst_sta13["status_desc"])."'";
	$rs_mat_prog_cancel = mysqli_query($dbc, $query_mat_prog_cancel);
	$num_mat_prog_cancel = mysqli_fetch_assoc($rs_mat_prog_cancel)['cnt'];
	
	
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
            <h5>Progress Planning       </h5>
          </div>
          <div class="widget-content">
            <ul class="unstyled">
              <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span>Released <span class="pull-right strong"><?php echo $num_mat_prog_open;   ?></span>
                <div class="progress progress-striped ">
                  <div style="width: <?php  echo  $percent_open;  ?>%;" class="bar"></div>
                </div>
              </li>
              <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span>InProgress<span class="pull-right strong"><?php echo $num_mat_prog_close;   ?></span>
                <div class="progress progress-success progress-striped ">
                  <div style="width: <?php  echo  $percent_close;  ?>%;" class="bar"></div>
                </div>
              </li>
              <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span>Closed <span class="pull-right strong"><?php echo $num_mat_prog_cancel;   ?></span>
                <div class="progress progress-danger progress-striped ">
                  <div style="width: <?php  echo  $percent_cancel;  ?>%;" class="bar"></div>
                </div>
              </li>
            </ul>
          </div>
        </div>
        
      
      </div>
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
