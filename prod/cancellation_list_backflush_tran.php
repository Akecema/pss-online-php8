<?php
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");

$Cdate = date ("l, j F Y ");
set_time_limit(0);

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$url = "cancellation_list_backflush_tran.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
    $result2 = mysql_query($query2) or die (mysql_error());
    $res = mysql_fetch_array($result2);
//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysql_query($query_setup);   //run the query.
$num_setup = mysql_num_rows($rs_setup);   //how many material are there?
$data_setup = mysql_fetch_array($rs_setup);
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
$sta_res = mysql_query($sta);
$rst_sta = mysql_fetch_array($sta_res);

//CR status (Released)
$sta2 = "SELECT * from request_status WHERE status_id = '2' ";
$sta_res2 = mysql_query($sta2);
$rst_sta2 = mysql_fetch_array($sta_res2);	

//CR status (Cancel)
$sta4 = "SELECT * from request_status WHERE status_id = '4'";
$sta_res4 = mysql_query($sta4);
$rst_sta4 = mysql_fetch_array($sta_res4);	

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7' ";
$sta_res7 = mysql_query($sta7);
$rst_sta7 = mysql_fetch_array($sta_res7);	
	
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
<?php include "left_production_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_production.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a><a href="#" class="#">Production</a> <a href="#" class="current">Cancellation</a></div>
  <h1>Backflush Cancellation</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
    <!--  <div class="span12">-->
        <div class="widget-box">
          <div class="widget-title">
             <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li class="active"><a  role="tab" href="cancellation_list_backflush_tran.php">New Request</a></li>
              <li><a role="tab" href="display_cancel_list_backflush_tran.php">Display Request</a></li>
             </ul>
          </div>
          </div>
          
          
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmSearch" id="frmSearch">
            <table class="table table-bordered table-striped">
            <tr>
              <th>Planned Start Date :</th>
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
									$myCalendar->setYearInterval(date('Y') - 1, date('Y') + 10);
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
										$myCalendar->setYearInterval(date('Y') - 1, date('Y') + 10);
										// $myCalendar->setOnChange("myChanged('test')");
										$myCalendar->writeScript();
				
									}
			 ?></td>
              <th>Planned End Date :</th>
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
									$myCalendar->setYearInterval(date('Y') - 1, date('Y') + 10);
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
										$myCalendar->setYearInterval(date('Y') - 1, date('Y') + 10);
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
                   $result3 = mysql_query($query3);
  
                   while($row3=mysql_fetch_array($result3)) 
			      {
                  echo'<option value="',$row3['factory_desc2'],'">',stripslashes($row3['factory_desc']),'</option>';
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
              <th>Planned Order No. :</th>
              <td><select name="plan_no" id="plan_no" class="span11">
                  <option value="NULL" placeholder="Select Planned Order No."> -- Select Planned Order No. --</option>
                  <?php
	             $query9 = "SELECT * FROM pps_detail WHERE status_pps = '".$rst_sta7["status_desc"]."' ORDER BY plan_no ASC";
                   $result9 = mysql_query($query9);
  
                   while($row9=mysql_fetch_array($result9)) 
			      {
				   ?>
                  <option value="<?php echo $row9["plan_no"]; ?>"> <?php echo $row9["plan_no"]; ?></option>
                  <?php
                  }
				?>
              </select></td>
              <th>Shift :</th>
              <td><select name="shift_ops" id="shift_ops" class="span11">
                  <option value="NULL" placeholder="Select Shift"> -- Select Shift --</option>
                  <option value="D/S">D/S</option>
                  <option value="N/S">N/S</option>
                  </select></td>
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
            
            $dateF = $_POST["date1"];
            $dateT = $_POST["date2"];
			$factory = $_POST["factory"];
			$work_center = $_POST["work_center"];
			$plan_no = $_POST["plan_no"]; 
			$shift_ops = $_POST["shift_ops"]; 
						
            echo "<script>";
echo "window.location='cancellation_list_backflush_tran2.php?date1=$dateF&&date2=$dateT&&factory=$factory&&work_center=$work_center&&plan_no=$plan_no&&shift_ops=$shift_ops'";
            echo "</script>";
            exit(); //quit the script
        }
        
    	?>

          
           <?php

     //------range date for 30 days----------------------------
 $start_date_check = date('Y-m-01', strtotime("0 month"));
 $end_date_check = date('Y-m-31', strtotime("0 month"));
 
 // echo $start_date_check; echo "<br>";
 // echo $end_date_check;

								 
   $query8 = "SELECT COUNT(*) FROM pps_detail_transaction WHERE status_pps = '".$rst_sta7["status_desc"]."' AND (date_plan >= '$start_date_check' AND date_plan <= '$end_date_check')";
   $result8 = mysql_query($query8) or die(mysql_error());
   $num_rows = mysql_fetch_row($result8);

   $pages = new Paginator;
   $pages->items_total = $num_rows[0];
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') as R, DATE_FORMAT(date_posting,'%d-%m-%Y') as R2, DATE_FORMAT(date_create,'%d-%m-%Y') as R3 FROM pps_detail_transaction WHERE status_pps = '".$rst_sta7["status_desc"]."' AND (date_plan >= '$start_date_check' AND date_plan <= '$end_date_check') ORDER BY plan_no ASC";
$rs = mysql_query($query);   //run the query.
$num = mysql_num_rows($rs);   //how many material are there?

	
	 if ($num > 0) {
	 
	 echo '<div align="center">There are currently  '. $num_rows[0].' record(s).</div>';
	 }
	

