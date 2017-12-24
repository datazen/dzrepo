<?php
/*
  $Id: general.php,v 1.1.1.1 2004/03/04 23:40:50 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// This function is used to a JIT load of classes
function creClassLoader($className) {
  if (file_exists(DIR_FS_CLASSES . $className . '.php')) {
    include DIR_FS_CLASSES . $className . '.php';
  }
}
spl_autoload_register('creClassLoader');  // register it

// this function will record all PHP messages to prevent
// potential exposure of seecure details
function _exception_handler($severity, $message, $filepath, $line) {
  // We don't bother with "strict" notices since they will fill up
  // the log file with information that isn't normally very
  // helpful.  For example, if you are running PHP 5 and you
  // use version 4 style class functions (without prefixes
  // like "public", "private", etc.) you'll get notices telling
  // you that these have been deprecated.
  if ($severity == E_STRICT) return;
  
  $msg = '['.date("D M j G:i:s Y").'] [IP:'.$_SERVER['REMOTE_ADDR'].'] [URI:'.$_SERVER['REQUEST_URI'].'] Severity: '.$severity.' --> '.$message. ' '.$filepath.' '.$line;
  
  $fp = fopen(ERROR_MESSAGE_LOG, 'a');
  flock($fp, LOCK_EX);    
  fwrite($fp, $msg . "\r\n");
  fwrite($fp, "\r\n");
  flock($fp, LOCK_UN);
  fclose($fp);
}

// this function is used to log as part of the PHP error log
// any additional messages that should not be presented on the 
// web page, but are needed for debugging.
function _error_handler($message) {
  // capture the track back report
  // the actual passed message is ignored since
  // it will be included as part of the track back
  ob_start();
  debug_print_backtrace();
  $traceback = ob_get_clean();
  $msg = '['.date("D M j G:i:s Y").'] [IP:'.$_SERVER['REMOTE_ADDR'].'] [URI:'.$_SERVER['REQUEST_URI'].'] -->';
  
  $fp = @fopen(ERROR_MESSAGE_LOG, 'a');
  flock($fp, LOCK_EX);    
  fwrite($fp, $msg . "\r\n");
  fwrite($fp, $traceback . "\r\n");
  fwrite($fp, "\r\n");
  flock($fp, LOCK_UN);
  fclose($fp);
}

// Stop from parsing any further PHP code
  function tep_exit() {
   tep_session_close();
   exit();
  }

////
// Redirect to another page or site
  function tep_redirect($url) {
    if ( (strstr($url, "\n") != false) || (strstr($url, "\r") != false) ) { 
      tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false));
    }

    if ( (ENABLE_SSL == 'true') && (getenv('HTTPS') == 'on') ) { // We are loading an SSL page
      if (substr($url, 0, strlen(HTTP_SERVER . DIR_WS_HTTP_CATALOG)) == HTTP_SERVER . DIR_WS_HTTP_CATALOG) { // NONSSL url
        $url = HTTPS_SERVER . DIR_WS_HTTPS_CATALOG . substr($url, strlen(HTTP_SERVER . DIR_WS_HTTP_CATALOG)); // Change it to SSL
      }
    }
    
    $url =  str_replace("&amp;", "&", $url);
    
    header('Location: ' . $url);
    
    tep_exit();
  }

////
// Parse the data used in the html tags to ensure the tags will not break
  function tep_parse_input_field_data($data, $parse) {
    return strtr(trim($data), $parse);
  }

  function tep_output_string($string, $translate = false, $protected = false) {
    $string = str_replace('\\', '', $string);
    if ($protected == true) {
      $new_string = str_replace('&amp;', '&', $string);  // so corruption will not occur
      $new_string = str_replace('&#163;', '�', $new_string);  // so corruption will not occur
      return htmlspecialchars($new_string);
    } else {
      if ($translate == false) {
        return tep_parse_input_field_data($string, array('"' => '&quot;'));
      } else {
        return tep_parse_input_field_data($string, $translate);
      }
    }
  }

  function tep_output_string_protected($string) {
    return tep_output_string($string, false, true);
  }

  function tep_sanitize_string($string) {
    $string = preg_replace('/ +/', ' ', trim($string));

    return preg_replace("/[<>]/", '_', $string);
  }

////
// Return a random row from a database query
  function tep_random_select($query) {
    $random_product = '';
    $random_query = tep_db_query($query);
    $num_rows = tep_db_num_rows($random_query);
    if ($num_rows > 0) {
      $random_row = tep_rand(0, ($num_rows - 1));
      tep_db_data_seek($random_query, $random_row);
      $random_product = tep_db_fetch_array($random_query);
    }

    return $random_product;
  }

////
// Return a product's name
// TABLES: products
  function tep_get_products_name($product_id, $language = '') {
    global $languages_id;

    if (empty($language)) $language = $languages_id;

    $product_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language . "'");
    $product = tep_db_fetch_array($product_query);
    $product['products_name'] = tep_db_decoder($product['products_name']);
    $product['products_name'] = tep_db_output($product['products_name']);

    return $product['products_name'];
  }
//

// Return a product's model
// TABLES: products
  function tep_get_products_model($product_id) {
    global $languages_id;

    $product_query = tep_db_query("select products_model from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_model'];
  }


// Return a product's image
// TABLES: products
  function tep_get_products_image($product_id) {
    global $languages_id;

    $product_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_image'];
  }

//gets manufacurs name for a manufacture
 function tep_get_manufacturers_name($manufacturers_id) {

    $manufactures_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$manufacturers_id . "'");
    $manufactures = tep_db_fetch_array($manufactures_query);

    return $manufactures['manufacturers_name'];
  }

////
// Return a product's stock
// TABLES: products
  function tep_get_products_stock($products_id) {
    $products_id = tep_get_prid($products_id);
    $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
    $stock_values = tep_db_fetch_array($stock_query);

    return $stock_values['products_quantity'];
  }

////
// Check if the required stock is available
// If insufficent stock is available return an out of stock message
  function tep_check_stock($products_id, $products_quantity) {
    $stock_left = tep_get_products_stock($products_id) - $products_quantity;
    $out_of_stock = '';

    if ($stock_left < 0) {
      $out_of_stock = '<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
    }

    return $out_of_stock;
  }

////
// Break a word in a string if it is longer than a specified length ($len)
  function tep_break_string($string, $len, $break_char = '-') {
    $l = 0;
    $output = '';
    for ($i=0, $n=strlen($string); $i<$n; $i++) {
      $char = substr($string, $i, 1);
      if ($char != ' ') {
        $l++;
      } else {
        $l = 0;
      }
      if ($l > $len) {
        $l = 1;
        $output .= $break_char;
      }
      $output .= $char;
    }

    return $output;
  }

////
// Return all HTTP GET variables, except those passed as a parameter
  function tep_get_all_get_params($exclude_array = '') {
    if (!is_array($exclude_array)) $exclude_array = array();

    $get_url = '';
    if (is_array($_GET) && (sizeof($_GET) > 0)) {
      reset($_GET);
      while (list($key, $value) = each($_GET)) {
        if ( (strlen($value) > 0) && ($key != tep_session_name()) && ($key != 'error') && (!in_array($key, $exclude_array)) && ($key != 'x') && ($key != 'y') ) {
          $get_url .= $key . '=' . rawurlencode(stripslashes($value)) . '&amp;';
        }
      }
    }

    return $get_url;
  }

////
// Returns an array with countries
// TABLES: countries
  function tep_get_countries($countries_id = '', $with_iso_codes = false) {
    $countries_array = array();
    if (tep_not_null($countries_id)) {
      if ($with_iso_codes == true) {
        $countries = tep_db_query("select countries_name, countries_iso_code_2, countries_iso_code_3 from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "' order by countries_name");
        $countries_values = tep_db_fetch_array($countries);
        $countries_array = array('countries_name' => $countries_values['countries_name'],
                                 'countries_iso_code_2' => $countries_values['countries_iso_code_2'],
                                 'countries_iso_code_3' => $countries_values['countries_iso_code_3']);
      } else {
        $countries = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "'");
        $countries_values = tep_db_fetch_array($countries);
        $countries_array = array('countries_name' => $countries_values['countries_name']);
      }
    } else {
      $countries = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
      while ($countries_values = tep_db_fetch_array($countries)) {
        $countries_array[] = array('countries_id' => $countries_values['countries_id'],
                                   'countries_name' => $countries_values['countries_name']);
      }
    }

    return $countries_array;
  }

////
// Alias function to tep_get_countries, which also returns the countries iso codes
  function tep_get_countries_with_iso_codes($countries_id) {
    return tep_get_countries($countries_id, true);
  }

////
// Generate a path to categories
  function tep_get_path($current_category_id = '') {
    global $cPath_array;

    if (tep_not_null($current_category_id)) {
      $cp_size = sizeof($cPath_array);
      if ($cp_size == 0) {
        $cPath_new = $current_category_id;
      } else {
        $cPath_new = '';
        $last_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$cPath_array[($cp_size-1)] . "'");
        $last_category = tep_db_fetch_array($last_category_query);

        $current_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
        $current_category = tep_db_fetch_array($current_category_query);

        if ($last_category['parent_id'] == $current_category['parent_id']) {
          for ($i=0; $i<($cp_size-1); $i++) {
            $cPath_new .= '_' . $cPath_array[$i];
          }
        } else {
          for ($i=0; $i<$cp_size; $i++) {
            $cPath_new .= '_' . $cPath_array[$i];
          }
        }
        $cPath_new .= '_' . $current_category_id;

        if (substr($cPath_new, 0, 1) == '_') {
          $cPath_new = substr($cPath_new, 1);
        }
      }
    } else {
      $cPath_new = implode('_', $cPath_array);
    }

    return 'cPath=' . $cPath_new;
  }

////
// Returns the clients browser
  function tep_browser_detect($component) {
    global $HTTP_USER_AGENT;

    return stristr($HTTP_USER_AGENT, $component);
  }

////
// Alias function to tep_get_countries()
  function tep_get_country_name($country_id) {
    $country_array = tep_get_countries($country_id);

    return $country_array['countries_name'];
  }

////
// Returns the zone (State/Province) name
// TABLES: zones
  function tep_get_zone_name($country_id, $zone_id, $default_zone) {
    $zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' and zone_id = '" . (int)$zone_id . "'");
    if (tep_db_num_rows($zone_query)) {
      $zone = tep_db_fetch_array($zone_query);
      return $zone['zone_name'];
    } else {
      return $default_zone;
    }
  }

////
// Returns the zone (State/Province) code
// TABLES: zones
  function tep_get_zone_code($country_id, $zone_id, $default_zone) {
    $zone_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country_id . "' and zone_id = '" . (int)$zone_id . "'");
    if (tep_db_num_rows($zone_query)) {
      $zone = tep_db_fetch_array($zone_query);
      return $zone['zone_code'];
    } else {
      return $default_zone;
    }
  }

////
// Wrapper function for round()
  function tep_round($number, $precision) {
    if (strpos($number, '.') && (strlen(substr($number, strpos($number, '.')+1)) > $precision)) {
      $number = substr($number, 0, strpos($number, '.') + 1 + $precision + 1);

      if (substr($number, -1) >= 5) {
        if ($precision > 1) {
          $number = substr($number, 0, -1) + ('0.' . str_repeat(0, $precision-1) . '1');
        } elseif ($precision == 1) {
          $number = substr($number, 0, -1) + 0.1;
        } else {
          $number = substr($number, 0, -1) + 1;
        }
      } else {
        $number = substr($number, 0, -1);
      }
    }

    return $number;
  }

////
// Returns the tax rate for a zone / class
// TABLES: tax_rates, zones_to_geo_zones
  function tep_get_tax_rate($class_id, $country_id = -1, $zone_id = -1) {
    if ( ($country_id == -1) && ($zone_id == -1) ) {
      if ( ! isset($_SESSION['customer_id']) ) {
        $country_id = STORE_COUNTRY;
        $zone_id = STORE_ZONE;
      } else {
        $country_id = $_SESSION['customer_country_id'];
        $zone_id = $_SESSION['customer_zone_id'];
      }
    }

    $tax_query = tep_db_query("select sum(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int)$country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int)$zone_id . "') and tr.tax_class_id = '" . (int)$class_id . "' group by tr.tax_priority");
    if (tep_db_num_rows($tax_query)) {
      $tax_multiplier = 1.0;
      while ($tax = tep_db_fetch_array($tax_query)) {
        $tax_multiplier *= 1.0 + ($tax['tax_rate'] / 100);
      }
      return ($tax_multiplier - 1.0) * 100;
    } else {
      return 0;
    }
  }

////
// Return the tax description for a zone / class
// TABLES: tax_rates;
  function tep_get_tax_description($class_id, $country_id, $zone_id) {
    $tax_query = tep_db_query("select tax_description from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int)$country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int)$zone_id . "') and tr.tax_class_id = '" . (int)$class_id . "' order by tr.tax_priority");
    if (tep_db_num_rows($tax_query)) {
      $tax_description = '';
      while ($tax = tep_db_fetch_array($tax_query)) {
        $tax_description .= $tax['tax_description'] . ' + ';
      }
      $tax_description = substr($tax_description, 0, -3);

      return $tax_description;
    } else {
      return TEXT_UNKNOWN_TAX_RATE;
    }
  }

////
// Add tax to a products price
  function tep_add_tax($price, $tax) {
    global $currencies;

    if ( (DISPLAY_PRICE_WITH_TAX == 'true') && ($tax > 0) ) {
      return tep_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']) + tep_calculate_tax($price, $tax);
    } else {
      return tep_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
    }
  }

// Calculates Tax rounding the result
  function tep_calculate_tax($price, $tax) {
    global $currencies;

    return tep_round($price * $tax / 100, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
  }

////
// Return the number of products in a category
// TABLES: products, products_to_categories, categories
  function tep_count_products_in_category($category_id, $include_inactive = false) {
    $products_count = 0;
    if ($include_inactive == true) {
      $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$category_id . "'");
    } else {
      $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1' and p2c.categories_id = '" . (int)$category_id . "'");
    }
    $products = tep_db_fetch_array($products_query);
    $products_count += $products['total'];

    $child_categories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
    if (tep_db_num_rows($child_categories_query)) {
      while ($child_categories = tep_db_fetch_array($child_categories_query)) {
        $products_count += tep_count_products_in_category($child_categories['categories_id'], $include_inactive);
      }
    }

    return $products_count;
  }

////
// Return true if the category has subcategories
// TABLES: categories
  function tep_has_category_subcategories($category_id) {
    $child_category_query = tep_db_query("select count(*) as count from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$category_id . "'");
    $child_category = tep_db_fetch_array($child_category_query);

    if ($child_category['count'] > 0) {
      return true;
    } else {
      return false;
    }
  }

////
// Returns the address_format_id for the given country
// TABLES: countries;
  function tep_get_address_format_id($country_id) {
    $address_format_query = tep_db_query("select address_format_id as format_id from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$country_id . "'");
    if (tep_db_num_rows($address_format_query)) {
      $address_format = tep_db_fetch_array($address_format_query);
      return $address_format['format_id'];
    } else {
      return '1';
    }
  }

////
// Return a formatted address
// TABLES: address_format
  function tep_address_format($address_format_id, $address, $html, $boln, $eoln) {
    $address_format_query = tep_db_query("select address_format as format from " . TABLE_ADDRESS_FORMAT . " where address_format_id = '" . (int)$address_format_id . "'");
    $address_format = tep_db_fetch_array($address_format_query);

    $company = isset($address['company']) ? tep_output_string_protected($address['company']) : '';
    if (isset($address['firstname']) && tep_not_null($address['firstname'])) {
      $firstname = tep_output_string_protected($address['firstname']);
      $lastname = tep_output_string_protected($address['lastname']);
    } elseif (isset($address['name']) && tep_not_null($address['name'])) {
      $firstname = tep_output_string_protected($address['name']);
      $lastname = '';
    } else {
      $firstname = '';
      $lastname = '';
    }
    $street = isset($address['street_address']) ? tep_output_string_protected($address['street_address']) : '';
    $suburb = isset($address['suburb']) ? tep_output_string_protected($address['suburb']) : '';
    $city = isset($address['city']) ? tep_output_string_protected($address['city']) : '';
    $state = isset($address['state']) ? tep_output_string_protected($address['state']) : '';
    if (isset($address['country_id']) && tep_not_null($address['country_id'])) {
      $country = tep_get_country_name($address['country_id']);

      if (isset($address['zone_id']) && tep_not_null($address['zone_id'])) {
        $state = tep_get_zone_code($address['country_id'], $address['zone_id'], $state);
      }
    } elseif (isset($address['country']) && tep_not_null($address['country'])) {
      $country = tep_output_string_protected($address['country']);
    } else {
      $country = '';
    }
    $postcode = isset($address['postcode']) ? tep_output_string_protected($address['postcode']) : 0;
    $zip = $postcode;
    $telephone = isset($address['telephone']) ? tep_output_string_protected($address['telephone']) : '';
    if ($html) {
// HTML Mode
      $HR = '<hr>';
      $hr = '<hr>';
      if ( ($boln == '') && ($eoln == "\n") ) { // Values not specified, use rational defaults
        $CR = '<br>';
        $cr = '<br>';
        $eoln = $cr;
      } else { // Use values supplied
        $CR = $eoln . $boln;
        $cr = $CR;
      }
    } else {
// Text Mode
      $CR = $eoln;
      $cr = $CR;
      $HR = '----------------------------------------';
      $hr = '----------------------------------------';
    }

    $statecomma = '';
    $streets = $street;
    if ($suburb != '') $streets = $street . $cr . $suburb;
    if ($country == '') $country = isset($address['country']) ? tep_output_string_protected($address['country']) : '';
    if ($state != '') $statecomma = $state . ', ';

    $fmt = $address_format['format'];
    eval("\$address = \"$fmt\";");

    if ( (ACCOUNT_COMPANY == 'true') && (tep_not_null($company)) ) {
      $address = $company . $cr . $address;
    }
    
    if (tep_not_null($telephone)) {
      $address .= $cr . $telephone;
    }
    
    return $address;
  }

////
// Return a formatted address
// TABLES: customers, address_book
  function tep_address_label($customers_id, $address_id = 1, $html = false, $boln = '', $eoln = "\n") {
    $address_query = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id, entry_telephone as telephone from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customers_id . "' and address_book_id = '" . (int)$address_id . "'");
    $address = tep_db_fetch_array($address_query);

    $format_id = tep_get_address_format_id($address['country_id']);

    return tep_address_format($format_id, $address, $html, $boln, $eoln);
  }

  function tep_row_number_format($number) {
    if ( ($number < 10) && (substr($number, 0, 1) != '0') ) $number = '0' . $number;

    return $number;
  }

  function tep_get_categories($categories_array = '', $parent_id = '0', $indent = '') {
    global $languages_id;

    if (!is_array($categories_array)) $categories_array = array();

    $categories_query = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where parent_id = '" . (int)$parent_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_array[] = array('id' => $categories['categories_id'],
                                  'text' => $indent . $categories['categories_name']);

      if ($categories['categories_id'] != $parent_id) {
        $categories_array = tep_get_categories($categories_array, $categories['categories_id'], $indent . '&nbsp;&nbsp;');
      }
    }

    return $categories_array;
  }

  function tep_get_manufacturers($manufacturers_array = '') {
    if (!is_array($manufacturers_array)) $manufacturers_array = array();

    $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
    while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
      $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'], 'text' => $manufacturers['manufacturers_name']);
    }

    return $manufacturers_array;
  }

////
// Return all subcategory IDs
// TABLES: categories
  function tep_get_subcategories(&$subcategories_array, $parent_id = 0) {
    $subcategories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$parent_id . "'");
    while ($subcategories = tep_db_fetch_array($subcategories_query)) {
      $subcategories_array[sizeof($subcategories_array)] = $subcategories['categories_id'];
      if ($subcategories['categories_id'] != $parent_id) {
        tep_get_subcategories($subcategories_array, $subcategories['categories_id']);
      }
    }
  }

// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
  function tep_date_long($raw_date) {
    if ( ($raw_date == '0000-00-00 00:00:00') || ($raw_date == '') ) return false;

    $year = (int)substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);

    return strftime(DATE_FORMAT_LONG, mktime($hour,$minute,$second,$month,$day,$year));
  }

////
// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
// NOTE: Includes a workaround for dates before 01/01/1970 that fail on windows servers
  function tep_date_short($raw_date) {
    if ( ($raw_date == '0000-00-00 00:00:00') || empty($raw_date) ) return false;

    $year = substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 5, 2);
    $day = (int)substr($raw_date, 8, 2);
    $hour = (int)substr($raw_date, 11, 2);
    $minute = (int)substr($raw_date, 14, 2);
    $second = (int)substr($raw_date, 17, 2);

    if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
      return date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
    } else {
      return preg_replace('/2037' . '$/', $year, date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, 2037)));
    }
  }

////
// Parse search string into indivual objects
  function tep_parse_search_string($search_str = '', &$objects) {
    $search_str = trim(strtolower($search_str));

// Break up $search_str on whitespace; quoted string will be reconstructed later
    $pieces = preg_split('/[[:space:]]+/', $search_str);
    $objects = array();
    $tmpstring = '';
    $flag = '';

    for ($k=0; $k<count($pieces); $k++) {
      while (substr($pieces[$k], 0, 1) == '(') {
        $objects[] = '(';
        if (strlen($pieces[$k]) > 1) {
          $pieces[$k] = substr($pieces[$k], 1);
        } else {
          $pieces[$k] = '';
        }
      }

      $post_objects = array();

      while (substr($pieces[$k], -1) == ')')  {
        $post_objects[] = ')';
        if (strlen($pieces[$k]) > 1) {
          $pieces[$k] = substr($pieces[$k], 0, -1);
        } else {
          $pieces[$k] = '';
        }
      }

// Check individual words

      if ( (substr($pieces[$k], -1) != '"') && (substr($pieces[$k], 0, 1) != '"') ) {
        $objects[] = trim($pieces[$k]);

        for ($j=0; $j<count($post_objects); $j++) {
          $objects[] = $post_objects[$j];
        }
      } else {
/* This means that the $piece is either the beginning or the end of a string.
   So, we'll slurp up the $pieces and stick them together until we get to the
   end of the string or run out of pieces.
*/

