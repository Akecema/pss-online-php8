<?php

/**
 * ppc/consumable_request_analysis.php
 * Part of: PPC module (Production Planning & Control)
 * Filename suggests: consumable request analysis
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, factory_detail, consumable_detail.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_ppc_menu.php, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
session_start();
$username = $_SESSION['username'] ?? '';
include '../include/config.php';
require_once '../include/auth.php';
require_role($dbc, 4);
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "consumable_request_analysis.php";


    $query2 = "SELECT * FROM user_detail WHERE username = ?"; $query2_args = [$username];
    $result2 = db_query_bind($dbc, $query2, $query2_args) or die(db_fail($dbc));
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
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

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
<?php include "left_ppc_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
 <div id="content-header">
  <div id="breadcrumb"> <a href="index_ppc.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Consumable Request</a> <a href="#" class="current">Consumable Request Analysis</a> </div>
  <h1>Consumable Request</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
    <!--  <div class="span12">-->
      
          
    <form action="<?php echo h($_SERVER['PHP_SELF']); ?>" method="post" name="frmSearch" id="frmSearch">
         <table class="table table-bordered table-striped">
            <tr>
                 
                </tr>
               
                 <tr>
                 
                </tr>
                <tr>
            
            </tr>
              <tr>
                  <th>Request Date From :</th>
                  <td><?php
    
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
									$myCalendar->setYearInterval((date('Y')- 1), date('Y') + 10);
									// $myCalendar->setOnChange("myChanged('test')");
									$myCalendar->writeScript();
									
								}
								else	 
								{
                                      
										$dt = $today['mday'];
										$mt = $today['mon'];
										$yr = $today['year'];
									 
										$myCalendar = new tc_calendar("date1", true, false);
										$myCalendar->setIcon("../calendar/images/iconCalendar.gif");
										$myCalendar->setDate($dt,$mt,$yr);
										$myCalendar->setPath("/calendar/");
										$myCalendar->setYearInterval((date('Y')- 1), date('Y') + 10);
										// $myCalendar->setOnChange("myChanged('test')");
										$myCalendar->writeScript();
				
									}

                 ?></td>
                  <th>Request Date To :</th>
                  <td> <?php
			               if(isset($_POST['date2']))
								{ 
									
									//GET value
									$dd2 = substr($_POST['date2'],8,2);
									$mm2 = substr($_POST['date2'],5,2);
									$yy2 = substr($_POST['date2'],0,4);
									
									$myCalendar = new tc_calendar("date2", true, false);
									$myCalendar->setIcon("../calendar/images/iconCalendar.gif");
									$myCalendar->setDate($dd2, $mm2, $yy2);
									$myCalendar->setPath("/calendar/");
									$myCalendar->setYearInterval((date('Y')- 1), date('Y') + 10);
									// $myCalendar->setOnChange("myChanged('test')");
									$myCalendar->writeScript();
									
								}
								else	 
								{
                                      
										$dt2 = $today['mday'];
										$mt2 = $today['mon'];
										$yr2 = $today['year'];
									 
										$myCalendar = new tc_calendar("date2", true, false);
										$myCalendar->setIcon("../calendar/images/iconCalendar.gif");
								        $myCalendar->setDate($dt2,$mt2,$yr2);
										$myCalendar->setPath("/calendar/");
										$myCalendar->setYearInterval((date('Y')- 1), date('Y') + 10);
										// $myCalendar->setOnChange("myChanged('test')");
										$myCalendar->writeScript();
				
									}
					?></td>
                </tr>
               
                     
            
            <tr>
              <th>Factory :</th>
                  <td><select name="factory" id="factory">
                    <option value="NULL" placeholder="Select Factory"> -- Select Factory -- </option>
                    <?php
	               $query3 = "SELECT * FROM factory_detail GROUP BY factory_desc2 ORDER BY id_fac ASC";
                   $result3 = mysqli_query($dbc, $query3);
  
                   while($row3=mysqli_fetch_array($result3, MYSQLI_NUM)) 
			      {
                  echo'<option value="',h($row3[2]),'">',stripslashes($row3[1]),'</option>';
                  }
				?>
                  </select></td>
               <th>MRIN Status :</th>
                  <td>
                  <select name="status" id="status">
                  <option value="NULL" placeholder="Select Status"> -- Select Status --</option>
                  <option value="New">Open</option>
                  <option value="Close">Close</option>
                  </select></td>
                  
            </tr>
            <tr>
               <th>Material No.  :</th>
               <td><select name="material_no" id="material_no" >
                  <option value="NULL" placeholder="Select Material No."> -- Select Material No. --</option>
                  <?php
	             $query9 = "SELECT * FROM consumable_detail WHERE con_status = 'Y'";
                   $result9 = mysqli_query($dbc, $query9);
  
                   while($row9=mysqli_fetch_array($result9)) 
			      {
				   ?>
                  <option value="<?php echo h($row9["material_no"]); ?>"> <?php echo h($row9["material_no"]).' -  '.h($row9["mat_desc"]); ?></option>
                  <?php
                  }
				?>
              </select></td>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
            </tr>
             <tr>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th><input name="Submit" type="submit" value="Search" class="btn btn-info"></th>
            </tr>
           </table>
        </form>
              
         <?php
        if(isset($_POST["Submit"]))
        {
            $material_no = $_POST["material_no"];
			$factory = $_POST["factory"];
			$status = $_POST["status"];
            $dateF = $_POST["date1"];
            $dateT = $_POST["date2"];
			 
            echo "<script>";
		   echo "window.location='consumable_request_analysisProc.php?factory=$factory&&status=$status&&date1=$dateF&&date2=$dateT&&material_no=$material_no'";
            echo "</script>";
            exit(); //quit the script
        }
        
    	?>
     
          </div>
        
      </div>
    </div>
  </div>
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
