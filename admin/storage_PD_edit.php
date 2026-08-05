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

$url = 'storage_PD_edit.php';

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


?>

<body>
<div id="content">
  <h4>&nbsp;</h4>
  <h4>Edit Storage Location</h4> 

<?php

$cd = $_GET['code'];

$queryu = "SELECT * FROM storage_tbl WHERE sloc_code = '$cd' ";
$resultu = mysqli_query($dbc, $queryu);   //run the query.
$row = mysqli_fetch_row($resultu);   //how many records are there?


if (isset($_POST['submit']))
{

	
	$pd_dc = $_POST['sloc_desc'];
	

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
	
	
	//$size = count($_POST["id_dtl"]) + 1;
	
	$i = 1;

	//------------------------------end function --------------------------------

	
	// check for a status
	if (empty($_POST['sloc_desc'])) 
	{ 
		$pd_dc = FALSE;
		$message.= '<p> You are required to enter storage location description!</p>';
	}
	else
	{ 
		$pd_dc = escape_data($_POST['sloc_desc']);
	}

   
	if($pd_dc) //everything ok
	{     	
	
		$query_search = "SELECT * FROM storage_tbl WHERE sloc_code = '".$cd."'";
		$result_search = mysqli_query($dbc, $query_search);   //run the query.
		$num_search = mysqli_num_rows($result_search);   //how many suppliers are there?
		
		if($num_search == 1) {
		//echo $num_search; 
		$row = mysqli_fetch_array($result_search, MYSQLI_NUM);
		// make the update query
		
		$query_upd = "UPDATE storage_tbl SET sloc_desc = '".$_POST['sloc_desc']."' WHERE sloc_code = '".$cd."' "; 
		$result_upd = mysqli_query($dbc, $query_upd); 
		
						
		if($result_upd)
		{
			echo "<script>";
			echo "alert('Storage location is successfully updated.');";
			echo "parent.location.reload(1);";
			echo "parent.$.fancybox.close();";
			echo "</script>"; 
			exit(); //quit the script						
		} 
		else 
		{ 
			echo 'Cannot update record'; 
		}
	}
	//print the message if there is one.
		  
} 

//---------------------------function message------------------------------ 
if (isset($message))
{ 
	echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}
} 
 ?> 

     
          <!-- End Box Head -->
          <form name="form1" method="post" action="storage_PD_edit.php?code=<?php echo $cd; ?>" >
              <table class="table table-bordered">
               <tr>
                 <td>Storage Locaton Code <font color="#FF0000"> * </font></td>
                 <td>:</td>
                 <td>
                   <input name="sloc_code" type="text" id="sloc_code" size="20" maxlength="8" readonly value="<?php echo $row[0]; ?>" /></td>
               </tr>
               <tr>
                 <td>Description <font color="#FF0000"> * </font></td>
                 <td>:</td>
                 <td>
                   <input name="sloc_desc" type="text" class="span11" id="sloc_desc" size="55" maxlength="100" value="<?php echo $row[1]; ?>" />
               </td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
               </tr>
               <tr>
                 <td><font color="#FF0000"> * </font> Compulsory field </td>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
               </tr>
            </table>
                  
            <!-- Form Buttons -->

           <input name="submit" type="submit" class="btn btn-success" id="submit" value="UPDATE">
           <input name="Reset" type="reset" id="Reset" value="CLEAR" class="btn btn-danger">
        
            <!-- End Form Buttons -->
          </form>
        
</div>
<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part-->
 </body>
</html>
