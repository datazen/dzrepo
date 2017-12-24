<?php
/*
  $Id: wpjunior_help.php,v 1.0 2009/01/27 00:36:41 datazen Exp $  

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// wpjunior_help
define('WPJUNIOR_HELP_TITLE','WorldPay Junior Payment Module CRE Help Screen');
define('WPJUNIOR_HELP_MSG_1','WorldPay Junior Payment Module - CRE Edition');
define('WPJUNIOR_HELP_MSG_2','Configuration Help Screen');
define('WPJUNIOR_HELP_MSG_3','<a href="http://www.worldpay.com/" target="_blank"><h2>Link to Apply for WorldPay Junior Account</h2></a>');
define('WPJUNIOR_HELP_MSG_4','<p>Use your browser back button to return to the WorldPay edit screen.</p><p>The WorldPay Select Junior payment module supports the following WorldPay features:<br>&nbsp; • Payment response Password<br>&nbsp; • MD5 Transaction Password</p>
<p>The WorldPay Select Junior payment module requires account settings to be defined on the WorldPay Customer Management System in order to function correctly.</p><p><u><b>WorldPay Customer Management System Settings</b></u></p>
<p><i><font color="#FF0000">The following settings are found in the WorldPay Customer Management System -&gt; System settings -&gt; Installation Account -&gt; Integration Setup section and must be defined in order to function correctly.</font></i></p>
<p><b>Payment Response URL</b><br>This value must be set to either of the following values depending on your server type.<br>&nbsp; • For Non-SSL (HTTP) Webservers:&nbsp; <b><font color="#0000FF">http://&lt;WPDISPLAY ITEM=MC_callback&gt;</font></b><br>
&nbsp; • For SSL (HTTPS) Webservers:&nbsp; <b><font color="#0000FF">https://&lt;WPDISPLAY ITEM=MC_callback&gt;</font></b><br>Use the first value if you are not sure if your webserver supports SSL (HTTPS) connections.</p>
<p><b>Payment Response enabled?</b><br>This option must be checked to enable payment responses.</p><p><b>Enable the Shopper Response?</b><br>This option must be checked to enable shopper responses..</p><p>&nbsp;</p><p><u><b>Configuration Settings</b></u></p>
<p><i><font color="#FF0000">The following settings are found in the CRE Loaded Admin -&gt; Modules -&gt; Payment -&gt; WorldPay Junior section and must be defined in order to function correctly.</font></i></p><b>Enable WorldPay Select Junior</b><br>Setting this parameter to True makes the payment method available to customers during the checkout procedure.<p><b>Installation ID</b><br>The WorldPay Installation ID to assign transactions to (supplied by WorldPay).</p><p><b>Payment Response Password</b><br>The 
Payment Response Password to verify callback responses with. This value must match the value provided in the WorldPay Customer Management System -&gt; System Setetings -&gt; Installation Account -&gt; Integration Setup -&gt; Payment Response password field..</p><p><b>MD5 Password</b><br>The MD5 Password to verify transactions and callback responses with. This value must match the value provided in the WorldPay Customer Management System -&gt; System Setetings -&gt; Installation Account -&gt; Integration Setup -&gt;&nbsp; MD5 
secret for transactions field. </p><p><b>Transaction Method</b><br>The transaction method to use for payment transactions.&nbsp; The Pre-Authorization method only authorizes the transaction and must be captured through the WorldPay Merchant Interface site.&nbsp; The Capture method instantly transfers the funds to your account..</p><p><b>Test Mode</b><br>Defines if transactions should be processed in test mode (true) or in production mode (false).&nbsp; Test Credit Card number can be found on the WorldPay website at 
http://www.worldpay.com/support/kb/mergedProjects/htmlredirect/rhtml5208.html</p><p><b>Payment Zone</b><br>If set, this payment method will only be available to orders made within the defined zone.</p><p><b>Set Preparing Order Status</b><br>The customers order is saved in the database on the checkout confirmation page before the customer is forwarded to WorldPay to finalize the payment transaction. The order is saved in the database with this defined order status; by default it is Preparing [WorldPay].&nbsp; The order status is updated again when the customer finalizes the payment transaction at WorldPay and returns to the store with the link provided by WorldPay. The order status is also 
updated when the response call from WorldPay is received. If the customer does not finalize the payment transaction at WorldPay, the order remains in the database with this order status and can be removed after a period of time.</p><p><b>Set Order Status</b><br>The orders status will be updated to this value when the response call from WorldPay is received and the order has been verified..</p><p><b>Sort Order</b><br>The position to show the payment method on the checkout payment page against other available payment methods. (lowest is displayed first).</p><p>&nbsp;</p><p></p><p>If you experience high levels of transaction failure, you may need to adjust your sessions configuration.  Consult your technical support team for more information</p>');
?>