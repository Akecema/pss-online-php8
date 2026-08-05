<!--sidebar-menu-->
<div id="sidebar"><a href="index_ppc.php" class="visible-phone"><i class="icon icon-home"></i> Home</a>
  <ul>
    <?php if ($url == "index_ppc.php"){ ?>
    <li class="active"><?php }else { echo "<li>"; } ?><a href="index_ppc.php"><i class="icon icon-home"></i> <span>Home</span></a> </li>
     <?php if (($url == "posting_request_all_screen_LCD.php") ||($url == "posting_request_all_screen_LCD_consumable.php")) { ?>
    <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?><a href="posting_request_all_screen_LCD.php"><i class="icon icon-th-list"></i> <span>Board</span> <span class="label label-important">2</span></a>
      <ul>
         <?php if ($url == "posting_request_all_screen_LCD.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_all_screen_LCD.php">Material Request Board</a></li>
        <?php if ($url == "posting_request_all_screen_LCD_consumable.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_all_screen_LCD_consumable.php">Consumable Request Board</a></li>
		</ul>
    </li>
     <?php if (($url == "posting_request_all.php") || ($url == "report_PPC.php") || ($url == "posting_request_scan_+_urgent.php") || ($url == "posting_request_history.php") || ($url == "material_request_analysis.php")) { ?> 
   <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?> <a href="material_request_list.php"><i class="icon icon-barcode"></i> <span>Material Request</span> <span class="label label-important">6</span></a>
      <ul>
         <?php if ($url == "posting_request_all.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_all.php">Material Request</a></li>
          <?php if ($url == "report_PPC.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="report_PPC.php">Material Request Report</a></li>
         <?php if ($url == "posting_request_scan_+_urgent.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_scan_+_urgent.php">Display Material Request</a></li>
         <?php if ($url == "posting_request_history.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_history.php">Material Request History</a></li>
        <?php if ($url == "material_request_analysis.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="material_request_analysis.php">Material Request Analysis</a></li>
      </ul>
    </li>
     <?php if (($url == "display_consumable_request.php") || ($url == "report_PPC_consumable.php") || ($url == "posting_request_consumable.php") || ($url == "history_consumable_request.php") || ($url == "consumable_request_analysis.php")) { ?> 
   <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?>
   <a href="display_consumable_request.php"><i class="icon icon-barcode"></i> <span>Consumable Request
   <?php
	  
	  $query = "SELECT *, DATE_FORMAT(MR.date_require,'%d-%m-%Y') AS R FROM consumable_request AS MR WHERE MR.status_request = 'Y' AND MR.status_view = 'N' AND (MR.status != 'Close' AND MR.status  != 'Cancel') GROUP BY MR.temp_mrin";
$rs = mysqli_query($dbc, $query);   //run the query.
	    $num_rows = mysqli_num_rows($rs); 

	  ?></span><span class="label label-important">5</span></a> 
      <ul>
         <?php if ($url == "display_consumable_request.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="display_consumable_request.php">Consumable Request <font color="#FF9900"> [<?php  echo $num_rows; ?>] </font></a></li>
         <?php if ($url == "report_PPC_consumable.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="report_PPC_consumable.php">Consumable Request Report</a></li>
         <?php if ($url == "posting_request_consumable.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="posting_request_consumable.php">Display Consumable Request</a></li>
         <?php if ($url == "history_consumable_request.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="history_consumable_request.php">Consumable Request History</a></li>
        <?php if ($url == "consumable_request_analysis.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="consumable_request_analysis.php">Consumable Request Analysis</a></li>
      </ul>
    </li>
   <li><a href="TP_manual_upload.php"><i class="icon icon-upload"></i><span>Uploaded MIGO TR</span></a></li>
   <li><a href="manual_upload_file.php" ><i class="icon icon-upload"></i>Manually Upload File Transfer (TP)</a></li>
   
  <li><a href="../logout.php"><i class="icon icon-signout"></i> <span>Logout</span></a></li>
  
  </ul>
</div>
<!--sidebar-menu-->