?>
     
          
      <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>New Request</h5>
          </div>
             
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                <th>No.</th>
                <th width="77">Part No.</th>
                <th>Planned Order No.</th>
                <th>Planned Date</th>
                <th>Work Center</th>
                <th>Shift</th>
                <th>To Location</th>
                <th>Document No.</th>
                <th>Output Status</th>
                <th>Output Qty.</th>
                <th>Action</th>
                </tr>
              </thead>   
              <tbody>
           <?php
   
   $counter = 1;
   $no = 1;
   $sta_out = "";
   
   while ($row = mysql_fetch_array($rs))
   {
	   
	//-----checking QA/QC entering output production ----not allow cancellation-------
	   $query_qqc = "SELECT * FROM qqc_detail_transaction WHERE bflush_no = '".$row["bflush_no"]."' AND status_QC != '".$rst_sta4["status_desc"]."'";
	   $result_qqc = mysql_query($query_qqc);
	   $data_qqc = mysql_fetch_array($result_qqc);
	   
	   
	   	//-----checking Release Production PPS entering output
	   $query_prod_out = "SELECT * FROM pps_detail WHERE plan_no = '".$row["plan_no"]."' AND status_pps = '".$rst_sta2["status_desc"]."'";
	   $result_prod_out = mysql_query($query_prod_out);
	   $data_prod_out = mysql_fetch_array($result_prod_out);
		
	//shift	
		if($row["shift_pps1"] != "")
	{
		$sta = "D/S";
		
	}elseif($row["shift_pps2"] != "")
	 {
		$sta = "N/S";
	 }else{
		 
		$sta = " ";
	 }	
	
	//quantity output
		if($row["qty_actual"] != "0.000")
	{
		$qty_final = $row["qty_actual"];
	}elseif($row["qty_NG"] != "0.000")
	{
		$qty_final = $row["qty_NG"];
	}else{
		$qty_final == " ";
	}
	
	//status output
	//----check string -----------
	
	$sta_out = substr($row["bflush_no"],2,3);
	
	
	if($sta_out == "211")
	{
		$status_output = "OK";
	}elseif($sta_out == "221")
	{
	    $status_output = "NG";
	}else{
	     $status_output = "";
	}
	 
      ?>
           
                <tr class="gradeX">
                <td width="30"><?php echo $no; ?></td>
                <td><?php echo $row["material_no"]; ?></td>
                <td width="80"><font color="#0000CC"><?php echo $row["plan_no"]; ?></font></td>
                <td width="80"><?php echo $row["R"]; ?></td> 
                <td width="60"><?php echo $row["work_center"]; ?></td>
                <td width="40"><?php echo $sta; ?></td>
                <td width="50"><?php echo $row["ploc"]; ?></td>
                <td width="90"><font color="#0000CC"><?php echo $row["bflush_no"]; ?></font></td>
                <td width="50"><font color="#FF9900"><?php echo $status_output; ?></font></td>
                <td width="60"><?php echo intval($qty_final); ?></td>
                <td width="99"><?php
				
				if(($data_qqc["bflush_no"]) == ($row["bflush_no"]))
				{
					
				echo "QC Transaction";	
					
				}else{
				
				if($sta_out == "211")
				{	
					
				?>
                  <a value="Cancellation" href="cancel_backflush_tran_proc.php?buid=<?php echo $row["id"]; ?>&&TB_iframe=true&height=400&width=1000" class="thickbox" target="_self"><img src="../img/delete.png" width="16" height="16" alt="Cancellation">Cancellation</a><?php
				   }elseif($sta_out == "221")
				   {
					   ?>
				     <a value="Cancellation" href="cancel_backflush_NG_tran_proc.php?buid=<?php echo $row["id"]; ?>&&TB_iframe=true&height=400&width=1000" class="thickbox" target="_self"><img src="../img/delete.png" width="16" height="16" alt="Cancellation">Cancellation</a>
				   
				<?php   }else{
					   
					   
				   }
				}
				    ?></td>
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
