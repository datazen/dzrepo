<?php
/*
  $Id: product_info.tpl.php,v 1.2.0.0 2008/01/22 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('productinfo', 'top');
// RCI code eof
echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action', 'products_id', 'id')) . 'action=add_product' . '&' . $params), 'post', 'enctype="multipart/form-data"'); ?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
<?php
  if ($messageStack->size('cart_quantity') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('cart_quantity'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }

  // added for CDS CDpath support
  $params = (isset($_SESSION['CDpath'])) ? 'CDpath=' . $_SESSION['CDpath'] : ''; 
  if ($product_check['total'] < 1) {
    ?>
    <tr>
      <td><?php  new infoBox(array(array('text' => TEXT_PRODUCT_NOT_FOUND))); ?></td>
    </tr>
    <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
    <tr>
      <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, $params) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
    <?php
  } else {
    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, p.products_image_med, p.products_image_lrg, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);
    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$product_info['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
    /*if (tep_not_null($product_info['products_model'])) {
      $products_name = $product_info['products_name'] . '&nbsp;<span class="smallText">[' . $product_info['products_model'] . ']</span>';
    } else {
      $products_name = $product_info['products_name'];
    }*/
    $products_name = $product_info['products_name'];
    $pf->loadProduct($product_info['products_id'],$languages_id);
      $products_price = $pf->getPriceStringShort();

    if (SHOW_HEADING_TITLE_ORIGINAL=='yes') {
      $header_text = '';
      ?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><h1 class="pageHeading"><?php echo $products_name; ?></h1></td>
            <td class="pageHeading" align="right">&nbsp;<?php //echo $products_price; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <?php
    } else {
      $header_text =  $products_name .'</td><td class="productlisting-headingPrice">' . $products_price;
    }
    // RCI code start
    echo $cre_RCI->get('productinfo', 'underpriceheading');
    // RCI code eof
    ?>
    <tr>
      <td class="main" valign="top">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
            <?php
              if (tep_not_null($product_info['products_image']) || tep_not_null($product_info['products_image_med'])) {
                // BOF MaxiDVD: Modified For Ultimate Images Pack!
                if ($product_info['products_image_med']!='') {
                    $new_image = $product_info['products_image_med'];
                    $image_width = MEDIUM_IMAGE_WIDTH;
                    $image_height = MEDIUM_IMAGE_HEIGHT;
                 } else {
                    $new_image = $product_info['products_image'];
                    $image_width = SMALL_IMAGE_WIDTH;
                    $image_height = SMALL_IMAGE_HEIGHT;
                 }
                 echo '<center><a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image_lrg']) . '" rel="prettyPhoto[Product]" title="' .$product_info['products_name']. '">' . tep_image(DIR_WS_IMAGES . $new_image, $product_info['products_name'], $image_width, $image_height, 'hspace="5" vspace="5"') . '</a><br><span class="smallText">' . TEXT_CLICK_TO_ENLARGE . '</span></center>';    
                 // EOF MaxiDVD: Modified For Ultimate Images Pack!
                 if (isset($_SESSION['affiliate_id'])) {
                   echo '<br><br><a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD, 'individual_banner_id=' . $product_info['products_id'] . '&' . $params) .'" target="_self">' . tep_template_image_button('button_affiliate_build_a_link.gif', LINK_ALT) . ' </a>';
                 }
               }  // end if image
             ?>
            </td>
       <td valign="top">
        <table border="0" cellspacing="4" cellpadding="0" width="100%" align="center">
          <tr>
            <td class="main"><b><?php echo TEXT_PRICE;?></b></td>
            <td class="pageHeading"><?php echo $products_price; ?></td>
          </tr>
          <?php  if (tep_not_null($product_info['products_model'])) { ?>
          <tr>
            <td class="main"><b><?php echo TEXT_MODEL;?></b></td>
            <td class="main"><b><?php echo $product_info['products_model']; ?></b></td>
          </tr>
          <?php }
          if ($product_info['manufacturers_id'] != 0) { ?>
          <tr>
            <td class="main"><b><?php echo TEXT_MANUFACTURER;?></b></td>
            <td class="main"><b><?php echo tep_get_manufacturers_name($product_info['manufacturers_id']); ?></b></td>
          </tr>
         <?php } ?>
          <tr>
            <td class="main"><?php echo TEXT_ENTER_QUANTITY . ':&nbsp;&nbsp;' . tep_draw_input_field('cart_quantity', '1', 'size="4" maxlength="4" id="Qty1" onkeyup="document.getElementById(\'Qty2\').value = document.getElementById(\'Qty1\').value;"  ');?></td>
            <td class="main"><?php
            $valid_to_checkout= true;
            if (STOCK_CHECK == 'true') {
              $stock_check = tep_check_stock((int)$_GET['products_id'], 1);
              if (tep_not_null($stock_check) && (STOCK_ALLOW_CHECKOUT == 'false')) {
                $valid_to_checkout = false;
              }
            }
            echo tep_draw_hidden_field('products_id', $product_info['products_id']) ;
            if ($valid_to_checkout == true) {
              echo tep_template_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART, 'align="middle"'); 
            }
            ?>
            </td>
          </tr>
          <tr>
            <td>
              <!-- AddThis Button BEGIN -->
              <div class="addthis_toolbox addthis_default_style ">
              <a class="addthis_button_preferred_1"></a>
              <a class="addthis_button_preferred_2"></a>
              <a class="addthis_button_preferred_3"></a>
              <a class="addthis_button_preferred_4"></a>
              <a class="addthis_button_compact"></a>
              <a class="addthis_counter addthis_bubble_style"></a>
              </div>
              <script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
              <script type="text/javascript" src="https://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4d9b61612caae177"></script>
              <!-- AddThis Button END -->
            </td>
            <td>
            <?php
              if ($product_check['total'] > 0) {
                if (DESIGN_BUTTON_WISHLIST == 'true') {
                  echo '<script type="text/javascript"><!--' . "\n";
                  echo 'function addwishlist() {' . "\n";
                  echo 'document.cart_quantity.action=\'' . str_replace('&amp;', '&', tep_href_link(FILENAME_PRODUCT_INFO, 'action=add_wishlist' . '&' . $params)) . '\';' . "\n";
                  echo 'document.cart_quantity.submit();' . "\n";
                  echo '}' . "\n";
                  echo '--></script>' . "\n";
                  echo '<a href="javascript:addwishlist()">' . tep_template_image_button('button_add_wishlist.gif', IMAGE_BUTTON_ADD_WISHLIST,'align="middle"') . '</a>';
                }
              } // if products_check
            ?>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params() . $params) . '">' . tep_template_image_button('button_reviews.gif', IMAGE_BUTTON_REVIEWS,'align="middle"') . '</a>'; ?></td>
          </tr>
         </table>
       </td>
     </tr>
   </table>
 </td>
