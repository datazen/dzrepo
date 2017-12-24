<?php
/*
  $Id: product_info.php,v 1.1.1.1 2004/03/04 23:38:02 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

  if (isset($_GET['werror']) && $_GET['werror'] == 1) {
  $error = true;
  if (PRODUCT_INFO_SUB_PRODUCT_ADDCART_TYPE == 'Checkbox') {
    $messageStack->add('cart_quantity', WISHLIST_SUB_PRODUCT_CHECKBOX_ERROR);
  } else {
    $messageStack->add('cart_quantity', WISHLIST_SUB_PRODUCT_INPUT_ERROR);
  }
}



  $product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  $product_check = tep_db_fetch_array($product_check_query);

  $content = CONTENT_PRODUCT_INFO;
  $javascript = 'popup_window.js';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
