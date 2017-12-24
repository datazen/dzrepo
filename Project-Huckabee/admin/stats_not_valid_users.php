<?php
/*
  $Id: stats_inactive_user.php,v 1.2 2004/05/02 15:00:00
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
  Created by John Wood - www.z-is.net
  Modified by Steel Shadow - rebelstyle.com

*/

 require('includes/application_top.php');
 ?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Loaded Commercial Open Source eCommerce</title>
<link rel="icon" type="image/png" href="favicon.ico" />
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
  <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
  <!-- ================== BEGIN BASE CSS STYLE ================== -->
  <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
  <link href="assets/css/animate.min.css" rel="stylesheet" />
  <link href="assets/css/style.min.css" rel="stylesheet" />
  <link href="assets/css/style-responsive.min.css" rel="stylesheet" />
  <link href="assets/css/theme/blue.css" rel="stylesheet" id="theme" />
  <!-- ================== END BASE CSS STYLE ================== -->
  
  <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
  <link href="assets/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />  
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Select/css/select.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
  <!-- ================== END PAGE LEVEL STYLE ================== -->
  <script language="javascript" src="includes/general.js"></script>
  <script type="text/javascript" src="includes/menu.js"></script>
</head>
<body>
    <!-- begin #page-container -->
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed gradient-enabled">
    <!-- header //-->
    <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
    <!-- header_eof //-->
      
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
      
    <!-- begin #content -->
    <div id="content" class="content">
      <!-- begin breadcrumb -->
      <ol class="breadcrumb pull-right">
        <li>Create &nbsp; <a title="<?php echo BOX_MANUAL_ORDER_CREATE_ACCOUNT;?>" href="<?php echo tep_href_link(FILENAME_CREATE_ACCOUNT,'','SSL');?>" class="btn btn-xs btn-header"><i class="fa fa-user"></i><span class="label">+</span></a> <a title="<?php echo BOX_MANUAL_ORDER_CREATE_ORDER;?>" href="<?php echo tep_href_link(FILENAME_CREATE_ORDER,'','SSL');?>" class="btn btn-xs btn-header"><i class="fa fa-shopping-cart"></i><span class="label">+</span></a></li>
        <li>Search &nbsp; <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="ProductsPopover">Products</a> <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="CustomerPopover">Customers</a> <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="OrdersPopover">Orders</a> <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="PagesPopover">Pages</a></li>
      </ol>
      <!-- end breadcrumb -->
      <!-- begin page-header -->
      <h1 class="page-header"><?php echo HEADING_TITLE; ?></h1>
      <!-- end page-header -->
      
    <!-- begin panel -->
    <div class="panel panel-inverse"><table border="0" width="100%" cellspacing="0" cellpadding="4">
          <tr>
            <td valign="top" class="main">
<?php
  $go = (isset($_GET['go']) ? $_GET['go'] : '' );
  $id = (isset($_GET['id']) ? $_GET['id'] : '' );
$cust_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $id . "'");
$cust = tep_db_fetch_array($cust_query);

if ($go == ''){
?>
      <table border="0" width="100%" cellspacing="0" cellpadding="0" class="data-table">
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ID; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_EMAIL; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ACCOUNT_CREATED; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LAST_LOGON; ?></td>
                      <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DELETE; ?></td>
                    </tr>
<?php
  $siu_query_raw = "select * from " . TABLE_CUSTOMERS_INFO . " ci, " . TABLE_CUSTOMERS . " c where c.customers_id = ci.customers_info_id and c.customers_validation = '0' order by c.customers_id";
  $siu_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $siu_query_raw, $siu_query_numrows );
  $siu_query = tep_db_query($siu_query_raw);
  while ($customers = tep_db_fetch_array($siu_query)) {

 ?>
      <tr class="dataTableRow"> 
        <td class="dataTableContent"><?php echo $customers['customers_id'];?></td>
        <td class="dataTableContent"><?php echo $customers['customers_firstname'] . ' ' . $customers['customers_lastname'];?></td>
        <td class="dataTableContent"><?php echo '<a href="mailto:' . $customers['customers_email_address'] . '"><u>' . $customers['customers_email_address'] . '</u></a>' ; ?></td>
        <td class="dataTableContent"><?php echo tep_date_short($customers['customers_info_date_account_created']); ?></td>
        <td class="dataTableContent"><?php echo tep_date_short($customers['customers_info_date_of_last_logon']); ?></td>
        <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_STATS_NOT_VALID_USER, 'go=delete&id=' . $customers['customers_id'] . '&page=' . $_GET['page']) .'">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>'; ?></td>
      </tr>
<?php
  }
?>
<?php
            } elseif ($_GET['go'] == 'delete')
      {
              echo '<br>' . sprintf(SURE_TO_DELETE, $cust[customers_firstname] . ' ' . $cust[customers_lastname]) . '<br><br><a href="' . tep_href_link(FILENAME_STATS_NOT_VALID_USER,  'page=' . $_GET['page'] . '&go=deleteyes&id=' . $_GET['id']) . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_STATS_NOT_VALID_USER, 'page=' . $_GET['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a><br><br>';
            } elseif ($_GET['go'] == 'deleteyes'){
      tep_db_query("DELETE FROM " . TABLE_CUSTOMERS . " where customers_id = '" . $_GET['id'] . "'");
      tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . $_GET['id'] . "'");
      tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . $_GET['id'] . "'");
      tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $_GET['id'] . "'");
      tep_db_query("DELETE FROM " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $_GET['id'] . "'");
      tep_db_query("DELETE FROM " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . $_GET['id'] . "'");
      echo '<br>' . sprintf(SIU_CUSTOMER_DELETED, $cust[customers_firstname] . ' ' . $cust[customers_lastname]) . '<br><br><br><a href="' . tep_href_link(FILENAME_STATS_NOT_VALID_USER, 'page=' . $_GET['page']) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a><br><br>';
    }
        
?>
       </table></td>
      </tr>
    
  <?php if ($go == ''){?>   
      <tr>
        <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="0" class="data-table-foot">
          <tr>
            <td class="smallText" valign="top"><?php echo $siu_split->display_count($siu_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
            <td class="smallText" align="right"><?php echo $siu_split->display_links($siu_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
          </tr>
       </table></td>
     </tr>
  <?php }?> 
  
    </table></td>
 </tr>
</table></div></div>
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>