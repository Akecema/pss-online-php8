<?php
error_reporting(E_ALL &~ E_NOTICE &~ E_DEPRECATED);
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
$currentdate = (date("Y-m-d"));

$url = "trans_posting_to_store.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
    $result2 = mysql_query($query2) or die (mysql_error());
    $res = mysql_fetch_array($result2);
	
//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysql_query($query_setup);   //run the query.
$num_setup = mysql_num_rows($rs_setup);   //how many material are there?
$data_setup = mysql_fetch_array($rs_setup);
//----------------------------------------------------	
	 
//CR status (Transfer Posting)
$sta19 = "SELECT * from request_status WHERE status_id = '19'";
$sta_res19 = mysql_query($sta19);
$rst_sta19 = mysql_fetch_array($sta_res19);	
	        
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
<style>
input[value="+ Add Item"]{
  display:none;
}


</style>
<?php
//echo "the following values have been checked: ";
$number = "";
$checked="";
$amount ="";
$amount2 ="";
$barcode_ref = "";
$uid = ""; 

?>
</head>
<body>

<!--Header-part-->
<div id="header">
  <h1>&nbsp;</h1>
</div>
<?php  include "top_modal_menu.php";   ?>
<!--close-Header-part--> 

<!--sidebar-menu-->
<?php include "left_ppc_menu.php";  ?>
<!--sidebar-menu-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index_ppc_store.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Transfer Posting</a> <a href="#" class="current">TP to Store</a> </div>
  <h1>Transfer Posting to Store</h1>
</div>

<div class="container-fluid">
  <hr>
  <div class="row-fluid">

        <?php

 //-------------------generate scan TP to store ---------------
     $query_id_2 = "SELECT * FROM run_count_no WHERE uid = '4'";
	 $result_id_2 = mysql_query($query_id_2);

 if ($result_id_2) {
	$nrows_2 = mysql_num_rows($result_id_2);
    $row_id_2 = mysql_fetch_array($result_id_2);

  $dht_2 = "0";

  if($row_id_2["count_max"] <= 0)
  { 
   
    $lastID_2 = ($row_id_2["count_max"] + 1);
    $dg_2 = ($dht_2 + ($lastID_2));

   }else{
      $lastID_2 = ($row_id_2["count_max"] + 1);
      $dg_2 =  $lastID_2;
    }



	 $number = $dg_2; // Length of the supplied number is 3
	
	 //echo $number;	
   
  } // end if $result_id	
		


