<?php
/*
  $Id: paypalxc_checkoutpayment_paymentmodule.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
global $payment_modules, $payment;

$ec_enabled = tep_paypal_xc_enabled(); 
$dp_enabled = (defined('MODULE_PAYMENT_PAYPAL_STATUS') && MODULE_PAYMENT_PAYPAL_STATUS == 'True' && MODULE_PAYMENT_PAYPAL_SERVICE == 'Website Payments Pro') ? true : false;

$modules_count = tep_count_payment_modules();
if ($dp_enabled) $modules_count++;
?>
<tr>
  <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
    <tr class="infoBoxContents">
      <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <?php
        $selection = $payment_modules->selection();
        ?>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
          <td class="main" width="100%" colspan="2"><?php echo TEXT_ENTER_PAYMENT_INFORMATION; ?></td>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
        </tr>
        <?php
        $radio_buttons = 0;
        for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
          ?>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <?php
              if ( ($selection[$i]['id'] == $payment) || ($n == 1) ) {
                echo '<tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
              } else {
                echo '<tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
              }
              ?>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" colspan="3"><b><?php echo $selection[$i]['module']; ?></b></td>
                <td class="main" align="right">
                  <?php
                  if (sizeof($selection) > 1 || $ec_enabled || $dp_enabled ) {
                    echo tep_draw_radio_field('payment', $selection[$i]['id']);
                  } else {
                    echo tep_draw_hidden_field('payment', $selection[$i]['id']);
                  }
                  ?>
                </td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
              <?php
              if (isset($selection[$i]['error'])) {
                ?>
                <tr>
                  <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  <td class="main" colspan="4"><?php echo $selection[$i]['error']; ?></td>
                  <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                 </tr>
                <?php
              } elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) {
                ?>
                <tr>
                  <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  <td colspan="4"><table border="0" cellspacing="0" cellpadding="2">
                    <?php
                    for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) { 
                      ?>
                      <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main"><?php echo $selection[$i]['fields'][$j]['title']; ?></td>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main"><?php echo $selection[$i]['fields'][$j]['field']; ?></td>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      </tr>
                      <?php
                    }
                    ?>
                  </table></td>
                  <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                </tr>
                <?php
              }
              ?>
            </table></td>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
          </tr>
          <?php
          $radio_buttons++;
        }
        if ( $ec_enabled ) {
          ?>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <?php
              echo '<tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";    
                ?>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" colspan="3"><table><tr><td><b><?php echo MODULE_PAYMENT_PAYPAL_XC_TEXT_TITLE; ?></b></td><td><img src="<?php echo MODULE_PAYMENT_PAYPAL_XC_MARK_IMG; ?>"></td><td><?php echo MODULE_PAYMENT_PAYPAL_XC_LEARN_MORE_LINK; ?></td></tr></table></td>
                <td class="main" align="right">
                  <?php 
                  if ($radio_buttons > 0 ) {
                    echo tep_draw_radio_field('payment', 'paypal_xc'); 
                  } else {
                    echo tep_draw_hidden_field('payment', 'paypal_xc');
                  }
                  ?>
                </td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
          </tr>
          <?php
        } else if ( $dp_enabled ) {
          ?>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <?php
              echo '<tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";   
              ?>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              <td class="main" colspan="3"><table><tr><td><b><?php echo MODULE_PAYMENT_PAYPAL_DP_TEXT_TITLE; ?></b></td><td><img src="<?php echo MODULE_PAYMENT_PAYPAL_XC_MARK_IMG; ?>"></td><td><?php echo MODULE_PAYMENT_PAYPAL_XC_LEARN_MORE_LINK; ?></td></tr></table></td>
              <td class="main" align="right">
                <?php 
                if ($radio_buttons > 0 ) {
                  echo tep_draw_radio_field('payment', 'paypal_wpp_dp'); 
                } else {
                  echo tep_draw_hidden_field('payment', 'paypal_wpp_dp');
                }
                ?>
              </td>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            </tr>
            </table></td>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
          </tr>
          <?php         
        }
        ?>
      </table></td>
    </tr>
  </table></td>
</tr>