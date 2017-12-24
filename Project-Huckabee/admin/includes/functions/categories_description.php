<?php
  //---------------------------------------------------------------------------//
  //
  //  Code: categories_description
  //  Author: Brian Lowe <blowe@wpcusrgrp.org>
  //  Date: June 2002
  //
  //  Contains code snippets for the categories_description contribution to
  //  osCommerce.
  //---------------------------------------------------------------------------//
  //  Code: categories_description MS2 1.5
  //  Editor: Lord Illicious <shaolin-venoms@illicious.net>
  //  Date: July 2003
  //
  //---------------------------------------------------------------------------//

  //---------------------------------------------------------------------------//
  //  Get a category heading_title or description
  // These should probably be in admin/includes/functions/general.php, but since
  // this is a contribution and not part of the base code, they are here instead
  //---------------------------------------------------------------------------//
  function tep_get_category_heading_title($category_id, $language_id) {
    $category_query = tep_db_query("select categories_heading_title from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
    $category = tep_db_fetch_array($category_query);
    return $category['categories_heading_title'];
  }

  function tep_get_category_description($category_id, $language_id) {
    $category_query = tep_db_query("select categories_description from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
    $category = tep_db_fetch_array($category_query);
    return $category['categories_description'];
  }
 function tep_get_category_head_title_tag($category_id, $language_id) {
    $category_query = tep_db_query("select categories_head_title_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
    $category = tep_db_fetch_array($category_query);
    return $category['categories_head_title_tag'];
  }
   function tep_get_category_head_desc_tag($category_id, $language_id) {
      $category_query = tep_db_query("select categories_head_desc_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
      $category = tep_db_fetch_array($category_query);
      return $category['categories_head_desc_tag'];
  }
   function tep_get_category_head_keywords_tag($category_id, $language_id) {
      $category_query = tep_db_query("select categories_head_keywords_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
      $category = tep_db_fetch_array($category_query);
      return $category['categories_head_keywords_tag'];
  }
?>
