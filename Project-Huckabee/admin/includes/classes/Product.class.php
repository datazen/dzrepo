<?php
class Product {

  public static function getProductParameters() {
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

    return $parameters;
  }

  public static function getImageUploadDirOptions() {

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

    return $file_dir;
  }

  public static function copyProductAttributes($products_id_from, $products_id_to) {
    global $languages_id, $messageStack;

    $copy_attributes_delete_first = (isset($_POST['copy_attributes_delete_first']) && $_POST['copy_attributes_delete_first'] == 'on') ? '1' : '0';
    $copy_attributes_duplicates_overwrite = (isset($_POST['copy_attributes_duplicates_overwrite']) && $_POST['copy_attributes_duplicates_overwrite'] == 'on') ? '1' : '0';
    $copy_attributes_duplicates_skipped = (isset($_POST['copy_attributes_duplicates_skipped']) && $_POST['copy_attributes_duplicates_skipped'] == 'on') ? '1' : '0';
    $copy_attributes_include_downloads = (isset($_POST['copy_attributes_include_downloads']) && $_POST['copy_attributes_include_downloads'] == 'on') ? '1' : '0';

    $products_copy_to_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_id='" . $products_id_to . "'");
    $products_copy_to_check_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where products_id='" . $products_id_to . "'");
    $products_copy_from_query = tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . $products_id_from . "'");
    $products_copy_from_check_query = tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . $products_id_from . "'");

    // Check for errors in copy request
    $error = false;
    if ( (!$products_copy_from_check = tep_db_fetch_array($products_copy_from_check_query)) ||
         (!$products_copy_to_check = tep_db_fetch_array($products_copy_to_check_query)) ||
         ($products_id_to == $products_id_from) ) {
    
      if ($products_id_to == $products_id_from) {
        // same products_id
        $error = true;
        $messageStack->add_session('categories', sprintf(WARNING_CANNOT_COPY_TO_SAME_PRODUCT, Product::getProductName($products_id_from, $languages_id), Product::getProductName($products_id_to, $languages_id)), 'warning');         
      } else {
        if (!$products_copy_from_check) {
          // no attributes found to copy
          $error = true;
          $messageStack->add_session('categories', sprintf(WARNING_NO_ATTRIBUTES_FOUND, Product::getProductName($products_id_from, $languages_id), Product::getProductName($products_id_to, $languages_id)), 'error');         
        } else {
          // invalid products_id
          $error = true;
          $messageStack->add_session('categories', sprintf(WARNING_TARGET_DOES_NOT_EXIST, Product::getProductName($products_id_from, $languages_id), Product::getProductName($products_id_to, $languages_id)), 'error');         
        }
      }

      return $error;

    } else {

      if (false) { // Used for testing
      echo $products_id_from . 'x' . $products_id_to . '<br>';
      echo $copy_attributes_delete_first;
      echo $copy_attributes_duplicates_skipped;
      echo $copy_attributes_duplicates_overwrite;
      echo $copy_attributes_include_downloads;
      echo $copy_attributes_include_filename . '<br>';
      } // true for testing

      if ($copy_attributes_delete_first == '1') {
        // delete all attributes and downloads first
        $products_delete_from_query= tep_db_query("select pa.products_id, pad.products_attributes_id from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad  where pa.products_id='" . $products_id_to . "' and pad.products_attributes_id= pa.products_attributes_id");
        while ( $products_delete_from=tep_db_fetch_array($products_delete_from_query) ) {
          tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id = '" . $products_delete_from['products_attributes_id'] . "'");
        }
        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_copy_to_check['products_id'] . "'");
      }
      if (!(isset($rows))) {
        $rows = 0;
      }

      while ($products_copy_from = tep_db_fetch_array($products_copy_from_query)) {
        $rows++;
        // This must match the structure of your products_attributes table
        // Current Field Order: products_attributes_id, options_values_price, price_prefix, products_options_sort_order, product_attributes_one_time, products_attributes_weight, products_attributes_weight_prefix, products_attributes_units, products_attributes_units_price
        // First test for existing attribute already being there
        $check_attribute_query= tep_db_query("select products_id, products_attributes_id, options_id, options_values_id from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . $products_id_to . "' and options_id='" . $products_copy_from['options_id'] . "' and options_values_id ='" . $products_copy_from['options_values_id'] . "'");
        $check_attribute= tep_db_fetch_array($check_attribute_query);
        // Check if there is a download with this attribute
        $check_attributes_download_query= tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id ='" . $products_copy_from['products_attributes_id'] . "'");
        $check_attributes_download=tep_db_fetch_array($check_attributes_download_query);

        // Process Attribute
        $skip_it = false;
        switch (true) {
          case ($check_attribute and $copy_attributes_duplicates_skipped):
            // skip duplicate attributes
            //echo 'DUPLICATE ' . ' Option ' . $products_copy_from['options_id'] . ' Value ' . $products_copy_from['options_values_id'] . ' Price ' . $products_copy_from['options_values_price'] . ' SKIPPED<br>';
            $skip_it=true;
            break;
          case (!$copy_attributes_include_downloads and $check_attributes_download['products_attributes_id']):
            // skip download attributes
            //echo 'Download - ' . ' Attribute ID ' . $check_attributes_download['products_attributes_id'] . ' do not copy it<br>';
            $skip_it=true;
            break;
          default:
            //echo '$check_attributes_download ' . $check_attributes_download['products_attributes_id'] . '<br>';
            if ($check_attributes_download['products_attributes_id']) {
              if (DOWNLOAD_ENABLED=='false' or !$copy_attributes_include_downloads) {
                // do not copy this download
                //echo 'This is a download not to be copied <br>';
                $skip_it=true;
              } else {
                // copy this download
                //echo 'This is a download to be copied <br>';
              }
            }

            // skip anything when $skip_it
            if (!$skip_it) {
              if ($check_attribute['products_id']) {
                // Duplicate attribute - update it
                //echo 'Duplicate - Update ' . $check_attribute['products_id'] . ' Option ' . $check_attribute['options_id'] . ' Value ' . $check_attribute['options_values_id'] . ' Price ' . $products_copy_from['options_values_price'] . '<br>';
                // tep_db_query("update set " . TABLE_PRODUCTS_ATTRIBUTES . ' ' . options_id=$products_copy_from['options_id'] . "', '" . options_values_id=$products_copy_from['options_values_id'] . "', '" . options_values_price=$products_copy_from['options_values_price'] . "', '" . price_prefix=$products_copy_from['price_prefix'] . "', '" . products_options_sort_order=$products_copy_from['products_options_sort_order'] . "', '" . product_attributes_one_time=$products_copy_from['product_attributes_one_time'] . "', '" . products_attributes_weight=$products_copy_from['products_attributes_weight'] . "', '" . products_attributes_weight_prefix=$products_copy_from['products_attributes_weight_prefix'] . "', '" . products_attributes_units=$products_copy_from['products_attributes_units'] . "', '" . products_attributes_units_price=$products_copy_from['products_attributes_units_price'] . " where products_id='" . $products_id_to . "' and products_attributes_id='" . $check_attribute['products_attributes_id'] . "'");

                $sql_data_array = array(
                  'options_id' => tep_db_prepare_input($products_copy_from['options_id']),
                  'options_values_id' => tep_db_prepare_input($products_copy_from['options_values_id']),
                  'options_values_price' => tep_db_prepare_input($products_copy_from['options_values_price']),
                  'price_prefix' => tep_db_prepare_input($products_copy_from['price_prefix']),
                  'products_options_sort_order' => tep_db_prepare_input($products_copy_from['products_options_sort_order']),
                );

                $cur_attributes_id = $check_attribute['products_attributes_id'];
                tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES, $sql_data_array, 'update', 'products_id = \'' . tep_db_input($products_id_to) . '\' and products_attributes_id=\'' . tep_db_input($cur_attributes_id) . '\'');
              } else {
                // New attribute - insert it
                //echo 'New - Insert ' . 'Option ' . $products_copy_from['options_id'] . ' Value ' . $products_copy_from['options_values_id']  . ' Price ' . $products_copy_from['options_values_price'] . '<br>';
                tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values ('', '" . $products_id_to . "', '" . $products_copy_from['options_id'] . "', '" . $products_copy_from['options_values_id'] . "', '" . $products_copy_from['options_values_price'] . "', '" . $products_copy_from['price_prefix'] . "', '" . $products_copy_from['products_options_sort_order'] . "') ");
              }

              // Manage download attribtues
              if (DOWNLOAD_ENABLED == 'true') {
                if ($check_attributes_download and $copy_attributes_include_downloads) {
                  // copy download attributes
                  //echo 'Download - ' . ' Attribute ID ' . $check_attributes_download['products_attributes_id'] . ' ' . $check_attributes_download['products_attributes_filename'] . ' copy it<br>';
                  $new_attribute_query= tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . $products_id_to . "' and options_id='" . $products_copy_from['options_id'] . "' and options_values_id ='" . $products_copy_from['options_values_id'] . "'");
                  $new_attribute= tep_db_fetch_array($new_attribute_query);

                  $sql_data_array = array(
                    'products_attributes_id' => tep_db_prepare_input($new_attribute['products_attributes_id']),
                    'products_attributes_filename' => tep_db_prepare_input($check_attributes_download['products_attributes_filename']),
                    'products_attributes_maxdays' => tep_db_prepare_input($check_attributes_download['products_attributes_maxdays']),
                    'products_attributes_maxcount' => tep_db_prepare_input($check_attributes_download['products_attributes_maxcount'])
                  );

                  $cur_attributes_id = $check_attribute['products_attributes_id'];
                  tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD, $sql_data_array);
                }
              }
            } // $skip_it
        } // end of switch
      } // end of product attributes while loop

      $messageStack->add_session('categories', sprintf(SUCCESS_ATTRIBUTES_COPIED, Product::getProductName($products_id_from, $languages_id), Product::getProductName($products_id_to, $languages_id)), 'success');         
    } // end of no attributes or other errors
  } 

  public static function hasProductAttributes($products_id) {
    global $languages_id;

    $products_attributes = tep_db_query("select poptt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt,  " . TABLE_PRODUCTS_OPTIONS_TEXT  . " poptt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $products_id . "' and patrib.options_id = popt.products_options_id and poptt.language_id = '" . $languages_id . "'");
    
    $result = (tep_db_num_rows($products_attributes)) ? true : false;

    return $result;
  }

  public static function getProductName($products_id, $language_id) {
    $product_query = tep_db_query("SELECT products_name FROM " . TABLE_PRODUCTS_DESCRIPTION . " WHERE products_id = '" . $products_id . "' AND language_id = '" . $language_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_name'];
  }
}
?>