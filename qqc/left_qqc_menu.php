<?php
/**
 * qqc/left_qqc_menu.php
 * Part of: QQC module (Quality)
 * Filename suggests: left qqc menu
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
<div id="sidebar"><a href="index_qqc.php" class="visible-phone"><i class="icon icon-home"></i> Home</a>
  <ul>
    <?php if ($url == "index_qqc.php"){ ?>
    <li class="active"><?php }else { echo "<li>"; } ?><a href="index_qqc.php"><i class="icon icon-home"></i> <span>Home</span></a> </li>
    
    <?php if ($url == "dash.php"){ ?>
    <li class="active"><?php }else { echo "<li>"; } ?><a href="dash.php"><i class="icon icon-dashboard"></i> <span>Dashboard</span></a> </li>
    
     <?php if (($url == "production_output_list_tran.php") ||($url == "rework_output_list_tran.php") ||($url == "rework_history_output_list_tran.php") ||($url == "reject_qc_tran_NG.php") || ($url == "wastage_qc_tran_NG.php") ||($url == "disposal_qc_tran_NG.php") || ($url == "cancellation_QC_output_list_tran.php") || ($url == "report_all_planning_module.php") || ($url == "report_rework_reject.php") || ($url == "report_wastage_reject.php")) { ?>
    <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?><a href="production_output_list_tran.php"><i class="icon icon-th-list"></i> <span>QA/QC</span></a>
      <ul>
         <?php if ($url == "production_output_list_tran.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="production_output_list_tran.php">Production Output List</a></li>
        <?php if ($url == "rework_output_list_tran.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="rework_output_list_tran.php">Rework List</a></li>
          <?php if ($url == "rework_history_output_list_tran.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="rework_history_output_list_tran.php">Document List</a></li>
         <?php if ($url == "reject_qc_tran_NG.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="reject_qc_tran_NG.php">Rework Reject</a></li>
        <?php if ($url == "wastage_qc_tran_NG.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="wastage_qc_tran_NG.php">Wastage &amp; Reject</a></li>
        <?php if ($url == "disposal_qc_tran_NG.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="disposal_qc_tran_NG.php">Disposal</a></li>
          <?php if ($url == "cancellation_QC_output_list_tran.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="cancellation_QC_output_list_tran.php">Cancellation Rework/Prod. Output</a></li>
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
    
    
     
     <!--
     
        <?php if (($url == "wip_request_list.php") || ($url == "wip_request_urgent.php") || ($url == "posting_request_scan_+_urgent_wip.php") || ($url == "display_request_wip_cancel.php") || ($url == "wip_request_analysis.php")) { ?> 
   <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?> <a href="wip_request_list.php"><i class="icon icon-barcode"></i> <span>Closed Planned Order</span> <span class="label label-important">5</span></a>
      <ul>
         <?php if ($url == "wip_request_list.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="wip_request_list.php">WIP Request</a></li>
           <?php if ($url == "wip_request_urgent.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="wip_request_urgent.php">WIP Request Urgent</a></li>
          <?php if ($url == "posting_request_scan_+_urgent_wip.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_scan_+_urgent_wip.php">Display WIP Request</a></li>
         <?php if ($url == "display_request_wip_cancel.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="display_request_wip_cancel.php">Cancel WIP Request</a></li>
        <?php if ($url == "wip_request_analysis.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="wip_request_analysis.php">WIP Request Report</a></li>
      </ul>
    </li>
     -->
  <li><a href="../logout.php"><i class="icon icon-signout"></i> <span>Logout</span></a></li>
  
  </ul>
</div>
<!--sidebar-menu-->
