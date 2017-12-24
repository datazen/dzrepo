<?php
echo tep_draw_form('order', tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'action=update', 'SSL'), 'post', 'enctype="multipart/form-data"'); ?>
<div class="row">
  <div class="col-sm-12 col-lg-12 large-margin-bottom">
  <?php
  // BOF: Lango Added for template MOD
  if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
    $header_text = '&nbsp;'
    //EOF: Lango Added for template MOD
    ?>
<div class="well large-margin-top">
  <h3 class="no-margin-top">   <?php echo HEADING_TITLE; ?></h3>


</div>

    <?php
    // BOF: Lango Added for template MOD
  }else{
    $header_text = HEADING_TITLE;
  }
  // EOF: Lango Added for template MOD

  if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_ADD_CHECKOUT_SUCCESS)) {
    require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_ADD_CHECKOUT_SUCCESS);
  } else {
    require(DIR_WS_MODULES . FILENAME_ADD_CHECKOUT_SUCCESS);
  }
  ?>

  <!-- checkout_success_modules - start -->


      <?php
      if (MODULE_CHECKOUT_SUCCESS_INSTALLED) {
        $checkout_success_modules->process();
        echo $checkout_success_modules->output();
      }
      ?>


  <!-- checkout_success_modules - end -->



  <?php
  //RCI start
  echo $cre_RCI->get('checkoutsuccess', 'insideformabovebuttons');
  //RCI end
  ?>

<div class="btn-set small-margin-top clearfix">
      <button type="submit" class="pull-right btn btn-lg btn-primary">Continue</button>
     <?php echo '<a href="javascript:popupWindow(\'' .  tep_href_link(FILENAME_ORDERS_PRINTABLE, tep_get_all_get_params(array('order_id')) . 'order_id=' . (int)$_GET['order_id'].'&customer_id='.(int)$customer_id, 'NONSSL') . '\')"><button type="button" class="pull-left btn btn-lg btn-default">' . IMAGE_BUTTON_PRINT_ORDER . '</button></a>'; ?>

</div>
  <?php
  //RCI start
  echo $cre_RCI->get('checkoutsuccess', 'insideformbelowbuttons');
   //RCI end
  ?>
 <?php/* <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%" align="right"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
            <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
          </tr>
        </table></td>
        <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
        <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
        <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            <td width="50%"><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_DELIVERY; ?></td>
        <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_PAYMENT; ?></td>
        <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
        <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
      </tr>
    </table></td>
  </tr>*/?>
  <?php if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . 'downloads.php'); ?>
</div>
</div>
</form>
<?php
// RCI code start
echo $cre_RCI->get('checkoutsuccess', 'bottom', false);
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>