<!--sidebar-menu-->
<div id="sidebar"><a href="index_planning.php" class="visible-phone"><i class="icon icon-home"></i> Home</a>
  <ul>
    <?php if ($url == "index_planning.php"){ ?>
    <li class="active"><?php }else { echo "<li>"; } ?><a href="index_planning.php"><i class="icon icon-home"></i> <span>Home</span></a> </li>
      
    <?php if ($url == "dash.php"){ ?>
    <li class="active"><?php }else { echo "<li>"; } ?><a href="dash.php"><i class="icon icon-dashboard"></i> <span>Dashboard</span></a> </li>
    
     <?php if (($url == "upload_pps_month.php") ||($url == "display_pps_month_reprint.php")) { ?>
	<li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?><a href="upload_pps_month.php"><i class="icon icon-upload"></i> <span>Planning Sheet (PPS)</span></a>
      <ul>
         <?php if ($url == "upload_pps_month.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="upload_pps_month.php">Upload PPS</a></li>
        <?php if ($url == "display_pps_month_reprint.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="display_pps_month_reprint.php">PPS Listing</a></li>
       	</ul>
    </li>
     <?php if ($url == "release_plan_order_list.php")
	  { ?> 
   <li class="active"> <?php }else { echo "<li>"; } ?> <a href="release_plan_order_list.php"><i class="icon icon-ok-sign"></i> <span>Cancel Planned Order</span></a></li>
   
     <?php if ($url == "inprogress_plan_order_list.php")
	  { ?> 
   <li class="active"> <?php }else { echo "<li>"; } ?>
   <a href="inprogress_plan_order_list.php"><i class="icon icon-truck"></i> <span>InProgress Planned Order</span></a>   </li>    <?php if ($url == "technical_complete_tran.php"){ ?>
        <li class="active"><?php }else { echo "<li>"; } ?><a href="technical_complete_tran.php"><i class="icon icon-folder-close"></i> <span>Technical Complete</span></a></li>
     
     <?php if ($url == "list_ftp_pending_sap.php"){ ?>
        <li class="active"><?php }else { echo "<li>"; } ?><a href="list_ftp_pending_sap.php"><i class="icon icon-folder-close"></i> <span>FTP Monitor</a></li>
        
         <?php if ($url == "list_ftp_to-ipos.php"){ ?>
        <li class="active"><?php }else { echo "<li>"; } ?><a href="list_ftp_to-ipos.php"><i class="icon icon-folder-close"></i> <span>FTP to IPOS</a></li>
        
        <?php //if ($url == "list_ftp_to-ipos.php")
		// { ?> 
  <!-- <li class="active"> <?php //}else { echo "<li>"; } ?> <a href="close_plan_order_list.php"><i class="icon icon-folder-close"></i> <span>Closed Planned Order</span></a>
   </li>-->

   <?php if ($url == "report_all_planning_module.php")
		 { ?> 
   <li class="active"> <?php }else { echo "<li>"; } ?> <a href="report_all_planning_module.php"><i class="icon icon-folder-close"></i> <span>Report Planned Order</span></a>
   </li>
    
  <li><a href="../logout.php"><i class="icon icon-signout"></i> <span>Logout</span></a></li>
  
  </ul>
</div>
<!--sidebar-menu-->
