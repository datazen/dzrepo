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
echo $cre_RCI->get('productinfotabs', 'top');
// RCI code eof
echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action', 'products_id', 'id')) . 'action=add_product' . '&' . $params), 'post', 'enctype="multipart/form-data"');
echo tep_draw_hidden_field('products_id', $product_info['products_id']);
?>

<div class="row" id="content">
<?php
  if ($messageStack->size('cart_quantity') > 0) {
?>
<div class="message-stack-container alert alert-danger margin-bottom mid-margin-right with-padding">

<?php echo $messageStack->output('cart_quantity'); ?>
</div>


<?php
  }
  // added for CDS CDpath support
  $params = (isset($_SESSION['CDpath'])) ? 'CDpath=' . $_SESSION['CDpath'] : '';
  if ($product_check['total'] < 1) {
    ?>
<?php  new infoBox(array(array('text' => TEXT_PRODUCT_NOT_FOUND))); ?>
<?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, $params) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?>
  <?php
  } else {
    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, p.products_image_med, p.products_image_lrg, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);
    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$product_info['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
   /*if (tep_not_null($product_info['products_model'])) {
      $products_name = '<h1>' . $product_info['products_name'] . '</h1>&nbsp;<span class="smallText">[' . $product_info['products_model'] . ']</span>';
    } else {
      $products_name = '<h1 class="pageHeading">' . $product_info['products_name'] . '</h1>';
    }*/
    $products_name = '<h1 class="pageHeading">' . $product_info['products_name'] . '</h1>';
    if ($product_has_sub > '0'){ // if product has sub products
      $products_price ='';// if you like to show some thing in place of price add here
    } else {
      $pf->loadProduct($product_info['products_id'],$languages_id);
      $products_price = $pf->getPriceStringShort();
    } // end sub product check
      ?>




<?php
              if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
                echo '<div class="col-sm-12 col-lg-12 clearfix" ><span class="main alert-success">' . sprintf(TEXT_DATE_AVAILABLE . '<br>', tep_date_long($product_info['products_date_available'])) . '</span></div>';
              }
              ?>
  <?php
    // RCI code start
    echo $cre_RCI->get('productinfotabs', 'underpriceheading');
    // RCI code eof
  ?>

