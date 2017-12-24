<?php
/*
  $Id: reports.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- reports //-->
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_REPORTS,
                   'link'  => tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED, 'selected_box=reports'));

  //RCI to include links 
  $returned_rci_top = $cre_RCI->get('reports', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('reports', 'boxesbottom');
  $returned_rci_bottom2 = $cre_RCI->get('reports', 'boxesbottom2'); 
  $returned_rci_bottom3 = $cre_RCI->get('reports', 'boxesbottom3'); 
  $returned_rci_bottom4 = $cre_RCI->get('reports', 'boxesbottom4'); 
  $returned_rci_bottom5 = $cre_RCI->get('reports', 'boxesbottom5');
  $returned_rci_bottom5 = $cre_RCI->get('reports', 'boxesbottom6');    
  $contents[] = array('text'  => $returned_rci_top .
                                 // top viewed
                                 tep_admin_files_boxes(FILENAME_STATS_PRODUCTS_VIEWED, BOX_REPORTS_PRODUCTS_VIEWED, 'SSL','selected_box=reports&section=reports_products_viewed','2') .
                                 tep_admin_files_boxes(FILENAME_STATS_PRODUCTS_PURCHASED, BOX_REPORTS_PRODUCTS_PURCHASED, 'SSL','selected_box=reports&section=reports_products_purchased','2') .
                                 tep_admin_files_boxes(FILENAME_STATS_ARTICLES_VIEWED,BOX_REPORTS_ARTICLES_VIEWED, 'SSL','selected_box=reports&section=reports_articles_viewed','2') .
                                 $returned_rci_bottom .
                                 // customer
                                 tep_admin_files_boxes(FILENAME_STATS_WISHLIST, BOX_REPORTS_CUSTOMER_WISHLIST, 'SSL','selected_box=reports&section=reports_wishlist','2') .
                                 tep_admin_files_boxes(FILENAME_STATS_CUSTOMERS_ORDERS, BOX_REPORTS_CUSTOMERS_ORDERS, 'SSL','selected_box=reports&section=reports_orders','2') .   
                                 tep_admin_files_boxes(FILENAME_STATS_CUSTOMERS, BOX_REPORTS_ORDERS_TOTAL, 'SSL','selected_box=reports&section=reports_orders_total','2') .
                                 tep_admin_files_boxes(FILENAME_STATS_NOT_VALID_USER, BOX_REPORTS_NOT_VALID_USER, 'SSL','selected_box=reports&section=reports_invalid_user','2') .
                                 $returned_rci_bottom2 .
                                 // sales
                                 tep_admin_files_boxes(FILENAME_STATS_SALES_REPORT2, BOX_REPORTS_SALES_REPORT2, 'SSL','selected_box=reports&section=reports_sales_report','2') .             
                                 tep_admin_files_boxes(FILENAME_STATS_DAILY_SALES_REPORT, BOX_REPORTS_DAILY_PRODUCTS_ORDERS, 'SSL','selected_box=reports&section=reports_daily_orders','2') .
                                 tep_admin_files_boxes(FILENAME_STATS_MANUFACTURERS, BOX_REPORTS_SALES_MANUFACTURERS, 'SSL','selected_box=reports&section=reports_manufacturer_sales','2') .
                                 $returned_rci_bottom3 . 
                                 // products
                                 tep_admin_files_boxes(FILENAME_STATS_PRODUCTS_NOTIFICATIONS, BOX_REPORTS_PRODUCTS_NOTIFICATIONS, 'SSL','selected_box=reports&section=reports_product_notifications','2') .
                                 tep_admin_files_boxes(FILENAME_STATS_LOW_STOCK, BOX_LOW_STOCK_REPORT, 'SSL','selected_box=reports&section=reports_low_stock','2') .
                                 $returned_rci_bottom4 .
                                 // orders
                                 tep_admin_files_boxes(FILENAME_ORDERLIST, BOX_REPORTS_ORDERLIST, 'SSL','selected_box=reports&section=reports_orderlist','2') . 
                                 // other
                                 $returned_rci_bottom5 .
                                 tep_admin_files_boxes(FILENAME_STATS_MONTHLY_SALES, BOX_REPORTS_MONTHLY_SALES, 'SSL','selected_box=reports&section=reports_monthly_sales','2') .
                                 $returned_rci_bottom6);  

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
<!-- reports_eof //-->