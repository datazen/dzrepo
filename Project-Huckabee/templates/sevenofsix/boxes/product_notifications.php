<?php
/*
  $Id: product_notifications.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (isset($_POST['products_id']) && tep_not_null($_POST['products_id'])) {
  $products_id = (int)$_POST['products_id'];
} elseif (isset($_GET['products_id']) && tep_not_null($_GET['products_id'])) {
  $products_id = (int)$_GET['products_id'];
}
if ( (isset($_GET['products_id'])) || (isset($_POST['products_id'])) ) {
  ?>
  <!-- notifications //-->

     <div class="well">
      <div class="box-header small-margin-bottom small-margin-left"><?php echo  BOX_HEADING_NOTIFICATIONS ; ?></div>
      <?php
      if ( isset($_SESSION['customer_id']) ) {
        $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$_GET['products_id'] . "' and customers_id = '" . (int)$_SESSION['customer_id'] . "'");
        $check = tep_db_fetch_array($check_query);
        $notification_exists = (($check['count'] > 0) ? true : false);
      } else {
        $notification_exists = false;
      }
      if ($notification_exists == true) {
        echo '<div style="text-align:center"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify_remove', $request_type) . '">' . tep_image(DIR_WS_IMAGES . 'box_products_notifications_remove.gif', IMAGE_BUTTON_REMOVE_NOTIFICATIONS) . '</a><br><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify_remove', $request_type) . '">' . sprintf(BOX_NOTIFICATIONS_NOTIFY_REMOVE, tep_get_products_name((int)$_GET['products_id'])) .'</a></div>';
      } else {
        echo '<div style="text-align:center"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify', $request_type) . '">' . tep_image(DIR_WS_IMAGES . 'box_products_notifications.gif', IMAGE_BUTTON_NOTIFICATIONS) . '</a><br><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=notify', $request_type) . '">' . sprintf(BOX_NOTIFICATIONS_NOTIFY, tep_get_products_name((int)$_GET['products_id'])) .'</a></div>';
      }
      ?>

	  </div>
  <!-- notifications eof//-->
  <?php
}
?>