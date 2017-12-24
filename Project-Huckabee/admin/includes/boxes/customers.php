<?php
/*
  $Id: customers.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- customers //-->
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_CUSTOMERS,
                   'link'  => tep_href_link(FILENAME_ORDERS, 'selected_box=customers', 'SSL'));

  // RCO start
  if ($cre_RCO->get('customers', 'columnleft') !== true) {   
    //RCI to include links  
    $returned_rci_orders_top = $cre_RCI->get('orders', 'boxestop');
    $returned_rci_orders_bottom = $cre_RCI->get('orders', 'boxesbottom');
    $returned_rci_customers_top = $cre_RCI->get('customers', 'boxestop');
    $returned_rci_customers_bottom = $cre_RCI->get('customers', 'boxesbottom');
    $contents[] = array('text'  => $returned_rci_orders_top .
                                   tep_admin_files_boxes(FILENAME_ORDERS, BOX_CUSTOMERS_ORDERS, 'SSL','selected_box=customers&section=order','2') .
                                   tep_admin_files_boxes(FILENAME_CREATE_ORDER, BOX_MANUAL_ORDER_CREATE_ORDER, 'SSL','selected_box=customers&section=create_order','2') .
                                   tep_admin_files_boxes(FILENAME_CREATE_ORDERS_ADMIN, BOX_CREATE_ORDERS_ADMIN, 'SSL','selected_box=customers&section=orders_admin','2') .
                                   $returned_rci_orders_bottom .
                                   tep_admin_files_boxes('', BOX_CUSTOMERS_MENU) .
                                   $returned_rci_customers_top .
                                   tep_admin_files_boxes(FILENAME_CUSTOMERS, BOX_CUSTOMERS_CUSTOMERS, 'SSL','selected_box=customers&section=customers','2') .
                                   tep_admin_files_boxes(FILENAME_CREATE_ACCOUNT, BOX_MANUAL_ORDER_CREATE_ACCOUNT, 'SSL','selected_box=customers&section=create_account','2') .
                                   tep_admin_files_boxes(FILENAME_CRE_MARKETPLACE, BOX_CRE_MARKETPLACE, 'SSL','selected_box=customers&section=marketplace','2') .
                                   $returned_rci_customers_bottom);
    }
    // RCO eof

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
<!-- customers_eof //-->