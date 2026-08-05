<!--sidebar-menu-->
<div id="sidebar"><a href="index_planning_super.php" class="visible-phone"><i class="icon icon-home"></i> Home</a>
  <ul>
    <?php if ($url == "index_planning_super.php"){ ?>
    <li class="active"><?php }else { echo "<li>"; } ?><a href="index_planning_super.php"><i class="icon icon-home"></i> <span>Home</span></a> </li>
    
    <?php if ($url == "dash.php"){ ?>
    <li class="active"><?php }else { echo "<li>"; } ?><a href="dash.php"><i class="icon icon-dashboard"></i> <span>Dashboard</span></a> </li>
    
     <?php if (($url == "posting_request_all_screen_LCD.php") ||($url == "posting_request_all_screen_LCD_consumable.php") ||($url == "posting_request_all_screen_LCD_WIP.php")) { ?>
    <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?><a href="posting_request_all_screen_LCD.php"><i class="icon icon-th-list"></i> <span>Upload PPS</span> <span class="label label-important">3</span></a>
      <ul>
         <?php if ($url == "posting_request_all_screen_LCD.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_all_screen_LCD.php">Upload Production Planning Sheet (PPS)</a></li>
        <?php if ($url == "posting_request_all_screen_LCD_consumable.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_all_screen_LCD_consumable.php">Print PPS</a></li>
        <?php if ($url == "posting_request_all_screen_LCD_WIP.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_all_screen_LCD_WIP.php">Searching Record</a></li>
		</ul>
    </li>
     <?php if (($url == "material_request_list.php") || ($url == "material_request_urgent.php") || ($url == "report_prod.php") || ($url == "posting_request_scan_+_urgent.php") || ($url == "posting_request_history.php") || ($url == "material_request_analysis.php")) { ?> 
   <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?> <a href="material_request_list.php"><i class="icon icon-barcode"></i> <span>Released Planned Order</span> <span class="label label-important">6</span></a>
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
     <?php if (($url == "display_consumable_request.php") || ($url == "report_prod_consumable.php") || ($url == "posting_request_consumable.php") || ($url == "history_consumable_request.php") || ($url == "consumable_request_analysis.php")) { ?> 
   <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?>
   <a href="display_consumable_request.php"><i class="icon icon-barcode"></i> <span>InProgress Planned Order</span></a> 
      <ul>
         <?php if ($url == "display_consumable_request.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="display_consumable_request.php">Consumable Request <font color="#FF9900"> [] </font></a></li>
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
    
  <li><a href="../logout.php"><i class="icon icon-signout"></i> <span>Logout</span></a></li>
  
  </ul>
</div>
<!--sidebar-menu-->
