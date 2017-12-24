<?php
 /**
  @name       loadedpayments.php   
  @version    1.0.0 | 05-21-2012 | datazen
  @author     Loaded Commerce Core Team
  @copyright  (c) 2012 LOADEDPAYMENTS.com
  @license    GPL2
*/
class loadedpayments {
  var $code, $title, $subtitle, $description, $sort_order, $enabled, $version, $form_action_url, $pci, $allowed_types = array();

  // class constructor
  function loadedpayments() {
    $this->code = 'loadedpayments';
    $this->title = (defined('MODULE_PAYMENT_LOADEDPAYMENTS_TEXT_TITLE') && MODULE_PAYMENT_LOADEDPAYMENTS_TEXT_TITLE != NULL) ? MODULE_PAYMENT_LOADEDPAYMENTS_TEXT_TITLE : NULL;
    $this->subtitle = (defined('MODULE_PAYMENT_LOADEDPAYMENTS_TEXT_SUBTITLE') && MODULE_PAYMENT_LOADEDPAYMENTS_TEXT_SUBTITLE != NULL) ? MODULE_PAYMENT_LOADEDPAYMENTS_TEXT_SUBTITLE : NULL;
    $this->description = (defined('MODULE_PAYMENT_LOADEDPAYMENTS_TEXT_DESCRIPTION') && MODULE_PAYMENT_LOADEDPAYMENTS_TEXT_DESCRIPTION != NULL) ? MODULE_PAYMENT_LOADEDPAYMENTS_TEXT_DESCRIPTION : NULL;
    $this->sort_order = (defined('MODULE_PAYMENT_LOADEDPAYMENTS_SORT_ORDER') && MODULE_PAYMENT_LOADEDPAYMENTS_SORT_ORDER != NULL) ? (int)MODULE_PAYMENT_LOADEDPAYMENTS_SORT_ORDER : NULL;
    $this->enabled = (defined('MODULE_PAYMENT_LOADEDPAYMENTS_STATUS') && MODULE_PAYMENT_LOADEDPAYMENTS_STATUS == 'True') ? TRUE : FALSE;
    $this->version = (!file_exists(DIR_WS_CLASSES . 'rci.php')) ? 'OSC' : 'LC'; 
    $this->form_action_url = 'loadedpayments.php';  
    $this->pci = TRUE;
    // create the credit card array
    $card_types = array('American Express' => MODULE_PAYMENT_LOADEDPAYMENTS_TEXT_AMEX,
                        'Mastercard' => MODULE_PAYMENT_LOADEDPAYMENTS_TEXT_MASTERCARD,
                        'Discover' => MODULE_PAYMENT_LOADEDPAYMENTS_TEXT_DISCOVER,
                        'Visa' => MODULE_PAYMENT_LOADEDPAYMENTS_TEXT_VISA);    

    $cc_array = (defined('MODULE_PAYMENT_LOADEDPAYMENTS_ACCEPTED_CC') && MODULE_PAYMENT_LOADEDPAYMENTS_ACCEPTED_CC != NULL) ? explode(', ', MODULE_PAYMENT_LOADEDPAYMENTS_ACCEPTED_CC) : array();
    while (list($key, $value) = each($cc_array)) {
      $this->allowed_types[$value] = $card_types[$value];
    }
    $_SESSION['payform_url'] = (defined('MODULE_PAYMENT_LOADEDPAYMENTS_TESTMODE') && MODULE_PAYMENT_LOADEDPAYMENTS_TESTMODE != 'Test') ? 'https://secure1.payleap.com/plcheckout.aspx' : 'https://uat.payleap.com/plcheckout.aspx'; 
  }
 /**
  * Update the module status
  *
  * @access public
  * @return void
  */  
  function update_status() {
    global $order;
    if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_LOADEDPAYMENTS_ZONE > 0) ) {
      $check_flag = false;
      $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_LOADEDPAYMENTS_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
      while ($check = tep_db_fetch_array($check_query)) {
        if ($check['zone_id'] < 1) {
          $check_flag = true;
          break;
        } elseif ($check['zone_id'] == $order->billing['zone_id']) {
          $check_flag = true;
          break;
        }
      }
      if ($check_flag == false) {
        $this->enabled = false;
      }
    }
  }  
 /**
  * Javascript validation logic
  *
  * @access public
  * @return boolean
  */  
  function javascript_validation() {
    return false;
  }
 /**
  * Get the card image string
  *
  * @access public
  * @return string
  */ 
  function get_cc_images() {
    $cc_images = '';
    reset($this->allowed_types);
    while (list($key, $value) = each($this->allowed_types)) {
      if ($key == 'American Express') $key = 'amex'; 
      $cc_images .= tep_image(DIR_WS_IMAGES . 'cards/lp_' . strtolower(str_replace(" ", "", $key)) . '.gif', $value);
    }

    return $cc_images;
  }
 /**
  * Create the payment selection string
  *
  * @access public
  * @return array
  */    
  function selection() {
    if (isset($_SESSION['loadedPaymentsCartID'])) {
      $loadedPaymentsCartID = $_SESSION['loadedPaymentsCartID'];
      $order_id = substr($loadedPaymentsCartID, strpos($loadedPaymentsCartID, '-')+1);
      $check_query = tep_db_query('select * from `' . TABLE_ORDERS_STATUS_HISTORY . '` where `orders_id` = "' . (int)$order_id . '" limit 1');
      if (tep_db_num_rows($check_query) < 1) {
        tep_db_query('delete from ' . TABLE_ORDERS . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_TOTAL . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_DOWNLOAD . ' where orders_id = "' . (int)$order_id . '"');

        unset($_SESSION['loadedPaymentsCartID']);
      }
    }        

    $selection = array('id' => $this->code,
                       'module' => '<table><tr><td width="260px" class="main" style=:font-weight:bold;">' . $this->title . '</td><td>' . $this->get_cc_images() . '</td></tr><tr><td colspan="2" class="main" style="font-weight:normal;">' . MODULE_PAYMENT_LOADEDPAYMENTS_BUTTON_DESCRIPTION . '</td></tr></table>');
                       
    return $selection;
  }
 /**
  * Perform the pre confirmaiton actions
  *
  * @access public
  * @return boolean
  */  
  function pre_confirmation_check() {
    global $cart;
    if (isset($cart->cartID) && $cart->cartID != '') {
      $cartID = $cart->cartID;     
    } else {
      $cartID = $cart->generate_cart_id();
    }
    if ((!isset($_SESSION['cartID'])) || (isset($_SESSION['cartID']) && $_SESSION['cartID'] == '')) {
      $_SESSION['cartID'] = $cartID;
    }
    return true;
  }
 /**
  * Perform the confirmation actions
  *
  * @access public
  * @return array
  */
  function confirmation() {
    global $cartID, $loadedPaymentsCartID, $customer_id, $currencies, $order, $order_total_modules;

    $insert_order = false;

    if (isset($_SESSION['loadedPaymentsCartID'])) {
      $loadedPaymentsCartID = $_SESSION['loadedPaymentsCartID'];
      $order_id = substr($loadedPaymentsCartID, strpos($loadedPaymentsCartID, '-')+1);

      $curr_check = tep_db_query("select currency from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
      $curr = tep_db_fetch_array($curr_check);
      
      /*echo '[' . $order_id . ']<br>';    
      echo '[' . $cartID . ']<br>';    
      echo '[' . $loadedPaymentsCartID . ']<br>';    
      echo '[' . $curr['currency'] . ']<br>';    
      echo '[' . $order->info['currency'] . ']<br>';    
      */
      
      if ( ($curr['currency'] != $order->info['currency']) || ($cartID != substr($loadedPaymentsCartID, 0, strlen($cartID))) ) {
        $check_query = tep_db_query('select orders_id from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '" limit 1');

        if (tep_db_num_rows($check_query) < 1) {
          tep_db_query('delete from ' . TABLE_ORDERS . ' where orders_id = "' . (int)$order_id . '"');
          tep_db_query('delete from ' . TABLE_ORDERS_TOTAL . ' where orders_id = "' . (int)$order_id . '"');
          tep_db_query('delete from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '"');
          tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS . ' where orders_id = "' . (int)$order_id . '"');
          tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . ' where orders_id = "' . (int)$order_id . '"');
          tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_DOWNLOAD . ' where orders_id = "' . (int)$order_id . '"');
        }
        $insert_order = true;
      }
    } else {
      $insert_order = true;
    }
      
    //echo '[' . $insert_order . ']<br>';    
    //die('55'); 
             
    if ($insert_order == true) {
      if (defined('MODULE_ADDONS_ONEPAGECHECKOUT_STATUS') && MODULE_ADDONS_ONEPAGECHECKOUT_STATUS == 'True') {
        if (is_array($order_total_modules->modules)) {
          // OPC has not run the process, so do so now
          $order_total_modules->process();
        }
      }
      $order_totals = array();
      if (is_array($order_total_modules->modules)) {
        reset($order_total_modules->modules);
        while (list(, $value) = each($order_total_modules->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
            for ($i=0, $n=sizeof($GLOBALS[$class]->output); $i<$n; $i++) {
              if (tep_not_null($GLOBALS[$class]->output[$i]['title']) && tep_not_null($GLOBALS[$class]->output[$i]['text'])) {
              // temp fix for buysafe issue
              if ($class == 'ot_buysafe') $GLOBALS[$class]->output[$i]['title'] = 'buySAFE Bond Guarantee:';
                $order_totals[] = array('code' => $GLOBALS[$class]->code,
                                        'title' => $GLOBALS[$class]->output[$i]['title'],
                                        'text' => $GLOBALS[$class]->output[$i]['text'],
                                        'value' => $GLOBALS[$class]->output[$i]['value'],
                                        'sort_order' => $GLOBALS[$class]->sort_order);
              }
            }
          }
        }
      }
      $sql_data_array = array('customers_id' => $customer_id,
                              'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
                              'customers_company' => $order->customer['company'],
                              'customers_street_address' => $order->customer['street_address'],
                              'customers_suburb' => $order->customer['suburb'],
                              'customers_city' => $order->customer['city'],
                              'customers_postcode' => $order->customer['postcode'],
                              'customers_state' => $order->customer['state'],
                              'customers_country' => $order->customer['country']['title'],
                              'customers_telephone' => $order->customer['telephone'],
                              'customers_email_address' => $order->customer['email_address'],
                              'customers_address_format_id' => $order->customer['format_id'],
                              'delivery_name' => $order->delivery['firstname'] . ' ' . $order->delivery['lastname'],
                              'delivery_company' => $order->delivery['company'],
                              'delivery_street_address' => $order->delivery['street_address'],
                              'delivery_suburb' => $order->delivery['suburb'],
                              'delivery_city' => $order->delivery['city'],
                              'delivery_postcode' => $order->delivery['postcode'],
                              'delivery_state' => $order->delivery['state'],
                              'delivery_country' => $order->delivery['country']['title'],
                              'delivery_address_format_id' => $order->delivery['format_id'],
                              'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'],
                              'billing_company' => $order->billing['company'],
                              'billing_street_address' => $order->billing['street_address'],
                              'billing_suburb' => $order->billing['suburb'],
                              'billing_city' => $order->billing['city'],
                              'billing_postcode' => $order->billing['postcode'],
                              'billing_state' => $order->billing['state'],
                              'billing_country' => $order->billing['country']['title'],
                              'billing_address_format_id' => $order->billing['format_id'],
                              'payment_method' => strip_tags($order->info['payment_method']),
                              'payment_info' => $cartID,
                              'cc_type' => $order->info['cc_type'],
                              'cc_owner' => $order->info['cc_owner'],
                              'cc_number' => $order->info['cc_number'],
                              'cc_expires' => $order->info['cc_expires'],
                              'date_purchased' => 'now()',
                              'orders_status' => MODULE_PAYMENT_LOADEDPAYMENTS_ORDER_STATUS_ID,
                              'currency' => $order->info['currency'],
                              'currency_value' => $order->info['currency_value']);
      if (isset($order->delivery['telephone']) && isset($order->delivery['email_address'])) {
        $sql_data_array['delivery_telephone'] = $order->delivery['telephone'];
        $sql_data_array['delivery_email_address'] = $order->delivery['email_address'];
      }
      if (isset($order->billing['telephone']) && isset($order->billing['email_address'])) {
        $sql_data_array['billing_telephone'] = $order->billing['telephone'];
        $sql_data_array['billing_email_address'] = $order->billing['email_address'];
      }
                              
                              
      tep_db_perform(TABLE_ORDERS, $sql_data_array);
      $insert_id = tep_db_insert_id();
      for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
        $sql_data_array = array('orders_id' => $insert_id,
                                'title' => $order_totals[$i]['title'],
                                'text' => $order_totals[$i]['text'],
                                'value' => $order_totals[$i]['value'],
                                'class' => $order_totals[$i]['code'],
                                'sort_order' => $order_totals[$i]['sort_order']);
        tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
      }
      $_SESSION['products_ordered'] = '';
      for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
        $sql_data_array = array('orders_id' => $insert_id,
                                'products_id' => tep_get_prid($order->products[$i]['id']),
                                'products_model' => $order->products[$i]['model'],
                                'products_name' => $order->products[$i]['name'],
                                'products_price' => $order->products[$i]['price'],
                                'final_price' => $order->products[$i]['final_price'],
                                'products_tax' => $order->products[$i]['tax'],
                                'products_quantity' => $order->products[$i]['qty']);
        tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
        $order_products_id = tep_db_insert_id();
        
        // product attributes
        $products_ordered_attributes = '';
        if (isset($order->products[$i]['attributes'])) {               
          for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
            if ($this->version == 'LC') {  
              $sql_data_array = array('orders_id' => $insert_id,
                                      'orders_products_id' => $order_products_id,
                                      'products_options' => $order->products[$i]['attributes'][$j]['option'],
                                      'products_options_values' => $order->products[$i]['attributes'][$j]['value'],
                                      'options_values_price' => $order->products[$i]['attributes'][$j]['price'],
                                      'price_prefix' => $order->products[$i]['attributes'][$j]['prefix'],
                                      'products_options_id' => $order->products[$i]['attributes'][$j]['option_id'],
                                      'products_options_values_id' => $order->products[$i]['attributes'][$j]['value_id']);
            } else {
              $sql_data_array = array('orders_id' => $insert_id,
                                      'orders_products_id' => $order_products_id,
                                      'products_options' => $order->products[$i]['attributes'][$j]['option'],
                                      'products_options_values' => $order->products[$i]['attributes'][$j]['value'],
                                      'options_values_price' => $order->products[$i]['attributes'][$j]['price'],
                                      'price_prefix' => $order->products[$i]['attributes'][$j]['prefix']);                  
            }
            tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);
            if (DOWNLOAD_ENABLED == 'true') {     
              $attributes_query = "SELECT pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename
                                     from " . TABLE_PRODUCTS_ATTRIBUTES . " pa,
                                          " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                   WHERE pa.products_id = '" . $order->products[$i]['id'] . "'
                                     and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "'
                                     and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "'
                                     and pa.products_attributes_id = pad.products_attributes_id";
                                     
              $attributes = tep_db_query($attributes_query);
              $attributes_values = tep_db_fetch_array($attributes);
              if ( isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename']) ) {
                $sql_data_array = array('orders_id' => $insert_id,
                                        'orders_products_id' => $order_products_id,
                                        'orders_products_filename' => $attributes_values['products_attributes_filename'],
                                        'download_maxdays' => $attributes_values['products_attributes_maxdays'],
                                        'download_count' => $attributes_values['products_attributes_maxcount']);
                tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
              }
            }
            $products_ordered_attributes .= "\n\t" . $order->products[$i]['attributes'][$j]['option'] . ' ' . $order->products[$i]['attributes'][$j]['value'] . ' ' . $order->products[$i]['attributes'][$j]['prefix'] . ' ' . $currencies->display_price($order->products[$i]['attributes'][$j]['price'], tep_get_tax_rate($products[$i]['tax_class_id']), 1);
          }
        } 
        $_SESSION['products_ordered'] .= $order->products[$i]['qty'] . ' x ' . tep_db_decoder($order->products[$i]['name']) . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
      } // end for      
      $loadedPaymentsCartID = $cartID . '-' . $insert_id;
      $_SESSION['loadedPaymentsCartID'] = $loadedPaymentsCartID;   
    }
    $confirmation = array('title' => $this->title);  
   
    return $confirmation;
  }
 /**
  * Create the process button string
  *
  * @access public
  * @return string
  */
  function process_button() {
    global  $order, $loadedPaymentsCartID;
   
    $order_id = substr($loadedPaymentsCartID, strpos($loadedPaymentsCartID, '-')+1);    
    $loginid = (defined('MODULE_PAYMENT_LOADEDPAYMENTS_USERNAME')) ? MODULE_PAYMENT_LOADEDPAYMENTS_USERNAME : '';
    $transactionkey = (defined('MODULE_PAYMENT_LOADEDPAYMENTS_TRANSKEY')) ? MODULE_PAYMENT_LOADEDPAYMENTS_TRANSKEY : '';  
    $sequence = rand(1, 1000); // a sequence number is randomly generated 
    $timestamp = time(); // a timestamp is generated 
    $amount = (float)number_format($order->info['total'], 2, '.', '');
    if( phpversion() >= "5.1.2" ) { 
      $fingerprint = hash_hmac("md5", $loginid . "" . $amount . "" . $sequence . "" . $timestamp . "", $transactionkey); 
    } else { 
      $fingerprint = bin2hex(mhash(MHASH_MD5, $loginid . "" . $amount . "" . $sequence . "" . $timestamp . "", $transactionkey)); 
    }      
    
    $process_button_string = tep_draw_hidden_field('loginid', $loginid) .
                             tep_draw_hidden_field('transactionkey', $transactionkey) .
                             tep_draw_hidden_field('firstname', (isset($order->billing['firstname'])) ? $order->billing['firstname'] : $order->customer['firstname']) .
                             tep_draw_hidden_field('lastname', (isset($order->billing['lastname'])) ? $order->billing['lastname'] : $order->customer['lastname']) .
                             tep_draw_hidden_field('address1', (isset($order->billing['street_address'])) ? $order->billing['street_address'] : $order->customer['street_address']) .
                             tep_draw_hidden_field('email', (isset($order->billing['email_address'])) ? $order->billing['email_address'] : $order->customer['email_address']) .
                             tep_draw_hidden_field('phone', (isset($order->billing['telephone'])) ? $order->billing['telephone'] : $order->customer['telephone']) .
                             tep_draw_hidden_field('city', (isset($order->billing['city'])) ? $order->billing['city'] : $order->customer['city']) . 
                             tep_draw_hidden_field('state', (isset($order->billing['state'])) ?  tep_get_zone_code($order->billing['country']['id'], $order->billing['zone_id'], '') :  tep_get_zone_code($order->customer['country']['id'], $order->customer['zone_id'], '')) . 
                             tep_draw_hidden_field('zip', (isset($order->billing['postcode'])) ? $order->billing['postcode'] : $order->customer['postcode']) .
                             tep_draw_hidden_field('country', (isset($order->billing['country']['iso_code_3'])) ? $order->billing['country']['iso_code_3'] : $order->customer['country']['iso_code_3']) .
                             tep_draw_hidden_field('amount', $amount) .
                             tep_draw_hidden_field('sequence', $sequence) .
                             tep_draw_hidden_field('timestamp', $timestamp) .
                             tep_draw_hidden_field('fingerprint', $fingerprint) .
                             tep_draw_hidden_field('invoicenumber', $order_id) .
                             tep_draw_hidden_field('ponumber', $order_id) . 
                             tep_draw_hidden_field('isRelayResponse', 'T') .
                             tep_draw_hidden_field('customField1', tep_session_name()) .
                             tep_draw_hidden_field('customField2', tep_session_id()) .
                             tep_draw_hidden_field('customField3', $order_id) .
                             tep_draw_hidden_field('relayResponseURL', tep_href_link('loadedpayments-relay.php', '', 'SSL', false, false));
                             
    //if (defined('MODULE_PAYMENT_LOADEDPAYMENTS_FORM_URL') && MODULE_PAYMENT_LOADEDPAYMENTS_FORM_URL != NULL) {   
    //} else {                     
      $process_button_string .= tep_draw_hidden_field('includeMerchantName', 'F') .
                                tep_draw_hidden_field('readonlyorderdetail', 'F') .
                                tep_draw_hidden_field('emailReceipt', 'T') .
                                tep_draw_hidden_field('includePO', 'F') .
                                tep_draw_hidden_field('includeInvoice', 'F') .
                                tep_draw_hidden_field('hideAddress', 'T') .
                                tep_draw_hidden_field('styleSheetURL', tep_href_link('loadedpayments.css', '', 'SSL', false, false)); 
    //}
                             
    return $process_button_string;
  }
 /**
  * Perform before process actions
  *
  * @access public
  * @return string
  */    
  function before_process() {
    global $order, $order_total_modules, $cart, $order_id;
  
    $cancel            = (isset($_POST['message']) && $_POST['messsage'] == 'Payment Cancelled') ? TRUE : FALSE;
    $order_id          = (isset($_GET['order_id'])) ? (int)$_GET['order_id'] : 0; 
    $response_code     = (isset($_POST['code'])) ? $_POST['code'] : NULL;
    $response_msg      = (isset($_POST['message'])) ? $_POST['message'] : '';
    $response_error    = (isset($_POST['error']) && $_POST['error'] == '1') ? TRUE : FALSE;   
    $response_authcode = (isset($_POST['authcode']) && !empty($_POST['authcode'])) ? $_POST['authcode'] : NULL; 
    $response_pnref    = (isset($_POST['pnref']) && !empty($_POST['pnref'])) ? $_POST['pnref'] : NULL; 
    $response_name     = (isset($_POST['ccOwner']) && !empty($_POST['ccOwner'])) ? $_POST['ccOwner'] : NULL;
    $response_type     = (isset($_POST['ccType']) && !empty($_POST['ccType'])) ? $_POST['ccType'] : NULL;
    $response_last4    = (isset($_POST['ccLast4']) && !empty($_POST['ccLast4'])) ? $_POST['ccLast4'] : NULL;
    $response_exp      = (isset($_POST['ccExp']) && !empty($_POST['ccExp'])) ? $_POST['ccExp'] : NULL;

    if ($response_code != '000') {
      if($response_error == TRUE) {
        tep_db_query('delete from ' . TABLE_ORDERS . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_TOTAL . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_DOWNLOAD . ' where orders_id = "' . (int)$order_id . '"');            
        $error_msg = $response_code . ' : ' . $response_msg; 
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode($error_msg), 'SSL', true, false));
      } else if ($cancel == TRUE) {           
        tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, '', 'SSL'));   
      } else {
        tep_db_query('delete from ' . TABLE_ORDERS . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_TOTAL . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_STATUS_HISTORY . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . ' where orders_id = "' . (int)$order_id . '"');
        tep_db_query('delete from ' . TABLE_ORDERS_PRODUCTS_DOWNLOAD . ' where orders_id = "' . (int)$order_id . '"');              
        $error_msg = ($error_msg = '') ? '360: Unknown payment module error' : $response_code . ' : ' . $response_msg; 
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode($error_msg), 'SSL', true, false));
      }
    }  
    // update orders table
    $sql_data_array = array('cc_owner' => $response_name,
                            'cc_type' => $response_type,
                            'cc_number' => str_repeat('X', 12) . $response_last4,
                            'cc_expires' => $response_exp,
                            );
    tep_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id = ' . (int)$order_id);
    // update orders status
    tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . (MODULE_PAYMENT_LOADEDPAYMENTS_ORDER_STATUS_COMPLETE_ID > 0 ? (int)MODULE_PAYMENT_LOADEDPAYMENTS_ORDER_STATUS_COMPLETE_ID : (int)DEFAULT_ORDERS_STATUS_ID) . "', last_modified = now() where orders_id = '" . (int)$order_id . "'");
    // update order status history
    
    $comments = (defined('MODULE_PAYMENT_LOADEDPAYMENTS_TESTMODE') && MODULE_PAYMENT_LOADEDPAYMENTS_TESTMODE == 'Test') ? ':TESTMODE: ' . $order->info['comments'] : $order->info['comments']; 
    $sql_data_array = array('orders_id' => $order_id,
                            'orders_status_id' => (MODULE_PAYMENT_LOADEDPAYMENTS_ORDER_STATUS_COMPLETE_ID > 0 ? (int)MODULE_PAYMENT_LOADEDPAYMENTS_ORDER_STATUS_COMPLETE_ID : (int)DEFAULT_ORDERS_STATUS_ID),
                            'date_added' => 'now()',
                            'customer_notified' => (SEND_EMAILS == 'true') ? '1' : '0',
                            'comments' => $comments);
    tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

    if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
      $shipping_array = $_SESSION['shipping']['vendor'];
      if(is_array($shipping_array)) {
        foreach ($shipping_array as $vendors_id => $shipping_data) {
          $vendors_query = tep_db_query("SELECT vendors_name
                                           from " . TABLE_VENDORS . "
                                         WHERE vendors_id = '" . (int)$vendors_id . "'"
                                       );
          $vendors_name = 'Unknown';
          if ($vendors = tep_db_fetch_array($vendors_query)) {
            $vendors_name = $vendors['vendors_name'];
          }
          $shipping_method_array = explode ('_', $shipping_data['id']);
          if ($shipping_method_array[0] == 'fedexwebservices') {
            $shipping_method = 'Federal Express';
          } elseif ($shipping_method_array[0] == 'upsxml') {
            $shipping_method = 'UPS';
          } elseif ($shipping_method_array[0] == 'usps') {
            $shipping_method = 'USPS';
          } else {
            $shipping_method = $shipping_method_array[0];
          }
          $sql_data_array = array('orders_id' => $order_id,
                                  'vendors_id' => $vendors_id,
                                  'shipping_module' => $shipping_method,
                                  'shipping_method' => $shipping_data['title'],
                                  'shipping_cost' => $shipping_data['cost'],
                                  'shipping_tax' =>  $shipping_data['ship_tax'],
                                  'vendors_name' => $vendors_name,
                                  'vendor_order_sent' => 'no'
                                 );
          tep_db_perform(TABLE_ORDERS_SHIPPING, $sql_data_array);
        }
      }
    }

    // stock update 
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      if (STOCK_LIMITED == 'true') {
        if (DOWNLOAD_ENABLED == 'true') {
          $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename
                                from " . TABLE_PRODUCTS . " p
                              LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                               on (p.products_id = pa.products_id)
                              LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                on (pa.products_attributes_id = pad.products_attributes_id)
                              WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
          // will work with only one option for downloadable products
          // otherwise, we have to build the query dynamically with a loop
          $products_attributes = $order->products[$i]['attributes'];
          if (is_array($products_attributes)) {
            $stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
          }
          $stock_query = tep_db_query($stock_query_raw);
        } else {
          $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
        }
        if (tep_db_num_rows($stock_query) > 0) {
          $stock_values = tep_db_fetch_array($stock_query);
          // do not decrement quantities if products_attributes_filename exists
          if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
            $stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];
          } else {
            $stock_left = $stock_values['products_quantity'];
          }
          tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
          if ( ($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false') ) {
            tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
          }
        }
      }
      // update products_ordered (for bestsellers list)
      tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
      
      if ($this->version == 'LC' && is_object($order_total_modules)) {
        $insert_id = $order_id;
        $order_total_modules->update_credit_account($i);//ICW ADDED FOR CREDIT CLASS SYSTEM
      }

    }
    // include affiliate checkout process
    if (isset($_SESSION['affiliate_ref'])) {
      require(DIR_WS_INCLUDES . 'affiliate_checkout_process.php');
    }    
    if ($this->version == 'LC' && is_object($order_total_modules)) $order_total_modules->apply_credit();   
    // send emails
    $this->sendEmail();
    // reset the cart
    $cart->reset(true);
    // unregister session variables used during checkout
    if (isset($_SESSION['sendto'])) unset($_SESSION['sendto']);
    if (isset($_SESSION['billto'])) unset($_SESSION['billto']);
    if (isset($_SESSION['shipping'])) unset($_SESSION['shipping']);
    if (isset($_SESSION['payment'])) unset($_SESSION['payment']);
    if (isset($_SESSION['comments'])) unset($_SESSION['comments']);
    if (isset($_SESSION['products_ordered'])) unset($_SESSION['products_ordered']);  
    if (isset($_SESSION['cot_gv'])) unset($_SESSION['cot_gv']);
    if (isset($_SESSION['credit_covers'])) unset($_SESSION['credit_covers']);  
    if (isset($_SESSION['loadedPaymentsCartID'])) unset($_SESSION['loadedPaymentsCartID']);  
    if (isset($_SESSION['cartID'])) unset($_SESSION['cartID']);  
    if (isset($_SESSION['payform_url'])) unset($_SESSION['payform_url']);  
    if ($this->version == 'LC' && is_object($order_total_modules)) $order_total_modules->clear_posts();
    $transID = (isset($response_pnref) && $response_pnref != '') ? '&pnref=' . $response_pnref : NULL;
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'order_id='. $order_id . '&customer_id=' . $_SESSION['customer_id'] . $transID, 'SSL'));
  } 
 /**
  * Perform after process actions
  *
  * @access public
  * @return boolean
  */
  function after_process() {
    return false;
  }
 /**
  * Send the order email
  *
  * @access public
  * @return boolean
  */  
  function sendEmail() {
    global $order, $order_id, $order_totals;
    
    if ( ! isset($_SESSION['noaccount']) ) {
      $email_order = STORE_NAME . "\n" .
                     EMAIL_SEPARATOR . "\n" .
                     EMAIL_TEXT_ORDER_NUMBER . ' ' . $order_id . "\n" .
                     EMAIL_TEXT_INVOICE_URL . ' ' .
                     tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $order_id, 'SSL', false) . "\n" .
                     EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";
    } else {
      $email_order = STORE_NAME . "\n" .
                     EMAIL_SEPARATOR . "\n" .
                     EMAIL_TEXT_ORDER_NUMBER . ' ' . $order_id . "\n" .
                     EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";
    }
    if ($order->info['comments']) {
      $email_order .= tep_db_output($order->info['comments']) . "\n\n";
    }
    $email_order .= EMAIL_TEXT_PRODUCTS . "\n" .
                    EMAIL_SEPARATOR . "\n" .
                    $_SESSION['products_ordered'] .
                    EMAIL_SEPARATOR . "\n";
    for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
      $email_order .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "\n";
    }

    if ($order->content_type != 'virtual') {
      $email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" . EMAIL_SEPARATOR . "\n" .
      tep_address_label($_SESSION['customer_id'], $_SESSION['sendto'], 0, '', "\n") . "\n";
    }
    $email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" . EMAIL_SEPARATOR . "\n" .
    tep_address_label($_SESSION['customer_id'], $_SESSION['billto'], 0, '', "\n") . "\n\n";
    $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" . EMAIL_SEPARATOR . "\n" . $this->title . "\n\n";
    tep_mail($order->customer['firstname'] . ' ' . $order->customer['lastname'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    // send emails to other people
    if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
      tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    }
    return true;
  }
 /**
  * Check the status of the payment module
  *
  * @access public
  * @return integer
  */
  function check() {
    if (!isset($this->_check)) {
      $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_LOADEDPAYMENTS_STATUS'");
      $this->_check = tep_db_num_rows($check_query);
    }
    return $this->_check;
  }
 /**
  * Install the module
  *
  * @access public
  * @return void
  */
  function install() {
    $check_query = tep_db_query("select orders_status_id from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'Preparing [Loaded Payments]' limit 1");
    if (tep_db_num_rows($check_query) < 1) {
      $status_query = tep_db_query("select max(orders_status_id) as status_id from " . TABLE_ORDERS_STATUS);
      $status = tep_db_fetch_array($status_query);
      $status_id = $status['status_id']+1;
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        tep_db_query("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $status_id . "', '" . $languages[$i]['id'] . "', 'Preparing [Loaded Payments]')");
      }
      $flags_query = tep_db_query("describe " . TABLE_ORDERS_STATUS . " public_flag");
      if (tep_db_num_rows($flags_query) == 1) {
        tep_db_query("update " . TABLE_ORDERS_STATUS . " set public_flag = 0 and downloads_flag = 0 where orders_status_id = '" . $status_id . "'");
      }
    } else {
      $check = tep_db_fetch_array($check_query);
      $status_id = $check['orders_status_id'];
    }    
    // remove any old test mode value
    tep_db_query("delete from " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_PAYMENT_LOADEDPAYMENTS_TESTMODE'");    
    // insert the new values
    tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Loaded Payments Module', 'MODULE_PAYMENT_LOADEDPAYMENTS_STATUS', 'True', 'Do you want to accept transparent payments through Loaded Payments?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('API Username', 'MODULE_PAYMENT_LOADEDPAYMENTS_USERNAME', '', 'Enter your Loaded Payments API Username', '6', '0', now())");
    tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Transaction Key', 'MODULE_PAYMENT_LOADEDPAYMENTS_TRANSKEY', '', 'Enter your Loaded Payments Transaction Key', '6', '0', now())");
    //tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Configured Form URL', 'MODULE_PAYMENT_LOADEDPAYMENTS_FORM_URL', '', 'Enter your Loaded Payments Configured Form URL.  Leave blank to use the Programatic Form Method with custom stylesheet. ', '6', '0', now())");
    if ($this->version == 'OSC') {
      tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Credit Cards Display', 'MODULE_PAYMENT_LOADEDPAYMENTS_ACCEPTED_CC', 'MasterCard, Visa', 'The credit card images you want displayed on the payment page.', '6', '0', now())");  
    } else {
      tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Credit Cards Display', 'MODULE_PAYMENT_LOADEDPAYMENTS_ACCEPTED_CC', 'MasterCard, Visa', 'The credit card images you want displayed on the payment page.', '6', '0', '_selectLPOptions(array(\'American Express\',\'Discover\',\'MasterCard\',\'Visa\'), ', now())");
    }    
    tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Mode', 'MODULE_PAYMENT_LOADEDPAYMENTS_TESTMODE', 'Test', 'Transaction mode used for processing orders', '6', '0', 'tep_cfg_select_option(array(\'Test\', \'Production\'), ', now())");
    tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_PAYMENT_LOADEDPAYMENTS_SORT_ORDER', '0', 'Sort order of display (lowest is displayed first)', '6', '0', now())");
    tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_LOADEDPAYMENTS_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
    tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Pending Order Status', 'MODULE_PAYMENT_LOADEDPAYMENTS_ORDER_STATUS_ID', '0', 'For Pending orders, set the status of orders made with this payment module to this value.  Default is \'Preparing [Loaded Payments]\'', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    tep_db_query("insert ignore into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Completed Order Status', 'MODULE_PAYMENT_LOADEDPAYMENTS_ORDER_STATUS_COMPLETE_ID', '0', 'For Completed orders, set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");

    if ($this->version == 'OSC') {                  
      tep_db_query("ALTER IGNORE TABLE `orders` ADD `payment_info` TEXT;");                     
    }  
  }
 /**
  * Remove the payment module
  *
  * @access public
  * @return void
  */    
  function remove() {
    tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    // remove order status 
    tep_db_query("delete from " . TABLE_ORDERS_STATUS . " where orders_status_name = 'Preparing [Loaded Payments]'");
    if ($this->version == 'OSC') {                  
      tep_db_query("ALTER IGNORE TABLE `orders` DROP `payment_info`;");                     
    }    
  }
 /**
  * Return the configuration values
  *
  * @access public
  * @return array
  */
  function keys() {
    return array('MODULE_PAYMENT_LOADEDPAYMENTS_STATUS', 
                 'MODULE_PAYMENT_LOADEDPAYMENTS_USERNAME', 
                 'MODULE_PAYMENT_LOADEDPAYMENTS_TRANSKEY', 
                 //'MODULE_PAYMENT_LOADEDPAYMENTS_FORM_URL', 
                 'MODULE_PAYMENT_LOADEDPAYMENTS_ACCEPTED_CC',
                 'MODULE_PAYMENT_LOADEDPAYMENTS_TESTMODE', 
                 'MODULE_PAYMENT_LOADEDPAYMENTS_SORT_ORDER',
                 'MODULE_PAYMENT_LOADEDPAYMENTS_ZONE',
                 'MODULE_PAYMENT_LOADEDPAYMENTS_ORDER_STATUS_ID',
                 'MODULE_PAYMENT_LOADEDPAYMENTS_ORDER_STATUS_COMPLETE_ID');
  }
} // end class
/**
* Create the checkbox options string
*
* @access public
* @return array
*/
function _selectLPOptions($select_array, $key_value, $key = '') {
  for ($i=0; $i<(sizeof($select_array)); $i++) {
    $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
    $string .= '<br><input type="checkbox" name="' . $name . '" value="' . $select_array[$i] . '"';
    $key_values = explode(", ", $key_value);
    if (in_array($select_array[$i], $key_values)) $string .= ' checked="checked"';
    $string .= '> ' . $select_array[$i];
  }
  
  return $string;
}
if (!function_exists('tep_db_decoder')) {
  function tep_db_decoder($string) {
    $string = str_replace('&#39;', "'", $string);
    $string = str_replace('&#39', "'", $string); //backward compatabiliy
    return $string;
  }
}  
?>