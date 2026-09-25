<?php

/**
 * prod/dash.php
 * Part of: Production module
 * Filename suggests: dash
 *
 * Behavior: requires an active login session ($_SESSION['username']).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, login_detail, material_request, consumable_request, pps_detail, reject_detail_disposal, pps_detail_transaction, wip_request.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_production_menu.php, footer.php.
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
require_role($dbc, 2);
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

    $query2 = "SELECT * FROM user_detail WHERE username = ?"; $query2_args = [$username];
    $result2 = db_query_bind($dbc, $query2, $query2_args) or die(db_fail($dbc));
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

//CR status (Completed)
$sta14 = "SELECT * from request_status WHERE status_id = '14'";
$sta_res14 = mysqli_query($dbc, $sta14);
$rst_sta14 = mysqli_fetch_array($sta_res14);

//CR status (Deleted)
$sta16 = "SELECT * from request_status WHERE status_id = '16'";
$sta_res16 = mysqli_query($dbc, $sta16);
$rst_sta16 = mysqli_fetch_array($sta_res16);

	?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo h($data_setup["title_desc"]); ?></title>
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
   
   <!-- <script type="text/javascript">
jQuery(document).ready(function ($) {
    $.fancybox({
        href: "backjob_initial_pass.php?username=<?php echo h($username); ?>",
        type: "iframe" // <-- whatever content image, inline, swf, etc
    });
}); // ready
</script>-->
   
    <?php
	/* }
	  elseif(($info['expired_pass_date'] <=  $currentdate )) 
	  {
*/
	?>
    
    <!--<script type="text/javascript">
