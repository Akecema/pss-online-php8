<?php

/**
 * ppc_store/document_list_TP_to_subcont.php
 * Part of: PPC Store module
 * Filename suggests: document list TP to subcont
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, vendor_detail.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_ppc_menu.php, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
error_reporting(E_ALL &~ E_NOTICE &~ E_DEPRECATED);
session_start();
$username = $_SESSION['username'] ?? '';
include '../include/config.php';
require_once '../include/auth.php';
require_role($dbc, 12);
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$today = getdate();
$hours = $today['hours']; 
$minutes = $today['minutes'];
$seconds = $today['seconds'];
$month = $today['mon']; 
$mday = $today['mday']; 
$year = $today['year']; 

$url = "document_list_TP_to_store.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
    $result2 = mysqli_query($dbc, $query2) or die(db_fail($dbc));
    $res = mysqli_fetch_array($result2);
	
//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------	
//--------------------------------------------------		
//CR status (Cancelled Posting)
$sta4 = "SELECT * from request_status WHERE status_id = '4'";
$sta_res4 = mysqli_query($dbc, $sta4);
$rst_sta4 = mysqli_fetch_array($sta_res4);	
			 
//CR status (Transfer Posting)
$sta19 = "SELECT * from request_status WHERE status_id = '19'";
$sta_res19 = mysqli_query($dbc, $sta19);
$rst_sta19 = mysqli_fetch_array($sta_res19);	

//---------------------------------------------------------	        
	?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo h($data_setup["title_desc"]); ?></title>
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
@page {
    size: A4;
   /* margin: 0;*/
    margin-top: 1.5cm;
	margin-bottom: 1.5cm;
}
@media print {
    .noprint {display:none !important;}
    a:link:after, a:visited:after {  
      display: none;
      content: ""; 
	     
    }
	 html, body {
    width: 28cm;
    height: 29cm;
  }
}

</style>
<?php
//echo "the following values have been checked: ";
$number = "";
$checked="";
$amount ="";
$amount2 ="";
$barcode_ref = "";
$uid = "";
    
	 

$a = array();
if(isset($_POST["cancel"])) {
	foreach($_POST["cancel"] as $j=>$i) {
		
		$amount .= $_POST["scan_qty"][$i]."|";
	    $amount2 .= $_POST["item_no"][$i]."|";
		$checked .= ($checked==""?"":",") . "checkbox" . $i;

		
		array_push($a, $i);
	   // array_push($amount2, $i);
	}
}

 // echo $checked;
 //echo $amount;

