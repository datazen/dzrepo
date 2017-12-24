<?php
/*
  $Id: idealm.php, v2.1

  Released under the GNU General Public License

  Parts may be copyrighted by osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
*/

  require('includes/application_top.php');

  global $_POST, $cart, $cart_contents, $payment, $customer_id, $order, $languages_id, $currencies, $currency;

  require_once(DIR_WS_CLASSES . "idealm.php");

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_IDEALM);

  if (!isset($_GET['trxid'])) { 
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

    $issuerid = $_POST['issuerID'];
    $entrance_code = tep_session_id();
    $insert_id = 0;
    $order_status = MODULE_PAYMENT_IDEALM_ORDER_PENDING_STATUS_ID;
    tep_db_query("INSERT INTO " . TABLE_IDEAL_PAYMENTS . " (transaction_id, entrancecode, issuer_id, order_id, payment_status, date_last_check) VALUES (0, '$entrance_code', $issuerid, $insert_id, $order_status, now())");
    $payment_id = tep_db_insert_id();

    $data = new AcquirerTrxRequest();
    $data->setIssuerID( $_POST["issuerID"] );
    $data->setMerchantID( MODULE_PAYMENT_IDEALM_MERCHANT_ID );	
    $data->setSubID( MODULE_PAYMENT_IDEALM_SUB_ID );
    $data->setAuthentication( MODULE_PAYMENT_IDEALM_AUTHENTICATION );
    if (MODULE_PAYMENT_IDEALM_RETURN_URL == '') {
      $data->setMerchantReturnURL(HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_CHECKOUT_PROCESS);
    } else {
      $data->setMerchantReturnURL(MODULE_PAYMENT_IDEALM_RETURN_URL);
    }
    $data->setPurchaseID($payment_id);
    $data->setAmount($_POST['idealm_amount']);
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
        $data->setAcqURL(MODULE_PAYMENT_IDEALM_RABO_IP . MODULE_PAYMENT_IDEALM_RABO_PATH);
        break;
      case 'ABN/AMRO' :
        $data->setAcqURL(MODULE_PAYMENT_IDEALM_ABN_IP . MODULE_PAYMENT_IDEALM_ABN_PATH . '/' . MODULE_PAYMENT_IDEALM_ABN_TRANSACTION_PATH . '/' . MODULE_PAYMENT_IDEALM_ABN_TRANSACTION_URL);
        break;
    }
    
    $rule = new ThinMPI();
    $result = $rule->ProcessRequest( $data );

    if (!$result->isOk()) {
      $errorMsg = $result->getErrorMessage();
      if ($result->getErrorDetail != '') $errorMsg .= '<br>' . $result->getErrorDetail();
      if ($errorMsg == '') $errorMsg = $result->getConsumerMessage();
      tep_redirect(tep_href_link('idealm_error.php', 'ec=' . $ec . '&stat='. $order_status . '&errormsg=' . $errorMsg, 'SSL'));
	   } else {
		    $acquirerID = $result->getAcquirerID;
		    $IssuerAuthenticationURL = $result->getIssuerAuthenticationURL();
		    $transactionID = $result->getTransactionID();
		    tep_redirect(str_replace('&amp;', '&', $IssuerAuthenticationURL));
    }               
  } else {
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
      if ($result->getErrorDetail != '') $errorMsg .= '<br>' . $result->getErrorDetail();
      if ($errorMsg == '') $errorMsg = $result->getConsumerMessage();
      $this->enabled = false;
      $orderstatus = MODULE_PAYMENT_IDEALM_ORDER_FAILED_STATUS_ID;
      $orderid = tep_db_query("SELECT order_id,cart_contents FROM " . TABLE_IDEAL_PAYMENTS . " WHERE transaction_id = '" . $trid . "' AND entrancecode = '" . $ec ."'");    
      $orderid = tep_db_fetch_array($orderid);
      tep_db_query("UPDATE " . TABLE_IDEAL_PAYMENTS . " SET payment_status = '" . $orderstatus . "', date_last_check = now() WHERE transaction_id = '" . $trid . "' AND entrancecode = '" . $ec ."'");
      tep_db_query("UPDATE " . TABLE_ORDERS . " SET orders_status = '" . $orderstatus . "' WHERE orders_id='".$orderid['order_id']."'");
      if (!isset($_SESSION['cart_contents'])) tep_session_register('cart_contents'); 
      $_SESSION['cart_contents'] = unserialize($orderid['cart_contents']);
      tep_redirect(tep_href_link('idealm_error.php', 'ec=' . $ec . '&stat=' . $order_status . '&errormsg=' . $errorMsg, 'SSL'));
    } else {
      $authenticated = $result->isAuthenticated();
      $consumerName = $result->getConsumerName();
      $consumerAccountNumber = $result->getConsumerAccountNumber();
      $consumerCity = $result->getConsumerCity();
      if (strtoupper($authenticated) == 'SUCCESS') {
        $orderstatus = MODULE_PAYMENT_IDEALM_ORDER_PAID_STATUS_ID;
      } elseif (strtoupper($authenticated) == 'OPEN') {
        $orderstatus = MODULE_PAYMENT_IDEALM_ORDER_PENDING_STATUS_ID;
      } elseif (strtoupper($authenticated) == 'FAILURE') {
        $orderstatus = MODULE_PAYMENT_IDEALM_ORDER_FAILED_STATUS_ID;
        $errorMsg = 'Uw betaling is geannuleerd';
      } elseif (strtoupper($authenticated) == 'CANCELLED') {
        $orderstatus = MODULE_PAYMENT_IDEALM_ORDER_CANCELLED_STATUS_ID;
        $errorMsg = 'Uw betaling is geannuleerd';
      } elseif (strtoupper($authenticated) == 'EXPIRED') {
        $orderstatus = MODULE_PAYMENT_IDEALM_ORDER_EXPIRED_STATUS_ID;
        $errorMsg = 'Uw betaling is geannuleerd';
      } else {
        $errorMsg = 'Uw betaling heeft een onbekende status';
        tep_redirect(tep_href_link('idealm_error.php', 'ec=' . $ec . '&stat=' . $order_status . '&errormsg=' . $errorMsg, 'SSL'));
      }
      $orderid = tep_db_query("SELECT order_id, cart_contents FROM " . TABLE_IDEAL_PAYMENTS . " WHERE transaction_id = '" . $trid . "' AND entrancecode = '" . $ec ."'");    
      $orderid = tep_db_fetch_array($orderid);
      tep_db_query("UPDATE " . TABLE_IDEAL_PAYMENTS . " SET payment_status = '" . $orderstatus . "', date_last_check = now() WHERE transaction_id = '" . $trid . "' AND entrancecode = '" . $ec ."'");
      tep_db_query("UPDATE " . TABLE_ORDERS . " SET orders_status = '" . $orderstatus . "' WHERE orders_id = '" . $orderid['order_id'] . "'");

      if (($orderstatus == MODULE_PAYMENT_IDEALM_ORDER_PENDING_STATUS_ID) || ($orderstatus == MODULE_PAYMENT_IDEALM_ORDER_PAID_STATUS_ID)) {
        $customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
        if ($orderstatus == MODULE_PAYMENT_IDEALM_ORDER_PAID_STATUS_ID) {
          tep_db_query("INSERT INTO " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) VALUES ('" . $orderid['order_id'] . "', '" . $orderstatus . "', now(), '" . $customer_notification . "', 'Payment verified by iDEAL system. Naam: " . $consumerName . " Rekening Nr: " . $consumerAccountNumber . " Plaatsnaam: " . $consumerCity . "')");
        } else {
          tep_db_query("INSERT INTO " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) VALUES ('" . $orderid['order_id'] . "', '" . $orderstatus . "', now(), 0, 'Payment pending in iDEAL system.')");
        }         
        if ($orderstatus == MODULE_PAYMENT_IDEALM_ORDER_PAID_STATUS_ID) {
          $trans = 'success';
        } else {
          $trans = 'pending';
        }

        tep_session_register('trans');

        if (MODULE_PAYMENT_IDEALM_RETURN_URL == '') {
          $checkout_url = tep_redirect(tep_href_link('checkout_ideal.php', 'osCsid=' . $ec . '&stat=' . $order_status . '&errormsg=' . $errorMsg, 'SSL'));
        } else {
          $checkout_url = MODULE_PAYMENT_IDEALM_RETURN_URL;
        }
        tep_redirect($checkout_url);
      } else {
        $order_query = tep_db_query("SELECT products_id, products_quantity FROM " . TABLE_ORDERS_PRODUCTS . " WHERE orders_id = '" . (int)$orderid['order_id'] . "'");
        while ($order = tep_db_fetch_array($order_query)) {
          tep_db_query("UNPDATE " . TABLE_PRODUCTS . " SET products_quantity = products_quantity + " . $order['products_quantity'] . ", products_ordered = products_ordered - " . $order['products_quantity'] . " WHERE products_id = '" . (int)$order['products_id'] . "'");
        }
        tep_db_query("INSERT INTO " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified) VALUES ('" . (int)$orderid['order_id'] . "', '" . $orderstatus . "', now(), '0')");

        if (!isset($_SESSION['cart_contents'])) tep_session_register('cart_contents'); 
        $_SESSION['cart_contents'] = unserialize($orderid['cart_contents']);
        global $cart_contents; 
        tep_redirect(tep_href_link('idealm_error.php', 'ec=' . $ec . '&stat=' . $order_status . '&errormsg=' . $errorMsg, 'SSL'));
      }
    } 
  }	
?>