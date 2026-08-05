<?php
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include '../include/config_mail.php';
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}


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
  <h4>Unlock Account</h4> 
<?php

//- First page:
$url = 'add_user.php';
$url2 = 'display_user.php';

$user_no = $_GET["user_no"];


$queryu = "SELECT * from user_detail where user_no = '$user_no'";
$resultu = mysqli_query($dbc, $queryu);   //run the query.
$row = mysqli_fetch_row($resultu);   //how many records are there?



//------------------------------end function --------------------------------
if (isset($_POST['submit']))
{
// create a function for escaping the data.
function escape_data ($data) {
global $dbc;   // need the connection.
if (ini_get('magic_quotes_gpc')) {
    $data = stripslashes($data);
	}
	return mysqli_real_escape_string($dbc, $data);
	}   // end function.
$message = NULL; // create an empty new variable.
	
$user_no = $_POST['user_no'];
$user_id = $_POST['user_id'];
$user_fullname = $_POST['user_fullname'];
$status_failed = $_POST['status_failed'];


// check for a status account
if (empty($_POST["status_failed"]) || ($_POST["status_failed"] == ""))
{ $status_failed = FALSE;
  $message.= '<p> You are required to select STATUS ACCOUNT!</p>';
  }
  else
  { $status_failed = escape_data($_POST["status_failed"]);
  }

 
  $user_id = escape_data($_POST["user_id"]);
  $user_fullname = escape_data($_POST["user_fullname"]);
   
 if ($status_failed)
{  

		  	  $query_search = "SELECT * FROM user_detail WHERE user_no = '$user_no'";
              $result_search = mysqli_query($dbc, $query_search);   //run the query.
              $num_search = mysqli_num_rows($result_search);   //how many suppliers are there?
			  $row_search = mysqli_fetch_array($result_search);
			  
			  if($num_search == 1) {
			  //echo $num_search; 
			    
				// make the update query
				
				$query_upd = "UPDATE user_detail SET status_failed = 'N', date_update= NOW(), user_update ='$username', date_failed = '' WHERE user_no = '$user_no'";
				$result_upd = mysqli_query($dbc, $query_upd); 
			
			 if(mysqli_affected_rows($dbc) == 1) { //If it ran ok
				  
			
			$to = $row_search["user_email"]; 
			$subject = "MRIN Online Activate Account."; 
			$headers = "From: " .$data_setup["email_account"]."\r\n"; 
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
			
			$mess2 ="<p>Dear ".$row_search["user_fullname"]."</br>";
			
			$mess2 .="<p>The user account is activate as below; </p>";
			$mess = '<html><body>';
			$mess .= '<table cellpadding="5">';
			$mess .= "<tr><td><strong>Username :</strong> </td><td>" .$row_search["username"].  "</td></tr>";		
			$mess .= "<tr><td><strong>Staff ID :</strong> </td><td>" .$row_search["staff_ID"]. "</td></tr>";
		    $mess .= "</table>";	
			$mess .= "<br>"; 
		
			$mess .="<p>Please use the following link to view:</br>";
			$mess .="<a href='".$data_setup["urls_system"]."'>" .$data_setup["urls_system"]."</a> </p>";
			$mess .= "<p><font color='black'>This is a system generated email. Please DO NOT reply. </font></p>";
			$mess .= "<p>&nbsp;</p>";
			$mess .= "</body></html>";
			
			mail($to, $subject, $mess2.$mess, $headers);
			
				//-----------------delete clear table failed_login-----------
			$query_failed_del = "DELETE FROM failed_login WHERE staff_ID = '".$row_search["staff_ID"]."'";
		    $result_failed_del = mysqli_query($dbc, $query_failed_del); 			
			
			  }
			
			  }
					
echo "<script>";
echo "alert('Account is successfully activate.');";
echo "parent.location.reload(1);";
echo "parent.$.fancybox.close();";
echo "</script>"; 
exit(); 

} else { echo 'Cannot update record'; 
}
//print the message if there is one.
	  

//---------------------------function message------------------------------ 
if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}
} 
 ?> 

          <form name="form1" method="post" action="unlock_pass_account.php?user_no=<?php echo $user_no; ?>" >
             <table class="table table-bordered">
               <tr>
                 <td>Staff ID </td>
                 <td>:</td>
                 <td>
                   <input name="user_id" type="text" id="user_id" size="20" maxlength="20" readonly value="<?php echo $row[2]; ?>" />
                 </td>
               </tr>
                 <tr>
                 <td height="25">Name </td>
                 <td height="25">:</td>
                 <td height="25">
         <input name="user_fullname" type="text" class="span11" id="user_fullname" size="55" maxlength="100" readonly value="<?php echo $row[5]; ?>" />
                </td>
               </tr>
               <?php
	
	  if($row[20] == "Y")
	  {
	     $sts = "LOCK";
		 }
		 else{
		 $sts = "UNLOCK";
		 }
      ?>
               <tr>
                 <td>Status Account *</td>
                 <td>:</td>
                 <td><select name="status_failed"  class="span1">
	  <option value ="<?php  echo $row[20]; ?>" ><?php  echo $sts; ?></option>
	  <option value="Y">LOCK</option>
	  <option value="N">UNLOCK</option>
	      </select>
                  </td>
               </tr>
                <tr>
                 <td>Date Lock Access</td>
                 <td>:</td>
                 <td><?php  echo $row[21]; ?></td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
               </tr>
              
               <tr>
                 <td>* Compulsory field</td>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
               </tr>
            </table>
          
           
          <input name="submit" type="submit"  id="submit" value="UPDATE" class="btn btn-success">
          <input name="Reset" type="reset" id="Reset" value="CLEAR" class="btn btn-danger">
          <input type="hidden" name="user_no" id="user_no" value="<?php echo $row[0]; ?>">
     
           
          </form>
    </div>
<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part--> 
</body>
</html>
