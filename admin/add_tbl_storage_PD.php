<?php

/**
 * admin/add_tbl_storage_PD.php
 * Part of: Admin module
 * Filename suggests: add tbl storage PD
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, storage_tbl.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_admin_menu.php, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "add_tbl_storage_PD.php";

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
  <div id="breadcrumb"> <a href="index_admin.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Table Maintenance</a> <a href="#" class="current">Production Storage Location </a> </div>
  <h1>Table Maintenance</h1>
</div>

<?php

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
   
$slocCD = $_POST['sloc_code'];
$slocDC = $_POST['sloc_desc'];
  

// check for a wastage_desc.
if (empty($_POST['sloc_code']))
{ 
	$slocCD = FALSE;
	$message.= '<p>You are required to enter Storage Location Code!</p>';
}
else
{ 
	$slocCD = escape_data($_POST['sloc_code']);
}


// check for a status
if (empty($_POST['sloc_desc'])) 
{ 
	$slocDC = FALSE;
	$message.= '<p> You are required to enter storage location description</p>';
}
else
{ 
	$slocDC = escape_data($_POST['sloc_desc']);
}


if($slocCD && $slocDC) //everything ok
{
	//register the user in the db.
	$query_db = "INSERT INTO storage_tbl(sloc_code,sloc_desc) VALUES('".$slocCD."','".$slocDC."')";
	$result = mysqli_query($dbc, $query_db) or die (mysqli_error($dbc));
	
	if($result)
	{
		echo "<script>";
		echo "alert('Production storage location is successfully created.');";
		echo "window.location='storage_PD_list.php'";
		echo "</script>";
		exit(); //quit the script
	}
	else 
	{
		$message = '<p><strong>Error!</strong> Cannot create production location. </p>';
		mysqli_close($dbc); //close db
	}  
}
//print the message if there is one.
	  
	  
if (isset($message))
{ 
	echo '<div class="alert alert-error">', $message, '</div>';
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
              <li class="active"><a role="tab" href="add_tbl_storage_PD.php">Production Storage Location </a></li>
              <li><a role="tab" href="storage_PD_list.php">Production Storage Location List</a></li>
            </ul>
          </div>
          </div>
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5><a role="tab" href="add_tbl_storage_PD.php">Production </a>Storage Location</h5></div>
        <div class="widget-content nopadding">
         <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" class="form-horizontal">
       <!--    <div class="control-group">
              <label class="control-label">ID Type : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
                <input name="id_wastage" type="text" id="id_wastage" size="20" maxlength="8" value="<?php if(isset($_POST['id_wastage'])) echo $_POST['id_wastage']; ?>" class="span11" placeholder="Enter ID Type Wastage" />
            </div>
            </div>-->
            <div class="control-group">
              <label class="control-label">Storage Location Code : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
               <input name="sloc_code" type="text" class="span11" id="sloc_code" size="55" maxlength="20" value="<?php if(isset($_POST['sloc_code'])) echo $_POST['sloc_code']; ?>"  placeholder="Enter Storage Location " />
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Storage Location : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
                <input name="sloc_desc" type="text" class="span11" id="sloc_desc" size="55" maxlength="20" value="<?php if(isset($_POST['sloc_desc'])) echo $_POST['sloc_desc']; ?>"  placeholder="Enter Storage Location Description" />
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
