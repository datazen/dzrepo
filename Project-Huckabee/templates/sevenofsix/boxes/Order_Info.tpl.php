<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('orderinfo', 'top');
// RCI code eof
?>
<form name="account_edit" method="post" <?php echo 'action="' .
tep_href_link('Order_Info_Process.php', '', 'SSL')
. '"'; ?> onSubmit="return check_form(this);"><input type="hidden" name="action" value="process">
<div class="row">
  <div class="col-sm-12 col-lg-12">

  <?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
          <h1 class="no-margin-top"><?php echo HEADING_TITLE_CHECKOUT; ?></h1>
  <?php
// BOF: Lango Added for template MOD
}else{
$header_text = '<h1 class="no-margin-top">' .HEADING_TITLE .'</h1>';
}
// EOF: Lango Added for template MOD
?>

        <?php
// BOF: Lango Added for template MOD
// EOF: Lango Added for template MOD
?>
<?php
  if (sizeof($navigation->snapshot) > 0) {
?>
<div class="row"><div class="col-sm-12 col-lg-12"><div class="well"><?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?></div></div></div>

<?php
  }
?>
<div class="clear-both"></div>

<?php

  $email_address = tep_db_prepare_input(isset($_GET['email_address']));
  $account['entry_country_id'] = STORE_COUNTRY;

//  require(DIR_WS_MODULES . 'Order_Info_Check.php');
//  require(DIR_WS_MODULES . FILENAME_ORDER_INFO_CHECK);
         if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_ORDER_INFO_CHECK)) {
            require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_ORDER_INFO_CHECK);
        } else {
            echo (DIR_WS_MODULES . FILENAME_ORDER_INFO_CHECK);
        }
?>

        <?php
      // RCI code start
      echo $cre_RCI->get('orderinfo', 'menu');
      // RCI code eof
      // BOF: Lango Added for template MOD

      // EOF: Lango Added for template MOD
      ?>

                <?php/* echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE);*/ ?>
     <div class="btn-set small-margin-top clearfix">
      <input type="submit" value="Continue" class="pull-right btn btn-lg btn-primary">

      </div>

  </div>
</div>
</form>
<?php
// RCI code start
echo $cre_RCI->get('orderinfo', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>