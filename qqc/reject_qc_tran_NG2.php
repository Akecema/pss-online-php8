<?php
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
$sta4 = "SELECT * from request_status WHERE status_id = '4' ";
$sta_res4 = mysql_query($sta4);
$rst_sta4 = mysql_fetch_array($sta_res4);	

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7' ";
$sta_res7 = mysql_query($sta7);
$rst_sta7 = mysql_fetch_array($sta_res7);	

//CR status (Pending Approve)
$sta15 = "SELECT * from request_status WHERE status_id = '15' ";
$sta_res15 = mysql_query($sta15);
$rst_sta15 = mysql_fetch_array($sta_res15);	
	
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
      <!--  <div class="widget-box">
          <div class="widget-title">
             <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li><a role="tab" href="reject_backflush_tran_NG.php">Production Reject</a></li>
              <li class="active"><a role="tab" href="reject_qc_tran_NG.php">QC Reject</a></li>
             </ul>
          </div>
          </div>-->
          
          
       <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="frmSearch" id="frmSearch">
            <table class="table table-bordered table-striped">
            <tr>
              <th width="23%">Planned Start Date :</th>
              <td colspan="2"><?php
    
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
              
            </tr>
          
            <tr>
              <th>Factory : </th>
              <td colspan="2"><select name="factory" id="factory" onChange="getFactory(this.value)" class="span11">
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
              <th>Work Center :</th>
              <td colspan="2"><div id="work_centerdiv"> 
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
              <th>&nbsp;</th>
              <th width="45%">&nbsp;</th>
              <th width="32%"><input name="Submit2" type="submit"  class="btn btn-info" id="button" value="SEARCH" /></th>
              </tr>
            </table>
        </form>
