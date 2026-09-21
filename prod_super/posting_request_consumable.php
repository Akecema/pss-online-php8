<?php

/**
 * prod_super/posting_request_consumable.php
 * Part of: Production module (supervisor/admin tier)
 * Filename suggests: posting request consumable
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, factory_detail, consumable_request, consumable_request_close, consumable_request_cancel, post_consumable_detail_header.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_prod_super_menu.php, ]., td>
            <td width=, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
session_start();
$username = $_SESSION['username'] ?? '';
date_default_timezone_set("Asia/Kuala_Lumpur");

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

$url = "posting_request_consumable.php";

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
<?php include "left_prod_super_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_production_super.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Consumable Request</a> <a href="#" class="current">Display Consumable Request</a> </div>
  <h1>Consumable Request</h1>
</div>

<div class="container-fluid">
  <hr>
  <div class="row-fluid">
 
   
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmSearch" id="frmSearch">
            <table class="table table-bordered table-striped">
            <tr>
              <th>Posting Date From :</th>
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
									$myCalendar->setYearInterval(2010, 2030);
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
										$myCalendar->setDate(0,0,0);
										$myCalendar->setPath("/calendar/");
										$myCalendar->setYearInterval(2010, 2030);
										// $myCalendar->setOnChange("myChanged('test')");
										$myCalendar->writeScript();
				
									}
			 ?></td>
              <th>Posting Date To :</th>
              <td><?php
                
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
									$myCalendar->setYearInterval(2010, 2030);
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
								        $myCalendar->setDate(0,0,0);
										$myCalendar->setPath("/calendar/");
										$myCalendar->setYearInterval(2010, 2030);
										// $myCalendar->setOnChange("myChanged('test')");
										$myCalendar->writeScript();
				
									}
                
                ?></td>
            </tr>
            
            <tr>
              <th>Factory : </th>
              <td><select name="factory" id="factory" onChange="getFactory(this.value)">
                <option value="NULL" placeholder="Select Factory"> -- Select Factory --</option>
                <?php
	       $query3 = "SELECT * FROM factory_detail GROUP BY factory_desc2 ORDER BY id_fac ASC";
                   $result3 = mysqli_query($dbc, $query3);
  
                   while($row3=mysqli_fetch_array($result3, MYSQLI_NUM)) 
			      {
                  echo'<option value="',$row3[2],'">',stripslashes($row3[1]),'</option>';
                  }
				?>
              </select></td>
              <th>Work Center :</th>
              <td><div id="work_centerdiv"> 
               <select name="work_center" id="work_center" class="span11">
                <option value="NULL" placeholder="Select Work Center"> -- Select Work Center --</option>
                </select></div></td>
            </tr>
            <tr>
              <th>MRIN No :</th>
              <td><input name="temp_mrin" type="text" id="temp_mrin" size="25"class="span11"  /></td>
              <th>&nbsp;</th>
              <td>&nbsp;</td>
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
        if(isset($_POST["Submit2"]))
        {
            $temp_mrin = $_POST["temp_mrin"];
			$factory = $_POST["factory"];
			$dateF = $_POST["date1"];
            $dateT = $_POST["date2"];
			$work_center = $_POST["work_center"];
			 
            echo "<script>";
echo "window.location='posting_request_consumable2.php?temp_mrin=$temp_mrin&&date1=$dateF&&date2=$dateT&&work_center=$work_center&&factory=$factory'";
            //echo "window.location='posting_request_all2.php'";
            echo "</script>";
            exit(); //quit the script
        }
        
    	?>

  <?php

								 
   $query8 = "SELECT * FROM consumable_request AS MR WHERE MR.status_request = 'Y' AND  (MR.status != 'Close' AND MR.status  != 'Cancel') AND MR.status_print != 'Y' GROUP BY MR.temp_mrin";
   $result8 = mysqli_query($dbc, $query8) or trigger_error("SQL", E_USER_ERROR);
     //$num_8 = mysqli_fetch_row($result8);
   $num_rows = mysqli_num_rows($result8);
	 
   $pages = new Paginator;
   $pages->items_total = $num_rows;
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT *, DATE_FORMAT(MR.date_require,'%d-%m-%Y') AS R FROM consumable_request AS MR WHERE MR.status_request = 'Y' AND (MR.status != 'Close' AND MR.status  != 'Cancel') AND MR.status_print != 'Y' GROUP BY MR.temp_mrin ORDER BY MR.date_posting DESC, MR.temp_mrin ASC ";
$rs = mysqli_query($dbc, $query);   //run the query.


	
	 if($num_rows > 0) {	
	 
	 echo '<div align="center">There are currently  '. $num_rows.' record(s).</div>';
	
	

?>

       
      <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display Cosumable Request</h5>
          </div>
             
          <div class="widget-content nopadding">
          
            <table class="table table-bordered data-table">
             <thead>
             <tr>
                <th>MRIN No.</th>
                <th>Factory</th>
                <th>Request Date</th>
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
   
   while ($row2 = mysqli_fetch_array($rs))
   {
		
	$query_again = "SELECT * FROM consumable_request WHERE status_request = 'Y' and id_scan = '".db_esc($dbc, $row2[5])."'";
    $rs_again = mysqli_query($dbc, $query_again);   //run the query.
    $row = mysqli_fetch_array($rs_again);
	
	$query3 = "SELECT * FROM factory_detail WHERE id_fac = '".db_esc($dbc, $row2["factory"])."'";
    $result3 = mysqli_query($dbc, $query3);
	$row3 = mysqli_fetch_array($result3);
	
	$query_u = "SELECT * FROM user_detail WHERE user_no = '".db_esc($dbc, $row["user_create"])."'";
    $result_u = mysqli_query($dbc, $query_u);   //run the query.
    $data_u = mysqli_fetch_array($result_u);   //how many records are there?  
	
	//------------------------------
	// check yg mana dah ada dlm table history 
	//------------------------------
	
	    $query_check = "SELECT * FROM consumable_request_close WHERE temp_mrin = '".db_esc($dbc, $row2["temp_mrin"])."'";
		$result_check = mysqli_query($dbc, $query_check);		
		$rst_check = mysqli_fetch_array($result_check);
		
		if($rst_check > 0)
		
		{
		//skip for duplicate data
		$stat = '<font color="#FF0000"><b>Close</b></font>';
	
		}else{
	
	   $stat = '<font color="#009900"><b>Open</b></font>';
	
	       
	
	    $query_check2 = "SELECT * FROM consumable_request_cancel WHERE temp_mrin = '".db_esc($dbc, $row2["temp_mrin"])."'";
		$result_check2 = mysqli_query($dbc, $query_check2);		
		$rst_check2 = mysqli_fetch_array($result_check2);
	
	           if($rst_check2 > 0)
		 {
		   $stat = '<font color="#FFCC00"><b>Cancel</b></font>';
		  }
	   } 
	   
	   
	   
	  $sql2 = "SELECT *, DATE_FORMAT(MR.date_require,'%d-%m-%Y') AS R FROM consumable_request AS MR, consumable_detail AS SD WHERE MR.id_con = SD.id_con AND MR.status_request = 'Y' AND (MR.status != 'Close' AND MR.status != 'Cancel') GROUP BY MR.temp_mrin, SD.cost_center ORDER BY MR.date_require DESC,MR.time_require DESC";
$result2 = mysqli_query($dbc, $sql2) or trigger_error("SQL", E_USER_ERROR); 
	  
	  while ($list = mysqli_fetch_array($result2)) {
   
   //--------------------------------------------------------------------------------------------------------------
   //Update listing board   - MRIN disappear from listing if all component status_posting = "Close"         	        
   //--------------------------------------------------------------------------------------------------------------
	 $TOT = 0.000;
	 $outs_qty = 0;
	 
	$query_tp = "SELECT *,SUM(rquantity) as TOT FROM post_consumable_detail_header WHERE mrin_no = '".db_esc($dbc, $list["temp_mrin"])."' AND mvt_type = 201 AND status_posting = 'New' GROUP BY material_no";
	$result_tp  = mysqli_query($dbc, $query_tp); 
	//$row_tp = mysqli_fetch_assoc($result_tp); 

	$outs_qty = 0;

    while($row_tp = mysqli_fetch_assoc($result_tp))
{
  
		
	$tp_quantity = $row_tp["TOT"]; 
	
	$outs_qty = (($row["con_qty"]) - ($row_tp["TOT"]));
	
	
	   if(($row["con_qty"] == $tp_quantity) || ($row["con_qty"] < $tp_quantity) && ($outs_qty <= 0))
       {  
	   
 $query_upd2 = "UPDATE `post_consumable_detail_header` SET status_posting = 'Close', date_close = NOW() WHERE mrin_no = '".db_esc($dbc, $list["temp_mrin"])."' AND material_no = '".db_esc($dbc, $row_tp["material_no"])."' ";
 $result_upd2 = mysqli_query($dbc, $query_upd2); 
	      
 $query_upd3 = "UPDATE `consumable_request` SET status = 'Close' WHERE temp_mrin = '".db_esc($dbc, $list["temp_mrin"])."' AND material_no = '".db_esc($dbc, $row_tp["material_no"])."' ";
 $result_upd3 = mysqli_query($dbc, $query_upd3); 
 
  $query_upd4 = "UPDATE `consumable_request` SET status = 'Close' WHERE temp_mrin = '".db_esc($dbc, $list["temp_mrin"])."' AND (con_qty = '0.000' OR con_qty = '')";
 $result_upd4 = mysqli_query($dbc, $query_upd4); 
	
	 //--------------------------------------------------------------------
       //copy yg close MRIN masuk dalam MRIN history
	   //---------------------------------------------------------------------
        if($result_upd3 || $result_upd4)
		 {
		 
		   $query_upd4 = "SELECT * FROM `consumable_request` WHERE temp_mrin = '".db_esc($dbc, $list["temp_mrin"])."' AND status = 'Close'";
		   $result_upd4 = mysqli_query($dbc, $query_upd4) or trigger_error("SQL", E_USER_ERROR);
		   $row_upd4 = mysqli_num_rows($result_upd4); 
        // $r4 = mysqli_num_rows($result_upd4);
		 
		 // if(mysqli_affected_rows($dbc) == 0) { //If it ran ok
		 
		   
		   // }else{
		   if($row_upd4 > 0 )
		   {
			
			$query_mm3 = "SELECT * FROM `consumable_request` WHERE temp_mrin = '".db_esc($dbc, $list["temp_mrin"])."' AND status = 'Close' AND material_no = '".db_esc($dbc, $row_tp["material_no"])."'"; 
        	$result_mm3 = mysqli_query($dbc, $query_mm3) or die(db_fail($dbc));
			$row_mm3 = mysqli_fetch_array($result_mm3); 
			
			
			$query_mm3_insert =  "INSERT INTO consumable_request_close(id_req_con, mrin_doc, mrin_year, temp_mrin, id_con, id_scan, material_no, con_qty, con_uom, status_request, status_print, status_view, factory, user_create, date_create, user_update, date_update, date_posting, time_posting, status, date_require, time_require, reason_close, reason_close2, id_work) VALUES('".db_esc($dbc, $row_mm3["id_req_con"])."','".db_esc($dbc, $row_mm3["mrin_doc"])."','".db_esc($dbc, $row_mm3["mrin_year"])."','".db_esc($dbc, $row_mm3["temp_mrin"])."','".db_esc($dbc, $row_mm3["id_con"])."','".db_esc($dbc, $row_mm3["id_scan"])."','".db_esc($dbc, $row_mm3["material_no"])."','".db_esc($dbc, $row_mm3["con_qty"])."', '".db_esc($dbc, $row_mm3["con_uom"])."','".db_esc($dbc, $row_mm3["status_request"])."','".db_esc($dbc, $row_mm3["status_print"])."', '".db_esc($dbc, $row_mm3["status_view"])."','".db_esc($dbc, $row_mm3["factory"])."','".db_esc($dbc, $row_mm3["user_create"])."','".db_esc($dbc, $row_mm3["date_create"])."','".db_esc($dbc, $row_mm3["user_update"])."','".db_esc($dbc, $row_mm3["date_update"])."','".db_esc($dbc, $row_mm3["date_posting"])."','".db_esc($dbc, $row_mm3["time_posting"])."','".db_esc($dbc, $row_mm3["status"])."','".db_esc($dbc, $row_mm3["date_require"])."','".db_esc($dbc, $row_mm3["time_require"])."','6','','".db_esc($dbc, $row_mm3["id_work"])."')";
$result_mm3_insert = mysqli_query($dbc, $query_mm3_insert) or die(db_fail($dbc));
			
				  
		  
		    } // if $r4 == $r5
		  
		 
		   }// if($result_upd3)
	
	   }elseif(($row["con_qty"] > $tp_quantity))
       {
	
	    }
	
} // end while loop $row_tp
	   
	   
}// end while loop $list  

   		  ?> 
      
          <tr>
            <td width="141">&nbsp;<?php echo $row2["temp_mrin"]; ?></td>
            <td width="185"><div align="center"><?php echo $row2["factory"]; ?></div></td>
            <td width="125"><?php echo $row2["R"]; ?>&nbsp;</td>
            <td width="96"><?php echo $row2["time_require"]; ?></td>
            <td width="128"><?php echo $res["user_fullname"]; ?>&nbsp;</td>
            <td width="72">&nbsp;<?php echo $stat; ?></td>
            <td width="62"><a value="Details" href="detail_consumable_request.php?mrin_no=<?php echo $row2["temp_mrin"]; ?>&&TB_iframe=true&height=400&width=1000" class="thickbox" target="_self"><img src="../img/icon_view.jpg" width="16" height="16" alt="View">View</a></td>
                <td width="67"><a href="detail_consumable_request_printing.php?mrin_no=<?php echo $row2["temp_mrin"]; ?>&&TB_iframe=true&height=400&width=1000" class="thickbox" target="_self"><img src="../img/print.jpg" width="16" height="16" alt="Print">Print</a> </td>
           </tr>
           
  <?php 
		 
		 $no ++;
		  
		  $counter++; // menambah counter 
		
		    
		  } // end while loop ?>

  <?php
   mysqli_free_result($rs); 
   ?>
     </tbody></table>     
    <?php }   // free up the resources 
  else
{
?>
<table class="table">
  <tr> 
    <td><div align="center"><font color="#FF0000"><strong>There are currently 
          no material request.</strong></font></div></td>
  </tr>
</table>
        <?php
		   } 
mysqli_close($dbc)
?>
<p>Legend :</p>
<table width="70%" border="1">
  <tr>
    <td width="10%"><div align="center"><img src="../img/red_icon2.jpg" alt="" width="20" height="20" /></div></td>
    <td width="90%">MRIN Request has passed 20 minutes from the requested time.</td>
  </tr>
  <tr>
    <td><div align="center"><img src="../img/yellow_icon2.jpg" alt="" width="20" height="20" /></div></td>
    <td>MRIN Request has reached the requested time.</td>
  </tr>
  <tr>
    <td><div align="center"><img src="../img/green_icon2.jpg" alt="" width="20" height="20" /></div></td>
    <td>MRIN Request is now 20 minutes before the requested time.</td>
  </tr>
  <tr>
    <td><div align="center"><img src="../img/grey_icon.jpg" alt="" width="20" height="20" /></div></td>
    <td>New MRIN Request has been posted.</td>
  </tr>
</table>

</div>
       
      </div></div></div>
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