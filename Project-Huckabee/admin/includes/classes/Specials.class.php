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

    $special_price = 0.00;
    if ($sInfo['status'] == 1) {
      $special_price = ($sInfo['specials_new_products_price'] != '') ? $sInfo['specials_new_products_price'] : 0.00;
    }

    // check expiration if set
    if (isset($sInfo['expires_date']) && $sInfo['expires_date'] != '' && $sInfo['expires_date'] != '0000-00-00 00:00:00') {
      $expire = strtotime($sInfo['expires_date']);
      $today = strtotime("today midnight");

      if($today >= $expire){
        // expired
        $special_price = 0.00;
      }
    }

    return $special_price;
  }
}
?>