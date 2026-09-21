<?php

/**
 * admin/add_user.php
 * Part of: Admin module
 * Filename suggests: add user
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, login_detail, company, department, designation, level_detail.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_admin_menu.php, header.inc, footer.php.
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
require_role($dbc, 1);
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$url = "add_user.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
    $result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
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
  <div id="breadcrumb"> <a href="index_admin.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">User Maintenance</a> <a href="#" class="current">Add User</a> </div>
  <h1>User Maintenance</h1>
</div>
 <?php
// Set the page title and include the HTML header.
//include ('templates/header.inc');

if (isset($_POST['submit'])) 
{ // handle the form.

require_once('../include/config.php');   //connect to the db.


// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
// create a function for escaping the data.
function escape_data ($data) {
global $dbc;   // need the connection.
if (ini_get('magic_quotes_gpc')) {
    $data = stripslashes($data);
	}
	return mysqli_real_escape_string($dbc, $data);
	}   // end function.
$message = NULL; // create an empty new variable.
   
   $vendor_no = $_POST['vendor_no'];
   $user_id = $_POST['user_id'];
   $upw = $_POST['user_password'];
   $user_fullname = $_POST['user_fullname'];
   $department = $_POST['dept'];
   $designation = $_POST['design'];
   $company = $_POST['company'];
   $user_telno1 = $_POST['user_telno1'];
   $level_id = $_POST['level_id'];
   $status = $_POST['status'];
   $user_email = $_POST['user_email'];

  
// check for a vendor no
if (empty($_POST['vendor_no']))
{ $vendor_no = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter VENDOR ID!</p>';
  }


// check for a user id
if (empty($_POST['user_id']))
{ $user_id = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter USER ID!</p>';
  }
 
// check for a password and match against the confirmed password.
if (empty($_POST['user_password']))
{ $upw = FALSE;
  $message_pass = '<p><strong>Error!</strong> You are required to enter PASSWORD!</p>';
  }
  else
  { 
  
  if ($_POST['user_password'] == $_POST['user_password2'])
    { 
	$upw = $_POST['user_password'];
	    if (preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/", $upw)) {
		$message_pass = '<p><strong>Error!</strong> Your passwords is strong.!</p>';
        
         } else {
		$message_pass = '<p><strong>Error!</strong> Your passwords is weak.! Password must be at least 20 characters and must contain at least one lower case letter, one upper case letter and one digit</p>';
         
         }
	//$upw = escape_data($_POST['user_password']); 
	}
	else
    { $upw = FALSE;
      $message_pass = '<p><strong>Error!</strong> PASSWORD did not match the CONFIRMED PASSWORD!</p>';
     }
  }


// check for a fullname
if (empty($_POST['user_fullname']))
{ $user_fullname = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter your NAME!</p>';
  }
 

// check for a telephone no 1
if (empty($_POST['user_telno1']))
{ $user_telno1 = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter TELEPHONE NO (1)!</p>';
  }

  // check for a EMAIL
  
  $email = $user_email;
  $regexp = "/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/";

if (!preg_match($regexp, $email)) {
    
   $user_email = FALSE; 
   $message.= '<p><strong>Error!</strong> You are required to enter a valid E-MAIL address!</p>';
}

// check for a department
if (empty($_POST['dept']) || ($_POST['dept'] == ""))
{ $department= FALSE;
  $message.= '<p><strong>Error!</strong> You are required to select DEPARTMENT!</p>';
  }
// check for a designation
if (empty($_POST['design']) || ($_POST['design'] == ""))
{ $designation= FALSE;
  $message.= '<p><strong>Error!</strong> You are required to select DESIGNATION!</p>';
  }

// check for a company
if (empty($_POST['company']) || ($_POST['company'] == ""))
{ $company= FALSE;
  $message.= '<p><strong>Error!</strong> You are required to select COMPANY!</p>';
  }

// check for a LEVEL USER
if (empty($_POST['level_id']) || ($_POST['level_id'] == ""))
{ $level_id = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to select LEVEL USER!</p>';
  }

// check for a Status
if (empty($_POST['status']) || ($_POST['status'] == ""))
{ $status= FALSE;
  $message.= '<p><strong>Error!</strong> You are required to select STATUS USER!</p>';
  }


  $user_telno2 = escape_data($_POST['user_telno2']);
  $user_fax = escape_data($_POST['user_fax']);
  
  $_POST['user_password'] = md5($_POST['user_password']);
 	if (!get_magic_quotes_gpc()) {
 		$_POST['user_password'] = addslashes($_POST['user_password']);
 		$user_id = addslashes($_POST['user_id']);
 			}
  
if ($vendor_no && $user_id && $upw && $user_fullname && $department && $designation && $company && $user_telno1 && $user_email && $level_id && $status) //everything ok
{  


//register the user in the db.
$query_db = "INSERT INTO user_detail (vendor_no,staff_ID,username,password,user_fullname,department,designation,company,user_telno1,user_telno2,user_fax,user_email,user_created,date_created,status,level_id,user_update,date_update,last_login,status_failed,date_failed) VALUES('$vendor_no','$user_id','$user_id','".$_POST['user_password']."','$user_fullname','$department','$designation','$company','$user_telno1','$user_telno2','$user_fax','$user_email','$username',now(),'$status','$level_id','','','','N','')";
$result = mysqli_query($dbc, $query_db) or die(mysqli_error($dbc));

//login detail
$query_login = "INSERT INTO login_detail (staff_ID, username, password, company, user_email, user_created, date_created, status, level_id, user_update, date_update, last_login, expired_pass_date, status_pass) VALUES('".strtoupper($user_id)."','".strtoupper($user_id)."', '".$_POST['user_password']."', '$company', '$user_email', '".$username."', NOW(), '$status', '$level_id','','','','','N')";
$result_login = mysqli_query($dbc, $query_login) or die (mysqli_error($dbc));



             if($result && $result_login)
             {
echo "<script>";
echo "alert('Congratulations! User successfully created');";
echo "window.location='add_user.php'";
echo "</script>";
			  exit(); //quit the script
             }
             else 
			 {
             $message = '<p><strong>Error!</strong> Cannot create User. </p>';
              mysqli_close($dbc); //close db
             }  
}
//print the message if there is one.
	  
	  
if (isset($message))
{ echo '<div class="alert alert-error">', $message, '</div>';
}
}
?>


<div class="container-fluid">
  <hr>
  <div class="row-fluid">
  <!--  <div class="span6">-->
          <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li class="active"><a  role="tab" href="add_user.php">Add User</a></li>
              <li><a role="tab" href="display_user.php">Display User</a></li>
              <li><a role="tab" href="reset_password_user.php">Reset Password</a></li>
            </ul>
          </div>
          </div>
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Add Account</h5>
        </div>
        <div class="widget-content nopadding">
         <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" class="form-horizontal">
           <div class="control-group">
              <label class="control-label">Vendor ID : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
              <input name="vendor_no" type="text" id="vendor_no" size="20" maxlength="8" value="<?php if(isset($_POST['vendor_no'])) echo $_POST['vendor_no']; ?>" class="span11" placeholder="Enter Vendor ID"/>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Staff ID : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
             <input name="user_id" type="text" class="span11" id="user_id" size="20" maxlength="20" value="<?php if(isset($_POST['user_id'])) echo $_POST['user_id']; ?>"  placeholder="Enter Staff ID" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Password : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
               <input name="user_password" type="password" class="span11" id="user_password" size="20" maxlength="20" value="<?php if(isset($_POST['user_password'])) echo $_POST['user_password']; ?>" placeholder="Enter Password"/>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Confirmed Password :<font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
                <input name="user_password2" type="password" class="span11" placeholder="Enter Confirmed Password"  id="user_password2" size="20" maxlength="20" value="<?php if(isset($_POST['user_password2'])) echo $_POST['user_password2']; ?>" />
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Name : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
             <input name="user_fullname" type="text"  class="span11" id="user_fullname" size="60" maxlength="100" value="<?php if(isset($_POST['user_fullname'])) echo $_POST['user_fullname']; ?>" placeholder="Enter Name" />
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Company Name : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
             
             <?php		
 	echo ' <select name="company" class="span11">
  <option value=""> --Select-- </option>';
  
  //Retrieve and display the available types
  $query3 ='Select * from company';
  $result3 = mysqli_query($dbc, $query3);
  
    
     while($row3 =mysqli_fetch_array($result3, MYSQLI_NUM)) {
	
	 if($_POST['submit'] == true){ ?>
               <!--RETAIN VALUE-->
               <option value="<?php echo $row3[0]?>" <?php if($row3[0]==$_POST["company"]) echo "selected"; ?>> <?php echo $row3[1]?></option>
               <?php }else{ ?>
               <option value="<?php echo $row3[0]?>" > <?php echo stripslashes($row3[1])?></option>
               <?php } ?>
               <?php
							}
	 
	  	//complete the form
	
	echo '</select>';

	?>

            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Department : <font color="#FF0000"><b> *</b></font></label>
           
              <div class="controls">
              <?php		
 	echo ' <select name="dept" class="span11">
  <option value=""> --Select-- </option>';
  
  //Retrieve and display the available types
  $query2 ='Select * from department';
  $result2 = mysqli_query($dbc, $query2);
  
      while($row2 =mysqli_fetch_array($result2, MYSQLI_NUM)) {

        if($_POST['submit'] == true){ ?>
               <!--RETAIN VALUE-->
               <option value="<?php echo $row2[0]?>" <?php if($row2[0]==$_POST["dept"]) echo "selected"; ?>> <?php echo $row2[2]?></option>
               <?php }else{ ?>
               <option value="<?php echo $row2[0]?>" > <?php echo stripslashes($row2[2])?></option>
               <?php } ?>
               <?php
							}
					
	//complete the form
	
	echo '</select>';

	?>
              </div>
           
            </div>
            <div class="control-group">
              <label class="control-label">Designation : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
             <?php		
 	echo ' <select name="design" class="span11">
  <option value=""> --Select-- </option>';
  
  //Retrieve and display the available types
  $query2b ='Select * from designation';
  $result2b = mysqli_query($dbc, $query2b);
  
    while($row2b =mysqli_fetch_array($result2b, MYSQLI_NUM)) {

      if($_POST['submit'] == true){ ?>
               <!--RETAIN VALUE-->
               <option value="<?php echo $row2b[0]?>" <?php if($row2b[0]==$_POST["design"]) echo "selected"; ?>> <?php echo $row2b[1]?></option>
               <?php }else{ ?>
               <option value="<?php echo $row2b[0]?>" > <?php echo strtoupper($row2b[1])?></option>
               <?php } ?>
               <?php
							}
				
	echo '</select>';


	?>
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Telephone No. 1 : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
               
             <input name="user_telno1" type="text" id="mask-phone" class="span8 mask text" size="20" maxlength="20" value="<?php if(isset($_POST['user_telno1'])) echo $_POST['user_telno1']; ?>"  placeholder="Enter Telephone No. 1"/>
          <!-- <span class="help-block blue span8">(999) 999-9999</span></div>-->
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Telephone No. 2 : </label>
             <div class="controls">
             <input name="user_telno2" type="text" class="span11" id="user_telno2" size="20" maxlength="20" value="<?php if(isset($_POST['user_telno2'])) echo $_POST['user_telno2']; ?>"  placeholder="Enter Telephone No. 2"/>
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Fax No : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
           <input name="user_fax" type="text" class="span11" id="user_fax" size="20" maxlength="20" value="<?php if(isset($_POST['user_fax'])) echo $_POST['user_fax']; ?>"  placeholder="Enter Fax No " />
            </div>
            </div>
              <div class="control-group">
              <label class="control-label">E-mail : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
             <input name="user_email" type="text" class="span11" id="user_email" size="60" maxlength="200" value="<?php if(isset($_POST['user_email'])) echo $_POST['user_email']; ?>" placeholder="Enter E-mail " />
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Level : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
            <?php		
 	echo ' <select name="level_id">
  <option value=""> --Select-- </option>';
  
  //Retrieve and display the available types
  $query4 ='Select * from level_detail where status_level = "Y"';
  $result4 = mysqli_query($dbc, $query4);
  
   
     while($row4 =mysqli_fetch_array($result4, MYSQLI_NUM)) {
	 
	  if($_POST['submit'] == true){ ?>
               <!--RETAIN VALUE-->
               <option value="<?php echo $row4[0]?>" <?php if($row4[0]==$_POST["level_id"]) echo "selected"; ?>> <?php echo $row4[1]?></option>
               <?php }else{ ?>
               <option value="<?php echo $row4[0]?>" > <?php echo stripslashes($row4[1])?></option>
               <?php } ?>
               <?php
							}
	 
	//complete the form
	
	echo '</select>';

	?>
            </div>
            </div>
              <div class="control-group">
              <label class="control-label">Status User : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
          <select name="status" id="status">
                   <?php if($_POST['submit'] == true)
						{ ?>
               <option value="AC" <?php if($_POST["status"] == 'AC') { ?> selected="selected"<?php } ?>>ACTIVE</option>
               <option value="NA" <?php if($_POST["status"] == 'NA') { ?> selected="selected"<?php } ?>>NON-ACTIVE</option>
               <?php 
						}
						else
						{ ?>
               <option value="AC">ACTIVE</option>
               <option value="NA">NON-ACTIVE</option>
               <?php } ?>
                 </select>
            </div>
            </div> 
             <div class="control-group">
              <label class="control-label"><font color="#FF0000"><b>  * Compulsory field</b></font></label>
             <div class="controls">
            </div>
            </div>
            <div class="form-actions">
               <input name="submit" type="submit" id="submit" value="CREATE" class="btn btn-success">
               <input name="Reset" type="reset" id="Reset" class="btn btn-warning" value="CLEAR">
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
