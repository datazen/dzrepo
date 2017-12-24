<?php
// Ultimate SEO URL's Independant Validation
function tep_validate_seo_urls() {
  global $_GET, $request_type;
  ($request_type == 'NONSSL' ? $fwr_server_port = HTTP_SERVER : $fwr_server_port = HTTPS_SERVER);
  $querystring = str_replace('?', '&', $_SERVER['REQUEST_URI']);
  if (isset($_GET['products_id'])) $get_id_vars = str_replace(strstr($_GET['products_id'], '{'), '', $_GET['products_id']); // Remove attributes
  $qs_parts = explode('&', $querystring); // explode the querystring into an array
  $count = count($qs_parts);
  $added_uri = array();
  $remove_nasties = array('%3C', '%3E', '<', '>', ':/', 'http', 'HTTP'); // We do tep_sanitize_string() later anyway
  for ( $i=0; $i<$count; $i++ ) { // We don't want to introduce vulnerability do we :)
    switch($qs_parts[$i]) {
      case(false !== strpos($qs_parts[$i], '.html')):
          $core = urldecode($qs_parts[$i]); // Found the path
          ((strstr($core, '{') !== false) ? ($core = str_replace(strstr($core, '{'), '', $core) . '.html') : NULL); // Remove attributes
        break;
      case(false !== strpos($qs_parts[$i], 'osCsid')):
          $seo_sid = $qs_parts[$i]; // Found the osCsid
        break;
      default:
        $added_uri[] = (urldecode(str_replace($remove_nasties, '', $qs_parts[$i]))); // Found the additional querystring (e.g. &page=3&sort=2a from split_page_results)
    }
  }
  $do_validation = true; // Set to false later if it is not an seo url so that other .html files pass through unhindered
  // If -x- is in the querystring create var $querytype which is a string which explodes into an array on -
  (strpos($_SERVER['REQUEST_URI'], '-p-') ? ($querytype = 'filename_product_info-products_id=' . $get_id_vars) :
  (strpos($_SERVER['REQUEST_URI'], '-c-') ? ($querytype = 'filename_default-cPath=' . $_GET['cPath']) :
  (strpos($_SERVER['REQUEST_URI'], '-m-') ? ($querytype = 'filename_default-manufacturers_id=' . $_GET['manufacturers_id']) :
  (strpos($_SERVER['REQUEST_URI'], '-pi-') ? ($querytype = 'filename_popup_image-pID=' . $_GET['pID']) :
  (strpos($_SERVER['REQUEST_URI'], '-t-') ? ($querytype = 'filename_articles-tPath=' . $_GET['tPath']) :
  (strpos($_SERVER['REQUEST_URI'], '-a-') ? ($querytype = 'filename_article_info-articles_id=' . $_GET['articles_id']) :
  (strpos($_SERVER['REQUEST_URI'], '-pr-') ? ($querytype = 'filename_product_reviews-products_id=' . $get_id_vars) :
  (strpos($_SERVER['REQUEST_URI'], '-pri-') ? ($querytype = 'filename_product_reviews_info-products_id=' . $get_id_vars) :
  (strpos($_SERVER['REQUEST_URI'], '-prw-') ? ($querytype = 'filename_product_reviews_write-products_id=' . $get_id_vars) :
  (strpos($_SERVER['REQUEST_URI'], '-i-') ? ($querytype = 'filename_information-info_id=' . $_GET['info_id']) :
  (strpos($_SERVER['REQUEST_URI'], '-links-') ? ($querytype = 'filename_links-lPath=' . $_GET['lPath']) :
  $do_validation = false )))))))))) );

  if (true === $do_validation) { // It's an SEO URL so we will validate it
    $validate_array = explode('-', $querytype); // Gives e.g. $validate_array[0] = filename_default, $validate_array[1] = products_id=xx
    $linkreturned = tep_href_link(constant(strtoupper($validate_array[0])), $validate_array[1]); // Get a propper new SEO link
    // Rebuild the extra querystring
    ((strpos($linkreturned, '?') !== false) ? ($seperator = '&') : ($seperator = '?')); // Is there an osCsid on $linkreturned?
    $count = count($added_uri); // Count the extra querystring items
    for ($i=0; $i<$count; $i++) 
    if ($i == 0) 
      $linkreturned = $linkreturned . $seperator . tep_sanitize_string($added_uri[$i]); //add the first using seperator ? or &
    else 
      $linkreturned = $linkreturned . '&' . tep_sanitize_string($added_uri[$i]); // Just add "&" this time
    $linkreturnedstripped = str_replace( strstr($linkreturned, '?'), '', $linkreturned); // Strip osCsid to allow a match with $core
    $linktest = str_replace($fwr_server_port . DIR_WS_HTTP_CATALOG, '', $linkreturned); // Pair the url down to the querystring
    if (strpos($linktest, '-') === 0) { // If the link returned by seo.class.php has no text mysite.com/-c-xxx.html
      four_o_four_die(); // Product/category does not exist so die here with a 404
      exit;
    } else if ( $fwr_server_port . $core != $linkreturnedstripped ) { // Link looks bad so 301
      $linkreturned = str_replace('&amp;', '&', $linkreturned); // Just in case those sneaky W3C urls tried to throw in an &amp;
      header("HTTP/1.0 301 Moved Permanently"); // redirect to the good version
      header("Location: $linkreturned"); // 301 redirect
      exit;
    }
  } // We're not doing validation as the -p-, -c- etc was not found
}
function four_o_four_die() { // 404 then redirect doesn't work as Google records a 302 so we need to die here with a 404
  echo
    header("HTTP/1.0 404 Not Found") . 
    '<p align="left" style="font-size: large;">&nbsp;&nbsp;404 Page not found!</p>
    <div align="center" style="width: 100%; margin-top: 70px;">
    <div align="center" style="font-family: verdana; font-size: 0.8em; color: #818181; padding: 90px 10px 90px 10px; width: 60%; border: 1px solid #818181;">
    This product/category does not exist it may have been deleted.<p />
    To return to ' . STORE_NAME .
    '. Please click here <a href="' . tep_href_link(FILENAME_DEFAULT) . '" title="' . STORE_NAME . '">back to ' . STORE_NAME . '</a>
    </div></div>';
}
?>