// Add this word to the $tmpstring, starting the $tmpstring
        $tmpstring = trim(preg_replace('/"/', ' ', $pieces[$k]));

// Check for one possible exception to the rule. That there is a single quoted word.
        if (substr($pieces[$k], -1 ) == '"') {
// Turn the flag off for future iterations
          $flag = 'off';

          $objects[] = trim($pieces[$k]);

          for ($j=0; $j<count($post_objects); $j++) {
            $objects[] = $post_objects[$j];
          }

          unset($tmpstring);

// Stop looking for the end of the string and move onto the next word.
          continue;
        }

// Otherwise, turn on the flag to indicate no quotes have been found attached to this word in the string.
        $flag = 'on';

// Move on to the next word
        $k++;

// Keep reading until the end of the string as long as the $flag is on

        while ( ($flag == 'on') && ($k < count($pieces)) ) {
          while (substr($pieces[$k], -1) == ')') {
            $post_objects[] = ')';
            if (strlen($pieces[$k]) > 1) {
              $pieces[$k] = substr($pieces[$k], 0, -1);
            } else {
              $pieces[$k] = '';
            }
          }

// If the word doesn't end in double quotes, append it to the $tmpstring.
          if (substr($pieces[$k], -1) != '"') {
// Tack this word onto the current string entity
            $tmpstring .= ' ' . $pieces[$k];

// Move on to the next word
            $k++;
            continue;
          } else {
/* If the $piece ends in double quotes, strip the double quotes, tack the
   $piece onto the tail of the string, push the $tmpstring onto the $haves,
   kill the $tmpstring, turn the $flag "off", and return.
*/
            $tmpstring .= ' ' . trim(preg_replace('/"/', ' ', $pieces[$k]));

// Push the $tmpstring onto the array of stuff to search for
            $objects[] = trim($tmpstring);

            for ($j=0; $j<count($post_objects); $j++) {
              $objects[] = $post_objects[$j];
            }

            unset($tmpstring);

// Turn off the flag to exit the loop
            $flag = 'off';
          }
        }
      }
    }

