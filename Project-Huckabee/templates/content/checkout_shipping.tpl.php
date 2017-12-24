<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('checkoutshipping', 'top');
// RCI code eof
echo tep_draw_form('checkout_address', tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL')) . tep_draw_hidden_field('action', 'process'); ?>
<div class="row">
<div class="col-sm-12 col-lg-12 large-margin-bottom">

  <?php
  if (isset($_GET['shipping_error'])) {
    $error['error'] = TEXT_CHOOSE_SHIPPING_METHOD ;
    ?>


           <?php echo tep_output_string_protected($error['error']); ?></td>
    <?php
  }
  ?>

            <?php
            // RCO start
            //if ($cre_RCO->get('checkoutshipping', 'changeaddressbutton') !== true) {
           // }
            // RCO eof
            ?>
		<div class="panel panel-default no-margin-bottom">
			  <div class="panel-heading">
				<h3 class="no-margin-top no-margin-bottom"><?php echo HEADING_TITLE; ?></h3>
			  </div>
			  <div class="panel-body no-padding-bottom">
				<div class="row">

				<div class="col-sm-6 col-lg-6">
				 <div class="well clearfix" style="height:210px;">
						<?php echo '<h4 class="no-margin-top"><b>' . TITLE_SHIPPING_ADDRESS . '</b></h4>' ; ?>
						<?php
		       		echo '' . TEXT_CHOOSE_SHIPPING_DESTINATION . '<br><br><a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '" type="button" class="btn btn-sm cursor-pointer small-margin-right btn-success" style="color:#ffffff !important;text-decoration:none !important;">' . IMAGE_BUTTON_CHANGE_ADDRESS  . '</a>';
					?>
					 </div>
					</div>
				<div class="col-sm-6 col-lg-6">
				 <div class="well clearfix" style="height:210px;">
					 <?php echo '<h4 class="no-margin-top"><b>' . TITLE_SHIPPING_ADDRESS . '</b></h4>' ; ?>
						  <?php echo tep_address_label($_SESSION['customer_id'], $_SESSION['sendto'], true, ' ', '<br>'); ?>
					 </div>
					</div>

  <?php
  if (MAIN_TABLE_BORDER == 'yes'){
    table_image_border_bottom();
  }
  //MVS start
  if (tep_count_shipping_modules() > 0 || MVS_STATUS == 'true') {?>
  	<div class="col-sm-12 col-lg-12">
  	   <div class="well clearfix">

  <?php  if (MVS_STATUS == 'true') {
      require(DIR_WS_MODULES . 'vendor_shipping.php');
    } else {
      if (MAIN_TABLE_BORDER == 'yes'){
        table_image_border_top(false, false, TABLE_HEADING_SHIPPING_METHOD);
      } else {
        ?>
        <tr>
          <td align="left"><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td class="main"><b><?php echo TABLE_HEADING_SHIPPING_METHOD; ?></b></td>
            </tr>
          </table></td>
        </tr>
       <?php
      }
      ?>
	  <tr>
          <td align="left"><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td class="main"><b><?php echo TABLE_HEADING_SHIPPING_METHOD; ?></b></td>
            </tr>
          </table></td>
        </tr>
      <tr>
        <td align="left"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <?php
              if (sizeof($quotes) > 1 && sizeof($quotes[0]) > 1) {
                ?>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  <td class="main" width="50%" valign="top"><?php echo TEXT_CHOOSE_SHIPPING_METHOD; ?></td>
                  <td class="main" width="50%" valign="top" align="right"><?php echo '<b>' . TITLE_PLEASE_SELECT . '</b><br>' . tep_image(DIR_WS_IMAGES . 'arrow_east_south.gif'); ?></td>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                </tr>
                <?php
              } elseif ($free_shipping == false) {
                ?>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  <td class="main" width="100%" colspan="2"><?php echo TEXT_ENTER_SHIPPING_INFORMATION; ?></td>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                </tr>
                <?php
              }
              if ($free_shipping == true) {
                ?>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  <td colspan="2" width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      <td class="main" colspan="3"><b><?php echo FREE_SHIPPING_TITLE; ?></b>&nbsp;<?php echo (isset($quotes[$i]['icon']) ? $quotes[$i]['icon'] : ''); ?></td>
                      <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    </tr>
                    <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, 0)">
                      <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      <td class="main" width="100%"><?php echo sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format($freeshipping_over_amount)) . tep_draw_hidden_field('shipping', 'free_free'); ?></td>
                      <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    </tr>
                  </table></td>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                </tr>
                <?php
              } else {
                $radio_buttons = 0;
                for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
                  ?>
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="table">
                      <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main" colspan="3"><b><?php echo $quotes[$i]['module']; ?></b>&nbsp;<?php if (isset($quotes[$i]['icon']) && tep_not_null($quotes[$i]['icon'])) { echo $quotes[$i]['icon']; } ?></td>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      </tr>
                      <?php
                      if (isset($quotes[$i]['error'])) {
                        ?>
                        <tr>
                          <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                          <td class="main" colspan="3"><?php echo $quotes[$i]['error']; ?></td>
                          <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        </tr>
                        <?php
                      } else {
                        for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
                          // set the radio button to be checked if it is the method chosen
                          $checked = (($quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] == $_SESSION['shipping']['id']) ? true : false);
                          if ( ($checked == true) || ($n == 1 && $n2 == 1) ) {
                            echo '<tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                          } else {
                            echo '<tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                          }
                          ?>
                          <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                          <td class="main" width="75%"><?php echo $quotes[$i]['methods'][$j]['title']; ?></td>
                          <?php
                          if ( ($n > 1) || ($n2 > 1) ) {
                            ?>
                            <td class="main"><?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0))); ?></td>
                            <td class="main" align="right"><?php echo tep_draw_radio_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'], $checked); ?></td>
                            <?php
                          } else {
                            ?>
                            <td class="main" align="right" colspan="2"><?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0))) . tep_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']); ?></td>
                            <?php
                          }
                          ?>
                          <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                          </tr>
                          <?php
                          $radio_buttons++;
                        }
                      }
                      ?>
                    </table></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                  <?php
                }
              }
              ?>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <?php
      if (MAIN_TABLE_BORDER == 'yes'){
        table_image_border_bottom();
      }
    }
