<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Written by Jonathan Hilgeman of SiteCreative.com (osc@sitecreative.com)
  Version History

*/
  require('includes/application_top.php');
  require(DIR_WS_FUNCTIONS . 'c_orders.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  require_once(DIR_WS_CLASSES . 'PriceFormatter.php');
  $pf = new PriceFormatter;

  include(DIR_WS_CLASSES . 'order.php');
  
  // RCI top
  echo $cre_RCI->get('global', 'top', false);
  echo $cre_RCI->get('editorders', 'top', false); 
  
 // Optional Tax Rate/Percent
  $AddShippingTax = "0.0"; // e.g. shipping tax of 17.5% is "17.5"

  // intilize variables
  $update_products = '';
  $update_totals = '';
  $add_products_model = '';
  $add_products_name = '';
  $add_products_price = '';
  $products_tax_class_id = '';
  $add_product_options = '';
  $item_has_down = '';
  $products_options_name = '';
  $AddedOptionsPrice = '';
  $customer_id = '' ;

        if (isset($_GET['product_count'])) {
              $product_count = $_GET['product_count'] ;
            }else if (isset($_POST['product_count'])){
              $product_count = $_POST['product_count'] ;
            } else {
             $product_count = '' ;
    }
  // New "Status History" table has different format.
  $OldNewStatusValues = (tep_field_exists(TABLE_ORDERS_STATUS_HISTORY, "old_value") && tep_field_exists(TABLE_ORDERS_STATUS_HISTORY, "new_value"));
  $CommentsWithStatus = tep_field_exists(TABLE_ORDERS_STATUS_HISTORY, "comments");
  $SeparateBillingFields = tep_field_exists(TABLE_ORDERS, "billing_name");



  // Optional Tax Rate/Percent
  $AddShippingTax = "0.0"; // e.g. shipping tax of 17.5% is "17.5"

  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }
//get shipping method
  $orders_ship_method = array();
  $orders_ship_method_array = array();
  $orders_ship_method_query = tep_db_query("select ship_method from orders_ship_methods where ship_method_language = '" . (int)$languages_id . "'");
  while ($orders_ship_methods = tep_db_fetch_array($orders_ship_method_query)) {
    $orders_ship_method[] = array('id'   => $orders_ship_methods['ship_method'],
                                  'text' => $orders_ship_methods['ship_method']);
    $orders_ship_method_array[$orders_ship_methods['ship_method']] = $orders_ship_methods['ship_method'];
  }
//get pay method
  $orders_pay_method = array();
  $orders_pay_method_array = array();
  $orders_pay_method_query = tep_db_query("select pay_method from orders_pay_methods where pay_method_language =  '" . (int)$languages_id . "'");
  while ($orders_pay_methods = tep_db_fetch_array($orders_pay_method_query)) {
    $orders_pay_method[] = array('id'   => $orders_pay_methods['pay_method'],
                                  'text' => $orders_pay_methods['pay_method']);
    $orders_pay_method_array[$orders_pay_methods['pay_method']] = $orders_pay_methods['pay_method'];
  }
//get variables

 if (isset($_GET['oID'])){
$oID = tep_db_prepare_input($_GET['oID']);
}else if (isset($_POST['oID'])){
      $oID = $_POST['oID'] ;
    } else {
     $oID = '' ;
    }

if (isset($_GET['step'])) {
      $step = $_GET['step'] ;
    }else if (isset($_POST['step'])){
      $step = $_POST['step'] ;
    } else {
     $step = 1 ;
    }