if(isset($_POST["submit3"])) 
{ // handle the form.

require_once('../include/config.php');   //connect to the db.

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
// create a function for escaping the data.
function escape_data ($data) {
global $dbc;   // need the connection.
if (ini_get('magic_quotes_gpc')) {
    $data = stripslashes($data);
	}
	return mysql_real_escape_string($data,$dbc);
	}   // end function.
$message = NULL; // create an empty new variable.




   
   $barcode_ref = $_POST["barcode_ref"];
   $s_doc = $_GET["scan_doc"];
  	
// check for a barcode ref (scan from FG Tag)
if(empty($_POST["barcode_ref"]))
{ 
  $barcode_ref = FALSE;
  $message.= '<p>You are required to scanning FG Tag!</p>';
  }
     

if($barcode_ref) //everything ok
{  

//checking delete space semasa scanning

$barcode_ref2 =trim($barcode_ref);
			
 
//split dulu pps ref kpd prod_order, material,uom, plant, sloc, qty
$str = $barcode_ref2;


list($part1, $part2, $part3, $part4, $part5, $part6, $part7, $part8, $part9, $part10, $part11, $part12) = (explode('|', $str, 12));


/*echo "no 1 ".$part1;
echo "<br>";
echo "no 2 ".$part2;
echo "<br>";
echo "no 3 ".$part3;
echo "<br>";
echo "no 4 ".$part4;
echo "<br>";
echo "no 5 ".$part5;
echo "<br>";
echo "no 6 ".$part6;
echo "<br>";
echo "no 7 ".$part7;
echo "<br>";
echo "no 8 ".$part8;
echo "<br>";
echo "no 9 ".$part9;
echo "<br>";  
echo "no 10 ".$part10;
echo "<br>";
echo "no 11 ".$part11;
echo "<br>"; */

// negative limit (since PHP 5.1)
//print_r(explode('|', $str, -1));


            $dd1 = substr($part10,0,2);
			$mm1 = substr($part10,3,2);
			$yy1 = substr($part10,6,5);
			$post_dt = ($yy1.'-'.$mm1.'-'.$dd1);
			
            $dd2 = substr($part11,0,2);
			$mm2 = substr($part11,3,2);
			$yy2 = substr($part11,6,5);
			$create_dt = ($yy2.'-'.$mm2.'-'.$dd2);
				   
  $query_q2 = "SELECT * FROM table_material WHERE material_no = '$part4'";
  $result_q2 = mysql_query($query_q2) or die (mysql_error());
  $ans3 = mysql_fetch_array($result_q2);
  
  
  //-----------------checking sudah generate scan id ------------------
  
//insert to scan_tp_store
$query_db = "INSERT INTO scan_tp_store(id_scan_tp, scan_doc, barcode_ref, doc_no, plan_code, scan_sloc, material_no, material_desc, plan_no, scan_shift, scan_qty, scan_uom, scan_date_posting, scan_date_create, slip_no, user_create, date_create, status) VALUES ('','".$number."','$barcode_ref2', '$part1', '$part2', '".strtoupper($part3)."', '".strtoupper($part4)."', '".strtoupper($ans3["material_desc"])."', '".strtoupper($part5)."', '".strtoupper($part6)."', '$part7', '".strtoupper($part8)."', '$post_dt', '$create_dt', '".strtolower($part12)."', '$username', NOW(),'N')";
$result_db = mysql_query($query_db) or die (mysql_error());

//----add for record [status = 'Y' will be generate trans posting running no]

        
 

	 
}//print the message if there is one.
	  
if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}
}
?>
<?php