<div class="col-sm-12 col-lg-12 clearfix" ><h1 class="no-margin-top"><?php echo $product_info['products_name']; ?></h1></div>
<div class="clearfix"></div>
   <div class="col-sm-6 product-left">

        <div class="product-info">
           <div class="left product-image thumbnails">
		<?php
		                    if ($product_info['products_image_med']!='') {
		                      $new_image = $product_info['products_image_med'];
		                      $image_height = SMALL-IMAGE-HEIGHT;
		                    } else {
		                      $new_image = $product_info['products_image'];
		                      $image_height = SMALL-IMAGE-HEIGHT;
                    }
               ?>
   	  <!-- Megnor Cloud-Zoom Image Effect Start -->
   	  	<div class="image"><a rel="<?php echo $product_info['products_name'] ;?>" href="<?php echo DIR_WS_IMAGES . $new_image ;?>" class="thumbnail fancybox"><?php echo tep_image(DIR_WS_IMAGES . $new_image, $product_info['products_name'],  $image_height,'', 'class="img-responsive"');?></a></div>


   		 <div class="additional-carousel">
   		  		  	<div class="customNavigation">
   				<span class="fa prev fa-chevron-left">&nbsp;</span>
   				<span class="fa next fa-chevron-right">&nbsp;</span>
   			</div>
   		  <div id="additional-carousel" class="image-additional product-carousel">

					<?php
					if (ULTIMATE_ADDITIONAL_IMAGES == 'enable') {
					if ( ($product_info['products_image_sm_1'] != '') || ($product_info['products_image_xl_1'] != '') ||
					($product_info['products_image_sm_2'] != '') || ($product_info['products_image_xl_2'] != '') ||
					($product_info['products_image_sm_3'] != '') || ($product_info['products_image_xl_3'] != '') ||
					($product_info['products_image_sm_4'] != '') || ($product_info['products_image_xl_4'] != '') ||
					($product_info['products_image_sm_5'] != '') || ($product_info['products_image_xl_5'] != '') ||
					($product_info['products_image_sm_6'] != '') || ($product_info['products_image_xl_6'] != '') ) {
					?>
						<?php
					  //include(DIR_WS_MODULES . 'additional_images.php');
					 if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . 'additional_images.php')) {
						require(TEMPLATE_FS_CUSTOM_MODULES . 'additional_images.php');
					} else {
						require(DIR_WS_MODULES . 'additional_images.php');
					}
					   ?>
					<?php
					} }
					?>



       	  </div>

   		  <span class="additional_default_width" style="display:none; visibility:hidden"></span>
   		  </div>
   	<!-- Megnor Cloud-Zoom Image Effect End-->
          </div>
     </div>
    </div><!--product-left finished-->
    <div class="col-sm-6 col-lg-6 clearfix ">
                 <?php if ($product_has_sub > '0') { } else { ?>


                  <?php } if (tep_not_null($product_info['products_model'])) { ?>

                  <?php }
                  if ($product_info['manufacturers_id'] != 0) {
                  ?>

                  <b><?php /*echo TEXT_MANUFACTURER;?></b>
                    <b><?phpecho tep_get_manufacturers_name($product_info['manufacturers_id']); */?></b>

                  <?php } ?>

                  <?php
                      if ($product_has_sub > '0') {
                        echo '&nbsp;';
                      } else {
                  ?>
                    <?php /*(echo TEXT_ENTER_QUANTITY . ':&nbsp;&nbsp;' . tep_draw_input_field('cart_quantity', '1', 'size="4" maxlength="4" id="Qty1" onkeyup="document.getElementById(\'Qty2\').value = document.getElementById(\'Qty1\').value;"  ');*/?>

                  <?php
                      }
                  ?>

                    <?php
                      $valid_to_checkout= true;
                      if (STOCK_CHECK == 'true') {
                        $stock_check = tep_check_stock((int)$_GET['products_id'], 1);
                        if (tep_not_null($stock_check) && (STOCK_ALLOW_CHECKOUT == 'false')) {
                          $valid_to_checkout = false;
                        }
                      }
                      if ($hide_add_to_cart == 'false' && group_hide_show_prices() == 'true') {
                        echo tep_draw_hidden_field('products_id', $product_info['products_id']);
                        if ($valid_to_checkout == true) {

                        }
                      }
                    ?>


    <div class="social-container">
      <div class="social_wrap list-inline no-print">

			<div class="addthis_toolbox addthis_32x32_style addthis_default_style">

				<a class="addthis_button_email"></a>
				<a class="addthis_button_facebook"></a>
				<a class="addthis_button_twitter"></a>
				<a class="addthis_button_google_plusone_share"></a>
				<a class="addthis_button_linkedin"></a>
				<a class="addthis_button_addthis.com"></a>
				<a class="addthis_button_compact"></a>
			</div>
      </div>
    </div>
  <script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
  <script type="text/javascript" src="https://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4d9b61612caae177"></script>
		            <!-- sub products -->
		            <?php
		    // sub product start
		    if (STOCK_ALLOW_CHECKOUT =='false') {
		      $allowcriteria = "";
		    }
		    // get sort order
		    $csort_order = tep_db_fetch_array(tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'SUB_PRODUCTS_SORT_ORDER'"));
		    $select_order_by = '';
		    switch (strtoupper($csort_order['configuration_value'])) {
		      case 'MODEL':
		        $select_order_by .= 'sp.products_model';
		        break;
		      case 'NAME':
		        $select_order_by .= 'spd.products_name';
		        break;
		      case 'PRICE':
		        $select_order_by .= 'sp.products_price';
		        break;
		      case 'QUANTITY':
		        $select_order_by .= 'sp.products_quantity';
		        break;
		      case 'WEIGHT':
		        $select_order_by .= 'sp.products_weight';
		        break;
		      case 'SORT ORDER':
		        $select_order_by .= 'sp.sort_order';
		        break;
		      case 'LAST ADDED':
		        $select_order_by .= 'sp.products_date_added';
		        break;
		      default:
		        $select_order_by .= 'sp.products_model';
		        break;
		    }
		    ?>
		            <!-- sub products eof -->

<div class="well large-margin-top margin-bottom">
<div class="content-products-info-price-container clearfix">
	<?php
		$load_attributes_for = $product_info['products_id'];
		include(DIR_WS_MODULES . 'product_info/product_attributes.php');
	?>
</div>
	<div class="content-products-info-price-container clearfix"> <span class="content-products-info-price pull-left lt-blue"><?php echo $products_price; ?></span> </div>
      <div class="content-products-info-reviews-container large-margin-bottom">
       <label class="content-products-info-reviews-rating-label with-padding-no-top-bottom">Model:&nbsp;<?php echo $product_info['products_model']; ?></label><br/>
       <label class="content-products-info-reviews-rating-label with-padding-no-top-bottom">Manufacturers:&nbsp;<?php echo tep_get_manufacturers_name($product_info['manufacturers_id']); ?></label>
	  </div>

		<label class="content-products-info-qty-label"><?php echo TEXT_ENTER_QUANTITY,':'; ?></label>
		<?php echo  tep_draw_input_field('cart_quantity', '1', 'class="form-control" style="width:150px;" class="content-products-info-qty-input mid-margin-right" maxlength="4" id="Qty1" onkeyup="document.getElementById(\'Qty2\').value = document.getElementById(\'Qty1\').value;"  ');?>
		<i onclick="setQty('up');" style="position:relative; right:-154px; top:-37px; opacity:.3; cursor:pointer;" class="fa fa-plus-square-o fa-lg"></i>
		<i onclick="setQty('dn');" style="position:relative; right:-136px; top:-15px; opacity:.3; cursor:pointer;" class="fa fa-minus-square-o fa-lg"></i>


   </div>
    <div class="relative clear-both clearfix buy-btn-div">
  <div class="col-sm-12 col-lg-12 text-right">
	<p class="margin-top"><button id="btn-buy-now" class="btn  btn-lg btn-success"><?php echo IMAGE_BUTTON_IN_CART; ?></button></p>
  </div>

      <div class="display-inline">
<div class="col-sm-4 col-lg-4 align-right mid-margin-top"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params() . $params) . '" class="btn btn-sm cursor-pointer small-margin-right btn-success">' .  IMAGE_BUTTON_REVIEWS . '</a>'; ?></div>
        <div class="col-sm-8 col-lg-8 align-right mid-margin-top">
          <div class="form-group">
                    <?php
                      if ($product_check['total'] > 0) {
                        if (DESIGN_BUTTON_WISHLIST == 'true') {
                          echo '<script type="text/javascript"><!--' . "\n";
                          echo 'function addwishlist() {' . "\n";
                          echo 'document.cart_quantity.action=\'' . str_replace('&amp;', '&', tep_href_link(FILENAME_PRODUCT_INFO, 'action=add_wishlist' . '&' . $params)) . '\';' . "\n";
                          echo 'document.cart_quantity.submit();' . "\n";
                          echo '}' . "\n";
                          echo '--></script>' . "\n";
                          echo '<a href="javascript:addwishlist()" class="btn btn-sm cursor-pointer small-margin-right btn-success">' . IMAGE_BUTTON_ADD_WISHLIST . '</a>';
                        }
                      } // if products_check
                    ?>
          </div>
        </div>
      </div>
    </div>
    </div>
    <div class="col-sm-12 col-lg-12 no-padding-left no-padding-right large-margin-bottom"><!--newtabstart12div-->
	<div class="col-sm-12 prod-desc"><!--prod-desc Started-->
		<h3><?php echo TEXT_TAB_PRODUCT_INFO ?></h3>
		<?php
			echo '<p>' .  cre_clean_product_description($product_info['products_description']) . '</p><table>';
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
			echo '</table>';
		?>

	</div><!--prod-desc finished-->
 </div><!--newtabend12div-->

<div class="clearfix"></div>
<div class="col-sm-12 col-lg-12">
  <?php
    if (SHOW_PRICE_BREAK_TABLE == 'true') {
    ?>
    <?php
      //include_once(DIR_WS_MODULES . FILENAME_PRODUCT_QUANTITY_TABLE);
         if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_PRODUCT_QUANTITY_TABLE)) {
            require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_PRODUCT_QUANTITY_TABLE);
        } else {
            require(DIR_WS_MODULES . FILENAME_PRODUCT_QUANTITY_TABLE);
        }

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
           // require(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
        }
    }
  }
  // product info page bottom
  echo $cre_RCI->get('productinfotabs', 'bottom');
  ?>

 </div>
</div>
</form>
<?php
// RCI code start
echo $cre_RCI->get('global', 'bottom');
?>