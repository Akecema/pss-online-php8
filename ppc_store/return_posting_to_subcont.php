<?php

/**
 * ppc_store/return_posting_to_subcont.php
 * Part of: PPC Store module
 * Filename suggests: return posting to subcont
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, request_status, run_count_no, table_material, scan_ret_subcont, ret_subcont_detail, ftp_ret_subcont, vendor_detail.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, top_modal_menu.php, left_ppc_menu.php, footer.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
error_reporting(E_ALL &~ E_NOTICE &~ E_DEPRECATED);
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
require_once '../include/auth.php';
require_role($dbc, 12);
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

$url = "return_posting_to_subcont.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '".db_esc($dbc, $username)."'";
    $result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
    $res = mysqli_fetch_array($result2);
	
//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------	

//CR status (Return Posting)
$sta20 = "SELECT * from request_status WHERE status_id = '20'";
$sta_res20 = mysqli_query($dbc, $sta20);
$rst_sta20 = mysqli_fetch_array($sta_res20);	
	        
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
  <div id="breadcrumb"> <a href="index_ppc_store.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Transfer Posting</a> <a href="#" class="current">Return from Subcont</a> </div>
  <h1>Return from Subcont</h1>
</div>

<div class="container-fluid">
  <hr>
  <div class="row-fluid">

        <?php
    
		
		  //-------------------generate scan return to subcont doc no.---------------
     $query_id_2 = "SELECT MAX(count_max) FROM run_count_no WHERE uid = '16'";
	 $result_id_2 = mysqli_query($dbc, $query_id_2);

 if ($result_id_2) {
	$nrows_2 = mysqli_num_rows($result_id_2);
    $row_id_2 = mysqli_fetch_row($result_id_2);

  $dht_2 = "0";

  if($row_id_2[0] <= 0)
  { 
   
    $lastID_2 = ($row_id_2[0] + 1);
    $dg_2 = ($dht_2 + ($lastID_2));

   }else{
      $lastID_2 = ($row_id_2[0] + 1);
      $dg_2 =  $lastID_2;
    }

	 $number = $dg_2; // Length of the supplied number is 3
 	// echo $number;	
	
  } // end if $result_id
	   
// Set the page title and include the HTML header.

if(isset($_POST['submit3'])) 
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
	return mysqli_real_escape_string($dbc, $data);
	}   // end function.
$message = NULL; // create an empty new variable.
   
  $barcode_ref = $_POST["barcode_ref"];
  $vendor_no = $_POST["vendor_no"];

if(isset($_POST["vendor_no"]))
{ 
 
 }else{
  
  
// check for a barcode ref (scan from FG Tag)
if(empty($_POST["barcode_ref"]))
{ 
  $barcode_ref = FALSE;
  $message.= '<p>You are required to scanning barcode list!</p>';
  }
 }

if($barcode_ref) //everything ok
{  

//checking delete space semasa scanning

$barcode_ref2 =trim($barcode_ref);
			
 
//split dulu pps ref kpd prod_order, material,uom, plant, sloc, qty
$str = $barcode_ref2;


list($part1, $part2, $part3) = (explode('|', $str, 3));


/*echo "no 1 ".$part1;
echo "<br>";
echo "no 2 ".$part2;
echo "<br>";
echo "no 3 ".$part3;
echo "<br>";
*/

// negative limit (since PHP 5.1)
//print_r(explode('|', $str, -1));
			   
  $query_q2 = "SELECT * FROM table_material WHERE material_no = '".db_esc($dbc, $part1)."'";
  $result_q2 = mysqli_query($dbc, $query_q2) or die (mysqli_error($dbc));
  $ans3 = mysqli_fetch_array($result_q2);
				   

