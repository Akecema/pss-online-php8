<?php
/**
 * prod_super/left_prod_super_menu.php
 * Part of: Production module (supervisor/admin tier)
 * Filename suggests: left prod super menu
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
<div id="sidebar"><a href="index_production_super.php" class="visible-phone"><i class="icon icon-home"></i> Home</a>
  <ul>
    <?php if ($url == "index_production_super.php"){ ?>
    <li class="active"><?php }else { echo "<li>"; } ?><a href="index_production_super.php"><i class="icon icon-home"></i> <span>Home</span></a> </li>
    
    <?php if ($url == "dash.php"){ ?>
    <li class="active"><?php }else { echo "<li>"; } ?><a href="dash.php"><i class="icon icon-dashboard"></i> <span>Dashboard</span></a> </li>
    
     <?php if (($url == "disposal_backflush_tran_NG.php") ||($url == "document_list_backflush_tran.php") ||($url == "report_all_planning_module.php") || ($url == "report_production_reject.php") || ($url == "report_wastage_reject.php")) { ?>
    <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?><a href="disposal_backflush_tran_NG.php"><i class="icon icon-th-list"></i> <span>Production</span> </a>
      <ul>
         <?php if ($url == "disposal_backflush_tran_NG.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="disposal_backflush_tran_NG.php">Approval Disposal</a></li>
        <?php if ($url == "document_list_backflush_tran.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="document_list_backflush_tran.php">Document List</a></li>
        <?php if ($url == "report_all_planning_module.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="report_all_planning_module.php">Report Planned Order</a></li>
         <?php if ($url == "report_production_reject.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="report_production_reject.php">Report Production Reject</a></li>
        <?php if ($url == "report_wastage_reject.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="report_wastage_reject.php">Report Wastage Reject</a></li>
		</ul>
    </li>
    
     <?php if (($url == "list_ftp_pending_sap.php") || ($url == "FTP_bflush_download.php") || ($url =="FTP_gdtranfer_download.php")) { ?>
    <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?><a href="list_ftp_pending_sap.php"><i class="icon icon-list"></i> <span>FTP Monitoring</span> </a>
      <ul>
         <?php if ($url == "list_ftp_pending_sap.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="list_ftp_pending_sap.php">FTP Monitoring</a></li>
        <?php if ($url == "FTP_bflush_download.php"){ ?>
    		<li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="FTP_bflush_download.php">FTP Backflush </a></li>
       <?php if ($url =="FTP_gdtranfer_download.php"){ ?>
    		<li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="FTP_gdtranfer_download.php">FTP Good Transfer </a></li>
		</ul>
    </li>
    
     <?php if (($url == "posting_request_scan_+_urgent.php") || ($url == "posting_request_history.php") || ($url == "material_request_analysis.php")) { ?> 
   <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?> <a href="posting_request_scan_+_urgent.php"><i class="icon icon-barcode"></i> <span>Material Request</span></a>
      <ul>
         <?php if ($url == "posting_request_scan_+_urgent.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_scan_+_urgent.php">Display Material Request</a></li>
         <?php if ($url == "posting_request_history.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_history.php">Material Request History</a></li>
        <?php if ($url == "material_request_analysis.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="material_request_analysis.php">Material Request Analysis</a></li>
      </ul>
    </li>
     <?php if (($url == "posting_request_consumable.php") || ($url == "history_consumable_request.php") || ($url == "consumable_request_analysis.php")) { ?> 
   <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?>
   <a href="posting_request_consumable.php"><i class="icon icon-barcode"></i> <span>Consumable Request</span> </a> 
      <ul>
         <?php if ($url == "posting_request_consumable.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_consumable.php">Display Consumable Request</a></li>
         <?php if ($url == "history_consumable_request.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="history_consumable_request.php">Consumable Request History</a></li>
        <?php if ($url == "consumable_request_analysis.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="consumable_request_analysis.php">Consumable Request Analysis</a></li>
      </ul>
    </li>
      <?php if ($url == "material_master_list.php"){ ?>
    <li class="active"><?php }else { echo "<li>"; } ?><a href="material_master_list.php"><i class="icon icon-list"></i> <span>Material List</span></a> </li>
        <?php /*
		if (($url == "posting_request_scan_+_urgent_wip.php") || ($url == "display_request_wip_cancel.php") || ($url == "wip_request_analysis.php")) { ?> 
   <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?> <a href="posting_request_scan_+_urgent_wip.php"><i class="icon icon-barcode"></i> <span>WIP Request</span> <span class="label label-important">2</span></a>
      <ul>
        <?php if ($url == "posting_request_scan_+_urgent_wip.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_scan_+_urgent_wip.php">Display WIP Request</a></li>
         <?php if ($url == "wip_request_analysis.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="wip_request_analysis.php">WIP Request Report</a></li>
      </ul>
    </li> */
      ?>
  <li><a href="../logout.php"><i class="icon icon-signout"></i> <span>Logout</span></a></li>
  
  </ul>
</div>
<!--sidebar-menu-->