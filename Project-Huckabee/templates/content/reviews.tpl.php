<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('reviews', 'top');
// RCI code eof
?>
<div class="row">
   <div class="col-sm-12 col-lg-12">
	  <h1 class="no-margin-top headertext"><?php echo HEADING_TITLE; ?></h1>
<?php
  $reviews_query_raw = "select r.reviews_id, left(rd.reviews_text, 100) as reviews_text, r.reviews_rating, r.date_added, p.products_id, pd.products_name, p.products_image, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCT_TO_WEBSITES . " pw where p.products_status = '1' and p.products_id = r.products_id and r.reviews_id = rd.reviews_id and p.products_id = pd.products_id and p.products_id = pw.products_id and pw.site_id = '".(int)WEBSITE_ID."' and pd.language_id = '" . (int)$languages_id . "' and rd.languages_id = '" . (int)$languages_id . "' order by r.reviews_id DESC";
  $reviews_split = new splitPageResults_rspv($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS);

  if ($reviews_split->number_of_rows > 0) {
    if ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3')) {
?>
      <div class="product-listing-module-pagination margin-bottom">
        <div class="pull-left large-margin-bottom page-results"><?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></div>
        <div class="pull-right large-margin-bottom no-margin-top">
          <ul class="pagination no-margin-top no-margin-bottom">
           <?php echo  $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?>
          </ul>

        </div>
      </div>
<div class="clearfix"></div>
<?php
    }
?>
<?php
    $reviews_query = tep_db_query($reviews_split->sql_query);
    while ($reviews = tep_db_fetch_array($reviews_query)) {
?>
<hr>

<div class="col-sm-8 col-lg-8 no-padding-left no-padding-right"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $reviews['products_id'] . '&amp;reviews_id=' . $reviews['reviews_id']) . '"><u><b>' . $reviews['products_name'] . '</b></u></a><br><span class="smallText">' . sprintf(TEXT_REVIEW_BY, tep_output_string_protected($reviews['customers_name'])) . '</span>'; ?></div>
<div class="col-sm-4 col-lg-4 no-padding-left no-padding-right"><?php echo sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($reviews['date_added'])); ?></div>
<div class="clearfix"></div>
<hr>

<div class="col-sm-4 col-lg-4 no-padding-left no-padding-right text-center"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $reviews['products_id'] . '&amp;reviews_id=' . $reviews['reviews_id']) . '">' . tep_image(DIR_WS_IMAGES . $reviews['products_image'], $reviews['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>'; ?></div>
<div class="col-sm-8 col-lg-8 no-padding-left no-padding-right text-center"><?php echo tep_break_string(tep_output_string_protected($reviews['reviews_text']), 60, '-<br>') . ((strlen($reviews['reviews_text']) >= 100) ? '..' : '') . '<br><br><i>' . sprintf(TEXT_REVIEW_RATING, tep_image(DIR_WS_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])), sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])) . '</i>'; ?></div>
<div class="clearfix"></div>

<?php
    }
?>
<?php
  } else {
?>

echo '<div class="col-sm-12 col-lg-12">' . TEXT_NO_REVIEWS .'</div>';
<?php
  }

// RCI code start
echo $cre_RCI->get('reviews', 'menu');
// RCI code eof
// BOF: Lango Added for template MOD
// EOF: Lango Added for template MOD
?>
  <div class="clearfix"></div>
  <div class="content-product-listing-div">

<?php
  if (($reviews_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <div class="product-listing-module-pagination margin-bottom">
        <div class="pull-left large-margin-bottom page-results"><?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></div>
        <div class="pull-right large-margin-bottom no-margin-top">
          <ul class="pagination no-margin-top no-margin-bottom">
           <?php echo  $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?>
          </ul>

        </div>
      </div>
<?php
  }
?>
</div>

 </div>
</div>

<?php
// RCI code start
echo $cre_RCI->get('reviews', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>