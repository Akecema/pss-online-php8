<?php
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "report_prod_consumable.php";

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
<style>
#iframe1{
visibility:hidden;
}
</style>	
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
  <div id="breadcrumb"> <a href="index_production.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Consumable Request</a> <a href="#" class="current">Consumable Request Report</a> </div>
  <h1>Consumable Request</h1>
</div>




<div class="container-fluid">
  <hr>
  <div class="row-fluid">

           <form name="frmSearch" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
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
					  $myCalendar->setYearInterval(2010, 2030);
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
					$myCalendar->setYearInterval(2010, 2030);
					// $myCalendar->setOnChange("myChanged('test')");
					$myCalendar->writeScript();
                
                ?></td>
            </tr>
            <tr>
              <th>Material No :</th>
              <td><input name="material_no" type="text" id="material_no" size="25" class="span11" value="<?php echo $_GET["material_no"]; ?>" /></td>
              <th>Factory :</th>
              <td><select name="factory" id="factory">
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
            </tr>
            <tr>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th><input name="Submit" type="submit"  class="btn btn-info" id="button" value="SEARCH" /></th>
            </tr>
            </table>
        </form>
 
      <?php
           $material_no = $_GET["material_no"];
            $dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			$factory = $_GET["factory"];
			
			
			
			//convert material no kpd id_hdr
			
			$query_convert2 = "SELECT * FROM `factory_detail` as MH2 WHERE MH2.factory_desc = '".$_GET["factory"]."'";
			$result_convert2 = mysql_query($query_convert2); 
			
			while ($row_convert2 = mysql_fetch_array($result_convert2))
			{
			
			//echo $row_convert2["id_fac"];
			
			}
			
			
			//-------Count all results------------------------//
		 $where_sql = '';
		 
		 // 1. temp_mrin
                if($material_no == "") {
                     $wheresql_01 = ''; }
                else {
                      $wheresql_01 = " AND MR.material_no = '$material_no'"; }
		  // 2. dateF
                if ($dateF == "0000-00-00" ){
                    $wheresql_02 = ''; }
                else {
                    $wheresql_02 = " AND (MR.date_posting >= '$dateF')"; }      
                                                
		 // 3. dateT
                if ($dateT == "0000-00-00" ){
                    $wheresql_03 = ''; }
                else {
                    $wheresql_03 = " AND (MR.date_posting <= '$dateT')"; }          
                                
          //4. factory
                if ($factory == "NULL" ){
                    $wheresql_04 = ''; }
                else {
					$wheresql_04 = " AND MR.factory = '$factory'"; }
   
	   
			$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04;
			
	//********** END CONDITION **************
 

 $query8 = "SELECT * FROM consumable_request AS MR WHERE MR.status_request = 'Y' AND MR.status != 'Cancel'" .$where_sql."GROUP BY MR.id_scan ORDER BY MR.id_req_con ASC";
   $result8 = mysql_query($query8) or die(mysql_error());
   $num_rows = mysql_num_rows($result8);
   
   $pages = new Paginator;
   $pages->items_total = $num_rows;
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT * FROM consumable_request AS MR WHERE MR.status_request = 'Y' AND MR.status != 'Cancel'".$where_sql."GROUP BY MR.id_scan ORDER BY MR.id_req_con ASC";
$rs = mysql_query($query);   //run the query.

	
	 if($num_rows > 0) {	
	 
	 echo '<div align="center">There are currently  '. $num_rows.' record(s).</div>';
	
	

?>

       
      <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display Cosumable Request</h5>
          </div>
             
          <div class="widget-content nopadding">
          
            <table class="table table-bordered">
             <thead>
              <tr>
                <th>Item</th>
                <th>Material No. </th>
                <th>Production Order Number</th>
                <th>Required Quantity</th>
                <th>UoM</th>
                <th>Work Center</th>
            </tr>
            </thead>
           
                 <tbody>
    <?php
		
   $i = 1;  
   $counter = 1;
   $no = 1;
   
    while ($row2 = mysql_fetch_array($rs))
   {
	?>
    
    <tr>
    <td width="55"><?php echo $no; ?></td>
    <td>&nbsp;<?php if ($row2["status"] == "Close") {  ?><font color="#FF0000"><b><?php echo $row2["temp_mrin"]; ?> </b></font> <?php } else { ?><font color="#000099"><b><?php echo $row2["temp_mrin"]; ?></b></font> <?php }  ?></td>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
     </tr>
   

     
         <?php
	$query_again = "SELECT * FROM consumable_request AS MR, consumable_detail AS SD WHERE MR.material_no = SD.material_no AND status_request = 'Y' and id_scan = '".$row2[5]."' ORDER BY id_req_con ASC";
    $rs_again = mysql_query($query_again);   //run the query.
	 while ($row = mysql_fetch_array($rs_again))
   {


		  	 
		 ?>
 			
               <tr>
               <td width="55">&nbsp;&nbsp;</td>
                <td width="154">&nbsp;<?php  echo $row["material_no"]; ?></td>
                <td><?php  echo $row["mat_desc"]; ?></td>
                <td width="144"><div align="right"><?php echo $row["con_qty"];  ?>&nbsp;</div></td>
                <td width="80"><div align="center"><?php echo $row["con_uom"]; ?></div></td>
                <td width="100"><div align="center"><font color="#FF0000"><?php echo $row["id_work"]; ?></font></div></td>
               </tr>
      
         <?php 
		 
		
		 $i++;
		  $counter++; // menambah counter 
		 }
		
		 
		 $no ++;
		echo "  </tbody>"; 
	
		  } ?> </table>
  
                    
            
   <?php
   mysql_free_result($rs); 
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
//mysql_close()

?>          </div>



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