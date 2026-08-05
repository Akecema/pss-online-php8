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
$url = "report_wastage_reject.php";

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
$sta2 = "SELECT * from request_status WHERE status_id = '2'";
$sta_res2 = mysqli_query($dbc, $sta2);
$rst_sta2 = mysqli_fetch_array($sta_res2);	

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7'";
$sta_res7 = mysqli_query($dbc, $sta7);
$rst_sta7 = mysqli_fetch_array($sta_res7);	

//CR status (Pending Approve)
$sta15 = "SELECT * from request_status WHERE status_id = '15'";
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
<?php include "left_qqc_super_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_qqc_super.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a><a href="#" class="#">QA/QC</a> <a href="#" class="current">Report Wastage Reject</a></div>
  <h1>Report Wastage Reject</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
     <!-- <div class="span12">-->
      <!--  <div class="widget-box">
          <div class="widget-title">
             <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li class="active"><a role="tab" href="reject_backflush_tran_NG.php">Production Reject</a></li>
              <li><a role="tab" href="reject_qc_tran_NG.php">QC Reject</a></li>
             </ul>
          </div>
          </div>-->
          
           <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="frmSearch" id="frmSearch">
            <table class="table table-bordered table-striped">
            <tr>
              <th width="18%">Posting Date From :</th>
              <td width="32%"><?php
    
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
              <th width="18%">Posting Date To :</th>
              <td width="32%"><?php
    
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
              <th>Disposal Doc. No. From : </th>
              <td><select name="disposal_doc" id="disposal_doc">
                <option value="NULL" placeholder="Select Disposal Document No."> -- Select Disposal Doc. No. --</option>
                <?php
                $queryd1 = "SELECT DISTINCT doc_disposal_no FROM reject_detail_disposal WHERE status_part = 'WQ' ORDER BY doc_disposal_no ASC";
                $resultd1 = mysqli_query($dbc, $queryd1);
                
                while($rowd1 = mysqli_fetch_array($resultd1)) 
                {
				?>
                <option value="<?php echo $rowd1["doc_disposal_no"]; ?>"<?php if(($rowd1["doc_disposal_no"]) == ($_GET["disposal_doc"])) echo "selected"; ?>> <?php echo $rowd1["doc_disposal_no"]; ?></option>
                <?php
                }
                ?>
              </select></td>
             <th>Disposal Doc. No. To : </th>
              <td><select name="disposal_doc2" id="disposal_doc2">
                <option value="NULL" placeholder="Select Disposal Document No."> -- Select Disposal Doc. No. --</option>
                <?php
                $queryd2 = "SELECT DISTINCT doc_disposal_no FROM reject_detail_disposal WHERE status_part = 'WQ' ORDER BY doc_disposal_no ASC";
                $resultd2 = mysqli_query($dbc, $queryd2);
                
                while($rowd2 = mysqli_fetch_array($resultd2)) 
                {
				?>
                <option value="<?php echo $rowd2["doc_disposal_no"]; ?>"<?php if(($rowd2["doc_disposal_no"]) == ($_GET["disposal_doc2"])) echo "selected"; ?>> <?php echo $rowd2["doc_disposal_no"]; ?></option>
                <?php
                }
                ?>
              </select></td>
              
            </tr>
              <tr>
              <th>&nbsp;</th>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
             
              </tr>
             <tr>
              <th>&nbsp;</th>
              <th width="45%" colspan="2">&nbsp;</th>
              <th width="32%"><input name="Submit2" type="submit"  class="btn btn-info" id="button" value="SEARCH" /></th>
              </tr>
            </table>
        </form>
	
	<?php
	
	$dateF = $_GET["date1"];
	$dateT = $_GET["date2"];
	$disposal_doc = $_GET["disposal_doc"];
	$disposal_doc2 = $_GET["disposal_doc2"];
	
	//-------Count all results------------------------//
	
	$where_sql = '';
	
	// 1. DateF
	if($dateF == "0000-00-00") {
		 $wheresql_01 = ""; }
	else {
		  $wheresql_01 = " AND (MR.date_posting >= '$dateF% 00:00:00' )"; }
		  
	// 2. DateT
	if($dateT == "0000-00-00") {
		 $wheresql_02 = ""; }
	else {
		  $wheresql_02 = " AND (MR.date_posting <= '$dateT 00:00:00' )"; }
			
	//3. Disposal Doc. No. From
	if ($disposal_doc == "NULL"){ 
		$wheresql_03 = ""; }
	else {
		$wheresql_03 = " AND MR.doc_disposal_no >= '".$disposal_doc."'"; } 
		
	//4. Disposal Doc. No. To
	if ($disposal_doc2 == "NULL"){ 
		$wheresql_04 = ""; }
	else {
		$wheresql_04 = " AND MR.doc_disposal_no <= '".$disposal_doc2."'"; } 
		
			

	$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04;	
	
	
	//********** END CONDITION **************
				 
	$query8 = "SELECT COUNT(*) FROM  reject_detail_disposal AS MR WHERE MR.status_part = 'WQ' AND MR.qty_wastage != '' AND MR.doc_disposal_no != '' ".$where_sql;
	$result8 = mysqli_query($dbc, $query8) or die(mysqli_error($dbc));
	$num_rows = mysqli_fetch_row($result8);
	
	$pages = new Paginator;
	$pages->items_total = $num_rows[0];
	$pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
	$pages->paginate();
	
	
	$query = "SELECT *, DATE_FORMAT(MR.date_wastage,'%d-%m-%Y  %H:%i:%s') as R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2 FROM reject_detail_disposal AS MR WHERE MR.status_part = 'WQ' AND MR.qty_wastage != '' AND MR.doc_disposal_no != '' ".$where_sql." ORDER BY MR.plan_no ASC";
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
          <td width="7%"><a href="report_wastage_reject_download.php?date1=<?php echo $dateF; ?>&&date2=<?php echo $dateT; ?>&&disposal_doc=<?php echo $disposal_doc; ?>&&disposal_doc2=<?php echo $disposal_doc2; ?>" ><img src="../img/dload_excel.jpg" width="48" height="48" title="Download" /></a></td>
         <td width="7%"><img src="../img/print2.jpg" width="48" height="48" onClick="window.print()" title="Print"/></td>
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
                <th>Document No.</th>
                <th width="77">Part No.</th>
                <th>Posting Date</th> 
                <th>Quantity</th> 
                <th>UOM</th>  
                <th>From Location</th>
                <th>Work Center</th>
                <th>Type of Wastage</th>
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
	
	$query_type = "SELECT * FROM type_wastage_detail WHERE id_wastage = '".$row['type_wastage']."' ORDER BY id_wastage ASC";
    $result_type = mysqli_query($dbc, $query_type);
    $row_type = mysqli_fetch_array($result_type); 
	
	$query_reason = "SELECT * FROM reason_wastage WHERE id_reason_wastage = '".$row['reason_wastage']."' ORDER BY id_reason_wastage ASC";
    $result_reason = mysqli_query($dbc, $query_reason);
    $row_reason = mysqli_fetch_array($result_reason);
	
	$query_scan = "SELECT * FROM mat_master_header WHERE material_no = '".$row['material_no']."'";
    $result_scan = mysqli_query($dbc, $query_scan);
    $row_scan = mysqli_fetch_array($result_scan);
	
   ?>
           
             <tr class="gradeX">
                <td width="30"><?php echo $no; ?></td>
                <td width="80"><?php echo $row["model_code"]; ?></td>
                <td width="48"><font color="#0000CC"><?php echo $row["doc_disposal_no"]; ?></font></td>
                <td><?php echo $row["material_no"]; ?></td>
                <td width="80"><?php echo $row["R2"]; ?></td>  
                <td width="60"><?php echo intval($row["qty_wastage"]); ?></td>
                <td width="60"><?php echo $row["UOM_unit"]; ?></td> 
                <td width="60"><?php echo $row["ploc"]; ?></td>
                <td width="60"><?php echo $row["work_center"]; ?></td> 
                <td width="80"><?php echo $row_type['wastage_desc']; ?></td>
                <td width="80"><?php echo $row_reason['reason_wastage_desc']; ?></td>
                <td width="140"><?php echo $row["remarks"]; ?>
               <input name="id" type="hidden" value="<?php echo $row["id_disposal"]; ?>">
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
