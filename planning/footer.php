<?php
/**
 * planning/footer.php
 * Part of: Planning module
 * Filename suggests: footer
 *
 * Behavior: no form submission, file upload, or export detected (likely a display/listing page, utility, or bootstrap/include file).
 * Database tables referenced: sys_setup_maintain.
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
?>
<style type="text/css" media="print"> 

 @media print{
  body{  margin-top: -1.5cm;  font-family: Arial, Helvetica, sans-serif; font-size:12px; }
  #ad{ display:none;}
  #leftbar{ display:none;}
  #contentarea{ width:100%;}
  .header, .hide { visibility: hidden }
  .tfoot { display: table-footer-group; }
   a[href]:after {
    content: none !important;
  }
} 

</style> 
<?php

//--------setup website page --------------------------
// The including page has usually closed $dbc already (mysqli_close) and loaded $data_setup;
// querying again on a closed mysqli object is a fatal error on PHP 8.
if (!isset($data_setup['title_desc'])) {
	$query_setup = "SELECT * FROM sys_setup_maintain WHERE status_system = 'AC'";
	$rs_setup = mysqli_query($dbc, $query_setup);
	$data_setup = mysqli_fetch_array($rs_setup);
}
//----------------------------------------------------
?>
<!--Footer-part-->
<div class="row-fluid">
  <div id="footer" class="span12"> 2017 &copy; <?php echo h($data_setup["title_desc"]); ?>. Brought to you by <a href="http://themedesigner.in">Themedesigner.in</a> </div>
</div>
<!--end-Footer-part--> 