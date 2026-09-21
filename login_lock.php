<?php 


/**
 * login_lock.php
 * Part of: Core / entry-point script
 * Filename suggests: login lock
 *
 * Behavior: no form submission, file upload, or export detected (likely a display/listing page, utility, or bootstrap/include file).
 * Database tables referenced: sys_setup_maintain.
 * Includes: config.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
include 'include/config.php';

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);


?>
<!DOCTYPE html>
<html lang="en">
<head>

        <title><?php echo h($data_setup["title_desc"]); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="shortcut icon" href="img/favicon.ico">
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="css/matrix-login.css" />
        <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="scripts/thickbox.css" type="text/css" media="screen" />
		<script type="text/javascript" src="javascript/jquery-latest.js"></script> 
		<script type="text/javascript" src="javascript/thickbox.js"></script>	
		<script language="javascript">

 defaultStatus = "MRIN Online  <?php echo h($data_setup['title_desc']); ?>"
 function show ( text )
 {
  window.status=text;
  return true;
 }
</script>
</head>
<!-- Shown after 5 failed login attempts within 24h (see ckies-aut_frst.php /
     ckies-aut_scd.php, which redirect here instead of back to index.php).
     Auto-refreshes to the login page after 5 seconds; onload calls
     logout() (js/index.js) to clear any stale session/cookies first. -->
<body onLoad="logout()">
<meta http-equiv="refresh" content="5;URL=index.php"> 
<div id="loginbox">  
  <div class="form">
    <h2>Access Denied</h2>
    <div><img src="img/lock2.png" width="150" height="180" >   </div>
  </div>
  <div class="text-warning">
  Access to the web page was blocked. </br>Kindly contact System Administrator </div>

<script src="js/index.js"></script>


</div>
</body>
</html>
