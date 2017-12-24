<?php
/*
  $Id: featured.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$featured = new box_featured();

if ($featured->row_count > 0) {
?>
  <!-- featured -->
<div class="well" >
<div class="box-header small-margin-bottom small-margin-left"><?php echo  BOX_HEADING_FEATURED ; ?></div>
<div style="text-align:center">
<?php
      $featured_product21_id = $featured->rows[0]['products_id'];
      $featured_product21_image = $featured->rows[0]['products_image'];
      $featured_product21_name = tep_get_products_name($featured->rows[0]['products_id']);
      $pf->loadProduct($featured->rows[0]['products_id'],$languages_id);
      $featured_price1 = $pf->getPriceStringShort();
echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_product21_id) . '">' . tep_image(DIR_WS_IMAGES . $featured_product21_image, $featured_product21_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_product21_id, 'NONSSL') . '">' . $featured_product21_name . '</a><br>' . $featured_price1;
      ?>
 </div>
</div>

<?php
}
?>
<!-- featured eof//-->