// add default logical operators if needed
    $temp = array();
    for($i=0; $i<(count($objects)-1); $i++) {
      $temp[] = $objects[$i];
      if ( ($objects[$i] != 'and') &&
           ($objects[$i] != 'or') &&
           ($objects[$i] != '(') &&
           ($objects[$i+1] != 'and') &&
           ($objects[$i+1] != 'or') &&
           ($objects[$i+1] != ')') ) {
        $temp[] = ADVANCED_SEARCH_DEFAULT_OPERATOR;
      }
    }
    $temp[] = $objects[$i];
    $objects = $temp;

    $keyword_count = 0;
    $operator_count = 0;
    $balance = 0;
    for($i=0; $i<count($objects); $i++) {
      if ($objects[$i] == '(') $balance --;
      if ($objects[$i] == ')') $balance ++;
      if ( ($objects[$i] == 'and') || ($objects[$i] == 'or') ) {
        $operator_count ++;
      } elseif ( ($objects[$i]) && ($objects[$i] != '(') && ($objects[$i] != ')') ) {
        $keyword_count ++;
      }
    }

    if ( ($operator_count < $keyword_count) && ($balance == 0) ) {
      return true;
    } else {
      return false;
    }
  }

////
// Check date
  function tep_checkdate($date_to_check, $format_string, &$date_array) {
    $separator_idx = -1;

    $separators = array('-', ' ', '/', '.');
    $month_abbr = array('jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec');
    $no_of_days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    $format_string = strtolower($format_string);
   // Commended for fixing #4231
   // if (strlen($date_to_check) != strlen($format_string)) {
   //   return false;
   // }

    $size = sizeof($separators);
    for ($i=0; $i<$size; $i++) {
      $pos_separator = strpos($date_to_check, $separators[$i]);
      if ($pos_separator != false) {
        $date_separator_idx = $i;
        break;
      }
    }

    for ($i=0; $i<$size; $i++) {
      $pos_separator = strpos($format_string, $separators[$i]);
      if ($pos_separator != false) {
        $format_separator_idx = $i;
        break;
      }
    }

    if ($date_separator_idx != $format_separator_idx) {
      return false;
    }

    if ($date_separator_idx != -1) {
      $format_string_array = explode( $separators[$date_separator_idx], $format_string );
      if (sizeof($format_string_array) != 3) {
        return false;
      }

      $date_to_check_array = explode( $separators[$date_separator_idx], $date_to_check );
      if (sizeof($date_to_check_array) != 3) {
        return false;
      }

      $size = sizeof($format_string_array);
      for ($i=0; $i<$size; $i++) {
        if ($format_string_array[$i] == 'mm' || $format_string_array[$i] == 'mmm') $month = $date_to_check_array[$i];
        if ($format_string_array[$i] == 'dd') $day = $date_to_check_array[$i];
        if ( ($format_string_array[$i] == 'yyyy') || ($format_string_array[$i] == 'aaaa') ) $year = $date_to_check_array[$i];
      }
    } else {
      if (strlen($format_string) == 8 || strlen($format_string) == 9) {
        $pos_month = strpos($format_string, 'mmm');
        if ($pos_month != false) {
          $month = substr( $date_to_check, $pos_month, 3 );
          $size = sizeof($month_abbr);
          for ($i=0; $i<$size; $i++) {
            if ($month == $month_abbr[$i]) {
              $month = $i;
              break;
            }
          }
        } else {
          $month = substr($date_to_check, strpos($format_string, 'mm'), 2);
        }
      } else {
        return false;
      }

      $day = substr($date_to_check, strpos($format_string, 'dd'), 2);
      $year = substr($date_to_check, strpos($format_string, 'yyyy'), 4);
    }

    if (strlen($year) != 4) {
      return false;
    }

    if (!settype($year, 'integer') || !settype($month, 'integer') || !settype($day, 'integer')) {
      return false;
    }

    if ($month > 12 || $month < 1) {
      return false;
    }

    if ($day < 1) {
      return false;
    }

    if (tep_is_leap_year($year)) {
      $no_of_days[1] = 29;
    }

    if ($day > $no_of_days[$month - 1]) {
      return false;
    }

    $date_array = array($year, $month, $day);

    return true;
  }

