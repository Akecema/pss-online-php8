<?php
session_start();
$username = $_SESSION['username'];
include '../include/config.php';
include_once ('../classes/paginator.class2.php');
require_once("../calendar/classes/tc_calendar.php");

//$Cdate = date ("l, j F Y ");
date_default_timezone_set('Asia/Bangkok');
// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username'])) {
header('Location: ../index.php');
exit();
}

 /* $today = getdate();
  $hours = $today['hours']; 
  $minutes = $today['minutes'];
  $seconds = $today['seconds'];
  $month = $today['mon']; 
  $mday = $today['mday']; 
  $year = $today['year'];   */

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Ingress Autoventures Co., Ltd.</title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../css/style.css" type="text/css" media="all" />
<link rel="stylesheet" href="../scripts/pagination3.css" type="text/css" />
<link rel="stylesheet" href="../scripts/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript" src="../javascript/jquery-latest.js"></script> 
<script type="text/javascript" src="../javascript/thickbox.js"></script>	
<link href="../calendar/calendar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../calendar/calendar.js"></script>
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
   <div class="small-nav">Consumable Request Maintenance<span>&nbsp;</span>Initial Screen</div>
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
                <td width="98%"><form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="frmSearch" id="frmSearch">
                    <table width="880" style="border:solid 1px #d5d5d5;">
                      <tr>
                        <th height="25"><div align="right">Posting Date From :</div></th>
                        <td height="25"><?php
			   
			    $dd1 = substr($_GET["date1"],8,2);
				$mm1 = substr($_GET["date1"],5,2);
				$yy1 = substr($_GET["date1"],0,4);
    
				$myCalendar = new tc_calendar("date1", true);
				$myCalendar->setIcon("../calendar/images/iconCalendar.gif");
				$myCalendar->setDate($dd1, $mm1, $yy1);
				$myCalendar->setPath("/calendar/");
				$myCalendar->setYearInterval(2001, 2030);
				// $myCalendar->setOnChange("myChanged('test')");
				$myCalendar->writeScript();

?></td>
                        <th height="25"><div align="right">Posting Date To :</div></th>
                        <td height="25" colspan="2"><?php
						
				$dd2 = substr($_GET['date2'],8,2);
				$mm2 = substr($_GET['date2'],5,2);
				$yy2 = substr($_GET['date2'],0,4);
                
                $myCalendar = new tc_calendar("date2", true);
                $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
                 $myCalendar->setDate($dd2, $mm2, $yy2);
                $myCalendar->setPath("/calendar/");
                $myCalendar->setYearInterval(2001, 2030);
                // $myCalendar->setOnChange("myChanged('test')");
                $myCalendar->writeScript();
                
                ?></td>
                      </tr>
                      <tr>
                        <th width="132" height="25"><div align="right">Factory :</div></th>
                        <td width="286" height="25"><select name="factory" id="factory">
                          <option value="NULL" placeholder="Select Factory"> -- Select Factory --</option>
                          <?php
	       $query3 = "SELECT * FROM factory_detail GROUP BY factory_desc2 ORDER BY id_fac ASC";
                   $result3 = mysql_query($query3);
  
                    while($row3=mysql_fetch_array($result3, MYSQL_NUM)) 
			      {
				  
				  
				  ?>
                            <option value="<?php echo $row3[2]; ?>" <?php if($row3[2] == $_GET["factory"]) echo "selected"; ?>> <?php echo $row3[1]; ?></option>
                            <?php
                  }
				?>
                        </select></td>
                        <th width="124" height="25"><div align="right">
                          <div align="right">MRIN No : </div>
                        </div></th>
                        <td height="25" colspan="2"><input name="temp_mrin" type="text" id="temp_mrin" size="25" style="background:#FFFF97" value="<?php echo $_GET["temp_mrin"]; ?>"/>
                            <img src="../images/search.png" width="20" height="20" /></td>
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
            $temp_mrin = $_GET["temp_mrin"];
            $dateF = $_GET["date1"];
            $dateT = $_GET["date2"];
			$factory = $_GET["factory"];
			
		  //********* CONDITION *************
		
			//-------Count all results------------------------//
		 $where_sql = '';
		 
		 // 1. temp_mrin
                if($temp_mrin == "") {
                     $wheresql_01 = ''; }
                else {
                      $wheresql_01 = " AND MR.temp_mrin = '$temp_mrin'"; }
		  // 2. dateF
                if ($dateF == "0000-00-00" ){
                    $wheresql_02 = ''; }
                else {
                    $wheresql_02 = " AND (MR.date_posting >= '$dateF')"; }      
                                                
		 // 3. dateT
                if ($dateT == "0000-00-00" ){
                    $wheresql_03 = ''; }
                else {
                    $wheresql_03 = " AND (MR.date_posting <= '$dateT')"; }          
                                
          //4. factory
                if ($factory == "NULL" ){
                    $wheresql_04 = ''; }
                else {
					$wheresql_04 = " AND MR.factory = '$factory'"; }
				
   
	   
			$where_sql =  $wheresql_01 .$wheresql_02 .$wheresql_03 .$wheresql_04;
	
	
	//********** END CONDITION **************	
			
    	?>
        <!-- Content -->
      </p>
      <p>&nbsp; </p>
      <div id="content">
        <!-- Box -->
        <div class="box">
          <!-- Box Head -->
          <div class="box-head">
            <h2 class="left">Consumable Request Maintenance</h2>
            <div class="right">
              <label></label>
            </div>
          </div>
          <!-- End Box Head -->
          
          
          <?php

								 
    $query8 = "SELECT *, DATE_FORMAT(MR.date_require,'%d-%m-%Y') AS R FROM consumable_request AS MR WHERE MR.status_request = 'Y' AND (MR.status != 'Close' AND MR.status != 'Cancel') ".$where_sql."  GROUP BY MR.temp_mrin";
	   $result8 = mysql_query($query8) or die(mysql_error());
     //$num_8 = mysql_fetch_row($result8);
     $num_rows = mysql_num_rows($result8);

   $pages = new Paginator;
   $pages->items_total = $num_rows;
   $pages->mid_range = 5; // Number of pages to display. Must be odd and > 3
   $pages->paginate(); 
   
