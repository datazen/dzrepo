<?php
/*
  $Id: categories.php,v 1.2 2004/03/29 00:18:17 ccwjr Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
require('includes/functions/categories_description.php');
require(DIR_WS_CLASSES . 'file_select.php');
require(DIR_WS_CLASSES . 'currencies.php');
require(DIR_WS_CLASSES . 'Products.class.php');
require(DIR_WS_CLASSES . 'Product.class.php');
require(DIR_WS_CLASSES . 'Specials.class.php');
require(DIR_WS_CLASSES . 'Featured.class.php');
$currencies = new currencies();

// RCI code start
echo $cre_RCI->get('global', 'top', false);
echo $cre_RCI->get('categories', 'top', false);
// RCI code eof

//intilize varibles
$categories_id = '';
$categories_image = '';

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

// DZ added for top level cats
if ($cPath == '' && $cID != '') $cPath = $cID;

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
        if ((isset($_POST['unlink_cat_image']) && $_POST['unlink_cat_image'] == 'on')  ||
           (isset($_POST['delete_cat_image']) && $_POST['delete_cat_image'] == 'on')) {
           tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '' where categories_id = '" . (int)$categories_id . "'");

           if (isset($_POST['delete_cat_image']) && $_POST['delete_cat_image'] == 'on') {
             if (isset($_POST['categories_image'])) unlink(DIR_FS_CATALOG_IMAGES . $_POST['categories_image']);

           }

        } else {       
          if (isset($_FILES['categories_image']) && tep_not_null($_FILES['categories_image']['name'])) {
            if (strtolower($_FILES['categories_image']['name']) != 'none') {
              $uploadFile = DIR_FS_CATALOG_IMAGES . urldecode($_POST[$image['dir']]) . $_FILES['categories_image']['name'];
              @move_uploaded_file($_FILES['categories_image']['tmp_name'], $uploadFile);
            }

            tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . tep_db_input($_FILES['categories_image']['name']) . "' where categories_id = '" . (int)$categories_id . "'");
          }
        }
        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }

        $mode = (isset($_POST['mode']) && $_POST['mode'] != '') ? $_POST['mode'] : 'save';
        if ($mode == 'save') {
          tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
        } else {  // save & stay
          tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id . '&action=edit_category'));
        }
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
      $_SESSION['action_result'] = Product::copyProductAttributes($pID, $copy_to_products_id);     
      //tep_copy_products_attributes($pID,$copy_to_products_id);

      tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pID));

      break;

    case 'create_copy_product_attributes_categories':
      $make_copy_from_products_id = isset($_POST['make_copy_from_products_id']) ? $_POST['make_copy_from_products_id'] : 0;
      $categories_products_copying_query= tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id='" . $cID . "'");
      while ( $categories_products_copying=tep_db_fetch_array($categories_products_copying_query) ) {
        // process all products in category
        $_SESSION['action_result'] = Product::copyProductAttributes($make_copy_from_products_id, $categories_products_copying['products_id']);     
        //tep_copy_products_attributes($make_copy_from_products_id,$categories_products_copying['products_id']);
      }

      tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cID));
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

      $products_special_price_old = Specials::getSpecialPrice($products_id);


      unset($products_old_query);
      unset($products_description_old_query);
      unset($description_old);

      $sql_data_array = array(); //declare the array and add to it anything changed
      $products_date_available = tep_db_prepare_input($_POST['products_date_available']);

      // convert to datetime
      $products_date_available = date('Y-m-d H:i:s', strtotime($products_date_available));              // returns Saturday, January 30 10 02:06:34

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

      $products_status = isset($_POST['products_status']) ? tep_db_prepare_input($_POST['products_status']) : 'off';
      if ($products_status == 'on') $products_status = 1;
      if ($products_status == 'off') $products_status = 0;
      if ($products_status != $products_old['products_status']) $sql_data_array['products_status'] = $products_status;

      $products_tax_class_id = isset($_POST['products_tax_class_id']) ? tep_db_prepare_input($_POST['products_tax_class_id']) : 0;
      if ($products_tax_class_id != $products_old['products_tax_class_id']) $sql_data_array['products_tax_class_id'] = $products_tax_class_id;
      $manufacturers_id = isset($_POST['manufacturers_id']) ? tep_db_prepare_input($_POST['manufacturers_id']) : 0;
      if ($manufacturers_id != $products_old['manufacturers_id']) $sql_data_array['manufacturers_id'] = $manufacturers_id;

      // update Specials
      $products_special_price_old = Specials::getSpecialPrice($products_id);
      $products_special_price = isset($_POST['products_special_price']) ? tep_db_prepare_input($_POST['products_special_price']) : 0.00;
      if ($products_special_price != $products_special_price_old) Specials::update($products_id, $products_special_price);

      // update Featured
      $featured_old = Featured::isFeatured($products_id);
      $featured = isset($_POST['featured']) ? tep_db_prepare_input($_POST['featured']) : 'off';
      if ($featured == 'on') $featured = 1;
      if ($featured == 'off') $featured = 0;     
      if ($featured != $featured_old) Featured::update($products_id, $featured);      


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
        if (isset($_POST[$image['delete']]) && $_POST[$image['delete']] == 'on' && $products_old[$image['table']] != '') {
          unlink(DIR_FS_CATALOG_IMAGES . $products_old[$image['table']]);
          $sql_data_array[$image['table']] = '';
       } elseif (isset($_POST[$image['unlink']]) && $_POST[$image['unlink']] == 'on' && $products_old[$image['table']] != '') {
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

      $mode = (isset($_POST['mode']) && $_POST['mode'] != '') ? $_POST['mode'] : 'save';
      if ($mode == 'save') {
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_old['products_id']));
      } else {  // save & stay
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_old['products_id'] . '&action=new_product'));
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
  
        $products_status = isset($_POST['products_status']) ? tep_db_prepare_input($_POST['products_status']) : 'off';
        if ($products_status == 'on') $products_status = 1;
        if ($products_status == 'off') $products_status = 0;

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

        $mode = (isset($_POST['mode']) && $_POST['mode'] != '') ? $_POST['mode'] : 'save';
        if ($mode == 'save') {
          tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id));
        } else {  // save & stay
          tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id . '&action=new_product'));
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

  } // end switch($action)
} // end tep_not_null($action)

// check if the catalog image directory exists
if (is_dir(DIR_FS_CATALOG_IMAGES)) {
  if (!is_writeable(DIR_FS_CATALOG_IMAGES)) {
    $messageStack->add('categories', ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  }
} else {
  $messageStack->add('categories', ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
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

$go_back_to = $_SERVER["REQUEST_URI"];

include(DIR_WS_INCLUDES . 'html_top.php');
include(DIR_WS_INCLUDES . 'header.php');
include(DIR_WS_INCLUDES . 'column_left.php');

?>
<div id="content" class="content p-relative">         
  <h1 class="page-header"><i class="fa fa-laptop"></i> <?php echo HEADING_TITLE; ?></h1>

  <?php if (file_exists(DIR_WS_INCLUDES . 'toolbar.php')) include(DIR_WS_INCLUDES . 'toolbar.php'); ?>
  
  <div class="col main-col">  
    <?php     
    if ($messageStack->size('categories') > 0) {
      echo $messageStack->output('categories'); 
    }

    if (isset($action) && ($action == 'new_product' || $action == 'new_category' || $action == 'edit_category')) {
      ?>   
      <!-- begin static alerts -->
      <div id="alert-inactive" class="row" style="display:none;"><div class="col p-0 mt-0 mb-2"><div class="note note-danger m-0"><h4 class="m-0"><?php echo WARNING_ITEM_INACTIVE_TITLE; ?></h4><p class="mb-0 mt-2"><?php echo WARNING_ITEM_INACTIVE_TEXT; ?></p></div></div></div>
      <div id="alert-stock" class="row" style="display:none;"><div class="col p-0 mt-0 mb-2"><div class="note note-warning m-0"><h4 class="m-0"><?php echo WARNING_ITEM_OUT_OF_STOCK_TITLE;?></h4><p class="mb-0 mt-2"><?php echo WARNING_ITEM_OUT_OF_STOCK_TEXT; ?></p></div></div></div>
      <!-- end static alerts -->

      <?php 
      if ($action == 'new_category' || $action == 'edit_category') {
        echo '<form id="new_category" name="new_category" method="post" enctype="multipart/form-data" data-parsley-validate>';
      } else {
        echo '<form id="new_product" name="new_product" method="post" enctype="multipart/form-data" data-parsley-validate>';
      }
      ?>

      <!-- begin button bar --> 
      <div id="button-bar" class="row">
        <div class="col-9 m-b-10 w-100 pt-1 pl-0 pr-0"> 
          <a href="<?php echo tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . ((isset($cID) && $cID != '') ? '&cID=' . $cID : '') . ((isset($pID) && $pID != '') ? '&pID=' . $pID : '')); ?>" class="btn btn-link m-r-3 f-w-200 text-primary hidden-xs hidden-sm"><i class="fa fa-chevron-left"></i> <?php echo BUTTON_RETURN_TO_LIST; ?></a>
          <?php
          if ($action == 'new_product') {
            ?>
            <button type="submit" onclick="updateProduct('save');" class="btn btn-primary m-r-3"><i class="fa fa-save"></i> <?php echo BUTTON_SAVE; ?></button>
            <button type="submit" onclick="updateProduct('stay');" class="btn btn-info m-r-3 btn-save-stay"><i class="fa fa-save"></i> <?php echo BUTTON_SAVE_STAY; ?></button>
            <a href="<?php echo HTTP_SERVER . DIR_WS_CATALOG .'product_info.php?products_id=' . $pID; ?>" target="_blank" class="hidden-xs hidden-sm hidden-md btn btn-link m-r-5 f-w-200 text-primary"><i class="fa fa-laptop"></i> <?php echo BUTTON_VIEW_IN_CATALOG; ?></a>
            <?php 
          } else {
            ?>
            <button type="submit" onclick="updateCategory('save');" class="btn btn-primary m-r-3"><i class="fa fa-save"></i> <?php echo BUTTON_SAVE; ?></button>
            <button type="submit" onclick="updateCategory('stay');" class="btn btn-info m-r-3 btn-save-stay"><i class="fa fa-save"></i> <?php echo BUTTON_SAVE_STAY; ?></button>
            <a href="<?php echo HTTP_SERVER . DIR_WS_CATALOG . 'index.php?cPath=' . $cPath . ((isset($cID) && $cID != '') ? '&cID=' . $cID : ''); ?>" target="_blank" class="hidden-xs hidden-sm hidden-md btn btn-link m-r-5 f-w-200 text-primary"><i class="fa fa-laptop"></i> <?php echo BUTTON_VIEW_IN_CATALOG; ?></a>            
            <?php 
          } 
          ?>         
        </div>
        <div class="col-3 m-b-10 pt-1 pr-2">
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
    } else {
      ?>
      <div class="row">
        <div class="col-9"></div>
        <div class="col-3 pr-0">
          <?php echo tep_draw_form('goto', FILENAME_CATEGORIES, '', 'get');?>
           <div class="form-group row mb-2 pr-0">
            <label for="cPath" class="hidden-xs col-sm-3 col-form-label text-center m-t-10 pr-0"><?php echo LABEL_GOTO; ?></label>
            <div class="col-sm-9 p-0 dark rounded">
              <?php echo tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();" class="form-control"'); ?>
            </div>
          </div>
          <?php
          if (isset($_GET[tep_session_name()])) {
            echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
          }
          echo '</form></div>';
          ?>    
        </div>
      </div>
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
            if ( isset($action) && ($action == 'new_category' || $action == 'edit_category') ) {
              if ( ($cID) && (!$_POST) ) {
                // edit category
                $categories_query = tep_db_query("select c.categories_id, cd.categories_name, cd.categories_heading_title, cd.categories_description, cd.categories_head_title_tag, cd.categories_head_desc_tag, cd.categories_head_keywords_tag, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $cID . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by c.sort_order, cd.categories_name");
                $category = tep_db_fetch_array($categories_query);
                $cInfo = new objectInfo($category);
              } elseif ($_POST) {
                die('preview_category?');
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
                // new category
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

              // RCI start
              echo $cre_RCI->get('categories', 'cedittop');
              // RCI eof

              // RCO start fieldsetcdescr
              if ($cre_RCO->get('categories', 'fieldsetcdescr') !== true) {
                ?>
                <div class="category-lang-content">
                  <?php
                  for ($i=0; $i<sizeof($languages); $i++) {
                    $display = ($languages[$i]['id'] == $_SESSION['languages_id']) ? '' : 'display:none;'
                    ?>
                    <div style="<?php echo $display; ?>" class="category-lang-pane <?php echo (($languages[$i]['id'] == $_SESSION['languages_id']) ? 'active' : '');?>" id="category-default-pane-<?php echo $languages[$i]['id'];?>">

                      <!-- CATEGORY INFO start -->
                      <div class="ml-2 mr-2">
                        <div class="main-heading"><span>Category Info</span>
                          <div class="main-heading-footer"></div>
                        </div>             

                        <div class="form-group row mb-3 m-t-20 p-relative">
                          <label class="col-xs-4 col-md-3 col-lg-2 control-label main-text mt-1"><?php echo LABEL_NAME; ?><span class="required"></span></label>
                          <div class="col-xs-7 col-md-8 col-lg-9 p-r-0 meta-input">
                            <?php
                            if (isset($cInfo->categories_id)) {
                              echo tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', (isset($categories_name[$languages[$i]['id']]) ? stripslashes($categories_name[$languages[$i]['id']]) : tep_get_category_name($cInfo->categories_id, $languages[$i]['id'])), 'id="categories_name_' . $languages[$i]['id'] . '" class="form-control f-w-600 f-s-12 p-l-10 p-r-10" required');
                            } else{
                              echo tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', (isset($categories_name[$languages[$i]['id']]) ? stripslashes($categories_name[$languages[$i]['id']]) : ''), 'id="categories_name_' . $languages[$i]['id'] . '" class="form-control f-w-600 f-s-12 p-l-10 p-r-10" required');
                            }
                            ?>
                          </div>

                          <div class="col-xs-1 p-l-0 p-r-0 p-relative">
                            <div class="notify-container-name rounded-left rounded-right"><span class="text-black"><?php echo TEXT_COPIED; ?></span></div>
                            <div id="cat-name-ctc-options" class="btn-group btn-xs"> <a aria-expanded="false" href="javascript:;" data-toggle="dropdown" class="f-w-100 text-right btn btn-xs btn-white dropdown-toggle width-full"> <span class="caret"></span> </a>
                              <ul id="cat-name-ctc-list" class="dropdown-menu pull-left">
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

                        <div class="form-group row mb-3 clearfix p-relative">
                          <label class="col-xs-4 col-md-3 col-lg-2 control-label main-text mt-1"><?php echo LABEL_DESCRIPTION; ?></label>
                          <div class="col-xs-7 col-md-8 col-lg-9 p-r-0 meta-input">
                            <?php
                            if (isset($cInfo->categories_id)) {
                              echo tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($categories_description[$languages[$i]['id']]) ? stripslashes($categories_description[$languages[$i]['id']]) : tep_get_category_description($cInfo->categories_id, $languages[$i]['id'])), 'style="width:99%;" class="ckeditor" id="categories_description_' . $languages[$i]['id'] . '"');
                            } else {
                              echo tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($categories_description[$languages[$i]['id']]) ? stripslashes($categories_description[$languages[$i]['id']]) : ''), 'style="width:99%;" class="ckeditor" id="categories_description_' . $languages[$i]['id'] . '"');
                            }
                            ?>
                          </div>
                          <div class="col-xs-1 p-l-0 p-r-0">
                            <div class="notify-container-desc rounded-left rounded-right"><span class="text-black"><?php echo TEXT_COPIED; ?></span></div>                            
                            <div id="cat-desc-ctc-options" class="btn-group btn-xs"> <a aria-expanded="false" href="javascript:;" data-toggle="dropdown" class="f-w-100 text-right btn btn-xs btn-white dropdown-toggle width-full"> <span class="caret"></span> </a>
                              <ul id="cat-desc-ctc-list" class="dropdown-menu pull-left">
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

                        <div class="form-group row mb-3 mt-3">
                          <label class="col-xs-4 col-md-3 col-lg-2 control-label main-text mt-1"><?php echo LABEL_PAGE_HEADING; ?></label>
                          <div class="col-xs-7 col-md-8 col-lg-9 p-r-0 meta-input">
                            <?php
                            if (isset($cInfo->categories_id)) {
                              echo tep_draw_input_field('categories_heading_title[' . $languages[$i]['id'] . ']', (isset($categories_heading_title[$languages[$i]['id']]) ? stripslashes($categories_heading_title[$languages[$i]['id']]) : tep_get_category_heading_title($cInfo->categories_id, $languages[$i]['id'])), 'class="form-control" id="categories_heading_title_' . $languages[$i]['id'] . '"');
                            } else {
                              echo tep_draw_input_field('categories_heading_title[' . $languages[$i]['id'] . ']', (isset($categories_heading_title[$languages[$i]['id']]) ? stripslashes($categories_heading_title[$languages[$i]['id']]) : ''), 'class="form-control" id="categories_heading_title_' . $languages[$i]['id'] . '"');
                            }
                            ?>
                          </div>
                          <div class="col-xs-1 p-l-0 p-r-0 p-relative">
                            <div class="notify-container-head-title rounded-left rounded-right"><span class="text-black"><?php echo TEXT_COPIED; ?></span></div>                            
                            <div id="cat-head-title-ctc-options" class="btn-group btn-xs "> <a aria-expanded="false" href="javascript:;" data-toggle="dropdown" class=" f-w-100 text-right btn btn-xs btn-white dropdown-toggle width-full"> <span class="caret"></span> </a>
                              <ul id="cat-head-title-ctc-list" class="dropdown-menu pull-left">
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

                        <?php
                        $categories_image = 'no_image.png';
                        if (isset($cInfo)) {
                          if ($cInfo->categories_image != '') $categories_image = $cInfo->categories_image;
                        }
                        ?>
                        <div class="form-group row mb-3 m-t-20 p-relative">
                          <label class="col-xs-4 col-md-3 col-lg-2 control-label main-text mt-1"><?php echo LABEL_IMAGE; ?></label>

                          <div class="col-xs-7 col-md-8 col-lg-9 p-r-0">
                            <div class="media border rounded">
                              <div class="lc-border p-10">
                                <a onclick="$('#categories_image').click();" class="media-left" style="min-width:<?php echo SMALL_IMAGE_WIDTH; ?>px;"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $categories_image, $categories_image, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="media-object mt-2"'); ?></a>
                                <div class="media-body mr-2 mb-0">
                                  <?php
                                  echo '<div class="col d-sm-inline p-0 mb-2">' . $categories_image . tep_draw_hidden_field('categories_previous_image', $cInfo->categories_image) . '</div>'; 
                                  if ($categories_image != 'no_image.png') { 
                                    ?>
                                    <div class="col d-sm-inline p-0"><label class="control-label ml-3 mr-2 main-text"><?php echo LABEL_DELETE; ?></label><input type="checkbox" name="delete_cat_image" class="js-switch js-delete"></div>
                                    <div class="col d-sm-inline p-0"><label class="control-label ml-3 mr-2 main-text"><?php echo LABEL_UNLINK; ?></label><input type="checkbox" name="unlink_cat_image" class="js-switch js-unlink"></div>
                                    <?php 
                                  } 
                                  ?>
                                  <div class="fine-input-container mt-2 mb-3">
                                    <input type="file" class="filestyle" id="categories_image" name="categories_image" placeholder="<?php echo TEXT_CHOOSE_FILE; ?>" /><?php echo tep_draw_hidden_field('categories_image_previous', $categories_image); ?>
                                  </div>
                                  <!-- div class="mt-2"><select name="products_image_destination" class="form-control w-50" id="dirPath" ><?php echo Product::getImageUploadDirOptions(); ?></select></div -->
                                </div>
                              </div>
                            </div>
                          </div>                        
                        </div>                       
                      </div>                       
                      <!-- CATEGORY INFO end -->   
                      <!-- SEO & META TAGS start-->
                      <div class="ml-2 mr-2">
                        <div class="main-heading m-t-20"><span><?php echo HEADING_META_TAGS; ?></span>
                          <div class="main-heading-footer"></div>
                        </div>  

                        <div class="form-group row mb-3 mt-3">
                          <label class="col-xs-4 col-md-3 col-lg-2 control-label main-text mt-1"><?php echo LABEL_META_TITLE; ?></label>
                          <div class="col-xs-7 col-md-8 col-lg-9 p-r-0 meta-input">
                            <?php
                            if (isset($cInfo->categories_id)) {
                              echo tep_draw_textarea_field('categories_head_title_tag[' . $languages[$i]['id'] . ']', 'soft', '15', '2', (isset($categories_head_title_tag[$languages[$i]['id']]) ? stripslashes($categories_head_title_tag[$languages[$i]['id']]) : tep_get_category_head_title_tag($cInfo->categories_id, $languages[$i]['id'])),'class="form-control" id="categories_head_title_tag_' . $languages[$i]['id'] . '"');
                            } else {
                              echo tep_draw_textarea_field('categories_head_title_tag[' . $languages[$i]['id'] . ']', 'soft', '15', '2', (isset($categories_head_title_tag[$languages[$i]['id']]) ? stripslashes($categories_head_title_tag[$languages[$i]['id']]) : ''),'class="form-control" id="categories_head_title_tag_' . $languages[$i]['id'] . '"');
                            }
                            ?>                                                 
                          </div>
                          <div class="col-xs-1 p-l-0 p-r-0 p-relative">
                            <div class="notify-container-meta-title rounded-left rounded-right"><span class="text-black"><?php echo TEXT_COPIED; ?></span></div>                            
                            <div id="cat-meta-title-ctc-options" class="btn-group btn-xs "> <a aria-expanded="false" href="javascript:;" data-toggle="dropdown" class=" f-w-100 text-right btn btn-xs btn-white dropdown-toggle width-full"> <span class="caret"></span> </a>
                              <ul id="cat-meta-title-ctc-list" class="dropdown-menu pull-left">
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
                          <label class="col-xs-4 col-md-3 col-lg-2 control-label main-text mt-1"><?php echo LABEL_META_KEYWORDS;?></label>
                          <div class="col-xs-7 col-md-8 col-lg-9 p-r-0 meta-input">
                            <?php
                            if (isset($cInfo->categories_id)) {
                              echo tep_draw_textarea_field('categories_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '25', '5', (isset($categories_head_keywords_tag[$languages[$i]['id']]) ? stripslashes($categories_head_keywords_tag[$languages[$i]['id']]) : tep_get_category_head_keywords_tag($cInfo->categories_id, $languages[$i]['id'])),'class="form-control" id="categories_head_desc_tag_' . $languages[$i]['id'] . '"');
                            } else {
                              echo tep_draw_textarea_field('categories_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '25', '5', (isset($categories_head_keywords_tag[$languages[$i]['id']]) ? stripslashes($categories_head_keywords_tag[$languages[$i]['id']]) : ''),'class="form-control" id="categories_head_desc_tag_' . $languages[$i]['id'] . '"');
                            }
                            ?>                                        
                          </div>
                          <div class="col-xs-1 p-l-0 p-r-0 p-relative">
                            <div id="cat-meta-keywords-ctc-options" class="notify-container-meta-keywords rounded-left rounded-right"><span class="text-black"><?php echo TEXT_COPIED; ?></span></div>                            
                            <div id="cat-meta-keywords-ctc-list" class="btn-group btn-xs "> <a aria-expanded="false" href="javascript:;" data-toggle="dropdown" class=" f-w-100 text-right btn btn-xs btn-white dropdown-toggle width-full"> <span class="caret"></span> </a>
                              <ul class="dropdown-menu pull-left">
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
                          <label class="col-xs-4 col-md-3 col-lg-2 control-label main-text mt-1 pl-0"><?php echo LABEL_META_DESCRIPTION; ?></label>
                          <div class="col-xs-7 col-md-8 col-lg-9 p-r-0 meta-input">
                            <?php
                            if (isset($cInfo->categories_id)) {
                              echo tep_draw_textarea_field('categories_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '25', '5', (isset($categories_head_desc_tag[$languages[$i]['id']]) ? stripslashes($categories_head_desc_tag[$languages[$i]['id']]) : tep_get_category_head_desc_tag($cInfo->categories_id, $languages[$i]['id'])),'class="form-control" id="categories_head_keywords_tag_' . $languages[$i]['id'] . '"');
                            } else {
                              echo tep_draw_textarea_field('categories_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '25', '5', (isset($categories_head_desc_tag[$languages[$i]['id']]) ? stripslashes($categories_head_desc_tag[$languages[$i]['id']]) : ''),'class="form-control" id="categories_head_keywords_tag_' . $languages[$i]['id'] . '"');
                            }
                            ?>                                                 
                          </div>
                          <div class="col-xs-1 p-l-0 p-r-0 p-relative">
                            <div id="cat-meta-desc-ctc-options" class="notify-container-meta-desc rounded-left rounded-right"><span class="text-black"><?php echo TEXT_COPIED; ?></span></div>                                                     
                            <div id="cat-meta-desc-ctc-list" class="btn-group btn-xs"> <a aria-expanded="false" href="javascript:;" data-toggle="dropdown" class=" f-w-100 text-right btn btn-xs btn-white dropdown-toggle width-full"> <span class="caret"></span> </a>
                              <ul class="dropdown-menu pull-left">
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
                      </div>
                      <!-- SEO & META TAGS end--> 

                      <!-- USER ACCESS SETTINGS start-->
                      <div class="ml-2 mr-2">              
                        <div class="main-heading m-t-20"><span><?php echo HEADING_USER_ACCESS_SETTINGS; ?></span>
                          <div class="main-heading-footer"></div>
                        </div>  
                                                   
                        <div class="form-group m-t-20">
                          <label class="col-md-2 control-label text-right"> User Groups</label>
                          <span class="upsell-label c-pointer label label-theme m-l-5 bg-orange">B2B</span>

                          <div class="col-md-6" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_B2B_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_B2B_UPSELL_GET_B2B_URL; ?>" target="_blank" class="btn btn-warning btn-sm m-r-5 m-t-10"><?php echo TEXT_B2B_UPSELL_GET_B2B; ?></a></div>'><div class="lc-border p-10"><div id="jstree-checkable"></div>
                          <div class="p-t-10 p-b-10">Push access changes to children of this category:</div>
                            <div class="btn-group" data-toggle="buttons">                                                         
                              <label class="btn btn-white active">
                                <input name="optionsRadios" value="option1" checked="" type="radio"> None
                              </label> 
                              <label class="btn btn-white">
                                <input name="optionsRadios" value="option2" checked="" type="radio"> Sub Categories
                              </label> 
                              <label class="btn btn-white">
                                <input name="optionsRadios" value="option3" checked="" type="radio"> Sub Categories and Products
                              </label>       
                            </div>
                          </div>
                        </div></div>
                      </div>
                      <!-- USER ACCESS SETTINGS end--> 
                    </div>
                    <?php
                  }
                  ?>
                </div> <!-- end category-lang-content -->
                <?php
              } // RCO eof fieldsetcdescr
            } elseif (isset($action) && $action == 'new_product') {
              // initialize the empty product object
              $pInfo = new objectInfo(Product::getProductParameters());

              if (isset($pID) && empty($_POST)) {
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
              ?>

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

                      <div class="form-group row mb-3 m-t-20 p-relative">
                        <label class="col-xs-4 col-md-3 col-lg-2 control-label main-text mt-1"><?php echo LABEL_NAME; ?><span class="required"></span></label>
                        <div class="col-xs-7 col-md-8 col-lg-9 p-r-0 meta-input">
                          <?php echo tep_draw_input_field('products_name[' . $languages[$i]['id'] . ']', (isset($products_name[$languages[$i]['id']]) ? $products_name[$languages[$i]['id']] : tep_get_products_name($pInfo->products_id, $languages[$i]['id'])), 'id="products_name_' . $languages[$i]['id'] . '"class="form-control f-w-600 f-s-12 p-l-10 p-r-10" required'); ?>
                        </div>

                        <div class="col-xs-1 p-l-0 p-r-0 p-relative">
                          <div class="notify-container-name rounded-left rounded-right"><span class="text-black"><?php echo TEXT_COPIED; ?></span></div>
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

                      <div class="form-group row mb-3" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_PRO_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_PRO_UPSELL_GET_PRO_URL; ?>" target="_blank" class="btn btn-danger btn-sm m-r-5 m-t-10"><?php echo TEXT_PRO_UPSELL_GET_PRO; ?></a></div>'>
                        <label class="col-xs-4 col-md-3 col-lg-2 control-label pr-0 c-pointer main-text mt-1"><?php echo LABEL_LISTING_BLURB; ?></label>
                        <div class="col-xs-7 col-md-8 col-lg-9 p-r-0 meta-input c-pointer p-relative">
                          <textarea readonly class="form-control" rows="2"></textarea>
                          <div class="ribbon-left"><img src="assets/img/ribbon-pro.png"></div>
                        </div>
                        <div class="col-xs-1 p-l-0 p-r-0"> </div>
                      </div>

                      <div class="form-group row mb-3 clearfix p-relative">
                        <label class="col-xs-4 col-md-3 col-lg-2 control-label main-text mt-1"><?php echo LABEL_DESCRIPTION; ?></label>
                        <div class="col-xs-7 col-md-8 col-lg-9 p-r-0 meta-input">
                          <?php echo tep_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_description[$languages[$i]['id']]) ? $products_description[$languages[$i]['id']] : tep_get_products_description($pInfo->products_id, $languages[$i]['id'])), 'style="width:99%;" class="ckeditor" id="products_description_' . $languages[$i]['id'] . '"'); ?>
                        </div>
                        <div class="col-xs-1 p-l-0 p-r-0">
                          <div class="notify-container-desc rounded-left rounded-right"><span class="text-black"><?php echo TEXT_COPIED; ?></span></div>                            
                          <div id="desc-ctc-options" class="btn-group btn-xs"> <a aria-expanded="false" href="javascript:;" data-toggle="dropdown" class="f-w-100 text-right btn btn-xs btn-white dropdown-toggle width-full"> <span class="caret"></span> </a>
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
                        <label class="col-xs-4 col-md-3 col-lg-2 control-label main-text mt-1"><?php echo LABEL_URL; ?></label>
                        <div class="col-xs-7 col-md-8 col-lg-9 p-r-0 meta-input">
                          <?php echo tep_draw_input_field('products_url[' . $languages[$i]['id'] . ']', (isset($products_url[$languages[$i]['id']]) ? $products_url[$languages[$i]['id']] : tep_get_products_url($pInfo->products_id, $languages[$i]['id'])), 'id="products_url_' . $languages[$i]['id'] . '" class="form-control" placeholder="www.domain.com" data-parsley-pattern="^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9](?:\.[a-zA-Z]{2,})+$"'); ?>
                        </div>
                        <div class="col-xs-1 pl-1 m-t-1"><button onclick="goToUrl('<?php echo $languages[$i]['id']; ?>', 'products'); return false;" class="btn btn-white btn-xs"><i class="fa fa-external-link"></i></button></div>
                      </div>
                    </div>
                    <!-- PRODUCT INFO end -->

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
                      <div class="main-heading m-t-20"><span></span><?php echo HEADING_IMAGES; ?></span>
                        <div class="main-heading-footer"></div>
                      </div>                  

                      <div class="form-group row mt-3 mb-3">
                        <label class="col-md-2 control-label main-text mt-2 p-0"><?php echo LABEL_MAIN_IMAGE; ?></label>
                        <div class="col-md-10">
                          <div class="medi border rounded">
                            <div class="lc-border p-10">
                              <a onclick="$('#products_image').click();" class="media-left" style="min-width:<?php echo SMALL_IMAGE_WIDTH; ?>px;" href="javascript:;"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image, $products_image, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="media-object mt-2"'); ?></a>
                              <div class="media-body mr-2 mb-0">
                                <?php 
                                echo '<div class="col d-sm-inline p-0 mb-2">' . $products_image . '</div>'; 
                                if ($products_image != 'no_image.png') { 
                                  ?>
                                  <div class="col d-sm-inline p-0"><label class="control-label ml-3 mr-2 main-text"><?php echo LABEL_DELETE; ?></label><input type="checkbox" name="delete_image" class="js-switch js-delete-1"></div>
                                  <div class="col d-sm-inline p-0"><label class="control-label ml-3 mr-2 main-text"><?php echo LABEL_UNLINK; ?></label><input type="checkbox" name="unlink_image" class="js-switch js-unlink-1"></div>
                                  <?php 
                                } 
                                ?>
                                <script>
                                  function openFileInput() {

                                  }
                                </script>
                                <div class="fine-input-container mt-2 mb-3">
                                  <input type="file" class="filestyle" id="products_image" name="products_image" placeholder="<?php echo TEXT_CHOOSE_FILE; ?>" /><?php echo tep_draw_hidden_field('products_image_previous', $products_image); ?>
                                </div>
                                <!-- div class="mt-2"><select name="products_image_destination" class="form-control w-50" id="dirPath" ><?php echo Product::getImageUploadDirOptions(); ?></select></div -->
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="form-group row mt-3 mb-3">
                        <label class="col-md-2 control-label main-text mt-2 p-0"><?php echo LABEL_THUMBNAIL_IMAGE; ?></label>
                        <div class="col-md-10">
                          <div class="media border rounded">
                            <div class="lc-border p-10">
                              <a onclick="$('#products_image_med').click();" class="media-left" style="min-width:<?php echo SMALL_IMAGE_WIDTH; ?>px;" href="javascript:;"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_med, $products_image_med, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="media-object mt-2"'); ?></a>
                              <div class="media-body mr-2 mb-0">
                                <?php 
                                echo '<div class="col d-sm-inline p-0 mb-2">' . $products_image_med . '</div>'; 
                                if ($products_image_med != 'no_image.png') { 
                                  ?>
                                  <div class="col d-sm-inline p-0"><label class="control-label ml-3 mr-2 main-text"><?php echo LABEL_DELETE; ?></label><input type="checkbox" name="delete_image_med" class="js-switch js-delete-2"></div>
                                  <div class="col d-sm-inline p-0"><label class="control-label ml-3 mr-2 main-text"><?php echo LABEL_UNLINK; ?></label><input type="checkbox" name="unlink_image_med" class="js-switch js-unlink-2"></div>
                                  <?php 
                                } 
                                ?>
                                <div class="fine-input-container mt-2 mb-3">
                                  <input type="file" class="filestyle" id="products_image_med" name="products_image_med" placeholder="<?php echo TEXT_CHOOSE_FILE; ?>" /><?php echo tep_draw_hidden_field('products_image_med_previous', $products_image_med); ?>
                                </div>
                                <!-- div class="mt-2"><select name="products_image_med_destination" class="form-control w-50" id="dirPath" ><?php echo Product::getImageUploadDirOptions(); ?></select></div -->
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="form-group row mt-3 mb-3">
                        <label class="col-md-2 control-label main-text mt-2 p-0"><?php echo LABEL_LARGE_IMAGE; ?></label>
                        <div class="col-md-10">
                          <div class="media border rounded">
                            <div class="lc-border p-10">
                              <a onclick="$('#products_image_lrg').click();" class="media-left" style="min-width:<?php echo SMALL_IMAGE_WIDTH; ?>px;" href="javascript:;"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_IMAGES . $products_image_lrg, $products_image_lrg, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="media-object mt-2"'); ?></a>
                              <div class="media-body mr-2 mb-0">
                              <?php 
                                echo '<div class="col d-sm-inline p-0 mb-2">' . $products_image_lrg . '</div>'; 
                                if ($products_image_lrg != 'no_image.png') { 
                                  ?>
                                  <div class="col d-sm-inline p-0"><label class="control-label ml-3 mr-2 main-text"><?php echo LABEL_DELETE; ?></label><input type="checkbox" name="delete_image_lrg" class="js-switch js-delete-3"></div>
                                  <div class="col d-sm-inline p-0"><label class="control-label ml-3 mr-2 main-text"><?php echo LABEL_UNLINK; ?></label><input type="checkbox" name="unlink_image_lrg" class="js-switch js-unlink-3"></div>
                                  <?php 
                                } 
                                ?>
                                <div class="fine-input-container mt-2 mb-3">
                                  <input type="file" class="filestyle" id="products_image_lrg" name="products_image_lrg" placeholder="<?php echo TEXT_CHOOSE_FILE; ?>" /><?php echo tep_draw_hidden_field('products_image_rg_previous', $products_image_lrg); ?>
                                </div>
                                <!-- div class="mt-2"><select name="products_image_lrg_destination" class="form-control w-50" id="dirPath" ><?php echo Product::getImageUploadDirOptions(); ?></select></div -->
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>                

                      <div class="form-group row mb-3">
                        <label class="col-md-2 control-label main-text mt-2 p-0" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_PRO_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_PRO_UPSELL_GET_PRO_URL; ?>" target="_blank" class="btn btn-danger btn-sm m-r-5 m-t-10"><?php echo TEXT_PRO_UPSELL_GET_PRO; ?></a></div>'><?php echo TEXT_PRODUCTS_IMAGE_ADDITIONAL; ?></label>                    
                        <div class="col-md-10" >
                          <div class="col-3 c-pointer" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_PRO_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_PRO_UPSELL_GET_PRO_URL; ?>" target="_blank" class="btn btn-danger btn-sm m-r-5 m-t-10"><?php echo TEXT_PRO_UPSELL_GET_PRO; ?></a></div>'> 
                            <i class="fa fa-plus-square-o fa-3x disabled"></i>
                            <span class="upsell-label label label-theme bg-red ml-1" style="vertical-align:110%;">PRO</span>
                          </div>
                        </div>
                      </div>               
                    </div>                
                    <!-- IMAGES end-->

                    <!-- SEO & META TAGS start-->
                    <div class="ml-2 mr-2">
                      <div class="main-heading m-t-20"><span><?php echo HEADING_META_TAGS; ?></span>
                        <div class="main-heading-footer"></div>
                      </div>  

                      <div class="form-group row mb-3 mt-3">
                        <label class="col-xs-4 col-md-3 col-lg-2 control-label main-text mt-1"><?php echo LABEL_META_TITLE; ?></label>
                        <div class="col-xs-7 col-md-8 col-lg-9 p-r-0 meta-input">
                          <?php
                          echo tep_draw_textarea_field('products_head_title_tag[' . $languages[$i]['id'] . ']', 'soft', '15', '2', (isset($products_head_title_tag[$languages[$i]['id']]) ? $products_head_title_tag[$languages[$i]['id']] : tep_get_products_head_title_tag($pInfo->products_id, $languages[$i]['id'])),'class="form-control" id="products_head_title_tag_' . $languages[$i]['id'] . '"');
                          ?>                      
                        </div>
                        <div class="col-xs-1 p-l-0 p-r-0 p-relative">
                          <div class="notify-container-meta-title rounded-left rounded-right"><span class="text-black"><?php echo TEXT_COPIED; ?></span></div>                            
                          <div id="meta-title-ctc-options" class="btn-group btn-xs "> <a aria-expanded="false" href="javascript:;" data-toggle="dropdown" class=" f-w-100 text-right btn btn-xs btn-white dropdown-toggle width-full"> <span class="caret"></span> </a>
                            <ul id="meta-title-ctc-list" class="dropdown-menu pull-left">
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
                        <label class="col-xs-4 col-md-3 col-lg-2 control-label main-text mt-1"><?php echo LABEL_META_KEYWORDS;?></label>
                        <div class="col-xs-7 col-md-8 col-lg-9 p-r-0 meta-input">
                          <?php
                          echo tep_draw_textarea_field('products_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '35', '5', (isset($products_head_keywords_tag[$languages[$i]['id']]) ? $products_head_keywords_tag[$languages[$i]['id']] : tep_get_products_head_keywords_tag($pInfo->products_id, $languages[$i]['id'])),'class="form-control" id="products_head_keywords_tag_' . $languages[$i]['id'] . '"');
                          ?>                                         
                        </div>
                        <div class="col-xs-1 p-l-0 p-r-0 p-relative">
                          <div id="meta-keywords-ctc-options" class="notify-container-meta-keywords rounded-left rounded-right"><span class="text-black"><?php echo TEXT_COPIED; ?></span></div>                            
                          <div id="meta-keywords-ctc-list" class="btn-group btn-xs "> <a aria-expanded="false" href="javascript:;" data-toggle="dropdown" class=" f-w-100 text-right btn btn-xs btn-white dropdown-toggle width-full"> <span class="caret"></span> </a>
                            <ul class="dropdown-menu pull-left">
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
                        <label class="col-xs-4 col-md-3 col-lg-2 control-label main-text mt-1 pl-0"><?php echo LABEL_META_DESCRIPTION; ?></label>
                        <div class="col-xs-7 col-md-8 col-lg-9 p-r-0 meta-input">
                          <?php
                          echo tep_draw_textarea_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '35', '5', (isset($products_head_desc_tag[$languages[$i]['id']]) ? $products_head_desc_tag[$languages[$i]['id']] : tep_get_products_head_desc_tag($pInfo->products_id, $languages[$i]['id'])),'class="form-control" id="products_head_desc_tag_' . $languages[$i]['id'] . '"');
                          ?>  
                        </div>
                        <div class="col-xs-1 p-l-0 p-r-0 p-relative">
                          <div id="meta-desc-ctc-options" class="notify-container-meta-desc rounded-left rounded-right"><span class="text-black"><?php echo TEXT_COPIED; ?></span></div>                                                     
                          <div id="meta-desc-ctc-list" class="btn-group btn-xs"> <a aria-expanded="false" href="javascript:;" data-toggle="dropdown" class=" f-w-100 text-right btn btn-xs btn-white dropdown-toggle width-full"> <span class="caret"></span> </a>
                            <ul class="dropdown-menu pull-left">
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

              <!-- PRICING RULES start-->
              <div class="ml-2 mr-2">
                <div class="main-heading m-t-30"><span><?php echo HEADING_PRICING_RULES; ?></span>
                  <div class="main-heading-footer"></div>
                </div>  
                <table class="table mt-2">
                  <tbody>
                    <tr class="table-row">
                      <td class="table-col dark text-left p-r-5 p-l-5 table-th-valign-middle no-border">
                        <div class="pull-left m-r-10 c-pointer main-text f-s-12 f-w-600" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_PRO_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_PRO_UPSELL_GET_PRO_URL; ?>" target="_blank" class="btn btn-danger btn-sm m-r-5 m-t-10"><?php echo TEXT_PRO_UPSELL_GET_PRO; ?></a></div>'><?php echo LABEL_QTY_PRICE_BREAKS; ?></div>
                        <div class="col-sm-6 p-l-10 c-pointer" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_PRO_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_PRO_UPSELL_GET_PRO_URL; ?>" target="_blank" class="btn btn-danger btn-sm m-r-5 m-t-10"><?php echo TEXT_PRO_UPSELL_GET_PRO; ?></a></div>'>
                          <input disabled data-width="60" data-toggle="toggle" data-on="<i class='fa fa-check'></i> <?php echo TEXT_ON; ?>" data-off="<i class='fa fa-times'></i> <?php echo TEXT_OFF; ?>" data-onstyle="success-toggle" data-offstyle="danger disabled" type="checkbox" data-size="mini" >
                          <span class="upsell-label label label-theme m-l-5 bg-red">PRO</span>
                        </div>
                      </td>
                    </tr>
                    <tr class="table-row">
                      <td class="table-col dark text-left p-r-5 p-l-5 table-th-valign-middle no-border">
                        <div class="pull-left m-r-10 c-pointer main-text f-s-12 f-w-600" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_B2B_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_B2B_UPSELL_GET_B2B_URL; ?>" target="_blank" class="btn btn-warning btn-sm m-r-5 m-t-10"><?php echo TEXT_B2B_UPSELL_GET_B2B; ?></a></div>'><?php echo LABEL_GROUP_PRICE_OVERRIDES; ?></div>
                        <div class="col-sm-6 p-l-10 c-pointer" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_B2B_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_B2B_UPSELL_GET_B2B_URL; ?>" target="_blank" class="btn btn-warning btn-sm m-r-5 m-t-10"><?php echo TEXT_B2B_UPSELL_GET_B2B; ?></a></div>'>
                          <input disabled data-width="60" data-toggle="toggle" data-on="<i class='fa fa-check'></i> <?php echo TEXT_ON; ?>" data-off="<i class='fa fa-times'></i> <?php echo TEXT_OFF; ?>" data-onstyle="success-toggle" data-offstyle="danger disabled" type="checkbox" data-size="mini" >
                          <span class="upsell-label label label-theme m-l-5 bg-orange">B2B</span>
                        </div>
                      </td>
                    </tr>
                  </tbody>                           
                </table>
              </div>
              <!-- PRICING RULES end-->

              <!-- SUB PRODUCTS start-->
              <div class="ml-2 mr-2">
                <div class="main-heading m-t-30"><span class="mr-2"><?php echo HEADING_SUBPRODUCTS; ?></span>
                  <span class="c-pointer" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_PRO_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_PRO_UPSELL_GET_PRO_URL; ?>" target="_blank" class="btn btn-danger btn-sm m-r-5 m-t-10"><?php echo TEXT_PRO_UPSELL_GET_PRO; ?></a></div>'> 
                    <input disabled data-width="60" data-toggle="toggle" data-on="<i class='fa fa-check'></i> <?php echo TEXT_ON; ?>" data-off="<i class='fa fa-times'></i> <?php echo TEXT_OFF; ?>" data-onstyle="success-toggle" data-offstyle="danger disabled" type="checkbox" data-size="mini" >
                  </span>
                  <span class="upsell-label c-pointer main-text f-s-12 f-w-600 mr-1" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_PRO_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_PRO_UPSELL_GET_PRO_URL; ?>" target="_blank" class="btn btn-danger btn-sm m-r-5 m-t-10"><?php echo TEXT_PRO_UPSELL_GET_PRO; ?></a></div>'><span class="label label-theme m-l-5 bg-red" style="vertical-align:30%;">PRO</span></span>

                  <div class="main-heading-footer"></div>
                </div> 
                <table class="table">
                  <thead>
                    <tr class="th-row">
                      <th class="th-col text-center"><i class="fa fa-camera"></i></th>
                      <th class="th-col text-left"><?php echo TABLE_SUBPRODUCT_NAME; ?></th>
                      <th class="th-col text-left hidden-xs"><?php echo TABLE_SUBPRODUCT_MODEL; ?></th>
                      <th class="th-col text-right"><?php echo TABLE_SUBPRODUCT_PRICE; ?></th>
                      <th class="th-col text-right hidden-xs"><?php echo TABLE_SUBPRODUCT_ACTION; ?></th>
                    </tr>
                  </thead>
                </table>
              </div>
              <!-- SUB PRODUCTS end -->

              <!-- USER ACCESS SETTINGS start-->
              <div class="ml-2 mr-2">              
                <div class="main-heading m-t-30"><span><?php echo HEADING_USER_ACCESS_SETTINGS; ?></span>
                  <div class="main-heading-footer"></div>
                </div>  
                <table class="table mt-2">
                  <tbody>
                    <tr class="table-row">
                      <td class="table-col dark text-left p-r-5 p-l-5 table-th-valign-middle no-border">
                        <div class="pull-left m-r-10 c-pointer main-text f-s-12 f-w-600" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_B2B_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_B2B_UPSELL_GET_B2B_URL; ?>" target="_blank" class="btn btn-warning btn-sm m-r-5 m-t-10"><?php echo TEXT_B2B_UPSELL_GET_B2B; ?></a></div>'><?php echo LABEL_RESTRICT_ACCESS; ?></div>
                        <div class="col-sm-6 p-l-10 c-pointer" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content='<div class="text-white"><?php echo TEXT_B2B_UPSELL_POPOVER_BODY; ?></div><div class="text-center w-100"><a href="<?php echo TEXT_B2B_UPSELL_GET_B2B_URL; ?>" target="_blank" class="btn btn-warning btn-sm m-r-5 m-t-10"><?php echo TEXT_B2B_UPSELL_GET_B2B; ?></a></div>'>
                          <input disabled data-width="60" data-toggle="toggle" data-on="<i class='fa fa-check'></i> <?php echo TEXT_ON; ?>" data-off="<i class='fa fa-times'></i> <?php echo TEXT_OFF; ?>" data-onstyle="success-toggle" data-offstyle="danger disabled" type="checkbox" data-size="mini" >
                          <span class="upsell-label c-pointer label label-theme m-l-5 bg-orange">B2B</span>
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
                      <td class="table-col dark text-left<?php echo $col_selected; ?>"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '"><i class="fa fa-folder fa-lg text-warning mr-2"></i></a>' . $categories['categories_name']; ?></td>
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
                                                             p.products_model like '%" . tep_db_input($search) . "%' or
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
                          echo '<tr class="table-row dark selected" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_product&pID=' . $products['products_id'])  . '\'">' . "\n";
                        } else {
                          echo '<tr class="table-row dark" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '\'">' . "\n";
                        }
                        $pcol_selected = ($pselected) ? ' selected' : '';

                        ?>
                          <td class="table-col dark text-left<?php echo $pcol_selected; ?>"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product') . '"><i class="fa fa-circle-o fa-lg text-info mr-2"></i></a>' . $products['products_name']; ?></td>
                          <td class="table-col dark text-center<?php echo $pcol_selected; ?> d-none d-lg-table-cell col-blank">
                          <?php
                            if ($products['products_status'] == '1') {
                              echo '<i class="fa fa-lg fa-check-circle text-success mr-2"></i><a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '"><i class="fa fa-lg fa-times-circle text-secondary"></i></a>';
                            } else {
                              echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '"><i class="fa fa-lg fa-times-circle text-danger"></i></a>&nbsp;&nbsp;<i class="fa fa-lg fa-check-circle text-secondary"></i>';
                            }
                            ?>
                          </td>
                          <td class="table-col dark text-right<?php echo $pcol_selected; ?>"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product') . '"><i class="fa fa-edit fa-lg text-success"></i></a>'; ?>
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
                        echo '<button class="btn btn-success btn-sm ml-1" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'ggcPath=' . $cPath . '&action=new_category') . '\'">' . IMAGE_NEW_CATEGORY . '</button><button class="btn btn-success btn-sm ml-2" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_product')  . '\'">' . IMAGE_NEW_PRODUCT . '</button>'; 
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
              case 'delete_category':
                $heading[] = array('text' => TEXT_INFO_HEADING_DELETE_CATEGORY);
                $contents[] = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
                if ($cInfo->childs_count > 0) $contents[] = array('text' => '<div class="row"><div class="col p-0 mt-3 ml-2 mr-2"><div class="note note-warning m-0"><h4 class="m-0">' . TEXT_WARNING . '</h4><p class="mb-0 mt-2">' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count) . '</p></div></div></div>');     
                if ($cInfo->products_count > 0) $contents[] = array('text' => '<div class="row"><div class="col p-0 mt-3 ml-2 mr-2"><div class="note note-warning m-0"><h4 class="m-0">' . TEXT_WARNING . '</h4><p class="mb-0 mt-2">' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count) . '</p></div></div></div>');     
                $contents[] = array('text' => '<div class="row"><div class="col p-0 mt-3 ml-2 mr-2"><div class="note note-danger m-0 fw-400">' . sprintf(TEXT_DELETE_CATEGORY_INTRO, $cInfo->categories_name) . '</div></div></div>');
                $contents[] = array('align' => 'center', 'text' => '<button type="button" class="btn btn-default btn-sm mr-2 mt-3 mb-4" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id)  . '\'">' . IMAGE_CANCEL . '</button><button class="btn btn-danger btn-sm mt-3 mb-4" type="submit">' . IMAGE_CONFIRM_DELETE . '</button>');
                break;
              case 'move_category':
                $heading[] = array('text' => HEADING_MOVE_CATEGORY);
                $contents[] = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=move_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
                $contents[] = array('text' => '<div class="sidebar-text mt-3">' . sprintf(LABEL_MOVE_INTRO, $cInfo->categories_name) . '</div>');
                $contents[] = array('text' => '<div class="sidebar-title mt-2">' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '</div><div>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id, 'class="form-control"') . '</div>');
                $contents[] = array('align' => 'center', 'text' => '<button type="button" class="btn btn-default btn-sm mr-2 mt-3 mb-4" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '\'">' . IMAGE_CANCEL . '</button><button class="btn btn-success btn-sm mt-3 mb-4" type="submit">' . IMAGE_MOVE . '</button>');
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
                $heading[] = array('text' => HEADING_MOVE_PRODUCT);
                $contents[] = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=move_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
                $contents[] = array('text' => '<div class="sidebar-text mt-3">' . sprintf(LABEL_MOVE_INTRO, $pInfo->products_name));
                $contents[] = array('text' => '<div class="sidebar-text mt-3">' . LABEL_CURRENT_CATEGORIES . '</div><div class="sidebar-title">' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</div>');
                $contents[] = array('text' => '<div class="sidebar-title mt-3">' . sprintf(TEXT_MOVE, $pInfo->products_name) . '</div><div>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id, 'class="form-control"') . '</div>');
                $contents[] = array('align' => 'center', 'text' => '<button type="button" class="btn btn-default btn-sm mr-2 mt-3 mb-4" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '\'">' . IMAGE_CANCEL . '</button><button class="btn btn-info btn-sm mt-3 mb-4" type="submit">' . IMAGE_MOVE . '</button>');
                break;
              case 'copy_to':
                $heading[] = array('text' => HEADING_COPY_TO);
                $contents[] = array('form' => tep_draw_form('copy_to', FILENAME_CATEGORIES, 'action=copy_to_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
                $contents[] = array('text' => '<div class="sidebar-text mt-3">' . LABEL_CURRENT_CATEGORY . '</div><div class="sidebar-title">' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</div>');
                $contents[] = array('text' => '<div class="sidebar-text mt-3">' . LABEL_COPY_TO_INTRO . '</div><div>' . tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id, 'class="form-control"') . '</div>');
                $contents[] = array('text' => '<div class="sidebar-text mt-3">' . TEXT_HOW_TO_COPY . '</div><div class="mt-2">' . tep_draw_radio_field('copy_as', 'link', true, null, 'onchange="setAttrText(\'link\');"') . '<span class="sidebar-text ml-1">' . TEXT_COPY_AS_LINK . '</span></div><div class="mt-2">' . tep_draw_radio_field('copy_as', 'duplicate', false, null, 'onchange="setAttrText(\'dupe\');"') . '<span class="sidebar-text ml-1">' . TEXT_COPY_AS_DUPLICATE . '</span></div>');
                // only ask about attributes if they exist
                if (tep_has_product_attributes($pInfo->products_id)) {
                  $contents[] = array('text' => '<div id="copy_attr" style="display:none;"><div class="sidebar-text mt-3 mb-3">' . TEXT_COPY_ATTRIBUTES . '</div><div class="mt-2">' . tep_draw_radio_field('copy_attributes', 'copy_attributes_yes', true) . '<span class="sidebar-text ml-1">' . TEXT_COPY_ATTRIBUTES_YES . '</span></div><div class="mt-2">' . tep_draw_radio_field('copy_attributes', 'copy_attributes_no') . '<span class="sidebar-text ml-1">' . TEXT_COPY_ATTRIBUTES_NO . '</span></div></div>');
                  $contents[] = array('align' => 'center', 'text' => '<div class="mt-3 mb-0"><button class="btn btn-success btn-sm mt-0" data-toggle="modal" data-target="#listAttributesModal">' . TEXT_LIST_ATTRIBUTES . '</button></div>');
                }
                $contents[] = array('align' => 'center', 'text' => '<button type="button" class="btn btn-default btn-sm mr-2 mt-2 mb-4" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '\'">' . IMAGE_CANCEL . '</button><button class="btn btn-info btn-sm mt-2 mb-4" type="submit">' . IMAGE_COPY . '</button>');
                break;
              case 'new_category':
              case 'edit_category':
                $heading[] = array('text' => 'Settings');
                $contents[] = array('text' => '
                  <div class="sidebar-content-container">
                    <div class="form-group row mt-3">
                      <label class="col-sm-5 control-label sidebar-edit pl-3 pr-0 c-pointer text-muted mt-2">' . LABEL_SORT_ORDER . '</label>
                      <div class="col-sm-7 p-relative">' . 
                        tep_draw_input_field('sort_order', (isset($cInfo->sort_order) ? $cInfo->sort_order : ''), 'class="form-control"') . '
                      </div>
                    </div> 
                  </div>');              
                break;
              case 'new_product':
                $heading[] = array('text' => 'Pricing and Availability');
                $contents[] = array('text' => '
                  <div class="sidebar-content-container">
                    <div class="form-group row">
                      <label class="col-sm-5 control-label sidebar-edit mt-2 pl-3 pr-0">' . LABEL_BASE_PRICE . '</label>
                      <div class="col-sm-7">
                        <div class="input-group"> <span class="input-group-addon text-white bg-blue-lighter p-5">' . $currencies->get_symbol_left(DEFAULT_CURRENCY) . '</span>' .
                          tep_draw_input_field('products_price', (isset($pInfo) ? number_format($pInfo->products_price, 2) : ''), 'id="products_price" class="form-control f-w-600 f-s-14 p-l-5 p-r-5 text-primary" onkeyup="updateGross()"') . '
                        </div>
                      </div>
                    </div>' . ((isset($pInfo) && $pInfo->products_tax_class_id > 0) ? '
                    <div class="price-with-tax form-group row mt-3">
                      <label class="col-sm-5 control-label sidebar-edit mt-2 pl-3 pr-0">' . LABEL_PRICE_WITH_TAX . '</label>
                      <div class="col-sm-7">
                        <div class="input-group"> <span class="input-group-addon text-white bg-success p-5">' . $currencies->get_symbol_left(DEFAULT_CURRENCY) . '</span>' .
                          tep_draw_input_field('products_price_gross', null, 'id="products_price_gross" class="form-control f-w-600 f-s-14 p-l-5 p-r-5 text-green" onkeyup="updateNet()"') . '
                        </div>
                      </div>
                    </div>' : '') .'                                           
                    <div class="form-group row mt-3">
                      <label class="col-sm-5 control-label sidebar-edit mt-2 pl-3 pr-0">' . LABEL_SPECIAL_PRICE . '</label>
                      <div class="col-sm-7">
                        <div class="input-group"> <span class="input-group-addon text-white bg-red-lighter p-5 ">' . $currencies->get_symbol_left(DEFAULT_CURRENCY) . '</span>' . 
                          tep_draw_input_field('products_special_price', (isset($pInfo) ? Specials::getSpecialPrice($pInfo->products_id) : ''), 'class="form-control f-s-14 p-l-5 p-r-5 text-danger"') . '
                        </div>
                      </div>
                    </div>

                    <div class="form-group row mt-3" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content="<div class=\'text-white\'>' . TEXT_PRO_UPSELL_POPOVER_BODY . '</div><div class=\'text-center w-100\'><a href=\''. TEXT_PRO_UPSELL_GET_PRO_URL . '\' target=\'_blank\' class=\'btn btn-danger btn-sm m-r-5 m-t-10\'>' . TEXT_PRO_UPSELL_GET_PRO . '</a></div>">
                      <label class="col-sm-5 control-label sidebar-edit mt-2 pl-3 pr-0 c-pointer mb-1">' . LABEL_MSRP . '</label>
                      <div class="col-sm-7 p-relative">
                        <div class="input-group "> <span class="input-group-addon text-white bg-silver-darker p-5">' . $currencies->get_symbol_left(DEFAULT_CURRENCY) . '</span>
                          <input disabled class="form-control f-s-14 p-l-5 p-r-5" type="text" value=""> 
                        </div>
                        <div class="ribbon"><img src="assets/img/ribbon-pro.png"></div>
                      </div>
                    </div>

                    <div class="form-group row mt-3">
                      <label class="col-sm-5 control-label sidebar-edit mt-2 pl-3 pr-0">' . LABEL_DATE_AVAILABLE . '</label>
                      <div class="col-sm-7">
                        <input id="products_date_available" name="products_date_available" value="' . (isset($pInfo) ? tep_date_short($pInfo->products_date_available) : '') . '" type="text" class="form-control" placeholder="MM/DD/YYYY" data-date-autoclose />
                      </div>
                    </div>

                    <div class="form-group row mt-3">
                      <label class="col-sm-5 control-label sidebar-edit mt-2 pl-3 pr-0">' . LABEL_STATUS . '</label>
                      <div class="col-sm-7">
                        <input name="products_status" ' . (($in_status) ? 'checked' : '') . ' data-toggle="toggle" data-on="<i class=\'fa fa-check\'></i> ' . TEXT_ACTIVE . '" data-off="<i class=\'fa fa-times\'></i> ' . TEXT_INACTIVE . '" data-onstyle="success" data-offstyle="danger" type="checkbox" data-size="small">
                      </div>
                    </div>

                    <div class="form-group row mt-3">
                      <label class="col-sm-5 control-label sidebar-edit mt-2 pl-3 pr-0">' . LABEL_FEATURED_PRODUCTS . '</label>
                      <div class="col-sm-7">
                        <input name="featured" ' . (isset($pInfo) ? ((Featured::isFeatured($pInfo->products_id)) ? 'checked' : '') : '') . ' data-toggle="toggle" data-on="<i class=\'fa fa-star\'></i> ' . TEXT_YES . '" data-off="<i class=\'fa fa-star-o\'></i> ' . TEXT_NO . '" data-onstyle="success" data-offstyle="danger" type="checkbox" data-size="small">
                      </div>
                    </div>
                  </div>

                  <div class="sidebar-heading mt-3">    
                    <span>' . HEADING_INVENTORY . '</span>  
                  </div><div class="sidebar-heading-footer w-100"></div>

                  <div class="form-group row mt-3">
                    <label class="col-sm-5 control-label sidebar-edit mt-2 pl-3 pr-0">' . LABEL_MODEL . '</label>
                    <div class="col-sm-7">' . tep_draw_input_field('products_model', (isset($pInfo) ? $pInfo->products_model : ''), 'class="form-control"') . '</div>
                  </div>

                  <div class="form-group row mt-3">
                    <label class="col-sm-5 control-label sidebar-edit mt-2 pl-3 pr-0">' . LABEL_SKU . '</label>
                    <div class="col-sm-7">' . tep_draw_input_field('products_sku', (isset($pInfo) ? $pInfo->products_sku : ''), 'class="form-control"') . '
                    </div>
                  </div>
                  <div class="form-group row mt-3">
                    <label class="col-sm-5 control-label sidebar-edit mt-2 pl-3 pr-0">' . LABEL_MANUFACTURER . '</label>
                    <div class="col-sm-7">' . tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, (isset($pInfo) ? $pInfo->manufacturers_id : 0), 'class="form-control"') . '</div>
                  </div>

                  <div class="form-group row mt-3">
                    <label class="col-sm-5 control-label sidebar-edit mt-2 pl-3 pr-0">' . LABEL_TAX_CLASS . '</label>
                    <div class="col-sm-7">' . tep_draw_pull_down_menu('products_tax_class_id', $tax_class_array, (isset($pInfo) ? $pInfo->products_tax_class_id : 0), 'id="products_tax_class_id" class="form-control" onchange="updateGross()"') . '</div>
                  </div>

                  <div class="form-group row mt-3">
                    <label class="col-sm-5 control-label sidebar-edit mt-2 pl-3 pr-0">' . LABEL_WEIGHT . '</label>
                    <div class="col-sm-7 input-group">' . tep_draw_input_field('products_weight', (isset($pInfo) ? $pInfo->products_weight : 0), 'class="form-control"') . '</div>
                  </div>

                  <div class="form-group row mt-3">
                    <label class="col-sm-5 control-label sidebar-edit mt-2 pl-3 pr-0">' . LABEL_QUANTITY . '</label>
                    <div class="col-sm-7">' . tep_draw_input_field('products_quantity', (isset($pInfo) ? $pInfo->products_quantity : ''), 'class="form-control"') . '</div>
                  </div>

                  <div class="form-group row mt-3" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content="<div class=\'text-white\'>' . TEXT_PRO_UPSELL_POPOVER_BODY . '</div><div class=\'text-center w-100\'><a href=\''. TEXT_PRO_UPSELL_GET_PRO_URL . '\' target=\'_blank\' class=\'btn btn-danger btn-sm m-r-5 m-t-10\'>' . TEXT_PRO_UPSELL_GET_PRO . '</a></div>">
                    <label class="col-sm-5 control-label sidebar-edit mt-2 pl-3 pr-0 text-muted c-pointer mb-1">' . LABEL_ITEM_COST . '</label>
                    <div class="col-sm-7 p-relative">
                      <input disabled class="form-control" type="text">
                      <div class="ribbon"><img src="assets/img/ribbon-pro.png"></div>                       
                    </div>
                  </div>

                  <div class="form-group row mt-3" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content="<div class=\'text-white\'>' . TEXT_PRO_UPSELL_POPOVER_BODY . '</div><div class=\'text-center w-100\'><a href=\''. TEXT_PRO_UPSELL_GET_PRO_URL . '\' target=\'_blank\' class=\'btn btn-danger btn-sm m-r-5 m-t-10\'>' . TEXT_PRO_UPSELL_GET_PRO . '</a></div>">
                    <label class="col-sm-5 control-label sidebar-edit mt-2 pl-3 pr-0 text-muted c-pointer mb-1">' . LABEL_VENDOR . '</label>
                    <div class="col-sm-7 p-relative">
                      <select disabled class="form-control">
                        <option>' . OPTION_SELECT_VENDOR . '</option>
                      </select>
                      <div class="ribbon"><img src="assets/img/ribbon-pro.png"></div>                        
                    </div>
                  </div>

                  <div class="form-group row mt-3" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content="<div class=\'text-white\'>' . TEXT_PRO_UPSELL_POPOVER_BODY . '</div><div class=\'text-center w-100\'><a href=\''. TEXT_PRO_UPSELL_GET_PRO_URL . '\' target=\'_blank\' class=\'btn btn-danger btn-sm m-r-5 m-t-10\'>' . TEXT_PRO_UPSELL_GET_PRO . '</a></div>">
                    <label class="col-sm-5 control-label sidebar-edit mt-2 pl-3 pr-0 text-muted c-pointer mb-1">' . LABEL_VENDOR_NOTE . '</label>
                    <div class="col-sm-7 p-relative">
                      <textarea disabled class="form-control" id="vendornote" name="vendornote" rows="3"></textarea>
                      <div class="ribbon"><img src="assets/img/ribbon-pro.png"></div>
                    </div>
                  </div>

                  <div class="sidebar-heading mt-3">    
                    <span>' . HEADING_CATALOG . '</span>  
                  </div><div class="sidebar-heading-footer w-100"></div>

                  <div class="form-group row mt-3" data-toggle="popover" data-placement="top" data-html="true" data-content="<div class=\'text-white\'>' . TEXT_PRO_UPSELL_POPOVER_BODY . '</div><div class=\'text-center w-100\'><a href=\''. TEXT_PRO_UPSELL_GET_PRO_URL . '\' target=\'_blank\' class=\'btn btn-danger btn-sm m-r-5 m-t-10\'>' . TEXT_PRO_UPSELL_GET_PRO . '</a></div>">
                    <label class="col-sm-5 control-label sidebar-edit pl-3 pr-0 c-pointer text-muted mt-2">' . LABEL_SORT_ORDER . '</label>
                    <div class="col-sm-7 p-relative">
                      <input disabled class="form-control" type="text">
                      <div class="ribbon"><img src="assets/img/ribbon-pro.png"></div>
                    </div>
                  </div>      

                  <div class="sidebar-heading mt-3">    
                    <span>' . HEADING_EXTRA_FIELDS . '</span>  
                  </div><div class="sidebar-heading-footer w-100"></div>                                  

                  <div class="form-group row mt-3 mb-3" data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content="<div class=\'text-white\'>' . TEXT_PRO_UPSELL_POPOVER_BODY . '</div><div class=\'text-center w-100\'><a href=\''. TEXT_PRO_UPSELL_GET_PRO_URL . '\' target=\'_blank\' class=\'btn btn-danger btn-sm m-r-5 m-t-10\'>' . TEXT_PRO_UPSELL_GET_PRO . '</a></div>">
                    <label class="col-xs-5 control-label sidebar-edit pl-3 pr-0 text-muted c-pointer mb-1">' . LABEL_ENABLE . '</label>
                    <div class="col-xs-7">
                      <input disabled data-width="60" data-toggle="toggle" data-on="<i class=\'fa fa-check\'></i> ' . TEXT_ON . '" data-off="<i class=\'fa fa-times\'></i> ' . TEXT_OFF . '" data-onstyle="success-toggle" data-offstyle="danger disabled" type="checkbox" data-size="mini" >
                      <span class="upsell-label c-pointer label label-theme ml-2 bg-red">PRO</span>                        
                    </div>
                  </div>

                  ');
                   
                break;
              default:
                if ($rows > 0) {
                  if (isset($cInfo) && is_object($cInfo)) { // category info box contents
                    $heading[] = array('text' => '<div class="text-truncate">' . $cInfo->categories_name . '</div>');
                    // RCO start
                    if ($cre_RCO->get('categories', 'csidebarbuttons') !== true) {
                      $contents[] = array('align' => 'center', 'text' => '<div class="mt-2 mb-2">
                        <button class="btn btn-success btn-sm mt-2" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '\'">' . IMAGE_EDIT . '</button>
                        <button class="btn btn-danger btn-sm mr-1 ml-1 mt-2" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=delete_category')  . '\'">' . IMAGE_DELETE . '</button>
                        <button class="btn btn-success btn-sm mt-2" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=move_category') . '\'">' . IMAGE_MOVE . '</button>
                        <button class="btn btn-success btn-sm mt-2" onclick="window.open(\'' . HTTP_SERVER . DIR_WS_CATALOG . 'index.php?cPath=' . $cPath . ((isset($cID) && $cID != '') ? '&cID=' . $cID : ''). '\')">' . BUTTON_VIEW_IN_CATALOG . '</button>' .           
                        (($cInfo->childs_count == 0 && $cInfo->products_count >= 1 && $cID) ? '
                        <button class="btn btn-success btn-sm ml-1 mt-2" data-toggle="modal" data-target="#copyCategoryAttributesModal">' . TEXT_BUTTON_COPY_ATTRIBUTES . '</button>' : '') . '</div>');
                    }
                    // RCO eof
                    $contents[] = array('text' => '<div class="sidebar-text mt-3">' . TEXT_DATE_ADDED . '<span class="sidebar-title ml-2">' . tep_date_short($cInfo->date_added) . '</span></div>');
                    if (tep_not_null($cInfo->last_modified)) $contents[] = array('text' => '<div class="sidebar-text mt-1">' . TEXT_LAST_MODIFIED . '<span class="sidebar-title ml-2">' . tep_date_short($cInfo->last_modified) . '</span></div>');
                    $contents[] = array('align' => 'center', 'text' => '<div class="sidebar-img mt-3 well ml-4 mr-4 mb-1">' . tep_info_image($cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '</div><div class="sidebar-text mb-0 mt-0">' . $cInfo->categories_image . '</div><div class="sidebar-text mt-0 mb-3">(' . HEADING_IMAGE_WIDTH . 'x' . ((HEADING_IMAGE_HEIGHT == '') ? HEADING_IMAGE_WIDTH : HEADING_IMAGE_HEIGHT) . ')</div>');
                    $contents[] = array('text' => '<div class="sidebar-text mt-1">' . TEXT_SUBCATEGORIES . '<span class="sidebar-title ml-2">' . $cInfo->childs_count . '</span></div><div class="sidebar-text mt-1">' . TEXT_PRODUCTS . '<span class="sidebar-title ml-2">' . $cInfo->products_count . '</span></div>');

                    // RCI include category sidebar bottom text
                    $returned_rci = $cre_RCI->get('categories', 'csidebarbottom');
                    $contents[] = array('text' => $returned_rci);
                  } elseif (isset($pInfo) && is_object($pInfo)) { // product info box contents
                    $heading[] = array('text' => '<div class="text-truncate">' . tep_get_products_name($pInfo->products_id, $languages_id) . '</div>');
                    // RCO start
                    if ($cre_RCO->get('categories', 'psidebarbuttons') !== true) {
                      $contents[] = array('align' => 'center', 'text' => '<div class="mt-2 mb-2">
                        <button class="btn btn-success btn-sm mt-2" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $pInfo->categories_id . '&pID=' . $pInfo->products_id . '&action=new_product') . '\'">' . IMAGE_EDIT . '</button>
                        <button class="btn btn-danger btn-sm mr-1 ml-1 mt-2" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $pInfo->categories_id . '&pID=' . $pInfo->products_id . '&action=delete_product') . '\'">' . IMAGE_DELETE . '</button>
                        <button class="btn btn-success btn-sm mr-1 mt-2" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $pInfo->categories_id . '&pID=' . $pInfo->products_id . '&action=move_product') . '\'">' . IMAGE_MOVE . '</button>
                        <button class="btn btn-success btn-sm mt-2 mr-1" onclick="window.location=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $pInfo->categories_id . '&pID=' . $pInfo->products_id . '&action=copy_to') . '\'">' . IMAGE_COPY_TO . '</button>' .
                        ((Product::hasProductAttributes($pInfo->products_id)) ? '<button class="btn btn-success btn-sm ml-1 mt-2" data-toggle="modal" data-target="#copyAttributesModal">' . TEXT_BUTTON_COPY_ATTRIBUTES . '</button>' : '') . '
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
                    $contents[] = array('text' => '<div class="sidebar-text mt-1 mb-3">' . TEXT_PRODUCTS_AVERAGE_RATING . '<span class="sidebar-title ml-2">' . number_format($pInfo->average_rating, 2) . '%</span></div>');
                    //RCI include product sidebar product text
                    $returned_rci = $cre_RCI->get('categories', 'psidebarproducttext');
                    $contents[] = array('text' => $returned_rci);

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

          <!-- copy attributes modal -->
          <div class="modal fade" id="copyAttributesModal" tabindex="-1" role="dialog" aria-labelledby="copyAttributesModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content fade-in">
                <form id="copy_attributes" name="copy_attributes" role="form" method="post">
                <div class="modal-header">
                  <h4 class="modal-title" id="copyAttributesModal"><?php echo HEADING_COPY_PRODUCT_ATTRIBUTES; ?></h4>
                  <button type="button" class="close" data-dismiss="modal" style="position:absolute; top:0; right:20px;" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body pt-0">
                  <div class="border rounded mt-2 ml-2 mr-2" style="background-color:#e1e8ef;">
                    <div class="form-group ml-2 mb-0">
                      <label class="control-label mt-2 pr-0"><?php echo LABEL_COPY_PRODUCT_ATTRIBUTES_FROM; ?></label>
                      <div class="mr-2"><?php echo tep_draw_pull_down_menu('copy_from', array(array('id' => $pInfo->products_id, 'text' => $pInfo->products_name)), (isset($pInfo) ? $pInfo->products_id : 0), 'class="form-control" readonly'); ?></div>
                    </div>
                    <?php 
                    if (Product::hasProductAttributes($pInfo->products_id)) {
                      ?>
                      <div onclick="$('.attributes-container').toggle();" class="mt-2 mb-3 ml-2" style="cursor:pointer;"><span class="label label-success">View Current Attributes <i class="fa fa-chevron-down"></i></span></div>
                      <div class="attributes-container row ml-2 mr-2" style="display:none;">
                        <?php  
                        $result = '';
                        $products_options_name = tep_db_query("select distinct popt.products_options_id, poptt.products_options_name, popt.products_options_sort_order from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_TEXT  . " poptt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $pInfo->products_id . "' and patrib.options_id = popt.products_options_id and poptt.language_id = '" . $languages_id . "'" . " order by popt.products_options_sort_order");
                        while ($products_options_name_values = tep_db_fetch_array($products_options_name)) {
                          $selected = 0;
                          $products_options_array = array();
                          
                          echo '<div class="p-option mt-2 pb-3 w-100">
                                  <label class="control-label">' . $products_options_name_values['products_options_name'] . ':</label>
                                  <span class="p-option-value">' . "\n";
                          
                          $products_options = tep_db_query("select pa.products_options_sort_order, pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $pInfo->products_id . "' and pa.options_id = '" . $products_options_name_values['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . $languages_id . "'" . " order by pa.products_options_sort_order, pa.options_values_price");
                          while ($products_options_values = tep_db_fetch_array($products_options)) {
                            $products_options_array[] = array('id' => $products_options_values['products_options_values_id'], 'text' => $products_options_values['products_options_values_name']);
                            if ($products_options_values['options_values_price'] != '0') {
                              $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options_values['price_prefix'] . $currencies->format($products_options_values['options_values_price']) .') ';
                            }
                          }
                          echo tep_draw_pull_down_menu('id[' . $products_options_name_values['products_options_id'] . ']', $products_options_array, $cart->contents[$_GET['products_id']]['attributes'][$products_options_name_values['products_options_id']], 'class="form-control"');
                          echo '</span></div>';
                        }
                        ?>

                      </div>
                      <?php 
                    } 
                    ?>
                  </div>

                  <div class="border rounded mt-2 ml-2 mr-2 pb-3" style="background-color:#f0e7e2;">
                    <div class="form-group ml-2 mb-0">
                      <label class="control-label mt-2 pr-0"><?php echo LABEL_COPY_PRODUCT_ATTRIBUTES_TO; ?></label>
                      <div class="mr-2"><?php echo tep_draw_pull_down_menu('copy_to_products_id', Products::getAll('list', $pInfo->products_id), null, 'class="form-control"'); ?></div>
                    </div>
                  </div>

                  <div class="form-group mt-3">
                    <label class="control-label mt-0 mb-0 pl-2 pr-0"><?php echo LABEL_DELETE_ALL_ATTRIBUTES; ?></label>
                    <span class="ml-2"><?php echo tep_draw_checkbox_field('copy_attributes_delete_first', $copy_attributes_delete_first, null, null, 'class="js-switch js-attr-check-0" checked'); ?></span>
                  </div>

                  <div class="form-group">
                    <label class="control-label mt-0 mb-0 pl-2 pr-0"><?php echo LABEL_SKIP_DUPLICATE_ATTRIBUTES; ?></label>
                    <span class="ml-2"><?php echo tep_draw_checkbox_field('copy_attributes_duplicates_skipped', $copy_attributes_duplicates_skipped, null, null, 'class="js-switch js-attr-check-1"'); ?></span>
                  </div>

                  <div class="form-group">
                    <label class="control-label mt-0 mb-0 pl-2 pr-0"><?php echo LABEL_OVERWRITE_DUPLICATE_ATTRIBUTES; ?></label>
                    <span class="ml-2"><?php echo tep_draw_checkbox_field('copy_attributes_duplicates_overwrite', $copy_attributes_duplicates_overwrite, null, null, 'class="js-switch js-attr-check-2"'); ?></span>
                  </div>

                  <div class="form-group">
                    <label class="control-label mt-0 mb-0 pl-2 pr-0"><?php echo LABEL_INCLUDE_DOWNLOAD_ATTRIBUTES; ?></label>
                    <span class="ml-2"><?php echo tep_draw_checkbox_field('copy_attributes_include_downloads', $copy_attributes_include_downloads, null, null, 'class="js-switch" checked'); ?></span>
                  </div>                  
  
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo IMAGE_CANCEL; ?></button>
                  <button type="button" class="btn btn-success" onclick="copyProductAttributes();"><?php echo IMAGE_COPY; ?></button>
                </div>
              </form>
              </div>
              <div class="modal-loader" style="display:none;"><span class="spinner"></span></div>
            </div>
          </div>

          <!-- copy categories attributes modal -->
          <div class="modal fade" id="copyCategoryAttributesModal" tabindex="-1" role="dialog" aria-labelledby="copyCategoryAttributesModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content fade in">

                <form id="copy_category_attributes" name="copy_category_attributes" role="form" method="post">
                <div class="modal-header">
                  <h4 class="modal-title" id="copyCategoryAttributesModal"><?php echo HEADING_COPY_PRODUCT_ATTRIBUTES; ?></h4>
                  <button type="button" class="close" data-dismiss="modal" style="position:absolute; top:0; right:20px;" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body pt-0">
                  <div class="border rounded mt-2 ml-2 mr-2" style="background-color:#e1e8ef;">
                    <div class="form-group ml-2 mb-0">
                      <label class="control-label mt-2 pr-0"><?php echo LABEL_COPY_PRODUCT_ATTRIBUTES_FROM; ?></label>
                      <div class="mr-2 mb-3"><?php echo tep_draw_pull_down_menu('make_copy_from_products_id', Products::getAll('list'), null, 'class="form-control"'); ?></div>
                    </div>
                    <?php 
                    /*
                    if (Product::hasProductAttributes($pInfo->products_id)) {
                      ?>
                      <div onclick="$('.attributes-container').toggle();" class="mt-2 mb-3 ml-2" style="cursor:pointer;"><span class="label label-success">View Current Attributes <i class="fa fa-chevron-down"></i></span></div>
                      <div class="attributes-container row ml-2 mr-2" style="display:none;">
                        <?php  
                        $result = '';
                        $products_options_name = tep_db_query("select distinct popt.products_options_id, poptt.products_options_name, popt.products_options_sort_order from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_TEXT  . " poptt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $pInfo->products_id . "' and patrib.options_id = popt.products_options_id and poptt.language_id = '" . $languages_id . "'" . " order by popt.products_options_sort_order");
                        while ($products_options_name_values = tep_db_fetch_array($products_options_name)) {
                          $selected = 0;
                          $products_options_array = array();
                          
                          echo '<div class="p-option mt-2 pb-3 w-100">
                                  <label class="control-label">' . $products_options_name_values['products_options_name'] . ':</label>
                                  <span class="p-option-value">' . "\n";
                          
                          $products_options = tep_db_query("select pa.products_options_sort_order, pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $pInfo->products_id . "' and pa.options_id = '" . $products_options_name_values['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . $languages_id . "'" . " order by pa.products_options_sort_order, pa.options_values_price");
                          while ($products_options_values = tep_db_fetch_array($products_options)) {
                            $products_options_array[] = array('id' => $products_options_values['products_options_values_id'], 'text' => $products_options_values['products_options_values_name']);
                            if ($products_options_values['options_values_price'] != '0') {
                              $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options_values['price_prefix'] . $currencies->format($products_options_values['options_values_price']) .') ';
                            }
                          }
                          echo tep_draw_pull_down_menu('id[' . $products_options_name_values['products_options_id'] . ']', $products_options_array, $cart->contents[$_GET['products_id']]['attributes'][$products_options_name_values['products_options_id']], 'class="form-control"');
                          echo '</span></div>';
                        }
                        ?>

                      </div>
                      <?php 
                    } 
                    */
                    ?>
                  </div>

                  <div class="border rounded mt-2 ml-2 mr-2 pb-3" style="background-color:#f0e7e2;">
                    <div class="form-group ml-2 mb-0">
                      <label class="control-label mt-2 pr-0"><?php echo LABEL_COPY_ALL_PRODUCTS_TO_CATEGORY; ?></label>
                      <div class="mr-2"><?php echo tep_draw_pull_down_menu('copy_to_products_id', array(array('id' => 0, 'text' => tep_get_category_name($cID, $languages_id))), 0, 'class="form-control"'); ?></div>
                    </div>
                  </div>

                  <div class="form-group mt-3">
                    <label class="control-label mt-0 mb-0 pl-2 pr-0"><?php echo LABEL_DELETE_ALL_ATTRIBUTES; ?></label>
                    <span class="ml-2"><?php echo tep_draw_checkbox_field('copy_attributes_delete_first', $copy_attributes_delete_first, null, null, 'class="js-switch js-attr-check-0" checked'); ?></span>
                  </div>

                  <div class="form-group">
                    <label class="control-label mt-0 mb-0 pl-2 pr-0"><?php echo LABEL_SKIP_DUPLICATE_ATTRIBUTES; ?></label>
                    <span class="ml-2"><?php echo tep_draw_checkbox_field('copy_attributes_duplicates_skipped', $copy_attributes_duplicates_skipped, null, null, 'class="js-switch js-attr-check-1"'); ?></span>
                  </div>

                  <div class="form-group">
                    <label class="control-label mt-0 mb-0 pl-2 pr-0"><?php echo LABEL_OVERWRITE_DUPLICATE_ATTRIBUTES; ?></label>
                    <span class="ml-2"><?php echo tep_draw_checkbox_field('copy_attributes_duplicates_overwrite', $copy_attributes_duplicates_overwrite, null, null, 'class="js-switch js-attr-check-2"'); ?></span>
                  </div>

                  <div class="form-group">
                    <label class="control-label mt-0 mb-0 pl-2 pr-0"><?php echo LABEL_INCLUDE_DOWNLOAD_ATTRIBUTES; ?></label>
                    <span class="ml-2"><?php echo tep_draw_checkbox_field('copy_attributes_include_downloads', $copy_attributes_include_downloads, null, null, 'class="js-switch" checked'); ?></span>
                  </div>                  
  
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo IMAGE_CANCEL; ?></button>
                  <button type="button" class="btn btn-success btn-copy" onclick="copyCategoryProductAttributes();"><?php echo IMAGE_COPY; ?></button>
                </div>
              </form>
              </div>
              <div class="modal-loader" style="display:none;"><span class="spinner"></span></div>
            </div>
          </div>

        </div> <!-- end row -->
      </div> <!-- end table-categories -->
    </div> <!-- end dark -->
    <?php if (isset($action) && ($action == 'new_product' || $action == 'new_category' || $action == 'edit_category')) {
      ?>
      <!-- div class="col-sm-9 col-md-10 m-b-10 mt-2 pl-0"> 
        <button type="submit" onclick="updateProduct('save');" class="btn btn-primary m-r-3"><i class="fa fa-save"></i> <?php echo BUTTON_SAVE; ?></button>
        <button type="submit" onclick="updateProduct('stay');" class="btn btn-info m-r-3 btn-save-stay"><i class="fa fa-save"></i> <?php echo BUTTON_SAVE_STAY; ?></button>
      </div -->
      </form>
      <?php 
    } 
    ?>
  </div> <!-- end col -->
