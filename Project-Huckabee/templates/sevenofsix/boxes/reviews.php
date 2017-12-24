<?php
/*
  $Id: reviews.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- reviews //-->
     <div class="well">
      <div class="box-header small-margin-bottom small-margin-left"><?php echo  BOX_HEADING_REVIEWS ; ?></div>
    <?php

$customer_group_array = array();
if(!isset($_SESSION['sppc_customer_group_id'])) {
  $customer_group_array[] = 'G';
} else {
  $customer_group_array[] = tep_get_customers_access_group($_SESSION['customer_id']);
}

    $random_select = "SELECT r.reviews_id, r.reviews_rating, p.products_id, p.products_image, pd.products_name
                        from " . TABLE_REVIEWS . " r,
                             " . TABLE_REVIEWS_DESCRIPTION . " rd,
                             " . TABLE_PRODUCTS . " p,
                             " . TABLE_PRODUCTS_DESCRIPTION . " pd
                      WHERE p.products_status = '1'
                        and p.products_id = r.products_id
                        and r.reviews_id = rd.reviews_id
                        and rd.languages_id = '" . (int)$languages_id . "'
                        and p.products_id = pd.products_id
                        and pd.language_id = '" . (int)$languages_id . "'";
    if (isset($_GET['products_id'])) {
      $random_select .= " and p.products_id = '" . (int)$_GET['products_id'] . "'";
    }

    $random_select .= " order by r.reviews_id desc limit " . MAX_RANDOM_SELECT_REVIEWS;
    $random_product = tep_random_select($random_select);
    if ($random_product) {
      // display random review box
      $review_query = tep_db_query("SELECT substring(reviews_text, 1, 60) as reviews_text
                                      from " . TABLE_REVIEWS_DESCRIPTION . "
                                    WHERE reviews_id = '" . (int)$random_product['reviews_id'] . "'
                                      and languages_id = '" . (int)$languages_id . "'");
      $review = tep_db_fetch_array($review_query);
      $review = tep_break_string(tep_output_string_protected($review['reviews_text']), 15, '-<br>');
      echo '<div align="center"><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&amp;reviews_id=' . $random_product['reviews_id']) . '">' . tep_image(DIR_WS_IMAGES . $random_product['products_image'], $random_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></div><br><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&amp;reviews_id=' . $random_product['reviews_id']) . '">' . $review . ' ..</a><br><br><div align="center">' . tep_image(DIR_WS_IMAGES . 'stars_' . $random_product['reviews_rating'] . '.gif' , sprintf(BOX_REVIEWS_TEXT_OF_5_STARS, $random_product['reviews_rating'])) . '</div></br>';
    } elseif (isset($_GET['products_id'])) {

        // display 'write a review' box
        if (DESIGN_BUTTON_REVIEWS == 'true') {
          echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . (int)$_GET['products_id']) . '">' . tep_image(DIR_WS_IMAGES . 'box_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . (int)$_GET['products_id']) . '">' . BOX_REVIEWS_WRITE_REVIEW .'</a>';
        } else {
          echo  '';
        }

    } else {
      // display 'no reviews' box
        echo BOX_REVIEWS_NO_REVIEWS ;
    }
    ?>
</div>




<tr>
  <td>

  </td>
</tr>
<!-- reviews eof//-->