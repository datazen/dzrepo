<?php
/*
  $Id: shopping_cart.php,v 2.1 2008/06/12 00:36:41 datazen Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require("includes/application_top.php");
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);
$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SHOPPING_CART));
// validate cart for checkout
$valid_to_checkout = true;
$cart->get_products(true);
if (!$valid_to_checkout) {
//    $messageStack->add_session('header', 'Please update your order ...', 'error');
//    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
}
if ($cart->count_contents() > 100000){ $cart->reset(); }
$content = CONTENT_SHOPPING_CART;
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>