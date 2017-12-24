<?php
/*
  $Id: checkout_confirmation.tpl.php,v 1.0.0.0 2008/01/16 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('checkoutconfirmation', 'top');
// RCI code eof
?>
<div class="row">
  <div class="col-sm-12 col-lg-12 large-margin-bottom">
  <h1 class="no-margin-top">   <?php echo HEADING_TITLE; ?></h1>


        <?php
        if ($_SESSION['sendto'] != false) {
          ?>

              <?php
              // RCO start
             /* if ($cre_RCO->get('checkoutconfirmation', 'editdeliveryaddresslink') !== true) {*/
              /*  echo '<div><h4>' . HEADING_DELIVERY_ADDRESS . '</h4> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a><div>' . "\n";*/
           /*   }
              // RCO end
             */ ?>
            <div class="clearfix panel panel-default no-margin-bottom">
             <div onclick="window.location.href='<?php echo tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') ;?>'" class="panel-heading cursor-pointer">
          <h3 class="no-margin-top no-margin-bottom">Payment Information</h3>
        </div>
      </div>
    <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="no-margin-top no-margin-bottom"><?php echo HEADING_TITLE; ?></h3>
          </div>
        <div class="panel-body no-padding-bottom">
    <div class="col-sm-6 col-lg-6">
        <div class="well relative no-padding-bottom" style="min-height: 260px;">
        <?php    echo '<h4 class="no-margin-top"><b>' . HEADING_DELIVERY_ADDRESS . '</b></h4> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '"><div class="btn-group clearfix absolute-top-right small-padding-right small-padding-top"><h4></h4><button class="btn btn-default btn-xs">' . TEXT_EDIT . '</button></div></a>' . "\n";?>

              <?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>'); ?>
              		 <h4></h4>

            <?php
            if ($order->info['shipping_method']) {
              ?>
                <?php
                // RCO start
                if ($cre_RCO->get('checkoutconfirmation', 'editshippingmethodlink') !== true) {
                  echo '<h3>' . HEADING_SHIPPING_METHOD . '</h3> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>' . "\n";
                }
                // RCO end
                ?>


               <?php echo $order->info['shipping_method']; ?>

              <?php
            }
            ?>

          <?php
        }
        ?>

       </div>
    </div>


      <div class="col-sm-6 col-lg-6">
        <div class="well relative no-padding-bottom" style="min-height: 260px;">
		  <h4 class="no-margin-top"><b><?php echo HEADING_BILLING_INFORMATION; ?></b></h4><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '"><div class="btn-group clearfix absolute-top-right small-padding-right small-padding-top"><h4></h4><button class="btn btn-default btn-xs">' . TEXT_EDIT . '</button></div></a>' . "\n";?>

				<?php
				// RCO start
				if ($cre_RCO->get('checkoutconfirmation', 'editbillingaddresslink') !== true) {
				  echo '<td class="main"><b>' . HEADING_BILLING_ADDRESS . '</b><br> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a></td>' . "\n";
				}
				// RCO end
				?>

			<br>
				<?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>'); ?>
			<?php	  echo '<h4 class="no-margin-top"><b>'  . HEADING_PAYMENT_METHOD . '</b></h4>';?>

          <?php echo $order->info['payment_method']; ?>

		</div>
	  </div>
			<div class="col-sm-12 col-lg-12">
			<div class="well">

               <table class="table responsive-table no-margin-bottom">
                    <thead>
              <?php
              if (sizeof($order->info['tax_groups']) > 1) {
                ?>
                <tr>
                  <?php
                  // RCO start
                  if ($cre_RCO->get('checkoutconfirmation', 'editproductslink') !== true) {
                    echo ' <th colspan="2"><h4 class="no-margin-top"><b>' . HEADING_PRODUCTS . '</b></h4> <a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><div class="btn-group clearfix absolute-top-right small-padding-right small-padding-top"><button class="btn btn-default btn-xs">' . TEXT_EDIT . '</button></div></a></th>' . "\n";
                  }
                  // RCO end
                  ?>
                  <th class="text-right"><b><?php echo HEADING_TAX; ?></b></th>
                  <th class="text-right"><b><?php echo HEADING_TOTAL; ?></b></th>
                </tr>
                <?php
              } else {
                ?>
                <tr>
                  <?php
                  // RCO start
                  if ($cre_RCO->get('checkoutconfirmation', 'editproductslink') !== true) {
                    echo ' <th colspan="2"><h4 class="no-margin-top"><b>' . HEADING_PRODUCTS . '</b></h4> <a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><div class="btn-group clearfix absolute-top-right-large-padding"><button class="btn btn-default btn-xs">' . TEXT_EDIT . '</button></div></a></th>' . "\n";
                  }
                  // RCO end
                  ?>
                </tr>

                <?php
              }?>

              </thead>
           <?php   for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
                echo '          <tr class="confirmation-products-listing-row">' . "\n" .
                     '            <td class="content-checkout-confirmation-qty-td">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
                     '            <td><span class="text-info strong">' . $order->products[$i]['name'] .'</span>';
                if (STOCK_CHECK == 'true') {
                  echo tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
                }
                if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
                  for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
                    echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . ' ' . $order->products[$i]['attributes'][$j]['prefix'] . ' ' . $currencies->display_price($order->products[$i]['attributes'][$j]['price'], tep_get_tax_rate($products[$i]['tax_class_id']), 1)  . '</i></small></nobr>';
                  }
                }
                echo '            </td>' . "\n";
                if (sizeof($order->info['tax_groups']) > 1) echo '            <td class="text-right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";
                echo '          <td class="text-right">' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . '</td>' . "\n" .
                     '          </tr>' . "\n";
              }
              ?>
            </table>
       		  <table class="table margin-bottom-neg"><tr><td>&nbsp;</td></tr></table>
              <div class="row padding-right" id="content-checkout-confirmation-order-totals">
                <div id="content-checkout-confirmation-order-totals-left" class="col-sm-5 col-lg-5"></div>
                <div id="content-checkout-confirmation-order-totals-right" class="col-sm-7 col-lg-7">
				<div class="clearfix">
					<table class="table" width="100%">
					  <?php
					  if (MODULE_ORDER_TOTAL_INSTALLED) {
						$order_total_modules->process();
						echo '<div class="clearfix"><span class="pull-left ot-total">' . $order_total_modules->output() .'</span></div>';
					  }
					  // RCI code start
					  echo $cre_RCI->get('checkoutconfirmation', 'display');
					  // RCI code eof
					  ?>
					</table>
           </div>
          </div>
         </div>
        </div>
       </div>
         			   <div class="col-sm-12 col-lg-12">

			  <?php
			  if (is_array($payment_modules->modules)) {
				if ($confirmation = $payment_modules->confirmation()) {
				  $payment_info = $confirmation['title'];
				  if (!isset($_SESSION['payment_info'])) $_SESSION['payment_info'] = $payment_info;
				  ?>
		 				 <div class="well relative no-padding-bottom">

				   <h4 class="no-margin-top"><b><?php echo HEADING_PAYMENT_INFORMATION; ?></b></h4>
				 <br>
				 <?php echo $confirmation['title']; ?><h4></h4>
              <?php
              for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
                ?>

                  <?php echo $confirmation['fields'][$i]['title']; ?>
                  <?php echo $confirmation['fields'][$i]['field']; ?>

                <?php
              }?>
			 </div>

		<?php	}
		  }
		  ?>
					</div>

  <?php
  if (tep_not_null($order->info['comments'])) {
    ?>
<div class="col-sm-12 col-lg-12">
<div class="well">
      <?php echo '<b>' . HEADING_ORDER_COMMENTS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><div class="btn-group clearfix absolute-top-right large-padding-right small-padding-top"><button class="btn btn-default btn-xs">' . TEXT_EDIT . '</button></div></a>'; ?>
             <br /><?php echo nl2br(tep_output_string_protected($order->info['comments'])) . tep_draw_hidden_field('comments', $order->info['comments']); ?>
 </div>
</div>
<?php
	  }
	  ?>
          <?php
          // added for PPSM
          $process_button_string = '';
          if (isset($_POST['payment']) && $_POST['payment'] == 'paypal_wpp_dp') {
            $process_button_string = process_dp_button();
            if (defined('MODULE_PAYMENT_CRESECURE_TEST_MODE') && MODULE_PAYMENT_CRESECURE_TEST_MODE == 'True') {
              //$this->form_action_url = 'https://dev-cresecure.net/securepayments/a1/cc_collection.php';  // cre only internal test url
              $form_action_url = 'https://sandbox-cresecure.net/securepayments/a1/cc_collection.php';  // sandbox url
            } else {
              $form_action_url = 'https://cresecure.net/securepayments/a1/cc_collection.php';  // production url
            }
          } else if (isset($$payment->form_action_url)) {
            $form_action_url = $$payment->form_action_url;
          } else {
            $form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
          }
          if (ACCOUNT_CONDITIONS_REQUIRED == 'false' ) {
            echo tep_draw_form('checkout_confirmation', $form_action_url, 'post','enctype="multipart/form-data"');
          } else {
            echo tep_draw_form('checkout_confirmation', $form_action_url, 'post','onsubmit="return checkCheckBox(this)" enctype="multipart/form-data"');
          }
          // added for PPSM
          if (isset($_POST['payment']) && $_POST['payment'] == 'paypal_wpp_dp') {
            echo $process_button_string;
          } else if (is_array($payment_modules->modules)) {
            echo $payment_modules->process_button();
          }
          ?>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <?php
            //RCI start
            echo $cre_RCI->get('checkoutconfirmation', 'insideformabovebuttons');
            if (ACCOUNT_CONDITIONS_REQUIRED == 'true') {
              ?>

                <?php echo CONDITION_AGREEMENT; ?> <input type="checkbox" value="0" name="agree">

              <?php
            }
            ?>
              <div class="col-sm-12 col-lg-12 large-margin-bottom"><div class="well relative no-padding-bottom">

             <?php echo '<h4 class="no-margin-top"><b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE .'&nbsp;' . TEXT_CONTINUE_CHECKOUT_PROCEDURE . '</b></h4>&nbsp;&nbsp;'  ; ?>
             <?php
                echo HEADING_IPRECORDED_1;
                $ip_iprecorded = YOUR_IP_IPRECORDED;
                $isp_iprecorded = YOUR_ISP_IPRECORDED;
                $ip = $_SERVER["REMOTE_ADDR"];
                $client = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
                $str = preg_split("/\./", $client);
                $i = count($str);
                $x = $i - 1;
                $n = $i - 2;
                $isp = $str[$n] . "." . $str[$x];
                echo "<div align=\"justify\">&nbsp;&nbsp;&nbsp;<font size=\".1\">$ip_iprecorded: $ip<br>&nbsp;&nbsp;&nbsp;$isp_iprecorded: $isp</div><h4></h4>";
                ?>
				</div>
			  </div>
            <?php
            //RCI start
            echo $cre_RCI->get('checkoutconfirmation', 'insideformbelowbuttons');
            ?>
            <?php   /*echo   '<div class="btn-set clearfix">' . tep_template_image_submit('button_confirm_order.gif', IMAGE_BUTTON_CONFIRM_ORDER) . '</div>' . "\n"*/;?>
   </div>
  </div>
 </div>
     <div class="btn-set small-margin-top clearfix">
       <button class="pull-right btn btn-lg btn-primary" type="submit"><?php echo IMAGE_BUTTON_CONTINUE; ?></button>
      </div>
  </form>

  <?php
  //RCI start
  echo $cre_RCI->get('checkoutconfirmation', 'menu');
  //RCI end
  // RCO start
  if ($cre_RCO->get('checkoutconfirmation', 'checkoutbar') !== true) {
    ?>
  <?php/*  <tr>
      <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td width="50%" align="right"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
              <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            </tr>
          </table></td>
          <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
          <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              <td><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
              <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            </tr>
          </table></td>
          <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_DELIVERY . '</a>'; ?></td>
          <td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_PAYMENT . '</a>'; ?></td>
          <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
          <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
        </tr>*/?>
      </table></td>
    </tr>

 <?php
  }
  // RCO end
  ?>
 </div>
</div>
 </div>
</div>
<?php
// RCI code start
echo $cre_RCI->get('checkoutconfirmation', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>