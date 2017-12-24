<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('passwordforgotten', 'top');
// RCI code eof
echo tep_draw_form('password_forgotten', tep_href_link(FILENAME_PASSWORD_FORGOTTEN, 'action=process', 'SSL')); ?>
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
// BOF: Lango Added for template MOD
}else{
$header_text = '  <h1 class="no-margin-top">' . HEADING_TITLE . '</h1>';
}
// EOF: Lango Added for template MOD


  if ($messageStack->size('password_forgotten') > 0) {
?>
  <div class="message-stack-container alert alert-danger">
    <?php echo $messageStack->output('password_forgotten'); ?>
  </div>


  <?php
  }
?>
    <div class="well">
      <p class="no-margin-bottom"><?php echo TEXT_MAIN; ?></p>
      <form role="form" name="password_forgotten">
        <div class="form-group">
          <label></label><?php echo tep_draw_input_field('email_address', null, 'placeholder="' . ENTRY_EMAIL_ADDRESS . '" class="form-control"'); ?>
        </div>
      </form>
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
						<?php } } ?>
		</div>
  <?php
// RCI code start
echo $cre_RCI->get('passwordforgotten', 'menu');
// RCI code eof
?>

	    <div class="btn-set small-margin-top clearfix">
			<?php echo '<a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '"><button class="pull-right btn btn-lg btn-primary" type="submit">' . IMAGE_BUTTON_CONTINUE . '</button></a>'; ?>
			<?php echo '<a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '"><button class="pull-left btn btn-lg btn-default" type="button">' .  IMAGE_BUTTON_BACK  . '</button></a>'; ?>

  	    </div>

 </div>
</div>
</form>
<?php
// RCI code start
echo $cre_RCI->get('passwordforgotten', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>