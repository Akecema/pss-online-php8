<?php
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

date_default_timezone_set('Asia/Kuala_Lumpur');

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

$url = "material_consumable_request.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
    $result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
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
	   
  $lastID = 0;
  	   
// Set the page title and include the HTML header.
//include ('templates/header.inc');

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
  

$query_data = "SELECT * FROM consumable_detail WHERE id_con = '$material_no'";
$result_data = mysqli_query($dbc, $query_data) or die (mysqli_error($dbc));
$row_data = mysqli_fetch_array($result_data);

	   //create ID scan
	   

$query_create_id = "SELECT * FROM consumable_request WHERE id_scan = (SELECT MAX(id_scan) FROM consumable_request) ";
$result_create_id = mysqli_query($dbc, $query_create_id);

if ($result_create_id) {

$nrows_create_id = mysqli_num_rows($result_create_id);
$row_create_id = mysqli_fetch_array($result_create_id);


 $dht = "00000";

 
 if($row_create_id["id_scan"] <= 0)
  { 
   
    $lastID = ($row_create_id["id_scan"] + 1);
    $dg = ($dht + ($lastID));

   }else{
      $lastID = ($row_create_id["id_scan"] + 1);
      $dg =  $lastID;
    }
	
	$lastID = $dg;
	
	} // end $result_create_id   
	 

 // $_SESSION['lastID'] = $lastID;

//insert to scan_detail
$query_db = "INSERT INTO consumable_request (id_req_con, mrin_doc, mrin_year, temp_mrin, id_con, id_scan, material_no, con_qty, con_uom, status_request, status_print, status_view, factory, user_create, date_create, user_update, date_update, date_posting, time_posting, status, date_require, time_require, id_work) VALUES ('".mysqli_insert_id($dbc)."', '', '','', '$material_no', '".$lastID."','".$row_data["material_no"]."', '$con_qty', '".$row_data["BUn"]."', 'N', 'N','N','$factory', '$user_no', NOW(),'','','','','New','$date1','$t_time','$work_center')";
$result = mysqli_query($dbc, $query_db) or die (mysqli_error($dbc));




            echo "<script>";
		   echo "window.location='material_consumable_request2.php?material_no=$material_no&&lastID=$lastID&&con_qty=$con_qty&&date1=$date1&&t_time=$t_time&&factory=$factory&&work_center=$work_center'";
            echo "</script>";
            exit(); //quit the script


}
//print the message if there is one.
	 
 
 	  
if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}

      /*  unset($_SESSION["lastID"]);
		session_unset();
		session_destroy(); */  
}
?>
<?php

 if (isset($_POST["submit"])) 
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
  
  $query_data = "SELECT * FROM consumable_detail WHERE id_con = '$material_no'";
$result_data = mysqli_query($dbc, $query_data) or die (mysqli_error($dbc));
$row_data = mysqli_fetch_array($result_data);
  

   //--------------------------------create (temporary MRIN)-----------------------------
$query_id_2 = "SELECT * FROM consumable_request WHERE id_scan = (SELECT MAX(id_scan) FROM consumable_request)";
$result_id_2 = mysqli_query($dbc, $query_id_2);

if ($result_id_2) {
$nrows_2 = mysqli_num_rows($result_id_2);
$row_id_2 = mysqli_fetch_array($result_id_2);

 $dht_2 = "00000";

  if($row_id_2["id_scan"] <= 0)
  { 
   
    $lastID_2 = ($row_id_2["id_scan"] + 1);
    $dg_2 = ($dht_2 + ($lastID_2));

   }else{
      $lastID_2 = ($row_id_2["id_scan"] + 1);
      $dg_2 =  $lastID_2;
    }

	 $number = $dg_2; // Length of the supplied number is 3
	 $number = sprintf('%05d', $number);

  $ref = ('C'.$year.$number);  
		  
  } // end if $result_id
 

  
//insert to scan_detail
$query_db = "INSERT INTO consumable_request(id_req_con, mrin_doc, mrin_year, temp_mrin, id_con, id_scan, material_no, con_qty, con_uom, status_request, status_print, status_view, factory, user_create, date_create, user_update, date_update, date_posting, time_posting, status, date_require, time_require, id_work) VALUES('".mysqli_insert_id($dbc)."', '$number', '$year','$ref', '$material_no', '$lastID_2', '".$row_data["material_no"]."',  '$con_qty', '".$row_data["BUn"]."', 'Y', 'N','N','$factory', '$user_no', NOW(), '', '', NOW() ,NOW(), 'New', '$date1', '$t_time', '$work_center')";
$result = mysqli_query($dbc, $query_db) or die (mysqli_error($dbc));


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
             // mysqli_close($dbc); //close db
             }  
   
   
   	
} // end IF
		

//print the message if there is one.
if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}
} // end IF confirm_button [POST]

