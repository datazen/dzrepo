<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Featured Products V1.1
  Displays a list of featured products, selected from admin
  For use as an Infobox instead of the "New Products" Infobox
*/
?>
<!-- D featured_products_mainpage //-->
<?php
 
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text' => TABLE_HEADING_FEATURED_PRODUCTS);

      $mainpage_featured_query = tep_db_query("select distinct
                           p.products_image, 
                           p.products_id,
                           pd.products_name,
         p.products_image 
         from (" . TABLE_PRODUCTS . " p 
         left join " . TABLE_SPECIALS . " s using(products_id)),
          " . TABLE_PRODUCTS_DESCRIPTION . " pd,
          " . TABLE_FEATURED . " f
          where 
          p.products_status = '1'
          and f.status ='1'
          and p.products_id = f.products_id
                            and pd.products_id = p.products_id
                            and pd.language_id = '" . $languages_id . "'
                            order by rand(), p.products_date_added DESC, pd.products_name limit " . MAX_DISPLAY_FEATURED_PRODUCTS);
   
   $mainpage_featured_check = tep_db_num_rows($mainpage_featured_query);
     if ($mainpage_featured_check > 0){
   
 
 $row = 0;
  $col = 0;
  $num = 0;
  while ($mainpage_featured_products = tep_db_fetch_array($mainpage_featured_query)) {

    $num ++;
  
    if ($num == 1) {
    new contentBoxHeading($info_box_contents, tep_href_link(FILENAME_FEATURED_PRODUCTS));
    }    

  $pf->loadProduct($mainpage_featured_products['products_id'],$languages_id);
        $products_price_2bb = $pf->getPriceStringShort();
    $info_box_contents[$row][$col] = array('align' => 'center',
                                           'params' => 'class="smallText" width="33%" valign="top"',
                                           'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $mainpage_featured_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $mainpage_featured_products['products_image'], $mainpage_featured_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $mainpage_featured_products['products_id']) . '">' . $mainpage_featured_products['products_name'] . '</a><br>' . $products_price_2bb);

    $col ++;
    if ($col > 2) {
      $col = 0;
      $row ++;
    }
  }

  new contentBox($info_box_contents, true, true);
if (TEMPLATE_INCLUDE_CONTENT_FOOTER =='true'){ 
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                                'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                              );
  new contentBoxFooter($info_box_contents);
  }
} 
?>
<!-- featured_products_eof //-->
