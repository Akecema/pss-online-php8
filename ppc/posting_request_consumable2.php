<?php
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");

//$Cdate = date ("l, j F Y ");
date_default_timezone_set("Asia/Kuala_Lumpur");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "posting_request_consumable.php";

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
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

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
		
		var strURL="findWorkcenter3.php?factory="+factory;
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
<?php include "left_ppc_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_ppc.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Consumable Request</a> <a href="#" class="current">Display Consumable Request</a> </div>
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
              <td><select name="factory" id="factory" onChange="getFactory(this.value)">
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
              <th>Work Center :</th>
              <td><div id="work_centerdiv"> 
               <select name="work_center" id="work_center" class="span11">
                <option value="NULL" placeholder="Select Work Center"> -- Select Work Center --</option>
                 <?php
	               $query5 = "SELECT * FROM work_center_detail WHERE id_factory = '".$_GET["factory"]."' ORDER BY id_work ASC";
                   $result5 = mysql_query($query5);
  
                   while($row5=mysql_fetch_array($result5)) 
				    { 
				   
				   ?>
                <option value="<?php echo $row5["id_work"]; ?>" <?php if($row5["id_work"] == $_GET["work_center"]) echo "selected"; ?>> <?php echo $row5["id_work"],' - ',stripslashes($row5["wc_desc"]); ?></option>
                <?php
                  }
				?>
                </select></div></td>
            </tr>
            <tr>
              <th>MRIN No :</th>
              <td><input name="temp_mrin" type="text" id="temp_mrin" size="25" class="span11" value="<?php echo $_GET["temp_mrin"]; ?>" /></td>
              <th>&nbsp;</th>
              <td>&nbsp;</td>
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
    $temp_mrin = $_GET["temp_mrin"];
			//$cost_center = $_GET["cost_center"];
            $dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			$work_center = $_GET["work_center"];
			$factory = $_GET["factory"];
		

					
	//********* CONDITION *************
	
	
		
			//-------Count all results------------------------//
		 $where_sql = '';
		 
		 // 1. temp_mrin
                if($temp_mrin == "") {
                     $wheresql_01 = ''; }
                else {
                      $wheresql_01 = " AND MR.temp_mrin = '$temp_mrin'"; }
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
					
		  //5. work center
                if ($work_center == "NULL" ){
                    $wheresql_05 = ''; }
                else {
					$wheresql_05 = " AND MR.id_work = '$work_center'"; }
   
	   
			$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04 .$wheresql_05;
	
	
	//********** END CONDITION **************

	  //------range date for 30 days in draft ---------------------------
 $start_date_check_draft = date('Y-m-d', strtotime("-30 days"));
 $end_date_check_draft = date('Y-m-d', strtotime("+30 days"));


								 
   $query8 = "SELECT *, DATE_FORMAT(MR.date_require,'%d-%m-%Y') AS R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R2 FROM consumable_request AS MR, consumable_detail AS SD WHERE MR.id_con = SD.id_con AND MR.status_request = 'Y' AND (MR.date_require >= '$start_date_check_draft' AND MR.date_require <= '$end_date_check_draft') ".$where_sql." GROUP BY MR.temp_mrin ORDER BY MR.id_con ASC";
   $result8 = mysql_query($query8) or die(mysql_error());
   $num_rows = mysql_num_rows($result8); 
 
   $pages = new Paginator;
   $pages->items_total = $num_rows;
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
//echo $num_rows;
  
