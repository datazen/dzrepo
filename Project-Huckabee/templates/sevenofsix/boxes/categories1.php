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
$categories = new box_categories();
?>
  <!-- categories //-->
<?php/*    <div class="well">
    <div class="box-header small-margin-bottom small-margin-left">Categories</div>
  <ul class="box-product-categories-ul list-unstyled list-indent-large">
  <li><a access="" href="index.php?cPath=4">DVD Movies</a></li>
  <li><a access="" href="index.php?cPath=17">Gift Certificate</a></li>
  <li><a access="" href="index.php?cPath=1">Hardware</a></li>
  <li><a access="" href="index.php?cPath=11">Software</a></li>
  </ul>
  </div>
<tr>
    <td valign="top">
    <?php
      $info_box_contents = array();
      $info_box_contents[] = array('text'  => '<div class="box-header small-margin-bottom small-margin-left">' . BOX_HEADING_CATEGORIES . '</div>');
      new $infobox_template_heading($info_box_contents, '', ((isset($column_location) && $column_location !='') ? $column_location : '') );

      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left',
                                   'text'  => "\n" . '<table border="0" width="100%" cellspacing="0" cellpadding="0">'. "\n" . $categories->categories_string . '</table>'
                                  );
      new $infobox_template($info_box_contents, true, true, ((isset($column_location) && $column_location !='') ? $column_location : '') );

      if (TEMPLATE_INCLUDE_FOOTER =='true'){
        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left',
                                     'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                    );
        new $infobox_template_footer($info_box_contents, ((isset($column_location) && $column_location !='') ? $column_location : '') );
      }
      ?>
    </td>
  </tr>*/?>
  <!-- categories_eof //-->
  <div class="well" >
      <div class="box-header small-margin-bottom small-margin-left">Categories</div>
      <?php echo  $categories->categories_string ; ?>
<script>
$('.box-product-categories-ul-top').addClass('list-unstyled list-indent-large');
$('.box-product-categories-ul').addClass('list-unstyled list-indent-large');
</script>

  </div>