</div> <!-- end content -->
<script>
$(document).ready(function(){

  var action = '<?php echo $action; ?>';
  var pID = '<?php echo (isset($pID) ? $pID : 0); ?>';

  // fade any error messages
  $('.fade-error').delay(6000).fadeOut('slow');

  //  instantiate checkbox switches
  var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
  elems.forEach(function(html) {
    var switchery = new Switchery(html, { size: 'small', 
                                          color: '#ff4044',
                                          secondaryColor: '#a8acb1' });
  });  

  if (action == 'new_product') {
    if (pID > 0) {
      // products status alert 
      var status = '<?php echo ((isset($pInfo->products_status) && $pInfo->products_status == '1') ? 1 : 0); ?>';
      if (status != '1') $('#alert-inactive').show();

      // products out of stock alert
      var checkStock = '<?php echo ((STOCK_CHECK == 'true') ? 1 : 0); ?>';
      var outOfStock = '<?php echo ((isset($pInfo->products_quantity) && $pInfo->products_quantity <= 0) ? 1 : 0); ?>';    
      if (checkStock == '1' && outOfStock == '1') $('#alert-stock').show();
    }

    // instantiate datepicker
    $('#products_date_available').datepicker();

    // image delete/unlink switches
    var deleteCheckbox1 = document.querySelector('.js-delete-1');
    var unlinkCheckbox1 = document.querySelector('.js-unlink-1');
    var deleteCheckbox2 = document.querySelector('.js-delete-2');
    var unlinkCheckbox2 = document.querySelector('.js-unlink-2');
    var deleteCheckbox3 = document.querySelector('.js-delete-3');
    var unlinkCheckbox3 = document.querySelector('.js-unlink-3');

    if (deleteCheckbox1) {
      deleteCheckbox1.onchange = function() {
          if (deleteCheckbox1.checked) {
            if (unlinkCheckbox1.checked) $('.js-unlink-1').click();
          }
      }
    }
    if (unlinkCheckbox1) {
      unlinkCheckbox1.onchange = function() {
          if (unlinkCheckbox1.checked) {
            if (deleteCheckbox1.checked) $('.js-delete-1').click();
          }
      }
    }
    if (deleteCheckbox2) {
      deleteCheckbox2.onchange = function() {
          if (deleteCheckbox2.checked) {
            if (unlinkCheckbox2.checked) $('.js-unlink-2').click();
          }
      }
    }
    if (unlinkCheckbox2) {
      unlinkCheckbox2.onchange = function() {
          if (unlinkCheckbox2.checked) {
            if (deleteCheckbox2.checked) $('.js-delete-2').click();
          }
      }
    }
    if (deleteCheckbox3) {
      deleteCheckbox3.onchange = function() {
          if (deleteCheckbox3.checked) {
            if (unlinkCheckbox3.checked) $('.js-unlink-3').click();
          }
      }
    }
    if (unlinkCheckbox3) {
      unlinkCheckbox3.onchange = function() {
          if (unlinkCheckbox3.checked) {
            if (deleteCheckbox3.checked) $('.js-delete-3').click();
          }
      }
    }
    // if product edit and taxable update net price
    var taxclassid = '<?php echo ((isset($pInfo) && $pInfo->products_tax_class_id > 0) ? $pInfo->products_tax_class_id : 0); ?>';
    var pedit = '<?php echo (isset($_GET['action']) && $_GET['action'] == 'new_product') ? 1 : 0; ?>'
    if (pedit == 1 && taxclassid > 0) updateGross();
  } else if (action == 'new_category' || action == 'edit_category') {

    // image delete/unlink switches
    var deleteCheckbox = document.querySelector('.js-delete');
    var unlinkCheckbox = document.querySelector('.js-unlink');

    if (deleteCheckbox) {
      deleteCheckbox.onchange = function() {
          if (deleteCheckbox.checked) {
            if (unlinkCheckbox.checked) $('.js-unlink').click();
          }
      }
    }
    if (unlinkCheckbox) {
      unlinkCheckbox.onchange = function() {
          if (unlinkCheckbox.checked) {
            if (deleteCheckbox.checked) $('.js-delete').click();
          }
      }
    }    
  } else {
    // copy attributes switches
    var attrCheckbox0 = document.querySelector('.js-attr-check-0');
    var attrCheckbox1 = document.querySelector('.js-attr-check-1');
    var attrCheckbox2 = document.querySelector('.js-attr-check-2');

    if (attrCheckbox0) {
      attrCheckbox0.onchange = function() {
          if (attrCheckbox0.checked) {
            if (attrCheckbox1.checked) $('.js-attr-check-1').click();
            if (attrCheckbox2.checked) $('.js-attr-check-2').click();
          }
          if (attrCheckbox0.checked == false && attrCheckbox1.checked == false && attrCheckbox2.checked == false) $('.js-attr-check-2').click();
      }
    }    

    if (attrCheckbox1) {
      attrCheckbox1.onchange = function() {
          if (attrCheckbox1.checked) {
            if (attrCheckbox0.checked) $('.js-attr-check-0').click();
            if (attrCheckbox2.checked) $('.js-attr-check-2').click();
          }
          if (attrCheckbox0.checked == false && attrCheckbox1.checked == false && attrCheckbox2.checked == false) $('.js-attr-check-0').click();
      }
    }
    if (attrCheckbox2) {
      attrCheckbox2.onchange = function() {
          if (attrCheckbox2.checked) {
            if (attrCheckbox0.checked) $('.js-attr-check-0').click();
            if (attrCheckbox1.checked) $('.js-attr-check-1').click();
          }
          if (attrCheckbox0.checked == false && attrCheckbox1.checked == false && attrCheckbox2.checked == false) $('.js-attr-check-1').click();
      }
    }
  }

}); 

