<?php
/*
  $Id: paypalxc_english_lang.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
$module_enabled = (defined('MODULE_PAYMENT_PAYPAL_STATUS') && MODULE_PAYMENT_PAYPAL_STATUS == 'True' && MODULE_PAYMENT_PAYPAL_SERVICE == 'Express Checkout') ? true : false;
if ( $module_enabled ) {
  define('TEXT_CREATE_ACCOUNT', 'Create an account');
  define('EMAIL_TEXT_PASSWORD', 'Your password: ');
  define('TEXT_PAYPAL_REFUND', 'PayPal Refund');
  define('TEXT_PAYPAL_SINGIN_TITLE', 'Sign In with Your PayPal Account');
  define('TEXT_PAYPAL_ERROR_MSG', 'Error occurred, paypal checkout can\'t continue');
  define('TEXT_PAYPAL_EXPLAIN', 'New customers login with your paypal account for fast checkout. Existing customer please proceed to normal checkout.');
  define('MODULE_PAYMENT_PAYPAL_XC_TEXT_OR', '-OR-');
}
?>