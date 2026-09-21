<?php

/**
 * ppc/profile_setting.php
 * Part of: PPC module (Production Planning & Control)
 * Filename suggests: profile setting
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: sys_setup_maintain, user_detail, company, department, designation, level_detail.
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
require_role($dbc, 4);
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


//- First page:
$url = 'add_user.php';
$url2 = 'display_user.php';

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
<p>
  <!-- Header -->
  <!-- End Header -->
  <?php

$user_no = $_GET["user_no"];

$queryu = "SELECT * from user_detail where user_no = '".db_esc($dbc, $user_no)."'";
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
</p>
<p>&nbsp;</p>
<table width="650">
    <tr>
      <td width="1%">&nbsp;</td>
      <td width="85%"> <div class="small-nav"></div></td>
      <td width="14%"><img src="../images/print2.jpg" width="48" height="48" onClick="window.print()" title="Print"/></td>
  </tr>
  </table>
      
      <div class="cl">&nbsp;</div>
      <!-- Content -->
      <div id="subcontent">
        <!-- Box -->
        <div class="box">
          <!-- Box Head -->
          <div class="box-head">
            <h2><img src="../images/Login Manager.png" width="24" height="24" />Personal Information</h2>
          </div>
          <!-- End Box Head -->
         <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <!-- Form -->
            <div class="form">
             <table width="99%" border="0" cellspacing="2">
               <tr>
                 <td width="26%" height="25">Vendor ID </td>
                 <td width="3%" height="25">:</td>
                 <td width="71%" height="25"><?php echo h($row[1]); ?></td>
               </tr>
               <tr>
                 <td height="25">Staff ID </td>
                 <td height="25">:</td>
                 <td height="25"><b><font color="blue"><?php echo h($row[2]); ?></font></b></td>
               </tr>
                 <tr>
                 <td height="25">Name</td>
                 <td height="25">:</td>
                 <td height="25"><?php echo h($row[5]); ?> </td> 
               </tr>
               <tr>
                 <td height="25">Company's Name</td>
                 <td height="25">:</td>
                 <td height="25"><?php		
 	
  //Retrieve and display the available types
  $query3 = "SELECT * FROM company WHERE comp_code = '".db_esc($dbc, $row[8])."'";
  $result3 = mysqli_query($dbc, $query3);
  $row3 = mysqli_fetch_array($result3);
  
	    echo h($row3["comp_name"]);
		

	?></td>
               </tr>
      
               <tr>
                 <td height="25">Department</td>
                 <td height="25">:</td>
                 <td height="25"><?php		
   //Retrieve and display the available types
  $query2 ="SELECT * from department WHERE id_dept = '".db_esc($dbc, $row[6])."'";
  $result2 = mysqli_query($dbc, $query2);
   $row2 = mysqli_fetch_array($result2);
	    
		echo h($row2["dept_name"]); 
	
	?></td>
               </tr>
               <tr>
                 <td height="25">Designation</td>
                 <td height="25">:</td>
                 <td height="25"><?php		

  //Retrieve and display the available types
  $query2b = "SELECT * FROM designation WHERE id_design = '".db_esc($dbc, $row[7])."'";
  $result2b = mysqli_query($dbc, $query2b);
   $row2b =mysqli_fetch_array($result2b);
   
  echo h($row2b[1]);

	?></td>
               </tr>
               <tr>
                 <td height="25">Telephone No. 1</td>
                 <td height="25">:</td>
                 <td height="25"><?php echo h($row[9]); ?></td>
               </tr>
               <tr>
                 <td height="25">Telephone No. 2</td>
                 <td height="25">:</td>
                 <td height="25"><?php echo h($row[10]); ?></td>
               </tr>
               <tr>
                 <td height="25">Fax No</td>
                 <td height="25">:</td>
                 <td height="25"><?php echo h($row[11]); ?> </td>
               </tr>
               <tr>
                 <td height="25">E-mail</td>
                 <td height="25">:</td>
                 <td height="25"><?php echo h($row[12]); ?></td>
               </tr>
               <tr>
                 <td height="25">Level</td>
                 <td height="25">:</td>
                 <td height="25"><?php		
 
  //Retrieve and display the available types
  $query4 = "SELECT * FROM level_detail WHERE status_level = 'Y' AND id_level = '".db_esc($dbc, $row[16])."'";
  $result4 = mysqli_query($dbc, $query4);
  $row4 =mysqli_fetch_array($result4);
	    echo h($row4["desc_level"]);
	
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
                   <?php  echo h($row[14]); ?>
                 </font></b></td>
               </tr>
              
               <tr>
                 <td height="25">&nbsp;</td>
                 <td height="25">&nbsp;</td>
                 <td height="25">&nbsp;</td>
               </tr>
            </table> 
             <div class="buttons">
            <input type="button" onClick="location.href='profile_edit_personal.php?user_no=<?php echo h($row[0]); ?>'" value="EDIT" class="button" >
          <input type="button" class="button" id="Reset" value="CLOSE" onClick="window.close()">
          
          <input type="hidden" name="user_no" id="user_no" value="<?php echo h($row[0]); ?>">
            
              
            </div>
            </div>
            <!-- End Form -->
        
          </form>
        </div>
        <!-- End Box -->
      </div>
      <!-- End Content -->
 
</div>


</body>
</html>
