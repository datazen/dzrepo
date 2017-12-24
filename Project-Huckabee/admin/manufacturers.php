<?php
/*
  $Id: manufacturers.php,v 1.2 2008/05/30 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$action = (isset($_GET['action']) ? $_GET['action'] : '');
if (tep_not_null($action)) {
  switch ($action) {
    case 'insert':
    case 'save':
      if (isset($_GET['mID'])) $manufacturers_id = tep_db_prepare_input($_GET['mID']);
      $manufacturers_name = tep_db_prepare_input($_POST['manufacturers_name']);
      $sql_data_array = array('manufacturers_name' => $manufacturers_name);
      if ($action == 'insert') {
        $insert_sql_data = array('date_added' => 'now()');
        $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
        tep_db_perform(TABLE_MANUFACTURERS, $sql_data_array);
        $manufacturers_id = tep_db_insert_id();
      } elseif ($action == 'save') {
        $update_sql_data = array('last_modified' => 'now()');
        $sql_data_array = array_merge($sql_data_array, $update_sql_data);
        tep_db_perform(TABLE_MANUFACTURERS, $sql_data_array, 'update', "manufacturers_id = '" . (int)$manufacturers_id . "'");
      }
      $manufacturers_image = new upload('manufacturers_image', DIR_FS_CATALOG_IMAGES);
      if (!empty($manufacturers_image->filename)) {
        tep_db_query("update " . TABLE_MANUFACTURERS . " set manufacturers_image = '" . $manufacturers_image->filename . "' where manufacturers_id = '" . (int)$manufacturers_id . "'");
      }
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $manufacturers_url_array = $_POST['manufacturers_url'];
        $manufacturers_htc_title_array = $_POST['manufacturers_htc_title_tag'];
        $manufacturers_htc_desc_array = $_POST['manufacturers_htc_desc_tag'];
        $manufacturers_htc_keywords_array = $_POST['manufacturers_htc_keywords_tag'];
        $language_id = $languages[$i]['id'];
        $sql_data_array = array('manufacturers_url' => tep_db_prepare_input($manufacturers_url_array[$language_id]),
                                'manufacturers_htc_title_tag' => tep_db_prepare_input($manufacturers_htc_title_array[$language_id]),
                                'manufacturers_htc_desc_tag' => tep_db_prepare_input($manufacturers_htc_desc_array[$language_id]),
                                'manufacturers_htc_keywords_tag' => tep_db_prepare_input($manufacturers_htc_keywords_array[$language_id])
                               );
        if ($action == 'insert') {
          $insert_sql_data = array('manufacturers_id' => $manufacturers_id,
                                   'languages_id' => $language_id);
          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
          tep_db_perform(TABLE_MANUFACTURERS_INFO, $sql_data_array);
        } elseif ($action == 'save') {
          tep_db_perform(TABLE_MANUFACTURERS_INFO, $sql_data_array, 'update', "manufacturers_id = '" . (int)$manufacturers_id . "' and languages_id = '" . (int)$language_id . "'");
        }
      }
      if (USE_CACHE == 'true') {
        tep_reset_cache_block('manufacturers');
      }
      tep_redirect(tep_href_link(FILENAME_MANUFACTURERS, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'mID=' . $manufacturers_id));
      break;
    case 'deleteconfirm':
      $manufacturers_id = tep_db_prepare_input($_GET['mID']);
      if (isset($_POST['delete_image']) && ($_POST['delete_image'] == 'on')) {
        $manufacturer_query = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$manufacturers_id . "'");
        $manufacturer = tep_db_fetch_array($manufacturer_query);
        $image_location = DIR_FS_DOCUMENT_ROOT . DIR_WS_CATALOG_IMAGES . $manufacturer['manufacturers_image'];
        if (file_exists($image_location)) @unlink($image_location);
      }
      tep_db_query("delete from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$manufacturers_id . "'");
      tep_db_query("delete from " . TABLE_MANUFACTURERS_INFO . " where manufacturers_id = '" . (int)$manufacturers_id . "'");
      if (isset($_POST['delete_products']) && ($_POST['delete_products'] == 'on')) {
        $products_query = tep_db_query("select products_id from " . TABLE_PRODUCTS . " where manufacturers_id = '" . (int)$manufacturers_id . "'");
        while ($products = tep_db_fetch_array($products_query)) {
          tep_remove_product($products['products_id']);
        }
      } else {
        tep_db_query("update " . TABLE_PRODUCTS . " set manufacturers_id = '' where manufacturers_id = '" . (int)$manufacturers_id . "'");
      }
      if (USE_CACHE == 'true') {
        tep_reset_cache_block('manufacturers');
      }
      tep_redirect(tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $_GET['page']));
      break;
  }
}
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="icon" type="image/png" href="favicon.ico" />
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? 'https:' : 'http:'); ?>//ajax.googleapis.com/ajax/libs/jquery/<?php echo JQUERY_VERSION; ?>/jquery.min.js"></script>


  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
                                                             <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
  <!-- ================== BEGIN BASE CSS STYLE ================== -->
  <link href="<?php echo (($request_type == 'SSL') ? 'https:' : 'http:'); ?>//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
  <link href="assets/css/animate.min.css" rel="stylesheet" />
  <link href="assets/css/style.min.css" rel="stylesheet" />
  <link href="assets/css/style-responsive.min.css" rel="stylesheet" />
  <link href="assets/css/theme/blue.css" rel="stylesheet" id="theme" />
  <!-- ================== END BASE CSS STYLE ================== -->
  
  <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
  <link href="assets/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />  
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Select/css/select.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
  <!-- ================== END PAGE LEVEL STYLE ================== -->
  <script language="javascript" src="includes/general.js"></script>
  <script type="text/javascript" src="includes/menu.js"></script>
<script language = "javascript">
  function check_form() {    
    if(document.manufacturers.manufacturers_name.value == '') {
      alert("<?php echo EMPTY_MANUFACTURERS_ERROR_TEXT;?>");
      return false;
    }  
    return true;    
  }
</script>
</head>
<body>
    <!-- begin #page-container -->
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed gradient-enabled">
    <!-- header //-->
    <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
    <!-- header_eof //-->
      
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
      
    <!-- begin #content -->
    <div id="content" class="content">
      <!-- begin breadcrumb -->
      <ol class="breadcrumb pull-right">
        <li>Create &nbsp; <a title="<?php echo BOX_MANUAL_ORDER_CREATE_ACCOUNT;?>" href="<?php echo tep_href_link(FILENAME_CREATE_ACCOUNT,'','SSL');?>" class="btn btn-xs btn-header"><i class="fa fa-user"></i><span class="label">+</span></a> <a title="<?php echo BOX_MANUAL_ORDER_CREATE_ORDER;?>" href="<?php echo tep_href_link(FILENAME_CREATE_ORDER,'','SSL');?>" class="btn btn-xs btn-header"><i class="fa fa-shopping-cart"></i><span class="label">+</span></a></li>
        <li>Search &nbsp; <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="ProductsPopover">Products</a> <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="CustomerPopover">Customers</a> <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="OrdersPopover">Orders</a> <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="PagesPopover">Pages</a></li>
      </ol>   
      
      <!-- end breadcrumb -->
      <!-- begin page-header -->
      <h1 class="page-header"><?php echo HEADING_TITLE; ?></h1>
      <!-- end page-header -->
      
    <!-- begin panel -->
    <div class="panel panel-inverse"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0" class="data-table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MANUFACTURERS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <?php
              $manufacturers_query_raw = "SELECT m.manufacturers_id, m.manufacturers_name, m.manufacturers_image, m.date_added, m.last_modified, 
                                                 mi.manufacturers_htc_title_tag, mi.manufacturers_htc_desc_tag, mi.manufacturers_htc_keywords_tag
                                          FROM " . TABLE_MANUFACTURERS . " m,
                                               " .  TABLE_MANUFACTURERS_INFO . " mi
                                          WHERE m.manufacturers_id = mi.manufacturers_id
                                            and mi.languages_id = " . $languages_id . "
                                          ORDER BY m.manufacturers_name";
              $manufacturers_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $manufacturers_query_raw, $manufacturers_query_numrows);
              $manufacturers_query = tep_db_query($manufacturers_query_raw);
              while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
                if ((!isset($_GET['mID']) || (isset($_GET['mID']) && ($_GET['mID'] == $manufacturers['manufacturers_id']))) && !isset($mInfo) && (substr($action, 0, 3) != 'new')) {
                  $manufacturer_products_query = tep_db_query("SELECT count(*) as products_count 
                                                                 from " . TABLE_PRODUCTS . " 
                                                               WHERE manufacturers_id = '" . (int)$manufacturers['manufacturers_id'] . "'");
                  $manufacturer_products = tep_db_fetch_array($manufacturer_products_query);
                  $mInfo_array = array_merge((array)$manufacturers, (array)$manufacturer_products);
                  $mInfo = new objectInfo($mInfo_array);
                }
                if (isset($mInfo) && is_object($mInfo) && ($manufacturers['manufacturers_id'] == $mInfo->manufacturers_id)) {
                  echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $manufacturers['manufacturers_id'] . '&action=edit') . '\'">' . "\n";
                } else {
                  echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $manufacturers['manufacturers_id']) . '\'">' . "\n";
                }
                ?>
                <td class="dataTableContent"><?php echo $manufacturers['manufacturers_name']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($mInfo) && is_object($mInfo) && ($manufacturers['manufacturers_id'] == $mInfo->manufacturers_id)) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png'); } else { echo '<a href="' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $manufacturers['manufacturers_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?></td>
                </tr>
                <?php
              }
              ?>
              </table>
              <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $manufacturers_split->display_count($manufacturers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS); ?></td>
                    <td class="smallText" align="right"><?php echo $manufacturers_split->display_links($manufacturers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
              <?php
              if (empty($action)) {
                ?>
                <tr>
                  <td align="right" colspan="2" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . (isset($mInfo->manufacturers_id) ? $mInfo->manufacturers_id : 0) . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>&nbsp;'; ?></td>
                </tr>
                <?php
              }
              ?>
            </table></td><td>&nbsp;</td>
            <?php
            $heading = array();
            $contents = array();
            switch ($action) {
              case 'new':
                $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_MANUFACTURER . '</b>');
                $contents = array('form' => tep_draw_form('manufacturers', FILENAME_MANUFACTURERS, 'action=insert', 'post', 'onSubmit="return check_form();" enctype="multipart/form-data"'));
                $contents[] = array('text' => TEXT_NEW_INTRO);
                $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_NAME . '<br>' . tep_draw_input_field('manufacturers_name').'*');
                $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_IMAGE . '<br>' . tep_draw_file_field('manufacturers_image'));
                $manufacturer_inputs_string = '';
                $languages = tep_get_languages();
                for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                  $manufacturer_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('manufacturers_url[' . $languages[$i]['id'] . ']');
                  $manufacturer_htc_title_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('manufacturers_htc_title_tag[' . $languages[$i]['id'] . ']');
                  $manufacturer_htc_desc_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('manufacturers_htc_desc_tag[' . $languages[$i]['id'] . ']');
                  $manufacturer_htc_keywords_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('manufacturers_htc_keywords_tag[' . $languages[$i]['id'] . ']');
                }
                $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_URL . $manufacturer_inputs_string);
                $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_HTC_TITLE . $manufacturer_htc_title_inputs_string);
                $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_HTC_DESC . $manufacturer_htc_desc_inputs_string);
                $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_HTC_KEYWORDS . $manufacturer_htc_keywords_inputs_string);
                $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $_GET['mID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_save.gif', IMAGE_SAVE));
                break;
              case 'edit':
                $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_MANUFACTURER . '</b>');
                $contents = array('form' => tep_draw_form('manufacturers', FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=save', 'post', 'onSubmit="return check_form();" enctype="multipart/form-data"'));
                $contents[] = array('text' => TEXT_EDIT_INTRO);
                $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_NAME . '<br>' . tep_draw_input_field('manufacturers_name', $mInfo->manufacturers_name).'*');
                $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_IMAGE . '<br>' . tep_draw_file_field('manufacturers_image') . '<br>' . $mInfo->manufacturers_image);
                $manufacturer_inputs_string = '';
                $languages = tep_get_languages();
                for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                  $manufacturer_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('manufacturers_url[' . $languages[$i]['id'] . ']', tep_get_manufacturer_url($mInfo->manufacturers_id, $languages[$i]['id']));
                  $manufacturer_htc_title_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('manufacturers_htc_title_tag[' . $languages[$i]['id'] . ']', tep_get_manufacturer_htc_title($mInfo->manufacturers_id, $languages[$i]['id']));
                  $manufacturer_htc_desc_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('manufacturers_htc_desc_tag[' . $languages[$i]['id'] . ']', tep_get_manufacturer_htc_desc($mInfo->manufacturers_id, $languages[$i]['id']));
                  $manufacturer_htc_keywords_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('manufacturers_htc_keywords_tag[' . $languages[$i]['id'] . ']', tep_get_manufacturer_htc_keywords($mInfo->manufacturers_id, $languages[$i]['id']));
                }
                $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_URL . $manufacturer_inputs_string);
                $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_HTC_TITLE . $manufacturer_htc_title_inputs_string);
                $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_HTC_DESC . $manufacturer_htc_desc_inputs_string);
                $contents[] = array('text' => '<br>' . TEXT_MANUFACTURERS_HTC_KEYWORDS . $manufacturer_htc_keywords_inputs_string);
                $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $mInfo->manufacturers_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_save.gif', IMAGE_SAVE));
                break;
              case 'delete':
                $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_MANUFACTURER . '</b>');
                $contents = array('form' => tep_draw_form('manufacturers', FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=deleteconfirm'));
                $contents[] = array('text' => TEXT_DELETE_INTRO);
                $contents[] = array('text' => '<br><b>' . $mInfo->manufacturers_name . '</b>');
                $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_image', '', true) . ' ' . TEXT_DELETE_IMAGE);
                if ($mInfo->products_count > 0) {
                  $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_products') . ' ' . TEXT_DELETE_PRODUCTS);
                  $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $mInfo->products_count));
                }
                $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $mInfo->manufacturers_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_delete.gif', IMAGE_DELETE));
                break;
              default:
                if (isset($mInfo) && is_object($mInfo)) {
                  $heading[] = array('text' => '<b>' . $mInfo->manufacturers_name . '</b>');
                  $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a><a href="' . tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $_GET['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
                  $returned_rci = $cre_RCI->get('manufacturers', 'msidebarbuttons');
                  $contents[] = array('align' => 'center', 'text' => $returned_rci);
                  $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' <b>' . tep_date_short($mInfo->date_added) . '</b>');
                  if (tep_not_null($mInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' <b>' . tep_date_short($mInfo->last_modified) . '</b>');
                  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_info_image($mInfo->manufacturers_image, $mInfo->manufacturers_name));
                  $contents[] = array('text' => '<br>' . TEXT_PRODUCTS . ' <b>' . $mInfo->products_count . '</b>');
                  $contents[] = array('text' => '<br>' . 'Header Tags Manufacturer Title' . $mInfo->manufacturers_htc_title_tag);
                  $contents[] = array('text' => '<br>' . 'Header Tags Manufacturer Description' . $mInfo->manufacturers_htc_desc_tag);
                  $contents[] = array('text' => '<br>' . 'Header Tags Manufacturer Keywords' . $mInfo->manufacturers_htc_keywords_tag);                }
                break;
            }
            if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
              echo '<td width="25%" valign="top">' . "\n";
              $box = new box;
              echo $box->infoBox($heading, $contents);
              echo '</td>' . "\n";
            }
            ?>
          </tr>
        </table></td>
      </tr>
    </table></div>
    </div>
    <!-- end panel -->
    </div>
    <!-- end #content -->
      
      <!-- begin #footer -->
  <?php
require(DIR_WS_INCLUDES . 'footer.php');
?>

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
