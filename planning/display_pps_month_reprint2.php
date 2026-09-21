<?php

/**
 * planning/display_pps_month_reprint2.php
 * Part of: Planning module
 * Filename suggests: display pps month reprint2
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, factory_detail, work_center_detail, pps_detail, plan_cat_pps, ftp_pps.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_planning_menu.php, footer.php.
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

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$url = "display_pps_month_reprint.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
    $result2 = mysqli_query($dbc, $query2) or die(db_fail($dbc));
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

//CR status (Released)
$sta2 = "SELECT * from request_status WHERE status_id = '2' ";
$sta_res2 = mysqli_query($dbc, $sta2);
$rst_sta2 = mysqli_fetch_array($sta_res2);

//CR status (Cancel)
$sta4 = "SELECT * from request_status WHERE status_id = '4' ";
$sta_res4 = mysqli_query($dbc, $sta4);
$rst_sta4 = mysqli_fetch_array($sta_res4);

//CR status (Closed)
$sta13 = "SELECT * from request_status WHERE status_id = '13'";
$sta_res13 = mysqli_query($dbc, $sta13);
$rst_sta13 = mysqli_fetch_array($sta_res13);

//CR status (Delete)
$sta16 = "SELECT * from request_status WHERE status_id = '16' ";
$sta_res16 = mysqli_query($dbc, $sta16);
$rst_sta16 = mysqli_fetch_array($sta_res16);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <title><?php echo h($data_setup["title_desc"]); ?></title>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <link rel="shortcut icon" href="../img/favicon.ico">
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <!-- Start of Bootstrap Calendar CSS -->
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
   <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
   <!-- End of Bootstrap Calendar CSS -->
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
   <script type="text/javascript" href="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
   <script type="text/javascript" href="https://code.jquery.com/jquery-3.5.1.js"></script>
   <!-- Start of Bootstrap Calendar JS -->
   <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
   <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
   <!-- End of Bootstrap Calendar JS -->
   <script language="javascript" type="text/javascript">
      function getXMLHTTP() { //fuction to return the xml http object
         var xmlhttp = false;
         try {
            xmlhttp = new XMLHttpRequest();
         } catch (e) {
            try {
               xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
               try {
                  xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
               } catch (e1) {
                  xmlhttp = false;
               }
            }
         }
         return xmlhttp;
      }
      function getFactory(factory) {
         var strURL = "findWorkcenter4.php?factory=" + factory;
         var req = getXMLHTTP();
         if (req) {
            req.onreadystatechange = function() {
               if (req.readyState == 4) {
                  // only if "OK"
                  if (req.status == 200) {
                     document.getElementById('work_centerdiv').innerHTML = req.responseText;
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
   <style type="text/css">
      .com_i {
         color: #FF0000;
      }
   </style>
</head>
<body>
   <!--Header-part-->
   <div id="header">
      <h1>&nbsp;</h1>
   </div>
   <?php include "top_modal_menu.php";   ?>
   <!--close-Header-part-->
   <!--sidebar-menu-->
   <?php include "left_planning_menu.php";  ?>
   <!--sidebar-menu-->
   <div id="content">
      <div id="content-header">
         <div id="breadcrumb"> 
            <a href="index_planning.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
            <a href="#" class="tip-bottom">Planning Sheet (PPS)</a>PPS Listing
         </div>
         <h1>Planning Sheet (PPS)</h1>
      </div>
      <div class="container-fluid">
         <hr>
         <div class="row-fluid">
            <!--  <div class="span12">-->
            <div class="widget-box">
               <div class="widget-title">
                  <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
                     <li><a role="tab" href="upload_pps_month.php">Upload PPS</a></li>
                     <li class="active"><a role="tab" href="display_pps_month_reprint.php">PPS Listing</a></li>
                  </ul>
               </div>
            </div>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="frmSearch" id="frmSearch">
            <table class="table table-bordered table-striped">
            <tr>
              <th>Plan Start Date :<span class="com_i">*</span></th>
              <td><?php
    
				      $dd1 = substr($_GET["date1"],8,2);
					  $mm1 = substr($_GET["date1"],5,2);
					  $yy1 = substr($_GET["date1"],0,4);
	
	
                      $myCalendar = new tc_calendar("date1", true, false);
					  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					  $myCalendar->setDate($dd1, $mm1, $yy1);
					  $myCalendar->setPath("/calendar/");
					  $myCalendar->setYearInterval(date('Y') - 5, date('Y') + 10);
					  // $myCalendar->setOnChange("myChanged('test')");
		    		  $myCalendar->writeScript();
			 ?></td>
              <th>Plan End Date : <span class="com_i">*</span></th>
              <td><?php
                
                      $dd2 = substr($_GET['date2'],8,2);
				      $mm2 = substr($_GET['date2'],5,2);
				      $yy2 = substr($_GET['date2'],0,4);
				
                      $myCalendar = new tc_calendar("date2", true, false);
					  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					  $myCalendar->setDate($dd2, $mm2, $yy2);
					  $myCalendar->setPath("/calendar/");
					  $myCalendar->setYearInterval(date('Y') - 5, date('Y') + 10);
					  // $myCalendar->setOnChange("myChanged('test')");
					  $myCalendar->writeScript();
                
                ?></td>
            </tr>
          
            <tr>
              <th>Factory : </th>
              <td><select name="factory" id="factory" onChange="getFactory(this.value)">
                  <option value="NULL" placeholder="Select Factory"> -- Select Factory --</option>
                  <?php
	               $query3 = "SELECT * FROM factory_detail GROUP BY factory_desc2 ORDER BY id_fac ASC";
                   $result3 = mysqli_query($dbc, $query3);
  
                   while($row3=mysqli_fetch_array($result3)) 
			      {
				  
				  
				  ?>
                  <option value="<?php echo h($row3["factory_desc2"]); ?>" <?php if($row3["factory_desc2"] == $_GET["factory"]) echo "selected"; ?>> <?php echo h($row3["factory_desc"]); ?></option>
                  <?php
                  }
				?>
              </select></td>
              <th>Work Center :</th>
              <td><div id="work_centerdiv"> 
               <select name="work_center" id="work_center" class="span11">
                <option value="NULL" placeholder="Select Work Center"> -- Select Work Center --</option>
                 <?php
	               $query5 = "SELECT * FROM work_center_detail WHERE id_factory = '".db_esc($dbc, $_GET["factory"])."' ORDER BY id_work ASC";
                   $result5 = mysqli_query($dbc, $query5);
  
                   while($row5=mysqli_fetch_array($result5)) 
				    { 
				   
				   ?>
                <option value="<?php echo h($row5["id_work"]); ?>" <?php if($row5["id_work"] == $_GET["work_center"]) echo "selected"; ?>> <?php echo $row5["id_work"],' - ',stripslashes($row5["wc_desc"]); ?></option>
                <?php
                  }
				?>
                </select></div></td>
            </tr>
              <tr>
              <th>Planned Order No. :</th>
              <td><select name="plan_no" id="plan_no" class="span11">
                  <option value="NULL" placeholder="Select Planned Order No."> -- Select Planned Order No. --</option>
                  <?php
	             $query9 = "SELECT * FROM pps_detail WHERE status_pps = '".db_esc($dbc, $rst_sta["status_desc"])."' OR status_pps = '".db_esc($dbc, $rst_sta2["status_desc"])."' ORDER BY plan_no ASC";
                   $result9 = mysqli_query($dbc, $query9);
  
                   while($row9=mysqli_fetch_array($result9)) 
			      {
				   ?>
                  <option value="<?php echo h($row9["plan_no"]); ?>"<?php if($row9["plan_no"] == $_GET["plan_no"]) echo "selected"; ?>> <?php echo h($row9["plan_no"]); ?></option>
                  <?php
                  }
				?>
              </select></td>
              <th>Shift :</th>
              <td><select name="shift_ops" id="shift_ops" class="span11">
                  <option value="NULL" placeholder="Select Shift"> -- Select Shift --</option>
                  <option value="D/S" <?php if($_GET["shift_ops"] == "D/S") { ?> selected="selected"<?php } ?>>D/S</option>
                  <option value="N/S" <?php if($_GET["shift_ops"] == "N/S") { ?> selected="selected"<?php } ?>>N/S</option>
                  </select></td>
            </tr>
             <tr>
              <th>Status :</th>
              <td>
               <select name="status" id="status" class="span11">
                  <option value="NULL" placeholder="Select Status"> -- Select Status --</option>
                  <option value="<?php echo h($rst_sta["status_desc"]); ?>" <?php if($_GET["status"] == $rst_sta["status_desc"]) { ?> selected="selected"<?php } ?>><?php echo h($rst_sta["status_desc"]); ?></option>
                  <option value="<?php echo h($rst_sta2["status_desc"]); ?>" <?php if($_GET["status"] == $rst_sta2["status_desc"]) { ?> selected="selected"<?php } ?>><?php echo h($rst_sta2["status_desc"]); ?></option>
              </select>
             </td>
             <th>Filename :</th>
              <td>  <select name="name_file" id="name_file">
                  <option value="NULL" placeholder="Select Filename"> -- Select Filename --</option>
                  <?php
	               $query19 = "SELECT * FROM pps_detail AS DC, ftp_pps AS FP  WHERE FP.upload_id = DC.upload_id AND (DC.status_pps != '".db_esc($dbc, $rst_sta4["status_desc"])."' AND DC.status_pps != '".db_esc($dbc, $rst_sta13["status_desc"])."') GROUP BY FP.file_name ORDER BY FP.file_name ASC";
                   $result19 = mysqli_query($dbc, $query19);
  
                   while($row19=mysqli_fetch_array($result19)) 
			      {
				   ?>
                  <option value="<?php echo h($row19["file_name"]); ?>" <?php if($row19["file_name"] == $_GET["name_file"]) echo "selected"; ?>> <?php echo h($row19["file_name"]); ?></option>
                  <?php
                  }
				?>
              </select></td>
            </tr>  
            <tr>
              <th>Plan Category :</th>
              <td><select name="plan_category" id="plan_category">
                  <option value="NULL" placeholder="Select Plan Category"> -- Select Plan Category --</option>
                  <?php
	               $query29 = "SELECT * FROM plan_cat_pps ORDER BY id_plan ASC";
                   $result29 = mysqli_query($dbc, $query29);
  
                   while($row29=mysqli_fetch_array($result29)) 
			      {
				   ?>
                     <option value="<?php echo h($row29["id_plan"]); ?>" <?php if($row29["id_plan"] == $_GET["plan_category"]) echo "selected"; ?>> <?php echo h($row29["plan_category_desc"]); ?></option>
                 
                  <?php
                  }
				?>
              </select></td>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
            </tr>
            <tr>
              <th><span class="com_i">*</span> Compulsory&nbsp;</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th><input name="Submit2" type="submit"  class="btn btn-info" id="button" value="SEARCH" /></th>
            </tr>
          
            </table>
        </form>
<?php
            $dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			$factory = $_GET["factory"];
			$work_center = $_GET["work_center"];
			$plan_no = $_GET["plan_no"];
			$shift_ops = $_GET["shift_ops"];
	        $status = $_GET["status"];
			$name_file = $_GET["name_file"];
			$plan_category = $_GET["plan_category"];
			
			
			 //convert 
			
			$query_convert = "SELECT * FROM work_center_detail WHERE id_work = '".db_esc($dbc, $_GET["work_center"])."'";
			$result_convert = mysqli_query($dbc, $query_convert); 
			$row_convert = mysqli_fetch_array($result_convert);
			
			$query_convert2 = "SELECT * FROM ftp_pps WHERE file_name = '".db_esc($dbc, $_GET["name_file"])."'";
			$result_convert2 = mysqli_query($dbc, $query_convert2); 
			$row_convert2 = mysqli_fetch_array($result_convert2);
			
		   // echo h($row_convert2["upload_id"]);
		  
		  		
			//-------Count all results------------------------//
			
				 $where_sql = '';
		 
		 // 1. DateT
                if($dateT == "0000-00-00") {
                     $wheresql_01 = ""; }
                else {
                      $wheresql_01 = " AND (date_plan <= '".db_esc($dbc, $dateT)."')"; }
		  //2. DateF 
                if ($dateF  == "0000-00-00" ){
                     $wheresql_02 = "";}
                else {
                     $wheresql_02 = " AND (date_plan >= '".db_esc($dbc, $dateF)."')";}  
					 
		 //3. Factory 
                if ($factory == "NULL"){ 
                    $wheresql_03 = ""; }
                else {
                    $wheresql_03 = " AND id_factory_pps = '".db_esc($dbc, $factory)."'"; } 
					
          //4. Work Center
                if ($work_center == "NULL" ){
                    $wheresql_04 = ""; }
                else {
					$wheresql_04 = " AND work_center = '".db_esc($dbc, $work_center)."'"; }
   
	       //5. Planned Order No.
                if ($plan_no == "NULL"){ 
                    $wheresql_05 = ""; }
                else {
                    $wheresql_05 = " AND plan_no = '".db_esc($dbc, $plan_no)."'"; }  	
					
		 
		  //6. Shift
                if ($shift_ops == "NULL"){ 
                    $wheresql_06 = ""; }
                else {
					// $wheresql_06 = ""; }
					
                    $wheresql_06 = " AND ((shift_pps1 = '".db_esc($dbc, $shift_ops)."') OR (shift_pps2 = '".db_esc($dbc, $shift_ops)."')) "; }  		 				                                             
		 // 7. Status
                if ($status == "NULL" ){
                    $wheresql_07 = ""; }
                else {
                    $wheresql_07 = " AND status_pps = '".db_esc($dbc, $status)."'"; }      
					
					
		 // 8. File name
                if ($name_file == "NULL" ){
                    $wheresql_08 = ""; }
                else {
                    $wheresql_08 = " AND upload_id = '".db_esc($dbc, $row_convert2["upload_id"])."'"; }    
					
		
		// 9. Plan Category
                if ($plan_category == "NULL" ){
                    $wheresql_09 = ""; }
                else {
                    $wheresql_09 = " AND plan_category = '".db_esc($dbc, $plan_category)."'"; }          	
	                                        
				
				$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04 .$wheresql_05 .$wheresql_06 .$wheresql_07 .$wheresql_08 .$wheresql_09;	
	
	//********** END CONDITION **************



								 
   $query8 = "SELECT COUNT(*) FROM pps_detail WHERE (status_pps != '".db_esc($dbc, $rst_sta["status_desc"])."' AND status_pps != '".db_esc($dbc, $rst_sta4["status_desc"])."' AND status_pps != '".db_esc($dbc, $rst_sta16["status_desc"])."') ".$where_sql;
   $result8 = mysqli_query($dbc, $query8) or die(db_fail($dbc));
   $num_rows = mysqli_fetch_row($result8);

   $pages = new Paginator;
   $pages->items_total = $num_rows[0];
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') as R FROM pps_detail WHERE (status_pps != '".db_esc($dbc, $rst_sta["status_desc"])."' AND status_pps != '".db_esc($dbc, $rst_sta4["status_desc"])."' AND status_pps != '".db_esc($dbc, $rst_sta16["status_desc"])."') ".$where_sql. " ORDER BY plan_no ASC";
$rs = mysqli_query($dbc, $query);   //run the query.
$num = mysqli_num_rows($rs);   //how many material are there?


	
	 if ($num > 0) {
	 
	 echo '<div align="center">There are currently  '. $num_rows[0].' record(s).</div>';
	 }
	

?>
            <table class="table" border="0">
               <tr>
                  <!-- <td width="1%">&nbsp;</td> -->
                  <td width="72%">
                     <div class="small-nav"></div>
                  </td>
                  <td width="14%"><a href="detail_pps_sheet_print_by_id.php?date1=<?php echo h($dateF); ?>&&date2=<?php echo h($dateT); ?>&&factory=<?php echo h($factory); ?>&&work_center=<?php echo h($work_center); ?>&&plan_no=<?php echo h($plan_no); ?>&&shift_ops=<?php echo h($shift_ops); ?>&&status=<?php echo h($status); ?>&&name_file=<?php echo h($name_file); ?>&&plan_category=<?php echo h($plan_category); ?>&&TB_iframe=true&height=500&width=1000" class="thickbox" target="_self"><img src="../img/print.jpg" width="48" height="48" title="Print Select" />Print Select</a></td>

                  <td width="14%"><a href="detail_pps_sheet_print_all_pdf.php?srcDateF=<?php echo h($dateF);?>&&srcDateT=<?php echo h($dateT);?>&&srcFactory=<?php echo h($factory);?>&&srcWC=<?php echo h($work_center);?>&&srcPlanNo=<?php echo h($plan_no);?>&&srcShift=<?php echo h($shift_ops);?>&&srcStatus=<?php echo h($status);?>&&srcNFile=<?php echo h($name_file);?>" target="_blank"><img src="../img/pdf-icon.png" width="30" height="45" title="Print Select" />PDF</a></td>
               </tr>
            </table>
            <div class="widget-box">
               <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                  <h5>Display Request</h5>
               </div>
               <div class="widget-content nopadding">
                  <table class="table table-bordered data-table" id="example">
                     <thead>
                        <tr>
                           <th>No.</th>
                           <th width="77">Part No.</th>
                           <th>Planned Order No.</th>
                           <th>Planned Date</th>
                           <th>Work Center</th>
                           <th>Shift</th>
                           <th>Planned Quantity</th>
                           <th>Planned Order Status</th>
                           <th>Options</th>
                           <th>Options</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        $counter = 1;
                        $no = 1;
                        
						while ($row = mysqli_fetch_array($rs)) {
                           if ($row["shift_pps1"] != "") {
                              $sta = "D/S";
                           } elseif ($row["shift_pps2"] != "") {
                              $sta = "N/S";
                           } else {
                              $sta = " ";
                           }
                          
						  
                        ?>
                           <tr>
                              <td width="48"><?php echo $no; ?></td>
                              <td><?php echo h($row["material_no"]); ?></td>
                              <td width="124" height="28"><?php echo h($row["plan_no"]); ?></td>
                              <td width="97"><?php echo h($row["R"]); ?></td>
                              <td width="99"><?php echo h($row["work_center"]); ?></td>
                              <td width="99"><?php echo $sta; ?></td>
                              <td width="99"><?php echo intval($row["qty_plan"]); ?></td>
                              <td width="99"><?php echo h($row["status_pps"]); ?></td>
                              <td width="99">&nbsp; <a value="Print" href="detail_pps_sheet_print_by_sel.php?plan_date=<?php echo h($row["date_plan"]); ?>&&work_centerA=<?php echo h($row["work_center"]); ?>&&TB_iframe=true&height=500&width=1000" class="thickbox" target="_self"> <img src="../img/print.jpg" width="20" height="20" alt="Print">Print</a></td>
                              <td width="99">&nbsp; <a value="PDF" href="detail_pps_sheet_print_by_sel_pdf.php?plan_date=<?php echo h($row["date_plan"]); ?>&&work_centerA=<?php echo h($row["work_center"]); ?>TB_iframe=true&height=500&width=1000" class="thickbox" target="_self"> <img src="../img/pdf.png" width="20" height="20" alt="Print">PDF</a></td>

                           </tr>
                        <?php
                           $no++;
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