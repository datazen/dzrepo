    <?php
    // RCI code start
    echo $cre_RCI->get('global', 'top');
    echo $cre_RCI->get('account', 'top');
    // RCI code eof
    ?>
    <div class="row">
    <?php
    // BOF: Lango Added for template MOD
    if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
      $header_text = '&nbsp;'
      //EOF: Lango Added for template MOD
      ?>
            <h1 class="no-margin-top"><?php echo HEADING_TITLE; ?></h1>

      <?php
      // BOF: Lango Added for template MOD
    } else {
      $header_text =' <h1 class="no-margin-top">'. HEADING_TITLE .'</h1>';
    }
    // EOF: Lango Added for template MOD
    // BOF: Lango Added for template MOD
    // EOF: Lango Added for template MOD
  if ($messageStack->size('account') > 0) {
      ?>
         <div class="message-success-container alert alert-success"><?php echo $messageStack->output('account'); ?></div>

      <?php
  }

    ?>
        <div class="col-sm-12 col-lg-12">
          <h3><?php echo MY_ACCOUNT_TITLE; ?></h3>
          <div class="well clearfix large-margin-right">
          <img class="img-responsive pull-left large-margin-right img-responsive"src="<?=DIR_WS_TEMPLATES . TEMPLATE_NAME . '/'?>images/account.png">
          <div>
			<div><?php echo  ' <a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">' . MY_ACCOUNT_INFORMATION . '</a>'; ?></div>
			<div><?php echo  ' <a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . MY_ACCOUNT_ADDRESS_BOOK . '</a>'; ?></div>
			<div><?php echo  ' <a href="' . tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL') . '">' . MY_ACCOUNT_PASSWORD . '</a>'; ?></div>
			<div><?php echo  ' <a href="' . tep_href_link(FILENAME_WISHLIST, '', 'SSL') . '">' . MY_ACCOUNT_WISHLIST . '</a>'; ?></div>
         </div>
       </div>
          <h3><?php echo MY_ORDERS_TITLE; ?></h3>
          <div class="well clearfix large-margin-right">
          <img class="img-responsive pull-left large-margin-right img-responsive"src="<?=DIR_WS_TEMPLATES . TEMPLATE_NAME . '/'?>images/orders.png">
          <div>
			<div><?php echo ' <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . MY_ORDERS_VIEW . '</a>'; ?></div>
         </div>
       </div>
          <h3><?php echo EMAIL_NOTIFICATIONS_TITLE; ?></h3>
          <div class="well clearfix large-margin-right">
          <img class="img-responsive pull-left large-margin-right img-responsive"src="<?=DIR_WS_TEMPLATES . TEMPLATE_NAME . '/'?>images/notifications.png">
          <div>
			<div><?php echo  ' <a href="' . tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL') . '">' . EMAIL_NOTIFICATIONS_NEWSLETTERS . '</a>'; ?></div>
			<div><?php echo  ' <a href="' . tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL') . '">' . EMAIL_NOTIFICATIONS_PRODUCTS . '</a>'; ?></div>
         </div>
       </div>

      <?php
      // RCI code start
    echo $cre_RCI->get('account', 'menu');
      // RCI code eof
    // BOF: Lango Added for template MOD
    // EOF: Lango Added for template MOD
      ?>
    </div>
   </div>
  <?php
  // RCI code start
  echo $cre_RCI->get('account', 'bottom');
  echo $cre_RCI->get('global', 'bottom');
  // RCI code eof
  ?>