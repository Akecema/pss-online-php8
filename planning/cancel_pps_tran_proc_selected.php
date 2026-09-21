<?php

/**
 * planning/cancel_pps_tran_proc_selected.php
 * Part of: Planning module
 * Filename suggests: cancel pps tran proc selected
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, work_center_detail, ftp_pps, pps_detail, pps_cancellation.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, tcpdf_barcodes_2d.php, footer.php.
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
require_role($dbc, 8);
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

set_time_limit(0);
// include 2D barcode class (search for installation path)
require_once(__DIR__ . '/tcpdf_barcodes_2d.php');

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "display_consumable_request.php";


    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
    $result2 = mysqli_query($dbc, $query2) or die(db_fail($dbc));
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
//CR status (New)

$sta = "SELECT * from request_status WHERE status_id = '1' ";
$sta_res = mysqli_query($dbc, $sta);
$rst_sta = mysqli_fetch_array($sta_res);

//CR status (Released)
$sta2 = "SELECT * from request_status WHERE status_id = '2' ";
$sta_res2 = mysqli_query($dbc, $sta2);
$rst_sta2 = mysqli_fetch_array($sta_res2);	

//CR status (Cancel)
$sta4 = "SELECT * from request_status WHERE status_id = '4' ";
$sta_res4 = mysqli_query($dbc, $sta4);
$rst_sta4 = mysqli_fetch_array($sta_res4);	

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
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

<!----------------->
<link rel="stylesheet" href="../scripts/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="../javascript/jquery-latest.js"></script> 
<script type="text/javascript" src="../javascript/thickbox.js"></script>	
<link href="../calendar/calendar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../calendar/calendar.js"></script>
<style type="text/css">
<!--
.style3 {color: #000000}
@media print{
  body{  margin-top: -2.2cm;  font-family: Arial, Helvetica, sans-serif; font-size:12px; }
  #ad{ display:none;}
  #leftbar{ display:none;}
  #contentarea{ width:100%;}
  .header, .hide { visibility: hidden }
  .tfoot { display: table-footer-group; }
    @page {size: landscape}
}
.style4 {
	font-size: 14px;
	font-weight: bold;
}
-->
</style>
</head>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>
</head>
<body>
<div class="widget-box">
  <!-- Header -->
  <!-- End Header -->
  <?php
            $uid = $_GET["uid"];
            $dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			$factory = $_GET["factory"];
			$work_center = $_GET["work_center"];
			$plan_no = $_GET["plan_no"];
			$shift_ops = $_GET["shift_ops"];
			$name_file = $_GET["name_file"];
			
			 //convert 
			
			$query_convert = "SELECT * FROM `work_center_detail` as SR WHERE SR.id_work = '".db_esc($dbc, $_GET["work_center"])."'";
			$result_convert = mysqli_query($dbc, $query_convert); 
			$row_convert = mysqli_fetch_array($result_convert);
			
			$query_convert2 = "SELECT * FROM `ftp_pps` WHERE file_name = '".db_esc($dbc, $_GET["name_file"])."'";
			$result_convert2 = mysqli_query($dbc, $query_convert2); 
			$row_convert2 = mysqli_fetch_array($result_convert2);
			
			//-------Count all results------------------------//
			
				 $where_sql = '';
		 
		 // 1. DateT
                if($dateT == "0000-00-00") {
                     $wheresql_01 = ""; }
                else {
                      $wheresql_01 = " AND (MR.date_plan <= '".db_esc($dbc, $dateT)."')"; }
		  //2. DateF 
                if ($dateF  == "0000-00-00" ){
                     $wheresql_02 = "";}
                else {
                     $wheresql_02 = " AND (MR.date_plan >= '".db_esc($dbc, $dateF)."')";}  
					 
		 //3. Factory 
                if ($factory == "NULL"){ 
                    $wheresql_03 = ""; }
                else {
                    $wheresql_03 = " AND MR.id_factory_pps = '".db_esc($dbc, $factory)."'"; } 
					
          //4. Work Center
                if ($work_center == "NULL" ){
                    $wheresql_04 = ""; }
                else {
					$wheresql_04 = " AND MR.work_center = '".db_esc($dbc, $work_center)."'"; }
   
	       //5. Planned Order No.
                if ($plan_no == "NULL"){ 
                    $wheresql_05 = ""; }
                else {
                    $wheresql_05 = " AND MR.plan_no = '".db_esc($dbc, $plan_no)."'"; }  	
					
		 
		  //6. Shift
                if ($shift_ops == "NULL"){ 
                    $wheresql_06 = ""; }
                else {
					// $wheresql_06 = ""; }
					
                    $wheresql_06 = " AND ((MR.shift_pps1 = '".db_esc($dbc, $shift_ops)."') OR (MR.shift_pps2 = '".db_esc($dbc, $shift_ops)."')) "; }  	
					
					
		  //7. File name
                if ($name_file == "NULL" ){
                    $wheresql_07 = ""; }
                else {
                    $wheresql_07 = " AND MR.upload_id = '".db_esc($dbc, $name_file)."'"; }    
						 			
				
	$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04 .$wheresql_05 .$wheresql_06 .$wheresql_07;	
	
	//********** END CONDITION **************
								 
   $query8 = "SELECT COUNT(*) FROM pps_detail AS MR WHERE MR.status_pps = '".db_esc($dbc, $rst_sta2["status_desc"])."'".$where_sql;
   $result8 = mysqli_query($dbc, $query8) or die(db_fail($dbc));
   $num_rows = mysqli_fetch_row($result8);

   $pages = new Paginator;
   $pages->items_total = $num_rows[0];
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 

  if(isset($_POST["cancel_btn"])) 
  
   { // handle the form.

// create a function for escaping the data.
function escape_data ($data) {
global $dbc;   // need the connection.
if(ini_get('magic_quotes_gpc')) {
    $data = stripslashes($data);
	}
	return mysqli_real_escape_string($dbc, $data);
	}   // end function.
$message = NULL; // create an empty new variable.

   
   $uid = $_POST["uid"];
   
  // echo $uid;
   
    //--------- pps detail ------------
	 
	   $query_pps = "SELECT * FROM pps_detail WHERE upload_id = '".db_esc($dbc, $uid)."'";
	   $result_pps = mysqli_query($dbc, $query_pps);
	  
	  while($data_pps = mysqli_fetch_array($result_pps))
	  
	  {
		  
	  //---------update cancellation--------------------------
	 
	$query_cancel = "UPDATE pps_detail SET status_pps = '".db_esc($dbc, $rst_sta4["status_desc"])."' WHERE upload_id = '".db_esc($dbc, $uid)."'";
	$result_cancel = mysqli_query($dbc, $query_cancel);
		  
		   //insert into table pps_detail_cancellation-------------
	
$query_data2 = "INSERT INTO pps_cancellation (id, ref_id, plan_no, upload_id, model_code, month_plan, material_no, qty_plan, qty_actual, status_pps, comp_code, work_center, shift_pps1, shift_pps2, date_plan, status, user_upload, date_upload,user_create, date_create, user_update, date_update, user_posting, date_posting, user_cancel, date_cancel, plan_category, id_factory_pps, rev_pps, seq_pps, man_hours, work_hours) VALUES('".db_esc($dbc, $data_pps["id"])."','".db_esc($dbc, $data_pps["ref_id"])."','".db_esc($dbc, $data_pps["plan_no"])."','".db_esc($dbc, $data_pps["upload_id"])."','".db_esc($dbc, $data_pps["model_code"])."','".db_esc($dbc, $data_pps["month_plan"])."','".db_esc($dbc, $data_pps["material_no"])."','".db_esc($dbc, $data_pps["qty_plan"])."','".db_esc($dbc, $data_pps["qty_actual"])."','".db_esc($dbc, $rst_sta4["status_desc"])."','".db_esc($dbc, $data_pps["comp_code"])."','".db_esc($dbc, $data_pps["work_center"])."','".db_esc($dbc, $data_pps["shift_pps1"])."','".db_esc($dbc, $data_pps["shift_pps2"])."','".db_esc($dbc, $data_pps["date_plan"])."','N','".db_esc($dbc, $data_pps["user_upload"])."','".db_esc($dbc, $data_pps["date_upload"])."','".db_esc($dbc, $data_pps["user_create"])."','".db_esc($dbc, $data_pps["date_create"])."','".db_esc($dbc, $data_pps["user_update"])."','".db_esc($dbc, $data_pps["date_update"])."','".db_esc($dbc, $data_pps["user_posting"])."','".db_esc($dbc, $data_pps["date_posting"])."','".db_esc($dbc, $username)."',NOW(),'".db_esc($dbc, $data_pps["plan_category"])."','".db_esc($dbc, $data_pps["id_factory_pps"])."','".db_esc($dbc, $data_pps["rev_pps"])."','".db_esc($dbc, $data_pps["seq_pps"])."','".db_esc($dbc, $data_pps["man_hours"])."','".db_esc($dbc, $data_pps["work_hours"])."')";
$result_data2 = mysqli_query($dbc, $query_data2) or die(db_fail($dbc));   

				  
		  
	  }
	  
 
 	 
	  if($result_cancel)
	 { 

		   echo "<script>";
		   echo "alert('Cancel Filename Document No : $uid');";
		   echo "parent.tb_remove(); parent.location.reload(1)";
		   //echo "window.location='ftp_bflush_SAP_cancel.php?uid=$uid&&buid=$ref'";
	       echo "</script>"; 
		   exit(); //quit the script
		
    }


   }// end submit
?>
<table class="table">
<tr>
  <td>&nbsp;</td>
  <td colspan="2"><div align="center"><span class="style4">PPS Cancellation</span></div></td>
  </tr>
</table>
<p>&nbsp;</p> 
   <form action="cancel_pps_tran_proc_selected.php?date1=<?php echo h($dateF); ?>&&date2=<?php echo h($dateT); ?>&&factory=<?php echo h($factory); ?>&&work_center=<?php echo h($work_center); ?>&&plan_no=<?php echo h($plan_no); ?>&&shift_ops=<?php echo h($shift_ops); ?>&&name_file=<?php echo h($name_file); ?>" method="post" name="frmSearch" id="frmSearch">     
  <table class="table table-bordered data-table">
    <thead>
      <tr>
        <th>No.</th>
        <th width="77">Part No.</th>
        <th>Planned Order No.</th>
        <th>Planned Date</th>
        <th>Work Center</th>
        <th>Shift</th>
        <th>Planned Quantity</th>
      </tr>
    </thead>
    <tbody>
      <?php
   
   $counter = 1;
   $no = 1;
   $sta_out = "";
   
$query_display = "SELECT *, DATE_FORMAT(MR.date_plan,'%d-%m-%Y') as R FROM pps_detail AS MR WHERE MR.status_pps = '".db_esc($dbc, $rst_sta2["status_desc"])."' AND  MR.upload_id = '".db_esc($dbc, $uid)."' ".$where_sql." order by MR.plan_no ASC";
$result_display = mysqli_query($dbc, $query_display);   //run the query.
   
   while ($row2 = mysqli_fetch_array($result_display))
   {
		
	//shift	
		if($row2["shift_pps1"] != "")
	{
		$sta = "D/S";
		
	}elseif($row2["shift_pps2"] != "")
	 {
		$sta = "N/S";
	 }else{
		 
		$sta = " ";
	 }	
	
	 
      ?>
      <tr class="gradeX">
        <td width="30"><?php echo $no; ?></td>
        <td><?php echo h($row2["material_no"]); ?></td>
        <td width="80"><font color="#0000CC"><?php echo h($row2["plan_no"]); ?></font></td>
        <td width="80"><?php echo h($row2["R"]); ?></td>
        <td width="60"><?php echo h($row2[11]); ?></td>
        <td width="40"><?php echo $sta; ?></td>
        <td width="90"><font color="#0000CC"><?php echo intval($row2["qty_plan"]); ?></font></td>
        <input name="uid" type="hidden" value="<?php echo h($row2["upload_id"]); ?> ">
      </tr> 
      
      <?php 
		  
		  $no ++;
		  $counter++; // menambah counter
		  } 
		  ?>
        
      </tr>  
     
    </tbody>
  </table> 
  <table>
    <tr><td>&nbsp;<input name="cancel_btn" type="submit"  class="btn btn-danger" id="button" value="CANCELLATION" onClick="return confirm('Are you sure want to perform this activity?');"/></td>
</tr>
       </table>
  </form>
  <p>&nbsp;</p>
  <p><br> 
    
    <!--Footer-part-->
  </p>
  <?php include "footer.php";   ?>
<!--end-Footer-part--> 
</div>
</body>
</html>