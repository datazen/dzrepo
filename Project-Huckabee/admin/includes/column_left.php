<?php
/*
  $Id: column_left.php,v 6.5.4 2017/12/17 01:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2017 Loaded Commerce
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<div id="sidebar" class="sidebar">
    <!-- begin sidebar scrollbar -->
    <div data-scrollbar="true" data-height="100%">
        <!-- begin sidebar nav -->
        <ul class="nav nav-list">
            <!-- sidebar minify button -->
            <li class="index">
                <a href="<?php echo tep_href_link(FILENAME_DEFAULT,'','SSL');?>"><i class="fa fa-laptop"></i> <span>Dashboard</span></a>
            </li>
            <?php
                $menu_active = '';
                $box_files_list = array(  
                    array('catalog','catalog.php', BOX_HEADING_CATALOG,'fa-tasks'),
                    array('customers', 'customers.php' , BOX_HEADING_CUSTOMERS,'fa-users'),
                    array('gv_admin', 'gv_admin.php' , BOX_HEADING_GV_ADMIN,'fa-gift'),
                    array('marketing', 'marketing.php', BOX_HEADING_MARKETING,'fa-signal'),
                    array('information', 'information.php', BOX_HEADING_INFORMATION,'fa-info'),
                    array('articles', 'articles.php' , BOX_HEADING_ARTICLES,'fa-book'),
                    array('reports', 'reports.php' , BOX_HEADING_REPORTS,'fa-bar-chart-o'),
                    array('data', 'data.php' , BOX_HEADING_DATA,'fa-database'),
                    array('links', 'links.php' , BOX_HEADING_LINKS,'fa-external-link-square'),
                    array('administrator','administrator.php', BOX_HEADING_ADMINISTRATOR, 'fa-user'),
                    array('configuration', 'configuration.php', BOX_HEADING_CONFIGURATION,'fa-gear'),
                    array('modules', 'modules.php' , BOX_HEADING_MODULES,'fa-cubes'),
                    array('design_controls' , 'design_controls.php' , BOX_HEADING_DESIGN_CONTROLS,'fa-archive'),
                    array('tools','tools.php',BOX_HEADING_TOOLS,'fa-wrench'),
                    array('taxes', 'taxes.php' , BOX_HEADING_LOCATION_AND_TAXES,'fa-bank'),
                    array('localization', 'localization.php' , BOX_HEADING_LOCALIZATION,'fa-language')
                );

                if (defined('MODULE_ADDONS_FDM_STATUS') && MODULE_ADDONS_FDM_STATUS == 'True') {
                    $box_files_list = array_merge($box_files_list, array(array('fdm_library', 'fdm_library.php' , BOX_HEADING_LIBRARY,'fa-file-text')));
                }
                if (defined('MODULE_ADDONS_CSMM_STATUS') && MODULE_ADDONS_CSMM_STATUS == 'True') {
                    $box_files_list = array_merge($box_files_list, array(array('ticket', 'ticket.php', BOX_HEADING_TICKET,'fa-ticket')));
                }
                if (defined('MODULE_ADDONS_CTM_STATUS') && MODULE_ADDONS_CTM_STATUS == 'True') {
                    $box_files_list = array_merge($box_files_list, array(array('testimonials', 'testimonials.php', BOX_HEADING_TESTIMONIALS,'fa-comments')));
                }

                foreach($box_files_list as $item_menu) {

                    // NOTE: Menu "selected" logic moved to javascript includes/column_left.php

                    if (tep_admin_check_boxes($item_menu[1]) == true) {

                        echo '<li class="' . $item_menu[0] . ' has-sub">
                                <a href="javascript:;">
                                  <i class="fa ' . $item_menu[3] . '"></i>
                                  <b class="caret pull-right"></b>
                                  <span>' . $item_menu[2] . '</span>
                                </a>';
                        require(DIR_WS_BOXES . $item_menu[1]);
                    }
                }                
                ?>
            <!-- begin sidebar minify button -->
            <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
            <!-- end sidebar minify button -->
        </ul>
        <!-- end sidebar nav -->
    </div>
    <!-- end sidebar scrollbar -->
</div>
<div class="sidebar-bg"></div>
<!-- end #sidebar -->
<script>
$( document ).ready(function() {
  // remove all active
  $('.nav-list li.active').removeClass('active');
  // add active class to current page
  var currentPage = '<?php echo str_replace(".php", "", basename($_SERVER['PHP_SELF'])); ?>'; 
  // menu mapping
//alert(currentPage);  
  var box = '';
  switch (currentPage) {
    case 'index':
      box = 'index';
      break;
    
    case 'categories':
    case 'product_edit':
    case 'products_attributes':
    case 'manufacturers':
    case 'reviews':
    case 'shopbyprice':
    case 'xsell_products':
    case 'featured':
    case 'products_expected':
    case 'extra_fields':
      box = 'catalog';
      break;

    case 'orders':
    case 'create_order':
    case 'create_orders_admin':
    case 'paypal_ipn':
    case 'customers':
    case 'create_account':
    case 'marketplace':
      box = 'customers';
      break; 

    case 'coupon_admin':
    case 'gift_voucher_report':
    case 'gift_voucher_queue':
    case 'mail_gift_voucher':
    case 'gift_vouchers_sent':
      box = 'gv_admin';
      break;           


    case 'admin_members':
    case 'admin_groups':
    case 'admin_account':
    case 'admin_files':
      box = 'administrator';
      break;
  }
  $('.' + box).addClass('active');

  // set sub menu active
  var section = '<?php echo $_GET['section']; ?>';
  var action = '<?php echo $_GET['action']; ?>';
  var admin_groupID = '<?php echo $_GET['gID']; ?>';

  //overrides
  if (action == 'new_member' || action == 'edit_member' || action == 'del_member') section = 'admin_members';
  if (action == 'new_group' || action == 'edit_group' || action == 'del_group' || action == 'define_group') section = 'admin_groups';
  if (admin_groupID) section = 'admin_groups';
  if (action == 'check_account') section = 'update_account';
  if (action == 'edit_product') section = 'categories';

  // set default if no parameters
  if (section == '') section = currentPage;
  // set active
  if (section) $('.' + section).addClass('active');
    
});    
</script>