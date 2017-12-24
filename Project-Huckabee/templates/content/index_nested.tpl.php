<?php
/*
  $Id: index_nested.tpl.php,v 1.2.0.0 2008/01/22 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('indexnested', 'top');
// RCI code eof
// added for CDS CDpath support
$params = (isset($_SESSION['CDpath'])) ? '&CDpath=' . $_SESSION['CDpath'] : '';
    // Get the category information
    $category_query = tep_db_query("select cd.categories_name, cd.categories_heading_title, cd.categories_description, c.categories_image from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . $languages_id . "'");
    $category = tep_db_fetch_array($category_query);

    if(tep_not_null($category['categories_heading_title'])){
        $heading_text = $category['categories_heading_title'];
    } else if(tep_not_null($category['categories_name'])){
        $heading_text = $category['categories_name'];
    } else {
        $heading_text = HEADING_TITLE;
    }
?>
  <!-- Bof content.index_nested.tpl.php-->
<div class="row">
<h1 class="no-margin-top"><?php echo $heading_text; ?></h1>
  <div id="content-product-listing-category-description-container">
    <?php if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (isset($category) && tep_not_null($category['categories_description'])) ) {  echo '<div id="content-product-listing-category-description">' . $category['categories_description'] . '</div>'; } ?>
   </div>
  <div class="clearfix"></div>
  <div class="content-product-listing-div">

<?php
    if (isset($cPath) && strpos('_', $cPath)) {
// check to see if there are deeper categories within the current category
      $category_links = array_reverse($cPath_array);
      for($i=0, $n=sizeof($category_links); $i<$n; $i++) {
        $categories_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");
        $categories = tep_db_fetch_array($categories_query);
        if ($categories['total'] < 1) {
          // do nothing, go through the loop
        } else {
          $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
          break; // we've found the deepest category the customer is in
        }
      }
    } else {
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
    }

    $number_of_categories = tep_db_num_rows($categories_query);

    $rows = 0;
    while ($categories = tep_db_fetch_array($categories_query)) {
      $rows++;
      $cPath_new = tep_get_path($categories['categories_id'] . $params);
      $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';
      echo '                      <div class="product-listing-module-container"><div class="product-listing-module-items"><div class="col-sm-4 col-lg-4 with-padding"><div class="thumbnail align-center large-padding-top" style="height: 220px;"><h3 style="line-height:1.1;"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . tep_image(DIR_WS_IMAGES . $categories['categories_image'], $categories['categories_name'], 200) . '<br>' . $categories['categories_name'] . '</a></h3></div></div></div></div>' . "\n";
      if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != $number_of_categories)) {
      }
    }

// needed for the new products module shown below
    $new_products_category_id = $current_category_id;
?>
 </div>  <div class="clearfix"></div>
<?php
           if ((INCLUDE_MODULE_ONE == 'new_products.php') ||
               (INCLUDE_MODULE_TWO == 'new_products.php') ||
               (INCLUDE_MODULE_THREE == 'new_products.php') ||
               (INCLUDE_MODULE_FOUR == 'new_products.php') ||
               (INCLUDE_MODULE_FIVE == 'new_products.php') ||
               (INCLUDE_MODULE_SIX == 'new_products.php') ) {

        if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_NEW_PRODUCTS)) {
            require(TEMPLATE_FS_CUSTOM_MODULES . FILENAME_NEW_PRODUCTS);
        } else {
            require(DIR_FS_TEMPLATE_MAINPAGES . FILENAME_NEW_PRODUCTS);
        }
     }?>

    </div>
<?php
// RCI code start
echo $cre_RCI->get('indexnested', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
 ?><!-- Eof content.index_nested.tpl.php-->