<?php
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
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

$query_u = "SELECT * FROM user_detail WHERE username = '$username'";
$result_u = mysql_query($query_u);   //run the query.
$data_u = mysql_fetch_array($result_u);   //how many records are there?       

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
<script type="text/javascript">// < ![CDATA[
// < ![CDATA[
// < ![CDATA[
function show1(){ document.getElementById('div1').style.display ='none'; } 
function show2(){ document.getElementById('div1').style.display = 'block'; }
// ]]></script>
<style><!--
.hide { display: none; }
hr.style-two {
    border: 0;
    height: 1px;
    background-image: -webkit-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
    background-image:    -moz-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
    background-image:     -ms-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
    background-image:      -o-linear-gradient(left, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0)); 
}
hr.style-six {
    border: 0;
    height: 0;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.3);
}
--></style>

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
     $current_date = date('Y-m-d H:i:s'); 
	 $next_date = date('Y-m-d H:i:s', strtotime($current_date .' +2 day'));

   $date1 = $_GET["date1"];
   $t_time = $_GET["t_time"]; 
   $factory = $_GET["factory"];
  // $work_center = $_GET["work_center"];
   
if(isset($_POST["save"]) && $_POST!=="") 
{ // handle the form.

// create a function for escaping the data.
function escape_data ($data) {
global $dbc;   // need the connection.
if(ini_get('magic_quotes_gpc')) {
    $data = stripslashes($data);
	}
	return mysql_real_escape_string($data,$dbc);
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

$query_data = "SELECT * FROM consumable_detail WHERE id_con = '$material_no'";
$result_data = mysql_query($query_data) or die (mysql_error());
$row_data = mysql_fetch_array($result_data);



	   
	   //create ID scan
$query_create_id = "SELECT MAX(id_scan) FROM consumable_request WHERE status_request = 'Y'";
$result_create_id = mysql_query($query_create_id);

if ($result_create_id) {

$nrows_create_id = mysql_num_rows($result_create_id);
$row_create_id = mysql_fetch_row($result_create_id);


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
$query_db = "INSERT INTO `consumable_request` (id_req_con, mrin_doc, mrin_year, temp_mrin, id_con, id_scan, material_no, con_qty, con_uom, status_request, status_print, status_view, factory, user_create, date_create, user_update, date_update, date_posting, time_posting, status, date_require, time_require, id_work) VALUES ('".mysql_insert_id()."', '', '','', '$material_no', '$lastID','".$row_data["material_no"]."', '$con_qty', '".$row_data["BUn"]."', 'N', 'N', 'N', '$factory', '$user_no', NOW(),'','','','','New','$date1','$t_time','$work_center')";
$result = mysql_query($query_db) or die (mysql_error());


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
	return mysql_real_escape_string($data,$dbc);
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
$query_id_2 = "SELECT MAX(mrin_doc) FROM consumable_request";
$result_id_2 = mysql_query($query_id_2);

if ($result_id_2) {
$nrows_2 = mysql_num_rows($result_id_2);
$row_id_2 = mysql_fetch_row($result_id_2);

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
 
	
 if($con_qty ) //everything ok
{  	
 
  
  while ($i < $size2) {

  
//update table material request with new quantity

$query_update = "UPDATE consumable_request SET status_request = 'Y', mrin_doc = '$number', mrin_year = '$year', temp_mrin = '$ref', con_qty = '".$_POST["con_qty"][$i]."', user_update = '".$data_u["user_no"]."', date_update = NOW(), date_posting = NOW(), time_posting = NOW() WHERE id_req_con = '".$_POST["id_req_con"][$i]."' AND user_create = '".$data_u["user_no"]."' ";

$result_update = mysql_query($query_update);

	 $i++;
   } // end while loop	
   
          if($result_update)
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
              mysql_close(); //close db
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
	       $query4 = "SELECT * FROM work_center_detail WHERE id_factory = '".$factory."' ORDER BY id_work ASC";
                   $result4 = mysql_query($query4);
  
                   while($row4=mysql_fetch_array($result4, MYSQL_NUM)) 
			      {
                  echo'<option value="',$row4[0],'">',stripslashes($row4[0]),' - ',stripslashes($row4[2]),'</option>';
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
                   $result2 = mysql_query($query2);
  
                   while($row2=mysql_fetch_array($result2, MYSQL_NUM)) 
			      {
                  echo'<option value="',$row2[0],'">',stripslashes($row2[1]),' - ',stripslashes($row2[2]),'</option>';
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
                 <td height="19"><input name="user_no" type="hidden" value="<?php echo $data_u["user_no"]; ?>" /></td>
                 <td height="19">&nbsp;</td>
                 <td height="10" colspan="2"><div align="right">
                     <input name="save" type="submit" id="submit" value="+ Add Consumable Item" class="button" onclick="return confirm('Confirm to add request?');" />
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
			$lastID = $_GET["lastID"];
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
        <form name="form2" method="post" action="material_consumable_request2.php?material_no=<?php echo $material_no; ?>&&lastID=<?php echo $lastID; ?>&&con_qty=<?php echo $con_qty; ?>&&date1=<?php echo $date1; ?>&&t_time=<?php echo $t_time; ?>&&factory=<?php echo $factory; ?>&&work_center=<?php echo $work_center; ?>">
             <?php
			
			  $i = 1;
			  
			$query_data2 = "SELECT * FROM consumable_request WHERE id_scan = '$lastID' AND status_request = 'N' AND user_create = '".$data_u["user_no"]."'";
            $result_data2 = mysql_query($query_data2) or die (mysql_error());
           
		   while ($row_data2 = mysql_fetch_array($result_data2))
		   {
			 
			 $query_con_detail = "SELECT * FROM consumable_detail WHERE id_con = '".$row_data2["id_con"]."'";
			 $result_con_detail = mysql_query($query_con_detail) or die (mysql_error());
			 $row_con_detail = mysql_fetch_array($result_con_detail);
			 
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
                 <td width="6%"><div align="center"><a href="delete_consumable_request.php?id_req_con=<?php echo $row_data2["id_req_con"]; ?>&&lastID=<?php echo $lastID; ?>" onclick="return confirm('Are you sure you want to delete?')"><img src="../images/delete.png" width="16" height="16"  /></a></div></td>
               </tr>
             </table>
             
            <?php  
			 
			 $i++;
			 
			 }
			 mysql_close();
			 
			 ?>
             <br />
             

             
             <div class="buttons">
           
                   <input name="confirm_button" type="submit" id="submit2" value="POST" class="button" onClick="return confirm('Confirm to post request?');">
          </div>
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
