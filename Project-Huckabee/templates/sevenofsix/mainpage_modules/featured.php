<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Featured Products V1.1
  Displays a list of featured products, selected from admin
  For use as an Infobox instead of the "New Products" Infobox
*/
?>
<!-- D featured_products_mainpage //-->
<?php

echo '<h1>' . TABLE_HEADING_FEATURED_PRODUCTS . '</h1>';

//Eversun mod for sppc and qty price breaks
  if(!isset($_SESSION['sppc_customer_group_id'])) {
  $customer_group_id = 'G';
  } else {
   $customer_group_id = $_SESSION['sppc_customer_group_id'];
  }
      $mainpage_featured_query = tep_db_query("select distinct
                           p.products_image,
                           p.products_id,
                           pd.products_name,
                          if (isnull(pg.customers_group_price), p.products_price, pg.customers_group_price) as products_price,
         p.products_image
                          from (" . TABLE_PRODUCTS . " p
                         left join " . TABLE_PRODUCTS_GROUPS . " pg on p.products_id = pg.products_id and pg.customers_group_id = '" . $customer_group_id . "'),
                              " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                        " . TABLE_FEATURED . " f
                                 where
                                   p.products_status = '1'
                                   and f.status = '1'
                                   and p.products_id = f.products_id
                                   and pd.products_id = f.products_id
                                   and pd.language_id = '" . $languages_id . "'
                                   and p.products_group_access like '%". $customer_group_id."%'
                                   order by rand(),p.products_date_added DESC, pd.products_name limit " . MAX_DISPLAY_FEATURED_PRODUCTS);
   $mainpage_featured_check = tep_db_num_rows($mainpage_featured_query);
     if ($mainpage_featured_check > 0){

  $row = 0;
  $col = 0;
  $num = 0;
  while ($mainpage_featured_products = tep_db_fetch_array($mainpage_featured_query)) {

    $num ++;

    if ($num == 1) {
   // new contentBoxHeading($info_box_contents, tep_href_link(FILENAME_FEATURED_PRODUCTS));
    }

  $pf->loadProduct($mainpage_featured_products['products_id'],$languages_id);
        $products_price_2bb = $pf->getPriceStringShort();
		echo  '<div class="col-sm-4 col-lg-4 text-center" style="height:250px"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $mainpage_featured_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $mainpage_featured_products['products_image'], $mainpage_featured_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $mainpage_featured_products['products_id']) . '">' . $mainpage_featured_products['products_name'] . '</a><br>' . $new_products['products_name'] . '</a></h3><p class="">' . ((INSTALLED_VERSION_TYPE == 'B2B' ||  INSTALLED_VERSION_TYPE == 'Pro')? cre_products_blurb($mainpage_featured_products['products_id']): cre_product_short_description_template($mainpage_featured_products['products_id'])) . $products_price_2bb . '</div>';

    $col ++;
    if ($col > 2) {
      $col = 0;
      $row ++;
    }
  }

 // new contentBox($info_box_contents, true, true);

}
?>
<!-- featured_products_eof //-->
