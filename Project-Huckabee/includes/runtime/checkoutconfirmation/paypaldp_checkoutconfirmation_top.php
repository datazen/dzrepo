<?php
/*
  $Id: paypaldp_checkoutconfirmation_insideformbelowbuttons.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
function process_dp_button() {

//  $dp_enabled = (defined('MODULE_PAYMENT_PAYPAL_STATUS') && MODULE_PAYMENT_PAYPAL_STATUS == 'True' && MODULE_PAYMENT_PAYPAL_SERVICE == 'Website Payments Pro') ? true : false;
//  if ( $dp_enabled ) {
    global $order, $language, $currency;

    if ( !is_object($order) ) {
      require_once(DIR_WS_CLASSES . 'order.php');
      $order = new order;
    }

    $username = (defined('MODULE_PAYMENT_PAYPAL_CRESECURE_LOGIN')) ? MODULE_PAYMENT_PAYPAL_CRESECURE_LOGIN : '';
    $password = (defined('MODULE_PAYMENT_PAYPAL_CRESECURE_PASS')) ? MODULE_PAYMENT_PAYPAL_CRESECURE_PASS : '';  
    $branded_url = (file_exists('checkout_payment_template.php')) ? 'checkout_payment_template.php' : 'default';   
    $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';
    if ($request_type == 'SSL') {
      $content_template_url = 'https://' . substr(tep_href_link($branded_url, '', 'NONSSL', false, false), strpos(tep_href_link($branded_url, '', 'NONSSL', false, false), '://')+3);
    } else {
      $content_template_url = '';  // no SSL = force default template page
    }      
    // calculate total weight and formulate order description
    $total_weight = 0;
    $order_desc = '';
    $order_array = array();
    for ($i=0; $i<sizeof($order->products); $i++) {
      $total_weight = $total_weight + ((int)$order->products[$i]['qty'] * (float)$order->products[$i]['weight']);
      $order_desc .= $order->products[$i]['qty'] . ' x ' . ($order->products[$i]['name']) . ', ';
    }
    $order_desc = substr($order_desc, 0, -2);

    $order_query = tep_db_fetch_array(tep_db_query("SELECT max(orders_id) as id from " . TABLE_ORDERS));
    $order_id = $order_query['id'] + 1;

    $_data = array('CRESecureID' => $username,
                   'CRESecureAPIToken' => $password,
                   'customer_company' => (isset($order->billing['company'])) ? $order->billing['company'] : $order->customer['company'],
                   'customer_firstname' => (isset($order->billing['firstname'])) ? $order->billing['firstname'] : $order->customer['firstname'],
                   'customer_lastname' => (isset($order->billing['lastname'])) ? $order->billing['lastname'] : $order->customer['lastname'],
                   'customer_address' => (isset($order->billing['street_address'])) ? $order->billing['street_address'] : $order->customer['street_address'],
                   'customer_email' => (isset($order->billing['email_address'])) ? $order->billing['email_address'] : $order->customer['email_address'],
                   'customer_phone' => (isset($order->billing['telephone'])) ? $order->billing['telephone'] : $order->customer['telephone'],
                   'customer_city' => (isset($order->billing['city'])) ? $order->billing['city'] : $order->customer['city'], 
                   'customer_state' => (isset($order->billing['state'])) ?  tep_get_zone_code($order->billing['country']['id'], $order->billing['zone_id'], '') :  tep_get_zone_code($order->customer['country']['id'], $order->customer['zone_id'], ''), 
                   'customer_postal_code' => (isset($order->billing['postcode'])) ? $order->billing['postcode'] : $order->customer['postcode'],
                   'customer_country' => (isset($order->billing['country']['iso_code_3'])) ? $order->billing['country']['iso_code_3'] : $order->customer['country']['iso_code_3'],
                   'delivery_company' => $order->delivery['company'],
                   'delivery_firstname' => $order->delivery['firstname'],
                   'delivery_lastname' => $order->delivery['lastname'],
                   'delivery_address' => $order->delivery['street_address'],
                   'delivery_email' => (isset($order->delivery['delivery_email'])) ? $order->delivery['delivery_email'] : $order->customer['email_address'],
                   'delivery_phone' => (isset($order->delivery['telephone'])) ? $order->delivery['telephone'] : $order->customer['telephone'],
                   'delivery_city' => $order->delivery['city'], 
                   'delivery_state' =>  tep_get_zone_code($order->delivery['country']['id'], $order->delivery['zone_id'], ''),
                   'delivery_postal_code' => $order->delivery['postcode'],
                   'delivery_country' => $order->delivery['country']['iso_code_3'],
                   'total_amt' => number_format($order->info['total'], 2),
                   'total_weight' => $total_weight,
                   'order_desc' => $order_desc,
                   'order_id' => $order_id,
                   'customer_id' => $_SESSION['customer_id'],
                   'currency_code' => $currency,
                   'lang' => $language,       
                   'allowed_types' => 'Visa|Mastercard',                                                                         
                   'sess_id' => tep_session_id(),
                   'sess_name' => tep_session_name(),
                   'ip_address' => $_SERVER["REMOTE_ADDR"],
                   'return_url' => tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', false, false),
                   'content_template_url' => $content_template_url); 

     // re-post vars
     $process_button_string = '';
     foreach ($_data as $key => $value) {
       $process_button_string .= tep_draw_hidden_field($key, $value);
     }

    //return $process_button_string;
    return $process_button_string;
  }
//}
?>