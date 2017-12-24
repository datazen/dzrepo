<?php
 /*
  $Id: index_default.php,v 1.0 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI top
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('indexdefault', 'top');
?>
<div class="row">
  <?php
  // added for show/hide customer greeting
  if (tep_not_null(INCLUDE_MODULE_ONE)) {
    echo '<div class="content-product-listing-div">';
    include($modules_folder . INCLUDE_MODULE_ONE);
    echo '<div style="clear:both"></div>';
    echo '</div>';
    ?>


    <?php
  }
  if (tep_not_null(INCLUDE_MODULE_TWO)) {
    echo '<div class="content-product-listing-div">';
    include($modules_folder . INCLUDE_MODULE_TWO);
    echo '<div style="clear:both"></div>';
    echo '</div>';
    ?>

    <?php
  }
  if (tep_not_null(INCLUDE_MODULE_THREE)) {
    echo '<div class="content-product-listing-div">';
   // echo  '<h3 class="no-margin-top">'. sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')) .'</h3>';
    include($modules_folder . INCLUDE_MODULE_THREE);
    echo '<div style="clear:both"></div>';
    echo '</div>';
    ?>


    <?php
  }
  if (tep_not_null(INCLUDE_MODULE_FOUR)) {
    echo '<div class="content-product-listing-div">';
    include($modules_folder . INCLUDE_MODULE_FOUR);
    echo '</div>';
    ?>
    <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
    <?php
  }
  if (tep_not_null(INCLUDE_MODULE_FIVE)) {
    echo '<div class="content-product-listing-div">';
    include($modules_folder . INCLUDE_MODULE_FIVE);
    echo '</div>';
    ?>
    <?php
  }
  if (tep_not_null(INCLUDE_MODULE_SIX)) {
    echo '<div class="content-product-listing-div">';
    include($modules_folder . INCLUDE_MODULE_SIX);
    echo '</div>';
  }
  ?>
 </div>
<?php
// RCI bottom
echo $cre_RCI->get('indexdefault', 'bottom');
echo $cre_RCI->get('global', 'bottom');
?>