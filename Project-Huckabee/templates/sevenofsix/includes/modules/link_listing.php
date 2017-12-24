<?php
/*
  $Id: link_listing.php,v 1.1.1.1 2004/03/04 23:41:10 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class linkListingBox extends tableBox {
    function linkListingBox($contents) {
      $this->table_parameters = 'class="linkListing"';
      $this->tableBox($contents, true);
    }
  }

  $listing_split = new splitPageResults_rspv($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'l.links_id');


?>

        <table class="table tabled-striped table-responsive no-margin-bottom" id="shopping-cart-table">
          <thead>
            <tr>
              <th><?php echo  TABLE_HEADING_LINKS_IMAGE;?></th>
              <th class="text-left "><?php echo  TABLE_HEADING_LINKS_TITLE;?></th>
              <th  class="text-right hide-on-mobile-portrait"><?php echo TABLE_HEADING_LINKS_DESCRIPTION; ?></th>
              <th class="text-center large-padding-left"><?php echo TABLE_HEADING_LINKS_URL; ?></th>
              <th class="text-right"><?php echo TABLE_HEADING_LINKS_COUNT; ?></th>
            </tr>
          </thead>
<?php

  $list_box_contents = array();

  for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
    switch ($column_list[$col]) {
      case 'LINK_LIST_TITLE':
        $lc_text = TABLE_HEADING_LINKS_TITLE;
        $lc_align = '';
        break;
      case 'LINK_LIST_URL':
        $lc_text = TABLE_HEADING_LINKS_URL;
        $lc_align = '';
        break;
      case 'LINK_LIST_IMAGE':
        $lc_text = TABLE_HEADING_LINKS_IMAGE;
        $lc_align = 'center';
        break;
      case 'LINK_LIST_DESCRIPTION':
        $lc_text = TABLE_HEADING_LINKS_DESCRIPTION;
        $lc_align = 'center';
        break;
      case 'LINK_LIST_COUNT':
        $lc_text = TABLE_HEADING_LINKS_COUNT;
        $lc_align = '';
        break;
    }

    if ($column_list[$col] != 'LINK_LIST_IMAGE') {
      $lc_text = tep_create_sort_heading($_GET['sort'], $col+1, $lc_text);
    }

    /*$list_box_contents[0][] = array('align' => $lc_align,
                                    'params' => 'class="linkListing-heading"',
                                    'text' => '&nbsp;' . $lc_text . '&nbsp;');*/?>
          <tbody>
<?php
  }

  if ($listing_split->number_of_rows > 0) {
    $rows = 0;
    $listing_query = tep_db_query($listing_split->sql_query);
    while ($listing = tep_db_fetch_array($listing_query)) {
      $rows++;

      if (($rows/2) == floor($rows/2)) {
        $list_box_contents[] = array('params' => 'class="linkListing-even"');
      } else {
        $list_box_contents[] = array('params' => 'class="linkListing-odd"');
      }

      $cur_row = sizeof($list_box_contents) - 1;
      echo  '<tr>';
      for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
        $lc_align = '';

        switch ($column_list[$col]) {
          case 'LINK_LIST_TITLE':
            $lc_text = '<td class="text-right  content-shopping-cart-price-td">' . $listing['links_title'] . '</td>';
            break;
          case 'LINK_LIST_URL':
            $lc_text = '<td class="text-right content-shopping-cart-qty-input-td "><a href="' . tep_get_links_url($listing['links_id']) . '" target="_blank">' . $listing['links_url'] . '</a></td>';
            break;
          case 'LINK_LIST_DESCRIPTION':
            $lc_text = '<td class="text-right hide-on-mobile-portrait">' . $listing['links_description'] . '</td>';
            break;
          case 'LINK_LIST_IMAGE':
            $lc_align = 'center';
            if (tep_not_null($listing['links_image_url'])) {
              $lc_text = '<td class="text-left content-shopping-cart-image-td"><a href="' . tep_get_links_url($listing['links_id']) . '" target="_blank">' . tep_links_image($listing['links_image_url'], $listing['links_title'], LINKS_IMAGE_WIDTH, LINKS_IMAGE_HEIGHT) . '</a></td>';
            } else {
              $lc_text = '<td class="text-left content-shopping-cart-image-td"><a href="' . tep_get_links_url($listing['links_id']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . 'no_picture.gif', $listing['links_title'], LINKS_IMAGE_WIDTH, LINKS_IMAGE_HEIGHT) . '</a></td>';
            }
            break;
          case 'LINK_LIST_COUNT':
            $lc_text = '<td class="text-right">' . $listing['links_clicked'] . '</td>';
            break;
        }

		  echo   $lc_text;
      }?>
     </tr>

<?php    }

   // new linkListingBox($list_box_contents);
  } else {

   echo '<div class="well large-margin-top">' . TEXT_NO_LINKS . '</div>';

   //new linkListingBox($list_box_contents);
  }?>

		<tfoot>
            <tr>
              <td></td>
              <td class="hide-on-mobile-portrait"></td>
              <td class="hide-on-mobile-portrait"></td>
              <td colspan="3"></td>

            </tr>
          </tfoot>
          </tbody>
		 </table>
<?php  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>

      <div class="product-listing-module-pagination margin-bottom">
        <div class="pull-left large-margin-bottom page-results"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_LINKS); ?></div>
        <div class="pull-right large-margin-bottom no-margin-top">
          <ul class="pagination no-margin-top no-margin-bottom">
		    <?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
          </ul>
        </div>
      </div>
      <div class="clear-both"></div>
<?php
  }
?>