// category tree sample for b2b upsell
var handleJstreeCheckable = function() {
  $('#jstree-checkable').jstree({
      'plugins': ["wholerow", "checkbox", "types"],
      'core': {
          "themes": {
              "responsive": false
          },    
          'data': [{
              "text": "All Groups",
              "state" :{"opened":true},
              "children": [{
                  "text": "Public Groups",
                  "state": { "selected": true, },
                   "children": [{
                  "text": "Guests",
                  }, {                    
                  "text": "Logged in Customers",
                  "state": { "selected": true, },
              }
              ]},{ 
                  
                  "text": "Custom Groups",
                  "state": {"opened":true, },
                  "icon": "fa fa-user fa-lg text-inverse",
                  "children": [{
                  "text": "Discount Club",
                  }, {                    
                  "text": "Primium Club"
             
          }, 
      ]},
      ]}
      ]},
      "types": {
          "default": {
              "icon": "fa fa-user text-primary fa-lg"
          },
          "file": {
              "icon": "fa fa-user text-success fa-lg"
          }
      }
  });
};
handleJstreeCheckable();

function updateProduct(mode) {
  var action = '<?php echo str_replace('&amp;', '&', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($pID) ? '&pID=' . $pID : '') . '&action=' . ((!empty($pID)) ? 'update_product' : 'insert_product'))); ?>';
  // set the save mode in hidden form input
  $('<input />').attr('type', 'hidden')
      .attr('name', "mode")
      .attr('value', mode)
      .appendTo('#new_product');

  $('#new_product').attr('action', action).submit();
}  

