<?php
/*
  $Id: article_listing.php, v1.0 2003/12/04 12:00:00 datazen Exp $

  CRE Loaded, Commercial Open Source E-Commerce
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// added for CDS CDpath support
$CDpath = (isset($_SESSION['CDpath'])) ? '&CDpath=' . $_SESSION['CDpath'] : '';
$listing_split = new splitPageResults_rspv($listing_sql, MAX_ARTICLES_PER_PAGE);
if (($listing_split->number_of_rows > 0) && ((ARTICLE_PREV_NEXT_BAR_LOCATION == 'top') || (ARTICLE_PREV_NEXT_BAR_LOCATION == 'both'))) {
  ?>
      <div class="product-listing-module-pagination margin-bottom">
        <div class="pull-left large-margin-bottom page-results"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_ARTICLES); ?></div>
        <div class="pull-right large-margin-bottom no-margin-top">
          <ul class="pagination no-margin-top no-margin-bottom">
          <?php echo  $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>

          </ul>
        </div>
      </div><div class="clear-both"></div>
  <?php
}
?>
<tr>
  <td>
<tr>
  <table border="0" width="100%" cellspacing="0" cellpadding="0">
		<?php
		if ($listing_split->number_of_rows > 0) {
		  $articles_listing_query = tep_db_query($listing_split->sql_query);
		  ?>
    <div class="main"><?php echo TEXT_ARTICLES; ?></div>
		  <?php
		  while ($articles_listing = tep_db_fetch_array($articles_listing_query)) {
			?>
    <div class=" bor_rad shadow">
					  <?php
					  echo '<div style="text-align:left;float:left;padding-right:10px;"><a href="' . tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id=' . $articles_listing['articles_id'] . $CDpath) . '"><b>' . $articles_listing['articles_name'] . '</b></a></div> ';
					  if (DISPLAY_AUTHOR_ARTICLE_LISTING == 'true' && tep_not_null($articles_listing['authors_name'])) {
					   echo '<div>' .TEXT_BY .   '<a href="' . tep_href_link(FILENAME_ARTICLES, 'authors_id=' . $articles_listing['authors_id'] . $CDpath) . '"> ' . $articles_listing['authors_name'] . '</a>';
					  }
					  ?>
			 <div style="float:right;">
					 <?php
						 if (DISPLAY_TOPIC_ARTICLE_LISTING == 'true' && tep_not_null($articles_listing['topics_name'])) {
					  ?>

					  <?php echo TEXT_TOPIC . '&nbsp;<a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $articles_listing['topics_id'] . $CDpath) . '">' . $articles_listing['topics_name'] . '</a>'; ?>
						<?php
					  }
					  ?>
			 </div>

			 <div style="clear:both;margin-left:20px;padding-top:5px;">
				<?php
				if (DISPLAY_ABSTRACT_ARTICLE_LISTING == 'true') {
				  ?>
				  <?php echo clean_html_comments(substr($articles_listing['articles_head_desc_tag'],0, MAX_ARTICLE_ABSTRACT_LENGTH)) . ((strlen($articles_listing['articles_head_desc_tag']) >= MAX_ARTICLE_ABSTRACT_LENGTH) ? '...' : ''); ?>
				  <?php
				}
				?>
			</div>

			<div style="clear:both;margin-left:20px;padding-top:5px;">
			  <?php
					 if (DISPLAY_DATE_ADDED_ARTICLE_LISTING == 'true') {
				  ?>
				  <?php echo TEXT_DATE_ADDED . ' ' . tep_date_long($articles_listing['articles_date_added']); ?>
				  <?php
				  }
				?>
		  </div>
   </div>
</div>

        <?php
        if (DISPLAY_ABSTRACT_ARTICLE_LISTING == 'true') {
          ?>
          <?php
        }
        if (DISPLAY_DATE_ADDED_ARTICLE_LISTING == 'true') {
          ?>
          <?php
        }
        if (DISPLAY_ABSTRACT_ARTICLE_LISTING == 'true' || DISPLAY_DATE_ADDED_ARTICLE_LISTING) {

          ?>
          <?php
        }
      } // End of listing loop
    } else {
      ?>
      <tr>
        <td class="main">
          <?php
          if ($topic_depth == 'articles') {
            echo TEXT_NO_ARTICLES;
          } else if (isset($_GET['authors_id'])) {
            echo  TEXT_NO_ARTICLES2;
          }
          ?>
        </td>
      </tr>
      <?php
    }
    ?>
  </table>

  </tr></td>
</tr>
<?php
if (($listing_split->number_of_rows > 0) && ((ARTICLE_PREV_NEXT_BAR_LOCATION == 'bottom') || (ARTICLE_PREV_NEXT_BAR_LOCATION == 'both'))) {
  ?>
  <tr>
    <td>
        <div class="product-listing-module-pagination margin-bottom large-padding-top">
        <div class="pull-left large-margin-bottom page-results"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_ARTICLES); ?></div>
        <div class="pull-right large-margin-bottom no-margin-top">
          <ul class="pagination no-margin-top no-margin-bottom">
          <?php echo  $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>

          </ul>
        </div>
      </div><div class="clear-both"></div>
</td>
  </tr>
  <?php
}
?>