<?php

/**
 * admin/con_detail_edit.php
 * Part of: Admin module
 * Filename suggests: con detail edit
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, consumable_detail, record, uom_con.
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
$url = "con_detail_table.php";

    $query2 = "SELECT * FROM user_detail WHERE username = ?"; $query2_args = [$username];
    $result2 = db_query_bind($dbc, $query2, $query2_args) or die(db_fail($dbc));
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
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
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
  <h4>Edit Consumable</h4>  
    <?php

$id_con = $_GET['id_con'];

$queryu = "SELECT * FROM consumable_detail WHERE id_con = ?"; $queryu_args = [$id_con];
$resultu = db_query_bind($dbc, $queryu, $queryu_args);   //run the query.
$row = mysqli_fetch_row($resultu);   //how many records are there?


if (isset($_POST['submit']))
{
$id_con = $_POST['id_con'];
$material_no = $_POST['material_no'];
$mat_desc = $_POST['mat_desc'];
$cost_center = $_POST['cost_center'];
$plant = $_POST['plant'];
$BUn = $_POST['BUn'];
$con_status = $_POST['con_status'];


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
// check for a material No.
if (empty($_POST['material_no']))
{ $material_no = FALSE;
  $message.= '<p>You are required to enter Material No.!</p>';
  }else
  { $material_no = escape_data($_POST['material_no']);
  }
  
// check for a material Desc
if (empty($_POST['mat_desc']))
{ $mat_desc = FALSE;
  $message.= '<p> You are required to enter Material Description!</p>';
  }
    else
  { $mat_desc = escape_data($_POST['mat_desc']);
  }
  
// check for a plant code
if (empty($_POST['plant']))
{ $plant = FALSE;
  $message.= '<p> You are required to enter Plant!</p>';
  }
    else
  { $plant = escape_data($_POST['plant']);
  }

// check for a cost center
if (empty($_POST['cost_center']))
{ $cost_center = FALSE;
  $message.= '<p>You are required to enter Cost Center!</p>';
  }
  else
  { $cost_center = escape_data($_POST['cost_center']);
  }

// check for BUn
if (empty($_POST['BUn']) || ($_POST['BUn'] == "NULL"))
{ $BUn = FALSE;
  $message.= '<p> You are required to select BUn!</p>';
  }
  else
  { $BUn = escape_data($_POST['BUn']);
  }

// check for con sstatus
if (empty($_POST['con_status']) || ($_POST['con_status'] == "NULL"))
{ $con_status = FALSE;
  $message.= '<p> You are required to select Status!</p>';
  }
  else
  { $con_status = escape_data($_POST['con_status']);
  }

 
  
  //---------------------------------------------------------------------------------------
  // material component 
  //----------------------------------------------------------------------------------------

   
 if($id_con && $material_no && $mat_desc && $plant && $cost_center && $BUn && $con_status) //everything ok
{     	
		  	  $query_search = "SELECT * FROM consumable_detail WHERE id_con = ?"; $query_search_args = [$id_con];
              $result_search = db_query_bind($dbc, $query_search, $query_search_args);   //run the query.
              $num_search = mysqli_num_rows($result_search);   //how many suppliers are there?
			  
			  if($num_search == 1) {
			  //echo $num_search; 
			    $row = mysqli_fetch_array($result_search, MYSQLI_NUM);
				// make the update query
	
		$query_upd = "UPDATE consumable_detail SET mat_desc = ?, cost_center = ?, plant = ?, BUn = ?, con_status = ? WHERE id_con = ?"; $query_upd_args = [$mat_desc, $cost_center, $plant, $BUn, $con_status, $id_con]; 
		$result_upd = db_query_bind($dbc, $query_upd, $query_upd_args); 
								
			if($result_upd)
			{
			 echo "<script>";
		     echo "alert('Consumable is successfully update.');";
		     echo "parent.tb_remove(); parent.location.reload(1)";
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

      <form name="form1" method="post" action="con_detail_edit.php?id_con=<?php echo h($id_con); ?>" >
           <table class="table table-striped table-bordered">
               <tr>
                 <td width="24%" height="25">Material No. *</td>
                 <td width="2%" height="25">:</td>
                 <td width="74%" height="25">
                   <input name="material_no" type="text" id="material_no" size="20" maxlength="8" readonly value="<?php echo h($row[1]); ?>" />
                 </td>
               </tr>
               <tr>
                 <td height="25">Material  Desc</td>
                 <td height="25">:</td>
                 <td height="25">
                   <input name="mat_desc" type="text" class="textbox" id="mat_desc" size="55" maxlength="100" value="<?php echo h($row[2]); ?>" />
                 </td>
               </tr>
                        <tr>
                 <td height="25">Plant Code *</td>
                 <td height="25">:</td>
                 <td height="25">
         <input name="plant" type="text" class="textbox" id="plant" size="20" maxlength="20" value="<?php echo h($row[5]); ?>" />
                </td>
               </tr>
               
               <tr>
                 <td height="25">Cost Center *</td>
                 <td height="25">:</td>
                 
                 <td height="25"><input name="cost_center" type="text" class="textbox" id="cost_center" size="20" maxlength="30" value="<?php echo h($row[4]); ?>" /></td>
               </tr>
      
               <tr>
                 <td height="25">BUn *</td>
                 <td height="25">:</td>
                 <td height="25">
            			  <select name="BUn" id="BUn" >
                          <option value="NULL" placeholder="Select BUn"> -- Select BUn --</option>
                          <?php
	                      $query7 = "SELECT * FROM uom_con ORDER BY UOM ASC";
                          $result7 = mysqli_query($dbc, $query7);
  
                          while($row7 = mysqli_fetch_array($result7, MYSQLI_NUM)) 
			             {
					      ?>
                         <option value="<?php echo h($row7[0]); ?>" <?php if($row7[0] == $row[3]) echo "selected"; ?>> <?php echo h($row7[0]); ?></option>    
                          <?php
                           }
				           ?>
                          </select>
                </td>
               </tr>
               <tr>
                 <td height="25">Status *</td>
                 <td height="25">:</td>
                 <td height="25">
                 
      <select name="con_status"  class="textbox">
      <option value="NULL" placeholder="Select Status"> -- Select Status --</option>
	  <option value="Y" class="title" <?php if($row[6] == 'Y') echo "selected"; ?>>Y - Active</option>
	  <option value="N" class="title" <?php if($row[6] == 'N') echo "selected"; ?>>N - Inactive</option>
	      </select>
                 </td>
               </tr>
               <tr>
                 <td height="25">&nbsp;</td>
                 <td height="25">&nbsp;</td>
                 <td height="25">&nbsp;</td>
               </tr>
                 <tr>
                 <td height="25">* Compulsary field</td>
                 <td height="25">&nbsp;</td>
                 <td height="25">&nbsp;</td>
               </tr>
            </table>
           <input name="submit" type="submit" class="btn btn-success" id="submit" value="UPDATE">
           <input name="Reset" type="reset" class="btn btn-danger" id="Reset" value="CLEAR">
           <input type="hidden" name="id_con" id="id_con" value="<?php echo h($id_con); ?>">
           </form>
       
</div>

<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part--> 

</body>
</html>