function updateCategory(mode) {
  var action = '<?php echo str_replace('&amp;', '&', tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($cID) ? '&cID=' . $cID : '') . '&action=' . ((!empty($cID)) ? 'update_category' : 'insert_category'))); ?>';
  // set the save mode in hidden form input
  $('<input />').attr('type', 'hidden')
      .attr('name', "mode")
      .attr('value', mode)
      .appendTo('#new_category');

  $('#new_category').attr('action', action).submit();
}

function copyProductAttributes() {
  showModalLoader();
  var action = '<?php echo str_replace('&amp;', '&', tep_href_link(FILENAME_CATEGORIES, 'action=create_copy_product_attributes&cPath=' . $cPath . '&pID=' . $pInfo->products_id)); ?>';
  $('#copy_attributes').attr('action', action).submit();
}

function copyCategoryProductAttributes() {
  showModalLoader();
  var action = '<?php echo str_replace('&amp;', '&', tep_href_link(FILENAME_CATEGORIES, 'action=create_copy_product_attributes_categories&cPath=' . $cPath . '&cID=' . $cID . '&make_copy_from_products_id=' . $pInfo->products_id)); ?>';
  $('#copy_category_attributes').attr('action', action).submit();
}

function showModalLoader() {
  $('.btn-copy').addClass('disabled');
  $('.modal-body').attr('style', 'opacity:0.4');
  $('.modal-loader').show();
}

