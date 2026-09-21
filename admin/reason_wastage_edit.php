<?php

/**
 * admin/reason_wastage_edit.php
 * Part of: Admin module
 * Filename suggests: reason wastage edit
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, reason_wastage, record.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
session_start();
$username = $_SESSION['username'] ?? '';
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

    $url = 'reason_wastage_edit.php';

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
<title><?php echo h($data_setup["title_desc"]); ?></title>
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
  <h4>Edit Reason of Wastage</h4> 

    <?php

$id_reason_wastage = $_GET['id_reason_wastage'];

$queryu = "SELECT * FROM reason_wastage WHERE id_reason_wastage = '".db_esc($dbc, $id_reason_wastage)."'";
$resultu = mysqli_query($dbc, $queryu);   //run the query.
$row = mysqli_fetch_row($resultu);   //how many records are there?


if (isset($_POST['submit']))
{

$id_reason_wastage = $_POST['id_reason_wastage'];
$status_reason_wastage = $_POST['status_reason_wastage'];
$reason_wastage_desc = $_POST['reason_wastage_desc'];

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
// check for a reason_wastage_desc.
if (empty($_POST['reason_wastage_desc']))
{ $reason_wastage_desc = FALSE;
  $message.= '<p>You are required to enter Reason Wastage Description.!</p>';
  }else
  { $reason_wastage_desc = escape_data($_POST['reason_wastage_desc']);
  }
  
// check for a status
if (empty($_POST['status_reason_wastage'])) 
{ 
  $status_reason_wastage = FALSE;
  $message.= '<p> You are required to select Status Wastage!</p>';
  }
    else
  { $status_reason_wastage = escape_data($_POST['status_reason_wastage']);
  }

   
 if($reason_wastage_desc && $status_reason_wastage) //everything ok
{     	
		  	  $query_search = "SELECT * FROM reason_wastage WHERE id_reason_wastage = '".db_esc($dbc, $id_reason_wastage)."'";
              $result_search = mysqli_query($dbc, $query_search);   //run the query.
              $num_search = mysqli_num_rows($result_search);   //how many suppliers are there?
			  
			  if($num_search == 1) {
			  //echo $num_search; 
			    $row = mysqli_fetch_array($result_search, MYSQLI_NUM);
				// make the update query
	
		$query_upd = "UPDATE reason_wastage SET reason_wastage_desc = '".db_esc($dbc, $reason_wastage_desc)."', status_reason_wastage = '".db_esc($dbc, $status_reason_wastage)."' WHERE id_reason_wastage = '".db_esc($dbc, $id_reason_wastage)."'"; 
		$result_upd = mysqli_query($dbc, $query_upd); 
								
			if($result_upd)
			{
			 echo "<script>";
		     echo "alert('Reason Wastage is successfully update.');";
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
          <form name="form1" method="post" action="reason_wastage_edit.php?id_reason_wastage=<?php echo $id_reason_wastage; ?>" >
              <table class="table table-bordered">
               <tr>
                 <td>ID Reason *</td>
                 <td>:</td>
                 <td>
                   <input name="id_reason_wastage" type="text" id="id_reason_wastage" size="20" maxlength="8" readonly value="<?php echo h($row[0]); ?>" /></td>
               </tr>
               <tr>
                 <td>Reason Wastage Desc *</td>
                 <td>:</td>
                 <td>
                   <input name="reason_wastage_desc" type="text" class="span11" id="reason_wastage_desc" size="55" maxlength="100" value="<?php echo h($row[1]); ?>" />
               </td>
               </tr>
                        <tr>
                 <td>Status *</td>
                 <td>:</td>
                 <td>
         <select name="status_reason_wastage"  class="textbox">
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