function was_checked($i,$a) {
if(in_array($i, $a)===true) {
return "checked='checked'";
return "";
}
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
<?php include "left_ppc_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_ppc_store.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Transfer Posting</a> <a href="#" class="current">Document List</a> </div>
  <h1>Document List Transfer to Subcont</h1>
</div>

 <div class="container-fluid">
    <hr>
    <div class="row-fluid">
    <!--  <div class="span12">-->
        <div class="widget-box">
          <div class="widget-title">
             <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li><a  role="tab" href="document_list_TP_to_store.php">TP to Store</a></li>
              <li><a role="tab" href="document_list_TP_to_plb.php">TP to PLB</a></li>
              <li class="active"><a role="tab" href="document_list_TP_to_subcont.php">TP to Subcont</a></li>
              <li><a role="tab" href="document_list_RT_to_plb.php">Return from PLB</a></li>
              <li><a role="tab" href="document_list_RT_to_subcont.php">Return from Subcont</a></li>
             </ul>
             
          </div>
          </div>
  

       
            <form action="" method="post" name="frmSearch" id="frmSearch">
            <table class="table table-bordered table-striped">
              <tr>
                  <th width="184">Posting Date From :</th>
                  <td width="308"><?php
    
			
				 if(isset($_POST["date1"]))
								{ 
									
									//GET value
									$dd1 = substr($_POST["date1"],8,2);
									$mm1 = substr($_POST["date1"],5,2);
									$yy1 = substr($_POST["date1"],0,4);
									
									$myCalendar = new tc_calendar("date1", true, false);
									$myCalendar->setIcon("../calendar/images/iconCalendar.gif");
									$myCalendar->setDate($dd1, $mm1, $yy1);
									$myCalendar->setPath("/calendar/");
									$myCalendar->setYearInterval(date('Y') - 1, date('Y') + 10);
									// $myCalendar->setOnChange("myChanged('test')");
									$myCalendar->writeScript();
									
								}
								else	 
								{
                                      
										$dt = $today["mday"];
										$mt = $today["mon"];
										$yr = $today["year"];
									 
										$myCalendar = new tc_calendar("date1", true, false);
										$myCalendar->setIcon("../calendar/images/iconCalendar.gif");
										$myCalendar->setDate($dt, $mt, $yr);
										$myCalendar->setPath("/calendar/");
										$myCalendar->setYearInterval(date('Y') - 1, date('Y') + 10);
										// $myCalendar->setOnChange("myChanged('test')");
										$myCalendar->writeScript();
				
									}

?></td>
                  <th width="178">Posting Date To :</th>
                  <td width="359" colspan="2">
                <?php
			               if(isset($_POST["date2"]))
								{ 
									
									//GET value
									$dd2 = substr($_POST["date2"],8,2);
									$mm2 = substr($_POST["date2"],5,2);
									$yy2 = substr($_POST["date2"],0,4);
									
									$myCalendar = new tc_calendar("date2", true, false);
									$myCalendar->setIcon("../calendar/images/iconCalendar.gif");
									$myCalendar->setDate($dd2, $mm2, $yy2);
									$myCalendar->setPath("/calendar/");
									$myCalendar->setYearInterval(date('Y') - 1, date('Y') + 10);
									// $myCalendar->setOnChange("myChanged('test')");
									$myCalendar->writeScript();
									
								}
								else	 
								{
                                      
										$dt2 = $today["mday"];
										$mt2 = $today["mon"];
										$yr2 = $today["year"];
									 
										$myCalendar = new tc_calendar("date2", true, false);
										$myCalendar->setIcon("../calendar/images/iconCalendar.gif");
								        $myCalendar->setDate($dt2,$mt2,$yr2);
										$myCalendar->setPath("/calendar/");
										$myCalendar->setYearInterval(date('Y') - 1, date('Y') + 10);
										// $myCalendar->setOnChange("myChanged('test')");
										$myCalendar->writeScript();
				
									}
					?></td>
                </tr>
               
              
               <tr>
                 <th>Material Document No. From  :</th>
                 <td><input name="doc_tp_from" type="text" id="doc_tp_from" size="50" class="span11"  value="<?php if(isset($_POST["doc_tp_from"])) { echo h($_POST["doc_tp_from"]); } ?>"/></td>
                 <th>Material Document No. To :</th>
                 <td><input name="doc_tp_to" type="text" id="doc_tp_to" size="50" class="span11"  value="<?php if(isset($_POST["doc_tp_to"])) { echo h($_POST["doc_tp_to"]); } ?>"/></td>
               </tr>
               <tr>
              <th>Vendor :</th>
              <td><?php		
 	echo '<select name="vendor_no" class="span11">
  <option value="NULL"> --Select Vendor -- </option>';
  
  //Retrieve and display the available vendor detail
  $query2a = "SELECT * FROM vendor_detail WHERE status_acc = 'Y' AND status_subcont = 'Y'";
  $result2a = mysqli_query($dbc, $query2a);
  
      while($row2a =mysqli_fetch_array($result2a)) {

        if($_POST["Submit"] == true){ ?>
               <!--RETAIN VALUE-->
               <option value="<?php echo h($row2a["vendor_code"]); ?>" <?php if($row2a["vendor_code"] == $_POST["vendor_no"]) echo "selected"; ?>> <?php echo h($row2a["vendor_name"]); ?></option>
               <?php }else{ ?>
               <option value="<?php echo h($row2a["vendor_code"]); ?>" > <?php echo stripslashes($row2a["vendor_name"]); ?></option>
               <?php } ?>
               <?php
							}
					
	//complete the form
	
	echo '</select>';

	?></td>
              <th>Shift :</th>
              <td>                 
                  <select name="shift_day" id="shift_day" class="span11">
                  <option value="NULL" placeholder="Select Shift"> -- Select Shift --</option>
                   <?php if($_POST["Submit"] == true)
						{ ?>
               <option value="D/S" <?php if($_POST["shift_day"] == 'D/S') { ?> selected="selected"<?php } ?>>D/S</option>
               <option value="N/S" <?php if($_POST["shift_day"] == 'N/S') { ?> selected="selected"<?php } ?>>N/S</option>
               <?php 
						}
						else
						{ ?>
               <option value="D/S">D/S</option>
               <option value="N/S">N/S</option>
               <?php } ?>
                 </select>
                  
                  
                 </td>
               </tr>
               <tr>
                 <th>Part No. :</th>
                 <td><input name="material_no" type="text" id="material_no" size="50" class="span11" value="<?php if(isset($_POST["material_no"])) { echo h($_POST["material_no"]); } ?>"/></td>
                 <th>&nbsp;</th>
                 <td><input name="Submit" type="submit" value="Search" class="btn btn-info"></td>
               </tr>
              </table>
         
      <tr></form>
               
                   <?php
        if(isset($_POST["Submit"]))
        {   
		    $dateF = $_POST["date1"];
            $dateT = $_POST["date2"];
           	$shift_day = $_POST["shift_day"];
			$doc_tp_from = $_POST["doc_tp_from"];
			$doc_tp_to = $_POST["doc_tp_to"];
			$vendor_no = $_POST["vendor_no"];
            $material_no = $_POST["material_no"];
			 
            echo "<script>";
		   echo "window.location='document_list_TP_to_subcontProc.php?doc_tp_from=$doc_tp_from&&doc_tp_to=$doc_tp_to&&shift_day=$shift_day&&date1=$dateF&&date2=$dateT&&material_no=$material_no&&vendor_no=$vendor_no'";
            echo "</script>";
            exit(); //quit the script
        }
        
    	?>
         
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Document List</h5>
        </div>

   </div>

 <div class="widget-content nopadding">

   
<!--         <form name="myform" method="post" action="trans_posting_to_store.php?scan_doc=<?php echo $number; ?>" class="form-horizontal">
-->          <div class="control-group">
        <!--  <div class="controls"> -->
          
      
       </div>
          </div>
     <div class="control-group">
              
            </div>
          
   
            </div>
         </div>  
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
