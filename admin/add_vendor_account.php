<?php

/**
 * admin/add_vendor_account.php
 * Part of: Admin module
 * Filename suggests: add vendor account
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, vendor_detail.
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
$url = "add_vendor_account.php";

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
<style>
@media print 
{
	@page {
      size: A4; /* DIN A4 standard, Europe */
      margin-top:10mm;
	  margin-bottom:10mm;
    }
	
  a[href]:after { content: none !important; }
  img[src]:after { content: none !important; }
}

</style>
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
  <div id="breadcrumb"> <a href="index_admin.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Table Maintenance</a> <a href="#" class="current">Vendor Account</a> </div>
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
   
   $vendor_code = $_POST["vendor_code"];
   $vendor_name = $_POST["vendor_name"];
   $add_no1 = $_POST["add_no1"];
   $add_no2 = $_POST["add_no2"];
   $post_code = $_POST["post_code"];
   $post_city = $_POST["post_city"];
   $post_region = $_POST["post_region"];
   $post_country = $_POST["post_country"];
   $search_term = $_POST["search_term"];
   $tphone = $_POST["tphone"];
   $fax_no = $_POST["fax_no"];
   $payment_method = $_POST["payment_method"];
   $term_payment = $_POST["term_payment"];
   $status_acc = $_POST["status_acc"];
   $status_subcont = $_POST["status_subcont"];
   
   $tphone = escape_data($_POST["tphone"]);
   $fax_no = escape_data($_POST["fax_no"]);
   $payment_method = escape_data($_POST["payment_method"]);
   $term_payment = escape_data($_POST["term_payment"]);
  
// check for a vendor code
if (empty($_POST["vendor_code"]))
{ $vendor_code = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Vendor Code!</p>';
  }

// check for a vendor_name
if (empty($_POST["vendor_name"]))
{ $vendor_name = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Vendor Name!</p>';
  }else
  { $vendor_name = escape_data($_POST["vendor_name"]);
  }
  
// check for a address no 1
if (empty($_POST["add_no1"]))
{ $add_no1 = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Address No. 1!</p>';
  }
    else
  { $add_no1 = escape_data($_POST["add_no1"]);
  }
  
  // check for a address no 2
if (empty($_POST["add_no2"]))
{ $add_no2 = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Address No. 2!</p>';
  }
    else
  { $add_no2 = escape_data($_POST["add_no2"]);
  }

// check for a post_code
if (empty($_POST["post_code"]))
{ $post_code = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Postcode!</p>';
  }
  else
  { $post_code = escape_data($_POST["post_code"]);
  }

// check for a post city
if (empty($_POST["post_city"]))
{ $post_city = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter City!</p>';
  }
    else
  { $post_city = escape_data($_POST["post_city"]);
  }
  
  // check for a post region
if (empty($_POST["post_region"]))
{ $post_region = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Region!</p>';
  }
    else
  { $post_region = escape_data($_POST["post_region"]);
  }

// check for a post country
if (empty($_POST["post_country"]))
{ $post_country = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Country!</p>';
  }
    else
  { $post_country = escape_data($_POST["post_country"]);
  }
  
  // check for a search term
if (empty($_POST["search_term"]))
{ $search_term = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Search Term!</p>';
  }
    else
  { $search_term = escape_data($_POST["search_term"]);
  }

// check for status account
if (empty($_POST["status_acc"]) || ($_POST["status_acc"] == ""))
{ $status_acc = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to select Status Account!</p>';
  }
  else
  { $status_acc = escape_data($_POST["status_acc"]);
  }
  
  // check for status subcont
