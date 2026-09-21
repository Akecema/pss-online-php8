<?php

/**
 * planning/process_tran_pps.php
 * Part of: Planning module
 * Filename suggests: process tran pps
 *
 * Behavior: requires an active login session ($_SESSION['username']); reads parameters from the query string ($_GET).
 * Database tables referenced: sys_setup_maintain, request_status, pps_detail, pps_upload, work_center_detail, run_count_no.
 * Includes: config.php, paginator.class2.php, tc_calendar.php, excel_reader2.php.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
session_start();
$username = $_SESSION["username"];
include '../include/config.php';
require_once '../include/auth.php';
require_role($dbc, 8);
include_once ("../classes/paginator.class2.php");
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

ini_set("display_errors",0);
require_once "excel_reader2.php";

//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------		

//CR status (New)

$sta = "SELECT * from request_status WHERE status_id = '1' ";
$sta_res = mysqli_query($dbc, $sta);
$rst_sta = mysqli_fetch_array($sta_res);

//CR status (Released)
$sta2 = "SELECT * from request_status WHERE status_id = '2' ";
$sta_res2 = mysqli_query($dbc, $sta2);
$rst_sta2 = mysqli_fetch_array($sta_res2);	

//CR status (Cancel)
$sta4 = "SELECT * from request_status WHERE status_id = '4' ";
$sta_res4 = mysqli_query($dbc, $sta4);
$rst_sta4 = mysqli_fetch_array($sta_res4);	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $data_setup["title_desc"]; ?></title>
</head>

<body>
<!-- refresh page every 5 second auto.... -->
<!-- <body onload="JavaScript:timedRefresh(5000);">  -->

<?php


set_time_limit(0);

// create a function for escaping the data.
function escape_data ($data) {
global $dbc;   // need the connection.
if (ini_get('magic_quotes_gpc')) {
    $data = stripslashes($data);
	}
	return mysqli_real_escape_string($dbc, $data);
	}   // end function.
$message = NULL; // create an empty new variable.


//-----------------upload file into table pps_upload------------------------//
	
	foreach (glob("upload_pps/*.xls") as $filename) 
{ 
    $date1 = $_GET["date1"];
	$plan_category = $_GET["plan_category"];
	$upload_id = $_GET["upload_id"];
	
	$file = $filename;
	$number = "";
	
	//echo $file."<br>"; 

$data = new Spreadsheet_Excel_Reader($file);

             //--------check 1 days upload once----------------------------------------//
			//-------- 2nd upload will be remove/cancel the previous data-------------//
			/*
			$query_select_detail = "SELECT * FROM pps_detail WHERE (status_pps = '".$rst_sta["status_desc"]."' OR status_pps = '".$rst_sta2["status_desc"]."') AND date_plan = '".$date1."' AND plan_category = '".$plan_category."'";
			$result_select_detail = mysqli_query($dbc, $query_select_detail);
            $row_select_detail = mysqli_fetch_array($result_select_detail);
			
			if($row_select_detail > 0)
			{
				
            $query_upd_sts = "UPDATE pps_detail SET status_pps = '".$rst_sta4["status_desc"]."' WHERE (status_pps = '".$rst_sta["status_desc"]."' OR status_pps = '".$rst_sta2["status_desc"]."') AND date_plan = '".$date1."' AND plan_category = '".$plan_category."'";
			$result_upd_sts = mysqli_query($dbc, $query_upd_sts);
				
				
			}*/   // end if $row_select_detail
			
			//------------------end 2nd upload checking---------------------------
			
//echo "Total Sheets in this xls file: ".count($data->sheets)."<br /><br />";

$html="<table border='1'>";
//for($i=0;$i<count($data->sheets);$i++) // Loop to get all sheets in a file.