if(isset($_POST["submit4"]))  
{ // handle the form.

require_once('../include/config.php');   //connect to the db.

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
// create a function for escaping the data.
function escape_data($data) {
global $dbc;   // need the connection.
if (ini_get('magic_quotes_gpc')) {
    $data = stripslashes($data);
	}
	return mysql_real_escape_string($data,$dbc);
	}   // end function.
$message2 = NULL; // create an empty new variable.

      
	   $scan_doc = $number;  
       $date1 = $_POST["date1"]; 
	   $prepared_by = $_POST["prepared_by"]; 
       $plan_code = $_POST["plan_code"]; 
	   $shift_day = $_POST["shift_day"]; 
	   $scan_qty = $_POST["scan_qty"]; 
	 
	 			
				if(($_POST["prepared_by"]) == "")
				{
				  $prepared_by = FALSE;
				  $message2.= '<p align="center">You are required to enter Prepared by!</p>';
				  }else{
				  $prepared_by = TRUE;
				  }
				  
				  if(($_POST["plan_code"]) == "")
				{
				  $plan_code = FALSE;
				  $message2.= '<p align="center">You are required to enter Plan Code!</p>';
				  }else{
				  $plan_code = TRUE;
				  }
				  
				   if(($_POST["shift_day"]) == "NULL")
				{
				  $shift_day = FALSE;
				  $message2.= '<p align="center">You are required to select Shift!</p>';
				  }else{
				  $shift_day = TRUE;
				  }
				   if(($_POST["date1"]) == "0000-00-00")
				{
				  $date1 = FALSE;
				  $message2.= '<p align="center">You are required to select Posting Date!</p>';
				  }else{
				  $date1 = TRUE;
				  }
				  
				  
				  $a = array();
if(isset($_POST["cancel"])) {
	foreach($_POST["cancel"] as $j=>$i) {
		
		$amount .= $_POST["scan_qty"][$i]."|";
	    $amount2 .= $_POST["item_no"][$i]."|";
		$checked .= ($checked==""?"":",") . "checkbox" . $i;

		
		array_push($a, $i);
	   
	            if((($_POST["scan_qty"][$i]) == ""))
				{ 
				  $scan_qty = FALSE;
				  $message2.= '<p align="center">You are required to enter Quantity for Item '.$_POST["item_no"][$i].'!</p>';
				
				  }
	   
	}
	
}



function was_checked($i,$a) {
if(in_array($i, $a)===true) {
return "checked='checked'";
return "";
}
}


  if($prepared_by && $plan_code && $shift_day && $date1 && $scan_qty)
  {
		   
	//-------------------generate tp to store doc no.---------------
     
	 $query_gen_ID = "SELECT * FROM run_count_no WHERE uid = '35'";
	 $result_gen_ID = mysql_query($query_gen_ID);

 if ($result_gen_ID) {
	$nrows_gen_ID = mysql_num_rows($result_gen_ID);
    $row_gen_ID = mysql_fetch_array($result_gen_ID);

  $dht_ID = "0000000";
  $dht_OK = "22431";

  if($row_gen_ID["count_max"] <= 0)
  { 
   
    $lastID_gen_ID = ($row_gen_ID["count_max"] + 1);
    $dg_gen_ID = ($dht_ID + ($lastID_gen_ID));

   }else{
      $lastID_gen_ID = ($row_gen_ID["count_max"] + 1);
      $dg_gen_ID =  $lastID_gen_ID;
    }



	 $number2 = $dg_gen_ID; // Length of the supplied number is 3
	 $number2 = sprintf('%07d', $number2);
	 
	 //$ref_no = $number2;
     $ref = ($dht_OK.($number2)); 
	
	// echo "REF : ".$ref;

       } // end if $result_id


		
	 if(isset($_POST["cancel"])) 
  {
 
    $cancel = $_POST["cancel"]; 
    $how_many = count($cancel); 
	//$id_scan_tp = $_POST["id_scan_tp"]; 
	$item_no = $_POST["item_no"]; 
	$sloc_to2 = "";
	   
	   foreach($_POST["cancel"] as $j=>$i) {
	    
	    $amount .= $_POST["scan_qty"][$i];
		$amount2 .= $_POST["item_no"][$i];
		
		$string = explode("|",($amount));	
		$string2 = explode("|",($amount2));	
	  
			}
		 			
		   for ($i=0; $i<$how_many; $i++) { 
		   			
		/*echo ($i+1).'-'.$cancel[$i];   echo $string[$i]; echo "</br>";*/
		
		$query_update_scan2 = "UPDATE scan_tp_store SET scan_qty = '".$string[$i]."' WHERE id_scan_tp = '".$cancel[$i]."'";
	    $rst_update_scan2 = mysql_query($query_update_scan2);
		
		 //-----get info scan_tp_store-------------
		 
		$query_info = "SELECT * FROM scan_tp_store WHERE id_scan_tp = '".$cancel[$i]."'";
		$result_info = mysql_query($query_info);
		$row_info = mysql_fetch_array($result_info);
		 
		   if($row_info["scan_sloc"] == "P130")
	  {
		  $sloc_to2 = "W130";
	  }elseif($row_info["scan_sloc"] == "P120")
	  
	  {
		  $sloc_to2 = "W120";
	  }elseif($row_info["scan_sloc"] == "P110")
	  {
		  $sloc_to2 = "W110"; 
	  }


		//---------insert data at table tp_store_detail
		
		  $query_store = "INSERT INTO tp_store_detail(id_tp, doc_tp, id_scan_tp, scan_doc, doc_no, posting_date, posting_time, prepared_by, plan_code, shift_day, scan_shift_day, item_no, material_no, material_desc, qty_tp, uom, slip_no, plan_no, sloc_from, sloc_to, user_create, date_create, user_generate_tp, date_generate_tp, ref_doc_tp, user_cancel, date_cancel, status_ftp, status_tran, status_tp) VALUES('','".$ref."','".$row_info["id_scan_tp"]."','".$scan_doc."','".$row_info["doc_no"]."','".$_POST["date1"]."',NOW(),'".strtoupper($_POST["prepared_by"])."','".$_POST["plan_code"]."','".$_POST["shift_day"]."','".$row_info["scan_shift"]."','".$string2[$i]."','".$row_info["material_no"]."','".$row_info["material_desc"]."','".$string[$i]."','".strtoupper($row_info["scan_uom"])."','".$row_info["slip_no"]."','".$row_info["plan_no"]."','".$row_info["scan_sloc"]."','".$sloc_to2."','".$row_info["user_create"]."','".$row_info["date_create"]."','".$username."', NOW(),'','','','Y','Y','".$rst_sta19["status_desc"]."')";      
		  $rst_store = mysql_query($query_store);


		  //-----------------k azie edit 19/2/2024----------------------


	 $query_upd_shift = "SELECT * FROM tp_store_detail WHERE id_tp = '".mysql_insert_id()."'";
	 $result_upd_shift = mysql_query($query_upd_shift);
	 $row_upd_shift = mysql_fetch_array($result_upd_shift);
	   
	   
	   if(($row_upd_shift["shift_day"] == "N/S") && ($row_upd_shift["posting_date"] == $currentdate))
	   {
	   
	   $prev_date = date('Y-m-d', strtotime($currentdate .' -1 day'));	
	   
	   if(($row_upd_shift["posting_time"] > "21:00:00" ) && ($row_upd_shift["posting_time"] < "23:59:59" ))
	   {
		   
	   }else{
		   
	   $query_upd_shift2 = "UPDATE tp_store_detail SET posting_date = '".$prev_date."' WHERE id_tp = '".$row_upd_shift["id_tp"]."'";
	   $result_upd_shift2 = mysql_query($query_upd_shift2);  	
   
	   }
	   }  
		

		//---update status "yes" for generate tp to store----
		
		$query_update_scan = "UPDATE scan_tp_store SET status = 'Y' WHERE id_scan_tp = '".$cancel[$i]."'";
	    $rst_update_scan = mysql_query($query_update_scan);
		
	}//end for loop
       
	   //----checking ftp tp_store_detail-------
    $data_rcv = "";
   

   $query_rcv_ftp = "SELECT *, DATE_FORMAT(posting_date,'%d-%m-%Y') AS J, DATE_FORMAT(date_create,'%d-%m-%Y') AS R2 FROM tp_store_detail WHERE doc_tp = '".$ref."'";
   $result_rcv_ftp = mysql_query($query_rcv_ftp);
   
   $filen_rcv = "TP4".$ref; 
  
   while($data_rcv_ftp = mysql_fetch_array($result_rcv_ftp))
   
   {
   
$data_rcv .= $data_rcv_ftp["prepared_by"].";".$data_rcv_ftp["J"].";".$data_rcv_ftp["plan_code"].";".$data_rcv_ftp["shift_day"].";".$data_rcv_ftp["sloc_from"].";".$data_rcv_ftp["sloc_to"].";".$data_rcv_ftp["material_no"].";".$data_rcv_ftp["qty_tp"].";".$data_rcv_ftp["uom"].";".$data_rcv_ftp["slip_no"].";".$data_rcv_ftp["plan_no"].";311;".$ref."\r\n";

  
     //----------update table ftp_qc_received_detail------------
   
    $query_rcv_ftp_info = "INSERT INTO ftp_tp_store(id, file_name, doc_tp, id_tp, plan_no, material_no, material_desc, qty_ftp, uom, plant, shift_day,slip_no, mvt_type, status_ftp, posting_date, posting_time, user_create, date_create) VALUES('','".$filen_rcv."','".$ref."',".$data_rcv_ftp["id_tp"].",'".$data_rcv_ftp["plan_no"]."','".$data_rcv_ftp["material_no"]."','".$data_rcv_ftp["material_desc"]."','".$data_rcv_ftp["qty_tp"]."','".$data_rcv_ftp["uom"]."','".$data_rcv_ftp["plan_code"]."','".$data_rcv_ftp["shift_day"]."','".$data_rcv_ftp["slip_no"]."','311','Y','".$data_rcv_ftp["posting_date"]."','".$data_rcv_ftp["posting_time"]."','".$username."',NOW())"; 
     $rst_rcv_ftp_info = mysql_query($query_rcv_ftp_info);
	  
	  
	  }

		$file_rcv = "../FromPortal3/TP_STORE/".$filen_rcv.".csv";
		file_put_contents($file_rcv,$data_rcv);

   
	   // ---update status 

		$query_rcv_ftp2 = "UPDATE tp_store_detail SET status_ftp = 'Y' WHERE doc_tp = '".$data_rcv_ftp["doc_tp"]."'";
		$rst_query_rcv_ftp2 = mysql_query($query_rcv_ftp2); //or die ("Error in query: $query_ftp"); 
		
				
    //---------------------------------------end ftp -------------------------------------------------   
	   
	   
	//update count_max----------------------------------------
		
	
       $query_max_a = "UPDATE run_count_no SET count_max = '".$number."', date_updated = NOW() WHERE uid = '4'";
	   $result_max_a = mysql_query($query_max_a);
	   
	   $query_max_b = "UPDATE run_count_no SET count_max = '".$number2."', date_updated = NOW() WHERE uid = '35'";
	   $result_max_b = mysql_query($query_max_b);
		

   //end update count_max ---------------------------------	
	   
			   
		   
    } // end $_POST["cancel"]
	
	       echo "<script>";
		   echo "alert('Material Document $ref posted.');";
		   echo "window.location='trans_posting_to_store.php'";
	       echo "</script>"; 
		   exit(); //quit the script
	
	  }//end ifelse "OK"

  
if (isset($message2))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message2, '</font></div>';
}
		
}// end submit 4