<?php
       
		
		if(isset($_POST["Submit3"])) 
{ // handle the form.

// create a function for escaping the data.
function escape_data ($data) {
global $dbc;   // need the connection.
if(ini_get('magic_quotes_gpc')) {
    $data = stripslashes($data);
	}
	return mysql_real_escape_string($data,$dbc);
	}   // end function.
$message = NULL; // create an empty new variable.
 

   //-------------------generate disposal doc no.---------------
     $query_id_2 = "SELECT MAX(doc_dis) FROM reject_detail_disposal";
	 $result_id_2 = mysql_query($query_id_2);

 if ($result_id_2) {
	$nrows_2 = mysql_num_rows($result_id_2);
    $row_id_2 = mysql_fetch_row($result_id_2);

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
	  $result_info = mysql_query($query_info);
	  $data_info = mysql_fetch_array($result_info);
	  
	   //get data table mat_master_header
	  $query_info2 = "SELECT * FROM mat_master_header WHERE material_no = '".$data_info["material_no"]."'";
	  $result_info2 = mysql_query($query_info2);
	  $data_info2 = mysql_fetch_array($result_info2);
				
	 //get data table pps_detail_transaction
	  $query_info3 = "SELECT * FROM pps_detail_transaction WHERE material_no = '".$data_info["material_no"]."'";
	  $result_info3 = mysql_query($query_info3);
	  $data_info3 = mysql_fetch_array($result_info3);
	  
				
		//insert table reject_detail_disposal
		
		$query_insert2 = "INSERT INTO reject_detail_disposal (id_disposal, doc_dis, doc_disposal_no, bflush_qqc_no, plan_no, uid, material_no, material_desc, material_type, model_code, qty_plan, qty_actual, qty_balance, qty_NG, qty_qc, qty_qc_ok, qty_qc_NG, UOM_unit, comp_code, work_center, shift_day, date_plan, user_posting, date_posting, time_posting, status_disposal, ploc, ploc_prod_reject, ploc_qc_reject, type_reject, reason_reject, user_reject, date_reject, time_reject, qty_wastage, type_wastage, reason_wastage, user_wastage, date_wastage, time_wastage, user_disposal, date_disposal, remarks, approve_by, date_approve, remark_approve,  status_part, user_update, date_update, approve_by2, date_approve2, remark_approve2, cost_center) VALUES('','".$data_info["qqc_no"]."','".$data_info["qqc_no"]."','".$data_info["qqc_no"]."','".$data_info["plan_no"]."','".$cancel[$i]."','".$data_info["material_no"]."', '".$data_info["material_desc"]."','".$data_info["material_type"]."','".$data_info3["model_code"]."','".$data_info["qty_plan"]."','".$data_info["qty_actual"]."','".$data_info["qty_balance"]."','".$data_info["qty_NG"]."','".$data_info["qty_qc"]."','".$data_info["qty_qc_ok"]."','".$data_info["qty_qc_NG"]."','".$data_info2["BUn"]."','".$data_info["comp_code"]."','".$data_info["work_center"]."','".$data_info["shift_day"]."','".$data_info["date_plan"]."','".$data_info["user_qc_posting"]."','".$data_info["date_qc_posting"]."','".$data_info["time_qc_posting"]."','".$rst_sta["status_desc"]."','".$data_info["ploc"]."','','".$data_info["ploc_qc"]."','".$data_info["type_qc_reject"]."','".$data_info["reason_qc_reject"]."','".$data_info["user_qc_reject"]."','".$data_info["date_qc_reject"]."','".$data_info["time_qc_reject"]."','','','','','','','".$username."',NOW(),'".$string[$i]."','','','','QC','','','','','','')";
$result_insert2 = mysql_query($query_insert2) or die (mysql_error());
		
		
		//update table pps_detail_transaction
		
		//update table qqc_transaction 
		$query_update1 = "UPDATE qqc_transaction SET status = 'Y' WHERE id_qqc = '".$cancel[$i]."'";
        $result_update1 = mysql_query($query_update1) or die (mysql_error());
				
		
		//update table pps_detail_transaction
		/*$query_update2 = "UPDATE pps_detail_transaction SET status = 'Y' WHERE id = '".$cancel[$i]."'";
        $result_update2 = mysql_query($query_update2) or die (mysql_error());*/
		  
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

            $dateF = $_GET["date1"];
           	$factory = $_GET["factory"];
			$work_center = $_GET["work_center"];
			
			 //convert 
			
			$query_convert = "SELECT * FROM `work_center_detail` as SR WHERE SR.id_work = '".$_GET["work_center"]."'";
			$result_convert = mysql_query($query_convert); 
			$row_convert = mysql_fetch_array($result_convert);
			
		  
		
			//-------Count all results------------------------//
			
				 $where_sql = '';
		 
		 // 1. DateF
                if($dateF == "0000-00-00") {
                     $wheresql_01 = ""; }
                else {
                      $wheresql_01 = " AND (MR.date_plan >= '$dateF')"; }
							 
		 //2. Factory 
                if ($factory == "NULL"){ 
                    $wheresql_02 = ""; }
                else {
                    $wheresql_02 = " AND SR.id_factory = '".$row_convert["id_factory"]."'"; } 
					
          //3. Work Center
                if ($work_center == "NULL" ){
                    $wheresql_03 = ""; }
                else {
					$wheresql_03 = " AND MR.work_center = '$work_center'"; }
   
				
				$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03;	
	
	//********** END CONDITION **************

								 
   $query8 = "SELECT COUNT(*) FROM qqc_transaction AS MR, work_center_detail AS SR WHERE SR.id_work = MR.work_center AND MR.status = 'N' AND MR.status_QC != '".$rst_sta4["status_desc"]."' ".$where_sql;
   $result8 = mysql_query($query8) or die(mysql_error());
   $num_rows = mysql_fetch_row($result8);

   $pages = new Paginator;
   $pages->items_total = $num_rows[0];
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT *, DATE_FORMAT(MR.date_plan,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_qc_posting,'%d-%m-%Y') as R2, DATE_FORMAT(MR.date_create,'%d-%m-%Y') as R3 FROM qqc_transaction AS MR, work_center_detail AS SR WHERE SR.id_work = MR.work_center AND MR.status = 'N' AND MR.status_QC != '".$rst_sta4["status_desc"]."' ".$where_sql." ORDER BY MR.plan_no ASC";
$rs = mysql_query($query);   //run the query.
$num = mysql_num_rows($rs);   //how many material are there?


	
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
   
   while ($row = mysql_fetch_array($rs))
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
    $result_type = mysql_query($query_type);
    $row_type = mysql_fetch_array($result_type); 
	
	$query_reason = "SELECT * FROM reason_ng_reject WHERE id_reject = '".$row['reason_qc_reject']."' ORDER BY id_reject ASC";
    $result_reason = mysql_query($query_reason);
    $row_reason = mysql_fetch_array($result_reason);
	
	$query_scan = "SELECT * FROM mat_master_header WHERE material_no = '".$row['material_no']."'";
    $result_scan = mysql_query($query_scan);
    $row_scan = mysql_fetch_array($result_scan);
	
	  $query_model = "SELECT * FROM pps_detail WHERE plan_no = '".$row['plan_no']."'";
	  $result_model = mysql_query($query_model);
	  $data_model = mysql_fetch_array($result_model);
	 
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
