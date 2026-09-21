<?php

/**
 * admin/user_edit.php
 * Part of: Admin module
 * Filename suggests: user edit
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, record, company, department, designation.
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
  <h4>Edit Account</h4> 
<?php

//- First page:
$url = 'add_user.php';
$url2 = 'display_user.php';

$user_no = $_GET["user_no"];


$queryu = "SELECT * from user_detail where user_no = '".db_esc($dbc, $user_no)."'";
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

// check for a vendor no
if (empty($_POST['vendor_no']))
{ $vendor_no = FALSE;
  $message.= '<p>You are required to enter VENDOR ID!</p>';
  }

// check for a user id
if (empty($_POST['user_id']))
{ $user_id = FALSE;
  $message.= '<p>You are required to enter USER ID!</p>';
  }

// check for a  name
if (empty($_POST['user_fullname']))
{ $user_fullname = FALSE;
  $message.= '<p> You are required to enter your NAME!</p>';
  }
  else
  { $user_fullname = escape_data($_POST['user_fullname']);
  }

// check for company
if (empty($_POST['company']) || ($_POST['company'] == ""))
{ $company = FALSE;
  $message.= '<p> You are required to select COMPANY!</p>';
  }
  else
  { $company = escape_data($_POST['company']);
  }

// check for a department
if (empty($_POST['dept']) || ($_POST['dept'] == ""))
{ $department = FALSE;
  $message.= '<p> You are required to select DEPARTMENT!</p>';
  }
  else
  { $department = escape_data($_POST['dept']);
  }

// check for a designation
if (empty($_POST['design']) || ($_POST['design'] == ""))
{ $designation = FALSE;
  $message.= '<p> You are required to select DESIGNATION!</p>';
  }
  else
  { $designation = escape_data($_POST['design']);
  }
  


// check for a telephone no 1
if (empty($_POST['user_telno1']))
{ $user_telno1 = FALSE;
  $message.= '<p> You are required to enter TELEPHONE NO (1)!</p>';
  }
  else
  { 
  $user_telno1 = escape_data($_POST['user_telno1']);
  }


  $user_telno2 = escape_data($_POST['user_telno2']);
  $user_fax = escape_data($_POST['user_fax']);
  $user_email = escape_data($_POST['user_email']);
  $status = escape_data($_POST['status']);
  $level_id = escape_data($_POST['level_id']);

   
 if ($user_fullname && $department && $designation && $company && $user_telno1 && $user_email && $level_id && $status) //everything ok
{  


		  	  $query_search = "select user_no from user_detail where user_no = '".db_esc($dbc, $user_no)."'";
              $result_search = mysqli_query($dbc, $query_search);   //run the query.
              $num_search = mysqli_num_rows($result_search);   //how many suppliers are there?
			  if($num_search == 1) {
			  //echo $num_search; 
			    $row = mysqli_fetch_array($result_search, MYSQLI_NUM);
				// make the update query
				$query_upd = "UPDATE user_detail SET user_fullname='".db_esc($dbc, $user_fullname)."', department = '".db_esc($dbc, $department)."', designation = '".db_esc($dbc, $designation)."', company = '".db_esc($dbc, $company)."', user_telno1='".db_esc($dbc, $user_telno1)."', user_telno2='".db_esc($dbc, $user_telno2)."', user_fax='".db_esc($dbc, $user_fax)."', user_email='".db_esc($dbc, $user_email)."', 
							  date_update= NOW(), user_update ='".db_esc($dbc, $username)."', 
							 status = '".db_esc($dbc, $status)."', level_id = '".db_esc($dbc, $level_id)."' WHERE user_no = '".db_esc($dbc, $user_no)."'";
				$result_upd = mysqli_query($dbc, $query_upd); 
				if(mysqli_affected_rows($dbc) == 1)
				{
echo "<script>";
echo "alert('Profile is successfully updated.');";
echo "parent.location.reload(1);";
echo "parent.$.fancybox.close();";
echo "</script>"; 

exit(); 
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


          <form name="form1" method="post" action="user_edit.php?user_no=<?php echo $user_no; ?>" >
             <table class="table table-bordered">
               <tr>
                 <td>Vendor ID *</td>
                 <td>:</td>
                 <td>
                   <input name="vendor_no" type="text" id="vendor_no" size="20" maxlength="8" readonly value="<?php echo $row[1]; ?>" />
                 </td>
               </tr>
               <tr>
                 <td>Staff ID *</td>
                 <td>:</td>
                 <td>
                   <input name="user_id" type="text" id="user_id" size="20" maxlength="20" readonly value="<?php echo $row[2]; ?>" />
                 </td>
               </tr>
                 <tr>
                 <td height="25">Name *</td>
                 <td height="25">:</td>
                 <td height="25">
         <input name="user_fullname" type="text" class="span11" id="user_fullname" size="55" maxlength="100" value="<?php echo $row[5]; ?>" />
                </td>
               </tr>
               
               <tr>
                 <td>Company's Name *</td>
                 <td>:</td>
                 
                 <td><?php	
				 
  $query3_a = "SELECT * FROM company WHERE comp_code = '".db_esc($dbc, $row[8])."'";
  $result3_a = mysqli_query($dbc, $query3_a);
  $row3_a = mysqli_fetch_array($result3_a);
				 
				 	
 	echo ' <select name="company" class="span11">
  <option value="', $row3_a[0],'">', stripslashes($row3_a[1]), '</option>';
  
  //Retrieve and display the available types
  $query3 ='Select * from company';
  $result3 = mysqli_query($dbc, $query3);
  
     while($row3 =mysqli_fetch_array($result3, MYSQLI_NUM)) {
	    echo'<option value="', $row3[0],'">', stripslashes($row3[1]), '</option>';
		}
	//complete the form
	
	echo '</select>';

	?></td>
               </tr>
      
               <tr>
                 <td>Department *</td>
                 <td>:</td>
                 <td><?php	
   $query2_a ="SELECT * from department WHERE id_dept = '".db_esc($dbc, $row[6])."'";
   $result2_a = mysqli_query($dbc, $query2_a);
   $row2_a = mysqli_fetch_array($result2_a);
				 
				 	
 	echo ' <select name="dept" class="span11">
  <option value="', $row2_a[0],'"> ', stripslashes($row2_a["dept_name"]), '</option>';
  
  //Retrieve and display the available types
  $query2 ='Select * from department';
  $result2 = mysqli_query($dbc, $query2);
  
     while($row2 =mysqli_fetch_array($result2, MYSQLI_NUM)) {
	    echo'<option value="', $row2[0],'">', stripslashes($row2[2]), '</option>';
		}
	//complete the form
	
	echo '</select>';

	?></td>
               </tr>
               <tr>
                 <td>Designation* </td>
                 <td>:</td>
                 <td><?php		
	 $query2b_a = "SELECT * FROM designation WHERE id_design = '".db_esc($dbc, $row[7])."'";
     $result2b_a = mysqli_query($dbc, $query2b_a);
     $row2b_a =mysqli_fetch_array($result2b_a);
				 
				 
 	echo ' <select name="design" class="span11">
  <option value="', $row2b_a[0],'"> ', stripslashes($row2b_a[1]), '</option>';
  
  //Retrieve and display the available types
  $query2b ='Select * from designation';
  $result2b = mysqli_query($dbc, $query2b);
  
     while($row2b =mysqli_fetch_array($result2b, MYSQLI_NUM)) {
	    echo'<option value="', $row2b[0],'">', stripslashes($row2b[1]), '</option>';
		}
	//complete the form
	
	echo '</select>';

	?></td>
               </tr>
               <tr>
                 <td>Telephone No. 1 *</td>
                 <td>:</td>
                 <td>
         <input name="user_telno1" type="text" class="span11" id="user_telno1" size="20" maxlength="20" value="<?php echo $row[9]; ?>" />
                 </td>
               </tr>
               <tr>
                 <td height="25">Telephone No. 2</td>
                 <td height="25">:</td>
                 <td height="25">
                   <input name="user_telno2" type="text" class="span11" id="user_telno2" size="20" maxlength="20" value="<?php echo $row[10]; ?>" />
                </td>
               </tr>
               <tr>
                 <td>Fax No</td>
                 <td>:</td>
                 <td>
                   <input name="user_fax" type="text" class="span11" id="user_fax" size="20" maxlength="20" value="<?php echo $row[11]; ?>" />
                 </td>
               </tr>
               <tr>
                 <td>E-mail *</td>
                 <td>:</td>
                 <td>
       <input name="user_email" type="text" class="span11" id="user_email" size="55" maxlength="200" value="<?php echo $row[12]; ?>" /></td>
               </tr>
               <tr>
                 <td>Level *</td>
                 <td>:</td>
                 <td>
				 <?php
	   
  $query4_p ='Select * from level_detail as LD, user_detail as SD where SD.level_id = LD.id_level and LD.id_level = '.$row[16].'';
  $result4_p = mysqli_query($dbc, $query4_p);
  $row4_p = mysqli_fetch_array($result4_p);
	   
	   
	   		
 	echo ' <select name="level_id" class="span11">
  <option value="'.$row4_p["id_level"].'"> '.$row4_p["desc_level"].' </option>';
  
  //Retrieve and display the available types
  $query4 ='Select * from level_detail where status_level = "Y"';
  $result4 = mysqli_query($dbc, $query4);
  
     while($row4 =mysqli_fetch_array($result4, MYSQLI_NUM)) {
	    echo'<option value="', $row4[0],'">', stripslashes($row4[1]), '</option>';
		}
	//complete the form
	
	echo '</select>';

	?>
				</td>
               </tr>
               <?php
	
	  if($row[15] == "AC")
	  {
	     $sts = "Active";
		 }
		 else{
		 $sts = "Inactive";
		 }
      ?>
               <tr>
                 <td>Status User *</td>
                 <td>:</td>
                 <td><select name="status" class="span11" >
	  <option value ="<?php  echo $row[15]; ?>" ><?php  echo $sts; ?></option>
	  <option value="AC">Active</option>
	  <option value="NA">Inactive</option>
	      </select>
                  </td>
               </tr>
                <tr>
                 <td>Date Created</td>
                 <td>:</td>
                 <td><?php  echo $row[14]; ?></td>
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
