<?php

/**
 * admin/bc nor/material_master_edit_NA.php
 * Part of: Admin module
 * Filename suggests: material master edit NA
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: user_detail, sys_setup_maintain, mat_master_header, table_material, table_material_qc, mat_master_detail, bom.
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
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");
$Cdate = date ("l, j F Y ");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}


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
  <h4>Edit Material Master</h4>     

 
<?php

$id_hdr = $_GET['id_hdr'];

$queryu = "SELECT * FROM mat_master_header WHERE id_hdr = '".db_esc($dbc, $id_hdr)."'";
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
	
	

   
	//escape data for FG   
	/*$BUn = escape_data($_POST['BUn']);
	$material_group = escape_data($_POST['material_group']);
	$alternative_bom = escape_data($_POST['alternative_bom']);
	$date_create = escape_data($_POST['date1']);
	$date_bom_create = escape_data($_POST['date2']);
	$type_package = escape_data($_POST['type_package']);
	$location_deliver = escape_data($_POST['location_deliver']);
	$station_deliver = escape_data($_POST['station_deliver']);
	$rcv_point = escape_data($_POST['rcv_point']);
	$std_package = escape_data($_POST['std_package']);*/
	$status_BOM = escape_data($_POST['status_BOM']);

  
  
  //---------------------------------------------------------------------------------------
  // material component 
  //----------------------------------------------------------------------------------------

   
