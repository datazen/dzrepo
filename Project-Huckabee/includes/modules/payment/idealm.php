<?php
/*
  $Id: idealm.php, v 2.1 2007/04/30 22:50:52 jb Exp $

  Released under the GNU General Public License

  Parts may be copyrighted by osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  ////////////////
  Original idea and first version by Wicher (wpe) 
  ////////////////
*/
  class idealm {
    var $code, $title, $description, $enabled, $identifier;

    function idealm() {
      global $order;
      $this->code = 'idealm';
      $this->title = MODULE_PAYMENT_IDEALM_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_IDEALM_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_IDEALM_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_IDEALM_STATUS == 'True') ? true : false);
      $this->identifier = 'iDEAL Payment Module v1.2';
      if (MODULE_PAYMENT_IDEALM_ORDER_PREMATURE == 'False') $this->form_action_url = FILENAME_IDEALM;
      $this->pci = TRUE;
      $this->order_status = MODULE_PAYMENT_IDEALM_ORDER_PENDING_STATUS_ID;
    }

    // class methods
    function update_status() {
      global $order;
    }

    function javascript_validation() {
      $js = "if (payment_value == '" . $this->code . "') {\n" .
            "  for (var i = 1; i < document.checkout_payment.issuerID.length; i++) {\n" .
            "    if (document.checkout_payment.issuerID[i].selected) {\n" .
            "      error = 0;\n" .
            "      return;\n" .
            "    }\n" .
            "    error_message = '" . MODULE_PAYMENT_IDEALM_TEXT_SELECT_ISSUER ."';\n" .
            "    error = 1;\n" .
            "  }\n" .
            "}\n";
      return $js;
    }

    function selection() {
      global $order;
      $issuers = array();

      if (($this->enabled == true) && ((int)MODULE_PAYMENT_IDEALM_ZONE > 0)) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_IDEALM_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->delivery['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
          return false;
        }
      }

      switch (MODULE_PAYMENT_IDEALM_OWN_BANK) {
        case 'ING/POSTBANK' :
          $url = MODULE_PAYMENT_IDEALM_ING_IP;
          $port = MODULE_PAYMENT_IDEALM_ING_PORT;
          break;
        case 'RABO' :
          $url = MODULE_PAYMENT_IDEALM_RABO_IP;
          $port = MODULE_PAYMENT_IDEALM_RABO_PORT;
          break;
        case 'ABN/AMRO' :
          $url = MODULE_PAYMENT_IDEALM_ABN_IP;
          $port = MODULE_PAYMENT_IDEALM_ABN_PORT;
          break;
      }

      $fp = fsockopen($url, $port, $errno, $errstr, 5);
      if (!$fp) {
        return array('id' => $this->code,
                     'module' => $this->title,
                     'error' => MODULE_PAYMENT_IDEALM_TEXT_NOT_AVAILABLE);
      } else { 
        fclose($fp);
      }

      $cachereturn = true; 
      if (MODULE_PAYMENT_IDEALM_CACHE != 0) {
        require_once(DIR_WS_FUNCTIONS . "cache.php");
        $cachereturn = read_cache($issuers,'issuers',MODULE_PAYMENT_IDEALM_CACHE * 86400);
      } 

      if ((MODULE_PAYMENT_IDEALM_CACHE == 0) || ($cachereturn == false)) {
        require_once(DIR_WS_CLASSES . "idealm.php");

        $data = new DirectoryRequest();
        $data->setMerchantID(MODULE_PAYMENT_IDEALM_MERCHANT_ID);
        $data->setSubID(MODULE_PAYMENT_IDEALM_SUB_ID);
        $data->setAuthentication(MODULE_PAYMENT_IDEALM_AUTHENTICATION);
        
        $rule = new ThinMPI();
        $result = $rule->ProcessRequest($data);

        if (!$result->isOk()) {
          return array('id' => $this->code,
                       'module' => $this->title,
                       'error' => MODULE_PAYMENT_IDEALM_TEXT_NOT_AVAILABLE);
        } else {
          $IssuerList = $result->getIssuerList();
          if (count($IssuerList) == 0) {
            return array('id' => $this->code,
                         'module' => $this->title,
                         'error' => MODULE_PAYMENT_IDEALM_TEXT_NOT_AVAILABLE);
          } else {
            $i=0;
            $issuers[$i]['id'] = 0;
            $issuers[$i]['text'] = MODULE_PAYMENT_IDEALM_TEXT_SELECT_ISSUER;
            $i++;
            foreach ($IssuerList as $Issuer => $wert) {
              $issuers[$i]['id'] = $wert->getIssuerID();
              $issuers[$i]['text'] = $wert->getIssuerName(); 
              $i++;
            }
            $acquirerID = $result->getAcquirerID;
            if (MODULE_PAYMENT_IDEALM_CACHE != 0) {
              $cachereturn = write_cache($issuers,'issuers');
            } 
          }
        }      
      } else {
      }
      return array('id' => $this->code,
                   'module' => $this->title,
                   'fields' => array(array('title' =>  tep_image(DIR_WS_IMAGES . 'icons/iDeal_small.gif', 'iDeal betaling'),
                                           'field' => tep_draw_pull_down_menu('issuerID', $issuers) . ' ')));
    }        

    function pre_confirmation_check() {            
      return false;
    }

    function before_process() {
      global $order, $trid, $ec, $_POST, $cart;

      if (MODULE_PAYMENT_IDEALM_ORDER_PREMATURE == 'True') {
        $order->info['order_status'] = MODULE_PAYMENT_IDEALM_ORDER_PENDING_STATUS_ID;
        return;
      }

      require_once(DIR_WS_CLASSES . "idealm.php");

      $trid = $_GET['trxid'];
      $ec = $_GET['ec'];

      $data = new AcquirerStatusRequest();
      $data->setMerchantID(MODULE_PAYMENT_IDEALM_MERCHANT_ID); 
      $data->setSubID(MODULE_PAYMENT_IDEALM_SUB_ID);
      $data->setAuthentication(MODULE_PAYMENT_IDEALM_AUTHENTICATION);   
      $data->setTransactionID($trid); 

      $rule = new ThinMPI();
      $result = $rule->ProcessRequest($data);

      if ((!$result->isOk()) || (!$result->isAuthenticated())) {
        $errorMsg = $result->getErrorMessage();
        $this->enabled = false;
        $orderstatus = MODULE_PAYMENT_IDEALM_ORDER_FAILED_STATUS_ID;
        $orderid = tep_db_query("SELECT order_id FROM " . TABLE_IDEAL_PAYMENTS . " WHERE transaction_id = '" . $trid . "' AND entrancecode = '" . $ec ."'");    
        $orderid = tep_db_fetch_array($orderid);
        tep_db_query("UPDATE " . TABLE_IDEAL_PAYMENTS . " SET payment_status = '" . $orderstatus . "', date_last_check = now() WHERE transaction_id = '" . $trid . "' AND entrancecode = '" . $ec ."'");
        tep_db_query("UPDATE " . TABLE_ORDERS . " SET orders_status = '" . $orderstatus . "' WHERE orders_id = '" . (int)$orderid['order_id'] . "'");
        tep_redirect(tep_href_link('idealm_error.php', 'errormsg=' . $errorMsg, 'SSL'));
      } else {
        $authenticated = $result->isAuthenticated();
        $consumerName = $result->getConsumerName();
        $consumerAccountNumber = $result->getConsumerAccountNumber();
        $consumerCity = $result->getConsumerCity();
        if (strtoupper($authenticated) == 'SUCCESS') {
          $order->info['order_status'] = MODULE_PAYMENT_IDEALM_ORDER_PAID_STATUS_ID;
        } elseif (strtoupper($authenticated) == 'OPEN') {
          $order->info['order_status'] = MODULE_PAYMENT_IDEALM_ORDER_PENDING_STATUS_ID;
        } elseif (strtoupper($authenticated) == 'FAILURE') {
          $order->info['order_status'] = MODULE_PAYMENT_IDEALM_ORDER_FAILED_STATUS_ID;
        } elseif (strtoupper($authenticated) == 'CANCELLED') {
          $order->info['order_status'] = MODULE_PAYMENT_IDEALM_ORDER_CANCELLED_STATUS_ID;
        } elseif (strtoupper($authenticated) == 'EXPIRED') {
          $order->info['order_status'] = MODULE_PAYMENT_IDEALM_ORDER_EXPIRED_STATUS_ID;
        } else {
          tep_redirect(tep_href_link('idealm_error.php', 'errormsg=' . $errorMsg, 'SSL'));
        }

        $orderid = tep_db_query("SELECT order_id FROM " . TABLE_IDEAL_PAYMENTS . " WHERE transaction_id = '" . $trid . "' AND entrancecode = '" . $ec ."'");    
        $orderid = tep_db_fetch_array($orderid);
        tep_db_query("UPDATE " . TABLE_IDEAL_PAYMENTS . " SET payment_status = '" . $orderstatus . "', date_last_check = now() WHERE transaction_id = '" . $trid . "' AND entrancecode = '" . $ec ."'");
        tep_db_query("UPDATE " . TABLE_ORDERS . " SET orders_status = '" . $orderstatus . "' WHERE orders_id = '" . (int)$orderid['order_id'] . "'");

        if (($orderstatus == MODULE_PAYMENT_IDEALM_ORDER_PENDING_STATUS_ID) || ($orderstatus == MODULE_PAYMENT_IDEALM_ORDER_PAID_STATUS_ID)) {
          if ($orderstatus == MODULE_PAYMENT_IDEALM_ORDER_PAID_STATUS_ID) {   
            tep_db_query("INSERT INTO " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) VALUES ('" . (int)$orderid['order_id'] . "', '" . $orderstatus . "', now(), '0', 'Payment verified by iDEAL system. Naam: " . $consumerName . " Rekening Nr: " . $consumerAccountNumber . " Plaatsnaam: " . $consumerCity . "')");
          } else {
            tep_db_query("INSERT INTO " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) VALUES ('" . (int)$orderid['order_id'] . "', '" . $orderstatus . "', now(), '0', 'Payment pending by iDEAL system.')");
          }
          $cart->contents = array();
          $cart->total = 0;
          $cart->weight = 0;
          $cart->content_type = false;
          if (isset($_SESSION['customer_id'])) {
            tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
            tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
          }
          tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL')); 
        } else {
          tep_db_query("INSERT INTO " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified) VALUES ('" . (int)$orderid['order_id'] . "', '" . $orderstatus . "', now(), '0')");
          tep_redirect(tep_href_link('idealm_error.php', 'errormsg=' . $errorMsg, 'SSL'));
        }
      }
    }

    function confirmation() {
      return false; 
    }

    function after_process() {
      global $order, $trid, $ec, $insert_id, $issuerID, $cart, $paymentid;

      if (MODULE_PAYMENT_IDEALM_ORDER_PREMATURE == 'False') {
        return;
      }

      require_once(DIR_WS_CLASSES . "idealm.php");
 
      if (!$_POST['issuerID']) {
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL')); 
      }

      if (MODULE_PAYMENT_IDEALM_CURRENCY == 'Selected Currency') {
        $my_currency = $currency;
      } else {
        $my_currency = substr(MODULE_PAYMENT_IDEALM_CURRENCY, 5);
      }
      if (!in_array($my_currency, array('CAD', 'EUR', 'GBP', 'JPY', 'USD'))) {
        $my_currency = 'EUR';
      }

      $entrance_code = tep_session_id();
      $issuerid = $_POST['issuerID'];

      if (!isset($_SESSION['paymentid'])) {
        $cart_contents = serialize($cart);
        tep_db_query("INSERT INTO " . TABLE_IDEAL_PAYMENTS . " (transaction_id, entrancecode, issuer_id, order_id, payment_status, date_last_check,cart_contents) VALUES (0, '$entrance_code', $issuerid, $insert_id, $this->order_status, now(), '$cart_contents')");
        $paymentid = tep_db_insert_id();
        
        // start patch veiligheidslek verlagen bestelbedrag 10-8-2007
        global $order;
        $iamount = round($order->info['total']*100, 0);
        // end patch
        tep_session_register('paymentid');
        $cart->contents = array();
        $cart->total = 0;
        $cart->weight = 0;
        $cart->content_type = false;
        if (isset($_SESSION['customer_id'])) {
          tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
          tep_db_query("DELETE FROM " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
        }
      } else { 
        $cart_contents = serialize($cart);
        tep_db_query("UPDATE " . TABLE_IDEAL_PAYMENTS . " SET cart_contents = '" . $cart_contents . "' WHERE payment_id = '" . $paymentid . "'");
        $idealpayments = tep_db_query("SELECT * FROM ". TABLE_IDEAL_PAYMENTS ." WHERE payment_id = '" . $paymentid . "'");
        $idealpayments = tep_db_fetch_array($idealpayments);
        $entrance_code = $idealpayments['entrancecode']; 
        $ordersid = $idealpayments['order_id']; 
        $ordertotal = tep_db_query("SELECT value FROM ". TABLE_ORDERS_TOTAL . " WHERE orders_id = '" . (int)$ordersid . "' AND class = 'ot_total'");
        $ordertotal = tep_db_fetch_array($ordertotal);
        $iamount = round($ordertotal['value'],2) * 100;
      }

      $data = new AcquirerTrxRequest();
      $data->setIssuerID($issuerid);
      $data->setMerchantID(MODULE_PAYMENT_IDEALM_MERCHANT_ID); 
      $data->setSubID(MODULE_PAYMENT_IDEALM_SUB_ID);
      $data->setAuthentication(MODULE_PAYMENT_IDEALM_AUTHENTICATION);
      $data->setMerchantReturnURL(tep_href_link(FILENAME_IDEALM, '', 'SSL'));
      $data->setPurchaseID($paymentid);
      $data->setAmount($iamount);
      $data->setCurrency($my_currency);
      $data->setExpirationPeriod('PT60M');
      $data->setLanguage('nl');
      $data->setDescription(MODULE_PAYMENT_IDEALM_SHOPPING_CART_DESCRIPTION);
      $data->setEntranceCode($entrance_code); 

      switch (MODULE_PAYMENT_IDEALM_OWN_BANK) {
        case 'ING/POSTBANK' :
          $data->setAcqURL(MODULE_PAYMENT_IDEALM_ING_IP . MODULE_PAYMENT_IDEALM_ING_PATH);
          break;
        case 'RABO' :
          $data->setAcqURL(MODULE_PAYMENT_IDEALM_RABO_IP . MODULE_PAYMENT_IDEALM_RABO_PATH . '/' . MODULE_PAYMENT_IDEALM_RABO_TRANSACTION_URL);
          break;
        case 'ABN/AMRO' :
          $data->setAcqURL(MODULE_PAYMENT_IDEALM_ABN_IP . MODULE_PAYMENT_IDEALM_ABN_PATH . '/' . MODULE_PAYMENT_IDEALM_ABN_TRANSACTION_PATH . '/' . MODULE_PAYMENT_IDEALM_ABN_TRANSACTION_URL);
          break;
      }
      
      $rule = new ThinMPI();

      $result = $rule->ProcessRequest( $data );

      if (!$result->isOk()) {
        $errorMsg = $result->getErrorMessage();
        $this->enabled = false;
        tep_redirect(tep_href_link('idealm_error.php', 'errormsg=' . $errorMsg, 'SSL'));
      } else {
        $acquirerID = $result->getAcquirerID;
        $IssuerAuthenticationURL = $result->getIssuerAuthenticationURL();
        $transactionID = $result->getTransactionID();
        tep_db_query("UPDATE " . TABLE_IDEAL_PAYMENTS . " SET transaction_id = '" . $transactionID . "' WHERE payment_id = '" . $paymentid . "'");
        tep_redirect(str_replace('&amp;', '&', $IssuerAuthenticationURL));
      }               
    }
    
    function process_button() {
      global $order, $languages_id, $currencies, $currency, $cart;

      $process_button_string =
      tep_draw_hidden_field('idealm_cartid', $idealm_oscid) .
      tep_draw_hidden_field('idealm_currency', $currency) .
      tep_draw_hidden_field('issuerID', $_POST['issuerID']) .
      tep_draw_hidden_field('idealm_amount', round($order->info['total']*100, 0));

      return($process_button_string);
    }    
    
    function get_status($transactionID, $entranceCode) {
      return false;
    }
    
    function keys() {
      return array('MODULE_PAYMENT_IDEALM_STATUS', 
                   'MODULE_PAYMENT_IDEALM_SORT_ORDER',
                   'MODULE_PAYMENT_IDEALM_MERCHANT_ID',
                   'MODULE_PAYMENT_IDEALM_SUB_ID',
                   'MODULE_PAYMENT_IDEALM_SHOPPING_CART_DESCRIPTION',
                   'MODULE_PAYMENT_IDEALM_OWN_BANK',
                   'MODULE_PAYMENT_IDEALM_CURRENCY', 
                   'MODULE_PAYMENT_IDEALM_ZONE', 
                   'MODULE_PAYMENT_IDEALM_ORDER_PREMATURE', 
                   'MODULE_PAYMENT_IDEALM_RESTOCK_TIME' ,
                   'MODULE_PAYMENT_IDEALM_ORDER_PENDING_STATUS_ID', 
                   'MODULE_PAYMENT_IDEALM_ORDER_PAID_STATUS_ID', 
                   'MODULE_PAYMENT_IDEALM_ORDER_FAILED_STATUS_ID', 
                   'MODULE_PAYMENT_IDEALM_ORDER_CANCELLED_STATUS_ID', 
                   'MODULE_PAYMENT_IDEALM_ORDER_EXPIRED_STATUS_ID', 
                   'MODULE_PAYMENT_IDEALM_RETURN_URL',
                   'MODULE_PAYMENT_IDEALM_ING_IP',
                   'MODULE_PAYMENT_IDEALM_ING_PATH',
                   'MODULE_PAYMENT_IDEALM_ING_PORT',
                   'MODULE_PAYMENT_IDEALM_RABO_IP',
                   'MODULE_PAYMENT_IDEALM_RABO_PATH',
                   'MODULE_PAYMENT_IDEALM_RABO_PORT',
                   'MODULE_PAYMENT_IDEALM_ABN_IP',
                   'MODULE_PAYMENT_IDEALM_ABN_PORT',
                   'MODULE_PAYMENT_IDEALM_ABN_DIRECTORY_PATH', 
                   'MODULE_PAYMENT_IDEALM_ABN_TRANSACTION_PATH', 
                   'MODULE_PAYMENT_IDEALM_ABN_STATUS_PATH', 
                   'MODULE_PAYMENT_IDEALM_ABN_DIRECTORY', 
                   'MODULE_PAYMENT_IDEALM_ABN_TRANSACTION', 
                   'MODULE_PAYMENT_IDEALM_ABN_STATUS',
                   'MODULE_PAYMENT_IDEALM_AUTHENTICATION',
                   'MODULE_PAYMENT_IDEALM_PRIVATE_KEY',
                   'MODULE_PAYMENT_IDEALM_PRIVATE_PASSWORD',
                   'MODULE_PAYMENT_IDEALM_CERT_DIR',
                   'MODULE_PAYMENT_IDEALM_OWN_CERT',
                   'MODULE_PAYMENT_IDEALM_TRUSTED_CERT',
                   'MODULE_PAYMENT_IDEALM_CACHE',
                   'MODULE_PAYMENT_IDEALM_LOGGING',
                   'MODULE_PAYMENT_IDEALM_LOGFILE'); 
    }

    function install() {
      $index = 1; 
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('<hr>iDeal v2.1 for LoadedCommerce<hr>Enable iDEAL Module', 'MODULE_PAYMENT_IDEALM_STATUS', 'True', 'Do you want to accept iDEAL payments?', 6, $index, 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort order of display.', 'MODULE_PAYMENT_IDEALM_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<hr>Merchant Info<hr>MerchantID', 'MODULE_PAYMENT_IDEALM_MERCHANT_ID', '000000000', 'Merchant Id number', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('SubID', 'MODULE_PAYMENT_IDEALM_SUB_ID', '0', 'Space for PSPs', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Own bank', 'MODULE_PAYMENT_IDEALM_OWN_BANK', 'ING/POSTBANK', '', 6, $index, 'tep_cfg_select_option(array(\'ING/POSTBANK\',\'ABN/AMRO\',\'RABO\'), ', now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('<hr>Order Process Info<hr>Payment Zone', 'MODULE_PAYMENT_IDEALM_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.',6, $index, 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Transaction Currency', 'MODULE_PAYMENT_IDEALM_CURRENCY', 'Selected Currency', 'The currency to use for transactions', 6, $index, 'tep_cfg_select_option(array(\'Selected Currency\',\'Only USD\',\'Only CAD\',\'Only EUR\',\'Only GBP\',\'Only JPY\'), ', now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Save orders before payment', 'MODULE_PAYMENT_IDEALM_ORDER_PREMATURE', 'True', 'Do you want to save the order before going to the iDeal payment screen ?', 6, $index, 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Restock artikel after .. hours', 'MODULE_PAYMENT_IDEALM_RESTOCK_TIME', '24', 'Restock artikel and cancel order if no payment follows after this many hours<br>(0 = no timeout)', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('Set Pending Order Status', 'MODULE_PAYMENT_IDEALM_ORDER_PENDING_STATUS_ID', '" . $pending_status_id . "', 'Set the status of PENDING orders made with this payment module to this value', 6, $index, 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('Set Paid Order Status', 'MODULE_PAYMENT_IDEALM_ORDER_PAID_STATUS_ID', '" . $payed_status_id . "', 'Set the status of PAID orders made with this payment module to this value', 6, $index, 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('Set Cancelled Order Status', 'MODULE_PAYMENT_IDEALM_ORDER_CANCELLED_STATUS_ID', '" . $cancelled_status_id . "', 'Set the status of CANCELLED orders made with this payment module to this value', 6, $index, 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('Set Failed Order Status', 'MODULE_PAYMENT_IDEALM_ORDER_FAILED_STATUS_ID', '" . $failed_status_id . "', 'Set the status of FAILED orders made with this payment module to this value', 6, $index, 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('Set Expired Order Status', 'MODULE_PAYMENT_IDEALM_ORDER_EXPIRED_STATUS_ID', '" . $expired_status_id . "', 'Set the status of EXPIRED orders made with this payment module to this value', 6, $index, 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Return URL (blank = default)', 'MODULE_PAYMENT_IDEALM_RETURN_URL', '', 'Return URL - Leave empty otherwise the complete URL to checkout_process)', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Shopping cart description', 'MODULE_PAYMENT_IDEALM_SHOPPING_CART_DESCRIPTION', 'Oscommerce - iDeal payment', 'Payment shoppingcart description', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<hr>ING/POSTBANK Info<hr>POSTBANK/ING URL/IP', 'MODULE_PAYMENT_IDEALM_ING_IP', 'ssl://idealtest.secure-ing.com', 'URL', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Path', 'MODULE_PAYMENT_IDEALM_ING_PATH', '/ideal/iDeal', 'Extra Path', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Port', 'MODULE_PAYMENT_IDEALM_ING_PORT', '443', 'Port', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<hr>RABO Info<hr>RABO URL/IP', 'MODULE_PAYMENT_IDEALM_RABO_IP', 'ssl://idealtest.rabobank.nl', 'URL', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Path', 'MODULE_PAYMENT_IDEALM_RABO_PATH', '/ideal/iDeal', 'Extra Path', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Port', 'MODULE_PAYMENT_IDEALM_RABO_PORT', '443', 'Port', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<hr>ABN/AMRO Info<hr>ABN/AMRO URL/IP', 'MODULE_PAYMENT_IDEALM_ABN_IP', 'ssl://idealm-et.abnamro.nl', 'URL', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Port', 'MODULE_PAYMENT_IDEALM_ABN_PORT', '443', 'Port', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Directory path', 'MODULE_PAYMENT_IDEALM_ABN_DIRECTORY_PATH', '/nl/issuerInformation/', 'Special path to directory request', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Transaction path', 'MODULE_PAYMENT_IDEALM_ABN_TRANSACTION_PATH', '/nl/acquirerTrxRegistration/', 'Special path to transaction request', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Status path', 'MODULE_PAYMENT_IDEALM_ABN_STATUS_PATH', '/nl/acquirerStatusInquirery/', 'Special path to status request', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Directory Request', 'MODULE_PAYMENT_IDEALM_ABN_DIRECTORY', 'getIssuerInformation.xml', 'Directory Request URL used for requestion issuers list', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Transaction Request', 'MODULE_PAYMENT_IDEALM_ABN_TRANSACTION', 'getAcquirerTrxRegistration.xml', 'Transaction URL used when requesting a iDEAL payment', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Status Request', 'MODULE_PAYMENT_IDEALM_ABN_STATUS', 'getAcquirerStatusInquiry.xml', 'Status URL used for checking order status', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<hr>Security Info<hr>Authentication', 'MODULE_PAYMENT_IDEALM_AUTHENTICATION', 'SHA1_RSA', 'Authentication Type', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Certificate directory', 'MODULE_PAYMENT_IDEALM_CERT_DIR', 'security/', 'Certificat directory relative from includes directory', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Private key', 'MODULE_PAYMENT_IDEALM_PRIVATE_KEY', 'merchantprivatekey.pem', 'Private Key', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Private key password', 'MODULE_PAYMENT_IDEALM_PRIVATE_PASSWORD', 'test', 'Private Password', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Own certificate', 'MODULE_PAYMENT_IDEALM_OWN_CERT', 'merchantprivatecert.cer', 'Own certificate', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Trusted Certificates  (comma seperated)', 'MODULE_PAYMENT_IDEALM_TRUSTED_CERT', 'ideal.cer', 'Bank certificate (comma seperated)', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('<hr>Cache/Log Info<hr>Cache refresh in day\'s', 'MODULE_PAYMENT_IDEALM_CACHE', '0', 'Cache refresh in day\'s<br>(0 = no cache)', 6, $index, now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Logging', 'MODULE_PAYMENT_IDEALM_LOGGING', 'False', 'Loggin true/false', 6, $index, 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $index++;
      tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Logfile name', 'MODULE_PAYMENT_IDEALM_LOGFILE', './iDealm.log', 'Logfile name or path/name', 6, $index, now())");
   }    
    
    function remove() {
      tep_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
        
    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_PAYMENT_IDEALM_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }    
  }
?>