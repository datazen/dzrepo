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
  <!-- shop by price //-->
     <div class="well">
      <div class="box-header small-margin-bottom small-margin-left"><?php echo  BOX_HEADING_SHOP_BY_PRICE ; ?></div>
            <?php
	        require_once(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOP_BY_PRICE);
	        $sbp_array = unserialize(MODULE_SHOPBYPRICE_RANGE);
	        echo '<a href="' . tep_href_link(FILENAME_SHOP_BY_PRICE, 'range=0', 'NONSSL') . '">' . TEXT_INFO_UNDER . $currencies->format($sbp_array[0]) . '</a><br>';
	        for ($i=1, $ii=count($sbp_array); $i < $ii; $i++) {
	          echo '<a href="' . tep_href_link(FILENAME_SHOP_BY_PRICE, 'range=' . $i, 'NONSSL') . '">' . TEXT_INFO_FROM . $currencies->format($sbp_array[$i-1]) . TEXT_INFO_TO . $currencies->format($sbp_array[$i]) . '</a><br>';
	        }
	        if (defined('MODULE_SHOPBYPRICE_OVER') && MODULE_SHOPBYPRICE_OVER == True) {
	          echo '<a href="' . tep_href_link(FILENAME_SHOP_BY_PRICE, 'range=' . $i, 'NONSSL') . '">' . $currencies->format($sbp_array[$i-1]) . TEXT_INFO_ABOVE . '</a><br>';
	        }
	        ?>

    </div>
  <?php
}
?>
<!-- shop_by_price eof//-->