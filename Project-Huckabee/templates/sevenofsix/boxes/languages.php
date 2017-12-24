<?php
/*
  $Id: languages.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- languages //-->
    <div class="well" >
        <div class="box-header small-margin-bottom small-margin-left"><?php echo BOX_HEADING_LANGUAGES; ?></div>

    <?php
    if (!isset($lng) || (isset($lng) && !is_object($lng))) {
      include(DIR_WS_CLASSES . FILENAME_LANGUAGE);
      $lng = new language;
    }
    $languages_string = '';
    reset($lng->catalog_languages);
    while (list($key, $value) = each($lng->catalog_languages)) {
      $languages_string .= ' <a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type) . '">' . tep_image(DIR_WS_LANGUAGES .  $value['directory'] . '/images/' . $value['image'], $value['name']) . '</a> ';
    }
    echo  '<div style="text-align:center">' . $languages_string .'</div>';
    ?>

  <script>
  $('.box-product-categories-ul-top').addClass('list-unstyled list-indent-large');
  $('.box-product-categories-ul').addClass('list-unstyled list-indent-large');
  </script>

    </div>

<!-- languages eof//-->