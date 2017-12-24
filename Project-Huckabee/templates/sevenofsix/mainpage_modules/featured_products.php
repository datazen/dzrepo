<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Featured Products Listing Module
*/
?>
<!--D featured_products-->
<?php
 require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FEATURED_PRODUCTS);

//Eversun mod for sppc and qty price breaks
  if(!isset($_SESSION['sppc_customer_group_id'])) {
  $customer_group_id = 'G';
  } else {
   $customer_group_id = $_SESSION['sppc_customer_group_id'];
  }
     $info_box_contents = array();
 $mainpage_featured2_query = tep_db_query("select distinct
                           p.products_image,
                           p.products_id,
                           pd.products_name
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

  $row = 0;
  $col = 0;
  $num = 0;
   $mainpage_featured2_check = tep_db_num_rows($mainpage_featured2_query);
     if ($mainpage_featured2_check > 0){
  while ($mainpage_featured2_products = tep_db_fetch_array($mainpage_featured2_query)) {
   $num ++;
   $mainpage_featured2_products_array[] = array('id' => $mainpage_featured2_products['products_id'],
                                  'name' => $mainpage_featured2_products['products_name'],
                                  'blurb' =>  cre_products_blurb($mainpage_featured2_products['products_id']),
                                  'image' => $mainpage_featured2_products['products_image']   );
  }
     for($i=0; $i<sizeof($mainpage_featured2_products_array); $i++) {
        $num++;
        $pf->loadProduct($mainpage_featured2_products_array[$i]['id'],$languages_id);
        $products_price = str_replace('&nbsp;',' ',$pf->getPriceStringShort());


$featured_str  = '';
$featured_str = '
		<div class="col-sm-4 col-lg-4 no-padding-left no-padding-right"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $mainpage_featured2_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $mainpage_featured2_products_array[$i]['image'], $mainpage_featured2_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT,'hspace="5" vspace="5" align="center"') . '</a></div>
		<div class="col-sm-8 col-lg-8 no-padding-left no-padding-right"><div  class="pageHeading"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $mainpage_featured2_products_array[$i]['id'], 'NONSSL') . '">' . $mainpage_featured2_products_array[$i]['name'] . '</a></div><br />
      '  . (tep_not_null($mainpage_featured2_products_array[$i]['blurb'] && PRODUCT_BLURB == 'true') ? $mainpage_featured2_products_array[$i]['blurb'] . '<br>' : '') . '
      </div><div class="clearfix"></div><div class="col-sm-7 co-lg-7 text-center"><div class="price_mainpage">' . $products_price . '</div></div>';
if (hide_add_to_cart() == 'false' && group_hide_show_prices() == 'true') {
  $featured_str  .= '<div class="col-sm-5 co-lg-5 text-center"><a href="' . tep_href_link(FILENAME_SHOPPING_CART, tep_get_all_get_params(array('action','products_id')) . 'action=buy_now&products_id=' . $mainpage_featured2_products_array[$i]['id']) . '" class="btn btn-sm cursor-pointer small-margin-right btn-success">' . IMAGE_BUTTON_BUY_NOW . '</a></div>';
}
 echo '<div class="product-listing-module-items">
 			<div class="col-sm-6 col-lg-6 ">
 				<div class="thumbnail featur_p_r large-padding-top small-padding-bottom ">' . $featured_str .
 				'</div>
 				</div>
 			   </div>';
  $col ++;
}
  }
?>
 <!-- featured_products eof -->