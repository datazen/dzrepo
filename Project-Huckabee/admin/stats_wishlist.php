<?php
/*=======================================================================*\
|| #################### //-- SCRIPT INFO --// ########################### ||
|| #  Script name: stats_wishlist.php                                 # ||
|| #  Contribution: Simple Wishlist Report                            # ||
|| #  Version: 1.0                                                    # ||
|| #  Date: April 16 2005                                             # ||
|| # ------------------------------------------------------------------ # ||
|| #################### //-- COPYRIGHT INFO --// ######################## ||
|| #  Copyright (C) 2005 Chris LaRocque               # ||
|| #                                  # ||
|| #  This script is free software; you can redistribute it and/or  # ||
|| #  modify it under the terms of the GNU General Public License   # ||
|| #  as published by the Free Software Foundation; either version 2  # ||
|| #  of the License, or (at your option) any later version.      # ||
|| #                                  # ||
|| #  This script is distributed in the hope that it will be useful,  # ||
|| #  but WITHOUT ANY WARRANTY; without even the implied warranty of  # ||
|| #  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the # ||
|| #  GNU General Public License for more details.          # ||
|| #                                  # ||
|| #  Script is intended to be used with:               # ||
|| #  osCommerce, Open Source E-Commerce Solutions          # ||
|| #  http://www.oscommerce.com                   # ||
|| #  Copyright (c) 2003 osCommerce                 # ||
|| ###################################################################### ||
\*========================================================================*/
  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  include_once(DIR_WS_LANGUAGES . $language . '/' . 'stats_wishlist.php');
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
      <h1 class="page-header"><?php echo TEXT_OF_WISHLIST; ?></h1>
      <!-- end page-header -->
      
    <!-- begin panel -->
    <div class="panel panel-inverse"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" align="right">
      <?php
      # Get specific list data
      if (isset($_GET['cid'])) { 
      $customers_query_raw = tep_db_query("select customers_firstname, customers_lastname from " .
      TABLE_CUSTOMERS . " where customers_id = '".$_GET['cid']."'");
      $customers = tep_db_fetch_array($customers_query_raw);
      # name
      echo $customers['customers_firstname'] . ' ' . $customers['customers_lastname'];
      }else{
    //  echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);
      } ?></td>
          </tr>
        </table></td>
      </tr>
    <?php 
    if (isset($_GET['cid'])) {
    // Display wishlist data ;
    ?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
    
    <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TEXT_OF_PRODUCT; ?></td>
                <td class="dataTableHeadingContent"><?php echo TEXT_OF_MODEL; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TEXT_OF_PRICE; ?>&nbsp;</td>
              </tr>
<?php
     // show the contents
     $products_query_raw2 = tep_db_query("select * from customers_wishlist where customers_id = '". $_GET['cid']."'");
     while($products2 = tep_db_fetch_array($products_query_raw2)) { 
    // print_r($products2);
             $product_query_raw1 = tep_db_query("select p.products_id, p.products_model, p.products_price, pd.products_name, pd.language_id  from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . $products2['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
                while($products1 = tep_db_fetch_array($product_query_raw1)) { 
            $products_name = $products1['products_name'] ;
            $products_model = $products1['products_model'] ;
            $products_price = $products1['products_price'] ;
             

?>
              <tr class="dataTableRow">
                <td class="dataTableContent"><?php echo $products_name; ?></td>
                <td class="dataTableContent"><?php echo $products_model; ?></td>
                <td class="dataTableContent" align="right"><?php echo $currencies->format($products_price); ?>&nbsp;</td>
              </tr>
<?php // #eof while
            }  
  }
?>
            </table></td>
          </tr>
    <?php //show the list of wishlist owners
    }else{ 
    ;?>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0" class="data-table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">&nbsp;</td>
                <td class="dataTableHeadingContent"><?php echo TEXT_OF_CUSTOMER; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TEXT_OF_PRODUCTS_LIST; ?>&nbsp;</td>
              </tr>
         <?php
       # Get list of owners
       if (isset($_GET['page']) && ($_GET['page'] > 1)) 
       $rows = $_GET['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;
       $customers_query_raw = "select c.customers_firstname, c.customers_lastname, cw.customers_id, 
       count(cw.products_id) as prodcount from " . TABLE_CUSTOMERS . " c, customers_wishlist cw WHERE
       cw.customers_id = c.customers_id group by c.customers_firstname, c.customers_lastname order by
       prodcount DESC";
       $customers_query = tep_db_query($customers_query_raw);
       
       $customers_query_numrows = tep_db_num_rows($customers_query);
       $customers_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_numrows);
       
       
       $rows = 0;
       
       while ($customers = tep_db_fetch_array($customers_query)) {
       $rows++;
       
       if (strlen($rows) < 2) {
       $rows = '0' . $rows;
       }
       ?>
              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href='<?php echo tep_href_link('stats_wishlist.php', 'cid=' . $customers['customers_id'], 'NONSSL'); ?>'">
                <td class="dataTableContent"><?php echo $rows; ?></td>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link('stats_wishlist.php', 'cid=' . $customers['customers_id'], 'NONSSL') . '">' . $customers['customers_firstname'] . ' ' . $customers['customers_lastname'] . '</a>'; ?></td>
                <td class="dataTableContent" align="right"><?php echo $customers['prodcount']; ?>&nbsp;</td>
              </tr>
       <?php
           } # eof while
       } # eof if/else list or contents
       ?>
            </table></td>
          </tr>
      <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="0" class="data-table-foot">
              <tr>
                <td class="smallText" valign="top"><?php if (!isset($_GET['cid'])) echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                <td class="smallText" align="right"><?php  if (!isset($_GET['cid'])) echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></div></div></div>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