for($i=0;$i<= 1;$i++) // Loop to get all sheets in a file.
{	

	if(count($data->sheets[$i]["cells"])>0) // checking sheet not empty
	{
		echo "Sheet $i:<br /><br />Total rows in sheet $i  ".count($data->sheets[$i]["cells"])."<br />";
		
		
		for($j=4;$j<=count($data->sheets[$i]["cells"]);$j++) // loop used to get each row of the sheet
		{ 
		
			$html.="<tr>";
			for($k=1;$k<=count($data->sheets[$i]["cells"][$j]);$k++) // This loop is created to get data in a table format.
			{
				$html.="<td>";
				$html.=$data->sheets[$i]["cells"][$j][$k];
				$html.="</td>";
				
				
			    $month_plan =  mysqli_real_escape_string($dbc, $data->sheets[$i]["cells"][1][2]);
				//$date_plan =  mysqli_real_escape_string($dbc, $data->sheets[$i]["cells"][1][2]);
			}
			
			$model_code = mysqli_real_escape_string($dbc, $data->sheets[$i]["cells"][$j][1]);
			$shift_pps1 = mysqli_real_escape_string($dbc, $data->sheets[$i]["cells"][$j][6]);
			$shift_pps2 = mysqli_real_escape_string($dbc, $data->sheets[$i]["cells"][$j][8]);
			$material_no = mysqli_real_escape_string($dbc, $data->sheets[$i]["cells"][$j][2]);
			$material_desc = mysqli_real_escape_string($dbc, $data->sheets[$i]["cells"][$j][3]);
			$work_center = mysqli_real_escape_string($dbc, $data->sheets[$i]["cells"][$j][4]);
			$seq_pps1 = mysqli_real_escape_string($dbc, $data->sheets[$i]["cells"][$j][5]);
			$seq_pps2 = mysqli_real_escape_string($dbc, $data->sheets[$i]["cells"][$j][7]);
			//echo $data->sheets[$i]["cells"][$j][2];
				
				
			$html.="</tr>";
			
			
				if($material_no != " " )
					{
						
			$query_1 = "INSERT INTO pps_upload(ref_id, plan_no, upload_id, model_code, month_plan, material_no, qty_plan, qty_actual, status_pps, comp_code, work_center, shift_pps1, shift_pps2, date_plan, user_upload, date_upload, user_create, date_create, user_update, date_update, plan_category, seq_pps1, seq_pps2) VALUES('','','".$upload_id."','".$model_code."','".$month_plan."','".$material_no."','','','New','".$data_setup["comp_code"]."','".$work_center."','".$shift_pps1."','".$shift_pps2."','".$date1."','$username',NOW(),'$username',NOW(),'','','".$plan_category."','".$seq_pps1."','".$seq_pps2."')";
			
				$result_1 = mysqli_query($dbc, $query_1);

					}// end if
					
			
		}
		
		
	}

}

$html.="</table>";
//echo $html;

			
//	------ move file to another folder ----------------------------------
			$handle2 = $file;
			$destination = "upload_pps_update/".$file;
			$data = file_get_contents($handle2);

			$handle2 = fopen($destination, "w");
			fwrite($handle2, $data);
			fclose($handle2);
			fclose($handle);
			unlink($file);
			

echo "<br />Data Inserted in dababase";

