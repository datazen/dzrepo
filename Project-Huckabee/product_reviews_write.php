<?php
/*
  $Id: product_reviews_write.php,v 1.1.1.1 2004/03/04 23:38:02 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  // MailBeez review_advanced autologin
  // place before login-check
  if (file_exists(DIR_FS_CATALOG . 'mailhive/mailbeez/review_advanced/includes/autologin.php')) {
    include_once(DIR_FS_CATALOG . 'mailhive/mailbeez/review_advanced/includes/autologin.php');
  }

  if ( !isset($_SESSION['customer_id']) ) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  $product_info_query = tep_db_query("SELECT p.products_id, p.products_model, p.products_image, pd.products_name
                                      FROM " . TABLE_PRODUCTS . " p,
                                           " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                      WHERE p.products_id = '" . (int)$_GET['products_id'] . "'
                                        and pd.products_id = '" . (int)$_GET['products_id'] . "'
                                        and p.products_status = '1'
                                        and pd.language_id = '" . (int)$languages_id . "' ");
  if (!tep_db_num_rows($product_info_query)) {
    tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('action'))));
  } else {
    $product_info = tep_db_fetch_array($product_info_query);
  }

  $customer_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
  $customer = tep_db_fetch_array($customer_query);
 
    $error = false;

if (isset($_GET['action']) && ($_GET['action'] == 'process')){
    $rating = tep_db_prepare_input($_POST['rating']);
    $review = tep_db_prepare_input($_POST['review_text']);

    if (strlen($review) < REVIEW_TEXT_MIN_LENGTH) {
      $error = true;
      $messageStack->add('review', JS_REVIEW_TEXT);
    }

    if (($rating < 1) || ($rating > 5)) {
      $error = true;
      $messageStack->add('review', JS_REVIEW_RATING);
    }
    
    //VISUAL VERIFY CODE start
    if (defined('VVC_SITE_ON_OFF') && VVC_SITE_ON_OFF == 'On'){
    if (defined('VVC_PRODUCT_REVIEWS_ON_OFF') && VVC_PRODUCT_REVIEWS_ON_OFF == 'On'){
    $code_query = tep_db_query("select code from " . TABLE_VISUAL_VERIFY_CODE . " where oscsid = '" . tep_session_id() . "'");
    $code_array = tep_db_fetch_array($code_query);
    tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . tep_session_id() . "'"); //remove the visual verify code associated with this session to clean database and ensure new results
    if ( isset($_POST['visual_verify_code']) && tep_not_null($_POST['visual_verify_code']) && 
         isset($code_array['code']) &&  tep_not_null($code_array['code']) && 
         strcmp($_POST['visual_verify_code'], $code_array['code']) == 0) {   //make the check case sensitive
         //match is good, no message or error.
         } else {
      $error = true;
      $messageStack->add('review', VISUAL_VERIFY_CODE_ENTRY_ERROR);
    }
  }
}
//VISUAL VERIFY CODE stop

    if ($error == false) {
      tep_db_query("insert into " . TABLE_REVIEWS . " (products_id, customers_id, customers_name, reviews_rating, date_added) values ('" . (int)$_GET['products_id'] . "', '" . (int)$_SESSION['customer_id'] . "', '" . tep_db_input($customer['customers_firstname']) . ' ' . tep_db_input($customer['customers_lastname']) . "', '" . tep_db_input($rating) . "', now())");
      $insert_id = tep_db_insert_id();

      tep_db_query("insert into " . TABLE_REVIEWS_DESCRIPTION . " (reviews_id, languages_id, reviews_text) values ('" . (int)$insert_id . "', '" . (int)$languages_id . "', '" . tep_db_input($review) . "')");
      
      // Points/Rewards Module V2.00 BOF 
      if ((MODULE_ADDONS_POINTS_STATUS == 'True') && (tep_not_null(USE_POINTS_FOR_REVIEWS))) {
        $points_toadd = USE_POINTS_FOR_REVIEWS;
        $comment = 'TEXT_DEFAULT_REVIEWS';
        $points_type = 'RV';
        tep_add_pending_points($_SESSION['customer_id'], $product_info['products_id'], $points_toadd, $comment, $points_type);
      }
      // Points/Rewards Module V2.00 EOF
      
      // MailBeez review_advanced autologoff
      // place after last usage of $customer_id or $_SESSION['customer_id']
      if (file_exists(DIR_FS_CATALOG . 'mailhive/mailbeez/review_advanced/includes/autologoff.php')) {
        include_once(DIR_FS_CATALOG . 'mailhive/mailbeez/review_advanced/includes/autologoff.php');
      }

      tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('action'))));
    }
  }

  $pf->loadProduct($product_info['products_id'],$languages_id);
  $products_price = $pf->getPriceStringShort();

  if (tep_not_null($product_info['products_model'])) {
    $products_name = $product_info['products_name'] . '<br><span class="smallText">[' . $product_info['products_model'] . ']</span>';
  } else {
    $products_name = $product_info['products_name'];
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_WRITE);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));

  $content = CONTENT_PRODUCT_REVIEWS_WRITE;
  $javascript = $content . '.js';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  // MailBeez review_advanced autologoff
  // place after last usage of $customer_id or $_SESSION['customer_id']
  if (file_exists(DIR_FS_CATALOG . 'mailhive/mailbeez/review_advanced/includes/autologoff.php')) {
    include_once(DIR_FS_CATALOG . 'mailhive/mailbeez/review_advanced/includes/autologoff.php');
  } 
  
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>