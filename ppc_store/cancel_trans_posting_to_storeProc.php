<?php

/**
 * ppc_store/cancel_trans_posting_to_storeProc.php
 * Part of: PPC Store module
 * Filename suggests: cancel trans posting to storeProc
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, tp_store_detail.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_ppc_menu.php, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
error_reporting(E_ALL &~ E_NOTICE &~ E_DEPRECATED);
session_start();
$username = $_SESSION['username'];
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

$url = "cancel_trans_posting_to_store.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
    $result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
    $res = mysqli_fetch_array($result2);
	
//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------	

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
  <div id="breadcrumb"> <a href="index_ppc_store.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Transfer Posting</a> <a href="#" class="current">Cancel Document</a> </div>
  <h1>Cancel Document Transfer to Store</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
    <!--  <div class="span12">-->
        <div class="widget-box">
          <div class="widget-title">
             <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li class="active"><a  role="tab" href="cancel_trans_posting_to_store.php">TP to Store</a></li>
              <li><a role="tab" href="cancel_trans_posting_to_plb.php">TP to PLB</a></li>
              <li><a role="tab" href="cancel_trans_posting_to_subcont.php">TP to Subcont</a></li>
              <li><a role="tab" href="cancel_ret_posting_to_plb.php">Return from PLB</a></li>
              <li><a role="tab" href="cancel_ret_posting_to_subcont.php">Return from Subcont</a></li>
             </ul>
             
          </div>
          </div>
         
  
        
  <?php
 
            $dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
		    $doc_tp_from = $_GET["doc_tp_from"];
			$doc_tp_to = $_GET["doc_tp_to"];
			$shift_day = $_GET["shift_day"];
			$material_no = $_GET["material_no"];
	      	
			
	
 
 ?>        
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="frmSearch" id="frmSearch">
            <table class="table table-bordered table-striped">
              <tr>
                  <th width="184">Posting Date From :</th>
                  <td width="308"><?php
   
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
                  <th width="178">Posting Date To :</th>
                  <td width="359" colspan="2">
                <?php
			          
					  $dd2 = substr($_GET["date2"],8,2);
				      $mm2 = substr($_GET["date2"],5,2);
				      $yy2 = substr($_GET["date2"],0,4);
				
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
                 <th>Material Document No. From  :</th>
                 <td><input name="doc_tp_from" type="text" id="doc_tp_from" size="50" class="span11"  value="<?php  echo $_GET["doc_tp_from"];  ?>"/></td>
                 <th>Material Document No. To :</th>
                 <td><input name="doc_tp_to" type="text" id="doc_tp_to" size="50" class="span11"  value="<?php echo $_GET["doc_tp_to"]; ?>"/></td>
               </tr>
               <tr>
              <th>Part No. :</th>
              <td> <input name="material_no" type="text" id="material_no" size="50" class="span11" value="<?php echo $_GET["material_no"];  ?>"/></td>
              <th>Shift :</th>
              <td>  
              <select name="shift_day" id="shift_day" class="span11">
                  <option value="NULL" placeholder="Select Shift"> -- Select Shift --</option>
                  <option value="D/S" <?php if($_GET["shift_day"] == "D/S") { ?> selected="selected"<?php } ?>>D/S</option>
                  <option value="N/S" <?php if($_GET["shift_day"] == "N/S") { ?> selected="selected"<?php } ?>>N/S</option>
                  </select>
              </td>
               </tr>
               <tr>
                 <th>&nbsp;</th>
                 <td>&nbsp;</td>
                 <th>&nbsp;</th>
                 <td><input name="Submit" type="submit" value="Search" class="btn btn-info"></td>
               </tr>
              </table>
         
            <tr></form>
               
 <?php              
 
          //-------Count all results------------------------//
			
				 $where_sql = '';  
				 
		 //1. DateF 
                if ($dateF  == "0000-00-00" ){
                     $wheresql_01 = "";}
                else {
                     $wheresql_01 = " AND (posting_date >= '".db_esc($dbc, $dateF)."')";}  
		 
		 // 2. DateT
                if($dateT == "0000-00-00") {
                     $wheresql_02 = ""; }
                else {
                      $wheresql_02 = " AND (posting_date <= '".db_esc($dbc, $dateT)."')"; }
		
		 //3. doc tp from
                if ($doc_tp_from == ""){ 
                    $wheresql_03 = ""; }
                else {
                    $wheresql_03 = " AND doc_tp >= '".db_esc($dbc, $doc_tp_from)."'"; } 
					
          //4. doc tp to
                if ($doc_tp_to  == "" ){
                    $wheresql_04 = ""; }
                else {
					$wheresql_04 = " AND doc_tp <= '".db_esc($dbc, $doc_tp_tp)."'"; }
   
		  //5. Shift
                if ($shift_day == "NULL"){ 
                    $wheresql_05 = ""; }
                else {
				
                    $wheresql_05 = " AND shift_day = '".db_esc($dbc, $shift_day)."'"; }  
					
		  //6. Material No.
                if ($material_no == ""){ 
                    $wheresql_06 = ""; }
                else {
                    $wheresql_06 = " AND material_no = '".db_esc($dbc, $material_no)."'"; }  			 			
				
		$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04 .$wheresql_05 .$wheresql_06;	
	
	//********** END CONDITION **************

 
								 
   $query8 = "SELECT * FROM tp_store_detail WHERE status_tran = 'Y' AND status_tp = '".db_esc($dbc, $rst_sta19["status_desc"])."'".$where_sql."GROUP BY doc_tp";
   $result8 = mysqli_query($dbc, $query8) or die(mysqli_error($dbc));
   $num_rows = mysqli_fetch_row($result8);
   $num = mysqli_num_rows($result8);  

   $pages = new Paginator;
   $pages->items_total = $num_rows[0];
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
     $no = 1;
  
$query_sql2 = "SELECT *, DATE_FORMAT(posting_date,'%d-%m-%Y') as R2 FROM tp_store_detail WHERE status_tran = 'Y' AND status_tp = '".db_esc($dbc, $rst_sta19["status_desc"])."' ".$where_sql. "GROUP BY doc_tp ORDER BY posting_date DESC, posting_time DESC";
$result_sql2 = mysqli_query($dbc, $query_sql2);   //run the query.
//$num = mysqli_num_rows($result_sql2);   //how many material are there?


	
	// if ($num > 0) {
		
	 
	 echo '<div align="center">There are currently  '. $num.' record(s).</div>';
	// }
	




	?>   
    
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Cancel Document</h5>
        </div>

   

 <div class="widget-content nopadding">

   
        <div class="control-group">
        <!--  <div class="controls"> -->
          
  <table class="table table-bordered data-table">
    <thead>
     <tr>
    <th width="50">No.</th>
    <th width="100">Part No.</th> 
    <th width="150">Part Name</th>
    <th width="70">Posting Date</th>
    <th width="70">Posting Time</th>
    <th width="70">Quantity</th>
    <th width="70">UOM</th>
    <th width="70">Slip No.</th>
    <th width="70">Shift</th>
    <th width="100">Document No.</th>
    <th width="100">Action</th>
    </tr></thead>  <tbody>
    

 <?php
 
   while ($data_sql2 = mysqli_fetch_array($result_sql2))
   {
	   

	 $query_sql3 = "SELECT *, DATE_FORMAT(posting_date,'%d-%m-%Y') as R3 FROM tp_store_detail WHERE status_tran = 'Y' AND doc_tp = '".db_esc($dbc, $data_sql2["doc_tp"])."' ORDER BY doc_tp ASC";
     $result_sql3 = mysqli_query($dbc, $query_sql3);   //run the query.
	 

	   while ($data_sql3 = mysqli_fetch_array($result_sql3))
   {
	 
	 if($data_sql3["shift_day"] == "D/S")
	 {
		 $shift_desc = "Day";
		 
	 }else{
		 $shift_desc = "Night";
		 
	 }
		
	 ?>
     	   
     
   <tr class="gradeX">
    <td width="50"><?php echo $no; ?> </td>
    <td width="100"><?php echo $data_sql3["material_no"];  ?></td>
    <td width="150"><?php echo $data_sql3["material_desc"];  ?></td>
    <td width="70"><?php echo $data_sql3["R3"];  ?></td>
    <td width="70"><?php echo $data_sql3["posting_time"];  ?></td>
    <td width="70"><?php echo $data_sql3["qty_tp"];   ?> </td>
    <td width="70"><?php echo $data_sql3["uom"];  ?></td>
    <td width="70"><?php echo $data_sql3["slip_no"];  ?></td>
    <td width="70"><?php echo $shift_desc;  ?></td>
    <td width="100"><?php echo $data_sql3["doc_tp"];  ?></td>
    <td width="100"><div align="center"><a value="Cancel Document Transfer to Store" href="cancel_tp_to_store.php?uid=<?php echo $data_sql3["doc_tp"]; ?>&&TB_iframe=true&height=400&width=850" class="thickbox" target="_self"><img src="../img/font_red_delete.png" width="16" height="16" alt="Cancel Document Transfer to Store">Cancel</a></div></td>
  </tr>



<?php 
  
  
   }

 $no++;
mysqli_free_result($result_sql3);   
 }  
  ?></tbody></table> 
 &nbsp;</div>
</div>  

<?php

   //mysqli_free_result($result_sql2);   
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
