<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('productreviewswrite', 'top');
// RCI code eof
echo tep_draw_form('product_reviews_write', tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'action=process&amp;products_id=' . $product_info['products_id']), 'post', 'onSubmit="return checkForm();"'); ?>
<div class="row">
<div class="col-sm-12 col-lg-12 no-padding-left no-padding-right">
            <div class="col-sm-8 col-lg-8 headertext no-padding-left no-padding-right"><h3><?php echo $products_name; ?></h3></div>
            <div class="col-sm-4 col-lg-4 text-right headertext no-padding-left no-padding-right"><h3><?php echo $products_price; ?></h3></div>
			<div class="clearfix"></div>
			<?php
			  if ($messageStack->size('review') > 0) {
			?>
			<div class="col-sm-12 col-lg-12">
			  <div class="message-stack-container alert alert-danger"><?php echo $messageStack->output('review'); ?></div>
			</div>
			<div class="clearfix"></div>
			<?php
			  }
			?>
			<div class="col-sm-8 col-lg-8 no-padding-left no-padding-right">
			<?php echo '<b>' . SUB_TITLE_FROM . '</b> ' . tep_output_string_protected($customer['customers_firstname'] . ' ' . $customer['customers_lastname']); ?>
			<br><b><?php echo SUB_TITLE_REVIEW; ?></b>
			<?php echo tep_draw_textarea_field('review_text', 'soft', 60, 15,'','class="form-control"'); ?>
			<br><?php echo TEXT_NO_HTML; ?>
			<br><?php echo '<b>' . SUB_TITLE_RATING . '</b> ' . TEXT_BAD . ' ' . tep_draw_radio_field('rating', '1') . ' ' . tep_draw_radio_field('rating', '2') . ' ' . tep_draw_radio_field('rating', '3') . ' ' . tep_draw_radio_field('rating', '4') . ' ' . tep_draw_radio_field('rating', '5') . ' ' . TEXT_GOOD; ?>
			</div>
			<div class="col-sm-4 col-lg-4 no-padding-left no-padding-right text-center">

			<?php
			  if (tep_not_null($product_info['products_image'])) {?>
               <a rel="<?php echo $product_info['products_name'] ;?>" href="<?php echo DIR_WS_IMAGES . $product_info['products_image'] ;?>" class="thumbnail fancybox" style="border:none;"><?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image'], $product_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="img-responsive"');?></a>

								<?php

			}

			  echo '<p><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action','cPath','products_id')) . 'action=buy_now&products_id=' . $product_info['products_id'] . '&cPath=' . tep_get_product_path($product_info['products_id'])) . '">' . tep_template_image_button('button_in_cart.gif', IMAGE_BUTTON_IN_CART) . '</a></p>';
			?>
			</div>
			<div class="clearfix"></div>

					<!-- VISUAL VERIFY CODE start -->
		<div class="col-sm-12 col-lg-12 no-padding-left no-padding-right">

						<?php
						if (defined('VVC_SITE_ON_OFF') && VVC_SITE_ON_OFF == 'On') {
							if (defined('VVC_LINK_SUBMITT_ON_OFF') && VVC_LINK_SUBMITT_ON_OFF == 'On'){
						?>
						<h3><?php echo VISUAL_VERIFY_CODE_CATEGORY; ?></h3>
						<?php echo VISUAL_VERIFY_CODE_TEXT_INSTRUCTIONS; ?>
		    			<div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo tep_draw_input_field('visual_verify_code', '' , 'class="form-control" style="width:70%" placeholder="' . VISUAL_VERIFY_CODE_BOX_IDENTIFIER . '"'); ?></div>

						  <?php
							  //can replace the following loop with $visual_verify_code = substr(str_shuffle (VISUAL_VERIFY_CODE_CHARACTER_POOL), 0, rand(3,6)); if you have PHP 4.3
							$visual_verify_code = "";
							for ($i = 1; $i <= rand(3,6); $i++){
								  $visual_verify_code = $visual_verify_code . substr(VISUAL_VERIFY_CODE_CHARACTER_POOL, rand(0, strlen(VISUAL_VERIFY_CODE_CHARACTER_POOL)-1), 1);
							 }
							 $vvcode_oscsid = tep_session_id();
							 tep_db_query("DELETE FROM " . TABLE_VISUAL_VERIFY_CODE . " WHERE oscsid='" . $vvcode_oscsid . "'");
							 $sql_data_array = array('oscsid' => $vvcode_oscsid, 'code' => $visual_verify_code);
							 tep_db_perform(TABLE_VISUAL_VERIFY_CODE, $sql_data_array);
							 $visual_verify_code = "";
							 echo('<img class="img-responsive" src="' . FILENAME_VISUAL_VERIFY_CODE_DISPLAY . '?vvc=' . $vvcode_oscsid . '" alt="' . VISUAL_VERIFY_CODE_CATEGORY . '">');
						  ;?>
						<!-- VISUAL VERIFY CODE end -->
						<?php } } ?>
  </div>

		<div class="clearfix"></div>
		<?php
		// RCI code start
		echo $cre_RCI->get('productreviewswrite', 'menu');
		// RCI code eof
		// EOF: Lango Added for template MOD
		?>
		<div class="col-xs-6 col-sm-6 col-lg-6 small-padding-bottom large-padding-top">
			<?php echo '<a class="btn btn-sm cursor-pointer small-margin-right btn-success" href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id', 'action'))) . '">' .  IMAGE_BUTTON_BACK . '</a>'; ?>
		</div>
		<div class="col-xs-6 col-sm-6 col-lg-6 small-padding-bottom large-padding-top">
		<input type="submit" value="Continue" class="btn btn-lg cursor-pointer small-margin-right btn-success">
		</div>
		<div class="clearfix"></div>
  </div>
 </div>
</form>
<?php
// RCI code start
echo $cre_RCI->get('productreviewswrite', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>