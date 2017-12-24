<?php
/*
  $Id: idealm_email.php,v 1.2 2007/02/17 22:50:52 jb Exp $

  Released under the GNU General Public License

  Parts may be copyrighted by osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
*/

  $products_ordered = '';
  $subtotal = 0;
  $total_tax = 0;

  for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
    // Stock Update - Joao Correia
    if (STOCK_LIMITED == 'true') {
      if (DOWNLOAD_ENABLED == 'true') {
        $stock_query_raw = "SELECT p.products_quantity, 
                                   pad.products_attributes_filename 
                              FROM " . TABLE_PRODUCTS . " p 
                         LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa 
                                ON p.products_id = pa.products_id 
                         LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad 
                                ON pa.products_attributes_id = pad.products_attributes_id 
                             WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
        // Will work with only one option for downloadable products
        // otherwise, we have to build the query dynamically with a loop
        $products_attributes = $order->products[$i]['attributes'];
        if (is_array($products_attributes)) {
          $stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
        }
        $stock_query = tep_db_query($stock_query_raw);
      } else {
        $stock_query = tep_db_query("SELECT products_quantity FROM " . TABLE_PRODUCTS . " WHERE products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
      }
      if (tep_db_num_rows($stock_query) > 0) {
        $stock_values = tep_db_fetch_array($stock_query);
      }
    }
    // ------insert customer choosen option to order--------
    // hier is een groot deel code opgeruimd, laatste regel aangepast (17-2-2007)
    $products_ordered_attributes = '';
    if ((isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0)) {
      for ($j = 0, $n2 = sizeof($order->products[$i]['attributes']); $j < $n2; $j++) {
        $products_ordered_attributes .= "\n\t" . $order->products[$i]['attributes'][$j]['option'] . ' ' . $order->products[$i]['attributes'][$j]['value'];
      }
    }
    //------insert customer choosen option eof ----

    $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
    $total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
    $total_cost += $total_products_price;

    $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
  }

  // lets start with the email confirmation
  $email_order = STORE_NAME . "\n" . 
                 EMAIL_SEPARATOR . "\n" . 
                 EMAIL_TEXT_ORDER_NUMBER . ' ' . $insert_id . "\n" .
                 EMAIL_TEXT_INVOICE_URL . ' ' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false) . "\n" .
                 EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";
  $email_order .= EMAIL_TEXT_PRODUCTS . "\n" . 
                  EMAIL_SEPARATOR . "\n" . 
                  $products_ordered . 
                  EMAIL_SEPARATOR . "\n";

  for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
    $email_order .= strip_tags($order->totals[$i]['title']) . ' ' . strip_tags($order->totals[$i]['text']) . "\n";
  }
  
  //dit is een toevoeging om commentaar in de email te krijgen (17-2-2007)
  if ($comments) {
    $email_order .= EMAIL_SEPARATOR . "\n" . "\n" .
                    EMAIL_TEXT_COMMENTS . "\n" .
                    EMAIL_SEPARATOR . "\n\n" .
                    $comments . "\n\n";

  }
  if ($order->content_type != 'virtual') {
    $email_order .= "\n" . EMAIL_TEXT_DELIVERY_ADDRESS . "\n" . 
                    EMAIL_SEPARATOR . "\n" .
                    tep_address_format($order->delivery['format_id'], $order->delivery, 0, '', "\n") . "\n";
  }
  $email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" .
                  EMAIL_SEPARATOR . "\n" .
                  tep_address_format($order->billing['format_id'], $order->billing, 0, '', "\n") . "\n\n";
  $email_order .= EMAIL_TEXT_PAYMENT_METHOD . "\n" . 
                  EMAIL_SEPARATOR . "\n";
  $email_order .= $order->info['payment_method'] . "\n\n";

  tep_mail($order->customer['name'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

  // send emails to other people
  if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
    tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT, $email_order, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
  }
?>