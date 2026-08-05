<?php
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");

//$Cdate = date ("l, j F Y ");
date_default_timezone_set('Asia/Kuala_Lumpur');

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "history_consumable_request.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
    $result2 = mysql_query($query2) or die (mysql_error());
    $res = mysql_fetch_array($result2);
	
$today = getdate();
$hours = $today['hours']; 
$minutes = $today['minutes'];
$seconds = $today['seconds'];
$month = $today['mon']; 
$mday = $today['mday']; 
$year = $today['year']; 	
		
//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysql_query($query_setup);   //run the query.
$num_setup = mysql_num_rows($rs_setup);   //how many material are there?
$data_setup = mysql_fetch_array($rs_setup);
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

<script type="text/javascript">
function printPage(iFid){
iFid.focus();
iFid.print();
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
<?php include "left_ppc_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_ppc_super.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Consumable Request</a> <a href="#" class="current">Consumable Request History</a> </div>
  <h1>Consumable Request</h1>
</div>




<div class="container-fluid">
  <hr>
  <div class="row-fluid">

           <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="frmSearch" id="frmSearch">
            <table class="table table-bordered table-striped">
            <tr>
              <th>Posting Date From :</th>
              <td><?php
    
				
				      $dd1 = substr($_GET["date1"],8,2);
					  $mm1 = substr($_GET["date1"],5,2);
					  $yy1 = substr($_GET["date1"],0,4);
	
                      $myCalendar = new tc_calendar("date1", true, false);
					  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					  $myCalendar->setDate($dd1, $mm1, $yy1);
					  $myCalendar->setPath("/calendar/");
					  $myCalendar->setYearInterval((date('Y')- 1), date('Y') + 10);
					  // $myCalendar->setOnChange("myChanged('test')");
					  $myCalendar->writeScript();
					  
			 ?></td>
              <th>Posting Date To :</th>
              <td><?php
                
                      $dd2 = substr($_GET['date2'],8,2);
					  $mm2 = substr($_GET['date2'],5,2);
					  $yy2 = substr($_GET['date2'],0,4);
				
                      $myCalendar = new tc_calendar("date2", true, false);
					  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					  $myCalendar->setDate($dd2, $mm2, $yy2);
					  $myCalendar->setPath("/calendar/");
					  $myCalendar->setYearInterval((date('Y')- 1), date('Y') + 10);
					  // $myCalendar->setOnChange("myChanged('test')");
					  $myCalendar->writeScript();
                
				
                ?></td>
            </tr> 
             <tr>
              <th>Factory : </th>
              <td><select name="factory" id="factory" class="span11">
                <option value="NULL" placeholder="Select Factory"> -- Select Factory --</option>
                <?php
	       $query3 = "SELECT * FROM factory_detail GROUP BY factory_desc2 ORDER BY id_fac ASC";
                   $result3 = mysql_query($query3);
  
                   while($row3=mysql_fetch_array($result3, MYSQL_NUM)) 
			      {
				  
				  
				  ?>
                <option value="<?php echo $row3[2]; ?>" <?php if($row3[2] == $_GET["factory"]) echo "selected"; ?>> <?php echo $row3[1]; ?></option>
                <?php
                  }
				?>
              </select></td>
               <th>MRIN No :</th>
              <td><input name="temp_mrin" type="text" id="temp_mrin" class="span11" value="<?php echo $_GET["temp_mrin"]; ?>" /></td>
            </tr>
           
           
            <tr>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <td><input name="Submit2" type="submit"  class="btn btn-info" id="button" value="SEARCH" /></td>
            </tr>
            </table>
        </form>
<?php
            $temp_mrin = $_GET["temp_mrin"];
			$dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			$factory = $_GET["factory"];
			
				//convert material no kpd id_hdr
			
			$query_convert = "SELECT * FROM `factory_detail` as MH WHERE MH.factory_desc = '".$_GET["factory"]."'";
			$result_convert = mysql_query($query_convert); 
			
			while ($row_convert = mysql_fetch_array($result_convert))
			{
			
			echo $row_convert["id_fac"];
			
			}
			
			//-------Count all results------------------------//
			
				 $where_sql = '';
		 
		 // 1. DateT
                if($dateT == "0000-00-00") {
                     $wheresql_01 = ""; }
                else {
                      $wheresql_01 = " AND (MR.date_posting <= '$dateT')"; }
		  //2. DateF 
                if ($dateF  == "0000-00-00" ){
                     $wheresql_02 = "";}
                else {
                     $wheresql_02 = " AND (MR.date_posting >= '$dateF')";}
                                                
		 // 3. Temp MRIN
                if ($temp_mrin == "" ){
                    $wheresql_03 = ""; }
                else {
                    $wheresql_03 = " AND MR.temp_mrin = '$temp_mrin'"; }          
                                
       				
		   //4. Factory 
                if ($factory == "NULL"){ 
                    $wheresql_04 = ""; }
                else {
                    $wheresql_04 = " AND MR.factory = '$factory'"; }  			
	       	
	                                        
				
				$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04;	
	
	//********** END CONDITION **************
			
	$query8 = "SELECT *, DATE_FORMAT(MR.date_require,'%d-%m-%Y') AS R FROM consumable_request_close AS MR, consumable_detail AS SD WHERE MR.id_con = SD.id_con AND MR.status_request = 'Y' AND MR.status = 'Close'".$where_sql." GROUP BY MR.temp_mrin";
     $result8 = mysql_query($query8) or die(mysql_error());
     $num_8 = mysql_fetch_row($result8);
     $num_rows = mysql_num_rows($result8);
 
   $pages = new Paginator;
   $pages->items_total = $num_rows;
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
   
  
	$query = "SELECT *, DATE_FORMAT(MR.date_require,'%d-%m-%Y') AS R FROM consumable_request_close AS MR, consumable_detail AS SD WHERE MR.id_con = SD.id_con AND MR.status_request = 'Y' AND MR.status = 'Close'".$where_sql." GROUP BY MR.temp_mrin ORDER BY MR.date_posting ASC, MR.temp_mrin ASC ";
	$rs = mysql_query($query);   //run the query.GROUP BY MR.temp_mrin

	
	 if($num_rows > 0) {	
	 
	 echo '<div align="center">There are currently  '. $num_rows.' record(s).</div>';
	

?>

       
      <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display Cosumable Request History</h5>
          </div>
             
          <div class="widget-content nopadding">
          
             <table class="table table-bordered data-table" data-sort-name="name" data-sort-order="desc">
             <thead>
              <tr>
                <th data-field="name" data-sortable="true">MRIN No. <i class="icon-sort"></i></th>
                <th>Factory</th>
                <th>Line</th>
                <th data-field="name" data-sortable="true">Request Date <i class="icon-sort"></i></th>
                <th>Request Time</th>
                <th>Requestor</th>
                <th>View</th>
                <th>Print</th>
            </tr>
           </thead>
           <tbody>
    <?php
		  
   $counter = 1;
   $no = 1;
   
    while ($row2 = mysql_fetch_array($rs))
   {
	
   $query_u = "SELECT * FROM user_detail WHERE user_no = '$row2[user_create]'";
	$result_u = mysql_query($query_u);   //run the query.
	$data_u = mysql_fetch_array($result_u);   //how many records are there?    

 	
	//$query4_p = "SELECT * from mat_master_header as LD, mat_master_detail as SD WHERE SD.id_hdr = LD.id_hdr and SD.id_dtl = '".$row2[5]."'";
  //	$result4_p = mysql_query($query4_p);
 //	$row4_p = mysql_fetch_array($result4_p); 
 
  
	  
		  ?>
   <tr>
    <td width="143">&nbsp;
        <?php if($row2["status_view"] == "N") { echo "<b>";  echo $row2["temp_mrin"]."</b>"; } else {  echo $row2["temp_mrin"];  } ?></td>
    <td width="50">&nbsp;&nbsp;<?php echo $row2["factory"]; ?></td>
    <td width="50">&nbsp;&nbsp;<?php echo $row2["id_work"]; ?></td>
    <td width="100">&nbsp;&nbsp;<?php echo $row2["R"]; ?></td>
    <td width="100">&nbsp;&nbsp;<?php echo $row2["time_require"]; ?></td>
    <td width="134">&nbsp;&nbsp;<?php echo $data_u["user_fullname"]; ?></td>
    <td width="60"><a value="Details" href="history_detail_consumable_request.php?mrin_no=<?php echo $row2["temp_mrin"]; ?>&amp;&amp;TB_iframe=true&amp;height=400&amp;width=800" class="thickbox" target="_self"><img src="../img/icon_view.jpg" width="16" height="16" alt="View" />View</a></td>
    <td width="60"><a href="history_detail_consumable_request_printing.php?mrin_no=<?php echo $row2["temp_mrin"]; ?>&amp;&amp;TB_iframe=true&amp;height=400&amp;width=800" class="thickbox" target="_self"><img src="../img/print.jpg" width="16" height="16" alt="Print" />Print</a> </td>
  </tr>

<?php 
		 
		  $no ++;
		  $counter++; // menambah counter 
		   
		   //}// end if
		
		    
		  } ?></tbody></table> 

  <?php
   mysql_free_result($rs); 
	?>
      
          
          
    <?php }   // free up the resources 
  else
{
?>
<table width="800" cellspacing="0" class="textboxred">
  <tr> 
    <td><div align="center"><font color="#FF0000"><strong>There are currently 
          no material request.</strong></font></div></td>
  </tr>
</table>
        <?php
		   } 
// mysql_close()
?>


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