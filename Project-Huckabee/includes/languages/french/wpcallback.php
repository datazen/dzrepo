<?php
/*
  $Id: wpcallback.php,v 2.0 2008/07/17 00:36:41 datazen Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
define('NAVBAR_TITLE', 'WorldPay');
define('WP_TEXT_HEADING', 'Response from WorldPay:');
define('HEADING_TITLE', 'Thank you for shopping with ' . STORE_NAME . ' ...');
define('WP_TEXT_SUCCESS', '<font color="#009933">... your payment was successfully received.</font>');
define('WP_TEXT_SUCCESS_WAIT', '<b><font color="#FF0000">Please wait...</font></b> while we finish processing your order.<br>If you are not automatically re-directed in 10 seconds, please click continue.');
define('WP_TEXT_FAILURE', '<font color="#FF0000">... Your payment has been cancelled!</font>');
define('WP_TEXT_FAILURE_WAIT', '<b><font color="#FF0000">Please wait...</font></b><br>If you are not automatically re-directed in 10 seconds, please click continue.');
define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', 'Continue Checkout Procedure');
define('TEXT_CONTINUE_CHECKOUT_PROCEDURE', 'to select the preferred payment method.');
?>