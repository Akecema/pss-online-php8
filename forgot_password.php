<?php

/**
 * forgot_password.php
 * Part of: Core / entry-point script
 * Filename suggests: forgot password
 *
 * Behavior: processes submitted form data ($_POST); sends email.
 * Database tables referenced: sys_setup_maintain, user_detail, login_detail.
 * Includes: config.php, config_mail.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
include 'include/config.php';
include 'include/config_mail.php';


$today = getdate();
$hours = $today['hours']; 
$minutes = $today['minutes'];
$seconds = $today['seconds'];
$month = $today['mon']; 
$mday = $today['mday']; 
$year = $today['year']; 

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
<link href="css/templatemo_style.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="scripts/pagination3.css" type="text/css" />

<link rel="stylesheet" href="scripts/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="javascript/jquery-latest.js"></script> 
<script type="text/javascript" src="javascript/thickbox.js"></script>	

<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}
.style2 {
	color: #000066;
	font-weight: bold;
}
.style4 {font-size: 12px}
.style6 {font-size: 12px}
-->
</style>
</head>
<body>
 <?php 

// make the query.
// reset password
if(isset($_POST['submit2']))
{

// create a function for escaping the data.
function escape_data ($data) {
global $dbc;   // need the connection.
if(ini_get('magic_quotes_gpc')) {
    $data = stripslashes($data);
	}
	return mysqli_real_escape_string($dbc, $data);
	}   // end function.
$message = NULL; // create an empty new variable.
	
	$user_name =  $_POST['user_name'];
	$email =  $_POST['email'];
	
    //check for a username
    if(empty($_POST['user_name'])) 
       { $user_name = FALSE;
	     $message .= 'You forgot to enter your username!';
           }
    
      else {
	      $user_name = escape_data($_POST['user_name']);
      }
	  
	  if(empty($_POST['email'])) 
       { $email = FALSE;
	     $message .= 'You forgot to enter your email!';
           }
    
      else {
	      $email = escape_data($_POST['email']);
      }
	  
// check for existence of that username
    if($user_name && $email) { 
	
	       // $user_name/$email went through escape_data() (mysqli_real_escape_string)
	       // above, so this is reasonably defended against SQL injection.
		   $query = "SELECT * FROM user_detail WHERE username = '$user_name' and user_email= '$email'";
		   $result = mysqli_query($dbc, $query);
		   $num = mysqli_num_rows($result);
		   
				  if($num == 1) {
				    $row = mysqli_fetch_array($result);
					
					 // Generates a random temporary password ($p), stores its
					 // password_hash() (bcrypt) hash ($p2) in the DB - MIGRATED
					 // (2026-08-17), was a bare MD5 hash - and emails the plaintext
					 // temp password to the user below. Better than change_password.php
					 // (which emails a user-chosen password), but a reset *link* would
					 // still be safer than emailing any password, even a temporary one.
					 $p = substr (md5(uniqid(rand(),1)),3,10);
					 $p2 = password_hash($p, PASSWORD_DEFAULT);
					 	
				  $query2 = "UPDATE user_detail set password = '$p2', user_update = '".$row["username"]."', date_update = NOW() WHERE username = '".$row["username"]."'";
				  $result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
				  
				$query_login = "UPDATE login_detail SET password = '$p2', user_update = '".$row["username"]."', date_update = NOW() WHERE username = '".$row["username"]."'";
				$result_login = mysqli_query($dbc, $query_login) or die (mysqli_error($dbc));
				  
			
			      // NOTE: if this branch runs (no email on file), execution falls
			      // through (no exit()) into the "username and password do not
			      // match" message + exit() a few lines below - so the user sees
			      // both messages in sequence. Looks like a pre-existing logic
			      // slip rather than intentional; left as-is since behavior wasn't
			      // otherwise being changed in this pass.
			      if(($row["user_email"] == "") or ($row["user_email"] == "NULL"))
				  { 
				   echo "User don't have the e-mail account. Please create e-mail account for this user.";

				    }else{ //If it ran ok
					
     	  
				
				  
				  //Send an email, if desired
				    $body = "Temporary password: '$p'.\r\n\r\nPlease use given password with username to login.\r\nYou may change your password after first login.";
					mail($row["user_email"], 'Temporary Password.', $body, 'From: pss-online@ingresscorp.com.my');

		   
			echo "<script>";
			echo "alert('Your new password has been send to your email. We recommend you to print the e-mail for your reference.');";
			echo "window.location='index.php'";
			echo "</script>";
			exit(); //quit the script
					
					
					
				   }  //end else
			
			echo "<script>";
			echo "alert('Your username and password do not match our database');";
		    echo "window.location='index.php'";
			echo "</script>";
			exit(); //quit the script
				   
				  
				}
					 
		    echo "<script>";
			echo "alert('Your username or password do not match our database');";
		    echo "window.location='index.php'";
			echo "</script>";
			exit(); //quit the script
				
			}	 
			
				 mysqli_close($dbc);    //Close the database connection
				 
		  //Print error 
		  if(isset($message)) {
			  
			  
		    echo "<script>";
			echo "alert('$message');";
		    echo "window.location='index.php'";
			echo "</script>";
			exit(); //quit the script
				    

	    }  
	            
	  }  //End of the main Submit conditional
	  
	  ?>


</body>
</html>