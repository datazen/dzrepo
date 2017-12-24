<?php
/*
  $Id: cds_pages.php,v 1.2.0.0 2007/11/06 11:21:11 datazen Exp $

  CRE Loaded, Commercial Open Source E-Commerce
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('cdspages', 'top');
// RCI code eof
?>
<!-- cds_pages.tpl.php -->
 <div class="row">
   <div class="col-sm-12 col-lg-12 large-padding-left margin-top">

<h1><?php echo $heading_title; ?></h1>
 <div class="well no-padding-top">
  <?php

//echo '[' . $listing_columns . ']<br>';
  $displayed = false;
  if ( ($listing_columns != 1) && (!isset($_GET['pID'])) ) {
    $product_insert = (isset($product_string) && $product_string != '') ? $product_string : '';
    if (strip_tags($product_insert . $product_string) != '') {
      ?>

       <?php echo $product_insert . $descr . $display_string; ?>

      <?php
      $displayed = true;
    }
  }
//echo '[' . $displayed . ']<br>';

  if (strip_tags($display_string) != '') {
    ?>
            <?php
            if ($listing_columns == 1) {
              $product_insert = (isset($product_string) && $product_string != '') ? $product_string : '';
              if((strip_tags($descr) != '') || ($product_insert != '')) {
                echo '<div class="cds_category_description">'. $product_insert . $descr . '</div>';
              }
              echo '<div>'. $display_string . '</div>';
            } else {
              if (!$displayed) {
                echo '<div class="cds_category_description">'. $descr . $display_string .  '</div>';
              }
            }
            ?>


            <!-- ACF start -->
            <div class="cds_category_description">
              <?php
              if (isset($acf_file) && $acf_file != '') {
                @include_once($acf_file);
              }
              ?>
            </div>
            <!-- ACF eof -->
    <?php
  }
  ?>
</div></div></div>
<?php
// RCI code start
echo $cre_RCI->get('global', 'bottom');
echo $cre_RCI->get('cdspages', 'bottom');
// RCI code eof
?><!-- cds_pages.tpl.php-eof -->