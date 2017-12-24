<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('productreviews', 'top');
// RCI code eof
?>
<div class="row">
   <div class="col-sm-12 col-lg-12">
            <div class="col-sm-8 col-lg-8 headertext no-padding-left no-padding-right"><h3><?php echo $products_name; ?></h3></div>
            <div class="col-sm-4 col-lg-4 text-right headertext no-padding-left no-padding-right"><h3><?php echo $products_price; ?></h3></div>
			<div class="clearfix"></div>
			<?php
			  $reviews_query_raw = "select r.reviews_id, left(rd.reviews_text, 100) as reviews_text, r.reviews_rating, r.date_added, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '" . (int)$product_info['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' order by r.reviews_id desc";
			  $reviews_split = new splitPageResults_rspv($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS);

			  if ($reviews_split->number_of_rows > 0) {
				if ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3')) {
			?>
				  <div class="product-listing-module-pagination margin-bottom">
					<div class="pull-left large-margin-bottom page-results"><?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></div>
					<div class="pull-right large-margin-bottom no-margin-top">
					  <ul class="pagination no-margin-top no-margin-bottom">
					   <?php echo  $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?>
					  </ul>

					</div>
				  </div>
			<div class="clearfix"></div>
			<?php
				}
				$reviews_query = tep_db_query($reviews_split->sql_query);
				while ($reviews = tep_db_fetch_array($reviews_query)) {
			?>
            <div class="col-sm-5 col-lg-5"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $product_info['products_id'] . '&amp;reviews_id=' . $reviews['reviews_id']) . '"><u><b>' . sprintf(TEXT_REVIEW_BY, tep_output_string_protected($reviews['customers_name'])) . '</b></u></a>'; ?></div>
            <div class="col-sm-7 col-lg-7 text-right"><?php echo sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($reviews['date_added'])); ?></div>
			<div class="clearfix"></div>
			<div class="col-sm-8 col-lg-8"><?php echo tep_break_string(tep_output_string_protected($reviews['reviews_text']), 60, '-<br>') . ((strlen($reviews['reviews_text']) >= 100) ? '..' : '') . '<br><br><i>' . sprintf(TEXT_REVIEW_RATING, tep_image(DIR_WS_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])), sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])) . '</i>'; ?></div>
			<div class="col-sm-4 col-lg-4 text-center">
					<?php
					  if (tep_not_null($product_info['products_image'])) {
							echo '<a data-toggle="modal" href="#popup-image-modal" class="">' . tep_image(DIR_WS_IMAGES .  $product_info['products_image'], $product_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="img-responsive"') . '</a><br><p class="text-center no-margin-top no-margin-bottom"><a data-toggle="modal" href="#popup-image-modal" class="btn normal">Click To Enlarge</a></p>    <div class="modal fade" id="popup-image-modal">
									  <div class="modal-dialog">
										<div class="modal-content">
										  <div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title">'.$product_info['products_name'] .'</h4>
										  </div>
										  <div class="modal-body pop_im">'.tep_image(DIR_WS_IMAGES .  $product_info['products_image'], $product_info['products_name'], LARGE_IMAGE_WIDTH, LARGE_IMAGE_HEIGHT, 'class="img-responsive" style="border:1px solid red"').'
										  </div>
										  <div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">close</button>
										  </div>
										</div>
									  </div>
									</div>
								';

						  }

						?>
					</div>
					<?php } ?>
					<?php
					  if (($reviews_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
					?>
					  <div class="content-product-listing-div">
						  <div class="product-listing-module-pagination margin-bottom">
							<div class="pull-left large-margin-bottom page-results"><?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></div>
							<div class="pull-right large-margin-bottom no-margin-top">
							  <ul class="pagination no-margin-top no-margin-bottom">
							   <?php echo  $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?>
							  </ul>

							</div>
						  </div>
						 </div>

					<?php
					  } //end bottom pagination
					  } else {
					echo '<div class="col-sm-12 col-lg-12"><div class="well"><h4>' . TEXT_NO_REVIEWS .'</h4></div></div>';

					// RCI code start
					echo $cre_RCI->get('productreviews', 'menu');
					  }
					?>
					<div class="clearfix"></div>
					<div class="col-xs-6 col-sm-3 col-lg-3 small-margin-top small-margin-bottom"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params()) . '" class="btn btn-sm cursor-pointer small-margin-right btn-success" >' . IMAGE_BUTTON_BACK . '</a>'; ?></div>

				    <div class="col-xs-6 col-sm-3 col-lg-3 small-margin-top small-margin-bottom"><a href="<?php echo tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action','cPath','products_id')) . 'action=buy_now&products_id=' . $product_info['products_id'] . '&cPath=' . tep_get_product_path($product_info['products_id']));?>"><?php echo tep_template_image_button('button_in_cart.gif', IMAGE_BUTTON_IN_CART)?></a></div>
					<div class="col-xs-6 col-sm-3 col-lg-3 small-margin-top small-margin-bottom"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params()) . '" class="btn btn-lg cursor-pointer small-margin-right btn-success">' . IMAGE_BUTTON_WRITE_REVIEW . '</a>'; ?></div>
					<div class="clearfix"></div>

					<div class="clearfix"></div>
 </div>
</div>
<?php
// RCI code start
echo $cre_RCI->get('productreviews', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>