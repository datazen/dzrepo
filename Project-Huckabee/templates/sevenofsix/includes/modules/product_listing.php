<?php
/*
  $Id: product_listing_col.php,v 1.1.1.1 2004/03/04 23:41:11 ccwjr Exp $
*/
//declare variables and initialize
// added for CDS CDpath support
$params = (isset($_SESSION['CDpath'])) ? '&CDpath=' . $_SESSION['CDpath'] : '';
$row = 0;
$column = 0;

  $listing_split = new splitPageResults_rspv($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');

  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
?>
<!--product-listin-col -->
<?php
  }
  $list_box_contents = array();

  if ($listing_split->number_of_rows > 0) {
    $listing_query = tep_db_query($listing_split->sql_query);

    $row = 0;
    $column = 0;
    $no_of_listings = tep_db_num_rows($listing_query);

    while ($_listing = tep_db_fetch_array($listing_query)) {
      $listing[] = $_listing;
    }

	echo '<div class="product-listing-module-container">';
    for ($x = 0; $x < $no_of_listings; $x++) {
      $product_contents = array();
      for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
        $lc_align = '';
        $lc_text = '';
        switch ($column_list[$col]) {
          case 'PRODUCT_LIST_MODEL':
            $lc_align = '';
            $lc_text = '&nbsp;' . $listing[$x]['products_model'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_NAME':
            $lc_align = '';
            if (isset($_GET['manufacturers_id'])) {
              $lc_text = '<div class="product-listing-module-name"><h3 style="line-height:1.1;"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . (int)$_GET['manufacturers_id'] . '&amp;products_id=' . $listing[$x]['products_id'] . $params) . '">' . $listing[$x]['products_name'] . '</a></h3></div>';
            } else {
              $lc_text = '<div class="product-listing-module-name"><h3 style="line-height:1.1;"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&amp;' : '') . 'products_id=' . $listing[$x]['products_id'] . $params) . '">' . $listing[$x]['products_name'] . '</a></h3></div>';
            }
            break;
          case 'PRODUCT_LIST_MANUFACTURER':
            $lc_align = '';
            $lc_text = '<div class="product-listing-module-manufacturer><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing[$x]['manufacturers_id'] . $params) . '">' . $listing[$x]['manufacturers_name'] . '</a></div>;';
            break;
          case 'PRODUCT_LIST_PRICE':
            $pf->loadProduct($listing[$x]['products_id'],$languages_id);
            $lc_text ='<div class="row pricing-row"><div class="col-sm-6 col-lg-6"><p class="lead small-margin-bottom">'. $pf->getPriceStringShort() .'</p></div>';
            break;
          case 'PRODUCT_LIST_QUANTITY':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing[$x]['products_quantity'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_WEIGHT':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing[$x]['products_weight'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_IMAGE':
            $lc_align = 'center';
            if (isset($_GET['manufacturers_id'])) {
              $lc_text = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'manufacturers_id=' . (int)$_GET['manufacturers_id'] . '&amp;products_id=' . $listing[$x]['products_id'] . $params) . '">' . tep_image(DIR_WS_IMAGES . $listing[$x]['products_image'], $listing[$x]['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'style="height:'.SMALL_IMAGE_HEIGHT.'px"') . '</a>';
            } else {
              $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&amp;' : '') . 'products_id=' . $listing[$x]['products_id'] . $params) . '">' . tep_image(DIR_WS_IMAGES . $listing[$x]['products_image'], $listing[$x]['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'style="height:'.SMALL_IMAGE_HEIGHT.'px"') . '</a>&nbsp;';
            }
            break;
          case 'PRODUCT_LIST_DATE_EXPECTED':
              $duedate= str_replace("00:00:00", "" , $listing[$x]['products_date_available']);
                  $lc_align = 'center';
                  $lc_text = '&nbsp;' .  $duedate . '&nbsp;';
            break;
          case 'PRODUCT_LIST_BUY_NOW':
              $valid_to_checkout= true;
              if (STOCK_CHECK == 'true') {
                $stock_check = tep_check_stock((int)$listing[$x]['products_id'], 1);
                if (tep_not_null($stock_check) && (STOCK_ALLOW_CHECKOUT == 'false')) {
                  $valid_to_checkout = false;
                }
              }

              if ($valid_to_checkout == true) {

                $hide_add_to_cart = hide_add_to_cart();
                $lc_text = '';
                if ($hide_add_to_cart == 'false' && group_hide_show_prices() == 'true') {
                  $lc_text = '<div class="col-sm-6 col-lg-6 no-margin-left product-listing-module-buy-now buy-btn-div"><a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action','cPath','products_id')) . 'action=buy_now&amp;products_id=' . $listing[$x]['products_id'] . '&amp;cPath=' . tep_get_product_path($listing[$x]['products_id']) . $params) . '"><button class="product-listing-module-buy-now-button btn btn-success btn-block">' . IMAGE_BUTTON_BUY_NOW . '</button></a></div>';
                }
              }
              $lc_text .= '</div>';
              break;
        }
        $product_contents[] = $lc_text;
      }

      $lc_text = implode( $product_contents);

                  echo '<div class="product-listing-module-items"><div class="col-sm-4 col-lg-4 with-padding itembox"><div class="thumbnail align-center" style="padding-bottom:10px;padding-right:10px;">' . $lc_text . '</div></div></div>';

      $column ++;
      if ($column >= COLUMN_COUNT) {
        $row ++;
        $column = 0;
      }
    }
    echo '</div>';

    //new productListingBox($list_box_contents);
  } else {
    $list_box_contents = array();

                             echo      '<div class="product-listing-module-no-products"><div class="well"><p>'. TEXT_NO_PRODUCTS. '</p></div></div>';
 //   new productListingBox($list_box_contents);
  }?>
  <div class="clearfix"></div>
  <div class="content-product-listing-div">

<?php  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {?>
      <div class="product-listing-module-pagination margin-bottom">
        <div class="pull-left large-margin-bottom page-results"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></div>
        <div class="pull-right large-margin-bottom no-margin-top">
          <ul class="pagination no-margin-top no-margin-bottom">
           <?php echo  $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>

          </ul>
        </div>
      </div><div class="clear-both"></div>


<?php
  }
?>
</div>