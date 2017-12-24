<?php
/*
  $Id: account_history_info.tpl.php,v 1.0 20090/04/06 23:38:03 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI top start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('accounthistoryinfo', 'top');
?>
<div class="row">
  <div class="col-sm-12 col-lg-12 large-margin-bottom">
  <h1 class="no-margin-top">   <?php echo HEADING_TITLE; ?></h1>
      <form role="form" class="no-margin-bottom">
        <table class="table tabled-striped table-responsive no-margin-bottom" id="shopping-cart-table">
          <thead>
            <tr>
              <?php
              if (sizeof($order->info['tax_groups']) > 1) {
                ?>

                 <th><b><?php echo HEADING_PRODUCTS; ?></th>
                 <th class="text-left hide-on-mobile-portrait"></th>

                  <th class="text-right"<b><?php echo HEADING_TAX; ?></b></th>
                 <th class="text-right"<b><?php echo HEADING_TOTAL; ?></b></th>

                <?php
              } else {
                ?>

                 <th colspan="2" class="content-receipt-title"><b><?php echo HEADING_PRODUCTS; ?></th>
                 <th class="text-right hide-on-mobile-portrait"<b><?php echo HEADING_PRODUCTS_BASE_PRICE; ?></b></th>
                 <th class="text-right"<b><?php echo HEADING_PRODUCTS_FINAL_PRICE; ?></b></th>
            </tr>
          </thead>
    		  <tbody>
                          <?php
		                }
		                for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
		                  echo '          <tr>' . "\n" .
		                       '            <td class="products-listing-separator">' . $order->products[$i]['qty'] . '&nbsp;x</td>' .
		                       '          <td>  ' . $order->products[$i]['name'] .'';
		                  echo '<br><b>' . HEADING_OPTIONS  . '</b></small></td>';
		                  //check for attibutes:
		                  $attributes_check_query = tep_db_query("SELECT *
		                                                            from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . "
		                                                          WHERE orders_id = '" .(int)$_GET['order_id'] . "'
		                                                            and orders_products_id = '" . $order->products[$i]['orders_products_id'] . "' ");
		                  if (tep_db_num_rows($attributes_check_query)) {
		                    while ($attributes = tep_db_fetch_array($attributes_check_query)) {
		                      echo '<br><small><i> *' . $attributes['products_options'] . ' : ' . $attributes['products_options_values'] . '</i></small>';
		                      echo '<br><small> ' . $attributes['price_prefix'] . ' ' . $currencies->display_price($attributes['options_values_price'], tep_get_tax_rate($order->products[$i]['tax_class_id']), 1) . '</small>';
		                    }
		                  }
		                  // Begin RMA Returns System
		                  $return_link = '';
		                  if ($order->products[$i]['return'] == '1') {
		                    $rma_query_one = tep_db_query("SELECT returns_id FROM " . TABLE_RETURNS_PRODUCTS_DATA . " where products_id = '" . $order->products[$i]['id'] . "' and order_id = '" . $_GET['order_id'] . "'");
		                    while($rma_query = tep_db_fetch_array($rma_query_one)) {
		                      $rma_number_query = tep_db_query("SELECT rma_value FROM " . TABLE_RETURNS . " where returns_id = '" . $rma_query['returns_id'] . "'");
		                      $rma_result = tep_db_fetch_array($rma_number_query);
		                      $return_link .= '<b>' . TEXT_RMA . ' #&nbsp;<u><a href="' . tep_href_link(FILENAME_RETURNS_TRACK, 'action=returns_show&rma=' . $rma_result['rma_value'], 'NONSSL') . '">' . $rma_result['rma_value'] . '</a></u></b>';
		                    }
		                  }
		                  if (defined('DISPLAY_RMA_LINK') && DISPLAY_RMA_LINK == 'true') {
		                    $return_link .= '<a href="' . tep_href_link(FILENAME_RETURN, 'order_id=' . $_GET['order_id'] . '&product_id=' . ($order->products[$i]['id']), 'NONSSL') . '"><b><u>' . TEXT_RETURN_PRODUCT .'</a></u></b>';
		                  }
		                  // Don't show Return link if order is still pending or processing
		                  // You can change this or comment it out as best fits your store configuration
		                  if (($order->info['orders_status'] == 'Pending') OR ($order->info['orders_status'] == 'Processing')) {
		                    $return_link = '';
		                  }
		                  echo $return_link  . "\n";
		                  // End RMA Returns System
		                  echo '</td>' . "\n";
		                  echo '</td><td class="text-right hide-on-mobile-portrait content-shopping-cart-price-td">' .  $currencies->display_price($order->products[$i]['price'], (isset($products[$i]['tax_class_id']) ? tep_get_tax_rate($products[$i]['tax_class_id']) : 0), 1) . '</span></td>' ;
		                  if (sizeof($order->info['tax_groups']) > 1) {
		                    echo '<td class="text-right"><span class="price">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";
		                  }
		                  echo '<td class="text-right"><span class="price">' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</span></td>' .
		                       '</tr>' . "\n";
		                }
		                ?>
					</tbody>
					  <tfoot>
						<tr>
						  <td></td>
						  <td class="hide-on-mobile-portrait"></td>
						  <td class="hide-on-mobile-portrait"></td>
						  <td colspan="3"></td>

						</tr>
					  </tfoot>



			</table>
	    </form>

					  <?php
					  for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {
						echo '<div class="text-right margin-bottom">' . "\n" .
							 '<span class="">' . $order->totals[$i]['title'] . '' . "\n" .
							 '' . $order->totals[$i]['text'] . '</span>' . "\n" .
							 '</div>' . "\n";
					  }
					  ?>
<div class="row">
<div class="col-sm-6 col-lg-6">
 <div class="well relative no-padding-bottom">
      <b><?php echo sprintf(HEADING_ORDER_NUMBER, $_GET['order_id']) . ' <small>(' . $order->info['orders_status'] . ')</small>'; ?></b>


       <br><?php echo HEADING_ORDER_DATE . ' ' . tep_date_long($order->info['date_purchased']); ?><br>
        <h4></h4>

 </div>
</div>
<div class="col-sm-6 col-lg-6">
 <div class="well relative no-padding-bottom">
       <b><?php echo HEADING_ORDER_TOTAL . ' ' . $order->info['total']; ?></b>
        <h4></h4>

  </div>
 </div>
</div>

<div class="row">
 <div class="col-sm-6 col-lg-6">
        <div class="well relative no-padding-bottom">

        <?php
        if ($order->delivery != false) {
          ?>

			 <h4 class="no-margin-top"><b>Delivery Information</b></h4>
             <h4 class="no-margin-top"><?php echo HEADING_DELIVERY_ADDRESS; ?></h4>


             <?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>'); ?>

            <?php
            if (tep_not_null($order->info['shipping_method'])) {
              ?>

               <h4 class="no-margin-top"><?php echo HEADING_SHIPPING_METHOD; ?></h4>


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
	        <div class="well">
			   <h4 class="no-margin-top">  <b><?php echo HEADING_BILLING_INFORMATION; ?></b></h4>
            <h4 class="no-margin-top"><?php echo HEADING_BILLING_ADDRESS; ?></h4>


           <?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>'); ?>



			</div>
	    </div>
   </div>

		<div class="row">
         <div class="col-sm-12 col-lg-12">
	        <div class="well">
			   <h4 class="no-margin-top">  <b>Payment Information</b></h4>
	            <h4 class="no-margin-top"><?php echo HEADING_PAYMENT_METHOD; ?></h4>
			           <?php echo $order->info['payment_method']; ?>

	        </div>
	       </div>
		</div>
		<div class="col-sm-12 col-lg-12">
	        <div class="well">
			   <h4 class="no-margin-top">  <b><?php echo HEADING_ORDER_HISTORY; ?></b></h4>

			             <?php
			             $statuses_query = tep_db_query("select os.orders_status_name, osh.date_added, osh.comments from " . TABLE_ORDERS_STATUS . " os, " . TABLE_ORDERS_STATUS_HISTORY . " osh where osh.customer_notified <> 0 and osh.orders_id = '" . (int)$_GET['order_id'] . "' and osh.orders_status_id = os.orders_status_id and os.language_id = '" . (int)$languages_id . "' order by osh.date_added");
			             while ($statuses = tep_db_fetch_array($statuses_query)) {
			               echo  tep_date_short($statuses['date_added']) ;
			                   echo '&nbsp;('. $statuses['orders_status_name'] .')';
			                   echo   (empty($statuses['comments']) ? '&nbsp;<br>' : nl2br(tep_output_string_protected($statuses['comments']))) ;
			             }
			             ?>

	        </div>
	       </div>




  <?php
  // RCI menu
  echo $cre_RCI->get('accounthistoryinfo', 'bottominsidetable');
  if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . FILENAME_DOWNLOADS);
  ?>


    <div class="row">
      <div class="col-sm-12 col-lg-12">
        <div class="button-set clearfix large-margin-top">
           <?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, tep_get_all_get_params(array('order_id')), 'SSL') . '"><button class="pull-left btn btn-lg btn-default" type="button">' . IMAGE_BUTTON_BACK . '</button></a>'; ?>
            <?php
            // RCI menu buttons
            echo $cre_RCI->get('accounthistoryinfo', 'menubuttons');
            ?>
           <?php echo '<a href="javascript:popupWindow(\'' .  tep_href_link(FILENAME_ORDERS_PRINTABLE, tep_get_all_get_params(array('order_id')) . 'order_id=' . (int)$_GET['order_id'], 'NONSSL') . '\')"><button class="pull-right btn btn-lg btn-success	" type="button">' .  IMAGE_BUTTON_PRINT_ORDER . '</button></a>'; ?>


		</div>
       </div>
      </div>
 </div>
</div>

<?php
// RCI bottom
echo $cre_RCI->get('accounthistoryinfo', 'bottom');
echo $cre_RCI->get('global', 'bottom');
?>