$query = "SELECT *, DATE_FORMAT(MR.date_require,'%d-%m-%Y') AS R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R2 FROM consumable_request AS MR, consumable_detail AS SD WHERE MR.id_con = SD.id_con AND MR.status_request = 'Y' AND (date_require >= '$start_date_check_draft' AND date_require <= '$end_date_check_draft') ".$where_sql." GROUP BY MR.temp_mrin ORDER BY MR.date_posting ASC, MR.temp_mrin ASC ";
$rs = mysql_query($query);   //run the query.

	
	 if($num_rows > 0) {	
	 
	 echo '<div align="center">There are currently  '. $num_rows.' record(s).</div>';
	

?>

       
      <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display Consumable Request</h5>
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
                <th>Status</th>
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
	
   
  
	$query_again = "SELECT * FROM consumable_request WHERE status_request = 'Y' and id_scan = '".$row2[5]."'";
    $rs_again = mysql_query($query_again);   //run the query.
    $row = mysql_fetch_array($rs_again);
	
	$query3 = "SELECT * FROM factory_detail WHERE id_fac = '".$row2["factory"]."'";
    $result3 = mysql_query($query3);
	$row3 = mysql_fetch_array($result3);
	
	$query_u = "SELECT * FROM user_detail WHERE user_no = '".$row["user_create"]."'";
    $result_u = mysql_query($query_u);   //run the query.
    $data_u = mysql_fetch_array($result_u);   //how many records are there?  
	
	//------------------------------
	// check yg mana dah ada dlm table history 
	//------------------------------
	
	    $query_check = "SELECT * FROM consumable_request_close WHERE temp_mrin = '".$row2["temp_mrin"]."'";
		$result_check = mysql_query($query_check);		
		$rst_check = mysql_fetch_array($result_check);
		
		if($rst_check > 0)
		
		{
		//skip for duplicate data
		$stat = '<font color="#FF0000"><b>Close</b></font>';
	
		}else{
	
	   $stat = '<font color="#009900"><b>Open</b></font>';
	
	       
	
	    $query_check2 = "SELECT * FROM consumable_request_cancel WHERE temp_mrin = '".$row2["temp_mrin"]."'";
		$result_check2 = mysql_query($query_check2);		
		$rst_check2 = mysql_fetch_array($result_check2);
	
	           if($rst_check2 > 0)
		 {
		   $stat = '<font color="#FFCC00"><b>Cancel</b></font>';
		  }
	   } 
	   
	   
	   
	  $sql2 = "SELECT *, DATE_FORMAT(MR.date_require,'%d-%m-%Y') AS R FROM consumable_request AS MR, consumable_detail AS SD WHERE MR.id_con = SD.id_con AND MR.status_request = 'Y' AND (MR.status != 'Close' AND MR.status != 'Cancel') GROUP BY MR.temp_mrin, SD.cost_center ORDER BY MR.date_require DESC,MR.time_require DESC";
      $result2 = mysql_query($sql2) or die(mysql_error());
	  
	  while ($list = mysql_fetch_array($result2)) {
   
 //--------------------------------------------------------------------------------------------------------------
   //Update listing board   - MRIN disappear from listing if all component status_posting = "Close"         	        
   //--------------------------------------------------------------------------------------------------------------
	 $TOT = 0.000;
	 $outs_qty = 0;
	 
	$query_tp = "SELECT *,SUM(rquantity) as TOT FROM post_consumable_detail_header WHERE mrin_no = '".$list["temp_mrin"]."' AND mvt_type = 201 AND status_posting = 'New' GROUP BY material_no";
	$result_tp  = mysql_query($query_tp); 
	//$row_tp = mysql_fetch_assoc($result_tp); 

	$outs_qty = 0;

    while($row_tp = mysql_fetch_assoc($result_tp))
{
  
		
	$tp_quantity = $row_tp["TOT"]; 
	
	$outs_qty = (($row["con_qty"]) - ($row_tp["TOT"]));
	
	
	   if(($row["con_qty"] == $tp_quantity) || ($row["con_qty"] < $tp_quantity) && ($outs_qty <= 0))
       {  
	   
 $query_upd2 = "UPDATE post_consumable_detail_header SET status_posting = 'Close', date_close = NOW() WHERE mrin_no = '".$list["temp_mrin"]."' AND material_no = '".$row_tp["material_no"]."' ";
 $result_upd2 = mysql_query($query_upd2); 
	      
 $query_upd3 = "UPDATE consumable_request SET status = 'Close' WHERE temp_mrin = '".$list["temp_mrin"]."' AND material_no = '".$row_tp["material_no"]."' ";
 $result_upd3 = mysql_query($query_upd3); 
 
  $query_upd4 = "UPDATE consumable_request SET status = 'Close' WHERE temp_mrin = '".$list["temp_mrin"]."' AND (con_qty = '0.000' OR con_qty = '')";
 $result_upd4 = mysql_query($query_upd4); 
	
	 //--------------------------------------------------------------------
       //copy yg close MRIN masuk dalam MRIN history
	   //---------------------------------------------------------------------
        if($result_upd3 || $result_upd4)
		 {
		 
		   $query_upd4 = "SELECT * FROM consumable_request WHERE temp_mrin = '".$list["temp_mrin"]."' AND status = 'Close'";
		   $result_upd4 = mysql_query($query_upd4) or die(mysql_error());
		   $row_upd4 = mysql_num_rows($result_upd4); 
        // $r4 = mysql_num_rows($result_upd4);
		 

		 // if(mysql_affected_rows() == 0) { //If it ran ok
		 
		   
		   // }else{
		   if($row_upd4 > 0 )
		   {
			
			$query_mm3 = "SELECT * FROM consumable_request WHERE temp_mrin = '".$list["temp_mrin"]."' AND status = 'Close' AND material_no = '".$row_tp["material_no"]."'"; 
        	$result_mm3 = mysql_query($query_mm3) or die (mysql_error());
			$row_mm3 = mysql_fetch_array($result_mm3); 
			
			
			$query_mm3_insert =  "INSERT INTO consumable_request_close(id_req_con, mrin_doc, mrin_year, temp_mrin, id_con, id_scan, material_no, con_qty, con_uom, status_request, status_print, status_view, factory, user_create, date_create, user_update, date_update, date_posting, time_posting, status, date_require, time_require, reason_close, reason_close2, id_work) VALUES('".$row_mm3["id_req_con"]."','".$row_mm3["mrin_doc"]."','".$row_mm3["mrin_year"]."','".$row_mm3["temp_mrin"]."','".$row_mm3["id_con"]."','".$row_mm3["id_scan"]."','".$row_mm3["material_no"]."','".$row_mm3["con_qty"]."', '".$row_mm3["con_uom"]."','".$row_mm3["status_request"]."','".$row_mm3["status_print"]."', '".$row_mm3["status_view"]."','".$row_mm3["factory"]."','".$row_mm3["user_create"]."','".$row_mm3["date_create"]."','".$row_mm3["user_update"]."','".$row_mm3["date_update"]."','".$row_mm3["date_posting"]."','".$row_mm3["time_posting"]."','".$row_mm3["status"]."','".$row_mm3["date_require"]."','".$row_mm3["time_require"]."','6','','".$row_mm3["id_work"]."')";
$result_mm3_insert = mysql_query($query_mm3_insert) or die (mysql_error());
			
				  
		  
		    } // if $r4 == $r5
		  
		 
		   }// if($result_upd3)
	
	   }elseif(($row["con_qty"] > $tp_quantity))
       {
	
	    }
	
} // end while loop $row_tp
	   
	   
}// end while loop $list  
	   
   		  ?> 

          <tr>
            <td width="143">&nbsp;<?php echo $row2["temp_mrin"]; ?></td>
            <td width="50"><div align="center"><?php echo $row2["factory"]; ?></div></td>
            <td width="50"><?php echo $row2["id_work"]; ?></td>
            <td width="100">&nbsp;&nbsp;<?php echo $row2["R"]; ?>&nbsp;</td>
            <td width="100">&nbsp;&nbsp;<?php echo $row2["time_require"]; ?></td>
            <td width="134">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $data_u["user_fullname"]; ?></td>
            <td width="82"><div align="center"><?php echo $stat; ?></div></td>
            <td width="55"><a value="Details" href="detail_consumable_request.php?mrin_no=<?php echo $row2["temp_mrin"]; ?>&&TB_iframe=true&&height=400&&width=1000" class="thickbox" target="_self"><img src="../img/icon_view.jpg" width="16" height="16" alt="View">View</a></td>
                <td width="55"><a href="detail_consumable_request_printing.php?mrin_no=<?php echo $row2["temp_mrin"]; ?>&&TB_iframe=true&&height=400&&width=1000" class="thickbox" target="_self"><img src="../img/print.jpg" width="16" height="16" alt="Print">Print</a> </td>
           </tr>
           
  <?php 
		 
		 $no ++;
		  
		  $counter++; // menambah counter 
		
		    
		  } // end while loop ?></tbody></table> 

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
//mysql_close()
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