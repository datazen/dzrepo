<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('checkoutpaymentaddress', 'top');
// RCI code eof
echo tep_draw_form('checkout_address', tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL'), 'post', 'onSubmit="return check_form_optional(checkout_address);"'); ?>
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
     <div class="row">
        <div class="col-sm-12 col-lg-12">
		   <h1 style="no-margin-top"><?php echo HEADING_TITLE; ?></h1>
		</div>

<?php
// BOF: Lango Added for template MOD
}else{
$header_text = '<h1 style="no-margin-top">'. HEADING_TITLE . '</h1>';
}
// EOF: Lango Added for template MOD
?>

<?php
  if ($messageStack->size('checkout_address') > 0) {
?>
      <div class="message-success-container alert alert-success">
      <?php echo $messageStack->output('checkout_address'); ?>
      </div>
<?php
  }
?>
</div>
<?php
if ($process == false) {
?>
     <div class="row">
        <div class="col-sm-6 col-lg-6 well" style="margin-left:-4px">
         <h4 class="no-margin-top"><b><?php echo TABLE_HEADING_PAYMENT_ADDRESS; ?></b></h4>
         <h5 class="no-margin-top"><?php echo TEXT_SELECTED_PAYMENT_DESTINATION; ?></h5>
        </div>
        <div class="col-sm-6 col-lg-6 well cleft_c">
         <h4 class="no-margin-top"><?php echo '<b>' . TITLE_PAYMENT_ADDRESS . '</b>'; ?></h4>
         <?php echo tep_address_label($_SESSION['customer_id'], $_SESSION['billto'], true, ' ', '<br>'); ?>
        </div>
	 </div>

<?php
    if ($addresses_count > 1) {
?>
     <div class="row">
        <div class="col-sm-12 col-lg-12 well">


        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo TABLE_HEADING_ADDRESS_BOOK_ENTRIES; ?></b></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="50%" valign="top"><?php echo TEXT_SELECT_OTHER_PAYMENT_DESTINATION; ?></td>
                <td class="main" width="50%" valign="top" align="right"><?php echo '<b>' . TITLE_PLEASE_SELECT . '</b>'; ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
      $radio_buttons = 0;

      $addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $_SESSION['customer_id'] . "'");
      while ($addresses = tep_db_fetch_array($addresses_query)) {
        $format_id = tep_get_address_format_id($addresses['country_id']);
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
       if ($addresses['address_book_id'] == $_SESSION['billto']) {
          echo '                  <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
        } else {
          echo '                  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
        }
?>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="2"><b><?php echo $addresses['firstname'] . ' ' . $addresses['lastname']; ?></b></td>
                    <td class="main" align="right"><?php echo tep_draw_radio_field('address', $addresses['address_book_id'], ($addresses['address_book_id'] == $_SESSION['billto'])); ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td colspan="3"><table border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main"><?php echo tep_address_format($format_id, $addresses, true, ' ', ', '); ?></td>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      </tr>
                    </table></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                </table></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
        $radio_buttons++;
      }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
  </div>
 </div>
<?php
    }
  }

  if ($addresses_count < MAX_ADDRESS_BOOK_ENTRIES) {
?>
     <div class="row">
        <div class="col-sm-12 col-lg-12">
         <h4 class="no-margin-top"><b><?php echo TABLE_HEADING_NEW_PAYMENT_ADDRESS; ?></b></h4>
         <?php echo TEXT_CREATE_NEW_PAYMENT_ADDRESS; ?>

<?php
        // require(DIR_WS_MODULES . 'checkout_new_address.php');
        if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . 'checkout_new_address.php')) {
          require(TEMPLATE_FS_CUSTOM_MODULES . 'checkout_new_address.php');
        } else {
          require(DIR_WS_MODULES . 'checkout_new_address.php');
        }
?>
        </div>
	 </div>
<?php
  }
// RCI code start
echo $cre_RCI->get('checkoutpaymentaddress', 'menu');
// RCI code eof
// BOF: Lango Added for template MOD
// EOF: Lango Added for template MOD
?>

     <div class="row">
        <div class="col-sm-12 col-lg-12 well">
        <?php echo '<b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?>

        </div>
	 </div>
  <?php echo tep_draw_hidden_field('action', 'submit') . tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?>
<?php
  if ($process == true) {
?>

<?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?>

<?php
  }
?>
</form>
<?php
// RCI code start
echo $cre_RCI->get('checkoutpaymentaddress', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>