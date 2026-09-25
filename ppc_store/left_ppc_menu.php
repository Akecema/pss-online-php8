<?php
/**
 * ppc_store/left_ppc_menu.php
 * Part of: PPC Store module
 * Filename suggests: left ppc menu
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
<div id="sidebar"><a href="index_ppc_store.php" class="visible-phone"><i class="icon icon-home"></i> Home</a>
  <ul>
    <?php if ($url == "index_ppc_store.php"){ ?>
    <li class="active"><?php }else { echo "<li>"; } ?><a href="index_ppc_store.php"><i class="icon icon-home"></i> <span>Home</span></a> </li>
    
     <?php if ($url == "dash.php"){ ?>
    <li class="active"><?php }else { echo "<li>"; } ?><a href="dash.php"><i class="icon icon-dashboard"></i> <span>Dashboard</span></a> </li>
    
<?php if (($url == "trans_posting_to_store.php") || ($url == "trans_posting_to_PLB.php") || ($url  =="trans_posting_to_subcont.php") || ($url == "return_posting_to_PLB.php") || ($url == "return_posting_to_subcont.php") || ($url == "print_gdo_sdo.php") || ($url == "cancel_trans_posting_to_store.php") || ($url == "document_list_TP_to_store.php")) { ?> 
   <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?> <a href="trans_posting_to_store.php"><i class="icon icon-truck"></i> <span>Transfer Posting</span></a>
      <ul>
         <?php if ($url == "trans_posting_to_store.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="trans_posting_to_store.php">TP to Store</a></li>
          <?php if ($url == "trans_posting_to_PLB.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="trans_posting_to_PLB.php">TP to PLB</a></li>
         <?php if ($url == "trans_posting_to_subcont.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="trans_posting_to_subcont.php">TP to Subcont</a></li>
         <?php if ($url == "return_posting_to_PLB.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="return_posting_to_PLB.php">Return from PLB</a></li>
        <?php if ($url == "return_posting_to_subcont.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="return_posting_to_subcont.php">Return from Subcont</a></li>
        <?php if ($url == "print_gdo_sdo.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="print_gdo_sdo.php">Print GDO &amp; SDO</a></li>
        <?php if ($url == "cancel_trans_posting_to_store.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="cancel_trans_posting_to_store.php">Cancel Document</a></li>
        <?php if ($url == "document_list_TP_to_store.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="document_list_TP_to_store.php">Document List</a></li>
      </ul>
    </li>
  
    <?php if ($url == "list_ftp_pending_sap.php") { ?>
    <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?><a href="list_ftp_pending_sap.php"><i class="icon icon-list"></i> <span>FTP Monitoring : Production</span> </a>
      <ul>
         <?php if ($url == "list_ftp_pending_sap.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="list_ftp_pending_sap.php">FTP Monitoring</a></li>
       <!-- <?php //if ($url == "FTP_bflush_download.php"){ ?>
    		<li class="submenu active active"><?php // }else { echo "<li>"; } ?><a href="FTP_bflush_download.php">FTP Backflush </a></li>
       <?php //if ($url =="FTP_gdtranfer_download.php"){ ?>
    		<li class="submenu active active"><?php //}else { echo "<li>"; } ?><a href="FTP_gdtranfer_download.php">FTP Good Transfer </a></li>-->
		</ul>
    </li>
    
      <?php if (($url == "list_ftp_tr_store.php")) { ?>
    <li class="submenu active"> <?php }else { echo "<li class='submenu'>"; } ?><a href="list_ftp_tr_store.php"><i class="icon icon-list"></i> <span>FTP Monitoring : PPC Store</span> </a>
      <ul>
         <?php if ($url == "list_ftp_tr_store.php"){ ?>
        <li class="submenu active active"><?php }else { echo "<li>"; } ?><a href="list_ftp_tr_store.php">FTP Transfer</a></li>
  
		</ul>
    </li>
  
  
  <li><a href="../logout.php"><i class="icon icon-signout"></i> <span>Logout</span></a></li>
  
  </ul>
</div>
<!--sidebar-menu-->
