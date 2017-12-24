<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('checkoutpayment', 'top');
// RCI code eof
echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'), 'post', 'onsubmit="return check_form();"'); ?>
<div class="row">
<div class="col-sm-12 col-lg-12 large-margin-bottom">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>



    <h1 class="no-margin-top"><?php echo HEADING_TITLE; ?></h1>


<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>

<?php
  if (isset($_GET['error_message']) && tep_not_null($_GET['error_message'])) {
//echo 'x_Invoice_Num ' . $x_Invoice_Num;
    $sql_data_array = array('orders_id' =>  (isset($order_id1) ? $order_id1 : 0),
                           'orders_status_id' => '0',
                           'date_added' => 'now()',
                           'customer_notified' => '0',
                           'comments' => $_GET['error_message']);
   tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);


}
  if (isset($_GET['payment_error']) && is_object(${$_GET['payment_error']}) && ($error = ${$_GET['payment_error']}->get_error())) {
?>



           <b><?php echo tep_output_string_protected($error['title']); ?></b>

                <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>
                <?php echo tep_output_string_protected($error['error']); ?>
                <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>


<?php
  }
?>



 <div class="clearfix panel panel-default no-margin-bottom">
          <div class="panel-heading">
            <h3 class="no-margin-top no-margin-bottom">Payment Information</h3>
          </div>
          <div class="panel-body no-padding-bottom">
            <div class="row">



        <div class="col-sm-6 col-lg-6">
         <div class="well clearfix" style="height:210px;">
			<?php echo TEXT_SELECTED_BILLING_DESTINATION; ?><br><br><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '" type="button" class="btn btn-sm cursor-pointer small-margin-right btn-success" style="color:#ffffff !important;text-decoration:none !important;">' .  IMAGE_BUTTON_CHANGE_ADDRESS . '</a>'; ?>
		 </div>
		</div>
        <div class="col-sm-6 col-lg-6">
         <div class="well clearfix" style="height:210px;">
                  <?php echo '<h4 class="no-margin-top"><b>' . TITLE_BILLING_ADDRESS .'</b></h4>'; ?>
                  <?php echo tep_address_label($_SESSION['customer_id'], $_SESSION['billto'], true, ' ', '<br>'); ?>
        </div>
       </div>
<?php
// BOF: Lango Added for template MOD
// EOF: Lango Added for template MOD

// beginning of the coupon redemption code
  if ($order_total_modules->credit_selection()!='' ) {
?>
	<div class="row">
	<div class="col-sm-12 col-lg-12" style="padding-left:30px;padding-right:30px;">
	  <div class="well clearfix">
  <?php  if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
      ?>
            <?php echo '<h4 class="no-margin-top"><b>' . TABLE_HEADING_CREDIT . '</b></h4>' ; ?>

      <?php
    }

    echo $order_total_modules->credit_selection();//ICW ADDED FOR CREDIT CLASS SYSTEM
  ?>
    </div>
  </div>
</div>
<div class="clearfix"></div>
  <?php
  }

// End of the coupon redemption code
?>
    <div class="col-sm-12 col-lg-12">
         <div class="well clearfix">

			<h4 class="no-margin-top"><b><?php echo TABLE_HEADING_PAYMENT_METHOD; ?></b></h4><br>
          <div class="col-sm-7 col-lg-7 hide-on-mobile"><?php echo TEXT_SELECT_PAYMENT_METHOD; ?></div>
		  <div class="col-sm-5 col-lg-5 text-right hide-on-mobile"><b><?php echo TITLE_PLEASE_SELECT; ?></b></div><br>


