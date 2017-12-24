<?php
  if (MODULE_ADDONS_PREVNEXT_STATUS == 'True') {
    if ((PREV_NEXT_PRODUCTINFO_PLACEMENT == 'bottom') || (PREV_NEXT_PRODUCTINFO_PLACEMENT == 'topbottom')) {
      global $cPath, $languages_id;
      $cPath_query = tep_db_query ("SELECT categories_id FROM " . TABLE_PRODUCTS_TO_CATEGORIES . " WHERE products_id ='" .  (int)$_GET['products_id'] . "'");
      $cPath_row = tep_db_fetch_array($cPath_query);
      $current_category_id = $cPath_row['categories_id'];
      $pID = (int)$_GET['products_id'];
      include('prevNextGroups.php');
      if (isset($_GET['manufacturers_id'])) { 
        $products_ids = tep_db_query("select p.products_id from " . TABLE_PRODUCTS . " p where p.products_status = '1'  and p.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' " . $customer_access);
        $category_name_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'");
        $category_name_row = tep_db_fetch_array($category_name_query);
        $prev_next_in = PREV_NEXT_MB . '&nbsp;' . ($category_name_row['manufacturers_name']);
        $fPath = 'manufacturers_id=' . (int)$_GET['manufacturers_id'];
      } else {
        $fPath = 'cPath=' . $cPath;
        $products_ids = tep_db_query("select p.products_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc where p.products_status = '1'  and p.products_id = ptc.products_id and ptc.categories_id = '" . (int)$current_category_id . "' " . $customer_access);
        $category_name_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$current_category_id . "' AND language_id = $languages_id");
        $category_name_row = tep_db_fetch_array($category_name_query);
        $category_title = PREV_NEXT_BACK_TO . $category_name_row['categories_name'] . PREV_NEXT_LISTING;
        $prev_next_in = PREV_NEXT_CAT . '&nbsp;<a class="main" href="' . tep_href_link(FILENAME_DEFAULT, "$fPath") . '" title="' . $category_title . '"><b>' . ($category_name_row['categories_name']) . '</b></a>';
      }
      while ($product_row = tep_db_fetch_array($products_ids)) {
        $id_array[] = $product_row['products_id'];
      }
      // calculate the previous and next
      reset ($id_array);
      $counter = 0;
      while (list($key, $value) = each ($id_array)) {
        if ($value == $pID) {
          $position = $counter;
          if ($key == 0) {
            $previous = -1; // it was the first to be found
          } else {
            $previous = $id_array[$key - 1];
          }
          if ($id_array[$key + 1]) {
            $next_item = $id_array[$key + 1];
          } else {
            $next_item = $id_array[0];
          }
        }
        $last = $value;
        $counter++;
      }
      if ($previous == -1) {
        $previous = $last;
      }
      //Get the previous name
      $previous_id = $previous;
      $previous_name_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $previous_id . "'");
      $previous_name = tep_db_fetch_array($previous_name_query);
      // Get the next name
      $next_id = $next_item;
      $next_name_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $next_id . "'");
      $next_name = tep_db_fetch_array($next_name_query);
      if ($counter == 1) {
        '';
      } else {
    ?>
    <tr>
      <td>
        <div id="prevNextBottom">
        <table width="100%" align="center">
          <tr>
    	       <td width="15%" align="right" class="main"><?php if (($counter != 1) && ($position != 0)) { echo ('<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, "$fPath&products_id=$previous") . '">' .  tep_image(DIR_WS_IMAGES . 'chevron_previous.gif', $previous_name['products_name']) . '</a>&nbsp;'); } ?></td>
            <td width="70%" align="center" class="main" valign="top"><?php echo (PREV_NEXT_PRODUCT) . '&nbsp;<b>' . ($position+1 . '</b>&nbsp;' . PREV_NEXT_OF . '&nbsp;' . $counter) . '&nbsp;' . $prev_next_in; ?></td>
    		      <td width="15%" align="left" class="main"><?php if (($counter !=1) && (($position+1) != $counter)) { echo ('&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, "$fPath&products_id=$next_item") . '">' . tep_image(DIR_WS_IMAGES . 'chevron_next.gif', $next_name['products_name']) . '</a>'); } ?></td>
          </tr>
        </table>
        </div>
      </td>
    </tr>
    <?php
      }
    }
  }
?>