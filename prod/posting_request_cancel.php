<?php

/**
 * prod/posting_request_cancel.php
 * Part of: Production module
 * Filename suggests: posting request cancel
 *
 * Behavior: requires an active login session ($_SESSION['username']); processes submitted form data ($_POST).
 * Database tables referenced: sys_setup_maintain, factory_detail, work_center_detail, material_request, scan_detail, user_detail, post_detail_header.
 * Includes: config.php, paginator.class2.php, tc_calendar.php.
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

//$Cdate = date ("l, j F Y ");
date_default_timezone_set("Asia/Kuala_Lumpur");

// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}
//--------setup website page --------------------------
$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
$rs_setup = mysqli_query($dbc, $query_setup);   //run the query.
$num_setup = mysqli_num_rows($rs_setup);   //how many material are there?
$data_setup = mysqli_fetch_array($rs_setup);
//----------------------------------------------------	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo h($data_setup["title_desc"]); ?></title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../css/style.css" type="text/css" media="all" />
<link rel="stylesheet" href="../scripts/pagination3.css" type="text/css" />
<link rel="stylesheet" href="../scripts/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="../javascript/jquery-latest.js"></script> 
<script type="text/javascript" src="../javascript/thickbox.js"></script>	
<link href="../calendar/calendar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../calendar/calendar.js"></script>
<link href="../css/ddtabmenu.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style3 {color: #000000}
-->
</style>
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
$url = 'material_request_listProc.php';
$url2 = 'display_request.php';
$url3 = 'posting_request.php';


$warnaGenap = "#F4FBCA";   // warna blue grey
$warnaGanjil = "#f8f8f8";  // warna putih

?>
<script type="text/javascript">
//SYNTAX: ddtabmenu.definemenu("tab_menu_id", integer OR "auto")
ddtabmenu.definemenu("ddtabs1", 0) //initialize Tab Menu #1 with 1st tab selected
ddtabmenu.definemenu("ddtabs2", 1) //initialize Tab Menu #2 with 2nd tab selected
ddtabmenu.definemenu("ddtabs3", 1) //initialize Tab Menu #3 with 2nd tab selected
ddtabmenu.definemenu("ddtabs4", 2) //initialize Tab Menu #4 with 3rd tab selected
ddtabmenu.definemenu("ddtabs5", -1) //initialize Tab Menu #5 with NO tabs selected (-1)
</script>
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
<body>
<!-- Header -->
<!-- End Header -->
<!-- Container -->
<div id="container">
   <div class="small-nav">Cancel Material Request Initial Screen</div>
<div class="shell">
  <!-- Small Nav --> 
  
    <!-- End Small Nav -->

    
    <!-- Message OK --><!-- End Message OK -->
    <!-- Message Error -->
    <!-- End Message Error -->
    <br />
    <!-- Main -->
    <div id="main">
<table width="750">
  <tr>
    <td width="2%" height="45"></td>
    <td width="98%"><table width="800">
      <tr>
        <td width="2%" height="45"></td>
        <td width="98%"><form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="frmSearch" id="frmSearch">
          <table width="880" style="border:solid 1px #d5d5d5;">
            <tr>
              <th height="25"><div align="right">Posting Date From :</div></th>
              <td height="25"><?php
    
				$myCalendar = new tc_calendar("date1", true);
				$myCalendar->setIcon("../calendar/images/iconCalendar.gif");
				$myCalendar->setDate(0,0,0);
				$myCalendar->setPath("/calendar/");
				$myCalendar->setYearInterval(date('Y'), date('Y') + 10);
				//$myCalendar->dateAllow('2010-01-01', '2015-03-01');
				//$myCalendar->setHeight(350);
				//$myCalendar->autoSubmit(true, "form1");
				//$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-13", "2011-04-25"), 0, 'month');
				// $myCalendar->setOnChange("myChanged('test')");
				//$myCalendar->disabledDay("Sat");
				// $myCalendar->disabledDay("sun");
				//$myCalendar->rtl = true;
				$myCalendar->writeScript();

?></td>
              <th height="25"><div align="right">Posting Date To :</div></th>
              <td height="25" colspan="2"><?php
                
                $myCalendar = new tc_calendar("date2", true);
                $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
                $myCalendar->setDate(0,0,0);
                $myCalendar->setPath("/calendar/");
                $myCalendar->setYearInterval(date('Y'), date('Y') + 10);
                //$myCalendar->dateAllow('2010-01-01', '2015-03-01');
                //$myCalendar->setHeight(350);
                //$myCalendar->autoSubmit(true, "form1");
                //$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-13", "2011-04-25"), 0, 'month');
                // $myCalendar->setOnChange("myChanged('test')");
                //$myCalendar->disabledDay("Sat");
                // $myCalendar->disabledDay("sun");
                //$myCalendar->rtl = true;
                $myCalendar->writeScript();
                
                ?></td>
            </tr>
            <tr>
              <th width="132" height="25"><div align="right">MRIN No : </div></th>
              <td width="286" height="25"><input name="temp_mrin" type="text" id="temp_mrin" size="25" style="background:#FFFF97" />
                      <img src="../images/search.png" width="20" height="20" /></td>
              <th width="124" height="25"><div align="right">Production Order :</div></th>
              <td height="25" colspan="2"><input name="prod_order" type="text" id="prod_order" size="25" style="background:#FFFF97" />
                      <img src="../images/search.png" width="20" height="20" /></td>
            </tr>
            <tr>
              <th height="25"><div align="right">Factory :</div></th>
              <td height="25"><select name="factory" id="factory">
                <option value="NULL" placeholder="Select Factory"> -- Select Factory --</option>
                <?php
	       $query3 = "SELECT * FROM factory_detail GROUP BY factory_desc2 ORDER BY id_fac ASC";
                   $result3 = mysqli_query($dbc, $query3);
  
                   while($row3=mysqli_fetch_array($result3, MYSQLI_NUM)) 
			      {
                  echo'<option value="',$row3[2],'">',stripslashes($row3[1]),'</option>';
                  }
				?>
              </select></td>
              <th height="25"><div align="right">Work Center :</div></th>
              <td height="25" colspan="2"><select name="work_center" id="work_center">
                <option value="NULL" placeholder="Select Work Center"> -- Select Work Center --</option>
                <?php
	       $query4 = "SELECT * FROM work_center_detail ORDER BY id_work ASC";
                   $result4 = mysqli_query($dbc, $query4);
  
                   while($row4=mysqli_fetch_array($result4, MYSQLI_NUM)) 
			      {
                  echo'<option value="',$row4[0],'">',stripslashes($row4[0]),'</option>';
                  }
				?>
              </select></td>
            </tr>
            <tr>
              <th height="25">&nbsp;</th>
              <th height="25">&nbsp;</th>
              <th height="25">&nbsp;</th>
              <th width="197" height="25">&nbsp;</th>
              <th width="117"><input name="Submit2" type="submit" class="button" id="button" value="SEARCH" /></th>
            </tr>
            <tr>
              <th height="41" colspan="5">&nbsp;</th>
            </tr>
          </table>
        </form></td>
      </tr>
    </table></td>
  </tr>
</table>
<p>
<?php
        if(isset($_POST["Submit2"]))
        {
            $temp_mrin = $_POST["temp_mrin"];
			$factory = $_POST["factory"];
			$prod_order = $_POST["prod_order"];
            $dateF = $_POST["date1"];
            $dateT = $_POST["date2"];
			$work_center = $_POST["work_center"];
			 
            echo "<script>";
			echo "window.location='posting_request_cancel2.php?temp_mrin=$temp_mrin&&prod_order=$prod_order&&date1=$dateF&&date2=$dateT&&work_center=$work_center&&factory=$factory'";
            //echo "window.location='posting_request_all2.php'";
            echo "</script>";
            exit(); //quit the script
        }
        
    	?>
        <!-- Content -->
      </p>
      <p>&nbsp; </p>
      <div id="content">
        <!-- Box -->
        <div class="box">
          <!-- Box Head -->
          <div class="box-head">
            <h2 class="left">Cancel Material Request</h2>
            <div class="right">
              <label></label>
            </div>
          </div>
          <!-- End Box Head -->
          
          
          <?php
		  
		 

								 
   $query8 = "SELECT *, DATE_FORMAT(MR.date_mrin,'%d-%m-%Y') AS R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R2 FROM material_request AS MR, scan_detail AS SD WHERE MR.id_scan = SD.id_scan AND MR.status_request = 'Y' AND (MR.status != 'Close' AND MR.status != 'Cancel') GROUP BY MR.temp_mrin";
     $result8 = mysqli_query($dbc, $query8) or trigger_error("SQL", E_USER_ERROR);
     //$num_8 = mysqli_fetch_row($result8);
     $num_rows = mysqli_num_rows($result8);

   $pages = new Paginator;
   $pages->items_total = $num_rows;
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate();
 
 
  
$query = "SELECT *, DATE_FORMAT(MR.date_mrin,'%d-%m-%Y') AS R, DATE_FORMAT(MR.date_posting,'%d-%m-%Y') AS R2 FROM material_request AS MR, scan_detail AS SD WHERE MR.id_scan = SD.id_scan AND MR.status_request = 'Y' AND (MR.status != 'Close' AND MR.status != 'Cancel') GROUP BY MR.temp_mrin ORDER BY MR.date_mrin DESC,MR.time_mrin DESC $pages->limit";
$rs = mysqli_query($dbc, $query);   //run the query.
//$num = mysqli_num_rows($rs);   //how many material are there?

	echo '<br>';
	echo '<br>';
	
	 if($num_rows > 0) {	
	 
	 echo '<div align="center">There are currently  '. $num_rows.' record(s).</div>';
	
	

?>

<table width="92%">
<tr>
            <td width="48%" height="15">&nbsp;</td>
        <td width="51%"><div align="right"><?php echo "<span class=\"\">".$pages->display_jump_menu().$pages->display_items_per_page()."</span>" ;?></div>              </td>
        <td width="1%">&nbsp;</td>
          </tr>
</table>  
<br>
          <!-- Table 
          <div class="table">-->
           
<br />

     
       
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:solid 1px #d5d5d5;">
              <tr>
                <th width="140" height="28" bgcolor="#E9F58D"><span class="style3">MRIN No.</span></th>
                <th width="77" height="28" bgcolor="#E9F58D"><span class="style3">Factory</span></th>
                <th width="98" height="28" bgcolor="#E9F58D"><span class="style3">Line</span></th>
                <th width="69" height="28" bgcolor="#E9F58D"><span class="style3">Request Date</span></th>
                <th width="56" bgcolor="#E9F58D"><span class="style3">Request Time</span></th>
                <th width="103" height="28" bgcolor="#E9F58D" class="ac style3">Requestor</th>
                <th width="87" bgcolor="#E9F58D" class="ac style3">Status</th>
                 <th width="60" bgcolor="#E9F58D" class="ac style3">Cancel</th>
                <th width="40" bgcolor="#E9F58D" class="ac style3">View</th>
                <th width="40" bgcolor="#E9F58D" class="ac style3">Print</th>
            </tr>
          </table>
    <?php
		  
   $counter = 1;
   $no = 1;
   
    while ($row2 = mysqli_fetch_array($rs))
   {
		//$user_no = $row[0]; 

		
		if ($counter % 2 == 0)
		{ $warna = $warnaGenap;}
		else { $warna = $warnaGanjil; }	
		
		
	$query_scan = "SELECT * FROM scan_detail WHERE id_scan = '".db_esc($dbc, $row2[6])."'";
   $result_scan = mysqli_query($dbc, $query_scan);
   $row_scan = mysqli_fetch_array($result_scan);	
	
	$query_again = "SELECT * FROM material_request WHERE status_request = 'Y' and id_scan = '".db_esc($dbc, $row2[6])."' ORDER BY id_req ASC";
    $rs_again = mysqli_query($dbc, $query_again);   //run the query.
    $row = mysqli_fetch_array($rs_again);
	
	$query3 = "SELECT * FROM factory_detail WHERE id_fac = '".db_esc($dbc, $row_scan["factory"])."'";
    $result3 = mysqli_query($dbc, $query3);
	$row3 = mysqli_fetch_array($result3);
	
	$query_u = "SELECT * FROM user_detail WHERE user_no = '".db_esc($dbc, $row['user_create'])."'";
$result_u = mysqli_query($dbc, $query_u);   //run the query.
$data_u = mysqli_fetch_array($result_u);   //how many records are there?       
		  

		  ?>         
          <table width="100%" border="0" cellpadding="0" cellspacing="0"  style="border:solid 1px #d5d5d5;">
  <tr>
    <td width="130">&nbsp;<?php echo h($row2["temp_mrin"]); ?></td>
    <td width="70"><?php echo h($row_scan["factory"]); ?></td>
     <td width="70" height="28"><?php echo h($row_scan["work_center"]); ?></td>
                <td width="70"><?php echo h($row2["R"]); ?>&nbsp;</td>
                <td width="57"><?php echo h($row2["time_mrin"]); ?></td>
                <td width="103"><?php echo h($data_u["user_fullname"]); ?></td>
                
  <?php

	//------------------------   Traffic light-------------------------------------------------------------
		//- edit date : 5/11/2014    by : azie
		//-----------------------------------------------------------------------------------------------------
		
		
$curr_time = date("Y-m-d H:i:s"); 
$request_time = ($row2["date_mrin"].' '.$row2["time_mrin"]);

$min_20 = date("Y-m-d H:i:s", strtotime("$request_time - 20 minutes"));
$plus_20 = date("Y-m-d H:i:s", strtotime("$request_time + 20 minutes"));

   $start_date = new DateTime($min_20);
   $diff_time = $start_date->diff(new DateTime($request_time));


if ($curr_time < $min_20)
{
                echo '<td width="33"><img src="../images/grey_icon.jpg" width="25" height="25" /></td>';
}
elseif(($curr_time >= $min_20) && ($curr_time < $request_time) )
{
                echo '<td width="33"><img src="../images/green_icon2.jpg" width="25" height="25" /></td>';
}
elseif(($curr_time >= $request_time) && ($curr_time < $plus_20) )
{
                echo '<td width="33"><img src="../images/yellow_icon2.jpg" width="25" height="25" /></td>';
}
elseif($curr_time >= $plus_20)
{
                echo '<td width="33"><img src="../images/red_icon2.jpg" width="25" height="25" /></td>';
}

	
		//---checking transfer poasting-----
  $query_tp = "SELECT * FROM post_detail_header WHERE mrin_no = '".db_esc($dbc, $row2[3])."' AND mvt_type = 311 AND (status_posting = 'New' OR status_posting != 'Close' OR status_posting != 'Cancel')";
  $result_tp  = mysqli_query($dbc, $query_tp); 
	$row_tp = mysqli_fetch_array($result_tp);
	
   
	  if($row_tp["mrin_no"] == $row2["temp_mrin"])
	  {
	  
	   echo "<td width=60>&nbsp;</td>";
	  
	  }else{	  
	
	
	?>
    <td width="60"><a href="cancel_material_request.php?mrin_no=<?php echo h($row2["temp_mrin"]); ?>&&prod_order=<?php echo h($row2["prod_order"]); ?>&&user_no=<?php echo h($data_u["user_no"]); ?>&&TB_iframe=true&&height=400&&width=1000" class="thickbox" target="_self"><img src="../images/delete.png" width="16" height="16" alt="Delete">Cancel</a></td>
<?php
	  }
	 ?>       
              <td width="40"><a value="Details" href="detail_material_request.php?mrin_no=<?php echo h($row2["temp_mrin"]); ?>&&TB_iframe=true&&height=400&&width=800" class="thickbox" target="_self"><img src="../images/icon_view.jpg" width="16" height="16" alt="View">View</a></td>
                <td width="40"><a href="detail_material_request_printing.php?mrin_no=<?php echo h($row2["temp_mrin"]); ?>&&TB_iframe=true&&height=400&&width=800" class="thickbox" target="_self"><img src="../images/print.jpg" width="16" height="16" alt="Print">Print</a> </td>
  </tr>
</table>    
  <?php 
		 
		 $no ++;
		  
		  $counter++; // menambah counter 
		
		   
		  } ?>
<p>&nbsp;</p>

  </center>
  <?php
   mysqli_free_result($rs); 
	?>
      <table width="700" height="25" border="0" align="center" >
<tr>
				<td width="10%">&nbsp;</td>
	      <td width="90%"><div align="left"><?php 
					//Display pagination
					echo $pages->display_pages();
					echo '<font class="Verdana">';
					echo '<p>&nbsp; </p>';
					echo "<p class=\"paginate\">Page: $pages->current_page of $pages->num_pages</p>\n";
					echo '</font>';  ?></div></td>
	    </tr>
			  <tr>
				<td colspan="2"></td>
			  </tr>
		  </table> 
          
          
    <?php }   // free up the resources 
  else
{
?><center>
<table width="800" cellspacing="0" class="textboxred">
  <tr> 
    <td><div align="center"><font color="#FF0000"><strong>There are currently 
          no material request.</strong></font></div></td>
  </tr>
</table></center>
        <?php
		   } 
mysqli_close($dbc)
?>


<p>&nbsp;</p>
<p>&nbsp;</p>
<p>Legend :</p>
<table width="70%" border="1">
  <tr>
    <td width="10%"><div align="center"><img src="../images/red_icon2.jpg" alt="" width="20" height="20" /></div></td>
    <td width="90%">MRIN Request has passed 20 minutes from the requested time.</td>
  </tr>
  <tr>
    <td><div align="center"><img src="../images/yellow_icon2.jpg" alt="" width="20" height="20" /></div></td>
    <td>MRIN Request has reached the requested time.</td>
  </tr>
  <tr>
    <td><div align="center"><img src="../images/green_icon2.jpg" alt="" width="20" height="20" /></div></td>
    <td>MRIN Request is now 20 minutes before the requested time.</td>
  </tr>
  <tr>
    <td><div align="center"><img src="../images/grey_icon.jpg" alt="" width="20" height="20" /></div></td>
    <td>New MRIN Request has been posted.</td>
  </tr>
</table>
<p>&nbsp;</p>

                    
            
        
           
          <!-- Table 
        </div>-->
        <!-- End Box -->
       
      </div>
      <!-- End Content -->
      <!-- Sidebar -->
      <!-- End Sidebar -->
      <div class="cl">&nbsp;</div>
    </div>
    <!-- Main -->
  </div>
</div>
</body>
</html>
