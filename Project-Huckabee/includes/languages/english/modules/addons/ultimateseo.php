<?php
/*
  $Id: ultimateseo.php,v 1.0.0 2008/05/22 13:41:11 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Based on Ultimate SEO URL's by Chemo
  
  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  define('MODULE_ADDONS_ULTIMATESEO_TITLE', 'LC Ultimate SEO URL\'s');
  $seo_enabled_query = tep_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_ADDONS_ULTIMATESEO_STATUS'");
  $seo_enabled = tep_db_fetch_array($seo_enabled_query);
  if ($seo_enabled['configuration_value'] == 'True') {
    define('MODULE_ADDONS_ULTIMATESEO_DESCRIPTION', '<p style="color:red;">After the installation of this module you will have to rename the "<b>seo.htaccess</b>" file in the root directory of your online store to "<b>.htaccess</b>" removing "<b>seo</b>" from the beginning of the filename via your hosting control panel (cPanel, Plesk or similar) or ftp. Then visit the store front to be sure the needed configuration variables have been loaded into the database.</p><p>You can also view the user manual for this addon to fine tune the settings for your store <A href="ultimate_seo.php">here</a>.</p>');
  } else{
    define('MODULE_ADDONS_ULTIMATESEO_DESCRIPTION', '<p>This module Adds the popular Ultimate SEO URL\'s (Long Live Chemo!) to your store\'s links and replaces the 6.4.2 CRE SEO URL\'s.</p>');
  }
?>