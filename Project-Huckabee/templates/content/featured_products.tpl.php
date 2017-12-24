<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('featuredproducts', 'top');
// RCI code eof
?>
 <div class="row">
   <div class="col-sm-12 col-lg-12 large-padding-left margin-top">
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
	$header_text ='<h1 class="no-margin-top">' . HEADING_TITLE . '</h1>';
}
// EOF: Lango Added for template MOD
?>
<?php
   // queries now in the root featured_products.php
  //decide which product listing to use
   if (PRODUCT_LIST_CONTENT_LISTING == 'column'){
  $listing_method = FILENAME_PRODUCT_LISTING_COL;
  } else {
  $listing_method = FILENAME_PRODUCT_LISTING;
  }
  //Then show product listing
 // include(DIR_WS_MODULES . $listing_method);
         if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . $listing_method)) {
            require(TEMPLATE_FS_CUSTOM_MODULES . $listing_method);
        } else {
            require(DIR_WS_MODULES . $listing_method);
        }
// RCI code start
echo $cre_RCI->get('featuredproducts', 'menu');
// RCI code eof
?>
<?php
?>
    </div></div>
<?php
// RCI code start
echo $cre_RCI->get('featuredproducts', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>