<?php
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");

$Cdate = date ("l, j F Y ");
set_time_limit(0);

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
date_default_timezone_set('Asia/Kuala_Lumpur');


$url = "cancel_trans_posting_to_storeProc.php";

    $query2 = "SELECT * FROM user_detail WHERE username = '$username'";
    $result2 = mysql_query($query2) or die (mysql_error());
    $res = mysql_fetch_array($result2);
	
$today = getdate();
$hours = $today['hours']; 
$minutes = $today['minutes'];
$seconds = $today['seconds'];
$month = $today['mon']; 
$mday = $today['mday']; 
$year = $today['year']; 	


//--------------------------------------------------		
//CR status (Cancelled Posting)
$sta4 = "SELECT * from request_status WHERE status_id = '4'";
$sta_res4 = mysql_query($sta4);
$rst_sta4 = mysql_fetch_array($sta_res4);	
			 
//CR status (Transfer Posting)
$sta19 = "SELECT * from request_status WHERE status_id = '19'";
$sta_res19 = mysql_query($sta19);
$rst_sta19 = mysql_fetch_array($sta_res19);	

//---------------------------------------------------------

 $doc_tp = $_GET["uid"];

 
$queryu = "SELECT * FROM tp_plb_detail WHERE doc_tp = '".$doc_tp."'";
$rs = mysql_query($queryu);   //run the query.


$query_2 = "SELECT *, DATE_FORMAT(MR.posting_date,'%d-%m-%Y') as R, DATE_FORMAT(MR.date_create,'%d-%m-%Y') as R2 FROM tp_plb_detail as MR, scan_tp_plb as SD WHERE MR.id_scan_tp = SD.id_scan_tp AND MR.doc_tp = '".$doc_tp."'";
$result_2 = mysql_query($query_2);   //run the query.
$data_2 = mysql_fetch_array($result_2);

  
$query_k = "SELECT * from `user_detail` as uc WHERE uc.user_no = '".$data_2["user_create"]."'";
$result_k = mysql_query($query_k);
$row_k = mysql_fetch_array($result_k);

$query_k2 = "SELECT * from `user_detail` as uc2 WHERE uc2.username = '$username'";
$result_k2 = mysql_query($query_k2);
$row_k2 = mysql_fetch_array($result_k2);

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysql_query($query_setup);   //run the query.
$num_setup = mysql_num_rows($rs_setup);   //how many material are there?
$data_setup = mysql_fetch_array($rs_setup);
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
<script type="text/javascript" src="../javascript/multiValue1.js"></script>

<!----------------->
<link rel="stylesheet" href="../scripts/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="../javascript/jquery-latest.js"></script> 
<script type="text/javascript" src="../javascript/thickbox.js"></script>	
<link href="../calendar/calendar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../calendar/calendar.js"></script>
<?php

//FUNCTION RETAIN TEXTBOX VALUE
function prepopulate($name) 
{ 
	if(isset($_POST[$name])) 
	{ 
		return $_POST[$name]; 
	} 
	else 
	{ 
		return ""; 
	} 
} 


