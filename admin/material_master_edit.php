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


$query2 = "SELECT * FROM user_detail WHERE username = '$username'";
$result2 = mysqli_query($dbc, $query2) or die (mysqli_error($dbc));
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
<title><?php echo $data_setup["title_desc"]; ?></title>
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
<script language="javascript" src="../calendar/calendar.js"></script>
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


//- First page:
$url = 'material_master_list.php';


?>
<body>
<div id="content">
  <h4>&nbsp;</h4>
  <h4>Edit Material Master</h4>     

 
<?php

$id_hdr = $_GET['id_hdr'];

$queryu = "SELECT * FROM mat_master_header WHERE id_hdr = '".$id_hdr."'";
$resultu = mysqli_query($dbc, $queryu) ;   //run the query.
$row = mysqli_fetch_row($resultu);   //how many records are there?


if (isset($_POST['submit']))
{
	
	$id_hdr = $_POST['id_hdr'];//id material
	//$id_dtl = $_POST['id_dtl'];//id component

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
	
	//count component
	/*$size = count($_POST["id_dtl"]) + 1;
	
	$i = 1;*/
	
	//------------------------------end function --------------------------------
	// check for a material no.
	/*if (empty($_POST['material_no']))
	{ 
		$material_no = FALSE;
		$message.= '<p>You are required to enter Material No.!</p>';
	}*/
	
	
	//CHECKING FOR MAT HEADER
	// check for a material desc
	if (empty($_POST['material_desc']))
	{ 
		$material_desc = FALSE;
		$message.= '<p>You are required to enter Material Description!</p>';
	}
	else
	{ 
		$material_desc = escape_data($_POST['material_desc']);
	}
	
	// check for a material type
	if (empty($_POST['material_type']))
	{ 
		$material_type = FALSE;
		$message.= '<p> You are required to enter Material Type!</p>';
	}
	else
	{ 
		$material_type = escape_data($_POST['material_type']);
	}
	
	// check for a material GROUP
	if (empty($_POST['material_group']))
	{ 
		$material_group = FALSE;
		$message.= '<p> You are required to enter Material Group!</p>';
	}
	else
	{ 
		$material_group = escape_data($_POST['material_group']);
	}
	
	// check for a plant
	if (empty($_POST['plant']))
	{ 
		$plant = FALSE;
		$message.= '<p> You are required to enter Plant!</p>';
	}
	else
	{ 
		$plant = escape_data($_POST['plant']);
	}
	
	// check for a bom
	if (empty($_POST['bom']))
	{ 
		$bom = FALSE;
		$message.= '<p> You are required to enter BOM!</p>';
	}
	else
	{ 
		$bom = escape_data($_POST['bom']);
	}
	

	
	// check for a bom_usage
	if (empty($_POST['bom_usage']))
	{ 
		$bom_usage = FALSE;
		$message.= '<p> You are required to enter BOM Usage!</p>';
	}
	else
	{ 
		$bom_usage = escape_data($_POST['bom_usage']);
	}
	

	
	// check for a standard package
	/*if (empty($_POST['std_package']))
	{ 
		$std_package = FALSE;
		$message.= '<p> You are required to enter Standard Packaging!</p>';
	}
	else
	{ 
		$std_package = escape_data($_POST['std_package']);
	}*/
	
	
	// check for a part side
	if (empty($_POST['part_side']))
	{ 
		$part_side = FALSE;
		$message.= '<p> You are required to enter Part of Side!</p>';
	}
	else
	{ 	
		$part_side = escape_data($_POST['part_side']);
	} 
	
	
	//check for status BOM
	if (empty($_POST['status_BOM']))
	{ 
		$status_BOM = FALSE;
		$message.= '<p> You are required to select BOM Status!</p>';
	}
	else
	{ 	
		$status_BOM = escape_data($_POST['status_BOM']);
	}
	



	/*///--------------------------start checking component ---------------------------------------------------  
	// check for a material_desc_c
	if (empty($_POST['material_desc_c'][$i]))
	{ 
		$material_desc_c = FALSE;
		$message.= '<p> You are required to enter Material Description Component!</p>';
	}
	else
	{ 	
		$material_desc_c = escape_data($_POST['material_desc_c'][$i]);
	}
	
	// check for a mat_type_c
	if (empty($_POST['mat_type_c'][$i]))
	{ 
		$mat_type_c = FALSE;
		$message.= '<p> You are required to enter Material Type Component!</p>';
	}
	else
	{ 
		$mat_type_c = escape_data($_POST['mat_type_c'][$i]);
	}
	
	
	//check for plant_c
	if (empty($_POST['plant_c'][$i]))
	{ 
		$plant_c = FALSE;
		$message.= '<p> You are required to enter Plant of Component!</p>';
	}
	else
	{ 
		$plant_c = escape_data($_POST['plant_c'][$i]);
	}
	
	//check for bom_c
	if (empty($_POST['bom_c'][$i]))
	{ 
		$bom_c = FALSE;
		$message.= '<p> You are required to enter Bill of Material Component!</p>';
	}
	else
	{ 
		$bom_c = escape_data($_POST['bom_c'][$i]);
	}
	
	//check for consumption
	if (empty($_POST['consumption'][$i]))
	{ 
		$consumption = FALSE;
		$message.= '<p> You are required to enter Consumption!</p>';
	}
	else
	{ 
		$consumption = escape_data($_POST['consumption'][$i]);
	}
	
	  //check for bom status
	if (empty($_POST['bom_status'][$i]))
	{ 
		$bom_status = FALSE;
		$message.= '<p> You are required to select BOM Status!</p>';
	}
	else
	{ 
		$bom_status = escape_data($_POST['bom_status'][$i]);
	}*/
   
   
	//escape data for FG   
	$BUn = escape_data($_POST['BUn']);
	$material_group = escape_data($_POST['material_group']);
	$alternative_bom = escape_data($_POST['alternative_bom']);
	$date_create = escape_data($_POST['date1']);
	$date_bom_create = escape_data($_POST['date2']);
	$type_package = escape_data($_POST['type_package']);
	$location_deliver = escape_data($_POST['location_deliver']);
	$station_deliver = escape_data($_POST['station_deliver']);
	$rcv_point = escape_data($_POST['rcv_point']);
	$std_package = escape_data($_POST['std_package']);

	
	/*//escape data for component
	$matl_group = escape_data($_POST['matl_group'][$i]);
	$alternative_bom_c = escape_data($_POST['alternative_bom_c'][$i]);
	$comp_unit = escape_data($_POST['comp_unit'][$i]);
	$sloc = escape_data($_POST['sloc'][$i]);
	$isloc = escape_data($_POST['isloc'][$i]);
	$valid_from = escape_data($_POST['date3'][$i]);
	$date_create_bom = escape_data($_POST['date4'][$i]);*/
  
  
  //---------------------------------------------------------------------------------------
  // material component 
  //----------------------------------------------------------------------------------------

   
//if($material_desc && $material_type && $plant && $bom && $bom_usage && $std_package && $part_side && $status_BOM) //everything ok
if($material_desc && $material_type && $plant && $bom && $bom_usage && $part_side && $status_BOM) //everything ok
{      	
	
	$query_search = "SELECT * FROM mat_master_header WHERE id_hdr = '".$id_hdr."'";
	$result_search = mysqli_query($dbc, $query_search);   //run the query.
	$num_search = mysqli_num_rows($result_search);   //how many suppliers are there?

	if($num_search == 1) 
	{
		//echo $num_search; 
		$row2 = mysqli_fetch_array($result_search, MYSQLI_NUM);
		
		// update tbl header
		$query_upd = "UPDATE mat_master_header SET material_desc = '$material_desc', material_type = '$material_type', material_group = '$material_group', plant = '$plant', bom = '$bom', alternative_bom = '$alternative_bom', bom_usage = '$bom_usage', BUn = '$BUn', date_create = '$date_create', date_bom_create = '$date_bom_create', std_package = '$std_package', part_side = '$part_side', status_BOM = '$status_BOM', type_package = '$type_package', location_deliver = '$location_deliver', station_deliver = '$station_deliver', date_updated = NOW(),updated_by='$username'
						WHERE id_hdr = '$id_hdr'"; 
		$result_upd = mysqli_query($dbc, $query_upd); 
		
		//update tbl material
		$query_updM = "UPDATE table_material SET material_desc = '$material_desc',mat_type = '$material_type', plan_code = '$plant', BUn = '$BUn', date_create_bom = '$date_bom_create', bom_status = '$status_BOM',date_updated = NOW(),updated_by = '$username'
						  WHERE  material_no = '$row[1]' "; 
		$result_updM = mysqli_query($dbc, $query_updM);
		
		
		if($_POST['material_type'] == 'Z310')
		{
			
			$searchz3 = "SELECT * FROM table_material_qc WHERE material_no = '$row[1]' ";
			$rst_searchz3 = mysqli_query($dbc, $searchz3);   
			$result_searchz3 = mysqli_fetch_array($rst_searchz3);
			$result_z3 = mysqli_num_rows($rst_searchz3);

			//if($result_searchz3 > 0)
			if($result_z3 == 1)
			{
				//update table material qc
				$query_updQ = "UPDATE table_material_qc SET material_desc = '$material_desc',mat_type = '$material_type',plan_code = '$plant', BUn = '$BUn', date_create_bom = '$date_bom_create', bom_status = '$status_BOM',material_group = '$material_group' ,date_updated = NOW(),updated_by = '$username'
									WHERE  material_no = '$row[1]' "; 
				$result_updQ = mysqli_query($dbc, $query_updQ)or die('Error, failed to UPDATE table material qc.');
			}
			else
			{			
				//insert into table material QC FOR Z310
				$ist_qc = "INSERT INTO table_material_qc
					(id_mat,material_no,material_desc,mat_type,plan_code,BUn,date_create_bom,bom_status,material_group,date_uploaded,uploaded_by)
						VALUES('','$row[1]','$material_desc','$material_type','$plant','$BUn','$date_bom_create','$status_BOM','$material_group',NOW(),'".$username."') ";
							
				$result_qc = mysqli_query($dbc, $ist_qc) or die('Error, failed to add into table material qc.');	
			}
		}
		
			
		
		/*//update table material qc
		if($_POST['material_type'] == 'Z310')
		{
			//check if exist
			//$searchz3 = "SELECT * FROM table_material_qc WHERE material_no = '$row[1]' AND mat_type = '".$_POST['material_type']."' AND material_group = '".$_POST['material_group']."' AND bom_status = '".$_POST['status_BOM']."' ";
			
			
			
		}//end mat type z310*/
			
		
		//DELETE FROM Z310
		//delete header from table material qc if mat type != Z310 
		if($_POST['material_type'] != 'Z310')
		{
			
			//check if exist
			$searchDz3 = "SELECT * FROM mat_master_header WHERE material_no = '$row[1]' AND status_BOM = '".$_POST['status_BOM']."'  ";
			$rst_searchDz3 = mysqli_query($dbc, $searchDz3) or die(mysqli_error($dbc));   
			$result_searchDz3 = mysqli_fetch_array($rst_searchDz3);
		
			if($result_searchDz3 > 0)
			{
				$query_delQ = "DELETE FROM table_material_qc WHERE material_no = '$row[1]' AND bom_status = '".$_POST['status_BOM']."' "; 
				$result_delQ = mysqli_query($dbc, $query_delQ) or die('Error, failed to delete from table material qc.');	 
			}
				
		}
			
		
		//--------------checking for component---------------------------------//
		// $i = 1;
		//for components
		$query_searchCP = "SELECT * FROM mat_master_detail WHERE id_hdr = '".$id_hdr."' ";
		$result_searchCP = mysqli_query($dbc, $query_searchCP);   //run the query.
		$num_searchCP = mysqli_num_rows($result_searchCP);   //how many suppliers are there?
		$row_CP = mysqli_fetch_row($result_searchCP);
		
		
		//if ada components
		if($num_searchCP  > 0)
		{
			
			$id_dtl = $_POST['id_dtl'];//id component
			$size = count($_POST["id_dtl"]) + 1;
			
			$i = 1;
			
			///--------------------------start checking component ---------------------------------------------------  
			// check for a material_desc_c
			if (empty($_POST['material_desc_c'][$i]))
			{ 
				$material_desc_c = FALSE;
				$message.= '<p> You are required to enter Material Description Component!</p>';
			}
			else
			{ 	
				$material_desc_c = escape_data($_POST['material_desc_c'][$i]);
			}
			
			// check for a mat_type_c
			if (empty($_POST['mat_type_c'][$i]))
			{ 
				$mat_type_c = FALSE;
				$message.= '<p> You are required to enter Material Type Component!</p>';
			}
			else
			{ 
				$mat_type_c = escape_data($_POST['mat_type_c'][$i]);
			}
			
			
			//check for plant_c
			if (empty($_POST['plant_c'][$i]))
			{ 
				$plant_c = FALSE;
				$message.= '<p> You are required to enter Plant of Component!</p>';
			}
			else
			{ 
				$plant_c = escape_data($_POST['plant_c'][$i]);
			}
			
			//check for bom_c
			if (empty($_POST['bom_c'][$i]))
			{ 
				$bom_c = FALSE;
				$message.= '<p> You are required to enter Bill of Material Component!</p>';
			}
			else
			{ 
				$bom_c = escape_data($_POST['bom_c'][$i]);
			}
			
			//check for consumption
			if (empty($_POST['consumption'][$i]))
			{ 
				$consumption = FALSE;
				$message.= '<p> You are required to enter Consumption!</p>';
			}
			else
			{ 
				$consumption = escape_data($_POST['consumption'][$i]);
			}
			
			  //check for bom status
			if (empty($_POST['bom_status'][$i]))
			{ 
				$bom_status = FALSE;
				$message.= '<p> You are required to select BOM Status!</p>';
			}
			else
			{ 
				$bom_status = escape_data($_POST['bom_status'][$i]);
			}
			
			
			//escape data for component
			$matl_group = escape_data($_POST['matl_group'][$i]);
			$alternative_bom_c = escape_data($_POST['alternative_bom_c'][$i]);
			$comp_unit = escape_data($_POST['comp_unit'][$i]);
			$sloc = escape_data($_POST['sloc'][$i]);
			$isloc = escape_data($_POST['isloc'][$i]);
			$valid_from = escape_data($_POST['date3'][$i]);
			$date_create_bom = escape_data($_POST['date4'][$i]);
	
	
	
			while ($i < $size) {
				
			$sc = "SELECT * FROM mat_master_detail WHERE id_dtl = '".$_POST["id_dtl"][$i]."'";
			$rst_sc = mysqli_query($dbc, $sc);   //run the query.
			$result_sc = mysqli_fetch_array($rst_sc);
			$num_sc = mysqli_num_rows($rst_sc);   //how many suppliers are there?
			$row_sc = mysqli_fetch_row($rst_sc);
			
			//echo "billr".$result_sc['bill_component'];
	  
	  		//update tbl component
			$query_upd5 = "UPDATE mat_master_detail SET material_desc_c = '".$_POST["material_desc_c"][$i]."',
									 mat_type = '".$_POST["mat_type_c"][$i]."', 
									 bom_status = '".$_POST["bom_status"][$i]."',
									 matl_group = '".$_POST["matl_group"][$i]."', 
									 alternative_bom = '".$_POST["alternative_bom_c"][$i]."', 
									 consumption = '".$_POST["consumption"][$i]."', 
									 comp_unit = '".$_POST["comp_unit"][$i]."', 
									 plant = '".$_POST['plant_c'][$i]."',
									 bom = '".$_POST['bom_c'][$i]."',
									 sloc = '".$_POST["sloc"][$i]."', 
									 isloc = '".$_POST["isloc"][$i]."', 
									 valid_from = '".$_POST["date3"][$i]."', 
									 date_create_bom = '".$_POST["date4"][$i]."',
									 date_updated = NOW(),
									 updated_by = '$username'  
									 WHERE id_dtl = '".$_POST["id_dtl"][$i]."'";
									 
			$result_upd5 = mysqli_query($dbc, $query_upd5)or die('Error, failed to update tbl master details.');
			
			
			//update tbl material
			$query_updMT = "UPDATE table_material SET material_desc = '".$_POST["material_desc_c"][$i]."',
									mat_type = '".$_POST["mat_type_c"][$i]."', 
									plan_code = '".$_POST['plant_c'][$i]."',
									BUn =  '".$_POST['comp_unit'][$i]."',
									date_create_bom = '".$_POST["date4"][$i]."',
									bom_status = '".$_POST['bom_status'][$i]."',
									date_updated = NOW(),updated_by = '".$username."'
									WHERE material_no = '".$result_sc["bill_component"]."' ";
									
			$result_updMT = mysqli_query($dbc, $query_updMT)or die('Error, failed to update tbl material.');
			
			
			//if header = non active,component = non active
			if( $_POST['status_BOM'] == 'N')
			{
				//upd tbl component
				$updcp = "UPDATE mat_master_detail SET bom_status = '".$_POST['status_BOM']."' WHERE id_hdr = '$id_hdr' "; 
				$rst_updcp = mysqli_query($dbc, $updcp) or die(mysqli_error($dbc)); 

				//upd tbl material
				$updtb = "UPDATE table_material SET bom_status = '".$_POST['status_BOM']."' WHERE material_no = '".$result_sc["bill_component"]."' "; 
				$rst_updtb = mysqli_query($dbc, $updtb) or die(mysqli_error($dbc));
					
			}
			
			
			//update table material qc
			if($_POST["mat_type_c"][$i] == 'Z310')
			{
				//check if exist
				//$searchz3C = "SELECT * FROM table_material_qc WHERE material_no = '$row_CP[10][$i]' AND mat_type = '".$_POST["mat_type_c"][$i]."'  AND bom_status = '".$_POST['bom_status'][$i]."' AND material_group = '".$_POST['matl_group'][$i]."' ";
				$searchz3C = "SELECT * FROM table_material_qc WHERE material_no = '".$result_sc["bill_component"]."' AND bom_status = '".$_POST['bom_status'][$i]."' ";
				$rst_searchz3C = mysqli_query($dbc, $searchz3C);   
				$result_searchz3C = mysqli_fetch_array($rst_searchz3C);
				
				$obj_searchz3C = mysqli_num_rows($rst_searchz3C);
				$occ = mysqli_fetch_row($rst_searchz3C);
				
				
				/*echo "MT". $result_searchz3C['material_no'];
				echo  "</br>";*/
				
				if($result_searchz3C > 0)
				{
					//update table material qc
					$query_updQC = "UPDATE table_material_qc SET material_desc = '".$_POST["material_desc_c"][$i]."',
										mat_type = '".$_POST["mat_type_c"][$i]."', 
										plan_code = '".$_POST['plant_c'][$i]."',
										BUn =  '".$_POST['comp_unit'][$i]."',
										date_create_bom = '".$_POST["date4"][$i]."',
										bom_status = '".$_POST['bom_status'][$i]."',
										material_group = '".$_POST['matl_group'][$i]."',
										date_updated = NOW(),updated_by = '$username'
										WHERE material_no = '".$result_sc["bill_component"]."'  "; 
										
					$result_updQC = mysqli_query($dbc, $query_updQC)or die('Error, failed to update table material qc.');	 
				}
				else
				{			
					//insert into table material QC FOR Z310
					$ist_qc = "INSERT INTO table_material_qc
						(id_mat,material_no,material_desc,mat_type,plan_code,BUn,date_create_bom,bom_status,material_group,date_uploaded,uploaded_by)
							VALUES('','".$result_sc["bill_component"]."','".$_POST["material_desc_c"][$i]."','".$_POST["mat_type_c"][$i]."','".$_POST['plant_c'][$i]."' ,'".$_POST['comp_unit'][$i]."',
									'".$_POST["date4"][$i]."','".$_POST['bom_status'][$i]."','".$_POST['matl_group'][$i]."',NOW(),'".$username."' ) ";
								
					$result_qc = mysqli_query($dbc, $ist_qc) or die('Error, failed to add into table material qc.');	
					
				}	
			}//end mat type z310
			
			
			
			//DELETE COMPONENT FROM Z310
			//delete component from table material qc if mat type != Z310 
			if($_POST["mat_type_c"][$i] != 'Z310')
			{
				
				//check if exist
				/*$searchDz3C = "SELECT * FROM mat_master_detail WHERE id_hdr = '".$id_hdr."'  ";
				$rst_searchDz3C = mysqli_query($dbc, $searchDz3C) or die(mysqli_error($dbc));   
				$searchDz3C = mysqli_fetch_array($rst_searchDz3C);*/
				
				$searchzD3C = "SELECT * FROM table_material_qc WHERE material_no = '".$result_sc["bill_component"]."' ";
				$rst_searchzD3C = mysqli_query($dbc, $searchzD3C);   
				$QsearchzD3C = mysqli_fetch_array($rst_searchzD3C);
				
				
				if($QsearchzD3C > 0)
				{
					$query_delQC = "DELETE FROM table_material_qc WHERE material_no = '".$result_sc["bill_component"]."' AND bom_status = '".$_POST['bom_status'][$i]."' "; 
					$result_delQC = mysqli_query($dbc, $query_delQC) or die('Error, failed to delete component from table material qc .');	 
				}
					
			}
				

			$i++;
			} // end while loop
			

			//if(mysqli_affected_rows($dbc) == 1)
			/*if($result_upd || $result_upd5)
			{	
				$query_upd2 = "UPDATE mat_master_detail SET plant = '$plant', bom = '$bom' WHERE id_hdr = '$id_hdr'"; 
				$result_upd2 = mysqli_query($dbc, $query_upd2); */		
				
				/*
				echo "<script>";
				echo "alert('Material Request successfully update.');";
				echo "parent.tb_remove(); parent.location.reload(1)";
				echo "</script>"; 
				*/
				//echo "bb";
				
			/*	exit(); //quit the script				
			} 
			else 
			{ 
				echo 'Cannot update record'; 
			} */
		
		}//end if ada component
		
		
		if($result_upd || $result_updM || $result_upd5 || $result_updMT )
		{
				echo "<script>";
				echo "alert('Material Request successfully update.');";
				echo "parent.tb_remove(); parent.location.reload(1)";
				echo "</script>"; 	
				
				//echo "bb";
		}
		/*elseif()
		{
		}*/
		
	}//end if ada header

	  
} 
//---------------------------function message------------------------------ 
if (isset($message))
{ 
	echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}
} 
 ?> 

          <form name="form1" method="post" action="material_master_edit.php?id_hdr=<?php echo $id_hdr; ?>" >
            <table class="table table-striped table-bordered">
               <tr>
                 <td width="28%">Material No. <font color="#FF0000">*</font></td>
                 <td width="3%">:</td>
                 <td width="69%">
                   <input name="material_no" type="text" class="span1"  id="material_no" size="20" maxlength="8" readonly value="<?php echo $row[1]; ?>" />
                 </td>
               </tr>
               <tr>
                 <td>Material Desc <font color="#FF0000">*</font></td>
                 <td>:</td>
                 <td>
                   <input name="material_desc" type="text" class="span1" id="material_desc" size="55" maxlength="100" value="<?php echo $row[2]; ?>" />
                 </td>
               </tr>
               <tr>
                 <td>Material Type <font color="#FF0000">*</font></td>
                 <td>:</td>
                 <td>
         <input name="material_type" type="text" class="span1" id="material_type" size="20" maxlength="20" value="<?php echo $row[3]; ?>" />
                 </td>
               </tr>
               <tr>
                 <td>Material Group</td>
                 <td>:</td>
                 
                 <td><input name="material_group" type="text" class="span1" id="material_group" size="20" maxlength="20" value="<?php echo $row[4]; ?>" /></td>
               </tr>
               <tr>
                 <td>Plant <font color="#FF0000">*</font></td>
                 <td>:</td>
                 <td><input name="plant" type="text" class="span1" id="plant" size="20" maxlength="20" value="<?php echo $row[5]; ?>" />
                  <font color="#006699"> </font></td>
               </tr>
               <tr>
                 <td>BOM <font color="#FF0000">*</font></td>
                 <td>:</td>
                 <td><input name="bom" type="text" class="span1" id="bom" size="20" maxlength="20" value="<?php echo $row[7]; ?>" /></td>
               </tr>
               <tr>
                 <td>Alternative BOM</td>
                 <td>:</td>
                 <td>
         <input name="alternative_bom" type="text" class="span1" id="alternative_bom" size="20" maxlength="20" value="<?php echo $row[8]; ?>" />
                 </td>
               </tr>
               <tr>
                 <td height="25">BOM Usage <font color="#FF0000">*</font></td>
                 <td height="25">:</td>
                 <td height="25">
                   <input name="bom_usage" type="text" class="span1" id="bom_usage" size="20" maxlength="20" value="<?php echo $row[6]; ?>" />
               </td>
               </tr>
               <tr>
                 <td height="25">UoM</td>
                 <td height="25">:</td>
                 <td height="25">
                   <input name="BUn" type="text" class="span1" id="BUn" size="20" maxlength="20" value="<?php echo $row[9]; ?>" /></td>
               </tr>
               <tr>
                 <td height="25">Date Created</td>
                 <td height="25">:</td>
                 <td height="25" class="span3">
                 <?php
    
					 $dt = substr($row[10],8,2);
					 $mt = substr($row[10],5,2);
					 $yr = substr($row[10],0,4);
	 
	                  $myCalendar = new tc_calendar("date1", true, false);
					  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					  $myCalendar->setDate($dt,$mt,$yr);
					  $myCalendar->setPath("/calendar/");
					  $myCalendar->setYearInterval(2000, 2030);
					 // $myCalendar->setAlignment('left', 'bottom');
					  // $myCalendar->setOnChange("myChanged('test')");
					  $myCalendar->writeScript();
					  
             ?></td>
               </tr>
               <tr>
                 <td>Date BOM Created</td>
                 <td>:</td>
                 <td>
                  <?php
    
					 $dt2 = substr($row[11],8,2);
					 $mt2 = substr($row[11],5,2);
					 $yr2 = substr($row[11],0,4);
	 
	                  $myCalendar = new tc_calendar("date2", true, false);
					  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					  $myCalendar->setDate($dt2,$mt2,$yr2);
					  $myCalendar->setPath("/calendar/");
					  $myCalendar->setYearInterval(2000, 2030);
					  // $myCalendar->setOnChange("myChanged('test')");
					  $myCalendar->writeScript();
					  
					?></td>
               </tr>
               <tr>
                 <td height="25">Standard Packaging <font color="#FF0000">*</font></td>
                 <td height="25">:</td>
                 <td height="25"><input name="std_package" id="std_package" type="number" min="0" class="span1" value="<?php echo $row[13]; ?>" ></td>
               </tr>
               <tr>
                 <td height="25">Type of package</td>
                 <td height="25">:</td>
                 <td height="25"><input name="type_package" type="text" class="span1" id="type_package" size="20" maxlength="20" value="<?php echo $row[14]; ?>" /></td>
               </tr>
               <tr>
                 <td height="25">Location Deliver</td>
                 <td height="25">:</td>
                 <td height="25"><input name="location_deliver" type="text" class="span1" id="location_deliver" size="20" maxlength="20" value="<?php echo $row[15]; ?>" /></td>
               </tr>
               <tr>
                 <td height="25">Station Deliver</td>
                 <td height="25">:</td>
                 <td height="25"><input name="station_deliver" type="text" class="span1" id="station_deliver" size="20" maxlength="20" value="<?php echo $row[16]; ?>" /></td>
               </tr>
               <tr>
                 <td height="25">Received Point</td>
                 <td height="25">:</td>
                 <td height="25"><input name="rcv_point" type="text" class="span1" id="rcv_point" size="20" maxlength="20" value="<?php echo $row[17]; ?>" /></td>
               </tr>
               <tr>
                 <td height="25">Part of Side <font color="#FF0000">*</font></td>
                 <td height="25">:</td>
                 <td height="25">
            <select name="part_side"  class="textbox">
            <option value="NULL" placeholder="Select Part of Side"> -- Select Part of Side --</option>
            <option value="LH" class="title" <?php if($row[18] == 'LH') echo "selected"; ?>>LH - Left Hand</option>
            <option value="RH" class="title" <?php if($row[18] == 'RH') echo "selected"; ?>>RH - Right Hand</option>
            <option value="RH/LH" class="title" <?php if($row[18] == 'RH/LH') echo "selected"; ?>>RH/LH - Right Hand/Left Hand</option>
             <option value="LH/RH" class="title" <?php if($row[18] == 'LH/RH') echo "selected"; ?>>LH/RH - Left Hand/Right Hand</option>
            </select>
               </td>
               </tr>
               <tr>
                 <td height="25">Status <font color="#FF0000">*</font></td>
                 <td height="25">:</td>
                 <td height="25"><select name="status_BOM"  class="textbox">
      <option value="NULL" placeholder="Select Status"> -- Select Status --</option>
	  <option value="Y" class="title" <?php if($row[12] == 'Y') echo "selected"; ?>>Y - Active</option>
	  <option value="N" class="title" <?php if($row[12] == 'N') echo "selected"; ?>>N - Inactive</option>
	      </select></td>
               </tr>
               <tr>
                 <td height="25"><font color="#FF0000">* </font>Compulsory field</td>
                 <td height="25">&nbsp;</td>
                 <td height="25">&nbsp;</td>
               </tr>
            </table>
             <p>&nbsp;</p>
			 <?php
			 
			 $i = 1;
			 
	  $query_component = "SELECT *, DATE_FORMAT(valid_from, '%d-%m-%Y') AS R FROM mat_master_header AS h, mat_master_detail AS s WHERE h.id_hdr = s.id_hdr AND h.id_hdr = '$id_hdr'";
	   $result_component = mysqli_query($dbc, $query_component);
	   
	  while($row2 = mysqli_fetch_array($result_component))
			{  
			 ?> 
             
            <h5><img src="../img/display_icon.png" width="24" height="24"/>Edit Component Detail</h5>

                   <table class="table table-striped table-bordered">
               <tr>
                 <td width="28%">Component <font color="#FF0000">*</font></td>
                 <td width="3%" height="25">:</td>
                 <td width="69%" height="25">
                   <input name="bill_component[<?php echo $i; ?>]" type="text" id="bill_component" size="20" maxlength="8" readonly value="<?php echo $row2["bill_component"];   ?>" />
                 </td>
               </tr>
               <tr>
                 <td>Component Description <font color="#FF0000">*</font></td>
                 <td>:</td>
                 <td>
                   <input name="material_desc_c[<?php echo $i; ?>]" type="text"  class="span1" id="material_desc_c" size="55" maxlength="100" value="<?php echo $row2["material_desc_c"]; ?>" />
                </td>
               </tr>
                        <tr>
                 <td>Material Type <font color="#FF0000">*</font></td>
                 <td>:</td>
                 <td>
         <input name="mat_type_c[<?php echo $i; ?>]" type="text"  class="span1" id="mat_type_c" size="20" maxlength="20" value="<?php echo $row2["mat_type"]; ?>" />
                 </td>
               </tr>
               
               <tr>
                 <td>Material Group</td>
                 <td>:</td>
                 <td><input name="matl_group[<?php echo $i; ?>]" type="text"  class="span1" id="matl_group" size="20" maxlength="20" value="<?php echo $row2["matl_group"]; ?>" /></td>
               </tr>
      
               <tr>
                 <td>Plant <font color="#FF0000">*</font></td>
                 <td>:</td>
                 <td><input name="plant_c[<?php echo $i; ?>]" type="text"  class="span1" id="plant_c" size="20" maxlength="20" value="<?php echo $row2["plant"]; ?>" />
               </td>
               </tr>
               <tr>
                 <td>BOM <font color="#FF0000">*</font></td>
                 <td>:</td>
                 <td><input name="bom_c[<?php echo $i; ?>]" type="text"  class="span1" id="bom_c" size="20" maxlength="20" value="<?php echo $row2["bom"]; ?>" />
                </td>
               </tr>
               <tr>
                 <td>Alternative BOM</td>
                 <td>:</td>
                 <td>
         <input name="alternative_bom_c[<?php echo $i; ?>]" type="text"  class="span1" id="alternative_bom_c" size="20" maxlength="20" value="<?php echo $row2["alternative_bom"]; ?>" />
                 </td>
               </tr>
               <tr>
                 <td>Consumption <font color="#FF0000">*</font></td>
                 <td>:</td>
                 <td>
                   <input name="consumption[<?php echo $i; ?>]" type="text" class="span1" id="consumption" size="20" maxlength="20" value="<?php echo $row2["consumption"]; ?>" />
                </td>
               </tr>
               <tr>
                 <td>UoM</td>
                 <td>:</td>
                 <td>
                   <input name="comp_unit[<?php echo $i; ?>]" type="text" class="span1" id="comp_unit" size="20" maxlength="20" value="<?php echo $row2["comp_unit"]; ?>" />
                 </td>
               </tr>
                    <tr>
                 <td>SLoc</td>
                 <td>:</td>
                 <td>
                   <input name="sloc[<?php echo $i; ?>]" type="text" class="span1" id="sloc" size="20" maxlength="20" value="<?php echo $row2["sloc"]; ?>" />
                 </td>
               </tr>
                 <tr>
                 <td>IsLoc</td>
                 <td>:</td>
                 <td>
                   <input name="isloc[<?php echo $i; ?>]" type="text" class="span1" id="isloc" size="20" maxlength="20" value="<?php echo $row2["isloc"]; ?>" />
                 </td>
               </tr>
               <tr>
                 <td>Valid From</td>
                 <td>:</td>
                 <td>
              <input name="date3[<?php echo $i; ?>]" type="text" class="span1" id="date3" size="20" maxlength="20" value="<?php echo $row2["valid_from"]; ?>" />   
                 <?php
    
	/*$dt3 = substr($row2["valid_from"],8,2);
     $mt3 = substr($row2["valid_from"],5,2);
     $yr3 = substr($row2["valid_from"],0,4);
	 
	 echo 
	                  $myCalendar = new tc_calendar("date3", true);
					  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					 $myCalendar->setDate($dt3,$mt3,$yr3);
					 $myCalendar->setPath("/calendar/");
					  $myCalendar->setYearInterval(2001, 2030);
					  //$myCalendar->dateAllow('2010-01-01', '2015-03-01');
					  //$myCalendar->setHeight(350);
					  //$myCalendar->autoSubmit(true, "form1");
					  //$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-13", "2011-04-25"), 0, 'month');
					  // $myCalendar->setOnChange("myChanged('test')");
					  //$myCalendar->disabledDay("Sat");
					 // $myCalendar->disabledDay("sun");
					  //$myCalendar->rtl = true;
					  $myCalendar->writeScript();
					  
					  
					  */
					  
				?></td>
               </tr>
               <tr>
                 <td>Date BOM Created</td>
                 <td>:</td>
                 <td>
                  <input name="date4[<?php echo $i; ?>]" type="text" class="span1" id="date4" size="20" maxlength="20" value="<?php echo $row2["date_create_bom"]; ?>" />
            
        
                  <?php
    
	/*$dt4 = substr($row2["date_create_bom"],8,2);
     $mt4 = substr($row2["date_bom_create"],5,2);
     $yr4 = substr($row2["date_bom_create"],0,4);
	 
	                  $myCalendar = new tc_calendar("date4[$i]", true);
					  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
					 $myCalendar->setDate($dt4,$mt4,$yr4);
					 $myCalendar->setPath("/calendar/");
					  $myCalendar->setYearInterval(2001, 2030);
					  //$myCalendar->dateAllow('2010-01-01', '2015-03-01');
					  //$myCalendar->setHeight(350);
					  //$myCalendar->autoSubmit(true, "form1");
					  //$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-13", "2011-04-25"), 0, 'month');
					  // $myCalendar->setOnChange("myChanged('test')");
					  //$myCalendar->disabledDay("Sat");
					 // $myCalendar->disabledDay("sun");
					  //$myCalendar->rtl = true;
					  $myCalendar->writeScript();  */
					  
?></td>
               </tr>
               <?php
			   
			    if($row2["bom_status"] == "Y")
			  {
				 $sts = "Active";
				 }
				 else{
				 $sts = "Inactive";
				 }
			   ?>
               <tr>
                 <td>Status BOM <font color="#FF0000">*</font></td>
                 <td>:</td>
                 <td><select name="bom_status[<?php echo $i; ?>]"  class="span1">
                      <option value ="<?php  echo $row2["bom_status"]; ?>" ><?php  echo $sts; ?></option>
                      <option value="Y" class="title">Active</option>
                      <option value="N" class="title">Inactive</option>
                          </select></td>
               </tr>
               <tr>
                 <td>&nbsp; <input type="hidden" name="id_dtl[<?php echo $i; ?>]" id="id_dtl" value="<?php echo $row2["id_dtl"]; ?>"></td>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
               </tr>
                <tr>
                 <td><font color="#FF0000">* </font>Compulsory field</td>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
               </tr>
               
               
            </table>
            
            <?php
			
			
		 $i++;
		 
			 }  ?>
            

           <input name="submit" type="submit" class="btn btn-success" id="submit" value="UPDATE">
          <input name="Reset" type="reset"  class="btn btn-danger" id="Reset" value="CLEAR">
           <input type="hidden" name="id_hdr" id="id_hdr" value="<?php echo $id_hdr; ?>">
          </form>

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