// begin action
if (isset($_GET['action'])) {
      $action = $_GET['action'] ;
    }else if (isset($_POST['action'])){
      $action = $_POST['action'] ;
    } else {
     $action = 'edit' ;
    }
  //$action = (isset($_GET['action']) ? $_GET['action'] : 'edit');

  if (tep_not_null($action)) {
    switch ($action) {

  // Update Order
  case 'update_order':

    $order = new order($oID);
    $status = tep_db_prepare_input($_POST['status']);
    $comments = tep_db_prepare_input($_POST['comments']);

    // Update Order Info
    $UpdateOrders = "update " . TABLE_ORDERS . " set
      customers_name = '" . tep_db_input(stripslashes($_POST['update_customer_name'])) . "',
      customers_company = '" . tep_db_input(stripslashes($_POST['update_customer_company'])) . "',
      customers_street_address = '" . tep_db_input(stripslashes($_POST['update_customer_street_address'])) . "',
      customers_suburb = '" . tep_db_input(stripslashes($_POST['update_customer_suburb'])) . "',
      customers_city = '" . tep_db_input(stripslashes($_POST['update_customer_city'])) . "',
      customers_state = '" . tep_db_input(stripslashes($_POST['update_customer_state'])) . "',
      customers_postcode = '" . tep_db_input($_POST['update_customer_postcode']) . "',
      customers_country = '" . tep_db_input(stripslashes($_POST['update_customer_country'])) . "',
      customers_telephone = '" . tep_db_input($_POST['update_customer_telephone']) . "',
      customers_email_address = '" . strtolower(tep_db_input($_POST['update_customer_email_address'])) . "',";

    if($SeparateBillingFields)
    {
    $UpdateOrders .= "billing_name = '" . tep_db_input(stripslashes($_POST['update_billing_name'])) . "',
      billing_company = '" . tep_db_input(stripslashes($_POST['update_billing_company'])) . "',
      billing_street_address = '" . tep_db_input(stripslashes($_POST['update_billing_street_address'])) . "',
      billing_suburb = '" . tep_db_input(stripslashes($_POST['update_billing_suburb'])) . "',
      billing_city = '" . tep_db_input(stripslashes($_POST['update_billing_city'])) . "',
      billing_state = '" . tep_db_input(stripslashes($_POST['update_billing_state'])) . "',
      billing_telephone = '" . tep_db_input($_POST['update_billing_telephone']) . "',
      billing_fax = '" . tep_db_input($_POST['update_billing_fax']) . "',
      billing_email_address = '" . tep_db_input($_POST['update_billing_email_address']) . "',
      billing_postcode = '" . tep_db_input($_POST['update_billing_postcode']) . "',
      billing_country = '" . tep_db_input(stripslashes($_POST['update_billing_country'])) . "',";
    }

// Becasue of the way the form is written these variable may not be passed from the previous form
// so they must be checked to see if they are set if not the must be emtied,

//initlize variable
    $account_name = '';
    $account_number = '';
    $po_number = '';
//check to see if form passed these variable
    if(isset($_POST['account_name'])){
      $account_name = tep_db_input($_POST['account_name']);
      }
    if(isset($_POST['account_name'])){
      $account_number = tep_db_input($_POST['account_number']);
      }
    if(isset($_POST['po_number'])){
      $po_number = tep_db_input($_POST['po_number']);
      }

    $UpdateOrders .= "delivery_name = '" . tep_db_input(stripslashes($_POST['update_delivery_name'])) . "',
      delivery_company = '" . tep_db_input(stripslashes($_POST['update_delivery_company'])) . "',
      delivery_street_address = '" . tep_db_input(stripslashes($_POST['update_delivery_street_address'])) . "',
      delivery_suburb = '" . tep_db_input(stripslashes($_POST['update_delivery_suburb'])) . "',
      delivery_city = '" . tep_db_input(stripslashes($_POST['update_delivery_city'])) . "',
      delivery_state = '" . tep_db_input(stripslashes($_POST['update_delivery_state'])) . "',
      delivery_postcode = '" . tep_db_input($_POST['update_delivery_postcode']) . "',
      delivery_country = '" . tep_db_input(stripslashes($_POST['update_delivery_country'])) . "',
      delivery_telephone = '" . tep_db_input($_POST['update_delivery_telephone']) . "',
      delivery_fax = '" . tep_db_input($_POST['update_delivery_fax']) . "',
      delivery_email_address = '" . tep_db_input($_POST['update_delivery_email_address']) . "',
      payment_method = '" . tep_db_input($_POST['update_info_payment_method']) . "',
      account_name = '" . $account_name . "',
      account_number = '" . $account_number . "',
      po_number = '" . $po_number . "',
      last_modified = now() ";

    $UpdateOrders .= " where orders_id = '" . tep_db_input($oID) . "' ";

    tep_db_query($UpdateOrders);

    $Query1 = "update orders set last_modified = now() where orders_id = '" . tep_db_input($oID) . "';";
    tep_db_query($Query1);
    $order_updated = 1;

// status and comments

        $status = tep_db_prepare_input($_POST['status']);
        $comments = tep_db_prepare_input($_POST['comments']);


        $check_status_query = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
        $check_status = tep_db_fetch_array($check_status_query);

      // NOTE: you must post the order status to both the order, and order status history
      if($CommentsWithStatus)
// always update date and time on order_status
//check to see if can download status change
  if ( ($check_status['orders_status'] != $status) || $comments != '' || ($status ==DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE) ) {

  tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($status) . "', last_modified = now() where orders_id = '" . (int)$oID . "'");
  $check_status_query2 = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
  $check_status2 = tep_db_fetch_array($check_status_query2);
  if ( $check_status2['orders_status']==DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE ) {
    tep_db_query("update " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " set download_maxdays = '" . tep_get_configuration_key_value('DOWNLOAD_MAX_DAYS') . "', download_count = '" . tep_get_configuration_key_value('DOWNLOAD_MAX_COUNT') . "' where orders_id = '" . (int)$oID . "'");
  }
  $customer_notified = '0';
  //if notify = 1 then send email update
  if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) {
            $notify_comments = '';

            if (isset($_POST['notify_comments']) && ($_POST['notify_comments'] == 'on')) {
              $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";
            }

             $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' .
        tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED .
        ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments .
        sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);

            tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

            $customer_notified = '1';
          }

          tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comments)  . "')");

   $order_updated = 1;

        }
   
   // RCI code start
   echo $cre_RCI->get('editorders', 'updateorder', false);
   // RCI code eof

    // check to see if there are products to update
    if (count($update_products) > 0) {
      // Update Products
      $RunningSubTotal = 0;
      $RunningTax = 0;
          // CWS EDIT (start) -- Check for existence of subtotals...
          // Do pre-check for subtotal field existence
      $ot_subtotal_found = 0;
//$total_details = $_GET['total_details'];
//update_totals

      if (isset($_GET['update_totals'])) {
        $update_totals = $_GET['update_totals'] ;
      }else if (isset($_POST['update_totals'])){
        $update_totals = $_POST['update_totals'] ;
      } else {
       $update_totals = array();
      }
      if (isset($_GET['update_products'])) {
        $update_products = $_GET['update_products'] ;
      }else if (isset($_POST['update_products'])){
        $update_products = $_POST['update_products'] ;
      } else {
       $update_products = array();
      }

      $ot_class = '';
      foreach($update_totals as $total_details) {
        extract($total_details,EXTR_PREFIX_ALL,"ot");

        if($ot_class == "ot_subtotal") {
          $ot_subtotal_found = 1;
          break;
        }
      }
    
    // CWS EDIT (end) -- Check for existence of subtotals...
    //check to see if any product as in order yet.
      if (!(empty($update_products))) {
        foreach($update_products as $orders_products_id => $products_details) {
      // Update orders_products Table
      //UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
      $order_query = tep_db_query("select products_id, products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_products_id = '" . (int)$orders_products_id . "'");
     if (tep_db_num_rows($order_query) > 0) {
        $order_array = tep_db_fetch_array($order_query);
      } else {
        $order_array['products_quantity'] = 0;
      }
      if ($products_details['qty'] != $order_array['products_quantity']) {
        $quantity_difference = (int)($products_details["qty"]) - (int)($order_array['products_quantity']);
          $products_quantity = tep_db_fetch_array(tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . (int)$order_array['products_id'] . "'"));
          $products_new_quantity = (int)$products_quantity['products_quantity'] - $quantity_difference;
          $products_ordered = 0;
          if ($order_array['products_quantity'] == 0) {
            $products_ordered = 1;
          }
          tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = " . $products_new_quantity . ", products_ordered = products_ordered + " . (int)$products_ordered . " where products_id = '" . (int)$order_array['products_id'] . "'");
      }
      //UPDATE_INVENTORY_QUANTITY_END##############################################################################################################
if ($products_details["qty"] > 0) {
//check for qty pricing.
  if ($products_details['qty'] >= 1){
    $customer_id = $order->customer['id'];
    $products_check = $pf->loadProduct((int)$order_array['products_id'], $languages_id, $customer_id);
    $p_products_price = $pf->computePrice($products_details['qty']);
// compare final price to computed price if differnet then use computed price.
    if ($products_details['price'] != $p_products_price){
      $products_details['price'] = $p_products_price ;
    }
  } // end qtycheck

//if price is 0, get the regular price
if($p_products_price == 0){
    $product_price_query = tep_db_query("select products_price from " . TABLE_PRODUCTS . " where products_id = '" . $order_array['products_id'] . "'");
    if (tep_db_num_rows($product_price_query)) {
      $product_price = tep_db_fetch_array($product_price_query);
    $p_products_price = $product_price['products_price'];
    }
}
/*
//get product per order
 $attributes_order_query = tep_db_query("select poa.options_values_price, poa.price_prefix FROM
  " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " poa
   WHERE
  orders_id = '" . $oID . "'
  and orders_products_id= '" . $orders_products_id. "'");
   $AddedOptionsPrice = '';
while($attributes_order = tep_db_fetch_array($attributes_order_query)){
           if ($attributes_order['price_prefix'] == '+'){
              $AddedOptionsPrice += $attributes_order['options_values_price'];
             }else{
              $AddedOptionsPrice -= $attributes_order['options_values_price'];
            }
   }
*/
          $Query = "update " . TABLE_ORDERS_PRODUCTS . " set
          products_model = '" . $products_details['model'] . "',
          products_name = '" . str_replace("'", "&#39;", $products_details['name']) . "',          
          final_price = '" . $products_details['final_price']. "',
          products_tax = '" . $products_details['tax'] . "',
          products_quantity = '" . $products_details['qty'] . "'
          where orders_products_id = '$orders_products_id';";
        tep_db_query($Query);

        // Update Tax and Subtotals
        $RunningSubTotal += $products_details["qty"] * $products_details['final_price'];
        $RunningTax += (($products_details["tax"]/100) * ($products_details['qty'] * $products_details['final_price']));
      } else {
        // 0 Quantity = Delete
        $Query = "delete from " . TABLE_ORDERS_PRODUCTS . " where orders_products_id = '$orders_products_id';";
        tep_db_query($Query);
        $Query = "delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_products_id = '$orders_products_id';";
        tep_db_query($Query);
      }
    }
  }//end empty
    // Shipping Tax
      foreach ($update_totals as $total_index => $total_details) {
        extract($total_details,EXTR_PREFIX_ALL,"ot");
        if ($ot_class == "ot_shipping") {
          $RunningTax += (($AddShippingTax / 100) * $ot_value);
        }
      }

    // Update Totals

      $RunningTotal = 0;
      $sort_order = 0;

      // Do pre-check for Tax field existence
      $ot_tax_found = 0;
      foreach ($update_totals as $total_details) {
        extract($total_details,EXTR_PREFIX_ALL,"ot");
        if ($ot_class == "ot_tax") {
          $ot_tax_found = 1;
          break;
        }
      }

      foreach ($update_totals as $total_index => $total_details) {
        extract($total_details,EXTR_PREFIX_ALL,"ot");

       if (($ot_tax_found == 0) && ($RunningTax > 0)) {
              //check to see if it's  an admin order or catalog order
                $order_type = 0;

                $ot_total_tax_exists = 0;
                   $totals_query_5= tep_db_query("select class as ot_class_1  from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $oID . "' and class = 'ot_tax'");
          while ($totals_5 = tep_db_fetch_array($totals_query_5)) {
                    $ot_total_tax_exists = 1;
           }


            //get ot_total from db since that does not exist yet.

          $totals_query_4= tep_db_query("select class as ot_class_1, sort_order  from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $oID . "' and class = 'ot_total' order by sort_order");
          while ($totals_4 = tep_db_fetch_array($totals_query_4)) {
                 $sort_order_tmp_4 = $totals_4['sort_order'];
                 $ot_class_tmp_4 = $totals_4['ot_class_1'];
          }

           if ( ($ot_class_tmp_4 == "ot_total" ) && ($sort_order_tmp_4 <= 5)) {
               $sort_order_tmp = $sort_order_tmp_4 - 1;
               } else {
               $sort_order_tmp = MODULE_ORDER_TOTAL_TAX_SORT_ORDER ;
               }
          $ot_title_tax = 'Tax';
          $ot_text_tax = $currencies->format($RunningTax, 1, $order->info['currency'], $order->info['currency_value']);
          $ot_value_tax = $RunningTax;
          $ot_class_tax = 'ot_tax';
          $sort_order_tax = $sort_order_tmp;

            if ($ot_total_tax_exists == 0){
              $Query = "insert into " . TABLE_ORDERS_TOTAL . " set
                        orders_id = '$oID',
                        title = '$ot_title_tax',
                        text = '$ot_text_tax',
                        value = '$ot_value_tax',
                        class = '$ot_class_tax',
                        sort_order = '$sort_order_tax'";
                      tep_db_query($Query);

             $ot_tax_found = 1;
           }
      }
        if( trim(strtolower($ot_title)) == "tax" || trim(strtolower($ot_title)) == "tax:" )
        {
          if($ot_class != "ot_tax" && $ot_tax_found == 0)
          {
            // Inserting Tax
            $ot_class = "ot_tax";
            $ot_value = "x"; // This gets updated in the next step
            $ot_tax_found = 1;
          }
        }

        if( trim($ot_title) && trim($ot_value) )
        {
          $sort_order++;

          // Update ot_subtotal, ot_tax, and ot_total classes
            if($ot_class == "ot_subtotal")
            $ot_value = $RunningSubTotal;

            if($ot_class == "ot_tax")
            {
            $ot_value = $RunningTax;
            // print "ot_value = $ot_value<br>\n";
            }
//discount
     // CWS EDIT (start) -- Check for existence of subtotals...
            if($ot_class == "ot_total")
                        {

                $ot_value = $RunningTotal ;
                            if ( !$ot_subtotal_found )
                            {
                                // There was no subtotal on this order, lets add the running subtotal in.
                                $ot_value = $ot_value + $RunningSubTotal;
                            }
                        }
     // CWS EDIT (end) -- Check for existence of subtotals...

          // Set $ot_text (display-formatted value)
            // $ot_text = "\$" . number_format($ot_value, 2, '.', ',');

            $order = new order($oID);
            $ot_text = $currencies->format($ot_value, 1, $order->info['currency'], $order->info['currency_value']);

            if($ot_class == "ot_total")
            $ot_text = "<b>" . $ot_text . "</b>";

          if($ot_total_id > 0)
          {
            // In Database Already - Update
            $Query = "update " . TABLE_ORDERS_TOTAL . " set
              title = '$ot_title',
              text = '$ot_text',
              value = '$ot_value'
              where orders_total_id = '$ot_total_id'";
            tep_db_query($Query);
          }
          else
          {
            // New Insert
            $Query = "insert into " . TABLE_ORDERS_TOTAL . " set
              orders_id = '$oID',
              title = '$ot_title',
              text = '$ot_text',
              value = '$ot_value',
              class = '$ot_class',
              sort_order = '$sort_order'";
            tep_db_query($Query);
          }

          if ($ot_class == "ot_shipping" || $ot_class == "ot_lev_discount" || $ot_class == "ot_custom" || $ot_class == "ot_cod_fee") {
            // Again, because products are calculated in terms of default currency, we need to align shipping, custom etc. values with default currency
            $RunningTotal += $ot_value;
          } else if($ot_class == "ot_coupon" ||$ot_class == "ot_customer_discount" || $ot_class == "ot_discount" || $ot_class == "ot_gv"){
          //subtract discounts
          $RunningTotal -= $ot_value;
          }else{
          //add to gether all other ot-totals
            if($ot_class != "ot_tax") {
               $RunningTotal += $ot_value;
            }else if (($ot_class == "ot_tax") && (DISPLAY_PRICE_WITH_TAX == true) ) {
            //add tax to ot-total
               $RunningTotal += $ot_value;
            }
          }
        }
      elseif (($ot_total_id > 0) && ($ot_class != "ot_shipping")) { // Delete Total Piece

          // Delete Total Piece
          $Query = "delete from " . TABLE_ORDERS_TOTAL . " where orders_total_id = '$ot_total_id'";
          tep_db_query($Query);
        }

      }
    }
        if ($order_updated == 1) {
       //  $messageStack->add_session('search', SUCCESS_ORDER_UPDATED, 'success');
        } else {
          $messageStack->add_session('search', WARNING_ORDER_NOT_UPDATED, 'warning');
        }
 //teoteo
 tep_redirect(tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('action')) . 'oID=' . $oID . '&action=edit', 'SSL'));

  break;
