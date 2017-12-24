<?php
/*
  $Id: order_history.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$history = new box_order_history();

if (count($history->rows) > 0) {
?>
    <!-- order_history //-->
    <div class="well" >
        <div class="box-header small-margin-bottom small-margin-left"><?php echo BOX_HEADING_CUSTOMER_ORDERS; ?></div>
        <?php
        foreach ($history->rows as $products) {
          // changes the cust_order into a buy_now action
          $customer_orders_string .= ' <ul class="box-information_pages-ul list-unstyled list-indent-large"><li> ' .
                                     '    <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id']) . '">' . $products['products_name'] . '</a>' .
                                     '    <a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action','cPath','products_id')) . 'action=buy_now&products_id=' . $products['products_id'] . '&cPath=' . tep_get_product_path($products['products_id'])) . '">' . tep_image(DIR_WS_ICONS . 'cart.gif', ICON_CART) . '</a>' .
                                     '</li></ul>  ';
        }
        echo '<div style="margin-left:5px">' . $customer_orders_string .'</div>';
        ?>


  <script>
  $('.box-product-categories-ul-top').addClass('list-unstyled list-indent-large');
  $('.box-product-categories-ul').addClass('list-unstyled list-indent-large');
  </script>

    </div>
    <!-- order_history_eof //-->
<?php
}
?>