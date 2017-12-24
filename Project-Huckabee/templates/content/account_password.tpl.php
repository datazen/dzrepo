  <?php
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('accountpassword', 'top');
  // RCI code eof
  echo tep_draw_form('account_password', tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'), 'post', 'onSubmit="return check_form(account_password);"') . tep_draw_hidden_field('action', 'process'); ?>
  <div class="row">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
  <div class="col-sm-12 col-lg-12">
           <h3 class="no-margin-top"><?php echo HEADING_TITLE; ?></h3>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>

<?php
  if ($messageStack->size('account_password') > 0) {
?>

        <div><?php echo $messageStack->output('account_password'); ?></div>
        <div><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></div>

<?php
  }
?>

    <div class="well">
        <div class=""><label class="sr-only"></label><?php echo tep_draw_password_field('password_current', '', 'placeholder="' . ENTRY_PASSWORD_CURRENT . '" class="form-control"'); ?></div>
        <div class="margin-top"><label class="sr-only"></label><?php echo tep_draw_password_field('password_new','', 'placeholder="' . ENTRY_PASSWORD_NEW . '" class="form-control"'); ?></div>
        <div class="margin-top"><label class="sr-only"></label><?php echo tep_draw_password_field('password_confirmation','', 'placeholder="' . ENTRY_PASSWORD_CONFIRMATION . '" class="form-control"'); ?></div>

    </div>

    <div class="btn-set small-margin-top clearfix">
      <button class="pull-right btn btn-lg btn-primary" type="submit"><?php echo IMAGE_BUTTON_CONTINUE; ?></button>
      <a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL') ?>"><button class="pull-left btn btn-lg btn-default" type="button"><?php echo IMAGE_BUTTON_BACK; ?></button></a>

   </div>

      <?php
      // RCI code start
      echo $cre_RCI->get('accountpassword', 'menu');
      // RCI code eof
      // BOF: Lango Added for template MOD
      if (MAIN_TABLE_BORDER == 'yes'){
        table_image_border_bottom();
      }
      // EOF: Lango Added for template MOD
      ?>
   </div>
  </div></form>
    <?php
    // RCI code start
    echo $cre_RCI->get('accountpassword', 'bottom');
    echo $cre_RCI->get('global', 'bottom');
    // RCI code eof
    ?>