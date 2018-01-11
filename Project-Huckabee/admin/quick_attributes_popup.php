<?php
/*
   quick_attributes_popup.php
   WebMakers.com Added: Show current attributes of the product
*/

include('includes/application_top.php');
require(DIR_WS_CLASSES . 'currencies.php');

$currencies = new currencies();
$look_it_up = isset($_GET['look_it_up']) ? (int)$_GET['look_it_up'] : '';
// Get Product Info
$product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . $look_it_up . "' and pd.products_id = '" . $look_it_up . "' and pd.language_id = '" . $languages_id . "'");
$product_info = tep_db_fetch_array($product_info_query);
?>
<div class="row">
  <div class="col-6">
    <div class="attributes-container">
      <div class="text-left"><?php echo QUICK_ATTRIBUTES_POPUP_TXT_1;?><span class="text-left ml-2 fw-600"><?php echo $look_it_up;?></span></div>
      <div class="lead">
        <?php echo $product_info['products_name'];?>
      </div>
    </div>
  </div>
  <div class="col-6">
    <div class="text-center">
      <?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $product_info['products_image'],'', SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);?>
    </div>
    <div class="text-center"><?php echo QUICK_ATTRIBUTES_POPUP_TXT_2;?> <?php echo $product_info['products_model'];?></div>
  </div>
</div>
<?php
// BOF: attribute options
echo '<div class="product-attributes-list">';
$products_attributes = tep_db_query("select poptt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt,  " . TABLE_PRODUCTS_OPTIONS_TEXT  . " poptt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $look_it_up . "' and patrib.options_id = popt.products_options_id and poptt.language_id = '" . $languages_id . "'");
if (tep_db_num_rows($products_attributes)) {
  $products_attributes = '1';
} else {
  $products_attributes = '0';
  echo '<div class="text-danger"><b>' . QUICK_ATTRIBUTES_POPUP_TXT_3 . '</b></div>';
}
if ($products_attributes == '1') {
  $products_options_name = tep_db_query("select distinct popt.products_options_id, poptt.products_options_name, popt.products_options_sort_order from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_TEXT  . " poptt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $look_it_up . "' and patrib.options_id = popt.products_options_id and poptt.language_id = '" . $languages_id . "'" . " order by popt.products_options_sort_order");
  echo '<div class=""><b>' . QUICK_ATTRIBUTES_POPUP_TXT_4 . '</b></div>';
  while ($products_options_name_values = tep_db_fetch_array($products_options_name)) {
    $selected = 0;
    $products_options_array = array();
    echo '<div><span>' . $products_options_name_values['products_options_name'] . ':</span><span>' . "\n";
    $products_options = tep_db_query("select pa.products_options_sort_order, pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $look_it_up . "' and pa.options_id = '" . $products_options_name_values['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . $languages_id . "'" . " order by pa.products_options_sort_order, pa.options_values_price");
    while ($products_options_values = tep_db_fetch_array($products_options)) {
      $products_options_array[] = array('id' => $products_options_values['products_options_values_id'], 'text' => $products_options_values['products_options_values_name']);
      if ($products_options_values['options_values_price'] != '0') {
        $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options_values['price_prefix'] . $currencies->format($products_options_values['options_values_price']) .') ';
      }
    }
    echo tep_draw_pull_down_menu('id[' . $products_options_name_values['products_options_id'] . ']', $products_options_array, $cart->contents[$_GET['products_id']]['attributes'][$products_options_name_values['products_options_id']]);
    echo '</span></div>';
  }
}
echo '</div>';
// EOF: attribute options
?>