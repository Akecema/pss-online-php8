<?php

/**
 * qqc_super/FTP_gdtranfer_downloadProc.php
 * Part of: QQC module (supervisor/admin tier)
 * Filename suggests: FTP gdtranfer downloadProc
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: user_detail, sys_setup_maintain, ftp_bflush_detail, ftp_goodtran_detail.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_qqc_super_menu.php, ]., ]);
	$date_transfer = ($row5[, footer.php.
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
require_role($dbc, 11);
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "FTP_gdtranfer_download.php";


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
</head>
<body>

<!--Header-part-->
<div id="header">
  <h1>&nbsp;</h1>
</div>
<div class="noprint">
<?php  include "top_modal_menu.php";   ?>
<!--close-Header-part--> 

<!--sidebar-menu-->
<?php include "left_qqc_super_menu.php";  ?>
<!--sidebar-menu-->
</div>
<div id="content">
 <div id="content-header" class="noprint">
  <div id="breadcrumb"> <a href="index_qqc_super.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">QC FTP Monitor</a> <a href="#" class="current">FTP Good Transfer</a> </div>
  <h1>FTP Good Transfer</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
    <!--  <div class="span12">-->
      
          
       <form action="<?php echo h($_SERVER['PHP_SELF']); ?>" method="get" name="frmSearch" id="frmSearch">
         <table class="table table-bordered table-striped">
               <tr>
                  <th height="57">Request Date From :</th>
                  <td><?php
   
					  $dd1 = substr($_GET["date1"],8,2);
					  $mm1 = substr($_GET["date1"],5,2);
					  $yy1 = substr($_GET["date1"],0,4);
	
	
                      $myCalendar = new tc_calendar("date1", true, false);
					  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					  $myCalendar->setDate($dd1, $mm1, $yy1);
					  $myCalendar->setPath("/calendar/");
					  $myCalendar->setYearInterval(2010, 2030);
					  // $myCalendar->setOnChange("myChanged('test')");
		    		  $myCalendar->writeScript();

                  ?></td>
                  <th>Request Date To :</th>
                  <td> <?php
			              
					  $dd2 = substr($_GET['date2'],8,2);
				      $mm2 = substr($_GET['date2'],5,2);
				      $yy2 = substr($_GET['date2'],0,4);
				
                      $myCalendar = new tc_calendar("date2", true, false);
					  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					  $myCalendar->setDate($dd2, $mm2, $yy2);
					  $myCalendar->setPath("/calendar/");
					  $myCalendar->setYearInterval(2010, 2030);
					  // $myCalendar->setOnChange("myChanged('test')");
					  $myCalendar->writeScript();
					  
					?></td>
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
	       
		$dateF = $_GET["date1"];
		$dateT = $_GET["date2"];
		
		//convert material no kpd id_hdr
		
/*		$query_convert = "SELECT * FROM `ftp_bflush_detail` WHERE posting_date >= $dateF or $dateF <= $dateT ";
		$result_convert = mysqli_query($dbc, $query_convert); 
		$row_convert = mysqli_fetch_array($result_convert);
		*/
		
					
		//********* CONDITION *************
		
		$where_sql = ""; $where_args = [];
		
		
		// 1. dateF
		if ($dateF == "0000-00-00" ){
			$wheresql_01 = ""; $wheresql_01_args = []; }
		else {
			$wheresql_01 = " AND posting_date >= ?"; $wheresql_01_args = [$dateF]; }      
										
		// 2. dateT
		if ($dateT == "0000-00-00" ){
			$wheresql_02 = ""; $wheresql_02_args = []; }
		else {
			$wheresql_02 = " AND posting_date <= ? "; $wheresql_02_args = [$dateT]; }          
						
		
		$where_sql =  $wheresql_01 .$wheresql_02 ; $where_args = array_merge($wheresql_01_args, $wheresql_02_args);
		
	
	//********** END CONDITION **************

	$query8 = "SELECT *,DATE_FORMAT(posting_date,'%d-%m-%Y') AS R2 FROM ftp_goodtran_detail WHERE status_ftp = 'Y' " .$where_sql; $query8_args = [...$where_args];
	$result8 = db_query_bind($dbc, $query8, $query8_args)or die(db_fail($dbc));
	$num_8 = mysqli_fetch_row($result8);
	$num_rows = mysqli_num_rows($result8);
	
	$pages = new Paginator;
	$pages->items_total = $num_rows;
	$pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
	$pages->paginate();
 
 
  
	$query = "SELECT *,DATE_FORMAT(posting_date,'%d-%m-%Y') AS R2 FROM ftp_goodtran_detail WHERE status_ftp = 'Y' " .$where_sql; $query_args = [...$where_args];
	$rs = db_query_bind($dbc, $query, $query_args);   //run the query.
	$num = mysqli_num_rows($rs);   //how many material are there?

	
	 if($num > 0) 
	 //if($num_rows[0] != 0)
	 {
	 
	 echo '<div align="center">There are currently  '. h($num).' record(s).</div>';
	
?>

<table class="table">
<tr>
    <td width="1%">&nbsp;</td> 
    <td width="85%"> <div class="small-nav"></div></td> 
       <td width="7%"><a href="report_FTP_gdtranfer_downloadProc.php?date1=<?php echo h($dateF); ?>&&date2=<?php echo h($dateT); ?>"><img src="../img/dload_excel.jpg" width="48" height="48" title="Download" /></a></td>
     <td width="7%"><!--<img src="../img/print2.jpg" width="48" height="48" onClick="window.print()" title="Print"/>--></td>
   
  </tr>
</table>

<div class="widget-box">
  <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
    <h5>Good Tranfer </h5>
  </div>
     
  <div class="widget-content nopadding">
          
    <table class="table table-bordered data-table">
     <thead>
 	<tr>
      <th width="50" height="28" bgcolor="#E9F58D"><span class="style3">File</span></th>
      <th width="50" height="28" bgcolor="#E9F58D"><span class="style3">QC Backflush No.</span></th>
      <th width="60" height="28" bgcolor="#E9F58D"><span class="style3">Backflush No</span></th>
      <th width="100" height="28" bgcolor="#E9F58D"><span class="style3">Plan No</span></th>
      <th width="180" height="28" bgcolor="#E9F58D"><span class="style3">Material No.</span></th>
      <th width="60" height="28" bgcolor="#E9F58D"><span class="style3">Quantity</span></th>
      <th width="40" height="28" bgcolor="#E9F58D"><span class="style3">UoM</span></th>
      <th width="90" height="28" bgcolor="#E9F58D"><span class="style3">Posting Date </th>
      <th width="90" height="28" bgcolor="#E9F58D"><span class="style3">Posting Time </th>
      </tr></thead>
  <tbody>
       
  <?php 
    		  
   $counter = 1;
   $no = 1;
   $i = 1;
   $variance_qty = 0;
   $bq = 0;
   $rq = 0;
   
   while ($row2 = mysqli_fetch_array($rs))
   {
  	
	$query_u = "SELECT * FROM user_detail WHERE user_no = ?"; $query_u_args = [$row2["user_create"]];
	$result_u = db_query_bind($dbc, $query_u, $query_u_args);   //run the query.
	$data_u = mysqli_fetch_array($result_u);   //how many records are there?    
	

	//-------------------------------------------------------Transfer Posting [Traffic Light] --------------------------
	// table post_detail_header --- checking traffic licht
	//-----------------------------------------------------------------------------------------------------------------
	
	/*$date_post =  ($row2["date_require"].' '.$row2["time_require"]);
	$date_transfer = ($row5["date_create"].' '.$row5["time_create"]);
	
	$start_date = new DateTime($date_post);
	$since_start = $start_date->diff(new DateTime($date_transfer));*/

	?>   
 
       
    <tr>
    <td width="50"><div align="center"><?php echo h($row2["file_name"]); ?></div></td> 
    <td width="50"><div align="center"><?php echo h($row2["qqc_no"]); ?></div></td> 
    <td width="60" height="28"><div align="center"><?php echo h($row2["bflush_no"]); ?></div></td>
    <td width="100">&nbsp;<?php echo h($row2["plan_no"]); ?></td>
    <td width="160">&nbsp;<?php echo h($row2["material_no"]); ?></td>
    <td width="50"><div align="right"><?php echo h($row2["qty_ftp"]); ?></div>   </td>
    <td width="40"><div align="center"><?php echo h($row2["uom"]); ?></div></td>
     <td width="90"><div align="center"><?php echo h($row2["R2"]); ?></div></td>
    <td width="100"><div align="center"><?php echo h($row2["posting_time"]); ?></div></td>

	<?php 
    
    $no ++;
    
    $counter++; // menambah counter 
    
    //}// end if
    
    
    } ?>
          
          </tr>
          </tbody></table>   

  
        
<?php
mysqli_free_result($rs); 
}   // free up the resources 
else
{
?>

<center>
<table width="800" cellspacing="0" class="textboxred">
<tr> 
<td><div align="center"><font color="#FF0000"><strong>No records found.</strong></font></div></td>
</tr>
</table>
</center>
    <?php
       } 
//mysqli_close($dbc);
?>

    
</div></div>
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