//********************* add product *************
  // Add a Product
  case 'add_product':
        if($step == 5) {
            // Get Order Info
         $order = new order($oID);

   if (isset($_GET["add_product_options"])) {
      $add_product_options = $_GET["add_product_options"] ;
    }else if (isset($_POST["add_product_options"])){
      $add_product_options = $_POST["add_product_options"] ;
    } else {
      $add_product_options = array() ;
   }
         $add_product_products_id = (isset($_POST['add_product_products_id']) ? $_POST['add_product_products_id'] : '');
         $add_product_quantity = (isset($_POST['add_product_quantity']) ? $_POST['add_product_quantity'] : '');
         $customer_id = (isset($order->customer['id']) ? $order->customer['id'] : '');

// intialize for first attribute
         $AddedOptionsPrice = 0;
         $option_is_text = 0;
         $item_has_down = 0;
         $query_products_options_values = '';
         $products_options_values= '';

  $customer_id = $order->customer['id'];
    // Get Product Info
    $product_info_guery_1 = tep_db_query("select
     pd.products_name,
     p.products_id,
     p.products_price,
     p.products_tax_class_id,
     p.products_model,
     p.products_status
    FROM " . TABLE_PRODUCTS . " p,
         " . TABLE_PRODUCTS_DESCRIPTION . " pd
    where
     p.products_id = '" . $add_product_products_id . "'
     and pd.products_id = p.products_id
     and pd.language_id = '" . (int)$languages_id . "'
    ");
            while ($product_info_array = tep_db_fetch_array($product_info_guery_1)){
                    $add_products_model = $product_info_array['products_model'];
                    $add_products_name = $product_info_array['products_name'] ;
                    $add_products_price = $product_info_array['products_price'];
                    $products_tax_class_id = $product_info_array['products_tax_class_id'];
                }

     $products = $pf->loadProduct($add_product_products_id, $languages_id, $customer_id);
     $p_products_price = $pf->computePrice($add_product_quantity);

//if price is 0, get the regular price
if($p_products_price == 0){
    $product_price_query = tep_db_query("select products_price from " . TABLE_PRODUCTS . " where products_id = '" . $add_product_products_id . "'");
    if (tep_db_num_rows($product_price_query)) {
      $product_price = tep_db_fetch_array($product_price_query);
    $p_products_price = $product_price['products_price'];
    }
}
      // Following functions are defined at the bottom of this file
      $CountryID = tep_get_country_id($order->delivery["country"]);
      $ZoneID = tep_get_zone_id($CountryID, $order->delivery["state"]);

      $ProductsTax = tep_get_tax_rate($products_tax_class_id, $CountryID, $ZoneID);

                $Query = "insert into " . TABLE_ORDERS_PRODUCTS . " set
                orders_id = '" . $oID . "',
                products_id = '" . $add_product_products_id . "',
                products_model = '" . $add_products_model . "',
                products_name = '" . str_replace("'", "&#39;", $add_products_name) . "',
                products_price = '" . $p_products_price . "',
                final_price = '" . ($p_products_price + $AddedOptionsPrice) . "',
                products_tax = '" . $ProductsTax . "',
                products_quantity = '" . $add_product_quantity . "'";
            tep_db_query($Query);
            $new_product_id = tep_db_insert_id();

  // Get Product Attribute Info

   if( (isset($add_product_options)) && (is_array($add_product_options)) ){

foreach($add_product_options as $attri_id => $attri_option_value_id){
    $products_id_query = $add_product_products_id;

//get option type:
$attributes_type_query = tep_db_query("select pa.products_attributes_id, pa.options_values_id, po.options_type
          FROM
          " . TABLE_PRODUCTS_ATTRIBUTES . " pa,
          " . TABLE_PRODUCTS_OPTIONS . " po
        WHERE
        pa.products_id = '" . $products_id_query . "'
        and pa.products_attributes_id = '" . $attri_id. "'
        and po.products_options_id = pa.options_id" );

while ($attributes_type = tep_db_fetch_array($attributes_type_query)){
        $attributes_options_type = $attributes_type['options_type'];
}

// if drop down select
if($attributes_options_type == 0){
$attri_id = $attri_option_value_id ;
}

//echo '$attri_id ' . $attri_id ;
//echo  '$attri_option_value_id ' . $attri_option_value_id;
////echo '$attributes_options_type ' . $attributes_options_type;
//echo '<br>';

//check box array
if (is_array($attri_option_value_id)){
    $attri_option_value_id_tmp = $attri_option_value_id ;
   foreach($attri_option_value_id_tmp as $attri_option_id_tmp => $attri_option_value_id_tmp){
    $attri_option_id = $attri_option_id_tmp ;
    $attri_option_value_id= $attri_option_value_id_tmp;
   }

unset($attri_option_value_id);
 }
//intialize values
$option_is_text = 0;
$item_has_down = 0;
$query_products_options_values = '';
$products_options_values= '';


// check to see if there is a downloadable file
  if (DOWNLOAD_ENABLED ==  1) {
//check for downloads
     $attributes_download_query = tep_db_query("select pa.products_attributes_id, poval.products_options_values_id, pa.products_id, pa.options_id, pa.options_values_id, pa.options_values_price, pa.price_prefix, poptt.products_options_name, poval.products_options_values_name, pad.products_attributes_filename, pad.products_attributes_maxdays, pad.products_attributes_maxcount  FROM
     " . TABLE_PRODUCTS_OPTIONS . " popt,
     " . TABLE_PRODUCTS_OPTIONS_TEXT . " poptt,
     " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval,
     " . TABLE_PRODUCTS_ATTRIBUTES . " pa,
     " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
   WHERE
    pa.products_id = '" . $products_id_query . "'
    and pa.products_attributes_id = '" . $attri_id. "'
    and poptt.products_options_text_id = pa.options_id
    and poval.products_options_values_id =  pa.options_values_id
    and poptt.language_id = '" . (int)$languages_id . "'
    and poval.language_id = '" . (int)$languages_id . "'
   and pad.products_attributes_id = pa.products_attributes_id
  order by pa.products_options_sort_order
    limit 1");

 if (tep_db_num_rows($attributes_download_query)) {
   $item_has_down = '1';
   } // end if tep_db_num_rows
}// end if DOWNLOAD_ENABLED

//check for text
  if($attributes_options_type != '1' && $attributes_options_type != '4'){
    $query_products_options_values = $attri_option_value_id ;
    $option_is_text = 0 ;
  }else{
    if (!empty($attri_option_value_id)){
     $query_products_options_values = 0;
     $option_is_text = 1 ;
   }
  }

// get attibutes data
if ($item_has_down == '1')  {
  $attributes_query = tep_db_query("select pa.products_attributes_id, poval.products_options_values_id, pa.products_id, pa.options_id, pa.options_values_id, pa.options_values_price, pa.price_prefix, poptt.products_options_name, poval.products_options_values_name, pad.products_attributes_filename, pad.products_attributes_maxdays, pad.products_attributes_maxcount  FROM
  " . TABLE_PRODUCTS_OPTIONS . " popt,
  " . TABLE_PRODUCTS_OPTIONS_TEXT . " poptt,
  " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval,
  " . TABLE_PRODUCTS_ATTRIBUTES . " pa,
  " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
   WHERE
    pa.products_id = '" . $products_id_query . "'
    and pa.products_attributes_id = '" . $attri_id. "'
  and poptt.products_options_text_id = pa.options_id
  and poval.products_options_values_id =  pa.options_values_id
  and poptt.language_id = '" . (int)$languages_id . "'
  and poval.language_id = '" . (int)$languages_id . "'
  and pad.products_attributes_id = pa.products_attributes_id
  order by pa.products_options_sort_order
  limit 1
  ");
} else if (($item_has_down == '0') && ($option_is_text == 0)){
  $attributes_query = tep_db_query("select pa.products_attributes_id, poval.products_options_values_id, pa.products_id, pa.options_id, pa.options_values_id, pa.options_values_price, pa.price_prefix, poptt.products_options_name, poval.products_options_values_name from
  " . TABLE_PRODUCTS_OPTIONS . " popt,
  " . TABLE_PRODUCTS_OPTIONS_TEXT . " poptt,
  " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval,
  " . TABLE_PRODUCTS_ATTRIBUTES . " pa
where
    pa.products_id = '" . $products_id_query . "'
    and pa.products_attributes_id = '" . $attri_id. "'
  and poptt.products_options_text_id = pa.options_id
  and poval.products_options_values_id =  pa.options_values_id
  and poptt.language_id = '" . (int)$languages_id . "'
  and poval.language_id = '" . (int)$languages_id . "'
  order by pa.products_options_sort_order
  limit 1
  ");
   } else if ($option_is_text == 1){
  $attributes_query = tep_db_query("select poval.products_options_values_id, pa.products_attributes_id, pa.products_id, pa.options_id, pa.options_values_id, pa.options_values_price, pa.price_prefix, poptt.products_options_name  FROM
  " . TABLE_PRODUCTS_OPTIONS . " popt,
  " . TABLE_PRODUCTS_OPTIONS_TEXT . " poptt,
  " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval,
  " . TABLE_PRODUCTS_ATTRIBUTES . " pa
   WHERE
    pa.products_id = '" . $products_id_query . "'
    and pa.products_attributes_id = '" . $attri_id. "'
  and poptt.products_options_text_id = pa.options_id
  and poptt.language_id = '" . (int)$languages_id . "'
  order by pa.products_options_sort_order
  limit 1
  ");
    }

while  ($attributes = tep_db_fetch_array($attributes_query)){
  if($option_is_text == 1){
    $products_options_values = htmlentities(stripslashes($attri_option_value_id), ENT_QUOTES);
   }else{
    $products_options_values = $attributes['products_options_values_name'];
  }

  $orders_products_id = $new_product_id;
  $products_options = $attributes['products_options_name'];
  $options_values_price = $attributes['options_values_price'];
  $price_prefix = $attributes['price_prefix'];
  $products_options_id = $attributes['options_id'];
  $products_options_values_id = $attributes['options_values_id'];

//downloads
if ($item_has_down == '1') {
$orders_products_filename = $attributes['products_attributes_filename'];
$download_maxdays = $attributes['products_attributes_maxdays'];
$download_count = $attributes['products_attributes_maxcount'];
    }

//add attibute price to product price
 if ($price_prefix == '+'){
   $AddedOptionsPrice += $options_values_price;
  }else{
   $AddedOptionsPrice -= $options_values_price;
  }

// update final price
$product_price_update = tep_db_query("select final_price from
                             " . TABLE_ORDERS_PRODUCTS . "
                            where
                            orders_id = $oID and
                            orders_products_id = $orders_products_id ");
  while  ($product_price_array_1 = tep_db_fetch_array($product_price_update)){
    $Query_final_price = "update " . TABLE_ORDERS_PRODUCTS . " set
     final_price = '" . ($p_products_price + $AddedOptionsPrice) . "'
  where orders_id = $oID and orders_products_id = $orders_products_id ";
   tep_db_query($Query_final_price);
 }

if(isset($add_product_options)){
   $Query = "insert into " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " set
               orders_id = $oID,
               orders_products_id = $orders_products_id,
               products_options = '". $products_options . "',
               products_options_values = '" . $products_options_values . "',
               options_values_price = $options_values_price,
               price_prefix = '" . $price_prefix . "',
               products_options_id = '" . $products_options_id . "' ,
               products_options_values_id =  '" . $products_options_values_id . "' ";

  tep_db_query($Query);
 }

// add download insert
          if ((DOWNLOAD_ENABLED ==  1) && isset($orders_products_filename) && tep_not_null($orders_products_filename)) {
            $sql_data_array = array('orders_id' => $oID,
                                    'orders_products_id' => $new_product_id,
                                    'orders_products_filename' => $orders_products_filename,
                                    'download_maxdays' => $download_maxdays,
                                    'download_count' => $download_count);
            tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
           }


       } // end while get option
     }
    }
//add product info
// recalculate totals
  //UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
  tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity - " . $add_product_quantity . ", products_ordered = products_ordered + " . $add_product_quantity . " where products_id = '" . $add_product_products_id . "'");
  //UPDATE_INVENTORY_QUANTITY_END##############################################################################################################
//end add product info

//get total of product using order class
      $order = new order($oID);
      $RunningSubTotal = 0;
      $RunningTax = 0;

      for ($i=0; $i<sizeof($order->products); $i++)
      {
      $RunningSubTotal += ($order->products[$i]['qty'] * $order->products[$i]['final_price']);
     //tax caculated on a per product bases
      $RunningTax += (($order->products[$i]['tax'] / 100) * ($order->products[$i]['qty'] * $order->products[$i]['final_price']));
      }
    //subtotal to total
       $RunningTotal = $RunningSubTotal;
    //add tax
       $RunningTotal += $RunningTax;

      // Calculate Tax and Sub-Totals
   // get exsisting order details so we can update them

            $totals_query_5 = tep_db_query("select title as ot_title, text as ot_text, value as ot_value, class as ot_class  from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . $oID . "' order by sort_order");
          while ($totals_1 = tep_db_fetch_array($totals_query_5)) {
            $totals_array[] = array('ot_title' => $totals_1['ot_title'],
                              'ot_text' => $totals_1['ot_text'],
                              'ot_value' => $totals_1['ot_value'],
                              'ot_class' => $totals_1['ot_class']);
          }
//parse array
  for ($i=0, $n=sizeof($totals_array); $i<$n; $i++) {
    $totals = $totals_array[$i];

       //add other order totals
          if ($totals['ot_class'] == "ot_shipping" || $totals['ot_class'] == "ot_custom" || $totals['ot_class'] == "ot_cod_fee") {
            // add ot_ items not already added
            $RunningTotal += $totals['ot_value'] / $order->info['currency_value'];
       //deduct discounts
           } else if($totals['ot_class'] == "ot_coupon" ||$totals['ot_class'] == "ot_customer_discount" || $totals['ot_class'] == "ot_discount" || $totals['ot_class'] == "ot_gv" || $totals['ot_class'] == "ot_lev_discount" ){
          //subtract discounts
          $RunningTotal -= $totals['ot_value'] / $order->info['currency_value'];
          }

      // Tax
      $Query = "update " . TABLE_ORDERS_TOTAL . " set
        text = '\$" . number_format($RunningTax, 2, '.', ',') . "',
        value = '" . $RunningTax . "'
        where class='ot_tax' and orders_id=$oID";
      tep_db_query($Query);

      // Sub-Total
      $Query = "update " . TABLE_ORDERS_TOTAL . " set
        text = '\$" . number_format($RunningSubTotal, 2, '.', ',') . "',
        value = '" . $RunningSubTotal . "'
        where class='ot_subtotal' and orders_id=$oID";
      tep_db_query($Query);

      $Query = "update " . TABLE_ORDERS_TOTAL . " set
        text = '<b>\$" . number_format($RunningTotal, 2, '.', ',') . "</b>',
        value = '" . $RunningTotal . "'
        where class='ot_total' and orders_id=$oID";
      tep_db_query($Query);

  } //end of for
  tep_redirect(tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('action')) . 'oID=' . $_GET['oID'] . '&action=edit', 'SSL'));
    }
  break;

    } //end action
  }// end action NUll

  if (($action == 'edit') && isset($_GET['oID'])) {
    $oID = (int)$_GET['oID'];

    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
    $order_exists = 1;
    if (!tep_db_num_rows($orders_query)) {
      $order_exists = 0;
      $messageStack->add('search', sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
  }

  for ($i=1; $i<13; $i++) {
    $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime(' %m',mktime(0,0,0,$i,1,2000)));
  }
  $today = getdate();
  for ($i=$today['year']; $i < $today['year']+10; $i++) {
    $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
  }
  for ($i=1; $i < 13; $i++) {
    $start_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime(' %m',mktime(0,0,0,$i,1,2000)));
  }
  $today = getdate();      
  for ($i=$today['year']-4; $i <= $today['year']; $i++) {
    $start_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? 'https:' : 'http:'); ?>//ajax.googleapis.com/ajax/libs/jquery/<?php echo JQUERY_VERSION; ?>/jquery.min.js"></script>
<script type="text/javascript">
  if (typeof jQuery == 'undefined') {
    //alert('You are running a local copy of jQuery!');
    document.write(unescape("%3Cscript src='includes/javascript/jquery-1.6.2.min.js' type='text/javascript'%3E%3C/script%3E"));
  }
</script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=650,height=500,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
<link rel="stylesheet" type="text/css" href="includes/headernavmenu.css">
<script type="text/javascript" src="includes/menu.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
   $order = new order($oID);
  if (($action == 'edit') && ($order_exists == 1)) {
  //  $order = new order($oID);
  if ($_SESSION['create_order'] == true) {
      unset($_SESSION['create_order']);
?>
      <tr>
        <td width="100%">
          <table bgcolor="#7c6bce" border="0" width="100%" cellspacing="1" cellpadding="1">
          <tr>
             <td class=main><font color='#FFFFFF'><b><?php echo HEADING_STEP2 . $oID; ?></b></td>
             <td align="right"><?php echo '<a href="javascript: history.go(-1)" style="text-decoration: none;">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>
      <?php
    } else {
      ?>
      <tr>
        <td width="100%">
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%" class="pageHeading"><?php echo HEADING_TITLE; ?></td> 
            <td width="50%" class="main" align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" align="right">
                <?php  
                  echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')), 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; 
                  echo tep_image_submit('button_update.gif', IMAGE_UPDATE); 
                ?>
                </td>
              </tr>
            </table></td>            
          </tr>
        </table></td>
      </tr>
      <?php
    }
    ?>

<!-- Begin Addresses Block -->
      <tr>
        <?php 
        if (isset($_SESSION['is_std']) && $_SESSION['is_std'] === true) {
          echo tep_draw_form('order_nag', FILENAME_GET_LOADED, 'page=edit_order&oID=' . $oID, 'post', '', 'SSL'); 
        } else {        
          echo tep_draw_form('edit_order', FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('action','paycc'), 'post', '', 'SSL') . 'action=update_order', 'post', '', 'SSL'); 
        }
        ?>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td valign="top">
      <!-- Customer Info Block -->
    <table border="0" cellspacing="0" cellpadding="2">
    <tr>
    <td colspan='2' class="main" valign="top"><b><?php echo ENTRY_CUSTOMER; ?></b></td>
    <td colspan='2' class="main" valign="top"><b><?php echo ENTRY_BILLING_ADDRESS; ?></b></td>
    </tr>
    <tr>
    <td colspan='2' class="main">
    <table border="0" cellspacing="0" cellpadding="2" class="infoBox">
      <tr>
      <td class="editOrder"><b><?php echo ENTRY_NAME ?></b></font></td>
      <td><input name='update_customer_name' size='37' value='<?php echo tep_html_quotes($order->customer['name']); ?>'></td>
      </tr>
      <tr>
       <td  class="editOrder"><?php echo TABLE_HEADING_CUSTOMER_ID ?>  </td>
       <td><?php
       if ( $order->customer['id'] == 0 ) {
       echo TABLE_HEADING_PWA ;
       }else{
       echo $order->customer['id'];
       }
       ;?>  </td>
      </tr>
      <tr>
        <td class="editOrder"><b><?php echo ENTRY_COMPANY ?></b></font></td>
        <td><input name='update_customer_company' size='37' value='<?php echo tep_html_quotes($order->customer['company']); ?>'></td>
      </tr>
      <tr>
        <td class="editOrder"><b><?php echo ENTRY_CUSTOMER_ADDRESS ?></b></font></td>
        <td><input name='update_customer_street_address' size='37' value='<?php echo tep_html_quotes($order->customer['street_address']); ?>'></td>
      </tr>
      <tr>
        <td class="editOrder"><b><?php echo ENTRY_SUBURB ?></b></font></td>
        <td><input name='update_customer_suburb' size='37' value='<?php echo tep_html_quotes($order->customer['suburb']); ?>'></td>
      </tr>
      <tr>
        <td class="editOrder"><b><?php echo ENTRY_CITY ?></b></font></td>
        <td><input name='update_customer_city' size='15' value='<?php echo tep_html_quotes($order->customer['city']); ?>'> </td>
      </tr>
      <tr>
        <td class="editOrder"><b><?php echo ENTRY_STATE ?></b></font></td>
        <td><input name='update_customer_state' size='15' value='<?php echo tep_html_quotes($order->customer['state']); ?>'> </td>
      </tr>
      <tr>
        <td class="editOrder"><b><?php echo ENTRY_POST_CODE ?></b></font></td>
        <td><input name='update_customer_postcode' size='5' value='<?php echo $order->customer['postcode']; ?>'></td>
      </tr>
      <tr>
        <td class="editOrder"><b><?php echo ENTRY_COUNTRY ?></b></font></td>
        <td><input name='update_customer_country' size='37' value='<?php echo tep_html_quotes($order->customer['country']); ?>'></td>
      </tr>
     </table>
    </td>


<?php if($SeparateBillingFields) { ?>
      <td>
       <!-- Billing Address Block -->
       <table border="0" cellspacing="0" cellpadding="2">

      <tr>
        <td colspan='2' class="main">
          <table border="0" cellspacing="0" cellpadding="2" class="infoBox">
          <tr>
            <td class="editOrder"><b><?php echo ENTRY_NAME ?></b></font></td>
              <td><input name='update_billing_name' size='37' value='<?php echo tep_html_quotes($order->billing['name']); ?>'></td>
            </tr>
            <tr>
              <td class="editOrder"><b><?php echo ENTRY_COMPANY ?></b></font></td>
              <td><input name='update_billing_company' size='37' value='<?php echo tep_html_quotes($order->billing['company']); ?>'></td>
            </tr>
            <tr>
              <td class="editOrder"><b><?php echo ENTRY_CUSTOMER_ADDRESS ?></b></font></td>
              <td><input name='update_billing_street_address' size='37' value='<?php echo tep_html_quotes($order->billing['street_address']); ?>'></td>
            </tr>
            <tr>
              <td class="editOrder"><b><?php echo ENTRY_SUBURB ?></b></font></td>
              <td><input name='update_billing_suburb' size='37' value='<?php echo tep_html_quotes($order->billing['suburb']); ?>'></td>
            </tr>
            <tr>
              <td class="editOrder"><b><?php echo ENTRY_CITY ?></b></font></td>
              <td><input name='update_billing_city' size='15' value='<?php echo tep_html_quotes($order->billing['city']); ?>'> </td>
            </tr>
            <tr>
              <td class="editOrder"><b><?php echo ENTRY_STATE ?></b></font></td>
              <td><input name='update_billing_state' size='15' value='<?php echo tep_html_quotes($order->billing['state']); ?>'> </td>
            </tr>
            <tr>
              <td class="editOrder"><b><?php echo ENTRY_POST_CODE ?></b></font></td>
              <td><input name='update_billing_postcode' size='5' value='<?php echo $order->billing['postcode']; ?>'></td>
            </tr>
            <tr>
              <td class="editOrder"><b><?php echo ENTRY_COUNTRY ?></b></font></td>
              <td><input name='update_billing_country' size='37' value='<?php echo tep_html_quotes($order->billing['country']); ?>'></td>
            </tr>
            <tr>
              <td class="editOrder"><b><?php echo ENTRY_TELEPHONE_NUMBER ?></b></td>
              <td><input name='update_billing_telephone' size='37' value='<?php echo tep_html_quotes($order->billing['telephone']); ?>'></td>
            </tr>
            <tr>
              <td class="editOrder"><b><?php echo ENTRY_FAX_NUMBER ?></b></td>
              <td><input name='update_billing_fax' size='37' value='<?php echo tep_html_quotes($order->billing['fax']); ?>'></td>
            </tr>
            <tr>
              <td class="editOrder"><b><?php echo ENTRY_EMAIL_ADDRESS ?></b></td>
              <td><input name='update_billing_email_address' size='37' value='<?php echo tep_html_quotes($order->billing['email_address']); ?>'></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
      </td>
<?php } ?>

    </tr>
    </table>
      </td>
      </tr>

      <tr>
      <td valign="top">
      <!-- Shipping Address Block -->
    <table border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td class="main" valign="top"><b><?php echo ENTRY_SHIPPING_ADDRESS; ?></b></td>
      </tr>
      <tr>
        <td colspan='1' class="main">
          <table border="0" cellspacing="0" cellpadding="2" class="infoBox">
          <tr>
            <td class="editOrder"><b><?php echo ENTRY_NAME ?></b></font></td>
              <td><input name='update_delivery_name' size='37' value='<?php echo tep_html_quotes($order->delivery['name']); ?>'></td>
            </tr>
            <tr>
              <td class="editOrder"><b><?php echo ENTRY_COMPANY ?></b></font></td>
              <td><input name='update_delivery_company' size='37' value='<?php echo tep_html_quotes($order->delivery['company']); ?>'></td>
            </tr>
            <tr>
              <td class="editOrder"><b><?php echo ENTRY_CUSTOMER_ADDRESS ?></b></font></td>
              <td><input name='update_delivery_street_address' size='37' value='<?php echo tep_html_quotes($order->delivery['street_address']); ?>'></td>
            </tr>
            <tr>
              <td class="editOrder"><b><?php echo ENTRY_SUBURB ?></b></font></td>
              <td><input name='update_delivery_suburb' size='37' value='<?php echo tep_html_quotes($order->delivery['suburb']); ?>'></td>
            </tr>
            <tr>
              <td class="editOrder"><b><?php echo ENTRY_CITY ?></b></font></td>
              <td><input name='update_delivery_city' size='15' value='<?php echo tep_html_quotes($order->delivery['city']); ?>'> </td>
            </tr>
            <tr>
              <td class="editOrder"><b><?php echo ENTRY_STATE ?></b></font></td>
              <td><input name='update_delivery_state' size='15' value='<?php echo tep_html_quotes($order->delivery['state']); ?>'> </td>
            </tr>
            <tr>
              <td class="editOrder"><b><?php echo ENTRY_POST_CODE ?></b></font></td>
              <td><input name='update_delivery_postcode' size='5' value='<?php echo $order->delivery['postcode']; ?>'></td>
            </tr>
            <tr>
              <td class="editOrder"><b><?php echo ENTRY_COUNTRY ?></b></font></td>
              <td><input name='update_delivery_country' size='37' value='<?php echo tep_html_quotes($order->delivery['country']); ?>'></td>
            </tr>
            <tr>
              <td class="editOrder"><b><?php echo ENTRY_TELEPHONE_NUMBER ?></b></td>
              <td><input name='update_delivery_telephone' size='37' value='<?php echo tep_html_quotes($order->delivery['telephone']); ?>'></td>
            </tr>
            <tr>
              <td class="editOrder"><b><?php echo ENTRY_FAX_NUMBER ?></b></td>
              <td><input name='update_delivery_fax' size='37' value='<?php echo tep_html_quotes($order->delivery['fax']); ?>'></td>
            </tr>
            <tr>
              <td class="editOrder"><b><?php echo ENTRY_EMAIL_ADDRESS ?></b></font></td>
              <td><input name='update_delivery_email_address' size='37' value='<?php echo tep_html_quotes($order->delivery['email_address']); ?>'></td>
            </tr>
          </table>
        </td>
        <td class="main" align="center" valign="middle">
          <font size="2" face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular" color="red"><b><?php echo HEADING_INSTRUCT1 ?></b></font><br><br>
          <?php echo HEADING_INSTRUCT2 ?>

        </td>
       </tr>
      </table>
      </td>
    </tr>
  </table></td>
      </tr>
<!-- End Addresses Block -->

      <tr>
  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

<!-- Begin Phone/Email Block -->
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2" class="infoBox">
          <tr>
            <td class="main"><b><?php echo ENTRY_TELEPHONE_NUMBER; ?></b></td>
            <td class="main"><input name='update_customer_telephone' size='15' value='<?php echo $order->customer['telephone']; ?>'></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
            <td class="main"><input name='update_customer_email_address' size='35' value='<?php echo $order->customer['email_address']; ?>'></td>
          </tr>
               <tr>
                <td class="main"><b><?php echo ENTRY_IPADDRESS; ?></b></td>
                <td class="main"><?php echo $order->customer['ipaddy']; ?></td>
    </tr>
    <tr>
    <td class="main"><b><?php echo ENTRY_IPISP; ?></b></td>
    <td class="main"><?php echo $order->customer['ipisp']; ?></td>
    </tr>
        </table></td>
      </tr>
<!-- End Phone/Email Block -->

      <tr>
  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

<!-- Begin Payment Block -->
      <tr>
      <?php
      if (stristr($order->info['payment_method'], 'paypal')) {
        include(DIR_FS_CATALOG_MODULES . 'payment/paypal/admin/TransactionSummaryLogs.inc.php');
      }
      if (strtolower($order->info['payment_method']) == 'ignore') {
      } else {
        ?>
        <td><table border="0" cellspacing="0" cellpadding="2" class="infoBox">
          <tr valine="middle">
            <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
            <?php
            //list exsisting payment if not in current order_pay_methods table
            $orders_pay_methodA[] = array('id'   => $order->info['payment_method'],
                                                          'text' => $order->info['payment_method']);
            $orders_pay_method1 = array_merge($orders_pay_methodA, $orders_pay_method) ;
            ?>
            <td class="main"><?php echo tep_draw_pull_down_menu('update_info_payment_method', $orders_pay_method1, $order->info['payment_method']); ?>
              <?php
              echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
              ?>
            </td>
          </tr>
        <?php
        if ( (isset($order->info['account_name']) && $order->info['account_name']) ||
             (isset($order->info['account_number']) && $order->info['account_number']) ||
             (isset($order->info['payment_method']) && ($order->info['payment_method']) == "Purchase Order") ||
             (isset($order->info['po_number']) && $order->info['po_number']) ) {
        ?>
        <tr>
          <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td class="main" valign="top" align="left"><b><?php echo TEXT_INFO_PO ?></b></td>
          <td><table border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
              <td class="main"><?php echo TEXT_INFO_NAME ?></td>
              <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
              <td class="main"><input type="text" name="account_name" value='<?php echo (isset($order->info['account_name']) ? $order->info['account_name'] : ''); ?>'></td></td>
              <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
            </tr>
            <tr>
              <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
              <td class="main"><?php echo TEXT_INFO_AC_NR ?></td>
              <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
              <td class="main"><input type="text" name="account_number" value='<?php echo (isset($order->info['account_number']) ? $order->info['account_number'] : ''); ?>'></td>
              <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
            </tr>
            <tr>
              <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
              <td class="main"><?php echo TEXT_INFO_PO_NR ?></td>
              <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
              <td class="main"><input type="text" name="po_number" value='<?php echo (isset($order->info['po_number']) ? $order->info['po_number'] : ''); ?>'></td>
              <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
            </tr>
          </table></td>
        </tr>
        <?php
        }
      }
      ?>
        </table></td>
      </tr>
      <?php
      // RCI edit orders payment data handling
      echo $cre_RCI->get('editorders', 'paymentdata');
      ?>
    <!-- End Payment Block -->

      <tr>
  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<!-- Begin Products Listing Block -->
      <tr>
  <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr class="dataTableHeadingRow">
      <td class="dataTableHeadingContent" colspan="2" style="white-space:nowrap;"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
      <td class="dataTableHeadingContent" style="white-space:nowrap;"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
      <td class="dataTableHeadingContent" align="center" style="white-space:nowrap;"><?php echo TABLE_HEADING_TAX; ?></td>
      <td class="dataTableHeadingContent" align="right" style="white-space:nowrap;"><?php echo TABLE_HEADING_BASE_PRICE; ?></td>
      <td class="dataTableHeadingContent" align="right" style="white-space:nowrap;"><?php echo TABLE_HEADING_UNIT_PRICE; ?></td>
      <td class="dataTableHeadingContent" align="right" style="white-space:nowrap;"><?php echo TABLE_HEADING_TOTAL_PRICE; ?></td>
<?php
  if ( DISPLAY_PRICE_WITH_TAX == 'true'){
    echo  '<td class="dataTableHeadingContent" align="right">' . TABLE_HEADING_TOTAL_PRICE_TAXED . '</td>';
    }
;?>
  </tr>

  <!-- Begin Products Listings Block -->
  <?php
        // Override order.php Class's Field Limitations
    $index = 0;
    $order->products = array();
    $orders_products_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$oID . "'");
    while ($orders_products = tep_db_fetch_array($orders_products_query)) {
    $order->products[$index] = array('qty' => $orders_products['products_quantity'],
                                        'name' => str_replace("'", "&#39;", $orders_products['products_name']),
                                        'model' => $orders_products['products_model'],
                                        'tax' => $orders_products['products_tax'],
                                        'price' => $orders_products['products_price'],
                                        'final_price' => $orders_products['final_price'],
                                        'orders_products_id' => $orders_products['orders_products_id']);

    $subindex = 0;
    $attributes_query_string = "select * from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$oID . "' and orders_products_id = '" . (int)$orders_products['orders_products_id'] . "'";
    $attributes_query = tep_db_query($attributes_query_string);

    if (tep_db_num_rows($attributes_query)) {
    while ($attributes = tep_db_fetch_array($attributes_query)) {
      $order->products[$index]['attributes'][$subindex] = array('option' => $attributes['products_options'],
                                                               'value' => $attributes['products_options_values'],
                                                               'prefix' => $attributes['price_prefix'],
                                                               'price' => $attributes['options_values_price'],
                                                               'orders_products_attributes_id' => $attributes['orders_products_attributes_id']);
    $subindex++;
    }
    }
    $index++;
    }

  for ($i=0; $i<sizeof($order->products); $i++) {
    $orders_products_id = $order->products[$i]['orders_products_id'];

    $RowStyle = "dataTableContent";

    echo '    <tr class="dataTableRow">' . "\n" ;
 if (ORDER_EDIT_EDT_PRICE == '1'){
   echo   '      <td class="' . $RowStyle . '" valign="top" align="right">' . "<input name='update_products[$orders_products_id][qty]' type='input' size='2' value='" . $order->products[$i]['qty'] . "'>&nbsp;x</td>\n" .
          '      <td class="' . $RowStyle . '" valign="top">' . "<input name='update_products[$orders_products_id][name]' type='input' size='25' value='" . $order->products[$i]['name'] . "'>";
  }else{
   echo   '      <td class="' . $RowStyle . '" valign="top" align="right">' . "<input name='update_products[$orders_products_id][qty]' size='2' value='" . $order->products[$i]['qty'] . "'>&nbsp;x</td>\n" .
        '      <td class="' . $RowStyle . '" valign="top">' . $order->products[$i]['name'] . "<input name='update_products[$orders_products_id][name]' type='hidden' size='25' value='" . $order->products[$i]['name'] . "'>";
}
    // Has Attributes?
   if (isset($order->products[$i]['attributes'])){
    if (sizeof($order->products[$i]['attributes']) > 0) {
      for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
        $orders_products_attributes_id = $order->products[$i]['attributes'][$j]['orders_products_attributes_id'];
        echo '<br><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ' : ' . $order->products[$i]['attributes'][$j]['value'] . ' ' . $order->products[$i]['attributes'][$j]['prefix'] . ' ' . $currencies->format($order->products[$i]['attributes'][$j]['price']) ;
        echo '</i></small>';
      }
    }
  }
if (ORDER_EDIT_EDT_PRICE == '1'){
  $edit_price =   ('      <td class="' . $RowStyle . '" align="right" valign="top">' . "<input name='update_products[$orders_products_id][final_price]' size='5' value='" . number_format($order->products[$i]['final_price'], 2, '.', '') . "'>" . '</td>');


} else {
  $edit_price =   ('      <td class="' . $RowStyle . '" align="right" valign="top">' . $currencies->format($order->products[$i]['final_price'], 1, $order->info['currency'], $order->info['currency_value']) . "<input name='update_products[$orders_products_id][final_price]' type='hidden' size='5' value='" . number_format($order->products[$i]['final_price'], 2, '.', '') . "'>" . '</td>' );
}

    echo '      </td>' . "\n" .
         '      <td class="' . $RowStyle . '" valign="top">' . $order->products[$i]['model'] . "<input name='update_products[$orders_products_id][model]' type='hidden' size='12' value='" . $order->products[$i]['model'] . "'>" . '</td>' . "\n" .
         '      <td class="' . $RowStyle . '" align="center" valign="top">' . "<input name='update_products[$orders_products_id][tax]' size='3' value='" . tep_display_tax_value($order->products[$i]['tax']) . "'>" . '</td>' . "\n" .
         '      <td class="' . $RowStyle . '" align="right" valign="top">' . $currencies->format($order->products[$i]['price'], 1, $order->info['currency'], $order->info['currency_value']). "<input name='update_products[$orders_products_id][price]' type='hidden' size='5' value='" . number_format($order->products[$i]['price'], 2, '.', '') . "'>" . '</td>' . "\n" .
         $edit_price . "\n" .
         '      <td class="' . $RowStyle . '" align="right" valign="top">' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], 1, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" ;
     if ( DISPLAY_PRICE_WITH_TAX == 'true'){
    echo '      <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], 1, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
      }
    echo '    </tr>' . "\n";
  }
  ?>
  <!-- End Products Listings Block -->

  <!-- Begin Order Total Block -->
    <tr>
      <td align="right" colspan="8">
        <table border="0" cellspacing="0" cellpadding="2" width="100%">
        <tr>
        <td align='center' valign='top'><br><?php echo '<a href="' . tep_href_link(FILENAME_EDIT_ORDERS, 'oID=' . $_GET['oID'] . '&action=add_product&step=1') . '">'. tep_image_button('button_add_product.gif', 'Add a product') . '&nbsp;</a></td>' ;?>
        <td align='right'>
        <table border="0" cellspacing="0" cellpadding="2">
<?php

        // Override order.php Class's Field Limitations
    $totals_query = tep_db_query("select * from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' order by sort_order");
    $order->totals = array();
    while ($totals = tep_db_fetch_array($totals_query)) {
      $order->totals[] = array('title' => $totals['title'], 'text' => $totals['text'], 'class' => $totals['class'], 'value' => $totals['value'], 'orders_total_id' => $totals['orders_total_id']);
    }
    $TotalsArray = array();
    for ($i=0; $i<sizeof($order->totals); $i++) {
      $TotalsArray[] = array("Name" => $order->totals[$i]['title'], "Price" => number_format($order->totals[$i]['value'], 2, '.', ''), "Class" => $order->totals[$i]['class'], "TotalID" => $order->totals[$i]['orders_total_id']);
      $TotalsArray[] = array("Name" => "          ", "Price" => "", "Class" => "ot_custom", "TotalID" => "0");
    }
  array_pop($TotalsArray);
  foreach($TotalsArray as $TotalIndex => $TotalDetails)
  {
    $TotalStyle = "smallText";
    if(($TotalDetails["Class"] == "ot_subtotal") || ($TotalDetails["Class"] == "ot_total"))
    {
      echo  '       <tr>' . "\n" .
        '   <td class="main" align="right"><b>' . $TotalDetails["Name"] . '</b></td>' .
        '   <td class="main" align="right"><b>' . $TotalDetails["Price"] .
            '<input name="update_totals[' . $TotalIndex . '][title]" type="hidden" value="' . trim($TotalDetails['Name']) . '" />' .
            '<input name="update_totals[' . $TotalIndex . '][value]" type="hidden" value="' . $TotalDetails['Price'] . '" size="6" />' .
            '<input name="update_totals[' . $TotalIndex . '][class]" type="hidden" value="' . $TotalDetails['Class'] . '" />' . "\n" .
            '<input type="hidden" name="update_totals[' . $TotalIndex . '][total_id]" value="' . $TotalDetails['TotalID'] . '"></b></td>' .
        '       </tr>' . "\n";
    }
    elseif($TotalDetails["Class"] == "ot_customer_discount")
        {
          echo  '       <tr>' . "\n" .
            '   <td class="main" align="right"><font color="#FF0000">' . ENTRY_CUSTOMER_DISCOUNT . '<b>' . $TotalDetails["Name"] . '</b></font></td>' .
            '   <td align="right" SPAN class="' . $TotalStyle . '">' . '<input name="update_totals[' . $TotalIndex . '][value]" size="6" value="' . $TotalDetails['Price'] . '"></SPAN>' .
                '<input name="update_totals[' . $TotalIndex . '][title]" type="hidden" value="' . trim($TotalDetails['Name']) . '" />' .
                '<input name="update_totals[' . $TotalIndex . '][class]" type="hidden" value="' . $TotalDetails['Class'] . '" />' . "\n" .
                '<input type="hidden" name="update_totals[' . $TotalIndex . '][total_id]" value="' . $TotalDetails['TotalID'] . '"></b></td>' .
            '       </tr>' . "\n";
    }
//discounts
    elseif(($TotalDetails["Class"] == "ot_gv") || ($TotalDetails["Class"] == "ot_coupon"))
        {
          echo  '       <tr>' . "\n" .
            '   <td class="main" align="right">' . ENTRY_CUSTOMER_GV . '<b>' . $TotalDetails["Name"] . '</b></td>' .
            '   <td align="right" class="' . $TotalStyle . '">' . '<input name="update_totals[' . $TotalIndex . '][value]" size="6" value="' . $TotalDetails['Price'] . '">' .
                '<input name="update_totals[' . $TotalIndex . '][title]" type="hidden" value="' . trim($TotalDetails['Name']) . '" />' .
                '<input name="update_totals[' . $TotalIndex . '][class]" type="hidden" value="' . $TotalDetails['Class'] . '" />' . "\n" .
                '<input type="hidden" name="update_totals[' . $TotalIndex . '][total_id]" value="' . $TotalDetails['TotalID'] . '"></b></td>' .
            '       </tr>' . "\n";
    }
    elseif($TotalDetails["Class"] == "ot_tax" || $TotalDetails["Class"] == "ot_buysafe")
 //taxes
    {
      echo  '       <tr>' . "\n" .
        '   <td class="main" align="right"><b>' . $TotalDetails["Name"] . '</b></td>' .
        '   <td class="main" align="right"><b>' . $TotalDetails["Price"] .
            '<input name="update_totals[' . $TotalIndex . '][title]" type="hidden" value="' . trim($TotalDetails['Name']) . '" />' .
            '<input name="update_totals[' . $TotalIndex . '][value]" type="hidden" value="' . $TotalDetails['Price'] . '" size="6" />' .
            '<input name="update_totals[' . $TotalIndex . '][class]" type="hidden" value="' . $TotalDetails['Class'] . '" />' . "\n" .
            '<input type="hidden" name="update_totals[' . $TotalIndex . '][total_id]" value="' . $TotalDetails['TotalID'] . '" /></b></td>' .
        '       </tr>' . "\n";
    }
        //  Shipping
    elseif($TotalDetails["Class"] == "ot_shipping")
    {
      //list exsisting shipping if not in current order_ship_meahtods table
           $orders_ship_methodA[] = array('id'   => $TotalDetails["Name"],
                                                    'text' => $TotalDetails["Name"]);

                  //  $orders_ship_method1 = array_merge($orders_ship_method, $orders_ship_methodA) ;
                  // check to see if method in totals  is in same as in order_ship_meahtods table
                          // if 0 merge if 1 dont merge

                         $orders_ship_method1 = array_merge($orders_ship_methodA, $orders_ship_method) ;


      echo  ' <tr>' . "\n" .
          '       <td align="right" class="' . $TotalStyle . '"><b>' . HEADING_SHIPPING . '</b>' . tep_draw_pull_down_menu('update_totals[' . $TotalIndex . '][title]', $orders_ship_method1, $TotalDetails["Name"]) . '</td>' . "\n";
      echo  ' <td align="right" class="' . $TotalStyle . '">' . '<input name="update_totals[' . $TotalIndex . '][value]" size="6" value="' . $TotalDetails['Price'] . '" />' .
            '<input type="hidden" name="update_totals[' . $TotalIndex . '][class]" value="' . $TotalDetails['Class'] . '" />' .
            '<input type="hidden" name="update_totals[' . $TotalIndex . '][total_id]" value="' . $TotalDetails['TotalID'] . '" /></td>' .
        '       </tr>' . "\n";
    }
    // End Shipping
    else
    {
      echo  '       <tr>' . "\n" .
          '   <td class="main" align="right"><b>' . $TotalDetails["Name"] . '</b></td>' .
        '   <td align="right" class="' . $TotalStyle . '">' . '<input type="hidden" name="update_totals[' . $TotalIndex . '][value]" size="6" value="' . $TotalDetails['Price'] . '" />' .
            '<input type="hidden" name="update_totals[' . $TotalIndex . '][title]" value="' . trim($TotalDetails['Name']) . '" />' .
            '<input type="hidden" name="update_totals[' . $TotalIndex . '][class]" value="' . $TotalDetails['Class'] . '" />' .
            '<input type="hidden" name="update_totals[' . $TotalIndex . '][total_id]" value="' . $TotalDetails['TotalID'] . '" />' .
            '</td>' . "\n" .
        '       </tr>' . "\n";
    }
  }
?>
        </table>
        </td>
        </tr>
        </table>
      </td>
    </tr>
  <!-- End Order Total Block -->

  </table></td>
      </tr>

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

      <tr>
        <td class="main"><table border="1" cellspacing="0" cellpadding="5">
          <tr>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
           </tr>
<?php

$orders_status_query = tep_db_query("select orders_status from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");
   while ($orders_status_1 = tep_db_fetch_array($orders_status_query)) {
       $orders_status_test = $orders_status_1['orders_status'];
       }
    $orders_history_query = tep_db_query("select * from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($oID) . "' order by date_added");
    if (tep_db_num_rows($orders_history_query)) {
      while ($orders_history = tep_db_fetch_array($orders_history_query)) {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
             '            <td class="smallText" align="center">';
        if ($orders_history['customer_notified'] == '1') {
          echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
        } else {
          echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
        }
          $order_status = '';
        if (isset($orders_status_array[$orders_history['orders_status_id']])){
            $order_status = $orders_status_array[$orders_history['orders_status_id']];
            }

        echo '            <td class="smallText">' . $order_status . '</td>' . "\n";
        echo '            <td class="smallText">' . nl2br(tep_db_output($orders_history['comments'])) . '&nbsp;</td>' . "\n";
        echo '          </tr>' . "\n";
      }
    } else {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
?>
        </table></td>
      </tr>

      <tr>
        <td class="main"><br><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
      </tr>
      <tr>
        <td class="main">
<?php
            echo tep_draw_textarea_field('comments', 'soft', '60', '5');
?>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo ENTRY_STATUS; ?></b> <?php echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status_number']); ?></td>
                <td align="center"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b> <?php echo tep_draw_checkbox_field('notify', '', 0); ?></td>
                <td class="main"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b> <?php echo tep_draw_checkbox_field('notify_comments', '', 0); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <?php
      // RCI edit orders below comments
      echo $cre_RCI->get('editorders', 'belowcomments');
      ?>      
      <tr>
        <td colspan="2" align="right">
        <?php 
        echo tep_image_submit('button_update.gif', IMAGE_UPDATE).'<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')),  'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
        if (isset($_GET[tep_session_name()])) {
          $oscid = '&' . tep_session_name() . '=' . $_GET[tep_session_name()];
        } else {
          $oscid = '';
        }
        echo '<a href="javascript:popupWindow(\'' .  (HTTP_SERVER . DIR_WS_ADMIN . FILENAME_ORDERS_INVOICE) . '?' . (tep_get_all_get_params(array('oID')) . 'oID=' . $_GET['oID']) . $oscid . '\')">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a><a href="javascript:popupWindow(\'' .  (HTTP_SERVER . DIR_WS_ADMIN . FILENAME_ORDERS_PACKINGSLIP) . '?' . (tep_get_all_get_params(array('oID')) . 'oID=' . $_GET['oID']) . $oscid . '\')">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a>'; 
        ?>
      </td>
    </tr>
      </form>
<?php
  }
