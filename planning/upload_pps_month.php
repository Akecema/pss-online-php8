<?php
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");
ini_set("display_errors",0);
require_once 'excel_reader2.php';

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

 set_time_limit(0);
 ini_set('post_max_size', '5M');
 ini_set('upload_max_filesize', '5M');

 $url = "upload_pps_month.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
    $result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
    $res = mysqli_fetch_array($result2);
	
//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------		

$today = getdate();
$hours = $today['hours']; 
$minutes = $today['minutes'];
$seconds = $today['seconds'];
$month = $today['mon']; 
$mday = $today['mday']; 
$year = $today['year']; 		
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
<link rel="stylesheet" href="../css/uniform.css" />
<link rel="stylesheet" href="../css/select2.css" />
<link rel="stylesheet" href="../css/matrix-style.css" />
<link rel="stylesheet" href="../css/matrix-media.css" />
<link href="../font-awesome/css/font-awesome.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/jquery.gritter.css" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

<!----------------->
<link rel="stylesheet" href="../scripts/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="../javascript/jquery-latest.js"></script> 
<script type="text/javascript" src="../javascript/thickbox.js"></script>	
<link href="../calendar/calendar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../calendar/calendar.js"></script>

</head>
<body>

<!--Header-part-->
<div id="header">
  <h1>&nbsp;</h1>
</div>
<?php  include "top_modal_menu.php";   ?>
<!--close-Header-part--> 

<!--sidebar-menu-->
<?php include "left_planning_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_planning.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Planning Sheet (PPS)</a> <a href="#" class="current">Upload PPS</a> </div>
  <h1>Planning Sheet (PPS)</h1>
</div>

<div class="container-fluid">
  <hr>
  <div class="row-fluid">
  <!--  <div class="span6">-->
          <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li class="active"><a  role="tab" href="upload_pps_month.php">Upload PPS</a></li>
              <li><a role="tab" href="display_pps_month_reprint.php">PPS Listing</a></li>
            </ul>
          </div>
          </div>
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>New Request</h5>
        </div>



   
       <?php
// Set the page title and include the HTML header.
//include ('templates/header.inc');

