<?php
/*
  $Id: paypalxc_shoppingcart_logic.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
$ec_enabled = tep_paypal_xc_enabled();
if ( $ec_enabled ) { 
  ?>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr><td align="right" class="main" style="padding-right:65px;"><?php echo MODULE_PAYMENT_PAYPAL_XC_TEXT_OR; ?></td></tr>
          <tr><td align="right"><a href="<?php echo tep_href_link(FILENAME_EC_PROCESS, '', 'SSL'); ?>"><img src="<?php echo MODULE_PAYMENT_PAYPAL_EC_BUTTON_IMG; ?>" border="0"  style="cursor: pointer;"></a></td></tr>
        </table></td>
      </tr>
    </table></td>
  </tr>  
  <?php 
} 
?>