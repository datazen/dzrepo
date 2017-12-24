<?php
/**
  @name       pm2checkout.php   
  @version    2.2.1 | 05-21-2012 | datazen
  @author     Loaded Commerce Core Team
  @copyright  (c) 2012 loadedcommerce.com
  @license    GPL2
*/
class pm2checkout {
  var $code, $title, $description, $enabled;

  // class constructor
  function pm2checkout() {
    global $order;
    $this->signature = '2checkout|pm2checkout|1.1|2.2';
    $this->code = 'pm2checkout';
    $this->title = defined('MODULE_PAYMENT_2CHECKOUT_TEXT_TITLE') ? MODULE_PAYMENT_2CHECKOUT_TEXT_TITLE : '';
    $this->subtitle = defined('MODULE_PAYMENT_2CHECKOUT_TEXT_SUBTITLE') ? MODULE_PAYMENT_2CHECKOUT_TEXT_SUBTITLE : '';
    $this->public_title = defined('MODULE_PAYMENT_2CHECKOUT_TEXT_PUBLIC_TITLE') ? MODULE_PAYMENT_2CHECKOUT_TEXT_PUBLIC_TITLE : '';
    $this->description = defined('MODULE_PAYMENT_2CHECKOUT_TEXT_DESCRIPTION') ? MODULE_PAYMENT_2CHECKOUT_TEXT_DESCRIPTION : '';
    $this->sort_order = defined('MODULE_PAYMENT_2CHECKOUT_SORT_ORDER') ? (int)MODULE_PAYMENT_2CHECKOUT_SORT_ORDER : 0;
    $this->enabled = (defined('MODULE_PAYMENT_2CHECKOUT_STATUS') && MODULE_PAYMENT_2CHECKOUT_STATUS == 'True') ? true : false;
    $this->pci = true;
    if ((int)MODULE_PAYMENT_2CHECKOUT_ORDER_STATUS_ID > 0) {
      $this->order_status = MODULE_PAYMENT_2CHECKOUT_ORDER_STATUS_ID;
    }
    if (is_object($order)) $this->update_status();
    $this->form_action_url = 'https://www.2checkout.com/checkout/spurchase';
  }

