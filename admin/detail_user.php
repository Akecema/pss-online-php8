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
$url = "detail_user.php";

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


$user_no = $_GET["user_no"];

$queryu = "SELECT * from user_detail where user_no = '$user_no'";
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
<body>


<div id="content">
  <h4>Display Account</h4>     
          <!-- End Box Head -->
         <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
         
             <table class="table table-bordered">
               <tr>
                 <td width="26%" height="25">Vendor ID </td>
                 <td width="3%" height="25">:</td>
                 <td width="71%" height="25"><?php echo $row[1]; ?></td>
               </tr>
               <tr>
                 <td height="25">Staff ID </td>
                 <td height="25">:</td>
                 <td height="25"><b><font color="blue"><?php echo $row[2]; ?></font></b></td>
               </tr>
                 <tr>
                 <td height="25">Name</td>
                 <td height="25">:</td>
                 <td height="25"><?php echo $row[5]; ?> </td> 
               </tr>
               <tr>
                 <td height="25">Company's Name</td>
                 <td height="25">:</td>
                 <td height="25"><?php		
 	
  //Retrieve and display the available types
  $query3 = "SELECT * FROM company WHERE comp_code = '$row[8]'";
  $result3 = mysqli_query($dbc, $query3);
  $row3 = mysqli_fetch_array($result3);
  
	    echo $row3["comp_name"];
		

	?></td>
               </tr>
      
               <tr>
                 <td height="25">Department</td>
                 <td height="25">:</td>
                 <td height="25"><?php		
   //Retrieve and display the available types
  $query2 ="SELECT * from department WHERE id_dept = '$row[6]'";
  $result2 = mysqli_query($dbc, $query2);
   $row2 = mysqli_fetch_array($result2);
	    
		echo $row2["dept_name"]; 
	
	?></td>
               </tr>
               <tr>
                 <td height="25">Designation</td>
                 <td height="25">:</td>
                 <td height="25"><?php		

  //Retrieve and display the available types
  $query2b = "SELECT * FROM designation WHERE id_design = '$row[7]'";
  $result2b = mysqli_query($dbc, $query2b);
   $row2b =mysqli_fetch_array($result2b);
   
  echo $row2b[1];

	?></td>
               </tr>
               <tr>
                 <td height="25">Telephone No. 1</td>
                 <td height="25">:</td>
                 <td height="25"><?php echo $row[9]; ?></td>
               </tr>
               <tr>
                 <td height="25">Telephone No. 2</td>
                 <td height="25">:</td>
                 <td height="25"><?php echo $row[10]; ?></td>
               </tr>
               <tr>
                 <td height="25">Fax No</td>
                 <td height="25">:</td>
                 <td height="25"><?php echo $row[11]; ?> </td>
               </tr>
               <tr>
                 <td height="25">E-mail</td>
                 <td height="25">:</td>
                 <td height="25"><?php echo $row[12]; ?></td>
               </tr>
               <tr>
                 <td height="25">Level</td>
                 <td height="25">:</td>
                 <td height="25"><?php		
 
  //Retrieve and display the available types
  $query4 = "SELECT * FROM level_detail WHERE status_level = 'Y' AND id_level = '$row[16]'";
  $result4 = mysqli_query($dbc, $query4);
  $row4 =mysqli_fetch_array($result4);
	    echo $row4["desc_level"];
	
	?></td>
               </tr>
               <tr>
                 <td height="25">Status User</td>
                 <td height="25">:</td>
                 <td height="25">
	 <?php
	 
	  if($row[15] == "AC")
	  {
	     $sts = "Active";
		 }
		 else{
		 $sts = "Inactive";
		 }
	 
	 echo $sts; 
	  
      ?></td>
               </tr>
               <tr>
                 <td height="25">Date Created</td>
                 <td height="25">:</td>
                 <td height="25"><b><font color="blue">
                   <?php  echo $row[14]; ?>
                 </font></b></td>
               </tr>
              
               <tr>
                 <td height="25">&nbsp;</td>
                 <td height="25">&nbsp;</td>
                 <td height="25">&nbsp;</td>
               </tr>
            </table>
    
          </form>
           <br>
<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part--> 
        

</div>

</body>
</html>
