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

$url = "TP_manual_upload.php";


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
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

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
    margin-top: 1.0cm;
	margin-bottom: 1.0cm;
	margin-left:0.5cm;
	margin-right:0.5cm;
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
<?php include "left_ppc_menu.php";  ?>
<!--sidebar-menu-->
</div>
<div id="content">
 <div id="content-header" class="noprint">
  <div id="breadcrumb"> <a href="index_ppc.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a><a href="#" class="tip-bottom">Uploaded MIGO TR </a><a href="#" class="current">Uploaded TP</a></div>
  <h1>Uploaded MIGO TR</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
         <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li class="active"><a  role="tab" href="TP_manual_upload.php">Uploaded TP</a></li>
              <li><a role="tab" href="ST_manual_upload.php">Uploaded ST</a></li>
            </ul>
          </div>
          </div>
      
          
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
              <td>
			   <?php
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
					?>
			  
			</td>
            </tr>
           <tr>
              <th>Material No.  :</th>
              <td><select name="material_no" id="material_no">
                  <option value="NULL" placeholder="Select Material No."> -- Select Material No. --</option>
                  <?php
	    $query9 = "SELECT * FROM mat_master_detail WHERE mat_type = 'Z100' GROUP BY bill_component ORDER BY bill_component ASC";
        $result9 = mysqli_query($dbc, $query9);
  
                   while($row9=mysqli_fetch_array($result9)) 
			      {
				   ?>
                  <option value="<?php echo $row9["bill_component"]; ?>" <?php if($row9["bill_component"] == $_GET["material_no"]) echo "selected"; ?>> <?php echo $row9["bill_component"]; ?></option>
                  <?php
                  }
				?>
              </select></td>
              <th>&nbsp;</th>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <th>&nbsp;</th>
              <td>&nbsp;</td>
              <th>&nbsp;</th>
              <td><input name="Submit" type="submit" value="Search" class="btn btn-info"></td>
            </tr>
           </table>
        </form>
              
           
      <?php
 
	       
		    $material_no = $_GET["material_no"];
			$dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			
		    //convert material no kpd id_hdr
			
			$query_convert = "SELECT * FROM `mat_master_header` as MH WHERE MH.material_no = '".$_GET["material_no"]."'";
			$result_convert = mysqli_query($dbc, $query_convert); 
			$row_convert = mysqli_fetch_array($result_convert);
		
				
		    $where_sql = '';
		 
		 // 1. material_no
                if($material_no == "NULL") {
                     $wheresql_01 = ''; }
                else {
                      $wheresql_01 = " AND MR.material_no = '$material_no'"; }
		  // 2. dateF
                if ($dateF == "0000-00-00" ){
                    $wheresql_02 = ''; }
                else {
                    $wheresql_02 = " AND (MR.date_posting >= '$dateF')"; }      
                                                
		 // 3. dateT
                if ($dateT == "0000-00-00" ){
                    $wheresql_03 = ''; }
                else {
                    $wheresql_03 = " AND (MR.date_posting <= '$dateT')"; }          
                                
       
	   
			$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03;

				
		
	//********** END CONDITION **************
 

								 
   $query8 = "SELECT *,DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R FROM ftp_detail AS MR WHERE MR.mrin_no != '' " .$where_sql;
   $result8 = mysqli_query($dbc, $query8) or die(mysqli_error($dbc));
     $num_8 = mysqli_fetch_row($result8);
     $num_rows = mysqli_num_rows($result8);
   
   $pages = new Paginator;
   $pages->items_total = $num_rows;
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT *,DATE_FORMAT(MR.transfer_date,'%d-%m-%Y') AS R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R2 FROM ftp_detail AS MR WHERE  MR.mrin_no != '' ".$where_sql;
$rs = mysqli_query($dbc, $query);   //run the query.
$num = mysqli_num_rows($rs);   //how many material are there?

	
	 if($num > 0) {
	 
	 echo '<div align="center">There are currently  '. $num.' record(s).</div>';
	
?>
<table class="table">
<tr>
    <td width="1%">&nbsp;</td> 
    <td width="85%"> <div class="small-nav"></div></td> 
      <td width="7%"><a href="report_TP_manual_uploadProc.php?date1=<?php echo $dateF; ?>&&date2=<?php echo $dateT; ?>&&material_no=<?php echo $material_no; ?>"><img src="../img/dload_excel.jpg" width="48" height="48" title="Download" /></a></td>
     <td width="7%"><img src="../img/print2.jpg" width="48" height="48" onClick="window.print()" title="Print"/></td>
   
  </tr>
</table>

 <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display Uploaded TP</h5>
          </div>
             
          <div class="widget-content nopadding">
          
    <table class="table table-bordered data-table">
     <thead>
       <tr>
          <th width="100">MRIN No.</th>
          <th width="151">Material No.</th>
          <th width="115">Prod. Order</th>
          <th width="90">Mat. Doc</th>
          <th width="65">Mat. Doc Item</th>
          <th width="50">Mat. Doc Year</th>
          <th width="89">Transfer <br />Date</th>
          <th width="80">Posting Date</th>
          <th width="60">Posting Time </th>
          <th>Filename Transfer Doc.</th>
</tr>
         </thead><tbody>
          
       
<?php
		  
   $counter = 1;
   $no = 1;
   $i = 1;
   $variance_qty = 0;
    $bq = 0;
   $rq = 0;
   
   while ($row2 = mysqli_fetch_array($rs))
   {
		
 	?>        
       
  <tr>
    <td width="99" height="28">&nbsp;<font color="#003399"><?php echo $row2["mrin_no"]; ?></font></td>
    <td width="151">&nbsp;<?php echo $row2["material_no"]; ?></td>
    <td width="118"><?php echo $row2["prod_order"]; ?></td>
    <td width="91" height="28"><div align="center"><?php echo $row2["mat_doc"]; ?></div></td>
    <td width="65"><div align="center"><?php echo $row2["mat_doc_item"]; ?></div></td>
    <td width="50"><div align="center"><?php echo $row2["mat_doc_year"]; ?></div></td>
    <td width="88"><div align="center"><?php echo $row2["R"]; ?></div>
    <td width="80"><div align="center"><?php echo $row2["R2"]; ?></div>
    <td width="60"><div align="center"><?php echo $row2["time_posting"]; ?></div>	</td>
    <td><font color="#003399"><?php echo $row2["file_name"]; ?></font></td>
  </tr>
  
  <?php 
		 
		 $no ++;
		  
		  $counter++; // menambah counter 
		   
		   //}// end if
		
		    
		  } ?></tbody></table>  
  </center>
 
            
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
// mysqli_close($dbc);
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
