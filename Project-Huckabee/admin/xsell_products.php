
<?php

/*
  $Id: xsell_products.php,v 1.2 2008/06/13 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

if (isset($_POST['page'])) {
  $page = $_POST['page'] ;
} else if (isset($_GET['page'])) {
  $page = $_GET['page'] ;
} else {
  $page = '1' ;
}

if (isset($_POST['action'])) {
  $action = $_POST['action'] ;
} else if (isset($_GET['action'])) {
  $action = $_GET['action'] ;
} else {
  $action = '' ;
}

switch($action){
  case 'update_cross' :
    if (isset($_POST['product'])) {
      foreach ($_POST['product'] as $temp_prod){
        tep_db_query('delete from ' . TABLE_PRODUCTS_XSELL . ' where xsell_id = "'.$temp_prod.'" and products_id = "'.$_GET['add_related_product_ID'].'"');
      }
    }
    $sort_start_query = tep_db_query('select sort_order from ' . TABLE_PRODUCTS_XSELL . ' where products_id = "'.$_GET['add_related_product_ID'].'" order by sort_order desc limit 1');
    $sort_start = tep_db_fetch_array($sort_start_query);
    $sort = (($sort_start['sort_order'] > 0) ? $sort_start['sort_order'] : '0');
    if (isset($_POST['cross'])) {
      foreach ($_POST['cross'] as $temp){
        $sort++;
        $insert_array = array();
        $insert_array = array('products_id' => $_GET['add_related_product_ID'],
                              'xsell_id' => $temp,
                              'sort_order' => $sort);
        tep_db_perform(TABLE_PRODUCTS_XSELL, $insert_array);
      }
    }
    $messageStack->add('search', CROSS_SELL_SUCCESS, 'success');
    break;
  case 'update_sort' :
    foreach ($_POST as $key_a => $value_a){
        if (is_numeric ($value_a)) {
            tep_db_query('update ' . TABLE_PRODUCTS_XSELL . ' set sort_order = "' . $value_a . '" where xsell_id = "' . $key_a . '" AND products_id = "' . $_GET['add_related_product_ID'] . '"');
        }
    }
    $messageStack->add('search', SORT_CROSS_SELL_SUCCESS, 'success');
    break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="icon" type="image/png" href="favicon.ico" />
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
  <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
  <!-- ================== BEGIN BASE CSS STYLE ================== -->
  <link href="<?php echo (($request_type == 'SSL') ? 'https:' : 'http:'); ?>//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
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
  <?php require('includes/account_check.js.php'); ?>
</head>
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
    <div class="panel panel-inverse">
<!-- header_eof //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
    <td valign="top" class="page-container">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5');?></td>
        </tr>
        <tr>
          <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="smallText" align="right">
                <?php
                echo tep_draw_form('search', 'xsell_products.php');
                echo 'Search: ' . tep_draw_input_field('search');
                echo '</form>';
                ?>
              </td>
            </tr>
          </table></td>
          <td align="right" width="1"><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="smallText" align="right">
                <?php
                echo tep_draw_form('reset', 'xsell_products.php');
                echo '<input type="submit" value="Reset Search">';
                echo '</form>';
                ?>
              </td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '5');?></td>
        </tr>
      </table>
      <?php
      if (!isset($_GET['add_related_product_ID']) || $_GET['add_related_product_ID'] == '') {
        ?>
        <table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" width="75"><?php echo TABLE_HEADING_PRODUCT_ID;?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCT_MODEL;?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCT_NAME;?></td>
            <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo TABLE_HEADING_CURRENT_SELLS;?></td>
            <td class="dataTableHeadingContent" colspan="2" nowrap="nowrap" align="center"><?php echo TABLE_HEADING_UPDATE_SELLS;?></td>
          </tr>
          <?php
          if (isset($_POST['search'])) {
            $search = str_replace("'", "&#39", tep_db_prepare_input($_POST['search']));
            $products_query_raw = "SELECT p.products_id,
                                          p.products_model,
                                          pd.products_name,
                                          p.products_id
                                     FROM " . TABLE_PRODUCTS . " p,
                                          " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                    WHERE p.products_id = pd.products_id
                                      and (pd.products_name like '%" . tep_db_input($search) . "%' or p.products_model like '%" . tep_db_input($search) . "%')
                                      and pd.language_id = '" . (int)$languages_id . "'
                                 ORDER BY p.products_model, pd.products_name ASC";
          } else {
            $products_query_raw = 'select p.products_id, p.products_model, pd.products_name, p.products_id from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" order by p.products_id asc';
          }
          $products_split = new splitPageResults($page, MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
          $products_query = tep_db_query($products_query_raw);
          while ($products = tep_db_fetch_array($products_query)) {
            ?>
            <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onClick="document.location.href=<?php echo tep_href_link(FILENAME_XSELL_PRODUCTS, 'add_related_product_ID=' . $products['products_id'], 'NONSSL');?>">
              <td class="dataTableContent">&nbsp;<?php echo $products['products_id'];?>&nbsp;</td>
              <td class="dataTableContent">&nbsp;<?php echo $products['products_model'];?>&nbsp;</td>
              <td class="dataTableContent">&nbsp;<?php echo $products['products_name'];?>&nbsp;</td>
              <td class="dataTableContent"><table border="0" width="100%" cellspacing="0" cellpadding="0">
                <?php
                $products_cross_query = tep_db_query('select p.products_id, p.products_model, pd.products_name, p.products_id, x.products_id, x.xsell_id, x.sort_order, x.ID from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd, '.TABLE_PRODUCTS_XSELL.' x where x.xsell_id = p.products_id and x.products_id = "'.$products['products_id'].'" and p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" order by x.sort_order asc');
                $i=0;
                while ($products_cross = tep_db_fetch_array($products_cross_query)){
                  $i++;
                  ?>
                  <tr>
                    <td class="dataTableContent">&nbsp;<?php echo $i . '.&nbsp;&nbsp;<b>' . $products_cross['products_model'] . '</b>&nbsp;' . $products_cross['products_name'];?>&nbsp;</td>
                  </tr>
                  <?php
                }
                if ($i <= 0) {
                  ?>
                  <tr>
                    <td class="dataTableContent">&nbsp;--&nbsp;</td>
                  </tr>
                  <?php
                } else {
                  ?>
                  <tr>
                    <td class="dataTableContent"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1');?></td>
                  </tr>
                  <?php
                }
                ?>
              </table></td>
              <td class="dataTableContent" align="center"><a href="<?php echo tep_href_link(FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'add_related_product_ID=' . $products['products_id'], 'NONSSL');?>"><?php echo TEXT_EDIT_SELLS;?></a>&nbsp;</td>
              <td class="dataTableContent" align="center"><?php echo (($i > 0) ? '<a href="' . tep_href_link(FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'sort=1&add_related_product_ID=' . $products['products_id'], 'NONSSL') .'">'.TEXT_SORT.'</a>&nbsp;' : '--')?></td>
            </tr>
            <?php
          }
          ?>
          </table>
          <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
          <tr>
            <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $page, TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
                <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $page, tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID', 'action'))); ?></td>
              </tr>
            </table></td>
          </tr>
          <?php
            // RCI code start
            echo $cre_RCI->get('xsell', 'bottom');
            // RCI code eof
          ?>
        </table>
        <?php
      } elseif ( (isset($_GET['add_related_product_ID']) && $_GET['add_related_product_ID'] != '') && (!isset($_GET['sort']) || $_GET['sort'] == '') ){
        $products_name_query = tep_db_query('select pd.products_name, p.products_model, p.products_image from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = "'.$_GET['add_related_product_ID'].'" and p.products_id = pd.products_id and pd.language_id ="'.(int)$languages_id.'"');
        $products_name = tep_db_fetch_array($products_name_query);
        ?>
        <table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
          <tr>
            <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBoxContent">
              <tr>
                <td class="pageHeading"><?php echo TEXT_SETTING_SELLS . '&nbsp;' . $products_name['products_name'].' ('.TEXT_MODEL.': '.$products_name['products_model'].') ('.TEXT_PRODUCT_ID.': '.$_GET['add_related_product_ID'].')';?></td>
                <td><?php echo tep_image( DIR_WS_CATALOG_IMAGES . $products_name['products_image'],$products_name['products_name'],SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"');?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td class="dataTableContent"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1');?></td>
          </tr>
          <tr>
            <td>
              <?php
                $products_cross_image_query = tep_db_query('select p.products_id, p.products_model, p.products_image, p.products_id, x.products_id, x.xsell_id, x.sort_order, x.ID from '.TABLE_PRODUCTS.' p,  '.TABLE_PRODUCTS_XSELL.' x where x.xsell_id = p.products_id and x.products_id = "'.$_GET['add_related_product_ID'].'" order by x.sort_order asc');
                echo '<table border ="0"  cellpadding = "0" cellspacing = "0"><tr>';
                $i = 0;
                 while ($products_cross_image = tep_db_fetch_array($products_cross_image_query)){
                 echo '<td>'.tep_image( DIR_WS_CATALOG_IMAGES . $products_cross_image['products_image'],$products_name['products_name'],SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"').'</td>';
                 if($i == 6){
                   echo '</tr><tr>';
                 }
                 $i++;
                 }
                echo '</table>';
               ?>
            </td>
          </tr>
          <tr>
            <td class="dataTableContent"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1');?></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_form('update_cross', FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'action=update_cross', 'post');?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="15%">&nbsp;<?php echo TABLE_HEADING_PRODUCT_ID;?>&nbsp;</td>
                <td class="dataTableHeadingContent" width="15%">&nbsp;<?php echo TABLE_HEADING_PRODUCT_MODEL;?>&nbsp;</td>
                <td class="dataTableHeadingContent" width="15%">&nbsp;<?php echo TABLE_HEADING_PRODUCT_IMAGE;?>&nbsp;</td>
                <td class="dataTableHeadingContent" width="15%">&nbsp;<?php echo TABLE_HEADING_CROSS_SELL_THIS;?>&nbsp;</td>
                <td class="dataTableHeadingContent" width="25%">&nbsp;<?php echo TABLE_HEADING_PRODUCT_NAME;?>&nbsp;</td>
                <td class="dataTableHeadingContent" width="15%">&nbsp;<?php echo TABLE_HEADING_PRODUCT_PRICE;?>&nbsp;</td>
              </tr>
              <?php
              $products_query_raw = 'select p.products_id, p.products_model, p.products_image, p.products_price, pd.products_name, p.products_id from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" order by p.products_id asc';
              $products_split = new splitPageResults($page, MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
              $products_query = tep_db_query($products_query_raw);
              while ($products = tep_db_fetch_array($products_query)) {
                $xsold_query = tep_db_query('select * from '.TABLE_PRODUCTS_XSELL.' where products_id = "'.$_GET['add_related_product_ID'].'" and xsell_id = "'.$products['products_id'].'"');
                ?>
                <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
                  <td class="dataTableContent" align="center">&nbsp;<?php echo $products['products_id'];?>&nbsp;</td>
                  <td class="dataTableContent" align="center">&nbsp;<?php echo $products['products_model'];?>&nbsp;</td>
                  <td class="dataTableContent" align="center">&nbsp;<?php echo ((is_file(DIR_FS_CATALOG_IMAGES.$products['products_image'])) ?  tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES.$products['products_image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) : '<br>No Image<br>');?>&nbsp;</td>
                  <td class="dataTableContent">&nbsp;<?php echo tep_draw_hidden_field('product[]', $products['products_id']) . tep_draw_checkbox_field('cross[]', $products['products_id'], ((tep_db_num_rows($xsold_query) > 0) ? true : false), '', ' onMouseOver="this.style.cursor=\'hand\'"');?>&nbsp;<label onMouseOver="this.style.cursor='hand'"><?php echo TEXT_CROSS_SELL;?></label>&nbsp;</td>
                  <td class="dataTableContent">&nbsp;<?php echo $products['products_name'];?>&nbsp;</td>
                  <td class="dataTableContent">&nbsp;<?php echo $currencies->format($products['products_price']);?>&nbsp;</td>
                </tr>
                <?php
              }
              ?>
              </table>
              <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
              <tr class="main">
                <td colspan="6"><table cellpadding="3" cellspacing="0" border="0" width="100%">
                  <tr class="main">
                    <td align="right"><?php echo '<a href="'.tep_href_link(FILENAME_XSELL_PRODUCTS, 'men_id=catalog').'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_update.gif',IMAGE_UPDATE);?></td>
                  </tr>
                </table></form></td>
              </tr>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBoxContent">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $page, TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                    <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $page, tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID', 'action'))); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <?php
            // RCI code start
            echo $cre_RCI->get('xsell', 'bottom');
            // RCI code eof
          ?>
        </table>
        <?php
      } elseif ( (isset($_GET['add_related_product_ID']) && $_GET['add_related_product_ID'] != '') && (isset($_GET['sort']) && $_GET['sort'] != '') ){
        $products_name_query = tep_db_query('select pd.products_name, p.products_model, p.products_image from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = "'.$_GET['add_related_product_ID'].'" and p.products_id = pd.products_id and pd.language_id ="'.(int)$languages_id.'"');
        $products_name = tep_db_fetch_array($products_name_query);
        ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td><?php echo tep_draw_form('update_sort', FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'action=update_sort', 'post');?><table width="100%" cellpadding="1" cellspacing="1" border="0">
              <tr>
                <td colspan="6"><table cellpadding="3" cellspacing="0" border="0" width="100%">
                  <tr class="main">
                    <td valign="top" colspan="6"><b><?php echo TEXT_SETTING_SELLS.': '.$products_name['products_name'].' ('.TEXT_MODEL.': '.$products_name['products_model'].') ('.TEXT_PRODUCT_ID.': '.$_GET['add_related_product_ID'].')';?></b></td>
                  </tr>
                </table></td>
              </tr>
 		<div class="table-responsive">
			<table class="table table-bordered">
			 <thead>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="15%">&nbsp;<?php echo TABLE_HEADING_PRODUCT_ID;?>&nbsp;</td>
                <td class="dataTableHeadingContent" width="15%">&nbsp;<?php echo TABLE_HEADING_PRODUCT_MODEL;?>&nbsp;</td>
                <td class="dataTableHeadingContent" width="15%">&nbsp;<?php echo TABLE_HEADING_PRODUCT_IMAGE;?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="center" width="25%">&nbsp;<?php echo TABLE_HEADING_PRODUCT_NAME;?>&nbsp;</td>
                <td class="dataTableHeadingContent" width="15%">&nbsp;<?php echo TABLE_HEADING_PRODUCT_PRICE;?>&nbsp;</td>
                <td class="dataTableHeadingContent" width="15%">&nbsp;<?php echo TABLE_HEADING_PRODUCT_SORT;?>&nbsp;</td>
              </tr>
             </thead>
             <tbody>
              <?php
              $products_query_raw = 'select p.products_id as products_id, p.products_price, p.products_image, p.products_model, pd.products_name, p.products_id, x.products_id as xproducts_id, x.xsell_id, x.sort_order, x.ID from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd, '.TABLE_PRODUCTS_XSELL.' x where x.xsell_id = p.products_id and x.products_id = "'.$_GET['add_related_product_ID'].'" and p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" order by x.sort_order asc';
              $products_split = new splitPageResults($page, MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
              $sort_order_drop_array = array();
              for ($i=1;$i<=$products_query_numrows;$i++) {
                $sort_order_drop_array[] = array('id' => $i, 'text' => $i);
              }
              $products_query = tep_db_query($products_query_raw);
              while ($products = tep_db_fetch_array($products_query)){
                ?>
                <tr bgcolor="#DFE4F4">
                  <td class="dataTableContent" align="center">&nbsp;<?php echo $products['products_id'];?>&nbsp;</td>
                  <td class="dataTableContent" align="center">&nbsp;<?php echo $products['products_model'];?>&nbsp;</td>
                  <td class="dataTableContent" align="center">&nbsp;<?php echo ((is_file(DIR_FS_CATALOG_IMAGES.$products['products_image'])) ?  tep_image( DIR_WS_CATALOG_IMAGES.$products['products_image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) : '<br>No Image<br>');?>&nbsp;</td>
                  <td class="dataTableContent" align="center">&nbsp;<?php echo $products['products_name'];?>&nbsp;</td>
                  <td class="dataTableContent" align="center">&nbsp;<?php echo $currencies->format($products['products_price']);?>&nbsp;</td>
                  <td class="dataTableContent" align="center">&nbsp;<?php echo tep_draw_pull_down_menu($products['products_id'], $sort_order_drop_array, $products['sort_order']);?>&nbsp;</td>
                </tr>
                <?php
              }
              ?>
				</tbody>
              </table>
             </div>
                <table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBoxContent">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $page, TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
                    <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $page, tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID', 'action'))); ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr class="main">
                <td colspan="6"><table cellpadding="3" cellspacing="0" border="0" width="100%">
                  <tr class="main">
                    <td align="right"><?php echo '<a href="'.tep_href_link(FILENAME_XSELL_PRODUCTS, 'men_id=catalog').'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_update.gif',IMAGE_UPDATE);?></td>
                  </tr>
                </table></td>
              </tr>
            </table></form></td>
          </tr>
          <?php
            // RCI code start
            echo $cre_RCI->get('xsell', 'bottom');
            // RCI code eof
          ?>
        </table>
        <?php
        }
      ?>
  </tr>
</table>
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
