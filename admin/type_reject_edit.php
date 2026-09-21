<?php

/**
 * admin/type_reject_edit.php
 * Part of: Admin module
 * Filename suggests: type reject edit
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, type_reject_detail, record.
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

    $url = 'type_reject_edit.php';

    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
    $result2 = mysqli_query($dbc, $query2) or die(db_fail($dbc));
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
  <h4>Edit Type of Reject</h4> 

    <?php

$id_type = $_GET['id_type'];

$queryu = "SELECT * FROM type_reject_detail WHERE id_type = '".db_esc($dbc, $id_type)."'";
$resultu = mysqli_query($dbc, $queryu);   //run the query.
$row = mysqli_fetch_row($resultu);   //how many records are there?


if (isset($_POST['submit']))
{

$id_type = $_POST['id_type'];
$status_type = $_POST['status_type'];
$type_desc = $_POST['type_desc'];

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
// check for a type_reject_desc.
if (empty($_POST['type_desc']))
{ $type_desc = FALSE;
  $message.= '<p>You are required to enter Type Reject Description.!</p>';
  }else
  { $type_desc = escape_data($_POST['type_desc']);
  }
  
// check for a status
if (empty($_POST['status_type'])) 
{ 
  $status_type = FALSE;
  $message.= '<p> You are required to select Status Type!</p>';
  }
    else
  { $status_type = escape_data($_POST['status_type']);
  }

   
 if($type_desc && $status_type) //everything ok
{     	
		  	  $query_search = "SELECT * FROM type_reject_detail WHERE id_type = '".db_esc($dbc, $id_type)."'";
              $result_search = mysqli_query($dbc, $query_search);   //run the query.
              $num_search = mysqli_num_rows($result_search);   //how many suppliers are there?
			  
			  if($num_search == 1) {
			  //echo $num_search; 
			    $row = mysqli_fetch_array($result_search, MYSQLI_NUM);
				// make the update query
	
		$query_upd = "UPDATE type_reject_detail SET type_desc = '".db_esc($dbc, $type_desc)."', status_type = '".db_esc($dbc, $status_type)."' WHERE id_type = '".db_esc($dbc, $id_type)."'"; 
		$result_upd = mysqli_query($dbc, $query_upd); 
								
			if($result_upd)
			{
			 echo "<script>";
		     echo "alert('Type Reject is successfully update.');";
			 echo "parent.location.reload(1);";
             echo "parent.$.fancybox.close();";
		     echo "</script>"; 
		     exit(); //quit the script
			
							
  } else { echo 'Cannot update record'; 
  }
}
//print the message if there is one.
	  
} 
//---------------------------function message------------------------------ 
if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}
} 
 ?> 

     
          <!-- End Box Head -->
          <form name="form1" method="post" action="type_reject_edit.php?id_type=<?php echo $id_type; ?>" >
              <table class="table table-bordered">
               <tr>
                 <td>ID Reason *</td>
                 <td>:</td>
                 <td>
                   <input name="id_type" type="text" id="id_type" size="20" maxlength="8" readonly value="<?php echo $row[0]; ?>" /></td>
               </tr>
               <tr>
                 <td>Reason Reject Desc *</td>
                 <td>:</td>
                 <td>
                   <input name="type_desc" type="text" class="span11" id="type_desc" size="55" maxlength="100" value="<?php echo $row[1]; ?>" />
               </td>
               </tr>
                        <tr>
                 <td>Status *</td>
                 <td>:</td>
                 <td>
         <select name="status_type"  class="textbox">
      <option value="NULL" placeholder="Select Status"> -- Select Status --</option>
	  <option value="Y" class="title" <?php if($row[2] == 'Y') echo "selected"; ?>>Y - Active</option>
	  <option value="N" class="title" <?php if($row[2] == 'N') echo "selected"; ?>>N - Inactive</option>
	      </select></td>
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
