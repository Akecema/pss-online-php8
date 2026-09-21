<?php

/**
 * admin/add_tbl_con_detail.php
 * Part of: Admin module
 * Filename suggests: add tbl con detail
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, consumable_detail, uom_con.
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
$url = "con_detail_table.php";

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
  <div id="breadcrumb"> <a href="index_admin.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Table Maintenance</a> <a href="#" class="current">Consumable Details</a> </div>
  <h1>Table Maintenance</h1>
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
   
$material_no = $_POST['material_no'];
$mat_desc = $_POST['mat_desc'];
$cost_center = $_POST['cost_center'];
$plant = $_POST['plant'];
$BUn = $_POST['BUn'];
$con_status = $_POST['con_status'];
  
// check for a material No.
if (empty($_POST['material_no']))
{ $material_no = FALSE;
  $message.= '<p>You are required to enter Material No.!</p>';
  }else
  { $material_no = escape_data($_POST['material_no']);
  }
  
// check for a material Desc
if (empty($_POST['mat_desc']))
{ $mat_desc = FALSE;
  $message.= '<p> You are required to enter Material Description!</p>';
  }
    else
  { $mat_desc = escape_data($_POST['mat_desc']);
  }
  
// check for a plant code
if (empty($_POST['plant']))
{ $plant = FALSE;
  $message.= '<p> You are required to enter Plant!</p>';
  }
    else
  { $plant = escape_data($_POST['plant']);
  }

// check for a cost center
if (empty($_POST['cost_center']))
{ $cost_center = FALSE;
  $message.= '<p>You are required to enter Cost Center!</p>';
  }
  else
  { $cost_center = escape_data($_POST['cost_center']);
  }

// check for BUn
if (empty($_POST['BUn']) || ($_POST['BUn'] == "NULL"))
{ $BUn = FALSE;
  $message.= '<p> You are required to select BUn!</p>';
  }
  else
  { $BUn = escape_data($_POST['BUn']);
  }

// check for con sstatus
if (empty($_POST['con_status']) || ($_POST['con_status'] == "NULL"))
{ $con_status = FALSE;
  $message.= '<p> You are required to select Status!</p>';
  }
  else
  { $con_status = escape_data($_POST['con_status']);
  }

  
  //---------------------------------------------------------------------------------------
  // material component 
  //----------------------------------------------------------------------------------------

   
 if($material_no && $mat_desc && $plant && $cost_center && $BUn && $con_status) //everything ok
 {  

//register the user in the db.
$query_db = "INSERT INTO consumable_detail(id_con,material_no, mat_desc, BUn, cost_center,plant,con_status) VALUES
                                ('','".db_esc($dbc, $material_no)."','".strtoupper($mat_desc)."','".db_esc($dbc, $BUn)."','".db_esc($dbc, $cost_center)."','".db_esc($dbc, $plant)."','".db_esc($dbc, $con_status)."')";
$result = mysqli_query($dbc, $query_db) or die(db_fail($dbc));


             if($result)
             {
echo "<script>";
echo "alert('Consumable is successfully created');";
echo "window.location='con_detail_table.php'";
echo "</script>";
			  exit(); //quit the script
             }
             else 
			 {
             $message = '<p> Cannot create consumable. </p>';
              mysqli_close($dbc); //close db
             }  
}
//print the message if there is one.
	  
	  
if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
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
              <li class="active"><a  role="tab" href="add_tbl_con_detail.php">Add Consumable</a></li>
              <li><a role="tab" href="con_detail_table.php">Display Consumable Details</a></li>
            </ul>
          </div>
          </div>
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Add Consumable</h5>
        </div>
        <div class="widget-content nopadding">
         <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" class="form-horizontal">
           <div class="control-group">
              <label class="control-label">Material No. * : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
              <input name="material_no" type="text" id="material_no" size="20" maxlength="8" value="<?php if(isset($_POST['material_no'])) echo h($_POST['material_no']); ?>" class="span11" placeholder="Enter Material No."/>
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Material Description * : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
                <input name="mat_desc" type="text" class="span11" id="mat_desc" size="55" maxlength="100" value="<?php if(isset($_POST['mat_desc'])) echo h($_POST['mat_desc']); ?>"  placeholder="Enter Material Description"/>
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Plant Code : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
         <input name="plant" type="text" class="span11" id="plant" size="20" maxlength="20" value="<?php if(isset($_POST['plant_code'])) echo h($_POST['plant']); ?>" placeholder="Enter Plant Code" />
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Cost Center :<font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
                 <input name="cost_center" type="text" class="span11" placeholder="Enter Cost Center"id="cost_center" size="20" maxlength="20" value="<?php if(isset($_POST['cost_center'])) echo h($_POST['cost_center']); ?>" />
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">BUn : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
             
             <?php		
 	      echo ' <select name="BUn" class="span5" id="BUn">
                 <option value="NULL"> --Select-- </option>';
  
                   $query7 = "SELECT * FROM uom_con ORDER BY UOM ASC";
                   $result7 = mysqli_query($dbc, $query7);
  
                   while($row7 = mysqli_fetch_array($result7, MYSQLI_NUM)) 
			      {
	
	 if($_POST['submit'] == true){ ?>
               <!--RETAIN VALUE-->
            <option value="<?php echo $row7[0]; ?>" <?php if($row7[0] == $_POST["BUn"]) { echo "selected"; } ?>> <?php echo $row7[0]; ?></option> 
               <?php }else{ ?>
               <option value="<?php echo $row7[0]; ?>"> <?php echo $row7[0]; ?></option>    
               <?php } 
							}
	 
	  	//complete the form
	
	echo '</select>';

	?>

            </div>
            </div>
             <div class="control-group">
              <label class="control-label">Status: <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
             <select name="con_status"  class="span5">
                   <option value="NULL" placeholder="Select Status"> -- Select Status --</option>
                            <?php if($_POST['submit'] == true)
						{ ?>
                            <option value="Y" <?php if($_POST["con_status"] == 'Y') { ?> selected="selected"<?php } ?>>Y - Active</option>
                            <option value="N" <?php if($_POST["con_status"] == 'N') { ?> selected="selected"<?php } ?>>N - Inactive</option>
                            <?php 
						}
						else
						{ ?>
                            <option value="Y">Y - Active</option>
                            <option value="N">N - Inactive</option>
                            <?php } ?> 
                   
                    </select>
            </div></div>
            
            
            
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