// temp fix for upsell popover KNOWN ISSUE: bootstrap 4 beta.3
$(function () {
  var el = $('[data-toggle="popover"]');
  el.on('click', function(e){
    var el = $(this);
    setTimeout(function(){
      el.popover('show');
    }, 200); // Must occur after document click event below.
  })
  .on('shown.bs.popover', function(){
    $(document).on('click.popover', function() {
      el.popover('hide'); // Hides all 
    });
  })
  .on('hide.bs.popover', function(){
    $(document).off('click.popover');
  }); 
});

// sticky menu bar
$(function () {
  var y = 60;
  $(window).on('scroll', function () {
    if (y <= $(window).scrollTop()) {
      // if so, add the fixed class
      $('#button-bar').addClass('button-bar-fixed');
    } else {
      // otherwise remove it
      $('#button-bar').removeClass('button-bar-fixed');
    }
  })
});

// copy to clipboard functions
/* categories */
$("#cat-name-ctc-list li a").click(function(){
  var id = $(this).attr('data-lang-id');
  var lang = $(this).attr('data-lang-name');  
  var target = $("#categories_name_" + id).val();
  copy(target);
  $('.notify-container-name').fadeIn().delay(1000).fadeOut();
});

$("#cat-desc-ctc-list li a").click(function(){
  var id = $(this).attr('data-lang-id');
  var lang = $(this).attr('data-lang-name');  
  var target = $("#categories_description_" + id).val();
  copy(target);
  $('.notify-container-desc').fadeIn().delay(1000).fadeOut();
});

