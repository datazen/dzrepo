<?php
class Products {
  public static function getAll($format = 'list', $exclude_pID = '') {
    global $languages_id;

    $products_query = tep_db_query("select pd.*, p.* from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");

    $result = array();
    switch ($format) {
      case 'list' :
        while($products = tep_db_fetch_array($products_query)) {

          if ($exclude_pID != '' && $exclude_pID == $products['products_id']) continue;

          $result[] = array('id' => $products['products_id'], 'text' => $products['products_name']);
        }

        break;
    }

    return $result;
  }
}
?>