jQuery(document).ready(function ($) {
    $.fancybox({
        href: "backjob_reminder_pass.php?username=<?php echo h($username); ?>",
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
<?php include "left_production_menu.php";  ?>
<!--sidebar-menu-->

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index_production.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->
<?php 
 
 // ------------------------------  display dashboard ------------------------
 //new material request
 // 2026-08-10 perf fix: these 6 queries were "SELECT *"/"SELECT *, ..." with
 // the result only ever used for mysqli_num_rows() (confirmed via grep - no
 // other code reads a row from these result sets). Converted to COUNT()/
 // COUNT(DISTINCT ...) so MySQL returns one number instead of materializing
 // and transmitting every matching row. The GROUP BY queries become
 // COUNT(DISTINCT <the GROUP BY column>), which counts the same number of
 // distinct groups the original GROUP BY + num_rows did.
$query_mat_req = "SELECT COUNT(DISTINCT MR.temp_mrin) AS cnt FROM material_request AS MR, scan_detail AS SD WHERE MR.id_scan = SD.id_scan AND MR.status_request = 'Y' AND (MR.status != 'Close' AND MR.status != 'Cancel')";
$rs_mat_req = mysqli_query($dbc, $query_mat_req);   //run the query.
$num_mat_req = mysqli_fetch_assoc($rs_mat_req)['cnt'];   //how many material are there?

//new consumable request
$query_con_req = "SELECT COUNT(DISTINCT MR.temp_mrin) AS cnt FROM consumable_request AS MR WHERE MR.status_request = 'Y' AND (MR.status != 'Close' AND MR.status  != 'Cancel') AND MR.status_print != 'Y'";
$rs_con_req = mysqli_query($dbc, $query_con_req);   //run the query.
$num_con_req = mysqli_fetch_assoc($rs_con_req)['cnt'];   //how many material are there?

 //in progress planned order
$query_plan_req = "SELECT COUNT(*) AS cnt FROM pps_detail WHERE status_pps = ?"; $query_plan_req_args = [$rst_sta7["status_desc"]];
$rs_plan_req = db_query_bind($dbc, $query_plan_req, $query_plan_req_args);   //run the query.
$num_plan_req = mysqli_fetch_assoc($rs_plan_req)['cnt'];   //how many material are there?


 //release planned order
$query_release_req = "SELECT COUNT(*) AS cnt FROM pps_detail WHERE status_pps = ?"; $query_release_req_args = [$rst_sta2["status_desc"]];
$rs_release_req = db_query_bind($dbc, $query_release_req, $query_release_req_args);   //run the query.
$num_release_req = mysqli_fetch_assoc($rs_release_req)['cnt'];   //how many material are there?


 //pending approval disposal
$query_disposal_req = "SELECT COUNT(DISTINCT doc_disposal_no) AS cnt FROM reject_detail_disposal WHERE status_disposal = ? AND (status_part = 'PR' OR status_part = 'WS') AND doc_disposal_no != ''"; $query_disposal_req_args = [$rst_sta["status_desc"]];
$rs_disposal_req = db_query_bind($dbc, $query_disposal_req, $query_disposal_req_args);   //run the query.
$num_disposal_req = mysqli_fetch_assoc($rs_disposal_req)['cnt'];   //how many material are there?


// approved disposal
$query_disposal_req_app = "SELECT COUNT(DISTINCT doc_disposal_no) AS cnt FROM reject_detail_disposal WHERE status_disposal != ? AND status_disposal != ? AND (status_part = 'PR' OR status_part = 'WS') AND doc_disposal_no != ''"; $query_disposal_req_app_args = [$rst_sta["status_desc"], $rst_sta16["status_desc"]];
$rs_disposal_req_app = db_query_bind($dbc, $query_disposal_req_app, $query_disposal_req_app_args);   //run the query.
$num_disposal_req_app = mysqli_fetch_assoc($rs_disposal_req_app)['cnt'];   //how many material are there?

?>
<!--Action boxes-->
  <div class="container-fluid">
    <div class="quick-actions_homepage">
       <ul class="quick-actions">
        <li class="bg_ls"> <a href="posting_request_all_screen_LCD.php"> <i class="icon-barcode"></i> <span class="label label-important"><?php echo h($num_mat_req);   ?></span> Open Material <br> Request </a> </li>
        <li class="bg_lb"> <a href="posting_request_all_screen_LCD_consumable.php"> <i class="icon-barcode"></i><span class="label label-important"><?php echo h($num_con_req);   ?></span> Open Consumable  <br>Request</a> </li>
        <li class="bg_ly"> <a href="technical_complete_tran.php"> <i class="icon-bar-chart"></i><span class="label label-important"><?php echo h($num_plan_req);   ?></span>In Progress <br>Planned Order</a> </li>
        <li class="bg_lv"> <a href="technical_complete_tran.php"> <i class="icon-th"></i><span class="label label-important"><?php echo h($num_release_req);   ?></span> Released <br> Planned Order</a> </li>
        <li class="bg_lo"> <a href="disposal_backflush_tran_NG.php"> <i class="icon-th"></i> <span class="label label-important"><?php echo h($num_disposal_req);   ?></span>Pending Approval <br> Disposal</a> </li>
        <li class="bg_lg"> <a href="disposal_backflush_tran_NG_approved.php"> <i class="icon-check"></i> <span class="label label-important"><?php echo h($num_disposal_req_app); ?></span>Approved Disposal<br>&nbsp;</a> </li>
       </ul>
    </div>
<!--End-Action boxes-->    


    <hr/>
    <div class="row-fluid">
      <div class="span6">
     <?php
		//Progress Production
		
		$total_month_q = 0.00;
		$percent_open_q = 0.00;
		$percent_close_q = 0.00;
		$percent_cancel_q = 0.00;
		
		//1. - status "In Progress"
		// 2026-08-10 perf fix: was "SELECT *" + mysqli_num_rows(), which had
		// MySQL materialize and transmit all ~248k matching rows just to be
		// counted (measured at 9.3 seconds - the dominant cost of this whole
		// page). No GROUP BY in the original query, so COUNT(*) is exactly
		// equivalent and lets MySQL return a single number instead.
	$query_mat_prog_open_q = "SELECT COUNT(*) AS cnt FROM pps_detail_transaction AS MR, pps_detail AS SD WHERE MR.plan_no = SD.plan_no AND MR.status_pps = ? AND (SD.status_pps != 'Closed' AND SD.status_pps != 'Cancel')"; $query_mat_prog_open_q_args = [$rst_sta7["status_desc"]];
	$rs_mat_prog_open_q = db_query_bind($dbc, $query_mat_prog_open_q, $query_mat_prog_open_q_args);
	$num_mat_prog_open_q = mysqli_fetch_assoc($rs_mat_prog_open_q)['cnt'];


		//2.  - status Completed (same fix as above)
	$query_mat_prog_close_q = "SELECT COUNT(*) AS cnt FROM pps_detail_transaction AS MR, pps_detail AS SD WHERE MR.plan_no = SD.plan_no AND MR.status_pps = ?"; $query_mat_prog_close_q_args = [$rst_sta14["status_desc"]];
	$rs_mat_prog_close_q = db_query_bind($dbc, $query_mat_prog_close_q, $query_mat_prog_close_q_args);
	$num_mat_prog_close_q = mysqli_fetch_assoc($rs_mat_prog_close_q)['cnt'];

	
	//-------calculation percentage--------------------------
	
	if(($num_mat_prog_open_q > 0) || ($num_mat_prog_close_q > 0))
	{
	
	$total_month_q = 	($num_mat_prog_open_q + $num_mat_prog_close_q);
	
	$percent_open_q =  ($total_month_q != 0 ? (($num_mat_prog_open_q / $total_month_q) * 100) : 0);
	$percent_open_q = number_format($percent_open_q, 2);
	
	$percent_close_q =  ($total_month_q != 0 ? (($num_mat_prog_close_q / $total_month_q) * 100) : 0);
	$percent_close_q = number_format($percent_close_q, 2);
	
	}else{
		
		
		$num_mat_prog_open_q = 0;
		$num_mat_prog_close_q = 0;
		
		$percent_open_q = 0;
		$percent_close_q = 0;
		
		
	}
		?>
                
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-ok"></i></span>
            <h5>Progress Production</h5>
          </div>
          <div class="widget-content">
            <ul class="unstyled">
             
              <li><span class="icon24 icomoon-icon-arrow-up-2 green"></span> In Progress<span class="pull-right strong"><?php echo h($num_mat_prog_open_q);   ?></span>
                <div class="progress progress-striped ">
                  <div style="width: <?php  echo  h($percent_open_q);  ?>%;" class="bar"></div>
                </div>
              </li>
              <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span>Completed<span class="pull-right strong"><?php echo h($num_mat_prog_close_q);   ?></span>
                <div class="progress progress-success progress-striped ">
                  <div style="width: <?php  echo  h($percent_close_q);  ?>%;" class="bar"></div>
                </div>
              </li>
              </ul>
          </div>
        </div>
      
        <?php
		//Progress Material Request
		
		$total_month = 0.00;
		$percent_open = 0.00;
		$percent_close = 0.00;
		$percent_cancel = 0.00;
		
		//1. - open (same query/result as $num_mat_req above - "Open Material
		// Request" badge and this progress bar show the identical open-request
		// count, so it's reused instead of re-running the same expensive
		// material_request x scan_detail join+group-by a second time)
	$num_mat_prog_open = $num_mat_req;


		//2.  - close (2026-08-10 perf fix: COUNT(DISTINCT ...) instead of
		// SELECT * + GROUP BY + num_rows - see comment above $query_mat_req)
	$query_mat_prog_close = "SELECT COUNT(DISTINCT MR.temp_mrin) AS cnt FROM material_request AS MR, scan_detail AS SD WHERE MR.id_scan = SD.id_scan AND MR.status_request = 'Y' AND (MR.status != 'New' AND MR.status != 'Cancel')";
	$rs_mat_prog_close = mysqli_query($dbc, $query_mat_prog_close);
	$num_mat_prog_close = mysqli_fetch_assoc($rs_mat_prog_close)['cnt'];


		//3. - cancel (same fix)

	$query_mat_prog_cancel = "SELECT COUNT(DISTINCT MR.temp_mrin) AS cnt FROM material_request AS MR, scan_detail AS SD WHERE MR.id_scan = SD.id_scan AND MR.status_request = 'Y' AND (MR.status = 'Cancel')";
	$rs_mat_prog_cancel = mysqli_query($dbc, $query_mat_prog_cancel);
	$num_mat_prog_cancel = mysqli_fetch_assoc($rs_mat_prog_cancel)['cnt'];
	
	
	//-------calculation percentage--------------------------
	
	if(($num_mat_prog_open > 0) || ($num_mat_prog_close > 0) || ($num_mat_prog_cancel > 0))
	{
	
	$total_month = 	($num_mat_prog_open + $num_mat_prog_close + $num_mat_prog_cancel);
	
	$percent_open =  ($total_month != 0 ? (($num_mat_prog_open / $total_month) * 100) : 0);
	$percent_open = number_format($percent_open, 2);
	
	$percent_close =  ($total_month != 0 ? (($num_mat_prog_close / $total_month) * 100) : 0);
	$percent_close = number_format($percent_close, 2);
	
	$percent_cancel =  ($total_month != 0 ? (($num_mat_prog_cancel / $total_month) * 100) : 0);
	$percent_cancel = number_format($percent_cancel, 2);
	
	}else{
		
		
		$percent_open = 0;
		$percent_close = 0;
		$percent_cancel = 0;
		
	}
				
		?>
                
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-ok"></i></span>
            <h5>Progress Material Request       </h5>
          </div>
          <div class="widget-content">
            <ul class="unstyled">
              <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span>Open <span class="pull-right strong"><?php echo h($num_mat_prog_open);   ?></span>
                <div class="progress progress-striped ">
                  <div style="width: <?php  echo  h($percent_open);  ?>%;" class="bar"></div>
                </div>
              </li>
              <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span>Close <span class="pull-right strong"><?php echo h($num_mat_prog_close);   ?></span>
                <div class="progress progress-success progress-striped ">
                  <div style="width: <?php  echo  h($percent_close);  ?>%;" class="bar"></div>
                </div>
              </li>
              <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span>Cancel <span class="pull-right strong"><?php echo h($num_mat_prog_cancel);   ?></span>
                <div class="progress progress-danger progress-striped ">
                  <div style="width: <?php  echo  h($percent_cancel);  ?>%;" class="bar"></div>
                </div>
              </li>
            </ul>
          </div>
        </div>
        
        <?php
		//Progress Consumable Request
		
		$total_month2 = 0.00;
		$percent_open2 = 0.00;
		$percent_close2 = 0.00;
		$percent_cancel2 = 0.00;
		
		//1. - open (same query/result as $num_con_req above - reused for the
		// same reason as $num_mat_prog_open, see comment there)
	$num_con_prog_open = $num_con_req;


		//2.  - close (2026-08-10 perf fix: COUNT(DISTINCT ...) instead of
		// SELECT * + GROUP BY + num_rows - see comment above $query_mat_req)
	$query_con_prog_close = "SELECT COUNT(DISTINCT MR.temp_mrin) AS cnt FROM consumable_request AS MR WHERE MR.status_request = 'Y' AND (MR.status != 'New' AND MR.status  != 'Cancel') AND MR.status_print != 'Y'";
	$rs_con_prog_close = mysqli_query($dbc, $query_con_prog_close);
	$num_con_prog_close = mysqli_fetch_assoc($rs_con_prog_close)['cnt'];


		//3. - cancel (same fix)

	$query_con_prog_cancel = "SELECT COUNT(DISTINCT MR.temp_mrin) AS cnt FROM consumable_request AS MR WHERE MR.status_request = 'Y' AND (MR.status = 'Cancel') AND MR.status_print != 'Y'";
	$rs_con_prog_cancel = mysqli_query($dbc, $query_con_prog_cancel);
	$num_con_prog_cancel = mysqli_fetch_assoc($rs_con_prog_cancel)['cnt'];
	
	
	//-------calculation percentage--------------------------
	if(($num_con_prog_open > 0) || ($num_con_prog_close > 0) || ($num_con_prog_cancel > 0))
	{
	
	$total_month2 = 	($num_con_prog_open + $num_con_prog_close + $num_con_prog_cancel);
	
	$percent_open2 =  ($total_month2 != 0 ? (($num_con_prog_open / $total_month2) * 100) : 0);
	$percent_open2 = number_format($percent_open2, 2);
	
	$percent_close2 =  ($total_month2 != 0 ? (($num_con_prog_close / $total_month2) * 100) : 0);
	$percent_close2 = number_format($percent_close2, 2);
	
	$percent_cancel2 =  ($total_month2 != 0 ? (($num_con_prog_cancel / $total_month2) * 100) : 0);
	$percent_cancel2 = number_format($percent_cancel2, 2);
	
	}else{
		
		$percent_open2 = 0;
		$percent_close2 = 0;
		$percent_cancel2 = 0;
		
		
	}
				
		?>
        
        
        
          <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-ok"></i></span>
            <h5>Progress Consumable Request</h5>
          </div>
          <div class="widget-content">
            <ul class="unstyled">
              <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span> Open<span class="pull-right strong"><?php echo h($num_con_prog_open);   ?></span>
                <div class="progress progress-striped ">
                  <div style="width: <?php echo h($percent_open2);   ?>%;" class="bar"></div>
                </div>
              </li>
              <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span> Close <span class="pull-right strong"><?php echo h($num_con_prog_close);   ?></span>
                <div class="progress progress-success progress-striped ">
                  <div style="width: <?php echo h($percent_close2);   ?>%;" class="bar"></div>
                </div>
              </li>
             <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span> Cancel <span class="pull-right strong"><?php echo h($num_con_prog_cancel);   ?></span>
                <div class="progress progress-danger progress-striped ">
                  <div style="width: <?php echo h($percent_cancel2);   ?>%;" class="bar"></div>
                </div>
              </li>
            </ul>
          </div>
        </div>
        
        
           <?php
		// "Progress WIP Request" section removed (2026-08-10, performance fix):
		// this used to run 3 more full material_request-style joined/grouped
		// queries (wip_request x scan_detail_wip) on every dashboard load, but
		// the HTML block that would display $num_wip_prog_open/close/cancel and
		// $percent_open3/close3/cancel3 has been entirely commented out below
		// since before this fix - the values were computed and then never used.
		// Confirmed unused anywhere else in this file before removing.
		?>
                
        <!--- <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-ok"></i></span>
            <h5>Progress WIP Request       </h5>
          </div>
          <div class="widget-content">
            <ul class="unstyled">
              <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span>Open <span class="pull-right strong"><?php echo $num_wip_prog_open;   ?></span>
                <div class="progress progress-striped ">
                  <div style="width: <?php  echo  $percent_open3;  ?>%;" class="bar"></div>
                </div>
              </li>
              <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span>Close <span class="pull-right strong"><?php echo $num_wip_prog_close;   ?></span>
                <div class="progress progress-success progress-striped ">
                  <div style="width: <?php  echo  $percent_close3;  ?>%;" class="bar"></div>
                </div>
              </li>
              <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span>Cancel <span class="pull-right strong"><?php echo $num_wip_prog_cancel;   ?></span>
                <div class="progress progress-danger progress-striped ">
                  <div style="width: <?php  echo  $percent_cancel3;  ?>%;" class="bar"></div>
                </div>
              </li>
            </ul>
          </div>
        </div>
             
      </div>  -->
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
