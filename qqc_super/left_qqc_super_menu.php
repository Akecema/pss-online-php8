<?php
/**
 * qqc_super/left_qqc_super_menu.php
 * Part of: QQC module (supervisor/admin tier)
 * Filename suggests: left qqc super menu
 *
 * Behavior: no form submission, file upload, or export detected (likely a display/listing page, utility, or bootstrap/include file).
 *
 * NOTE: this summary was generated automatically by static analysis during
 * the PHP8 migration (looking at queries/includes/superglobals actually used
 * in this file). It describes *what the code touches*, not necessarily *why* -
 * treat it as a starting point and refine as you work in this file.
 */
?>
<!--sidebar-menu-->
<div id="sidebar"><a href="index_qqc_super.php" class="visible-phone"><i class="icon icon-home"></i> Home</a>
  <ul>
    <?php if ($url == "index_qqc_super.php"){ ?>
    <li class="active"><?php }else { echo "<li>"; } ?><a href="index_qqc_super.php"><i class="icon icon-home"></i> <span>Home</span></a> </li>
    
     <?php if ($url == "dash.php"){ ?>
    <li class="active"><?php }else { echo "<li>"; } ?><a href="dash.php"><i class="icon icon-dashboard"></i> <span>Dashboard</span></a> </li>
    
     <?php if (($url == "disposal_qc_tran_NG.php") ||($url == "document_list_qc_tran.php") ||($url == "disposal_approve_list_qc_tran_NG.php") ||($url == "report_all_planning_module.php") || ($url == "report_rework_reject.php") || ($url == "report_wastage_reject.php")) { ?>
    <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?><a href="disposal_qc_tran_NG.php"><i class="icon icon-th-list"></i> <span>QA/QC</span></a>
      <ul>
         <?php if ($url == "disposal_qc_tran_NG.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="disposal_qc_tran_NG.php">QC Disposal</a></li>
          <?php if ($url == "disposal_production_tran_NG.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="disposal_production_tran_NG.php">Production Disposal</a></li>
         <?php if ($url == "disposal_approve_list_qc_tran_NG.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="disposal_approve_list_qc_tran_NG.php">Approved List</a></li>
        <?php if ($url == "document_list_qc_tran.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="document_list_qc_tran.php">Document List</a></li>
        <?php if ($url == "report_all_planning_module.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="report_all_planning_module.php">Report Planned Order</a></li>
        <?php if ($url == "report_rework_reject.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="report_rework_reject.php">Report Rework Reject</a></li>
        <?php if ($url == "report_wastage_reject.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="report_wastage_reject.php">Report Wastage Reject</a></li>
		</ul>
      </li>
      
        <?php if (($url == "list_ftp_pending2_NG_sap.php") || ($url == "FTP_bflush_download.php") || ($url =="FTP_gdtranfer_download.php")) { ?>
    <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?><a href="list_ftp_pending2_NG_sap.php"><i class="icon icon-list"></i> <span>QC FTP Monitor</span> </a>
      <ul>
         <?php if ($url == "list_ftp_pending2_NG_sap.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="list_ftp_pending2_NG_sap.php">QC FTP Monitor</a></li>
        <?php if ($url == "FTP_bflush_download.php"){ ?>
    		<li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="FTP_bflush_download.php">FTP Backflush </a></li>
       <?php if ($url =="FTP_gdtranfer_download.php"){ ?>
    		<li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="FTP_gdtranfer_download.php">FTP Good Transfer </a></li>
		</ul>
    </li>
   
  <li><a href="../logout.php"><i class="icon icon-signout"></i> <span>Logout</span></a></li>
  
  </ul>
</div>
<!--sidebar-menu-->