<?php
/*
  $Id: checkout_ideal.php v2.1

  Released under the GNU General Public License

  Parts may be copyrighted by osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
*/

  include('includes/application_top.php');

  if (defined('MVS_STATUS') && MVS_STATUS == 'true') {  
    function vendors_email($vendors_id, $oID, $status, $vendor_order_sent) {
      $vendor_order_sent = false;
      $debug = 'no';
      $vendor_order_sent = 'no';
      $index2 = 0;
      
      //let's get the Vendors
      $vendor_data_query = tep_db_query("SELECT v.vendors_id, 
                                                v.vendors_name, 
                                                v.vendors_email, 
                                                v.vendors_contact, 
                                                v.vendor_add_info, 
                                                v.vendor_street, 
                                                v.vendor_city, 
                                                v.vendor_state, 
                                                v.vendors_zipcode, 
                                                v.vendor_country, 
                                                v.account_number, 
                                                v.vendors_status_send, 
                                                v.vendors_send_email, 
                                                os.shipping_module, 
                                                os.shipping_method, 
                                                os.shipping_cost, 
                                                os.shipping_tax, 
                                                os.vendor_order_sent 
                                           FROM " . TABLE_VENDORS . " v, 
                                                " . TABLE_ORDERS_SHIPPING . " os 
                                          WHERE v.vendors_id = os.vendors_id 
                                            AND v.vendors_id = '" . $vendors_id . "' 
                                            AND os.orders_id = '" . (int)$oID . "' 
                                            AND v.vendors_status_send = '" . $status . "'");
      while ($vendor_order = tep_db_fetch_array($vendor_data_query)) {
        $vendor_products[$index2] = array('Vid' => $vendor_order['vendors_id'],
                                          'Vname' => $vendor_order['vendors_name'],
                                          'Vemail' => $vendor_order['vendors_email'],
                                          'Vcontact' => $vendor_order['vendors_contact'],
                                          'Vaccount' => $vendor_order['account_number'],
                                          'Vstreet' => $vendor_order['vendor_street'],
                                          'Vcity' => $vendor_order['vendor_city'],
                                          'Vstate' => $vendor_order['vendor_state'],
                                          'Vzipcode' => $vendor_order['vendors_zipcode'],
                                          'Vcountry' => $vendor_order['vendor_country'],
                                          'Vaccount' => $vendor_order['account_number'],                               
                                          'Vinstructions' => $vendor_order['vendor_add_info'],
                                          'Vmodule' => $vendor_order['shipping_module'],                               
                                          'Vmethod' => $vendor_order['shipping_method'],
                                          'Vnotify' => $vendor_order['vendors_send_email']);
        if ($debug == 'yes') {
          echo 'The vendor query: ' . $vendor_order['vendors_id'] . '<br>';
        }
        $index = 0;
        $vendor_orders_products_query = tep_db_query("SELECT o.orders_id, 
                                                             o.orders_products_id, 
                                                             o.products_model, 
                                                             o.products_id, 
                                                             o.products_quantity, 
                                                             o.products_name, 
                                                             p.vendors_id, 
                                                             p.vendors_prod_comments, 
                                                             p.vendors_prod_id, 
                                                             p.vendors_product_price 
                                                        FROM " . TABLE_ORDERS_PRODUCTS . " o, 
                                                             " . TABLE_PRODUCTS . " p 
                                                       WHERE p.vendors_id = '" . (int)$vendor_order['vendors_id'] . "' 
                                                         AND o.products_id = p.products_id 
                                                         AND o.orders_id = '" . $oID . "' 
                                                    ORDER BY o.products_name");
        while ($vendor_orders_products = tep_db_fetch_array($vendor_orders_products_query)) {
          $vendor_products[$index2]['vendor_orders_products'][$index] = array(
                                    'Pqty' => $vendor_orders_products['products_quantity'],
                                    'Pname' => $vendor_orders_products['products_name'],
                                    'Pmodel' => $vendor_orders_products['products_model'],
                                    'Pprice' => $vendor_orders_products['products_price'],
                                    'Pvendor_name' => $vendor_orders_products['vendors_name'],
                                    'Pcomments' => $vendor_orders_products['vendors_prod_comments'],
                                    'PVprod_id' => $vendor_orders_products['vendors_prod_id'],
                                    'PVprod_price' => $vendor_orders_products['vendors_product_price'],
                                    'spacer' => '-');
          if ($debug == 'yes') {
            echo 'The products query: ' . $vendor_orders_products['products_name'] . "\n";
          }
          $subindex = 0;
          $vendor_attributes_query = tep_db_query("SELECT products_options, 
                                                          products_options_values, 
                                                          options_values_price, 
                                                          price_prefix 
                                                     FROM " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " 
                                                    WHERE orders_id = '" . (int)$oID . "' 
                                                      AND orders_products_id = '" . (int)$vendor_orders_products['orders_products_id'] . "'");
          if (tep_db_num_rows($vendor_attributes_query)) {
            while ($vendor_attributes = tep_db_fetch_array($vendor_attributes_query)) {
              $vendor_products[$index2]['vendor_orders_products'][$index]['vendor_attributes'][$subindex] = array('option' => $vendor_attributes['products_options'],
                                                                                                                  'value' => $vendor_attributes['products_options_values'],
                                                                                                                  'prefix' => $vendor_attributes['price_prefix'],
                                                                                                                  'price' => $vendor_attributes['options_values_price']);
              $subindex++;
            }
          }
          $index++;
        }
        
        $index2++;
        
        // let's build the email
        // Get the delivery address
        $delivery_address_query = tep_db_query("SELECT distinct delivery_company, 
                                                       delivery_name, 
                                                       delivery_street_address, 
                                                       delivery_city, 
                                                       delivery_state, 
                                                       delivery_postcode 
                                                  FROM " . TABLE_ORDERS . " 
                                                 WHERE orders_id = '" . $oID . "'") ;
        $vendor_delivery_address_list = tep_db_fetch_array($delivery_address_query);
        if ($debug == 'yes') {
          echo 'The number of vendors: ' . sizeof($vendor_products) . "\n";
        }
        $email = '';
        for ($l = 0, $m = sizeof($vendor_products); $l < $m; $l++) {
          $vendor_country = tep_get_country_name($vendor_products[$l]['Vcountry']);
          $order_number = $oID;
          $vendors_id = $vendor_products[$l]['Vid'];
          $the_email = $vendor_products[$l]['Vemail'];
          $notify_vendor = $vendor_products[$l]['Vnotify'];
          $the_name = $vendor_products[$l]['Vname'];
          $the_contact = $vendor_products[$l]['Vcontact'];
          $email = "\n" . 
          'To: ' . $the_contact . "\n" . 
          $the_name . "\n" . 
          $the_email . "\n" .
          $vendor_products[$l]['Vstreet'] . "\n" .
          $vendor_products[$l]['Vcity'] . ', ' . $vendor_products[$l]['Vstate'] . ' ' . $vendor_products[$l]['Vzipcode'] . ' ' . $vendor_country . "\n\n" . 
          EMAIL_SEPARATOR . "\n" . 
          'Special Comments or Instructions: ' . $vendor_products[$l]['Vinstructions'] . "\n\n" . 
          EMAIL_SEPARATOR . "\n" . 
          'From: ' . STORE_OWNER . "\n" . 
          STORE_NAME_ADDRESS . "\n" . 
          'Accnt #: ' . $vendor_products[$l]['Vaccount'] . "\n" . 
          EMAIL_SEPARATOR . "\n" . 
          EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . 
          EMAIL_SEPARATOR . "\n\n" . 
          'Shipping Method: ' . $vendor_products[$l]['Vmodule'] . ' -- '  .  $vendor_products[$l]['Vmethod'] . "\n" . 
          EMAIL_SEPARATOR . "\n\n" . 
          'Dropship deliver to:' . "\n" .
          $vendor_delivery_address_list['delivery_company'] . "\n" .
          $vendor_delivery_address_list['delivery_name'] . "\n" .
          $vendor_delivery_address_list['delivery_street_address'] . "\n" .
          $vendor_delivery_address_list['delivery_city'] .', ' . $vendor_delivery_address_list['delivery_state'] . ' ' . $vendor_delivery_address_list['delivery_postcode'] . "\n\n";
          $email .= 'Products:' . "\n" . 
          EMAIL_SEPARATOR . "\n";
          for ($i = 0, $n = sizeof($vendor_products[$l]['vendor_orders_products']); $i < $n; $i++) {
            $product_attribs = '';
            if (isset($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) && (sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']) > 0)) {
              for ($j = 0, $k = sizeof($vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes']); $j < $k; $j++) {
                $product_attribs .= ' -- ' . $vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes'][$j]['option'] . ': ' .  $vendor_products[$l]['vendor_orders_products'][$i]['vendor_attributes'][$j]['value'] . "\n";
              }
            }
            if (tep_not_null($vendor_products[$l]['vendor_orders_products'][$i]['Pmodel'])) {
              $prod_module = ' (' . $vendor_products[$l]['vendor_orders_products'][$i]['Pmodel'] . ')';
            } else {
              $prod_module = '';
            }
            $email .= $vendor_products[$l]['vendor_orders_products'][$i]['Pqty'] . ' X ' . $vendor_products[$l]['vendor_orders_products'][$i]['Pname'] . $prod_module . "\n" . $product_attribs . "\n";
          }
          if ($notify_vendor) {
            tep_mail($the_name, $the_email, EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID , $email . '<br>', STORE_NAME, STORE_OWNER_EMAIL_ADDRESS);
            $vendor_order_sent = 'yes';
          }
          tep_db_query("UPDATE " . TABLE_ORDERS_SHIPPING . " SET vendor_order_sent = '" . tep_db_input($vendor_order_sent) . "' WHERE orders_id = '" . (int)$oID . "' AND vendors_id = '" . (int)$vendors_id . "'");
          if ($debug == 'yes') {
            echo 'The $email(including headers:' . "\n" . 'Vendor Email Addy' . $the_email . "\n" . 'Vendor Name' . $the_name . "\n" . 'Vendor Contact' . $the_contact . "\n" . 'Body--' . "\n" . $email . "\n";
          }
        }
      }
      return true;
    } 
  }

  $ip = $_SERVER['REMOTE_ADDR'];
  $client = gethostbyaddr($_SERVER['REMOTE_ADDR']);
  $str = preg_split("/\./", $client);
  $i = count($str);
  $x = $i - 1;
  $n = $i - 2;
  $isp = (isset($str[$n]) ? $str[$n] : '') . "." . (isset($str[$x]) ? $str[$x] : '');
  // if the customer is not logged on, redirect them to the login page
  if (!isset($_SESSION['customer_id'])) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
  if (!isset($_SESSION['sendto'])) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
  }
  if ((tep_not_null(MODULE_PAYMENT_INSTALLED)) && (!isset($_SESSION['payment']))) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
  }
  // avoid hack attempts during the checkout procedure by checking the internal cartID
  if (!isset($_GET['order_id'])) {
    if (isset($cart->cartID) && isset($_SESSION['cartID'])) {
      if ($cart->cartID != $_SESSION['cartID']) {
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
      }
    }
  }
  include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PROCESS);
  // RCI checkoutprocess check
  echo $cre_RCI->get('checkoutprocess', 'check', false);
  
  // added for PPSM
  if (isset($_SESSION['sub_payment']) && $_SESSION['sub_payment'] == 'paypal_wpp_dp') $_SESSION['payment'] = 'paypal';
  
  // load selected payment module
  require(DIR_WS_CLASSES . 'payment.php');
  if (isset($_SESSION['credit_covers'])) $_SESSION['payment'] = ''; //ICW added for CREDIT CLASS
  $payment_modules = new payment($_SESSION['payment']);
  // load the selected shipping module
  if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
    include(DIR_WS_CLASSES . 'vendor_shipping.php');
  } else {
    include(DIR_WS_CLASSES . 'shipping.php');
  }
  $shipping_modules = new shipping($_SESSION['shipping']); 
  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;
  if (!class_exists('order_total')) {
    include(DIR_WS_CLASSES . 'order_total.php');
    $order_total_modules = new order_total;
  }
  $order_totals = $order_total_modules->process();
  // load the before_process function from the payment modules.
  // Authorize.net/QuickCommerce/PlugnPlay processing - this called moved to a later point
  // This is maintained for compatiblity with all other modules
  if (((defined('MODULE_PAYMENT_AUTHORIZENET_STATUS') && MODULE_PAYMENT_AUTHORIZENET_STATUS == 'True') && ($_SESSION['payment'] == 'authorizenet')) ||
     ((defined('MODULE_PAYMENT_PAYFLOWPRO_STATUS') && MODULE_PAYMENT_PAYFLOWPRO_STATUS =='True') && ($_SESSION['payment'] == 'payflowpro')) ||  
     ((defined('MODULE_PAYMENT_CREMERCHANT_AUTHORIZENET_STATUS') && MODULE_PAYMENT_CREMERCHANT_AUTHORIZENET_STATUS == 'True') && ($_SESSION['payment'] == 'CREMerchant_authorizenet')) ||
     ((defined('MODULE_PAYMENT_CRESECURE_STATUS') && MODULE_PAYMENT_CRESECURE_STATUS == 'True') && (MODULE_PAYMENT_CRESECURE_BRANDED == 'False') && ($_SESSION['payment'] == 'cresecure')) ||   
     ((defined('MODULE_PAYMENT_QUICKCOMMERCE_STATUS') && MODULE_PAYMENT_QUICKCOMMERCE_STATUS =='True') && ($_SESSION['payment'] == 'quickcommerce')) || 
     ((defined('MODULE_PAYMENT_PLUGNPAY_STATUS') && MODULE_PAYMENT_PLUGNPAY_STATUS =='True')  && ($_SESSION['payment'] == 'plugnpay'))){
     // don't load before process
  } elseif ((defined('MODULE_PAYMENT_PAYPAL_STATUS') && MODULE_PAYMENT_PAYPAL_STATUS == 'True') && ($_SESSION['payment'] == 'paypal')) {
    if (isset($_SESSION['sub_payment']) && $_SESSION['sub_payment'] == 'paypal_wpp_dp') {
      if (isset($_GET['action']) && $_GET['action'] == 'cancel') tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL')); 
      $order->info['order_status'] = MODULE_PAYMENT_CRESECURE_ORDER_STATUS_COMPLETE_ID;
    } else {
      if(isset($order->info['total']) && $order->info['total'] > 0) {
        $payment_modules->before_process();  
        include(DIR_WS_MODULES . 'payment/paypal/catalog/checkout_process.inc.php');
      } 
    }
  } else {  
    $payment_modules->before_process();
  }
  
  if ((PAYMENT_CC_CRYPT == 'True') && !empty($order->info['cc_number'])) {
    $cc_number1 = cc_encrypt($order->info['cc_number']);
    $cc_expires1 = cc_encrypt($order->info['cc_expires']);
  } else {
    $cc_number1 = $order->info['cc_number'];
    $cc_expires1 = $order->info['cc_expires'];
  }

  // IDEAL AANPASSING
	 $paymentid = $_SESSION['paymentid'];
  $orderid = tep_db_query("SELECT order_id FROM " . TABLE_IDEAL_PAYMENTS . " WHERE payment_id='" . $paymentid . "'");    
  $orderid = tep_db_fetch_array($orderid);
  $order = new order($orderid['order_id']);
  $insert_id = $orderid['order_id'];
  $customer_id = $order->customer['id'];
  require_once('admin/idealm_email.php');
  unset($_SESSION['paymentid']);
  unset($paymentid);
  unset($_SESSION['trans']);
  unset($trans);
  // EINDE IDEAL AANPASSING

  // multi-vendor shipping
  if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
    if ((defined('MVS_VENDOR_EMAIL_WHEN') && MVS_VENDOR_EMAIL_WHEN == 'Catalog') || (defined('MVS_VENDOR_EMAIL_WHEN') && MVS_VENDOR_EMAIL_WHEN == 'Both')) {
      $status=$order->info['order_status'];
      if (isset($status)) {
        $order_sent_query = tep_db_query("SELECT vendor_order_sent, vendors_id FROM " . TABLE_ORDERS_SHIPPING . " WHERE orders_id = '" . $insert_id . "'");
        while ($order_sent_data = tep_db_fetch_array($order_sent_query)) {
          $order_sent_ckeck = $order_sent_data['vendor_order_sent'];
          $vendors_id = $order_sent_data['vendors_id'];
          if ($order_sent_ckeck == 'no') {
            $status = '';
            $oID = $insert_id;
            $vendor_order_sent = false;
            $status = $order->info['order_status'];
            vendors_email($vendors_id, $oID, $status, $vendor_order_sent);
          }
        }
      }
    }
  }
  // multi-vendor shipping eof//
  
  // load the after_process function from the payment modules
  // record the customers order and ip address info for fraud screening process
  $ip = $_SERVER['REMOTE_ADDR'];
  $proxy = (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR']: '');
  if ($proxy != '') {
    $ip = $proxy;
  }
  $sql_data_array = array('order_id' => $insert_id,
                          'ip_address' => $ip);
  tep_db_perform('algozone_fraud_queries', $sql_data_array);
  $cart->reset(true);
  // unregister session variables used during checkout
  unset($_SESSION['sendto']);
  unset($_SESSION['billto']);
  unset($_SESSION['shipping']);
  unset($_SESSION['payment']);
  unset($_SESSION['comments']);
  if (isset($_SESSION['cot_gv'])) {
    unset($_SESSION['cot_gv']);
  }
  if (isset($_SESSION['credit_covers'])) unset($_SESSION['credit_covers']);

  /* IDEAAL/IDEAL AANPASSING */
  unset($_SESSION['paymentid']);
  unset($paymentid);
  unset($_SESSION['trans']);
  unset($trans);
  /* EINDE IDEAAL/IDEAL AANPASSING */
	 
  tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'order_id='. $insert_id . '&customer_id=' . $_SESSION['customer_id'], 'SSL'));
  
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>