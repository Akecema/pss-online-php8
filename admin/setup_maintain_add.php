<?php

/**
 * admin/setup_maintain_add.php
 * Part of: Admin module
 * Filename suggests: setup maintain add
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST); handles a file upload.
 * Database tables referenced: user_detail, sys_setup_maintain, company.
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

 set_time_limit(0);
 ini_set('post_max_size', '2M');
 ini_set('upload_max_filesize', '2M');

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$url = "setup_maintain_add.php";

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
<script language="javascript">
	function fncCreateElement(){
		
	   var mySpan = document.getElementById('mySpan');

	   var myElement1 = document.createElement('input');
	   myElement1.setAttribute('type',"file");
	   myElement1.setAttribute('name',"fileUpload[]");
	   mySpan.appendChild(myElement1);	

	   var myElement2 = document.createElement('<br>');
	   mySpan.appendChild(myElement2);
	}
</script>
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
  <div id="breadcrumb"> <a href="index_admin.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Setup System</a> </div>
  <h1>Setup System</h1>
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
   
 $fileType = $_FILES['upload']['type'];
 $allowed = array("image/jpeg", "image/gif", "application/pdf");
 
 $title_desc = $_POST['title_desc'];
 $upload = $_FILES['upload'];
 $urls_system = $_POST['urls_system'];
 $smtp_account = $_POST['smtp_account'];
 $ftp_ip = $_POST['ftp_ip'];
 $status_system = $_POST['status_system'];
 $email_account = $_POST['email_account'];
 $comp_code = $_POST['comp_code'];

// check for a title desc
if(empty($_POST['title_desc']) || ($_POST['title_desc'] == ""))
{ $MFO_doc = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to select Title Description!</p>';
  }
  
 // check for a urls_system
if(empty($_POST['urls_system']))
{ $urls_system = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter URLs System!</p>';
  } 
  
  // check for a company code
if(empty($_POST['comp_code']))
{ $comp_code = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Company Code!</p>';
  } 
  
  
// check for a upload file
 if($_FILES['upload']['size'] == 0 || empty($_FILES['upload']['tmp_name']))
  { 
 
  $upload = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to select Upload File!</p>';
  }	 
 elseif(!in_array($fileType, $allowed)) 
	{
  		$upload = FALSE;
        $message.= '<p><strong>Error!</strong> Only IMAGE files are allowed.</p>';
	
	} 
     
// check for a smtp account
if(empty($_POST['smtp_account']))
{ $smtp_account = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter SMTP Mail!</p>';
  }

 // check for a EMAIL
  
  $email = $email_account;
  $regexp = "/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/";

if (!preg_match($regexp, $email)) {
    
   $email_account = FALSE; 
   $message.= '<p><strong>Error!</strong> You are required to enter a valid E-MAIL address!</p>';
} 

// check for a ftp ip
if(empty($_POST['ftp_ip']))
{ $ftp_ip = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Status Setting!</p>';
  }
  
// check for a status
if(empty($_POST['status_system']) || ($_POST['status_system'] == ""))
{ $status_system = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter FTP IP!</p>';
  }
  
  
  if($title_desc && $urls_system && $smtp_account && $ftp_ip && $status_system && $_FILES['upload']['size'] > 0 && $email_account && $comp_code) //everything ok
 {  	
   
   
	   //Add the record to the database
	   $query = "INSERT INTO sys_setup_maintain(id_setup, title_desc, logo_name, logo_comp, urls_system, smtp_account, email_account, ftp_ip, date_create, user_create, date_update, user_update, status_system, comp_code) VALUES('','".$title_desc."','".$_FILES['upload']['name']."','','".$urls_system."','".$smtp_account."', '".$email_account."', '".$ftp_ip."',NOW(),'$username','','','".$status_system."','".$comp_code."')";
	   $result = mysqli_query($dbc, $query) or die (mysqli_error($dbc));   
	  
	   if($result) {
	   //create the filename
	     $extension = explode ('.', $_FILES['upload']['name']);
		 $uid = mysqli_insert_id($dbc);  //upload ID
		// $filetest = $_FILES['upload']['name'];
		 //$filename = $filetest;
		 $filename = $uid .'.'.$extension[1];
		 
		 
		    $query_update2 = "UPDATE sys_setup_maintain SET logo_comp = '".$uid."' WHERE id_setup = '".$uid."'";
			$result_update2 = mysqli_query($dbc, $query_update2) or die (mysqli_error($dbc));   
		 
		 //--------update table sys_setup_maintain ----------------
		  if($status_system == "AC")
		  {
			$query_update1 = "UPDATE sys_setup_maintain SET status_system = 'NA' WHERE logo_comp != '".$uid."'";
			$result_update1 = mysqli_query($dbc, $query_update1) or die (mysqli_error($dbc));   
	       
		  }
		 
		 
		 
	 if(move_uploaded_file($_FILES['upload']['tmp_name'], "../set_upload/$filename"))  {
		 
   
echo "<script>";
echo "alert('Congratulations! Your submission is successfully processed');";
echo "window.location='display_setup_maintain.php'";
echo "</script>";
			  exit(); //quit the script
         
           } else {
			
			echo "<script>";
            echo "alert('The document could not be moved.');";
            echo "window.location='setup_maintain_add.php'";
            echo "</script>";
			

			   }
			   
			  } else {  //If the query did not run OK
			  
			echo "<script>";
            echo "alert('Your submission could not be processed due to a system error. We apologize for any inconvenience.');";
            echo "window.location='setup_maintain_add.php'";
            echo "</script>";
			  
		
				}
				  mysqli_close($dbc);   // close database conn
				
				}
	  
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
              <li class="active"><a  role="tab" href="setup_maintain_add.php">Add Setup</a></li>
              <li><a role="tab" href="display_setup_maintain.php">Display Setup</a></li>
            </ul>
          </div>
          </div>
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Add Setup</h5>
        </div>
        <div class="widget-content nopadding">
          <form name="form1" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal">
        <input type="hidden" name="MAX_FILE_SIZE" value="1024000000000">
            <div class="control-group">
              <label class="control-label">Title Header : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
             
             <?php		
 	echo ' <select name="title_desc" class="span11">
  <option value=""> --Select-- </option>';
  
  //Retrieve and display the available types
  $query3 ='Select * from company';
  $result3 = mysqli_query($dbc, $query3);
  
    
     while($row3 =mysqli_fetch_array($result3, MYSQLI_NUM)) {
	
	 if($_POST['submit'] == true){ ?>
               <!--RETAIN VALUE-->
               <option value="<?php echo $row3[1]?>" <?php if($row3[0]==$_POST["title_desc"]) echo "selected"; ?>> <?php echo $row3[1]?></option>
               <?php }else{ ?>
               <option value="<?php echo $row3[1]?>" > <?php echo stripslashes($row3[1])?></option>
               <?php } ?>
               <?php
							}
	 
	  	//complete the form
	
	echo '</select>';

	?>

            </div>
            </div>
            <div class="control-group">
              <label class="control-label">URLs : <font color="#FF0000"><b> *</b></font></label>
           
              <div class="controls">
              <input name="urls_system" type="text" class="span11" id="urls_system" size="20" value="<?php if(isset($_POST['urls_system'])) echo $_POST['urls_system']; ?>"  placeholder="Enter URLs System"/>
  
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Logo Company : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
 <input name="upload" type="file" class="textbox" value="<?php if(isset($_POST['upload'])) echo $_POST['upload']; ?>"maxlength="200" accept="image/x-png,image/gif,image/jpeg" /> 
              <p><span class="style3">Limit the size of an attachment is 2M.</span> </p>
            </div>
            </div>
             <div class="control-group">
              <label class="control-label">Company Code : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
             <input name="comp_code" type="text" class="span5" id="comp_code"  placeholder="Enter Company Code" value="<?php if(isset($_POST['comp_code'])) echo $_POST['comp_code']; ?>" size="20" maxlength="10"/>
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">SMTP Mail : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
             <input name="smtp_account" type="text" class="span11" id="smtp_account" size="20"  value="<?php if(isset($_POST['smtp_account'])) echo $_POST['smtp_account']; ?>"  placeholder="Enter SMTP Mail"/>
            </div>
            </div>
             <div class="control-group">
              <label class="control-label">E-mail System : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
             <input name="email_account" type="text" class="span11" id="email_account" size="60" maxlength="200" value="<?php if(isset($_POST['email_account'])) echo $_POST['email_account']; ?>" placeholder="Enter E-mail System" />
            </div>
            </div>
             <div class="control-group">
              <label class="control-label">FTP IP : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
             <input name="ftp_ip" type="text" class="span5" id="ftp_ip" size="20" value="<?php if(isset($_POST['ftp_ip'])) echo $_POST['ftp_ip']; ?>"  placeholder="Enter FTP IP"/>
            </div>
            </div>
            
              <div class="control-group">
              <label class="control-label">Status Setting : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
            <select name="status_system" id="status_system">
                   <?php if($_POST['submit'] == true)
						{ ?>
               <option value="AC" <?php if($_POST["status_system"] == 'AC') { ?> selected="selected"<?php } ?>>ACTIVE</option>
               <option value="NA" <?php if($_POST["status_system"] == 'NA') { ?> selected="selected"<?php } ?>>NON-ACTIVE</option>
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
