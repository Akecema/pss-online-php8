<?php

/**
 * change_password.php
 * Part of: Core / entry-point script
 * Filename suggests: change password
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST); sends email.
 * Database tables referenced: user_detail.
 * Includes: config.php, header2.php, config_mail.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
session_start();
$username = $_SESSION['username'] ?? '';
include 'include/config.php';
include 'header2.php';
include 'include/config_mail.php';
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Ingress Autoventures Co., Ltd.</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="shortcut icon" href="img/favicon.ico">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="css/fullcalendar.css" />
<link rel="stylesheet" href="css/matrix-style.css" />
<link rel="stylesheet" href="css/matrix-media.css" />
<link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
<link rel="stylesheet" href="css/jquery.gritter.css" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

<!-- Add jQuery basic library -->
<script type="text/javascript" src="fancybox/jquery-lib.js"></script>
		
<!-- Add required fancyBox files -->
<link rel="stylesheet" href="fancybox/fancybox/source/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/fancybox/source/jquery.fancybox.pack.js"></script>

<!-- Optional, Add fancyBox for media, buttons, thumbs -->
<link rel="stylesheet" href="fancybox/fancybox/source/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/fancybox/source/helpers/jquery.fancybox-buttons.js"></script>
<script type="text/javascript" src="fancybox/fancybox/source/helpers/jquery.fancybox-media.js"></script>
<link rel="stylesheet" href="fancybox/fancybox/source/helpers/jquery.fancybox-thumbs.css" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/fancybox/source/helpers/jquery.fancybox-thumbs.js"></script>
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
<div style="width:570px;height:450px;-webkit-border-radius: 5px;-moz-border-radius: 15px; ">

  <table width="100%" height="286" border="0" cellpadding="0" cellspacing="0" >
  <tr> 
    <td width="386" height="15"><span class="style2"><div align="center"><img src="img/lock.png" /></div> Change Password</span></td>
    <td width="414">&nbsp;</td>
  </tr>
  <tr> 
    <td height="150" colspan="2"> 
      <?php 

// make the query.
// change password
if(isset($_POST['submit']))
{
 // require_once('include/config.php');   //connect to the db.

// create a function for escaping the data.
function escape_data ($data) {
global $dbc;   // need the connection.
// magic_quotes_gpc was removed in PHP 5.4 and ini_get() for it always
// returns "" (falsy) now, so this branch never runs on PHP 8 - harmless
// dead code, left as-is rather than risk changing behavior.
if(ini_get('magic_quotes_gpc')) {
    $data = stripslashes($data);
	}
	return mysqli_real_escape_string($dbc, $data);
	}   // end function.
$message = NULL; // create an empty new variable.

	
	
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
			  $newpass = escape_data($_POST['newpass']);
			  } else {
			    $newpass = FALSE;
				$message .= '<p>Your new password did not match the confirmed new password!</p>';
				}
			}
			
				  	  
                 if($user && $password && $newpass) { // Everything's OK
				 
				 $pass = md5($password); // legacy-format fallback for the old-password check below

				  // MIGRATED (2026-08-17): can't match the old password by SQL equality
				  // anymore now that some accounts have bcrypt hashes (non-deterministic
				  // per hash, so "password = '$pass'" in SQL could never match those rows)
				  // - fetch by username only, then verify the old password and hash the
				  // new one in PHP. Same pattern as the login flow (ckies-aut_scd.php).
				  // $user was passed through escape_data() (mysqli_real_escape_string)
				  // above, so this query is reasonably defended against SQL injection.
				  $query = "SELECT * FROM user_detail WHERE username ='".db_esc($dbc, $user)."'";
				  $result = mysqli_query($dbc, $query);
				  $num = mysqli_num_rows($result);
				  
				  if($num == 1 ) {
				    $row = mysqli_fetch_array($result);
					$stored_pass = stripslashes($row['password']);
					if (password_get_info($stored_pass)['algo'] !== null) {
						$old_password_ok = password_verify($password, $stored_pass);
					} else {
						$old_password_ok = ($pass == $stored_pass);
					}
					if ($old_password_ok) {
					$newpass = password_hash($_POST['newpass'], PASSWORD_DEFAULT);
					//Make the query
			
		          $query2 = "UPDATE user_detail set password = '".db_esc($dbc, $newpass)."' where username='".db_esc($dbc, $row["username"])."'";
				  $result2 = mysqli_query($dbc, $query2) or die(db_fail($dbc));
				  
				  if(mysqli_affected_rows($dbc) == 1) { //If it ran ok
				  
				  // SECURITY: the new password is emailed to the user in plain text below.
				  // Email is not a secure channel (unencrypted in transit/at rest on most
				  // mail servers) and this trains users to expect/trust password-in-email,
				  // which is exactly the pattern phishing attacks exploit. Consider sending
				  // a time-limited reset *link* instead of the password itself.
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

		   echo '<h3>Your new password has been send to your email. We recommend you to print the e-mail for your reference.<h3>';
				
				 
				  exit();
				  
				  } else {   //If it did not run OK
				  $message = '<p>Password cannot be change due to system error. We apologize for any inconvenience.</p><p>'.mysqli_error($dbc).'</p>';
				  }
					} else {
					   $message = '<p>Your username and password do not match our database</p>';
					}
				}else {
				   $message = '<p>Your username and password do not match our database</p>';
				 }
				 mysqli_close($dbc);    //Close the database connection
				 
			 } else {
			     $message .='<p>Please try again.</p>';
	           }
			   
	  }  //End of the main Submit conditional
	  
	  //Print error
	  if (isset($message)) {
	     echo'<font color ="red">', $message, '</font>';
	    }
	  ?>
     
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="post">
        <fieldset>
        <legend class="leave style3">Please enter your information below:</legend>
        <p>&nbsp;</p>
        <p><br>
        </p>
        <table width="99%" border="0" class="table table-striped table-hover table-bordered">
          <tr> 
            <td><div align="right">Username</div></td>
            <td> <div align="center">:</div></td>
            <td><input type="text" name="username1" size="30" class="leave" id="username1"  readonly value="<?php echo $username; ?>" placeholder="Enter your username"></td>
          </tr>
          <tr> 
            <td><div align="right">Current Password</div></td>
            <td> <div align="center">:</div></td>
            <td><input type="password" name="password" class="leave" id="password" size="30" placeholder="Enter current password"></td>
          </tr>
          <tr> 
            <td><div align="right">New Password </div></td>
            <td><div align="center">:</div></td>
            <td><input type="password" name="newpass" class="leave" id="newpass" size="30" placeholder="Enter new password"></td>
          </tr>
          <tr> 
            <td><div align="right">Confirm New Password</div></td>
            <td> <div align="center">:</div></td>
            <td><input type="password" name="newpass2" class="leave" id="newpass2" size="30" placeholder="Enter new password"></td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
            <td><div align="right">
              <input type="submit" name="submit" value="Change Password" class="btn btn-info" />
            </div></td>
          </tr>
        </table>
        <p align="center">&nbsp;</p>
        </fieldset>
      </form></td>
  </tr>
  <tr> 
    <td height="25" colspan="2">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" colspan="2">    </td>
  </tr>
</table>
</div>
</body>
</html>