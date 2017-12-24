<?php
/*
  $Id: whats_new.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$whats = new box_whats_new();

if (count($whats->rows) > 0) {
?>
  <!-- whats_new //-->
     <div class="well">
      <div class="box-header small-margin-bottom small-margin-left"><?php echo  BOX_HEADING_WHATS_NEW ; ?></div>
      <?php
      foreach ($whats->rows as $whatsnew_product21) {
        $whatsnew_product21_id = $whatsnew_product21['products_id'];
        $whatsnew_product21_image = tep_get_products_image($whatsnew_product21['products_id']);
        $whatsnew_product21_name = tep_get_products_name($whatsnew_product21['products_id']);
        $pf->loadProduct($whatsnew_product21['products_id'],$languages_id);
        $whats_new_price = $pf->getPriceStringShort();
       echo  '<div style="text-align:center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $whatsnew_product21_id) . '">' . tep_image(DIR_WS_IMAGES . $whatsnew_product21_image, $whatsnew_product21_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $whatsnew_product21_id) . '">' . $whatsnew_product21_name . '</a><br>' . $whats_new_price .'<br></div>';
      }
      ?>

	 </div>
  <!-- whats_new eof//-->
  <?php
}
?>