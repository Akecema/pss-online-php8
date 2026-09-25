<?php 

/**
 * index.php
 * Part of: Core / entry-point script
 * Filename suggests: index
 *
 * Behavior: processes submitted form data ($_POST).
 * Includes: con-dbcIPSB.php, ckies-brw.php, ckies-aut_frst.php, ckies-aut_scd.php, frm-login.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
include 'con-dbcIPSB.php';

?><!DOCTYPE html>
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
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="scripts/thickbox.css" type="text/css" media="screen" />
		<style type="text/css">
		body,td,th {
	font-family: "Open Sans", sans-serif;
}
body {
	background-color: #FFFFFF;
}
        </style>
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
 <?php 
	   
	include 'ckies-brw.php';
	
	?>    </head>
    <body> 
    <p align="center"> <img src="set_upload/<?php echo $filename;  ?>" /><br>
    </p>
    <?php 

 include "ckies-aut_frst.php";
 
//if the login form is submitted 
 if (isset($_POST['submit'])) { // if form has been submitted
 
 include "ckies-aut_scd.php";
 }else 
{	 
 
 // if they are not logged in 
 ?>
        <div id="loginbox">
        <?php
		
	include "frm-login.php";
		
		?>
        
                    
          
        </div>
       <?php 
 } 

 ?>  
<script src="js/jquery.min.js"></script>  
<script src="js/matrix.login.js"></script> 
<script src="js/excanvas.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/jquery.flot.min.js"></script> 
<script src="js/jquery.flot.resize.min.js"></script> 
<script src="js/jquery.peity.min.js"></script> 
<script src="js/fullcalendar.min.js"></script> 
<script src="js/matrix.js"></script> 
<script src="js/matrix.dashboard.js"></script> 
<script src="js/jquery.gritter.min.js"></script> 

    </body>

</html>
