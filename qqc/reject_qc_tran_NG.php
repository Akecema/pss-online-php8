<?php

/**
 * qqc/reject_qc_tran_NG.php
 * Part of: QQC module (Quality)
 * Filename suggests: reject qc tran NG
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, factory_detail, reject_detail_disposal, qqc_transaction, mat_master_header, pps_detail_transaction, type_reject_detail, reason_ng_reject.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_qqc_menu.php, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
ini_set("error_reporting", E_ALL & ~E_DEPRECATED);
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
$url = "reject_qc_tran_NG.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
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
$sta4 = "SELECT * from request_status WHERE status_id = '4' ";
$sta_res4 = mysqli_query($dbc, $sta4);
$rst_sta4 = mysqli_fetch_array($sta_res4);	

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7' ";
$sta_res7 = mysqli_query($dbc, $sta7);
$rst_sta7 = mysqli_fetch_array($sta_res7);	

//CR status (Pending Approve)
$sta15 = "SELECT * from request_status WHERE status_id = '15' ";
$sta_res15 = mysqli_query($dbc, $sta15);
$rst_sta15 = mysqli_fetch_array($sta_res15);	
	
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
<SCRIPT LANGUAGE="JavaScript">
<!-- 

<!-- Begin
function Check(chk)
{
if(document.myform.Check_ctr.checked==true){
for (i = 0; i < chk.length; i++)
chk[i].checked = true ;
}else{

for (i = 0; i < chk.length; i++)
chk[i].checked = false ;
}
}

// End -->
</script>
<?php
//echo "the following values have been checked: ";
$checked="";
$amount ="";

$a = array();
if(isset($_POST["cancel"])) {
	foreach($_POST["cancel"] as $j=>$i) {
	    $amount .= $_POST["remark_reject"][$i]."|";
		$checked .= ($checked==""?"":",") . "checkbox" . $i;
		
		array_push($a, $i);
	//	 array_push($amount, $i);
	}
}
//echo $checked;
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
<?php include "left_qqc_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_qqc.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a><a href="#" class="#">QA/QC</a> <a href="#" class="current">Rework Reject</a></div>
  <h1>Rework Reject</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
     <!-- <div class="span12">-->
        <!--<div class="widget-box">
          <div class="widget-title">
             <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li><a role="tab" href="reject_backflush_tran_NG.php">Production Reject</a></li>
              <li class="active"><a role="tab" href="reject_qc_tran_NG.php">QC Reject</a></li>
             </ul>
          </div>
          </div>-->
          
          
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmSearch" id="frmSearch">
            <table class="table table-bordered table-striped">
            <tr>
              <th width="23%">Planned Start Date :</th>
              <td colspan="2"><?php
    
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
              
            </tr>
          
            <tr>
              <th>Factory : </th>
              <td colspan="2"><select name="factory" id="factory" onChange="getFactory(this.value)" class="span11">
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
              
            </tr>
              <tr>
              <th>Work Center :</th>
              <td colspan="2"><div id="work_centerdiv"> 
               <select name="work_center" id="work_center" class="span11">
                <option value="NULL" placeholder="Select Work Center"> -- Select Work Center --</option>
                </select></div></td>
             
            </tr>
            <tr>
              <th>&nbsp;</th>
              <th width="45%">&nbsp;</th>
              <th width="32%"><input name="Submit2" type="submit"  class="btn btn-info" id="button" value="SEARCH" /></th>
              </tr>
            </table>
        </form>
<?php
        if(isset($_POST["Submit2"]))
        {
            
            $dateF = $_POST["date1"];
           	$factory = $_POST["factory"];
			$work_center = $_POST["work_center"];
									
            echo "<script>";
			echo "window.location='reject_qc_tran_NG2.php?date1=$dateF&&factory=$factory&&work_center=$work_center'";
            echo "</script>";
            exit(); //quit the script
        }
        

   //-----------------------------------------------------------------
		
		if(isset($_POST["Submit3"])) 
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
 
  //-------------------generate disposal doc no.---------------
     $query_id_2 = "SELECT MAX(doc_dis) FROM reject_detail_disposal";
	 $result_id_2 = mysqli_query($dbc, $query_id_2);

 if ($result_id_2) {
	$nrows_2 = mysqli_num_rows($result_id_2);
    $row_id_2 = mysqli_fetch_row($result_id_2);

  $dht_2 = "000000";
  $dht_OK = "D";

  if($row_id_2[0] <= 0)
  { 
   
    $lastID_2 = ($row_id_2[0] + 1);
    $dg_2 = ($dht_2 + ($lastID_2));

   }else{
      $lastID_2 = ($row_id_2[0] + 1);
      $dg_2 =  $lastID_2;
    }



	 $number = $dg_2; // Length of the supplied number is 3
	 $number = sprintf('%06d', $number);
	 
	 $ref_no = $number;
     $ref = ($dht_OK.($number)); 
	
   
  } // end if $result_id
   

  if(isset($_POST["cancel"])) 
  {
 
    $cancel = $_POST["cancel"]; 
    $how_many = count($cancel); 
	$remark_reject = $_POST["remark_reject"]; 
	$string = "";
       
	   
	   foreach($_POST["cancel"] as $j=>$i) {
	    
		$amount .= $_POST["remark_reject"][$i];
	    $string = explode("|",($amount));	
			}
						
		   for ($i=0; $i<$how_many; $i++) { 
		   			
		//echo ($i+1).'-'.$cancel[$i]; echo $string[$i];
		//echo "</br>";
		
		//get data table pps_detail_transaction
	  $query_info = "SELECT * FROM qqc_transaction WHERE id_qqc = '".$cancel[$i]."'";
	  $result_info = mysqli_query($dbc, $query_info);
	  $data_info = mysqli_fetch_array($result_info);
	  
	   //get data table mat_master_header
	  $query_info2 = "SELECT * FROM mat_master_header WHERE material_no = '".$data_info["material_no"]."'";
	  $result_info2 = mysqli_query($dbc, $query_info2);
	  $data_info2 = mysqli_fetch_array($result_info2);
	  
	  //get data table pps_detail_transaction
	  $query_info3 = "SELECT * FROM pps_detail_transaction WHERE material_no = '".$data_info["material_no"]."'";
	  $result_info3 = mysqli_query($dbc, $query_info3);
	  $data_info3 = mysqli_fetch_array($result_info3);
	  
				
		//insert table reject_detail_disposal
		
		$query_insert2 = "INSERT INTO reject_detail_disposal (id_disposal, doc_dis, doc_disposal_no, bflush_qqc_no, plan_no, uid, material_no, material_desc, material_type, model_code, qty_plan, qty_actual, qty_balance, qty_NG, qty_qc, qty_qc_ok, qty_qc_NG, UOM_unit, comp_code, work_center, shift_day, date_plan, user_posting, date_posting, time_posting, status_disposal, ploc, ploc_prod_reject, ploc_qc_reject, type_reject, reason_reject, user_reject, date_reject, time_reject, qty_wastage, type_wastage, reason_wastage, user_wastage, date_wastage, time_wastage, user_disposal, date_disposal, remarks, approve_by, date_approve, remark_approve, status_part, user_update, date_update, approve_by2, date_approve2, remark_approve2, cost_center) VALUES('','".$data_info["qqc_no"]."','".$data_info["qqc_no"]."','".$data_info["qqc_no"]."','".$data_info["plan_no"]."','".$cancel[$i]."','".$data_info["material_no"]."', '".$data_info["material_desc"]."','".$data_info["material_type"]."','".$data_info3["model_code"]."','".$data_info["qty_plan"]."','".$data_info["qty_actual"]."','".$data_info["qty_balance"]."','".$data_info["qty_NG"]."','".$data_info["qty_qc"]."','".$data_info["qty_qc_ok"]."','".$data_info["qty_qc_NG"]."','".$data_info2["BUn"]."','".$data_info["comp_code"]."','".$data_info["work_center"]."','".$data_info["shift_day"]."','".$data_info["date_plan"]."','".$data_info["user_qc_posting"]."','".$data_info["date_qc_posting"]."','".$data_info["time_qc_posting"]."','".$rst_sta["status_desc"]."','".$data_info["ploc"]."','','".$data_info["ploc_qc"]."','".$data_info["type_qc_reject"]."','".$data_info["reason_qc_reject"]."','".$data_info["user_qc_reject"]."','".$data_info["date_qc_reject"]."','".$data_info["time_qc_reject"]."','','','','','','','".$username."',NOW(),'".$string[$i]."','','','','QC','','','','','','')";
$result_insert2 = mysqli_query($dbc, $query_insert2) or die (mysqli_error($dbc));
		
		
		
		//update table pps_detail_transaction
		
		//update table qqc_transaction 
		$query_update1 = "UPDATE qqc_transaction SET status = 'Y' WHERE id_qqc = '".$cancel[$i]."'";
        $result_update1 = mysqli_query($dbc, $query_update1) or die (mysqli_error($dbc));
				
		
		//update table pps_detail_transaction
		/*$query_update2 = "UPDATE pps_detail_transaction SET status = 'Y' WHERE id = '".$cancel[$i]."'";
        $result_update2 = mysqli_query($dbc, $query_update2) or die (mysqli_error($dbc));*/
		  
			}// end for loop
			
	
	       echo "<script>";
		   echo "alert('Disposal Document No : $data_info[qqc_no]');";
		   echo "window.location='reject_qc_tran_NG.php'";
	       echo "</script>"; 
		   exit(); //quit the script
	  
					
   

  }// end if
   else{
	   
	       echo "<script>";
		   echo "alert('Please tick the check box for proceed the transaction.');";
		   echo "window.location='reject_qc_tran_NG.php'";
		   echo "</script>"; 
		   exit(); //quit the script
	   
   }

}	
   //-----------------------------------------------------------------




 
								 
   $query8 = "SELECT COUNT(*) FROM qqc_transaction WHERE status = 'N' AND status_QC != '".$rst_sta4["status_desc"]."'";
   $result8 = mysqli_query($dbc, $query8) or die(mysqli_error($dbc));
   $num_rows = mysqli_fetch_row($result8);

   $pages = new Paginator;
   $pages->items_total = $num_rows[0];
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') as R, DATE_FORMAT(date_qc_posting,'%d-%m-%Y') as R2, DATE_FORMAT(date_create,'%d-%m-%Y') as R3 FROM qqc_transaction WHERE status = 'N' AND status_QC != '".$rst_sta4["status_desc"]."' ORDER BY plan_no ASC";
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
     <form name="myform" method="post" action="reject_qc_tran_NG.php">
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
                <th>Planned Order No.</th>
                <th width="77">Part No.</th>
                <th>Posting Date</th> 
                <th>Quantity</th> 
                <th>UOM</th>  
                <th>From Location</th>
                <th>Work Center</th> 
                <th>Type of Reject</th>
                <th>Reason</th>
                <th>Remarks</th>
                </tr>
              </thead>   
              <tbody>
           <?php
   
   $counter = 1;
   $no = 1;
   $sta_out = "";
   $k= 1;
   
   while ($row = mysqli_fetch_array($rs))
   {
		
	
	//quantity output
		if($row["qty_qc_ok"] != "0.000")
	{
		$qty_final = $row["qty_qc_ok"];
	}elseif($row["qty_qc_NG"] != "0.000")
	{
		$qty_final = $row["qty_qc_NG"];
	}else{
		$qty_final == " ";
	}
	
	
	$query_type = "SELECT * FROM type_reject_detail WHERE id_type = '".$row['type_qc_reject']."' ORDER BY id_type ASC";
    $result_type = mysqli_query($dbc, $query_type);
    $row_type = mysqli_fetch_array($result_type); 
	
	$query_reason = "SELECT * FROM reason_ng_reject WHERE id_reject = '".$row['reason_qc_reject']."' ORDER BY id_reject ASC";
    $result_reason = mysqli_query($dbc, $query_reason);
    $row_reason = mysqli_fetch_array($result_reason);
	
	$query_scan = "SELECT * FROM mat_master_header WHERE material_no = '".$row['material_no']."'";
    $result_scan = mysqli_query($dbc, $query_scan);
    $row_scan = mysqli_fetch_array($result_scan);
	
	  $query_model = "SELECT * FROM pps_detail WHERE plan_no = '".$row['plan_no']."'";
	  $result_model = mysqli_query($dbc, $query_model);
	  $data_model = mysqli_fetch_array($result_model);
	 
      ?>
           
                <tr class="gradeX">
                <td width="30"><div align="center"><input type="checkbox" name="cancel[]" value="<?php echo $row["id_qqc"]; ?>" <?=was_checked($row["id_qqc"],$a) ?> /><input type="hidden" name="Check_ctr" value="yes" 
onClick="Check(document.myform.cancel)">  </div><?php echo $no; ?></td>
                <td width="80"><?php echo $data_model["model_code"]; ?></td>
                <td width="48"><font color="#0000CC"><?php echo $row["plan_no"]; ?></font></td>
                <td><?php echo $row[6]; ?></td>
                <td width="80"><?php echo $row["R2"]; ?></td>  
                <td width="60"><?php echo $qty_final; ?></td>
                <td width="60"><?php echo $row_scan["BUn"]; ?></td> 
                <td width="60"><?php echo $row["ploc_qc"]; ?></td>
                <td width="60"><?php echo $row["work_center"]; ?></td> 
                <td width="80"><?php echo $row_type['type_desc']; ?></td>
                <td width="80"><?php echo $row_reason['reject_desc']; ?></td>
                <td width="140"><textarea name="remark_reject[<?php echo $row["id_qqc"]; ?>]" id="textarea" rows="2" cols="10" maxlength="250" ><?php if (isset($_POST['remark_reject'][($row["id_qqc"])])) { echo $_POST['remark_reject'][($row["id_qqc"])]; } ?></textarea>
               <input name="id_qqc[<?php echo $k; ?>]" type="hidden" value="<?php echo $row["id_qqc"]; ?>">
          </td>
                </tr>
                
          <?php 
		  
		  $no ++;
		  $counter++; // menambah counter
		  $k ++;
		  } 
		  ?>
                
              
              </tbody>
            </table>
            <table class="table">
  <tr>
    <td>&nbsp;  <input name="Submit3" type="submit"  class="btn btn-success" id="button" value="Generate Disposal Document" onClick="return confirm('Confirm to generate disposal request?');"/></td>
  </tr>
</table>
          </div>
        
      </div></form>
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