//if($material_desc && $material_type && $plant && $bom && $bom_usage && $std_package && $part_side && $status_BOM) //everything ok
//if($status_BOM == 'Y') //everything ok
if($_POST['status_BOM'] == 'Y')
{      	
	
	$query_search = "SELECT * FROM mat_master_header WHERE id_hdr = '".db_esc($dbc, $id_hdr)."'";
	$result_search = mysqli_query($dbc, $query_search);   //run the query.
	$num_search = mysqli_num_rows($result_search);   //how many suppliers are there?


	if($num_search == 1) 
	{
		//echo $num_search; 
		$row2 = mysqli_fetch_array($result_search, MYSQLI_NUM);
		
		// update tbl header
		$query_upd = "UPDATE mat_master_header SET status_BOM = '".db_esc($dbc, $status_BOM)."',date_updated = NOW(),updated_by='".db_esc($dbc, $username)."' WHERE id_hdr = '".db_esc($dbc, $id_hdr)."'"; 
		$result_upd = mysqli_query($dbc, $query_upd); 
		
		//update tbl material for header
		$query_updM = "UPDATE table_material SET bom_status = '".db_esc($dbc, $status_BOM)."',date_updated = NOW(),updated_by = '".db_esc($dbc, $username)."' WHERE  material_no = '".db_esc($dbc, $row[1])."' "; 
		$result_updM = mysqli_query($dbc, $query_updM);
		
		//update table material qc
		//if($_POST['material_type'] == 'Z310')
		if($row[3] == 'Z310')
		{
			//update table material qc
			$query_updQ = "UPDATE table_material_qc SET bom_status = '".db_esc($dbc, $_POST['status_BOM'])."',date_updated = NOW(),updated_by = '".db_esc($dbc, $username)."' WHERE  material_no = '".db_esc($dbc, $row[1])."' "; 
			$result_updQ = mysqli_query($dbc, $query_updQ); 	
				
		}//end mat type z310
			
		
		
		//-------------- for component------------------------------//
		
		$query_searchCP = "SELECT * FROM mat_master_detail WHERE id_hdr = '".db_esc($dbc, $id_hdr)."'";
		$result_searchCP = mysqli_query($dbc, $query_searchCP);   //run the query.
		$num_searchCP = mysqli_num_rows($result_searchCP);   //how many suppliers are there?
		$row_CP = mysqli_fetch_row($result_searchCP);
		$row_CP2 = mysqli_fetch_array($result_searchCP);
		
		//if ada components
		if($num_searchCP  > 0)
		{
			
			$id_dtl = $_POST['id_dtl'];//id component
			$size = count($_POST["id_dtl"]) + 1;
			
			$i = 1;
			
			///--------------------------start checking component ---------------------------------------------------  
			// check for a material_desc_c
			
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
		/*	$matl_group = escape_data($_POST['matl_group'][$i]);
			$alternative_bom_c = escape_data($_POST['alternative_bom_c'][$i]);
			$comp_unit = escape_data($_POST['comp_unit'][$i]);
			$sloc = escape_data($_POST['sloc'][$i]);
			$isloc = escape_data($_POST['isloc'][$i]);
			$valid_from = escape_data($_POST['date3'][$i]);
			$date_create_bom = escape_data($_POST['date4'][$i]);*/
	
	
	
			while ($i < $size) {
			
				
	  		if($_POST["bom_status"][$i] == 'Y')//if update component status = Y,but header status = N
			{
				
				$query_sel = "SELECT * FROM mat_master_header WHERE id_hdr = '".db_esc($dbc, $id_hdr)."'";
				$result_sel = mysqli_query($dbc, $query_sel); 
				$row_sel = mysqli_fetch_array($result_sel);
				
				/*if($row_sel["status_BOM"] == 'N') 
				{
					/*echo "<script>";
					echo "alert('Error to update bom status due tu bom header is non active.');";
					echo "parent.tb_remove(); parent.location.reload(1)";
					echo "</script>";
				}
				else
				{*/
					//update tbl component
					$query_upd5 = "UPDATE mat_master_detail SET
									 bom_status = '".db_esc($dbc, $_POST["bom_status"][$i])."',date_updated = NOW(),updated_by = '".db_esc($dbc, $username)."'
										WHERE id_dtl = '".db_esc($dbc, $_POST["id_dtl"][$i])."'";
					$result_upd5 = mysqli_query($dbc, $query_upd5);
					
					
					//update tbl material
					$query_updMT = "UPDATE table_material SET
											 bom_status = '".db_esc($dbc, $_POST['bom_status'][$i])."',date_updated = NOW(),updated_by = '".db_esc($dbc, $username)."'
												WHERE material_no = '".db_esc($dbc, $row_CP[10])."' ";
					$result_updMT = mysqli_query($dbc, $query_updMT);
					
					//update table material qc
					if($row_CP2["mat_type"][$i] == 'Z310')
					{
						//check if exist
						$searchz3C = "SELECT * FROM table_material_qc WHERE material_no = '".db_esc($dbc, $row[10])."' ";
						$rst_searchz3C = mysqli_query($dbc, $searchz3C);   
						$result_searchz3C = mysqli_fetch_array($rst_searchz3C);
						
						if($result_searchz3C > 0)
						{
							//update table material qc
							$query_updQC = "UPDATE table_material SET
											 bom_status = '".db_esc($dbc, $_POST['bom_status'][$i])."',date_updated = NOW(),updated_by = '".db_esc($dbc, $username)."'
												WHERE material_no = '".db_esc($dbc, $row_CP[10])."'  "; 
							$result_updQC = mysqli_query($dbc, $query_updQC); 
						}
						else
						{			
							//insert into table material QC FOR Z310
							$ist_qc = "INSERT INTO table_material_qc
								(id_mat,material_no,material_desc,mat_type,plan_code,BUn,date_create_bom,bom_status,material_group,date_uploaded,uploaded_by)
									VALUES('','".db_esc($dbc, $row_CP[10])."','".db_esc($dbc, $material_desc_c)."','".db_esc($dbc, $mat_type_c)."','".db_esc($dbc, $plant_c)."' ,'".db_esc($dbc, $comp_unit)."','".db_esc($dbc, $date_create_bom)."', '".db_esc($dbc, $bom_status)."', '".db_esc($dbc, $matl_group)."',NOW(),'".db_esc($dbc, $username)."') ";
										
							$result_qc = mysqli_query($dbc, $ist_qc) or die('Error, failed to add into table material qc.');	
						}	
					}//end mat type z310
							
					
				//}
				
			}
			
			/*//if update header = Y,component also status Y
			if($_POST['status_BOM'] == 'Y')
			{
				//upd tbl component
				$updcp = "UPDATE mat_master_detail SET bom_status = '".$_POST['bom_status']."' WHERE id_hdr = '".$id_hdr."'";
				$rst_updcp = mysqli_query($dbc, $updcp) or die(db_fail($dbc)); 

				//upd tbl material
				$updtb = "UPDATE table_material SET bom_status = '".$_POST['bom_status']."' WHERE material_no = '".$row_CP2["bill_component"]."' "; 
				$rst_updtb = mysqli_query($dbc, $updtb) or die(db_fail($dbc));
					
			}*/
	

			$i++;
			} // end while loop

		
		}//end if ada component
	
		
		
		
		if($result_upd || $result_updM || $result_updQ )
		{
			echo "<script>";
			echo "alert('Material Request successfully update.');";
			echo "parent.tb_remove(); parent.location.reload(1)";
			echo "</script>"; 	
		}
		/*elseif()
		{
		}*/
		
	}//end if ada header

	  
} 
else
{
	echo "<script>";
	echo "alert('Error to update bom status due tu bom header is non active.');";
	echo "parent.tb_remove(); parent.location.reload(1)";
	echo "</script>";
}
//---------------------------function message------------------------------ 
if (isset($message))
{ 
	echo '<div class="msg msg-error"><font color="red" class ="error_entry">', $message, '</font></div>';
}
} 
 ?> 

          <form name="form1" method="post" action="material_master_edit_NA.php?id_hdr=<?php echo $id_hdr; ?>" >
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
                   <input name="material_desc" type="text" class="span1" id="material_desc" size="55" maxlength="100" value="<?php echo $row[2]; ?>" disabled/>
                 </td>
               </tr>
               <tr>
                 <td>Material Type <font color="#FF0000">*</font></td>
                 <td>:</td>
                 <td>
         <input name="material_type" type="text" class="span1" id="material_type" size="20" maxlength="20" value="<?php echo $row[3]; ?>" disabled/>
                 </td>
               </tr>
               <tr>
                 <td>Material Group</td>
                 <td>:</td>
                 
                 <td><input name="material_group" type="text" class="span1" id="material_group" size="20" maxlength="20" value="<?php echo $row[4]; ?>" disabled/></td>
               </tr>
               <tr>
                 <td>Plant <font color="#FF0000">*</font></td>
                 <td>:</td>
                 <td><input name="plant" type="text" class="span1" id="plant" size="20" maxlength="20" value="<?php echo $row[5]; ?>" disabled/>
                  <font color="#006699"> </font></td>
               </tr>
               <tr>
                 <td>BOM <font color="#FF0000">*</font></td>
                 <td>:</td>
                 <td><input name="bom" type="text" class="span1" id="bom" size="20" maxlength="20" value="<?php echo $row[7]; ?>" disabled/></td>
               </tr>
               <tr>
                 <td>Alternative BOM</td>
                 <td>:</td>
                 <td>
         <input name="alternative_bom" type="text" class="span1" id="alternative_bom" size="20" maxlength="20" value="<?php echo $row[8]; ?>" disabled/>
                 </td>
               </tr>
               <tr>
                 <td height="25">BOM Usage <font color="#FF0000">*</font></td>
                 <td height="25">:</td>
                 <td height="25">
                   <input name="bom_usage" type="text" class="span1" id="bom_usage" size="20" maxlength="20" value="<?php echo $row[6]; ?>" disabled/>
               </td>
               </tr>
               <tr>
                 <td height="25">UoM</td>
                 <td height="25">:</td>
                 <td height="25">
                   <input name="BUn" type="text" class="span1" id="BUn" size="20" maxlength="20" value="<?php echo $row[9]; ?>" disabled/></td>
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
                 <td height="25"><input name="std_package" id="std_package" type="number" min="0" class="span1" value="<?php echo $row[13]; ?>" disabled></td>
               </tr>
               <tr>
                 <td height="25">Type of package</td>
                 <td height="25">:</td>
                 <td height="25"><input name="type_package" type="text" class="span1" id="type_package" size="20" maxlength="20" value="<?php echo $row[14]; ?>" disabled/></td>
               </tr>
               <tr>
                 <td height="25">Location Deliver</td>
                 <td height="25">:</td>
                 <td height="25"><input name="location_deliver" type="text" class="span1" id="location_deliver" size="20" maxlength="20" value="<?php echo $row[15]; ?>" disabled/></td>
               </tr>
               <tr>
                 <td height="25">Station Deliver</td>
                 <td height="25">:</td>
                 <td height="25"><input name="station_deliver" type="text" class="span1" id="station_deliver" size="20" maxlength="20" value="<?php echo $row[16]; ?>" disabled/></td>
               </tr>
               <tr>
                 <td height="25">Received Point</td>
                 <td height="25">:</td>
                 <td height="25"><input name="rcv_point" type="text" class="span1" id="rcv_point" size="20" maxlength="20" value="<?php echo $row[17]; ?>" disabled/></td>
               </tr>
               <tr>
                 <td height="25">Part of Side <font color="#FF0000">*</font></td>
                 <td height="25">:</td>
                 <td height="25">
            <select name="part_side"  class="textbox" disabled>
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
			 
	  $query_component = "SELECT *, DATE_FORMAT(valid_from, '%d-%m-%Y') AS R FROM mat_master_header AS h, mat_master_detail AS s WHERE h.id_hdr = s.id_hdr AND h.id_hdr = '".db_esc($dbc, $id_hdr)."'";
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
                   <input name="material_desc_c[<?php echo $i; ?>]" type="text"  class="span1" id="material_desc_c" size="55" maxlength="100" value="<?php echo $row2["material_desc_c"]; ?>" disabled/>
                </td>
               </tr>
                        <tr>
                 <td>Material Type <font color="#FF0000">*</font></td>
                 <td>:</td>
                 <td>
         <input name="mat_type_c[<?php echo $i; ?>]" type="text"  class="span1" id="mat_type_c" size="20" maxlength="20" value="<?php echo $row2["mat_type"]; ?>" disabled/>
                 </td>
               </tr>
               
               <tr>
                 <td>Material Group</td>
                 <td>:</td>
                 <td><input name="matl_group[<?php echo $i; ?>]" type="text"  class="span1" id="matl_group" size="20" maxlength="20" value="<?php echo $row2["matl_group"]; ?>" disabled/></td>
               </tr>
      
               <tr>
                 <td>Plant <font color="#FF0000">*</font></td>
                 <td>:</td>
                 <td><input name="plant_c[<?php echo $i; ?>]" type="text"  class="span1" id="plant_c" size="20" maxlength="20" value="<?php echo $row2["plant"]; ?>" disabled/>
               </td>
               </tr>
               <tr>
                 <td>BOM <font color="#FF0000">*</font></td>
                 <td>:</td>
                 <td><input name="bom_c[<?php echo $i; ?>]" type="text"  class="span1" id="bom_c" size="20" maxlength="20" value="<?php echo $row2["bom"]; ?>" disabled/>
                </td>
               </tr>
               <tr>
                 <td>Alternative BOM</td>
                 <td>:</td>
                 <td>
         <input name="alternative_bom_c[<?php echo $i; ?>]" type="text"  class="span1" id="alternative_bom_c" size="20" maxlength="20" value="<?php echo $row2["alternative_bom"]; ?>" disabled/>
                 </td>
               </tr>
               <tr>
                 <td>Consumption <font color="#FF0000">*</font></td>
                 <td>:</td>
                 <td>
                   <input name="consumption[<?php echo $i; ?>]" type="text" class="span1" id="consumption" size="20" maxlength="20" value="<?php echo $row2["consumption"]; ?>" disabled/>
                </td>
               </tr>
               <tr>
                 <td>UoM</td>
                 <td>:</td>
                 <td>
                   <input name="comp_unit[<?php echo $i; ?>]" type="text" class="span1" id="comp_unit" size="20" maxlength="20" value="<?php echo $row2["comp_unit"]; ?>" disabled/>
                 </td>
               </tr>
                    <tr>
                 <td>SLoc</td>
                 <td>:</td>
                 <td>
                   <input name="sloc[<?php echo $i; ?>]" type="text" class="span1" id="sloc" size="20" maxlength="20" value="<?php echo $row2["sloc"]; ?>" disabled/>
                 </td>
               </tr>
                 <tr>
                 <td>IsLoc</td>
                 <td>:</td>
                 <td>
                   <input name="isloc[<?php echo $i; ?>]" type="text" class="span1" id="isloc" size="20" maxlength="20" value="<?php echo $row2["isloc"]; ?>" disabled/>
                 </td>
               </tr>
               <tr>
                 <td>Valid From</td>
                 <td>:</td>
                 <td>
              <input name="date3[<?php echo $i; ?>]" type="text" class="span1" id="date3" size="20" maxlength="20" value="<?php echo $row2["valid_from"]; ?>" disabled/>   
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
                  <input name="date4[<?php echo $i; ?>]" type="text" class="span1" id="date4" size="20" maxlength="20" value="<?php echo $row2["date_create_bom"]; ?>" disabled/>
            
        
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