if(isset($_POST["submit5"])) 
{ // handle the form.

require_once('../include/config.php');   //connect to the db.

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

//-----------delete all data current screen-------------

   $query_delete_scan = "DELETE FROM scan_tp_store WHERE scan_doc = '".$number."'";
   $result_delete_scan = mysql_query($query_delete_scan);

//---------end delete ----------------------------------

}//end submit5
          
 ?>         
            <form action="trans_posting_to_store.php?scan_doc=<?php echo $number; ?>" method="post" name="frmSearch" id="frmSearch">
            <table class="table table-bordered table-striped">
              <tr>
                  <th width="18%">Posting Date :</th>
                  <td width="31%"><?php
    
			
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
									$myCalendar->setYearInterval(date('Y') - 1, date('Y') + 10);
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
										$myCalendar->setYearInterval(date('Y') - 1, date('Y') + 10);
										// $myCalendar->setOnChange("myChanged('test')");
										$myCalendar->writeScript();
				
									}

?></td>
                  <th width="17%">Prepared by :</th>
                  <td colspan="2">
                <input name="prepared_by" type="text" id="prepared_by" size="25" class="span11"  value="<?php if (isset($_POST["prepared_by"])) { echo $_POST["prepared_by"]; } ?>"/></td>
              </tr>
               
               <tr>
              <th>Plant :</th>
              <td> <input name="plan_code" type="text" id="plan_code" size="25" class="span11" readonly value="2200"/></td>
              <th>Shift :</th>
              <td>                 
                  <select name="shift_day" id="shift_day" class="span11">
                  <option value="NULL" placeholder="Select Shift"> -- Select Shift --</option>
                   <?php if(($_POST["submit3"] == true) || ($_POST["submit4"] == true))
						{ ?>
               <option value="D/S" <?php if($_POST["shift_day"] == 'D/S') { ?> selected="selected"<?php } ?>>D/S</option>
               <option value="N/S" <?php if($_POST["shift_day"] == 'N/S') { ?> selected="selected"<?php } ?>>N/S</option>
               <?php 
						}
						else
						{ ?>
               <option value="D/S">D/S</option>
               <option value="N/S">N/S</option>
               <?php } ?>
                 </select>
                  
                  
                 </td>
               </tr>
              
              </table>
         
            <tr>  
              <table class="table table-bordered table-striped">
                  <th width="18%">Barcode :</th>
                  <th width="80%"><input name="barcode_ref" type="text" id="barcode_ref" size="60" maxlength="200" class="span12" autofocus />
               </th>
                </tr> 
                <tr>
               <th>
               <td colspan="2">
               <input name="submit3" type="submit" id="submit3" value="+ Add Item" class="button"  /> </th></td>
               </tr>
      </table><!--</form>-->
                
                 <?php

     $no = 1;
	 $sloc_to = "";
	 $k = 1;
	 $w = 1;


   
             $query_sql2 = "SELECT * FROM scan_tp_store WHERE scan_doc = '".$number."' AND user_create = '".$username."'";
			 $result_sql2 = mysql_query($query_sql2);
			
    
    
	 
	?>   
    
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>TP to Store</h5>
        </div>

   </div>

 <div class="widget-content nopadding">

   
