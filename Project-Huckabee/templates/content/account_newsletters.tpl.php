  <?php
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('accountnewsletters', 'top');
  // RCI code eof
  echo tep_draw_form('account_newsletter', tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL')) . tep_draw_hidden_field('action', 'process'); ?>


	<div class="row">
	  <div class="col-sm-12 col-lg-12">
		<h1 class="no-margin-top"><?php echo HEADING_TITLE; ?></h1>
		<form role="form" class="form-inline" name="account_newsletter" id="account_newsletter" action="<?php tep_draw_form('account_newsletter', tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL')) . tep_draw_hidden_field('action', 'process'); ?>" method="post">
		  <div class="well">
			<div class="checkbox"    onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="checkBox('newsletter_general')">

			  <label class=""><?php echo tep_draw_checkbox_field('newsletter_general', '1', (($newsletter['customers_newsletter'] == '1') ? true : false), 'onclick="checkBox(\'newsletter_general\')"'); ?><?php echo MY_NEWSLETTERS_GENERAL_NEWSLETTER; ?></label>
			</div>
			<p class="margin-top normal"><?php echo MY_NEWSLETTERS_GENERAL_NEWSLETTER_DESCRIPTION; ?></p>
		  </div>
		</form>
			<div class="btn-set small-margin-top clearfix">
			<a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL') ?>">  <button class="pull-right btn btn-lg btn-primary"  type="button"><?php echo IMAGE_BUTTON_CONTINUE; ?></button></a>
	 	    <a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL') ?>"><button class="pull-left btn btn-lg btn-default" type="button"><?php echo IMAGE_BUTTON_BACK; ?></button></a>
			</div>

	  </div>
	</div>

    <?php
    // RCI code start
    echo $cre_RCI->get('accountnewsletters', 'bottom');
    echo $cre_RCI->get('global', 'bottom');
    // RCI code eof
    ?>