////
// Check if year is a leap year
  function tep_is_leap_year($year) {
    if ($year % 100 == 0) {
      if ($year % 400 == 0) return true;
    } else {
      if (($year % 4) == 0) return true;
    }

    return false;
  }

////
// Return table heading with sorting capabilities
  function tep_create_sort_heading($sortby, $colnum, $heading) {
    global $PHP_SELF;

    $sort_prefix = '';
    $sort_suffix = '';

    if ($sortby) {
      $sort_prefix = '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('page', 'info', 'sort')) . 'page=1&sort=' . $colnum . ($sortby == $colnum . 'a' ? 'd' : 'a')) . '" title="' . tep_output_string(TEXT_SORT_PRODUCTS . ($sortby == $colnum . 'd' || substr($sortby, 0, 1) != $colnum ? TEXT_ASCENDINGLY : TEXT_DESCENDINGLY) . TEXT_BY . $heading) . '" class="productListing-heading">' ;
      $sort_suffix = (substr($sortby, 0, 1) == $colnum ? (substr($sortby, 1, 1) == 'a' ? '+' : '-') : '') . '</a>';
    }

    return $sort_prefix . $heading . $sort_suffix;
  }

////
// Recursively go through the categories and retreive all parent categories IDs
// TABLES: categories
  function tep_get_parent_categories(&$categories, $categories_id) {
    $parent_categories_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$categories_id . "'");
    while ($parent_categories = tep_db_fetch_array($parent_categories_query)) {
      if ($parent_categories['parent_id'] == 0) return true;
      $categories[sizeof($categories)] = $parent_categories['parent_id'];
      if ($parent_categories['parent_id'] != $categories_id) {
        tep_get_parent_categories($categories, $parent_categories['parent_id']);
      }
    }
  }