//************************************************** add product ****************************
if($action == "add_product")
{

    if (isset($_GET['add_product_categories_id'])) {
      $add_product_categories_id = $_GET['add_product_categories_id'] ;
    }else if (isset($_POST['add_product_categories_id'])){
      $add_product_categories_id = $_POST['add_product_categories_id'] ;
    } else {
      $add_product_categories_id  = '' ;
    }

    if (isset($_GET['add_product_products_id'])) {
      $add_product_products_id = $_GET['add_product_products_id'] ;
    }else if (isset($_POST['add_product_products_id'])){
      $add_product_products_id = $_POST['add_product_products_id'] ;
    } else {
      $add_product_products_id  = '' ;
    }
  $customer_id = $order->customer['id'];
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo ADDING_TITLE; ?> #<?php echo $oID; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params(array('action', 'add_product')), 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>

<?php
  // ############################################################################
  //   Get List of All Products
  // ############################################################################
  $parent_ids = array();
  $parent_query = tep_db_query("SELECT DISTINCT products_parent_id
                                FROM " . TABLE_PRODUCTS . "
                                WHERE products_parent_id <> 0");
  while($parent = tep_db_fetch_array($parent_query)) {
    $parent_ids[$parent['products_parent_id']] = 1;
  }
  ksort($parent_ids);
  $ProductList = array();
  $CategoryList = array();
    $result = tep_db_query("SELECT
     pd.products_name,
     p.products_id,
     cd.categories_name,
     ptc.categories_id
    FROM " . TABLE_PRODUCTS . " p,
         " . TABLE_PRODUCTS_DESCRIPTION . " pd,
         " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc,
         " . TABLE_CATEGORIES_DESCRIPTION . " cd
    where cd.categories_id=ptc.categories_id
     and ptc.products_id=p.products_id
     and (p.products_status = '1'
     or (p.products_status <> '1' and p.products_parent_id <> 0))
     and p.products_id=pd.products_id
     and pd.language_id = '" . (int)$languages_id . "'
                            and cd.language_id = '" . (int)$languages_id . "'
    ORDER BY cd.categories_name");
    while($row = tep_db_fetch_array($result)) {
      if (isset($parent_ids[$row['products_id']])) {
        continue;
      }
      extract($row,EXTR_PREFIX_ALL,"db");
      $ProductList[$db_categories_id][$db_products_id] = $db_products_name;
      $CategoryList[$db_categories_id] = $db_categories_name;
      $LastCategory = $db_categories_name;
    }
    $LastOptionTag = "";
    $ProductSelectOptions = "<option value='0'>".DONT_ADD_NEW_PRODUCT . $LastOptionTag . "\n";
    $ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
    foreach($ProductList as $Category => $Products) {
      $ProductSelectOptions .= "<option value='0'>$Category" . $LastOptionTag . "\n";
      $ProductSelectOptions .= "<option value='0'>---------------------------" . $LastOptionTag . "\n";
      asort($Products);
      foreach($Products as $Product_ID => $Product_Name)
      {
        $ProductSelectOptions .= "<option value='$Product_ID'> &nbsp; $Product_Name" . $LastOptionTag . "\n";
      }

      if($Category != $LastCategory)
      {
        $ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
        $ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
      }
    }


  // ############################################################################
  //   Add Products Steps
  // ############################################################################

    echo '<tr><td><table border=\'0\'>' . "\n";
    ?>
      <tr>
        <td width="100%"><table class="main" border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main" align="right"><b><?php echo ADDPRODUCT_TEXT_PROGRESS ;?> </B></td>
            <td class="main" align="left">
            <?php
            if ($step == 1){
            echo '&nbsp;<b>' . ADDPRODUCT_TEXT_STEP_1 . '</b>' .  '&nbsp;' . ADDPRODUCT_TEXT_STEP_2  . '&nbsp;' . ADDPRODUCT_TEXT_STEP_3 . '&nbsp;' . ADDPRODUCT_TEXT_STEP_4  ;
            }
            if ($step == 2){
             echo '<a href="' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params() . 'step=1&add_product_categories_id='. $add_product_categories_id ) . '">' . '&nbsp;' . ADDPRODUCT_TEXT_STEP_1 . ' </a>' .  '&nbsp;<b>' . ADDPRODUCT_TEXT_STEP_2  . '</b>'. '&nbsp; ' . ADDPRODUCT_TEXT_STEP_3 . '&nbsp;' . ADDPRODUCT_TEXT_STEP_4 ;
            }
            if ($step == 3){
             echo '<a href="' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params() . 'step=1&add_product_categories_id='. $add_product_categories_id ) . '">' . '&nbsp;' . ADDPRODUCT_TEXT_STEP_1 . '</a>' .  '<a href="' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params() . 'step=2&add_product_categories_id='. $add_product_categories_id . '&add_product_products_id='. $add_product_products_id) . '">' . '&nbsp;' . ADDPRODUCT_TEXT_STEP_2  . '</a>' . '&nbsp;<b>' . ADDPRODUCT_TEXT_STEP_3 . '</b>'  . '&nbsp;' . ADDPRODUCT_TEXT_STEP_4;
            }
            if ($step == 4){
             echo '<a href="' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params() . 'step=1&add_product_categories_id='. $add_product_categories_id ) . '">' . '&nbsp;' . ADDPRODUCT_TEXT_STEP_1 . '</a>' .   '<a href="' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params() . 'step=2&add_product_categories_id='. $add_product_categories_id . '&add_product_products_id='. $add_product_products_id ) . '">' . '&nbsp;' . ADDPRODUCT_TEXT_STEP_2  . '</a>' .  '<a href="' . tep_href_link(FILENAME_EDIT_ORDERS, tep_get_all_get_params() . 'step=3&add_product_categories_id='. $add_product_categories_id . '&add_product_products_id='. $add_product_products_id ) . '">' . '&nbsp;' . ADDPRODUCT_TEXT_STEP_3 . '</a>' . '&nbsp;<b> ' . ADDPRODUCT_TEXT_STEP_4. '</b>';
            }


            ; ?></td>
           <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

          </tr>
        </table></td>
      </tr>
