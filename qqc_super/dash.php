<?php

/**
 * qqc_super/dash.php
 * Part of: QQC module (supervisor/admin tier)
 * Filename suggests: dash
 *
 * Behavior: requires an active login session ($_SESSION['username']).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, login_detail, qqc_detail_transaction, reject_detail_disposal, pps_detail.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_qqc_super_menu.php, footer.php.
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
require_role($dbc, 11);
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

//CR status (Release)
$sta2 = "SELECT * from request_status WHERE status_id = '2'";
$sta_res2 = mysqli_query($dbc, $sta2);
$rst_sta2 = mysqli_fetch_array($sta_res2);

//CR status (Approved)
$sta3 = "SELECT * from request_status WHERE status_id = '3'";
$sta_res3 = mysqli_query($dbc, $sta3);
$rst_sta3 = mysqli_fetch_array($sta_res3);

//CR status (In Progress)
$sta7 = "SELECT * from request_status WHERE status_id = '7'";
$sta_res7 = mysqli_query($dbc, $sta7);
$rst_sta7 = mysqli_fetch_array($sta_res7);

//CR status (Pending)
$sta8 = "SELECT * from request_status WHERE status_id = '8'";
$sta_res8 = mysqli_query($dbc, $sta8);
$rst_sta8 = mysqli_fetch_array($sta_res8);
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

 /* $query_sql = "SELECT * FROM login_detail WHERE username = '$username' and status = 'AC'";
   $result_sql = mysqli_query($dbc, $query_sql);
   $info = mysqli_fetch_array($result_sql);
    
 
    if(($info['status_pass'] == 'N'))
         {*/
	?>
   
    <!--<script type="text/javascript">
jQuery(document).ready(function ($) {
    $.fancybox({
        href: "backjob_initial_pass.php?username=<?php echo $username; ?>",
        type: "iframe" // <-- whatever content image, inline, swf, etc
    });
}); // ready
</script>-->
   
    <?php
	/* }
	  elseif(($info['expired_pass_date'] <=  $currentdate )) 
	  {*/

	?>
    
   <!-- <script type="text/javascript">
jQuery(document).ready(function ($) {
    $.fancybox({
        href: "backjob_reminder_pass.php?username=<?php echo $username; ?>",
        type: "iframe" // <-- whatever content image, inline, swf, etc
    });
}); // ready
</script>-->
  
   
    <?php
	// }
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
$query_in_progress = "SELECT COUNT(DISTINCT MR.plan_no) AS cnt FROM qqc_detail_transaction AS MR, pps_detail AS SD WHERE MR.plan_no = SD.plan_no AND MR.status_QC = '".db_esc($dbc, $rst_sta8["status_desc"])."' AND (SD.status_pps != 'Closed' AND SD.status_pps != 'Cancel')";
$rs_in_progress = mysqli_query($dbc, $query_in_progress);   //run the query.
$num_in_progress = mysqli_fetch_assoc($rs_in_progress)['cnt'];   //how many material are there?

// approval disposal for QQC
$query_con_req = "SELECT COUNT(DISTINCT doc_disposal_no) AS cnt FROM reject_detail_disposal WHERE status_disposal = '".db_esc($dbc, $rst_sta["status_desc"])."' AND (status_part = 'QC' OR status_part = 'WQ') AND doc_disposal_no != ''";
$rs_con_req = mysqli_query($dbc, $query_con_req);   //run the query.
$num_con_req = mysqli_fetch_assoc($rs_con_req)['cnt'];   //how many material are there?

 // Dead code removed: "release planned order" query ($num_plan_req) was
 // computed here but its only display line further down has the echo
 // itself PHP-commented out (the "echo" is prefixed with // inside that
 // PHP tag), so the query ran on every page load and the result was
 // never used.

//pending approval disposal for Prod
$query_disposal_req = "SELECT COUNT(DISTINCT doc_disposal_no) AS cnt FROM reject_detail_disposal WHERE status_disposal = '".db_esc($dbc, $rst_sta3["status_desc"])."' AND (status_part = 'PR' OR status_part = 'WS') AND doc_disposal_no != ''";
$rs_disposal_req = mysqli_query($dbc, $query_disposal_req);   //run the query.
$num_disposal_req = mysqli_fetch_assoc($rs_disposal_req)['cnt'];   //how many material are there?


?>
<!--Action boxes-->
  <div class="container-fluid">
    <div class="quick-actions_homepage">
       <ul class="quick-actions">
        <li class="bg_lb"> <a href="document_list_qc_tran.php"> <i class="icon-exchange"></i> <span class="label label-important"><?php echo $num_in_progress;   ?></span> In Progress<br> &nbsp;&nbsp;</a> </li>
        <li class="bg_ly"> <a href="disposal_production_tran_NG.php"> <i class="icon-bar-chart"></i><span class="label label-important"><?php echo $num_disposal_req;   ?></span>Pending <br> Production Disposal</a> </li>
       <!-- <li class="bg_lv"> <a href="document_list_qc_tran.php"> <i class="icon-th"></i><span class="label label-important"><?php //echo $num_plan_req;   ?></span> Released Planned Order</a> </li>-->
        <li class="bg_ls"> <a href="disposal_qc_tran_NG.php"> <i class="icon-check"></i><span class="label label-important"><?php echo $num_con_req;  ?></span> Pending&nbsp;&nbsp; <br>QC Disposal &nbsp;&nbsp;</a> </li>
        
       
        </ul>
    </div>
<!--End-Action boxes-->    


    <hr/>
    <div class="row-fluid">
      <div class="span6">
        <?php
		//Progress QA/QC Request
		
		$total_month = 0.00;
		$percent_open = 0.00;
		$percent_close = 0.00;
		$percent_cancel = 0.00;
		
		//1. - status "Pending" (same query as $query_in_progress above — reuse instead of re-running)
	$num_mat_prog_open = $num_in_progress;


		//2.  - status QC OK
	$query_mat_prog_close = "SELECT COUNT(DISTINCT MR.plan_no) AS cnt FROM qqc_detail_transaction AS MR, pps_detail AS SD WHERE MR.plan_no = SD.plan_no AND MR.status_QC = 'QC OK'";
	$rs_mat_prog_close = mysqli_query($dbc, $query_mat_prog_close);
	$num_mat_prog_close = mysqli_fetch_assoc($rs_mat_prog_close)['cnt'];
		
	
	//-------calculation percentage--------------------------
	if(($num_mat_prog_open > 0) || ($num_mat_prog_close > 0))
	{
	
	$total_month = 	($num_mat_prog_open + $num_mat_prog_close);
	
	$percent_open =  (($num_mat_prog_open / $total_month) * 100);
	$percent_open = number_format($percent_open, 2);
	
	$percent_close =  (($num_mat_prog_close / $total_month) * 100);
	$percent_close = number_format($percent_close, 2);
	
	}else{
		
		
		$percent_open = 0;
		$percent_close = 0;
		
	}
	
				
		?>
                
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-ok"></i></span>
            <h5>Progress QA/QC</h5>
          </div>
          <div class="widget-content">
            <ul class="unstyled">
              <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span>Pending <span class="pull-right strong"><?php echo $num_mat_prog_open;   ?></span>
                <div class="progress progress-striped ">
                  <div style="width: <?php  echo  $percent_open;  ?>%;" class="bar"></div>
                </div>
              </li>
              <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span>QC OK <span class="pull-right strong"><?php echo $num_mat_prog_close;   ?></span>
                <div class="progress progress-success progress-striped ">
                  <div style="width: <?php  echo  $percent_close;  ?>%;" class="bar"></div>
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
