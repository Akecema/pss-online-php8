<?php

/**
 * prod/reset_password_user.php
 * Part of: Production module
 * Filename suggests: reset password user
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST); sends email.
 * Database tables referenced: sys_setup_maintain, user_detail.
 * Includes: config.php, config_mail.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include '../include/config_mail.php';

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------	
 ?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $data_setup["title_desc"]; ?></title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../css/style.css" type="text/css" media="all" />
<link href="../css/ddtabmenu.css" rel="stylesheet" type="text/css" />
</head>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>
<?php 


$today = getdate();
$hours = $today['hours']; 
$minutes = $today['minutes'];
$seconds = $today['seconds'];
$month = $today['mon']; 
$mday = $today['mday']; 
$year = $today['year']; 
?>

<body>
<p>
  <!-- Header -->
  <!-- End Header -->
    <?php 

// make the query.
// change password
if(isset($_POST['submit']))
{
// load the variables form address bar
$verif_box = $_POST["verif_box"];


// create a function for escaping the data.
function escape_data ($data) {
global $dbc;   // need the connection.
if(ini_get('magic_quotes_gpc')) {
    $data = stripslashes($data);
	}
	return mysqli_real_escape_string($dbc, $data);
	}   // end function.
$message = NULL; // create an empty new variable.

    //check for a username
    if(empty($_POST['username1'])) 
       { $user = FALSE;
	     $message .='<p> You forgot to select username!</p>';
           }
    
      else {
	  
	      $query_all = "SELECT * FROM user_detail where user_no = '".$_POST['username1']."'";
		  $result_all = mysqli_query($dbc, $query_all);
		  $db_all = mysqli_fetch_array($result_all);
	  
	  
	      if($_POST["username1"] == $db_all["user_no"]) {
			  $user = escape_data($_POST['username1']);
			  } else {
			    $user = FALSE;
				$message .= '<p>Your username did not match from database!</p>';
				}
	
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
			
				  	  
                 if($user && $verif_box && $newpass) { // Everything's OK
				 
	// check to see if verificaton code was correct
     if(md5($verif_box).'a4xn' == $_COOKIE['tntcon']){			 
				 
				 
				 
				 $newpass = md5($_POST['newpass']);
				 // $pass = md5($password);
				 
				  $query = "SELECT * FROM user_detail WHERE user_no = '$user'";
				  $result = mysqli_query($dbc, $query);
				  $num = mysqli_num_rows($result);
				  
				  if($num == 1 ) {
				    $row = mysqli_fetch_array($result);
					
					//Make the query
			
		          $query2 = "UPDATE user_detail set password = '$newpass' where user_no ='".$row["user_no"]."'";
				  $result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
				  
				  if(mysqli_affected_rows($dbc) == 1) { //If it ran ok
				  
				  //Send an email, if desired
				$pass_new =  $_POST['newpass'];
				
			$to = $row["user_email"]; 
			$subject = "MRIN Online Account password changed."; 
			$headers = "From: mrin@ingresscorp.com.my\r\n"; 
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
			
			$message2 ="<p>Dear Sir; </br>";
			
			$message2 .="<p>The password for your MRIN Online Account was recently changed as below; </p>";
			$message = '<html><body>';
			
			$message .= '<table cellpadding="5">';
			$message .= "<tr><td><strong>Username :</strong> </td><td>" .$row["username"].  "</td></tr>";		
			$message .= "<tr><td><strong>Password :</strong> </td><td>" .$pass_new. "</td></tr>";
		    $message .= "</table>";	
			$message .= "<br>"; 
		
			$message .="<p>Please use the following link to view:</br>";
			$message .="<a href='https://mrin-online.ingresscorp.net'>https://mrin-online.ingresscorp.net</a> </p>";
			$message .= "<p><font color='black'>This is a system generated email. Please DO NOT reply. </font></p>";
			$message .= "<p>&nbsp;</p>";
			$message .= "</body></html>";
			
			mail($to, $subject, $message2.$message, $headers);

		   echo '<h3>Your new password has been send to your email. We recommend you to print the e-mail for your reference.<h3>';
				
				 
				  exit();
				  
				  } else {   //If it did not run OK
				  $message = '<p>Password cannot be change due to system error. We apologize for any inconvenience.</p>';
				  }
				}else { 
				   $message = '<p>Your username and password do not match our database</p>';
				 }
				 mysqli_close($dbc);    //Close the database connection
				 
			 } else {
			     $message .='<p>Please try again.</p>';
	           }
			   
			   setcookie('tntcon','');
		}	   
	  }  //End of the main Submit conditional
	  
	  //Print error
	if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}
	  ?>
</p>
<p>&nbsp;</p>
<table width="650">
    <tr>
      <td width="1%">&nbsp;</td>
      <td width="85%"> <div class="small-nav"> User Maintenance&nbsp;&gt;Reset Password </div></td>
      <td width="14%"><a href="javascript:printWindow(); return false;" target="_blank" title="Print"><img src="../images/print2.jpg" width="48" height="48" /></a></td>
  </tr>
  </table>

<div id="main2">
      
      <div class="cl">&nbsp;</div>
      <!-- Content -->
      <div id="subcontent">
        <!-- Box -->
        <div class="box">
          <!-- Box Head -->
          <div class="box-head">
            <h2><img src="../images/chg_pass.gif" width="20" height="20" /> Reset Passwords</h2>
          </div>
          <!-- End Box Head -->
         <form action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="post">
            <!-- Form -->
            <div class="form">
             <table width="99%" border="0" cellspacing="2">
               <tr>
                 <td width="26%" height="25">Username</td>
                 <td width="3%" height="25">:</td>
                 <td width="71%" height="25"><select name="username1" id="username1">
      <option value="NULL" placeholder="Select username"> -- Select username --</option>
      <?php
	       $query2 = "SELECT * FROM user_detail WHERE level_id != '1' AND status = 'AC' ORDER BY vendor_no ASC";
                   $result2 = mysqli_query($dbc, $query2);
  
                   while($row2=mysqli_fetch_array($result2, MYSQLI_NUM)) 
			      {
                  echo'<option value="',$row2[0],'">',stripslashes($row2[2]),' - ',stripslashes($row2[5]),'</option>';
                  }
				?>
    </select></td>
               </tr>
               <tr>
                 <td height="25">New Password</td>
                 <td height="25">:</td>
                 <td height="25"><input type="password" name="newpass"  id="newpass" size="30" placeholder="Enter new password" /></td>
               </tr>
                 <tr>
                 <td height="25">Confirm New Password</td>
                 <td height="25">:</td>
                 <td height="25"><input type="password" name="newpass2" id="newpass2" size="30" placeholder="Enter new password" /></td> 
               </tr>
               <tr>
                 <td height="25">Type verification image</td>
                 <td height="25">:</td>
                 <td height="25"><input name="verif_box" type="text" id="verif_box" size="30" placeholder="Enter verification code" /></td>
               </tr>
      
               <tr>
                 <td height="25"></td>
                 <td height="25"></td>
                 <td height="25"><img src="verificationimage.php?<?php echo rand(0,9999);?>" alt="verification image, type it in the box" width="50" height="24" style="border:solid" /> </td>
               </tr>
               <tr>
                 <td height="25">&nbsp;</td>
                 <td height="25">&nbsp;</td>
                 <td height="25"><div align="right">
              <input type="submit" name="submit" value="Reset Password" class="button" />
            </div></td>
               </tr>
            </table>
            </div>
            <!-- End Form -->
        
          </form>
        </div>
        <!-- End Box -->
      </div>
      <!-- End Content -->
    
      <div class="cl">&nbsp;</div>
</div>


</body>
</html>