?>
 </div>
</div>


<?php  } //MVS end
  ?>


			<?php
			  if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
				$header_text = '&nbsp;'
			  ?>

            <div class="col-sm-12 col-lg-12">
              <div class="well ">
				<div class="form-group"><label class="sr-only"></label>

					 <h4 style="color:#000000"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></h4>

			  <?php
			  } else {
				 $header_text = TABLE_HEADING_COMMENTS;
			  }
			?>
			<?php echo tep_draw_textarea_field('comments','soft', '60', '5',$_SESSION['comments']); ?>
			   </div>
			  </div>
			 </div>

			  <?php
			  //RCI above buttons
			  echo $cre_RCI->get('checkoutshipping', 'insideformabovebuttons');
			  ?>
			  <div class="col-sm-12 col-lg-12"><div class="well clearfix">  <?php echo '<h4 style="color:#000000"><b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b></h4><br>' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></div></div>
							<div class="btn-set small-margin-top clearfix">
						  <button type="submit" class="pull-right btn btn-lg btn-primary">Continue</button>

					   </div>

						 <?php/* echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); */?>
  <?php
  //RCI below buttonsbullet
  echo $cre_RCI->get('checkoutshipping', 'insideformbelowbuttons');
  ?>
      </div>
     </div>
    </div>
        <div class="clearfix panel panel-default no-margin-bottom">
          <div class="panel-heading">
            <h3 class="no-margin-top no-margin-bottom">Payment Information</h3>
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
echo $cre_RCI->get('checkoutshipping', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>