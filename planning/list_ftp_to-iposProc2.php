<?php

/**
 * planning/list_ftp_to-iposProc2.php
 * Part of: Planning module
 * Filename suggests: list ftp to iposProc2
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, work_center_detail, factory_detail, plan_cat_pps, pps_detail, mat_master_header, ftp_pss_ipos.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_planning_menu.php, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
error_reporting(E_ALL &~ E_NOTICE &~ E_DEPRECATED);
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");
date_default_timezone_set('Asia/Kuala_Lumpur');

$Cdate = date ("l, j F Y ");
set_time_limit(0);
//$t=time();
$t = (date("His"));
$fmt_curr_date = (date("d-m-Y"));



                 $drun = substr($fmt_curr_date,0,2);
				 $mrun = substr($fmt_curr_date,3,2);
				 $yrun = substr($fmt_curr_date,6,4);
				 
				 $date_run = ($yrun.$mrun.$drun.$t);
				 

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$url = "list_ftp_to-ipos.php";

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


//CR status (Cancelled)
$sta4 = "SELECT * from request_status WHERE status_id = '4'";
$sta_res4 = mysqli_query($dbc, $sta4);
$rst_sta4 = mysqli_fetch_array($sta_res4);	

//CR status (InProgress)
$sta7 = "SELECT * from request_status WHERE status_id = '7'";
$sta_res7 = mysqli_query($dbc, $sta7);
$rst_sta7 = mysqli_fetch_array($sta_res7);	

//CR status (Closed)
$sta13 = "SELECT * from request_status WHERE status_id = '13'";
$sta_res13 = mysqli_query($dbc, $sta13);
$rst_sta13 = mysqli_fetch_array($sta_res13);

//CR status (Completed)
$sta14 = "SELECT * from request_status WHERE status_id = '14'";
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
<style type="text/css">
.com_i {
	color: #FF0000;
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
	    $amount .= $_POST["remark_closed"][$i]."|";
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
<?php include "left_planning_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_planning.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a><a href="#" class="current">FTP to IPOS</a></div>
  <h1>FTP Planned Order to IPOS</h1>
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
            <?php
		
			$dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			$factory = $_GET["factory"];
			$work_center = $_GET["work_center"];
		    $plan_category = $_GET["plan_category"]; 
			$shift_ops = $_GET["shift_ops"];
	     
		   $where_sql = "";
			
		   	 //convert 
			
			$query_convert = "SELECT * FROM work_center_detail WHERE id_work = '".$_GET["work_center"]."'";
			$result_convert = mysqli_query($dbc, $query_convert); 
			$row_convert = mysqli_fetch_array($result_convert);
	

			//-------Count all results------------------------//
			
				
			
		 //1. DateF 
                if ($dateF  == "0000-00-00"){
                     $wheresql_01 = "";}
                else {
                     $wheresql_01 = " AND (date_plan >= '".$dateF."')";} 
					  
		 // 2. DateT
                if($dateT == "0000-00-00") {
                     $wheresql_02 = ""; }
                else {
                      $wheresql_02 = " AND (date_plan <= '".$dateT."')"; }
		  
					 
		 //3. Factory 
                if ($factory == "NULL"){ 
                    $wheresql_03 = ""; }
                else {
                    $wheresql_03 = " AND id_factory_pps = '".$factory."'"; } 
					
          //4. Work Center
                if ($work_center == "NULL" ){
                    $wheresql_04 = ""; }
                else {
					$wheresql_04 = " AND work_center = '".$work_center."'"; }
   
	
		  //6. Shift
                if ($shift_ops == "NULL"){ 
                    $wheresql_06 = ""; }
                else {
					// $wheresql_06 = ""; }
					
                    $wheresql_06 = " AND ((shift_pps1 = '".$shift_ops."') OR (shift_pps2 = '".$shift_ops."'))"; }  		 			
				
		  //7. plan category
                if ($plan_category == "NULL"){ 
                    $wheresql_07 = ""; }
                else {
								
                    $wheresql_07 = " AND plan_category = '".$plan_category."'"; } 	
			 			
				
				$where_sql =  $wheresql_01. $wheresql_02. $wheresql_03. $wheresql_04. $wheresql_06. $wheresql_07;		
				
	//********** END CONDITION **************
			
			?>     
      
           <form name="form1" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <table class="table table-bordered table-striped">
            <tr>
              <th>Planned Start Date :<span class="com_i">*</span></th>
              <td><?php
			  
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
              <th>Planned End Date :<span class="com_i">*</span></th>
              <td><?php
			  
			  
                      $dd2 = substr($_GET['date2'],8,2);
				      $mm2 = substr($_GET['date2'],5,2);
				      $yy2 = substr($_GET['date2'],0,4);
				
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
              <th>Factory : </th>
              <td><select name="factory" id="factory" onChange="getFactory(this.value)">
                  <option value="NULL" placeholder="Select Factory"> -- Select Factory --</option>
                  <?php
	               $query3 = "SELECT * FROM factory_detail GROUP BY factory_desc2 ORDER BY id_fac ASC";
                   $result3 = mysqli_query($dbc, $query3);
  
                   while($row3=mysqli_fetch_array($result3)) 
			      {
				  
				  
				  ?>
                  <option value="<?php echo $row3["factory_desc2"]; ?>" <?php if($row3["factory_desc2"] == $_GET["factory"]) echo "selected"; ?>> <?php echo $row3["factory_desc"]; ?></option>
                  <?php
                  }
				?>
              </select></td>
              <th>Work Center :</th>
              <td><div id="work_centerdiv"> 
               <select name="work_center" id="work_center">
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
              <th>Plan Category :</th>
              <td><select name="plan_category" id="plan_category">
                  <option value="NULL" placeholder="Select Plan Category"> -- Select Plan Category --</option>
                  <?php
	               $query29 = "SELECT * FROM plan_cat_pps ORDER BY id_plan ASC";
                   $result29 = mysqli_query($dbc, $query29);
  
                   while($row29=mysqli_fetch_array($result29)) 
			      {
				   ?>
                     <option value="<?php echo $row29["id_plan"]; ?>" <?php if($row29["id_plan"] == $_GET["plan_category"]) echo "selected"; ?>> <?php echo $row29["plan_category_desc"]; ?></option>
                 
                  <?php
                  }
				?>
              </select></td>
              <th>Shift :</th>
              <td><select name="shift_ops" id="shift_ops" class="span11">
                  <option value="NULL" placeholder="Select Shift"> -- Select Shift --</option>
                  <option value="D/S" <?php if($_GET["shift_ops"] == "D/S") { ?> selected="selected"<?php } ?>>D/S</option>
                  <option value="N/S" <?php if($_GET["shift_ops"] == "N/S") { ?> selected="selected"<?php } ?>>N/S</option>
                  </select></td>
            </tr>
            <tr>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
            </tr>
            <tr>
              <th><span class="com_i">*</span> Compulsory&nbsp;</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
              <th><input name="Submit2" type="submit"  class="btn btn-info" id="button" value="SEARCH" /></th>
            </tr>
            </table>
        </form>
           <?php

	
	
		
   $query8 = "SELECT COUNT(*) FROM pps_detail WHERE status_pps = '".$rst_sta2["status_desc"]."' AND status = 'Y' ".$where_sql;
   $result8 = mysqli_query($dbc, $query8);
   $num_rows = mysqli_fetch_row($result8);

   $pages = new Paginator;
   $pages->items_total = $num_rows[0];
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query_all = "SELECT *, DATE_FORMAT(date_plan,'%d-%m-%Y') as R, DATE_FORMAT(date_posting,'%d-%m-%Y') as R2, DATE_FORMAT(date_create,'%d-%m-%Y') as R3 FROM pps_detail WHERE status_pps = '".$rst_sta2["status_desc"]."' AND status = 'Y' ".$where_sql." ORDER BY plan_no ASC";
$rs = mysqli_query($dbc, $query_all);   //run the query.
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
         <form name="myform" method="post" action="list_ftp_to-iposProc2.php?date1=<?php echo $dateF; ?>&&date2=<?php echo $dateT; ?>&&factory=<?php echo $factory; ?>&&work_center=<?php echo $work_center; ?>&&shift_ops=<?php echo $shift_ops; ?>&&plan_category=<?php echo $plan_category; ?>">
         
          <table>
     	  <tr>
          <td width="1000">&nbsp;</td>
          </tr>
          <tr>
          <td width="200"><br><div align="right"><input name="Submit3" type="submit"  class="btn btn-success" id="button" value="FTP to IPOS" onclick="return confirm('Are you sure you want FTP to IPOS Server?');"/></div></td>
          </tr>
          </table><br>
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                <th>No. </br> <input type="checkbox" name="chkDel" class="selectall"/> </br> </th> 
                <th>Sent</th>
                <th>Factory</th>
                <th>Work Center</th> 
                <th>Planned Order No.</th>
                <th>Part No.</th>
                <th>Part Description</th>
                <th>Planned Date</th>
                <th>Shift</th>
                <th>Plan Quantity</th>
                <th>UoM</th>
                <th>FTP Date</th>
                <th>FTP Time</th>
                </tr>
              </thead>   
              <tbody>
           <?php
   
   $counter = 1;
   $no = 1;
   $sta_out = "";
   $k = 1;
   
   while ($row = mysqli_fetch_array($rs))
   {
		
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
	 
	 
	//---factory detail -------
	$query_fac = "SELECT * FROM factory_detail WHERE factory_desc2 = '".$row["id_factory_pps"]."'";
	$result_fac = mysqli_query($dbc, $query_fac);
	$data_fac = mysqli_fetch_array($result_fac);	 
	 
	
	//-----material info -------
	$query_mat = "SELECT * FROM mat_master_header WHERE material_no = '".$row["material_no"]."' AND status_BOM = 'Y'";
	$result_mat = mysqli_query($dbc, $query_mat);
	$data_mat = mysqli_fetch_array($result_mat);
	

	
	 
      ?>
           
                <tr class="gradeX">
                <td width="30"><div align="center"><input type="checkbox" name="cancel[]" value="<?php echo $row["id"]; ?>" <?=was_checked($row["id"],$a) ?> /><input type="hidden" name="Check_ctr" value="yes" 
onClick="Check(document.myform.cancel)">  
                 </div>    <?php echo $no; ?></td>
                <td width="48">
              <?php	
				
	//-----ftp info -------
	$query_ftp_sent = "SELECT * FROM ftp_pss_ipos WHERE plan_no = '".$row["plan_no"]."' AND material_no = '".$row["material_no"]."'";
	$result_ftp_sent = mysqli_query($dbc, $query_ftp_sent);
	$data_ftp_sent = mysqli_fetch_array($result_ftp_sent);
	       
			   if($data_ftp_sent > 0 ){
			     
			    ?> <img src="../img/tick.png" width="12" height="12"><?php echo "<br>"; ?><?php  
			   }
			
			    ?>
                
                </td>
                <td width="48"><?php echo $data_fac["factory_desc"]; ?></td>
                <td width="80"><?php  echo $row["work_center"]; ?></td> 
                <td width="120"><?php echo $row["plan_no"]; ?></td>
                <td width="100"><?php echo $row["material_no"]; ?></td>
                <td width="100"><?php echo $data_mat["material_desc"]; ?></td>
                <td width="80"><?php echo $row["R"]; ?></td>
                <td width="40"><?php echo $sta; ?></td>
                <td width="43"><div align="center"><?php echo number_format($row["qty_plan"]); ?></div></td>
                <td width="40"><?php echo $data_mat["BUn"]; ?></td>
             
                <td width="100">   <?php	
				
	//-----ftp info -------
	$query_ftp = "SELECT *, DATE_FORMAT(posting_date,'%d-%m-%Y') as TG  FROM ftp_pss_ipos WHERE plan_no = '".$row["plan_no"]."' AND material_no = '".$row["material_no"]."'";
	$result_ftp = mysqli_query($dbc, $query_ftp);
                while($data_ftp = mysqli_fetch_array($result_ftp))
	           {  
			     
			    ?><?php echo $data_ftp["TG"]; echo "<br>"; ?><?php  
			 
			 
                  }
			    ?></td>
                <td width="40">
			 <?php	
				
	//-----ftp info -------
	$query_ftp2 = "SELECT *, DATE_FORMAT(posting_time,'%H:%i:%s') as TG2  FROM ftp_pss_ipos WHERE plan_no = '".$row["plan_no"]."' AND material_no = '".$row["material_no"]."' ORDER BY posting_time ASC";
	$result_ftp2 = mysqli_query($dbc, $query_ftp2);
                while($data_ftp2 = mysqli_fetch_array($result_ftp2))
	           {  
			     
			    ?><?php echo $data_ftp2["TG2"]; echo "<br>"; ?><?php  
			 
			 
                  }
			    ?>	
				
				
				</td>
             <input name="id[<?php echo $k; ?>]" type="hidden" value="<?php echo $row["id"]; ?>">
                </tr>
          <?php 
	          
		  $no ++;
		  $counter++; // menambah counter
		  $k ++;
		  } 
	 
		  ?>
 </tbody>
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
	return mysqli_real_escape_string($dbc, $data);
	}   // end function.
$message = NULL; // create an empty new variable.
 
// $i = 0;
// $size2 = count($_POST["id"]) + 1;
// $cancel = $_POST["cancel"]; 
 $sta_cntr = '';
 
  if(isset($_POST["cancel"])) 
  {
 
    $cancel = $_POST["cancel"]; 
    $how_many = count($cancel); 
	$remark_closed = $_POST["remark_closed"]; 
	$string = "";
	
	     foreach($_POST["cancel"] as $j=>$i) {
	    
		$amount .= $_POST["remark_closed"][$i];
	    $string = explode("|",($amount));	
			}
	

	    
		   for ($i=0; $i<$how_many; $i++) { 
		
	
		//echo ($i+1).'-'.$cancel[$i]; echo $string[$i]; echo "</br>";
		
		
		//insert table pps_detail_close
		
		$query_info = "SELECT * FROM pps_detail WHERE id = '".$cancel[$i]."'";
		$result_info = mysqli_query($dbc, $query_info);
		$row_info = mysqli_fetch_array($result_info);
	
		
		//-----material info ----
	
	$query_mat_info2 = "SELECT * FROM mat_master_header WHERE material_no = '".$row_info["material_no"]."' AND status_BOM = 'Y'";
	$result_mat_info2 = mysqli_query($dbc, $query_mat_info2);
	$data_mat_info2 = mysqli_fetch_array($result_mat_info2);
	
	//-----shift day ----
	
	if($row_info["shift_pps1"] != "")
	{
		$shift_dayF = "D/S"; 
	}elseif($row_info["shift_pps2"] != "")
	{
		$shift_dayF = "N/S"; 
	}else{
		
		$shift_dayF = ""; 
	}
	
	//https://www.w3schools.com/Php/func_array_column.asp
	
	$cntr = $row_info['work_center'];
	
    $filen="PPS".$date_run.$cntr;
	$file_chker = "PPS".$date_run;
	   
    $query_pps_closed = "INSERT INTO ftp_pss_ipos(id,file_chker,file_name,plan_no,id_pss,material_no,material_desc,qty_ftp,uom,work_center,id_factory,plant,date_plan,shift_day,status_ftp,posting_date,posting_time,user_create,date_create) VALUES('','".$file_chker."','".$filen."','".$row_info['plan_no']."','".$row_info["id"]."','".$row_info["material_no"]."','".$data_mat_info2["material_desc"]."','".$row_info["qty_plan"]."','".$data_mat_info2["BUn"]."','".$row_info["work_center"]."','".$row_info["id_factory_pps"]."','".$row_info["comp_code"]."','".$row_info["date_plan"]."','".$shift_dayF."','Y',NOW(),NOW(),'".$row_info["user_create"]."','".$row_info["date_create"]."')";
	$rst_pps_closed = mysqli_query($dbc, $query_pps_closed);
     
	
	
		  
	}// end for loop
			


//-------  checking line x sama --------//

  $query_chk_line = "SELECT COUNT(DISTINCT work_center) AS GG FROM ftp_pss_ipos WHERE file_chker = '".$file_chker."'";
  $rst_chk_line = mysqli_query($dbc, $query_chk_line);
  $data_chk_line = mysqli_fetch_array($rst_chk_line);

 
 if($data_chk_line["GG"] > 1)
 {
	
		//-----delete file date x ssama ------
			
		     $query_del_tarikh = "DELETE FROM ftp_pss_ipos WHERE file_chker = '".$file_chker."'";
			 $result_del_tarikh =  mysqli_query($dbc, $query_del_tarikh); 
			
	
	        echo '<script type="text/javascript">';
	        echo "alert('Planned Order must be same work center to FTP.');";
	        echo "window.location='list_ftp_to-iposProc2.php?date1=$dateF&&date2=$dateT&&factory=$factory&&work_center=$work_center&&shift_ops=$shift_ops&&plan_category=$plan_category';"; 
			echo "</script>";
			//exit(); //quit the script   
			
			
		
	 
 }elseif($data_chk_line["GG"] == 1)
 {
	 
	
			
//	 
$qry = mysqli_query($dbc, "SELECT *, DATE_FORMAT(date_plan,'%Y%m%d') AS R FROM ftp_pss_ipos WHERE file_name = '".$filen."'");
$data = "";
$bil = 1;
while($rowz = mysqli_fetch_array($qry)) {
	
	
  $data .= $bil.";REL;".$rowz['plant'].";".$rowz['id_factory'].";".$rowz['work_center'].";".$rowz['plan_no'].";".$rowz['material_no'].";".$rowz['material_desc'].";".$rowz['R'].";".$rowz["shift_day"].";".$rowz['qty_ftp'].";".$rowz['uom']."\r\n";

$bil++;

}

//$filen="PPS".$date_run.$cntr;

//-----split filen to work center for folder -------

$sta_cntr = substr($filen,17,4);	

//echo $sta_cntr;

 if($sta_cntr == "N1G7")
 {
$file = "../ToIPOS/N1G7/".$filen.".csv";
//chmod($file, 0777);
file_put_contents($file,$data);	  
	 
 }elseif($sta_cntr == "N1G9")
 {
	 
$file = "../ToIPOS/N1G9/".$filen.".csv";
//chmod($file, 0777);
file_put_contents($file,$data);	 
	 
 }else{
	 
$file = "../ToIPOS/".$filen.".csv";
//chmod($file, 0777);
file_put_contents($file,$data);	 
	 
 }


	
 }else{
	 
	 
 }		
		
			
			//------message successfully update --------
		   echo "<script>";
		   echo "alert('File has been sent to IPOS server successfully.');";
		   echo "window.location='list_ftp_to-iposProc2.php?date1=$dateF&&date2=$dateT&&factory=$factory&&work_center=$work_center&&shift_ops=$shift_ops&&plan_category=$plan_category'";
		   echo "</script>"; 
		   exit(); //quit the script
			
			
			
   

  }// end if
   else{
	   
	       echo "<script>";
		   echo "alert('Please tick the check box for proceed the transaction.');";
		   echo "window.location='list_ftp_to-iposProc2.php?date1=$dateF&&date2=$dateT&&factory=$factory&&work_center=$work_center&&shift_ops=$shift_ops&&plan_category=$plan_category'";
		   echo "</script>"; 
		   exit(); //quit the script
	   
   }

}	
        
    	?>
          </div>
        
      </div>
    </div>
  </div>
</div>

<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part--> 
<!--checkbox-->
<script type="text/javascript">
$('.selectall').click(function() {
    if ($(this).is(':checked')) {
        $('div input').attr('checked', true);
    } else {
        $('div input').attr('checked', false);
    }
});
</script>

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
