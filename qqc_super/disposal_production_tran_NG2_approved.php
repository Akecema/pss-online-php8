<?php

/**
 * qqc_super/disposal_production_tran_NG2_approved.php
 * Part of: QQC module (supervisor/admin tier)
 * Filename suggests: disposal production tran NG2 approved
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, reject_detail_disposal.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_qqc_super_menu.php, footer.php.
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
require_role($dbc, 11);
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");

$Cdate = date ("l, j F Y ");
set_time_limit(0);

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
$url = "disposal_production_tran_NG.php";

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

//CR status (Approved)
$sta3 = "SELECT * from request_status WHERE status_id = '3'";
$sta_res3 = mysqli_query($dbc, $sta3);
$rst_sta3 = mysqli_fetch_array($sta_res3);	

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
<link rel="stylesheet" href="../css/pagination3.css" type="text/css" />

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
<?php include "left_qqc_super_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_qqc_super.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a><a href="#" class="#">QA/QC</a> <a href="#"><b>Production Disposal</b></a></div>
  <h1>Production Disposal</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
          <div class="widget-box">
          <div class="widget-title">
             <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li><a role="tab" href="disposal_production_tran_NG.php">New Disposal</a></li>
              <li class="active"><a role="tab" href="disposal_production_tran_NG_approved.php">Approved Disposal</a></li>
             </ul>
          </div>
          </div> 
          
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="frmSearch" id="frmSearch">
            <table class="table table-bordered table-striped">
            <tr>
              <th width="180">Posting Start Date :</th>
              <td width="250"><?php
    
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
              <th width="180">Posting End Date :</th>
              <td width="250"><?php
                
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
              <th>Disposal Doc. No. :</th>
              <td><select name="doc_disposal_no" id="doc_disposal_no" class="span8">
                  <option value="NULL" placeholder="Select Disposal Doc. No.">-Disposal Doc. No.-</option>
                  <?php
	             $query9 = "SELECT * FROM reject_detail_disposal WHERE (status_part = 'PR' OR status_part = 'WS') AND (status_disposal = '".db_esc($dbc, $rst_sta17["status_desc"])."') GROUP BY doc_disposal_no";
                   $result9 = mysqli_query($dbc, $query9);
  
                      while($row9=mysqli_fetch_array($result9)) 
			      {
				   ?>
                  <option value="<?php echo $row9["doc_disposal_no"]; ?>"<?php if($row9["doc_disposal_no"] == $_GET["doc_disposal_no"]) echo "selected"; ?>> <?php echo $row9["doc_disposal_no"]; ?></option>
                  <?php
                  }
				?>
              </select></td>
              <th></th>
              <td></td>
            </tr>
            <tr>
              <th>&nbsp;</th>
              <td>&nbsp;</td>
              <th>&nbsp;</th>
              <td><input name="Submit2" type="submit"  class="btn btn-info" id="button" value="SEARCH" /></td>
            </tr>
            </table>
        </form>
<?php
        $dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			$doc_disposal_no = $_GET["doc_disposal_no"];		
		  
		
			//-------Count all results------------------------//
			
				 $where_sql = '';
		 
		 // 1. DateT
                if($dateT == "0000-00-00") {
                     $wheresql_01 = ""; }
                else {
                      $wheresql_01 = " AND (MR.date_posting <= '".$dateT."')"; }
		  //2. DateF 
                if ($dateF  == "0000-00-00" ){
                     $wheresql_02 = "";}
                else {
                     $wheresql_02 = " AND (MR.date_posting >= '".$dateF."')";}  
					 
		 //3. Doc. Disposal No. 
                if ($doc_disposal_no == "NULL"){ 
                    $wheresql_03 = ""; }
                else {
                    $wheresql_03 = " AND MR.doc_disposal_no = '".$doc_disposal_no."'"; } 
		
				
				$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03;	
	
	//********** END CONDITION **************
   
								 
   $query8 = "SELECT * FROM reject_detail_disposal AS MR WHERE (MR.status_part = 'PR' OR MR.status_part = 'WS') AND doc_disposal_no != '' AND status_disposal = '".db_esc($dbc, $rst_sta17["status_desc"])."'".$where_sql." GROUP BY MR.doc_disposal_no ORDER BY MR.plan_no ASC";
   $result8 = mysqli_query($dbc, $query8) or die(mysqli_error($dbc));
   //$num_rows = mysqli_fetch_row($result8);
    $num_rows = mysqli_num_rows($result8);

   $pages = new Paginator;
   $pages->items_total = $num_rows;
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT *, DATE_FORMAT(MR.date_plan,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') as R2 FROM reject_detail_disposal AS MR WHERE (MR.status_part = 'PR' OR MR.status_part = 'WS') AND doc_disposal_no != '' AND status_disposal = '".db_esc($dbc, $rst_sta17["status_desc"])."'".$where_sql." GROUP BY MR.doc_disposal_no ORDER BY MR.plan_no ASC $pages->limit";
$rs = mysqli_query($dbc, $query);   //run the query.
//$num = mysqli_num_rows($rs);   //how many material are there?


	
	 if ($num_rows > 0) {
	 

?>
  <table width="98%" cellspacing="10">
<tr>
   <td height="15"><?php echo '<div align="center">There are currently  '.$num_rows.' record(s).</div>';  ?></td>
   <td width="15%"><div align="right"><?php echo "<span class=\"\">".$pages->display_jump_menu().$pages->display_items_per_page()."</span>" ;?></div></td>
        </tr>
</table>

      <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display Request</h5>
          </div>
             
          <div class="widget-content nopadding">
             <table class="table table-striped table-hover table-bordered">
              <thead>
                <tr>
                <th>No.</th> 
                <th>Document No.</th>
                <th>Status</th>
                <th colspan="2">Action</th>
                </tr>
              </thead>   
            
           <?php
   
   $counter = 1;
   $no = 1;
   $sta_out = "";
   
   while ($row = mysqli_fetch_array($rs))
   {

      ?>
             <tbody>
                <tr class="gradeX">
                <td width="78"><?php echo $no; ?></td>
                <td width="324"><?php echo $row["doc_disposal_no"]; ?></td>
                <td width="158"><?php echo $row["status_disposal"]; ?></td>
                <td width="93">
              <?php
			  
			  if($row["status_disposal"] == "Approved")
			   { 
					
					 if($row["status_part"] == "PR")
				   {
					?>
				 <a value="Details" href="approval_disposal_approve_tran_NG.php?doc_disposal=<?php echo $row["doc_disposal_no"]; ?>&&TB_iframe=true&height=400&width=1100" class="thickbox" target="_self"><img src="../img/tick.png" width="16" height="16" alt="View">Approval</a>	<?php
				 
				   }elseif($row["status_part"] == "WS")
					 {  
					 ?> <a value="Details" href="approval_disposal_approve_tran_NG_wastage.php?doc_disposal=<?php echo $row["doc_disposal_no"]; ?>&&TB_iframe=true&height=400&width=1100" class="thickbox" target="_self"><img src="../img/tick.png" width="16" height="16" alt="View">Approval</a> 
					<?php	 }
				 
				}elseif($row["status_disposal"] == "Approved QC"){
					
					 if($row["status_part"] == "PR")
				   {
					
					?>
				 <a value="Details" href="view_approval_disposal_approve_tran_NG.php?doc_disposal=<?php echo $row["doc_disposal_no"]; ?>&&TB_iframe=true&height=400&width=1100" class="thickbox" target="_self"><img src="../img/icon_view.jpg" width="16" height="16" alt="View">View</a>	<?php
				 
				   }elseif($row["status_part"] == "WS")
					 {
		      ?>
               <a value="Details" href="view_approval_disposal_approve_tran_NG_wastage.php?doc_disposal=<?php echo $row["doc_disposal_no"]; ?>&&TB_iframe=true&height=400&width=1100" class="thickbox" target="_self"><img src="../img/icon_view.jpg" width="16" height="16" alt="View">View</a>	
			   
			   <?php  }
			   
				} ?></td>
                <td width="94">
                
                <?php
                 if(($row["status_part"] == "PR") || ($row["status_part"] == "QC"))
				   {
					?>
				 <a value="Print" href="print_approval_disposal_tran_NG.php?doc_disposal=<?php echo $row["doc_disposal_no"]; ?>&&TB_iframe=true&height=400&width=1100" class="thickbox" target="_self"><img src="../img/print2.jpg" width="16" height="16" alt="Print">Print</a>	<?php
				 
				   }elseif(($row["status_part"] == "WS") || ($row["status_part"] == "WQ"))
				   
				   {
					   
					?>  <a value="Print" href="print_approval_disposal_tran_NG_wastage.php?doc_disposal=<?php echo $row["doc_disposal_no"]; ?>&&TB_iframe=true&height=400&width=1100" class="thickbox" target="_self"><img src="../img/print2.jpg" width="16" height="16" alt="Print">Print</a> 
					<?php }
                
                  ?>
                </td>
                </tr>
               </tbody>   
          <?php 
		  
		  $no ++;
		  $counter++; // menambah counter
		  } 
		  ?>
       </table>
       <?php
        mysqli_free_result($rs); 
       ?>
   	
     <table width="98%" height="25" border="0" align="center" cellspacing="5" >
<tr>
				<td><div align="left"><?php 
					//Display pagination
					echo $pages->display_pages();
					echo '<font class="Verdana">';
					echo '<p>&nbsp; </p>';
					echo "<p class=\"paginate\">Page: $pages->current_page of $pages->num_pages</p>\n";
					echo '</font>';  ?>
				</div></td>
        </tr>
			  <tr>
				<td></td>
			  </tr>
	  </table>
   
   <?php
   
	}   // free up the resources 
else
{
?><center>
<table width="800" cellspacing="0" class="textboxred">
  <tr> 
    <td><div align="center"><font color="#FF0000"><strong>There are currently 
          no record(s).</strong></font></div></td>
  </tr>
</table></center>
        <?php
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
