
<?php
/*
$Id: xsell_products.php, v1  2002/09/11

osCommerce, Open Source E-Commerce Solutions
<http://www.oscommerce.com>

Copyright (c) 2002 osCommerce

Released under the GNU General Public License
*/

  if(!isset($_SESSION['sppc_customer_group_id'])) {
  $customer_group_id = 'G';
  } else {
   $customer_group_id = $_SESSION['sppc_customer_group_id'];
  }

if ($_GET['products_id']) {

$xsell_query = tep_db_query("select distinct
   p.products_id,
   p.products_image,
   p.products_price,
   p.manufacturers_id,
   pd.products_name,
   p.products_tax_class_id,
   p.products_price,
   p.products_date_added,
   p.products_image ,
   xp.xsell_id
   products_price
from (" . TABLE_PRODUCTS . " p
      left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id),
" . TABLE_PRODUCTS_XSELL . " xp,
" . TABLE_PRODUCTS_DESCRIPTION . " pd
where
xp.products_id = '" . (int)$_GET['products_id'] . "'
and p.products_id = xp.xsell_id
and pd.products_id = xp.xsell_id
and pd.language_id = '" . $languages_id . "'
and p.products_status = '1'
order by rand(), xp.products_id asc limit " . MAX_DISPLAY_XSELL);


$num_products_xsell = tep_db_num_rows($xsell_query);

if ($num_products_xsell >= MIN_DISPLAY_XSELL) {
  require(DIR_WS_LANGUAGES . $language . '/modules/xsell_products.php');
?>
<!-- xsell_products //-->

<?php

echo '<h3>' . TEXT_XSELL_PRODUCTS . '</h3>';

      $row = 0;
      $col = 0;
      $info_box_contents = array();
      while ($xsell = tep_db_fetch_array($xsell_query)) {

        $pf->loadProduct($xsell['products_id'],$languages_id);
        $xsell_price = $pf->getPriceStringShort();
        $s_buy_now = '';
        $hide_add_to_cart = hide_add_to_cart();
        if ($hide_add_to_cart == 'false' && group_hide_show_prices() == 'true') {
          $s_buy_now = '<br><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action','cPath','products_id')) . 'action=buy_now&products_id=' . $xsell['products_id'] . '&cPath=' . tep_get_product_path($xsell['products_id']), 'NONSSL') . '">' . tep_template_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) .'</a>';
        }
       echo  '<div class="col-sm-4 col-lg-4 text-center" ><div class="thumbnail small-padding-top small-padding-bottom small-padding-left small-padding-right"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $xsell['products_image'], $xsell['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $xsell['products_id']) . '">' . $xsell['products_name'] .'</a><br>' . $xsell_price. $s_buy_now. '</div></div>';

        $col ++;
        if ($col > 2) {
          $col = 0;
          $row ++;
        }
      }

// Modify to avoid display if no X-Sells Defined - courtesy Halbert DMG
//  halbert mod - Don't show heading if no cross-sell items
if (($num_products_xsell >= MIN_DISPLAY_XSELL) && ($num_products_xsell > 0)) {
      new contentBox($info_box_contents, true, true);

// added one brace for Halbert if statement DMG
}
?>

<!-- xsell_products_eof //-->
<?php
    }
  }
?>