<!--         <form name="myform" method="post" action="trans_posting_to_store.php?scan_doc=<?php echo $number; ?>" class="form-horizontal">
-->          <div class="control-group">
        <!--  <div class="controls"> -->
          
          <table width="100%" border="0" cellspacing="2" cellpadding="0">
     <tr>
    <td width="50">&nbsp;</td>
    <td width="75">Item</td>
    <td width="150">Material</td>
    <td width="200">Description</td>
    <td width="130">Qty</td>
    <td width="60">UOM</td>
    <td width="100">Slip No.</td>
    <td width="160">Plan Order</td>
    <td width="60">From</td>
    <td width="60">To</td>
  </tr></table>
 <?php
  while($data_sql2 = mysql_fetch_array($result_sql2))
  {
	  $no = sprintf('%04d',$no);
	  
	  if($data_sql2["scan_sloc"] == "P130")
	  {
		  $sloc_to = "W130";
	  }elseif($data_sql2["scan_sloc"] == "P120")
	  
	  {
		  $sloc_to = "W120";
	  }elseif($data_sql2["scan_sloc"] == "P110")
	  {
		  $sloc_to = "W110"; 
	  }
 ?>
       <table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
     <td width="50">
     <div align="center"><input type="checkbox" name="cancel[]" value="<?php echo $data_sql2["id_scan_tp"]; ?>" checked /><input type="hidden" name="Check_ctr" value="yes" onClick="Check(document.myform.cancel)">  </div> </td>  
    <td width="75"><?php echo $no; ?> <input name="item_no[<?php echo $data_sql2["id_scan_tp"]; ?>]" type="hidden" value="<?php echo $no; ?>"></td>
    <td width="150"><?php echo $data_sql2["material_no"];  ?></td>
    <td width="200"><?php echo $data_sql2["material_desc"];  ?></td>
    <td width="130">
	<?php  if(($data_sql2["scan_qty"] == "") || ($data_sql2["scan_qty"] == "0.000")) { ?><input name="scan_qty[<?php echo $data_sql2["id_scan_tp"]; ?>]" type="number" min="1" value="<?php if(isset($_POST["scan_qty"])) { echo $_POST["scan_qty"][($data_sql2["id_scan_tp"])]; } ?>" id="scan_qty" class="span10"><?php }else{  ?> <input name="scan_qty[<?php echo $data_sql2["id_scan_tp"]; ?>]" type="number" min="1" value="<?php echo $data_sql2["scan_qty"];   ?>" required class="span10"><?php  } ?>
	<?php // echo number_format($data_sql2["scan_qty"]);  ?></td>
    <td width="60">&nbsp;<?php echo $data_sql2["scan_uom"];  ?></td>
    <td width="100"><?php echo $data_sql2["slip_no"];  ?></td>
    <td width="160"><?php echo $data_sql2["plan_no"];  ?></td>
    <td width="60"><?php echo $data_sql2["scan_sloc"];  ?></td>
    <td width="60"><?php echo $sloc_to;  ?></td>
  </tr>
</table>

<?php 

  $w ++; 
  $no++;
  $k ++;


 } 
   mysql_free_result($result_sql2);   
?>
       </div>
          </div>
     <div class="control-group">
              
            </div>
            <div class="form-actions">
               <input name="submit4" type="submit" id="submit" value="POST" class="btn btn-success" onclick="return confirm('Confirm to transfer?');" >
               <input name="submit5" type="submit" id="submit5" class="btn btn-warning" value="CLEAR">
           </div>
            </form>
            </div>
         </div>  
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
