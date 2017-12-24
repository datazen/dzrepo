<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('createaccount', 'top');
// RCI code eof
echo tep_draw_form('create_account', tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'), 'post', 'onSubmit="return check_form(create_account);" enctype="multipart/form-data"') . tep_draw_hidden_field('action', 'process'); ?>
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <h1 class="no-margin-top"><?php echo HEADING_TITLE; ?></h1>
<?php
  if ($messageStack->size('create_account') > 0) {
?>
<div class="message-stack-container alert alert-danger small-margin-bottom small-margin-left">
        <?php echo $messageStack->output('create_account'); ?>


</div>
<?php
  }
?>

          <div class="row">
            <div class="col-sm-6 col-lg-6 large-padding-left margin-top">
	          <div class="well no-padding-top">
				<h3><?php echo CATEGORY_PERSONAL; ?></h3>
				<?php
				  if (ACCOUNT_GENDER == 'true') {
				?>
				<?php echo ENTRY_GENDER; ?>
				<?php echo tep_draw_radio_field('gender', 'm') . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f') . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?>
				<?php
				  }
				?>


				<div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo tep_draw_input_field('firstname', '' , 'class="form-control" placeholder="' . ENTRY_FIRST_NAME . '"'); ?></div>
				<div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo tep_draw_input_field('lastname', '' , 'class="form-control" placeholder="' . ENTRY_LAST_NAME . '"'); ?></div>
				<?php
				  if (ACCOUNT_DOB == 'true') {
				?>

				<?php echo ENTRY_DATE_OF_BIRTH; ?>
				<?php echo tep_draw_input_field('dob') . '&nbsp;' . (tep_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="inputRequirement">' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>': ''); ?>

				<?php
				  }
				?>



			 </div>
			</div>
	          <?php
			    if (ACCOUNT_COMPANY == 'true') {
			  ?>
			<div class="col-sm-6 col-lg-6 margin-top clearfix">
	          <div class="well no-padding-top">

				<h3><?php echo CATEGORY_COMPANY; ?></h3>
				<div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo tep_draw_input_field('company', '' , 'class="form-control" placeholder="' . ENTRY_COMPANY . '"'); ?></div>
				<div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo tep_draw_input_field('company_tax_id', '' , 'class="form-control" placeholder="' . ENTRY_COMPANY_TAX_ID . '"'); ?></div>
			 </div>
			</div>

			<?php
			  }
			?>
       </div>
<!--row2--start-->
                 <div class="row">
	               <div class="col-sm-6 col-lg-6 large-padding-left margin-top">
	   	          <div class="well no-padding-top">
	   				<h3><?php echo CATEGORY_ADDRESS; ?></h3>
	   				<div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo tep_draw_input_field('street_address', '' , 'class="form-control" placeholder="' . ENTRY_STREET_ADDRESS . '"'); ?></div>
					<?php
					  if (ACCOUNT_SUBURB == 'true') {
					?>
	   				<div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo tep_draw_input_field('suburb', '' , 'class="form-control" placeholder="' . ENTRY_SUBURB . '"'); ?></div>

					<?php
					  }
					?>
	   				<div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo tep_draw_input_field('postcode', '' , 'class="form-control" placeholder="' . ENTRY_POST_CODE . '"'); ?></div>
	   				<div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo tep_draw_input_field('city', '' , 'class="form-control" placeholder="' . ENTRY_CITY . '"'); ?></div>
					<?php
					  if (ACCOUNT_STATE == 'true') {
					?>

							<?php
								if ($process == true) {
								  if ($entry_state_has_zones == true) {
									$zones_array = array();
									$selected_zone = '';
									$zones_array[] = array('id' => '', 'text' => '');
									$zones_query = tep_db_query("select zone_name, zone_code from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
									while ($zones_values = tep_db_fetch_array($zones_query)) {
									  $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
									  if (strtolower($state) == strtolower($zones_values['zone_code']) || strtolower($state) == strtolower($zones_values['zone_name'])) {
										$selected_zone = $zones_values['zone_name'];
									  }
									}
									echo tep_draw_pull_down_menu('state', $zones_array, $selected_zone);
								  } else {
									echo tep_draw_input_field('state', '', 'placeholder="' . ENTRY_STATE . '"');
								  }
								} else {
								  echo '<div class="form-group full-width margin-bottom"><label class="sr-only"></label><span class="address-book-state-container">' . tep_draw_input_field('state', '', 'placeholder="' . ENTRY_STATE . '"') .'</span></div>';
								}

							?>

							<?php
							  }
							?>

                       <div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo tep_get_country_list('country','','class="box-manufacturers-select form-control form-input-width"') ; ?></span></div>

	   			 </div>
	   			</div>
            <div class="col-sm-6 col-lg-6 margin-top clearfix">
	          <div class="well no-padding-top">
	   				<h3><?php echo CATEGORY_CONTACT; ?></h3>
	   				<div class="form-group">
					<label class="sr-only"></label><?php echo tep_draw_input_field('email_address', '' , 'class="form-control" placeholder="' . ENTRY_EMAIL_ADDRESS . '"'); ?>
					</div>

	   				<div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo tep_draw_input_field('telephone', '' , 'class="form-control" placeholder="' . ENTRY_TELEPHONE_NUMBER . '"'); ?></div>
        	<div class="form-group full-width margin-bottom"><label class="sr-only"></label><?php echo tep_draw_input_field('fax', '' , 'class="form-control" placeholder="' . ENTRY_FAX_NUMBER . '"'); ?></div>
			 </div>
			</div>
            <div class="col-sm-6 col-lg-6 margin-top clearfix">
	          <div class="well no-padding-top">
	   				<h3><?php echo CATEGORY_OPTIONS; ?></h3>
                <div class="checkbox margin-top">
                  <label class="normal"><?php echo ENTRY_NEWSLETTER; ?><input id="newsletter" class="small-margin-left" type="checkbox" checked="checked" value="1" name="newsletter"></label>
                </div>
                <div class="checkbox margin-top">
                  <label class="normal"><?php echo 'Entry daiy detail'; ?><input id="dailydetail" class="small-margin-left" type="checkbox" checked="checked" value="1" name="dailydetail"></label>
                </div>

			 </div>
			</div>

       </div><!--row end 2-->
      <div class="row">
              <?php
              // RCI code start
                 echo $cre_RCI->get('createaccount', 'forms');
              // RCI code eof
              ?>



        </div>
<!--row start 3-->
          <div class="row">
            <div class="col-sm-6 col-lg-6 large-padding-left margin-top">
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
						<?php } } ?>

	                 </div>

			</div>
            <div class="col-sm-6 col-lg-6 margin-top clearfix">
	          <div class="well no-padding-top">
				<h3><?php echo CATEGORY_PASSWORD; ?></h3>
	            <div class="form-group full-width margin-bottom"><?php echo tep_draw_password_field('password', '' , 'class="form-control" placeholder="' . ENTRY_PASSWORD . '"'); ?></div>
	            <div class="form-group full-width margin-bottom"><?php echo tep_draw_password_field('confirmation', '' , 'class="form-control" placeholder="' . ENTRY_PASSWORD_CONFIRMATION . '"'); ?></div>

			  </div>
 			</div>

	  </div>
<!--row end 3-->

    <div class="btn-set small-margin-top clearfix">
      <button class="pull-right btn btn-lg btn-primary" type="submit"><?php echo IMAGE_BUTTON_CONTINUE; ?></button>

      </div>
<hr>

 </div>
</div>



  <?php
// RCI code start
echo $cre_RCI->get('createaccount', 'menu');
// RCI code eof
?>
    <?/*  <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
*/?>

   </form>
<?php
// RCI code start
echo $cre_RCI->get('createaccount', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>
