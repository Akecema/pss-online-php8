<!--sidebar-menu-->
<div id="sidebar"><a href="index_supply_super.php" class="visible-phone"><i class="icon icon-home"></i> Home</a>
  <ul>
    <?php if ($url == "index_supply_super.php"){ ?>
    <li class="active"><?php }else { echo "<li>"; } ?><a href="index_supply_super.php"><i class="icon icon-home"></i> <span>Home</span></a> </li>  
    <?php if ($url == "posting_request_all_screen_LCD_WIP.php"){ ?>
        <li class="active"><?php }else { echo "<li>"; } ?><a href="posting_request_all_screen_LCD_WIP.php"><i class="icon icon-edit"></i><span>Display Board</span></a></li>
        
        <?php if (($url == "posting_request_scan_+_urgent_wip.php") || ($url == "wip_request_analysis.php")) { ?> 
   <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?> <a href="wip_request_list.php"><i class="icon icon-barcode"></i> <span>WIP Request</span> <span class="label label-important">2</span></a>
      <ul>
        <?php if ($url == "posting_request_scan_+_urgent_wip.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_scan_+_urgent_wip.php">Display WIP Request</a></li>
        <?php if ($url == "wip_request_analysis.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="wip_request_analysis.php">WIP Request Report</a></li>
      </ul>
    </li> 
     <?php if ($url == "display_request_wip_close.php"){ ?>
        <li class="active"><?php }else { echo "<li>"; } ?><a href="display_request_wip_close.php"><i class="icon icon-book"></i><span>WIP Request Maintenance</span></a></li>  
         <?php if ($url == "TP_manual_upload.php"){ ?>
        <li class="active"><?php }else { echo "<li>"; } ?><a href="TP_manual_upload.php"><i class="icon icon-upload"></i><span>Uploaded MIGO TR</span></a></li>
          <?php if ($url == "manual_upload_file.php"){ ?>
        <li class="active"><?php }else { echo "<li>"; } ?><a href="manual_upload_file.php" ><i class="icon icon-upload"></i><span>Manually Upload File Transfer (TP)</span></a></li>
   
  <li><a href="../logout.php"><i class="icon icon-signout"></i> <span>Logout</span></a></li>
       
  </ul>
</div>
<!--sidebar-menu-->
