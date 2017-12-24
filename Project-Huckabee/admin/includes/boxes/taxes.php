<?php
/*
  $Id: taxes.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- taxes //-->
<?php
$heading = array();
$contents = array();
$heading[] = array('text'  => BOX_HEADING_LOCATION_AND_TAXES,
                   'link'  => tep_href_link(FILENAME_COUNTRIES, 'selected_box=taxes'));

  //RCI to include links  
  $returned_rci_top = $cre_RCI->get('taxes', 'boxestop');
  $returned_rci_bottom = $cre_RCI->get('taxes', 'boxesbottom');
  $contents[] = array('text'  => $returned_rci_top .
                                 tep_admin_files_boxes(FILENAME_COUNTRIES, BOX_TAXES_COUNTRIES, 'SSL','selected_box=taxes&section=countries','2') .
                                 tep_admin_files_boxes(FILENAME_ZONES, BOX_TAXES_ZONES, 'SSL','selected_box=taxes&section=zones','2') .
                                 tep_admin_files_boxes(FILENAME_GEO_ZONES, BOX_TAXES_GEO_ZONES, 'SSL','selected_box=taxes&section=geo_zones','2') .
                                 tep_admin_files_boxes(FILENAME_TAX_CLASSES, BOX_TAXES_TAX_CLASSES, 'SSL','selected_box=taxes&section=tax_classes','2') .
                                 tep_admin_files_boxes(FILENAME_TAX_RATES, BOX_TAXES_TAX_RATES, 'SSL','selected_box=taxes&section=tax_rates','2') .
                                 $returned_rci_bottom);

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
<!-- taxes_eof //-->