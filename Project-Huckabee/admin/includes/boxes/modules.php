<?php
/*
  $Id: modules.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- modules //-->
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_MODULES,
                   'link'  => tep_href_link(FILENAME_MODULES, 'set=payment&selected_box=modules', 'SSL'));

  //RCI to include links
  $returned_rci_top = $cre_RCI->get('modules', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('modules', 'boxesbottom');
  $contents[] = array('text'  => $returned_rci_top .
                                 tep_admin_files_boxes(FILENAME_MODULES, BOX_MODULES_PAYMENT, 'SSL' , 'set=payment&selected_box=modules&section=payment', '2') .
                                 tep_admin_files_boxes(FILENAME_MODULES, BOX_MODULES_SHIPPING, 'NONSSL' , 'set=shipping&selected_box=modules&section=shipping', '2') . 
                                 tep_admin_files_boxes(FILENAME_MODULES, BOX_MODULES_ORDER_TOTAL, 'NONSSL' , 'set=ordertotal&selected_box=modules&section=order_total', '2') . 
                                 tep_admin_files_boxes(FILENAME_MODULES, BOX_MODULES_CHECKOUT_SUCCESS, 'NONSSL' , 'set=checkout_success&selected_box=modules&section=checkout_success', '2') .
                                 tep_admin_files_boxes(FILENAME_MODULES, BOX_MODULES_ADDONS, 'NONSSL' , 'set=addons&selected_box=modules&section=addons', '2') .
                                 $returned_rci_bottom);

$box = new box;
echo $box->menuBox($heading, $contents);
?>
<!-- modules_eof //-->