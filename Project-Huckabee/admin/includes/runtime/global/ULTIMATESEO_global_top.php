<?php
/*
  $Id: ULTIMATESEO_global_top.php,v 1.0.0.0 2011/10/20 13:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
    if (MODULE_ADDONS_ULTIMATESEO_STATUS == 'True') {
    global $action, $current_page, $faq_action;
    $reset_seo_cache = false;

        switch($current_page){
            case FILENAME_CATEGORIES:
            if (preg_match("/(insert|update|setflag)/i", $action)) $reset_seo_cache = true;
            break;
            
            case FILENAME_ARTICLES:
            if (preg_match("/(insert_topic|update_topic|setflag)/i", $action)) $reset_seo_cache = true;
            break;
            
            case FILENAME_AUTHORS:
            if (preg_match("/(insert|save)/i", $action)) $reset_seo_cache = true;
            break;
            
            case FILENAME_PAGES:
            if (preg_match("/(insert|update|setflag)/i", $action)) $reset_seo_cache = true;
            break;
            
            case FILENAME_FAQ_MANAGER:
            if (preg_match("/(AddSure|Update|Visible)/i", $faq_action)) $reset_seo_cache = true;
            break;
            
            case FILENAME_FAQ_CATEGORIES:
            if (preg_match("/(insert|update|setflag)/i", $action)) $reset_seo_cache = true;
            break;
            
            case FILENAME_MANUFACTURERS:
            if (preg_match("/(insert|save)/i", $action)) $reset_seo_cache = true;
            break;
            
            case FILENAME_LINKS:
            if (preg_match("/(insert|update)/i", $action)) $reset_seo_cache = true;
            break;
            
            case FILENAME_LINK_CATEGORIES:
            if (preg_match("/(insert|update|setflag)/i", $action)) $reset_seo_cache = true;
            break;
            
            case FILENAME_FSS_FORMS_BUILDER:
            if (preg_match("/(save|insert_form|save_form|save_system_form|save_categories|insert_categories|delete_categories_confirm|move_categories_confirm|delete_form_confirm|move_form_confirm|copy_to_form_confirm)/i", $action)) $reset_seo_cache = true;
            break;
            
            case FILENAME_LIBRARY_FILES:
            if (preg_match("/(setflag|file_confirm|folder_move_confirm|file_confirm_move|file_copy_to_confirm)/i", $action)) $reset_seo_cache = true;
            break;
        }
        if($reset_seo_cache == true){
            include_once('includes/reset_seo_cache.php');
        }
    }
?>