////
// Construct a category path to the product
// TABLES: products_to_categories
  function tep_get_product_path($products_id) {
    $cPath = '';

    $category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "' and p.products_id = p2c.products_id limit 1");
    if (tep_db_num_rows($category_query)) {
      $category = tep_db_fetch_array($category_query);

      $categories = array();
      tep_get_parent_categories($categories, $category['categories_id']);

      $categories = array_reverse($categories);

      $cPath = implode('_', $categories);

      if (tep_not_null($cPath)) $cPath .= '_';
      $cPath .= $category['categories_id'];
    }

    return $cPath;
  }

////
// Return a product ID with attributes
  function tep_get_uprid($prid, $params) {
    $uprid = $prid;
    if ( (is_array($params)) && (!strstr($prid, '{')) ) {
      // the PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES seeting now controls the format
      // of the attributes name field
      if (defined('PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES') && PRODUCT_INFO_SUB_PRODUCT_ATTRIBUTES == 'True' ) {
        // the atributes are passed as an array in the following format:
        // [option id] [identifier] = value
        while (list($option, $data) = each($params)) {
          while (list($type, $value) = each($data)) {
            // the text type is the only thing we handle diffeerently
            if ($type == 't') {  // this is a text input
              if ( ! empty($value))  $uprid = $uprid . '{' . $option . '}' . sprintf("%u", crc32($value));
            } elseif ($type == 'c') {  // this is a checkbox
              while (list($chkbox, $chkboxvalue) = each($value)) {
                $uprid = $uprid . '{' . $option . '}' . $chkbox;
              }
            } else {
             $uprid = $uprid . '{' . $option . '}' . $value;
            }
          }
        }
      } else {
        while (list($option, $value) = each($params)) {
          if (!is_array($value)) {
            $uprid = $uprid . '{' . $option . '}' . $value;
          } else {
            while (list($subtype, $subvalue) = each($value)) {
              if ($subtype == 'c') {  // this is a checkbox
                list($chkbox, $chkboxvalue) = each($subvalue);
                $uprid = $uprid . '{' . $option . '}' . $chkbox;
              } elseif ($subtype == 't') {  // this is a text input
                if ( ! empty($subvalue))  $uprid = $uprid . '{' . $option . '}' . sprintf("%u", crc32($subvalue));
              }
            }
          }
        }
      }
    }

    return $uprid;
  }

////
// Return a product ID from a product ID with attributes
  function tep_get_prid($uprid) {
    $pieces = explode('{', $uprid);

    return $pieces[0];
  }

////
// Return a customer greeting
  function tep_customer_greeting() {
  
    if ( isset($_SESSION['customer_first_name']) && isset($_SESSION['customer_id']) ) {
      $greeting_string = sprintf(TEXT_GREETING_PERSONAL, tep_output_string_protected($_SESSION['customer_first_name']), tep_href_link(FILENAME_PRODUCTS_NEW));
    } else {
      $greeting_string = sprintf(TEXT_GREETING_GUEST, tep_href_link(FILENAME_LOGIN, '', 'SSL'), tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
    }

    return $greeting_string;
  }

////
//! Send email (text/html) using MIME
// This is the central mail function. The SMTP Server should be configured
// correct in php.ini
// Parameters:
// $to_name           The name of the recipient, e.g. "Jan Wildeboer"
// $to_email_address  The eMail address of the recipient,
//                    e.g. jan.wildeboer@gmx.de
// $email_subject     The subject of the eMail
// $email_text        The text of the eMail, may contain HTML entities
// $from_email_name   The name of the sender, e.g. Shop Administration
// $from_email_adress The eMail address of the sender,
//                    e.g. info@mytepshop.com

  function tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address) {
    if (SEND_EMAILS != 'true') return false;

    // Instantiate a new mail object
    $message = new email(array('X-Mailer: osCommerce Mailer'));

    // Build the text version
    $text = strip_tags($email_text);
    if (EMAIL_USE_HTML == 'true') {
      $message->add_html($email_text, $text);
    } else {
      $message->add_text($text);
    }

    // Send message
    $message->build_message();
    $message->send($to_name, $to_email_address, $from_email_name, $from_email_address, $email_subject);
  }

////
// Check if product has attributes
  function tep_has_product_attributes($products_id) {
    $attributes_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "'");
    $attributes = tep_db_fetch_array($attributes_query);

    if ($attributes['count'] > 0) {
      return true;
    } else {
      return false;
    }
  }

