<?php
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

    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
    $result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
    $res = mysqli_fetch_array($result2);
	
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Ingress Autoventures Co., Ltd.</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../css/style.css" type="text/css" media="all" />
<link rel="stylesheet" href="../scripts/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="../javascript/jquery-latest.js"></script> 
<script type="text/javascript" src="../javascript/thickbox.js"></script>	
<style type="text/css">
<!--
.style1 {
	color: #FFFFFF
}
body {
	background-image: url(../images/01.jpg);
}
-->
</style>
<script type="text/javascript">
// Popup window code
function newPopup(url) {
	popupWindow = window.open(
		url,'popUpWindow','height=400,width=650,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes')
}
</script>

</head>

<body>
<!-- Header -->
<div id="header">
  <div class="shell">
    <!-- Logo + Top Nav -->
    <div id="top">
      <h1 class="style1 style3">
     Material Requisition Issue Note Online</h1>
      <!--  <marquee>Scrolling text</marquee> -->
      <div id="top-navigation"> Welcome <strong><?php echo $res['user_fullname']; ?></strong> <span>|</span><img src="../images/help.png" width="16" height="16" /> <a href="#">Help</a> <span>|</span><img src="../images/user.png" width="16" height="16" />  <a href="JavaScript:newPopup('profile_setting.php?user_no=<?php echo $res["user_no"]; ?>');" >Accounts</a> <span>|</span> <img src="../images/exit.png" width="16" height="16" /><a href="../logout.php" target="_parent">Log out</a> </div>
    </div>
    <!-- End Logo + Top Nav -->
    <!-- Main Nav -->
   
    <!-- End Main Nav -->
  </div>
</div>
<!-- End Header -->
<!-- Container --><!-- End Container -->
<!-- Footer -->
<!-- End Footer -->
</body>
</html>
