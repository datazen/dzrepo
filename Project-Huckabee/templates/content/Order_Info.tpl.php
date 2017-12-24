<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('orderinfo', 'top');
// RCI code eof
?>
<form name="account_edit" method="post" <?php echo 'action="' .
tep_href_link('Order_Info_Process.php', '', 'SSL')
. '"'; ?> onSubmit="return check_form(this);"><input type="hidden" name="action" value="process">
<div class="row bg_pinfo">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
 <div class="col-sm-10 col-lg-10"><h1 class="no-margin-top"><?php echo HEADING_TITLE_CHECKOUT; ?></h1></div>
 <div class="col-sm-2 col-lg-2">
		<?php echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?>
 </div>
  <?php
// BOF: Lango Added for template MOD
}else{
$header_text = '<div class="col-sm-12 col-lg-12">'. HEADING_TITLE . '</div>';
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
<div class="col-sm-12 col-lg-12">
 <?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?>
</div>
 <div class="col-sm-12 col-lg-12" style="height:12px;"></div>

<?php
  }
?>
<div>
<?php

  $email_address = tep_db_prepare_input(isset($_GET['email_address']));
  $account['entry_country_id'] = STORE_COUNTRY;

//  require(DIR_WS_MODULES . 'Order_Info_Check.php');
//  require(DIR_WS_MODULES . FILENAME_ORDER_INFO_CHECK);
         if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_ORDER_INFO_CHECK)) {
            require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_ORDER_INFO_CHECK);
        } else {
            require(DIR_WS_MODULES . FILENAME_ORDER_INFO_CHECK);
        }
?>
</div>


        <?php
      // RCI code start
      echo $cre_RCI->get('orderinfo', 'menu');
      // RCI code eof
      // BOF: Lango Added for template MOD
      // EOF: Lango Added for template MOD
      ?>




<div class="col-sm-12 col-lg-12" style="text-align:right;">
  <?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>
</div>
</div></form>
<?php
// RCI code start
echo $cre_RCI->get('orderinfo', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>