  // class methods
  function update_status() {
    global $order;
    if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_2CHECKOUT_ZONE > 0) ) {
      $check_flag = false;
      $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_2CHECKOUT_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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

  function javascript_validation() {
    return false;
  }

  function selection() {
    return array('id' => $this->code,
                 'module' => $this->public_title . (strlen(MODULE_PAYMENT_2CHECKOUT_TEXT_PUBLIC_DESCRIPTION) > 0 ? ' (' . MODULE_PAYMENT_2CHECKOUT_TEXT_PUBLIC_DESCRIPTION . ')' : ''));
  }

  function pre_confirmation_check() {
    return false;
  }
 /**
  * Perform confirmation functions
  *
  * @access public
  * @return boolean
  */   
  function confirmation() {
    return false;
  }
 /**
  * Process button logic
  *
  * @access public
  * @return string
  */   
  function process_button() {
    global $currencies, $currency, $order, $languages_id;
	
    $tax = $order->info['tax'];
    $shipping = $order->info['shipping_cost'];
	
    $process_button_string = tep_draw_hidden_field('sid', MODULE_PAYMENT_2CHECKOUT_LOGIN) .
                             tep_draw_hidden_field('total', number_format($order->info['total'], 2)) .
                             tep_draw_hidden_field('cart_order_id', date('YmdHis')) .
                             tep_draw_hidden_field('fixed', 'Y') .
                             tep_draw_hidden_field('card_holder_name', $order->billing['firstname'] . ' ' . $order->billing['lastname']) .
                             tep_draw_hidden_field('street_address', $order->billing['street_address']) .
                             tep_draw_hidden_field('city', $order->billing['city']) .
                             tep_draw_hidden_field('state', $order->billing['state']) .
                             tep_draw_hidden_field('zip', $order->billing['postcode']) .
                             tep_draw_hidden_field('country', $order->billing['country']['title']) .
                             tep_draw_hidden_field('email', $order->customer['email_address']) .
                             tep_draw_hidden_field('phone', $order->customer['telephone']) .
                             tep_draw_hidden_field('ship_street_address', $order->delivery['street_address']) .
                             tep_draw_hidden_field('ship_city', $order->delivery['city']) .
                             tep_draw_hidden_field('ship_state', $order->delivery['state']) .
                             tep_draw_hidden_field('ship_zip', $order->delivery['postcode']) .
                             tep_draw_hidden_field('ship_country', $order->delivery['country']['title']) .
                             tep_draw_hidden_field('sh_cost', number_format($shipping['cost'], 2, '.', '')) .
                             tep_draw_hidden_field('2co_tax', number_format($tax, 2, '.', '')) ;

    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      $process_button_string .= tep_draw_hidden_field('c_prod_' . ($i+1), (int)$order->products[$i]['id'] . ',' . (int)$order->products[$i]['qty']) .
                                tep_draw_hidden_field('c_name_' . ($i+1), $order->products[$i]['name']) .
                                tep_draw_hidden_field('c_description_' . ($i+1), $order->products[$i]['name']) .
                                tep_draw_hidden_field('c_price_' . ($i+1), tep_round(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']), $currencies->currencies[$currency]['decimal_places']));
    }
    $process_button_string .= tep_draw_hidden_field('id_type', '1');
    if (defined('MODULE_PAYMENT_2CHECKOUT_TESTMODE') && MODULE_PAYMENT_2CHECKOUT_TESTMODE == 'Test') {
      $process_button_string .= tep_draw_hidden_field('demo', 'Y');
    }
    $process_button_string .= tep_draw_hidden_field('x_receipt_link_url', tep_href_link(FILENAME_CHECKOUT_PROCESS));
    // return URL is set in the Look and Feel section of your 2checkout account and should direct back to checkout_process.php
    $lang_query = tep_db_query("select code from " . TABLE_LANGUAGES . " where languages_id = '" . (int)$languages_id . "'");
    $lang = tep_db_fetch_array($lang_query);
    switch (strtolower($lang['code'])) {
      case 'es':
        $process_button_string .= tep_draw_hidden_field('lang', 'sp');
        break;
    }
    $process_button_string .= tep_draw_hidden_field('cart_brand_name', 'loadedcommerce') .
                              tep_draw_hidden_field('cart_version_name', PROJECT_VERSION);
                              
    return $process_button_string;
  }
 /**
  * Perform before process actions
  *
  * @access public
  * @return boolean
  */  
  function before_process() {
    if ($_REQUEST['credit_card_processed'] != 'Y') {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(MODULE_PAYMENT_2CHECKOUT_TEXT_ERROR_MESSAGE), 'SSL', true, false));
    }
  }
 /**
  * Perform after process actions
  *
  * @access public
  * @return void
  */
  function after_process() {
    global $order, $insert_id;
    if (MODULE_PAYMENT_2CHECKOUT_TESTMODE == 'Test') {
      $sql_data_array = array('orders_id' => (int)$insert_id, 
                              'orders_status_id' => (int)$order->info['order_status'], 
                              'date_added' => 'now()', 
                              'customer_notified' => '0',
                              'comments' => MODULE_PAYMENT_2CHECKOUT_TEXT_WARNING_DEMO_MODE);
      tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
    }

    if (tep_not_null(MODULE_PAYMENT_2CHECKOUT_SECRET_WORD) && (MODULE_PAYMENT_2CHECKOUT_TESTMODE == 'Production')) {
	     $compare_string = md5(MODULE_PAYMENT_2CHECKOUT_SECRET_WORD . MODULE_PAYMENT_2CHECKOUT_LOGIN . $_REQUEST['order_number'] . number_format($order->info['total'], 2));
      $compare_hash1 = strtoupper($compare_string);
      $compare_hash2 = $_REQUEST['key'];
      if ($compare_hash1 != $compare_hash2) {
        $sql_data_array = array('orders_id' => (int)$insert_id, 
                                'orders_status_id' => (int)$order->info['order_status'], 
                                'date_added' => 'now()', 
                                'customer_notified' => '0',
                                'comments' => "ORDER INVALID. MD5 HASH DID NOT MATCH.");

        tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
      }
    }
  }
 /**
  * Check the status of the payment module
  *
  * @access public
  * @return integer
  */  
  function check() {
    if (!isset($this->_check)) {
      $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_2CHECKOUT_STATUS'");
      $this->_check = tep_db_num_rows($check_query);
    }
    return $this->_check;
  }
 /**
  * Install the payment module
  *
  * @access public
  * @return void
  */   
  function install() {
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable 2CheckOut', 'MODULE_PAYMENT_2CHECKOUT_STATUS', 'True', 'Do you want to accept 2CheckOut payments?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Seller ID', 'MODULE_PAYMENT_2CHECKOUT_LOGIN', '', 'Seller ID used for the 2CheckOut service', '6', '0', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Mode', 'MODULE_PAYMENT_2CHECKOUT_TESTMODE', 'Test', 'Transaction mode used for the 2Checkout service', '6', '0', 'tep_cfg_select_option(array(\'Test\', \'Production\'), ', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Secret Word', 'MODULE_PAYMENT_2CHECKOUT_SECRET_WORD', '', 'The secret word to confirm transactions with (must be the same as defined on the merchat account configuration page', '6', '0', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_PAYMENT_2CHECKOUT_SORT_ORDER', '0', 'Sort order of display (lowest is displayed first)', '6', '0', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_2CHECKOUT_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
    tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_2CHECKOUT_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
  }
 /**
  * Remove the payment module
  *
  * @access public
  * @return void
  */   
  function remove() {
    tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }
  /**
  * Return the configuration values
  *
  * @access public
  * @return array
  */    
  function keys() {
    return array('MODULE_PAYMENT_2CHECKOUT_STATUS', 
                 'MODULE_PAYMENT_2CHECKOUT_LOGIN', 
                 'MODULE_PAYMENT_2CHECKOUT_TESTMODE', 
                 'MODULE_PAYMENT_2CHECKOUT_SECRET_WORD', 
                 'MODULE_PAYMENT_2CHECKOUT_ZONE', 
                 'MODULE_PAYMENT_2CHECKOUT_ORDER_STATUS_ID', 
                 'MODULE_PAYMENT_2CHECKOUT_SORT_ORDER');
  }
}
?>