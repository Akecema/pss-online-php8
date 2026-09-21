<?php

/**
 * prod_super/report_wastage_reject.php
 * Part of: Production module (supervisor/admin tier)
 * Filename suggests: report wastage reject
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, factory_detail, work_center_detail, reject_detail_disposal, type_wastage_detail, reason_wastage, mat_master_header.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_prod_super_menu.php, footer.php.
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
require_once '../include/auth.php';
require_role($dbc, 3);
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
echo $checked;
echo $amount;

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
<?php include "left_prod_super_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_production_super.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a><a href="#" class="#">Production</a> <a href="#" class="current">Report Wastage Reject</a></div>
  <h1>Report Wastage Reject</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
                   
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmSearch" id="frmSearch">
        <table class="table table-bordered table-striped">
          <tr>
            <th width="23%">Posting Date from :</th>
            <td width="31%"><?php
    
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
					$myCalendar->setYearInterval(2010, date('Y') + 10);
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
						//$myCalendar->setDate(0,0,0);
						$myCalendar->setPath("/calendar/");
						$myCalendar->setYearInterval(2010, date('Y') + 10);
						// $myCalendar->setOnChange("myChanged('test')");
						$myCalendar->writeScript();

					}
			 ?></td>
            <th width="21%">Posting Date to :</th>
            <td width="25%"><?php
    
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
					$myCalendar->setYearInterval(2010, date('Y') + 10);
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
						$myCalendar->setYearInterval(2010, date('Y') + 10);
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
                
                while($row3 = mysqli_fetch_array($result3)) 
                {
				?>
              <option value="<?php echo $row3['factory_desc2']?>" <?php if($row3['factory_desc2']=='1') echo "selected"; ?>> <?php echo $row3['factory_desc'] ?></option>
              <!--echo'<option value="',$row3[2],'">',stripslashes($row3[1]),'</option>';-->
              <?php
                }
                ?>
            </select></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <th>Work Center :</th>
            <td colspan="3"><div id="work_centerdiv">
              <select name="work_center" id="work_center" class="span11">
                <option value="" placeholder="Select Work Center"> -- Select Work Center --</option>
                <?php
                $query31 = "SELECT * FROM work_center_detail WHERE id_factory = '1' ORDER BY id_work ASC";
                $result31 = mysqli_query($dbc, $query31);
                
                while($row31 = mysqli_fetch_array($result31)) 
                {
				?>
                <option value="<?php echo $row31['id_work']?>"> <?php echo $row31['wc_desc'] ?></option>
                <!--echo'<option value="',$row3[2],'">',stripslashes($row3[1]),'</option>';-->
                <?php
                }
                ?>
              </select>
            </div></td>
          </tr>
          <tr>
            <th height="76">Wastage Document No from :</th>
            <td><select name="docF" id="docF">
              <option value="NULL">-- Select Document No --</option>
              <?php
						$sqld = "SELECT DISTINCT doc_disposal_no FROM reject_detail_disposal ORDER BY doc_disposal_no ASC";
						$resultsd = mysqli_query($dbc, $sqld);
						
						while($rowd = mysqli_fetch_assoc($resultsd))
						{
						?>
              <option value="<?php echo $rowd['doc_disposal_no']?>"> <?php echo $rowd['doc_disposal_no']?></option>
              <?php
						}
					  ?>
            </select></td>
            <th>Wastage Document No to :</th>
            <td><select name="docT" id="docT">
              <option value="NULL">-- Select Document No --</option>
              <?php
					$sqld2 = "SELECT DISTINCT doc_disposal_no FROM reject_detail_disposal ORDER BY doc_disposal_no ASC";
					$resultsd2 = mysqli_query($dbc, $sqld2);
					
					while($rowd2 = mysqli_fetch_assoc($resultsd2))
					{
					?>
              <option value="<?php echo $rowd2['doc_disposal_no']?>"> <?php echo $rowd2['doc_disposal_no']?></option>
              <?php
						}
					  ?>
            </select></td>
          </tr>
          <tr>
            <th height="62">&nbsp;</th>
            <th colspan="3"><input name="Submit2" type="submit"  class="btn btn-info" id="button" value="SEARCH" /></th>
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
			$docF = $_POST["docF"];
			$docT = $_POST["docT"];
									
            echo "<script>";
			echo "window.location='report_wastage_reject2.php?date1=$dateF&&date2=$dateT&&factory=$factory&&work_center=$work_center&&docF=$docF&&docT=$docT'";
            echo "</script>";
            exit(); //quit the script
        }
        
								 
   $query8 = "SELECT COUNT(*) FROM reject_detail_disposal WHERE status_part = 'WS' AND qty_wastage != '' AND doc_disposal_no != ''";
   $result8 = mysqli_query($dbc, $query8) or die(mysqli_error($dbc));
   $num_rows = mysqli_fetch_row($result8);

   $pages = new Paginator;
   $pages->items_total = $num_rows[0];
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT *, DATE_FORMAT(date_wastage,'%d-%m-%Y %H:%i:%s') as R, DATE_FORMAT(date_posting,'%d-%m-%Y') as R2 FROM reject_detail_disposal WHERE status_part = 'WS' AND qty_wastage != '' AND doc_disposal_no != '' ORDER BY doc_disposal_no ASC";
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
      <td width="7%"><a href="report_wastage_reject_download_all.php" ><img src="../img/dload_excel.jpg" width="48" height="48" title="Download" /></a></td>
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
		
	$query_type = "SELECT * FROM type_wastage_detail WHERE id_wastage = '".db_esc($dbc, $row['type_wastage'])."' ORDER BY id_wastage ASC";
    $result_type = mysqli_query($dbc, $query_type);
    $row_type = mysqli_fetch_array($result_type); 
	
	$query_reason = "SELECT * FROM reason_wastage WHERE id_reason_wastage = '".db_esc($dbc, $row['reason_wastage'])."' ORDER BY id_reason_wastage ASC";
    $result_reason = mysqli_query($dbc, $query_reason);
    $row_reason = mysqli_fetch_array($result_reason);
	
	$query_scan = "SELECT * FROM mat_master_header WHERE material_no = '".db_esc($dbc, $row['material_no'])."'";
    $result_scan = mysqli_query($dbc, $query_scan);
    $row_scan = mysqli_fetch_array($result_scan);
	 
      ?>
                <tr class="gradeX">
                <td width="30"><?php echo $no; ?></td>
                <td width="80"><?php echo $row["model_code"]; ?></td>
                <td width="48"><font color="#0000CC"><?php echo $row["doc_disposal_no"]; ?></font></td>
                <td><?php echo $row["material_no"]; ?></td>
                <td width="80"><?php echo $row["R2"]; ?></td>  
                <td width="60"><?php echo $row["qty_wastage"]; ?></td>
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
