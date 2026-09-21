<?php

/**
 * admin/con_detail_view.php
 * Part of: Admin module
 * Filename suggests: con detail view
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: user_detail, sys_setup_maintain, consumable_detail.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, footer.php.
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
require_role($dbc, 1);
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}


    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
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
<meta charset="utf-8" />
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
<link rel="stylesheet" href="../css/style.css" type="text/css" media="all" />
<link href="../css/ddtabmenu.css" rel="stylesheet" type="text/css" />
<style type="text/css">
body,td,th {
	font-family: "Open Sans", sans-serif;
}
body {
	background-color: #EEEEEE;
}
</style>
</head>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();
}
//  End -->
</script>
<?php

function encode($ss,$ntime){
    for($i=0;$i<$ntime;$i++){
        $ss=base64_encode($ss);
    }
return $ss;
}


function decode($ss,$ntime){
    for($i=0;$i<$ntime;$i++){
        $ss=base64_decode($ss);
    }
return $ss;
}


//- First page:
$url = 'con_detail_table.php';


?>
<body>
<div id="content">
  <h4>Display Consumable</h4>     

  <?php

$id_con = $_GET["id_con"];

$queryu = "SELECT * FROM consumable_detail WHERE id_con = '".db_esc($dbc, $id_con)."'";
$resultu = mysqli_query($dbc, $queryu);   //run the query.
$row = mysqli_fetch_row($resultu);   //how many records are there?

//--------------------function escape data from form ------------------------
function escape_data ($data) {
global $dbc;   // need the connection.
if (ini_get('magic_quotes_gpc')) 
{
    $data = stripslashes($data);
	}
	return mysqli_real_escape_string($dbc, $data);
	}   // end function.
$message = NULL; // create an empty new variable.
//------------------------------end function --------------------------------

 ?>
          
               <table class="table table-bordered table-condensed">
               <tr>
                 <td width="37%" height="25">Material No.</td>
                 <td width="2%" height="25">:</td>
                 <td width="61%" height="25"><b><font color="blue"><?php echo $row[1]; ?></font></b></td>
               </tr>
               <tr>
                 <td height="25">Material  Description </td>
                 <td height="25">:</td>
                 <td height="25"><b><font color="blue"><?php echo $row[2]; ?></font></b></td>
               </tr>
                 <tr>
                 <td height="25">Plant Code</td>
                 <td height="25">:</td>
                 <td height="25"><?php echo $row[5]; ?> </td> 
               </tr>
               <tr>
                 <td height="25">Cost Center</td>
                 <td height="25">:</td>
                 <td height="25"><?php echo $row[4];  ?></td>
               </tr>
               <tr>
                 <td height="25">BUn</td>
                 <td height="25">:</td>
                 <td height="25"><?php	echo $row[3];	?></td>
               </tr>
               <tr>
                 <td height="25">Status [ Y = Active ; N = Inactive]</td>
                 <td height="25">:</td>
                 <td height="25"><?php	echo $row[6];	?></td>
               </tr>
               <tr>
                 <td height="25">&nbsp;</td>
                 <td height="25">&nbsp;</td>
                 <td height="25">&nbsp;</td>
               </tr>
            </table>
           </div>
<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part--> 
</body>
</html>
