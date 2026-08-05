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

$url = "wip_request_analysis.php";


    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
    $result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
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
<style type="text/css" media="print">
@page {
    size: A4;
   /* margin: 0;*/
    margin-top: 1.5cm;
	margin-bottom: 1.5cm;
}
@media print {
    .noprint {display:none !important;}
    a:link:after, a:visited:after {  
      display: none;
      content: ""; 
	     
    }
	 html, body {
    width: 28cm;
    height: 29cm;
  }
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
<div class="noprint">
<?php  include "top_modal_menu.php";   ?>
<!--close-Header-part--> 

<!--sidebar-menu-->
<?php include "left_prod_super_menu.php";  ?>
<!--sidebar-menu-->
</div>
<div id="content">
 <div id="content-header" class="noprint">
  <div id="breadcrumb"> <a href="index_production_super.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">WIP Request</a> <a href="#" class="current">WIP Request Report</a> </div>
  <h1>WIP Request</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
    <!--  <div class="span12">-->
      
          
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="frmSearch" id="frmSearch">
         <table class="table table-bordered table-striped">
              <tr>
                  <th>Request Date From :</th>
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
                  <th>Request Date To :</th>
                  <td> <?php
				  
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
              <th>Factory :</th>
              <td><select name="factory" id="factory" onChange="getFactory(this.value)">
                  <option value="NULL" placeholder="Select Factory"> -- Select Factory --</option>
                  <?php
	               $query3 = "SELECT * FROM factory_detail GROUP BY factory_desc2 ORDER BY id_fac ASC";
                   $result3 = mysqli_query($dbc, $query3);
  
                   while($row3=mysqli_fetch_array($result3, MYSQLI_NUM)) 
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
                   $result5 = mysqli_query($dbc, $query5);
  
                   while($row5=mysqli_fetch_array($result5)) 
				    { 
				   
				   ?>
                <option value="<?php echo $row5["id_work"]; ?>" <?php if($row5["id_work"] == $_GET["work_center"]) echo "selected"; ?>> <?php echo $row5["id_work"],' - ',stripslashes($row5["wc_desc"]); ?></option>
                <?php
                  }
				?>
                </select></div></td>
            </tr>
            <tr>
              <th>Material No.  :</th>
              <td><select name="material_no" id="material_no">
                  <option value="NULL" placeholder="Select Material No."> -- Select Material No. --</option>
                  <?php
	               $query9 = "SELECT * FROM mat_master_detail WHERE mat_type = 'Z110' GROUP BY bill_component ORDER BY bill_component ASC";
                   $result9 = mysqli_query($dbc, $query9);
  
                   while($row9=mysqli_fetch_array($result9)) 
			      {
				   ?>
                  <option value="<?php echo $row9["bill_component"]; ?>" <?php if($row9["bill_component"] == $_GET["material_no"]) echo "selected"; ?>> <?php echo $row9["bill_component"]; ?></option>
                  <?php
                  }
				?>
              </select></td>
              <th>MRIN Status :</th>
              <td><select name="status" id="status">
                  <option value="NULL" placeholder="Select Status"> -- Select Status --</option>
                  <option value="Close" <?php if($_GET["status"] == "Close") { ?> selected="selected"<?php } ?>>Close</option>
                  <option value="Cancel" <?php if($_GET["status"] == "Cancel") { ?> selected="selected"<?php } ?>>Cancel</option>
              </select></td>
            </tr>
            <tr>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th><input name="Submit" type="submit" value="Search" class="btn btn-info"></th>
            </tr>
           </table>
        </form>
              
          <?php
	       
		    $material_no = $_GET["material_no"];
			$factory = $_GET["factory"];
			$work_center = $_GET["work_center"];
	        $status = $_GET["status"];
			$dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			
		    //convert material no kpd id_hdr
			
			$query_convert = "SELECT * FROM `mat_master_header` as MH WHERE MH.material_no = '".$_GET["material_no"]."'";
			$result_convert = mysqli_query($dbc, $query_convert); 
			$row_convert = mysqli_fetch_array($result_convert);
		
			//-------Count all results------------------------//
			
				 $where_sql = '';
		 
		 // 1. DateT
                if($dateT == "0000-00-00") {
                     $wheresql_01 = ""; }
                else {
                      $wheresql_01 = " AND (MR.date_mrin <= '$dateT')"; }
		  //2. DateF 
                if ($dateF  == "0000-00-00" ){
                     $wheresql_02 = "";}
                else {
                     $wheresql_02 = " AND (MR.date_mrin >= '$dateF')";}
                                                
		 // 3. Status
                if ($status == "NULL" ){
                    $wheresql_03 = ""; }
                else {
                    $wheresql_03 = " AND MR.status = '$status'"; }          
                                
          //4. Work Center
                if ($work_center == "NULL" ){
                    $wheresql_04 = ""; }
                else {
					$wheresql_04 = " AND SD.work_center = '$work_center'"; }
   
	       //5. Material No.
                if ($material_no == "NULL"){ 
                    $wheresql_05 = ""; }
                else {
                    $wheresql_05 = " AND MR.bom_component = '$material_no'"; }  	
					
		   //6. Factory 
                if ($factory == "NULL"){ 
                    $wheresql_06 = ""; }
                else {
                    $wheresql_06 = " AND SD.factory = '$factory'"; }  			
	       	
	                                        
				
				$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04 .$wheresql_05 .$wheresql_06;	
	
	//********** END CONDITION **************

								 
   $query8 = "SELECT *,DATE_FORMAT(MR.date_mrin,'%d-%m-%Y') AS R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R2 FROM wip_request AS MR, scan_detail_wip AS SD WHERE MR.id_scan_wip = SD.id_scan AND MR.status_request = 'Y' AND MR.status != 'New'" .$where_sql;
   $result8 = mysqli_query($dbc, $query8) or die(mysqli_error($dbc));
     $num_8 = mysqli_fetch_row($result8);
     $num_rows = mysqli_num_rows($result8);
   
   $pages = new Paginator;
   $pages->items_total = $num_rows;
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT *,DATE_FORMAT(MR.date_mrin,'%d-%m-%Y') AS R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R2 FROM wip_request AS MR, scan_detail_wip AS SD WHERE MR.id_scan_wip = SD.id_scan AND MR.status_request = 'Y' AND MR.status != 'New'".$where_sql;
$rs = mysqli_query($dbc, $query);   //run the query.
$num = mysqli_num_rows($rs);   //how many material are there?


	
	 if($num > 0) {
	 
	 echo '<div align="center">There are currently  '. $num.' record(s).</div>';
	
?>

<table class="table">
<tr>
    <td width="1%">&nbsp;</td> 
    <td width="85%"> <div class="small-nav"></div></td> 
      <td width="7%"><a href="report_wip_request_analysisProc.php?factory=<?php echo $factory; ?>&&work_center=<?php echo $work_center; ?>&&status=<?php echo $status; ?>&&date1=<?php echo $dateF; ?>&&date2=<?php echo $dateT; ?>&&material_no=<?php echo $material_no; ?>"><img src="../img/dload_excel.jpg" width="48" height="48" title="Download" /></a></td>
     <td width="7%"><img src="../img/print2.jpg" width="48" height="48" onClick="window.print()" title="Print"/></td>
   
  </tr>
</table>

     <div id="a_print">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>WIP Request Report</h5>
          </div>
             
          <div class="widget-content nopadding">
          
    <table class="table table-bordered data-table">
    <thead>
     <tr>
     <th>Factory</th>
     <th>Line</th>
     <th>MRIN No.</th>
     <th>Material No.</th>
     <th>Required <br />Date</th>
     <th>Required Time</th>
     <th>Duration</th>
     <th>Requested <br />Quantity</th>
     <th>Transferred <br />Quantity</th>
     <th>Variance <br />Quantity</th>
     <th>UoM</th>
     <th>Status</th>
     </tr></thead><tbody>
       
     <?php
		  
   $counter = 1;
   $no = 1;
   $i = 1;
   $variance_qty = 0;
    $bq = 0;
   $rq = 0;
   
   while ($row2 = mysqli_fetch_array($rs))
   {
		//$user_no = $row[0]; 

		
		
   	$query_scan = "SELECT * FROM scan_detail_wip WHERE id_scan = '".$row2[6]."'";
   	$result_scan = mysqli_query($dbc, $query_scan);
   	$row_scan = mysqli_fetch_array($result_scan);
	
	$query_again = "SELECT * FROM wip_request WHERE status_request = 'Y' and id_scan_wip = '".$row2[6]."' ORDER BY id_req_wip ASC";
    $rs_again = mysqli_query($dbc, $query_again);   //run the query.
    $row = mysqli_fetch_array($rs_again);
	
	$query_u = "SELECT * FROM user_detail WHERE user_no = '$row[user_create]'";
	$result_u = mysqli_query($dbc, $query_u);   //run the query.
	$data_u = mysqli_fetch_array($result_u);   //how many records are there?    

 	$query3 = "SELECT * FROM factory_detail WHERE id_fac = '".$row_scan["factory"]."'";
    $result3 = mysqli_query($dbc, $query3);
	$row3 = mysqli_fetch_array($result3);
	
	$query4_p = "SELECT * from mat_master_header as LD, mat_master_detail as SD WHERE SD.id_hdr = LD.id_hdr and SD.id_dtl = '".$row2[5]."'";
  	$result4_p = mysqli_query($dbc, $query4_p);
 	$row4_p = mysqli_fetch_array($result4_p); 
 
    $query5 = "SELECT * FROM post_detail_header_wip WHERE mrin_no = '".$row2["temp_mrin_wip"]."' AND material_no = '".$row2["bom_component"]."' AND mvt_type = 311 AND prod_order = '".$row_scan["prod_order"]."'";
    $result5 = mysqli_query($dbc, $query5);
	$row5 = mysqli_fetch_array($result5);
	
	$query6 = "SELECT * FROM post_detail_header_wip AS PD, wip_request AS MR WHERE PD.mrin_no = MR.temp_mrin_wip AND PD.material_no = MR.bom_component AND PD.mrin_no = '".$row2["temp_mrin_wip"]."' AND PD.material_no = '".$row2["bom_component"]."' AND PD.mvt_type = 311";
    $result6 = mysqli_query($dbc, $query6);
	$row6 = mysqli_fetch_array($result6);

//-------------------------------------------------------Transfer Posting [Traffic Light] --------------------------
// table post_detail_header --- checking traffic licht
//-----------------------------------------------------------------------------------------------------------------

$date_post =  ($row2["date_mrin"].' '.$row2["time_mrin"]);
//$date_transfer = (date("Y-m-d").' '.date("H:i:s"));
$date_transfer = ($row5["date_create"].' '.$row5["time_create"]);

$start_date = new DateTime($date_post);
$since_start = $start_date->diff(new DateTime($date_transfer));

/*echo $since_start->m.' month<br>';
 echo $since_start->d.' days<br>';
echo $since_start->h.' hours<br>';
echo $since_start->i.' minutes<br>';
echo $since_start->s.' seconds<br>';  */ 

//------------------------------------------------------ Variance Quantity-----------------------------------------
//
//------------------------------------------------------------------------------------------------------------------	

					
    $query_tp = "SELECT *,SUM(rquantity) as TOT FROM post_detail_header_wip WHERE mrin_no = '".$row2["temp_mrin_wip"]."' AND prod_order = '".$row_scan["prod_order"]."' AND mvt_type = 311 AND material_no = '".$row4_p["bill_component"]."'";
	$result_tp  = mysqli_query($dbc, $query_tp); 

    $outs_qty = 0;
					
	while($row_tp = mysqli_fetch_assoc($result_tp))
   {
	
	$tp_quantity = $row_tp["TOT"]; 
	
	$outs_qty = ((($row2["bom_qty_wip"])) - (($row_tp["TOT"])));
	
	 }//end while $row_tp	
	 
	$outs_qty1 = number_format($outs_qty,3);	
	
	  $bq = ($row2["bom_qty_wip"]);	
	  $rq = ($row5["rquantity"]);
	
      $variance_qty = ($bq - $rq);
	  $variance_qty2 =  number_format($variance_qty, 3, '.', '');
	  

		  ?>        
         
  <tr>
    <td width="50"><div align="center"><?php echo $row_scan["factory"]; ?></div></td> 
    <td width="60"><div align="center"><?php echo $row_scan["work_center"]; ?></div></td>
    <td width="100">&nbsp;<?php echo $row2["temp_mrin_wip"]; ?></td>
    <td width="160">&nbsp;<?php echo $row2["bom_component"]; ?></td>
    <td width="100"><div align="center"><?php echo $row2["R"]; ?></div></td>
    <td width="80"><div align="center"><?php echo $row2["time_mrin"]; ?></div></td>
    <td width="70">
	<?php
	//-------------------------------------------------------------------------------------
	//-   check status "Open" and still dont have TP
	//---------------------------------------------------------------------------------
	if ($row6 > 0)
	{
	
     echo $since_start->h." : ".$since_start->i; 
	}else{
	
	echo "&nbsp;";
	}
 
	?>
	
	
	</td>
    <td width="90"><div align="right"><?php echo $row2["bom_qty_wip"]; ?></div> </td>
    <td width="90"><div align="right"><?php echo $row5["rquantity"]; ?></div></td>
    <td width="90"><?php if($variance_qty2 < 0 ) { echo "<font color='red'>";  echo $variance_qty2;  echo "</font>"; }else{ echo $variance_qty2; } ?></td>
    <td width="50"><div align="center"><?php echo $row2["bom_oum_wip"]; ?></div></td>
    <td width="55"><?php echo $row2["status"];    ?></td>
  </tr>
 
  <?php 
		 
		  $no ++;
		  $counter++; // menambah counter 
		   
		   //}// end if
		
		    
		  } ?></tbody></table>   
  </center>

        </div>            
            
  <?php
  mysqli_free_result($rs); 
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
 mysqli_close($dbc);
?>   
</div></div>
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

