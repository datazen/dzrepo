<?php
class Featured {

  public static function getFeaturedInfo($products_id) {
    $featured_query_raw = "SELECT * FROM " . TABLE_FEATURED . " WHERE products_id = '" . (int)$products_id . "'";
    $featured_query = tep_db_query($featured_query_raw);
    $featured = tep_db_fetch_array($featured_query);

    return $featured;
  }

  public static function isFeatured($products_id) {
    $fInfo = self::getFeaturedInfo($products_id);

    $is_featured = false;
    if ($fInfo['status'] == 1) {
      $is_featured = true;
    }

    // check expiration if set
    if (isset($fInfo['expires_date']) && $fInfo['expires_date'] != '' && $fInfo['expires_date'] != '0000-00-00 00:00:00') {
      $expire = strtotime($fnfo['expires_date']);
      $today = strtotime("today midnight");

      if($today >= $expire){
        // expired
        $is_featured = false;
      }
    }

    return $is_featured;
  }

  public static function update($products_id, $featured) {
    $delete_query_raw = "DELETE FROM " . TABLE_FEATURED . " WHERE products_id = '" . (int)$products_id . "'";
    $delete_query = tep_db_query($delete_query_raw);

    if ($featured == 1) {
      $featured_query_raw = "INSERT INTO " . TABLE_FEATURED . " (products_id, featured_date_added, status) VALUES ('" . (int)$products_id . "','" . @date("Y-m-d H:i:s") . "','1')";
      $featured_query = tep_db_query($featured_query_raw);
    }

    return true;    
  }
}
?>