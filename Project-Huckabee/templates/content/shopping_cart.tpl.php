<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('shoppingcart', 'top');
// RCI code eof
if ($cart->count_contents() > 0) {
echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product'));
}
?>
<div class="row">
  <div class="col-sm-12 col-lg-12">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
    <h1 class="no-margin-top"><?php echo HEADING_TITLE; ?></h1>



<?php
}else{
$header_text = '<h1 class="no-margin-top">'. HEADING_TITLE .'</h1>';
}
?>

<?php
  if ($cart->count_contents() > 0) {
?>
      <form role="form" class="no-margin-bottom" name="shopping_cart" id="shopping_cart" method="post">
        <table class="table tabled-striped table-responsive no-margin-bottom" id="shopping-cart-table">
          <thead>
            <tr>
              <th><?php echo TABLE_HEADING_PRODUCTS; ?></th>
              <th class="text-left hide-on-mobile-portrait"></th>
              <th  class="text-right hide-on-mobile-portrait"><?php echo TABLE_HEADING_UNIT_PRICE; ?></th>
              <th class="text-center large-padding-left"><?php echo TABLE_HEADING_QUANTITY; ?></th>
              <th class="text-right"><?php echo TABLE_HEADING_TOTAL; ?></th>
              <th class="text-center">Remove</th>
            </tr>
          </thead>
		  <tbody>

<?php

    $any_out_of_stock = 0;
    $products = $cart->get_products();

    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      $db_sql = "select products_parent_id from " . TABLE_PRODUCTS . " where products_id = " . (int)$products[$i]['id'];
      $products_parent_id = tep_db_fetch_array(tep_db_query($db_sql));
// Push all attributes information in an array
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        $attribute_product_id = (int)$products[$i]['id'];
        if ((int)$products_parent_id['products_parent_id'] != 0) {
          $attribute_product_id = (int)$products_parent_id['products_parent_id'];
        }

        reset($products[$i]['attributes']);
        while (list($option, $value) = each($products[$i]['attributes'])) {
          if ( ! is_array($value) ) {
            $attributes = tep_db_query("select op.options_id, ot.products_options_name, o.options_type, ov.products_options_values_name, op.options_values_price, op.price_prefix
                                        from " . TABLE_PRODUCTS_ATTRIBUTES . " op,
                                             " . TABLE_PRODUCTS_OPTIONS_VALUES . " ov,
                                             " . TABLE_PRODUCTS_OPTIONS . " o,
                                             " . TABLE_PRODUCTS_OPTIONS_TEXT . " ot
                                        where op.products_id = " . $attribute_product_id . "
                                          and op.options_values_id = " . $value . "
                                          and op.options_id = " . $option . "
                                          and ov.products_options_values_id = op.options_values_id
                                          and ov.language_id = " . (int)$languages_id . "
                                          and o.products_options_id = op.options_id
                                          and ot.products_options_text_id = o.products_options_id
                                          and ot.language_id = " . (int)$languages_id . "
                                       ");
            $attributes_values = tep_db_fetch_array($attributes);

            $products[$i][$option][$value]['products_options_name'] = $attributes_values['products_options_name'];
            $products[$i][$option][$value]['options_values_id'] = $value;
            $products[$i][$option][$value]['products_options_values_name'] = $attributes_values['products_options_values_name'];
            $products[$i][$option][$value]['options_values_price'] = $attributes_values['options_values_price'];
            $products[$i][$option][$value]['price_prefix'] = $attributes_values['price_prefix'];

          } elseif ( isset($value['c'] ) ) {
            foreach ($value['c'] as $v) {
              $attributes = tep_db_query("select op.options_id, ot.products_options_name, o.options_type, ov.products_options_values_name, op.options_values_price, op.price_prefix
                                          from " . TABLE_PRODUCTS_ATTRIBUTES . " op,
                                               " . TABLE_PRODUCTS_OPTIONS_VALUES . " ov,
                                               " . TABLE_PRODUCTS_OPTIONS . " o,
                                               " . TABLE_PRODUCTS_OPTIONS_TEXT . " ot
                                          where op.products_id = " . $attribute_product_id . "
                                            and op.options_values_id = " . $v . "
                                            and op.options_id = " . $option . "
                                            and ov.products_options_values_id = op.options_values_id
                                            and ov.language_id = " . (int)$languages_id . "
                                            and o.products_options_id = op.options_id
                                            and ot.products_options_text_id = o.products_options_id
                                            and ot.language_id = " . (int)$languages_id . "
                                         ");
              $attributes_values = tep_db_fetch_array($attributes);

              $products[$i][$option][$v]['products_options_name'] = $attributes_values['products_options_name'];
              $products[$i][$option][$v]['options_values_id'] = $v;
              $products[$i][$option][$v]['products_options_values_name'] = $attributes_values['products_options_values_name'];
              $products[$i][$option][$v]['options_values_price'] = $attributes_values['options_values_price'];
              $products[$i][$option][$v]['price_prefix'] = $attributes_values['price_prefix'];
            }

          } elseif ( isset($value['t'] ) ) {
            $attributes = tep_db_query("select op.options_id, ot.products_options_name, o.options_type, op.options_values_price, op.price_prefix
                                        from " . TABLE_PRODUCTS_ATTRIBUTES . " op,
                                             " . TABLE_PRODUCTS_OPTIONS . " o,
                                             " . TABLE_PRODUCTS_OPTIONS_TEXT . " ot
                                        where op.products_id = " . $attribute_product_id . "
                                          and op.options_id = " . $option . "
                                          and o.products_options_id = op.options_id
                                          and ot.products_options_text_id = o.products_options_id
                                          and ot.language_id = " . (int)$languages_id . "
                                       ");
            $attributes_values = tep_db_fetch_array($attributes);

            $products[$i][$option]['t']['products_options_name'] = $attributes_values['products_options_name'];
            $products[$i][$option]['t']['options_values_id'] = '0';
            $products[$i][$option]['t']['products_options_values_name'] = $value['t'];
            $products[$i][$option]['t']['options_values_price'] = $attributes_values['options_values_price'];
            $products[$i][$option]['t']['price_prefix'] = $attributes_values['price_prefix'];
          }
        }
      }
    }
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      if (($i/2) == floor($i/2)) {
        $info_box_contents[] = array('params' => 'class="productListing-even"');
      } else {
        $info_box_contents[] = array('params' => 'class="productListing-odd"');
      }

      $cur_row = sizeof($info_box_contents) - 1;

echo	'<tr>';
///////////////////////////////////////////////////////////////////////////////////////////////////////
// MOD begin of sub product
    if ((int)$products_parent_id['products_parent_id'] != 0) {
    $products_name =
                       '  ' .
                       '<td class="text-left content-shopping-cart-image-td">    <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_parent_id['products_parent_id']) . '">' . tep_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td>' .
                       '    <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_parent_id['products_parent_id']) . '"><b>' . $products[$i]['name'] . '</b></a>';
    } else {
    $products_name =
                       '  ' .
                       '   <td class="text-left content-shopping-cart-image-td"> <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' . tep_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td>' .
                       '  <td class="text-left hide-on-mobile-portrait">  <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '"><h4 class="no-margin-top small-margin-bottom">' . $products[$i]['name'] . '</h4></a>';
    }

echo '</tr>';
      if (STOCK_CHECK == 'true') {
        $stock_check = tep_check_stock((int)$products[$i]['id'], $products[$i]['quantity']);
        if (tep_not_null($stock_check)) {
          $any_out_of_stock = 1;
          $products_name .= $stock_check;
        }
      }
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        reset($products[$i]['attributes']);
        while (list($option, $value) = each($products[$i]['attributes'])) {
          if ( !is_array($value) ) {
            if ($products[$i][$option][$value]['options_values_price'] > 0 ){
              $attribute_price = $products[$i][$option][$value]['price_prefix']  . $currencies->display_price($products[$i][$option][$value]['options_values_price'], tep_get_tax_rate($products[$i]['tax_class_id']));
            } else {
              $attribute_price ='';
            }
            $products_name .= '<br>' . ' - ' . '<small><i>' . $products[$i][$option][$value]['products_options_name'] . ' : ' . $products[$i][$option][$value]['products_options_values_name'] . '&nbsp;&nbsp;&nbsp;' .$attribute_price . '</i></small>';
          } else {
            if ( isset($value['c']) ) {
              foreach ( $value['c'] as $v ) {
                if ($products[$i][$option][$v]['options_values_price'] > 0 ){
                  $attribute_price = $products[$i][$option][$v]['price_prefix']  . $currencies->display_price($products[$i][$option][$v]['options_values_price'], tep_get_tax_rate($products[$i]['tax_class_id']));
                } else {
                  $attribute_price ='';
                }
                $products_name .= '<br>' . ' - ' . '<small><i>' . $products[$i][$option][$v]['products_options_name'] . ' : ' . $products[$i][$option][$v]['products_options_values_name'] . '&nbsp;&nbsp;&nbsp;' .$attribute_price . '</i></small>';
              }
            } elseif ( isset($value['t']) ) {
              if ($products[$i][$option]['t']['options_values_price'] > 0 ){
                $attribute_price = $products[$i][$option]['t']['price_prefix']  . $currencies->display_price($products[$i][$option]['t']['options_values_price'], tep_get_tax_rate($products[$i]['tax_class_id']));
              } else {
                $attribute_price ='';
              }
              $products_name .= '<br>' . ' - ' . '<small><i>' . $products[$i][$option]['t']['products_options_name'] . ' : ' . $value['t'] . '&nbsp;&nbsp;&nbsp;' . $attribute_price . '</i></small>';
            }
          }
        }
      }


      echo  $products_name;


     echo '<td class="text-right hide-on-mobile-portrait content-shopping-cart-price-td">' . $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id'])) . '</td>';
     echo '<td class="text-right content-shopping-cart-qty-input-td">' .tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'size="4" maxlength="4"' ) . tep_draw_hidden_field('products_id[]', $products[$i]['id_string']).'</td>';

      echo  ' <td class="text-right"><span class="price">' . $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</span></td>';

      echo '<td class="text-center content-shopping-cart-remove-td">'. tep_draw_checkbox_field('cart_delete[]', $products[$i]['id_string']).'</td>';

    }