if (empty($_POST["status_subcont"]) || ($_POST["status_subcont"] == ""))
{ $status_subcont = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to select Status Subcont!</p>';
  }
  else
  { $status_subcont = escape_data($_POST["status_subcont"]);
  }
  
 if($vendor_code && $vendor_name && $add_no1 && $add_no2 && $post_code && $post_city && $post_region && $post_country && $search_term && $status_acc && $status_subcont) //everything ok
 {  

//insert vendor detail

$query_db = "INSERT INTO vendor_detail(vendor_code,vendor_name,add_no1,add_no2,post_code,post_city,post_region,post_country,search_term,tphone,fax_no,payment_method,term_payment,user_create,date_create,user_update,date_update,status_acc,status_subcont) VALUES('".strtoupper($vendor_code)."','".strtoupper($vendor_name)."','".db_esc($dbc, $add_no1)."','".db_esc($dbc, $add_no2)."','".db_esc($dbc, $post_code)."','".db_esc($dbc, $post_city)."','".db_esc($dbc, $post_region)."','".db_esc($dbc, $post_country)."','".strtoupper($search_term)."','".db_esc($dbc, $tphone)."','".db_esc($dbc, $fax_no)."','".strtoupper($payment_method)."','".strtoupper($term_payment)."','".db_esc($dbc, $username)."',NOW(),'','','".db_esc($dbc, $status_acc)."','".db_esc($dbc, $status_subcont)."')";
$result = mysqli_query($dbc, $query_db) or die (mysqli_error($dbc));


             if($result)
             {
echo "<script>";
echo "alert('Account of vendor is successfully created');";
echo "window.location='add_vendor_account.php'";
echo "</script>";
			  exit(); //quit the script
             }
             else 
			 {
             $message = '<p><strong>Error!</strong> Cannot create account of VENDOR. </p>';
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
              <li class="active"><a  role="tab" href="add_vendor_account.php">Add Vendor</a></li>
              <li><a role="tab" href="vendor_account_table.php">Display Vendor</a></li>
            </ul>
          </div>
          </div>
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Add Vendor</h5>
        </div>
        <div class="widget-content nopadding">
         <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" class="form-horizontal">
           <div class="control-group">
              <label class="control-label">Vendor Code : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
                <input name="vendor_code" type="text" id="vendor_code" size="20" maxlength="8" value="<?php if(isset($_POST['vendor_code'])) echo h($_POST['vendor_code']); ?>" class="span5" placeholder="Enter Vendor Code" />
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Vendor Name : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
               <input name="vendor_name" type="text" class="span11" id="vendor_name" size="55" value="<?php if(isset($_POST['vendor_name'])) echo h($_POST['vendor_name']); ?>"  placeholder="Enter Vendor Name" />
            </div>
            </div>
             <div class="control-group">
              <label class="control-label">Search Term : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
             <input name="search_term" type="text" class="span5" id="search_term" size="20" maxlength="20" value="<?php if(isset($_POST['search_term'])) echo h($_POST['search_term']); ?>" placeholder="Enter Search Term"/>
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Address No. 1 : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
         <input name="add_no1" type="text" class="span11" id="add_no1" size="20" value="<?php if(isset($_POST['add_no1'])) echo h($_POST['add_no1']); ?>" placeholder="Enter Address No. 1" />
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Address No. 2 :<font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
                 <input name="add_no2" type="text" class="span11" placeholder="Enter Address No. 2"id="add_no2" size="20" value="<?php if(isset($_POST['add_no2'])) echo h($_POST['add_no2']); ?>" />
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Postcode : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
             <input name="post_code" type="text" class="span5" id="post_code" size="20" maxlength="15" value="<?php if(isset($_POST['post_code'])) echo h($_POST['post_code']); ?>" placeholder="Enter Postcode"/>
            </div>
            </div>
               <div class="control-group">
              <label class="control-label">City : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
             <input name="post_city" type="text" class="span11" id="post_city" size="20" value="<?php if(isset($_POST['post_city'])) echo h($_POST['post_city']); ?>" placeholder="Enter City"/>
            </div>
            </div>
             <div class="control-group">
              <label class="control-label">Region : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
             <input name="post_region" type="text" class="span11" id="post_region" size="20" value="<?php if(isset($_POST['post_region'])) echo h($_POST['post_region']); ?>" placeholder="Enter Region"/>
            </div>
            </div>
             <div class="control-group">
              <label class="control-label">Country : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
             <input name="post_country" type="text" class="span11" id="post_country" size="20" value="<?php if(isset($_POST['post_country'])) echo h($_POST['post_country']); ?>" placeholder="Enter Country"/>
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Phone No. : </label>
             <div class="controls">
             <input name="tphone" type="text" class="span5" id="tphone" size="20" maxlength="15" value="<?php if(isset($_POST['tphone'])) echo h($_POST['tphone']); ?>" placeholder="Enter Phone No."/>
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Fax No. : </label>
             <div class="controls">
             <input name="fax_no" type="text" class="span5" id="fax_no" size="20" maxlength="15" value="<?php if(isset($_POST['fax_no'])) echo h($_POST['fax_no']); ?>" placeholder="Enter Fax No."/>
            </div>
            </div>
             <div class="control-group">
              <label class="control-label">Payment Method : </label>
             <div class="controls">
             <input name="payment_method" type="text" class="span5" id="payment_method" size="20" maxlength="15" value="<?php if(isset($_POST['payment_method'])) echo h($_POST['payment_method']); ?>" placeholder="Enter Payment Method"/>
            </div>
            </div>
              <div class="control-group">
              <label class="control-label">Term Payment : </label>
             <div class="controls">
             <input name="term_payment" type="text" class="span5" id="term_payment" size="20" maxlength="15" value="<?php if(isset($_POST['term_payment'])) echo h($_POST['term_payment']); ?>" placeholder="Enter Term Payment"/>
            </div>
            </div>
             <div class="control-group">
              <label class="control-label">Status Account: <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
           <select name="status_acc" id="status_acc">
           <option value=""> --- Select --- </option>
                   <?php if($_POST['submit'] == true)
						{ ?>
               <option value="Y" <?php if($_POST["status_acc"] == 'Y') { ?> selected="selected"<?php } ?>>ACTIVE</option>
               <option value="N" <?php if($_POST["status_acc"] == 'N') { ?> selected="selected"<?php } ?>>INACTIVE</option>
               <?php 
						}
						else
						{ ?>
               <option value="Y">ACTIVE</option>
               <option value="N">INACTIVE</option>
               <?php } ?>
                 </select>
            </div>
            </div>
             <div class="control-group">
              <label class="control-label">Status Subcont : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
           <select name="status_subcont" id="status_subcont">
           <option value=""> --- Select --- </option>
                   <?php if($_POST['submit'] == true)
						{ ?>
               <option value="Y" <?php if($_POST["status_subcont"] == 'Y') { ?> selected="selected"<?php } ?>>YES</option>
               <option value="N" <?php if($_POST["status_subcont"] == 'N') { ?> selected="selected"<?php } ?>>NO</option>
               <?php 
						}
						else
						{ ?>
               <option value="Y">YES</option>
               <option value="N">NO</option>
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