//insert to scan_ret_subcont
//----add for record [status = 'Y' will be generate return posting running no]
$query_db = "INSERT INTO scan_ret_subcont(id_scan_tp, scan_doc, barcode_ref, plan_code, sloc_from, sloc_to, material_no, material_desc, scan_qty, scan_uom,  user_create, date_create, status) VALUES ('','".db_esc($dbc, $number)."','".db_esc($dbc, $barcode_ref2)."','".db_esc($dbc, $ans3["plan_code"])."','','".strtoupper($part3)."','".strtoupper($part1)."','".strtoupper($ans3["material_desc"])."', '','".strtoupper($ans3["BUn"])."','".db_esc($dbc, $username)."',NOW(),'N')";
$result_db = mysqli_query($dbc, $query_db) or die (mysqli_error($dbc));


 //-----------------------scan qty-----------------------
 
  if(isset($_POST["cancel"])) 
  {
 
    $cancel = $_POST["cancel"]; 
    $how_many = count($cancel); 
	$scan_qty = $_POST["scan_qty"]; 
	$item_no = $_POST["item_no"]; 
	$sloc_to2 = "";
	   
	   foreach($_POST["cancel"] as $j=>$i) {
	    
		$amount .= $_POST["scan_qty"][$i];
		$amount2 .= $_POST["item_no"][$i];
		
		$string = explode("|",($amount));	
		$string2 = explode("|",($amount2));	
	  
			}
		 			
		   for ($i=0; $i<$how_many; $i++) { 
		   			
		/* echo ($i+1).'-'.$cancel[$i]; echo "&nbsp;&nbsp;";  echo $string[$i]; echo "</br>"; */
		
		 
		 $query_update_scan = "UPDATE scan_ret_subcont SET scan_qty = '".db_esc($dbc, $string[$i])."' WHERE id_scan_tp = '".db_esc($dbc, $cancel[$i])."'";
	     $rst_update_scan = mysqli_query($dbc, $query_update_scan);
		   
		   }

      } // end $_POST["cancel"];


	 
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
function escape_data ($data) {
global $dbc;   // need the connection.
if (ini_get('magic_quotes_gpc')) {
    $data = stripslashes($data);
	}
	return mysqli_real_escape_string($dbc, $data);
	}   // end function.
