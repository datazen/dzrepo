<?php
/*
  $Id: upcoming_products.php,v 1.1.1.3 2004/04/07 23:42:23 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- upcoming_products mainpage_modules //-->
<?php
   echo '<div class="col-sm-10 col-lg-10"><h3 class="no-margin-top">' . sprintf(TABLE_HEADING_UPCOMING_PRODUCTS, strftime('%B')) .'</h3></div>';


  if (!isset($_SESSION['sppc_customer_group_id'])) {
    $customer_group_id = 'G';
  } else {
    $customer_group_id = $_SESSION['sppc_customer_group_id'];
  }

  $expected_query_raw= tep_db_query("select p.products_id, pd.products_name, p.products_image, products_date_available as date_expected,
     if (isnull(pg.customers_group_price), p.products_price, pg.customers_group_price) as products_price
     from (" . TABLE_PRODUCTS . " p
     left join " . TABLE_PRODUCTS_GROUPS . " pg on p.products_id = pg.products_id and pg.customers_group_id = '" . $customer_group_id . "'),
           " . TABLE_PRODUCTS_DESCRIPTION . " pd
     where to_days(products_date_available) >= to_days(now())
       and p.products_id = pd.products_id
       and pd.language_id = '" . (int)$languages_id . "'
       and p.products_group_access like '%". $customer_group_id."%'
     order by rand()
     limit " . MAX_DISPLAY_UPCOMING_PRODUCTS);

  $row = 0;
  $col = 0;
  $num = 0;
  while ($expected_query = tep_db_fetch_array($expected_query_raw)) {
    $num ++;
    if ($num == 1) {
    echo '<div class="col-sm-2 col-lg-2 hide-on-mobile small-margin-top text-right"><a href="' . tep_href_link(FILENAME_UPCOMING_PRODUCTS, '', 'SSL') .'"><img src="templates/cre65_rspv/images/rightarrow.png"></a></div>';
    }
    $pf->loadProduct($expected_query['products_id'],$languages_id);
    $products_price_s = '<div class="price_mainpage">'. $pf->getPriceStringShort() .'</div>';

    $duedate= str_replace("00:00:00", "" , $expected_query['date_expected']);

		echo '<div class="col-sm-4 col-lg-4 text-center"><div class="thumbnail small-padding-top" style="height:280px"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $expected_query['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $expected_query['products_image'], $expected_query['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $expected_query['products_id']) . '">' . $expected_query['products_name'] . '</a><br>' . cre_products_blurb($expected_query['products_id']) . $products_price_s . '<br><div class="alert-success" style="color:#ff0000;">' . TABLE_HEADING_DATE_EXPECTED . '&nbsp;' .$duedate .'</div></div></div>';


    $col ++;
    if ($col > 2) {
      $col = 0;
      $row ++;
    }
  }
?>
<!--D upcoming_products_eof //-->