$("#cat-head-title-ctc-list li a").click(function(){
  var id = $(this).attr('data-lang-id');
  var lang = $(this).attr('data-lang-name');  
  var target = $("#categories_heading_title_" + id).val();
  copy(target);
  $('.notify-container-head-title').fadeIn().delay(1000).fadeOut();
});

$("#cat-meta-title-ctc-list li a").click(function(){
  var id = $(this).attr('data-lang-id');
  var lang = $(this).attr('data-lang-name');  
  var target = $("#categories_head_title_tag_" + id).val();
  copy(target);
  $('.notify-container-meta-title').fadeIn().delay(1000).fadeOut();
});

$("#cat-meta-keywords-ctc-list li a").click(function(){
  var id = $(this).attr('data-lang-id');
  var lang = $(this).attr('data-lang-name');  
  var target = $("#categories_head_keywords_tag_" + id).val();
  copy(target);
  $('.notify-container-meta-keywords').fadeIn().delay(1000).fadeOut();
});

$("#cat-meta-desc-ctc-list li a").click(function(){
  var id = $(this).attr('data-lang-id');
  var lang = $(this).attr('data-lang-name');  
  var target = $("#categories_head_desc_tag_" + id).val();
  copy(target);
  $('.notify-container-meta-desc').fadeIn().delay(1000).fadeOut();
});

