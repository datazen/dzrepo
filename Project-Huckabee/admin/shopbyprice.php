<?php
/*
  $Id: shopbyprice.php,v 1.1.1.1 2004/03/04 23:39:00 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!defined('MODULE_SHOPBYPRICE_RANGES')) define('MODULE_SHOPBYPRICE_RANGES', 0);
  if (!defined('MODULE_SHOPBYPRICE_RANGE')) define('MODULE_SHOPBYPRICE_RANGE', '');
  
  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  $error_message = '';
  if (!isset($_GET['oID'])) {
    $oid = 1;
  } else {
    $oid = $_GET['oID'];
  }
  
 // define('MODULE_SHOPBYPRICE_RANGES', $sbp_ranges);
  if ($action == 'save') {
    if ($oid == 1) {
      $sbp_ranges = tep_db_prepare_input($_POST['sbp_ranges']);
      if (is_numeric($sbp_ranges)) {
        tep_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . (int)$sbp_ranges . '" where configuration_key = "MODULE_SHOPBYPRICE_RANGES" ');
        tep_redirect(tep_href_link(FILENAME_SHOPBYPRICE, tep_get_all_get_params(array('action'))));
        
      } else {
        $error_message .= TEXT_EDIT_ERROR_RANGES;
      }
      tep_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . $_POST['configuration_value'] . '" where configuration_key = "MODULE_SHOPBYPRICE_OVER" ');

      if ($error_message != '') {
        $action = 'edit';
      }
    } else {
      $sbp_input_array = $_POST['sbp_range'];
      $sbp_array[0] = tep_db_prepare_input($sbp_input_array[0]);
      for ($i = 1, $ii = MODULE_SHOPBYPRICE_RANGES; $i < $ii; $i++) {
        $sbp_array[$i] = tep_db_prepare_input($sbp_input_array[$i]);
        if (! is_numeric($sbp_array[$i])) {
          $error_message .= TEXT_EDIT_ERROR_NUMERIC;
        } elseif ($sbp_array[$i] <= $sbp_array[$i - 1]) {
          $error_message .= TEXT_EDIT_ERROR_RANGE;
        }
      }
      if ($error_message == '') {
        $serial_array = serialize($sbp_array);
        $text = tep_db_input($serial_array);
        tep_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . $text . '" where configuration_key = "MODULE_SHOPBYPRICE_RANGE" ');
        tep_redirect(tep_href_link(FILENAME_SHOPBYPRICE, tep_get_all_get_params(array('action'))));
        
      } else {
        $action = 'edit';
      }
    }
  }

  $sbp_array = unserialize(MODULE_SHOPBYPRICE_RANGE);
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
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_OPTIONS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  if ($oid == 1) {
    echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=1&action=edit') . '\'">' . "\n";
  } else {
    echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=1') . '\'">' . "\n";
  }
?>
                <td class="dataTableContent"><?php echo TEXT_INFO_OPTION_1; ?></td>
                <td class="dataTableContent" align="right"><?php if ($oid == 1) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=1') . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  if ($oid == 2) {
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=2&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=2') . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo TEXT_INFO_OPTION_2; ?></td>
                <td class="dataTableContent" align="right"><?php if ($oid == 2) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=2') . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
            </table></td> <td>&nbsp;</td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'edit':
      if ($oid == 1) {
        $heading[] = array('text' => '<b>' . TEXT_EDIT_HEADING_OPTIONS . '</b>');
        $contents = array('form' => tep_draw_form('sbp_options', FILENAME_SHOPBYPRICE, 'oID=1&action=save'));
        if ($error_message != '') {
          $contents[] = array('text' => '<font color="red">' . $error_message . '</font>');
        }
        $contents[] = array('text' => TEXT_EDIT_OPTIONS_INTRO);
        $contents[] = array('text' => '<br>' . TEXT_INFO_RANGES . '<br>' . tep_draw_input_field('sbp_ranges', MODULE_SHOPBYPRICE_RANGES));
        $contents[] = array('text' => '<br>' . TEXT_INFO_OVER . '<br>' . tep_cfg_select_option(array('True', 'False'),MODULE_SHOPBYPRICE_OVER));
        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=1') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_update.gif', IMAGE_UPDATE));
      } elseif (MODULE_SHOPBYPRICE_RANGES > 0) {
        $heading[] = array('text' => '<b>' . TEXT_EDIT_HEADING_RANGE . '</b>');
        $contents = array('form' => tep_draw_form('sbp_options', FILENAME_SHOPBYPRICE, 'oID=2&action=save'));
        if ($error_message != '') {
          $contents[] = array('text' => '<font color="red">' . $error_message . '</font>');
        }
        $contents[] = array('text' => TEXT_EDIT_RANGE_INTRO);
        $contents[] = array('text' => '<br>' . TEXT_INFO_UNDER . tep_draw_input_field('sbp_range[0]', $sbp_array[0]));
        for ($i = 1, $ii = MODULE_SHOPBYPRICE_RANGES; $i < $ii; $i++) {
          $contents[] = array('text' => '<br>' . TEXT_INFO_TO . tep_draw_input_field('sbp_range['.$i.']', $sbp_array[$i]));
        }
        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=1') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_update.gif', IMAGE_UPDATE));
      }
      break;
    default:

      if ($oid == 1) {
        $heading[] = array('text' => '<b>' . TEXT_EDIT_HEADING_OPTIONS . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=1&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_RANGES . ' <b>' . MODULE_SHOPBYPRICE_RANGES . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_OVER . ' <b>' . MODULE_SHOPBYPRICE_OVER . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_OPTIONS_DESCRIPTION . '<br><b>' . $tcInfo->tax_class_description . '</b>');
      } else {
        $heading[] = array('text' => '<b>' . TEXT_EDIT_HEADING_RANGE . '</b>');
        if (! MODULE_SHOPBYPRICE_RANGES > 0) {
          $contents[] = array('align' => 'center', 'text' => TEXT_INFO_ZERORANGE);
        } elseif (MODULE_SHOPBYPRICE_RANGE == '') {
          $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=2&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a>');
          $contents[] = array('align' => 'center', 'text' => TEXT_INFO_NORANGE);
        } else {
          $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=2&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a>');
          $contents[] = array('text' => '<br>' . TEXT_INFO_UNDER . '<b>' . $sbp_array[0] . '</b>');
          for ($i = 1, $ii = count($sbp_array); $i < $ii; $i++) {
            $contents[] = array('text' => '<br>' . TEXT_INFO_TO . '<b>' . $sbp_array[$i] . '</b>');
          }
          if (MODULE_SHOPBYPRICE_OVER) {
            $contents[] = array('text' => '<br><b>' . $sbp_array[$i-1] . '</b>' . TEXT_INFO_ABOVE);
          }
        }
      }
      break;
  }
  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
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
