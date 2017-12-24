<?php
/*
  $Id: paypal_xc.php,v 1.0.0.0 2007/11/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/

require_once(dirname(__FILE__) . '/paypal_xc/paypal_xc_base.php');
class paypal_xc extends paypal_xc_base {

    var $cards = array(array('id' => 'Visa', 'text' => 'Visa'),
                       array('id' => 'MasterCard', 'text' => 'MasterCard'),
                       array('id' => 'Discover', 'text' => 'Discover'),
                       array('id' => 'Amex', 'text' => 'American Express'));

    var $code, $title, $description, $enabled, $zone, $token, $avs, $cvv2, $trans_id, $response;

    function paypal_xc() {
      global $order;
      parent::paypal_xc_base();
      $this->code = 'paypal_xc';
      $this->enableDirectPayment = false;
      $this->avs = 'N/A';
      $this->title = MODULE_PAYMENT_PAYPAL_XC_TEXT_TITLE;
      $this->subtitle = MODULE_PAYMENT_PAYPAL_XC_TEXT_SUBTITLE;
      $this->description = MODULE_PAYMENT_PAYPAL_XC_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_PAYPAL_XC_SORT_ORDER;
      // changed for global PP module
      $this->service = MODULE_PAYMENT_PAYPAL_SERVICE;
      $this->enabled = (defined('MODULE_PAYMENT_PAYPAL_STATUS') && MODULE_PAYMENT_PAYPAL_STATUS == 
      'True' && $this->service == 'Express Checkout') ? true : false;
      //$this->enabled = (MODULE_PAYMENT_PAYPAL_XC_STATUS == 'True');

      $this->pci = true;

      // changed for global PP module
      $this->order_status = (defined('MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID')) ? 
      (int)MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID : 0;
      //if ((int)MODULE_PAYMENT_PAYPAL_XC_ORDER_STATUS_ID > 0) {
      //  $this->order_status = MODULE_PAYMENT_PAYPAL_XC_ORDER_STATUS_ID;
      //}

      //$this->zone = (int)MODULE_PAYMENT_PAYPAL_XC_ZONE;
      $this->zone = (int)MODULE_PAYMENT_PAYPAL_ZONE;
      if (is_object($order)) $this->update_status();
    }

    function update_status() {
        global $order;

        if ($this->enabled && ($this->zone > 0)) {
            $check_flag = false;
            $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where 
            geo_zone_id = '" . $this->zone . "' and ( zone_country_id = 0 or zone_country_id = '" . 
            $order->billing['country']['id'] . "' ) order by zone_id");
            while ($check = tep_db_fetch_array($check_query)) {
                if ($check['zone_id'] < 1) {
                    $check_flag = true;
                    break;
                } elseif ($check['zone_id'] == $order->billing['zone_id']) {
                    $check_flag = true;
                    break;
                }
            }

            if (!$check_flag) {
                $this->enabled = false;
            }
        }
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
      global $order;
      $selection = array();
      return $selection;
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      $confirmation = array('title' => MODULE_PAYMENT_PAYPAL_XC_TEXT_TITLE, 'fields' => array());
      return $confirmation;
    }

    function process_button() {
      return '';
    }

    function ec_step1() {
      global $order, $order_total_modules, $currency, $customer_first_name, $languages_id, 
      $currencies;

      if ( !is_object($order) ) {
        require_once(DIR_WS_CLASSES . 'order.php');
        $order = new order;
      }
      if ( !is_object($order_total_modules) ) {
        require(DIR_WS_CLASSES . 'order_total.php');//ICW ADDED FOR CREDIT CLASS SYSTEM
        $order_total_modules = new order_total;//ICW ADDED FOR CREDIT CLASS SYSTEM
        $order_total_modules->process();
      }

      // set the parameters
      if (defined('MODULE_ADDONS_ONEPAGECHECKOUT_STATUS') &&
      MODULE_ADDONS_ONEPAGECHECKOUT_STATUS == 'True') {
        $params['RETURNURL'] = tep_href_link(FILENAME_ORDER_CHECKOUT, '', 'SSL');
      } else {
        $params['RETURNURL'] = tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL');
      }

      $params['CANCELURL'] = tep_href_link(FILENAME_SHOPPING_CART);
      $params['REQCONFIRMSHIPPING'] = '0';      
      $params['PAYMENTREQUEST_0_AMT'] = number_format($order->info['total'] * $currencies->get_value($currency), 2, '.', '');
      if($order->info['shipping_cost'] > 0) {
        $params['PAYMENTREQUEST_0_SHIPPINGAMT']= number_format($order->info['shipping_cost'] * $currencies->get_value($currency), 2, '.', '');
      }
      if(!empty($_SESSION['cc_id'])) {
        $params['PAYMENTREQUEST_0_SHIPDISCAMT'] = "-".number_format($GLOBALS['ot_coupon']->deduction * $currencies->get_value($currency), 2, '.', '');
      }

      $params['ADDROVERRIDE'] = '1';
      $params['SOLUTIONTYPE'] = 'Sole';
      // set parameters if logged in only
      if (isset($_SESSION['customer_id'])) {
        if ($_SESSION['sendto'] == false) {
          $shipping_name = $order->customer['firstname'] . ' ' . $order->customer['lastname'];
          $shipping_street = $order->customer['street_address'];
          $shipping_city = $order->customer['city'];
          $shipping_postcode = $order->customer['postcode'];
          $country_id = $order->customer['country_id'];
          $zone_id = $order->customer['zone_id'];
          $state = $order->customer['state'];
        } else {
          $shipping_name = $order->delivery['firstname'] . ' ' . $order->delivery['lastname'];
          $shipping_street = $order->delivery['street_address'];
          $shipping_city = $order->delivery['city'];
          $shipping_postcode = $order->delivery['postcode'];
          $country_id = $order->delivery['country_id'];
          $zone_id = $order->delivery['zone_id'];
          $state = $order->delivery['state'];
        }

        $country = tep_get_countries($country_id, true);

        $billing_country = tep_get_countries($order->billing['country_id'], true);

        if ( $zone_id != 0 ) {
          $zone_id = tep_db_prepare_input($zone_id);
          $zone = tep_db_fetch_array(tep_db_query("SELECT zone_code FROM " . TABLE_ZONES . "
          WHERE zone_id = '" . $zone_id . "'"));
          $state = $zone['zone_code'];
        } else {
          $zone_name = tep_db_prepare_input($state);
          $zone = tep_db_fetch_array(tep_db_query("SELECT zone_code FROM " . TABLE_ZONES . "
          WHERE zone_name = '" . $zone_name . "' AND zone_country_id = '" . $country_id .
          "'"));
          if (tep_not_null($zone['zone_code'])) {
            $state = $zone['zone_code'];
          } else {
            $state = $zone_name;
          }
        }

        $address = array('PAYMENTREQUEST_0_SHIPTONAME' => $shipping_name,
                         'PAYMENTREQUEST_0_SHIPTOSTREET' => $shipping_street,
                         'PAYMENTREQUEST_0_SHIPTOCITY' => $shipping_city,
                         'PAYMENTREQUEST_0_SHIPTOSTATE' => $state,
                         'PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE' =>
                         $country['countries_iso_code_2'],
                         'PAYMENTREQUEST_0_SHIPTOZIP' => $shipping_postcode);

        $params = array_merge($params, $address);
      }

      $order_desc = '';
      $tmp_prod_qty = 0;
      $item_amount = 0;
      for ($i=0; $i<sizeof($order->products); $i++) {
        $order_desc .= $order->products[$i]['qty'] . ' x ' . ($order->products[$i]['name'])  . ', ';
        $products_price = $order->products[$i]['final_price'];
        $products_tax = $order->products[$i]['tax'];
        $quantity = $order->products[$i]['qty'];
        $tmp_prod_qty += $quantity;
        $params["L_PAYMENTREQUEST_0_NAME$i"] = $order->products[$i]['name'];
        $params["L_PAYMENTREQUEST_0_QTY$i"] = $quantity;

        if(DISPLAY_PRICE_WITH_TAX == 'false') {
          $tmp_amt = $order->products[$i]['final_price'];
          $params["L_PAYMENTREQUEST_0_AMT$i"]= number_format($tmp_amt * $currencies->get_value($currency), 2, '.', '');
        } else {
          $tmp_amt = $products_price + tep_calculate_tax($products_price, $products_tax);
          $params["L_PAYMENTREQUEST_0_AMT$i"] = number_format($tmp_amt * $currencies->get_value($currency), 2, '.', '');
        }
        $item_amount += $params["L_PAYMENTREQUEST_0_AMT$i"]*$quantity;
      }
      if(DISPLAY_PRICE_WITH_TAX == 'false' && $order->info['tax'] > 0) {
        $params['PAYMENTREQUEST_0_TAXAMT'] = number_format($order->info['tax'] * $currencies->get_value($currency), 2, '.', '');
      }
      $params['PAYMENTREQUEST_0_ITEMAMT'] = number_format($item_amount,2, '.', '');
      
      $tot = number_format(($params['PAYMENTREQUEST_0_ITEMAMT']+(isset($params['PAYMENTREQUEST_0_TAXAMT']) ? $params['PAYMENTREQUEST_0_TAXAMT'] : 0) + (isset($params['PAYMENTREQUEST_0_SHIPPINGAMT']) ? $params['PAYMENTREQUEST_0_SHIPPINGAMT'] : 0) + (isset($params['PAYMENTREQUEST_0_SHIPDISCAMT']) ? $params['PAYMENTREQUEST_0_SHIPDISCAMT'] : 0)),2, '.', '');

      if($params['PAYMENTREQUEST_0_AMT'] != $tot) {
        $params['PAYMENTREQUEST_0_AMT'] = $tot;
      }

      if(strlen($order_desc) > 127) {
        $order_desc = $tmp_prod_qty." x products in Carts   ";
      }
      $params['PAYMENTREQUEST_0_DESC'] = substr($order_desc, 0, -2);

      // set default
      $params['LOCALECODE'] = 'en_US';
      // add switch for HPP French and Espanol Language Redirect. The use of "de_DE" results    in English at PayPal???
      if (isset($_SESSION['language']) && $_SESSION['language'] == 'french') {
        $params['LOCALECODE'] = 'fr_FR';
      } else if (isset($_SESSION['language']) && $_SESSION['language'] == 'espanol') {
        $params['LOCALECODE'] = 'es_ES';
      } else if (isset($billing_country['countries_iso_code_2']) &&
      $billing_country['countries_iso_code_2'] != 'US') {
        $params['LOCALECODE'] = $billing_country['countries_iso_code_2'];
      }
      $response = $this->SetExpressCheckout($params);

      if ($this->is_successful($response) === true) {
        $token = $this->getField($response, 'TOKEN');
        tep_redirect($this->paypal_url . '?cmd=_express-checkout&token=' . $token);
      } else {
        tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, 'error=' . $this->error_msg));
      }
    }

    function ec_step2() {
      global $order, $sendto, $currency, $currencies;

      if (isset($_GET['token']) && $_GET['token'] != null) $_SESSION['token'] = $_GET['token'];
      if (isset($_GET['PayerID']) && $_GET['PayerID'] != null) $_SESSION['PayerID'] = 
      $_GET['PayerID'];

      if ( !tep_not_null($_SESSION['token']) ) {
        $this->ec_step1();
      }

      if ( !is_object($order) ) {
        require_once(DIR_WS_CLASSES . 'order.php');
        $order = new order;
      }

      if (!isset($order->customer['lastname']) || $order->customer['lastname'] == NULL) {
        // this was PWA so get the details via getTransactionDetails();
        $orderDetailsArr = $this->GetExpressCheckoutDetailsRequest($_SESSION['token'], 
        $_SESSION['PayerID']);
        $order->customer['firstname'] = $orderDetailsArr['FIRSTNAME'];
        $order->customer['lastname'] = $orderDetailsArr['LASTNAME'];
        $order->customer['street_address'] = $orderDetailsArr['SHIPTOSTREET'];
        $order->customer['city'] = $orderDetailsArr['SHIPTOCITY'];
        $order->customer['state'] = $orderDetailsArr['SHIPTOSTATE'];
        $order->customer['postcode'] = $orderDetailsArr['SHIPTOZIP'];
        $order->customer['country']['title'] = $orderDetailsArr['SHIPTOCOUNTRYNAME'];
        $order->customer['email_address'] = $orderDetailsArr['EMAIL'];
        $order->delivery['firstname'] = $orderDetailsArr['FIRSTNAME'];
        $order->delivery['lastname'] = $orderDetailsArr['LASTNAME'];
        $order->delivery['street_address'] = $orderDetailsArr['SHIPTOSTREET'];
        $order->delivery['city'] = $orderDetailsArr['SHIPTOCITY'];
        $order->delivery['postcode'] = $orderDetailsArr['SHIPTOZIP'];
        $order->delivery['state'] = $orderDetailsArr['SHIPTOSTATE'];
        $order->delivery['country']['title'] = $orderDetailsArr['SHIPTOCOUNTRYNAME'];
        $order->delivery['email_address'] = $orderDetailsArr['EMAIL'];
        $order->billing['firstname'] = $orderDetailsArr['FIRSTNAME'];
        $order->billing['lastname'] = $orderDetailsArr['LASTNAME'];
        $order->billing['street_address'] = $orderDetailsArr['PAYMENTREQUEST_0_SHIPTOSTREET'];
        $order->billing['city'] = $orderDetailsArr['PAYMENTREQUEST_0_SHIPTOCITY'];
        $order->billing['postcode'] = $orderDetailsArr['PAYMENTREQUEST_0_SHIPTOZIP'];
        $order->billing['state'] = $orderDetailsArr['PAYMENTREQUEST_0_SHIPTOSTATE'];
        $order->billing['country']['title'] = $orderDetailsArr['PAYMENTREQUEST_0_SHIPTOCOUNTRYNAME'];
        $order->billing['email_address'] = $orderDetailsArr['EMAIL'];
        $shiptoCountryCode = $orderDetailsArr['SHIPTOCOUNTRYCODE'];
      }

      if ($_SESSION['sendto'] == false) {
        $shipping_name = $order->customer['firstname'] . ' ' . $order->customer['lastname'];
        $shipping_street = $order->customer['street_address'];
        $shipping_city = $order->customer['city'];
        $shipping_postcode = $order->customer['postcode'];
        $country_id = $order->customer['country_id'];
        $zone_id = $order->customer['zone_id'];
        $state = $order->customer['state'];
      } else {
        $shipping_name = $order->delivery['firstname'] . ' ' . $order->delivery['lastname'];
        $shipping_street = $order->delivery['street_address'];
        $shipping_city = $order->delivery['city'];
        $shipping_postcode = $order->delivery['postcode'];
        $country_id = $order->delivery['country_id'];
        $zone_id = $order->delivery['zone_id'];
        $state = $order->delivery['state'];
      }

      $country = tep_get_countries($country_id, true);
      if ( tep_not_null($zone_id) ) {
        $zone_id = tep_db_prepare_input($zone_id);
        $zone = tep_db_fetch_array(tep_db_query("SELECT zone_code FROM " . TABLE_ZONES . " WHERE 
        zone_id = '" . $zone_id . "'"));
        $state = $zone['zone_code'];
      } else {
        $zone_name = tep_db_prepare_input($state);
        $zone = tep_db_fetch_array(tep_db_query("SELECT zone_code FROM " . TABLE_ZONES . " WHERE 
        zone_name = '" . $zone_name . "' AND zone_country_id = '" . $country_id . "'"));
        if (tep_not_null($zone['zone_code'])) {
          $state = $zone['zone_code'];
        } else {
          $state = $zone_name;
        }
      }

      $countryCode = (isset($country['countries_iso_code_2']) && 
      !empty($country['countries_iso_code_2'])) ? $country['countries_iso_code_2'] : NULL;
      if ($countryCode == NULL) $countryCode = (isset($shiptoCountryCode) && 
      !empty($shiptoCountryCode)) ? $shiptoCountryCode : 'US';

      $address = array('PAYMENTREQUEST_0_SHIPTONAME' => $shipping_name,
                       'PAYMENTREQUEST_0_SHIPTOSTREET' => $shipping_street,
                       'PAYMENTREQUEST_0_SHIPTOCITY' => $shipping_city,
                       'PAYMENTREQUEST_0_SHIPTOSTATE' => $state,
                       'PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE' => $countryCode,
                       'PAYMENTREQUEST_0_SHIPTOZIP' => $shipping_postcode);

      $response = $this->DoExpressCheckoutPayment($_SESSION['token'], $_SESSION['PayerID'], $address, 
      number_format($order->info['total'] * $currencies->get_value($currency), 2), $params);
      $this->trans_id = '';
      $this->response = $response;
      if ( !$this->is_successful($response) ) {
        unset($_SESSION['skip_payment']);
        if (defined('MODULE_ADDONS_ONEPAGECHECKOUT_STATUS') && MODULE_ADDONS_ONEPAGECHECKOUT_STATUS 
        == 'True') {
          tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=paypal_xc&error=' . 
          urlencode($this->error_msg), 'SSL'));
        } else {
          tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, 'error=' . $this->error_msg, 
          'SSL'));
        }
      } else {
        $this->trans_id = $this->getField($response, 'PAYMENTINFO_0_TRANSACTIONID');
      }
      $_SESSION['trans_id'] = $this->trans_id;

      return $this->trans_id;
    }

    function before_process() {
      $this->ec_step2();
    }

    function after_process() {
      global $insert_id, $customer_id, $language, $currency, $order;

      tep_db_query("update " . TABLE_ORDERS_STATUS_HISTORY. " set orders_status_id = '" . 
      MODULE_PAYMENT_PAYPAL_XC_ORDER_STATUS_ID . "', comments = concat(if (trim(comments) != '', 
      concat(trim(comments), '\n'), ''), 'Transaction ID: ". $_SESSION['trans_id'] . "') where 
      orders_id = " . $insert_id);

      if ( isset($_POST['create_account']) && $_POST['create_account'] == '1' ) {
        require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);

        $customers = tep_db_fetch_array(tep_db_query("select customers_gender, customers_firstname, 
        customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = 
        '" . $customer_id . "'"));
        $customer_name = $customers['customers_firstname'] . ' ' . $customers['customers_lastname'];
        if (ACCOUNT_GENDER == 'true') {
          if ($customers['customers_gender'] == 'm') {
            $email_text = sprintf(EMAIL_GREET_MR, $customers['customers_lastname']);
          } else {
            $email_text = sprintf(EMAIL_GREET_MS, $customers['customers_lastname']);
          }
        } else {
          $email_text = sprintf(EMAIL_GREET_NONE, $customers['customers_firstname']);
        }
        if (EMAIL_USE_HTML == 'true') {
          $formated_store_owner_email = '<a href="mailto:' . STORE_OWNER_EMAIL_ADDRESS . '">' . 
          STORE_OWNER . ': ' . STORE_OWNER_EMAIL_ADDRESS . '</a>';
        } else {
          $formated_store_owner_email = STORE_OWNER . ': ' . STORE_OWNER_EMAIL_ADDRESS;
        }
        $email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . $formated_store_owner_email . 
        "\n\n" . EMAIL_WARNING . $formated_store_owner_email . "\n\n";
        $email_text .= EMAIL_TEXT_PASSWORD . $_SESSION['temp_password'] . "\n\n";

        tep_mail($customer_name, $customers['customers_email_address'], EMAIL_SUBJECT, $email_text, 
        STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      } elseif ( $_SESSION['paypalxc_create_account'] == '1' ) {
        $_SESSION['noaccount'] = '1';
      }

      $tget_payment_date = $this->getField($this->response, 'PAYMENTINFO_0_ORDERTIME');      
      $get_payment_date = str_ireplace("T", " ", str_ireplace("Z", "", $tget_payment_date));    


      $txn_id = $this->getField($this->response, 'PAYMENTINFO_0_TRANSACTIONID');
      $txn_query = tep_db_query("select paypal_id from paypal where txn_id = '" . $txn_id . "'");
      if ( tep_db_num_rows($txn_query) == 0 ) {
        $sql_data = array('payment_type' => $this->getField($this->response, 'PAYMENTINFO_0_PAYMENTTYPE'),
                          'payment_status' => $this->getField($this->response, 'PAYMENTINFO_0_PAYMENTSTATUS'),
                          'mc_currency' => $this->getField($this->response, 'PAYMENTINFO_0_CURRENCYCODE'),
                          'mc_gross' => $this->getField($this->response, 'PAYMENTINFO_0_AMT'),
                          'mc_fee' => $this->getField($this->response, 'PAYMENTINFO_0_FEEAMT'),
                          'payment_date' => $get_payment_date,
                          'payer_id' => $_SESSION['PayerID'],
                          'receiver_id' => $_SESSION['token'],
                          'txn_id' => $txn_id,
                          'date_added' => 'now()');
        tep_db_perform('paypal', $sql_data);
        $paypal_id = tep_db_insert_id();
      } else {
        $txn_data = tep_db_fetch_array($txn_query);
        $paypal_id = $txn_data['paypal_id'];
      }
      tep_db_query("update " . TABLE_ORDERS . " set payment_id = '" . $paypal_id . "', orders_status 
      = '" . ((defined('MODULE_PAYMENT_PAYPAL_XC_ORDER_STATUS_ID') && 
      MODULE_PAYMENT_PAYPAL_XC_ORDER_STATUS_ID > 0 ) ? MODULE_PAYMENT_PAYPAL_XC_ORDER_STATUS_ID : 
      $this->order_status) . "' where orders_id = '" . $insert_id . "'");

      if (isset($_SESSION['xcSet'])) unset($_SESSION['xcSet']);
      if (isset($_SESSION['xcToken'])) unset($_SESSION['xcToken']);
      if (isset($_SESSION['xcPayerID'])) unset($_SESSION['xcPayerID']);
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " 
        where configuration_key = 'MODULE_PAYMENT_PAYPAL_XC_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, 
      configuration_value, configuration_description, configuration_group_id, sort_order, 
      set_function, date_added) values ('Enable PayPal Express Checkout', 
      'MODULE_PAYMENT_PAYPAL_XC_STATUS', 'True', 'Do you want to enable PayPal Express Checkout?<a 
      style=\"color: #0033cc;\" href=\"" . tep_href_link(FILENAME_PAYPAL, 'action=help', 'NONSSL') . 
      "\" target=\"paypalHelp\">[Help]</a>', '6', '10', 'tep_cfg_select_option(array(\'True\', 
      \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, 
      configuration_value, configuration_description, configuration_group_id, sort_order, 
      set_function, date_added) values ('Debug Mode', 'MODULE_PAYMENT_PAYPAL_XC_DEBUGGING', 'False', 
      'Would you like to enable debug mode?  A complete dump of transactions will be logged to the 
      debug file.', '6', '30', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, 
      configuration_value, configuration_description, configuration_group_id, sort_order, date_added) 
      values ('Sort order of display.', 'MODULE_PAYMENT_PAYPAL_XC_SORT_ORDER', '20', 'Sort order of 
      display. Lowest is displayed first.', '6', '130', now())");
      if(!defined('MODULE_PAYMENT_PAYPAL_XC_SERVER')) { tep_db_query("insert into " . 
      TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, 
      configuration_description, configuration_group_id, sort_order, set_function, date_added) values 
      ('Live or Sandbox API', 'MODULE_PAYMENT_PAYPAL_XC_SERVER', 'sandbox', 'Live: Live 
      transactions<br>Sandbox: For developers and testing', '6', '40', 
      'tep_cfg_select_option(array(\'live\', \'sandbox\'), ', now())");  }
      if(!defined('MODULE_PAYMENT_PAYPAL_XC_API_USERNAME')) { tep_db_query("insert into " . 
      TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, 
      configuration_description, configuration_group_id, sort_order, date_added) values ('API 
      Username', 'MODULE_PAYMENT_PAYPAL_XC_API_USERNAME', '', 'Your PayPal EC API Username', '6', 
      '50', now())"); }
      if(!defined('MODULE_PAYMENT_PAYPAL_XC_API_PASSWORD')) { tep_db_query("insert into " . 
      TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, 
      configuration_description, configuration_group_id, sort_order, date_added) values ('API 
      Password', 'MODULE_PAYMENT_PAYPAL_XC_API_PASSWORD', '', 'Your PayPal EC API Password', '6', 
      '60', now())"); }
      if(!defined('MODULE_PAYMENT_PAYPAL_XC_API_SIGNATURE')) { tep_db_query("insert into " . 
      TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, 
      configuration_description, configuration_group_id, sort_order, date_added) values ('API 
      Signature', 'MODULE_PAYMENT_PAYPAL_XC_API_SIGNATURE', '', 'Your PayPal EC API Signature', '6', 
      '70', now())"); }
      if(!defined('MODULE_PAYMENT_PAYPAL_XC_TRXTYPE')) { tep_db_query("insert into " . 
      TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, 
      configuration_description, configuration_group_id, sort_order, set_function, date_added) values 
      ('Transaction Type', 'MODULE_PAYMENT_PAYPAL_XC_TRXTYPE', 'Sale', 'Should customers be charged 
      immediately, or should we perform an authorization? If we perform authorizations, capture must 
      be handled manually by the store owner.)', '6', '90', 'tep_cfg_select_option(array(\'Sale\', 
      \'Authorization\'), ', now())"); }
      if(!defined('MODULE_PAYMENT_PAYPAL_XC_MERCHANT_COUNTRY')) { tep_db_query("insert into " . 
      TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, 
      configuration_description, configuration_group_id, sort_order, set_function, date_added) values 
      ('Merchant Country', 'MODULE_PAYMENT_PAYPAL_XC_MERCHANT_COUNTRY', 'US', 'The country of 
      merchant', '6', '120', 'tep_cfg_select_option(array(\'US\', \'UK\'), ', now())"); }
      if(!defined('MODULE_PAYMENT_PAYPAL_XC_ZONE')) { tep_db_query("insert into " . 
      TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, 
      configuration_description, configuration_group_id, sort_order, use_function, set_function, 
      date_added) values ('Payment Zone', 'MODULE_PAYMENT_PAYPAL_XC_ZONE', '0', 'If a zone is 
      selected, enable this payment method for that zone only.', '6', '140', 
      'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())"); }
      if(!defined('MODULE_PAYMENT_PAYPAL_XC_ORDER_STATUS_ID')) { tep_db_query("insert into " . 
      TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, 
      configuration_description, configuration_group_id, sort_order, set_function, use_function, 
      date_added) values ('Set Order Status', 'MODULE_PAYMENT_PAYPAL_XC_ORDER_STATUS_ID', '2', 'Set 
      the status of orders made with this payment module to this value', '6', '150', 
      'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())"); }
      if(!defined('MODULE_PAYMENT_PAYPAL_XC_REFUND_ORDER_STATUS_ID')) { tep_db_query("insert into " . 
      TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, 
      configuration_description, configuration_group_id, sort_order, set_function, use_function, 
      date_added) values ('Set Refund Order Status', 
      'MODULE_PAYMENT_PAYPAL_XC_REFUND_ORDER_STATUS_ID', '7', 'Set the status of refund orders made 
      with this payment module to this value', '6', '150', 'tep_cfg_pull_down_order_statuses(', 
      'tep_get_order_status_name', now())"); }
    }

    function remove() {
      if(defined('MODULE_PAYMENT_PAYPAL_STATUS') && MODULE_PAYMENT_PAYPAL_STATUS == 'True' ) {
        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . 
        implode("', '", $this->keys1()) . "')");
      }  else {
        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . 
        implode("', '", $this->keys()) . "')");
      }

    }
    function keys1() {
      return array('MODULE_PAYMENT_PAYPAL_XC_STATUS', 
      'MODULE_PAYMENT_PAYPAL_XC_DEBUGGING','MODULE_PAYMENT_PAYPAL_XC_SORT_ORDER');
    }

    function keys() {
      return array('MODULE_PAYMENT_PAYPAL_XC_STATUS', 'MODULE_PAYMENT_PAYPAL_XC_DEBUGGING', 
      'MODULE_PAYMENT_PAYPAL_XC_SERVER', 'MODULE_PAYMENT_PAYPAL_XC_API_USERNAME', 
      'MODULE_PAYMENT_PAYPAL_XC_API_PASSWORD', 'MODULE_PAYMENT_PAYPAL_XC_API_SIGNATURE', 
      'MODULE_PAYMENT_PAYPAL_XC_TRXTYPE', 'MODULE_PAYMENT_PAYPAL_XC_MERCHANT_COUNTRY', 
      'MODULE_PAYMENT_PAYPAL_XC_SORT_ORDER', 'MODULE_PAYMENT_PAYPAL_XC_ZONE', 
      'MODULE_PAYMENT_PAYPAL_XC_ORDER_STATUS_ID', 'MODULE_PAYMENT_PAYPAL_XC_REFUND_ORDER_STATUS_ID');
    }

    function get_error() {
        global $_GET, $language;
        require(DIR_WS_LANGUAGES . $language . '/modules/payment/' . FILENAME_PAYPAL_XC);

        $error = array('title' => MODULE_PAYMENT_PAYPAL_XC_ERROR_HEADING,
                       'error' => ((isset($_GET['error'])) ? stripslashes(urldecode($_GET['error'])) 
                       : MODULE_PAYMENT_PAYPAL_XC_TEXT_CARD_ERROR));

        return $error;
    }
}
?>