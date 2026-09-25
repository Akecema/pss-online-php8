<?php

/**
 * qqc_super/disposal_approve_list_qc_tran_NG2.php
 * Part of: QQC module (supervisor/admin tier)
 * Filename suggests: disposal approve list qc tran NG2
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, reject_detail_disposal.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_qqc_super_menu.php, footer.php.
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
set_time_limit(0);

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$url = "disposal_approve_list_qc_tran_NG.php";

    $query2 = "SELECT * FROM user_detail WHERE username = ?"; $query2_args = [$username];
    $result2 = db_query_bind($dbc, $query2, $query2_args) or die(db_fail($dbc));
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

//CR status (New)

$sta = "SELECT * from request_status WHERE status_id = '1' ";
$sta_res = mysqli_query($dbc, $sta);
$rst_sta = mysqli_fetch_array($sta_res);

//CR status (Approved)
$sta3 = "SELECT * from request_status WHERE status_id = '3' ";
$sta_res3 = mysqli_query($dbc, $sta3);
$rst_sta3 = mysqli_fetch_array($sta_res3);	

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7' ";
$sta_res7 = mysqli_query($dbc, $sta7);
$rst_sta7 = mysqli_fetch_array($sta_res7);	
	
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
		
		var strURL="findWorkcenter2.php?factory="+factory;
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
<?php include "left_qqc_super_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_qqc_super.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a><a href="#" class="#">QA/QC</a> <a href="#" class="current">Approval List</a></div>
  <h1>Approval List</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
 
          
     <form action="<?php echo h($_SERVER['PHP_SELF']); ?>" method="get" name="frmSearch" id="frmSearch">
            <table class="table table-bordered table-striped">
            <tr>
              <th>Disposal  Start Date :</th>
              <td><?php
    
				      $dd1 = substr($_GET["date1"],8,2);
					  $mm1 = substr($_GET["date1"],5,2);
					  $yy1 = substr($_GET["date1"],0,4);
	
	
                      $myCalendar = new tc_calendar("date1", true, false);
					  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					  $myCalendar->setDate($dd1, $mm1, $yy1);
					  $myCalendar->setPath("/calendar/");
					  $myCalendar->setYearInterval(date('Y') - 1, date('Y') + 10);
					  // $myCalendar->setOnChange("myChanged('test')");
		    		  $myCalendar->writeScript();
			 ?></td>
              <th>Disposal  End Date :</th>
              <td><?php
                
                      $dd2 = substr($_GET['date2'],8,2);
				      $mm2 = substr($_GET['date2'],5,2);
				      $yy2 = substr($_GET['date2'],0,4);
				
                      $myCalendar = new tc_calendar("date2", true, false);
					  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					  $myCalendar->setDate($dd2, $mm2, $yy2);
					  $myCalendar->setPath("/calendar/");
					  $myCalendar->setYearInterval(date('Y') - 1, date('Y') + 10);
					  // $myCalendar->setOnChange("myChanged('test')");
					  $myCalendar->writeScript();
                
                ?></td>
            </tr>
          
            <tr>
              <th>Disposal Doc. No. :</th>
              <td>
              <select name="doc_disposal_no" id="doc_disposal_no" class="span11">
               <option value="NULL" placeholder="Select Disposal Doc. No."> -- Select Disposal Doc. No. --</option>
                  <?php
	       $query9 = "SELECT * FROM reject_detail_disposal WHERE status_disposal = ? AND status_part = 'QC' AND doc_disposal_no != '' GROUP BY doc_disposal_no"; $query9_args = [$rst_sta3["status_desc"]];
            $result9 = db_query_bind($dbc, $query9, $query9_args);
  
                   while($row9=mysqli_fetch_array($result9)) 
			      {
				   ?>
                  <option value="<?php echo h($row9["doc_disposal_no"]); ?>"<?php if($row9["doc_disposal_no"] == $_GET["doc_disposal_no"]) echo "selected"; ?>> <?php echo h($row9["doc_disposal_no"]); ?></option>
                  <?php
                  }
				?>
              </select>
             </td>
              <th></th>
              <td></td>
            </tr>
            <tr>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th><input name="Submit2" type="submit"  class="btn btn-info" id="button" value="SEARCH" /></th>
            </tr>
            </table>
        </form>
<?php

            $dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			$doc_disposal_no = $_GET["doc_disposal_no"];		
		  
		
			//-------Count all results------------------------//
			
				 $where_sql = ''; $where_args = [];
		 
		 // 1. DateT
                if($dateT == "0000-00-00") {
                     $wheresql_01 = ""; $wheresql_01_args = []; }
                else {
                      $wheresql_01 = " AND (MR.date_posting <= ?)"; $wheresql_01_args = [$dateT]; }
		  //2. DateF 
                if ($dateF  == "0000-00-00" ){
                     $wheresql_02 = ""; $wheresql_02_args = [];}
                else {
                     $wheresql_02 = " AND (MR.date_posting >= ?)"; $wheresql_02_args = [$dateF];}  
					 
		 //3. Doc. Disposal No. 
                if ($doc_disposal_no == "NULL"){ 
                    $wheresql_03 = ""; $wheresql_03_args = []; }
                else {
                    $wheresql_03 = " AND MR.doc_disposal_no = ?"; $wheresql_03_args = [$doc_disposal_no]; } 
		
				
				$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03; $where_args = array_merge($wheresql_01_args, $wheresql_02_args, $wheresql_03_args);	
	
	//********** END CONDITION **************
   
								 
   $query8 = "SELECT COUNT(*) FROM reject_detail_disposal AS MR WHERE MR.status_disposal = ? AND MR.status_part = 'QC' AND MR.doc_disposal_no != ''".$where_sql; $query8_args = [$rst_sta3["status_desc"], ...$where_args];
   $result8 = db_query_bind($dbc, $query8, $query8_args) or die(db_fail($dbc));
   $num_rows = mysqli_fetch_row($result8);

   $pages = new Paginator;
   $pages->items_total = $num_rows[0];
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT *, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2 FROM reject_detail_disposal AS MR WHERE MR.status_disposal = ? AND MR.status_part = 'QC' AND MR.doc_disposal_no != ''".$where_sql." GROUP BY MR.doc_disposal_no ORDER BY MR.plan_no ASC"; $query_args = [$rst_sta3["status_desc"], ...$where_args];
$rs = db_query_bind($dbc, $query, $query_args);   //run the query.
$num = mysqli_num_rows($rs);   //how many material are there?


	
	 if ($num > 0) {
	 
	 echo '<div align="center">There are currently  '. h($num_rows[0]).' record(s).</div>';
	 }
	

?>
  <table class="table">
<tr>
    <td width="1%">&nbsp;</td> 
    <td width="85%"> <div class="small-nav"></div></td> 
      <td width="14%"><!--<a href="upload_pps_month.php"><img src="../img/upload_file2.png" width="48" height="48" title="Upload File" />Upload File</a>--></td> 
  </tr>
</table>        
          
      <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display Request</h5>
          </div>
             
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                <th>No.</th> 
                <th>Document No.</th>
                <th>Status</th>
                <th colspan="2">Action</th>
                </tr>
              </thead>   
              <tbody>
           <?php
   
   $counter = 1;
   $no = 1;
   $sta_out = "";
   
   while ($row = mysqli_fetch_array($rs))
   {
		
	 
      ?>
           
                <tr class="gradeX">
                <td width="78"><?php echo $no; ?></td>
                <td width="324"><?php echo h($row["doc_disposal_no"]); ?></td>
                <td width="158"><?php echo h($row["status_disposal"]); ?></td>
                <td width="94"> <?php
                 if($row["status_part"] == "QC")
				   {
					?>
				 <a value="Print" href="print_disposal_approve_qc_tran_NG.php?doc_disposal=<?php echo h($row["doc_disposal_no"]); ?>&&TB_iframe=true&height=400&width=1000" class="thickbox" target="_self"><img src="../img/print2.jpg" width="16" height="16" alt="Print">Print</a>	<?php
				 
				   } ?></td>
                </tr>
                
          <?php 
		  
		  $no ++;
		  $counter++; // menambah counter
		  } 
		  ?>
                
              
              </tbody>
            </table>
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
