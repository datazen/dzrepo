<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('productreviewswrite', 'top');
// RCI code eof
echo tep_draw_form('product_reviews_write', tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'action=process&amp;products_id=' . $product_info['products_id']), 'post', 'onSubmit="return checkForm();"'); ?><table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" valign="top"><?php echo $products_name; ?></td>
            <td class="pageHeading" align="right" valign="top"><?php echo $products_price; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
}else{
$header_text = $products_name . '&nbsp;&nbsp;&nbsp;&nbsp;' . $products_price;
}
?>

<?php
  if ($messageStack->size('review') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('review'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo '<b>' . SUB_TITLE_FROM . '</b> ' . tep_output_string_protected($customer['customers_firstname'] . ' ' . $customer['customers_lastname']); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo SUB_TITLE_REVIEW; ?></b></td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                  <tr class="infoBoxContents">
                    <td><table border="0" width="100%" cellspacing="2" cellpadding="2">
                      <tr>
                        <td class="main"><?php echo tep_draw_textarea_field('review_text', 'soft', 60, 15); ?></td>
                      </tr>
                      <tr>
                        <td class="smallText" align="right"><?php echo TEXT_NO_HTML; ?></td>
                      </tr>
                      <tr>
                        <td class="main"><?php echo '<b>' . SUB_TITLE_RATING . '</b> ' . TEXT_BAD . ' ' . tep_draw_radio_field('rating', '1') . ' ' . tep_draw_radio_field('rating', '2') . ' ' . tep_draw_radio_field('rating', '3') . ' ' . tep_draw_radio_field('rating', '4') . ' ' . tep_draw_radio_field('rating', '5') . ' ' . TEXT_GOOD; ?></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
            <td width="<?php echo SMALL_IMAGE_WIDTH + 10; ?>" align="right" valign="top"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td align="center" class="smallText">
<?php
  if (tep_not_null($product_info['products_image'])) {
?>
<script language="javascript"><!--
document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info['products_id']) . '\\\')">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>');
//--></script>
<noscript>
<?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], $product_info['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') . '<br>' . TEXT_CLICK_TO_ENLARGE . '</a>'; ?>
</noscript>
<?php
  }
  
    echo '<p><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action','cPath','products_id')) . 'action=buy_now&products_id=' . $product_info['products_id'] . '&cPath=' . tep_get_product_path($product_info['products_id'])) . '">' . tep_template_image_button('button_in_cart.gif', IMAGE_BUTTON_IN_CART) . '</a></p>';
  
?>
                </td>
              </tr>
            </table>
          </td>
        </table></td>
          </tr>
      <!--  VISUAL VERIFY CODE start -->
  <?php 
      if (defined('VVC_SITE_ON_OFF') && VVC_SITE_ON_OFF == 'On') {
        if (defined('VVC_PRODUCT_REVIEWS_ON_OFF') && VVC_PRODUCT_REVIEWS_ON_OFF == 'On') {
    ?>

      <tr>
        <td class="main"><b><?php echo VISUAL_VERIFY_CODE_CATEGORY; ?></b></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><?php echo VISUAL_VERIFY_CODE_TEXT_INSTRUCTIONS; ?></td>
                <td class="main"><?php echo tep_draw_input_field('visual_verify_code') . '&nbsp;' . '<span class="inputRequirement">' . VISUAL_VERIFY_CODE_ENTRY_TEXT . '</span>'; ?></td>

                <td class="main">
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
                     echo('<img src="' . FILENAME_VISUAL_VERIFY_CODE_DISPLAY . '?vvc=' . $vvcode_oscsid . '" alt="' . VISUAL_VERIFY_CODE_CATEGORY . '">');
                  ?>
                </td>
                <td class="main"><?php echo VISUAL_VERIFY_CODE_BOX_IDENTIFIER; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>

  <?php
        }
      }
?>
<!-- VISUAL VERIFY CODE stop   -->
<?php
// RCI code start
echo $cre_RCI->get('productreviewswrite', 'menu');
// RCI code eof
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                  <tr class="infoBoxContents">
                    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('reviews_id', 'action'))) . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                        <td class="main" align="right"><?php echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
    </table></form>
<?php
// RCI code start
echo $cre_RCI->get('productreviewswrite', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>