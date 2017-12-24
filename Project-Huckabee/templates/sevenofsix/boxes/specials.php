<?php
/*
  $Id: specials.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$specials = new box_specials();

if (count($specials->rows) > 0) {
?>
  <!-- specials //-->
<?php
/*
  $Id: shop_by_price.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('MODULE_SHOPBYPRICE_RANGES') && MODULE_SHOPBYPRICE_RANGES > 0) {
  ?>
<!-- shop_by_price eof//-->
    <div class="well" style="border:3px solid red">
      <div class="box-header small-margin-bottom small-margin-left"><?php echo  BOX_HEADING_SPECIALS ; ?></div>
      <?php
      foreach ($specials->rows as $product_specials22) {
        $product_specials22_id = $product_specials22['products_id'];
        $product_specials22_image = tep_get_products_image($product_specials22['products_id']);
        $product_specials22_name = tep_get_products_name($product_specials22['products_id']);
        $pf->loadProduct($product_specials22['products_id'],$languages_id);
        $special_random_price = $pf->getPriceStringShort();
		echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_specials22_id) . '">' . tep_image(DIR_WS_IMAGES . $product_specials22_image, $product_specials22_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_specials22_id) . '">' . $product_specials22_name . '</a><br>' . $special_random_price;
      }
      ?>

    </div>
  <?php
}
?>




<!-- specials eof//-->
  <?php
}
?>