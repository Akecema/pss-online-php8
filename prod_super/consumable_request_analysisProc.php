<?php

/**
 * prod_super/consumable_request_analysisProc.php
 * Part of: Production module (supervisor/admin tier)
 * Filename suggests: consumable request analysisProc
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: user_detail, sys_setup_maintain, factory_detail, consumable_detail, consumable_request, post_consumable_detail_header.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_prod_super_menu.php, ]., ]);
$date_transfer = ($row5[, td>
  
    <td width=.
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
require_role($dbc, 3);
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
<?php include "left_prod_super_menu.php";  ?>
<!--sidebar-menu-->
</div>
<div id="content">
 <div id="content-header" class="noprint">
  <div id="breadcrumb"> <a href="index_production_super.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Consumable Request</a> <a href="#" class="current">Consumable Request Analysis</a> </div>
  <h1>Consumable Request</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
    <!--  <div class="span12">-->
      
          
       <form action="<?php echo h($_SERVER['PHP_SELF']); ?>" method="get" name="frmSearch" id="frmSearch">
         <table class="table table-bordered table-striped">
               <tr>
                  <th>Request Date From :</th>
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
              <th>Factory :</th>
              <td><select name="factory" id="factory">
                  <option value="NULL" placeholder="Select Factory"> -- Select Factory --</option>
                  <?php
	               $query3 = "SELECT * FROM factory_detail GROUP BY factory_desc2 ORDER BY id_fac ASC";
                   $result3 = mysqli_query($dbc, $query3);
  
                   while($row3=mysqli_fetch_array($result3, MYSQLI_NUM)) 
			      {
				  
				  
				  ?>
                  <option value="<?php echo h($row3[2]); ?>" <?php if($row3[2] == $_GET["factory"]) echo "selected"; ?>> <?php echo h($row3[1]); ?></option>
                  <?php
                  }
				?>
              </select></td>
              <th>MRIN Status :</th>
              <td><select name="status" id="status" >
                  <option value="NULL" placeholder="Select Status"> -- Select Status --</option>
                  <option value="New" <?php if($_GET["status"] == "New") { ?> selected="selected"<?php } ?>>Open</option>
                  <option value="Close" <?php if($_GET["status"] == "Close") { ?> selected="selected"<?php } ?>>Close</option>
              </select></td>
            </tr>
            <tr>
              <th>Material No.  :</th>
              <td><select name="material_no" id="material_no">
                  <option value="NULL" placeholder="Select Material No."> -- Select Material No. --</option>
                  <?php
	               $query9 = "SELECT * FROM consumable_detail WHERE con_status = 'Y'";
                   $result9 = mysqli_query($dbc, $query9);
  
                   while($row9=mysqli_fetch_array($result9)) 
			      {
				   ?>
                  <option value="<?php echo h($row9["material_no"]); ?>" <?php if($row9["material_no"] == $_GET["material_no"]) echo "selected"; ?>> <?php echo h($row9["material_no"]).' -  '.h($row9["mat_desc"]); ?></option>
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
	       
		    $material_no = $_GET["material_no"];
			$factory = $_GET["factory"];
		    $status = $_GET["status"];
			$dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			
		    //convert material no kpd id_hdr
			
			$query_convert = "SELECT * FROM `consumable_detail` as MH WHERE MH.material_no = ?"; $query_convert_args = [$_GET["material_no"]];
			$result_convert = db_query_bind($dbc, $query_convert, $query_convert_args); 
			$row_convert = mysqli_fetch_array($result_convert);
		
				
							
	//********* CONDITION *************
	
	 $where_sql = ""; $where_args = [];
		 
		 // 1. material_no
                if($material_no == "NULL") {
                     $wheresql_01 = ""; $wheresql_01_args = []; }
                else {
                      $wheresql_01 = " AND MR.material_no = ?"; $wheresql_01_args = [$material_no]; }
		  // 2. dateF
                if ($dateF == "0000-00-00" ){
                    $wheresql_02 = ""; $wheresql_02_args = []; }
                else {
                    $wheresql_02 = " AND (MR.date_require >= ?)"; $wheresql_02_args = [$dateF]; }      
                                                
		 // 3. dateT
                if ($dateT == "0000-00-00" ){
                    $wheresql_03 = ""; $wheresql_03_args = []; }
                else {
                    $wheresql_03 = " AND (MR.date_require <= ?)"; $wheresql_03_args = [$dateT]; }          
                                
          //4. factory
                if ($factory == "NULL" ){
                    $wheresql_04 = ""; $wheresql_04_args = []; }
                else {
					$wheresql_04 = " AND MR.factory = ?"; $wheresql_04_args = [$factory]; }
					
		 //5. status
                if ($status == "NULL" ){
                    $wheresql_05 = ""; $wheresql_05_args = []; }
                else {
					$wheresql_05 = " AND MR.status = ?"; $wheresql_05_args = [$status]; }
   
	   
			$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04 .$wheresql_05; $where_args = array_merge($wheresql_01_args, $wheresql_02_args, $wheresql_03_args, $wheresql_04_args, $wheresql_05_args);
	
	
	//********** END CONDITION **************

 $query8 = "SELECT *,DATE_FORMAT(MR.date_require,'%d-%m-%Y') AS R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R2 FROM consumable_request AS MR, consumable_detail AS SD WHERE MR.id_con = SD.id_con AND MR.status != 'Cancel'" .$where_sql; $query8_args = [...$where_args];
   $result8 = db_query_bind($dbc, $query8, $query8_args) or die(db_fail($dbc));
   $num_8 = mysqli_fetch_row($result8);
   $num_rows = mysqli_num_rows($result8);
   
   $pages = new Paginator;
   $pages->items_total = $num_rows;
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT *,DATE_FORMAT(MR.date_require,'%d-%m-%Y') AS R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R2 FROM consumable_request AS MR, consumable_detail AS SD WHERE MR.id_con = SD.id_con AND MR.status != 'Cancel'".$where_sql; $query_args = [...$where_args];
$rs = db_query_bind($dbc, $query, $query_args);   //run the query.
$num = mysqli_num_rows($rs);   //how many material are there?

	
	 if($num > 0) {
	 
	 echo '<div align="center">There are currently  '. h($num).' record(s).</div>';
	
?>

<table class="table">
<tr>
    <td width="1%">&nbsp;</td> 
    <td width="85%"> <div class="small-nav"></div></td> 
       <td width="7%"><a href="report_consumable_request_analysisProc.php?factory=<?php echo h($factory); ?>&&status=<?php echo h($status); ?>&&date1=<?php echo h($dateF); ?>&&date2=<?php echo h($dateT); ?>&&material_no=<?php echo h($material_no); ?>"><img src="../img/dload_excel.jpg" width="48" height="48" title="Download" /></a></td>
     <td width="7%"><img src="../img/print2.jpg" width="48" height="48" onClick="window.print()" title="Print"/></td>
   
  </tr>
</table>

        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display Consumable Request Analysis</h5>
          </div>
             
          <div class="widget-content nopadding">
          
    <table class="table table-bordered data-table">
     <thead>
 <tr>
      <th width="50" height="28" bgcolor="#E9F58D"><span class="style3">Factory</span></th>
          <th width="60" height="28" bgcolor="#E9F58D"><span class="style3">Line</span></th>
          <th width="100" height="28" bgcolor="#E9F58D"><span class="style3">MRIN No.</span></th>
          <th width="180" height="28" bgcolor="#E9F58D"><span class="style3">Material No.</span></th>
          <th width="60" height="28" bgcolor="#E9F58D"><span class="style3">UoM</span></th>
          <th width="90" height="28" bgcolor="#E9F58D"><span class="style3">Requested <br />
          Quantity</span></th>
          <th width="90" height="28" bgcolor="#E9F58D"><span class="style3">Transferred <br />
          Quantity</span></th>
          <th width="90" height="28" bgcolor="#E9F58D"><span class="style3">Variance <br />
          Quantity</span></th>
          <th width="100" height="28" bgcolor="#E9F58D"><span class="style3">Required <br />Date</span></th>
          <th width="80" height="28" bgcolor="#E9F58D" class="ac style3"><div align="center">Required Time</div></th>
          <th width="70" bgcolor="#E9F58D" class="ac style3">Duration</th>
          <th width="55" bgcolor="#E9F58D" class="ac style3">Status</th>
      </tr></thead><tbody>
       
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

 	$query3 = "SELECT * FROM factory_detail WHERE id_fac = ?"; $query3_args = [$row2["factory"]];
    $result3 = db_query_bind($dbc, $query3, $query3_args);
	$row3 = mysqli_fetch_array($result3);
	
	$query5 = "SELECT * FROM post_consumable_detail_header WHERE mrin_no = ? AND material_no = ? AND mvt_type = 201"; $query5_args = [$row2["temp_mrin"], $row2["material_no"]];
    $result5 = db_query_bind($dbc, $query5, $query5_args);
	$row5 = mysqli_fetch_array($result5);
	
	$query6 = "SELECT * FROM post_consumable_detail_header AS PD, consumable_request AS MR WHERE PD.mrin_no = MR.temp_mrin AND PD.material_no = MR.material_no AND PD.mrin_no = ? AND PD.material_no = ? AND PD.mvt_type = 201"; $query6_args = [$row2["temp_mrin"], $row2["material_no"]];
    $result6 = db_query_bind($dbc, $query6, $query6_args);
	$row6 = mysqli_fetch_array($result6);
	

//-------------------------------------------------------Transfer Posting [Traffic Light] --------------------------
// table post_detail_header --- checking traffic licht
//-----------------------------------------------------------------------------------------------------------------

$date_post =  ($row2["date_require"].' '.$row2["time_require"]);
$date_transfer = ($row5["date_create"].' '.$row5["time_create"]);

$start_date = new DateTime($date_post);
$since_start = $start_date->diff(new DateTime($date_transfer));

/*
echo $since_start->m.' month<br>';
echo $since_start->d.' days<br>';
echo $since_start->h.' hours<br>';
echo $since_start->i.' minutes<br>';
echo $since_start->s.' seconds<br>';   
*/
//------------------------------------------------------ Variance Quantity-----------------------------------------
//
//------------------------------------------------------------------------------------------------------------------	

					
    $query_tp = "SELECT *,SUM(rquantity) as TOT FROM post_consumable_detail_header WHERE mrin_no = ? AND mvt_type = 201 AND material_no = ?"; $query_tp_args = [$row2["temp_mrin"], $row2["material_no"]];
	$result_tp  = db_query_bind($dbc, $query_tp, $query_tp_args); 

    $outs_qty = 0;
					
	while($row_tp = mysqli_fetch_assoc($result_tp))
   {
	
	$tp_quantity = $row_tp["TOT"]; 
	
	$outs_qty = ((($row2["con_qty"])) - (($row_tp["TOT"])));
	
	 }//end while $row_tp	
	 
	$outs_qty1 = number_format($outs_qty,3);	
	
	  $bq = ($row2["con_qty"]);	
	  $rq = ($row5["rquantity"]);
	
      $variance_qty = ($bq - $rq);
	  $variance_qty2 =  number_format($variance_qty, 3, '.', '');
	  

		  ?>   
 
       
    <tr>
    <td width="50"><div align="center"><?php echo h($row2["factory"]); ?></div></td> 
    <td width="60" height="28"><div align="center"><?php echo h($row2["id_work"]); ?></div></td>
    <td width="100">&nbsp;<?php echo h($row2["temp_mrin"]); ?></td>
    <td width="160">&nbsp;<?php echo h($row2["material_no"]); ?></td>
    <td width="50" height="28"><div align="center"><?php echo h($row2["con_uom"]); ?></div></td>
    <td width="90"><div align="right"><?php echo h($row2["con_qty"]); ?></div>   </td>
    <td width="90"><div align="right"><?php echo h($row5["rquantity"]); ?></div></td>
    <td width="90"><div align="right"><?php if($variance_qty2 < 0 ) { echo "<font color='red'>";  echo h($variance_qty2);  echo "</font>"; }else{ echo h($variance_qty2); } ?></div>
    <td><?php echo h($row2["R"]); ?></td>
    <td width="100"><?php echo h($row2["time_require"]); ?></td>
  
    <td width="70">
	<?php
	//-------------------------------------------------------------------------------------
	//-   check status "Open" and still dont have TP
	//---------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------
	if ($row6 > 0)
	{
	
     echo $since_start->d." days  ".$since_start->h." : ".$since_start->i; 
	}else{
	
	echo "&nbsp;";
	}
 
	?>
	
	
	</td>
    <td width="55">
	<?php if($row2["status"] == "New")
	 {
	  echo "Open"; 
	 
	 }else{ 
	 
	  echo "Close"; 
	   } ?> </td>
  <?php 
		 
		 $no ++;
		  
		  $counter++; // menambah counter 
		   
		   //}// end if
		
		    
		  } ?></tr>
          </tbody></table>   

  
            
  <?php
  mysqli_free_result($rs); 
	}   // free up the resources 
else
{
?><center>
<table width="800" cellspacing="0" class="textboxred">
  <tr> 
    <td><div align="center"><font color="#FF0000"><strong>There are currently 
          no material request.</strong></font></div></td>
  </tr>
</table></center>
        <?php
		   } 
 mysqli_close($dbc);
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
