<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('advancedsearch', 'top');
// RCI code eof
echo tep_draw_form('advanced_search', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get', 'onSubmit="return check_form(this);"') . tep_hide_session_id(); ?>
<div class="col-sm-12 col-lg-12">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
    <div class="row">
      <div class="col-sm-12 col-lg-12 large-margin-bottom">
         <h1> <?php echo HEADING_TITLE_1; ?></h1>




<?php
}else{
$header_text = '<h1>' . HEADING_TITLE_1 . '</h1>';
}
?>

<?php
// BOF: Lango Added for template MOD
// EOF: Lango Added for template MOD
?>
<?php
  if ($messageStack->size('search') > 0) {
?>
       <div class="message-stack-container alert alert-danger">
             <?php echo $messageStack->output('search'); ?>
       </div>



<?php
  }
?>
        <div class="content-search-container">
          <div class="form-group  large-padding-right">
            <label class="sr-only"></label>
            <input type="text" name="keywords" value="" class="form-control" placeholder="<?php echo HEADING_SEARCH_CRITERIA; ?>">
          </div>
        </div>
        <div class="button-set">
			<p class="pull-right"><?php echo tep_template_image_submit('button_search.gif', IMAGE_BUTTON_SEARCH); ?> </p>
			<p class="help-block margin-left"><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_SEARCH_HELP) . '\')">' . TEXT_SEARCH_HELP_LINK . '</a>'; ?></p>
			<p class="help-block margin-left"><?php echo tep_draw_checkbox_field('search_in_description', '1') . ' ' . TEXT_SEARCH_IN_DESCRIPTION; ?></p>

        </div>
      </div>
    </div>

    <div class="row large-margin-top">
      <div class="col-sm-12 col-lg-12">
        <h3 class="large-margin-bottom margin-top"><?php echo HEADING_TITLE_1; ?></h3>
        <div class="form-group">
          <label class="control-label col-sm-3 col-lg-3 text-right"><?php echo ENTRY_CATEGORIES; ?></label>
          <div class="col-sm-9 col-lg-9"><?php echo tep_draw_pull_down_menu('categories_id', tep_get_categories(array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES))), null, 'class="form-control"'); ?></div>
        </div>&nbsp;
        <div class="form-group">
          <label class="control-label col-sm-3 col-lg-3 text-right margin-top" style="padding-left:0px;"><?php echo ENTRY_INCLUDE_SUBCATEGORIES; ?></label>
          <div class="col-sm-9 col-lg-9"><?php echo tep_draw_checkbox_field('inc_subcat', null, null, 'class="form-control"', null); ?></div>
        </div>&nbsp;
        <div class="form-group">
          <label class="control-label col-sm-3 col-lg-3 text-right small-margin-top"><?php echo ENTRY_MANUFACTURERS; ?></label>
          <div class="col-sm-9 col-lg-9"><?php echo tep_draw_pull_down_menu('manufacturers_id', tep_get_manufacturers(array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS))), null, 'class="form-control"'); ?></div>
        </div>&nbsp;
        <div class="form-group">
          <label class="control-label col-sm-3 col-lg-3 text-right small-margin-top"><?php echo ENTRY_PRICE_FROM; ?></label>
          <div class="col-sm-9 col-lg-9"><?php echo tep_draw_input_field('pfrom', null, 'class="form-control"'); ?></div>
        </div>&nbsp
        <div class="form-group">
          <label class="control-label col-sm-3 col-lg-3 text-right small-margin-top"><?php echo ENTRY_PRICE_TO; ?></label>
          <div class="col-sm-9 col-lg-9"><?php echo tep_draw_input_field('pto', null, 'class="form-control"'); ?></div>
        </div>&nbsp
        <div class="form-group">
          <label class="control-label col-sm-3 col-lg-3 text-right small-margin-top"><?php echo ENTRY_DATE_FROM; ?></label>
          <div class="col-sm-9 col-lg-9"><?php echo tep_draw_input_field('dfrom', DOB_FORMAT_STRING, 'onFocus="RemoveFormatString(this, \'' . DOB_FORMAT_STRING . 'class="form-control"'); ?></div>
        </div>&nbsp
        <div class="form-group">
          <label class="control-label col-sm-3 col-lg-3 text-right small-margin-top"><?php echo ENTRY_DATE_TO; ?></label>
          <div class="col-sm-9 col-lg-9"><?php echo tep_draw_input_field('dto', DOB_FORMAT_STRING, 'onFocus="RemoveFormatString(this, \'' . DOB_FORMAT_STRING . 'class="form-control"'); ?></div>
        </div>&nbsp
      </div>
     </div>



<?php
// BOF: Lango Added for template MOD
// RCI code start
echo $cre_RCI->get('advancedsearch', 'menu');
// RCI code eof
// EOF: Lango Added for template MOD
?>
    </div>
   </form>
<?php
// RCI code start
echo $cre_RCI->get('advancedsearch', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>