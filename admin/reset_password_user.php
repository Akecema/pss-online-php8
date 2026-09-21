<?php

/**
 * admin/reset_password_user.php
 * Part of: Admin module
 * Filename suggests: reset password user
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST); sends email.
 * Database tables referenced: user_detail, sys_setup_maintain, login_detail.
 * Includes: config.php, config_mail.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_admin_menu.php, footer.php.
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
require_role($dbc, 1);
include '../include/config_mail.php';

include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$url = "add_user.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
    $result2 = mysqli_query($dbc, $query2) or die(db_fail($dbc));
    $res = mysqli_fetch_array($result2);
	
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
</head>
<body>

<!--Header-part-->
<div id="header">
  <h1>&nbsp;</h1>
</div>
<?php  include "top_modal_menu.php";   ?>
<!--close-Header-part--> 

<!--sidebar-menu-->
<?php include "left_admin_menu.php";  ?>
<!--sidebar-menu-->



<!--close-left-menu-stats-sidebar-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_admin.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">User Maintenance</a> <a href="#" class="current">Reset Passwords</a> </div>
  <h1>User Maintenance</h1>
</div>
 <?php
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
	  
	      $query_all = "SELECT * FROM user_detail where user_no = '".db_esc($dbc, $_POST['username1'])."'";
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
				 
				 
				 
				 $newpass = password_hash($_POST['newpass'], PASSWORD_DEFAULT);
				 // $pass = md5($password);
				 
				  $query = "SELECT * FROM user_detail WHERE user_no = '".db_esc($dbc, $user)."'";
				  $result = mysqli_query($dbc, $query);
				  $num = mysqli_num_rows($result);
				  
				  if($num == 1 ) {
				    $row = mysqli_fetch_array($result);
					
					//Make the query
			
		          $query2 = "UPDATE user_detail set password = '".db_esc($dbc, $newpass)."' where user_no ='".db_esc($dbc, $row["user_no"])."'";
				  $result2 = mysqli_query($dbc, $query2) or die(db_fail($dbc));
				  
				  $query12 = "UPDATE login_detail SET password = '".db_esc($dbc, $newpass)."', user_update = '".db_esc($dbc, $row["username"])."', date_update = NOW() WHERE username = '".db_esc($dbc, $row["username"])."'";
				  $result12 = mysqli_query($dbc, $query12) or die(db_fail($dbc));
				  
				  if(mysqli_affected_rows($dbc) == 1) { //If it ran ok
				  
				  //Send an email, if desired
				$pass_new =  $_POST['newpass'];
				
			$to = $row["user_email"]; 
			$subject = "PSS Online Account password changed."; 
			$headers = "From: " .$data_setup["email_account"]."\r\n"; 
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
			
			$message2 ="<p>Dear Sir; </br>";
			
			$message2 .="<p>The password for your PSS Online Account was recently changed as below; </p>";
			$message = '<html><body>';
			
			$message .= '<table cellpadding="5">';
			$message .= "<tr><td><strong>Username :</strong> </td><td>" .$row["username"].  "</td></tr>";		
			$message .= "<tr><td><strong>Password :</strong> </td><td>" .$pass_new. "</td></tr>";
		    $message .= "</table>";	
			$message .= "<br>"; 
		
			$message .= "<p>Please use the following link to view:</br>";
			$message .= "<a href='".$data_setup["urls_system"]."'>" .$data_setup["urls_system"]."</a> </p>";
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


<div class="container-fluid">
  <hr>
  <div class="row-fluid">
  <!--  <div class="span6">-->
          <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li><a  role="tab" href="add_user.php">Add User</a></li>
              <li><a role="tab" href="display_user.php">Display User</a></li>
              <li class="active"><a role="tab" href="reset_password_user.php">Reset Password</a></li>
            </ul>
          </div>
          </div>
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Reset Passwords</h5>
        </div>
        <div class="widget-content nopadding">
         <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" class="form-horizontal">
           <div class="control-group">
              <label class="control-label">Username : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
              <select name="username1" id="username1" class="span11">
      <option value="NULL" placeholder="Select username"> -- Select username --</option>
      <?php
	       $query2 = "SELECT * FROM user_detail WHERE status = 'AC' ORDER BY vendor_no ASC";
                   $result2 = mysqli_query($dbc, $query2);
  
                   while($row2=mysqli_fetch_array($result2, MYSQLI_NUM)) 
			      {
                  echo'<option value="',$row2[0],'">',stripslashes($row2[2]),' - ',stripslashes($row2[5]),'</option>';
                  }
				?>
    </select>
              
              
              
           
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">New Password : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
              <input type="password" name="newpass"  id="newpass" size="30" placeholder="Enter new password"  class="span11" value="<?php if(isset($_POST['newpass'])) echo h($_POST['newpass']); ?>"/>
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Confirmed Password :<font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
          <input type="password" name="newpass2" id="newpass2" class="span11" size="30" placeholder="Enter new password"  value="<?php if(isset($_POST['newpass2'])) echo h($_POST['newpass2']); ?>" />
            </div>
            </div>
           
            <div class="control-group">
              <label class="control-label">Type verification image : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
              <input name="verif_box" type="text" id="verif_box" size="30" placeholder="Enter verification code" class="span11" /> <br>
            <img src="verificationimage.php?<?php echo rand(0,9999);?>" alt="verification image, type it in the box" width="50" height="24" style="border:solid" />    
            </div>
            </div>
             <div class="control-group">
              <label class="control-label"><font color="#FF0000"><b>  * Compulsory field</b></font></label>
             <div class="controls">
            </div>
            </div>
            <div class="form-actions">
            <input name="submit" type="submit" id="submit" value="Reset Password" class="btn btn-success">
            </div>
          </form>
        </div>
      </div>
   
  </div>
<!-------------last form ------------->
</div></div>
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