<?php
    // Set Defaults
      if(!isset($add_product_categories_id)){
      $add_product_categories_id = 0;
     }
      if(!isset($add_product_products_id)){
      $add_product_products_id = 0;
      }
    // Step 1: Choose Category
    if(($step >= 1) && ($step < 3)){
      echo '<tr><td><table border=\'0\'>' . "\n";
      print "<tr class=\"dataTableRow\"><form action='" . tep_href_link(FILENAME_EDIT_ORDERS, 'oID='.$oID.'&action=add_product') . "' method='POST'>\n";
      echo "<td class=\"dataTableContent\" valign=\"top\">";

     $tree = tep_get_category_tree('0');
      $dropdown= tep_draw_pull_down_menu('add_product_categories_id', $tree, '', ''); //single
      echo $dropdown;

      $CategoryOptions = str_replace("value='$add_product_categories_id'","value='$add_product_categories_id' selected", $CategoryOptions);
      print $CategoryOptions;
      print "</td>\n";
      print "<td class='dataTableContent' align='center'><input type='submit' value='" . TEXT_SELECT_CAT . "'>";
      print "<input type='hidden' name='step' value='2'>";
      print "</td>\n";
      print "</form></tr>\n";

      print "<tr><td colspan='3'>&nbsp;</td></tr>\n";
    }
    // Step 2: Choose Product
    if(($step > 1) && ($step < 3)) {
      print "<tr class=\"dataTableRow\"><form action='" . tep_href_link(FILENAME_EDIT_ORDERS, 'oID='.$oID.'&action='.$action) . "' method='POST'>\n";
      print "<td class='dataTableContent' valign='top'><select name='add_product_products_id'>";
      $ProductOptions = "<option value='0'> " . TEXT_ADD_PROD_CHOOSE;
      asort($ProductList[$add_product_categories_id]);
      foreach($ProductList[$add_product_categories_id] as $ProductID => $ProductName)
      {
      $ProductOptions .= "<option value='$ProductID'> $ProductName\n";
      }
      $ProductOptions = str_replace("value='$add_product_products_id'","value='$add_product_products_id' selected", $ProductOptions);
      print $ProductOptions;
      print "</select></td>\n";
      print "<td class='dataTableContent' align='center'><input type='submit' value='" . TEXT_SELECT_PROD . "'>";
      print "<input type='hidden' name='add_product_categories_id' value='$add_product_categories_id'>";
      print "<input type='hidden' name='step' value='3'>";
      print "</td>\n";
      print "</form></tr>\n";

      print "<tr><td colspan='3'>&nbsp;</td></tr>\n";
    }
