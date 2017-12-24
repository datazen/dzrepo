<?php
  /*
  $Id: pm2checkout.php,v 1.3 2002/11/18 14:45:23 project3000 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  LoadedCommerce, Commerical Open Source eCommerce
  http://www.loadedcommerce.com

  Copyright (c) 2012 Loaded Commerce
  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Updated 01/24/2010 by Craig Christenson (undeadzed)
  */
  
  define('MODULE_PAYMENT_2CHECKOUT_TEXT_TITLE', '<b>Credit Card via 2Checkout</b>');
  define('MODULE_PAYMENT_2CHECKOUT_TEXT_SUBTITLE', 'Process Credit Card Transaction Securely via 2Checkout.');
  define('MODULE_PAYMENT_2CHECKOUT_TEXT_PUBLIC_TITLE', '2Checkout');
  define('MODULE_PAYMENT_2CHECKOUT_TEXT_PUBLIC_DESCRIPTION', 'Visa, MasterCard, Amex, Discover, JCB, Diners Club, Debit Card, PayPal');
  define('MODULE_PAYMENT_2CHECKOUT_CC_TEXT', "&nbsp;%s%s%s%s%s%s");
  define('MODULE_PAYMENT_2CHECKOUT_TEXT_TYPE', 'Type:');
  define('MODULE_PAYMENT_2CHECKOUT_TEXT_CREDIT_CARD_OWNER', 'Credit Card Owner:');
  define('MODULE_PAYMENT_2CHECKOUT_TEXT_CREDIT_CARD_OWNER_FIRST_NAME', 'Credit Card Owner First Name:');
  define('MODULE_PAYMENT_2CHECKOUT_TEXT_CREDIT_CARD_OWNER_LAST_NAME', 'Credit Card Owner Last Name:');
  define('MODULE_PAYMENT_2CHECKOUT_TEXT_CREDIT_CARD_NUMBER', 'Credit Card Number:');
  define('MODULE_PAYMENT_2CHECKOUT_TEXT_CREDIT_CARD_EXPIRES', 'Credit Card Expiry Date:');
  define('MODULE_PAYMENT_2CHECKOUT_TEXT_CREDIT_CARD_CHECKNUMBER', 'Credit Card Checknumber:');
  define('MODULE_PAYMENT_2CHECKOUT_TEXT_CREDIT_CARD_CHECKNUMBER_LOCATION', '(located at the back of the credit card)');
  define('MODULE_PAYMENT_2CHECKOUT_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_2CHECKOUT_TEXT_ERROR_MESSAGE', 'There has been an error processing your credit card. Please try again.');
  define('MODULE_PAYMENT_2CHECKOUT_TEXT_ERROR_HASH_MESSAGE', 'Your payment seems to come from other site then 2CheckOut . Please do not continue the checkout procedure AND contact us!');  
  define('MODULE_PAYMENT_2CHECKOUT_CURRENCY_CONVERSITION', ' - Prices will be converted to US Dollars on confirmation.');
  $enabled = (defined('MODULE_PAYMENT_2CHECKOUT_STATUS') && MODULE_PAYMENT_2CHECKOUT_STATUS == 'True') ? true : false; 
  if ($enabled === true) {
    $text = '<p>Credit Card Test Info:<br><br>CC#: 4111111111111111<br>Expiry: Any<br /><br /><b>Approved/Pending URL:</b><br />' . HTTP_SERVER . DIR_WS_CATALOG . 'checkout_process.php</p>';
  }
  if ((defined('MODULE_PAYMENT_2CHECKOUT_LOGIN') && MODULE_PAYMENT_2CHECKOUT_LOGIN != '') && (defined('MODULE_PAYMENT_2CHECKOUT_SECRET_WORD') && MODULE_PAYMENT_2CHECKOUT_SECRET_WORD != '')) {
    define('MODULE_PAYMENT_2CHECKOUT_TEXT_DESCRIPTION', '<p><a href=\"' . tep_href_link('pm2checkout_help.php', '', 'NONSSL') . '\" style=\"color:#0033cc\">[Setup Help]</a></p><p>' . $text . '</p>');
  } else {
    define('MODULE_PAYMENT_2CHECKOUT_TEXT_DESCRIPTION', '<p>Waive your first monthly fee of $19.99 using our promo code below during the signup process!<h2 style="text-align:center;margin-top:0px;">"loaded2checkout"</h2></p><p><img src="images/icon_popup.gif" border="0">&nbsp;<a href="https://www.2checkout.com/signup" target="_blank" style="text-decoration: underline; font-weight: bold;">Signup at 2Checkout Now!</a>&nbsp;&nbsp;<a href=\"' . tep_href_link('pm2checkout_help.php', '', 'NONSSL') . '\" style=\"color:#0033cc\">[Setup Help]</a></p><p>' . $text . '</p>');
  }
  define('MODULE_PAYMENT_2CHECKOUT_TEXT_WARNING_DEMO_MODE', 'In Review: Transaction performed in demo mode.');
  define('MODULE_PAYMENT_2CHECKOUT_TEXT_WARNING_TRANSACTION_ORDER', 'In Review: Transaction total did not match order total.');
?>