</tr>
<?php
      if (MAIN_TABLE_BORDER == 'yes'){
      table_image_border_top(false, false, $header_text);
    }
    ?>
    <tr>
      <td class="main"><table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="main" valign="top">
          <?php 
            echo '<p>' .  cre_clean_product_description($product_info['products_description']) . '</p>';
            echo tep_draw_separator('pixel_trans.gif', '100%', '10');
            $attributes = new creAttributes();
            if ($attributes->load($product_info['products_id'])) {
              $options_HTML = $attributes->get_HTML();
              if (count($options_HTML) > 0) {
           ?>
          </td>
         </tr>
          <tr>
            <td>
              <table width="100%" border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td class="main" colspan="2"><strong><?php echo TEXT_PRODUCT_OPTIONS; ?></strong></td>
                </tr>
<?php
                foreach ($options_HTML as $op_data) {
                ?>
                <tr>
                  <td class="main"><?php echo $op_data['label']; ?></td>
                  <td class="main"><?php echo $op_data['HTML'];  ?></td>
                </tr>
<?php
                } //end of foreach
                ?>
              </table>
            </td>
          </tr>
<?php
              }  // end of count
            } // end of new attributes
            ?>
          </td>
        </tr>
      </table></td>
    </tr>
    <?php
    if (ULTIMATE_ADDITIONAL_IMAGES == 'enable') { 
         if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . 'additional_images.php')) {
            require(TEMPLATE_FS_CUSTOM_MODULES . 'additional_images.php');
        } else {
            require(DIR_WS_MODULES . 'additional_images.php');
        }
    }
    $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . (int)$product_info['products_id'] . "'");
    $reviews = tep_db_fetch_array($reviews_query);
    if ($reviews['count'] > 0) {
      ?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo TEXT_CURRENT_REVIEWS . ' ' . $reviews['count']; ?></td>
      </tr>
      <?php
    }
    if (tep_not_null($product_info['products_url'])) {
      ?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo sprintf(TEXT_MORE_INFORMATION, tep_href_link(FILENAME_REDIRECT, 'action=url&amp;goto=' . urlencode($product_info['products_url']), 'NONSSL', true, false)); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <?php
    }
    if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
      ?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_DATE_AVAILABLE, tep_date_long($product_info['products_date_available'])); ?></td>
      </tr>
      <?php
    } else {
      ?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_DATE_ADDED, tep_date_long($product_info['products_date_added'])); ?></td>
      </tr>
      <?php
    }
    if (MAIN_TABLE_BORDER == 'yes'){
      table_image_border_bottom();
    }
    // sub product start
    if (STOCK_ALLOW_CHECKOUT =='false') {
      $allowcriteria = "";
    }
    if ( (USE_CACHE == 'true') && !SID) {
      echo tep_cache_also_purchased(3600);
//      include(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS);
         if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_XSELL_PRODUCTS)) {
            require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_XSELL_PRODUCTS);
        } else {
            require(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS);
        }
    } else {
      //include(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS_BUYNOW);
         if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_XSELL_PRODUCTS_BUYNOW)) {
            require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_XSELL_PRODUCTS_BUYNOW);
        } else {
            require(DIR_WS_MODULES . FILENAME_XSELL_PRODUCTS_BUYNOW);
        }
        
      //include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
         if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS)) {
            require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
        } else {
            require(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
        }
    }
  }
  // product info page bottom
  echo $cre_RCI->get('productinfo', 'bottom');
  ?>
</table>
</form>
<?php
// RCI code start
echo $cre_RCI->get('global', 'bottom');
?>