$query = "SELECT *, DATE_FORMAT(MR.date_require,'%d-%m-%Y') AS R FROM consumable_request AS MR WHERE MR.status_request = 'Y' AND (MR.status != 'Close' AND MR.status != 'Cancel') ".$where_sql." GROUP BY MR.temp_mrin ORDER BY MR.date_posting ASC, MR.temp_mrin ASC $pages->limit";
$rs = mysql_query($query);   //run the query.
//$num = mysql_num_rows($rs);   //how many material are there?

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
                <th width="143" height="28" bgcolor="#E9F58D"><span class="style3">MRIN No.</span></th>
                <th width="50" height="28" bgcolor="#E9F58D"><span class="style3">Factory</span></th>
                <th width="50" height="28" bgcolor="#E9F58D"><span class="style3">Line</span></th>
                <th width="120" height="28" bgcolor="#E9F58D"><span class="style3">Request Date</span></th>
                <th height="28" bgcolor="#E9F58D" class="ac style3"><div align="left">Request Time</div></th>
                <th width="120" bgcolor="#E9F58D" class="ac style3"><div align="left">Requestor</div></th>
                <th width="50" bgcolor="#E9F58D" class="ac style3">Close</th>
                <th width="50" bgcolor="#E9F58D" class="ac style3">View</th>
                <th width="50" bgcolor="#E9F58D" class="ac style3">Print</th>
          </tr>
          </table>
    <?php
	  
   $counter = 1;
   $no = 1;
 
   
   while ($row2 = mysql_fetch_array($rs))
   {

		//if($row_req["total"] !=  $row_tp["total2"])
		
		//{

		
		if ($counter % 2 == 0)
		{ $warna = $warnaGenap;}
		else { $warna = $warnaGanjil; }	
   
   
	
	$query_again = "SELECT * FROM material_request WHERE status_request = 'Y' and id_scan = '".$row2[6]."' ORDER BY id_req ASC";
    $rs_again = mysql_query($query_again);   //run the query.
    $row = mysql_fetch_array($rs_again);
	
	$query_u = "SELECT * FROM user_detail WHERE user_no = '$row2[user_create]'";
	$result_u = mysql_query($query_u);   //run the query.
	$data_u = mysql_fetch_array($result_u);   //how many records are there?    

 	
  
 
		  ?>        
          <table width="100%" border="0" cellpadding="0" cellspacing="0"  style="border:solid 1px #d5d5d5;">
  <tr>
    <td width="143">&nbsp;<?php echo $row2["temp_mrin"]; ?></td>
    <td width="50"><?php echo $row2["factory"]; ?></td>
     <td width="50" height="28"><?php echo $row2["id_work"]; ?></td>
              <td width="120"><div align="center"><?php echo $row2["R"]; ?></div></td>
              <td><div align="center"><?php echo $row2["time_require"]; ?></div></td>
              <td width="120"><?php echo $data_u["user_fullname"]; ?></td>
              <td width="50"><a value="Details" href="close_MRIN_consumable_manual.php?mrin_no=<?php echo $row2["temp_mrin"]; ?>&&user_no=<?php echo $data_u["user_no"]; ?>&amp;&amp;TB_iframe=true&amp;height=400&amp;width=1000" class="thickbox" target="_self"><img src="../images/exclamation.png" width="16" height="16" alt="View" />Close</a></td>
         
            <td width="50"><a value="Details" href="detail_consumable_request.php?mrin_no=<?php echo $row2["temp_mrin"]; ?>&&TB_iframe=true&height=400&width=1000" class="thickbox" target="_self"><img src="../images/icon_view.jpg" width="16" height="16" alt="View">View</a></td>
                <td width="50"><a href="detail_consumable_request_printing.php?mrin_no=<?php echo $row2["temp_mrin"]; ?>&&TB_iframe=true&height=400&width=1000" class="thickbox" target="_self"><img src="../images/print.jpg" width="16" height="16" alt="Print">Print</a> </td>
  </tr>
</table>    
  <?php 
		 
		 $no ++;
		  
		  $counter++; // menambah counter 
		   
		   //}// end if
		
		    
		  } ?>
<p>&nbsp;</p>
  </center>
 
  <?php
  mysql_free_result($rs); 
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
  <?php
	}   // free up the resources 
else
{
?><center>
<table width="800" cellspacing="0" class="textboxred">
  <tr> 
    <td><div align="center"><font color="#FF0000"><strong>There are currently no material request.</strong></font></div></td>
  </tr>
</table></center>
        <?php
		   } 
mysql_close()

?>

 

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>Legend :</p>
<table width="70%" border="1">
  <tr>
    <td width="10%"><div align="center"><img src="../images/red_icon2.jpg" alt="" width="20" height="20" /></div></td>
    <td width="90%">Request has not been attended for more than 30 minutes.</td>
  </tr>
  <tr>
    <td><div align="center"><img src="../images/yellow_icon2.jpg" alt="" width="20" height="20" /></div></td>
    <td>Request has not been attended for more than 20 minutes.</td>
  </tr>
  <tr>
    <td><div align="center"><img src="../images/green_icon2.jpg" alt="" width="20" height="20" /></div></td>
    <td>Request has been posted not more than 19 minutes.</td>
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
<!-- End Container -->

</body>
</html>
