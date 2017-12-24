<?php
/*
  $Id: paypalxc_shoppingcart_top.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
$ec_enabled = tep_paypal_xc_enabled(); 
if ( $ec_enabled && isset($_GET['error']) && tep_not_null($_GET['error']) ) {
  echo '<div class="messageStackError">' . TEXT_PAYPAL_ERROR_MSG . '<br>' . urldecode($_GET['error']) . '</div>';
}
?>