// Step 3: Show product selected
if ($step == 3){

  if (isset($_GET['qty'])) {
    $qty = $_GET['qty'] ;
    }else if (isset($_POST['qty'])){
    $qty = $_POST['qty'] ;
    } else {
    $qty = '1' ;
   }
   if (isset($_POST['add_product_products_price'])){
   $p_products_price = $_POST['add_product_products_price'];
   }

// Eversun mod for show product price
   if ($add_product_products_id > 0) {
     $products = $pf->loadProduct($add_product_products_id, $languages_id, $customer_id);
     $p_products_price = $pf->computePrice('1');
   }
//if price is 0, get the regular price
if($p_products_price == 0){
    $product_price_query = tep_db_query("select products_price from " . TABLE_PRODUCTS . " where products_id = '" . $add_product_products_id . "'");
    if (tep_db_num_rows($product_price_query)) {
      $product_price = tep_db_fetch_array($product_price_query);
    $p_products_price = $product_price['products_price'];
    }
}

    $p_products_price = $currencies->format($p_products_price);
    $products_name_step = tep_get_products_name($add_product_products_id, (int)$languages_id);

// check to see if product has attibutes
          $products_id_query = $add_product_products_id;

$result_attributes = tep_db_query("SELECT * FROM
" . TABLE_PRODUCTS_ATTRIBUTES . " pa
WHERE
pa.products_id = '" . $products_id_query . "'");

$product_has_attributes = 0;

if(tep_db_num_rows($result_attributes) > 0) {
$product_has_attributes = 1;
}

  echo tep_draw_form('add_product', FILENAME_EDIT_ORDERS, 'oID='.$oID . '&action=add_product', 'post', '', 'SSL');

;?>
<td width="100%" align="left"><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
         <td class="main" align="left">
         <?php    echo '<b>' .TEXT_ADD_PROD. '</b>' .$add_product_products_id . '&nbsp;&nbsp;<b>' . TEXT_ADD_PROD_NAME .'</b>&nbsp;' . $products_name_step . '&nbsp;&nbsp;<b>' . TEXT_ADD_PROD_PRICE .'</b>&nbsp;' . $p_products_price. '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'; ?>
         </td>
        </tr>
   </table></td>
    </tr>
    <tr>
     <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
           <tr class="dataTableRow">
<?php
if ($product_has_attributes == 1) {

    $products_name_step = tep_get_products_name($add_product_products_id, (int)$languages_id);
    $products_id_query = $add_product_products_id;

$result = tep_db_query("SELECT * FROM
" . TABLE_PRODUCTS_ATTRIBUTES . " pa,
" . TABLE_PRODUCTS_OPTIONS . " po,
" . TABLE_PRODUCTS_OPTIONS_TEXT . " pot,
" . TABLE_PRODUCTS_OPTIONS_VALUES . " pov
WHERE
pa.products_id = '" . $products_id_query . "' and
pa.options_id = po.products_options_id and
pa.options_id = pot.products_options_text_id and
pa.options_values_id =pov.products_options_values_id
order by pa.products_options_sort_order, po.products_options_sort_order
");

    while($row = tep_db_fetch_array($result))
      {
       extract($row,EXTR_PREFIX_ALL,"db");
       $Options[$db_products_options_id] = $db_products_options_name;
       $ProductOptionValues[$db_products_options_id][$db_products_options_values_id] = $db_products_options_values_name;
     }

   if (isset($_POST['p_products_price'])){
   $p_products_price = $_POST['p_products_price'];
   }

;?>
     <td class="main"><strong><?php echo TEXT_PRODUCT_OPTIONS; ?></strong></td>
<?php
//Display attibutes in there native format
//intilize variables
$tax_rate = '';
$rows = '';
$cols = '';
          $products_id_query = $add_product_products_id;

    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . (int)$products_id_query . "' ");
    $products_attributes = tep_db_fetch_array($products_attributes_query);

     $products_options_query = tep_db_query("select pa.products_attributes_id, pa.options_id, pa.options_values_id, pa.options_values_price, pa.price_prefix, po.options_type, po.options_length, pot.products_options_name, pot.products_options_instruct from
           " . TABLE_PRODUCTS_ATTRIBUTES  . " AS pa,
           " . TABLE_PRODUCTS_OPTIONS  . " AS po,
           " . TABLE_PRODUCTS_OPTIONS_TEXT  . " AS pot
           where pa.products_id = '" . (int)$products_id_query . "'
             and pa.options_id = po.products_options_id
             and po.products_options_id = pot.products_options_text_id
             and pot.language_id = '" . (int)$languages_id . "'
           order by pa.products_options_sort_order, po.products_options_sort_order
           ");

      // Store the information from the tables in arrays for easy of processing
      $options = array();
      $options_values = array();
      while ($po = tep_db_fetch_array($products_options_query)) {
        //  we need to find the values name

        $product_attribute_id = $po['products_attributes_id'];



        if ( $po['options_type'] != 1  && $po['options_type'] != 4 ) {
          $options_values_query = tep_db_query("select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id ='". $po['options_values_id'] . "' and language_id = '" . (int)$languages_id . "'");
          $ov = tep_db_fetch_array($options_values_query);
        } else {
          $ov['products_options_values_name'] = '';
        }
        $options[$po['options_id']] = array('name' => $po['products_options_name'],
                                            'type' => $po['options_type'],
                                            'length' => $po['options_length'],
                                            'instructions' => $po['products_options_instruct'],
                                            'price' => $po['options_values_price'],
                                            'prefix' => $po['price_prefix'],
                                            'attrib_id' => $po['products_attributes_id'],
                                            );

        $options_values[$po['options_id']][$po['options_values_id']] =  array('name' => stripslashes($ov['products_options_values_name']),
                                                                              'price' => $po['options_values_price'],
                                                                              'prefix' => $po['price_prefix'],
                                                                              'attrib_id' => $po['products_attributes_id']);
      }
      if(!isset($tax_rate)) {
        $tax_rate = tep_get_tax_rate($product_info['products_tax_class_id']);
      }

      foreach ($options as $oID => $op_data) {
        switch ($op_data['type']) {

          case 1:
            $maxlength = ( $op_data['length'] > 0 ? ' maxlength="' . $op_data['length'] . '"' : '' );
            $attribute_price = $currencies->display_price($op_data['price'], $tax_rate);
            $tmp_html = '<input type="text" name="add_product_options[' . $op_data['attrib_id'] . ']"' . $maxlength . ' />';
?>
              <tr>
                <td class="main"><?php
                echo $op_data['name'] . ':' . ($op_data['instructions'] != '' ? '<br><span class="smallText">' . $op_data['instructions'] . '</span>' : '' );
                echo ($attribute_price >= 0 ? '<br><span class="smallText">' . $op_data['prefix'] . ' ' . $attribute_price . '</span>' : '' );
                 ;?>
                </td>
                <td class="main"><?php echo $tmp_html;  ?></td>
              </tr>
              <?php
            break;

          case 4:
            if ($op_data['length'] == '') {
             $tmp_html = '<textarea name="add_product_options[' . $op_data['attrib_id'] . ']" wrap="virtual"></textarea>';
            } else {
             $text_area_array = explode(';',$op_data['length']);
             $cols = '';
             $rows = '';
             if ($text_area_array[0] != '') $cols = ' cols="' . $text_area_array[0] . '" ';
             if (isset($text_area_array[1]) && $text_area_array[1] != '') $rows = ' rows="' .
            $text_area_array[1] . '" ';
            $attribute_price = $currencies->display_price($op_data['price'], $tax_rate);

             $tmp_html = '<textarea name="add_product_options[' . $op_data['attrib_id'] . ']" '.$rows.' '.$cols.'"
            wrap="virtual"></textarea>';
            }
?>
              <tr>
                <td class="main">
<?php
                echo $op_data['name'] . ':' . ($op_data['instructions'] != '' ? '<br><span class="smallText">' . $op_data['instructions'] . '</span>' : '' );
                echo ($attribute_price >= 0 ? '<br><span class="smallText">' . $op_data['prefix'] . ' ' . $attribute_price . '</span>' : '' );
;?>
                </td>
                <td class="main" align="center"><?php echo $tmp_html;  ?></td>
              </tr>
<?php
            break;

          case 2:
          //radio buttons
            $tmp_html = '';
            foreach ( $options_values[$oID] as $vID => $ov_data ) {
              if ( (float)$ov_data['price'] == 0 ) {
                  $price = '&nbsp;';
              } else {
                  $price = '(&nbsp;' . $ov_data['prefix'] . '&nbsp;' . $currencies->display_price($ov_data['price'], $tax_rate) . '&nbsp;)';
              }
              $tmp_html .= '<input type="radio" name="add_product_options[' . $ov_data['attrib_id'] . ']" value="' . $vID . '">' . $ov_data['name'] . '&nbsp;' . $price . '<br>';
            } // End of the for loop on the option value
?>
              <tr>
                <td class="main"><?php echo $op_data['name'] . ':' . ($op_data['instructions'] != '' ? '<br><span class="smallText">' . $op_data['instructions'] . '</span>' : '' ); ?></td>
                <td class="main"><?php echo $tmp_html;  ?></td>
              </tr>
<?php
            break;

          case 3:
          //check box
            $tmp_html = '';
            $i = 0;
            foreach ( $options_values[$oID] as $vID => $ov_data ) {
              if ( (float)$ov_data['price'] == 0 ) {
                $price = '&nbsp;';
              } else {
                $price = '(&nbsp;'.$ov_data['prefix'] . '&nbsp;' . $currencies->display_price($ov_data['price'], $tax_rate).'&nbsp;)';
              }
              $tmp_html .= '<input type="checkbox" name="add_product_options[' . $ov_data['attrib_id'] . ']" value="' . $vID . '">' . $ov_data['name'] . '&nbsp;' . $price . '<br>';
              $i++;
            }
?>
              <tr>
                <td class="main"><?php echo $op_data['name'] . ':' . ($op_data['instructions'] != '' ? '<br><span class="smallText">' . $op_data['instructions'] . '</span>' : '' ); ?></td>
                <td class="main"><?php echo $tmp_html;  ?></td>
              </tr>
<?php
            break;

          case 0:
          //drop down select
            $tmp_html = '<select name="add_product_options[' . $op_data['attrib_id'] . ']">';
            foreach ( $options_values[$oID] as $vID => $ov_data ) {
              if ( (float)$ov_data['price'] == 0 ) {
                $price = '&nbsp;';
              } else {
                $price = '(&nbsp; '.$ov_data['prefix'] . '&nbsp;' . $currencies->display_price($ov_data['price'], $tax_rate).'&nbsp;)';
              }
              $tmp_html .= '<option value="' . $ov_data['attrib_id'] . '">' . $ov_data['name'] . '&nbsp;' . $price .'</option>';
            } // End of the for loop on the option values
            $tmp_html .= '</select>';
?>
              <tr>
                <td class="main"><?php echo $op_data['name'] . ':' . ($op_data['instructions'] != '' ? '<br><span class="smallText">' . $op_data['instructions'] . '</span>' : '' ); ?></td>
                <td class="main"><?php echo $tmp_html;  ?></td>
              </tr>
<?php
            break;
        }  //end of switch
      } //end of while
?>
            </table>

        </tr>
        <tr>
           <td class="dataTableContent" align='center'><input type="submit" value="<?php echo SELECT_THESE_OPTIONS ;?>">
             <input type="hidden" name="oID" value="<?php echo $oID ;?>">
              <input type="hidden" name="step" value="4">
              <input type="hidden" name="add_product_products_id" value="<?php echo $add_product_products_id ;?>">
              <input type="hidden" name="product_options" value="1">
              <input type="hidden" name="$products_name_step" value="<?php echo $products_name_step;?>">
              <input type="hidden" name="add_products_price" value="<?php echo $p_products_price ;?>">
             </td>
         </tr>
      </table></td>
    </tr></form>
<?php
  } else { // has attibutes
;?>
        <tr>
            <td class="dataTableContent" align='center' colspan = '3'>
              <input type="submit" value="<?php echo ADDPRODUCT_TEXT_OPTIONS_NOTEXIST ;?>">
              <input type="hidden" name="oID" value="<?php echo $oID ;?>">
              <input type="hidden" name="step" value="4">
              <input type="hidden" name="add_product_products_id" value="<?php echo $add_product_products_id ;?>">
              <input type="hidden" name="product_options" value="0">
              <input type="hidden" name="products_name_step" value="<?php echo $products_name_step;?>">
              <input type="hidden" name="add_products_price" value="<?php echo $p_products_price ;?>">
             </td>
           </tr>
      </table></td>
    </tr></form>
<?php
  } //end  has attibutes
} //end step
//end step 3
//**************************************************step 4 ****************************
// Step 4: Confirm
   if($step == 4){
    $products_name_step = tep_get_products_name($add_product_products_id, (int)$languages_id);
   if (isset($_POST['add_products_price'])){
   $p_products_price = $_POST['add_products_price'];
   }
     ;?>    <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr class="dataTableRow">
            <?php
            echo  tep_draw_form('select_product', FILENAME_EDIT_ORDERS, 'oID='.$oID . '&action=add_product', 'post', '', 'SSL');
            ;?>
    <td width="100%" colspan= "3"><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
        <td class="main" align="left">
        <?php    echo '<b>' .TEXT_ADD_PROD. ':</b>&nbsp;' .$add_product_products_id . '&nbsp;&nbsp;<b>&nbsp;' . TEXT_ADD_PROD_NAME .':</b>&nbsp;' . $products_name_step . '&nbsp;&nbsp;<b>' . TEXT_ADD_PROD_PRICE .':</b>&nbsp;' . $p_products_price. '&nbsp;&nbsp;<b>';?>
         </td></tr>
      </table></td>
     </tr><tr>

            <td class="dataTableContent" align="left"></td>
            <td class="dataTableContent" valign="top"><input name="add_product_quantity" size="2" value="1"><?php echo TEXT_ADD_QUANTITY ;?></td>
            <td class="dataTableContent" align="center"><input type="submit" value="<?php echo TEXT_ADD_NOW ;?>">
      <?php
  $product_options = $_POST['product_options'];
  $add_product_options = (isset($_POST['add_product_options']) ? $_POST['add_product_options'] :'') ;

$option_id = '';
$value = '';
$option_value_id = '';


if ( (isset($add_product_options)) && is_array(($add_product_options)) )   {
$key = '';
$value = '';
      while (list($key, $value) = each($_POST['add_product_options'])) {

        if (!is_array($key)) {
          echo tep_draw_hidden_field('add_product_options[' .$key. ']', htmlentities(stripslashes($value), ENT_QUOTES)) . "\n";
        } else {
          while (list($k, $v) = each($value)) {
            echo tep_draw_hidden_field('add_product_options[' .$key. ']' . '[' . $k . ']', htmlentities(stripslashes($v), ENT_QUOTES)) . "\n";
          }
        }
      }
 }
        ;?>
    <input type="hidden" name="oID" value="<?php echo $oID ;?>">
    <input type="hidden" name="add_product_products_id" value="<?php echo $add_product_products_id ;?>">
    <input type="hidden" name="products_name_step" value="<?php echo $products_name_step;?>">
    <input type="hidden" name="add_products_price" value="<?php echo $p_products_price ;?>">
    <input type="hidden" name="step" value="5">
             </td>
             </tr>
         </table></td>
    </tr></form>
        <?php
    }// end step

        print "</table></td></tr>\n";
}// end add product
?>
    </table></td>
    </tr>
</table></td>
<!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
