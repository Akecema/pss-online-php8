<?php

/**
 * ppc_store/dash.php
 * Part of: PPC Store module
 * Filename suggests: dash
 *
 * Behavior: requires an active login session ($_SESSION['username']).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, tp_store_detail, tp_store_cancel, tp_plb_detail, tp_plb_cancel, ret_plb_detail, tp_subcont_detail, tp_subcont_cancel.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_ppc_menu.php, footer.php.
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
require_role($dbc, 12);
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

//CR status (Cancelled)
$sta4 = "SELECT * from request_status WHERE status_id = '4'";
$sta_res4 = mysqli_query($dbc, $sta4);
$rst_sta4 = mysqli_fetch_array($sta_res4);

//CR status (Transfer Posting)
$sta19 = "SELECT * from request_status WHERE status_id = '19'";
$sta_res19 = mysqli_query($dbc, $sta19);
$rst_sta19 = mysqli_fetch_array($sta_res19);	

//CR status (Return Posting)
$sta20 = "SELECT * from request_status WHERE status_id = '20'";
$sta_res20 = mysqli_query($dbc, $sta20);
$rst_sta20 = mysqli_fetch_array($sta_res20);	

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
<?php include "left_ppc_menu.php";  ?>
<!--sidebar-menu-->

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index_ppc_store.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->
<?php 
 
 // ------------------------------  display dashboard ------------------------
 //TP to store
$query_tp_store = "SELECT COUNT(DISTINCT doc_tp) AS cnt FROM tp_store_detail WHERE status_tran = 'Y' AND status_tp = ?"; $query_tp_store_args = [$rst_sta19["status_desc"]];
$rs_tp_store= db_query_bind($dbc, $query_tp_store, $query_tp_store_args);   //run the query.
$num_tp_store = mysqli_fetch_assoc($rs_tp_store)['cnt'];   //how many record are there?

 //Cancel TP to store
$query_cancel_tp_store = "SELECT COUNT(DISTINCT doc_tp) AS cnt FROM tp_store_cancel WHERE status_tran = 'Y' AND status_tp = ?"; $query_cancel_tp_store_args = [$rst_sta4["status_desc"]];
$rs_cancel_tp_store= db_query_bind($dbc, $query_cancel_tp_store, $query_cancel_tp_store_args);   //run the query.
$num_cancel_tp_store = mysqli_fetch_assoc($rs_cancel_tp_store)['cnt'];   //how many record are there?

//TP to PLB
$query_tp_plb = "SELECT COUNT(DISTINCT doc_tp) AS cnt FROM tp_plb_detail WHERE status_tran = 'Y' AND status_tp = ?"; $query_tp_plb_args = [$rst_sta19["status_desc"]];
$rs_tp_plb = db_query_bind($dbc, $query_tp_plb, $query_tp_plb_args);   //run the query.
$num_tp_plb = mysqli_fetch_assoc($rs_tp_plb)['cnt'];   //how many rrecord are there?

//Cancel TP to PLB
$query_cancel_tp_plb = "SELECT COUNT(DISTINCT doc_tp) AS cnt FROM tp_plb_cancel WHERE status_tran = 'Y' AND status_tp = ?"; $query_cancel_tp_plb_args = [$rst_sta4["status_desc"]];
$rs_cancel_tp_plb = db_query_bind($dbc, $query_cancel_tp_plb, $query_cancel_tp_plb_args);   //run the query.
$num_cancel_tp_plb = mysqli_fetch_assoc($rs_cancel_tp_plb)['cnt'];   //how many rrecord are there?

//Return from PLB
$query_ret_plb = "SELECT COUNT(DISTINCT doc_tp) AS cnt FROM ret_plb_detail WHERE status_tran = 'Y' AND status_tp = ?"; $query_ret_plb_args = [$rst_sta20["status_desc"]];
$rs_ret_plb = db_query_bind($dbc, $query_ret_plb, $query_ret_plb_args);   //run the query.
$num_ret_plb = mysqli_fetch_assoc($rs_ret_plb)['cnt'];   //how many rrecord are there?

//TP to Subcont
$query_tp_subcont = "SELECT COUNT(DISTINCT doc_tp) AS cnt FROM tp_subcont_detail WHERE status_tran = 'Y' AND status_tp = ?"; $query_tp_subcont_args = [$rst_sta19["status_desc"]];
$rs_tp_subcont = db_query_bind($dbc, $query_tp_subcont, $query_tp_subcont_args);   //run the query.
$num_tp_subcont = mysqli_fetch_assoc($rs_tp_subcont)['cnt'];   //how many rrecord are there?

//Cancel TP to Subcont
$query_cancel_tp_subcont = "SELECT COUNT(DISTINCT doc_tp) AS cnt FROM tp_subcont_cancel WHERE status_tran = 'Y' AND status_tp = ?"; $query_cancel_tp_subcont_args = [$rst_sta4["status_desc"]];
$rs_cancel_tp_subcont = db_query_bind($dbc, $query_cancel_tp_subcont, $query_cancel_tp_subcont_args);   //run the query.
$num_cancel_tp_subcont = mysqli_fetch_assoc($rs_cancel_tp_subcont)['cnt'];   //how many rrecord are there?


//Return from Subcont
$query_ret_subcont = "SELECT COUNT(DISTINCT doc_tp) AS cnt FROM ret_subcont_detail WHERE status_tran = 'Y' AND status_tp = ?"; $query_ret_subcont_args = [$rst_sta20["status_desc"]];
$rs_ret_subcont = db_query_bind($dbc, $query_ret_subcont, $query_ret_subcont_args);   //run the query.
$num_ret_subcont = mysqli_fetch_assoc($rs_ret_subcont)['cnt'];   //how many rrecord are there?

?>

<!--Action boxes-->
  <div class="container-fluid">
    <div class="quick-actions_homepage">
       <ul class="quick-actions">
        <li class="bg_lb"> <a href="trans_posting_to_store.php"> <i class="icon-arrow-right"></i><span class="label label-important"><?php echo $num_tp_store;   ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TP to Store&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a> </li>
        <li class="bg_lv"> <a href="trans_posting_to_plb.php"> <i class="icon-arrow-right"></i><span class="label label-important"><?php echo $num_tp_plb;   ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TP to PLB &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a> </li>
        <li class="bg_ly"> <a href="trans_posting_to_subcont.php"> <i class="icon-arrow-right"></i><span class="label label-important"><?php echo $num_tp_subcont;   ?></span>TP to Subcont</a> </li>
         <li class="bg_lv"> <a href="return_posting_to_plb.php"> <i class="icon-exchange"></i><span class="label label-important"><?php echo $num_ret_plb;   ?></span>&nbsp;&nbsp;&nbsp;&nbsp;Return from PLB&nbsp;&nbsp;&nbsp;&nbsp;</a> </li>
           <li class="bg_ly"> <a href="return_posting_to_subcont.php"> <i class="icon-exchange"></i><span class="label label-important"><?php echo $num_ret_subcont;   ?></span>Return from Subcont</a> </li>
             <li class="bg_lo"> <a href="cancel_trans_posting_to_store.php"> <i class="icon-remove-sign"></i><span class="label label-important"><?php echo $num_cancel_tp_store;   ?></span>&nbsp;&nbsp;Cancel TP to Store&nbsp;&nbsp;</a> </li>
               <li class="bg_lo"> <a href="cancel_trans_posting_to_plb.php"> <i class="icon-remove-sign"></i><span class="label label-important"><?php echo $num_cancel_tp_plb;   ?></span>&nbsp;&nbsp;Cancel TP to PLB&nbsp;&nbsp;</a> </li>
                 <li class="bg_lo"> <a href="cancel_trans_posting_to_subcont.php"> <i class="icon-remove-sign"></i><span class="label label-important"><?php echo $num_cancel_tp_subcont;   ?></span>Cancel TP to Subcont</a> </li>
       </ul>
    </div>
<!--End-Action boxes-->    


    <hr/>
    <div class="row-fluid">
      <div class="span6">
       
        
        
          <!--<div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-ok"></i></span>
            <h5>Progress WIP Request</h5>
          </div>
          <div class="widget-content">
            <ul class="unstyled">
              <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span> Open<span class="pull-right strong">567</span>
                <div class="progress progress-striped ">
                  <div style="width: 81%;" class="bar"></div>
                </div>
              </li>
              <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span> Close <span class="pull-right strong">507</span>
                <div class="progress progress-success progress-striped ">
                  <div style="width: 72%;" class="bar"></div>
                </div>
              </li>
              <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span> Cancel<span class="pull-right strong">8</span>
                <div class="progress progress-danger progress-striped ">
                  <div style="width: 3%;" class="bar"></div>
                </div>
              </li>
            </ul>
          </div>
        </div>-->
             
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
