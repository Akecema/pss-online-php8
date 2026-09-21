<?php

/**
 * admin/add_model_table.php
 * Part of: Admin module
 * Filename suggests: add model table
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, model_detail.
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
$url = "display_model_table.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
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
  <div id="breadcrumb"> <a href="index_admin.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Table Maintenance</a> <a href="#" class="current">Material Model</a> </div>
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
   
$model_name = $_POST['model_name'];
$model_desc = $_POST['model_desc'];
$comp_code = $_POST['comp_code'];

// check for a model_name.
if (empty($_POST['model_name']))
{ $model_name = FALSE;
  $message.= '<p>You are required to enter Model Code.!</p>';
  }else
  { $model_name = escape_data($_POST['model_name']);
  }
  

// check for a model_desc.
if (empty($_POST['model_desc']))
{ $model_desc = FALSE;
  $message.= '<p>You are required to enter Model Description.!</p>';
  }else
  { $model_desc = escape_data($_POST['model_desc']);
  }
  
// check for a company
if (empty($_POST['comp_code'])) 
{ 
  $comp_code = FALSE;
  $message.= '<p> You are required to enter Company!</p>';
  }
    else
  { $comp_code = escape_data($_POST['comp_code']);
  }

   
 if($model_name && $model_desc && $comp_code) //everything ok
{

//register the user in the db.
$query_db = "INSERT INTO model_detail(code_model,model_name,model_desc,comp_code) VALUES('','".strtoupper($model_name)."','".db_esc($dbc, $model_desc)."','".strtoupper($comp_code)."')";
$result = mysqli_query($dbc, $query_db) or die(mysqli_error($dbc));


             if($result)
             {
echo "<script>";
echo "alert('Model is successfully created');";
echo "window.location='display_model_table.php'";
echo "</script>";
			  exit(); //quit the script
             }
             else 
			 {
             $message = '<p><strong>Error!</strong> Cannot create Model of Material. </p>';
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
               <li class="active"><a  role="tab" href="add_model_table.php">Add Model</a></li>
              <li><a role="tab" href="display_model_table.php">Display Model</a></li>
            </ul>
          </div>
          </div>
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Add Model of Material</h5>
        </div>
        <div class="widget-content nopadding">
         <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" class="form-horizontal">
           <div class="control-group">
              <label class="control-label">Model Code : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
                <input name="model_name" type="text" id="model_name" size="20" maxlength="8" value="<?php if(isset($_POST['model_name'])) echo h($_POST['model_name']); ?>" class="span11" placeholder="Enter Model Code" />
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Model Desc. : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
               <input name="model_desc" type="text" class="span11" id="model_desc" size="55" maxlength="20" value="<?php if(isset($_POST['model_desc'])) echo h($_POST['model_desc']); ?>"  placeholder="Enter Model Description" />
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Company : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
           <input name="comp_code" type="text" class="span11" id="comp_code" size="55" maxlength="20" value="<?php if(isset($_POST['comp_code'])) echo h($_POST['comp_code']); ?>"  placeholder="Enter Company of Model" />
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
