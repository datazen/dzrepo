<?php
/*
  $Id: ultimateseo_english_lang.php, v1.5 2003/04/25 21:37:11 maestro Exp $

  Contribution Central, Custom CRELoaded & osCommerce Programming
  http://www.contributioncentral.com
  Copyright (c) 2008 Contribution Central

  Released under the GNU General Public License
*/
if (defined('MODULE_ADDONS_ULTIMATESEO_STATUS') && MODULE_ADDONS_ULTIMATESEO_STATUS == 'True') {
  global $action, $category_id, $language_id;
  // Function to reset SEO URLs database cache entries 
  // Ultimate SEO URLs v2.1e BOF
    function tep_reset_cache_data_seo_urls($action){        
      switch ($action) {
        case 'reset':
            tep_db_query("DELETE FROM cache WHERE cache_name LIKE '%seo_urls%'");
            tep_db_query("UPDATE configuration SET configuration_value = 'false' WHERE configuration_key = 'SEO_URLS_CACHE_RESET'");
          break;
        default:
          break;
      }
      # The return value is used to set the value upon viewing
      # It's NOT returining a false to indicate failure!!
      return 'false';
    }

    function tep_get_category_seo_url($category_id, $language_id) {
      $category_query = tep_db_query("select categories_seo_url from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$category_id . "' and language_id = '" . (int)$language_id . "'");
      $category = tep_db_fetch_array($category_query);
      return $category['categories_seo_url'];
    }

    function tep_get_products_seo_url($product_id, $language_id = 0) {
      global $languages_id;
      if ($language_id == 0) $language_id = $languages_id;
      $product_query = tep_db_query("select products_seo_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language_id . "'");
      $product = tep_db_fetch_array($product_query);
      return $product['products_seo_url'];
    }
  // Ultimate SEO URLs v2.1e EOF
}
?>