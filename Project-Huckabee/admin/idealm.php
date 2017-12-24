<?php
/*
  $Id: idealm.php,v 1.2 2006/01/14 22:50:52 jb Exp $

  Released under the GNU General Public License

  Parts may be copyrighted by osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  require(DIR_WS_CLASSES . 'idealm.php');

  include(DIR_WS_CLASSES . 'order.php');

  include('../' . DIR_WS_LANGUAGES . $language . '/checkout_process.php');

  $orders_statuses = array();
  $orders_status_array = array();

  $orders_status_query = tep_db_query("SELECT orders_status_id, 
                                              orders_status_name 
                                       FROM " . TABLE_ORDERS_STATUS . " 
                                      WHERE ((orders_status_id = '" . MODULE_PAYMENT_IDEALM_ORDER_PENDING_STATUS_ID . "'" . " 
                                         OR orders_status_id = '" . MODULE_PAYMENT_IDEALM_ORDER_PAID_STATUS_ID . "'" . " 
                                         OR orders_status_id = '" . MODULE_PAYMENT_IDEALM_ORDER_FAILED_STATUS_ID . "'" . " 
                                         OR orders_status_id = '" . MODULE_PAYMENT_IDEALM_ORDER_CANCELLED_STATUS_ID . "'" . " 
                                         OR orders_status_id = '" . MODULE_PAYMENT_IDEALM_ORDER_EXPIRED_STATUS_ID . "'" . ") 
                                        AND language_id = '" . (int)$languages_id . "')");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'update_order':
        $oID = tep_db_prepare_input($_GET['oID']);
        $status = tep_db_prepare_input($_POST['status']);
        $comments = tep_db_prepare_input($_POST['comments']);

        $order_updated = false;
        $check_status_query = tep_db_query("SELECT customers_name, customers_email_address, orders_status, date_purchased FROM " . TABLE_ORDERS . " WHERE orders_id = '" . (int)$oID . "'");
        $check_status = tep_db_fetch_array($check_status_query);

        if (($check_status['orders_status'] != $status) || tep_not_null($comments)) {
          tep_db_query("UPDATE " . TABLE_IDEAL_PAYMENTS . " SET payment_status = '" . tep_db_input($status) . "', date_last_check = now() WHERE order_id = '" . (int)$oID . "'");
          tep_db_query("UPDATE " . TABLE_ORDERS . " SET orders_status = '" . tep_db_input($status) . "', last_modified = now() WHERE orders_id = '" . (int)$oID . "'");

          $customer_notified = '0';
          if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) {
            $notify_comments = '';
            if (isset($_POST['notify_comments']) && ($_POST['notify_comments'] == 'on')) {
              $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";
            }

            $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);

            tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

            $customer_notified = '1';
          }

          tep_db_query("INSERT INTO " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) VALUES ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comments)  . "')");

          $order_updated = true;
        }

        if ($order_updated == true) {
          $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
        } else {
          $messageStack->add_session(WARNING_ORDER_NOT_UPDATED, 'warning');
        }

        tep_redirect(tep_href_link(FILENAME_IDEALM, tep_get_all_get_params(array('action')) . 'action=edit', 'SSL'));
        break;
      case 'ideal':
        $payments_query = tep_db_query("SELECT * FROM " . TABLE_IDEAL_PAYMENTS . " WHERE payment_status = '" . MODULE_PAYMENT_IDEALM_ORDER_PENDING_STATUS_ID . "'");    
        while ($payment = tep_db_fetch_array($payments_query)) {
          $data = new AcquirerStatusRequest();
          $data->setMerchantID( MODULE_PAYMENT_IDEALM_MERCHANT_ID );	
          $data->setSubID( MODULE_PAYMENT_IDEALM_SUB_ID );
          $data->setAuthentication( MODULE_PAYMENT_IDEALM_AUTHENTICATION );	  
          $data->setTransactionID( $payment['transaction_id'] ); 
          $trid = $payment['transaction_id'];
          $ec = $payment['entrancecode'];
          $orderstatus = MODULE_PAYMENT_IDEALM_ORDER_PENDING_STATUS_ID;

          $rule = new ThinMPI();
          $result = $rule->ProcessRequest($data);

          if ((!$result->isOk()) || (!$result->isAuthenticated())) {
            $errorMsg = $result->getErrorMessage();
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
          }
          if ($orderstatus == MODULE_PAYMENT_IDEALM_ORDER_PAID_STATUS_ID) {
            $orderid = mysql_query("SELECT order_id FROM " . TABLE_IDEAL_PAYMENTS . " WHERE transaction_id = '" . $trid . "' AND entrancecode = '" . $ec ."'");    
            $orderid = mysql_fetch_array($orderid);
            mysql_query("UPDATE " . TABLE_ORDERS . " SET orders_status = '" . $orderstatus . "' WHERE orders_id = '" . $orderid['order_id'] . "'");
            mysql_query("INSERT INTO " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) VALUES ('" . $orderid['order_id'] . "', '" . $orderstatus . "', now(), '1', 'Cron - Payment verified by iDEAL. Naam: " . $consumerName . " Rekening Nr: " . $consumerAccountNumber . " Plaatsnaam: " . $consumerCity . "')");
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
            mysql_query("UPDATE ".TABLE_ORDERS." SET orders_status = '" . $orderstatus . "' WHERE orders_id = '" . $orderid['order_id'] . "'");
            mysql_query("INSERT INTO " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) VALUES ('" . $orderid['order_id'] . "', '" . $orderstatus . "', now(), '0', 'Cron - Payment cancelled by iDEAL.')");
            mysql_query("UPDATE " . TABLE_IDEAL_PAYMENTS . " SET payment_status = '" . $orderstatus . "', date_last_check = now() WHERE transaction_id = '" . $trid . "' AND entrancecode = '" . $ec ."'");
          }
        }
        break; 
      case 'deleteconfirm':
        $oID = tep_db_prepare_input($_GET['oID']);

        tep_db_query("DELETE FROM " . TABLE_IDEAL_PAYMENTS . " WHERE order_id = '" . (int)$oID . "'");

        tep_remove_order($oID, $_POST['restock']);

        tep_redirect(tep_href_link(FILENAME_IDEALM, tep_get_all_get_params(array('oID', 'action')), 'SSL'));
        break;
    }
  }

  if (($action == 'edit') && isset($_GET['oID'])) {
    $oID = tep_db_prepare_input($_GET['oID']);
    $orders_query = tep_db_query("SELECT orders_id FROM " . TABLE_ORDERS . " WHERE orders_id = '" . (int)$oID . "'");
    $order_exists = true;
    if (!tep_db_num_rows($orders_query)) {
      $order_exists = false;
      $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    } else {
      $payments_query = tep_db_query("SELECT * FROM " . TABLE_IDEAL_PAYMENTS . " WHERE order_id = '" . (int)$oID . "'");    
      $payment = tep_db_fetch_array($payments_query);
    }
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
  <!-- left_navigation //-->
  <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
  <!-- left_navigation_eof //-->
<!-- body_text //-->    
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if (($action == 'edit') && ($order_exists == true)) {
    $order = new order($oID);
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_IDEALM, tep_get_all_get_params(array('action')), 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="3"><?php echo tep_draw_separator(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_CUSTOMER; ?></b></td>
                <td class="main"><?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_TELEPHONE_NUMBER; ?></b></td>
                <td class="main"><?php echo $order->customer['telephone']; ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
                <td class="main"><?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?></td>
              </tr>
            </table></td>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_SHIPPING_ADDRESS; ?></b></td>
                <td class="main"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'); ?></td>
              </tr>
            </table></td>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_BILLING_ADDRESS; ?></b></td>
                <td class="main"><?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, '', '<br>'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
            <td class="main"><?php echo $order->info['payment_method']; ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo ENTRY_PAYMENT_TRANSACTION; ?></b></td>
            <td class="main"><?php echo $payment['transaction_id']; ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo ENTRY_PAYMENT_ISSUER; ?></b></td>
            <td class="main"><?php echo $payment['issuer_id']; ?></td>
          </tr>
<?php
    if (tep_not_null($order->info['cc_type']) || tep_not_null($order->info['cc_owner']) || tep_not_null($order->info['cc_number'])) {
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
            <td class="main"><?php echo $order->info['cc_type']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
            <td class="main"><?php echo $order->info['cc_owner']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
            <td class="main"><?php echo $order->info['cc_number']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
            <td class="main"><?php echo $order->info['cc_expires']; ?></td>
          </tr>
<?php
    }
?>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_INCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
          </tr>
<?php
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      echo '          <tr class="dataTableRow">' . "\n" .
           '            <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $order->products[$i]['name'];

      if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
        for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {
          echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
          if ($order->products[$i]['attributes'][$j]['price'] != '0') echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
          echo '</i></small></nobr>';
        }
      }

      echo '            </td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
      echo '          </tr>' . "\n";
    }
?>
          <tr>
            <td align="right" colspan="9"><table border="0" cellspacing="0" cellpadding="2">
<?php
    for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
      echo '              <tr>' . "\n" .
           '                <td align="right" class="smallText">' . $order->totals[$i]['title'] . '</td>' . "\n" .
           '                <td align="right" class="smallText">' . $order->totals[$i]['text'] . '</td>' . "\n" .
           '              </tr>' . "\n";
    }
?>
            </table></td>
          </tr>
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
    $orders_history_query = tep_db_query("SELECT orders_status_id, date_added, customer_notified, comments FROM " . TABLE_ORDERS_STATUS_HISTORY . " WHERE orders_id = '" . tep_db_input($oID) . "' ORDER BY date_added");
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
        echo '            <td class="smallText">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n" .
             '            <td class="smallText">' . nl2br(tep_db_output($orders_history['comments'])) . '&nbsp;</td>' . "\n" .
             '          </tr>' . "\n";
      }
    } else {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" colspan="6">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
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
      <tr><?php echo tep_draw_form('status', FILENAME_IDEALM, tep_get_all_get_params(array('action')) . 'action=update_order'); ?>
        <td class="main"><?php echo tep_draw_textarea_field('comments', 'soft', '60', '5'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo ENTRY_STATUS; ?></b> <?php echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status']); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b> <?php echo tep_draw_checkbox_field('notify', '', true); ?></td>
                <td class="main"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b> <?php echo tep_draw_checkbox_field('notify_comments', '', true); ?></td>
              </tr>
            </table></td>
            <td valign="top"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
          </tr>
        </table></td>
      </form></tr>
      <tr>
       <td colspan="2" align="right"><?php echo '</a> <a href="' . tep_href_link(FILENAME_IDEALM, tep_get_all_get_params(array('action')), 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr><?php echo tep_draw_form('orders', FILENAME_IDEALM, '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('oID', '', 'size="12"') . tep_draw_hidden_field('action', 'edit'); ?></td>
              </form></tr>
              <tr><?php echo tep_draw_form('status', FILENAME_IDEALM, '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_STATUS . ' ' . tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), '', 'onChange="this.form.submit();"'); ?></td>
              </form></tr>            
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDER_TOTAL; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DATE_CHECKED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    // Here whe have to go to the ideal_payment tabel first
    $extrawhere = " AND (o.orders_status='" . MODULE_PAYMENT_IDEALM_ORDER_PENDING_STATUS_ID . "'" . " 
                     OR o.orders_status='" . MODULE_PAYMENT_IDEALM_ORDER_PAID_STATUS_ID . "'" . " 
                     OR o.orders_status='" . MODULE_PAYMENT_IDEALM_ORDER_FAILED_STATUS_ID . "'" . " 
                     OR o.orders_status='" . MODULE_PAYMENT_IDEALM_ORDER_CANCELLED_STATUS_ID . "'" . " 
                     OR o.orders_status='" . MODULE_PAYMENT_IDEALM_ORDER_EXPIRED_STATUS_ID . "')";  

    if (isset($_GET['cID'])) {
      $cID = tep_db_prepare_input($_GET['cID']);
      $orders_query_raw = "SELECT o.orders_id, 
                                  o.customers_name, 
                                  o.customers_id, 
                                  o.payment_method, 
                                  o.date_purchased, 
                                  o.last_modified, 
                                  o.currency, 
                                  o.currency_value, 
                                  s.orders_status_name, 
                                  i.date_last_check, 
                                  i.issuer_id, 
                                  i.transaction_id, 
                                  ot.text AS order_total 
                             FROM " . TABLE_ORDERS . " o 
                        LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot 
                               ON (o.orders_id = ot.orders_id) 
                        LEFT JOIN " . TABLE_IDEAL_PAYMENTS . " i 
                               ON (i.order_id = o.orders_id), 
                                  " . TABLE_ORDERS_STATUS . " s 
                            WHERE o.customers_id = '" . (int)$cID . "' 
                              AND o.orders_status = s.orders_status_id 
                              AND s.language_id = '" . (int)$languages_id . "' 
                              AND ot.class = 'ot_total' 
                         ORDER BY orders_id DESC";
    } elseif ((isset($_GET['status'])) && ($_GET['status'] != '')) {
      $status = tep_db_prepare_input($_GET['status']);
      $orders_query_raw = "SELECT o.orders_id, 
                                  o.customers_name, 
                                  o.payment_method, 
                                  o.date_purchased, 
                                  o.last_modified, 
                                  o.currency, 
                                  o.currency_value, 
                                  s.orders_status_name, 
                                  i.date_last_check, 
                                  i.issuer_id, 
                                  i.transaction_id, 
                                  ot.text AS order_total 
                             FROM " . TABLE_ORDERS . " o 
                        LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot 
                               ON (o.orders_id = ot.orders_id) 
                        LEFT JOIN " . TABLE_IDEAL_PAYMENTS . " i 
                               ON (i.order_id = o.orders_id), 
                                  " . TABLE_ORDERS_STATUS . " s 
                            WHERE o.orders_status = s.orders_status_id 
                              AND s.language_id = '" . (int)$languages_id . "' 
                              AND s.orders_status_id = '" . (int)$status . "' 
                              AND ot.class = 'ot_total' 
                         ORDER BY o.orders_id DESC";
    } else {
      $orders_query_raw = "SELECT o.orders_status, 
                                  o.orders_id, 
                                  o.customers_name, 
                                  o.payment_method, 
                                  o.date_purchased, 
                                  o.last_modified, 
                                  o.currency, 
                                  o.currency_value, 
                                  s.orders_status_name, 
                                  i.date_last_check, 
                                  i.issuer_id, 
                                  i.transaction_id, 
                                  ot.text AS order_total 
                             FROM " . TABLE_ORDERS . " o 
                        LEFT JOIN " . TABLE_ORDERS_TOTAL . " ot 
                               ON (o.orders_id = ot.orders_id) 
                        LEFT JOIN " . TABLE_IDEAL_PAYMENTS . " i 
                               ON (i.order_id = o.orders_id), 
                                  " . TABLE_ORDERS_STATUS . " s 
                            WHERE o.orders_status = s.orders_status_id 
                              AND s.language_id = '" . (int)$languages_id . "' 
                              AND ot.class = 'ot_total'" . $extrawhere . " 
                         ORDER BY o.orders_id DESC";
    }

    $orders_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_query_raw, $orders_query_numrows);
    $orders_query = tep_db_query($orders_query_raw);
    while ($orders = tep_db_fetch_array($orders_query)) {
      if ((!isset($_GET['oID']) || (isset($_GET['oID']) && ($_GET['oID'] == $orders['orders_id']))) && !isset($oInfo)) {
        $oInfo = new objectInfo($orders);
      }

      if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id)) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_IDEALM, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit', 'SSL') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_IDEALM, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id'], 'SSL') . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_IDEALM, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=edit', 'SSL') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $orders['customers_name'].'<br>'.$orders['transaction_id']; ?></td>
                <td class="dataTableContent" align="right"><?php echo strip_tags($orders['order_total']); ?></td>
                <td class="dataTableContent" align="center"><?php echo tep_datetime_short($orders['date_purchased']); ?></td>
                <td class="dataTableContent" align="center"><?php echo tep_datetime_short($orders['date_last_check']); ?></td>
                <td class="dataTableContent" align="right"><?php echo $orders['orders_status_name']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_IDEALM, tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id'], 'SSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                    <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ORDER . '</b>');

      $contents[] = array('form' => tep_draw_form('orders', FILENAME_IDEALM, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br><br><b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('restock') . ' ' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_IDEALM, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id, 'SSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($oInfo) && is_object($oInfo)) {
        $heading[] = array('text' => '<b>[' . $oInfo->orders_id . ']&nbsp;&nbsp;' . tep_datetime_short($oInfo->date_purchased) . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_IDEALM, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit', 'SSL') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_IDEALM, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=delete', 'SSL') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_IDEALM, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=ideal', 'SSL') . '">' . tep_image_button('button_update_ideal.gif', 'Update ideal') . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_DATE_ORDER_CREATED . ' ' . tep_date_short($oInfo->date_purchased));
        if (tep_not_null($oInfo->last_modified)) $contents[] = array('text' => TEXT_DATE_ORDER_LAST_MODIFIED . ' ' . tep_date_short($oInfo->last_modified));
        $contents[] = array('text' => '<br>' . TEXT_INFO_PAYMENT_METHOD . ' '  . $oInfo->payment_method);
      }
      break;
  }

  if ((tep_not_null($heading)) && (tep_not_null($contents))) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
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
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>