?>
        </tbody>
          <tfoot>
            <tr>
              <td></td>
              <td class="hide-on-mobile-portrait"></td>
              <td class="hide-on-mobile-portrait"></td>
              <td colspan="3"></td>

            </tr>
          </tfoot>

   	   </table>
   	   </form>

<?php    if ($any_out_of_stock == 1) {
      if (STOCK_ALLOW_CHECKOUT == 'true') {
        $valid_to_checkout = true;
?>

        <p class="alert alert-danger text-center"><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></p>

<?php
      } else {
        $valid_to_checkout= false;
?>

       <p class="alert alert-danger text-center"><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?><p>

<?php
      }
    }
?>
<?php echo tep_template_image_submit('index.png', IMAGE_BUTTON_UPDATE_CART)  ;?>
      <div class="row" id="content-shopping-cart-order-totals">
        <div id="content-shopping-cart-order-totals-left" class="col-sm-6 col-lg-9"></div>
        <div id="content-shopping-cart-order-totals-right" class="col-sm-6 col-lg-3">
<div class="clearfix">
   <?php

      // RCI code start
      $offset_amount = 0;
      $returned_rci = $cre_RCI->get('shoppingcart', 'offsettotal');
      // RCI code eof
      if (trim(strip_tags($returned_rci)) != NULL) {
        echo '      <b>' . SUB_TITLE_SUB_TOTAL . '</b>' . "\n";
        echo '      <b>' . $currencies->format($cart->show_total()) . '</b>' . "\n";
        echo $returned_rci;
        echo '      <b>' . SUB_TITLE_TOTAL . '</b></td>' . "\n";
        echo '     <b>' . $currencies->format($cart->show_total() + $offset_amount) . '</b>' . "\n";
      } else {
        echo '  <div class="clearfix"><span class="pull-left ot-total">' . SUB_TITLE_TOTAL . '</span>&nbsp;&nbsp;<span class="pull-right ot-total">' . $currencies->format($cart->show_total()) . '</span></div>' . "\n";
      }
