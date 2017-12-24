<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('addressbookprocess', 'top');
// RCI code eof
if (!isset($_GET['delete'])) echo tep_draw_form('addressbook', tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, (isset($_GET['edit']) ? 'edit=' . $_GET['edit'] : ''), 'SSL'), 'post', 'onSubmit="return check_form(addressbook);"'); ?>
<div class="row">
  <div class="col-sm-12 col-lg-12">


<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
//EOF: Lango Added for template MOD
?>



           <?php if (isset($_GET['edit'])) { echo ' <h1 class="no-margin-top">'. HEADING_TITLE_MODIFY_ENTRY .'</h1>'; } elseif (isset($_GET['delete'])) { echo ' <h1 class="no-margin-top">' . HEADING_TITLE_DELETE_ENTRY .' </h1>'; } else { echo ' <h1 class="no-margin-top">'. HEADING_TITLE_ADD_ENTRY .'</h1>'; } ?></h1>
            <?php/* echo tep_image(DIR_WS_IMAGES . 'table_background_address_book.gif', (isset($_GET['edit']) ? HEADING_TITLE_MODIFY_ENTRY : HEADING_TITLE_ADD_ENTRY), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); */?>





<?php
// BOF: Lango Added for template MOD
}else{
if (isset($_GET['edit'])) { $header_text = HEADING_TITLE_MODIFY_ENTRY; } elseif (isset($_GET['delete'])) { $header_text = HEADING_TITLE_DELETE_ENTRY; } else { $header_text = HEADING_TITLE_ADD_ENTRY; }
}
// EOF: Lango Added for template MOD
?>


<?php
  if ($messageStack->size('addressbook') > 0) {
?>
<div class="message-stack-container alert alert-danger small-margin-bottom small-margin-left">
        <?php echo $messageStack->output('addressbook'); ?>
</div>
<div class="row">
 <form role="form" class="form-inline" style="border:1px solid red">
<?php
}
// BOF: Lango Added for template MOD
// EOF: Lango Added for template MOD

  if (isset($_GET['delete'])) {
?>










      <div class="col-sm-6 col-lg-6">
        <h3 class="small-margin-top"><?php echo SELECTED_ADDRESS; ?></h3>
        <div class="well">
          <address class="small-margin-bottom no-margin-top"><?php echo tep_address_label($_SESSION['customer_id'], $_GET['delete'], true, ' ', '<br>'); ?></address>
        </div>
       </div>

      <div class="col-sm-6 col-lg-6">
        <h3 class="small-margin-top">Are you sure ?<?php/* echo DELETE_ADDRESS_TITLE;*/ ?></h3>
        <div class="well">
          <p> <?php echo DELETE_ADDRESS_DESCRIPTION; ?></p>
        </div>
      </div>









        <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>

<?php
    // RCI code start
    echo $cre_RCI->get('addressbookprocess', 'menu');
    // RCI code eof

?>


    <div class="btn-set small-margin-top clearfix">
      <form action="<?php echo  tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $_GET['delete'] . '&amp;action=deleteconfirm', 'SSL'); ?>" method="post"><button class="pull-right btn btn-lg btn-primary"  type="submit"><?php echo IMAGE_BUTTON_DELETE; ?></button></form>
      <form action="<?php echo tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'); ?>" method="post"><button class="pull-left btn btn-lg btn-default"  type="submit"><?php echo IMAGE_BUTTON_BACK; ?></button></form>
    </div>


<?php
  } else {
?>


<?php
      // include(DIR_WS_MODULES . FILENAME_ADDRESS_BOOK_DETAILS);
        if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_ADDRESS_BOOK_DETAILS)) {
          require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_ADDRESS_BOOK_DETAILS);
        } else {
          require (DIR_WS_MODULES . FILENAME_ADDRESS_BOOK_DETAILS);
        }
?>
</form>

        <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>

<?php
    if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>


    <div class="btn-set small-margin-top clearfix">
      <button class="pull-right btn btn-lg btn-primary" type="submit"><?php echo tep_draw_hidden_field('action', 'update') . tep_draw_hidden_field('edit', $_GET['edit']) . IMAGE_BUTTON_UPDATE; ?></button>
      <a href="<?php echo tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') ?>"><button class="pull-left btn btn-lg btn-default" type="button"><?php echo IMAGE_BUTTON_BACK; ?></button></a>

      </div>



              <?php/*  <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>
                <?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?>
                <?php echo tep_draw_hidden_field('action', 'update') . tep_draw_hidden_field('edit', $_GET['edit']) . tep_template_image_submit('button_update.gif', IMAGE_BUTTON_UPDATE); ?>
                <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>
			*/?>




<?php
    } else {
      if (sizeof($navigation->snapshot) > 0) {
        $back_link = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
      } else {
        $back_link = tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL');
      }
?>

<?php
// RCI code start
echo $cre_RCI->get('addressbookprocess', 'menu');
// RCI code eof
// BOF: Lango Added for template MOD
// EOF: Lango Added for template MOD
?>




    <div class="btn-set small-margin-top clearfix">
      <button class="pull-right btn btn-lg btn-primary" type="submit"><?php echo tep_draw_hidden_field('action', 'process') . tep_draw_hidden_field('edit', $_GET['edit']) . IMAGE_BUTTON_CONTINUE; ?></button>
      <a href="<?php echo tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') ?>"><button class="pull-left btn btn-lg btn-default" type="button"><?php echo IMAGE_BUTTON_BACK; ?></button></a>

      </div>


                <?php/* echo '<a href="' . $back_link . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?>
                <?php echo tep_draw_hidden_field('action', 'process') . tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>
                <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); */?>






<?php
    }
  }
?>
  </div>
  <?php if (!isset($_GET['delete'])) echo '</form>'; ?>
  </div>

<?php
// RCI code start
echo $cre_RCI->get('addressbookprocess', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>
