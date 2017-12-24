<?php
  /*
  $Id: pm2checkout_help.php,v 1.0 2009/01/27 00:36:41 datazen Exp $

  LoadedCommerce, Commerical Open Source eCommerce
  http://www.loadedcommerce.com

  Copyright (c) 2012 Loaded Commerce
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  */
  
  // pm2checkout_help
  define('PM2CHECKOUT_HELP_TITLE','2Checkout Payment Module Loaded Commerce Help Screen');
  define('PM2CHECKOUT_HELP_MSG_1','2Checkout Payment Module - Loaded Commerce Edition');
  define('PM2CHECKOUT_HELP_MSG_2','Configuration Help Screen');
  define('PM2CHECKOUT_HELP_MSG_3','<a href="https://www.2checkout.com/signup" target="_blank"><h2>Link to Apply for 2Checkout Account</h2></a><br>(Waive your first monthly fee of $19.99 by using our 2Checkout Promo Code below during the signup process!)<br><h1>loaded2checkout</h1>');
  define('PM2CHECKOUT_HELP_MSG_4','<p>Use your browser back button to return to the 2Checkout edit screen.</p><b>Approved / Pending Return URL</b>
    <br>In your 2Checkout account Look and Feel section enter <b>' . HTTP_SERVER . DIR_WS_CATALOG . 'checkout_process.php</b> as the listed URL for both Approved and Pending URL.</p>
    <p><b>Credit Card Test Info</b><br>This is an internal cart test number only.&nbsp; For 2Checkout testing you should use:<br>- Visa: 4111111111111111<br>- Any expiration date after the current date should work. Any CVV code should work.</p>
    <p><b>Enable 2Checkout</b><br>Set to True to enable the 2Checkout payment module.</p><p><b>Seller ID</b><br>Enter your Seller ID used for the 2Checkout service. (provided by 2Checkout)</p>
    <p><b>Transaction Mode</b><br>Select the Transaction mode used for the 2Checkout service.&nbsp; Set to Test for test orders or Production for live orders. </p>
    <p><b>Secret Word</b><br>Enter the secret word that was recorded on your Look and Feel page in your 2Checkout account.</p>
    <p><b>Payment Zone</b><br>If selected, only enable this payment method for that zone.</p>
    <p><b>Set Order Status</b><br>Set the status of orders made with this payment module to this value.</p>
    <p><b>Sort Order</b><br>Select the sort order of display (lowest is displayed first).</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
  <p>If you experience high levels of transaction failure, you may need to adjust your sessions configuration.  Consult your technical support team for more information.</p>');
?>