<!--sidebar-menu-->
<div id="sidebar"><a href="index_admin.php" class="visible-phone"><i class="icon icon-home"></i> Home</a>
  <ul>
    <?php if ($url == "index_admin.php"){ ?>
    <li class="active"><?php }else { echo "<li>"; } ?><a href="index_admin.php"><i class="icon icon-home"></i> <span>Home</span></a> </li>
    
     <?php if ($url == "setup_maintain_add.php"){ ?>
    <li class="active"><?php }else { echo "<li>"; } ?><a href="setup_maintain_add.php"><i class="icon icon-cog"></i> <span>Setup System</span></a> </li>
    
    <?php if ($url == "add_user.php"){ ?>
		
    <li class="active"> <?php }else { echo "<li>"; } ?><a href="add_user.php"><i class="icon icon-user-md"></i> <span>User Maintenance</span></a> </li>
      <?php if (($url == "work_center_table.php") ||($url == "material_master_list.php") || ($url == "con_detail_table.php") || ($url == "bom_header_upload.php") || ($url == "reason_reject_table.php") || ($url == "type_reject_table.php") || ($url == "reason_wastage_table.php") || ($url == "type_wastage_table.php") || ($url == "add_vendor_account.php") || ($url == "display_model_table.php")) { ?>
    <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?><a href="work_center_table.php"><i class="icon icon-th-list"></i> <span>System Data Maintenance</span> </a>
      <ul>
         <?php if ($url == "work_center_table.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="work_center_table.php">Work Center</a></li>
        <?php if ($url == "material_master_list.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="material_master_list.php">Material Master</a></li>
		<?php if ($url == "con_detail_table.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="con_detail_table.php">Consumable Details</a></li>
       <!-- <?php //if ($url == "bom_header_upload.php"){ ?>
        <li class="submenu active active"><?php //}else { echo "<li>"; } ?><a href="bom_header_upload.php">Material Master Data Upload</a></li>-->
         <?php if ($url == "reason_reject_table.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="reason_reject_table.php">Reason Reject</a></li>
        <?php if ($url == "type_reject_table.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="type_reject_table.php">Type of Reject</a></li>
        <?php if ($url == "reason_wastage_table.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="reason_wastage_table.php">Reason Wastage</a></li>
        <?php if ($url == "type_wastage_table.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="type_wastage_table.php">Type of Wastage</a></li>
        <?php if ($url == "add_tbl_storage_PD.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="add_tbl_storage_PD.php">Production Storage Location</a></li>
        <?php if ($url == "add_tbl_storage_QC.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="add_tbl_storage_QC.php">QC Storage Location</a></li>
        <?php if ($url == "add_vendor_account.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="add_vendor_account.php">Vendor Account</a></li>
         <?php if ($url == "display_model_table.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="display_model_table.php">Material Model</a></li>
      </ul>
    </li>
     <?php if (($url == "posting_request_all_screen_LCD.php") || ($url == "posting_request_all_screen_LCD_consumable.php") || ($url == "posting_request_all_screen_LCD_WIP.php")) { ?> 
   <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?> <a href="posting_request_all_screen_LCD.php"><i class="icon icon-file"></i> <span>Board</span></a>
      <ul>
         <?php if ($url == "posting_request_all_screen_LCD.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_all_screen_LCD.php">Material Request Board</a></li>
           <?php if ($url == "posting_request_all_screen_LCD_consumable.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_all_screen_LCD_consumable.php">Consumable Request Board</a></li>
         <?php if ($url == "posting_request_all_screen_LCD_WIP.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_all_screen_LCD_WIP.php">WIP Request Board</a></li>
      </ul>
    </li>
     <?php if (($url == "report_PPC.php") || ($url == "material_request_analysis.php")) { ?> 
   <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?>
   <a href="report_PPC.php"><i class="icon icon-barcode"></i> <span>Material Request</span></a>
      <ul>
         <?php if ($url == "report_PPC.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="report_PPC.php">Material Request Report</a></li>
         <?php if ($url == "material_request_analysis.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="material_request_analysis.php">Material Request Analysis</a></li>
      </ul>
    </li>
       <?php if (($url == "report_PPC_consumable.php") || ($url == "consumable_request_analysis.php")) { ?> 
   <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?><a href="report_PPC_consumable.php"><i class="icon icon-barcode"></i> <span>Consumable Request</span></a>
      <ul>
        <?php if ($url == "report_PPC_consumable.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="report_PPC_consumable.php">Consumable Request Report</a></li>
         <?php if ($url == "consumable_request_analysis.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="consumable_request_analysis.php">Consumable Request Analysis</a></li>
      </ul>
    </li>
      <?php if ($url == "wip_request_analysis.php") { ?> 
   <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?> <a href="wip_request_analysis.php"><i class="icon icon-barcode"></i> <span>WIP Request</span> </a>
      <ul>
        <?php if ($url == "wip_request_analysis.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="wip_request_analysis.php">WIP Request Report</a></li>
      </ul>
    </li>
    
    <?php if (($url == "list_ftp_pending_sap.php") ||($url == "FTP_bflush_download.php") || ($url =="FTP_gdtranfer_download.php")) { ?> 
    <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?> <a href="FTP_bflush_download.php"><i class="icon icon-barcode"></i> <span>FTP Monitoring</span></a>
    	<ul>
        <?php if ($url == "list_ftp_pending_sap.php"){ ?>
    		<li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="list_ftp_pending_sap.php">Backflush FTP Monitor</a></li>
    		<?php if ($url == "FTP_bflush_download.php"){ ?>
    		<li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="FTP_bflush_download.php">FTP Backflush </a></li>
            <?php if ($url =="FTP_gdtranfer_download.php"){ ?>
    		<li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="FTP_gdtranfer_download.php">FTP Good Tranfer </a></li>
    	</ul>
    </li>

    
    <?php //if ($url == "TP_manual_upload.php"){ ?>
     <!--   <li class="submenu active active"><?php //}else { echo "<li>"; } ?><a href="TP_manual_upload.php"><i class="icon icon-upload-alt"></i> <span>Uploaded MIGO TR</span></a></li>
    <?php // if ($url == "manual_upload_file.php"){ ?>
        <li class="submenu active active"><?php //}else { echo "<li>"; } ?><a href="manual_upload_file.php"><i class="icon icon-upload-alt"></i> <span>Manually Upload File Transfer (TP)</span></a></li>-->
  <li><a href="../logout.php"><i class="icon icon-signout"></i> <span>Logout</span></a></li>
  
  </ul>
</div>
<!--sidebar-menu-->
