<?php

/**
 * qqc/rework_output_list_tran.php
 * Part of: QQC module (Quality)
 * Filename suggests: rework output list tran
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, factory_detail, pps_detail, model_detail, qqc_detail_transaction, pps_detail_transaction, qqc_transaction.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_qqc_menu.php, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
require_once '../include/auth.php';
require_role($dbc, 10);
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");

$Cdate = date ("l, j F Y ");
set_time_limit(0);

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$url = "rework_output_list_tran.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
    $result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
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
$sta4 = "SELECT * from request_status WHERE status_id = '4'";
$sta_res4 = mysqli_query($dbc, $sta4);
$rst_sta4 = mysqli_fetch_array($sta_res4);		

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7' ";
$sta_res7 = mysqli_query($dbc, $sta7);
$rst_sta7 = mysqli_fetch_array($sta_res7);	

//CR status (Pending)
$sta8 = "SELECT * from request_status WHERE status_id = '8' ";
$sta_res8 = mysqli_query($dbc, $sta8);
$rst_sta8 = mysqli_fetch_array($sta_res8);	

//CR status (Completed)
$sta14 = "SELECT * from request_status WHERE status_id = '14' ";
$sta_res14 = mysqli_query($dbc, $sta14);
$rst_sta14 = mysqli_fetch_array($sta_res14);

//CR status (Transfer QC)
$sta18 = "SELECT * from request_status WHERE status_id = '18'";
$sta_res18 = mysqli_query($dbc, $sta18);
$rst_sta18 = mysqli_fetch_array($sta_res18);	

//CR status (Approved QC)
$sta17 = "SELECT * from request_status WHERE status_id = '17'";
$sta_res17 = mysqli_query($dbc, $sta17);
$rst_sta17 = mysqli_fetch_array($sta_res17);	
	
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
<?php include "left_qqc_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_qqc.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a><a href="#" class="#">QA/QC</a> <a href="#" class="current">Rework List</a></div>
  <h1>Rework List</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
    <!--  <div class="span12">-->
        <!--<div class="widget-box">
          <div class="widget-title">
             <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li><a  role="tab" href="upload_pps_month.php">New Request</a></li>
              <li class="active"><a role="tab" href="display_pps_month_reprint.php">Searching Record</a></li>
             </ul>
          </div>
          </div>-->
          
          
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
										$myCalendar->setDate(date('01'), date('m'), date('Y'));
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
								        $myCalendar->setDate(date('t'), date('m'), date('Y'));
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
              <th>Planned Order No. :</th>
              <td><select name="plan_no" id="plan_no" class="span11">
                  <option value="NULL" placeholder="Select Planned Order No."> -- Select Planned Order No. --</option>
                  <?php
	             $query9 = "SELECT * FROM pps_detail WHERE status_pps = '".db_esc($dbc, $rst_sta7["status_desc"])."' ORDER BY plan_no ASC";
                   $result9 = mysqli_query($dbc, $query9);
  
                   while($row9=mysqli_fetch_array($result9)) 
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
              <th>Model :</th>
              <td><select name="model_code" id="model_code" class="span11">
                  <option value="NULL" placeholder="Select Model"> -- Select Model --</option>
                  <?php
	               $query7 = "SELECT * FROM model_detail ORDER BY code_model ASC";
                   $result7 = mysqli_query($dbc, $query7);
  
                   while($row7=mysqli_fetch_array($result7)) 
			      {
				   ?>
                  <option value="<?php echo $row7["model_name"]; ?>"> <?php echo $row7["model_name"]; ?></option>
                  
                  <?php
                  }
				?>
              </select></td>
              <th>&nbsp;</th>
              <th></th>
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
			$model_code = $_POST["model_code"]; 
						
            echo "<script>";
echo "window.location='rework_output_list_tran2.php?date1=$dateF&&date2=$dateT&&factory=$factory&&work_center=$work_center&&plan_no=$plan_no&&shift_ops=$shift_ops&&model_code=$model_code'";
            echo "</script>";
            exit(); //quit the script
        }
        
 //------range date for 30 days----------------------------
 $start_date_check = date('Y-m-01', strtotime("0 month"));
 $end_date_check = date('Y-m-31', strtotime("0 month"));
 
 // echo $start_date_check; echo "<br>";
 // echo $end_date_check;

								 
   $query8 = "SELECT COUNT(*) FROM qqc_detail_transaction AS MR, pps_detail_transaction AS PD WHERE MR.bflush_no = PD.bflush_no AND MR.status_QC = '".db_esc($dbc, $rst_sta8["status_desc"])."' AND PD.status_pps != '".db_esc($dbc, $rst_sta14["status_desc"])."' AND MR.status_QC != '".db_esc($dbc, $rst_sta4["status_desc"])."' AND MR.qty_balance != '0' AND (MR.date_plan >= '$start_date_check' AND MR.date_plan <= '$end_date_check')";
   $result8 = mysqli_query($dbc, $query8) or die(mysqli_error($dbc));
   $num_rows = mysqli_fetch_row($result8);

   $pages = new Paginator;
   $pages->items_total = $num_rows[0];
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT *, DATE_FORMAT(MR.date_plan,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_qc_posting,'%d-%m-%Y') as R2, DATE_FORMAT(MR.date_create,'%d-%m-%Y') as R3 FROM qqc_detail_transaction AS MR, pps_detail_transaction AS PD WHERE MR.bflush_no = PD.bflush_no AND MR.status_QC = '".db_esc($dbc, $rst_sta8["status_desc"])."' AND MR.status_QC != '".db_esc($dbc, $rst_sta4["status_desc"])."' AND PD.status_pps != '".db_esc($dbc, $rst_sta14["status_desc"])."' AND MR.qty_balance != '0' AND (MR.date_plan >= '$start_date_check' AND MR.date_plan <= '$end_date_check') ORDER BY MR.plan_no ASC";
$rs = mysqli_query($dbc, $query);   //run the query.
$num = mysqli_num_rows($rs);   //how many material are there?


	
	 if ($num > 0) {
	 
	 echo '<div align="center">There are currently  '. $num_rows[0].' record(s).</div>';
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
                <th>Model</th>  
                <th width="77">Part No.</th>
                <th>Planned Order No.</th>
                <th>Planned Date</th>
                <th>Work Center</th>
                <th>Shift</th>
                <th>Location</th>
                <th>Pending Qty</th>
                <th>Pending Balance</th>
                <th>QC OK</th>
                <th>QC Reject</th>
                <th>QC Status</th>
                <th>Action</th>
               <!-- <th>Cancellation</th>-->
                </tr>
              </thead>   
              <tbody>
           <?php
   
   $counter = 1;
   $no = 1;
   $sta_out = "";
   $qty_total_rec = 0.000;
   $total_qty_pending = 0.000;
   $qty_total_ok = 0.000;
   $qty_total_NG = 0.000;
   $total_qty = 0.000;
   
   while ($row = mysqli_fetch_array($rs))
   {
		
	
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
	
	
   $qty_total_rec = 0.000;
   $total_qty_pending = 0.000;
   $qty_total_ok = 0.000;
   $qty_total_NG = 0.000;
   $total_qty = 0.000;	
   $qty_total_ok2 = 0.000;
   $qty_rev_ok2 = 0.000;
   $qty_total_NG2 = 0.000;
   
    //-----checking pps_trans entering output production
	/*   $query_plan_tran = "SELECT * FROM pps_detail_transaction WHERE plan_no = '".$row["plan_no"]."'";
	   $result_plan_tran = mysqli_query($dbc, $query_plan_tran);
       
	   while($data_plan_tran = mysqli_fetch_array($result_plan_tran))
    {*/

  	 //-----checking QA/QC entering output production
	   $query_qqc = "SELECT * FROM qqc_detail_transaction WHERE bflush_no = '".db_esc($dbc, $row["bflush_no"])."' AND plan_no = '".db_esc($dbc, $row["plan_no"])."'";
	   $result_qqc = mysqli_query($dbc, $query_qqc);
	   $data_qqc = mysqli_fetch_array($result_qqc);  
	   
	   $qty_total_ok2 = ($qty_total_ok2 + $row["qty_actual"]);  
	   $qty_rev_ok2 = ($qty_rev_ok2 + $data_qqc["qty_qc"]);  
	   $qty_total_NG2 = ($qty_total_NG2 + $row["qty_NG"]);  
	   
	     
	   
	 
	   $query_qqc2 = "SELECT * FROM qqc_transaction WHERE bflush_no = '".db_esc($dbc, $data_qqc["bflush_no"])."' AND plan_no = '".db_esc($dbc, $data_qqc["plan_no"])."' AND status_QC != '".db_esc($dbc, $rst_sta4["status_desc"])."'";
	   $result_qqc2 = mysqli_query($dbc, $query_qqc2);
	
	  
	   while($data_qqc2 = mysqli_fetch_array($result_qqc2))
	   {
		 	  
		$qty_total_rec = ($qty_total_rec + $data_qqc2["qty_qc"]);  
		$qty_total_ok = ($qty_total_ok + $data_qqc2["qty_qc_ok"]);  
	    $qty_total_NG = ($qty_total_NG + $data_qqc2["qty_qc_NG"]);  
		
	  //------quantity qc pending checking OK ------------
		  
	$total_qty = ($data_qqc["qty_balance"]);   
	$total_qty_pending = ($total_qty - (($qty_total_ok) + ($qty_total_NG)));
   

    }	

	
	//}	
	
	
	
	
	
	
	   //--------- qc detail (calculate total qty_qc receive from production) ------------
	 
	  /* $query_qqc = "SELECT * FROM qqc_transaction WHERE bflush_no = '".$row["bflush_no"]."' AND plan_no = '".$row["plan_no"]."'";
	   $result_qqc = mysqli_query($dbc, $query_qqc);
	   
	   while($data_qqc = mysqli_fetch_array($result_qqc))
	   {
		   
		$qty_total_rec = ($qty_total_rec + $data_qqc["qty_qc"]);  
		$qty_total_ok = ($qty_total_ok + $data_qqc["qty_qc_ok"]);  
	    $qty_total_NG = ($qty_total_NG + $data_qqc["qty_qc_NG"]);  
		   
		
	   }
	 
	  //------quantity qc pending checking OK ------------
	  
	//  echo $row["qty_balance"];
	  
	   $total_qty_pending = ($row["qty_balance"] - (($qty_total_ok) + ($qty_total_NG)));
	  */
	 
      ?>
           
                <tr class="gradeX">
                <td width="30"><?php echo $no; ?></td>
                <td><?php echo $row["model_code"]; ?></td>
                <td><?php echo $row["material_no"]; ?></td>
                <td width="80"><font color="#0000CC"><?php echo $row[4]; ?></font></td>
                <td width="80"><?php echo $row["R"]; ?></td> 
                <td width="60"><?php echo $row["work_center"]; ?></td>
                <td width="40"><?php echo $row["shift_day"];?></td>
                <td width="50"><?php echo $row["ploc"]; ?></td>
                <td width="90"><font color="#0000CC"><?php echo number_format($data_qqc["qty_balance"]); ?></font></td>
                <td width="80"><?php if ($total_qty_pending < 0){ echo "0"; } else{
					echo number_format($total_qty_pending); } ?></td>
                <td width="50"><?php echo number_format($qty_total_ok); ?></td>
                <td width="60"><?php echo number_format($qty_total_NG); ?></td>
                <td width="99"><?php echo $row["status_QC"]; ?></td>
                <td width="99">
                <a value="Rework" href="qc_rework_output_list.php?uid=<?php echo $row["id_qqc"]; ?>" ><img src="../img/page_white_magnify.png" width="16" height="16" alt="Rework">Rework</a>
                </td>
               <!-- <td>
                <?php if($total_qty_pending > 0) {    ?>
                <a value="Cancellation" href="cancel_qc_rework_output.php?uid=<?php echo $row["id_qqc"]; ?>&&TB_iframe=true&height=400&width=1000" class="thickbox" target="_self"><img src="../img/delete.png" width="16" height="16" alt="Cancellation">Cancellation</a>
                 <?php }elseif($total_qty_pending < 0) {    ?>
                <a value="Cancellation" href="cancel_qc_rework_output.php?uid=<?php echo $row["id_qqc"]; ?>&&TB_iframe=true&height=400&width=1000" class="thickbox" target="_self"><img src="../img/delete.png" width="16" height="16" alt="Cancellation">Cancellation</a>
                 <?php  }else{  ?>  <a value="Cancellation" href="cancel_qc_product_output.php?uid=<?php echo $row["id_qqc"]; ?>&&TB_iframe=true&height=400&width=1000" class="thickbox" target="_self"><img src="../img/delete.png" width="16" height="16" alt="Cancellation">Cancellation Product Output</a> <?php } ?>
                </td>-->
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
