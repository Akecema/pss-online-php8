<?php

/**
 * admin/vendor_account_edit.php
 * Part of: Admin module
 * Filename suggests: vendor account edit
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, vendor_detail, record.
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

    $url = 'add_vendor_account.php';

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
  <h4>Edit Vendor</h4> 

    <?php

$vendor_code = $_GET["vendor_code"];

$queryu = "SELECT * FROM vendor_detail WHERE vendor_code = '".db_esc($dbc, $vendor_code)."'";
$resultu = mysqli_query($dbc, $queryu);   //run the query.
$row = mysqli_fetch_array($resultu);   //how many records are there?


if (isset($_POST["submit"]))
{
	
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

  //$i = 1;
  
   $vendor_code = $_POST["vendor_code"];
   $vendor_name = $_POST["vendor_name"];
   $add_no1 = $_POST["add_no1"];
   $add_no2 = $_POST["add_no2"];
   $post_code = $_POST["post_code"];
   $post_city = $_POST["post_city"];
   $post_region = $_POST["post_region"];
   $post_country = $_POST["post_country"];
   $search_term = $_POST["search_term"];
   $tphone = $_POST["tphone"];
   $fax_no = $_POST["fax_no"];
   $payment_method = $_POST["payment_method"];
   $term_payment = $_POST["term_payment"];
   $status_acc = $_POST["status_acc"];
   $status_subcont = $_POST["status_subcont"];
   
   $tphone = escape_data($_POST["tphone"]);
   $fax_no = escape_data($_POST["fax_no"]);
   $payment_method = escape_data($_POST["payment_method"]);
   $term_payment = escape_data($_POST["term_payment"]);
  

//------------------------------end function --------------------------------
// check for a vendor code
if (empty($_POST["vendor_code"]))
{ $vendor_code = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Vendor Code!</p>';
  }

// check for a vendor_name
if (empty($_POST["vendor_name"]))
{ $vendor_name = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Vendor Name!</p>';
  }else
  { $vendor_name = escape_data($_POST["vendor_name"]);
  }
  
// check for a address no 1
if (empty($_POST["add_no1"]))
{ $add_no1 = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Address No. 1!</p>';
  }
    else
  { $add_no1 = escape_data($_POST["add_no1"]);
  }
  
  // check for a address no 2
if (empty($_POST["add_no2"]))
{ $add_no2 = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Address No. 2!</p>';
  }
    else
  { $add_no2 = escape_data($_POST["add_no2"]);
  }

// check for a post_code
if (empty($_POST["post_code"]))
{ $post_code = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Postcode!</p>';
  }
  else
  { $post_code = escape_data($_POST["post_code"]);
  }

// check for a post city
if (empty($_POST["post_city"]))
{ $post_city = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter City!</p>';
  }
    else
  { $post_city = escape_data($_POST["post_city"]);
  }
  
  // check for a post region
if (empty($_POST["post_region"]))
{ $post_region = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Region!</p>';
  }
    else
  { $post_region = escape_data($_POST["post_region"]);
  }

// check for a post country
if (empty($_POST["post_country"]))
{ $post_country = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Country!</p>';
  }
    else
  { $post_country = escape_data($_POST["post_country"]);
  }
  
  // check for a search term
if (empty($_POST["search_term"]))
{ $search_term = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to enter Search Term!</p>';
  }
    else
  { $search_term = escape_data($_POST["search_term"]);
  }

// check for status account
if (empty($_POST["status_acc"]) || ($_POST["status_acc"] == ""))
{ $status_acc = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to select Status Account!</p>';
  }
  else
  { $status_acc = escape_data($_POST["status_acc"]);
  }
  
  // check for status subcont
if (empty($_POST["status_subcont"]) || ($_POST["status_subcont"] == ""))
{ $status_subcont = FALSE;
  $message.= '<p><strong>Error!</strong> You are required to select Status Subcont!</p>';
  }
  else
  { $status_subcont = escape_data($_POST["status_subcont"]);
  }
  
 if($vendor_code && $vendor_name && $add_no1 && $add_no2 && $post_code && $post_city && $post_region && $post_country && $search_term && $status_acc && $status_subcont) //everything ok
 {  
 
 
 	     	  $query_search = "SELECT * FROM vendor_detail WHERE vendor_code = '".db_esc($dbc, $vendor_code)."'";
              $result_search = mysqli_query($dbc, $query_search);   //run the query.
              $num_search = mysqli_num_rows($result_search);   //how many suppliers are there?
			  
			  if($num_search == 1) {
			  //echo $num_search; 
			    $row = mysqli_fetch_array($result_search, MYSQLI_NUM);
				// make the update query
	
		$query_upd = "UPDATE vendor_detail SET vendor_name = '".strtoupper($vendor_name)."', add_no1 = '".db_esc($dbc, $add_no1)."', add_no2 = '".db_esc($dbc, $add_no2)."', search_term = '".strtoupper($search_term)."', post_code = '".db_esc($dbc, $post_code)."', post_city = '".db_esc($dbc, $post_city)."', post_region = '".db_esc($dbc, $post_region)."', post_country = '".db_esc($dbc, $post_country)."', tphone = '".db_esc($dbc, $tphone)."', fax_no = '".db_esc($dbc, $fax_no)."', payment_method = '".db_esc($dbc, $payment_method)."', term_payment = '".db_esc($dbc, $term_payment)."', status_acc = '".db_esc($dbc, $status_acc)."', status_subcont = '".db_esc($dbc, $status_subcont)."', user_update = '".db_esc($dbc, $username)."', date_update = NOW() WHERE vendor_code = '".db_esc($dbc, $vendor_code)."'"; 
		$result_upd = mysqli_query($dbc, $query_upd); 
								
			if($result_upd)
			{
			 echo "<script>";
		     echo "alert('Account vendor is successfully update.');";
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
          <form name="form1" method="post" action="vendor_account_edit.php?vendor_code=<?php echo $vendor_code; ?>" >
              <table class="table table-bordered">
               <tr>
                 <td>Vendor Code <font color="#FF0000"><b> *</b></font></td>
                 <td>:</td>
                 <td>
                   <input name="vendor_code" type="text" id="vendor_code" size="20" maxlength="8" readonly value="<?php echo h($row[0]); ?>" /></td>
               </tr>
               <tr>
                 <td>Vendor Name <font color="#FF0000"><b> *</b></font></td>
                 <td>:</td>
                 <td>
                   <input name="vendor_name" type="text" class="span11" id="vendor_name" maxlength="100" value="<?php echo h($row[1]); ?>" placeholder="Enter Vendor Name" />
               </td>
               </tr>
                <tr>
                 <td>Search Term <font color="#FF0000"><b> *</b></font></td>
                 <td>:</td>
                 <td>
                  <input name="search_term" type="text" class="span5" id="search_term" size="20" maxlength="20" value="<?php  echo h($row['search_term']); ?>" placeholder="Enter Search Term"/>
               </td>
               </tr>
                <tr>
                 <td>Address No. 1 <font color="#FF0000"><b> *</b></font></td>
                 <td>:</td>
                 <td>
                   <input name="add_no1" type="text"  class="span11" id="add_no1" maxlength="100" value="<?php echo h($row[2]); ?>" placeholder="Enter Address No. 1"/></td>
               </tr>
                  <tr>
                 <td>Address No. 2 <font color="#FF0000"><b> *</b></font></td>
                 <td>:</td>
                 <td>
                   <input name="add_no2" type="text"  class="span11" id="add_no2" maxlength="100" value="<?php echo h($row[3]); ?>" placeholder="Enter Address No. 2" /></td>
               </tr>
                <tr>
                 <td>Postcode <font color="#FF0000"><b> *</b></font></td>
                 <td height="25">:</td>
                 
                 <td height="25">   <input name="post_code" type="text" class="span5" id="post_code" size="20" maxlength="15" value="<?php echo h($row['post_code']); ?>" placeholder="Enter Postcode"/></td>
               </tr>
               <tr>
                 <td>City <font color="#FF0000"><b> *</b></font></td>
                 <td>:</td>
                 <td><input name="post_city" type="text" class="span11" id="post_city" size="20" value="<?php echo h($row['post_city']); ?>" placeholder="Enter City"/>
                  </td>
               </tr>
               <tr>
                 <td>Region <font color="#FF0000"><b> *</b></font></td>
                 <td>:</td>
                 <td> <input name="post_region" type="text" class="span11" id="post_region" size="20" value="<?php echo h($row['post_region']); ?>" placeholder="Enter Region"/>
                  </td>
               </tr>
                <tr>
                 <td>Country <font color="#FF0000"><b> *</b></font></td>
                 <td>:</td>
                 <td><input name="post_country" type="text" class="span11" id="post_country" size="20" value="<?php echo h($row['post_country']); ?>" placeholder="Enter Country"/>
                  </td>
               </tr>
                <tr>
                 <td>Phone </td>
                 <td>:</td>
                 <td><input name="tphone" type="text" class="span5" id="tphone" size="20" maxlength="15" value="<?php echo h($row['tphone']); ?>" placeholder="Enter Phone No."/>
                  </td>
               </tr>
                <tr>
                 <td>Fax No. </td>
                 <td>:</td>
                 <td>  <input name="fax_no" type="text" class="span5" id="fax_no" size="20" maxlength="15" value="<?php echo h($row['fax_no']); ?>" placeholder="Enter Fax No."/>
                  </td>
               </tr>
                <tr>
                 <td>Payment Method </td>
                 <td>:</td>
                 <td><input name="payment_method" type="text" class="span5" id="payment_method" size="20" maxlength="15" value="<?php echo h($row['payment_method']); ?>" placeholder="Enter Payment Method"/>
                  </td>
               </tr>
                <tr>
                 <td>Term Payment Method </td>
                 <td>:</td>
                 <td><input name="term_payment" type="text" class="span5" id="term_payment" size="20" maxlength="15" value="<?php echo h($row['term_payment']); ?>" placeholder="Enter Term Payment"/>
                  </td>
               </tr>
                <tr>
                 <td>Status Account <font color="#FF0000"><b> *</b></font></td>
                 <td>:</td>
                 <td> 
               <select name="status_acc" id="status_acc">
               <option value=""> --- Select --- </option>
               <option value="Y" <?php if($row["status_acc"] == 'Y') { ?> selected="selected"<?php } ?>>ACTIVE</option>
               <option value="N" <?php if($row["status_acc"] == 'N') { ?> selected="selected"<?php } ?>>INACTIVE</option>
               </select>
                  </td>
               </tr>
               <tr>
                 <td>Status Subcont <font color="#FF0000"><b> *</b></font></td>
                 <td>:</td>
                 <td> 
               <select name="status_subcont" id="status_subcont">
               <option value=""> --- Select --- </option>
               <option value="Y" <?php if($row["status_subcont"] == 'Y') { ?> selected="selected"<?php } ?>>YES</option>
               <option value="N" <?php if($row["status_subcont"] == 'N') { ?> selected="selected"<?php } ?>>NO</option>
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
           <input type="hidden" name="id_hdr" id="id_hdr" value="<?php echo $id_hdr; ?>">
           <input type="hidden" name="id_work" id="id_work" value="<?php echo $id_work; ?>">
          
            <!-- End Form Buttons -->
          </form>
        
</div>
<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part-->
 </body>
</html>