//--------------------------------------------------------------

} // end of foreach filename
			
	        $query_db_pps = "SELECT * FROM pps_upload WHERE status_pps = 'New'";
			$result_db_pps = mysqli_query($dbc, $query_db_pps);
             
			 while($row_db_pps = mysqli_fetch_array($result_db_pps))
			{
			
			$mon_plan = substr($row_db_pps["month_plan"],0,2);		
			
			 //---- check factory from work center -------// 
			$query_convert = "SELECT * FROM `work_center_detail` as SR WHERE SR.id_work = '".$row_db_pps["work_center"]."'";
			$result_convert = mysqli_query($dbc, $query_convert); 
			$row_convert = mysqli_fetch_array($result_convert);
			
			 //---------check shift --------------//
						
				if($row_db_pps["shift_pps1"] != "") 
				{
					
					
		//------generate Ref ID No.---------------------------------
	
	$query_id = "SELECT count_max FROM run_count_no WHERE uid = '45'";
	$result_id = mysqli_query($dbc, $query_id);
	
	if ($result_id) 
{
	$nrows = mysqli_num_rows($result_id);
	$row_id = mysqli_fetch_row($result_id);
	
	$dht = 0010000000; 
	$dg2 = 0;

  	if($row_id[0] <= 0)
  	{ 
   
    	$lastID = ($row_id[0] + 1);
    	$dg = ($dht + ($lastID));
   }
   else
   {
      $lastID = ($row_id[0] + 1);
      $dg =  $lastID;
	
    }
	$number = $dg; // Length of running no
    $number = sprintf('%010d', $number);  
	
	//echo $number;
	
	} // end if $result_id		
			
						
					
				
			$query_shift_day1 = "INSERT INTO pps_detail(id, ref_id, plan_no, upload_id, model_code, month_plan, material_no, qty_plan, qty_actual, status_pps, comp_code, work_center, shift_pps1, shift_pps2, date_plan, status, user_upload, date_upload, user_create, date_create, user_update, date_update, user_posting, date_posting, user_closed, date_closed, plan_category, id_factory_pps, rev_pps, seq_pps, man_hours, work_hours) VALUES('','".$number."','','".$row_db_pps["upload_id"]."','".$row_db_pps["model_code"]."','".$mon_plan."','".$row_db_pps["material_no"]."','".$row_db_pps["shift_pps1"]."','','New','".$row_db_pps["comp_code"]."','".$row_db_pps["work_center"]."','D/S','','".$row_db_pps["date_plan"]."','Y','".$row_db_pps["user_upload"]."','".$row_db_pps["date_upload"]."','".$row_db_pps["user_create"]."','".$row_db_pps["date_create"]."','','','','','','','".$row_db_pps["plan_category"]."','".$row_convert["id_factory"]."','','".$row_db_pps["seq_pps1"]."','','')";
		   $result_shift_day1 = mysqli_query($dbc, $query_shift_day1);	
		   
		   
		   
		   
	   //update count_max----------------------------------------
		
	
       $query_max_b = "UPDATE run_count_no SET count_max = '".$number."', date_updated = NOW() WHERE uid = '45'";
	   $result_max_b = mysqli_query($dbc, $query_max_b);
	 
      //end update count_max ---------------------------------	
		   
		   
				}
		      
			  if($row_db_pps["shift_pps2"] != "")
				{
	
	
	//------generate Ref ID No.---------------------------------
	
	$query_id = "SELECT count_max FROM run_count_no WHERE uid = '45'";
	$result_id = mysqli_query($dbc, $query_id);
	
	if ($result_id) 
{
	$nrows = mysqli_num_rows($result_id);
	$row_id = mysqli_fetch_row($result_id);
	
	$dht = 0010000000; 
	$dg2 = 0;

  	if($row_id[0] <= 0)
  	{ 
   
    	$lastID = ($row_id[0] + 1);
    	$dg = ($dht + ($lastID));
   }
   else
   {
      $lastID = ($row_id[0] + 1);
      $dg =  $lastID;
	
    }
	$number = $dg; // Length of running no
    $number = sprintf('%010d', $number);  
	
	//echo $number;
	
	} // end if $result_id		
							
					
					
				$query_shift_day2 = "INSERT INTO pps_detail(id, ref_id, plan_no, upload_id, model_code, month_plan, material_no, qty_plan, qty_actual, status_pps, comp_code, work_center, shift_pps1, shift_pps2, date_plan, status, user_upload, date_upload, user_create, date_create, user_update, date_update, user_posting, date_posting, user_closed, date_closed, plan_category, id_factory_pps, rev_pps, seq_pps, man_hours, work_hours) VALUES('','".$number."','','".$row_db_pps["upload_id"]."','".$row_db_pps["model_code"]."','".$mon_plan."','".$row_db_pps["material_no"]."','".$row_db_pps["shift_pps2"]."','','New','".$row_db_pps["comp_code"]."','".$row_db_pps["work_center"]."','','N/S','".$row_db_pps["date_plan"]."','Y','".$row_db_pps["user_upload"]."','".$row_db_pps["date_upload"]."','".$row_db_pps["user_create"]."','".$row_db_pps["date_create"]."','','','','','','','".$row_db_pps["plan_category"]."','".$row_convert["id_factory"]."','','".$row_db_pps["seq_pps2"]."','','')";
			$result_shift_day2 = mysqli_query($dbc, $query_shift_day2);	
			
			
		//update count_max----------------------------------------
		
	
       $query_max_b = "UPDATE run_count_no SET count_max = '".$number."', date_updated = NOW() WHERE uid = '45'";
	   $result_max_b = mysqli_query($dbc, $query_max_b);
	 
    //end update count_max ---------------------------------	
				
   					
				}
	
	

			}// end while loop
	
			
        //-------------------------------delete table pps_upload-------------------------------------
		    $query_hsekeeping = "DELETE FROM pps_upload";
			$result_hsekeeping =  mysqli_query($dbc, $query_hsekeeping);
	  
		//------------------------end delete upload table pps-upload---------------------------------	
		
	
	//--------------end transaction upload into table pps_upload------------------------------------//
	echo "<script>";
   // echo "window.location='index_planning.php'";
    echo "window.location='display_pps_month.php?upload_id=$upload_id'";
	//echo "window.open('display_pps_month.php?upload_id=$upload_id', 'myExample4', 'width=860,height=600')";
    echo "</script>";	
	exit;
		
?>
</body>
</html>