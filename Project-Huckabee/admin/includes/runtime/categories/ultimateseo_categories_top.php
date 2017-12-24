<?php

/*
  $Id: ultimateseo_categories_top.php,v 1.1.1.1 2008/06/17 23:38:51 maestro Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  if (defined('MODULE_ADDONS_ULTIMATESEO_STATUS') && MODULE_ADDONS_ULTIMATESEO_STATUS == 'True') {
    // Ultimate SEO URLs v2.1e BOF
    // If the action will affect the cache entries
    if (preg_match("/(insert|update|setflag)/i", $action)) include_once('includes/reset_seo_cache.php');
    // Ultimate SEO URLs v2.1e EOF
  }
?>