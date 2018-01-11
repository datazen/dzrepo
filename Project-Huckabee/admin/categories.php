<?php
/*
  $Id: categories.php,v 1.2 2004/03/29 00:18:17 ccwjr Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
global $groupcnt;

require('includes/application_top.php');
require('includes/functions/categories_description.php');
require(DIR_WS_CLASSES . 'file_select.php');
require(DIR_WS_CLASSES . 'currencies.php');
require(DIR_WS_CLASSES . 'Specials.class.php');
$currencies = new currencies();

define('TEXT_PRO_UPSELL_POPOVER_BODY', 'Get Pro to unlock this feature and many more, plus get support and updates!');            
define('TEXT_PRO_UPSELL_GET_PRO', 'Get Pro');
define('TEXT_PRO_UPSELL_GET_PRO_URL', 'http://www.loadedcommerce.com');
define('TEXT_PRO_UPSELL_RIBBON_TEXT', 'Get Pro');

define('TEXT_B2B_UPSELL_POPOVER_BODY', 'Get B2B to unlock this feature and many more, plus get support and updates!');            
define('TEXT_B2B_UPSELL_GET_B2B', 'Get B2B');
define('TEXT_B2B_UPSELL_GET_B2B_URL', 'http://www.loadedcommerce.com');
define('TEXT_B2B_UPSELL_RIBBON_TEXT', 'Get B2B');

// RCI code start
echo $cre_RCI->get('global', 'top', false);
echo $cre_RCI->get('categories', 'top', false);
// RCI code eof

//intilize varibles
$groupcnt = 0;
$categories_id = '';
$Push = '';
$categories_image = '';
$category_template_id = '';
$categories_previous_image = '';
$extra_field = '';
$parent_name = '';

// array used by the DirSelect class
$ImageLocations['base_dir'] = DIR_FS_CATALOG_IMAGES;
$ImageLocations['base_url'] = DIR_WS_CATALOG_IMAGES;

// POST GET compatibility
if (isset($_GET['cID'])) {
  $cID = $_GET['cID'] ;
} else if (isset($_POST['cID'])) {
  $cID = $_POST['cID'] ;
} else {
  $cID = '' ;
}
if (isset($_GET['pID'])) {
  $pID = $_GET['pID'] ;
} else if (isset($_POST['pID'])) {
  $pID = $_POST['pID'] ;
} else {
  $pID = '' ;
}
if (isset($_GET['cPath'])) {
  $cPath = $_GET['cPath'] ;
} else if (isset($_POST['cPath'])) {
  $cPath = $_POST['cPath'] ;
} else {
  $cPath = '' ;
}
if (isset($_GET['action'])) {
  $action = $_GET['action'] ;
} else if (isset($_POST['action'])) {
  $action = $_POST['action'] ;
  } else {
  $action = '' ;
}
if (tep_not_null($action)) {
  switch ($action) {
    case 'setflag':
      if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
        if (isset($pID)) {
          tep_set_product_status($pID, $_GET['flag']);
        }
        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }
      }
      tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pID));
      break;

    case 'insert_category':
    case 'update_category':
      if (isset($_POST[IMAGE_BACK])) {
        $action = 'edit_category';
      } else {
        if (isset($_POST['categories_id'])) $categories_id = tep_db_prepare_input($_POST['categories_id']);
        if ($categories_id == '') {
          $categories_id = tep_db_prepare_input($_GET['cID']);
        }
        $sort_order = tep_db_prepare_input($_POST['sort_order']);
        $categories_image = tep_db_prepare_input($_POST['categories_image']);
        $sql_data_array = array('sort_order' => $sort_order);
        if ($action == 'insert_category') {
          $insert_sql_data = array('parent_id' => $current_category_id,
                                   'date_added' => 'now()');
          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
          tep_db_perform(TABLE_CATEGORIES, $sql_data_array);
          $categories_id = tep_db_insert_id();
        } elseif ($action == 'update_category') {
          $update_sql_data = array('last_modified' => 'now()');
          $sql_data_array = array_merge($sql_data_array, $update_sql_data);
          tep_db_perform(TABLE_CATEGORIES, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "'");
        }
        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $language_id = $languages[$i]['id'];
          $sql_data_array = array('categories_name' => tep_db_prepare_input(tep_db_encoder($_POST['categories_name'][$language_id])),
                                  'categories_heading_title' => tep_db_prepare_input(tep_db_encoder($_POST['categories_heading_title'][$language_id])),
                                  'categories_description' => tep_db_prepare_input(tep_db_encoder($_POST['categories_description'][$language_id])),
                                  'categories_head_title_tag' => tep_db_prepare_input(tep_db_encoder($_POST['categories_head_title_tag'][$language_id])),
                                  'categories_head_desc_tag' => tep_db_prepare_input(tep_db_encoder($_POST['categories_head_desc_tag'][$language_id])),
                                  'categories_head_keywords_tag' => tep_db_prepare_input(tep_db_encoder($_POST['categories_head_keywords_tag'][$language_id]))
                                  );

          if ($action == 'insert_category') {
            $insert_sql_data = array('categories_id' => $categories_id,
                                     'language_id' => $languages[$i]['id']);
            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
            tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array);
          } elseif ($action == 'update_category') {
            tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
          }
        }
        if ( ( (isset($_POST['unlink_cat_image'])) && ($_POST['unlink_cat_image'] == 'yes') ) ||
           ( (isset($_POST['delete_cat_image'])) && ($_POST['delete_cat_image'] == 'yes')) ) {
           tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '' where categories_id = '" . (int)$categories_id . "'");
        } else {
          if (isset($_POST['categories_image_name'])) {
            tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . tep_db_input($_POST['categories_image_name']) . "' where categories_id = '" . (int)$categories_id . "'");
          }
        }
        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
      }
      break;

    case 'delete_category_confirm':
      if (isset($_POST['categories_id'])) {
        $categories_id = tep_db_prepare_input($_POST['categories_id']);
        $categories = tep_get_category_tree($categories_id, '', '0', '', true);
        $products = array();
        $products_delete = array();
        for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
          $product_ids_query = tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$categories[$i]['id'] . "'");
          while ($product_ids = tep_db_fetch_array($product_ids_query)) {
            $products[$product_ids['products_id']]['categories'][] = $categories[$i]['id'];
          }
        }
        reset($products);
        while (list($key, $value) = each($products)) {
          $category_ids = '';
          for ($i=0, $n=sizeof($value['categories']); $i<$n; $i++) {
            $category_ids .= "'" . (int)$value['categories'][$i] . "', ";
          }
          $category_ids = substr($category_ids, 0, -2);
          $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$key . "' and categories_id not in (" . $category_ids . ")");
          $check = tep_db_fetch_array($check_query);
          if ($check['total'] < '1') {
            $products_delete[$key] = $key;
          }
        }
        tep_set_time_limit(0);
        for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
          tep_remove_category($categories[$i]['id']);
        }
        reset($products_delete);
        while (list($key) = each($products_delete)) {
          tep_remove_product($key);
        }
      }
      if (USE_CACHE == 'true') {
        tep_reset_cache_block('categories');
        tep_reset_cache_block('also_purchased');
      }
      tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
      break;

    case 'delete_product_confirm':
      if (isset($_POST['products_id']) && isset($_POST['product_categories']) && is_array($_POST['product_categories'])) {
        $product_id = tep_db_prepare_input($_POST['products_id']);
        $product_categories = $_POST['product_categories'];
        for ($i=0, $n=sizeof($product_categories); $i<$n; $i++) {
          tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "' and categories_id = '" . (int)$product_categories[$i] . "'");
        }
        $product_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");
        $product_categories = tep_db_fetch_array($product_categories_query);
        if ($product_categories['total'] == '0') {
          tep_remove_product($product_id);
        }
      }
      if (USE_CACHE == 'true') {
        tep_reset_cache_block('categories');
        tep_reset_cache_block('also_purchased');
      }
      tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
      break;

    case 'move_category_confirm':
      if (isset($_POST['categories_id']) && ($_POST['categories_id'] != $_POST['move_to_category_id'])) {
        $categories_id = tep_db_prepare_input($_POST['categories_id']);
        $new_parent_id = tep_db_prepare_input($_POST['move_to_category_id']);
        $path = explode('_', tep_get_generated_category_path_ids($new_parent_id));
        if (in_array($categories_id, $path)) {
          $messageStack->add_session('search', ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');
          tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
        } else {
          tep_db_query("update " . TABLE_CATEGORIES . " set parent_id = '" . (int)$new_parent_id . "', last_modified = now() where categories_id = '" . (int)$categories_id . "'");
          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
          }
          tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&cID=' . $categories_id));
        }
      }
      break;

    case 'move_product_confirm':
      $products_id = tep_db_prepare_input($_POST['products_id']);
      $new_parent_id = tep_db_prepare_input($_POST['move_to_category_id']);
      $duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$new_parent_id . "'");
      $duplicate_check = tep_db_fetch_array($duplicate_check_query);
      if ($duplicate_check['total'] < 1) tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . (int)$new_parent_id . "' where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$current_category_id . "'");
      if (USE_CACHE == 'true') {
        tep_reset_cache_block('categories');
        tep_reset_cache_block('also_purchased');
      }
      tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&pID=' . $products_id));
      break;

    case 'create_copy_product_attributes':
      $copy_to_products_id = (int)$_POST['copy_to_products_id'];
      tep_copy_products_attributes($pID,$copy_to_products_id);
      break;

    case 'create_copy_product_attributes_categories':
      $categories_products_copying_query= tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id='" . $cID . "'");
      while ( $categories_products_copying=tep_db_fetch_array($categories_products_copying_query) ) {
        // process all products in category
        tep_copy_products_attributes($make_copy_from_products_id,$categories_products_copying['products_id']);
      }
      break;

    case 'update_product':
      $languages = tep_get_languages();
      $products_id = (int)$_GET['pID'];
      // get the current product data so we can compare it later to see what has changed
      $products_old_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS . " WHERE products_id = " . $products_id);
      $products_old = tep_db_fetch_array($products_old_query);
      $products_description_old_query = tep_db_query("SELECT language_id, products_name, products_description, products_url, products_viewed,
                                                             products_head_title_tag, products_head_desc_tag, products_head_keywords_tag
                                                      FROM " . TABLE_PRODUCTS_DESCRIPTION . "
                                                      WHERE products_id = " . $products_id);
      $products_description_old = array();
      while ($description_old = tep_db_fetch_array($products_description_old_query)) {
        $products_description_old[$description_old['language_id']] = array('products_name' => $description_old['products_name'],
                                                                           'products_description' => $description_old['products_description'],
                                                                           'products_url' => $description_old['products_url'],
                                                                           'products_viewed' => $description_old['products_viewed'],
                                                                           'products_head_title_tag' => $description_old['products_head_title_tag'],
                                                                           'products_head_desc_tag' => $description_old['products_head_desc_tag'],
                                                                           'products_head_keywords_tag' => $description_old['products_head_keywords_tag']
                                                                          );
      }
      unset($products_old_query);
      unset($products_description_old_query);
      unset($description_old);

      $sql_data_array = array(); //declare the array and add to it anything changed
      $products_date_available = tep_db_prepare_input($_POST['products_date_available']);
      if ($products_date_available != $products_old['products_date_available']) $sql_data_array['products_date_available'] = $products_date_available;
      $products_quantity = tep_db_prepare_input($_POST['products_quantity']);
      if ($products_quantity != $products_old['products_quantity']) $sql_data_array['products_quantity'] = $products_quantity;
      $products_model = tep_db_prepare_input(tep_db_encoder($_POST['products_model']));
      if ($products_model != $products_old['products_model']) $sql_data_array['products_model'] = $products_model;
      $products_sku = tep_db_prepare_input(tep_db_encoder($_POST['products_sku']));
      if ($products_sku != $products_old['products_sku']) $sql_data_array['products_sku'] = $products_sku;
      $products_price = tep_db_prepare_input($_POST['products_price']);
      if ($products_price != $products_old['products_price']) $sql_data_array['products_price'] = $products_price;
      $products_weight = isset($_POST['products_weight']) ? tep_db_prepare_input($_POST['products_weight']) : 0;
      if ($products_weight != $products_old['products_weight']) $sql_data_array['products_weight'] = $products_weight;
      $products_status = isset($_POST['products_status']) ? tep_db_prepare_input($_POST['products_status']) : 0;
      if ($products_status != $products_old['products_status']) $sql_data_array['products_status'] = $products_status;
      $products_tax_class_id = isset($_POST['products_tax_class_id']) ? tep_db_prepare_input($_POST['products_tax_class_id']) : 0;
      if ($products_tax_class_id != $products_old['products_tax_class_id']) $sql_data_array['products_tax_class_id'] = $products_tax_class_id;
      $manufacturers_id = isset($_POST['manufacturers_id']) ? tep_db_prepare_input($_POST['manufacturers_id']) : 0;
      if ($manufacturers_id != $products_old['manufacturers_id']) $sql_data_array['manufacturers_id'] = $manufacturers_id;

      $images = array(array('table' => 'products_image', 'delete' => 'delete_image', 'unlink' => 'unlink_image', 'dir' => 'products_image_destination'),
                      array('table' => 'products_image_med', 'delete' => 'delete_image_med', 'unlink' => 'unlink_image_med', 'dir' => 'products_image_med_destination'),
                      array('table' => 'products_image_lrg', 'delete' => 'delete_image_lrg', 'unlink' => 'unlink_image_lrg', 'dir' => 'products_image_lrg_destination'),
                      array('table' => 'products_image_sm_1', 'delete' => 'delete_image_sm_1', 'unlink' => 'unlink_image_sm_1', 'dir' => 'products_image_sm_1_destination'),
                      array('table' => 'products_image_xl_1', 'delete' => 'delete_image_xl_1', 'unlink' => 'unlink_image_xl_1', 'dir' => 'products_image_xl_1_destination'),
                      array('table' => 'products_image_sm_2', 'delete' => 'delete_image_sm_2', 'unlink' => 'unlink_image_sm_2', 'dir' => 'products_image_sm_2_destination'),
                      array('table' => 'products_image_xl_2', 'delete' => 'delete_image_xl_2', 'unlink' => 'unlink_image_xl_2', 'dir' => 'products_image_xl_2_destination'),
                      array('table' => 'products_image_sm_3', 'delete' => 'delete_image_sm_3', 'unlink' => 'unlink_image_sm_3', 'dir' => 'products_image_sm_3_destination'),
                      array('table' => 'products_image_xl_3', 'delete' => 'delete_image_xl_3', 'unlink' => 'unlink_image_xl_3', 'dir' => 'products_image_xl_3_destination'),
                      array('table' => 'products_image_sm_4', 'delete' => 'delete_image_sm_4', 'unlink' => 'unlink_image_sm_4', 'dir' => 'products_image_sm_4_destination'),
                      array('table' => 'products_image_xl_4', 'delete' => 'delete_image_xl_4', 'unlink' => 'unlink_image_xl_4', 'dir' => 'products_image_xl_4_destination'),
                      array('table' => 'products_image_sm_5', 'delete' => 'delete_image_sm_5', 'unlink' => 'unlink_image_sm_5', 'dir' => 'products_image_sm_5_destination'),
                      array('table' => 'products_image_xl_5', 'delete' => 'delete_image_xl_5', 'unlink' => 'unlink_image_xl_5', 'dir' => 'products_image_xl_5_destination'),
                      array('table' => 'products_image_sm_6', 'delete' => 'delete_image_sm_6', 'unlink' => 'unlink_image_sm_6', 'dir' => 'products_image_sm_6_destination'),
                      array('table' => 'products_image_xl_6', 'delete' => 'delete_image_xl_6', 'unlink' => 'unlink_image_xl_6', 'dir' => 'products_image_xl_6_destination')
                     );
      foreach ($images as $image) {
        if (isset($_POST[$image['delete']]) && $_POST[$image['delete']] == 'yes' && $products_old[$image['table']] != '') {
          unlink(DIR_FS_CATALOG_IMAGES . $products_old[$image['table']]);
          $sql_data_array[$image['table']] = '';
        } elseif (isset($_POST[$image['unlink']]) && $_POST[$image['unlink']] == 'yes' && $products_old[$image['table']] != '') {
          $sql_data_array[$image['table']] = '';
        } elseif (isset($_FILES[$image['table']]) && tep_not_null($_FILES[$image['table']]['name'])) {
          if (strtolower($_FILES[$image['table']]['name']) != 'none') {
            $uploadFile = DIR_FS_CATALOG_IMAGES . urldecode($_POST[$image['dir']]) . $_FILES[$image['table']]['name'];
            @move_uploaded_file($_FILES[$image['table']]['tmp_name'], $uploadFile);
            if ($_FILES[$image['table']]['name'] != $products_old[$image['table']]) $sql_data_array[$image['table']] = tep_db_prepare_input(urldecode($_POST[$image['dir']]) . $_FILES[$image['table']]['name']);
          } elseif ($products_old[$image['table']] != '') {
            $sql_data_array[$image['table']] = '';
          }
        } elseif (isset($_POST[$image['table']]) && tep_not_null($_POST[$image['table']])) {
          if (strtolower($_POST[$image['table']]) != 'none') {
            if ($_POST[$image['table']] != $products_old[$image['table']]) $sql_data_array[$image['table']] = tep_db_prepare_input($_POST[$image['table']]);
          } elseif ($products_old[$image['table']] != '') {
            $sql_data_array[$image['table']] = '';
          }
        }
      }

      // check to see if there is anything to actually update in the products table
      if (count($sql_data_array) > 0 ) {
        $sql_data_array['products_last_modified'] = 'now()';
        tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', 'products_id = ' . (int)$products_id);
      }

        // process the products description table data
        $products_description_parent = array(); // save the name and description for later use in processing sub products
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $language_id = $languages[$i]['id'];
          $products_description_parent[$language_id] = array('products_name' => tep_db_prepare_input(tep_db_encoder($_POST['products_name'][$language_id])),
                                                             'products_description' => tep_db_prepare_input(tep_db_encoder($_POST['products_description'][$language_id]))
                                                             );
          if (!isset($products_description_old[$language_id])) {
            $_POST['products_url'][$language_id] = urldecode($_POST['products_url'][$language_id]);
            if (substr($_POST['products_url'][$language_id], 0, 7) == 'http://') $_POST['products_url'][$language_id] = substr($_POST['products_url'][$language_id], 7);
            $sql_data_array = array('products_name' => tep_db_prepare_input(tep_db_encoder($_POST['products_name'][$language_id])),
                                    'products_description' => tep_db_prepare_input(tep_db_encoder($_POST['products_description'][$language_id])),
                                    'products_url' => tep_db_prepare_input($_POST['products_url'][$language_id]),
                                    'products_head_title_tag' => tep_db_prepare_input(tep_db_encoder($_POST['products_head_title_tag'][$language_id])),
                                    'products_head_desc_tag' => tep_db_prepare_input(tep_db_encoder($_POST['products_head_desc_tag'][$language_id])),
                                    'products_head_keywords_tag' => tep_db_prepare_input(tep_db_encoder($_POST['products_head_keywords_tag'][$language_id]))
                                   );
          } else {
            $sql_data_array = array(); //declare the array and add to it anything changed
            $products_name = tep_db_prepare_input(tep_db_encoder($_POST['products_name'][$language_id]));
            if ($products_description_old[$language_id]['products_name'] != $products_name) {
              $sql_data_array['products_name'] = tep_db_encoder($products_name);
            }
            $products_description = tep_db_prepare_input(tep_db_encoder($_POST['products_description'][$language_id]));
            if ($products_description_old[$language_id]['products_description'] != $products_description) $sql_data_array['products_description'] = tep_db_encoder($products_description);
            $_POST['products_url'][$language_id] = urldecode($_POST['products_url'][$language_id]);
            if (substr($_POST['products_url'][$language_id], 0, 7) == 'http://') $_POST['products_url'][$language_id] = substr($_POST['products_url'][$language_id], 7);
            $products_url = tep_db_prepare_input($_POST['products_url'][$language_id]);
            if ($products_description_old[$language_id]['products_url'] != $products_url) $sql_data_array['products_url'] = $products_url;
            $products_head_title_tag = tep_db_prepare_input(tep_db_encoder($_POST['products_head_title_tag'][$language_id]));
            if ($products_description_old[$language_id]['products_head_title_tag'] != $products_head_title_tag) $sql_data_array['products_head_title_tag'] = $products_head_title_tag;
            $products_head_desc_tag = tep_db_prepare_input(tep_db_encoder($_POST['products_head_desc_tag'][$language_id]));
            if ($products_description_old[$language_id]['products_head_desc_tag'] != $products_head_desc_tag) $sql_data_array['products_head_desc_tag'] = $products_head_desc_tag;
            $products_head_keywords_tag = tep_db_prepare_input(tep_db_encoder($_POST['products_head_keywords_tag'][$language_id]));
            if ($products_description_old[$language_id]['products_head_keywords_tag'] != $products_head_keywords_tag) $sql_data_array['products_head_keywords_tag'] = $products_head_keywords_tag;
          }

          // check to see if there is anything to actually update in the products table
          if (count($sql_data_array) > 0 ) {
            tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', 'products_id = ' . (int)$products_id . ' and language_id = ' . (int)$language_id);
          }
        }

        /////////////////////////////////////////////////////////////////
        // BOF: Eversun Added: Update Product Attributes and Sort Order
        // Update the changes to the attributes if any changes were made
        $rows = 0;
        $options_query = tep_db_query("select po.products_options_id, pot.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_OPTIONS_TEXT . " pot where pot.language_id = '" . $languages_id . "' and po.products_options_id = pot.products_options_text_id order by po.products_options_sort_order, pot.products_options_name");
        while ($options = tep_db_fetch_array($options_query)) {
          $values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id and p2p.products_options_id = '" . $options['products_options_id'] . "' and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
          while ($values = tep_db_fetch_array($values_query)) {
            $rows ++;
            $attributes_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix, products_options_sort_order from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_id . "' and options_id = '" . $options['products_options_id'] . "' and options_values_id = '" . $values['products_options_values_id'] . "'");
            if (tep_db_num_rows($attributes_query) > 0) {
              $attributes = tep_db_fetch_array($attributes_query);
              if (isset($_POST['option'][$rows])) {
                if ( ($_POST['prefix'][$rows] <> $attributes['price_prefix']) || ($_POST['price'][$rows] <> $attributes['options_values_price']) || ($_POST['products_options_sort_order'][$rows] <> $attributes['products_options_sort_order']) ) {
                  tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set options_values_price = '" . $_POST['price'][$rows] . "', price_prefix = '" . $_POST['prefix'][$rows] . "', products_options_sort_order = '" . $_POST['products_options_sort_order'][$rows] . "'  where products_attributes_id = '" . $attributes['products_attributes_id'] . "'");
                }
              } else {
                tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . $attributes['products_attributes_id'] . "'");
              }
            } elseif (isset($_POST['option'][$rows])) {
              tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values ('', '" . $products_id . "', '" . $options['products_options_id'] . "', '" . $values['products_options_values_id'] . "', '" . $_POST['price'][$rows] . "', '" . $_POST['prefix'][$rows] . "', '" . $_POST['products_options_sort_order'][$rows] . "')");
            }
          }
        }
        // EOF: Eversun Added: Update Product Attributes and Sort Order
        /////////////////////////////////////////////////////////////////////

      if (USE_CACHE == 'true') {
        tep_reset_cache_block('categories');
        tep_reset_cache_block('also_purchased');
      }

      if (isset($_SESSION['is_std']) && $_SESSION['is_std'] === true) {
        tep_redirect(tep_href_link(FILENAME_GET_LOADED, 'page=product&cPath=' . $cPath . '&pID=' . $products_id));
      } else {
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_old['products_id']));
      }
      break;

    case 'insert_product':
      if (isset($_POST[IMAGE_BACK])) {
        $action = 'new_product';
      } else {
        $languages = tep_get_languages();

        $products_date_available = tep_db_prepare_input($_POST['products_date_available']);
        $products_quantity = tep_db_prepare_input($_POST['products_quantity']);
        $products_model = tep_db_prepare_input(tep_db_encoder($_POST['products_model']));
        $products_sku = tep_db_prepare_input(tep_db_encoder($_POST['products_sku']));
        $products_price = tep_db_prepare_input($_POST['products_price']);
        $products_weight = isset($_POST['products_weight']) ? tep_db_prepare_input($_POST['products_weight']) : 0;
        $products_status = isset($_POST['products_status']) ? tep_db_prepare_input($_POST['products_status']) : 0;
        $products_tax_class_id = isset($_POST['products_tax_class_id']) ? tep_db_prepare_input($_POST['products_tax_class_id']) : 0;
        $manufacturers_id = isset($_POST['manufacturers_id']) ? tep_db_prepare_input($_POST['manufacturers_id']) : 0;
        $sql_data_array = array('products_date_available' => $products_date_available,
                                'products_quantity' => $products_quantity,
                                'products_model' => $products_model,
                                'products_sku' => $products_sku,
                                'products_price' => $products_price,
                                'products_weight' => $products_weight,
                                'products_status' => $products_status,
                                'products_tax_class_id' => $products_tax_class_id,
                                'manufacturers_id' => $manufacturers_id
                               );

        $images = array(array('table' => 'products_image', 'delete' => 'delete_image', 'unlink' => 'unlink_image', 'dir' => 'products_image_destination'),
                        array('table' => 'products_image_med', 'delete' => 'delete_image_med', 'unlink' => 'unlink_image_med', 'dir' => 'products_image_med_destination'),
                        array('table' => 'products_image_lrg', 'delete' => 'delete_image_lrg', 'unlink' => 'unlink_image_lrg', 'dir' => 'products_image_lrg_destination'),
                        array('table' => 'products_image_sm_1', 'delete' => 'delete_image_sm_1', 'unlink' => 'unlink_image_sm_1', 'dir' => 'products_image_sm_1_destination'),
                        array('table' => 'products_image_xl_1', 'delete' => 'delete_image_xl_1', 'unlink' => 'unlink_image_xl_1', 'dir' => 'products_image_xl_1_destination'),
                        array('table' => 'products_image_sm_2', 'delete' => 'delete_image_sm_2', 'unlink' => 'unlink_image_sm_2', 'dir' => 'products_image_sm_2_destination'),
                        array('table' => 'products_image_xl_2', 'delete' => 'delete_image_xl_2', 'unlink' => 'unlink_image_xl_2', 'dir' => 'products_image_xl_2_destination'),
                        array('table' => 'products_image_sm_3', 'delete' => 'delete_image_sm_3', 'unlink' => 'unlink_image_sm_3', 'dir' => 'products_image_sm_3_destination'),
                        array('table' => 'products_image_xl_3', 'delete' => 'delete_image_xl_3', 'unlink' => 'unlink_image_xl_3', 'dir' => 'products_image_xl_3_destination'),
                        array('table' => 'products_image_sm_4', 'delete' => 'delete_image_sm_4', 'unlink' => 'unlink_image_sm_4', 'dir' => 'products_image_sm_4_destination'),
                        array('table' => 'products_image_xl_4', 'delete' => 'delete_image_xl_4', 'unlink' => 'unlink_image_xl_4', 'dir' => 'products_image_xl_4_destination'),
                        array('table' => 'products_image_sm_5', 'delete' => 'delete_image_sm_5', 'unlink' => 'unlink_image_sm_5', 'dir' => 'products_image_sm_5_destination'),
                        array('table' => 'products_image_xl_5', 'delete' => 'delete_image_xl_5', 'unlink' => 'unlink_image_xl_5', 'dir' => 'products_image_xl_5_destination'),
                        array('table' => 'products_image_sm_6', 'delete' => 'delete_image_sm_6', 'unlink' => 'unlink_image_sm_6', 'dir' => 'products_image_sm_6_destination'),
                        array('table' => 'products_image_xl_6', 'delete' => 'delete_image_xl_6', 'unlink' => 'unlink_image_xl_6', 'dir' => 'products_image_xl_6_destination')
                       );

        foreach ($images as $image) {
          if (isset($_POST[$image['delete']]) && $_POST[$image['delete']] == 'yes' && $products_old[$image['table']] != '') {
            unlink(DIR_FS_CATALOG_IMAGES . $products_old[$image['table']]);
            $sql_data_array[$image['table']] = '';
          } elseif (isset($_POST[$image['unlink']]) && $_POST[$image['unlink']] == 'yes' && $products_old[$image['table']] != '') {
            $sql_data_array[$image['table']] = '';
          } elseif (isset($_FILES[$image['table']]) && tep_not_null($_FILES[$image['table']]['name'])) {
            if (strtolower($_FILES[$image['table']]['name']) != 'none') {
              $uploadFile = DIR_FS_CATALOG_IMAGES . urldecode($_POST[$image['dir']]) . $_FILES[$image['table']]['name'];
              @move_uploaded_file($_FILES[$image['table']]['tmp_name'], $uploadFile);
              $sql_data_array[$image['table']] = tep_db_prepare_input(urldecode($_POST[$image['dir']]) . $_FILES[$image['table']]['name']);
            } elseif ($products_old[$image['table']] != '') {
              $sql_data_array[$image['table']] = '';
            }
          } elseif (isset($_POST[$image['table']]) && tep_not_null($_POST[$image['table']])) {
            if (strtolower($_POST[$image['table']]) != 'none') {
              $sql_data_array[$image['table']] = tep_db_prepare_input($_POST[$image['table']]);
            }
          }
        }

        $sql_data_array['products_date_added'] = 'now()';
        tep_db_perform(TABLE_PRODUCTS, $sql_data_array);
        $products_id = tep_db_insert_id();

        // process the products description table data
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $language_id = $languages[$i]['id'];
          //if ($_POST['products_name'][$language_id] != '') {
            $_POST['products_url'][$language_id] = urldecode($_POST['products_url'][$language_id]);
            if (substr($_POST['products_url'][$language_id], 0, 7) == 'http://') $_POST['products_url'][$language_id] = substr($_POST['products_url'][$language_id], 7);
            $sql_data_array = array('products_name' => tep_db_prepare_input(tep_db_encoder($_POST['products_name'][$language_id])),
                                  'products_description' => tep_db_prepare_input(tep_db_encoder($_POST['products_description'][$language_id])),
                                  'products_url' => tep_db_prepare_input($_POST['products_url'][$language_id]),
                                  'products_head_title_tag' => tep_db_prepare_input(tep_db_encoder($_POST['products_head_title_tag'][$language_id])),
                                  'products_head_desc_tag' => tep_db_prepare_input(tep_db_encoder($_POST['products_head_desc_tag'][$language_id])),
                                  'products_head_keywords_tag' => tep_db_prepare_input(tep_db_encoder($_POST['products_head_keywords_tag'][$language_id])),
                                  'products_id' => $products_id,
                                  'language_id' => $language_id
                                 );
          tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
          //}
        }

        // add it to the cirrent category
        tep_db_query("INSERT INTO " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) VALUES (" . (int)$products_id . ", " . (int)$current_category_id . ")");

        $rows = 0;
        $options_query = tep_db_query("select po.products_options_id, pot.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_OPTIONS_TEXT . " pot where pot.language_id = '" . $languages_id . "' and po.products_options_id = pot.products_options_text_id order by po.products_options_sort_order, pot.products_options_name");
        while ($options = tep_db_fetch_array($options_query)) {
          $values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id and p2p.products_options_id = '" . $options['products_options_id'] . "' and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
          while ($values = tep_db_fetch_array($values_query)) {
            $rows ++;
            $attributes_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix, products_options_sort_order from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_id . "' and options_id = '" . $options['products_options_id'] . "' and options_values_id = '" . $values['products_options_values_id'] . "'");
            if (tep_db_num_rows($attributes_query) > 0) {
              $attributes = tep_db_fetch_array($attributes_query);
               if (isset($_POST['option'][$rows])) {
                  if ( ($_POST['prefix'][$rows] <> $attributes['price_prefix']) || ($_POST['price'][$rows] <> $attributes['options_values_price']) || ($_POST['products_options_sort_order'][$rows] <> $attributes['products_options_sort_order']) ) {
                    tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set options_values_price = '" . $_POST['price'][$rows] . "', price_prefix = '" . $_POST['prefix'][$rows] . "', products_options_sort_order = '" . $_POST['products_options_sort_order'][$rows] . "' where products_attributes_id = '" . $attributes['products_attributes_id'] . "'");
                  }
                } else {
                  tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . $attributes['products_attributes_id'] . "'");
                }

              } elseif (isset($_POST['option'][$rows])) {
                tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values ('', '" . $products_id . "', '" . $options['products_options_id'] . "', '" . $values['products_options_values_id'] . "', '" . $_POST['price'][$rows] . "', '" . $_POST['prefix'][$rows] . "', '" . $_POST['products_options_sort_order'][$rows] . "')");
              }
            }
          }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }
        if (isset($_SESSION['is_std']) && $_SESSION['is_std'] === true) {
          tep_redirect(tep_href_link(FILENAME_GET_LOADED, 'page=product&cPath=' . $cPath . '&pID=' . $products_id));
        } else {
          tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id));
        }
      }
      break;

    case 'copy_to_confirm':
      if (isset($_POST['products_id']) && isset($_POST['categories_id'])) {
        $products_id = tep_db_prepare_input($_POST['products_id']);
        $categories_id = tep_db_prepare_input($_POST['categories_id']);
        if ($_POST['copy_as'] == 'link') {
          if ($categories_id != $current_category_id) {
            $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$categories_id . "'");
            $check = tep_db_fetch_array($check_query);
            if ($check['total'] < '1') {
              tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$categories_id . "')");
            }
          } else {
            $messageStack->add_session('search', ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
          }
        } elseif ($_POST['copy_as'] == 'duplicate') {
          $product_query = tep_db_query("select products_quantity, products_model, products_sku, products_image, products_image_med, products_image_lrg, products_image_sm_1, products_image_xl_1, products_image_sm_2, products_image_xl_2, products_image_sm_3, products_image_xl_3, products_image_sm_4, products_image_xl_4, products_image_sm_5, products_image_xl_5, products_image_sm_6, products_image_xl_6, products_price, products_price1, products_price2, products_price3, products_price4, products_price5, products_price6, products_price7, products_price8, products_price9, products_price10, products_price11, products_price1_qty, products_price2_qty, products_price3_qty, products_price4_qty, products_price5_qty, products_price6_qty, products_price7_qty, products_price8_qty, products_price9_qty, products_price10_qty, products_price11_qty, products_qty_blocks, products_date_available, products_weight, products_tax_class_id, manufacturers_id from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
          $product = tep_db_fetch_array($product_query);
          tep_db_query("insert into " . TABLE_PRODUCTS . " (products_quantity, products_model, products_sku, products_image, products_image_med, products_image_lrg, products_image_sm_1, products_image_xl_1, products_image_sm_2, products_image_xl_2, products_image_sm_3, products_image_xl_3, products_image_sm_4, products_image_xl_4, products_image_sm_5, products_image_xl_5, products_image_sm_6, products_image_xl_6, products_price, products_price1, products_price2, products_price3, products_price4, products_price5, products_price6, products_price7, products_price8, products_price9, products_price10, products_price11, products_price1_qty, products_price2_qty, products_price3_qty, products_price4_qty, products_price5_qty, products_price6_qty, products_price7_qty, products_price8_qty, products_price9_qty, products_price10_qty, products_price11_qty, products_qty_blocks, products_date_added, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id) values
                      ('" . tep_db_input($product['products_quantity']) . "', '" . tep_db_input($product['products_model']) . "', '" . tep_db_input($product['products_sku']) . "', '" . tep_db_input($product['products_image']) . "', '" . tep_db_input($product['products_image_med']) . "', '" . tep_db_input($product['products_image_lrg']) . "', '" . tep_db_input($product['products_image_sm_1']) . "', '" . tep_db_input($product['products_image_xl_1']) . "', '" . tep_db_input($product['products_image_sm_2']) . "', '" . tep_db_input($product['products_image_xl_2']) . "', '" . tep_db_input($product['products_image_sm_3']) . "', '" . tep_db_input($product['products_image_xl_3']) . "', '" . tep_db_input($product['products_image_sm_4']) . "', '" . tep_db_input($product['products_image_xl_4']) . "', '" . tep_db_input($product['products_image_sm_5']) . "', '" . tep_db_input($product['products_image_xl_5']) . "', '" . tep_db_input($product['products_image_sm_6']) . "', '" . tep_db_input($product['products_image_xl_6']) . "', '" . tep_db_input($product['products_price']) . "',
                       '" . tep_db_input($product['products_price1']) . "', '" . tep_db_input($product['products_price2']) . "', '" . tep_db_input($product['products_price3']) . "', '" . tep_db_input($product['products_price4']) . "', '" . tep_db_input($product['products_price5']) . "', '" . tep_db_input($product['products_price6']) . "', '" . tep_db_input($product['products_price7']) . "', '" . tep_db_input($product['products_price8']) . "', '" . tep_db_input($product['products_price9']) . "', '" . tep_db_input($product['products_price10']) . "', '" . tep_db_input($product['products_price11']) . "', '" . tep_db_input($product['products_price1_qty']) . "', '" . tep_db_input($product['products_price2_qty']) . "', '" . tep_db_input($product['products_price3_qty']) . "', '" . tep_db_input($product['products_price4_qty']) . "', '" . tep_db_input($product['products_price5_qty']) . "', '" . tep_db_input($product['products_price6_qty']) . "', '" . tep_db_input($product['products_price7_qty']) . "', '" . tep_db_input($product['products_price8_qty']) . "', '" . tep_db_input($product['products_price9_qty']) . "', '" . tep_db_input($product['products_price10_qty']) . "', '" . tep_db_input($product['products_price11_qty']) . "', '" . tep_db_input($product['products_qty_blocks']) . "',
                       now(), '" . tep_db_input($product['products_date_available']) . "', '" . tep_db_input($product['products_weight']) . "', '0', '" . (int)$product['products_tax_class_id'] . "', '" . (int)$product['manufacturers_id'] . "')");
          $dup_products_id = tep_db_insert_id();
          $description_query = tep_db_query("select language_id, products_name, products_description, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag, products_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "'");
          while ($description = tep_db_fetch_array($description_query)) {
            tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_description, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag, products_url, products_viewed) values ('" . (int)$dup_products_id . "', '" . (int)$description['language_id'] . "', '" . tep_db_input($description['products_name']) . "', '" . tep_db_input($description['products_description']) . "', '" . tep_db_input($description['products_head_title_tag']) . "', '" . tep_db_input($description['products_head_desc_tag']) . "', '" . tep_db_input($description['products_head_keywords_tag']) . "', '" . tep_db_input($description['products_url']) . "', '0')");
          }
          tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$dup_products_id . "', '" . (int)$categories_id . "')");
          $products_id_from=tep_db_input($products_id);
          $products_id_to= $dup_products_id;
          $products_id = $dup_products_id;
          if ( $_POST['copy_attributes']=='copy_attributes_yes' and $_POST['copy_as'] == 'duplicate' ) {
            $copy_attributes_delete_first='1';
            $copy_attributes_duplicates_skipped='1';
            $copy_attributes_duplicates_overwrite='0';
            if (DOWNLOAD_ENABLED == 'true') {
              $copy_attributes_include_downloads='1';
              $copy_attributes_include_filename='1';
            } else {
              $copy_attributes_include_downloads='0';
              $copy_attributes_include_filename='0';
            }
            tep_copy_products_attributes($products_id_from,$products_id_to);
          }
        }
        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $categories_id . '&pID=' . $products_id));
      }
      break;

    case 'new_product_preview':
      $images = array(array('table' => 'products_image', 'delete' => 'delete_image', 'unlink' => 'unlink_image'),
                      array('table' => 'products_image_med', 'delete' => 'delete_image_med', 'unlink' => 'unlink_image_med'),
                      array('table' => 'products_image_lrg', 'delete' => 'delete_image_lrg', 'unlink' => 'unlink_image_lrg'),
                      array('table' => 'products_image_sm_1', 'delete' => 'delete_image_sm_1', 'unlink' => 'unlink_image_sm_1'),
                      array('table' => 'products_image_xl_1', 'delete' => 'delete_image_xl_1', 'unlink' => 'unlink_image_xl_1'),
                      array('table' => 'products_image_sm_2', 'delete' => 'delete_image_sm_2', 'unlink' => 'unlink_image_sm_2'),
                      array('table' => 'products_image_xl_2', 'delete' => 'delete_image_xl_2', 'unlink' => 'unlink_image_xl_2'),
                      array('table' => 'products_image_sm_3', 'delete' => 'delete_image_sm_3', 'unlink' => 'unlink_image_sm_3'),
                      array('table' => 'products_image_xl_3', 'delete' => 'delete_image_xl_3', 'unlink' => 'unlink_image_xl_3'),
                      array('table' => 'products_image_sm_4', 'delete' => 'delete_image_sm_4', 'unlink' => 'unlink_image_sm_4'),
                      array('table' => 'products_image_xl_4', 'delete' => 'delete_image_xl_4', 'unlink' => 'unlink_image_xl_4'),
                      array('table' => 'products_image_sm_5', 'delete' => 'delete_image_sm_5', 'unlink' => 'unlink_image_sm_5'),
                      array('table' => 'products_image_xl_5', 'delete' => 'delete_image_xl_5', 'unlink' => 'unlink_image_xl_5'),
                      array('table' => 'products_image_sm_6', 'delete' => 'delete_image_sm_6', 'unlink' => 'unlink_image_sm_6'),
                      array('table' => 'products_image_xl_6', 'delete' => 'delete_image_xl_6', 'unlink' => 'unlink_image_xl_6')
                     );
      foreach ($images as $image) {
        $dir_dest = $_POST[$image['table'].'_destination'];
        $dir_dest = str_ireplace( "%2F", "/", $dir_dest);
        ${$image['table'].'_name'} = '';
        if (isset($_FILES[$image['table']]) && tep_not_null($_FILES[$image['table']]['name']) && strtolower($_FILES[$image['table']]['name']) != 'none') {
          $image_upload = new upload($image['table']);
          $image_upload->set_destination(DIR_FS_CATALOG_IMAGES . $dir_dest);
          if ($image_upload->parse() && $image_upload->save()) {
            ${$image['table'].'_name'} = $image_upload->filename;
          } else {
            ${$image['table'].'_name'} = (isset($_POST[$image['table'].'_previous']) ? $_POST[$image['table'].'_previous'] : '');
          }
        }
      }
      break;
  } // end switch($action)
} // end tep_not_null($action)
// check if the catalog image directory exists
if (is_dir(DIR_FS_CATALOG_IMAGES)) {
  if (!is_writeable(DIR_FS_CATALOG_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
} else {
  $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
}
switch (true) {
  case (CATEGORIES_SORT_ORDER=="products_name"):
    $order_it_by = "pd.products_name";
    break;
  case (CATEGORIES_SORT_ORDER=="products_name-desc"):
    $order_it_by = "pd.products_name DESC";
    break;
  case (CATEGORIES_SORT_ORDER=="model"):
    $order_it_by = "p.products_model";
    break;
  case (CATEGORIES_SORT_ORDER=="model-desc"):
    $order_it_by = "p.products_model DESC";
    break;
  default:
    $order_it_by = "pd.products_name";
    break;
}
$go_back_to=$_SERVER["REQUEST_URI"];

include(DIR_WS_INCLUDES . 'html_top.php');
include(DIR_WS_INCLUDES . 'header.php');
include(DIR_WS_INCLUDES . 'column_left.php');
?>
<style>

</style>
<?php
$msg = 'TEST';
$error = false;
$warning = false;
?>
<div id="content" class="content p-relative">         
  <h1 class="page-header"><i class="fa fa-laptop"></i> <?php echo HEADING_TITLE; ?></h1>

  <?php if (file_exists(DIR_WS_INCLUDES . 'toolbar.php')) include(DIR_WS_INCLUDES . 'toolbar.php'); ?>
  
  <div class="col main-col">     
    <!-- begin alert -->
    <?php if ($error) echo '<div class="row"><div class="col p-0 mt-0 mb-3"><div class="note note-danger m-0"><h4 class="m-0">' . TEXT_ERROR . '</h4><p class="mb-0 mt-2">' . $msg . '</p></div></div></div>'; ?>
    <?php if ($warning) echo '<div class="row"><div class="col p-0 mt-0 mb-3"><div class="note note-warning m-0"><h4 class="m-0">' . TEXT_WARNING . '</h4><p class="mb-0 mt-2">' . $msg . '</p></div></div></div>'; ?>
    <!-- end alert -->
    <?php 
    if (isset($action) && $action == 'new_product') {
      ?>
      <form name="new_product" method="post" enctype="multipart/form-data" onSubmit="return OnSubmitForm();">
      <!-- begin button bar --> 
      <div class="row">
        <div class="col-9 m-b-10"> 
          <a href="javascript:;" class="btn btn-link m-r-3 f-w-200 text-primary hidden-xs hidden-sm"><i class="fa fa-chevron-left"></i> Return to List</a>
          <a href="javascript:;" class="btn btn-primary m-r-3"><i class="fa fa-save"></i> Save</a>
          <a href="javascript:;" class="btn btn-info m-r-3 btn-save-stay"><i class="fa fa-save"></i> Save and Stay</a>
          <a href="javascript:;" class="hidden-xs hidden-sm hidden-md btn btn-link m-r-5 f-w-200 text-primary"><i class="fa fa-laptop"></i> View in Catalog</a>
        </div>
        <div class="col-3">
          <div class="btn-group pull-right dark"> <a aria-expanded="false" href="javascript:;" data-toggle="dropdown" class="btn btn-white dropdown-toggle"> <?php echo ucwords($_SESSION['language']); ?> <span class="caret"></span> </a>
            <ul class="dropdown-menu pull-right">
              <?php
              $languages = tep_get_languages();
              for ($i=0; $i<sizeof($languages); $i++) {
                ?>
                <li <?php echo (($languages[$i]['id'] == $_SESSION['languages_id'])? 'class="active"':'');?>>
                  <a aria-expanded="false" href="#"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'],'align="absmiddle" style="height:16px; "') . '<span class="ml-2">' . $languages[$i]['name'];?></span></a>
                </li>
                <?php
              }
              ?>
            </ul>
          </div>
        </div>
      </div>
      <!-- end button bar -->
      <?php 
    } 
    ?>
    <!-- begin panel -->
    <div class="dark">
      <!-- body_text //-->           
      <div id="table-categories" class="table-categories">

        <div class="row">

          <div class="col-md-8 col-xl-9 dark panel-left rounded-left">
            <?php
            $manage_image = new DirSelect($ImageLocations);
            $image_dir = $manage_image->getDirs();
            $file_dir = '<option value="">/</option>';
            foreach($image_dir as $relative => $fullpath) {
              if (substr($relative, -1) == '/'){
                $relative = substr($relative, 1);
                $file_dir .= '<option value="' . rawurlencode($relative) . '">' . $relative . '</option>';
              }
            }
            unset($image_dir, $manage_image, $relative, $fullpath);

            if ( isset($action) && ($action == 'new_category' || $action == 'edit_category') ) {
              if ( ($cID) && (!$_POST) ) {
                $categories_query = tep_db_query("select c.categories_id, cd.categories_name, cd.categories_heading_title, cd.categories_description, cd.categories_head_title_tag, cd.categories_head_desc_tag, cd.categories_head_keywords_tag, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $cID . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by c.sort_order, cd.categories_name");
                $category = tep_db_fetch_array($categories_query);
                $cInfo = new objectInfo($category);
              } elseif ($_POST) {
                $cInfo = new objectInfo($_POST);
                $categories_name = (isset($_POST['categories_name']) ? $_POST['categories_name'] : '' );
                $categories_heading_title = (isset($_POST['categories_heading_title']) ? $_POST['categories_heading_title'] : '' );
                $categories_description = (isset($_POST['categories_description']) ? $_POST['categories_description'] : '' );
                $categories_head_title_tag = (isset($_POST['categories_head_title_tag']) ? $_POST['categories_head_title_tag'] : '' );
                $categories_head_desc_tag = (isset($_POST['categories_head_desc_tag']) ? $_POST['categories_head_desc_tag'] : '' );
                $categories_head_keywords_tag = (isset($_POST['categories_head_keywords_tag']) ? $_POST['categories_head_keywords_tag'] : '' );
                $categories_url = (isset($_POST['categories_url']) ? $_POST['categories_url'] : '' );
                $categories_image = (isset($_POST['categories_image']) ? $_POST['categories_image'] : '' );
              } else {
                $cInfo = new objectInfo(array());
                $cInfo ->categories_name = (isset($cInfo ->categories_name) ? $cInfo ->categories_name : '' );
                $cInfo ->categories_heading_title = (isset($_POST['categories_heading_title']) ? $_POST['categories_heading_title'] : '' );
                $cInfo ->categories_description = (isset($_POST['categories_description']) ? $_POST['categories_description'] : '' );
                $cInfo ->categories_head_title_tag = (isset($_POST['categories_head_title_tag']) ? $_POST['categories_head_title_tag'] : '' );
                $cInfo ->categories_head_desc_tag = (isset($_POST['categories_head_desc_tag']) ? $_POST['categories_head_desc_tag'] : '' );
                $cInfo ->categories_head_keywords_tag = (isset($_POST['categories_head_keywords_tag']) ? $_POST['categories_head_keywords_tag'] : '' );
                $cInfo ->categories_url = (isset($_POST['categories_url']) ? $_POST['categories_url'] : '' );
                $cInfo ->categories_image = (isset($cInfo ->categories_image) ? $cInfo ->categories_image : '' );
                $cInfo ->sort_order = (isset($cInfo ->sort_order) ? $cInfo ->sort_order : '' );
              }

              $languages = tep_get_languages();
              $text_new_or_edit = ($action=='new_category') ? TEXT_INFO_HEADING_NEW_CATEGORY : TEXT_INFO_HEADING_EDIT_CATEGORY;
               // editor functions
              echo tep_load_html_editor();
              $category_elements = '';
              for ($i=0; $i<sizeof($languages); $i++) {
                $category_elements .= 'categories_description[' . $languages[$i]['id'] . '],';
              }
              echo tep_insert_html_editor($category_elements);
              // editor functions eof
              // RCI start
              echo $cre_RCI->get('categories', 'cedittop');
              // RCI eof
              ?>
              <tr>
                <td>
                  <h1 class="page-header"><?php echo sprintf($text_new_or_edit, tep_output_generated_category_path($current_category_id)); ?></h1>
                </td>
              </tr>
              <?php
              // RCO start fieldsetcinfo
              if ($cre_RCO->get('categories', 'fieldsetcinfo') !== true) {
                ?>
                <tr>
                  <?php echo tep_draw_form('new_category', FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cID . '&action=new_category_preview', 'post', 'enctype="multipart/form-data"'); ?>
                  <td><fieldset><legend><?php echo TEXT_GENERAL_OPTIONS; ?></legend>
                    <table width="100%" border="0" cellspacing="3" cellpadding="3" align="center" summary="category image and sort order">
                      <tr valign="top">
                        <?php echo tep_draw_hidden_field('categories_previous_image', (isset($cInfo->categories_image) ? $cInfo->categories_image :'')); ?>
                        <td width="33%" class="main">
                          <strong><?php echo TEXT_EDIT_CATEGORIES_IMAGE; ?></strong><br><?php echo tep_draw_file_field('categories_image'); ?>
                          <br><br>
                          <strong><?php echo TEXT_SELECT_CATEGORIES_IMAGE_DIR; ?></strong><br><select name="file_destination" class="dirWidth" id="dirPath" ><?php echo $file_dir; ?></select>
                        </td>
                        <td width="33%" class="main" align="center">
                          <?php
                          if ($cInfo->categories_image == '') { } else {?>
                            <strong><?php echo TEXT_EXISTING_CATEGORIES_IMAGE; ?></strong><br>
                            <?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $cInfo->categories_image, $cInfo->categories_image, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="center" hspace="0" vspace="5"') . '<br>' . $cInfo->categories_image . '<br>' . tep_draw_hidden_field('categories_previous_image', $cInfo->categories_image);
                          }
                          if (!($cInfo->categories_image =='')) {
                            echo '<br> <input type="checkbox" name="unlink_cat_image" value="yes">' .  TEXT_CATEGORIES_IMAGE_REMOVE_SHORT;
                            echo '<br> <input type="checkbox" name="delete_cat_image" value="yes">' . TEXT_CATEGORIES_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '10'). '</td>';
                          }
                          ?>
                        </td>
                        <td width="33%" class="main" align="center"><strong><?php echo TEXT_EDIT_SORT_ORDER; ?></strong>&nbsp;<?php echo tep_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'); ?></td>
                      </tr>
                      <tr>
                        <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
                      </tr>
                    </table>
                  </fieldset></td>
                </tr>
                <?php
              } // RCO eof fieldsetcinfo
              ?>
              <?php
              // RCO start fieldsetcdescr
              if ($cre_RCO->get('categories', 'fieldsetcdescr') !== true) {
                ?>
                <tr>
                  <td colspan="3">
                    <table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
                      <tr>
                        <td class="main" valign="top" width="100%"><div class="tab-pane" id="tabPane1">
                          <script type="text/javascript">
                            tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );
                          </script>
                          <?php
                          for ($i=0; $i<sizeof($languages); $i++) {
                            ?>
                            <div class="tab-page" id="<?php echo $languages[$i]['name'];?>">
                            <h2 class="tab"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], '', '', 'align="middle" style="height:16px; width:30px;"') . '&nbsp;' .$languages[$i]['name'];?></h2>
                            <script type="text/javascript">tp1.addTabPage( document.getElementById( "<?php echo $languages[$i]['name'];?>" ) );</script>
                            <table width="98%" border="0" cellspacing="0" cellpadding="0" summary="tab table">
                              <tr>
                                <td valign="top">
                                  <table width="100%" border="0" cellspacing="4" cellpadding="0" summary="Title table">
                                    <tr valign="top">
                                      <td class="main" width="14%"><strong><?php echo TEXT_EDIT_CATEGORIES_NAME; ?></strong></td>
                                      <td class="main">
                                        <?php
                                        if (isset($cInfo->categories_id)) {
                                          echo tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', (isset($categories_name[$languages[$i]['id']]) ? stripslashes($categories_name[$languages[$i]['id']]) : tep_get_category_name($cInfo->categories_id, $languages[$i]['id'])), 'size="64" maxlength="64"');
                                        } else{
                                          echo tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', (isset($categories_name[$languages[$i]['id']]) ? stripslashes($categories_name[$languages[$i]['id']]) : ''), 'size="64" maxlength="64"');
                                        }
                                        ?>
                                      </td>
                                    </tr>
                                    <tr valign="top" >
                                      <td class="main" width="14%"><strong><?php echo TEXT_EDIT_CATEGORIES_HEADING_TITLE; ?></strong></td>
                                      <td class="main">
                                        <?php
                                        if (isset($cInfo->categories_id)) {
                                          echo tep_draw_input_field('categories_heading_title[' . $languages[$i]['id'] . ']', (isset($categories_heading_title[$languages[$i]['id']]) ? stripslashes($categories_heading_title[$languages[$i]['id']]) : tep_get_category_heading_title($cInfo->categories_id, $languages[$i]['id'])), 'size="64" maxlength="64"');
                                        } else {
                                          echo tep_draw_input_field('categories_heading_title[' . $languages[$i]['id'] . ']', (isset($categories_heading_title[$languages[$i]['id']]) ? stripslashes($categories_heading_title[$languages[$i]['id']]) : ''), 'size="64" maxlength="64"');
                                        }
                                        ?>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
                                    </tr>
                                  </table>
                                  <table width="100%"  border="0" cellspacing="4" cellpadding="0" summary="description tabe">
                                    <tr valign="top">
                                      <td class="main"><strong><?php echo TEXT_EDIT_CATEGORIES_DESCRIPTION; ?></strong></td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <?php
                                        if (isset($cInfo->categories_id)) {
                                          echo tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($categories_description[$languages[$i]['id']]) ? stripslashes($categories_description[$languages[$i]['id']]) : tep_get_category_description($cInfo->categories_id, $languages[$i]['id'])));
                                        } else {
                                          echo tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($categories_description[$languages[$i]['id']]) ? stripslashes($categories_description[$languages[$i]['id']]) : ''));
                                        }
                                        ?>
                                      </td>
                                    </tr>
                                  </table>
                                  <table width="100%"  border="0" cellspacing="3" cellpadding="0" summary="meta content holder table">
                                    <tr>
                                      <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
                                    </tr>
                                    <?php
                                    // RCO start fieldsetcmeta
                                    if ($cre_RCO->get('categories', 'fieldsetcmeta') !== true) {
                                      ?>
                                      <tr>
                                        <td valign="top"><fieldset><legend><?php echo TEXT_PRODUCT_METTA_INFO; ?></legend>
                                          <table width="100%"  border="0" cellspacing="3" cellpadding="3">
                                            <tr>
                                              <td class="main"><strong><?php echo TEXT_EDIT_CATEGORIES_TITLE_TAG; ?></strong></td>
                                            </tr>
                                            <tr>
                                              <td class="main">
                                                <?php
                                                if (isset($cInfo->categories_id)) {
                                                  echo tep_draw_textarea_field('categories_head_title_tag[' . $languages[$i]['id'] . ']', 'soft', '15', '2', (isset($categories_head_title_tag[$languages[$i]['id']]) ? stripslashes($categories_head_title_tag[$languages[$i]['id']]) : tep_get_category_head_title_tag($cInfo->categories_id, $languages[$i]['id'])),'style="width: 100%"');
                                                } else {
                                                  echo tep_draw_textarea_field('categories_head_title_tag[' . $languages[$i]['id'] . ']', 'soft', '15', '2', (isset($categories_head_title_tag[$languages[$i]['id']]) ? stripslashes($categories_head_title_tag[$languages[$i]['id']]) : ''),'style="width: 100%"');
                                                }
                                                ?>
                                              </td>
                                            </tr>
                                          </table>
                                          <table width="100%"  border="0" cellspacing="3" cellpadding="3">
                                            <tr>
                                              <td width="50%" class="main"><strong><?php echo TEXT_EDIT_CATEGORIES_DESC_TAG;?></strong></td>
                                              <td width="50%" class="main"><strong><?php echo TEXT_EDIT_CATEGORIES_KEYWORDS_TAG; ?></strong></td>
                                            </tr>
                                            <tr>
                                              <td class="main">
                                                <?php
                                                if (isset($cInfo->categories_id)) {
                                                  echo tep_draw_textarea_field('categories_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '25', '5', (isset($categories_head_desc_tag[$languages[$i]['id']]) ? stripslashes($categories_head_desc_tag[$languages[$i]['id']]) : tep_get_category_head_desc_tag($cInfo->categories_id, $languages[$i]['id'])),'style="width: 100%"');
                                                } else {
                                                  echo tep_draw_textarea_field('categories_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '25', '5', (isset($categories_head_desc_tag[$languages[$i]['id']]) ? stripslashes($categories_head_desc_tag[$languages[$i]['id']]) : ''),'style="width: 100%"');
                                                }
                                                ?>
                                              </td>
                                              <td class="main">
                                                <?php
                                                if (isset($cInfo->categories_id)) {
                                                  echo tep_draw_textarea_field('categories_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '25', '5', (isset($categories_head_keywords_tag[$languages[$i]['id']]) ? stripslashes($categories_head_keywords_tag[$languages[$i]['id']]) : tep_get_category_head_keywords_tag($cInfo->categories_id, $languages[$i]['id'])),'style="width: 100%"');
                                                } else {
                                                  echo tep_draw_textarea_field('categories_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '25', '5', (isset($categories_head_keywords_tag[$languages[$i]['id']]) ? stripslashes($categories_head_keywords_tag[$languages[$i]['id']]) : ''),'style="width: 100%"');
                                                }
                                                ?>
                                              </td>
                                            </tr>
                                          </table>
                                          <br>
                                        </fieldset></td>
                                      </tr>
                                      <?php
                                    } // RCO eof fieldsetcmeta
                                    ?>
                                  </table>
                                </td>
                              </tr>
                            </table>
                            </div>
                            <?php
                          }
                          ?>
                        </div>
                        <script type="text/javascript">
                          //<![CDATA[
                          setupAllTabs();
                          //]]>
                        </script>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <?php
              } // RCO eof fieldsetcdescr
              ?>
              <tr>
                <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td class="main" align="right">
                <?php
                echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($cID) ? '&cID=' . $cID : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>&nbsp;' . tep_draw_hidden_field('categories_date_added', (isset($cInfo->date_added) ? $cInfo->date_added : date('Y-m-d'))) . tep_draw_hidden_field('parent_id', $cInfo->parent_id) . tep_image_submit('button_preview_upload.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;';
                ?>
                </td>
              </tr>
              </form>
              <?php
              // new_category_preview (active when ALLOW_CATEGORY_DESCRIPTIONS is 'true')
            } elseif (isset($action) && $action == 'new_category_preview') {
                if (isset($_POST)) {
                  $cInfo = new objectInfo($_POST);
                  $categories_name = $_POST['categories_name'];
                  $categories_heading_title = $_POST['categories_heading_title'];
                  $categories_description = $_POST['categories_description'];
                  $categories_head_title_tag = $_POST['categories_head_title_tag'];
                  $categories_head_desc_tag = $_POST['categories_head_desc_tag'];
                  $categories_head_keywords_tag = $_POST['categories_head_keywords_tag'];
                  $categories_image = (isset($_POST['categories_image']) ? $_POST['categories_image'] : '');
                  $categories_previous_image = (isset($_POST['categories_previous_image']) ? $_POST['categories_previous_image'] : '');
                  $categories_type_image = (isset($_POST['categories_type_image']) ? $_POST['categories_type_image'] : '');
                  $categories_file_destination  = (isset($_POST['file_destination']) ? $_POST['file_destination'] : '');
                  $categories_file_destination = str_ireplace( "%2F", "/", $categories_file_destination);
                  $unlink_cat_image = (isset($_POST['unlink_cat_image']) ? $_POST['unlink_cat_image'] : '');
                  $delete_cat_image = (isset($_POST['delete_cat_image']) ? $_POST['delete_cat_image'] : '');
                  // copy image only if modified
                  if ( (isset($_POST['delete_cat_image'])) && ($_POST['delete_cat_image'] == 'yes') ) {
                    unlink(DIR_FS_CATALOG_IMAGES . $categories_previous_image);
                  }
                  if (((isset($_POST['unlink_cat_image'])) && ($_POST['unlink_cat_image'] == 'yes')) || ((isset($_POST['delete_cat_image'])) && ($_POST['delete_cat_image'] == 'yes'))) {
                    $categories_image = '';
                    $categories_image_name = '';
                  } else {
                    $categories_image_tmp = '';
                    $categories_image_tmp = new upload('categories_image');
                    $categories_image_tmp->set_destination(DIR_FS_CATALOG_IMAGES . $categories_file_destination);
                    if ($categories_image_tmp->parse() && $categories_image_tmp->save()) {
                      $categories_image_name =  $categories_file_destination . $categories_image_tmp->filename;
                    } else if (is_file(DIR_FS_CATALOG_IMAGES . $categories_file_destination . $categories_image)){
                      $categories_image_name = $categories_file_destination . $categories_image;
                    } else {
                      $categories_image_name = $categories_previous_image;
                    }
                  }
                } else {
                  $category_query = tep_db_query("select c.categories_id, cd.language_id, cd.categories_name, cd.categories_heading_title, cd.categories_description, cd.categories_head_title_tag, cd.categories_head_desc_tag, cd.categories_head_keywords_tag, c.categories_image, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and c.categories_id = '" . $cID . "'");
                  $category = tep_db_fetch_array($category_query);
                  $cInfo = new objectInfo($category);
                  $categories_image_name = $cInfo->categories_image;
                }
                $form_action = ((isset($cID)) && tep_not_null($cID) ) ? 'update_category' : 'insert_category';
                echo tep_draw_form($form_action, FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cID . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');
                $read = '';
                if (isset($_GET['read'])){
                  $read = $_GET['read'];
                }
                $languages = tep_get_languages();
                for ($i=0; $i<sizeof($languages); $i++) {
                  if ($read == 'only') {
                    $cInfo->categories_name = tep_get_category_name($cInfo->categories_id, $languages[$i]['id']);
                    $cInfo->categories_heading_title = tep_get_category_heading_title($cInfo->categories_id, $languages[$i]['id']);
                    $cInfo->categories_description = tep_get_category_description($cInfo->categories_id, $languages[$i]['id']);
                    $cInfo->category_template_id = tep_get_category_template_id($cInfo->categories_id, $languages[$i]['id']);
                    $cInfo->categories_head_title_tag = tep_get_category_head_title_tag($cInfo->categories_id, $languages[$i]['id']);
                    $cInfo->categories_head_desc_tag = tep_get_category_head_desc_tag($cInfo->categories_id, $languages[$i]['id']);
                    $cInfo->categories_head_keywords_tag = tep_get_category_head_keywords_tag($cInfo->categories_id, $languages[$i]['id']);
                  } else {
                    $cInfo->categories_name = tep_db_prepare_input($categories_name[$languages[$i]['id']]);
                    $cInfo->categories_heading_title = tep_db_prepare_input($categories_heading_title[$languages[$i]['id']]);
                    $cInfo->categories_description = tep_db_prepare_input($categories_description[$languages[$i]['id']]);
                    $cInfo->category_template_id = (isset($category_template_id[$languages[$i]['id']]) ? tep_db_prepare_input($category_template_id[$languages[$i]['id']]) : 0) ;
                    $cInfo->categories_head_title_tag = tep_db_prepare_input($categories_head_title_tag[$languages[$i]['id']]);
                    $cInfo->categories_head_desc_tag = tep_db_prepare_input($categories_head_desc_tag[$languages[$i]['id']]);
                    $cInfo->categories_head_keywords_tag = tep_db_prepare_input($categories_head_keywords_tag[$languages[$i]['id']]);
                  }
                  ?>
                  <tr>
                    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><h1 class="page-header"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $cInfo->categories_heading_title; ?></h1></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="main"><?php //echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $categories_image_name, $cInfo->categories_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . $cInfo->categories_description; ?>


                     <table border="0" width="100%" cellspacing="0" cellpadding="0">
                      <tr>
                        <td class="main" ><?php echo $cInfo->categories_description; ?></td>
                        <td class="main" width="10"></td>
                        <?php
                        if ($categories_image_name) {
                          echo '<td class="main" valign="top" >' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $categories_image_name, $categories_image_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . '</td>';
                        }
                        ?>
                      </tr>
                    </table>

                    </td>
                  </tr>
                  <?php
                }
                if ($read == 'only') {
                  if (isset($_GET['origin'])) {
                    $pos_params = strpos($_GET['origin'], '?', 0);
                    if ($pos_params != false) {
                      $back_url = substr($_GET['origin'], 0, $pos_params);
                      $back_url_params = substr($_GET['origin'], $pos_params + 1);
                    } else {
                      $back_url = $_GET['origin'];
                      $back_url_params = '';
                    }
                  } else {
                    $back_url = FILENAME_CATEGORIES;
                    $back_url_params = 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id;
                  }
                  ?>
                  <tr>
                    <td align="right"><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
                  </tr>
                  <?php
                } else {
                  ?>
                  <tr>
                    <td align="right" class="smallText">
                      <?php
                      reset($_POST);
                      while (list($key, $value) = each($_POST)) {
                        if (is_array($value)) {
                          while (list($k, $v) = each($value)) {
                            echo tep_draw_hidden_field($key . '[' . $k . ']', htmlspecialchars(stripslashes($v))) . "\n";
                          }
                        } else {
                          echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value))) . "\n";
                        }
                      }
                      echo tep_draw_hidden_field('X_categories_image', stripslashes($categories_image_name)) . "\n";
                      echo tep_draw_hidden_field('categories_image_name', stripslashes($categories_image_name)) . "\n";
                      echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . "\n\n";
                      echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cID) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>&nbsp;';
                      if ($cID) {
                        echo tep_image_submit('button_update.gif', IMAGE_UPDATE) . "\n";
                      } else {
                        echo tep_image_submit('button_insert.gif', IMAGE_INSERT) . "\n";
                      }
                      ?>&nbsp;
                    </td>
                    </form>
                  </tr>
                  <?php
                }
            } elseif (isset($action) && $action == 'new_product') {
              $parameters = array('products_name' => '',
                                 'products_description' => '',
                                 'products_url' => '',
                                 'products_id' => '',
                                 'products_quantity' => '',
                                 'products_model' => '',
                                 'products_sku' => '',
                                 'products_image' => '',
                                 'products_image_med' => '',
                                 'products_image_lrg' => '',
                                 'products_image_sm_1' => '',
                                 'products_image_xl_1' => '',
                                 'products_image_sm_2' => '',
                                 'products_image_xl_2' => '',
                                 'products_image_sm_3' => '',
                                 'products_image_xl_3' => '',
                                 'products_image_sm_4' => '',
                                 'products_image_xl_4' => '',
                                 'products_image_sm_5' => '',
                                 'products_image_xl_5' => '',
                                 'products_image_sm_6' => '',
                                 'products_image_xl_6' => '',
                                 'products_price' => '',
                                 'products_weight' => '',
                                 'products_date_added' => '',
                                 'products_last_modified' => '',
                                 'products_date_available' => date('Y-m-d'),
                                 'products_status' => '',
                                 'products_tax_class_id' => '',
                                 'manufacturers_id' => '');

              $pInfo = new objectInfo($parameters);
              if (isset($pID) && empty($_POST)) {
                /*$products_extra_fields_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " WHERE products_id=" . (int)$pID);
                while ($products_extra_fields = tep_db_fetch_array($products_extra_fields_query)) {
                  $extra_field[$products_extra_fields['products_extra_fields_id']] = $products_extra_fields['products_extra_fields_value'];
                  $extra_field_array=array('extra_field'=>$extra_field);
                  $pInfo->objectInfo($extra_field_array);
                }*/
                $product_query = tep_db_query("select pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_url, p.products_id, p.products_quantity, p.products_model, p.products_sku, p.products_image, p.products_image_med, p.products_image_lrg, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, p.products_price, p.products_weight, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.products_tax_class_id, p.manufacturers_id, p.products_price, p.products_price1, p.products_price2, p.products_price3, p.products_price4, p.products_price5, p.products_price6, p.products_price7, p.products_price8, p.products_price9, p.products_price10, p.products_price11, p.products_price1_qty, p.products_price2_qty, p.products_price3_qty, p.products_price4_qty, p.products_price5_qty, p.products_price6_qty, p.products_price7_qty, p.products_price8_qty, p.products_price9_qty, p.products_price10_qty, p.products_price11_qty from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$pID . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
                $product = tep_db_fetch_array($product_query);
                if (!empty($product)){
                  $pInfo->objectInfo($product);
                }
              } elseif ((isset($_POST)) && (tep_not_null($_POST)) ){
                $pInfo->objectInfo($_POST);
                $products_name = $_POST['products_name'];
                $products_description = $_POST['products_description'];
                $products_url = $_POST['products_url'];
              }
              $manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
              $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
              while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
                $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                               'text' => $manufacturers['manufacturers_name']);
              }
              $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
              $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
              while ($tax_class = tep_db_fetch_array($tax_class_query)) {
                $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                                           'text' => $tax_class['tax_class_title']);
              }
              $languages = tep_get_languages();
              if (!isset($pInfo->products_status)) $pInfo->products_status = '1';
              switch ($pInfo->products_status) {
                case '0': $in_status = false; $out_status = true; break;
                case '1':
                default: $in_status = true; $out_status = false;
              }

              // RCI start
              echo $cre_RCI->get('categories', 'pedittop');
              // RCI eof
              if ( !empty($pID) ) {
                $form_action_text = 'Update';
                $form_action_action = 'update_product';
                $form_action_button = tep_image_submit('button_quick_save.gif',IMAGE_UPDATE,'name="Operation" onClick="document.pressed=this.value" VALUE="'.$form_action_action.'"');
              } else {
                $form_action_text = 'Insert';
                $form_action_action = 'insert_product';
                $form_action_button = tep_image_submit('button_quick_save.gif',IMAGE_UPDATE,'name="Operation" onClick="document.pressed=this.value" VALUE="'.$form_action_action.'"');
              }
              ?>
              <script type="text/javascript">
              function OnSubmitForm() {
                if(trim(document.pressed) == '<?php echo $form_action_text;?>') {
                  document.new_product.action ="<?php echo str_replace('&amp;', '&', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($pID) ? '&pID=' . $pID : '') . '&action=' . $form_action_action)); ?>";
                } else if(trim(document.pressed) == 'Preview') {
                  document.new_product.action ="<?php echo str_replace('&amp;', '&', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($pID) ? '&pID=' . $pID : '') . '&action=new_product_preview')); ?>";
                }
                return true;
              }

              </script>
              <!-- LEFT PANEL start -->
              <?php
              // RCO start fieldsetdescr
              if ($cre_RCO->get('categories', 'fieldsetdescr') !== true) {                       
                ?>
                <div class="product-lang-content">
                  <?php
                  for ($i=0; $i<sizeof($languages); $i++) {
                    $display = ($languages[$i]['id'] == $_SESSION['languages_id']) ? '' : 'display:none;'
                    ?>
                    <div style="<?php echo $display; ?>" class="product-lang-pane <?php echo (($languages[$i]['id'] == $_SESSION['languages_id']) ? 'active' : '');?>" id="default-pane-<?php echo $languages[$i]['id'];?>">

                    <!-- PRODUCT INFO start -->
                    <div class="ml-2 mr-2">
                      <div class="main-heading"><span>Product Info</span>
                        <div class="main-heading-footer"></div>
                      </div>             

                      <div class="form-group row mb-3 m-t-20">
                        <label class="col-xs-4 col-md-3 col-lg-2 control-label main-text mt-1"><?php echo TEXT_PRODUCTS_NAME; ?></label>
                        <div class="col-xs-7 col-md-8 col-lg-9 p-r-0 meta-input">
                          <?php echo tep_draw_input_field('products_name[' . $languages[$i]['id'] . ']', (isset($products_name[$languages[$i]['id']]) ? $products_name[$languages[$i]['id']] : tep_get_products_name($pInfo->products_id, $languages[$i]['id'])), 'id="products_name_' . $languages[$i]['id'] . '"class="form-control f-w-600 f-s-12 p-l-10 p-r-10"'); ?>
                        </div>

                        <div class="col-xs-1 p-l-0 p-r-0 p-relative">
                          <div class="notify-container-name rounded-left rounded-right"><span class="text-black">Copied!</span></div>
                          <div id="name-ctc-options" class="btn-group btn-xs"> <a aria-expanded="false" href="javascript:;" data-toggle="dropdown" class="f-w-100 text-right btn btn-xs btn-white dropdown-toggle width-full"> <span class="caret"></span> </a>
                            <ul id="name-ctc-list" class="dropdown-menu pull-left">
                              <?php
                              for ($j=0; $j<sizeof($languages); $j++) {
                                ?>
                                <li><a data-container="body" data-lang-name="<?php echo ucwords($languages[$j]['name']); ?>" data-lang-id="<?php echo $languages[$j]['id']; ?>" aria-expanded="false" href="javscript:;"><i class="fa fa-clipboard mr-1" aria-hidden="true"></i><?php echo sprintf(TEXT_COPY_LANG_TO_CLIPBOARD, $languages[$j]['name']);?></a></li>
                                <?php
                              }
                              ?>
                            </ul>
                          </div>
                        </div>
                      </div>

                      <div class="form-group row mb-3">
                        <label class="col-xs-4 col-md-3 col-lg-2 control-label pr-0 getpro main-text mt-1" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_PRO_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_PRO_UPSELL_GET_PRO_URL; ?>" target="_blank" class="btn btn-danger btn-sm m-r-5 m-t-10"><?php echo TEXT_PRO_UPSELL_GET_PRO; ?></a></div>'>Listing Blurb <span class="label label-theme bg-red ml-1">PRO</span></label>
                        <div class="col-xs-7 col-md-8 col-lg-9 p-r-0 meta-input">
                          <textarea readonly class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-xs-1 p-l-0 p-r-0"> </div>
                      </div>

                      <div class="form-group row mb-3">
                        <label class="col-xs-4 col-md-3 col-lg-2 control-label main-text mt-1"><?php echo TEXT_PRODUCTS_DESCRIPTION; ?></label>
                        <div class="col-xs-7 col-md-8 col-lg-9 p-r-0 meta-input">
                          <div id="summernote">
                            <?php echo tep_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_description[$languages[$i]['id']]) ? $products_description[$languages[$i]['id']] : tep_get_products_description($pInfo->products_id, $languages[$i]['id'])), 'class="form-control" id="products_description_' . $languages[$i]['id'] . '"'); ?>
                          </div>
                        </div>
                        <div class="col-xs-1 p-l-0 p-r-0">
                          <div class="notify-container-desc rounded-left rounded-right"><span class="text-black">Copied!</span></div>                            
                          <div id="desc-ctc-options" class="btn-group btn-xs"> <a aria-expanded="false" href="javascript:;" style="color:#666" data-toggle="dropdown" class="f-w-100 text-right btn btn-xs btn-white dropdown-toggle width-full"> <span class="caret"></span> </a>
                            <ul id="desc-ctc-list" class="dropdown-menu pull-left">
                              <?php
                              for ($j=0; $j<sizeof($languages); $j++) {
                                ?>
                                <li><a data-lang-name="<?php echo ucwords($languages[$j]['name']); ?>" data-lang-id="<?php echo $languages[$j]['id']; ?>" aria-expanded="false" href="javscript:;"><i class="fa fa-clipboard mr-1" aria-hidden="true"></i><?php echo sprintf(TEXT_COPY_LANG_TO_CLIPBOARD, $languages[$i]['name']);?></a></li>
                                <?php
                              }
                              ?>                                
                            </ul>
                          </div>
                        </div>
                      </div>

                      <div class="form-group row mb-3">
                        <label class="col-xs-4 col-md-3 col-lg-2 control-label main-text mt-1"><?php echo TEXT_PRODUCTS_URL; ?></label>
                        <div class="col-xs-7 col-md-8 col-lg-9 p-r-0 meta-input">
                          <?php echo tep_draw_input_field('products_url[' . $languages[$i]['id'] . ']', (isset($products_url[$languages[$i]['id']]) ? $products_url[$languages[$i]['id']] : tep_get_products_url($pInfo->products_id, $languages[$i]['id'])), 'id="products_url_' . $languages[$i]['id'] . '" class="form-control" placeholder="http://www.mystore.com/this_product"'); ?>
                        </div>
                        <div class="col-xs-1 pl-1 m-t-1"><button onclick="goToUrl('<?php echo $languages[$i]['id']; ?>'); return false;" class="btn btn-white btn-xs"><i class="fa fa-external-link"></i></button></div>
                      </div>
                    </div>

                    <!-- SEO & META TAGS start-->
                    <div class="ml-2 mr-2">
                      <div class="main-heading m-t-30"><span><?php echo TEXT_PRODUCT_METTA_INFO; ?></span>
                        <div class="main-heading-footer"></div>
                      </div>  

                      <div class="form-group row mb-3 mt-3">
                        <label class="col-xs-4 col-md-3 col-lg-2 control-label main-text mt-1"><?php echo TEXT_EDIT_CATEGORIES_TITLE_TAG; ?></label>
                        <div class="col-xs-7 col-md-8 col-lg-9 p-r-0 meta-input">
                          <?php
                          echo tep_draw_textarea_field('products_head_title_tag[' . $languages[$i]['id'] . ']', 'soft', '15', '2', (isset($products_head_title_tag[$languages[$i]['id']]) ? $products_head_title_tag[$languages[$i]['id']] : tep_get_products_head_title_tag($pInfo->products_id, $languages[$i]['id'])),'class="form-control"');
                          ?>                      
                        </div>
                        <div class="col-xs-1 p-l-0 p-r-0">
                          <div class="btn-group btn-xs "> <a aria-expanded="false" href="javascript:;" style="color:#666" data-toggle="dropdown" class=" f-w-100 text-right btn btn-xs btn-white dropdown-toggle width-full"> <span class="caret"></span> </a>
                            <ul class="dropdown-menu pull-left">
                              <li class=""><a aria-expanded="false" href="#nav-pills-tab-1" data-toggle="tab"><i class="fa fa-clipboard" aria-hidden="true"></i> Copy English to Clipboard</a></li>
                              <li class=""><a aria-expanded="false" href="#nav-pills-tab-2" data-toggle="tab"><i class="fa fa-clipboard" aria-hidden="true"></i> Copy Hindi to Clipboard</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row mb-3">
                        <label class="col-xs-4 col-md-3 col-lg-2 control-label main-text mt-1">Meta Keywords</label>
                        <div class="col-xs-7 col-md-8 col-lg-9 p-r-0 meta-input">
                          <?php
                          echo tep_draw_textarea_field('products_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '35', '5', (isset($products_head_keywords_tag[$languages[$i]['id']]) ? $products_head_keywords_tag[$languages[$i]['id']] : tep_get_products_head_keywords_tag($pInfo->products_id, $languages[$i]['id'])),'class="form-control"');
                          ?>                                         
                        </div>
                        <div class="col-xs-1 p-l-0 p-r-0">
                          <div class="btn-group btn-xs "> <a aria-expanded="false" href="javascript:;" style="color:#666" data-toggle="dropdown" class=" f-w-100 text-right btn btn-xs btn-white dropdown-toggle width-full"> <span class="caret"></span> </a>
                            <ul class="dropdown-menu pull-left">
                              <li class=""><a aria-expanded="false" href="#nav-pills-tab-1" data-toggle="tab"><i class="fa fa-clipboard" aria-hidden="true"></i> Copy English to Clipboard</a></li>
                              <li class=""><a aria-expanded="false" href="#nav-pills-tab-2" data-toggle="tab"><i class="fa fa-clipboard" aria-hidden="true"></i> Copy Hindi to Clipboard</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row mb-3">
                        <label class="col-xs-4 col-md-3 col-lg-2 control-label main-text mt-1">Meta Description</label>
                        <div class="col-xs-7 col-md-8 col-lg-9 p-r-0 meta-input">
                          <?php
                          echo tep_draw_textarea_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '35', '5', (isset($products_head_desc_tag[$languages[$i]['id']]) ? $products_head_desc_tag[$languages[$i]['id']] : tep_get_products_head_desc_tag($pInfo->products_id, $languages[$i]['id'])),'class="form-control"');
                          ?>  
                        </div>
                        <div class="col-xs-1 p-l-0 p-r-0">
                          <div class="btn-group btn-xs"> <a aria-expanded="false" href="javascript:;" style="color:#666" data-toggle="dropdown" class=" f-w-100 text-right btn btn-xs btn-white dropdown-toggle width-full"> <span class="caret"></span> </a>
                            <ul class="dropdown-menu pull-left">
                              <li class=""><a aria-expanded="false" href="#nav-pills-tab-1" data-toggle="tab"><i class="fa fa-clipboard" aria-hidden="true"></i> Copy English to Clipboard</a></li>
                              <li class=""><a aria-expanded="false" href="#nav-pills-tab-2" data-toggle="tab"><i class="fa fa-clipboard" aria-hidden="true"></i> Copy Hindi to Clipboard</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- SEO & META TAGS end-->                      
                    <!-- PRODUCT INFO end -->
                    <?php
                  }
                  ?>
                </div> <!-- end product-lang-content -->
                <?php
              } // RCO eof fieldsetdescr
              ?>

              <!-- IMAGES start-->     
              <div class="ml-2 mr-2">
                <?php
                $products_image = 'no_image.png';
                $products_image_med = 'no_image.png';
                $products_image_lrg = 'no_image.png';
                if (isset($pInfo)) {
                  if ($pInfo->products_image != '') $products_image = $pInfo->products_image;
                  if ($pInfo->products_image_med != '') $products_image_med = $pInfo->products_image_med;
                  if ($pInfo->products_image_lrg != '') $products_image_lrg = $pInfo->products_image_lrg;
                }
                ?>                         
                <div class="main-heading m-t-30"><span>Images</span>
                  <div class="main-heading-footer"></div>
                </div>                  

                <div class="form-group row mt-3 mb-3">
                  <label class="col-md-2 control-label main-text mt-1">Main Image</label>
                  <div class="col-md-10">
                    <div class="media">
                      <div class="lc-border p-10">
                        <a class="media-left" style="min-width:<?php echo SMALL_IMAGE_WIDTH; ?>px;" href="javascript:;"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image, $products_image, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="media-object mt-2"'); ?></a>
                        <div class="media-body mr-2 mb-0"><?php echo $products_image; if ($products_image != 'no_image.png') { ?><a class="ml-2 btn btn-default btn-xs mb-1" href="#"><i class="fa fa-trash-o"></i> <span class="hidden-xs">Delete</span></a><?php } ?>
                          <div class="input-group input-file p-r-30 p-t-20">
                            <input type="text" name="products_image" class="form-control file-input" placeholder="<?php echo TEXT_CHOOSE_FILE; ?>" />
                            <span class="input-group-btn hidden-xs">
                              <!-- button class="btn btn-default btn-choose" type="button">Choose</button -->
                              <button class="btn btn-default btn-reset" type="button">Reset</button>
                            </span>
                          </div>
                          <div class="mt-2"><select name="products_image_destination" class="form-control w-50" id="dirPath" ><?php echo $file_dir; ?></select></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group row mt-3 mb-3">
                  <label class="col-md-2 control-label main-text mt-1">Thumbnail Image</label>
                  <div class="col-md-10">
                    <div class="media">
                      <div class="lc-border p-10">
                        <a class="media-left" style="min-width:<?php echo SMALL_IMAGE_WIDTH; ?>px;" href="javascript:;"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_med, $products_image_med, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="media-object mt-2"'); ?></a>
                        <div class="media-body mr-2 mb-0 p-l-20"><?php echo $products_image_med; if ($products_image_med != 'no_image.png') { ?><a class="ml-2 btn btn-default btn-xs mb-1" href="#"><i class="fa fa-trash-o"></i> <span class="hidden-xs">Delete</span></a><?php } ?>
                          <div class="input-group input-file p-r-30 p-t-20">                            
                            <input type="text" name="products_image_med" class="form-control file-input" placeholder="<?php echo TEXT_CHOOSE_FILE; ?>" /><?php echo tep_draw_hidden_field('products_image_med_previous', $products_image_med); ?>
                            <span class="input-group-btn hidden-xs">
                              <!-- button class="btn btn-default btn-choose" type="button">Choose</button -->
                              <button class="btn btn-default btn-reset" type="button">Reset</button>
                            </span>
                          </div>
                          <div class="mt-2"><select name="products_image_med_destination" class="form-control w-50" id="dirPath" ><?php echo $file_dir; ?></select></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group row mt-3 mb-3">
                  <label class="col-md-2 col-xs-12 control-label main-text mt-1">Large Image</label>
                  <div class="col-md-10 col-xs-12">
                    <div class="media">
                      <div class="lc-border p-10">
                        <a class="media-left" style="min-width:<?php echo SMALL_IMAGE_WIDTH; ?>px;" href="javascript:;"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_lrg, $products_image_lrg, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="media-object mt-2"'); ?></a>
                        <div class="media-body mr-2 mb-0 p-l-20"><?php echo $products_image_lrg; if ($products_image_lrg != 'no_image.png') { ?><a class="ml-2 btn btn-default btn-xs mb-1" href="#"><i class="fa fa-trash-o"></i> <span class="hidden-xs">Delete</span></a><?php } ?>
                          <div class="input-group input-file p-r-30 p-t-20">
                            <input type="text" name="products_image_lrg" class="form-control file-input" placeholder="<?php echo TEXT_CHOOSE_FILE; ?>" />
                            <span class="input-group-btn hidden-xs">
                              <!-- button class="btn btn-default btn-choose" type="button">Choose</button -->
                              <button class="btn btn-default btn-reset" type="button">Reset</button>
                            </span>
                          </div>
                          <div class="mt-2"><select name="products_image_lrg_destination" class="form-control w-50" id="dirPath" ><?php echo $file_dir; ?></select></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>                

                <div class="form-group row mb-3">
                  <label class="col-md-3 control-label pr-0 getpro main-text mt-1" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_PRO_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_PRO_UPSELL_GET_PRO_URL; ?>" target="_blank" class="btn btn-danger btn-sm m-r-5 m-t-10"><?php echo TEXT_PRO_UPSELL_GET_PRO; ?></a></div>'>Additional Images <span class="label label-theme bg-red ml-1">PRO</span></label>                    
                  <div class="col-md-9">
                    <div class="col-md-2"> <i class="fa fa-plus-square-o fa-3x disabled"></i></div>
                  </div>
                </div>               
              </div>   
              <!-- IMAGES end-->

              <!-- PRICING RULES start-->
              <div class="ml-2 mr-2">
                <div class="main-heading m-t-30"><span>Pricing Rules</span>
                  <div class="main-heading-footer"></div>
                </div>  
                <table class="table mt-2">
                  <tbody>
                    <tr class="table-row">
                      <td class="table-col dark text-left p-r-5 p-l-5 table-th-valign-middle no-border">
                        <div class="pull-left m-r-10 getpro main-text f-s-12 f-w-600" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_PRO_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_PRO_UPSELL_GET_PRO_URL; ?>" target="_blank" class="btn btn-danger btn-sm m-r-5 m-t-10"><?php echo TEXT_PRO_UPSELL_GET_PRO; ?></a></div>'>Quantity Price Breaks <span class="label label-theme m-l-5 bg-red">PRO</span></div>
                        <div class="col-sm-6 p-l-10 getpro" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_PRO_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_PRO_UPSELL_GET_PRO_URL; ?>" target="_blank" class="btn btn-danger btn-sm m-r-5 m-t-10"><?php echo TEXT_PRO_UPSELL_GET_PRO; ?></a></div>'>
                          <input disabled data-width="60" data-toggle="toggle" data-on="<i class='fa fa-check'></i> On" data-off="<i class='fa fa-times'></i> Off" data-onstyle="success-toggle" data-offstyle="danger" type="checkbox" data-size="mini" >
                        </div>
                      </td>
                    </tr>
                    <tr class="table-row">
                      <td class="table-col dark text-left p-r-5 p-l-5 table-th-valign-middle no-border">
                        <div class="pull-left m-r-10 getpro main-text f-s-12 f-w-600" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_B2B_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_B2B_UPSELL_GET_B2B_URL; ?>" target="_blank" class="btn btn-warning btn-sm m-r-5 m-t-10"><?php echo TEXT_B2B_UPSELL_GET_B2B; ?></a></div>'>Customer Group Pricing Overrides <span class="label label-theme m-l-5 bg-orange">B2B</span></div>
                        <div class="col-sm-6 p-l-10 getpro" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_B2B_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_B2B_UPSELL_GET_B2B_URL; ?>" target="_blank" class="btn btn-warning btn-sm m-r-5 m-t-10"><?php echo TEXT_B2B_UPSELL_GET_B2B; ?></a></div>'>
                          <input disabled data-width="60" data-toggle="toggle" data-on="<i class='fa fa-check'></i> On" data-off="<i class='fa fa-times'></i> Off" data-onstyle="success-toggle" data-offstyle="danger" type="checkbox" data-size="mini" >
                        </div>
                      </td>
                    </tr>
                  </tbody>                           
                </table>
              </div>
              <!-- PRICING RULES end-->

              <!-- SUB PRODUCTS start-->
              <div class="ml-2 mr-2">
                <div class="main-heading m-t-30"><span class="mr-0">Sub Products</span>
                  <span class="getpro main-text f-s-12 f-w-600 mr-1" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_PRO_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_PRO_UPSELL_GET_PRO_URL; ?>" target="_blank" class="btn btn-danger btn-sm m-r-5 m-t-10"><?php echo TEXT_PRO_UPSELL_GET_PRO; ?></a></div>'><span class="label label-theme m-l-5 bg-red" style="vertical-align:20%;">PRO</span></span>
                  <span class="getpro" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_PRO_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_PRO_UPSELL_GET_PRO_URL; ?>" target="_blank" class="btn btn-danger btn-sm m-r-5 m-t-10"><?php echo TEXT_PRO_UPSELL_GET_PRO; ?></a></div>'> 
                    <input disabled data-width="60" data-toggle="toggle" data-on="<i class='fa fa-check'></i> On" data-off="<i class='fa fa-times'></i> Off" data-onstyle="success-toggle" data-offstyle="danger" type="checkbox" data-size="mini" >
                  </span>
                  <div class="main-heading-footer"></div>
                </div> 
                <table class="table">
                  <thead>
                    <tr class="th-row">
                      <th class="th-col text-center"><i class="fa fa-camera"></i></th>
                      <th class="th-col text-left">Sub Product Name</th>
                      <th class="th-col text-left hidden-xs">Model</th>
                      <th class="th-col text-right">Price</th>
                      <th class="th-col text-right hidden-xs">Action</th>
                    </tr>
                  </thead>
                </table>
              </div>
              <!-- SUB PRODUCTS end -->

              <!-- USER ACCESS SETTINGS start-->
              <div class="ml-2 mr-2">              
                <div class="main-heading m-t-30"><span>User Access Settings</span>
                  <div class="main-heading-footer"></div>
                </div>  
                <table class="table mt-2">
                  <tbody>
                    <tr class="table-row">
                      <td class="table-col dark text-left p-r-5 p-l-5 table-th-valign-middle no-border">
                        <div class="pull-left m-r-10 getpro main-text f-s-12 f-w-600" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_B2B_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_B2B_UPSELL_GET_B2B_URL; ?>" target="_blank" class="btn btn-warning btn-sm m-r-5 m-t-10"><?php echo TEXT_B2B_UPSELL_GET_B2B; ?></a></div>'>Restrict Access <span class="label label-theme m-l-5 bg-orange">B2B</span></div>
                        <div class="col-sm-6 p-l-10 getpro" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_B2B_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_B2B_UPSELL_GET_B2B_URL; ?>" target="_blank" class="btn btn-warning btn-sm m-r-5 m-t-10"><?php echo TEXT_B2B_UPSELL_GET_B2B; ?></a></div>'>
                          <input disabled data-width="60" data-toggle="toggle" data-on="<i class='fa fa-check'></i> On" data-off="<i class='fa fa-times'></i> Off" data-onstyle="success-toggle" data-offstyle="danger" type="checkbox" data-size="mini" >
                        </div>
                      </td>
                    </tr>
                  </tbody>                           
                </table>
              </div>
              <!-- USER ACCESS SETTINGS end-->   

              <!-- LEFT PANEL end -->
              </div>
              <?php
              // new product previw begin
            } elseif (isset($action) && $action == 'new_product_preview') {
              if ( (isset($_POST)) && (tep_not_null($_POST))) {
                $pInfo = new objectInfo($_POST);
                $products_name = $_POST['products_name'];
                $products_description = $_POST['products_description'];
                $products_head_title_tag = $_POST['products_head_title_tag'];
                $products_head_desc_tag = $_POST['products_head_desc_tag'];
                $products_head_keywords_tag = $_POST['products_head_keywords_tag'];
                $products_url = $_POST['products_url'];
                $products_image_destination = str_ireplace( "%2F", "/", $_POST['products_image_destination']);
                $products_image_med_destination = str_ireplace( "%2F", "/", $_POST['products_image_med_destination']);
                $products_image_lrg_destination = str_ireplace( "%2F", "/", $_POST['products_image_lrg_destination']);
                $products_image_sm_1_destination = str_ireplace( "%2F", "/", $_POST['products_image_sm_1_destination']);
                $products_image_sm_2_destination = str_ireplace( "%2F", "/", $_POST['products_image_sm_2_destination']);
                $products_image_sm_3_destination = str_ireplace( "%2F", "/", $_POST['products_image_sm_3_destination']);
                $products_image_sm_4_destination = str_ireplace( "%2F", "/", $_POST['products_image_sm_4_destination']);
                $products_image_sm_5_destination = str_ireplace( "%2F", "/", $_POST['products_image_sm_5_destination']);
                $products_image_sm_6_destination = str_ireplace( "%2F", "/", $_POST['products_image_sm_6_destination']);
                $products_image_xl_1_destination = str_ireplace( "%2F", "/", $_POST['products_image_xl_1_destination']);
                $products_image_xl_2_destination = str_ireplace( "%2F", "/", $_POST['products_image_xl_2_destination']);
                $products_image_xl_3_destination = str_ireplace( "%2F", "/", $_POST['products_image_xl_3_destination']);
                $products_image_xl_4_destination = str_ireplace( "%2F", "/", $_POST['products_image_xl_4_destination']);
                $products_image_xl_5_destination = str_ireplace( "%2F", "/", $_POST['products_image_xl_5_destination']);
                $products_image_xl_6_destination = str_ireplace( "%2F", "/", $_POST['products_image_xl_6_destination']);
              } else {
                $product_query = tep_db_query("select p.products_id, pd.language_id, pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_url, p.products_quantity, p.products_model, p.products_sku, p.products_image, p.products_image_med, p.products_image_lrg, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, p.products_price, p.products_price1, p.products_price2, p.products_price3, p.products_price4, p.products_price5, p.products_price6, p.products_price7, p.products_price8, p.products_price9, p.products_price10, p.products_price11, p.products_price1_qty, p.products_price2_qty, p.products_price3_qty, p.products_price4_qty, p.products_price5_qty, p.products_price1_qty, p.products_price7_qty, p.products_price8_qty, p.products_price9_qty, p.products_price10_qty, products_price11_qty, p.products_qty_blocks, p.products_weight, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.manufacturers_id  from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and p.products_id = '" . (int)$pID . "'");
                $product = tep_db_fetch_array($product_query);
                $pInfo = new objectInfo($product);
                $products_image_destination ='';
                $products_image_med_destination = '';
                $products_image_lrg_destination = '';
                $products_image_sm_1_destination = '';
                $products_image_sm_2_destination = '';
                $products_image_sm_3_destination = '';
                $products_image_sm_4_destination ='';
                $products_image_sm_5_destination = '';
                $products_image_sm_6_destination = '';
                $products_image_xl_1_destination = '';
                $products_image_xl_2_destination = '';
                $products_image_xl_3_destination = '';
                $products_image_xl_4_destination = '';
                $products_image_xl_5_destination = '';
                $products_image_xl_6_destination = '';
                $products_image_name = $pInfo->products_image;
                $products_image_med_name =$pInfo->products_image_med;
                $products_image_lrg_name = $pInfo->products_image_lrg;
                $products_image_sm_1_name = $pInfo->products_image_sm_1;
                $products_image_sm_2_name = $pInfo->products_image_sm_2;
                $products_image_sm_3_name = $pInfo->products_image_sm_3;
                $products_image_sm_4_name = $pInfo->products_image_sm_4;
                $products_image_sm_5_name = $pInfo->products_image_sm_5;
                $products_image_sm_6_name = $pInfo->products_image_sm_6;
                $products_image_xl_1_name = $pInfo->products_image_xl_1;
                $products_image_xl_2_name = $pInfo->products_image_xl_2;
                $products_image_xl_3_name = $pInfo->products_image_xl_3;
                $products_image_xl_4_name = $pInfo->products_image_xl_4;
                $products_image_xl_5_name = $pInfo->products_image_xl_5;
                $products_image_xl_6_name = $pInfo->products_image_xl_6;
              }
              $form_action = (!empty($pID)) ? 'update_product' : 'insert_product';
              echo tep_draw_form($form_action, FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($pID) ? '&pID=' . $pID : '') . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');
              $read = '';
              if (isset($_GET['read'])){
                $read = $_GET['read'];
              }
              $languages = tep_get_languages();
              for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                if ($read == 'only') {
                  $pInfo->products_name = tep_get_products_name($pInfo->products_id, $languages[$i]['id']);
                  $pInfo->products_description = tep_get_products_description($pInfo->products_id, $languages[$i]['id']);
                  $pInfo->products_head_title_tag = (isset($products_head_title_tag[$languages[$i]['id']]) ? tep_db_prepare_input($products_head_title_tag[$languages[$i]['id']]) : '');
                  $pInfo->products_head_desc_tag = (isset($products_head_desc_tag[$languages[$i]['id']]) ? tep_db_prepare_input($products_head_desc_tag[$languages[$i]['id']]) : '');
                  $pInfo->products_head_keywords_tag = (isset($products_head_keywords_tag[$languages[$i]['id']]) ? tep_db_prepare_input($products_head_keywords_tag[$languages[$i]['id']]) : '');
                  $pInfo->products_url = tep_get_products_url($pInfo->products_id, $languages[$i]['id']);
                } else {
                  $pInfo->products_name = tep_db_prepare_input($products_name[$languages[$i]['id']]);
                  $pInfo->products_description = tep_db_prepare_input($products_description[$languages[$i]['id']]);
                  $pInfo->products_head_title_tag = tep_db_prepare_input($products_head_title_tag[$languages[$i]['id']]);
                  $pInfo->products_head_desc_tag = tep_db_prepare_input($products_head_desc_tag[$languages[$i]['id']]);
                  $pInfo->products_head_keywords_tag = tep_db_prepare_input($products_head_keywords_tag[$languages[$i]['id']]);
                  $pInfo->products_url = tep_db_prepare_input($products_url[$languages[$i]['id']]);
                }
                ?>
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                          <td><h1 class="page-header"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $pInfo->products_name; ?></h1></td>
                          <td align="right"><h1 class="page-header"><?php echo $currencies->format($pInfo->products_price); ?></h1></td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td class="main">
                    <?php
                    if ($products_image_med_name) {
                        echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_med_destination . $products_image_med_name, $products_image_med_destination . $products_image_med_name, MEDIUM_IMAGE_WIDTH, MEDIUM_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"');
                    } elseif ($products_image_lrg_name) {
                    ?>
                      <script script type="text/javascript">
                        <!--
                        document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_lrg_destination . $products_image_lrg_name) . '\\\')">' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_med_destination . $products_image_name, $products_image_med_destination . $products_image_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . '</a>'; ?>');
                        //-->
                      </script>
                      <?php
                    } elseif ($products_image_name) {
                          echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_name, $products_image_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"');
                    }
                      echo $pInfo->products_description . '<br><br>';

                      if ($read == 'only') {
                       /* $products_extra_fields_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS . " WHERE products_id=" . (int)$pID);
                        while ($products_extra_fields = tep_db_fetch_array($products_extra_fields_query)) {
                          $extra_fields_array[$products_extra_fields['products_extra_fields_id']] = $products_extra_fields['products_extra_fields_value'];
                        }*/
                      } else {
                        //$extra_fields_array = $_POST['extra_field'];
                      }
                      ?>
                      <!--<br><br><b>Extra Fields</b><br>
                       <table border="0" cellspacing="2" cellpadding="2">
                        <?php
                       // $extra_fields_names_query = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_EXTRA_FIELDS. " WHERE languages_id='0' or languages_id='".(int)$languages[$i]['id']."' ORDER BY products_extra_fields_order");
                       // while ($extra_fields_names = tep_db_fetch_array($extra_fields_names_query)) {
                          //$extra_field_name[$extra_fields_names['products_extra_fields_id']] = //$extra_fields_names['products_extra_fields_name'];
                          ?>
                          <tr bgcolor="#F0F9FF">
                            <td class="main" align="right"><b><?php //echo (isset($extra_fields_names['products_extra_fields_name']) ? $extra_fields_names['products_extra_fields_name'] : '');?> : </b></td>
                            <td class="main" align="left"><b><?php //echo (isset($extra_fields_array[$extra_fields_names['products_extra_fields_id']]) ? $extra_fields_array[$extra_fields_names['products_extra_fields_id']] : '');?></b></td>
                          </tr>
                          <?php
                        //}
                        ?>
                      </table> -->
                      <br>
                    </td>
                  </tr>
                  <?php
                  if ($pInfo->products_url) {
                    ?>
                    <tr>
                      <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>
                    <tr>
                      <td class="main"><?php echo sprintf(TEXT_PRODUCT_MORE_INFORMATION, $pInfo->products_url); ?></td>
                    </tr>
                    <?php
                  }
                  ?>
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                  </tr>
                  <?php
                  if ($pInfo->products_date_available > date('Y-m-d')) {
                    ?>
                    <tr>
                      <td align="center" class="smallText"><?php echo sprintf(TEXT_PRODUCT_DATE_AVAILABLE, tep_date_long($pInfo->products_date_available)); ?></td>
                    </tr>
                    <?php
                  } else {
                    ?>
                    <tr>
                      <td align="center" class="smallText"><?php echo sprintf(TEXT_PRODUCT_DATE_ADDED, tep_date_long($pInfo->products_date_added)); ?></td>
                    </tr>
                    <?php
                  }
                  ?>
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                  </tr>
                  <?php
              } // end for
              ?>
              <tr>
                <td><fieldset>
                  <legend><b><!-- Extra Images --><?php echo TEXT_EXTRA_IMAGES;?></b></legend>
                  <?php if ($products_image_xl_1_name) { ?>
                  <script script type="text/javascript"><!--
                    document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_sm_1_destination . $products_image_xl_1_name) . '\\\')">' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_1_destination . $products_image_sm_1_name, $products_image_sm_1_destination . $products_image_sm_1_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</a>'; ?>');
                    //--></script>
                  <?php } elseif ($products_image_sm_1_name) { ?>
                  <?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_1_destination . $products_image_sm_1_name, $products_image_sm_1_destination . $products_image_sm_1_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); }; ?>
                  <?php if ($products_image_xl_2_name) { ?>
                  <script script type="text/javascript"><!--
                    document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_xl_2_destination . $products_image_xl_2_name) . '\\\')">' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_2_destination . $products_image_sm_2_name, $products_image_sm_2_destination . $products_image_sm_2_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</a>'; ?>');
                    //-->
                  </script>
                  <?php } elseif ($products_image_sm_2_name) { ?>
                  <?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_2_destination . $products_image_sm_2_name, $products_image_sm_2_destination . $products_image_sm_2_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); }; ?>
                  <?php if ($products_image_xl_3_name) { ?>
                  <script script type="text/javascript"><!--
                    document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_xl_3_destination . $products_image_xl_3_name) . '\\\')">' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_3_destination . $products_image_sm_3_name, $products_image_sm_3_destination . $products_image_sm_3_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</a>'; ?>');
                   //-->
                  </script>
                  <?php } elseif ($products_image_sm_3_name) { ?>
                  <?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_3_destination . $products_image_sm_3_name, $products_image_sm_3_destination . $products_image_sm_3_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); }; ?>
                  <?php if ($products_image_xl_4_name) { ?>
                  <script script type="text/javascript"><!--
                    document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_xl_4_destination . $products_image_xl_4_name) . '\\\')">' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_4_destination . $products_image_sm_4_name, $products_image_sm_4_destination . $products_image_sm_4_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</a>'; ?>');
                    //-->
                  </script>
                  <?php } elseif ($products_image_sm_4_name) { ?>
                  <?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_4_destination . $products_image_sm_4_name, $products_image_sm_4_destination . $products_image_sm_4_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); }; ?>
                  <?php if ($products_image_xl_5_name) { ?>
                  <script script type="text/javascript"><!--
                    document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_xl_5_destination . $products_image_xl_5_name) . '\\\')">' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_5_destination . $products_image_sm_5_name, $products_image_sm_5_destination . $products_image_sm_5_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"') . '</a>'; ?>');
                    //-->
                  </script>
                  <?php } elseif ($products_image_sm_5_name) { ?>
                  <?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_5_destination . $products_image_sm_5_name, $products_image_sm_5_destination . $products_image_sm_5_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="5" vspace="5"'); }; ?>
                  <?php if ($products_image_xl_6_name) { ?>
                  <script script type="text/javascript"><!--
                    document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_xl_6_destination . $products_image_xl_6_name) . '\\\')">' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_6_destination . $products_image_sm_6_name, $products_image_sm_6_destination . $products_image_sm_6_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="6" vspace="6"') . '</a>'; ?>');
                    //-->
                  </script>
                  <?php } elseif ($products_image_sm_6_name) { ?>
                  <?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_sm_6_destination . $products_image_sm_6_name, $products_image_sm_6_destination . $products_image_sm_6_name, ULT_THUMB_IMAGE_WIDTH, ULT_THUMB_IMAGE_HEIGHT, 'align="center" hspace="6" vspace="6"'); };?>
                </fieldset></td>
              </tr>
              <?php
              if (isset($read) && ($read == 'only')) {
                if (isset($_GET['origin'])) {
                  $pos_params = strpos($_GET['origin'], '?', 0);
                  if ($pos_params != false) {
                    $back_url = substr($_GET['origin'], 0, $pos_params);
                    $back_url_params = substr($_GET['origin'], $pos_params + 1);
                  } else {
                    $back_url = $_GET['origin'];
                    $back_url_params = '';
                  }
                } else {
                  $back_url = FILENAME_CATEGORIES;
                  $back_url_params = 'cPath=' . $cPath . '&pID=' . $pInfo->products_id;
                }
                ?>
                <tr>
                  <td align="right"><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
                </tr>
                <?php
              } else {
                ?>
                <tr>
                  <td align="right" class="smallText">
                    <?php
                    reset($_POST);
                    while (list($key, $value) = each($_POST)) {
                      if (!is_array($_POST[$key])) {
                        echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value))) . "\n";
                      } else {
                        while (list($k, $v) = each($value)) {
                         echo tep_draw_hidden_field($key . '[' . $k . ']', htmlspecialchars(stripslashes($v))) . "\n";
                        }
                      }
                    }
                    if ($_POST['extra_field']) { // Check to see if there are any need to update extra fields.
                      foreach ($_POST['extra_field'] as $key=>$val) {
                        echo tep_draw_hidden_field('extra_field['.$key.']', stripslashes($val)) . "\n";
                      }
                    } // Check to see if there are any need to update extra fields.
                    echo tep_draw_hidden_field('products_image', stripslashes($products_image_destination . $products_image_name)) . "\n";
                    echo tep_draw_hidden_field('products_image_med', stripslashes($products_image_med_destination . $products_image_med_name)) . "\n";
                    echo tep_draw_hidden_field('products_image_lrg', stripslashes($products_image_lrg_destination . $products_image_lrg_name)) . "\n";
                    echo tep_draw_hidden_field('products_image_sm_1', stripslashes($products_image_sm_1_destination . $products_image_sm_1_name)) . "\n";
                    echo tep_draw_hidden_field('products_image_xl_1', stripslashes($products_image_xl_1_destination . $products_image_xl_1_name)) . "\n";
                    echo tep_draw_hidden_field('products_image_sm_2', stripslashes($products_image_sm_2_destination . $products_image_sm_2_name)) . "\n";
                    echo tep_draw_hidden_field('products_image_xl_2', stripslashes($products_image_xl_2_destination . $products_image_xl_2_name)) . "\n";
                    echo tep_draw_hidden_field('products_image_sm_3', stripslashes($products_image_sm_3_destination . $products_image_sm_3_name)) . "\n";
                    echo tep_draw_hidden_field('products_image_xl_3', stripslashes($products_image_xl_3_destination . $products_image_xl_3_name)) . "\n";
                    echo tep_draw_hidden_field('products_image_sm_4', stripslashes($products_image_sm_4_destination . $products_image_sm_4_name)) . "\n";
                    echo tep_draw_hidden_field('products_image_xl_4', stripslashes($products_image_xl_4_destination . $products_image_xl_4_name)) . "\n";
                    echo tep_draw_hidden_field('products_image_sm_5', stripslashes($products_image_sm_5_destination . $products_image_sm_5_name)) . "\n";
                    echo tep_draw_hidden_field('products_image_xl_5', stripslashes($products_image_xl_5_destination . $products_image_xl_5_name)) . "\n";
                    echo tep_draw_hidden_field('products_image_sm_6', stripslashes($products_image_sm_6_destination . $products_image_sm_6_name)) . "\n";
                    echo tep_draw_hidden_field('products_image_xl_6', stripslashes($products_image_xl_6_destination . $products_image_xl_6_name)) . "\n";
                    echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;' . "\n";
                    if (!empty($pID)) {
                      echo tep_image_submit('button_update.gif', IMAGE_UPDATE) . "\n";;
                    } else {
                      echo tep_image_submit('button_insert.gif', IMAGE_INSERT) . "\n";;
                    }
                    echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($pID) && (int)$pID > 0 ? '&pID=' . $pID : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
                    ?>
                  </td>
                </tr>
              </table>
              </form>
              <?php
              }
            } else {
              ?>
              <table class="table table-hover w-100 mt-2">
                <?php
                // RCI start
                echo $cre_RCI->get('categories', 'listingtop');
                // RCI eof
                ?>                
                <thead>
                  <tr class="th-row">
                    <th scope="col" class="th-col dark text-left"><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></th>
                    <th scope="col" class="th-col dark text-center d-none d-lg-table-cell col-blank"><?php echo TABLE_HEADING_STATUS; ?></th>
                    <th scope="col" class="th-col dark text-right"><?php echo TABLE_HEADING_ACTION; ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $categories_count = 0;
                  $rows = 0;
                  if (isset($_POST['search'])) {
                    $search = str_replace("'", "&#39;", tep_db_prepare_input($_POST['search']));
                    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and cd.categories_name like '%" . tep_db_input($search) . "%' order by c.sort_order, cd.categories_name");
                  } else {
                    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
                  }
                  while ($categories = tep_db_fetch_array($categories_query)) {
                    if (empty($cID)){
                      $cID = $categories['categories_id'];
                    }
                    $categories_count++;
                    $rows++;
                    if (isset($_POST['search'])) $cPath= $categories['parent_id'];
                    if ((!isset($_GET['cID']) && !isset($_GET['pID']) || (isset($_GET['cID']) && ($_GET['cID'] == $categories['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
                      $category_childs = array('childs_count' => tep_childs_in_category_count($categories['categories_id']));
                      $category_products = array('products_count' => tep_products_in_category_count($categories['categories_id']));
                      $cInfo_array = array_merge($categories, $category_childs, $category_products);
                      $cInfo = new objectInfo($cInfo_array);
                    }
                    $selected = (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id)) ? true : false;
                    if ($selected) {
                      echo '<tr class="table-row dark selected" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '\'">' . "\n";
                    } else {
                      echo '<tr class="table-row dark" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '\'">' . "\n";
                    }
                    $col_selected = ($selected) ? ' selected' : '';
                    ?>
                      <td class="table-col dark text-left<?php echo $col_selected; ?>"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '"><i class="fa fa-folder fa-lg text-warning mr-2"></i></a><b>' . $categories['categories_name'] . '</b>'; ?></td>
                      <td class="table-col dark text-left<?php echo $col_selected; ?> d-none d-lg-table-cell col-blank">&nbsp;</td>
                      <td class="table-col dark text-right<?php echo $col_selected; ?>"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id'] . '&action=edit_category') . '"><i class="fa fa-edit fa-lg text-success"></i></a>'; ?>
                        <?php
                        if ($selected) {
                          echo '<i class="fa fa-long-arrow-right fa-lg text-success" style="margin-left:1px;"></i>';
                        } else {
                          echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '"><i class="fa fa-info-circle fa-lg text-muted ml-1"></i></a></a>';
                        }
                        ?>
                      </td>
                    </tr>
                    <?php
                    }

                    $products_count = 0;
                    if (isset($_POST['search'])) {
                      $search = str_replace("'", "&#39;", tep_db_prepare_input($_POST['search']));
                      $products_query = tep_db_query("SELECT p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_model, p.products_sku, p2c.categories_id
                                                      FROM " . TABLE_PRODUCTS . " p,
                                                           " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                                           " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c
                                                      WHERE p.products_id = pd.products_id
                                                        and pd.language_id = " . (int)$languages_id . "
                                                        and p.products_id = p2c.products_id
                                                        and (pd.products_name like '%" . tep_db_input($search) . "%' or
                                                             p.products_model like '%" . tep_db_input($search) . "%')
                                                             p.products_sku like '%" . tep_db_input($search) . "%')
                                                      ORDER BY pd.products_name");
                    } else {
                      $products_query = tep_db_query("SELECT p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_model, p.products_sku, p2c.categories_id
                                                      FROM " . TABLE_PRODUCTS . " p,
                                                           " . TABLE_PRODUCTS_DESCRIPTION . " pd,
                                                           " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c
                                                      WHERE p.products_id = pd.products_id
                                                        and pd.language_id = " . (int)$languages_id . "
                                                        and p.products_id = p2c.products_id
                                                        and p2c.categories_id = " . (int)$current_category_id . "
                                                        and p.products_parent_id = 0
                                                      ORDER BY pd.products_name");
                    }
                    // products listing loop
                    while ($products = tep_db_fetch_array($products_query)) {
                      if (empty($pID)){
                        $pID = $products['products_id'];
                      }
                      $products_count++;
                      $rows++;
                      // Get categories_id for product if search
                      if (isset($_POST['search'])) {
                        $product_category_query = tep_db_query("SELECT categories_id
                                                      FROM " . TABLE_PRODUCTS_TO_CATEGORIES . "
                                                      WHERE products_id = " . $products['products_id'] . "
                                                      ORDER BY categories_id");
                        $prodcats = tep_db_fetch_array($product_category_query);
                        $cPath = $prodcats['categories_id'];
                      }
                      if ( (!isset($_GET['pID']) && !isset($_GET['cID']) || (isset($_GET['pID']) && ($_GET['pID'] == $products['products_id']))) && !isset($pInfo) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
                        // find out the rating average from customer reviews
                        $reviews_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . (int)$products['products_id'] . "'");
                        $reviews = tep_db_fetch_array($reviews_query);
                        $pInfo_array = array_merge($products, $reviews);
                        $pInfo = new objectInfo($pInfo_array);
                      }
                      // RCO start plistrows
                      if ($cre_RCO->get('categories', 'plistrows') !== true) {

                        $pselected = (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id)) ? true : false;
                        if ($pselected) {
                          echo '<tr class="table-row dark selected" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only')  . '\'">' . "\n";
                        } else {
                          echo '<tr class="table-row dark" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '\'">' . "\n";
                        }
                        $pcol_selected = ($pselected) ? ' selected' : '';

                        ?>
                          <td class="table-col dark text-left<?php echo $col_selected; ?>"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '"><i class="fa fa-search fa-lg text-info mr-2"></i></a>' . $products['products_name']; ?></td>
                          <td class="table-col dark text-center<?php echo $col_selected; ?>">
                          <?php
                            if ($products['products_status'] == '1') {
                              echo '<i class="fa fa-lg fa-check-circle text-success mr-2"></i><a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '"><i class="fa fa-lg fa-times-circle text-secondary"></i></a>';
                            } else {
                              echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '"><i class="fa fa-lg fa-times-circle text-danger"></i></a>&nbsp;&nbsp;<i class="fa fa-lg fa-check-circle text-secondary"></i>';
                            }
                            ?>
                          </td>
                          <td class="table-col dark text-right<?php echo $col_selected; ?>"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product') . '"><i class="fa fa-edit fa-lg text-success"></i></a>'; ?>
                          <?php
                          if ($pselected) {
                            echo '<i class="fa fa-long-arrow-right fa-lg text-success" style="margin-left:1px;"></i>';
                          } else {
                            echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '"><i class="fa fa-info-circle fa-lg text-muted ml-1"></i></a></a>';
                          }
                          ?>&nbsp;
                          </td>
                        </tr>
                        <?php
                      // RCO end plistrows
                      }
                    }
                    // end products listing loop
                    $cPath_back = '';
                    if (sizeof($cPath_array) > 0) {
                      for ($i=0, $n=sizeof($cPath_array)-1; $i<$n; $i++) {
                        if (empty($cPath_back)) {
                          $cPath_back .= $cPath_array[$i];
                        } else {
                          $cPath_back .= '_' . $cPath_array[$i];
                        }
                      }
                    }
                    $cPath_back = (tep_not_null($cPath_back)) ? 'cPath=' . $cPath_back . '&' : '';
                    ?>
                </tbody>
              </table>
              <div class="row">
                <div class="col mt-1 w-100">
                  <div class="float-right mr-2 mt-0 mb-3" role="group">
                    <?php
                    if (sizeof($cPath_array) > 0) { 
                      echo '<button class="btn btn-default btn-sm mr-1" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, $cPath_back . 'cID=' . $current_category_id) . '\'">' . IMAGE_BACK . '</button>';
                    }
                    // RCO start listing buttons
                    if ($cre_RCO->get('categories', 'listingbuttons') !== true) {
                      if (!isset($_POST['search'])) {                
                        echo '<button class="btn btn-success btn-sm ml-1" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_category') . '\'">' . IMAGE_NEW_CATEGORY . '</button><button class="btn btn-success btn-sm ml-2" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_product')  . '\'">' . IMAGE_NEW_PRODUCT . '</button>'; 
                      }
                    }
                    // RCO eof                  
                    ?>
                  </div>              
                </div>              
              </div>              
              <div class="rci-categories-listingbottom">
                <?php
                // RCI code start
                echo $cre_RCI->get('categories', 'listingbottom');
                // RCI code eof
                ?>                
              </div>
              <?php 
            }
            ?>
          </div>

          <div class="col-md-4 col-xl-3 dark panel-right rounded-right">           
            <?php
            $heading = array();
            $contents = array();
            switch ($action) {
              case 'new_category':
                $heading[] = array('text' => TEXT_INFO_HEADING_NEW_CATEGORY);
                $contents = array('form' => tep_draw_form('newcategory', FILENAME_CATEGORIES, 'action=insert_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"'));
                $contents[] = array('text' => TEXT_NEW_CATEGORY_INTRO);
                $category_inputs_string = '';
                $languages = tep_get_languages();
                for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                  $category_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']');
                }
                $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_NAME . $category_inputs_string);
                $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
                $contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', '', 'size="2"'));
                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                break;
              case 'delete_category':
                $heading[] = array('text' => TEXT_INFO_HEADING_DELETE_CATEGORY);
                $contents[] = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
                if ($cInfo->childs_count > 0) $contents[] = array('text' => '<div class="row"><div class="col p-0 mt-3 ml-2 mr-2"><div class="note note-warning m-0"><h4 class="m-0">' . TEXT_WARNING . '</h4><p class="mb-0 mt-2">' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count) . '</p></div></div></div>');     
                if ($cInfo->products_count > 0) $contents[] = array('text' => '<div class="row"><div class="col p-0 mt-3 ml-2 mr-2"><div class="note note-warning m-0"><h4 class="m-0">' . TEXT_WARNING . '</h4><p class="mb-0 mt-2">' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count) . '</p></div></div></div>');     
                $contents[] = array('text' => '<div class="row"><div class="col p-0 mt-3 ml-2 mr-2"><div class="note note-danger m-0 fw-400">' . sprintf(TEXT_DELETE_CATEGORY_INTRO, $cInfo->categories_name) . '</div></div></div>');
                $contents[] = array('align' => 'center', 'text' => '<button type="button" class="btn btn-default btn-sm mr-2 mt-3 mb-4" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id)  . '\'">' . IMAGE_CANCEL . '</button><button class="btn btn-danger btn-sm mt-3 mb-4" type="submit">' . IMAGE_CONFIRM_DELETE . '</button>');
                break;
              case 'move_category':
                $heading[] = array('text' => TEXT_INFO_HEADING_MOVE_CATEGORY);
                $contents[] = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=move_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
                $contents[] = array('text' => '<div class="sidebar-text mt-3">' . sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name) . '</div>');
                $contents[] = array('text' => '<div class="sidebar-title mt-2">' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '</div><div>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id) . '</div>');
                $contents[] = array('align' => 'center', 'text' => '<button type="button" class="btn btn-default btn-sm mr-2 mt-3 mb-4" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '\'">' . IMAGE_CANCEL . '</button><button class="btn btn-info btn-sm mt-3 mb-4" type="submit">' . IMAGE_MOVE . '</button>');
                break;
              case 'delete_product':
                $heading[] = array('text' => TEXT_INFO_HEADING_DELETE_PRODUCT);
                $contents[] = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=delete_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
                $contents[] = array('text' => '<div class="row"><div class="col p-0 mt-3 ml-2 mr-2"><div class="note note-danger m-0 fw-400">' . sprintf(TEXT_DELETE_PRODUCT_INTRO, $pInfo->products_name) . '</div></div></div>');
                $product_categories_string = '';
                $product_categories = tep_generate_category_path($pInfo->products_id, 'product');
                for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
                  $category_path = '';
                  for ($j = 0, $k = sizeof($product_categories[$i]); $j < $k; $j++) {
                    $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
                  }
                  $category_path = substr($category_path, 0, -16);
                  $product_categories_string .= tep_draw_checkbox_field('product_categories[]', $product_categories[$i][sizeof($product_categories[$i])-1]['id'], true) . '<span class="sidebar-text ml-2">' . $category_path . '</span><br>';
                }
                $product_categories_string = substr($product_categories_string, 0, -4);
                $contents[] = array('text' => '<br>' . $product_categories_string);
                $contents[] = array('align' => 'center', 'text' => '<button type="button" class="btn btn-default btn-sm mr-2 mt-3 mb-4" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '\'">' . IMAGE_CANCEL . '</button><button class="btn btn-danger btn-sm mt-3 mb-4" type="submit">' . IMAGE_CONFIRM_DELETE . '</button>');
                break;
              case 'move_product':
                $heading[] = array('text' => TEXT_INFO_HEADING_MOVE_PRODUCT);
                $contents[] = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=move_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
                $contents[] = array('text' => '<div class="sidebar-text mt-3">' . sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name));
                $contents[] = array('text' => '<div class="sidebar-text mt-3">' . TEXT_INFO_CURRENT_CATEGORIES . '</div><div class="sidebar-title">' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</div>');
                $contents[] = array('text' => '<div class="sidebar-title mt-3">' . sprintf(TEXT_MOVE, $pInfo->products_name) . '</div><div>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id) . '</div>');
                $contents[] = array('align' => 'center', 'text' => '<button type="button" class="btn btn-default btn-sm mr-2 mt-3 mb-4" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '\'">' . IMAGE_CANCEL . '</button><button class="btn btn-info btn-sm mt-3 mb-4" type="submit">' . IMAGE_MOVE . '</button>');
                break;
              case 'copy_to':
                $heading[] = array('text' => TEXT_INFO_HEADING_COPY_TO);
                $contents[] = array('form' => tep_draw_form('copy_to', FILENAME_CATEGORIES, 'action=copy_to_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
                $contents[] = array('text' => '<div class="sidebar-text mt-3">' . TEXT_INFO_COPY_TO_INTRO . '</div>');
                $contents[] = array('text' => '<div class="sidebar-text mt-3">' . TEXT_INFO_CURRENT_CATEGORIES . '</div><div class="sidebar-title">' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</div>');
                $contents[] = array('text' => '<div class="sidebar-text mt-3">' . TEXT_CATEGORIES . '</div><div>' . tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id) . '</div>');
                $contents[] = array('text' => '<div class="sidebar-text mt-3">' . TEXT_HOW_TO_COPY . '</div><div class="mt-2">' . tep_draw_radio_field('copy_as', 'link', true, null, 'onchange="setAttrText(\'link\');"') . '<span class="sidebar-text ml-1">' . TEXT_COPY_AS_LINK . '</span></div><div class="mt-2">' . tep_draw_radio_field('copy_as', 'duplicate', false, null, 'onchange="setAttrText(\'dupe\');"') . '<span class="sidebar-text ml-1">' . TEXT_COPY_AS_DUPLICATE . '</span></div>');
                // only ask about attributes if they exist
                if (tep_has_product_attributes($pInfo->products_id)) {
                  $contents[] = array('text' => '<div id="copy_attr" style="display:none;"><div class="sidebar-text mt-3 mb-3">' . TEXT_COPY_ATTRIBUTES . '</div><div class="mt-2">' . tep_draw_radio_field('copy_attributes', 'copy_attributes_yes', true) . '<span class="sidebar-text ml-1">' . TEXT_COPY_ATTRIBUTES_YES . '</span></div><div class="mt-2">' . tep_draw_radio_field('copy_attributes', 'copy_attributes_no') . '<span class="sidebar-text ml-1">' . TEXT_COPY_ATTRIBUTES_NO . '</span></div></div>');
                  $contents[] = array('align' => 'center', 'text' => '<div class="mt-3 mb-0"><button class="btn btn-success btn-sm mt-0" data-toggle="modal" data-target="#listAttributesModal">' . TEXT_LIST_ATTRIBUTES . '</button></div>');
                }
                $contents[] = array('align' => 'center', 'text' => '<button type="button" class="btn btn-default btn-sm mr-2 mt-2 mb-4" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '\'">' . IMAGE_CANCEL . '</button><button class="btn btn-info btn-sm mt-2 mb-4" type="submit">' . IMAGE_COPY . '</button>');
                break;
              case 'copy_product_attributes':
                $copy_attributes_delete_first='1';
                $copy_attributes_duplicates_skipped='1';
                $copy_attributes_duplicates_overwrite='0';
                if (DOWNLOAD_ENABLED == 'true') {
                  $copy_attributes_include_downloads='1';
                  $copy_attributes_include_filename='1';
                } else {
                  $copy_attributes_include_downloads='0';
                  $copy_attributes_include_filename='0';
                }
                $heading[] = array('text' => TEXT_COPY_ATTRIBUTES_TO_ANOTHER_PRODUCT);
                $contents[] = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=create_copy_product_attributes&cPath=' . $cPath . '&pID=' . $pInfo->products_id) . tep_draw_hidden_field('products_id', $pInfo->products_id) . tep_draw_hidden_field('products_name', $pInfo->products_name));
                $contents[] = array('text' => '<br>'.TEXT_COPYING_ATTRIBUTES_FROM.' #' . $pInfo->products_id . '<br><b>' . $pInfo->products_name . '</b>');
                $contents[] = array('text' => TEXT_COPYING_ATTRIBUTES_TO.' #&nbsp;' . tep_draw_input_field('copy_to_products_id', $copy_to_products_id, 'size="3"'));
                $contents[] = array('text' => '<br>'.TEXT_DELETE_ALL_ATTRIBUTE.'&nbsp;' . tep_draw_checkbox_field('copy_attributes_delete_first',$copy_attributes_delete_first, 'size="2"'));
                $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
                $contents[] = array('text' => '<br>' . TEXT_OTHERWISE);
                $contents[] = array('text' => TEXT_DUPLICATE_ATTRIBUTES_SKIPPED.'&nbsp;' . tep_draw_checkbox_field('copy_attributes_duplicates_skipped',$copy_attributes_duplicates_skipped, 'size="2"'));
                $contents[] = array('text' => '&nbsp;&nbsp;&nbsp;'.TEXT_DUPLICATE_ATTRIBUTES_OVERWRITTEN.'&nbsp;' . tep_draw_checkbox_field('copy_attributes_duplicates_overwrite',$copy_attributes_duplicates_overwrite, 'size="2"'));
                if (DOWNLOAD_ENABLED == 'true') {
                  $contents[] = array('text' => '<br>'.TEXT_COPY_ATTRIBUTES_WITH_DOWNLOADS.'&nbsp;' . tep_draw_checkbox_field('copy_attributes_include_downloads',$copy_attributes_include_downloads, 'size="2"'));
                }
                $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
                $contents[] = array('align' => 'center', 'text' => '<br>' . PRODUCT_NAMES_HELPER);
                if ($pID) {
                  $contents[] = array('align' => 'center', 'text' => '<br>' . ATTRIBUTES_NAMES_HELPER);
                } else {
                  $contents[] = array('align' => 'center', 'text' => '<br>'.TEXT_SELECT_PRODUCT_FOR_DISPLAY);
                }
                $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_copy.gif', TEXT_BUTTON_COPY_ATTRIBUTES));
                break;
              case 'copy_product_attributes_categories':
                $copy_attributes_delete_first='1';
                $copy_attributes_duplicates_skipped='1';
                $copy_attributes_duplicates_overwrite='0';
                if (DOWNLOAD_ENABLED == 'true') {
                  $copy_attributes_include_downloads='1';
                  $copy_attributes_include_filename='1';
                } else {
                  $copy_attributes_include_downloads='0';
                  $copy_attributes_include_filename='0';
                }
                $heading[] = array('text' => TEXT_COPY_PRODUCT_ATTRIBUTES_TO_CATEGORY);
                $contents[] = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=create_copy_product_attributes_categories&cPath=' . $cPath . '&cID=' . $cID . '&make_copy_from_products_id=' . $copy_from_products_id));
                $contents[] = array('text' => TEXT_COPY_PRODUCT_ATTRIBUTES_FROM_PRODUCT_ID.'#&nbsp;' . tep_draw_input_field('make_copy_from_products_id', $make_copy_from_products_id, 'size="3"'));
                $contents[] = array('text' => '<br>'.TEXT_COPYING_TO_ALL_PRODUCTS_IN_CATEGORY_ID.'#&nbsp;' . $cID . '<br>'.TEXT_CATEGORY_NAME.' <b>' . tep_get_category_name($cID, $languages_id) . '</b>');
                $contents[] = array('text' => '<br>'.TEXT_DELETE_ALL_ATTRIBUTE.'&nbsp;' . tep_draw_checkbox_field('copy_attributes_delete_first',$copy_attributes_delete_first, 'size="2"'));
                $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
                $contents[] = array('text' => '<br>' . TEXT_OTHERWISE);
                $contents[] = array('text' => TEXT_DUPLICATE_ATTRIBUTES_SKIPPED.'&nbsp;' . tep_draw_checkbox_field('copy_attributes_duplicates_skipped',$copy_attributes_duplicates_skipped, 'size="2"'));
                $contents[] = array('text' => '&nbsp;&nbsp;&nbsp;'.TEXT_DUPLICATE_ATTRIBUTES_OVERWRITTEN.'&nbsp;' . tep_draw_checkbox_field('copy_attributes_duplicates_overwrite',$copy_attributes_duplicates_overwrite, 'size="2"'));
                if (DOWNLOAD_ENABLED == 'true') {
                  $contents[] = array('text' => '<br>'.TEXT_COPY_ATTRIBUTES_WITH_DOWNLOADS.'&nbsp;' . tep_draw_checkbox_field('copy_attributes_include_downloads',$copy_attributes_include_downloads, 'size="2"'));
                }
                $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
                $contents[] = array('align' => 'center', 'text' => '<br>' . TEXT_ATTRIBUTES_PRODUCT_LOOKUP);
                $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cID) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_copy.gif', 'Copy Attribtues'));
                break;
              case 'new_product':
                  $heading[] = array('text' => 'Pricing and Availability');
                  $contents[] = array('text' => '
                    <div class="sidebar-content-container">

                      <div class="form-group row">
                        <label class="col-sm-5 sidebar-text mt-2 col-form-label pl-3 pr-0">' . TEXT_PRODUCTS_BASE_PRICE . '</label>
                        <div class="col-sm-7">
                          <div class="input-group"> <span class="input-group-addon text-white bg-blue-lighter p-5">$</span>' .
                            tep_draw_input_field('products_price_gross', number_format($pInfo->products_price, 2), 'class="form-control f-w-600 f-s-14 p-l-5 p-r-5 text-primary"') . '
                          </div>
                        </div>
                      </div>                     

                      <div class="form-group row mt-3">
                        <label class="col-sm-5 sidebar-text mt-2 col-form-label pl-3 pr-0">Special Price</label>
                        <div class="col-sm-7">
                          <div class="input-group"> <span class="input-group-addon text-white bg-red-lighter p-5 ">$</span>' . 
                            tep_draw_input_field('products_special_price', number_format(Specials::getSpecialPrice($pInfo->products_id), 2), 'class="form-control f-s-14 p-l-5 p-r-5 text-danger"') . '
                          </div>
                        </div>
                      </div>

                      <div class="form-group row mt-3" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content="<div class=\'text-white\'>' . TEXT_PRO_UPSELL_POPOVER_BODY . '</div><div class=\'text-center w-100\'><a href=\''. TEXT_PRO_UPSELL_GET_PRO_URL . '\' target=\'_blank\' class=\'btn btn-danger btn-sm m-r-5 m-t-10\'>' . TEXT_PRO_UPSELL_GET_PRO . '</a></div>">
                        <label class="col-sm-5 sidebar-text mt-2 col-form-label pl-3 pr-0 getpro mb-1">MSRP<span class="label label-theme ml-2 bg-red">PRO</span></label>
                        <div class="col-sm-7">
                          <div class="input-group "> <span class="input-group-addon text-white bg-silver-darker p-5">$</span>
                            <input disabled class="form-control f-s-14 p-l-5 p-r-5" type="text" value=""> 
                          </div>
                        </div>
                      </div>

                      <div class="form-group row mt-3">
                        <label class="col-sm-5 sidebar-text mt-2 col-form-label pl-3 pr-0">' . TEXT_PRODUCTS_DATE_AVAILABLE . '</label>
                        <div class="col-sm-7">
                          <input name="products_date_available" value="' . tep_date_short($pInfo->products_date_available) . '" type="text" class="form-control" id="datepicker-autoClose" placeholder="MM/DD/YYYY" />
                        </div>
                      </div>

                      <div class="form-group row mt-3">
                        <label class="col-sm-5 sidebar-text mt-2 col-form-label pl-3 pr-0">' . TEXT_PRODUCTS_STATUS . '</label>
                        <div class="col-sm-7">
                          <input name="products_status" ' . (($in_status) ? 'checked' : '') . ' data-toggle="toggle" data-on="<i class=\'fa fa-check\'></i> Active" data-off="<i class=\'fa fa-times\'></i> Inactive" data-onstyle="success" data-offstyle="danger" type="checkbox" data-size="small">
                        </div>
                      </div>

                      <div class="form-group row mt-3">
                        <label class="col-sm-5 sidebar-text mt-2 col-form-label pl-3 pr-0">Featured</label>
                        <div class="col-sm-7">
                          <input checked data-toggle="toggle" data-on="<i class=\'fa fa-star\'></i> Yes" data-off="<i class=\'fa fa-star-o\'></i> No" data-onstyle="success" data-offstyle="danger" type="checkbox" data-size="small">
                        </div>
                      </div>
                    </div>

                    <div class="sidebar-heading mt-3">    
                      <span>Inventory</span>  
                    </div><div class="sidebar-heading-footer w-100"></div>

                    <div class="form-group row mt-3">
                      <label class="col-sm-5 sidebar-text mt-2 col-form-label pl-3 pr-0">' . TEXT_PRODUCTS_MODEL . '</label>
                      <div class="col-sm-7">' . tep_draw_input_field('products_model', $pInfo->products_model, 'class="form-control"') . '</div>
                    </div>

                    <div class="form-group row mt-3">
                      <label class="col-sm-5 sidebar-text mt-2 col-form-label pl-3 pr-0">' . TEXT_PRODUCTS_SKU . '</label>
                      <div class="col-sm-7">' . tep_draw_input_field('products_sku', $pInfo->products_sku, 'class="form-control"') . '
                      </div>
                    </div>
                    <div class="form-group row mt-3">
                      <label class="col-sm-5 sidebar-text mt-2 col-form-label pl-3 pr-0">' . TEXT_PRODUCTS_MANUFACTURER . '</label>
                      <div class="col-sm-7">' . tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id, 'class="form-control"') . '</div>
                    </div>

                    <div class="form-group row mt-3">
                      <label class="col-sm-5 sidebar-text mt-2 col-form-label pl-3 pr-0">' . TEXT_PRODUCTS_TAX_CLASS . '</label>
                      <div class="col-sm-7">' . tep_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id, 'class="form-control"') . '</div>
                    </div>

                    <div class="form-group row mt-3">
                      <label class="col-sm-5 sidebar-text mt-2 col-form-label pl-3 pr-0">' . TEXT_PRODUCTS_WEIGHT . '</label>
                      <div class="col-sm-7 input-group">' . tep_draw_input_field('products_weight', $pInfo->products_weight, 'class="form-control"') . '</div>
                    </div>

                    <div class="form-group row mt-3">
                      <label class="col-sm-5 sidebar-text mt-2 col-form-label pl-3 pr-0">' . TEXT_PRODUCTS_QUANTITY . '</label>
                      <div class="col-sm-7">' . tep_draw_input_field('products_quantity', $pInfo->products_quantity, 'class="form-control"') . '</div>
                    </div>

                    <div class="form-group row mt-3" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content="<div class=\'text-white\'>' . TEXT_PRO_UPSELL_POPOVER_BODY . '</div><div class=\'text-center w-100\'><a href=\''. TEXT_PRO_UPSELL_GET_PRO_URL . '\' target=\'_blank\' class=\'btn btn-danger btn-sm m-r-5 m-t-10\'>' . TEXT_PRO_UPSELL_GET_PRO . '</a></div>">
                      <label class="col-sm-5 sidebar-text mt-2 col-form-label pl-3 pr-0 text-muted getpro mb-1">Item Cost<span class="label label-theme ml-2 bg-red">PRO</span></label>
                      <div class="col-sm-7">
                        <input disabled class="form-control" type="text">
                      </div>
                    </div>

                    <div class="form-group row mt-3" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content="<div class=\'text-white\'>' . TEXT_PRO_UPSELL_POPOVER_BODY . '</div><div class=\'text-center w-100\'><a href=\''. TEXT_PRO_UPSELL_GET_PRO_URL . '\' target=\'_blank\' class=\'btn btn-danger btn-sm m-r-5 m-t-10\'>' . TEXT_PRO_UPSELL_GET_PRO . '</a></div>">
                      <label class="col-sm-5 sidebar-text mt-2 col-form-label pl-3 pr-0 text-muted getpro mb-1">Vendor<span class="label label-theme ml-2 bg-red">PRO</span></label>
                      <div class="col-sm-7">
                        <select disabled class="form-control">
                          <option>Select Vendor</option>
                          <option>Gadgets 2000</option>
                          <option>Amazon</option>
                          <option>ebay</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row mt-3" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content="<div class=\'text-white\'>' . TEXT_PRO_UPSELL_POPOVER_BODY . '</div><div class=\'text-center w-100\'><a href=\''. TEXT_PRO_UPSELL_GET_PRO_URL . '\' target=\'_blank\' class=\'btn btn-danger btn-sm m-r-5 m-t-10\'>' . TEXT_PRO_UPSELL_GET_PRO . '</a></div>">
                      <label class="col-sm-5 sidebar-text mt-2 col-form-label pl-3 pr-0 text-muted getpro mb-1">Vendor Note<span class="label label-theme ml-2 bg-red">PRO</span></label>
                      <div class="col-sm-7">
                        <textarea disabled class="form-control" id="vendornote" name="vendornote" rows="3"></textarea>
                      </div>
                    </div>

                    <div class="sidebar-heading mt-3">    
                      <span>Catalog</span>  
                    </div><div class="sidebar-heading-footer w-100"></div>

                    <div class="form-group row mt-3" data-toggle="popover" data-placement="top" data-html="true" data-content="<div class=\'text-white\'>' . TEXT_PRO_UPSELL_POPOVER_BODY . '</div><div class=\'text-center w-100\'><a href=\''. TEXT_PRO_UPSELL_GET_PRO_URL . '\' target=\'_blank\' class=\'btn btn-danger btn-sm m-r-5 m-t-10\'>' . TEXT_PRO_UPSELL_GET_PRO . '</a></div>">
                      <label class="col-sm-5 sidebar-text col-form-label pl-3 pr-0 getpro mb-1">Sort Order<span class="label label-theme ml-2 bg-red">PRO</span></label></label>
                      <div class="col-sm-7">
                        <input disabled class="form-control" type="text">
                      </div>
                    </div>      

                    <div class="sidebar-heading mt-3">    
                      <span>Extra Fields</span>  
                    </div><div class="sidebar-heading-footer w-100"></div>                                  

                    <div class="form-group row mt-3 mb-3" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content="<div class=\'text-white\'>' . TEXT_PRO_UPSELL_POPOVER_BODY . '</div><div class=\'text-center w-100\'><a href=\''. TEXT_PRO_UPSELL_GET_PRO_URL . '\' target=\'_blank\' class=\'btn btn-danger btn-sm m-r-5 m-t-10\'>' . TEXT_PRO_UPSELL_GET_PRO . '</a></div>">
                      <label class="col-xs-5 sidebar-text col-form-label pl-3 pr-0 text-muted getpro mb-1">Enable<span class="label label-theme ml-2 bg-red">PRO</span></label>
                      <div class="col-xs-7">
                        <input disabled data-width="60" data-toggle="toggle" data-on="<i class=\'fa fa-check\'></i> On" data-off="<i class=\'fa fa-times\'></i> Off" data-onstyle="success-toggle" data-offstyle="danger" type="checkbox" data-size="mini" >
                      </div>
                    </div>

                    ');
                     
                  break;
              default:
                if ($rows > 0) {
                  if (isset($cInfo) && is_object($cInfo)) { // category info box contents
                    $heading[] = array('text' => $cInfo->categories_name);
                    // RCO start
                    if ($cre_RCO->get('categories', 'csidebarbuttons') !== true) {
                      $contents[] = array('align' => 'center', 'text' => '<div class="mt-2 mb-2">
                        <button class="btn btn-success btn-sm mt-2" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '\'">' . IMAGE_EDIT . '</button>
                        <button class="btn btn-danger btn-sm mr-1 ml-1 mt-2" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=delete_category')  . '\'">' . IMAGE_DELETE . '</button>
                        <button class="btn btn-info btn-sm mt-2" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=move_category') . '\'">' . IMAGE_MOVE . '</button>
                        </div>');
                    }
                    // RCO eof
                    $contents[] = array('text' => '<div class="sidebar-text mt-3">' . TEXT_DATE_ADDED . '<span class="sidebar-title ml-2">' . tep_date_short($cInfo->date_added) . '</span></div>');
                    if (tep_not_null($cInfo->last_modified)) $contents[] = array('text' => '<div class="sidebar-text mt-1">' . TEXT_LAST_MODIFIED . '<span class="sidebar-title ml-2">' . tep_date_short($cInfo->last_modified) . '</span></div>');
                    $contents[] = array('align' => 'center', 'text' => '<div class="sidebar-img mt-3 well ml-4 mr-4 mb-1">' . tep_info_image($cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '</div><div class="sidebar-text mb-0 mt-0">' . $cInfo->categories_image . '</div><div class="sidebar-text mt-0 mb-3">(' . HEADING_IMAGE_WIDTH . 'x' . ((HEADING_IMAGE_HEIGHT == '') ? HEADING_IMAGE_WIDTH : HEADING_IMAGE_HEIGHT) . ')</div>');
                    $contents[] = array('text' => '<div class="sidebar-text mt-1">' . TEXT_SUBCATEGORIES . '<span class="sidebar-title ml-2">' . $cInfo->childs_count . '</span></div><div class="sidebar-text mt-1">' . TEXT_PRODUCTS . '<span class="sidebar-title ml-2">' . $cInfo->products_count . '</span></div>');
                    // RCO start
                    if ($cre_RCO->get('categories', 'csidebarattributes') !== true) {
                      if ($cInfo->childs_count == 0 and $cInfo->products_count >= 1) {
                        if ($cID) {
                          $contents[] = array('align' => 'center', 'text' => '<button class="btn btn-success btn-sm mr-1 ml-1 mt-4 mb-3" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cID . '&action=copy_product_attributes_categories') . '\'">' . IMAGE_COPY_ATTRIBUTES . '</button>');
                        } else {
                          $contents[] = array('align' => 'center', 'text' => '<div class="sidebar-text">' . TEXT_ATTRIBUTES_COPY_TO . '</div>');
                        }
                      }
                    }
                    // RCO eof
                    // RCI include category sidebar bottom text
                    $returned_rci = $cre_RCI->get('categories', 'csidebarbottom');
                    $contents[] = array('text' => $returned_rci);
                  } elseif (isset($pInfo) && is_object($pInfo)) { // product info box contents
                    $heading[] = array('text' => tep_get_products_name($pInfo->products_id, $languages_id));
                    // RCO start
                    if ($cre_RCO->get('categories', 'psidebarbuttons') !== true) {
                      $contents[] = array('align' => 'center', 'text' => '<div class="mt-2 mb-2">
                        <button class="btn btn-success btn-sm mt-2" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $pInfo->categories_id . '&pID=' . $pInfo->products_id . '&action=new_product') . '\'">' . IMAGE_EDIT . '</button>
                        <button class="btn btn-danger btn-sm mr-1 ml-1 mt-2" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $pInfo->categories_id . '&pID=' . $pInfo->products_id . '&action=delete_product') . '\'">' . IMAGE_DELETE . '</button>
                        <button class="btn btn-info btn-sm mr-1 mt-2" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $pInfo->categories_id . '&pID=' . $pInfo->products_id . '&action=move_product') . '\'">' . IMAGE_MOVE . '</button>
                        <button class="btn btn-info btn-sm mt-2 mr-3" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $pInfo->categories_id . '&pID=' . $pInfo->products_id . '&action=copy_to') . '\'">' . IMAGE_COPY_TO . '</button>
                        </div>');
                    }
                    // RCO eof
                    //RCI include product sidebar buttons
                    $returned_rci = $cre_RCI->get('categories', 'psidebarbuttons');
                    $contents[] = array('align' => 'center', 'text' => $returned_rci);
                    $contents[] = array('text' => '<div class="sidebar-text mt-3">' . TEXT_DATE_ADDED . '<span class="sidebar-title ml-2">' . tep_date_short($pInfo->products_date_added) . '</span></div>');
                    if (tep_not_null($pInfo->products_last_modified)) $contents[] = array('text' => '<div class="sidebar-text mt-1">' . TEXT_LAST_MODIFIED . '<span class="sidebar-title ml-2">' . tep_date_short($pInfo->products_last_modified) . '</span></div>');
                    if (date('Y-m-d') < $pInfo->products_date_available) $contents[] = array('text' => '<div class="sidebar-text mt-1">' . TEXT_DATE_AVAILABLE . '<span class="sidebar-title ml-2">' . tep_date_short($pInfo->products_date_available) . '</span></div>'); 
                    $contents[] = array('align' => 'center', 'text' => '<div class="sidebar-img mt-3 well ml-4 mr-4 mb-1">' . tep_info_image($pInfo->products_image, tep_get_products_name($pInfo->products_id, $languages_id), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</div><div class="sidebar-text mb-0 mt-0">' . $cInfo->categories_image . '</div><div class="sidebar-text mt-0 mb-3">(' . SMALL_IMAGE_WIDTH . 'x' . ((SMALL_IMAGE_HEIGHT == '') ? SMALL_IMAGE_WIDTH : SMALL_IMAGE_HEIGHT) . ')</div>');

                    $contents[] = array('text' => '<div class="sidebar-text">' . TEXT_PRODUCTS_PRICE_INFO . '<span class="sidebar-title ml-2">' . $currencies->format($pInfo->products_price) . '</span></div>');
                    $contents[] = array('text' => '<div class="sidebar-text mt-1">' . TEXT_PRODUCTS_QUANTITY_INFO . '<span class="sidebar-title ml-2">' . $pInfo->products_quantity . '</span></div>');
                    $contents[] = array('text' => '<div class="sidebar-text mt-1">' . TEXT_PRODUCTS_AVERAGE_RATING . '<span class="sidebar-title ml-2">' . number_format($pInfo->average_rating, 2) . '%</span></div>');
                    //RCI include product sidebar product text
                    $returned_rci = $cre_RCI->get('categories', 'psidebarproducttext');
                    $contents[] = array('text' => $returned_rci);
                    // RCO start
                    if ($cre_RCO->get('categories', 'psidebarattributes') !== true) {
                      $contents[] = array('align' => 'center', 'text' => '<button class="btn btn-success btn-sm mr-1 ml-1 mt-4 mb-2" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $pInfo->categories_id . '&pID=' . $pInfo->products_id . '&action=copy_product_attributes') . '\'">' . TEXT_BUTTON_COPY_ATTRIBUTES . '</button>');
                      if ($pID) {
                        $contents[] = array('align' => 'center', 'text' => '<div class="mt-0 mb-3"><button class="btn btn-success btn-sm mt-0" data-toggle="modal" data-target="#listAttributesModal">' . TEXT_LIST_ATTRIBUTES . '</button></div>');
                      } else {
                        $contents[] = array('text' => '<div class="row"><div class="col p-0 mt-1 ml-2 mr-2 mb-3"><div class="note note-danger m-0"><p class="mb-0 mt-0">' . TEXT_SELECT_PRODUCT_TO_DISPLAY_ATTRIBUTES . '</p></div></div></div>');     
                      }
                    }
                    // RCO eof
                    //RCI include product sidebar bottom product text
                    $returned_rci = $cre_RCI->get('categories', 'psidebarbottom');
                    $contents[] = array('text' => $returned_rci);
                  }
                } else {
                  $heading[] = array('text' => EMPTY_CATEGORY);
                  $contents[] = array('text' => '<div class="sidebar-text text-left mt-3 mb-3">' . TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS . '</div>');
                }
                break;
            }
            if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
              $box = new box;
              echo $box->showSidebar($heading, $contents);
            }
            ?>
          </div>

        </div> <!-- end row -->
      </div> <!-- end table-categories -->
    </div> <!-- end dark -->
    <?php if (isset($action) && $action == 'new_product') {
      ?>
      <div class="col-sm-9 col-md-10 m-b-10 mt-2 pl-0"> 
        <a href="javascript:;" class="btn btn-primary m-r-5"><i class="fa fa-save"></i> Save</a>
        <a href="javascript:;" class="btn btn-info m-r-5"><i class="fa fa-save"></i> Save and Stay</a>
      </div>
      </form>
      <?php 
    } 
    ?>
  </div> <!-- end col -->
</div> <!-- end content -->
<script>
$(document).ready(function(){
  
  bs_input_file();

  $("#summernote").summernote({
      disableResizeEditor: true
  });
  $('.note-popover').hide();
});   


/* show file value after file select */
$('.custom-file-input').on('change',function(){
  $(this).next('.form-control-file').addClass("selected").html($(this).val());
})

/* method 2 - change file input to text input after selection
$('.custom-file-input').on('change',function(){
    var fileName = $(this).val();
    $(this).next('.form-control-file').hide();
    $(this).toggleClass('form-control custom-file-input').attr('type','text').val(fileName);
})
*/

$("#name-ctc-list li a").click(function(){
  var id = $(this).attr('data-lang-id');
  var lang = $(this).attr('data-lang-name');
  var target = $("#products_name_" + id).val();
  copy(target);
  $('.notify-container-name').fadeIn().delay(1000).fadeOut();
  //swal("Products Name", "Copied to Clipboard in " + lang + "!", "success")
});

$("#desc-ctc-list li a").click(function(){
  var id = $(this).attr('data-lang-id');
  var lang = $(this).attr('data-lang-name');  
  var target = $("#products_description_" + id).val();
  copy(target);
  $('.notify-container-desc').fadeIn().delay(1000).fadeOut();
//  swal("Products Description", "Copied to Clipboard in " + lang + "!", "success")
});

function setAttrText(sw) {
  if (sw == 'link') {
    $('#copy_attr').hide();
  } else {
    $('#copy_attr').show();
  }

}   

function copy(text){
  var inp =document.createElement('input');
  document.body.appendChild(inp)
  inp.value =text
  inp.select();
  document.execCommand('copy',false);
  inp.remove();
}

function goToUrl(lang) {
  var url = $('#products_url_' + lang).val();
  if (url == '' || url == undefined) {
    swal("Oh Crap!", "Product URL is Empty!", "error");
    return false;
  }
  if (url.indexOf('http://') === -1) {
    swal("Awe Geez!", "URL needs to begin with http:// or https://", "error");
    return false;    
  }
  if (url.indexOf(".") === -1) {
    swal("Awe C'mon!", "You got the http:// part but now you're missing the domain!", "error");
    return false;     
  }
  window.open(url);
}

function bs_input_file() {
  $(".input-file").before(
    function() {
      if ( ! $(this).prev().hasClass('input-ghost') ) {
        var element = $("<input type='file' class='input-ghost' style='visibility:hidden; height:0'>");
        element.attr("name",$(this).attr("name"));
        element.change(function(){
          element.next(element).find('input').val((element.val()).split('\\').pop());
        });
        $(this).find("button.btn-choose").click(function(){
          element.click();
        });
        $(this).find("button.btn-reset").click(function(){
          element.val(null);
          $(this).parents(".input-file").find('input').val('');
        });
        $(this).find('input').css("cursor","pointer");
        $(this).find('input').mousedown(function() {
          $(this).parents('.input-file').prev().click();
          return false;
        });
        return element;
      }
    }
  );
}
</script>
<?php 
include(DIR_WS_INCLUDES . 'html_bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>