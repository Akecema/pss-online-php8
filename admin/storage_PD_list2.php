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

$url = "add_tbl_storage_PD.php";


$query2 = "SELECT * FROM user_detail WHERE username = '$username'";
$result2 = mysql_query($query2) or die (mysql_error());
$res = mysql_fetch_array($result2);
	
//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysql_query($query_setup);   //run the query.
$num_setup = mysql_num_rows($rs_setup);   //how many material are there?
$data_setup = mysql_fetch_array($rs_setup);
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
<?php  include "top_modal_menu.php";   ?>
<div id="header">
  <h1>&nbsp;</h1>
</div>
<!--close-Header-part--> 

<!--sidebar-menu-->
<?php include "left_admin_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
 <div id="content-header">
  <div id="breadcrumb"> <a href="index_admin.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Table Maintenance</a> <a href="#" class="current">Production Storage Location</a> </div>
  <h1>Table Maintenance</h1>
</div>

  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
    <!--  <div class="span12">-->
        <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li><a role="tab" href="add_tbl_storage_PD.php">Production Storage location </a></li>
              <li class="active"><a role="tab" href="storage_PD_list.php">Production Storage Location list</a></li>
            </ul>
          </div>
          </div>
          
          
      <form name="frmSearch" method="get" action="<?=$_SERVER['SCRIPT_NAME'];?>">
              <table class="table table-bordered table-striped">
                <tr>
                  <th>Production Storage Location
                    <input name="txtKeyword" type="text" id="txtKeyword" size="60">
                      <input type="submit" value="Search" class="btn btn-info">
                  </th>
                </tr>
              </table>
            </form>

          
<?php


$query8 = "SELECT COUNT(*) FROM storage_tbl WHERE (sloc_code LIKE '%".$_GET["txtKeyword"]."%') or (sloc_desc LIKE '%".$_GET["txtKeyword"]."%')";
$result8 = mysql_query($query8) or die(mysql_error());
$num_rows = mysql_fetch_row($result8); 

$pages = new Paginator;
$pages->items_total = $num_rows[0];
$pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
$pages->paginate();



$query = "SELECT * FROM storage_tbl  WHERE (sloc_code LIKE '%".$_GET["txtKeyword"]."%') or (sloc_desc LIKE '%".$_GET["txtKeyword"]."%') ORDER BY sloc_code ASC";
$rs = mysql_query($query);   //run the query.
//$num = mysql_num_rows($rs);   //how many material are there?

	
	if ($num_rows > 0) { 
	
	 echo '<div align="center">There are currently  '. $num_rows[0].' record(s).</div>';

?>
          
          
      <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Display Type of Wastage</h5>
          </div>
             
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                 <th>Storage Location</th>
                <th>Description</th>
                <th>Options</th>
                </tr>
              </thead>   
              <tbody>
		<?php
        
        $counter = 1;
        $no = 1;
        
        while ($row2 = mysql_fetch_array($rs))
        {
        
        ?>
           
            <tr class="gradeX">
            <td>&nbsp;<?php  echo $row2["sloc_code"]; ?></td>
            <td>&nbsp;<?php  echo $row2["sloc_desc"]; ?></td>
            <td>&nbsp; <a value="Edit" href="storage_PD_edit.php?code=<?php echo $row2["sloc_code"]; ?>&amp;&amp;TB_iframe=true&amp;height=300&amp;width=700" class="thickbox" target="_self"> <img src="../img/edit.gif" width="16" height="16" alt="Edit" />Edit</a></td>             
            </tr>
                
          <?php 
		  
		  $no ++;
		  
		  $counter++; // menambah counter
		  } ?>
                
              
              </tbody>
            </table>
            <?php
   mysql_free_result($rs); 
   
   ?> <?php
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
//mysql_close()
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
