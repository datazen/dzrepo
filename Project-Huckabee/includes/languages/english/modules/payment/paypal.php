<?php
/*
  $Id: paypal.php,v 2.1 2008/06/12 00:36:41 datazen Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// PP WPS
define('MODULE_PAYMENT_PAYPAL_TEXT_TITLE', '<strong>Pay with PayPal</strong>');
define('MODULE_PAYMENT_PAYPAL_TEXT_SUBTITLE', 'Accept Credit Cards via PayPal Express Checkout or Website Payments Standard.');
define('MODULE_PAYMENT_PAYPAL_TEXT_DESCRIPTION', '<b>PayPal</b><br><br>PayPal is a fast, affordable way to start accepting credit cards and PayPal payments online. Your buyers pay on secure PayPal pages, but do not need a PayPal account to pay you.');
define('MODULE_PAYMENT_PAYPAL_CC_TEXT', "Credit Card&nbsp;%s%s%s%s&nbsp;or&nbsp;%s");
define('MODULE_PAYMENT_PAYPAL_IMAGE_BUTTON_CHECKOUT', 'PayPal Checkout');
define('MODULE_PAYMENT_PAYPAL_CC_DESCRIPTION','You do not need to be a PayPal member to pay by credit card');
define('MODULE_PAYMENT_PAYPAL_CC_URL_TEXT','<font color="blue"><u>[info]</u></font>');
define('MODULE_PAYMENT_PAYPAL_CUSTOMER_COMMENTS', 'Add Comments About Your Order');
define('MODULE_PAYMENT_PAYPAL_TEXT_TITLE_PROCESSING', 'Processing transaction');
define('MODULE_PAYMENT_PAYPAL_TEXT_DESCRIPTION_PROCESSING', 'If this page appears for more than 5 seconds, please click the PayPal Checkout button to complete your order.');

include_once('paypal_xc.php');
?>