$message2 = NULL; // create an empty new variable.

      
	    //-------------------generate return transfer to subcont---------------
     
	 $query_gen_ID = "SELECT MAX(count_max) FROM run_count_no WHERE uid = '39'";
	 $result_gen_ID = mysqli_query($dbc, $query_gen_ID);

 if ($result_gen_ID) {
	$nrows_gen_ID = mysqli_num_rows($result_gen_ID);
    $row_gen_ID = mysqli_fetch_row($result_gen_ID);

  $dht_ID = "0000000";
  $dht_OK = "22521";

  if($row_gen_ID[0] <= 0)
  { 
   
    $lastID_gen_ID = ($row_gen_ID[0] + 1);
    $dg_gen_ID = ($dht_ID + ($lastID_gen_ID));

   }else{
      $lastID_gen_ID = ($row_gen_ID[0] + 1);
      $dg_gen_ID =  $lastID_gen_ID;
    }



	 $number2 = $dg_gen_ID; // Length of the supplied number is 3
	 $number2 = sprintf('%07d', $number2);
	 
	 //$ref_no = $number2;
     $ref = ($dht_OK.($number2)); 
	
	
	// echo "REF : ".$ref;

       } // end if $result_id



	   $scan_doc = $number;  
       $date1 = $_POST["date1"]; 
	   $prepared_by = $_POST["prepared_by"]; 
       $plan_code = $_POST["plan_code"]; 
	   $shift_day = $_POST["shift_day"]; 
	   $vendor_no = $_POST["vendor_no"];
	   $scan_qty = $_POST["scan_qty"];
	   $ref_doc_no_return = $_POST["ref_doc_no_return"];
	 
	 			
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
				  	  
				  if((($_POST["vendor_no"]) == "NULL") || (($_POST["vendor_no"]) == ""))
				{
				  $vendor_no = FALSE;
				  $message2.= '<p align="center">You are required to select Vendor!</p>';
				  } else{
				  $vendor_no = TRUE;
				  }
				  
				     if(($_POST["ref_doc_no_return"]) == "")
				{
				  $ref_doc_no_return = FALSE;
				  $message2.= '<p align="center">You are required to enter Reference Document No.!</p>';
				  }else{
				  $ref_doc_no_return = TRUE;
				  }	 	 

					$a = array();
					if(isset($_POST["cancel"])) {
						foreach($_POST["cancel"] as $j=>$i) {
							
							$amount .= $_POST["scan_qty"][$i]."|";
							$amount2 .= $_POST["item_no"][$i]."|";
							$checked .= ($checked==""?"":",") . "checkbox" . $i;
					
							
							array_push($a, $i);
						   // array_push($amount2, $i);
							   
							 if(($_POST["scan_qty"][$i]) == "")
							{ 
							  $scan_qty = FALSE;
							  $message2.= '<p align="center">You are required to enter Quantity for Item '.$_POST["item_no"][$i].'!</p>';
							
							}
						}
					}
					
					 // echo $checked;
					 //echo $amount;
					
					function was_checked($i,$a) {
					if(in_array($i, $a)===true) {
					return "checked='checked'";
					return "";
					}
					}

  if($prepared_by && $plan_code && $shift_day && $date1 && $vendor_no && $scan_qty && $ref_doc_no_return)
	   {
		
	 if(isset($_POST["cancel"])) 
  {
 
    $cancel = $_POST["cancel"]; 
    $how_many = count($cancel); 
	$scan_qty = $_POST["scan_qty"]; 
	$item_no = $_POST["item_no"]; 
	$sloc_to2 = "";
	   
	   foreach($_POST["cancel"] as $j=>$i) {
	    
		$amount .= $_POST["scan_qty"][$i];
		$amount2 .= $_POST["item_no"][$i];
		
		$string = explode("|",($amount));	
		$string2 = explode("|",($amount2));	
	  
			}
		 			
		   for ($i=0; $i<$how_many; $i++) { 
		   			
	/*	echo ($i+1).'-'.$cancel[$i];   echo $string[$i]; echo "</br>";*/
	
	// $t_time = (($_POST["time1"]).":".($_POST["time2"]));
		
		$query_update_scan2 = "UPDATE scan_ret_subcont SET scan_qty = '".db_esc($dbc, $string[$i])."', sloc_from = '".db_esc($dbc, $_POST["vendor_no"])."' WHERE id_scan_tp = '".db_esc($dbc, $cancel[$i])."'";
	    $rst_update_scan2 = mysqli_query($dbc, $query_update_scan2);
		
		 //-----get info scan_ret_subcont-------------
		 
		$query_info = "SELECT * FROM scan_ret_subcont WHERE id_scan_tp = '".db_esc($dbc, $cancel[$i])."'";
		$result_info = mysqli_query($dbc, $query_info);
		$row_info = mysqli_fetch_array($result_info);
		 
		
		//---------insert data at table return_subcont_detail
		
		  $query_store = "INSERT INTO ret_subcont_detail(id_tp, doc_tp, id_scan_tp, scan_doc, posting_date, posting_time, prepared_by, vendor_no, plan_code, shift_day, item_no, material_no, material_desc, qty_tp, uom, sloc_from, sloc_to, user_create, date_create, user_generate_tp, date_generate_tp, ref_doc_tp, user_cancel, date_cancel, status_ftp, status_tran, status_tp, ref_doc_no_return) VALUES('','".db_esc($dbc, $ref)."','".db_esc($dbc, $row_info["id_scan_tp"])."','".db_esc($dbc, $scan_doc)."','".db_esc($dbc, $_POST["date1"])."',NOW(),'".strtoupper($_POST["prepared_by"])."','".db_esc($dbc, $_POST["vendor_no"])."','".db_esc($dbc, $_POST["plan_code"])."','".db_esc($dbc, $_POST["shift_day"])."','".db_esc($dbc, $string2[$i])."','".db_esc($dbc, $row_info["material_no"])."','".db_esc($dbc, $row_info["material_desc"])."','".db_esc($dbc, $string[$i])."','".db_esc($dbc, $row_info["scan_uom"])."','".db_esc($dbc, $row_info["sloc_from"])."','".db_esc($dbc, $row_info["sloc_to"])."','".db_esc($dbc, $row_info["user_create"])."','".db_esc($dbc, $row_info["date_create"])."','".db_esc($dbc, $username)."', NOW(),'','','','Y','Y','".db_esc($dbc, $rst_sta20["status_desc"])."','".strtoupper($_POST["ref_doc_no_return"])."')";      
		  $rst_store = mysqli_query($dbc, $query_store);
		

		//---update status "yes" for generate tp to subcont----
		
		$query_update_scan3 = "UPDATE scan_ret_subcont SET status = 'Y', sloc_from = '".db_esc($dbc, $_POST["vendor_no"])."' WHERE id_scan_tp = '".db_esc($dbc, $cancel[$i])."'";
	    $rst_update_scan3 = mysqli_query($dbc, $query_update_scan3);
		
	}//end for loop
       
	   //----checking ftp tp_subcont_detail-------
    $data_rcv = "";
   

   $query_rcv_ftp = "SELECT *, DATE_FORMAT(posting_date,'%d-%m-%Y') AS J, DATE_FORMAT(date_create,'%d-%m-%Y') AS R2 FROM ret_subcont_detail WHERE doc_tp = '".db_esc($dbc, $ref)."'";
   $result_rcv_ftp = mysqli_query($dbc, $query_rcv_ftp);
   
   $filen_rcv = "TP5".$ref; 
  
   while($data_rcv_ftp = mysqli_fetch_array($result_rcv_ftp))
   
   {
   
$data_rcv .= $data_rcv_ftp["prepared_by"].";".$data_rcv_ftp["J"].";".$data_rcv_ftp["plan_code"].";".$data_rcv_ftp["shift_day"].";".$data_rcv_ftp["sloc_from"].";".$data_rcv_ftp["sloc_to"].";".$data_rcv_ftp["material_no"].";".$data_rcv_ftp["qty_tp"].";".$data_rcv_ftp["uom"].";542;".$ref."\r\n";

  
     //----------update table ftp_tp_subcont------------
   
    $query_rcv_ftp_info = "INSERT INTO ftp_ret_subcont(id, file_name, doc_tp, id_tp, material_no, material_desc, qty_ftp, uom, plant, shift_day, mvt_type, status_ftp, posting_date, posting_time, user_create, date_create, vendor_no) VALUES('','".db_esc($dbc, $filen_rcv)."','".db_esc($dbc, $ref)."',".$data_rcv_ftp["id_tp"].",'".db_esc($dbc, $data_rcv_ftp["material_no"])."','".db_esc($dbc, $data_rcv_ftp["material_desc"])."','".db_esc($dbc, $data_rcv_ftp["qty_tp"])."','".db_esc($dbc, $data_rcv_ftp["uom"])."','".db_esc($dbc, $data_rcv_ftp["plan_code"])."','".db_esc($dbc, $data_rcv_ftp["shift_day"])."','542','Y','".db_esc($dbc, $data_rcv_ftp["posting_date"])."','".db_esc($dbc, $data_rcv_ftp["posting_time"])."','".db_esc($dbc, $username)."',NOW(),'".db_esc($dbc, $data_rcv_ftp["sloc_from"])."')"; 
     $rst_rcv_ftp_info = mysqli_query($dbc, $query_rcv_ftp_info);
	  
	  
	  }

		$file_rcv = "../FromPortal3/RT_SUBCON/".$filen_rcv.".csv";
		file_put_contents($file_rcv,$data_rcv);

   
	   // ---update status 

		$query_rcv_ftp2 = "UPDATE ret_subcont_detail SET status_ftp = 'Y' WHERE doc_tp = '".db_esc($dbc, $data_rcv_ftp["doc_tp"])."'";
		$rst_query_rcv_ftp2 = mysqli_query($dbc, $query_rcv_ftp2); //or die ("Error in query: $query_ftp"); 
		
				
    //---------------------------------------end ftp -------------------------------------------------   
	   
	   
	//update count_max----------------------------------------
		
	
       $query_max_a = "UPDATE run_count_no SET count_max = '".db_esc($dbc, $number)."', date_updated = NOW() WHERE uid = '16'";
	   $result_max_a = mysqli_query($dbc, $query_max_a);
	   
	   $query_max_b = "UPDATE run_count_no SET count_max = '".$number2."', date_updated = NOW() WHERE uid = '39'";
	   $result_max_b = mysqli_query($dbc, $query_max_b);
		

   //end update count_max ---------------------------------	
	   
			   
		   
    } // end $_POST["cancel"]
	
	       echo "<script>";
		   echo "alert('Material Document $ref posted.');";
		   echo "window.location='return_posting_to_subcont.php'";
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

   $query_delete_scan = "DELETE FROM scan_ret_subcont WHERE scan_doc = '".db_esc($dbc, $number)."'";
   $result_delete_scan = mysqli_query($dbc, $query_delete_scan);

//---------end delete ----------------------------------

}//end submit5
          
 ?>         
            <form action="return_posting_to_subcont.php?scan_doc=<?php echo $number; ?>" method="post" name="frmSearch" id="frmSearch">
            <table class="table table-bordered table-striped">
              <tr>
                  <th width="18%">Posting Date :</th>
                  <td width="344"><?php
    
			
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
                  <td width="331" colspan="2">
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
               <tr>
                 <th>Vendor :</th>
                 <td> 
                  <input name="vendor_no" type="text" id="vendor_no" size="20" maxlength="10" class="span3" value="<?php if (isset($_POST["vendor_no"])) { echo $_POST["vendor_no"]; } ?>" />
                 
                  <?php if(($_POST["submit3"] == true) || ($_POST["submit4"] == true))
						{  
						
  $query_vendor = "SELECT * FROM vendor_detail WHERE vendor_code = '".db_esc($dbc, $_POST["vendor_no"])."' AND status_acc = 'Y' AND status_subcont = 'Y'"; 	
  $result_vendor = mysqli_query($dbc, $query_vendor);
  $row_vendor = mysqli_fetch_array($result_vendor);
					
			       echo $row_vendor["vendor_name"]; ?>
                 
<!-- <input name="vendor_desc" type="text" id="vendor_desc" size="40" class="span8" readonly value="" />
-->                 
                 <?php   }
					 
					   ?></td>
                 <th>Ref. Doc. No. :</th>
                 <td><input name="ref_doc_no_return" type="text" id="ref_doc_no_return" size="25" class="span11"  value="<?php if (isset($_POST["ref_doc_no_return"])) { echo $_POST["ref_doc_no_return"]; } ?>"/></td>
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
               <td colspan="2"><input name="submit3" type="submit" id="submit3" value="+ Add Item" class="button"  /> </th></td>
               </tr>
      </table><!--</form>-->
                
                 <?php

     $no = 1;
	 $k = 1;
	 $w = 1;


   
             $query_sql2 = "SELECT * FROM scan_ret_subcont WHERE scan_doc = '".db_esc($dbc, $number)."'";
			 $result_sql2 = mysqli_query($dbc, $query_sql2);
			
	 
	?>   
    
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Return from Subcont</h5>
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
    <td width="220">Description</td>
    <td width="150">Quantity</td>
    <td width="80">UOM</td>
    <td width="100">SLoc To</td>
    </tr></table>
 <?php
  while($data_sql2 = mysqli_fetch_array($result_sql2))
  {
	  $no = sprintf('%04d',$no);
	  
	 ?>
       <table width="100%" border="0" cellspacing="2" cellpadding="0">
      <tr>
     <td width="50">
     <div align="center"><input type="checkbox" name="cancel[]" value="<?php echo $data_sql2["id_scan_tp"]; ?>" checked /><input type="hidden" name="Check_ctr" value="yes" onClick="Check(document.myform.cancel)">  </div> </td>  
    <td width="75"><?php echo $no; ?> <input name="item_no[<?php echo $data_sql2["id_scan_tp"]; ?>]" type="hidden" value="<?php echo $no; ?>"></td>
    <td width="150"><?php echo $data_sql2["material_no"];  ?></td>
    <td width="220"><?php echo $data_sql2["material_desc"];  ?></td>
    <td width="150" height="30"><?php  if(($data_sql2["scan_qty"] == "") || ($data_sql2["scan_qty"] == "0.000")) { ?><input name="scan_qty[<?php echo $data_sql2["id_scan_tp"]; ?>]" type="number" min="1" value="<?php if(isset($_POST["scan_qty"])) { echo $_POST["scan_qty"][($data_sql2["id_scan_tp"])]; } ?>" id="scan_qty" ><?php }else{  ?> <input name="scan_qty[<?php echo $data_sql2["id_scan_tp"]; ?>]" type="number" min="1" value="<?php echo $data_sql2["scan_qty"];   ?>" required><?php  } ?></td>
    <td width="80"><?php echo $data_sql2["scan_uom"];  ?></td>
    <td width="100"><?php echo $data_sql2["sloc_to"];  ?></td>
  </tr>
</table>

<?php   

  $w ++; 
  $no++;
  $k ++;


 } 
   mysqli_free_result($result_sql2);   
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
