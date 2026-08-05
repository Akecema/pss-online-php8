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
$url = "add_vendor_account.php";

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

$vendor_code = $_GET["vendor_code"];

$queryu = "SELECT *, DATE_FORMAT(date_create,'%d-%m-%Y') AS R, DATE_FORMAT(date_update,'%d-%m-%Y') AS R2 FROM vendor_detail WHERE vendor_code = '".$vendor_code."'";
$resultu = mysql_query($queryu);   //run the query.
$row = mysql_fetch_array($resultu);   //how many records are there?


//--------------------function escape data from form ------------------------
function escape_data ($data) {
global $dbc;   // need the connection.
if (ini_get('magic_quotes_gpc')) 
{
    $data = stripslashes($data);
	}
	return mysql_real_escape_string($data,$dbc);
	}   // end function.
$message = NULL; // create an empty new variable.
//------------------------------end function --------------------------------

    //----user created -----

    $query_create = "SELECT * FROM user_detail WHERE username = '".$row[13]."'";
    $result_create = mysql_query($query_create) or die (mysql_error());
    $data_create = mysql_fetch_array($result_create);
	
	//----user updated -----

    $query_update = "SELECT * FROM user_detail WHERE username = '".$row[15]."'";
    $result_update = mysql_query($query_update) or die (mysql_error());
    $data_update = mysql_fetch_array($result_update);
	
	if($row[17] == "Y")
	{
		$sta_acc = "Active";
		
	}else
	{
		$sta_acc = "In Active";
	}




   if($row[18] == "Y")
	{
		$sta_sub = "YES";
		
	}else
	{
		$sta_sub = "NO";
	}

 ?>
<body>
<div id="content">
  <h4>Display Vendor</h4>     
   <!-- End Box Head -->
         <form name="form1" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
             <table class="table table-bordered">
               <tr>
                 <td width="30%" height="25">Vendor Code </td>
                 <td width="3%" height="25">:</td>
                 <td width="67%" height="25"><?php echo $row[0]; ?></td>
               </tr>
               <tr>
                 <td height="25">Vendor Name </td>
                 <td height="25">:</td>
                 <td height="25"><b><font color="blue"><?php echo $row[1]; ?></font></b></td>
               </tr> 
               <tr>
                 <td height="25">Search Term</td>
                 <td height="25">:</td>
                 <td height="25"><?php	echo $row[8];	?></td>
               </tr>
                 <tr>
                 <td height="25">Address No. 1</td>
                 <td height="25">:</td>
                 <td height="25"><?php echo $row[2]; ?> </td> 
               </tr>
               <tr>
                 <td height="25">Address No. 2</td>
                 <td height="25">:</td>
                 <td height="25"><?php echo $row[3];  ?></td>
               </tr>
      
               <tr>
                 <td height="25">Postcode</td>
                 <td height="25">:</td>
                 <td height="25"><?php	echo $row[4];  ?></td>
               </tr>
               <tr>
                 <td height="25">City</td>
                 <td height="25">:</td>
                 <td height="25"><?php	echo $row[5];	?></td>
               </tr>
                 <tr>
                 <td height="25">Region</td>
                 <td height="25">:</td>
                 <td height="25"><?php	echo $row[6];	?></td>
               </tr>
                 <tr>
                 <td height="25">Country</td>
                 <td height="25">:</td>
                 <td height="25"><?php	echo $row[7];	?></td>
               </tr>
                <tr>
                 <td height="25">Phone</td>
                 <td height="25">:</td>
                 <td height="25"><?php	echo $row[9];	?></td>
               </tr>
                 <tr>
                 <td height="25">Fax</td>
                 <td height="25">:</td>
                 <td height="25"><?php	echo $row[10];	?></td>
               </tr>
                <tr>
                 <td height="25">Payment Method</td>
                 <td height="25">:</td>
                 <td height="25"><?php	echo $row[11];	?></td>
               </tr>
                <tr>
                 <td height="25">Term Payment</td>
                 <td height="25">:</td>
                 <td height="25"><?php	echo $row[12];	?></td>
               </tr>
                <tr>
                 <td height="25">User Created</td>
                 <td height="25">:</td>
                 <td height="25"><?php	echo $data_create["user_fullname"];	?></td>
               </tr>
                <tr>
                 <td height="25">Date Created</td>
                 <td height="25">:</td>
                 <td height="25"><?php	echo $row["R"];	?></td>
               </tr>
                <tr>
                 <td height="25">User Updated</td>
                 <td height="25">:</td>
                 <td height="25"><?php	echo $data_update["user_fullname"];	?></td>
               </tr>
                <tr>
                 <td height="25">Date Updated</td>
                 <td height="25">:</td>
                 <td height="25"><?php	echo $row["R2"];	?></td>
               </tr>
                 <tr>
                 <td height="25">Status Account <br>(Y = Active, N = In Active)</td>
                 <td height="25">:</td>
                 <td height="25"><?php	echo $row[17];	?> = <?php	echo $sta_acc;	?></td>
               </tr>
                <tr>
                 <td height="25">Status Subcont <br> (Y = YES, N = NO)</td>
                 <td height="25">:</td>
                 <td height="25"><?php	echo $row[18];	?> = <?php	echo $sta_sub;	?></td>
               </tr>
               <tr>
                 <td height="25">&nbsp;</td>
                 <td height="25">&nbsp;</td>
                 <td height="25">&nbsp;</td>
               </tr>
            </table>
           </form>
           </div>
   <!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part-->     

</body>
</html>