<?php
  if( $order->info['total'] != 0 ){
// RCO start
    if ($cre_RCO->get('checkoutpayment', 'paymentmodule') !== true) {
?>
    <table border="0" width="100%" cellspacing="1" cellpadding="2" class="table">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  $selection = $payment_modules->selection();

  if (sizeof($selection) > 1) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="50%" valign="top"><?php echo TEXT_SELECT_PAYMENT_METHOD; ?></td>
                <td class="main" width="50%" valign="top" align="right"><b><?php echo TITLE_PLEASE_SELECT; ?></b><br><?php echo tep_image(DIR_WS_IMAGES . 'arrow_east_south.gif'); ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
  } else {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="100%" colspan="2"><?php echo TEXT_ENTER_PAYMENT_INFORMATION; ?></td>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
  }

  $radio_buttons = 0;
  for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
    if ( (isset($payment) && $selection[$i]['id'] == $payment) || ($n == 1) ) {
      echo '                  <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
    } else {
      echo '                  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
    }
?>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="3"><b><?php echo $selection[$i]['module']; ?></b></td>
                    <td class="main" style="text-align:right;">
<?php
    if (sizeof($selection) > 1) {
      if (isset($_SESSION['payment']) && $_SESSION['payment'] == $selection[$i]['id']) {
        echo tep_draw_radio_field('payment', $selection[$i]['id'], true);
      } elseif (!isset($_SESSION['payment']) && $i == 0) {
        echo tep_draw_radio_field('payment', $selection[$i]['id'], true);
      } else {
        echo tep_draw_radio_field('payment', $selection[$i]['id']);
      }
    } else {
      echo tep_draw_hidden_field('payment', $selection[$i]['id']);
    }
?>
                    </td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
    if (isset($selection[$i]['error'])) {
?>
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" colspan="4"><?php echo $selection[$i]['error']; ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
    } elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) {
?>
                  <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td colspan="4"><table border="0" cellspacing="0" cellpadding="2">
<?php
      for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {
?>
                      <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main"><?php echo $selection[$i]['fields'][$j]['title']; ?></td>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main"><?php echo $selection[$i]['fields'][$j]['field']; ?></td>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      </tr>
<?php
      }
?>
                    </table></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
<?php
    }
?>
                </table></td>
              </tr>
<?php
    $radio_buttons++;
  }
?>
            </table></td>
          </tr>
        </table>
<?php
    }
    // RCO end
} else {
?>
      <tr>
        <td><?php echo TEXT_ORDER_TOTAL_ZERO;?></td>
      </tr>
<?php
$_SESSION['payment'] = 'freecharger';
}
// BOF: Lango Added for template MOD
// EOF: Lango Added for template MOD

  // RCI code start
 //   echo $cre_RCI->get('checkoutpayment', 'billingtableright');
  // RCI code eof
?>
<div class="clearfix"></div>
 </div>
</div>

<?php
  if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
    $header_text = '&nbsp;'
  ?>
<div class="col-sm-12 col-lg-12">
              <div class="well ">
            <h4><b><?php echo TABLE_HEADING_COMMENTS; ?></h3></h4>

  <?php
  } else {
     $header_text = TABLE_HEADING_COMMENTS;
  }
  ?>
<div class="form-group">
        <label class="sr-only"></label>

<?php/*<textarea class="form-control" name="comments" rows="5" cols="25" ></textarea>
  */ ?>
  <?php echo tep_draw_textarea_field('comments', 'soft', '60', '5', isset($_SESSION['comments']) ? $_SESSION['comments'] : '');?>
</div>
</div>
</div>



      <?php
      //RCI start
      echo $cre_RCI->get('checkoutpayment', 'insideformabovebuttons');
      //RCI end
      ?>
 <div class="col-sm-6 col-lg-6"><div class="well clearfix">  <?php echo '<h4 style="color:#000000"><b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b></h4><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></div></div>
<div class="col-sm-6 col-lg-6">
  <div class="btn-set small-margin-top clearfix">
      <button type="submit" class="pull-right btn btn-lg btn-primary">Continue</button>
    </div>
   </div>



   <?php/*   <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><b><?php echo TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></td>
                <td class="main" align="right"><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>*/?>
      <?php
      //RCI start
      echo $cre_RCI->get('checkoutpayment', 'insideformbelowbuttons');
      //RCI end
      ?>

      </div>
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
echo $cre_RCI->get('checkoutpayment', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>