?>
<style type="text/css">
<!--
.style3 {color: #000000}
@media print{
  body{ background-color:#FFFFFF; background-image:none; color:#000000 }
  #ad{ display:none;}
  #leftbar{ display:none;}
  #contentarea{ width:100%;}
}
.style4 {
	font-size: 14px;
	font-weight: bold;
}
-->
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
</head>
<body>
<div class="widget-box">



<table class="table table-condensed">
<tr>
      <td width="1%">&nbsp;</td> 
      <td width="7%"><img src="../img/print2.jpg" width="48" height="48" onClick="window.print()" title="Print"/></td>
    <td width="7%"><a href="javascript:parent.tb_remove();" ><img src="../img/back3.jpg" width="48" height="48" /></a></td>
      <td width="85%"> <div class="small-nav"></div></td>
     
  </tr>

</table> <?php
  if(isset($_POST["submit"])) 
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

 $doc_tp = $_POST["doc_tp"];
 $id_tp = $_POST["id_tp"];
 
 
   //-------------------generate tp to PLB cancellation doc no.---------------
     
	 $query_gen_ID = "SELECT MAX(count_max) FROM run_count_no WHERE uid = '42'";
	 $result_gen_ID = mysql_query($query_gen_ID);

 if ($result_gen_ID) {
	$nrows_gen_ID = mysql_num_rows($result_gen_ID);
    $row_gen_ID = mysql_fetch_row($result_gen_ID);

  $dht_ID = "0000000";
  $dht_OK = "22612";

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
 
 
 //-----select info tp_plb_detail -----------         
  $query_info = "SELECT * FROM tp_plb_detail WHERE doc_tp = '".$doc_tp."' AND status_tp = '".$rst_sta19["status_desc"]."'";
  $result_info  = mysql_query($query_info); 
  
 while($row2 = mysql_fetch_array($result_info))
 
 {

    $query_cancellation = "UPDATE tp_plb_detail SET status_tp = '".$rst_sta4["status_desc"]."', ref_doc_tp = '".$ref."', user_cancel = '".$username."', date_cancel = NOW() WHERE doc_tp = '".$doc_tp."' AND status_tp = '".$rst_sta19["status_desc"]."' AND id_tp = '".$row2["id_tp"]."'";
	$result_cancellation  = mysql_query($query_cancellation); 
	
	      
	      //---------------------------------------------------------------------------------------------------------------	
		  //-insert tp plb cancel
		  //---------------------------------------------------------------------------------------------------------------
		  
		  	$query_mm3 = "SELECT * FROM tp_plb_detail WHERE doc_tp = '".$doc_tp."' AND id_tp = '".$row2["id_tp"]."'"; 
        	$result_mm3 = mysql_query($query_mm3);
			
			while($row_mm3 = mysql_fetch_array($result_mm3))
			{ 
			
			$query_mm3_insert =  "INSERT INTO tp_plb_cancel(id_tp, doc_tp, id_scan_tp, scan_doc, posting_date, posting_time, prepared_by, driver_by, plate_no, plan_code, shift_day, item_no, material_no, material_desc, qty_tp, uom, sloc_from, sloc_to, user_create, date_create, user_generate_tp, date_generate_tp, ref_doc_tp, user_cancel, date_cancel, status_ftp, status_tran, status_tp) VALUES('".$row_mm3["id_tp"]."','".$row_mm3["doc_tp"]."','".$row_mm3["id_scan_tp"]."','".$row_mm3["scan_doc"]."','".$row_mm3["posting_date"]."','".$row_mm3["posting_time"]."','".$row_mm3["prepared_by"]."','".$row_mm3["driver_by"]."','".$row_mm3["plate_no"]."','".$row_mm3["plan_code"]."','".$row_mm3["shift_day"]."','".$row_mm3["item_no"]."','".$row_mm3["material_no"]."','".$row_mm3["material_desc"]."','".$row_mm3["qty_tp"]."','".$row_mm3["uom"]."','".$row_mm3["sloc_from"]."','".$row_mm3["sloc_to"]."','".$row_mm3["user_create"]."','".$row_mm3["date_create"]."','".$row_mm3["user_generate_tp"]."','".$row_mm3["date_generate_tp"]."','".$row_mm3["ref_doc_tp"]."','".$row_mm3["user_cancel"]."','".$row_mm3["date_cancel"]."','".$row_mm3["status_ftp"]."','".$row_mm3["status_tran"]."','".$row_mm3["status_tp"]."')";
			$result_mm3_insert = mysql_query($query_mm3_insert);
			
			}
			
			
	 //----checking ftp tp_store_detail-------
    $data_rcv = "";
   

   $query_rcv_ftp = "SELECT *, DATE_FORMAT(date_cancel,'%d-%m-%Y') AS J, DATE_FORMAT(date_create,'%Y') AS R2 FROM tp_plb_detail WHERE ref_doc_tp = '".$ref."' AND id_tp = '".$row2["id_tp"]."'";
   $result_rcv_ftp = mysql_query($query_rcv_ftp);
   $data_rcv_ftp = mysql_fetch_array($result_rcv_ftp);
   $filen_rcv = "TP6".$ref; 
  
   
$data_rcv .= $data_rcv_ftp["J"].";".$data_rcv_ftp["doc_tp"].";".$data_rcv_ftp["R2"]."\r\n";
  
     //----------update table ftp_qc_received_detail------------
   
    $query_rcv_ftp_info = "INSERT INTO ftp_tp_cancel_plb(id, file_name, doc_tp, ref_doc_tp, id_tp, material_no, material_desc, qty_ftp, uom, plant, shift_day, mvt_type, status_ftp, posting_date, posting_time, user_create, date_create) VALUES('','".$filen_rcv."','".$ref."','".$data_rcv_ftp["doc_tp"]."',".$data_rcv_ftp["id_tp"].",'".$data_rcv_ftp["material_no"]."','".$data_rcv_ftp["material_desc"]."','".$data_rcv_ftp["qty_tp"]."','".$data_rcv_ftp["uom"]."','".$data_rcv_ftp["plan_code"]."','".$data_rcv_ftp["shift_day"]."','312','Y',NOW(),NOW(),'".$username."',NOW())"; 
     $rst_rcv_ftp_info = mysql_query($query_rcv_ftp_info);
	  
			
			
 }// while loop
 

		$file_rcv = "../FromPortal3/TP_PLB/".$filen_rcv.".csv";
		file_put_contents($file_rcv,$data_rcv);
		
	  //update count_max----------------------------------------
	  
	   $query_max_a = "UPDATE run_count_no SET count_max = '".$number2."', date_updated = NOW() WHERE uid = '42'";
	   $result_max_a = mysql_query($query_max_a);


       //--------------------------------------------------------------------
       //copy yg close TP Store masuk dalam TP Store Close
	   //---------------------------------------------------------------------
       
			
			 echo "<script>";
			 echo "alert('Material Document $ref posted.');";
		    /* echo "window.location='cance_to_store.php'";*/
		     echo "parent.tb_remove(); parent.location.reload(1)";
		     echo "</script>"; 
		     exit(); //quit the script
			 
			 

if (isset($message))
{ echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}

	}



?>    
   
<br>
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Cancel TP to PLB   : [<?php echo $doc_tp;   ?>]</h5>
          </div>


        <table class="table table-bordered" >
        <tr>
          <th width="20%">Document No.</th>
          <th width="4%">:</th>
          <th width="30%"><?php echo $doc_tp; ?></th>
          <th width="18%">Plant</th>
          <th width="3%">:</th>
          <th width="25%"><?php echo $data_2["plan_code"];  ?></th>
        </tr>
        <tr>
          <th>Posting Date &amp; Time</th>
          <th>:</th>
          <th><?php echo $data_2["R2"]; ?>&nbsp;<?php echo $data_2["posting_time"]; ?></th>
          <th>Shift</th>
          <th>:</th>
          <th>&nbsp;<?php echo $data_2["shift_day"]; ?></th>
        </tr>
        <tr>
          <th>Prepared  by</th>
          <th>:</th>
          <th><?php echo $data_2["prepared_by"]; ?></th>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
        </tr>
      </table>

        <br>  
              <!-- End Box Head -->
            <form method="post" action="cancel_tp_to_plb.php?uid=<?php echo $doc_tp; ?>" >
            <table class="table table-bordered">
               <thead>
               <tr>
                 <th>Item</th>
                 <th>Material </th>
                 <th>Description </th> 
                 <th>Quantity</th>
                 <th>Uom</th>
                 <th>Driver Name</th>
                 <th>Plate No.</th>
                 <th>From</th> 
                 <th>To</th>
               </tr>
             	</thead>
             	<tbody> 

             <?php
      $counter = 1;
   $no = 1;
   
   while ($row = mysql_fetch_array($rs))
   {
		//$user_no = $row[0]; 
 $no = sprintf('%03d', $no);
		
	
		 ?>

               <tr>
                <td width="100"><?php echo $row["item_no"]; ?></td>
                <td width="122"><?php echo $row["material_no"]; ?></td>
                <td width="122"><?php echo $row["material_desc"]; ?></td>
                <td width="122"><?php echo $row["qty_tp"]; ?></td>
                <td width="45"><div align="center"><?php echo $row["uom"]; ?></div></td>
                <td width="140"><div align="center"><?php echo $row["driver_by"]; ?></div></td>
                <td width="60"><div align="center"><?php echo $row["plate_no"]; ?></div></td>
                <td width="55"><div align="center"><?php echo $row["sloc_from"]; ?></div></td>
                <td width="55"><div align="center"><?php echo $row["sloc_to"]; ?></div> 
                <input name="doc_tp" type="hidden" value="<?php echo $doc_tp; ?> ">
                <input name="id_tp" type="hidden" value="<?php echo $row["id_tp"]; ?> "></td> 
                </tr>
            <?php 
		 
		  
		  $counter++; // menambah counter 
   		  $no ++;   
			   
			   
			   }
			   
			   ?></tbody> </table> 
         <p>&nbsp;</p>
          <input type="submit" onClick="return confirm('Are you sure you want to cancel document no. : <?php echo $doc_tp; ?>?');" name="submit" id="button" value="CONFIRM" class="btn btn-success"/> &nbsp;&nbsp; 
               <input  name="btnback" type="button" id="btnCancel" class="btn btn-info" value="BACK" onclick="javascript:parent.tb_remove();" />    
         
         
         
         
         
         
      
              <p>&nbsp;</p>
           
</form>

<br>
<!--Footer-part-->
<?php include "footer.php";   ?>
<!--end-Footer-part--> 

</div>

</body>
</html>