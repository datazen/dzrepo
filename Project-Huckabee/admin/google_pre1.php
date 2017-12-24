<?php
/*
  $Id: google_pre.php,v 1.1.1.1  zip1 Exp $
  http://www.oscommerce.com
   google Data Feeder!

  Copyright (c) 2002 - 2005 Calvin K

  Released under the GNU General Public License
*/
  require('includes/application_top.php');

  include(DIR_WS_LANGUAGES . $language . '/google_pre.php');

  function tep_get_parent_categories(&$categories, $categories_id) {
    $parent_categories_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$categories_id . "'");
    while ($parent_categories = tep_db_fetch_array($parent_categories_query)) {
      if ($parent_categories['parent_id'] == 0) return true;
      $categories[sizeof($categories)] = $parent_categories['parent_id'];
      if ($parent_categories['parent_id'] != $categories_id) {
        tep_get_parent_categories($categories, $parent_categories['parent_id']);
      }
    }
  }

  function tep_get_product_path($products_id) {
    $cPath = '';

    $category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "' and p.products_status = '1' and p.products_id = p2c.products_id limit 1");
    if (tep_db_num_rows($category_query)) {
      $category = tep_db_fetch_array($category_query);

      $categories = array();
      tep_get_parent_categories($categories, $category['categories_id']);

      $categories = array_reverse($categories);

      $cPath = implode(' > ', $categories);

      if (tep_not_null($cPath)) $cPath .= ' > ';
      $cPath .= $category['categories_id'];
    }

    return $cPath;
  }


 function tep_get_categories_name($cats_id) {
 $cats_name = '';
   $categories_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd where cd.categories_id = '" . (int)$cats_id . "' ");
    while ($categories = tep_db_fetch_array($categories_query)) {
      $cats_name = $categories['categories_name'];
      }

 return $cats_name;

 }


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? 'https:' : 'http:'); ?>//ajax.googleapis.com/ajax/libs/jquery/<?php echo JQUERY_VERSION; ?>/jquery.min.js"></script>
<script type="text/javascript">
  if (typeof jQuery == 'undefined') {
    //alert('You are running a local copy of jQuery!');
    document.write(unescape("%3Cscript src='includes/javascript/jquery-1.6.2.min.js' type='text/javascript'%3E%3C/script%3E"));
  }
</script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<link rel="stylesheet" type="text/css" href="includes/headernavmenu.css">
<script type="text/javascript" src="includes/menu.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
  <!-- left_navigation //-->
  <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
  <!-- left_navigation_eof //-->
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="menuBoxHeading">
                         <tr>
                     <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                       <tr>
                         <td class="pageHeading"><?php echo HEADING_TITLE ; ?></td>
                        </tr>
                        <tr>
                        <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                       </tr>
                     </table></td>
                   </tr>
    <tr>
        <td>
<!--   Run category build        run feed build
 -->   <?php echo TEXT_OUTPUT_20;?>     </td>
      </tr>
   <tr>
        <td>
 <?php

//if ($action == categories)
//  Start TIMER
//  -----------
$stimer = explode( ' ', microtime() );
$stimer = $stimer[1] + $stimer[0];


$sql = "
SELECT
products_id AS id,
categories_id AS prodCatID
FROM
products_to_categories
";

$result=tep_db_query( $sql )or die( $FunctionName . ": SQL error " . mysql_error() . "| sql = " . htmlentities($sql) );

$loop_counter = 0;

while( $row = tep_db_fetch_array( $result ) )
{
$PROD_tree=tep_get_product_path($row['id']);

$catPath = explode(' > ', $PROD_tree);
$value1 = ' ';
foreach ($catPath as $value) {
   $value1 .= tep_get_categories_name($value) . ' > ';
   $value3 = rtrim($value1, "> ");
   $value2 = ltrim($value3, "&nbsp;");
   }
   $cat_query = tep_db_query("select * from  " . TABLE_DATA_CAT . " where cat_id = '" . $row['prodCatID'] ."' ");
if (tep_db_num_rows($cat_query) < '1') {

 $sql_data_array13 = array('cat_id'  => $row['prodCatID'],
                           'cat_tree'  => $value2);

 tep_db_perform(TABLE_DATA_CAT, $sql_data_array13, 'insert' );
 }
if (tep_db_num_rows($cat_query) < '1') {
     }
}

//  End TIMER
//  ---------
$etimer = explode( ' ', microtime() );
$etimer = $etimer[1] + $etimer[0];
echo '<p style="margin:auto; text-align:center">';
printf( TEXT_INFO_TIMER . " <b>%f</b> "  . TEXT_INFO_SECOND, ($etimer-$stimer) );
echo '</p>';
//  ---------
echo '<br> &nbsp;' . TEXT_INFO_DONE . tep_draw_form('run', FILENAME_GOOGLE_ADMIN, 'action=run', 'post', '');
echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;' . tep_image_submit('button_return.gif', TEXT_INFO_DONE) . '</form>';

?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
