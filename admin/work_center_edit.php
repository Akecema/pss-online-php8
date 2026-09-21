<?php

/**
 * admin/work_center_edit.php
 * Part of: Admin module
 * Filename suggests: work center edit
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, work_center_detail, record, factory_detail.
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

    $url = 'work_center_edit.php';

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
  <h4>Edit Work Center</h4> 

    <?php

$id_work = $_GET['id_work'];

$queryu = "SELECT * FROM work_center_detail WHERE id_work = '".db_esc($dbc, $id_work)."'";
$resultu = mysqli_query($dbc, $queryu);   //run the query.
$row = mysqli_fetch_row($resultu);   //how many records are there?


if (isset($_POST['submit']))
{

$id_work = $_POST['id_work'];
$id_hdr = $_POST['id_hdr'];
$wc_desc = $_POST['wc_desc'];
$cost_center = $_POST['cost_center'];
$cc_desc = $_POST['cc_desc'];
$plant_code = $_POST['plant_code'];
$id_factory = $_POST['id_factory'];



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
// check for a wc_desc.
if (empty($_POST['wc_desc']))
{ $wc_desc = FALSE;
  $message.= '<p>You are required to enter Work Center Description.!</p>';
  }else
  { $wc_desc = escape_data($_POST['wc_desc']);
  }
  
// check for a plant code
if (empty($_POST['plant_code']))
{ $plant_code = FALSE;
  $message.= '<p> You are required to enter Plant!</p>';
  }
    else
  { $plant_code = escape_data($_POST['plant_code']);
  }

// check for a cost center
if (empty($_POST['cost_center']))
{ $cost_center = FALSE;
  $message.= '<p>You are required to enter Cost Center!</p>';
  }
  else
  { $cost_center = escape_data($_POST['cost_center']);
  }

// check for a cost center Desc
if (empty($_POST['cc_desc']))
{ $cc_desc = FALSE;
  $message.= '<p> You are required to enter Cost Center Description!</p>';
  }
    else
  { $cc_desc = escape_data($_POST['cc_desc']);
  }

// check for factory
if (empty($_POST['id_factory']) || ($_POST['id_factory'] == ""))
{ $id_factory = FALSE;
  $message.= '<p> You are required to select Factory!</p>';
  }
  else
  { $id_factory = escape_data($_POST['id_factory']);
  }

 
  
  //---------------------------------------------------------------------------------------
  // material component 
  //----------------------------------------------------------------------------------------

   
 if($id_work && $wc_desc && $plant_code && $cost_center && $cc_desc && $id_factory) //everything ok
{     	
		  	  $query_search = "SELECT * FROM work_center_detail WHERE id_work = '".db_esc($dbc, $id_work)."'";
              $result_search = mysqli_query($dbc, $query_search);   //run the query.
              $num_search = mysqli_num_rows($result_search);   //how many suppliers are there?
			  
			  if($num_search == 1) {
			  //echo $num_search; 
			    $row = mysqli_fetch_array($result_search, MYSQLI_NUM);
				// make the update query
	
		$query_upd = "UPDATE work_center_detail SET wc_desc = '".db_esc($dbc, $wc_desc)."', cost_center = '".db_esc($dbc, $cost_center)."', cc_desc = '".db_esc($dbc, $cc_desc)."', plant_code = '".db_esc($dbc, $plant_code)."', id_factory = '".db_esc($dbc, $id_factory)."' WHERE id_work = '".db_esc($dbc, $id_work)."'"; 
		$result_upd = mysqli_query($dbc, $query_upd); 
								
			if($result_upd)
			{
			 echo "<script>";
		     echo "alert('Work Center is successfully update.');";
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
          <form name="form1" method="post" action="work_center_edit.php?id_work=<?php echo $id_work; ?>" >
              <table class="table table-bordered">
               <tr>
                 <td>Work Center *</td>
                 <td>:</td>
                 <td>
                   <input name="id_work" type="text" id="id_work" size="20" maxlength="8" readonly value="<?php echo $row[0]; ?>" /></td>
               </tr>
               <tr>
                 <td>Work Center Desc *</td>
                 <td>:</td>
                 <td>
                   <input name="wc_desc" type="text" class="span11" id="wc_desc" size="55" maxlength="100" value="<?php echo $row[2]; ?>" />
               </td>
               </tr>
                        <tr>
                 <td>Plant Code *</td>
                 <td>:</td>
                 <td>
         <input name="plant_code" type="text"  class="span11" id="plant_code" size="20" maxlength="20" value="<?php echo $row[1]; ?>" /></td>
               </tr>
                <tr>
                 <td>Cost Center</td>
                 <td height="25">:</td>
                 
                 <td height="25"><input name="cost_center" type="text"  class="span11" id="cost_center" size="20" maxlength="100" value="<?php echo $row[3]; ?>" /></td>
               </tr>
      
               <tr>
                 <td>Cost Center Description *</td>
                 <td>:</td>
                 <td><input name="cc_desc" type="text"  class="span11" id="cc_desc" size="55" maxlength="100" value="<?php echo $row[4]; ?>" />
                  </td>
               </tr>
               <tr>
                 <td>Factory *</td>
                 <td>:</td>
                 <td><select name="id_factory" id="id_factory" class="span11">
                <option value="NULL" placeholder="Select Factory"> -- Select Factory --</option>
                <?php
	               $query3 = "SELECT * FROM factory_detail GROUP BY factory_desc2 ORDER BY id_fac ASC";
                   $result3 = mysqli_query($dbc, $query3);
  
                   while($row3=mysqli_fetch_array($result3, MYSQLI_NUM)) 
			      {
				  
				  
				  ?>
                <option value="<?php echo $row3[2]; ?>" <?php if($row3[2] == $row[5]) echo "selected"; ?>> <?php echo $row3[1]; ?></option>
                <?php
                  }
				?>
              </select>
                 </td>
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
           <input type="hidden" name="id_hdr" id="id_hdr" value="<?php echo h($id_hdr); ?>">
           <input type="hidden" name="id_work" id="id_work" value="<?php echo $id_work; ?>">
          
            <!-- End Form Buttons -->
          </form>
        
</div>
<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part-->
 </body>
</html>
