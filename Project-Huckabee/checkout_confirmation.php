<?php
/*
  $Id: checkout_confirmation.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// if the customer is not logged on, redirect them to the login page
  if ( ! isset($_SESSION['customer_id']) ) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && isset($_SESSION['cartID'])) {
    if ($cart->cartID != $_SESSION['cartID']) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!isset($_SESSION['shipping']) && SHIPPING_SKIP == 'No') {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }

  if (!isset($_SESSION['payment'])) $_SESSION['payment'] = ''; 
  if (isset($_POST['payment'])) $_SESSION['payment'] = $_POST['payment'];
  
  $payment = $_SESSION['payment'];
  
  if(!isset($_SESSION['comments'])) {
    $_SESSION['comments'] = '';
  } else if (isset($_POST['comments']) && $_POST['comments'] != '' ) {
    $_SESSION['comments'] = tep_db_prepare_input($_POST['comments']);
  }
  
// set shipping addresss to customer's default address if without shipping
  
  if ( !isset($_SESSION['sendto']) ) {    
    $_SESSION['sendto'] = false;    
  }
  if ($_SESSION['sendto'] == false) {
    $_SESSION['sendto'] = $_SESSION['customer_default_address_id'];
  }
  // load the selected shipping module
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping($_SESSION['shipping']);

  // load the selected payment module
  require(DIR_WS_CLASSES . 'payment.php');
  if (isset($_SESSION['credit_covers'])) $_SESSION['payment']=''; //ICW added for CREDIT CLASS
  // added for PP Super Module support
  if (isset($_POST['payment']) && $_POST['payment'] == 'paypal_wpp_dp') $_SESSION['payment'] = 'paypal';
  
  $payment_modules = new payment($_SESSION['payment']);
  $payment_modules->update_status();

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

  if (isset($_SESSION['token']) && substr($_SESSION['token'], 0, 2) == 'EC') {
    $_SESSION['payment'] = 'paypal_xc';
    $payment_modules->modules = array('paypal_xc.php');
    $payment_modules->selected_module = 'paypal_xc'; 
    $order->info['payment_method'] = 'Paypal Express Checkout';
  } 
   
  // Stock Check
  $any_out_of_stock = false;
  if (STOCK_CHECK == 'true') {
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      if (tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty'])) {
        $any_out_of_stock = true;
      }
    }
    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
      tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
    }
  }

// Ok, the various checks have been applied.  Now if this is thesecond time thru
// we want to proceed.  The checks are to be applied before the confirmation screen is presented
// and once again after the confirm button is clicked to reduce possible errors in the order.
  if ($_SESSION['payment'] == 'paypal_xc') {
  } else {
    if ( isset($_POST['action']) && $_POST['action'] == 'proceed' ) {
      if (isset($$payment->form_action_url)) {
        tep_redirect($$payment->form_action_url);
      } else {
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL'));
      }
    }  
  }
  // RCI code start
  echo $cre_RCI->get('checkoutconfirmation', 'logic', false);
  // RCI code eof
//ICW ADDED FOR CREDIT CLASS SYSTEM
  require(DIR_WS_CLASSES . 'order_total.php');
//ICW ADDED FOR CREDIT CLASS SYSTEM
  $order_total_modules = new order_total;
//ICW ADDED FOR CREDIT CLASS SYSTEM
  $order_total_modules->collect_posts();
//ICW ADDED FOR CREDIT CLASS SYSTEM
  $order_total_modules->pre_confirmation_check();
// ICW CREDIT CLASS Amended Line
//  if ( ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {
  if (isset($_POST['payment']) && $_POST['payment'] == 'paypal_wpp_dp') {
    $_SESSION['sub_payment'] = 'paypal_wpp_dp';
  } else if ($_SESSION['payment'] == 'paypal_xc' ) {
  } else {
    if (isset($_SESSION['sub_payment'])) unset($_SESSION['sub_payment']);
    // Points/Rewards Module V2.00 check for error BOF
    if (isset($_POST['customer_shopping_points_spending']) && USE_REDEEM_SYSTEM == 'true') {
      if (isset($_POST['customer_shopping_points_spending']) && tep_calc_shopping_pvalue($_POST['customer_shopping_points_spending'])+.00001 < $order->info['total'] && !is_object($$payment)) {
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(REDEEM_SYSTEM_ERROR_POINTS_NOT), 'SSL'));
      } else {
      if (!isset($_SESSION['customer_shopping_points_spending']))
        $_SESSION['customer_shopping_points_spending'] = $_POST['customer_shopping_points_spending'];
      }
    }
    if (isset($_POST['customer_referred']) && tep_not_null($_POST['customer_referred'])) {
      $valid_referral_query = tep_db_query("SELECT customers_id FROM " . TABLE_CUSTOMERS . " WHERE customers_email_address = '" . $_POST['customer_referred'] . "'");
      $valid_referral = tep_db_fetch_array($valid_referral_query);
      if (!tep_db_num_rows($valid_referral_query)) {
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(REFERRAL_ERROR_NOT_FOUND), 'SSL'));
      }
      if ($_POST['customer_referred'] == $order->customer['email_address']) {
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(REFERRAL_ERROR_SELF), 'SSL'));
      } else {
        $customer_referral = $valid_referral['customers_id'];
        if (!isset($_SESSION['customer_referral'])) $_SESSION['customer_referral'] = $customer_referral;
      }
    } 
    if ( (is_array($payment_modules->modules)) && (sizeof($payment_modules->modules) > 1) && (!is_object($$payment)) && (!isset($_SESSION['credit_covers'])) && (!isset($_SESSION['point_covers'])) ) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
    }
    // Points/Rewards Module V2.00 EOF
  }

  if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
  }

//ICW Credit class amendment Lines below repositioned
//  require(DIR_WS_CLASSES . 'order_total.php');
//  $order_total_modules = new order_total;

  
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2);

  $content = CONTENT_CHECKOUT_CONFIRMATION;
  
  if (ACCOUNT_CONDITIONS_REQUIRED == 'true') $javascript = 'checkout_confirmation.js.php';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
