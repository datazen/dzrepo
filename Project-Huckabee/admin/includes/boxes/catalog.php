<?php
/*
  $Id: catalog.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- catalog //-->
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_CATALOG,
                   'link'  => tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog'));

  // RCO start
  if ($cre_RCO->get('categories', 'columnleft') !== true) {     
    //RCI to include links  
    $returned_rci_top = $cre_RCI->get('catalog', 'boxestop');
    $returned_rci_bottom = $cre_RCI->get('catalog', 'boxesbottom');
    $contents[] = array('text'  => $returned_rci_top .
                                   // NOTE: keep section= as last parameter   
                                   tep_admin_files_boxes(FILENAME_CATEGORIES, BOX_CATALOG_CATEGORIES_PRODUCTS, 'SSL','selected_box=catalog&section=categories','2') .
                                   tep_admin_files_boxes(FILENAME_PRODUCTS_ATTRIBUTES, BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES, 'SSL','selected_box=catalog&section=products_attributes','2') .
                                   tep_admin_files_boxes(FILENAME_PRODUCTS_MULTI, BOX_CATALOG_CATEGORIES_PRODUCTS_MULTI, 'SSL','selected_box=catalog&section=products_multi','2') .
                                   tep_admin_files_boxes(FILENAME_MANUFACTURERS, BOX_CATALOG_MANUFACTURERS, 'SSL','selected_box=catalog&section=manufacturers','2') .
                                   tep_admin_files_boxes(FILENAME_REVIEWS, BOX_CATALOG_REVIEWS, 'SSL','selected_box=catalog&section=reviews','2') .
                                   tep_admin_files_boxes(FILENAME_SHOPBYPRICE, BOX_CATALOG_SHOP_BY_PRICE, 'SSL','selected_box=catalog&section=shopbyprice','2') .
                                   tep_admin_files_boxes(FILENAME_XSELL_PRODUCTS, BOX_CATALOG_XSELL_PRODUCTS, 'SSL','selected_box=catalog&section=xsell_products','2') .
                                   tep_admin_files_boxes(FILENAME_FEATURED, BOX_CATALOG_FEATURED, 'SSL','selected_box=catalog&section=featured','2') .
                                   tep_admin_files_boxes(FILENAME_PRODUCTS_EXPECTED, BOX_CATALOG_PRODUCTS_EXPECTED, 'SSL','selected_box=catalog&section=products_expected','2') .
                                   tep_admin_files_boxes(FILENAME_PRODUCTS_EXTRA_FIELDS,BOX_CATALOG_PRODUCTS_EXTRA_FIELDS, 'SSL','selected_box=catalog&section=products_extra_fields','2') .
                                   $returned_rci_bottom);                                     
  }
  // RCO eof

$box = new box;
echo $box->menuBox($heading, $contents);
?>
<!-- catalog_eof //-->