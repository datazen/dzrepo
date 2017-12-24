<?php
/*
  $Id: featured.php,v 1.2 2008/05/30 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
function tep_set_featured_status($featured_id, $status) {
  if ($status == '1') {
    return tep_db_query("update " . TABLE_FEATURED . " set status = '1', expires_date = NULL, date_status_change = NULL where featured_id = '" . $featured_id . "'");
  } elseif ($status == '0') {
    return tep_db_query("update " . TABLE_FEATURED . " set status = '0', date_status_change = now() where featured_id = '" . $featured_id . "'");
  } else {
    return -1;
  }
}
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
$action = isset($_GET['action']) ? $_GET['action'] : '';
switch ($action) {
  case 'setflag':
    tep_set_featured_status($_GET['id'], $_GET['flag']);
    tep_redirect(tep_href_link(FILENAME_FEATURED, '', 'NONSSL'));
    break;
  case 'insert':
    $expires_date = '';
    if ($_POST['day'] && $_POST['month'] && $_POST['year']) {
      $expires_date = $_POST['year'];
      $expires_date .= (strlen($_POST['month']) == 1) ? '0' . $_POST['month'] : $_POST['month'];
      $expires_date .= (strlen($_POST['day']) == 1) ? '0' . $_POST['day'] : $_POST['day'];
    }
    tep_db_query("insert into " . TABLE_FEATURED . " (products_id, featured_date_added, expires_date, status) values ('" . $_POST['products_id'] . "', now(), '" . $expires_date . "', '1')");
    tep_redirect(tep_href_link(FILENAME_FEATURED, 'page=' . $_GET['page']));
    break;
  case 'update':
    $expires_date = '';
    if ($_POST['day'] && $_POST['month'] && $_POST['year']) {
      $expires_date = $_POST['year'];
      $expires_date .= (strlen($_POST['month']) == 1) ? '0' . $_POST['month'] : $_POST['month'];
      $expires_date .= (strlen($_POST['day']) == 1) ? '0' . $_POST['day'] : $_POST['day'];
    }
    tep_db_query("update " . TABLE_FEATURED . " set featured_last_modified = now(), expires_date = '" . $expires_date . "' where featured_id = '" . $_POST['featured_id'] . "'");
    tep_redirect(tep_href_link(FILENAME_FEATURED, 'page=' . $_GET['page'] . '&sID=' . $featured_id));
    break;
  case 'deleteconfirm':
    $featured_id = tep_db_prepare_input($_GET['sID']);
    tep_db_query("delete from " . TABLE_FEATURED . " where featured_id = '" . tep_db_input($featured_id) . "'");
    tep_redirect(tep_href_link(FILENAME_FEATURED, 'page=' . $_GET['page']));
    break;
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
<?php
if ( isset($_GET['action']) &&  (($_GET['action'] == 'new') || ($_GET['action'] == 'edit')) ) {
  ?>
  <link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">
  <script language="JavaScript" src="includes/javascript/calendarcode.js"></script>
  <?php
}
?>
</head>
<body>  <div id="popupcalendar" class="text"></div>
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
    <div class="panel panel-inverse"><table border="0" width="100%" cellspacing="0" cellpadding="0" >
      <?php
      if ( isset($_GET['action']) &&  (($_GET['action'] == 'new') || ($_GET['action'] == 'edit')) ) {
        $form_action = 'insert';
        if ( ($_GET['action'] == 'edit') && ($_GET['sID']) ) {
          $form_action = 'update';
          $product_query = tep_db_query("SELECT p.products_id, pd.products_name, s.expires_date 
                                           from " . TABLE_PRODUCTS . " p, 
                                                " . TABLE_PRODUCTS_DESCRIPTION . " pd, 
                                                " . TABLE_FEATURED . " s 
                                         WHERE p.products_id = pd.products_id 
                                           and pd.language_id = '" . $languages_id . "' 
                                           and p.products_id = s.products_id 
                                           and s.featured_id = '" . $_GET['sID'] . "' 
                                         ORDER BY pd.products_name");
          $product = tep_db_fetch_array($product_query);
          $sInfo = new objectInfo($product);
        } else {
          $sInfo = new objectInfo(array());
          // create an array of featured products, which will be excluded from the pull down menu of products
          // (when creating a new featured product)
          $featured_array = array();
          $featured_query = tep_db_query("select p.products_id from " . TABLE_PRODUCTS . " p, " . TABLE_FEATURED . " s where s.products_id = p.products_id");
          while ($featured = tep_db_fetch_array($featured_query)) {
            $featured_array[] = $featured['products_id'];
          }
        }
        ?>
        <tr><form name="new_feature" <?php echo 'action="' . tep_href_link(FILENAME_FEATURED, tep_get_all_get_params(array('action', 'info', 'sID')) . 'action=' . $form_action, 'NONSSL') . '"'; ?> method="post" class="form-inline"><?php if ($form_action == 'update') echo tep_draw_hidden_field('featured_id', $_GET['sID']); ?>
          <td><br><table border="0" cellspacing="0" cellpadding="2" class="data-table">
            <tr>
              <td class="main"><?php echo TEXT_FEATURED_PRODUCT; ?>&nbsp;</td>
              <td class="main"><?php echo (isset($sInfo->products_name) ? $sInfo->products_name : tep_draw_products_pull_down('products_id', 'class="form-control"', $featured_array)); 
                if (isset($sInfo->products_price)) {
                  echo tep_draw_hidden_field('products_price', $sInfo->products_price); 
                } else {
                  echo tep_draw_hidden_field('products_price', 0); 
                } ?>
              </td>
            </tr>
            <tr>
              <td class="main"><?php echo TEXT_FEATURED_EXPIRES_DATE; ?>&nbsp;</td>
              <td class="main"><table><tr><td><?php 
                if (isset($sInfo->expires_date)) {
                  echo tep_draw_input_field('day', substr($sInfo->expires_date, 8, 2), 'size="2" maxlength="2" class="form-control"') . ' </td><td>' . tep_draw_input_field('month', substr($sInfo->expires_date, 5, 2), 'size="2" maxlength="2" class="form-control"') . ' </td><td>' . tep_draw_input_field('year', substr($sInfo->expires_date, 0, 4), 'size="4" maxlength="4" class="form-control"');
                } else {
                  echo tep_draw_input_field('day', '', 'size="2" maxlength="2" class="form-control"') . ' </td><td>' . tep_draw_input_field('month', '', 'size="2" maxlength="2" class="form-control"') . ' </td><td>' . tep_draw_input_field('year', '', 'size="4" maxlength="4" class="form-control"');
                }
                ?></td><td> &nbsp; <a class="so-BtnLink" href="javascript:calClick();return false;" onmouseover="calSwapImg('BTN_date', 'img_Date_OVER',true);" onmouseout="calSwapImg('BTN_date', 'img_Date_UP',true);" onclick="calSwapImg('BTN_date', 'img_Date_DOWN');showCalendar('new_feature','dteWhen','BTN_date');return false;"><?php echo tep_image(DIR_WS_IMAGES . 'cal_date_up.gif', 'Calendar', '22', '17', 'align="absmiddle" name="BTN_date"'); ?></a>
                </td></tr></table>
              </td>
            </tr>
        <tr>
          <td align="center" colspan="2"><br><?php echo '<a href="' . tep_href_link(FILENAME_FEATURED, 'page=' . $_GET['page'] . '&sID=' . (isset($_GET['sID']) ? $_GET['sID'] : 0)) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . (($form_action == 'insert') ? tep_image_submit('button_insert.gif', IMAGE_INSERT) : tep_image_submit('button_update.gif', IMAGE_UPDATE)); ?>
          </td></form>
        </tr>
          </table></td>
        </tr>
        <?php
      } else {
        ?>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
                <tr class="dataTableHeadingRow">
                  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                  <td class="dataTableHeadingContent" align="right">&nbsp;</td>
                  <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                  <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                </tr>
                <?php
                $featured_query_raw = "SELECT p.products_id, pd.products_name, s.featured_id, s.featured_date_added, s.featured_last_modified, s.expires_date, s.date_status_change, s.status 
                                         from " . TABLE_PRODUCTS . " p, 
                                              " . TABLE_FEATURED . " s, 
                                              " . TABLE_PRODUCTS_DESCRIPTION . " pd 
                                       WHERE p.products_id = pd.products_id 
                                         and pd.language_id = '" . $languages_id . "' 
                                         and p.products_id = s.products_id 
                                       ORDER BY pd.products_name";
                $featured_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $featured_query_raw, $featured_query_numrows);
                $featured_query = tep_db_query($featured_query_raw);
                while ($featured = tep_db_fetch_array($featured_query)) {
                  if ( ((!isset($_GET['sID'])) || ($_GET['sID'] == $featured['featured_id'])) && (!isset($sInfo)) ) {
                    $products_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . $featured['products_id'] . "'");
                    $products = tep_db_fetch_array($products_query);
                    $sInfo_array = array_merge((array)$featured, (array)$products);
                    $sInfo = new objectInfo($sInfo_array);
                  }
                  if ( isset($sInfo) && (is_object($sInfo)) && ($featured['featured_id'] == $sInfo->featured_id) ) {
                    echo '<tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_FEATURED, 'page=' . $_GET['page'] . '&sID=' . $sInfo->featured_id . '&action=edit') . '\'">' . "\n";
                  } else {
                    echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_FEATURED, 'page=' . $_GET['page'] . '&sID=' . $featured['featured_id']) . '\'">' . "\n";
                  }
                  ?>
                  <td  class="dataTableContent"><?php echo $featured['products_name']; ?></td>
                  <td  class="dataTableContent" align="right">&nbsp;</td>
                  <td  class="dataTableContent" align="right">
                    <?php
                    if ($featured['status'] == '1') {
                      echo tep_image(DIR_WS_IMAGES . 'accept.png', IMAGE_ICON_STATUS_GREEN, 16, 16) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FEATURED, 'action=setflag&flag=0&id=' . $featured['featured_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'cancel-off.png', IMAGE_ICON_STATUS_RED_LIGHT, 16, 16) . '</a>';
                    } else {
                      echo '<a href="' . tep_href_link(FILENAME_FEATURED, 'action=setflag&flag=1&id=' . $featured['featured_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'accept-off.png', IMAGE_ICON_STATUS_GREEN_LIGHT, 16, 16) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'cancel.png', IMAGE_ICON_STATUS_RED, 16, 16);
                    }
                    ?>
                  </td>
                  <td class="dataTableContent" align="right"><?php if ( isset($sInfo) && (is_object($sInfo)) && ($featured['featured_id'] == $sInfo->featured_id) ) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_FEATURED, 'page=' . $_GET['page'] . '&sID=' . $featured['featured_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                  </tr>
                  <?php
                }
                ?>
                </table>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
                <tr>
                  <td colspan="4"><table border="0" width="100%" cellpadding="0"cellspacing="2">
                    <tr>
                      <td class="smallText" valign="top"><?php echo $featured_split->display_count($featured_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_FEATURED); ?></td>
                      <td class="smallText" align="right"><?php echo $featured_split->display_links($featured_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                    </tr>
                    <?php
                    if (!isset($_GET['action'])) {
                      ?>
                      <tr>
                        <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_FEATURED, 'page=' . $_GET['page'] . '&action=new') . '">' . tep_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?>&nbsp;</td>
                      </tr>
                      <?php
                    }
                    ?>
                  </table></td>
                </tr>
              </table></td>     <td>&nbsp;</td>
              <?php
              $heading = array();
              $contents = array();
              switch ($action) {
                case 'delete':
                  $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_FEATURED . '</b>');
                  $contents = array('form' => tep_draw_form('featured', FILENAME_FEATURED, 'page=' . $_GET['page'] . '&sID=' . $sInfo->featured_id . '&action=deleteconfirm'));
                  $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
                  $contents[] = array('text' => '<br><b>' . $sInfo->products_name . '</b>');
                  $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_FEATURED, 'page=' . $_GET['page'] . '&sID=' . $sInfo->featured_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_delete.gif', IMAGE_DELETE));
                  break;
                default:
                  if (isset($sInfo) && is_object($sInfo)) {
                    $heading[] = array('text' => '<b>' . $sInfo->products_name . '</b>');
                    $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_FEATURED, 'page=' . $_GET['page'] . '&sID=' . $sInfo->featured_id . '&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a><a href="' . tep_href_link(FILENAME_FEATURED, 'page=' . $_GET['page'] . '&sID=' . $sInfo->featured_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
                    $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' <b>' . tep_date_short($sInfo->featured_date_added) . '</b>');
                    $contents[] = array('text' => '' . TEXT_INFO_LAST_MODIFIED . ' <b>' . tep_date_short($sInfo->featured_last_modified) . '</b>');
                    $contents[] = array('align' => 'center', 'text' => '<br>' . tep_info_image($sInfo->products_image, $sInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT));
                    $contents[] = array('text' => '<br>' . TEXT_INFO_EXPIRES_DATE . ' <b>' . tep_date_short($sInfo->expires_date) . '</b>');
                    $contents[] = array('text' => '' . TEXT_INFO_STATUS_CHANGE . ' <b>' . tep_date_short($sInfo->date_status_change) . '</b>');
                  }
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
        <?php
      }
      // RCI code start
      echo $cre_RCI->get('featured', 'bottom');
      // RCI code eof
      ?>
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