?>
</div></div></div>


<?php
// RCI code start
echo $cre_RCI->get('shoppingcart', 'insideformabovebuttons');
// RCI code eof
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
?>


       <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>




                <?php /* echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>
                <?php echo tep_template_image_submit('button_update_cart.gif', IMAGE_BUTTON_UPDATE_CART); */ ?>
<?php
if (RETURN_CART == 'L'){
   $back = sizeof($navigation->path)-2;
    if (isset($navigation->path[$back])) {
        /***** Fix ********/
        $link_vars_post = tep_array_to_string($navigation->path[$back]['post'], array('cart_quantity','id'));
        $link_vars_get = tep_array_to_string($navigation->path[$back]['get'], array('action'));

        $return_link_vars = '';
        if($link_vars_get != '' && $link_vars_post !=''){
            $return_link_vars = $link_vars_get . '&' . $link_vars_post;
        } else if($link_vars_get != '' && $link_vars_post == ''){
            $return_link_vars = $link_vars_get;
        } else if($link_vars_get == '' && $link_vars_post != ''){
            $return_link_vars = $link_vars_post;
        }

       $nav_link = '<div class="margin-top large-margin-bottom pull-right"><a href="' . tep_href_link($navigation->path[$back]['page'], $return_link_vars, $navigation->path[$back]['mode']) . '"> <button  class="btn btn-lg btn-success disabled" type="button">' .  IMAGE_BUTTON_CONTINUE_SHOPPING . '</button></a></div>';
       //$nav_link = '<a href="' . tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']) . '">' . tep_template_image_button('button_continue_shopping.gif', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>';
       /***** fix end ****/
    }
 } else if ((RETURN_CART == 'C') || (isset($_SERVER['HTTP_REFERER']) && stristr($_SERVER['HTTP_REFERER'], 'wishlist'))){
  if (!stristr($_SERVER['HTTP_REFERER'], 'wishlist')) {
    $products = $cart->get_products();
    $products = array_reverse($products);
    $cat = tep_get_product_path($products[0]['id']) ;
    $cat1= 'cPath=' . $cat;
    if ($products == '') {
      $back = sizeof($navigation->path)-2;
      if (isset($navigation->path[$back])) {
        $nav_link = '<a href="' . tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']) . '">' . tep_template_image_button('button_continue_shopping.gif', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>';
      }
    }else{
      $nav_link = '<a href="' . tep_href_link(FILENAME_DEFAULT, $cat1) . '">' . tep_template_image_button('button_continue_shopping.gif', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>'  ;
    }
  }else{
    $nav_link = '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue_shopping.gif', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>'  ;
  }
} else if (RETURN_CART == 'P'){
  $products = $cart->get_products();
  $products = array_reverse($products);
  if ($products == '') {
    $back = sizeof($navigation->path)-2;
      if (isset($navigation->path[$back])) {
        $nav_link = '<a href="' . tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']) . '">' . tep_template_image_button('button_continue_shopping.gif', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>';
      }
  }else{
    $nav_link = '<div class="margin-top large-margin-bottom pull-left"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[0]['id']) . '" class="btn btn-primary" type="button">' .  IMAGE_BUTTON_CONTINUE_SHOPPING . '</a></div>';
  }
}
?>

                <?php echo $nav_link; ?>

                <?php
                if($valid_to_checkout == true){
                echo '<div class="margin-top large-margin-bottom pull-right"><a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="btn btn-lg btn-success " type="button">' .  IMAGE_BUTTON_CHECKOUT . '</a></div>';
                }
                ;?>
               <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>



          <?php
          //RCI start
          echo $cre_RCI->get('shoppingcart', 'insideformbelowbuttons');
          //RCI end
          ?>
        </form>


  <?php
  //RCI start
  echo $cre_RCI->get('shoppingcart', 'logic');
  //RCI end

  // WebMakers.com Added: Shipping Estimator
  if ((SHIPPING_SKIP == 'No' || SHIPPING_SKIP == 'If Weight = 0') && $cart->weight > 0) {
    if (SHOW_SHIPPING_ESTIMATOR == 'true') {
      // always show shipping table
      ?>

        <?php
         if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_SHIPPING_ESTIMATOR)) {
            require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_SHIPPING_ESTIMATOR);
        } else {
           // require(DIR_WS_MODULES . FILENAME_SHIPPING_ESTIMATOR);
        }
         ?>

      <?php
    }
  }
} else {
  ?>

    <?php

    if (isset($_GET['hide_add_to_cart_error']) &&     (int)$_GET['hide_add_to_cart_error'] == 1) {
      echo TEXT_HIDE_ADD_TO_CART_ERROR;
    } else {
      echo '<div class="well large-margin-top">' .TEXT_CART_EMPTY .'</div>';
    }
  ?>


  <?php
  if (MAIN_TABLE_BORDER == 'yes'){
    table_image_border_bottom();
  }
  ?>
      <div class="btn-set clearfix">
        <?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '"><button class="pull-right btn btn-lg btn-primary" type="submit">' .IMAGE_BUTTON_CONTINUE  . '</button></a>'; ?>
      </div>


<?php
  }
?>
    </div>
   </div>
<?php
// RCI code start
echo $cre_RCI->get('shoppingcart', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>