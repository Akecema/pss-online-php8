<?php

/**
 * prod/profile_edit_personal.php
 * Part of: Production module
 * Filename suggests: profile edit personal
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: sys_setup_maintain, user_detail, record, company, department, designation.
 * Includes: config.php, paginator.class2.php, tc_calendar.php.
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
require_role($dbc, 2);
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo h($data_setup["title_desc"]); ?></title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../css/style.css" type="text/css" media="all" />
<link href="../css/ddtabmenu.css" rel="stylesheet" type="text/css" />
</head>
<?php


function mb_rawurlencode($url){
$encoded='';
$length=mb_strlen($url);
for($i=0;$i<$length;$i++){
$encoded.='%'.wordwrap(bin2hex(mb_substr($url,$i,1)),5,'%',true);
}
return $encoded;
}
?>

<script type="text/javascript">
//SYNTAX: ddtabmenu.definemenu("tab_menu_id", integer OR "auto")
ddtabmenu.definemenu("ddtabs1", 0) //initialize Tab Menu #1 with 1st tab selected
ddtabmenu.definemenu("ddtabs2", 1) //initialize Tab Menu #2 with 2nd tab selected
ddtabmenu.definemenu("ddtabs3", 1) //initialize Tab Menu #3 with 2nd tab selected
ddtabmenu.definemenu("ddtabs4", 2) //initialize Tab Menu #4 with 3rd tab selected
ddtabmenu.definemenu("ddtabs5", -1) //initialize Tab Menu #5 with NO tabs selected (-1)
</script>
<body>
<!-- Header -->
<!-- End Header -->
<!-- Container -->
<div id="container">
  <div class="shell">
     <!-- Small Nav -->
  
    <!-- End Small Nav -->
   <!---------- tab menu  ---------------->
   <p>
      <!-- Message OK -->
      <!-- End Message OK -->
   </p>
    <div id="main2">
      <div class="cl">&nbsp;</div>
      <!-- Content -->
    <div id="subcontent">
    <?php

$user_no = $_GET['user_no'];

$queryu = "SELECT * FROM user_detail WHERE user_no = '".db_esc($dbc, $user_no)."'";
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
if (isset($_POST['submit']))
{
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


   
 if ($user_fullname && $department && $designation && $company && $user_telno1 && $user_email) //everything ok
{  


		  	  $query_search = "select user_no from user_detail where user_no = '".db_esc($dbc, $user_no)."'";
              $result_search = mysqli_query($dbc, $query_search);   //run the query.
              $num_search = mysqli_num_rows($result_search);   //how many suppliers are there?
			  if($num_search == 1) {
			  //echo $num_search; 
			    $row = mysqli_fetch_array($result_search, MYSQLI_NUM);
				// make the update query
				$query_upd = "UPDATE user_detail SET user_fullname='".db_esc($dbc, $user_fullname)."', department = '".db_esc($dbc, $department)."', designation = '".db_esc($dbc, $designation)."', company = '".db_esc($dbc, $company)."', user_telno1='".db_esc($dbc, $user_telno1)."', user_telno2='".db_esc($dbc, $user_telno2)."', user_fax='".db_esc($dbc, $user_fax)."', user_email='".db_esc($dbc, $user_email)."', 
							  date_update= NOW(), user_update ='".db_esc($dbc, $username)."' WHERE user_no = '".db_esc($dbc, $user_no)."'";
				$result_upd = mysqli_query($dbc, $query_upd); 
				if(mysqli_affected_rows($dbc) == 1)
				{
echo "<script>";
echo "alert('Profile is successfully updated.');";
echo "window.location='profile_setting.php?user_no=$user_no;'"; 
echo "</script>"; 
//echo 'ok'; 
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

        <!-- Box -->
        <div class="box">
          <!-- Box Head -->
          <div class="box-head">
            <h2><img src="../images/edit_icon.png" width="24" height="24"/>Edit Account</h2>
          </div>
          <!-- End Box Head -->
          <form name="form1" method="post" action="profile_edit_personal.php?user_no=<?php echo mb_rawurlencode($user_no); ?>" >
        
            <!-- Form -->
            <div class="form">
             <table width="99%" border="0" cellspacing="2">
               <tr>
                 <td width="24%" height="25">Vendor ID </td>
                 <td width="2%" height="25">:</td>
                 <td width="74%" height="25"><font color="#006699">
                   <input name="vendor_no" type="text" id="vendor_no" size="20" maxlength="8" readonly value="<?php echo h($row[1]); ?>" />
                 *</font></td>
               </tr>
               <tr>
                 <td height="25">Staff ID </td>
                 <td height="25">:</td>
                 <td height="25"><font color="#006699">
                   <input name="user_id" type="text" class="textbox" id="user_id" size="20" maxlength="20" readonly value="<?php echo h($row[2]); ?>" />
                 *</font></td>
               </tr>
                        <tr>
                 <td height="25">Name</td>
                 <td height="25">:</td>
                 <td height="25"><font color="#006699">
         <input name="user_fullname" type="text" class="textbox" id="user_fullname" size="55" maxlength="100" value="<?php echo h($row[5]); ?>" />
                 *</font></td>
               </tr>
               
               <tr>
                 <td height="25">Company's Name</td>
                 <td height="25">:</td>
                 
                 <td height="25"><?php	
				 
  $query3_a = "SELECT * FROM company WHERE comp_code = '".db_esc($dbc, $row[8])."'";
  $result3_a = mysqli_query($dbc, $query3_a);
  $row3_a = mysqli_fetch_array($result3_a);
				 
				 	
 	echo ' <select name="company" class="title">
  <option value="', $row3_a[0],'">', stripslashes($row3_a[1]), '</option>';
  
  //Retrieve and display the available types
  $query3 ='Select * from company';
  $result3 = mysqli_query($dbc, $query3);
  
     while($row3 =mysqli_fetch_array($result3, MYSQLI_NUM)) {
	    echo'<option value="', $row3[0],'">', stripslashes($row3[1]), '</option>';
		}
	//complete the form
	
	echo '</select>';

	?>*</td>
               </tr>
      
               <tr>
                 <td height="25">Department</td>
                 <td height="25">:</td>
                 <td height="25"><?php	
   $query2_a ="SELECT * from department WHERE id_dept = '".db_esc($dbc, $row[6])."'";
   $result2_a = mysqli_query($dbc, $query2_a);
   $row2_a = mysqli_fetch_array($result2_a);
				 
				 	
 	echo ' <select name="dept" class="title">
  <option value="', $row2_a[0],'"> ', stripslashes($row2_a["dept_name"]), '</option>';
  
  //Retrieve and display the available types
  $query2 ='Select * from department';
  $result2 = mysqli_query($dbc, $query2);
  
     while($row2 =mysqli_fetch_array($result2, MYSQLI_NUM)) {
	    echo'<option value="', $row2[0],'">', stripslashes($row2[2]), '</option>';
		}
	//complete the form
	
	echo '</select>';

	?>*</td>
               </tr>
               <tr>
                 <td height="25">Designation</td>
                 <td height="25">:</td>
                 <td height="25"><?php		
	 $query2b_a = "SELECT * FROM designation WHERE id_design = '".db_esc($dbc, $row[7])."'";
     $result2b_a = mysqli_query($dbc, $query2b_a);
     $row2b_a =mysqli_fetch_array($result2b_a);
				 
				 
 	echo ' <select name="design" class="title">
  <option value="', $row2b_a[0],'"> ', stripslashes($row2b_a[1]), '</option>';
  
  //Retrieve and display the available types
  $query2b ='Select * from designation';
  $result2b = mysqli_query($dbc, $query2b);
  
     while($row2b =mysqli_fetch_array($result2b, MYSQLI_NUM)) {
	    echo'<option value="', $row2b[0],'">', stripslashes($row2b[1]), '</option>';
		}
	//complete the form
	
	echo '</select>';

	?>*</td>
               </tr>
               <tr>
                 <td height="25">Telephone No. 1</td>
                 <td height="25">:</td>
                 <td height="25"><font color="#006699">
         <input name="user_telno1" type="text" class="textbox" id="user_telno1" size="20" maxlength="20" value="<?php echo h($row[9]); ?>" />
                 *</font></td>
               </tr>
               <tr>
                 <td height="25">Telephone No. 2</td>
                 <td height="25">:</td>
                 <td height="25"><font color="#006699">
                   <input name="user_telno2" type="text" class="textbox" id="user_telno2" size="20" maxlength="20" value="<?php echo h($row[10]); ?>" />
                 </font></td>
               </tr>
               <tr>
                 <td height="25">Fax No</td>
                 <td height="25">:</td>
                 <td height="25"><font color="#006699">
                   <input name="user_fax" type="text" class="textbox" id="user_fax" size="20" maxlength="20" value="<?php echo h($row[11]); ?>" />
                 </font></td>
               </tr>
               <tr>
                 <td height="25">E-mail</td>
                 <td height="25">:</td>
                 <td height="25"><font color="#006699">
       <input name="user_email" type="text" class="textbox" id="user_email" size="55" maxlength="200" value="<?php echo h($row[12]); ?>" />
                 *</font></td>
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
                 <td height="25">Date Created</td>
                 <td height="25">:</td>
                 <td height="25"><b><font color="blue">
                   <?php  echo h($row[14]); ?>
                 </font></b></td>
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
            </div>
            <!-- End Form -->
            <!-- Form Buttons -->
            <div class="buttons">
           <input name="submit" type="submit" class="button" id="submit" value="SUBMIT">
            <input type="button" class="button" id="Reset" value="CLOSE" onClick="window.close()">
          
          <input type="hidden" name="user_no" id="user_no" value="<?php echo h($row[0]); ?>">
            
              
            </div>
            <!-- End Form Buttons -->
          </form>
        </div>
        <!-- End Box -->
      </div>
      <!-- End Content -->
      <!-- Sidebar -->
      <!-- End Sidebar -->
      <div class="cl">&nbsp;</div>
    </div>
    <!-- Main -->
  </div>
</div>
<!-- End Container -->

</body>
</html>