// Check if product has attributes
  function tep_has_product_subproducts($products_id) {
    $subproducts_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS . " where products_parent_id  = '" . (int)$products_id . "'");
    $subproducts = tep_db_fetch_array($subproducts_query);

    if ($subproducts['count'] > 0) {
      return true;
    } else {
      return false;
    }
  }

  // count the number of images present for a product
  function tep_product_has_images($products_id) {
    $product_has_images = 0 ;
    $product_has_images_query = tep_db_query("select p.products_image, p.products_image_med, p.products_image_med from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$products_id . "'");
    while ($product_has_images_results = tep_db_fetch_array($product_has_images_query)){
      if ($product_has_images_results['products_image'] > '' ){
        $product_has_images = '1';
      } else {
        $product_has_images = '0';
      }
    }
    return $product_has_images;
  }

//returns the number of extra images a product has
//assumes that the large image is used only if a small is
function tep_product_has_extra_images($products_id) {
$product_has_extra_images = 0 ;
$product_has_extra_images_query = tep_db_query("select p.products_image_sm_1, p.products_image_sm_2, p.products_image_sm_3, p.products_image_sm_4, p.products_image_sm_5, p.products_image_sm_6 from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$products_id . "'");
  while ($product_has_extra_images_results = tep_db_fetch_array($product_has_extra_images_query)){
   if ($product_has_extra_images_results['products_image_sm_1'] > '' ){
   $product_has_extra_images++ ;
   }
   if ($product_has_extra_images_results['products_image_sm_2'] > '' ){
   $product_has_extra_images++ ;
   }
   if ($product_has_extra_images_results['products_image_sm_3'] > '' ){
   $product_has_extra_images++ ;
   }
   if ($product_has_extra_images_results['products_image_sm_4'] > '' ){
   $product_has_extra_images++ ;
   }
   if ($product_has_extra_images_results['products_image_sm_5'] > '' ){
   $product_has_extra_images++ ;
   }
   if ($product_has_extra_images_results['products_image_sm_6'] > '' ){
    $product_has_extra_images++ ;
   }
 }
  return $product_has_extra_images;
}


////
// Get the number of times a word/character is present in a string
  function tep_word_count($string, $needle) {
    $temp_array = preg_split('/' . $needle . '/', $string);

    return sizeof($temp_array);
  }

  function tep_count_modules($modules = '') {
    $count = 0;

    if (empty($modules)) return $count;

    $modules_array = preg_split('/;/', $modules);

    for ($i=0, $n=sizeof($modules_array); $i<$n; $i++) {
      $class = substr($modules_array[$i], 0, strrpos($modules_array[$i], '.'));

      if (isset($GLOBALS[$class]) && is_object($GLOBALS[$class])) {
        if ($GLOBALS[$class]->enabled) {
          $count++;
        }
      }
    }

    return $count;
  }

  function tep_count_payment_modules() {
    return tep_count_modules(MODULE_PAYMENT_INSTALLED);
  }

  function tep_count_shipping_modules() {
    return tep_count_modules(MODULE_SHIPPING_INSTALLED);
  }

  function tep_create_random_value($length, $type = 'mixed') {
    if ( ($type != 'mixed') && ($type != 'chars') && ($type != 'digits')) return false;

    $rand_value = '';
    while (strlen($rand_value) < $length) {
      if ($type == 'digits') {
        $char = tep_rand(0,9);
      } else {
        $char = chr(tep_rand(0,255));
      }
      if ($type == 'mixed') {
        if (preg_match('/^[a-z0-9]$/i', $char)) $rand_value .= $char;
      } elseif ($type == 'chars') {
        if (preg_match('/^[a-z]$/i', $char)) $rand_value .= $char;
      } elseif ($type == 'digits') {
        if (preg_match('/^[0-9]$/', $char)) $rand_value .= $char;
      }
    }

    return $rand_value;
  }

  function tep_array_to_string($array, $exclude = '', $equals = '=', $separator = '&') {
    if (!is_array($exclude)) $exclude = array();

    $get_string = '';
    if (sizeof($array) > 0) {
      while (list($key, $value) = each($array)) {
        if ( (!in_array($key, $exclude)) && ($key != 'x') && ($key != 'y') ) {
          $get_string .= $key . $equals . $value . $separator;
        }
      }
      $remove_chars = strlen($separator);
      $get_string = substr($get_string, 0, -$remove_chars);
    }

    return $get_string;
  }

  function tep_not_null($value) {
    if (is_array($value)) {
      if (sizeof($value) > 0) {
        return true;
      } else {
        return false;
      }
    } else {
      if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
        return true;
      } else {
        return false;
      }
    }
  }

////
// Output the tax percentage with optional padded decimals
  function tep_display_tax_value($value, $padding = TAX_DECIMAL_PLACES) {
    if (strpos($value, '.')) {
      $loop = true;
      while ($loop) {
        if (substr($value, -1) == '0') {
          $value = substr($value, 0, -1);
        } else {
          $loop = false;
          if (substr($value, -1) == '.') {
            $value = substr($value, 0, -1);
          }
        }
      }
    }

    if ($padding > 0) {
      if ($decimal_pos = strpos($value, '.')) {
        $decimals = strlen(substr($value, ($decimal_pos+1)));
        for ($i=$decimals; $i<$padding; $i++) {
          $value .= '0';
        }
      } else {
        $value .= '.';
        for ($i=0; $i<$padding; $i++) {
          $value .= '0';
        }
      }
    }

    return $value;
  }

////
// Checks to see if the currency code exists as a currency
// TABLES: currencies
  function tep_currency_exists($code) {
    $code = tep_db_prepare_input($code);

    $currency_code = tep_db_query("select currencies_id, code from " . TABLE_CURRENCIES . " where code = '" . tep_db_input($code) . "'");
    if (tep_db_num_rows($currency_code)) {
      $currency_data = tep_db_fetch_array($currency_code);
      return $currency_data['code'];
    } else {
      return false;
    }
  }

  function tep_string_to_int($string) {
    return (int)$string;
  }

////
// Parse and secure the cPath parameter values
  function tep_parse_category_path($cPath) {
// make sure the category IDs are integers
    $cPath_array = array_map('tep_string_to_int', explode('_', $cPath));

// make sure no duplicate category IDs exist which could lock the server in a loop
    $tmp_array = array();
    $n = sizeof($cPath_array);
    for ($i=0; $i<$n; $i++) {
      if ($cPath_array[$i] == 0) continue;
      if (!in_array($cPath_array[$i], $tmp_array)) {
        $tmp_array[] = $cPath_array[$i];
      }
    }

    return $tmp_array;
  }

////
// Return a random value
  function tep_rand($min = null, $max = null) {
    static $seeded;

    if ( ! isset($seeded)) {
      mt_srand((double)microtime()*1000000);
      $seeded = true;
    }

    if (isset($min) && isset($max)) {
      if ($min >= $max) {
        return $min;
      } else {
        return mt_rand($min, $max);
      }
    } else {
      return mt_rand();
    }
  }

  function tep_setcookie($name, $value = '', $expire = 0, $path = '/', $domain = '', $secure = 0) {
    setcookie($name, $value, $expire, $path, (tep_not_null($domain) ? $domain : ''), $secure);
  }

  function tep_get_ip_address() {
    if (isset($_SERVER)) {
      if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
      } else {
        $ip = $_SERVER['REMOTE_ADDR'];
      }
    } else {
      if (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
      } elseif (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
      } else {
        $ip = getenv('REMOTE_ADDR');
      }
    }

    return $ip;
  }

  function tep_count_customer_orders($id = '', $check_session = true) {
  
    if (is_numeric($id) == false) {
      if ( isset($_SESSION['customer_id']) ) {
        $id = $_SESSION['customer_id'];
      } else {
        return 0;
      }
    }

    if ($check_session == true) {
      if ( ! isset($_SESSION['customer_id']) || ($id != $_SESSION['customer_id']) ) {
        return 0;
      }
    }

    $orders_check_query = tep_db_query("select count(*) as total from " . TABLE_ORDERS . " where customers_id = '" . (int)$id . "'");
    $orders_check = tep_db_fetch_array($orders_check_query);

    return $orders_check['total'];
  }

  function tep_count_customer_address_book_entries($id = '', $check_session = true) {
  
    if (is_numeric($id) == false) {
      if ( isset($_SESSION['customer_id']) ) {
        $id = $_SESSION['customer_id'];
      } else {
        return 0;
      }
    }

    if ($check_session == true) {
      if ( ! isset($_SESSION['customer_id']) || ($id != $_SESSION['customer_id']) ) {
        return 0;
      }
    }

    $addresses_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$id . "'");
    $addresses = tep_db_fetch_array($addresses_query);

    return $addresses['total'];
  }

