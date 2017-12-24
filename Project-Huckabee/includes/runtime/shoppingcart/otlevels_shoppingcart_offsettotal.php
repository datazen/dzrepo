<?php
  /*
  $Id: otlevels_shoppingcart_offsettotal.php,v 1.0.0.0 2007/09/04 13:41:11 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  */
  global $offset_amount, $currencies, $cart;
  if (defined('MODULE_LEV_DISCOUNT_STATUS') &&  MODULE_LEV_DISCOUNT_STATUS == true) {
    $rci = '';
    include_once(DIR_WS_MODULES . 'order_total/ot_lev_discount.php');
    $ot_lev_discount = new ot_lev_discount;
    $od_amount = $ot_lev_discount->calculate_credit($cart->show_total());
    
    $parts = explode(".", $od_amount);
    $od_amount = $parts[0] . '.' . substr($parts[1], 0, 2);
    
    if ($od_amount > 0) {
      $offset_amount = $offset_amount - $od_amount;
      $rci .= '    <tr>' . "\n";
      $rci .= '      <td class="main" align="right"><b style="color:red;">Price Break Discount:</b></td>' . "\n";
      $rci .= '      <td class="main" align="right"><b style="color:red;">-' . $currencies->format($od_amount) . '</b></td>' . "\n";
      $rci .= '    </tr>' . "\n";
    }
    return $rci; 
  } 
?>