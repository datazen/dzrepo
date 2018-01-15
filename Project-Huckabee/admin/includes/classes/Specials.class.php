<?php
class Specials {

  public static function getSpecialsInfo($products_id) {
    $specials_query_raw = "SELECT * FROM " . TABLE_SPECIALS . " WHERE products_id = '" . (int)$products_id . "'";
    $specials_query = tep_db_query($specials_query_raw);
    $specials = tep_db_fetch_array($specials_query);

    return $specials;
  }

  public static function getSpecialPrice($products_id) {
    $sInfo = self::getSpecialsInfo($products_id);

    $special_price = '';
    if ($sInfo['status'] == 1) {
      $special_price = ($sInfo['specials_new_products_price'] != '') ? number_format($sInfo['specials_new_products_price'], 2) : '';
    }

    // check expiration if set
    if (isset($sInfo['expires_date']) && $sInfo['expires_date'] != '' && $sInfo['expires_date'] != '0000-00-00 00:00:00') {
      $expire = strtotime($sInfo['expires_date']);
      $today = strtotime("today midnight");

      if($today >= $expire){
        // expired
        $special_price = '';
      }
    }

    return $special_price;
  }

  public static function update($products_id, $special_price) {
    $delete_query_raw = "DELETE FROM " . TABLE_SPECIALS . " WHERE products_id = '" . (int)$products_id . "'";
    $delete_query = tep_db_query($delete_query_raw);

    if ($special_price != 0) {
      $specials_query_raw = "INSERT INTO " . TABLE_SPECIALS . " (products_id, specials_new_products_price, specials_date_added, status) VALUES ('" . (int)$products_id . "','" . $special_price . "','" . @date("Y-m-d H:i:s") . "','1')";
      $specials_query = tep_db_query($specials_query_raw);
    }

    return true;    
  }  
}
?>