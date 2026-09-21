<?php

/**
 * prod/change_password_prod.php
 * Part of: Production module
 * Filename suggests: change password prod
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST); sends email.
 * Database tables referenced: sys_setup_maintain, login_detail, user_detail, sys_param.
 * Includes: config.php, header2.php, config_mail.php.
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
require_role($dbc, 2);
include '../header2.php';
include '../include/config_mail.php';

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
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


$today = getdate();
$hours = $today['hours']; 
$minutes = $today['minutes'];
$seconds = $today['seconds'];
$month = $today['mon']; 
$mday = $today['mday']; 
$year = $today['year']; 
?>

<style type="text/css">
<!--
.style1 {color: #FFFFFF}
body {
	background-color: #ffffff;
}
.style2 {
	color: #000066;
	font-weight: bold;
}
.style3 {font-size: 11px}
-->
</style>
</HEAD>

<body>



      <?php 

// change password
if(isset($_POST["submit2"]))
{
 // require_once('../include/config.php');   //connect to the db.

// create a function for escaping the data.
function escape_data ($data) {
global $dbc;   // need the connection.
if(ini_get('magic_quotes_gpc')) {
    $data = stripslashes($data);
	}
	return mysqli_real_escape_string($dbc, $data);
	}   // end function.
$message = NULL; // create an empty new variable.

	$user = $_POST["username1"];
	$password = $_POST["password"];
	
    //check for a username
    if(empty($_POST['username1'])) 
       { $user = FALSE;
	     $message .='<p> You forgot to enter your username!</p>';
           }
    
      else {
	  
	      if($_POST['username1'] == $username) {
			  $user = escape_data($_POST['username1']);
			  } else {
			    $user = FALSE;
				$message .= '<p>Your username did not match from database!</p>';
				}
	
      }
	  

    //check for a old password
    if(empty($_POST['password'])) 
       {  $password = FALSE;
	       $message .='<p> You forgot to enter your existing password!</p>';
           }
    
      else {
	      $password = escape_data($_POST['password']);
	            }
				
				      
      //check for a password and match against the confirmed password.
     if(empty($_POST['newpass'])) {
	     $newpass = FALSE;
		 $message .= '<p>You forgot to enter your new password!</p>';
		 } else {
		     if($_POST['newpass'] == $_POST['newpass2']) {
			  $newpass = $_POST['newpass'];
			  
			  if (preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/", $newpass)) {
			   $newpass = escape_data($_POST['newpass']);
		$message .= '<p>  Your passwords is strong.!</p>';
         
		  
         } else {
		 $newpass = FALSE;
		$message .= '<p> Your passwords is weak.! Password must be at least 8 characters and must contain at least one lower case letter, one upper case letter and one digit.</p>';
         
         }
			  
			  } else {
			    $newpass = FALSE;
				$message .= '<p>Your new password did not match the confirmed new password!</p>';
				}
			}
			
				  	  
                 if($user && $password && $newpass) { // Everything's OK
				 
				 $newpass = md5($_POST['newpass']);
				  $pass = md5($password);
				 
				  $query = "SELECT * FROM login_detail WHERE username = '".$user."' AND password = '".$pass."'";
				  $result = mysqli_query($dbc, $query);
				  $num = mysqli_num_rows($result);
				  
				  if($num == 1 ) {
				    $row = mysqli_fetch_array($result);
					
						//Make the query
				//---------------------------update table login_detail & user_detail
				
				
				
				  $query12 = "UPDATE login_detail SET password = '$newpass', status_pass = 'Y', user_update = '".$row["username"]."', date_update = NOW() WHERE username = '".$row["username"]."'";
				  $result12 = mysqli_query($dbc, $query12) or die (mysqli_error($dbc));
				
				//----------------------------------------------	
			       $query2 = "UPDATE user_detail SET password = '$newpass', user_update = '".$row["username"]."', date_update = NOW() WHERE username = '".$row["username"]."'";
				  $result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
				  
				  if(mysqli_affected_rows($dbc) == 1) { //If it ran ok
				  
				  //Send an email, if desired
		    $pass_new =  $_POST['newpass'];
				
			$to = $row["user_email"]; 
			$subject = "PSS Online Account password changed."; 
			$headers = "From: " .$data_setup["email_account"]."\r\n"; 
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
			
			$mess2 ="<p>Dear Sir; </br>";
			
			$mess2 .="<p>The password for your PSS Online Account was recently changed as below; </p>";
			$mess = '<html><body>';
			$mess .= '<table cellpadding="5">';
			$mess .= "<tr><td><strong>Username :</strong> </td><td>" .$row["username"].  "</td></tr>";		
			$mess .= "<tr><td><strong>Password :</strong> </td><td>" .$pass_new. "</td></tr>";
		    $mess .= "</table>";	
			$mess .= "<br>"; 
		
			$mess .="<p>Please use the following link to view:</br>";
			$mess .="<a href='".$data_setup["urls_system"]."'>" .$data_setup["urls_system"]."</a> </p>";
			$mess .= "<p><font color='black'>This is a system generated email. Please DO NOT reply. </font></p>";
			$mess .= "<p>&nbsp;</p>";
			$mess .= "</body></html>";
			
			mail($to, $subject, $mess2.$mess, $headers);
			
			
			//------------------------get from table sys_param----------------------
			
			       $query_param = "SELECT * FROM sys_param WHERE param_name = 'logon_exp_days' ORDER BY id_param ASC";
                   $result_param = mysqli_query($dbc, $query_param);
				   $row_param = mysqli_fetch_array($result_param);
			
			//---------------calculation date for expiry date after change password-----------------------
					
				   $query_dtl = "SELECT * FROM login_detail WHERE username = '".$row["username"]."'";
                   $result_dtl = mysqli_query($dbc, $query_dtl);
				   $row_dtl = mysqli_fetch_array($result_dtl);
					
		    //------range date for new value ----------------------------
 $start_date_check = $row_dtl["date_update"];
 $end_date_check = date('Y-m-d H:m:s', strtotime("$row_param[new_value]"));
 
 
			  $query_dt = "UPDATE login_detail set expired_pass_date = '$end_date_check' where username='".$row["username"]."'";
		      $result_dt = mysqli_query($dbc, $query_dt) or die (mysqli_error($dbc));
			
			

          	echo "<script>";
			echo "alert('Your new password has been send to your email. We recommend you to print the e-mail for your reference.');";
			echo "window.location='index_production.php'";
			echo "</script>";
			exit(); //quit the script
       

				  } else {   //If it did not run OK
				  
				  	echo "<script>";
			echo "alert('Password cannot be change due to system error. We apologize for any inconvenience.');";
		    echo "window.location='index_production.php'";
			echo "</script>";
			exit(); //quit the script
				  
				  
				  
				/*  
				  $message = '<p>Password cannot be change due to system error. We apologize for any inconvenience.</p><p>'.mysqli_error($dbc).'</p>';*/
				  }
				}else { 
	
				   
				   echo "<script>";
			echo "alert('Your username and password do not match our database');";
		    echo "window.location='index_production.php'";
			echo "</script>";
			exit(); //quit the script
				   
				   
				   
				 }
				 mysqli_close($dbc);    //Close the database connection
				 
			 } else {
				 
				   echo "<script>";
			echo "alert('Please try again.');";
		    echo "window.location='index_production.php'";
			echo "</script>";
			exit(); //quit the script
		
	           }
			   
	  }  //End of the main Submit conditional
	  
	  //Print error
	/*  if (isset($message)) {
	     echo'<font color ="red">', $message, '</font>';
	    }*/
	  ?>

</body>
</html>