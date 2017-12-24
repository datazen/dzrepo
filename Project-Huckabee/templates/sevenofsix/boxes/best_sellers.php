<?php
/*
  $Id: best_sellers.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$best = new box_best_sellers();

if (count($best->rows) >= MIN_DISPLAY_BESTSELLERS) {
  ?>
  <!-- best_sellers //-->


    <div class="well" >
        <div class="box-header small-margin-bottom small-margin-left"><?php echo BOX_HEADING_BESTSELLERS; ?></div>
      <?php
      $rows = 0;
      foreach ($best->rows as $best_sellers) {
        $rows++;
        $bestsellers_list .= '<p>' . tep_row_number_format($rows) . '&nbsp<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best_sellers['products_id']) . '">' . $best_sellers['products_name'] . '</a></p>';
      }
       echo '<div style="margin-left:5px">' . $bestsellers_list .'</div>';
      ?>


  <script>
  $('.box-product-categories-ul-top').addClass('list-unstyled list-indent-large');
  $('.box-product-categories-ul').addClass('list-unstyled list-indent-large');
  </script>

    </div>



  <!-- best_sellers eof//-->
  <?php
}
?>