// nl2br() prior PHP 4.2.0 did not convert linefeeds on all OSs (it only converted \n)
  function tep_convert_linefeeds($from, $to, $string) {
    return str_replace($from, $to, $string);
  }

// BOF: WebMakers.com Added: Downloads Controller
//require(DIR_WS_FUNCTIONS . 'downloads_controller.php');
require(DIR_WS_FUNCTIONS . FILENAME_DOWNLOADS_CONTROLLER);
// EOF: WebMakers.com Added: Downloads Controller
////
//CLR 030228 Add function tep_decode_specialchars
// Decode string encoded with htmlspecialchars()
  function tep_decode_specialchars($string){
    $string=str_replace('&gt;', '>', $string);
    $string=str_replace('&lt;', '<', $string);
    $string=str_replace('&#039;', "'", $string);
    $string=str_replace('&quot;', "\"", $string);
    $string=str_replace('&amp;', '&', $string);

    return $string;
  }

////
// saved from old code
  function tep_output_warning($warning) {
  return  (tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . ' ' . $warning);
  }
  
  // Draw a pulldown for Option Types
  function draw_optiontype_pulldown($name, $default = '') {
    $values = array();
    $values[] = array('id' => 0, 'text' => 'Select');
    $values[] = array('id' => 1, 'text' => 'Text');
    $values[] = array('id' => 2, 'text' => 'Radio');
    $values[] = array('id' => 3, 'text' => 'Checkbox');
    $values[] = array('id' => 4, 'text' => 'Text Area');
    
    return tep_draw_pull_down_menu($name, $values, $default);
  }
  
  //CLR 030312 add function to translate type_id to name
  // Translate option_type_values to english string
  function translate_type_to_name($opt_type) {
    if ($opt_type == 0) return 'Select';
    if ($opt_type == 1) return 'Text';
    if ($opt_type == 2) return 'Radio';
    if ($opt_type == 3) return 'Checkbox';
    if ($opt_type == 4) return 'Text Area';
    return 'Error ' . $opt_type;
  }

  function tep_get_box_heading($infobox_id, $languages_id) {
    $configuration_query12 = tep_db_query("select box_heading from " . TABLE_INFOBOX_HEADING . " where infobox_id = '" . (int)$infobox_id . "' and languages_id = '" . (int)$languages_id . "'");
    $configuration12 = tep_db_fetch_array($configuration_query12);
    return $configuration12['box_heading'];
  }
  
// Contact Us Email Subject : DMG
// PassionSeed Contact Us Email Subject begin
  function tep_get_email_subjects_list($subjects_array = '') {
    if (!is_array($subjects_array)) $subjects_array = array();

    $subjects_query = tep_db_query("select email_subjects_id, email_subjects_name, email_subjects_category from " . TABLE_EMAIL_SUBJECTS . " where email_subjects_category = '2' order by email_subjects_name");
    while ($subjects = tep_db_fetch_array($subjects_query)) {
      $subjects_array[] = array('id' => $subjects['email_subjects_name'], 'text' => $subjects['email_subjects_name']);
    }

    return $subjects_array;
  }
// PassionSeed Contact us Email Subject end

