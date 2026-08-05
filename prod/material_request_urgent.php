<?php

/**
 * prod/material_request_urgent.php
 * Part of: Production module
 * Filename suggests: material request urgent
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, scan_detail, mat_master_header, factory_detail.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, tcpdf_barcodes_2d.php, top_modal_menu.php, left_production_menu.php, header.inc, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

set_time_limit(0);
// include 2D barcode class (search for installation path)
require_once('/tcpdf_barcodes_2d.php');

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "material_request_urgent.php";


    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
    $result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
    $res = mysqli_fetch_array($result2);
	
$today = getdate();
$hours = $today['hours']; 
$minutes = $today['minutes'];
$seconds = $today['seconds'];
$month = $today['mon']; 
$mday = $today['mday']; 
$year = $today['year']; 	

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

<style type="text/css" media="print"> 
/* div.page
      {
         page-break-after: always;
         page-break-inside: avoid;
       }
	   
	   */
	  
.breakAfter{ 
page-break-after: always; 
}
.breakBefore{
page-break-before: always ;
}
.tfoot { display: table-footer-group; }
.page {
 size:landscape;
   
}

 @media print{
  body{  margin-top: -1.8cm;  font-family: Arial, Helvetica, sans-serif; font-size:12px; }
  #ad{ display:none;}
  #leftbar{ display:none;}
  #contentarea{ width:100%;}
  .header, .hide { visibility: hidden }
  .tfoot { display: table-footer-group; }
  @page {size: landscape}
  /*tr.page-break  { display: block; page-break-before: always; }  */
  
} 

</style> 
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>

<script language="javascript" type="text/javascript">

function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
    }
	
	function getFactory(factory) {		
		
		var strURL="findWorkcenter.php?factory="+factory;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('work_centerdiv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
	
</script>



</head>

<body>


<!--Header-part-->
<div id="header">
  <h1>&nbsp;</h1>
</div>
<?php  include "top_modal_menu.php";   ?>
<!--close-Header-part--> 

<!--sidebar-menu-->
<?php include "left_production_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_production.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Material Request</a> <a href="#" class="current">Material Request Urgent</a> </div>
  <h1>Material Request</h1>
</div>

<div class="container-fluid">
  <hr>
  <div class="row-fluid">
  <!--  <div class="span6">-->
          <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li class="active"><a  role="tab" href="material_request_urgent.php">New Request</a></li>
              <li><a role="tab" href="display_request_urgent.php">Display Request</a></li>
              <li><a role="tab" href="posting_request_urgent.php">Posting Request</a></li>
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
   
   $material_no = $_POST["material_no"];
   $factory = $_POST["factory"];
   $work_center = $_POST["work_center"];
   $prod_order = $_POST["prod_order"];
   $user_no = $_POST["user_no"];
  
  
// check for a material no
if((empty($_POST["material_no"])) || ($_POST["material_no"] == "NULL"))
{ 
  $material_no = FALSE;
  $message.= '<p>You are required to select MATERIAL NO.!</p>';
  }
  
// check for a factory
if((empty($_POST["factory"])) || ($_POST["factory"] == "NULL"))
{ 
  $factory = FALSE;
  $message.= '<p>You are required to select FACTORY!</p>';
  }
  
 // check for a work center
if((empty($_POST["work_center"])) || ($_POST["work_center"] == "NULL"))
{ 
  $work_center = FALSE;
  $message.= '<p>You are required to enter PRODUCTION LINE/ WORK CENTER!</p>';
  }
  
$prod_order = escape_data($_POST['prod_order']);

if($material_no && $factory && $work_center) //everything ok
{  


//insert to scan_detail
$query_db = "INSERT INTO `scan_detail` (id_scan, pps_ref, factory, work_center, prod_order, material_no, scan_oum, scan_plant, scan_sloc, scan_qty, user_create, date_create, user_update, date_update, status_urgent) VALUES ('".mysqli_insert_id($dbc)."', '', '$factory', '$work_center', '$prod_order', '$material_no', '', '', '$factory', '', '$user_no', NOW(),'','','Y')";
$result = mysqli_query($dbc, $query_db) or die (mysqli_error($dbc));


             if($result)
             {
			 
			 $query_sql = "SELECT * FROM `scan_detail` WHERE id_scan = '".mysqli_insert_id($dbc)."'";
			 $result_sql = mysqli_query($dbc, $query_sql);
			 $data_sql = mysqli_fetch_array($result_sql);
			 
			 $query_sql2 = "SELECT * FROM `mat_master_header` WHERE material_no = '".$data_sql["material_no"]."'";
			 $result_sql2 = mysqli_query($dbc, $query_sql2);
			 $data_sql2 = mysqli_fetch_array($result_sql2);
			 
			 
	$query_update = "UPDATE `scan_detail` SET scan_oum = '".$data_sql2["BUn"]."', scan_plant = '".$data_sql2["plant"]."' WHERE id_scan = '".$data_sql["id_scan"]."'";
	$result_update =  mysqli_query($dbc, $query_update);
			 
			 
			 
echo "<script>";
echo "window.location='material_request_urgentProc.php?uid=$data_sql[id_scan]'";
echo "</script>";
			  exit(); //quit the script
             }
             else 
			 {
             $message = '<p> Cannot request Material Request. </p>';
             // mysqli_close($dbc); //close db
             }   
}
//print the message if there is one.
	 
	  
if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}
}

