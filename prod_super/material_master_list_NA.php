<?php
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "material_master_list.php";

$query2 = "SELECT * FROM user_detail WHERE username = '$username'";
$result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
$res = mysqli_fetch_array($result2);

	
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
  <div id="breadcrumb"> <a href="index_production_super.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Material List</a> <a href="#" class="current">Material Master</a> </div>
  <h1>Material Master</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
    <!--  <div class="span12">-->
        <!--<div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li><a  role="tab" href="add_tbl_work_center.php">Add Material Master</a></li>
              <li class="active"><a role="tab" href="material_master_list.php">Display Material Master</a></li>
            </ul>
          </div>
          </div>-->
          
          
     <form name="frmSearch" method="get" action="material_master_list2_NA.php">
              <table class="table table-bordered table-striped">
                <tr>
                  <th>Material No./BOM :
                      <input name="txtKeyword" type="text" id="txtKeyword" size="60">
                      <input type="submit" value="Search" class="btn btn-info">
                  </th>
                </tr>
              </table>
            </form>

          
<?php

$query8 = "SELECT COUNT(*) FROM mat_master_header WHERE status_BOM = 'N' ";
$result8 = mysqli_query($dbc, $query8) or die(mysqli_error($dbc));
$num_rows = mysqli_fetch_row($result8); 

$pages = new Paginator;
$pages->items_total = $num_rows[0];
$pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
$pages->paginate();


$query = "SELECT * FROM mat_master_header WHERE status_BOM = 'N' ORDER BY material_no ASC";
$rs = mysqli_query($dbc, $query);   //run the query.
//$num = mysqli_num_rows($rs);   //how many material are there?

		
if ($num_rows > 0) { 

echo '<div align="center">There are currently  '. $num_rows[0].' record(s).</div>';

?>
          

   
      <div class="widget-box">
      
      	  <div class="widget-title">
            <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li><a  role="tab" href="material_master_list.php">Material Master</a></li>
              <li class="active"><a  role="tab" href="material_master_list_NA.php">Material Master (Non Active)</a></li>
            </ul>
          </div>
          
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display Material Master</h5>
          </div>
          
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                <th>Item</th>
                <th>Material No.</th>
                <th>Material Description</th>
                <th>Plant</th>
                <th>UoM</th>
                <th>Options</th>
             <!--   <th>Options</th>-->
                </tr>
              </thead>   
              <tbody>
           <?php
   
  		  
   $counter = 1;
   $no = 1;
    $i = 1;
   
   while ($row2 = mysqli_fetch_array($rs))
   {
	
		   $no = sprintf('%03d', $no);
	 
      ?>
           
                <tr class="gradeX">
                <td><?php //echo $no; ?><?php  echo $row2["id_hdr"]; ?></td>
                <td>&nbsp;<?php  echo $row2["material_no"]; ?></td>
                <td><?php  echo $row2["material_desc"]; ?></td>
                <td><?php echo $row2["plant"]; ?></td>
                <td><?php echo $row2["BUn"]; ?></td>
                <td><a value="Details" href="material_master_view.php?id_hdr=<?php echo $row2["id_hdr"]; ?>&amp;&amp;TB_iframe=true&amp;height=500&amp;width=800" class="thickbox" target="_self"><img src="../img/icon_view.jpg" width="16" height="16" alt="View" />View</a></td>
              <!--  <td>&nbsp; <a value="Edit" href="material_master_edit_NA.php?id_hdr=<?php echo $row2["id_hdr"]; ?>&amp;&amp;TB_iframe=true&amp;height=700&amp;width=700" class="thickbox" target="_self"> <img src="../img/edit.gif" width="16" height="16" alt="Edit" />Edit</a></td>-->
                </tr>
       
            <input name="id_hdr[<?php echo $i; ?>]" type="hidden" value="<?php echo $row2["id_hdr"]; ?>">
          <?php 		 
		 $i++;
		 $counter++; // menambah counter 
		 $no ++;
		 
		  } 
		 ?>
              </tbody>
            </table>
            <?php
   mysqli_free_result($rs); 
   
   ?> <?php
	}   // free up the resources 
else
{
?><center>
<table width="800" cellspacing="0" class="textboxred">
  <tr> 
    <td><div align="center"><font color="#FF0000"><strong>There are currently no material request.</strong></font></div></td>
  </tr>
</table></center>
        <?php
		   } 
//mysqli_close($dbc)
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