/* products */
$("#name-ctc-list li a").click(function(){
  var id = $(this).attr('data-lang-id');
  var lang = $(this).attr('data-lang-name');
  var target = $("#products_name_" + id).val();
  copy(target);
  $('.notify-container-name').fadeIn().delay(1000).fadeOut();
});

$("#desc-ctc-list li a").click(function(){
  var id = $(this).attr('data-lang-id');
  var lang = $(this).attr('data-lang-name');  
  var target = $("#products_description_" + id).val();
  copy(target);
  $('.notify-container-desc').fadeIn().delay(1000).fadeOut();
});

$("#meta-title-ctc-list li a").click(function(){
  var id = $(this).attr('data-lang-id');
  var lang = $(this).attr('data-lang-name');  
  var target = $("#products_head_title_tag_" + id).val();
  copy(target);
  $('.notify-container-meta-title').fadeIn().delay(1000).fadeOut();
});

$("#meta-keywords-ctc-list li a").click(function(){
  var id = $(this).attr('data-lang-id');
  var lang = $(this).attr('data-lang-name');  
  var target = $("#products_head_keywords_tag_" + id).val();
  copy(target);
  $('.notify-container-meta-keywords').fadeIn().delay(1000).fadeOut();
});

$("#meta-desc-ctc-list li a").click(function(){
  var id = $(this).attr('data-lang-id');
  var lang = $(this).attr('data-lang-name');  
  var target = $("#products_head_desc_tag_" + id).val();
  copy(target);
  $('.notify-container-meta-desc').fadeIn().delay(1000).fadeOut();
});

