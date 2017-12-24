<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('productsnew', 'top');
// RCI code eof
?>
<div class="row">
<h1 class="no-margin-top"><?php echo HEADING_TITLE; ?></h1>
  <div id="content-product-listing-category-description-container">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
<?php
}else{
$header_text =  HEADING_TITLE;
}
?>
<?php
   // queries now in the root new_products.php

if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}


?>


        <?php

  //decide which product listing to use
   if (PRODUCT_LIST_CONTENT_LISTING == 'column'){
  $listing_method = FILENAME_PRODUCT_LISTING_COL;
  } else {
  $listing_method = FILENAME_PRODUCT_LISTING;
  }
  //Then show product listing
//  include(DIR_WS_MODULES . $listing_method);
         if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . $listing_method)) {
            require(TEMPLATE_FS_CUSTOM_MODULES . $listing_method);
        } else {
            require(DIR_WS_MODULES . $listing_method);
        }
?>


<?php
// RCI code start
echo $cre_RCI->get('productsnew', 'menu');
// RCI code eof
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>


<?php
?>
    </div>    </div>

<?php
// RCI code start
echo $cre_RCI->get('productsnew', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>