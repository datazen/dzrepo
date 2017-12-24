<?php
/*
  $Id: idealm.php,v 1.2 2006/01/14 22:50:52 jb Exp $

  Released under the GNU General Public License

  Parts may be copyrighted by osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
*/
  
  require('includes/configure.php');
  chdir(DIR_FS_CATALOG);
  $language = 'dutch';

  // Set the level of error reporting
  //   error_reporting(E_ALL & ~E_NOTICE);

  // Set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php'))
    include('includes/local/configure.php');

  // Include application configuration parameters
  require('includes/configure.php');

  // define our general functions used application-wide
  require(DIR_WS_FUNCTIONS . 'general.php');
  require(DIR_WS_FUNCTIONS . 'html_output.php');

  // include the list of project database tables
  require(DIR_WS_INCLUDES . 'database_tables.php');

  // include the database functions
  require(DIR_WS_FUNCTIONS . 'database.php');

  // make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

  // set application wide parameters
  $configuration_query = tep_db_query("SELECT configuration_key AS cfgKey, configuration_value AS cfgValue FROM " . TABLE_CONFIGURATION);
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    define($configuration['cfgKey'], $configuration['cfgValue']);
  }

  // email classes
  require(DIR_WS_CLASSES . 'mime.php');
  require(DIR_WS_CLASSES . 'email.php');

  // Include application configuration parameters
  require('includes/configure.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  require(DIR_WS_CLASSES . 'idealm.php');

  include(DIR_WS_CLASSES . 'order.php');

  // define how the session functions will be used
  require(DIR_WS_FUNCTIONS . 'sessions.php');

  include(DIR_WS_LANGUAGES . $language . '/checkout_process.php');
  include(DIR_WS_LANGUAGES . $language . '/index.php');
  include(DIR_WS_LANGUAGES . 'dutch.php');

  $payments_query = mysql_query("SELECT * FROM " . TABLE_IDEAL_PAYMENTS . " WHERE payment_status = '" . MODULE_PAYMENT_IDEALM_ORDER_PENDING_STATUS_ID . "'");    
  if (mysql_error()) die(mysql_error());

  while ($payment = mysql_fetch_array($payments_query)) {
    $data = new AcquirerStatusRequest();
    $data->setMerchantID(MODULE_PAYMENT_IDEALM_MERCHANT_ID);	
    $data->setSubID(MODULE_PAYMENT_IDEALM_SUB_ID);
    $data->setAuthentication(MODULE_PAYMENT_IDEALM_AUTHENTICATION);	  
    $data->setTransactionID($payment['transaction_id']); 
    $trid = $payment['transaction_id'];
    $ec = $payment['entrancecode'];
    $orderstatus = MODULE_PAYMENT_IDEALM_ORDER_PAID_STATUS_ID;

    $rule = new ThinMPI();
    $result = $rule->ProcessRequest($data);

    if ((!$result->isOk()) || (!$result->isAuthenticated())) {
      $errorMsg = $result->getErrorMessage();
      echo $errorMsg;
    } else {
      $authenticated = $result->isAuthenticated();
      $consumerName = $result->getConsumerName();
      $consumerAccountNumber = $result->getConsumerAccountNumber();
      $consumerCity = $result->getConsumerCity();

      if (strtoupper($authenticated) == 'SUCCESS') {
        $orderstatus = MODULE_PAYMENT_IDEALM_ORDER_PAID_STATUS_ID;
      } elseif (strtoupper($authenticated) == 'OPEN') {
        $orderstatus = MODULE_PAYMENT_IDEALM_ORDER_PENDING_STATUS_ID;
        if (MODULE_PAYMENT_IDEALM_RESTOCK_TIME != 0) {
          if ((strtotime($payment['date_last_check']) + (MODULE_PAYMENT_IDEALM_RESTOCK_TIME * 3600)) < time())
            $orderstatus = MODULE_PAYMENT_IDEALM_ORDER_CANCELLED_STATUS_ID;
        }
      } elseif (strtoupper($authenticated) == 'FAILED') {
        $orderstatus = MODULE_PAYMENT_IDEALM_ORDER_FAILED_STATUS_ID;
      } elseif (strtoupper($authenticated) == 'CANCELLED') {
        $orderstatus = MODULE_PAYMENT_IDEALM_ORDER_CANCELLED_STATUS_ID;
      } elseif (strtoupper($authenticated) == 'EXPIRED') {
        $orderstatus = MODULE_PAYMENT_IDEALM_ORDER_EXPIRED_STATUS_ID;
      }

      if ($orderstatus == MODULE_PAYMENT_IDEALM_ORDER_PAID_STATUS_ID) {
        $orderid = mysql_query("SELECT order_id FROM " . TABLE_IDEAL_PAYMENTS . " WHERE transaction_id = '" . $trid . "' AND entrancecode = '" . $ec ."'");    
        $orderid = mysql_fetch_array($orderid);
        mysql_query("UPDATE " . TABLE_ORDERS . " SET orders_status = '" . $orderstatus . "' WHERE orders_id = '" . $orderid['order_id'] . "'");
        mysql_query("INSERT INTO " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) VALUES ('" .$orderid['order_id'] . "', '" . $orderstatus . "', now(), '1', 'Cron - Payment verified by iDEAL. Naam: " . $consumerName . " Rekening Nr: " . $consumerAccountNumber . " Plaatsnaam: " . $consumerCity . "')");
        $order = new order($orderid['order_id']);
        $insert_id = $orderid['order_id'];
        // JTI vergeten updtae status
        mysql_query("UPDATE " . TABLE_IDEAL_PAYMENTS . " SET payment_status = '" . $orderstatus . "', date_last_check = now() WHERE transaction_id = '" . $trid . "' AND entrancecode = '" . $ec ."'");
        require_once('idealm_email.php');
      } elseif ($orderstatus != MODULE_PAYMENT_IDEALM_ORDER_PENDING_STATUS_ID) {
        $orderid = mysql_query("SELECT order_id FROM " . TABLE_IDEAL_PAYMENTS . " WHERE transaction_id = '" . $trid . "' AND entrancecode = '" . $ec ."'");    
        $orderid = mysql_fetch_array($orderid);
        if (STOCK_LIMITED == 'true') {
          $order_query = tep_db_query("SELECT products_id, products_quantity FROM " . TABLE_ORDERS_PRODUCTS . " WHERE orders_id = '" . (int)$orderid['order_id'] . "'");
          while ($order = tep_db_fetch_array($order_query)) {
            tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET products_quantity = products_quantity + " . $order['products_quantity'] . ", products_ordered = products_ordered - " . $order['products_quantity'] . " WHERE products_id = '" . (int)$order['products_id'] . "'");
            if (STOCK_ALLOW_CHECKOUT == 'false')
              mysql_query("UPDATE " . TABLE_PRODUCTS . " SET products_status = '1' WHERE products_id = '" . (int)$order['products_id'] . "'");
          }
        }
        mysql_query("UPDATE ".TABLE_ORDERS." SET orders_status = '" . $orderstatus . "' WHERE orders_id = '" . (int)$orderid['order_id'] . "'");
        mysql_query("INSERT INTO " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) VALUES ('" .$orderid['order_id'] . "', '" . $orderstatus . "', now(), '0', 'Cron - Payment cancelled by iDEAL.')");
        mysql_query("UPDATE " . TABLE_IDEAL_PAYMENTS . " SET payment_status = '" . $orderstatus . "', date_last_check = now() WHERE transaction_id = '" . $trid . "' AND entrancecode = '" . $ec ."'");
      }
    }
  }
?>