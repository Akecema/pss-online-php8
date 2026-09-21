<?php

/**
 * admin/bc nor/material_component_upload.php
 * Part of: Admin module
 * Filename suggests: material component upload
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST); handles a file upload.
 * Database tables referenced: user_detail, sys_setup_maintain, upload_mm60.
 * Includes: config.php, config_mail.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_admin_menu.php, footer.php.
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

include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");
date_default_timezone_set('Asia/Bangkok');

set_time_limit(0);
ini_set('post_max_size', '2M');
ini_set('upload_max_filesize', '2M');

$uploadedStatus = 0;


// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$url = "material_component_upload.php";

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

<script type="text/javascript">
function printPage(iFid){
iFid.focus();
iFid.print();
}
</script>
<style>
#iframe1{
visibility:hidden;
}
</style>
 
<?php

function _time_diff($hour_a, $hour_b){
   $y = date('Y-m-d').' ';
   return (int)((strtotime($y.$hour_b) - strtotime($y.$hour_a)) / 60);
}


?>
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


<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_admin.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Table Maintenance</a> <a href="#" class="current">Material Master Data Upload</a> </div>
  <h1>Finish Goods / Semi-Finish Goods Data Upload</h1>
</div>


<div class="container-fluid">
  <hr>
  <div class="row-fluid">
  <!--  <div class="span6">-->
          <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li><a  role="tab" href="material_master_list.php">Material Master</a></li>
              <li><a role="tab" href="material_master_upload.php">Material Master Upload</a></li>
              <li class="active"><a role="tab" href="material_component_upload.php">Material Component Upload</a></li>
            </ul>
          </div>
          </div>

    <form role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="form1"  enctype="multipart/form-data">
          <input type="hidden" name="MAX_FILE_SIZE" value="2097152">
        
          <table width="600" style="margin:40px auto; background:#f8f8f8; border:1px solid #eee; padding:10px;">
<tr><td colspan="2" style="font:bold 21px arial; text-align:center; border-bottom:1px solid #eee; padding:5px 0 10px 0;">&nbsp;</td>
</tr>

<tr><td colspan="2" style="font:bold 15px arial; text-align:center; padding:0 0 5px 0;">Data Uploading System</td></tr>

<tr>

<td width="50%" style="font:bold 12px tahoma, arial, sans-serif; text-align:right; border-bottom:1px solid #eee; padding:5px 10px 5px 0px; border-right:1px solid #eee;">Select material component</td>

<td width="50%" style="border-bottom:1px solid #eee; padding:5px;">


<input type="file" name="fileUpload" id="file" /></td>
</tr>
<tr>

<td style="font:bold 12px tahoma, arial, sans-serif; text-align:right; padding:5px 10px 5px 0px; border-right:1px solid #eee;">Submit</td>

<td width="50%" style=" padding:5px;">


 <input name="Submit2" type="submit"  class="btn btn-success" id="Submit2" value="UPLOAD" />
</td>
</tr>
</table>
</form>
<?php
if(isset($_POST["Submit2"]))
{

// create a function for escaping the data.
function escape_data ($data) {
global $dbc;   // need the connection.
if(ini_get('magic_quotes_gpc')) {
$data = stripslashes($data);
}
return mysqli_real_escape_string($dbc, $data);
}   // end function.
$message = NULL; // create an empty new variable.

			
  //-----file attachment detail-------------------
  
	$fileType = $_FILES['fileUpload']['type'];
    $fileSize = $_FILES['fileUpload']['size'];
    $fileUpload = $_FILES['fileUpload']; 
    $allowed = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/vnd.ms-excel");
	
	//echo $fileSize;
	//echo $_FILES["fileUpload"]["name"];
	
	//-----------------------------------
	
	
// check for a upload file
if($_FILES["fileUpload"]["size"] == 0 || empty($_FILES["fileUpload"]["tmp_name"]))
{ 
	$upload = FALSE;
	$message.= '<p> You are required to select UPLOAD FILE!</p>';
}	 
elseif(!in_array($fileType, $allowed)) 
{
	 $upload = FALSE;
	 $message.= '<p> Only EXCEL files are allowed.</p>';

}
elseif(in_array($fileType, $allowed)) 
{
	if($_FILES['fileUpload']['size'] > (2097152))
	{ 
		$upload = FALSE;
		$message .= '<p> File too large. File must be less than 2 megabytes.</p>'; 
	}
  
	$upload = TRUE; 
	$storagename = $_FILES["fileUpload"]["name"];	 
	move_uploaded_file($_FILES["fileUpload"]["tmp_name"], "../BOM_upload/$storagename" );
	$uploadedStatus = 1;

	//insert table upload_mb52

	/*$query_upload = "INSERT INTO upload_mm60(id_upload,file_name,file_size,file_type,date_upload,pic_upload, status_upload) VALUES ('','".$_FILES["fileUpload"]["name"]."', '".$_FILES["fileUpload"]["size"]."', '".$_FILES["fileUpload"]["type"]."',NOW(),'".$data_u["staff_ID"]."','Y')";		
	$result_upload = mysqli_query($dbc, $query_upload) or die (mysqli_error($dbc));*/
}  
 

if (isset($message))
{ 
	echo '<font color="red" class ="error_entry">', $message, '</font>';
}
	
//if there was an error uploading the file
if ($_FILES["fileUpload"]["error"] > 0) 
{
	echo "Return Code: " . $_FILES["fileUpload"]["error"] . "<br />";
}
else 
{
	if (file_exists($_FILES["fileUpload"]["name"])) 
	{	
		unlink($_FILES["fileUpload"]["name"]);
	}
}
} 
else 
{
	echo "No file selected <br />";
}
	

//}


if($uploadedStatus == 1)
{
	echo "<script>";
	echo "window.location='material_component_uploadProc.php?file=$storagename';";
	echo "</script>";
	exit(); //quit the script  
}

?>


  
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

