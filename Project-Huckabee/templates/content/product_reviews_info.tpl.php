<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('productreviewsinfo', 'top');
// RCI code eof
echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product')); ?>
<div class="row">
   <div class="col-sm-12 col-lg-12">
            <div class="col-sm-10 col-lg-10 headertext"><?php echo $products_name; ?></div>
            <div class="col-sm-2 col-lg-2 text-right headertext"><?php echo $products_price; ?></div>
			<div class="clearfix"></div>
            <div class="col-sm-5 col-lg-5"><?php echo '<b>' . sprintf(TEXT_REVIEW_BY, tep_output_string_protected($reviews_info['customers_name'])) . '</b>'; ?></div>
            <div class="col-sm-7 col-lg-7 text-right"><?php echo sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($reviews_info['date_added'])); ?></div>
			<div class="clearfix"></div>
			<div class="col-sm-8 col-lg-8"><?php echo tep_break_string(nl2br(tep_output_string_protected($reviews_info['reviews_text'])), 60, '-<br>') . '<br><br><i>' . sprintf(TEXT_REVIEW_RATING, tep_image(DIR_WS_IMAGES . 'stars_' . $reviews_info['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews_info['reviews_rating'])), sprintf(TEXT_OF_5_STARS, $reviews_info['reviews_rating'])) . '</i>'; ?></div>
			<div class="col-sm-4 col-lg-4 text-center">
			                <?php
			                 /* if (tep_not_null($reviews_info['products_image'])) {
			                  ?>
			                    <script language="javascript"><!--
			                      document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $reviews_info['products_id']) . '\\\')">' . tep_image(DIR_WS_IMAGES . $reviews_info['products_image'], addslashes($reviews_info['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>');
			                    //--></script>
			                    <noscript>
			                      <?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $reviews_info['products_image']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . $reviews_info['products_image'], $reviews_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>
			                    </noscript>
			                  <?php
			                  }*/
							echo '<a data-toggle="modal" href="#popup-image-modal" class="">' . tep_image(DIR_WS_IMAGES . $reviews_info['products_image'], addslashes($reviews_info['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="img-responsive"') . '</a><br><p class="text-center no-margin-top no-margin-bottom"><a data-toggle="modal" href="#popup-image-modal" class="btn normal">Click To Enlarge</a></p>    <div class="modal fade" id="popup-image-modal">
									  <div class="modal-dialog">
										<div class="modal-content">
										  <div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title">'.$product_info['products_name'] .'</h4>
										  </div>
										  <div class="modal-body pop_im">'.tep_image(DIR_WS_IMAGES . $reviews_info['products_image'], addslashes($reviews_info['products_name']), LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'class="img-responsive" style="border:1px solid red"').'
										  </div>
										  <div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">close</button>
										  </div>
										</div>
									  </div>
									</div>
								';

			                  echo '<p class="text-center"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action','cPath','products_id')) . 'action=buy_now&products_id=' . $reviews_info['products_id'] . '&cPath=' . tep_get_product_path($reviews_info['products_id'])) . '">' . tep_template_image_button('button_in_cart.gif', IMAGE_BUTTON_IN_CART) . '</a>';?>
			</div>
<div class="clearfix"></div>
<div class="col-xs-4 col-sm-4 col-lg-4">
<?php echo '<a href="javascript:history.go(-1)" class="btn btn-sm cursor-pointer small-margin-right btn-success">' . IMAGE_BUTTON_BACK . '</a>'; ?>

</div>
<div class="col-xs-4  col-sm-4 col-lg-4">
<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params(array('reviews_id'))) . '" class="btn btn-sm cursor-pointer small-margin-right btn-success">' . IMAGE_BUTTON_WRITE_REVIEW . '</a>'; ?>

</div>
<div class="col-xs-4  col-sm-4 col-lg-4">
                  <?php
                  // Begin Wishlist Code
                  if (DESIGN_BUTTON_WISHLIST == 'true') {
                    echo '<p><a href="javascript:document.cart_quantity.action=\'' . tep_href_link(basename(FILENAME_PRODUCT_INFO), 'action=add_wishlist') . '\'; document.cart_quantity.submit();" class="btn btn-sm cursor-pointer small-margin-right btn-success">' .  IMAGE_BUTTON_ADD_WISHLIST  . '</a>' ;
                  }
                  // End Wishlist Code
                  echo  '</form></p>';
                ?>

</div>
<div class="clearfix"></div>

<?php
// RCI code start
echo $cre_RCI->get('productreviewsinfo', 'menu');
// RCI code eof
?>
    </div>
   </div>
<?php
// RCI code start
echo $cre_RCI->get('productreviewsinfo', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>