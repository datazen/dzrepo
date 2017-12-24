<?php
/*
  $Id: worldpay_junior.php,v 2.0 2008/07/17 00:36:41 datazen Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
define('MODULE_PAYMENT_WORLDPAY_JUNIOR_TEXT_TITLE', 'Credit Card : WorldPay Junior');
$inst_id = (defined('MODULE_PAYMENT_WORLDPAY_JUNIOR_INSTALLATION_ID')) ? MODULE_PAYMENT_WORLDPAY_JUNIOR_INSTALLATION_ID : '';
define('MODULE_PAYMENT_WORLDPAY_JUNIOR_TEXT_PUBLIC_TITLE', 'Kreditkarte : WorldPay <script language="JavaScript" src="https://select.worldpay.com/wcc/logo?instId=' . $inst_id . '"></script>');  
define('MODULE_PAYMENT_WORLDPAY_JUNIOR_TEXT_DESCRIPTION', '<img src="images/icon_popup.gif" border="0">&nbsp;<a href="http://www.worldpay.com" target="_blank" style="text-decoration: underline; font-weight: bold;">WorldPay Webseite besuchen</a>&nbsp;&nbsp;<a href=\"' . tep_href_link(FILENAME_WPJUNIOR_HELP, '', 'NONSSL') . '\" style=\"color:#0033cc\">[Einstellungs-Hilfe]</a>');
define('MODULE_PAYMENT_WORLDPAY_JUNIOR_TEXT_WARNING_DEMO_MODE', 'Im Bericht: Verhandlung führte im Demomodus durch.');
define('MODULE_PAYMENT_WORLDPAY_JUNIOR_TEXT_SUCCESSFUL_TRANSACTION', 'Die Zahlungsverhandlung ist erfolgreich durchgeführt worden!');
define('MODULE_PAYMENT_WORLDPAY_JUNIOR_TEXT_CONTINUE_BUTTON', 'Klicken Sie hier, um zu fortzufahren %s');
?>