?>


<div class="widget-content nopadding">
         <form name="form1" method="post" action="material_request_urgent.php" class="form-horizontal">
           <div class="control-group">
              <label class="control-label">Factory : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
                 <select name="factory" id="factory" onChange="getFactory(this.value)">
                    <option value="NULL" placeholder="Select Factory"> -- Select Factory --</option>
                    <?php
	       $query3 = "SELECT * FROM factory_detail GROUP BY factory_desc2 ORDER BY id_fac ASC";
                   $result3 = mysqli_query($dbc, $query3);
  
                   while($row3=mysqli_fetch_array($result3)) 
			      {
                  echo'<option value="',$row3["factory_desc2"],'">',stripslashes($row3["factory_desc"]),'</option>';
                  }
				?>
                  </select>
              </div>
            </div>
             <div class="control-group">
            <label class="control-label">Production Line/Work Center : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
             <div id="work_centerdiv"> 
                <select name="work_center" id="work_center">
                <option value="NULL" placeholder="Select Production Line/ Work Center"> -- Select Production Line/ Work Center --</option>
                </select></div>
              </div>
            </div>
             <div class="control-group">
              <label class="control-label">Material No. : <font color="#FF0000"><b> *</b></font></label>
              <div class="controls">
                  <select name="material_no" id="material_no">
                     <option value="NULL" placeholder="Select Material No."> -- Select Material No. --</option>
                     <?php
	       $query2 = "SELECT * FROM mat_master_header WHERE status_BOM = 'Y' ORDER BY material_no ASC";
                   $result2 = mysqli_query($dbc, $query2);
  
                   while($row2=mysqli_fetch_array($result2)) 
			      {
                  echo'<option value="',$row2["material_no"],'">',stripslashes($row2["material_no"]),' - ',stripslashes($row2["material_desc"]),'</option>';
                  }
				?>
                   </select>
              </div>
            </div>
            <div class="control-group">
            <label class="control-label">Production Order : </label>
              <div class="controls">
            <input name="prod_order" type="text" id="prod_order" size="20" maxlength="15" placeholder="Enter Production Order" value="<?php if(isset($_POST['prod_order'])) echo $_POST['prod_order']; ?>" />
              </div>
            </div>
             <div class="control-group">
              <div class="controls">
             <input name="user_no" type="hidden" value="<?php echo $res["user_no"]; ?>" />
              </div>
            </div>
             <div class="control-group">
              <label class="control-label"><font color="#FF0000"><b>  * Compulsory field</b></font></label>
             <div class="controls">
            </div>
            </div>
            <div class="form-actions">
               <input name="submit" type="submit" id="submit" value="NEXT" class="btn btn-success">
               <input name="Reset" type="reset" id="Reset" class="btn btn-warning" value="CANCEL">
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