if(isset($_POST['submit3'])) 
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
 $allowed = array("application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
 
 
 $upload = $_FILES['upload'];
 $date_plan = $_POST['date1'];
 $plan_category = $_POST['plan_category'];
 
 
          //check file name duplicate-----
 
            $query_chk_attach4 = "SELECT * FROM ftp_pps WHERE file_name = '".$_FILES["upload"]["name"]."'";
            $result_chk_attach4 = mysqli_query($dbc, $query_chk_attach4);   //run the query.
            $data_chk_attach4 = mysqli_fetch_array($result_chk_attach4);   //how many records are there?   
			 
			 if($data_chk_attach4 >= 1 )
			 {
			   
				echo "<script>";
                echo "alert('File already exist or rename file then upload.');";
                echo "window.location='upload_pps_month.php'";
                echo "</script>";
				exit();
			              		   
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
	
// check for date plan

     if(($_POST["date1"]) == "0000-00-00")
	{
	   $date_plan = FALSE;
	   $message.= '<p align="center">You are required to select Planning Date!</p>';
	 }else{
	   $date_plan = TRUE;
	 }

// check for plan category

	if(($_POST["plan_category"]) == "NULL")
     {
	     $plan_category = FALSE;
		 $message.= '<p align="center">You are required to select Plan Category!</p>';
	 }else{
		 $plan_category = TRUE;
	  }
	  
  
  if($_FILES['upload']['size'] > 0 && $date_plan && $plan_category) //everything ok
 {  	
   
  $plan_category = $_POST["plan_category"];
  $date_plan = $_POST["date1"];
   
	   //Add the record to the database
	   
	    $query = "INSERT INTO ftp_pps(upload_id, id_file, file_name, file_size, file_type, date_plan, user_upload, date_upload, user_update, date_update) VALUES('','','".$_FILES['upload']['name']."','".$_FILES['upload']['size']."','".$_FILES['upload']['type']."','".$date_plan."','$username',NOW(),'','')";
	   $result = mysqli_query($dbc, $query) or die (mysqli_error($dbc));   
	   
	  
	   if($result) {
	   //create the filename
	     $extension = explode ('.', $_FILES['upload']['name']);
		 $uid = mysqli_insert_id($dbc);  //upload ID
	
		 $filename = $uid.'.'.$extension[1];
		 
		 
		    $query_update2 = "UPDATE ftp_pps SET id_file = '".$uid."' WHERE upload_id = '".$uid."'";
			$result_update2 = mysqli_query($dbc, $query_update2) or die (mysqli_error($dbc));   
		 
		 
	 if(move_uploaded_file($_FILES['upload']['tmp_name'], "upload_pps/$filename"))  {
	
   
echo "<script>";
echo "alert('Congratulations! Your submission is successfully processed');";
echo "window.open('process_tran_pps.php?upload_id=$uid&&date1=$date_plan&&plan_category=$plan_category', 'myExample4', 'width=860,height=600')";
//echo "window.location='process_tran_pps.php?upload_id=$uid&&date1=$date_plan'";
//echo "window.location='display_pps_month.php'";
echo "</script>";
			  exit(); //quit the script
         
           } else {
			   
			   //-----------move file or delete----------
				  $extensionV = '.xls';
				  
				  unlink("upload_pps/".$uid.$extensionV); 
			
			echo "<script>";
            echo "alert('The document could not be moved.');";
            echo "window.location='upload_pps_month.php'";
            echo "</script>";
			

			   }
			   
			  } else {  //If the query did not run OK
			  
			echo "<script>";
            echo "alert('Your submission could not be processed due to a system error. We apologize for any inconvenience.');";
            echo "window.location='upload_pps_month.php'";
            echo "</script>";
			  
		
				}
				  mysqli_close($dbc);   // close database conn
				
				}
	  
if (isset($message))
{ echo '<div class="alert alert-error">', $message, '</div>';
}
}
?>


 <div class="widget-content nopadding">
          <form name="form1" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal">
        <input type="hidden" name="MAX_FILE_SIZE" value="1024000000">
             <div class="control-group">
              <label class="control-label">Planning Date : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
             <?php
			               if(isset($_POST['date1']))
								{ 
									
									//GET value
									$dd1 = substr($_POST['date1'],8,2);
									$mm1 = substr($_POST['date1'],5,2);
									$yy1 = substr($_POST['date1'],0,4);
									
									$myCalendar = new tc_calendar("date1", true, false);
									$myCalendar->setIcon("../calendar/images/iconCalendar.gif");
									$myCalendar->setDate($dd1, $mm1, $yy1);
									$myCalendar->setPath("/calendar/");
									$myCalendar->setYearInterval(2010, 2030);
									// $myCalendar->setOnChange("myChanged('test')");
									$myCalendar->writeScript();
									
								}
								else	 
								{
                                      
										$dt1 = $today['mday'];
										$mt1 = $today['mon'];
										$yr1 = $today['year'];
									 
										$myCalendar = new tc_calendar("date1", true, false);
										$myCalendar->setIcon("../calendar/images/iconCalendar.gif");
								        $myCalendar->setDate($dt1,$mt1,$yr1);
										$myCalendar->setPath("/calendar/");
										$myCalendar->setYearInterval(2010, 2030);
										// $myCalendar->setOnChange("myChanged('test')");
										$myCalendar->writeScript();
				
									}
					?>
             
             
            </div>
            </div>
            <div class="control-group">
              <label class="control-label">Choose File : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
 <input name="upload" type="file" class="textbox" value="<?php if(isset($_POST['upload'])) echo $_POST['upload']; ?>"maxlength="200" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" /> 
               <p><span class="style3">File must be less than 5MB.</span> </br>
               <span class="style3">Allowed file type : MS Excel (Format file .xls)</span> </p>
            </div>
            </div>
               <div class="control-group">
              <label class="control-label">Plan Category : <font color="#FF0000"><b> *</b></font></label>
             <div class="controls">
             <select name="plan_category" id="plan_category" class="span5">
                  <option value="NULL" placeholder="Select Plan Category"> -- Select Plan Category --</option>
                  <?php
	               $query19 = "SELECT * FROM plan_cat_pps ORDER BY id_plan ASC";
                   $result19 = mysqli_query($dbc, $query19);
  
                   while($row19=mysqli_fetch_array($result19)) 
			      {
				   ?>
                     <option value="<?php echo $row19["id_plan"]; ?>"> <?php echo $row19["plan_category_desc"]; ?></option>
                 
                  <?php
                  }
				?>
              </select> 
              
            </div>
            </div>
           
            <div class="control-group">
              <label class="control-label"><font color="#FF0000"><b>  * Compulsory field</b></font></label>
             <div class="controls">
            </div>
            </div>
            <div class="form-actions">
               <input name="submit3" type="submit" id="submit" value="SUBMIT" class="btn btn-success">
               <!--<input name="Reset" type="reset" id="Reset" class="btn btn-warning" value="CLEAR">-->
           </div>
          </form>
            </div>



</div></div></div></div>
</div>
<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part--> 

<script src="../js/jquery.min.js"></script> 
<script src="../js/jquery.ui.custom.js"></script> 
<script src="../js/bootstrap.min.js"></script> 
<script src="../js/jquery.uniform.js"></script> 
<script src="../js/select2.min.js"></script> 
<script src="../js/jquery.dataTables.min.js"></script> 
<script src="../js/matrix.js"></script> 
<script src="../js/matrix.tables.js"></script>



</body>
</html>
