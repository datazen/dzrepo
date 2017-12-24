<?php
/*
  $Id: categories.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$categories = new box_categories_rspv();
?>
  <!-- categories_eof //-->
  <div class="well">
      <div class="box-header small-margin-bottom small-margin-left">Categories</div>
      <?php echo  $categories->categories_string ; ?>
<script>
$('.box-product-categories-ul-top').addClass('list-unstyled list-indent-large');
$('.box-product-categories-ul').addClass('list-unstyled list-indent-large');
</script>

  </div>
