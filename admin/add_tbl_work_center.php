<?php

/**
 * admin/add_tbl_work_center.php
 * Part of: Admin module
 * Filename suggests: add tbl work center
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, work_center_detail, factory_detail.
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
$url = "work_center_table.php";

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
  <div id="breadcrumb"> <a href="index_admin.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Table Maintenance</a> <a href="#" class="current">Work Center</a> </div>
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
   
   $id_work = $_POST['id_work'];
   $wc_desc = $_POST['wc_desc'];
   $cost_center = $_POST['cost_center'];
   $cc_desc = $_POST['cc_desc'];
   $plant_code = $_POST['plant_code'];
   $id_factory = $_POST['id_factory'];
  
// check for a id_work
if (empty($_POST['id_work']))
{ $id_work = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Work center!</p>';
  }

// check for a wc_desc.
if (empty($_POST['wc_desc']))
{ $wc_desc = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Work Center Description.!</p>';
  }else
  { $wc_desc = escape_data($_POST['wc_desc']);
  }
  
// check for a plant code
if (empty($_POST['plant_code']))
{ $plant_code = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Plant!</p>';
  }
    else
  { $plant_code = escape_data($_POST['plant_code']);
  }

// check for a cost center
if (empty($_POST['cost_center']))
{ $cost_center = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Cost Center!</p>';
  }
  else
  { $cost_center = escape_data($_POST['cost_center']);
  }

// check for a cost center Desc
if (empty($_POST['cc_desc']))
{ $cc_desc = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Cost Center Description!</p>';
  }
    else
  { $cc_desc = escape_data($_POST['cc_desc']);
  }

// check for factory
if (empty($_POST['id_factory']) || ($_POST['id_factory'] == "NULL"))
{ $id_factory = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to select Factory!</p>';
  }
  else
  { $id_factory = escape_data($_POST['id_factory']);
  }
  
 if($id_work && $wc_desc && $plant_code && $cost_center && $cc_desc && $id_factory) //everything ok
 {  

//register the user in the db.
$query_db = "INSERT INTO work_center_detail(id_work,plant_code,wc_desc,cost_center,cc_desc,id_factory) VALUES
                                ('$id_work','$plant_code','$wc_desc','$cost_center','$cc_desc','$id_factory')";
$result = mysqli_query($dbc, $query_db) or die (mysqli_error($dbc));


             if($result)
             {
echo "<script>";
echo "alert('Work Center is successfully created');";
echo "window.location='work_center_table.php'";
echo "</script>";
			  exit(); //quit the script
             }
             else 
			 {
             $message = '<p><strong>Error!</strong> Cannot Work Center. </p>';
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
              <li class="active"><a  role="tab" href="add_tbl_work_center.php">Add Work Center</a></li>
              <li><a role="tab" href="work_center_table.php">Display Work Center</a></li>
            </ul>
          </div>
          </div>
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Add Work Center</h5>
        </div>
        <div class="widget-content nopadding">
         <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" class="form-horizontal">
           <div class="control-group">
              <label class="control-label">Work Center : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
                <input name="id_work" type="text" id="id_work" size="20" maxlength="8" value="<?php if(isset($_POST['id_work'])) echo $_POST['id_work']; ?>" class="span11" placeholder="Enter Work Center" />
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Work Center Description : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
               <input name="wc_desc" type="text" class="span11" id="wc_desc" size="55" maxlength="20" value="<?php if(isset($_POST['wc_desc'])) echo $_POST['wc_desc']; ?>"  placeholder="Enter Work Center Description" />
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Plant Code : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
         <input name="plant_code" type="text" class="span11" id="plant_code" size="20" maxlength="20" value="<?php if(isset($_POST['plant_code'])) echo $_POST['plant_code']; ?>" placeholder="Enter Plant Code" />
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Cost Center :<font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
                 <input name="cost_center" type="text" class="span11" placeholder="Enter Cost Center"id="cost_center" size="20" maxlength="20" value="<?php if(isset($_POST['cost_center'])) echo $_POST['cost_center']; ?>" />
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Cost Center Description : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
             <input name="cc_desc" type="text" class="span11" id="cc_desc" size="55" maxlength="100" value="<?php if(isset($_POST['cc_desc'])) echo $_POST['cc_desc']; ?>" placeholder="Enter Cost Center Description"/>
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Factory : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
             
             <?php		
 	      echo ' <select name="id_factory" class="span11" id="id_factory">
                 <option value="NULL"> --Select-- </option>';
  
                   $query3 = "SELECT * FROM factory_detail GROUP BY factory_desc2 ORDER BY id_fac ASC";
                   $result3 = mysqli_query($dbc, $query3);
  
                   while($row3=mysqli_fetch_array($result3, MYSQLI_NUM)) 
			      {
	
	 if($_POST['submit'] == true){ ?>
               <!--RETAIN VALUE-->
               <option value="<?php echo $row3[2]?>" <?php if($row3[2]==$_POST["id_factory"]) echo "selected"; ?>> <?php echo $row3[1]?></option>
               <?php }else{ ?>
               <option value="<?php echo $row3[2]?>" > <?php echo stripslashes($row3[1])?></option>
               <?php } ?>
               <?php
							}
	 
	  	//complete the form
	
	echo '</select>';

	?>

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
