<?php
/*
  $Id: categories4.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if ((defined('USE_CACHE') && USE_CACHE == 'true') && !defined('SID')) {
  echo tep_cache_categories_box4();
} else {
  $categories4 = new box_categories4();
  ?>
  <!-- categories4 //-->
  <div class="well" >
      <div class="box-header small-margin-bottom small-margin-left"><?php echo BOX_HEADING_CATEGORIES4;?></div>
      <?php
      $categories_string4 = $categories4->categories_string;
      // added for CDS CDpath support
      $params = (isset($_SESSION['CDpath'])) ? 'CDpath=' . $_SESSION['CDpath'] : '';
      //coment out the below lines if you do not want to have an all products list
      $categories_string4 .= "<hr>\n";
      $categories_string4 .= '<a href="' . tep_href_link(FILENAME_ALL_PRODS, $params) . '"><b>' . BOX_INFORMATION_ALLPRODS . "</b></a>\n";
      $categories_string4 .= "<br><hr>\n";
      $categories_string4 .= '<a href="' . tep_href_link(FILENAME_ALL_PRODCATS, $params) . '"><b>' . ALL_PRODUCTS_LINK . "</b></a>\n";
      $categories_string4 .= "<br><hr>\n";
      $categories_string4 .= '<a href="' . tep_href_link(FILENAME_ALL_PRODMANF, $params) . '"><b>' . ALL_PRODUCTS_MANF . "</b></a>\n";
      $categories_string4 .= "<br>\n";
      $info_box_contents = array();
      echo  '<div style="margin-left:5px">' . $categories_string4 . '</div>';

      ?>

<script>
$('.box-product-categories-ul-top').addClass('list-unstyled list-indent-large');
$('.box-product-categories-ul').addClass('list-unstyled list-indent-large');
</script>

  </div>
  <!-- categories4_eof //-->
  <?php
}
?>