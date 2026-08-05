<!--sidebar-menu-->
<div id="sidebar"><a href="index_production.php" class="visible-phone"><i class="icon icon-home"></i> Home</a>
  <ul>
    <?php if ($url == "index_production.php"){ ?>
    <li class="active"><?php }else { echo "<li>"; } ?><a href="index_production.php"><i class="icon icon-home"></i> <span>Home</span></a> </li>
    
    <?php if ($url == "dash.php"){ ?>
    <li class="active"><?php }else { echo "<li>"; } ?><a href="dash.php"><i class="icon icon-dashboard"></i> <span>Dashboard</span></a> </li>
    
     <?php if (($url == "confirm_backflush_tran.php") || ($url == "reject_backflush_tran_NG.php") || ($url == "wastage_backflush_tran_NG.php") || ($url == "disposal_backflush_tran_NG.php") || ($url == "document_list_backflush_tran.php") ||($url == "cancellation_list_backflush_tran.php") || ($url == "print_tag_backflush_tran.php") || ($url == "technical_complete_tran.php") || ($url == "close_technical_complete_tran.php") || ($url == "report_all_planning_module.php") || ($url == "report_production_reject.php") || ($url == "report_wastage_reject.php")) { ?>
    <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?><a href="confirm_backflush_tran.php"><i class="icon icon-th-list"></i> <span>Production</span> </a>
      <ul>
         <?php if ($url == "confirm_backflush_tran.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="confirm_backflush_tran.php">Confirmation Backflush</a></li>
         <?php if ($url == "reject_backflush_tran_NG.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="reject_backflush_tran_NG.php">Backflush NG Reject</a></li>
        <?php if ($url == "wastage_backflush_tran_NG.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="wastage_backflush_tran_NG.php">Wastage</a></li>
         <?php if ($url == "disposal_backflush_tran_NG.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="disposal_backflush_tran_NG.php">Disposal</a></li>
        <?php if ($url == "document_list_backflush_tran.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="document_list_backflush_tran.php">Document List</a></li>
        <?php if ($url == "cancellation_list_backflush_tran.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="cancellation_list_backflush_tran.php">Cancellation</a></li>
         <?php if ($url == "print_tag_backflush_tran.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="print_tag_backflush_tran.php">Print Tag</a></li>
        <?php if ($url == "technical_complete_tran.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="technical_complete_tran.php">Technical Complete</a></li>
         <?php if ($url == "close_technical_complete_tran.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="close_technical_complete_tran.php">PPS Close</a></li>
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
    
     <?php if (($url == "posting_request_all_screen_LCD.php") ||($url == "posting_request_all_screen_LCD_consumable.php") ||($url == "posting_request_all_screen_LCD_WIP.php")) { ?>
    <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?><a href="posting_request_all_screen_LCD.php"><i class="icon icon-th-list"></i> <span>Board</span></a>
      <ul>
         <?php if ($url == "posting_request_all_screen_LCD.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_all_screen_LCD.php">Material Request Board</a></li>
        <?php if ($url == "posting_request_all_screen_LCD_consumable.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_all_screen_LCD_consumable.php">Consumable Request Board</a></li>
        <?php if ($url == "posting_request_all_screen_LCD_WIP.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_all_screen_LCD_WIP.php">WIP Request Board</a></li>
		</ul>
    </li>
     <?php if (($url == "material_request_list.php") || ($url == "material_request_urgent.php") || ($url == "report_prod.php") || ($url == "posting_request_scan_+_urgent.php") || ($url == "posting_request_history.php") || ($url == "material_request_analysis.php")) { ?> 
   <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?> <a href="material_request_list.php"><i class="icon icon-barcode"></i> <span>Material Request</span></a>
      <ul>
         <?php if ($url == "material_request_list.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="material_request_list.php">Material Request</a></li>
           <?php if ($url == "material_request_urgent.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="material_request_urgent.php">Material Request Urgent</a></li>
         <?php if ($url == "report_prod.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="report_prod.php">Material Request Report</a></li>
         <?php if ($url == "posting_request_scan_+_urgent.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_scan_+_urgent.php">Display Material Request</a></li>
         <?php if ($url == "posting_request_history.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_history.php">Material Request History</a></li>
        <?php if ($url == "material_request_analysis.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="material_request_analysis.php">Material Request Analysis</a></li>
      </ul>
    </li>
     <?php if (($url == "material_consumable_request.php") || ($url == "report_prod_consumable.php") || ($url == "posting_request_consumable.php") || ($url == "history_consumable_request.php") || ($url == "consumable_request_analysis.php")) { ?> 
   <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?>
   <a href="display_consumable_request.php"><i class="icon icon-barcode"></i> <span>Consumable Request
   <?php
	  
	  $query = "SELECT *, DATE_FORMAT(MR.date_require,'%d-%m-%Y') AS R FROM consumable_request AS MR WHERE MR.status_request = 'Y' AND MR.status_view = 'N' AND (MR.status != 'Close' AND MR.status  != 'Cancel') GROUP BY MR.temp_mrin";
$rs = mysql_query($query);   //run the query.
	    $num_rows = mysql_num_rows($rs); 

	  ?></span> </a> 
      <ul>
         <?php if ($url == "material_consumable_request.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="material_consumable_request.php">Consumable Request <font color="#FF9900"> [<?php  echo $num_rows; ?>] </font></a></li>
         <?php if ($url == "report_prod_consumable.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="report_prod_consumable.php">Consumable Request Report</a></li>
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
    
    
        <?php 
		 /* 
		if (($url == "wip_request_list.php") || ($url == "wip_request_urgent.php") || ($url == "posting_request_scan_+_urgent_wip.php") || ($url == "display_request_wip_cancel.php") || ($url == "wip_request_analysis.php")) { ?> 
   <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?> <a href="wip_request_list.php"><i class="icon icon-barcode"></i> <span>WIP Request</span> <span class="label label-important">5</span></a>
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
    */
    ?>
  <li><a href="../logout.php"><i class="icon icon-signout"></i> <span>Logout</span></a></li>
  
  </ul>
</div>
<!--sidebar-menu-->
