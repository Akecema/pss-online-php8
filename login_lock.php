<?php 

include 'include/config.php';

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysql_query($query_setup);   //run the query.
$num_setup = mysql_num_rows($rs_setup);   //how many material are there?
$data_setup = mysql_fetch_array($rs_setup);


?>
<!DOCTYPE html>
<html lang="en">
<head>

        <title><?php echo $data_setup["title_desc"]; ?></title>
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

 defaultStatus = "MRIN Online  <?php echo $data_setup['title_desc']; ?>"
 function show ( text )
 {
  window.status=text;
  return true;
 }
</script>
</head>
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