// Randomizer for specials modules ($products_id, $max_displayed)
  function cre_random_array($random,$i){
    srand((float) microtime() * 10000000);
    $rand_keys = array_rand($random, $i);
    $res = array();
    if($i > 1){
      for($a=0;$a<$i;$a++){
       $res[] = $random[$rand_keys[$a]];
      }
    }else{
      $res[] = $random[$rand_keys];
    }
    return $res;
  }
  
  //clean product desciption on product_info
  function cre_clean_product_description($description){
    $unwanted = array('<pre>', '</pre>'); //search for these
    $description = str_replace($unwanted, '', $description);
    
    return stripslashes($description);
  }
  
  //short description of product to be used with listng, mainpage modules
  function cre_product_short_description_template($products_id, $limit='80'){
    global $languages_id;
    if (empty($language)) $language = $languages_id;
    $product_query = tep_db_query("select products_description from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language . "'");
    $product_desc = tep_db_fetch_array($product_query);
    $products_description = $product_desc['products_description'];
    $search = array('@<script[^>]*?>.*?</script>@si', 
               '@<[\/\!]*?[^<>]*?>@si',
               '@<style[^>]*?>.*?</style>@siU',
               '@<![\s\S]*?--[ \t\n\r]*>@'
               );
    $products_description = preg_replace($search, '', $products_description);
    return strlen($products_description) > $limit ? substr($products_description, 0, $limit - 3) . '...' : $products_description;
  }
 
  //clean html and extract only text
  function cre_clean_html($html){
    $search = array('@<script[^>]*?>.*?</script>@si', 
               '@<[\/\!]*?[^<>]*?>@si',
               '@<style[^>]*?>.*?</style>@siU',
               '@<![\s\S]*?--[ \t\n\r]*>@'
               );
    return preg_replace($search, '', $html);
  }

  function tep_remove_order($order_id, $restock) {
    if ($restock == 'on') {
      $order_query = tep_db_query("select products_id, products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "'");
      while ($order = tep_db_fetch_array($order_query)) {
        tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity + " . $order['products_quantity'] . ", products_ordered = products_ordered - " . $order['products_quantity'] . " where products_id = '" . (int)$order['products_id'] . "'");
      }
    }

    //begin PayPal_Shopping_Cart_IPN 2.8 DMG
    // the file may no longer exist, so check for it
    if (file_exists(DIR_WS_MODULES . 'payment/paypal/functions/general.func.php')) {
      include_once(DIR_WS_MODULES . 'payment/paypal/functions/general.func.php');
      paypal_remove_order($order_id);
    }
    //end PayPal_Shopping_Cart_IPN
    tep_db_query("delete from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$order_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . (int)$order_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "'");
  }

  // Validate teh Zip Code 
  function validate_zip_code($zip_code) {
    $pattern = "/^[A-Za-z0-9- ]+$/"; /// allows alphanumeric value with minus and space character
    if( preg_match( $pattern, $zip_code ) ) {
      return true;
    } else {
      return false;
    }
  }

  function chk_free_shipping_zone($data) {    
    $data = MODULE_SHIPPING_FREESHIPPER_ZONE;
    $geo_zone_id = 0;
    // Get geo zone Id for free shipper
    if (defined('INSTALLED_VERSION_TYPE') && stristr(INSTALLED_VERSION_TYPE, 'B2B')) {
      $data_array = explode(',', $data);
      foreach ($data_array as $value) {
        $tmp = explode('-', $value);
        if ($tmp[0] == (int)$_SESSION['sppc_customer_group_id']) {
          $geo_zone_id = (int)$tmp[1];
          break;
        }
      }  
    } else {
      $geo_zone_id = $data;
    }   

    if ($geo_zone_id == 0) {
      return -1;
    }
    //if customer logins then collect country id and zone id for  shiping address
    if (isset($_SESSION['customer_id']) && (int)$_SESSION['customer_id'] > 0) {
      $address_query = tep_db_query("select ab.entry_country_id, ab.entry_zone_id from " . TABLE_ADDRESS_BOOK . " ab where ab.customers_id = '" . (int)$_SESSION['customer_id'] . "' and ab.address_book_id = '" . (int)($_SESSION['shoppingCart_data']['content_type'] == 'virtual' ? (int)$_SESSION['customer_default_address_id'] : (isset($_SESSION['sendto']) ? (int)$_SESSION['sendto'] : (int)$_SESSION['customer_default_address_id'])) . "'");

      $address = tep_db_fetch_array($address_query);
      $country = $address['entry_country_id'];
      $zone_id = $address['entry_zone_id'];
    } else {
      // if not login customers default counrty id and zone id
      $country = (int)$_SESSION['customer_country_id'];
      $zone_id = (int)$_SESSION['customer_zone_id'];
    } 
    // free shipping check for seleced country id and zone id 
    $tax_query = tep_db_query("select * from " . TABLE_ZONES_TO_GEO_ZONES . " za where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int)$country . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int)$zone_id . "') and za.geo_zone_id  = '" . (int)$geo_zone_id . "'");

    if (tep_db_num_rows($tax_query)) {
      return 1;
    } else {
      return 0;
    }  
  }
  //
  //check if product is really a subproduct
  //returns parent_id if product is a sub, or false if it is not
  function tep_subproducts_parent($products_id) {  
      return false;
  }
function cre_site_branding_rspv($show = '') {
  global $languages_id;    
    
  $store_info = '';
    
  $site_branding_query = tep_db_query("SELECT *
                                       FROM " . TABLE_BRANDING_DESCRIPTION . "
                                       WHERE language_id = " . $languages_id);
  $site_brand_info = tep_db_fetch_array($site_branding_query);
  
  $affiliate_branding = array();
  if (isset($_SESSION['affiliate_ref'])) {
    $affiliate_branding_query = tep_db_query("SELECT affiliate_template, affiliate_cobrand_image, affiliate_cobrand_name, affiliate_cobrand_slogan, affiliate_cobrand_url, affiliate_cobrand_support_email, affiliate_cobrand_support_phone
                                              FROM " . TABLE_AFFILIATE . "
                                              WHERE affiliate_id = " . $_SESSION['affiliate_ref']);
    $affiliate_branding = tep_db_fetch_array($affiliate_branding_query);
  }
  
  if (tep_not_null($affiliate_branding['affiliate_cobrand_url'])) {
    $brand_url = $affiliate_branding['affiliate_cobrand_url'];
  } elseif (tep_not_null($site_brand_info['store_brand_homepage'])) {
    $brand_url = $site_brand_info['store_brand_homepage'];
  } else {
    $brand_url = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
  }
  
  switch($show){
    case 'storeurl':
      $store_info = '<a href="' . $brand_url . '">' . str_replace('http://','',$brand_url) . '</a>';
      break;

    case 'phone':
      if (tep_not_null($affiliate_branding['affiliate_cobrand_support_phone'])) {
        $store_info = $affiliate_branding['affiliate_cobrand_support_phone'];
      } elseif (tep_not_null($site_brand_info['store_brand_support_phone'])) {
        $store_info = $site_brand_info['store_brand_support_phone'];
      }
      break;

    case 'email':
      if (tep_not_null($affiliate_branding['affiliate_cobrand_support_email'])) {
        $branding_mailto = $affiliate_branding['affiliate_cobrand_support_email'];
      } elseif (tep_not_null($site_brand_info['store_brand_support_email'])) {
        $branding_mailto = $site_brand_info['store_brand_support_email'];
      } else {
        $branding_mailto = STORE_OWNER_EMAIL_ADDRESS;
      }
      $branding_mailto = str_replace('@','&#64;',$branding_mailto); //let's fight spam. Not strong as javascript, but works...!
      $store_info = '<a href="mailto&#x3A;' . $branding_mailto . '">' . $branding_mailto . '</a>';
      break;

    case 'logo':
      if (tep_not_null($affiliate_branding['affiliate_cobrand_image']) && file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . 'affiliate_cobrand/' . $affiliate_branding['affiliate_cobrand_image'])) {
        $store_info = '<a href="'. $brand_url . '">' . tep_image(DIR_WS_IMAGES . 'affiliate_cobrand/' . $affiliate_branding['affiliate_cobrand_image']) . '</a>';
      } elseif (tep_not_null($affiliate_branding['affiliate_cobrand_name']))  {
        $store_info = '<a href="'. $brand_url . '" class="branding_name">' . $affiliate_branding['affiliate_cobrand_name'] . '</a>';
      } elseif (tep_not_null($site_brand_info['store_brand_image']) && file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . 'logo/' . $site_brand_info['store_brand_image'])) {
        $store_info = '<a href="' . $brand_url . '">' . tep_image(DIR_WS_IMAGES . 'logo/' . $site_brand_info['store_brand_image'], '', '', '', 'class="small-margin-right img-logo-responsive"') . '</a>';
      } elseif (tep_not_null($site_brand_info['store_brand_name'])) {
        $store_info = '<a class="branding_name" href="' . $brand_url . '">' . $site_brand_info['store_brand_name'] . '</a>';
      } else {
        $store_info = '<a class="branding_name" href="' . $brand_url . '">' . STORE_NAME .'</a>';
      }
      break;

    case 'favicon':
      if (tep_not_null($site_brand_info['store_brand_favicon']) && file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . 'logo/' . $site_brand_info['store_brand_favicon'])) {
        $store_info = DIR_WS_IMAGES . 'logo/' . $site_brand_info['store_brand_favicon'];
      } else {
        $store_info = DIR_WS_IMAGES . 'logo/loaded-favicon-64.gif';
      }
      break;

    case 'logourl':
      if (tep_not_null($affiliate_branding['affiliate_cobrand_image']) && file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . 'affiliate_cobrand/' . $affiliate_branding['affiliate_cobrand_image'])) {
        $store_info = (($_SERVER['SERVER_PORT'] == 443)?HTTPS_SERVER:HTTP_SERVER).'/'.DIR_WS_IMAGES . 'affiliate_cobrand/' . $affiliate_branding['affiliate_cobrand_image'];
      } elseif (tep_not_null($site_brand_info['store_brand_image']) && file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . 'logo/' . $site_brand_info['store_brand_image'])) {
        $store_info = (($_SERVER['SERVER_PORT'] == 443)?HTTPS_SERVER:HTTP_SERVER).'/'.DIR_WS_IMAGES . 'logo/' . $site_brand_info['store_brand_image'];
      } else {
        $store_info = (($_SERVER['SERVER_PORT'] == 443)?HTTPS_SERVER:HTTP_SERVER).'/'.DIR_WS_IMAGES.STORE_LOGO;
      }
      break;

    case 'slogan':
      if (tep_not_null($affiliate_branding['affiliate_cobrand_slogan'])) {
        $store_info = '<p class="slogan clear-both small-margin-top-neg hide-on-mobile">' . $affiliate_branding['affiliate_cobrand_slogan'] . '</p>';
      } elseif (tep_not_null($site_brand_info['store_brand_slogan']) ) {
        $store_info = '<p class="slogan clear-both small-margin-top-neg hide-on-mobile">' . $site_brand_info['store_brand_slogan'] . '</p>';
      }
      break;

    default:
      $store_info = '<a class="store_name" href="' . tep_href_link(FILENAME_DEFAULT) . '">' . STORE_NAME .'</a>';
      break;
        
  }//end switch
  return $store_info;
 }

?>