<?php

/**
 * prod/material_consumable_request2.php
 * Part of: Production module
 * Filename suggests: material consumable request2
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, consumable_detail, consumable_request, work_center_detail.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_production_menu.php, footer.php.
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
require_role($dbc, 2);
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

date_default_timezone_set('Asia/Kuala_Lumpur');

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "display_consumable_request.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
    $result2 = mysqli_query($dbc, $query2) or die(db_fail($dbc));
    $res = mysqli_fetch_array($result2);
	
$today = getdate();
$hours = $today['hours']; 
$minutes = $today['minutes'];
$seconds = $today['seconds'];
$month = $today['mon']; 
$mday = $today['mday']; 
$year = $today['year']; 	
		
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
<meta charset="UTF-8" />
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

<!----------------->
<link rel="stylesheet" href="../scripts/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="../javascript/jquery-latest.js"></script> 
<script type="text/javascript" src="../javascript/thickbox.js"></script>	
<link href="../calendar/calendar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../calendar/calendar.js"></script>

<script type="text/javascript">
function printPage(iFid){
iFid.focus();
iFid.print();
}
</script>
<style>
#iframe1{
visibility:hidden;
}
</style>
<script type="text/javascript">
function printPage(iFid){
iFid.focus();
iFid.print();
 return true;
}
</script>
<style>
#iframe1{
visibility:hidden;
}
</style>
<script language="javascript" type="text/javascript">

function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
    }
	
	function getFactory(factory) {		
		
		var strURL="findWorkcenter.php?factory="+factory;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('work_centerdiv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
	
	
	
	
</script>	
</head>
<body>

<!--Header-part-->
<div id="header">
  <h1>&nbsp;</h1>
</div>
<?php  include "top_modal_menu.php";   ?>
<!--close-Header-part--> 

<!--sidebar-menu-->
<?php include "left_production_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_production.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Consumable Request</a> <a href="#" class="current">New Request</a> </div>
  <h1>Consumable Request</h1>
</div>

<div class="container-fluid">
  <hr>
  <div class="row-fluid">
  <!--  <div class="span6">-->
          <div class="widget-box">
          <div class="widget-title">
            <ul class="nav nav-tabs" role="tablist" id="templatemo-tabs">
              <li class="active" ><a  role="tab" href="material_consumable_request.php">New Request</a></li>
              <li><a role="tab" href="display_consumable_request.php">Display Request</a></li>
              <li><a role="tab" href="posting_consumable_request.php">Posting Request</a></li>
            </ul>
          </div>
          </div>
       <?php
	   
    // $lastID = 0;
     $current_date = date('Y-m-d H:i:s'); 
	 $next_date = date('Y-m-d H:i:s', strtotime($current_date .' +2 day'));

   $lastID = $_GET["lastID"];
   $date1 = $_GET["date1"];
   $t_time = $_GET["t_time"]; 
   $factory = $_GET["factory"];
  // $work_center = $_GET["work_center"];
   
if(isset($_POST["saveT"]) && $_POST!=="") 
{ // handle the form.

// create a function for escaping the data.
function escape_data ($data) {
global $dbc;   // need the connection.
if(ini_get('magic_quotes_gpc')) {
    $data = stripslashes($data);
	}
	return mysqli_real_escape_string($dbc, $data);
	}   // end function.
$message = NULL; // create an empty new variable.



   $material_no = $_POST["material_no"];
   $user_no = $_POST["user_no"];
   $con_qty = $_POST["con_qty"];
   $work_center = $_POST["work_center"];

 // check for a work center
if((empty($_POST["work_center"])) || ($_POST["work_center"] == "NULL"))
{ 
  $work_center = FALSE;
  $message.= '<p>You are required to select Production Line/ Work Center!</p>';
  } 

// check for a material no
if((empty($_POST["material_no"])) || ($_POST["material_no"] == "NULL"))
{ 
  $material_no = FALSE;
  $message.= '<p>You are required to select MATERIAL NO.!</p>';
  }
 
  // check for a con_qty
if((empty($_POST["con_qty"])) || ($_POST["con_qty"] == "NULL"))
{ 
  $con_qty = FALSE;
  $message.= '<p>You are required to enter Quantity!</p>';
  }
 

  
if($work_center && $material_no && $con_qty) //everything ok
{  


   $material_no = $_POST["material_no"];
   $user_no = $_POST["user_no"];
   $con_qty = $_POST["con_qty"];
   $work_center = $_POST["work_center"];

$query_data = "SELECT * FROM consumable_detail WHERE id_con = '".db_esc($dbc, $material_no)."'";
$result_data = mysqli_query($dbc, $query_data) or die(db_fail($dbc));
$row_data = mysqli_fetch_array($result_data);


 // $_SESSION['lastID'] = $lastID;

 //echo h($_GET["lastID"]);
   
//insert to scan_detail
$query_db = "INSERT INTO `consumable_request` (id_req_con, mrin_doc, mrin_year, temp_mrin, id_con, id_scan, material_no, con_qty, con_uom, status_request, status_print, status_view, factory, user_create, date_create, user_update, date_update, date_posting, time_posting, status, date_require, time_require, id_work) VALUES ('".mysqli_insert_id($dbc)."', '', '','', '".db_esc($dbc, $material_no)."', '".db_esc($dbc, $lastID)."','".db_esc($dbc, $row_data["material_no"])."', '".db_esc($dbc, $con_qty)."', '".db_esc($dbc, $row_data["BUn"])."', 'N', 'N', 'N', '".db_esc($dbc, $factory)."', '".db_esc($dbc, $user_no)."', NOW(),'','','','','New','".db_esc($dbc, $date1)."','".db_esc($dbc, $t_time)."','".db_esc($dbc, $work_center)."')";
$result = mysqli_query($dbc, $query_db) or die(db_fail($dbc));


}
//print the message if there is one.

if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}
}
?>
<?php
 if(isset($_POST["confirm_button"]) && $_POST!=="") 
{ // handle the form.

// create a function for escaping the data.
function escape_data ($data) {
global $dbc;   // need the connection.
if(ini_get('magic_quotes_gpc')) {
    $data = stripslashes($data);
	}
	return mysqli_real_escape_string($dbc, $data);
	}   // end function.
$message = NULL; // create an empty new variable.


$size2 = count($_POST["id_req_con"]) + 1;
 $i = 1;

//check only deilvery quantity in number
	 
               if(empty($_POST['con_qty'][$i]))
				  {
				  $con_qty = FALSE;
				  $message.= '<p>Please enter required Quantity!</p>';
				  }
				  elseif(!is_numeric($_POST['con_qty'][$i]))
				  { $con_qty = FALSE;
				   $message.= '<p>Please enter NUMBERS only for required Quantity!</p>';
				  }  
				  else
				  { 
				  $con_qty= escape_data($_POST['con_qty'][$i]);
				  }	             

   //--------------------------------create (temporary MRIN)-----------------------------
$query_id_2 = "SELECT * FROM consumable_request WHERE id_scan = (SELECT MAX(mrin_doc) FROM consumable_request)";
$result_id_2 = mysqli_query($dbc, $query_id_2);

if ($result_id_2) {
$nrows_2 = mysqli_num_rows($result_id_2);
$row_id_2 = mysqli_fetch_array($result_id_2);

 $dht_2 = "00000";

  if($row_id_2["id_scan"] <= 0)
  { 
   
    $lastID = ($row_id_2["id_scan"] + 1);
    $dg_2 = ($dht_2 + ($lastID));

   }else{
      $lastID = ($row_id_2["id_scan"] + 1);
      $dg_2 =  $lastID;
    }

	 $number = $dg_2; // Length of the supplied number is 3
	 $number = sprintf('%05d', $number);

  $ref = ('C'.$year.$number);  
		  
  } // end if $result_id
 
	
 if($con_qty ) //everything ok
{  	
 
  
  while ($i < $size2) {

  
//update table material request with new quantity

$query_update = "UPDATE consumable_request SET status_request = 'Y', mrin_doc = '$number', mrin_year = '".db_esc($dbc, $year)."', temp_mrin = '".db_esc($dbc, $ref)."', con_qty = '".db_esc($dbc, $_POST["con_qty"][$i])."', user_update = '".db_esc($dbc, $res["user_no"])."', date_update = NOW(), date_posting = NOW(), time_posting = NOW() WHERE id_req_con = '".db_esc($dbc, $_POST["id_req_con"][$i])."' AND user_create = '".db_esc($dbc, $res["user_no"])."' ";

$result_update = mysqli_query($dbc, $query_update);

	 $i++;
   } // end while loop	
   
          if($result_update)
           {
		   
		   echo "<script>";
		   echo "alert('MRIN NO :$ref successfully posted.');"; 
		   echo "parent.location='index_production.php'";
	       echo "</script>"; 
			 exit(); //quit the script
			  
             }
             else 
			 {
             $message = '<p> CANNOT CREATE MATERIAL REQUEST!!!. </p>';
             // mysqli_close($dbc); //close db
             }  
   
   
   	
} // end IF
		

//print the message if there is one.
if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}
} // end IF confirm_button [POST]

?>
     <!-- Box -->
        <div class="box">
          <!-- Box Head 
          <div class="box-head">
            <h2>Consumable Request</h2>
          </div>-->
          <!-- End Box Head material_consumable_request.php?material_no=<?php //echo $material_no; ?>&&lastID=<?php //echo $lastID; ?>--> 
          
         
            <!-- Form  <div class="form">-->
           <form name="form1" method="post" action="">
             <table width="99%" border="0" cellspacing="2"><tr><td width="63%" height="35"><table width="99%" border="0" cellspacing="2">
              <tr>
                 <td width="13%" height="35">Production Line/Work Center</td>
                 <td width="4%" height="35">:</td>
                 <td width="63%" height="35"><font color="#006699">
                  <select name="work_center" id="work_center">
                    <option value="NULL" placeholder="Select Production Line/ Work Center"> -- Select Production Line/ Work Center --</option>
                    <?php
	       $query4 = "SELECT * FROM work_center_detail WHERE id_factory = '".db_esc($dbc, $factory)."' ORDER BY id_work ASC";
                   $result4 = mysqli_query($dbc, $query4);
  
                   while($row4=mysqli_fetch_array($result4)) 
			      {
                  echo'<option value="',$row4["id_work"],'">',stripslashes($row4["id_work"]),' - ',stripslashes($row4["wc_desc"]),'</option>';
                  }
				?>
                  </select>
            *</font></td>
                 <td width="20%">&nbsp;</td>
               </tr>            
             <tr>
                 <td width="13%" height="35">Material No.</td>
                 <td width="4%" height="35">:</td>
                 <td width="63%" height="35"><select name="material_no" id="material_no">
                     <option value="NULL" placeholder="Select Material No."> -- Select Material No. --</option>
                     <?php
	               $query2 = "SELECT * FROM consumable_detail WHERE con_status = 'Y' ORDER BY material_no ASC";
                   $result2 = mysqli_query($dbc, $query2);
  
                   while($row2=mysqli_fetch_array($result2)) 
			      {
                  echo'<option value="',$row2["id_con"],'">',stripslashes($row2["material_no"]),' - ',stripslashes($row2["mat_desc"]),'</option>';
                  }
				?>
                   </select>
                   *</td>
                    <td width="20%">&nbsp;</td>
               </tr>
               <tr>
                 <td width="13%" height="35">Quantity</td>
                 <td width="4%" height="35">:</td>
                 <td width="63%" height="35"><input name="con_qty" type="text" size="10"/>                 </td>
                 <td width="20%">&nbsp;</td>
               </tr>
               <tr>
                 <td height="19"><input name="user_no" type="hidden" value="<?php echo $res["user_no"]; ?>" /></td>
                 <td height="19">&nbsp;</td>
                 <td height="10" colspan="2"><div align="right">
                     <input name="saveT" type="submit" id="submit" value="+ Add Consumable Item" class="btn btn-warning" onclick="return confirm('Confirm to add request?');" />
                     <input name="lastID" type="hidden" value="<?php echo $lastID; ?>">
                     <input name="date1" type="hidden" value="<?php echo h($date1); ?>">
                     <input name="t_time" type="hidden" value="<?php echo h($t_time); ?>">
                     <input name="factory" type="hidden" value="<?php echo h($factory); ?>">
                 </div></td>
               </tr>
               <tr>
                 <td height="35" colspan="4">* Compulsary field</td>
               </tr>
             </table></td>
             </table>
          </form>
       
        <?php
	       
		   // $material_no = $_GET["material_no"];
			//$lastID = $_GET["lastID"];
			//$con_qty = $_GET["con_qty"];
			?>
       
           
     
            <!-- <div align="center"> <hr width="90%" class="style-six"/>&nbsp; </hr></div>  -->
             <table width="98%" height="32" border="1" cellpadding="3" cellspacing="1" bordercolor="#CCCCCC" style="background-image: -webkit-linear-gradient(top, #FFFFFF 20%, #999966 90%);">
               <tr>
                
                 <td width="44%"><div align="center">Consumable Description</div></td>
                 <td width="32%"><div align="center">Quantity</div></td>
                 <td width="18%"><div align="center">UoM</div></td>
                 <td width="6%">&nbsp;</td>
               </tr>
             </table>
           <!--     <form name="form2" method="post" action="material_consumable_request2.php">
              -->
      <form name="form2" method="post" action="material_consumable_request2.php?lastID=<?php echo $lastID; ?>&&date1=<?php echo h($date1); ?>&&t_time=<?php echo h($t_time); ?>&&factory=<?php echo h($factory); ?>" >
             <?php
			
			  $i = 1;
			  
			$query_data2 = "SELECT * FROM consumable_request WHERE id_scan = '".db_esc($dbc, $lastID)."' AND status_request = 'N' AND user_create = '".db_esc($dbc, $res["user_no"])."'";
            $result_data2 = mysqli_query($dbc, $query_data2) or die(db_fail($dbc));
           
		   while ($row_data2 = mysqli_fetch_array($result_data2))
		   {
			 
			 $query_con_detail = "SELECT * FROM consumable_detail WHERE id_con = '".db_esc($dbc, $row_data2["id_con"])."'";
			 $result_con_detail = mysqli_query($dbc, $query_con_detail) or die(db_fail($dbc));
			 $row_con_detail = mysqli_fetch_array($result_con_detail);
			 
			 ?>
             
             <table width="98%" height="26" border="0" cellspacing="2" bgcolor="#eee">
               <tr> 
                 <td width="21%"><?php echo  $row_data2["material_no"]; ?>
                 
                  <input name="id_req_con[<?php echo $i; ?>]" type="hidden" value="<?php echo  $row_data2["id_req_con"]; ?>" /></td>
                
                 <td width="21%"><?php echo  $row_con_detail["mat_desc"]; ?></td>
                 <td width="32%"><div align="center">
                 <input name="con_qty[<?php echo $i; ?>]" type="text" value="<?php  echo $row_data2["con_qty"];   ?>" size="10"/>
               
                 </div></td>
                 <td width="20%"><div align="center"><?php echo $row_data2["con_uom"]; ?></div></td>
                 <td width="6%">
                 <div align="center"><a href="delete_consumable_request.php?id_req_con=<?php echo $row_data2["id_req_con"]; ?>&&lastID=<?php echo $lastID; ?>&&date1=<?php echo h($date1); ?>&&t_time=<?php echo h($t_time); ?>&&factory=<?php echo h($factory); ?>" onclick="return confirm('Are you sure you want to delete?')"><img src="../img/delete.png" width="16" height="16"  /></a></div></td>
               </tr>
             </table>
             
            <?php  
			 
			 $i++;
			 
			 }
			// mysqli_close($dbc);
			 
			 ?>
             <br />
             

             
             <div class="buttons">
           
                   <input name="confirm_button" type="submit" id="submit2" value="POST" class="btn btn-success" onClick="return confirm('Confirm to post request?');">
          </div>
        </form>
          
 </div>



</div></div></div></div>
</div>
<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part--> 

<script src="../js/jquery.min.js"></script> 
<script src="../js/jquery.ui.custom.js"></script> 
<script src="../js/bootstrap.min.js"></script> 
<script src="../js/jquery.uniform.js"></script> 
<script src="../js/select2.min.js"></script> 
<script src="../js/jquery.dataTables.min.js"></script> 
<script src="../js/matrix.js"></script> 
<script src="../js/matrix.tables.js"></script>

</body>
</html>