?>
     
         <form name="form1" method="post" action="material_consumable_request.php">
           
             <table class="table table-bordered table-striped">
              <tr>
                <th>Required Date <font color="#FF0000"><b> *</b></font></th>
                <th>:</th>
                <td>
                  <?php
  
				 if(isset($_POST['date1']))
								{ 
									
									//GET value
									$dd1 = substr($_POST['date1'],8,2);
									$mm1 = substr($_POST['date1'],5,2);
									$yy1 = substr($_POST['date1'],0,4);
									
									$myCalendar = new tc_calendar("date1", true, false);
									$myCalendar->setIcon("../calendar/images/iconCalendar.gif");
									$myCalendar->setDate($dd1, $mm1, $yy1);
									$myCalendar->setPath("/calendar/");
									$myCalendar->setYearInterval(2010, 2030);
									// $myCalendar->setOnChange("myChanged('test')");
									$myCalendar->writeScript();
									
								}
								else	 
								{
                                      
										$dt = $today['mday'];
										$mt = $today['mon'];
										$yr = $today['year'];
									 
										$myCalendar = new tc_calendar("date1", true, false);
										$myCalendar->setIcon("../calendar/images/iconCalendar.gif");
										$myCalendar->setDate($dt, $mt, $yr);
										$myCalendar->setPath("/calendar/");
										$myCalendar->setYearInterval(2010, 2030);
										// $myCalendar->setOnChange("myChanged('test')");
										$myCalendar->writeScript();
				
									}
			 ?>
					  
            </td>
                <th>Required Time <font color="#FF0000"><b> *</b></font></th>
                <th>:</th>
                <td><select name="time1" id="time1">
                    <?php if(($_POST["confirm_button"] == true)) 
		{  
		?>
                    <option value="<?php echo $_POST["time1"]; ?>"><?php echo sprintf('%02d', $_POST["time1"]);	 ?></option>
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
                <td><select name="time2" id="time2">
                    <?php if(($_POST["confirm_button"] == true)) 
		{  
		?>
                    <option value="<?php echo $_POST["time2"]; ?>"><?php echo sprintf('%02d', $_POST["time2"]);	 ?></option>
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
                <th>Factory <font color="#FF0000"><b> *</b></font></th>
                <th>:</th>
                <td  colspan="5">
                  <select name="factory" id="factory" onChange="getFactory(this.value)">
                    <option value="NULL" placeholder="Select Factory"> -- Select Factory --</option>
                    <?php
	       $query3 = "SELECT * FROM factory_detail GROUP BY factory_desc2 ORDER BY id_fac ASC";
                   $result3 = mysqli_query($dbc, $query3);
  
                   while($row3=mysqli_fetch_array($result3)) 
			      {
                  echo'<option value="',$row3["factory_desc2"],'">',stripslashes($row3["factory_desc"]),'</option>';
                  }
				?>
                  </select>
               </td>
              </tr>
               <tr>
                <th>Production Line/Work Center <font color="#FF0000"><b> *</b></font></th>
                <th>:</th>
                <td  colspan="5">  
                <div id="work_centerdiv"> 
                <select name="work_center" id="work_center">
                <option value="NULL" placeholder="Select Production Line/ Work Center"> -- Select Production Line/ Work Center --</option>
                </select> </div>
               </td> 
              </tr>
               <tr>
                <th>Material No. <font color="#FF0000"><b> *</b></font></th>
                <th>:</th>
                <td colspan="5"><select name="material_no" id="material_no">
                  <option value="NULL" placeholder="Select Material No."> -- Select Material No. --</option>
                  <?php
	               $query2 = "SELECT * FROM consumable_detail WHERE con_status = 'Y' ORDER BY material_no ASC";
                   $result2 = mysqli_query($dbc, $query2);
  
                   while($row2=mysqli_fetch_array($result2)) 
			      {
                  /*echo'<option value="',$row2[0],'" data-material_no="',$row2[1],'" data-mat_desc="',$row2[2],'">',stripslashes($row2[1]),' - ',stripslashes($row2[2]),'</option>'; */
				   echo'<option value="',$row2["id_con"],'" >',stripslashes($row2["material_no"]),' - ',stripslashes($row2["mat_desc"]),'</option>';
				  
				  
                  }
				?>
                </select>
              </td>
              </tr>
              <tr>
                <th>Quantity <font color="#FF0000"><b> *</b></font></th>
                <th>:</th>
                <td  colspan="5"><input name="con_qty" type="text" value="<?php if(isset($_POST["con_qty"])) { echo $_POST["con_qty"]; } ?>" size="10"/>
                </td>
              </tr>
              <tr>
                <th><input name="user_no" type="hidden" value="<?php echo $res["user_no"]; ?>" /></th>
                <th>&nbsp;</th>
                <td colspan="5"><div align="right">
            <input name="saveT" type="submit" id="submit" value="+ Add Consumable Item" class="btn btn-info" onclick="return confirm('Confirm to add item?');"  />&nbsp;&nbsp; <input name="submit" type="submit" id="submit2" value="Confirm Request" class="btn btn-success" onClick="return confirm('Confirm to post request?');"  />
                </div></td>
              </tr>
              <tr>
                <td colspan="7"><font color="#FF0000"><b>  * Compulsory field</b></font></td>
              </tr>
            </table>
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