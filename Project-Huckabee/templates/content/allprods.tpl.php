<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('allprods', 'top');
// RCI code eof
?>
<h1 class="no-margin-top"><?php echo HEADING_TITLE; ?></h1>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
      <tr>
        <td>
        <table border="0" width="100%" cellspacing="0" cellpadding="2" class="table table-striped table-hover">
        <tr class="productListing-heading">
        <td align="left" class="productListing-heading"><?php echo HEADING_TEXT_PRODUCT; ?></td>
       <td align="center" class="productListing-heading"><?php echo HEADING_TEXT_MODEL; ?></td>
       <td align="right" class="productListing-heading"><?php echo HEADING_TEXT_PRICE; ?>&nbsp;&nbsp;</td>
       </tr>
             <?php

          $products_query = tep_db_query("SELECT p.products_id, p.products_model, p.products_price, p.products_tax_class_id, pd.products_name FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd WHERE p.products_id = pd.products_id AND p.products_status = 1 AND pd.language_id = '" . (int)$languages_id . "' ORDER BY pd.products_name");
            $rows = 0;
              while($products1 = tep_db_fetch_array($products_query)) {
               $rows++;
                      if (($rows/2) == floor($rows/2)) {
                $row_col = '<tr class="productListing-even" >';
                 } else {
                 $row_col = '<tr class="productListing-odd" >';
                 }
                    $pf->loadProduct($products1['products_id'],$languages_id);
                  $all_price = $pf->getPriceStringShort();

                             $all_id       = $products1['products_id'] ;
                             $all_name     = $products1['products_name'] ;
                             $all_model    = $products1['products_model'] ;
                             $all_tax      = $products1['products_tax_class_id'] ;
                             $this_url     = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $all_id, 'NONSSL');

               echo $row_col ;
               echo "<td class='productListing-data' align='left'><a href='$this_url'>$all_name</a></td>";
               echo "<td class='productListing-data' align='center'><a href='$this_url'>$all_model</a></td>";
               echo "<td class='productListing-data' align='right'><a href='$this_url'>$all_price</a></td>";
               echo "</tr>\n";
            }

?>
            </td>
          </tr>
        </table></td>
      </tr>
<?php
// RCI code start
echo $cre_RCI->get('allprods', 'menu');
// RCI code eof
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                <td class="main" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>

   </table>
<?php
// RCI code start
echo $cre_RCI->get('global', 'bottom');
echo $cre_RCI->get('allprods', 'bottom');
// RCI code eof
?>