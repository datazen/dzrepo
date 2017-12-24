<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('contactus', 'top');
// RCI code eof
echo tep_draw_form('contact_us', tep_href_link(FILENAME_CONTACT_US, 'action=send', 'SSL')); ?>

<div class="row">
  <div class="col-sm-12 col-lg-12">
          <h1 class="no-margin-top"><?php echo HEADING_TITLE; ?></h1>

<?php
  if ($messageStack->size('contact') > 0) {
?>
    <div class="message-stack-container alert alert-danger">
       <?php echo $messageStack->output('contact'); ?>

   </div>
  <?php
  }

  if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
?>
		<div class="message-success-container alert alert-success">
		  <?php echo TEXT_SUCCESS ;?>
		  <?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '"></a>'; ?>
		</div>
         <div class="btn-set small-margin-top clearfix">
			<button class="pull-right btn btn-lg btn-primary" type="submit"><?php echo IMAGE_BUTTON_CONTINUE; ?></button>
         </div>
<?php
  } else {
  if (defined('TEXT_BODY') && TEXT_BODY !='') {
?>


      <div class="row large-margin-top">
        <div class="col-sm-5 col-lg-5">
        <div class="well">
                 <?php echo TEXT_BODY; ?>
	   </div>
	 </div>



 <?php } ?>


<div class="col-sm-7 col-lg-7">
<form role="form" id="contact" name="contact" class="row-fluid">


          <?php if (defined('CONTACT_US_ADDRESS') && CONTACT_US_ADDRESS !='') {?>
         <?php echo CONTACT_US_ADDRESS; ?>
          <?php } ?>
         <?php if (defined('CONTACT_US_TELPHONE_NUMBER') && CONTACT_US_TELPHONE_NUMBER !='') { echo '<br>' . CONTACT_US_TELPHONE_NUMBER . '<br>';} if (defined('CONTACT_US_FAX_NUMBER') && CONTACT_US_FAX_NUMBER != '') {echo CONTACT_US_FAX_NUMBER; }?>

          <?php if (defined('CONTACT_US_EMAIL_ID') && CONTACT_US_EMAIL_ID != '') {?>

          <?php echo CONTACT_US_EMAIL_ID; ?>

          <?php }?>
            <?php if (defined('CONTACT_US_SKYPE_ID') && CONTACT_US_SKYPE_ID != '') { echo '<a href="skype:' . CONTACT_US_SKYPE_ID . '?call">' . tep_image(DIR_WS_ICONS . 'skype.gif') . '</a>'; }?>
            <?php if (defined('CONTACT_US_YAHOO_IM') && CONTACT_US_YAHOO_IM != '') { echo '<br><a href ="ymsgr:sendim?' . CONTACT_US_YAHOO_IM . '">' . tep_image(DIR_WS_ICONS . 'yahoo.gif') . '</a>';}?>
            <?php if (defined('CONTACT_US_AIM_ID') && CONTACT_US_AIM_ID != '') { echo '<br><a href ="aim:goim?screenname=' . CONTACT_US_AIM_ID . '&amp;message=Hi.+Are+you+there?">' . tep_image(DIR_WS_ICONS . 'aim.gif') . '</a>';}?></td>

				<div class="form-group"><label class="sr-only"></label><?php echo tep_draw_input_field('company', '' , 'class="form-control" placeholder="' . ENTRY_COMPANY . '"'); ?></div>
				<div class="form-group"><label class="sr-only"></label><?php echo tep_draw_input_field('name', '' , 'class="form-control" placeholder="' . ENTRY_NAME . '"'); ?></div>
				<div class="form-group"><label class="sr-only"></label><?php echo tep_draw_input_field('email', '' , 'class="form-control" placeholder="' . ENTRY_EMAIL . '"'); ?></div>
				<div class="form-group"><label class="sr-only"></label><?php echo tep_draw_input_field('telephone', '' , 'class="form-control" placeholder="' . ENTRY_TELEPHONE_NUMBER . '"'); ?></div>
				<div class="form-group"><label class="sr-only"></label><?php echo tep_draw_input_field('street', '' , 'class="form-control" placeholder="' . ENTRY_STREET_ADDRESS . '"'); ?></div>
				<div class="form-group"><label class="sr-only"></label><?php echo tep_draw_input_field('city', '' , 'class="form-control" placeholder="' . ENTRY_CITY . '"'); ?></div>
				<div class="form-group"><label class="sr-only"></label><?php echo tep_draw_input_field('state', '' , 'class="form-control" placeholder="' . ENTRY_STATE . '"'); ?></div>
				<div class="form-group"><label class="sr-only"></label><?php echo tep_draw_input_field('postcode', '' , 'class="form-control" placeholder="' . ENTRY_POST_CODE . '"'); ?></div>
				<div class="form-group"><label class="sr-only"></label><?php echo tep_draw_input_field('country', '' , 'class="form-control" placeholder="' . ENTRY_COUNTRY . '"'); ?></div>
				<?php
					$topic_array = array();
					$topic_array = array(array('id' => ENTRY_TOPIC_1, 'text' => ENTRY_TOPIC_1),
										 array('id' => ENTRY_TOPIC_2, 'text' => ENTRY_TOPIC_2),
										 array('id' => ENTRY_TOPIC_3, 'text' => ENTRY_TOPIC_3),
										 array('id' => ENTRY_TOPIC_4, 'text' => ENTRY_TOPIC_4)
										 );
				?>


				<div class="form-group"><label class="sr-only"></label><?php echo tep_draw_pull_down_menu('topic', $topic_array , 'class="form-control" placeholder="' . ENTRY_TOPIC . '"'); ?></div>
				<div class="form-group"><label class="sr-only"></label><?php echo tep_draw_input_field('subject', '' , 'class="form-control" placeholder="' . ENTRY_SUBJECT . '"'); ?></div>
				<div class="form-group"><label class="sr-only"></label><textarea class="form-control" name="enquiry" rows="5" cols="25" placeholder="<?php echo ENTRY_ENQUIRY; ?>"></textarea></div>
				<div class="form-group"><label class="sr-only"></label><?php echo ENTRY_URGENT; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo tep_draw_checkbox_field('urgent'); ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ENTRY_SELF; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo tep_draw_checkbox_field('self'); ?></div>
  </form>
  </div>
</div>
 <?php
// RCI code start
echo $cre_RCI->get('contactus', 'menu');
// RCI code eof
// BOF: Lango Added for template MOD
// EOF: Lango Added for template MOD
?>
    <div class="btn-set small-margin-top clearfix">
      <button class="pull-right btn btn-lg btn-primary" type="submit">Send Message</button>
    </div>
  <?php
  }
?>
 </div>
</div>
</form>
<?php
// RCI code start
echo $cre_RCI->get('contactus', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>
