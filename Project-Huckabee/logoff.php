<?php
/*
  $Id: logoff.php,v 1.1.1.1 2004/03/04 23:38:00 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGOFF);

  $breadcrumb->add(NAVBAR_TITLE);

  unset($_SESSION['customer_id']);
  unset($_SESSION['customer_default_address_id']);
  unset($_SESSION['customer_first_name']);
  unset($_SESSION['customer_country_id']);
  unset($_SESSION['customer_zone_id']);
  unset($_SESSION['comments']);
  // Points/Rewards system V2.00 BOF
  unset($_SESSION['customer_shopping_points']);
  unset($_SESSION['customer_shopping_points_spending']);
  unset($_SESSION['customer_referral']);
  if (isset($_SESSION['point_covers'])) unset($_SESSION['point_covers']);
  // Points/Rewards system V2.00 EOF
  // unset the special session variable used by the shipping estimator
  if (isset($_SESSION['cart_address_id'])) unset($_SESSION['cart_address_id']);
  if (isset($_SESSION['cart_country_id'])) unset($_SESSION['cart_country_id']);
  if (isset($_SESSION['cart_zip_code'])) unset($_SESSION['cart_zip_code']);
  // unset the special variables used by the shipping and payment routines
  if (isset($_SESSION['sendto'])) unset($_SESSION['sendto']);
  if (isset($_SESSION['billto'])) unset($_SESSION['billto']);
  if (isset($_SESSION['cartID'])) unset($_SESSION['cartID']);
  if (isset($_SESSION['shipping'])) unset($_SESSION['shipping']);
  if (isset($_SESSION['payment'])) unset($_SESSION['payment']);
  if (isset($_SESSION['admin_login'])) unset($_SESSION['admin_login']);
  // ICW - logout -> unregister GIFT VOUCHER sessions - Thanks Fredrik
  unset($_SESSION['gv_id']);
  unset($_SESSION['cc_id']);
  // ICW - logout -> unregister GIFT VOUCHER sessions  - Thanks Fredrik
  $cart->reset();

  $content = CONTENT_LOGOFF;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
