<?php

/**
 * ppc/left_ppc.php
 * Part of: PPC module (Production Planning & Control)
 * Filename suggests: left ppc
 *
 * Behavior: requires an active login session ($_SESSION['username']).
 * Database tables referenced: user_detail, level_detail, consumable_request.
 * Includes: config.php, paginator.class2.php, tc_calendar.php.
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
require_role($dbc, 4);
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
    $result2 = mysqli_query($dbc, $query2) or die(db_fail($dbc));
    $res = mysqli_fetch_array($result2);
	
	
   $query = "SELECT * from level_detail WHERE id_level = '".db_esc($dbc, $res['level_id'])."'";
   $result = mysqli_query($dbc, $query); // Run the query
   $deb = mysqli_fetch_array($result);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Ingress Autoventures Co., Ltd.</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../css/style.css" type="text/css" media="all" />
<link rel="stylesheet" href="../css/left_menu.css" type="text/css" media="all" />
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type='text/javascript' src='js/jquery.cookie.js'></script>
<script type='text/javascript' src='js/jquery.hoverIntent.minified.js'></script>
<script type='text/javascript' src='js/jquery.dcjqaccordion.2.7.min.js'></script>
<script type="text/javascript">
$(document).ready(function($){
					$('#accordion-1').dcAccordion({
						eventType: 'click',
						autoClose: true,
						saveState: true,
						disableLink: true,
						speed: 'slow',
						showCount: true,
						autoExpand: true,
						cookie	: 'dcjq-accordion-1',
						classExpand	 : 'dcjq-current-parent'
					});
					$('#accordion-2').dcAccordion({
						eventType: 'click',
						autoClose: false,
						saveState: true,
						disableLink: true,
						speed: 'fast',
						classActive: 'test',
						showCount: true
					});
					$('#accordion-3').dcAccordion({
						eventType: 'click',
						autoClose: false,
						saveState: false,
						disableLink: false,
						showCount: false,
						speed: 'slow'
					});
					$('#accordion-4').dcAccordion({
						eventType: 'hover',
						autoClose: true,
						saveState: true,
						disableLink: true,
						menuClose: false,
						speed: 'slow',
						showCount: true
					});
					$('#accordion-5').dcAccordion({
						eventType: 'hover',
						autoClose: false,
						saveState: true,
						disableLink: true,
						menuClose: true,
						speed: 'fast',
						showCount: true
					});
					$('#accordion-6').dcAccordion({
						eventType: 'hover',
						autoClose: false,
						saveState: false,
						disableLink: false,
						showCount: false,
						menuClose: true,
						speed: 'slow'
					});
});
</script>
<script langauge="javascript" type="text/javascript"> 
<!-- 

function reloadFrame() { 
        self.location.reload(); 
} 

--> 

</script> 
<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}

#nav {
    float: left;
    width: 240px;
    border-top: 1px solid #999;
    border-right: 1px solid #999;
    border-left: 1px solid #999;
}
#nav li a {
    display: block;
    padding: 10px 15px;
    background: #ccc;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #999;
    text-decoration: none;
    color: #000;
}
#nav li a:hover, #nav li a.active {
    background: #999;
    color: #fff;
}
#nav li ul {
    display: none; // used to hide sub-menus
}
#nav li ul li a {
    padding: 10px 25px;
    background: #ececec;
    border-bottom: 1px dotted #ccc;
}
-->
</style>
<script>
$(document).ready(function () {
  $('#nav > li > a').click(function(){
    if ($(this).attr('class') != 'active'){
      $('#nav li ul').slideUp();
      $(this).next().slideToggle();
      $('#nav li a').removeClass('active');
      $(this).addClass('active');
    }
  });
});

</script>
</head>
<!--<body  onload="setTimeout('reloadFrame()',5000);" > --> 
<body>
<p>
  <!-- Header --><!-- End Header -->
</p>
<table width="23%" border="0" cellspacing="5">
  <tr>
    <td><table width="238" border="0" cellpadding="0" cellspacing="1">
      <tr>
        <td><div align="left">User :</div></td>
        <td><div align="left"><?php echo h($res['user_fullname']); ?> </div></td>
      </tr>
      <tr>
        <td colspan="2"><div align="right"></div></td>
      </tr>
      <tr>
        <td width="44" class="Tcontent"><div align="left">Level :</div></td>
        <td width="171" class="Tcontent"><?php echo h($deb["desc_level"]); ?> </td>
      </tr>
    </table>
    
    <p>&nbsp;</p>
<p>
<div id = "nav" >
<ul class="accordion" id="accordion-1">
<li><a href="main.php" target="mainFrame">Home</a></p></li>
<li class="dcjq-current-parent"><a href="#">Board</a>
<ul>
<li><a href="posting_request_all_screen_LCD.php" target="mainFrame"><img src="../images/monitor.png" />&nbsp;Material Request Board</a></li>
<li><a href="posting_request_all_screen_LCD_consumable.php" target="mainFrame" ><img src="../images/monitor.png" />&nbsp;Consumable Request Board</a></li>
</ul>
</li>

<li class="dcjq-current-parent"><a href="#">Material Request</a>
<ul>
<li><a href="posting_request_all.php" target="mainFrame"><img src="../images/display_icon.png" />&nbsp;Material Request</a></li>
<li><a href="report_PPC.php" target="mainFrame"><img src="../images/report.png" />&nbsp;Material Request Report</a></li>
<li><a href="posting_request_scan_+_urgent.php" target="mainFrame" ><img src="../images/traffic_lights.png" />&nbsp;Display Material Request</a></li>
<li><a href="posting_request_history.php" target="mainFrame"><img src="../images/history_icon.png" />&nbsp;Material Request History</a></li> 
<li><a href="material_request_analysis.php" target="mainFrame"><img src="../images/piechart.png" />&nbsp;Material Request Analysis</a></li>
</ul>
</li>

<li class="dcjq-current-parent"><a href="#">Consumable Request</a>
    <ul> <?php
	  
	  $query = "SELECT *, DATE_FORMAT(MR.date_require,'%d-%m-%Y') AS R FROM consumable_request AS MR WHERE MR.status_request = 'Y' AND MR.status_view = 'N' AND (MR.status != 'Close' AND MR.status  != 'Cancel') GROUP BY MR.temp_mrin";
$rs = mysqli_query($dbc, $query);   //run the query.
	    $num_rows = mysqli_num_rows($rs); 

	  ?>
    

      <li><a href="display_consumable_request.php" target="mainFrame"><img src="../images/display_icon.png" />&nbsp; Consumable Request <font color="#0000FF">[<?php  echo $num_rows; ?>] </font>
      </a></li>
      <li><a href="report_PPC_consumable.php" target="mainFrame"><img src="../images/report.png" />&nbsp;Consumable Request Report</a></li>
    <li><a href="posting_request_consumable.php" target="mainFrame" ><img src="../images/traffic_lights.png" />&nbsp;Display Consumable Request</a></li>
      <li><a href="history_consumable_request.php" target="mainFrame"><img src="../images/history_icon.png" />&nbsp; Consumable Request History</a></li>
      <li><a href="consumable_request_analysis.php" target="mainFrame"><img src="../images/piechart.png" />&nbsp;Consumable Request Analysis</a></li>
     </ul>
  </li>
<li><a href="TP_manual_upload.php" target="mainFrame">Uploaded MIGO TR</a></li>
<li><a href="manual_upload_file.php" target="mainFrame">Manually Upload File Transfer (TP)</a></li>
<li><a href="../logout.php" target="_parent">Logout</a></li>
</ul>
</div>  
  
    </td>
  </tr>
</table>


</body>
</html>