function copy(text){
  var inp =document.createElement('input');
  document.body.appendChild(inp)
  inp.value =text
  inp.select();
  document.execCommand('copy',false);
  inp.remove();
}

function setAttrText(sw) {
  if (sw == 'link') {
    $('#copy_attr').hide();
  } else {
    $('#copy_attr').show();
  }
}

function goToUrl(lang, mode) {
  if (mode =='products') {
    var url = ($('#products_url_' + lang).val()).replace('https://', '').replace('http://', '');
  } else {
    var url = ($('#categories_url_' + lang).val()).replace('https://', '').replace('http://', '');
  }

  if (url == '' || url == undefined) {
    swal("Oh Crap!", "Product URL is Empty!", "error");
    return false;
  }
  if (url.indexOf('.') === -1) {
    swal("Awe Geez!", "The domain is invalid!", "error");
    return false;    
  }

  window.open('http://' + url);
}

var tax_rates = new Array();
<?php
    for ($i=0, $n=sizeof($tax_class_array); $i<$n; $i++) {
      if ($tax_class_array[$i]['id'] > 0) {
        echo 'tax_rates["' . $tax_class_array[$i]['id'] . '"] = ' . tep_get_tax_rate_value($tax_class_array[$i]['id']) . ';' . "\n";
      }
    }
?>

function updateNet() {
  var taxRate = getTaxRate();
  var netValue = $('#products_price_gross').val();

  if (taxRate > 0) {
    netValue = netValue / ((taxRate / 100) + 1);
  }

  $('#products_price').val(doRound(netValue, 2).toFixed(2));
}

function updateGross() {
  var taxRate = getTaxRate();
  var grossValue = $('#products_price').val();

  if (taxRate > 0) {
    $('.price-with-tax').show();
    grossValue = grossValue * ((taxRate / 100) + 1);
  } else {
    $('.price-with-tax').hide();
  }

  $('#products_price_gross').val(doRound(grossValue, 2).toFixed(2));
}

function doRound(x, places) {
  return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
}

function getTaxRate() {
  var tax_rates = new Array();
  <?php
      for ($i=0, $n=sizeof($tax_class_array); $i<$n; $i++) {
        if ($tax_class_array[$i]['id'] > 0) {
          echo 'tax_rates["' . $tax_class_array[$i]['id'] . '"] = ' . tep_get_tax_rate_value($tax_class_array[$i]['id']) . ';' . "\n";
        }
      }
  ?>

  var selected_value = $('#products_tax_class_id').val();
  var parameterVal = document.forms["new_product"].products_tax_class_id[selected_value].value;

  if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
    return tax_rates[parameterVal];
  } else {
    return 0;
  }
}

</script>
<?php 
include(DIR_WS_INCLUDES . 'html_bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>