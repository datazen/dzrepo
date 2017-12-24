<?php
/*
  $Id: ultimateseo.php, v2.5 2008/11/20 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  ContributionCentral, ContributionCentral provides CRE Loaded Modules Templates Add-Ons Contributions Features Customization and more!
  http://www.contributioncentral.com

  Based on Ultimate SEO URL's 2.1 by Chemo
  
  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce
  Copyright (c) 2009 ContributionCentral

  Released under the GNU General Public License
*/

  class ultimateseo {
    var $title;

    function ultimateseo() {
      $this->code = 'ultimateseo';
      $this->title = (defined('MODULE_ADDONS_ULTIMATESEO_TITLE')) ? MODULE_ADDONS_ULTIMATESEO_TITLE : '';
      $this->description = (defined('MODULE_ADDONS_ULTIMATESEO_DESCRIPTION')) ? MODULE_ADDONS_ULTIMATESEO_DESCRIPTION : '';
      if (defined('MODULE_ADDONS_ULTIMATESEO_STATUS')) {
        $this->enabled = ((MODULE_ADDONS_ULTIMATESEO_STATUS == 'True') ? true : false);
      } else {
        $this->enabled = false;
      }
      $this->sort_order  = (defined('MODULE_ADDONS_ULTIMATESEO_SORT_ORDER')) ? (int)MODULE_ADDONS_ULTIMATESEO_SORT_ORDER : 0;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("SELECT configuration_value 
                                     from " . TABLE_CONFIGURATION . " 
                                     WHERE configuration_key = 'MODULE_ADDONS_ULTIMATESEO_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function keys() {
      return array('MODULE_ADDONS_ULTIMATESEO_STATUS', 'SEO_VALIDATION_ON');
    }

    function install() {
      // insert module config values
      tep_db_query("INSERT IGNORE INTO configuration VALUES ('', 'Enable Ultimate SEO URL\'s', 'MODULE_ADDONS_ULTIMATESEO_STATUS', 'True', 'Enable the Ultimate SEO URL\'s Module', '6', '5039', now(), now(), NULL, 'tep_cfg_select_option(array(''True'', ''False''),')");
      tep_db_query("INSERT IGNORE INTO configuration VALUES ('', 'Enable Seo URL validation?', 'SEO_VALIDATION_ON', 'true', 'Enable the SEO URL validation?', '6', '5039', now(), now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      //tep_db_query("INSERT IGNORE INTO configuration VALUES ('', 'Enable Seo URL performance diagnostic code?', 'MODULE_ADDONS_ULTIMATESEO_PERFORMANCE', 'false', 'Enable the SEO URL performance diagnostic code by setting this option to \'true\' and adding \"?profile=on\" to any store side url. Turn the diagnostic code off by adding \"?profile=off\" to the url then set this option to \'false\'.', '6', '5039', now(), now(), NULL, 'tep_cfg_select_option(array(''true'', ''false''),')");
      
      // alter the products and categories table
      $fields = mysql_list_fields(DB_DATABASE, 'categories_description');
      $columns = mysql_num_fields($fields);
      for ($i = 0; $i < $columns; $i++) {$field_array[] = mysql_field_name($fields, $i);}
      if (!in_array('categories_seo_url', $field_array)) {
        tep_db_query("ALTER TABLE categories_description ADD categories_seo_url VARCHAR(255) NOT NULL;");
        tep_db_query("ALTER TABLE products_description ADD products_seo_url VARCHAR(255) NOT NULL;");
      }
    }

    function remove() {
      $seoConfigGroupID = tep_db_fetch_array(tep_db_query("SELECT configuration_group_id FROM configuration_group WHERE configuration_group_title = 'SEO URLs'"));
      tep_db_query("DELETE FROM configuration_group WHERE configuration_group_title = 'SEO URLs'");
      tep_db_query("DELETE FROM configuration WHERE configuration_key = 'MODULE_ADDONS_ULTIMATESEO_STATUS'");
      tep_db_query("DELETE FROM configuration WHERE configuration_key = 'SEO_VALIDATION_ON'");
      tep_db_query("DELETE FROM configuration WHERE configuration_group_id = '" . $seoConfigGroupID['configuration_group_id'] . "'");
      //tep_db_query("DROP TABLE IF EXISTS `cache`");
      //tep_db_query("ALTER TABLE `categories_description` DROP COLUMN `categories_seo_url`;");
      //tep_db_query("ALTER TABLE `products_description` DROP COLUMN `products_seo_url`;");
    }
  }  
?>