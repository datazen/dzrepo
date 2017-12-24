<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('checkoutshippingaddress', 'top');
// RCI code eof
echo tep_draw_form('checkout_address', tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL'), 'post', 'onSubmit="return check_form_optional(checkout_address);"'); ?>
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
<div class="row">
  <div class="col-sm-12 col-lg-12 large-margin-bottom">
    <h1 class="no-margin-top"><?php echo HEADING_TITLE; ?></h1>


<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>

<?php
  if ($messageStack->size('checkout_address') > 0) {
?>

        <?php echo $messageStack->output('checkout_address'); ?>


        <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>

<?php
  }

  if ($process == false) {
?>

 <div class="clearfix panel panel-default no-margin-bottom">
          <div class="panel-heading">
            <h3 class="no-margin-top no-margin-bottom"><?php echo HEADING_TITLE; ?></h3>
          </div>
          <div class="panel-body no-padding-bottom">




        <div class="col-sm-6 col-lg-6">
         <div class="well clearfix">
         <b><?php echo TABLE_HEADING_SHIPPING_ADDRESS; ?></b><br>
         <?php echo TEXT_SELECTED_SHIPPING_DESTINATION; ?>
		 </div>
		</div>

        <div class="col-sm-6 col-lg-6">
         <div class="well clearfix">
			<?php echo '<b>' . TITLE_SHIPPING_ADDRESS . '</b>'; ?><br>
		<?php echo tep_address_label($_SESSION['customer_id'], $_SESSION['sendto'], true, ' ', '<br>'); ?>
		</div>
		</div>


<?php
    if ($addresses_count > 1) {
?>
                   <div class="col-sm-12 col-lg-12">
         <div class="well clearfix">
            <b><?php echo TABLE_HEADING_ADDRESS_BOOK_ENTRIES; ?></b></br>
                <?php echo TEXT_SELECT_OTHER_SHIPPING_DESTINATION; ?>
                <?php echo '<b><br>' . TITLE_PLEASE_SELECT . '</b><br>' ; ?>

<?php
      $radio_buttons = 0;

      $addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
      while ($addresses = tep_db_fetch_array($addresses_query)) {
        $format_id = tep_get_address_format_id($addresses['country_id']);
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
       if ($addresses['address_book_id'] == $_SESSION['sendto']) {
          echo '                  <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
        } else {
          echo '                  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
        }
?>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="2"><b><?php echo tep_output_string_protected($addresses['firstname'] . ' ' . $addresses['lastname']); ?></b></td>
                    <td class="main" align="right"><?php echo tep_draw_radio_field('address', $addresses['address_book_id'], ($addresses['address_book_id'] == $_SESSION['sendto'])); ?></td>
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
</div>
</div>
<?php
    }
  }

  if ($addresses_count < MAX_ADDRESS_BOOK_ENTRIES) {
?>
<div class="row">
  <div class="col-sm-12 col-lg-12 large-margin-bottom">
    <h3 class="no-margin-top"><?php echo TABLE_HEADING_NEW_SHIPPING_ADDRESS; ?></h3><br>



<h7> <?php echo TEXT_CREATE_NEW_SHIPPING_ADDRESS; ?></h7>
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
echo $cre_RCI->get('checkoutshippingaddress', 'menu');
// RCI code eof
?>
     <?php/* <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><?php echo '<b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></td>
                <td class="main" align="right"><?php echo tep_draw_hidden_field('action', 'submit') . tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>*/?>

     <div class="btn-set small-margin-top clearfix">
      <button type="submit" class="pull-right btn btn-lg btn-primary"><?php echo tep_draw_hidden_field('action', 'submit') .  IMAGE_BUTTON_CONTINUE ; ?></button>
     <div class="well clearfix" style="max-width:47%">
     <?php echo '<b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?>
     </div>

   </div>



<?php
  if ($process == true) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
      </tr>
<?php
  }
?>
 <?php/*     <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table></td>
            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_DELIVERY; ?></td>
            <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_PAYMENT; ?></td>
            <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
            <td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
          </tr>
        </table></td>
      </tr>*/?>
     </div>
    </div>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="no-margin-top no-margin-bottom">Order Conformation</h3>
          </div>
        </div>

   </div>
  </div>

 </form>
<?php
// RCI code start
echo $cre_RCI->get('checkoutshippingaddress', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>