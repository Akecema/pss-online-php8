<?php

/**
 * prod/material_consumable_request_backup_v2.php
 * Part of: Production module
 * Filename suggests: material consumable request backup v2
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, consumable_detail, consumable_request, factory_detail.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, header.inc.
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

$today = getdate();
$hours = $today['hours']; 
$minutes = $today['minutes'];
$seconds = $today['seconds'];
$month = $today['mon']; 
$mday = $today['mday']; 
$year = $today['year']; 

$query_u = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
$result_u = mysqli_query($dbc, $query_u);   //run the query.
$data_u = mysqli_fetch_array($result_u);   //how many records are there?       

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Ingress Autoventures Co., Ltd.</title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../css/style.css" type="text/css" media="all" />
<link href="../css/ddtabmenu.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../scripts/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="../javascript/jquery-latest.js"></script> 
<script type="text/javascript" src="../javascript/thickbox.js"></script>	
<script language="javascript" src="../calendar/calendar.js"></script>

</head>
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
$url = 'material_consumable_request.php';
$url2 = 'display_consumable_request.php';
$url3 = 'posting_consumable_request.php';

?>
<script type="text/javascript">
//SYNTAX: ddtabmenu.definemenu("tab_menu_id", integer OR "auto")
ddtabmenu.definemenu("ddtabs1", 0) //initialize Tab Menu #1 with 1st tab selected
ddtabmenu.definemenu("ddtabs2", 1) //initialize Tab Menu #2 with 2nd tab selected
ddtabmenu.definemenu("ddtabs3", 1) //initialize Tab Menu #3 with 2nd tab selected
ddtabmenu.definemenu("ddtabs4", 2) //initialize Tab Menu #4 with 3rd tab selected
ddtabmenu.definemenu("ddtabs5", -1) //initialize Tab Menu #5 with NO tabs selected (-1)
</script>
<script language="javascript" type="text/javascript">

document.getElementById("material_no").selectedIndex = -1;

function updateTextBox() {
    var orderStatusSelect = document.getElementById("material_no");
    var status = orderStatusSelect.options[orderStatusSelect.selectedIndex].text;
    var myTextBox = document.getElementById("comment");
	
	<?php
	
	$query_unit = "SELECT * FROM consumable_detail WHERE id_con = '".db_esc($dbc, $material_no)."'";
$result_unit = mysqli_query($dbc, $query_unit) or die(db_fail($dbc));
$row_unit = mysqli_fetch_array($result_unit);
	
	
	
	?>
    
    if(status == "orderStatusSelect") {
        //set the value of the text box
        myTextBox.value = "We are now processing your request. Please wait for X days and we would inform you shortly." ;
    } else if(status == "2") {
        myTextBox.value = "We have cancelled your request!";
    } else if(status == "8") {
        myTextBox.value = "another message goes here!";
    }
    //and so on for all different options
}
</script>
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
<body>
<!-- Header -->
<!-- End Header -->
<!-- Container -->
<div id="container"> 
  <div class="small-nav">Consumable Request Initial Screen</div>
  <div class="shell">
<div id="ddtabs1" class="basictab">
<uk>
<lk class="select"><a href="material_consumable_request.php?page=<?php echo encode($url,5); ?>"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;New Request&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a></lk>
<lk ><a href="display_consumable_request.php?page=<?php echo encode($url2,5); ?>"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Display Request&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a></lk>
<lk ><a href="posting_consumable_request.php?page=<?php echo encode($url3,5); ?>"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Posting Request&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a></lk>

</uk>
</div>
    <p>
      <!-- End Message OK -->
      <!-- Message Error -->
      <!-- End Message Error -->
      <br />
      <!-- Main -->
    </p>
    <br /><br />
    <div id="main">
      <div class="cl">&nbsp;</div>
      <!-- Content -->
      <div id="content">
       <?php
	   
  $lastID = 0;
  	   
// Set the page title and include the HTML header.
//include ('templates/header.inc');

if(isset($_POST["save"]) && $_POST!=="") 
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
   $date1 = $_POST["date1"];
   $time1 = $_POST["time1"]; 
   $time2 = $_POST["time2"];
   $factory = $_POST["factory"];
   $work_center = $_POST["work_center"];
  
     $date_arini = date('Y-m-d'); 
       $current_date = date('Y-m-d H:i:s'); 
	   $next_date = date('Y-m-d H:i:s', strtotime($current_date .' +2 day'));
 
     //check only deilvery date
	 
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
  
    
	 
					  
				 $date_date = (($_POST["date1"])." ".($_POST["time1"]).":".($_POST["time2"]).":00");
				  
				  if($date_date >= $current_date)
				  {
				     if($date_date > $next_date)
					 {
					    $message.= '<p>You are not allowed to request for more than 2 days advance!</p>';
						  $date1 = FALSE;
						  
					   
						}
						
					 elseif($date_date < $current_date)
						{
						
						   $message.= '<p>You are not allowed to request backdated date and time!</p>';
						  $date1 = FALSE;
						}
			
					   else{
					  
					      if(isset($_POST["time1"]) < ($hours))
						 {
						  $time1 = FALSE;
					      $message.= '<p>You are not allowed to request backdated time!</p>';
						  $date1 = FALSE;
					      }
					  
					     $date1 = TRUE;
						
						}
	                 }else{
						$message.= '<p>You are not allowed to request backdated date and time!</p>';
						  $date1 = FALSE;
						  
						  }
						  
				      
					
				if(($_POST["time1"]) == "NULL")
				{
				  $time1 = FALSE;
				  $message.= '<p>You are required to select Hours!</p>';
				  }else{
				  $time1 = TRUE;
				  }
				  
				  if(($_POST["time2"]) == "NULL")
				{
				  $time2 = FALSE;
				  $message.= '<p>You are required to select Minutes!</p>';
				  } else{
				  $time2 = TRUE;
				  }

// check for a factory
if((empty($_POST["factory"])) || ($_POST["factory"] == "NULL"))
{ 
  $factory = FALSE;
  $message.= '<p>You are required to select FACTORY!</p>';
  }


// check for a work center
if((empty($_POST["work_center"])) || ($_POST["work_center"] == "NULL"))
{ 
  $work_center = FALSE;
  $message.= '<p>You are required to select Production Line/ Work Center!</p>';
  }


 if($material_no && $con_qty && $time1 && $time2 && $date1 && $factory && $work_center) //everything ok
{  

 //---------------------------------------------------------------------------//
  $t_time = (($_POST["time1"]).":".($_POST["time2"]));  
  $date1 = $_POST["date1"];
  

$query_data = "SELECT * FROM consumable_detail WHERE id_con = '".db_esc($dbc, $material_no)."'";
$result_data = mysqli_query($dbc, $query_data) or die(db_fail($dbc));
$row_data = mysqli_fetch_array($result_data);

	   //create ID scan
$query_create_id = "SELECT MAX(id_scan) FROM consumable_request WHERE status_request = 'Y'";
$result_create_id = mysqli_query($dbc, $query_create_id);

if ($result_create_id) {

$nrows_create_id = mysqli_num_rows($result_create_id);
$row_create_id = mysqli_fetch_row($result_create_id);


 $dht = "00000";

 
 if($row_create_id[0] <= 0)
  { 
   
    $lastID = ($row_create_id[0] + 1);
    $dg = ($dht + ($lastID));

   }else{
      $lastID = ($row_create_id[0] + 1);
      $dg =  $lastID;
    }
	
	} // end $result_create_id   
	   
  $_SESSION['lastID'] = $lastID;


   
//insert to scan_detail
$query_db = "INSERT INTO `consumable_request` (id_req_con, mrin_doc, mrin_year, temp_mrin, id_con, id_scan, material_no, con_qty, con_uom, status_request, status_print, status_view, factory, user_create, date_create, user_update, date_update, date_posting, time_posting, status, date_require, time_require, id_work) VALUES ('".mysqli_insert_id($dbc)."', '', '','', '".db_esc($dbc, $material_no)."', '".db_esc($dbc, $lastID)."','".db_esc($dbc, $row_data["material_no"])."', '".db_esc($dbc, $con_qty)."', '".db_esc($dbc, $row_data["BUn"])."', 'N', 'N','N','".db_esc($dbc, $factory)."', '".db_esc($dbc, $user_no)."', NOW(),'','','','','New','".db_esc($dbc, $date1)."','".db_esc($dbc, $t_time)."','".db_esc($dbc, $work_center)."')";
$result = mysqli_query($dbc, $query_db) or die(db_fail($dbc));




            echo "<script>";
		   echo "window.location='material_consumable_request2.php?material_no=$material_no&&lastID=$lastID&&con_qty=$con_qty&&date1=$date1&&t_time=$t_time&&factory=$factory&&work_center=$work_center'";
            echo "</script>";
            exit(); //quit the script


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

 $material_no = $_POST["material_no"];
   $user_no = $_POST["user_no"];
   $con_qty = $_POST["con_qty"];
   $date1 = $_POST["date1"];
   $time1 = $_POST["time1"]; 
   $time2 = $_POST["time2"];
   $factory = $_POST["factory"];
   $work_center = $_POST["work_center"];
  
     $date_arini = date('Y-m-d'); 
       $current_date = date('Y-m-d H:i:s'); 
	   $next_date = date('Y-m-d H:i:s', strtotime($current_date .' +2 day'));
 
     //check only deilvery date
	 
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
  
    
	 
					  
				 $date_date = (($_POST["date1"])." ".($_POST["time1"]).":".($_POST["time2"]).":00");
				  
				  if($date_date >= $current_date)
				  {
				     if($date_date > $next_date)
					 {
					    $message.= '<p>You are not allowed to request for more than 2 days advance!</p>';
						  $date1 = FALSE;
						  
					   
						}
						
					 elseif($date_date < $current_date)
						{
						
						   $message.= '<p>You are not allowed to request backdated date and time!</p>';
						  $date1 = FALSE;
						}
			
					   else{
					  
					      if(isset($_POST["time1"]) < ($hours))
						 {
						  $time1 = FALSE;
					      $message.= '<p>You are not allowed to request backdated time!</p>';
						  $date1 = FALSE;
					      }
					  
					     $date1 = TRUE;
						
						}
	                 }else{
						$message.= '<p>You are not allowed to request backdated date and time!</p>';
						  $date1 = FALSE;
						  
						  }
						  
				      
					
				if(($_POST["time1"]) == "NULL")
				{
				  $time1 = FALSE;
				  $message.= '<p>You are required to select Hours!</p>';
				  }else{
				  $time1 = TRUE;
				  }
				  
				  if(($_POST["time2"]) == "NULL")
				{
				  $time2 = FALSE;
				  $message.= '<p>You are required to select Minutes!</p>';
				  } else{
				  $time2 = TRUE;
				  }

// check for a factory
if((empty($_POST["factory"])) || ($_POST["factory"] == "NULL"))
{ 
  $factory = FALSE;
  $message.= '<p>You are required to select FACTORY!</p>';
  }


// check for a work center
if((empty($_POST["work_center"])) || ($_POST["work_center"] == "NULL"))
{ 
  $work_center = FALSE;
  $message.= '<p>You are required to select Production Line/ Work Center!</p>';
  }


 if($material_no && $con_qty && $time1 && $time2 && $date1 && $factory && $work_center) //everything ok
{  

 //---------------------------------------------------------------------------//
  $t_time = (($_POST["time1"]).":".($_POST["time2"]));  
  $date1 = $_POST["date1"];
  
  $query_data = "SELECT * FROM consumable_detail WHERE id_con = '".db_esc($dbc, $material_no)."'";
$result_data = mysqli_query($dbc, $query_data) or die(db_fail($dbc));
$row_data = mysqli_fetch_array($result_data);
  
   //--------------------------------create (temporary MRIN)-----------------------------
$query_id_2 = "SELECT MAX(mrin_doc) FROM consumable_request";
$result_id_2 = mysqli_query($dbc, $query_id_2);

if ($result_id_2) {
$nrows_2 = mysqli_num_rows($result_id_2);
$row_id_2 = mysqli_fetch_row($result_id_2);

 $dht_2 = "00000";

  if($row_id_2[0] <= 0)
  { 
   
    $lastID_2 = ($row_id_2[0] + 1);
    $dg_2 = ($dht_2 + ($lastID_2));

   }else{
      $lastID_2 = ($row_id_2[0] + 1);
      $dg_2 =  $lastID_2;
    }

	 $number = $dg_2; // Length of the supplied number is 3
	 $number = sprintf('%05d', $number);

  $ref = ('C'.$year.$number);  
		  
  } // end if $result_id
 

  
//insert to scan_detail
$query_db = "INSERT INTO `consumable_request` (id_req_con, mrin_doc, mrin_year, temp_mrin, id_con, id_scan, material_no, con_qty, con_uom, status_request, status_print, status_view, factory, user_create, date_create, user_update, date_update, date_posting, time_posting, status, date_require, time_require, id_work) VALUES ('".mysqli_insert_id($dbc)."', '$number', '".db_esc($dbc, $year)."','".db_esc($dbc, $ref)."', '".db_esc($dbc, $material_no)."', '".db_esc($dbc, $lastID)."','".db_esc($dbc, $row_data["material_no"])."', '".db_esc($dbc, $con_qty)."', '".db_esc($dbc, $row_data["BUn"])."', 'Y', 'N','N','".db_esc($dbc, $factory)."', '".db_esc($dbc, $user_no)."', NOW(),'','',NOW(),NOW(),'New','".db_esc($dbc, $date1)."','".db_esc($dbc, $t_time)."','".db_esc($dbc, $work_center)."')";
$result = mysqli_query($dbc, $query_db) or die(db_fail($dbc));


          if($result)
           {
		   
		   echo "<script>";
		   echo "alert('MRIN NO :$ref successfully posted.');"; 
		 // echo "window.location='display_consumable_request.php'";
		   echo "parent.location='index_production.php'";
	       echo "</script>"; 
			 exit(); //quit the script
			  
             }
             else 
			 {
             $message = '<p> CANNOT CREATE MATERIAL REQUEST!!!. </p>';
              mysqli_close($dbc); //close db
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
          <!-- Box Head -->
          <div class="box-head">
            <h2>Consumable Request</h2>
          </div>
          <!-- End Box Head -->
         <form name="form1" method="post" action="material_consumable_request.php?material_no=<?php echo $material_no; ?>&&lastID=<?php echo $lastID; ?>&&con_qty=<?php echo $con_qty; ?>&&date1=<?php echo $date1; ?>&&t_time=<?php echo $t_time; ?>&&factory=<?php echo $factory; ?>&&work_center=<?php echo $work_center; ?>">
            <!-- Form -->
            <div class="form">
            <table width="99%" border="0" cellspacing="2">
              <tr>
                <td width="17%" height="35">Required Date</td>
                <td width="1%" height="35">:</td>
                <td width="41%" height="35"><font color="#006699">
                  <?php
    
	               $dt = $today['mday'];
                   $mt = $today['mon'];
                   $yr = $today['year'];
	 
	                  $myCalendar = new tc_calendar("date1", true);
					  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					  $myCalendar->setDate($dt,$mt,$yr);
					  $myCalendar->setPath("/calendar/");
					  $myCalendar->setYearInterval(2001, 2030);
					  // $myCalendar->setOnChange("myChanged('test')");
					  $myCalendar->writeScript();
					  
?></font></td>
                <td width="16%">Required Time </td>
                <td width="2%">:</td>
                <td width="8%"><select name="time1" id="time1">
                    <?php if(($_POST["confirm_button"] == true)) 
		{  
		?>
                    <option value="<?php echo h($_POST["time1"]); ?>"><?php echo sprintf('%02d', $_POST["time1"]);	 ?></option>
                    <?php
	 }else{
	 ?>
                    <option value="NULL" placeholder="HOURS">&nbsp;</option>
                    <?php
	  }
	  
      for($i2 = 0; $i2 <= 23; $i2++): ?>
                    <option value="<?= $i2; ?>"> <?php echo sprintf('%02d', $i2); ?> </option>
                    <?php endfor; ?>
                  </select>
                :</td>
                <td width="15%"><select name="time2" id="time2">
                    <?php if(($_POST["confirm_button"] == true)) 
		{  
		?>
                    <option value="<?php echo h($_POST["time2"]); ?>"><?php echo sprintf('%02d', $_POST["time2"]);	 ?></option>
                    <?php
	 }else{
	 ?>
                    <option value="NULL" placeholder="MINUTES">&nbsp;</option>
                    <?php
	  }
      for($j = 0; $j <= 59; $j++): ?>
                    <option value="<?= $j; ?>"> <?php echo sprintf('%02d', $j); ?> </option>
                    <?php endfor; ?>
                </select></td>
              </tr>
              <tr>
                <td width="17%" height="35">Factory</td>
                <td width="1%" height="35">:</td>
                <td height="35" colspan="5"><font color="#006699">
                  <select name="factory" id="factory" onChange="getFactory(this.value)">
                    <option value="NULL" placeholder="Select Factory"> -- Select Factory --</option>
                    <?php
	       $query3 = "SELECT * FROM factory_detail GROUP BY factory_desc2 ORDER BY id_fac ASC";
                   $result3 = mysqli_query($dbc, $query3);
  
                   while($row3=mysqli_fetch_array($result3, MYSQLI_NUM)) 
			      {
                  echo'<option value="',$row3[2],'">',stripslashes($row3[1]),'</option>';
                  }
				?>
                  </select>
*</font></td>
              </tr>
               <tr>
                <td width="17%" height="35">Production Line/Work Center</td>
                <td width="1%" height="35">:</td>
                <td height="35" colspan="5">  
                <div id="work_centerdiv"> 
                <select name="work_center" id="work_center">
                <option value="NULL" placeholder="Select Production Line/ Work Center"> -- Select Production Line/ Work Center --</option>
                </select> <font color="#006699">  *</font></div>
               </td> 
              </tr>
               <tr>
                <td height="35">Material No. </td>
                <td height="35">:</td>
                <td height="35" colspan="5"><select name="material_no" id="material_no">
                  <option value="NULL" placeholder="Select Material No."> -- Select Material No. --</option>
                  <?php
	               $query2 = "SELECT * FROM consumable_detail ORDER BY material_no ASC";
                   $result2 = mysqli_query($dbc, $query2);
  
                   while($row2=mysqli_fetch_array($result2, MYSQLI_NUM)) 
			      {
                  echo'<option value="',$row2[0],'">',stripslashes($row2[1]),' - ',stripslashes($row2[2]),'</option>';
                  }
				?>
                </select>
                 <font color="#006699">*</font></td>
              </tr>
              <tr>
                <td height="35">Quantity</td>
                <td height="35">:</td>
                <td height="35" colspan="5"><input name="con_qty" type="text" value="<?php if(isset($_POST["con_qty"])) { echo h($_POST["con_qty"]); } ?>" size="10"/>
                <font color="#006699">*</font>
                </td>
              </tr>
              <tr>
                <td height="19"><input name="user_no" type="hidden" value="<?php echo $data_u["user_no"]; ?>" /></td>
                <td height="19">&nbsp;</td>
                <td height="10" colspan="5"><div align="right">
                    <input name="save" type="submit" id="submit" value="+ Add Consumable Item" class="button" onclick="return confirm('Confirm to add request?');"  />&nbsp;&nbsp;<input name="confirm_button" type="submit" id="submit2" value="Confirm Request" class="button" onClick="return confirm('Confirm to post request?');">
                </div></td>
              </tr>
              <tr>
                <td height="35" colspan="7"><font color="#006699">*</font> Compulsary field</td>
              </tr>
            </table>
         </form>
         
        </div>
            <!-- End Form -->
            <!-- Form Buttons -->
           
            <!-- End Form Buttons -->
    
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

<!-- End Container -->

</body>
</html>
