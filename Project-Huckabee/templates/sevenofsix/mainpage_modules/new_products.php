<?php
/*

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- D mainpages_modules.new_products.php//-->
<?php
echo '<h3 class="no-margin-top">'. sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')) .'</h3>';
      $new_products_query = tep_db_query("select distinct
                          p.products_id,
                          p.products_price,
                          p.manufacturers_id,
                          pd.products_name,
                          p.products_tax_class_id,
                          p.products_price,
                          p.products_date_added,
                           p.products_image
                          from (" . TABLE_PRODUCTS . " p
      left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id),
      " . TABLE_PRODUCTS_DESCRIPTION . " pd
      where
       p.products_status = '1'
       and pd.products_id = p.products_id
       and pd.language_id = '" . $languages_id . "'
       and DATE_SUB(CURDATE(),INTERVAL " .NEW_PRODUCT_INTERVAL ." DAY) <= p.products_date_added
       order by rand(), p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);




  $row = 0;
  $col = 0;
  $num = 0;
  while ($new_products = tep_db_fetch_array($new_products_query)) {

	$pf->loadProduct($new_products['products_id'],$languages_id);
	$products_price_s = $pf->getPriceStringShort();

    echo '<div class="product-listing-module-container"><div class="product-listing-module-items"><div class="col-sm-4 col-lg-4 with-padding"><div class="thumbnail align-center large-padding-top"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $new_products['products_image'], $new_products['products_name'], 200) . '</a><br/><h3 style="line-height:1.1;"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . $new_products['products_name'] . '</a></h3><div class="row pricing-row"><div class="col-sm-6 col-lg-6"> <p class="lead small-margin-bottom">' . $products_price_s . '</p></div><div class="col-sm-5 col-lg-5 no-margin-left buy-btn-div"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action','cPath','products_id')) . 'action=buy_now&amp;products_id=' . $new_products['products_id']) . '" style="text-decoration:none"><button type="button" class="content-new-products-add-button btn btn-success btn-block">Buy Now</button></a></div></div></div></div></div></div>';
  }
?>
<!-- D new_products_eof //-->
