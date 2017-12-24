  <?php
  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('accountnotifications', 'top');
  // RCI code eof
  echo tep_draw_form('account_notifications', tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL')) . tep_draw_hidden_field('action', 'process'); ?>
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <form role="form" class="form-inline" name="account_notifications" id="account_notifications" method="post">
		<h1 class="no-margin-top"><?php echo HEADING_TITLE; ?></h1>
		<h3 class="large-margin-top"><?php echo MY_NOTIFICATIONS_TITLE; ?></h3>
        <div class="well">
    		<p class="large-margin-left no-margin-bottom normal"> <?php echo MY_NOTIFICATIONS_DESCRIPTION; ?></p>
        </div>
		<h3 class="large-margin-top"><?php echo GLOBAL_NOTIFICATIONS_TITLE; ?></h3>
        <div class="well">
          <div class="checkbox" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="checkBox('product_global')">
            <label class="margin-left"><?php echo tep_draw_checkbox_field('product_global', '1', (($global['global_product_notifications'] == '1') ? true : false), 'onclick="checkBox(\'product_global\')"'); ?><?php echo GLOBAL_NOTIFICATIONS_TITLE; ?></label>
        </div>
        <p class="large-margin-left no-margin-bottom normal">&nbsp;<?php echo GLOBAL_NOTIFICATIONS_DESCRIPTION; ?></p>
       </div>
<?php
  if ($global['global_product_notifications'] != '1') {
?>
<h3 class=""><?php echo NOTIFICATIONS_TITLE; ?></h3>
        <div class="well">

<?php
    $products_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
    $products_check = tep_db_fetch_array($products_check_query);
    if ($products_check['total'] > 0) {
?>
<p class="large-margin-left no-margin-bottom normal"><?php echo NOTIFICATIONS_DESCRIPTION; ?></p>
<?php
      $counter = 0;
      $products_query = tep_db_query("select pd.products_id, pd.products_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_NOTIFICATIONS . " pn where pn.customers_id = '" . (int)$_SESSION['customer_id'] . "' and pn.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by pd.products_name");
      while ($products = tep_db_fetch_array($products_query)) {
?>

                    <?php echo tep_draw_checkbox_field('products[' . $counter . ']', $products['products_id'], true, 'onclick="checkBox(\'products[' . $counter . ']\')"'); ?>
                    <b><?php echo $products['products_name']; ?></b>

<?php
        $counter++;
      }
    } else {
?>
<?php echo NOTIFICATIONS_NON_EXISTING; ?>
<?php
    }
?>
</div>
      <?php
      }
      // RCI code start
      echo $cre_RCI->get('accountnotifications', 'menu');
      // RCI code eof
      // BOF: Lango Added for template MOD
      if (MAIN_TABLE_BORDER == 'yes'){
        table_image_border_bottom();
      }
      // EOF: Lango Added for template MOD
      ?>
			<div class="btn-set small-margin-top clearfix">
				<a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL') ?>">  <button class="pull-right btn btn-lg btn-primary"  type="button"><?php echo IMAGE_BUTTON_CONTINUE; ?></button></a>
				<a href="<?php echo tep_href_link(FILENAME_ACCOUNT, '', 'SSL') ?>"><button class="pull-left btn btn-lg btn-default" type="button"><?php echo IMAGE_BUTTON_BACK; ?></button></a>
				<?php /*echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); */?>
			</div>
    </form>
    </div>
   </div>
    <?php
    // RCI code start
    echo $cre_RCI->get('accountnotifications', 'bottom');
    echo $cre_RCI->get('global', 'bottom');
    // RCI code eof
    ?>