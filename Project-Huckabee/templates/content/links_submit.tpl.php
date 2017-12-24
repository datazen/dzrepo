<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('linkssubmit', 'top');
// RCI code eof
echo tep_draw_form('submit_link', tep_href_link(FILENAME_LINKS_SUBMIT, '', 'SSL'), 'post', 'onSubmit="return check_form(submit_link);"') . tep_draw_hidden_field('action', 'process'); ?>
<div class="row">
  <div class="col-sm-12 col-lg-12">

<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>



           <h1 class="no-margin-top"> <?php echo HEADING_TITLE; ?></h1>




<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>

        <?php echo TEXT_MAIN; ?>


<?php
  if ($messageStack->size('submit_link') > 0) {
?>
      <div class="message-stack-container alert alert-danger small-margin-bottom small-margin-left">

        <?php echo $messageStack->output('submit_link'); ?>
      </div>
<?php
  }
?>
          <div class="row">
            <div class="col-sm-6 col-lg-6 large-padding-left margin-top">
	          <div class="well no-padding-top">
	          <h4><?php echo CATEGORY_WEBSITE; ?></h4>
	             <?php echo ENTRY_LINKS_TITLE; ?>
				<div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo tep_draw_input_field('links_title', '' , 'class="form-control" placeholder="' . ENTRY_LINKS_TITLE . '"'); ?></div>
				<?php echo ENTRY_LINKS_URL; ?>
				<div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo tep_draw_input_field('links_url', 'http://', '' , 'class="form-control" placeholder="' . ENTRY_LINKS_URL . '"'); ?></div>

<?php
  //link category drop-down list
  $categories_array = array();
  $categories_query = tep_db_query("select lcd.link_categories_id, lcd.link_categories_name from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " lcd where lcd.language_id = '" . (int)$languages_id . "'order by lcd.link_categories_name");
  while ($categories_values = tep_db_fetch_array($categories_query)) {
    $categories_array[] = array('id' => $categories_values['link_categories_name'], 'text' => $categories_values['link_categories_name']);
  }

  $default_category = '';

  if (isset($_GET['lPath'])) {
    $current_categories_id = $_GET['lPath'];

    $current_categories_query = tep_db_query("select link_categories_name from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where link_categories_id ='" . (int)$current_categories_id . "' and language_id ='" . (int)$languages_id . "'");
    if ($categories = tep_db_fetch_array($current_categories_query)) {
      $default_category = $categories['link_categories_name'];
   }
  }
?>
              <?php echo ENTRY_LINKS_CATEGORY; ?>
              <div class="form-group full-width margin-bottom"><label class="sr-only"></label><span class="address-book-state-container"><?php echo tep_draw_pull_down_menu('links_category', $categories_array, $default_category,'class="box-manufacturers-select form-control form-input-width"');?></span></div>
			  <?php echo ENTRY_LINKS_DESCRIPTION; ?>
			  <div class="form-group"><label class="sr-only"></label><textarea class="form-control" name="links_description" rows="5" cols="25" placeholder="<?php echo ENTRY_LINKS_DESCRIPTION; ?>"></textarea></div>
	   	      <?php echo ENTRY_LINKS_DESCRIPTION; ?><?php echo ENTRY_LINKS_IMAGE; ?>
			  <div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo tep_draw_input_field('links_image', 'http://', '' , 'class="form-control" placeholder="' . ENTRY_LINKS_IMAGE . '"'); ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_LINKS_HELP) . '\')">' . TEXT_LINKS_HELP_LINK . '</a>'; ?></div>

			  </div>
			 </div>
	               <div class="col-sm-6 col-lg-6 large-padding-left margin-top"> <!--start-->
						<div class="well no-padding-top">
						<h4><?php echo CATEGORY_CONTACT; ?></h4>
						<?php echo ENTRY_LINKS_CONTACT_NAME; ?>
						<div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo tep_draw_input_field('links_contact_name', '' , 'class="form-control" placeholder="' . ENTRY_LINKS_CONTACT_NAME . '"'); ?></div>
						<?php echo ENTRY_EMAIL_ADDRESS; ?>
						<div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo tep_draw_input_field('links_contact_email', '' , 'class="form-control" placeholder="' . ENTRY_EMAIL_ADDRESS . '"'); ?></div>
						</div>
						<div class="well no-padding-top">
						<h4><?php echo CATEGORY_RECIPROCAL; ?></h4>
						<?php echo ENTRY_LINKS_RECIPROCAL_URL; ?>
		    			<div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo tep_draw_input_field('links_reciprocal_url', 'http://', '' , 'class="form-control" placeholder="' . ENTRY_LINKS_RECIPROCAL_URL . '"'); ?><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_LINKS_HELP) . '\')">' . TEXT_LINKS_HELP_LINK . '</a>'; ?></div>
						</div>
          	          <div class="well no-padding-top">
						<!-- VISUAL VERIFY CODE start -->
						<?php
						if (defined('VVC_SITE_ON_OFF') && VVC_SITE_ON_OFF == 'On') {
							if (defined('VVC_LINK_SUBMITT_ON_OFF') && VVC_LINK_SUBMITT_ON_OFF == 'On'){
						?>
						<h3><?php echo VISUAL_VERIFY_CODE_CATEGORY; ?></h3>
						<?php echo VISUAL_VERIFY_CODE_TEXT_INSTRUCTIONS; ?>
		    			<div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo tep_draw_input_field('visual_verify_code', '' , 'class="form-control" placeholder="' . VISUAL_VERIFY_CODE_BOX_IDENTIFIER . '"'); ?></div>

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


	                 </div>

	   	           </div><!--end-->
			</div>



<?php
 }
}

      // RCI code start
      echo $cre_RCI->get('linkssubmit', 'menu');
      // RCI code eof
      ?>
    <div class="btn-set small-margin-top clearfix">
      <button class="pull-right btn btn-lg btn-primary" type="submit"><?php echo IMAGE_BUTTON_CONTINUE; ?></button>

      </div>
    </div></div></form>
<?php
// RCI code start
echo $cre_RCI->get('linkssubmit', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>