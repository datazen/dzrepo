<?php
/*
  $Id: also_purchased_products.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if (isset($_GET['products_id'])) {
    $orders_query = tep_db_query("select p.products_id, p.products_image from " . TABLE_ORDERS_PRODUCTS . " opa, " . TABLE_ORDERS_PRODUCTS . " opb, " . TABLE_ORDERS . " o, " . TABLE_PRODUCTS . " p where opa.products_id = '" . (int)$_GET['products_id'] . "' and opa.orders_id = opb.orders_id and opb.products_id != '" . (int)$_GET['products_id'] . "' and opb.products_id = p.products_id and opb.orders_id = o.orders_id and p.products_status = '1' group by p.products_id order by o.date_purchased desc limit " . MAX_DISPLAY_ALSO_PURCHASED);
    $num_products_ordered = tep_db_num_rows($orders_query);
    if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {
?>
  <!-- also_purchased_products //-->

<?php
      echo '<h3 class="no-margin-top">' . TEXT_ALSO_PURCHASED_PRODUCTS . '</h3><div class="product-listing-module-container">';
      $row = 0;
      $col = 0;
      $info_box_contents = array();
      while ($orders = tep_db_fetch_array($orders_query)) {
        $orders['products_name'] = tep_get_products_name($orders['products_id']);
       echo  '<div class="product-listing-module-items"><div class="col-sm-4 col-lg-4 with-padding"><div class="align-center large-padding-top" style="height: 220px;"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $orders['products_image'], $orders['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>
       <br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . $orders['products_name'] . '</a></div></div></div>';

        $col ++;
        if ($col > 2) {
          $col = 0;
          $row ++;
        }
      }
	echo '</div>';
?>
<!-- also_purchased_products_eof //-->
<?php
    }
  }
?>
