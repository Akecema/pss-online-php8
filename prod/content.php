<?php

//include 'include/config.php';

$Cdate = date ("l, j F Y ");
$nextpage = 1;

$sql = "SELECT * FROM material_request AS MR WHERE MR.status_request = 'Y' AND (MR.status != 'Close' AND MR.status != 'Cancel') AND (MR.date_mrin >= '$start_date_check' AND MR.date_mrin <= '$end_date_check') GROUP BY MR.temp_mrin";
$result = mysqli_query($dbc, $sql);
$r = mysqli_num_rows($result);
$numrows = $r;


$rowsperpage = 15;

// $totalpages = ceil($numrows / $rowsperpage);
$totalpages = intval($numrows / $rowsperpage);
$lastpage =  fmod($numrows , $rowsperpage);

 if ($lastpage > 0)
 {
	$totalpages = ($totalpages + 1);
	}

	
if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {

$currentpage = (int) $_GET['currentpage'];
} else {

$currentpage = 1;
}


if ($currentpage > $totalpages) {

$currentpage = $totalpages;
} 
if ($currentpage < 1) {

$currentpage = 1;
} 


$offset = ($currentpage - 1) * $rowsperpage;


$range = 10;


if ($currentpage > 1) {

//echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=1'><<</a> ";

$prevpage = $currentpage - 1;

//echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage'><</a> ";



}  // end if $currentpage

for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {

if (($x > 0) && ($x <= $totalpages)) {

if ($x == $currentpage) {

 //echo " [<b>$x</b>] ";

} else {

 //echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$x'>$x</a> ";
} 


} // end if
} // end for


if ($currentpage != $totalpages) {

$nextpage = $currentpage + 1;

//echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage'>Next></a> ";

//echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages'>Last>></a> ";

}  // end if


header('Refresh: 15; URL='.$_SERVER['PHP_SELF'].'?currentpage='.$nextpage);
?>