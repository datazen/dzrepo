    <?php
    // RCI code start
    echo $cre_RCI->get('global', 'top');
    echo $cre_RCI->get('accountedit', 'top');
    // RCI code eof
    echo tep_draw_form('account_edit', tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'), 'post', 'onSubmit="return check_form(account_edit);"') . tep_draw_hidden_field('action', 'process'); ?>
    <div class="row">
     <div class="col-sm-12 col-lg-12">

    <?php
    // BOF: Lango Added for template MOD
    if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
      $header_text = '&nbsp;'
      //EOF: Lango Added for template MOD
      ?>
         <h1 class="no-margin-top">   <?php echo HEADING_TITLE; ?></h1>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>
<?php
// BOF: Lango Added for template MOD
// EOF: Lango Added for template MOD
?>

<?php
  if ($messageStack->size('account_edit') > 0) {
?>
        <div><?php echo $messageStack->output('account_edit'); ?></div>
<?php
  }
?>
  </div></div>
  <div class="col-sm-12 col-lg-12">
	  <div class="row">
     <div class="col-sm-6 col-lg-6">
     <h3 class="small-margin-top">Personal Detail</h3>
<?php
  if (ACCOUNT_GENDER == 'true') {
    if (isset($gender)) {
      $male = ($gender == 'm') ? true : false;
    } else {
      $male = ($account['customers_gender'] == 'm') ? true : false;
    }
    $female = !$male;
?>
					<?php echo ENTRY_GENDER; ?>
                    <?php echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?>
<?php
  }
?>

          <div class="well clearfix">
            <div class="form-group">
              <label class="sr-only"></label><?php echo tep_draw_input_field('firstname', $account['customers_firstname'] , 'class="form-control" placeholder="' . ENTRY_FIRST_NAME . '"'); ?>
            </div>
            <div class="form-group">
              <label class="sr-only"></label><?php echo tep_draw_input_field('lastname', $account['customers_lastname'] , 'class="form-control" placeholder="' . ENTRY_LAST_NAME . '"'); ?>
            </div>

<?php
  if (ACCOUNT_DOB == 'true') {
?>

                    <?php echo ENTRY_DATE_OF_BIRTH; ?>
                    <?php echo tep_draw_input_field('dob', tep_date_short($account['customers_dob'])) . '&nbsp;' . (tep_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="inputRequirement">' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>': ''); ?>

<?php
  }
?>
            <div class="form-group">
              <label class="sr-only"></label><?php echo tep_draw_input_field('email_address', $account['customers_email_address'] , 'class="form-control" placeholder="' . ENTRY_EMAIL_ADDRESS . '"'); ?>
            </div>
            <div class="form-group">
              <label class="sr-only"></label><?php echo tep_draw_input_field('telephone', $account['entry_telephone'] , 'class="form-control" placeholder="' . ENTRY_TELEPHONE_NUMBER . '"'); ?>
            </div>
            <div class="form-group">
              <label class="sr-only"></label><?php echo tep_draw_input_field('fax', $account['entry_fax'] , 'class="form-control" placeholder="' . ENTRY_FAX_NUMBER . '"'); ?>
            </div>
  </div>
 </div>
         <div class="col-sm-6 col-lg-6">
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>



         <h3 class="small-margin-top"><?php echo CATEGORY_COMPANY; ?></h3>
          <div class="well clearfix">
            <div class="form-group ">
              <label class="sr-only"></label><?php echo tep_draw_input_field('company', $account['entry_company'] , 'class="form-control" placeholder="' . ENTRY_COMPANY . '"'); ?>
            </div>
            <div class="form-group ">
              <label class="sr-only"></label><?php echo tep_draw_input_field('company_tax_id',  $account['entry_company_tax_id'] , 'class="form-control" placeholder="' . ENTRY_COMPANY_TAX_ID . '"'); ?>
            </div>
           </div>
          </div>


                <div class="btn-set small-margin-top clearfix">
		        <button class="pull-right btn btn-lg btn-primary" type="submit"><?php echo IMAGE_BUTTON_CONTINUE; ?></button>
		        <a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL') ?>"><button class="pull-left btn btn-lg btn-default" type="button"><?php echo IMAGE_BUTTON_BACK; ?></button></a>

		        </div>

<?php
  }
      // RCI to alow for additioanl fields to be listed
      echo $cre_RCI->get('accountedit', 'listing');
?>


      <?php
      // RCI code start
      echo $cre_RCI->get('accountedit', 'menu');
      // RCI code eof
      // BOF: Lango Added for template MOD
      // EOF: Lango Added for template MOD
      ?>
     </div>
    </div></form>
    <?php
    // RCI code start
    echo $cre_RCI->get('accountedit', 'bottom');
    echo $cre_RCI->get('global', 'bottom');
    // RCI code eof
    ?>
