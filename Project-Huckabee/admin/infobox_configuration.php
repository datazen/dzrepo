<?php
/*
  $Id: infobox_configuration.php,v 1.1.1.1 2004/03/04 23:38:38 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
// Alias function for Store configuration values in the Administration Tool
  function tep_cfg_select_option_infobox($select_array, $key_value, $key = '') {
    $string = '';

    for ($i=0, $n=sizeof($select_array); $i<$n; $i++) {
      $name = ((tep_not_null($key)) ? 'infobox_' .$key  : 'infobox_display');
      $string .= '<input type="radio" name="' . $name . '" value="' . $select_array[$i] . '"';
      if ($key_value == $select_array[$i]) $string .= ' CHECKED';
      $string .= '> ' . $select_array[$i];
    }
    return $string;
  }

  function tep_get_templates() {
    $templates_query = tep_db_query("select template_id, template_name from " . TABLE_TEMPLATE . " order by template_id");
    while ($template = tep_db_fetch_array($templates_query)) {
      // does the folder exists
      if ( ! file_exists(DIR_FS_TEMPLATES . $template['template_name'] . '/')) continue;
      // does the main_page.tpl.php file exist
      if ( ! file_exists(DIR_FS_TEMPLATES . $template['template_name'] . '/main_page.tpl.php')) continue;
      
      $template_array[] = array('id' => $template['template_id'],
                                'text' => $template['template_name']);
    }

    return $template_array;
  }

// find gID
if (isset($_GET['gID'])) {
      $gID = $_GET['gID'] ;
    }else if (isset($_POST['gID'])){
      $gID = $_POST['gID'] ;
    } else {
     $gID = '' ;
    }


// find cID
if (isset($_GET['cID'])) {
      $cID = $_GET['cID'] ;
    }else if (isset($_POST['cID'])){
      $cID = $_POST['cID'] ;
    } else {
     $cID = 1 ;
    }

 // begin action
 if (isset($_GET['action'])) {
       $action = $_GET['action'] ;
     }else if (isset($_POST['action'])){
       $action = $_POST['action'] ;
     } else {
      $action = '' ;
    }


  $template_array = tep_get_templates();
  $template_selected = '';
  $template_name = '';
  
  // get the selected template name
  for ($i=0, $n=sizeof($template_array); $i<$n; $i++) {
    if ($gID == $template_array[$i]['id']) {
      $template_name = $template_array[$i]['text'];
      $template_selected = $template_array[$i]['id'];
    }
  }
  // define the template name constant
  define('TEMPLATE_NAME', $template_name);
  
  // check to see if the selected template is ATS or BTS
  if (file_exists(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/template.php')) {
    $template_type = 'ATS';
    include DIR_FS_TEMPLATES . TEMPLATE_NAME . '/template.php';
  } else {
    $template_type = 'BTS';
  }
  // define the default location for the boxes
  if ( ! defined('DIR_FS_TEMPLATE_BOXES')) {
    if (file_exists(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes/')) {
      define('DIR_FS_TEMPLATE_BOXES', DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes/');
    } else {
      define('DIR_FS_TEMPLATE_BOXES', DIR_FS_TEMPLATES . 'default/boxes/');
    }
  }
  define('DIR_FS_TEMPLATE_DEFAULT_BOXES', DIR_FS_TEMPLATES . 'default/boxes/');
  

if (tep_not_null($action)) {
  switch ($action) {

    case 'add_language_files': //create language heading entries.
      // pull infobox info
      $infobox_query = tep_db_query("select box_heading, infobox_id from " . TABLE_INFOBOX_CONFIGURATION);
      // $infobox = tep_db_fetch_array($infobox_query);
      while ($infobox = tep_db_fetch_array($infobox_query)) {
        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $language_id = $languages[$i]['id'];
          $box_heading = tep_db_prepare_input($infobox['box_heading']);
          $infobox_id = $infobox['infobox_id'];
          $box_heading = str_replace("'", "\\'", $box_heading);
          tep_db_query("insert into " . TABLE_INFOBOX_HEADING . " (infobox_id, languages_id, box_heading) values ('" . $infobox_id . "', '" . $language_id . "', '" . $box_heading . "') ");
        }
      }
      
      tep_redirect(tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $gID . '&cID=' . $cID));
      break;

    case 'position_update': //set the status of a template active buttons.
      if ( ($_GET['flag'] == 'up') || ($_GET['flag'] == 'down') ) {
        if ($gID) {
          tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set  location = '" . $_GET['loc'] .  "', last_modified = now() where location = '" . $_GET['loc1'] . "' and display_in_column = '" . $_GET['col'] . "'");
          tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set  location = '" . $_GET['loc1'] .  "', last_modified = now() where infobox_id = '" . (int)$_GET['iID'] . "' and display_in_column = '" . $_GET['col'] . "'");
        }
      }
      
      tep_redirect(tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $gID . '&cID=' . $iID));
      break;

    case 'fixweight':
      global  $infobox_id, $cID;
      $rightpos = 'right';
      $leftpos = 'left';

      $result_query = tep_db_query("select infobox_id from " . TABLE_INFOBOX_CONFIGURATION . " where display_in_column = '" . $leftpos . "' and template_id = '" . (int)$gID . "' order by location");

      $sorted_position = 0;
      while ($result = tep_db_fetch_array($result_query)) {
        $sorted_position++;
        tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set location = '" . $sorted_position . "' where infobox_id = '" . (int)$result['infobox_id'] . "' and template_id = '" . (int)$gID . "'");
      }

      $result_query = tep_db_query("select infobox_id from " . TABLE_INFOBOX_CONFIGURATION . " where display_in_column = '" . $rightpos . "' and template_id = '" . (int)$gID . "' order by location");

      $sorted_position = 0;
      while ($result = tep_db_fetch_array($result_query)) {
        $sorted_position++;
        tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set location = '" . $sorted_position . "' where infobox_id = '" . (int)$result['infobox_id'] . "' and template_id = '" . (int)$gID . "'");
      }

      tep_redirect(tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $gID . '&cID=' . $cID));
      break;

    case 'setflag': //set the status of a news item.
      if ($_GET['cID'] && $_GET['flag'] == 'no') {
        tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set infobox_display = '" . $_GET['flag'] . "' where infobox_id = '" . (int)$cID . "'");
      } elseif ($_GET['cID'] && $_GET['flag'] == 'yes') {
        // find the name of the box
        $sql = "SELECT infobox_file_name
                FROM " . TABLE_INFOBOX_CONFIGURATION . "
                WHERE infobox_id = " . (int)$cID;
        $infobox_query = tep_db_query($sql);
        $infobox = tep_db_fetch_array($infobox_query);
        
        $found_box = false;
        if (file_exists(DIR_FS_TEMPLATE_BOXES . $infobox['infobox_file_name'])) {
          $found_box = true;
        } elseif (DIR_FS_TEMPLATE_DEFAULT_BOXES != DIR_FS_TEMPLATE_BOXES && file_exists(DIR_FS_TEMPLATE_DEFAULT_BOXES . $infobox['infobox_file_name'])) {
          $found_box = true;
        }
        if ($found_box) {
          tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set infobox_display = '" . $_GET['flag'] . "' where infobox_id = '" . (int)$cID . "'");
        }
      }

      tep_redirect(tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $gID . '&cID=' . $cID));
      break;

    case 'setflagcolumn': //set the status of a news item.
      if ( ($_GET['flag'] == 'left') || ($_GET['flag'] == 'right') ) {
        if ($_GET['cID']) {
          tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set display_in_column = '" . $_GET['flag'] . "' where infobox_id = '" . (int)$cID . "' and template_id = '" . $gID . "'");
        }
      }

      tep_redirect(tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $gID . '&cID=' . $cID));
      break;

    case 'save':
      $configuration_active = tep_db_prepare_input($_POST['infobox_active']);
      $infobox_file_name = tep_db_prepare_input($_POST['infobox_file_name']);
      $infobox_define = tep_db_prepare_input($_POST['infobox_define']);
      $display_in_column = tep_db_prepare_input($_POST['infobox_column']);
      $location = tep_db_prepare_input($_POST['location']);
      $box_template = tep_db_prepare_input($_POST['box_template']);
      $box_heading_font_color = tep_db_prepare_input($_POST['hexval']);
      $hexval = tep_db_prepare_input($_POST['hexval']);

      $error = false;
      if ($infobox_file_name == "") {
        $error = true;
        $messageStack->add('infobox_error_save', JS_INFO_BOX_FILENAME);
      }

      if ($infobox_define == "" || $infobox_define == "BOX_HEADING_????") {
        $error = true;
        $messageStack->add('infobox_error_save', JS_BOX_HEADING);
      }
      
      if ($hexval == "") {
        $error = true;
        $messageStack->add('infobox_error_save', JS_BOX_COLOR);
      }

      if ($error == 'true') {
        //do nothing and display error
      } else {
        $cID = tep_db_prepare_input($_GET['cID']);

        tep_db_query("update " . TABLE_INFOBOX_CONFIGURATION . " set infobox_file_name = '" . tep_db_input($infobox_file_name) . "',
               infobox_define = '" . tep_db_input($infobox_define) . "',
               location = '" . tep_db_input($location) . "',
               display_in_column = '" . tep_db_input($display_in_column) . "',
               infobox_display = '" . tep_db_input($configuration_active) . "',
               box_template = '" . tep_db_input($box_template) . "',
               box_heading_font_color = '" . tep_db_input($box_heading_font_color) . "',
               last_modified = now() where infobox_id = '" . (int)$cID . "' and template_id = '" . $gID . "'");

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $language_id = $languages[$i]['id'];
          $box_heading = tep_db_prepare_input($_POST['box_heading'][$language_id]);

          tep_db_query("update " . TABLE_INFOBOX_HEADING . " set box_heading = '" . tep_db_input($box_heading) . "',
                  languages_id = '" . tep_db_input($language_id) . "'
              where infobox_id = '" . (int)$cID . "'and languages_id = '" . (int)$language_id . "'");
        }
        tep_redirect(tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $gID . '&cID=' . $cID));
       }
       break;

    case 'insert':

      $infobox_file_name = tep_db_prepare_input($_POST['infobox_file_name']);
      $infobox_define = tep_db_prepare_input($_POST['infobox_define']);
      $configuration_active = tep_db_prepare_input($_POST['infobox_active']);
      $display_in_column = tep_db_prepare_input($_POST['infobox_column']);
      $location = tep_db_prepare_input($_POST['location']);
      $box_template = tep_db_prepare_input($_POST['box_template']);
      $box_heading_font_color = tep_db_prepare_input($_POST['hexval']);
      $template_id = tep_db_prepare_input($gID);
      $box_heading = tep_db_prepare_input('box_heading');
      
      $error = false;
      if ($infobox_file_name == "") {
        $error = true;
        $messageStack->add('infobox_error_insert', JS_INFO_BOX_FILENAME);
      }
      if ($box_heading == "") {
        $error = true;
        $messageStack->add('infobox_error_insert', JS_INFO_BOX_HEADING);
      }
      if ($infobox_define == "" || $infobox_define == "BOX_HEADING_????") {
        $error = true;
        $messageStack->add('infobox_error_insert', JS_BOX_HEADING);
      }
      if ($box_heading_font_color == "") {
        $error = true;
        $messageStack->add('infobox_error_insert', JS_BOX_COLOR);
      }

      if ($error == false) {
        tep_db_query("insert into " . TABLE_INFOBOX_CONFIGURATION . " (template_id, infobox_file_name, infobox_display, infobox_define, display_in_column, location, box_template, box_heading_font_color) values ('" . tep_db_input($template_id) . "', '" . tep_db_input($infobox_file_name) . "', '" . tep_db_input($configuration_active) . "', '" . tep_db_input($infobox_define) . "', '" . tep_db_input($display_in_column) . "', '" . tep_db_input($location) . "',  '" . tep_db_input($box_template) . "', '" . tep_db_input($box_heading_font_color) . "')");
        $infobox_id1 = tep_db_insert_id() ;
        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $language_id = $languages[$i]['id'];
          $box_heading = tep_db_prepare_input($_POST['box_heading'][$language_id]);
          tep_db_query("insert into " . TABLE_INFOBOX_HEADING . " (infobox_id, languages_id, box_heading) values ('" . $infobox_id1 . "', '" . $language_id . "', '" . tep_db_input($box_heading) . "')");
        }
        tep_redirect(tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $gID));
      }
      break;

    case 'deleteconfirm':
      $cIDa = tep_db_prepare_input($_GET['cID']);;

      tep_db_query("delete from " . TABLE_INFOBOX_CONFIGURATION . " where infobox_id = '" . tep_db_input($cIDa) . "'");
      tep_db_query("delete from " . TABLE_INFOBOX_HEADING . " where infobox_id = '" . tep_db_input($cIDa) . "'");

        tep_redirect(tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $gID));
      break;
  }  // end switch ($action)
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
<script language="javascript" src="includes/javascript/color_picker/picker.js"></script>
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=450,height=300%,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
<script language="javascript"><!--
var i=0;

function resize() {
  if (navigator.appName == 'Netscape') i = 40;
  window.resizeTo(document.images[0].width + 30, document.images[0].height + 60 - i);
}


<!-- Begin
function showColor(val) {
document.infobox_configuration.hexval.value = val;
}
// End -->

//--></script>
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
      <h1 class="page-header"><?php echo HEADING_TITLE ?></h1>
      <!-- end page-header -->
      
    <!-- begin panel -->
    <div class="panel panel-inverse"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><?php echo tep_draw_form('gID', FILENAME_INFOBOX_CONFIGURATION, '', 'get');
            if (isset($_GET[tep_session_name()])) {
              echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
            } ?>
            <td class="pageHeading"><?php echo $template_name . ' &raquo; ' . HEADING_TITLE; ?></td>
            <td class="pageHeading" align="center"><?php echo tep_draw_pull_down_menu('gID', $template_array,  $template_selected,
  'onChange="this.form.submit();"'); ?></td></form>
          </tr>
        </table></td>
      </tr>
<?php
  if ($messageStack->size('infobox_error_save') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('infobox_error_save'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
   <?php
  if ($messageStack->size('infobox_error_insert') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('infobox_error_insert'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr><td>
<?php
  echo '<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'action=fixweight&gID=' . $gID) . '">' . tep_image_button('button_update_box_positions.gif', IMAGE_BUTTON_UPDATE_BOX_POSITIONS) . '</a>';
  echo '<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $gID . '&action=new') . '">' . tep_image_button('button_module_install.gif', IMAGE_NEW_INFOBOX) . '</a>' ;
?>
      </td></tr>
      <tr>
         <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
             <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_INFOBOX_TITLE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_INFOBOX_FILE_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ACTIVE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_COLUMN; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SORT_ORDER; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $count_left_active = 0;
  $count_right_active = 0;
  $totInf_boxes = 1;
  
  $configuration_query = tep_db_query("select *  from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . $_GET['gID'] . "' order by display_in_column, location");
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    // if the dispaly is active, check to see if it exists
    if ($configuration['infobox_display'] == 'yes') {
      $found_box = false;
      if (file_exists(DIR_FS_TEMPLATE_BOXES . $configuration['infobox_file_name'])) {
        $found_box = true;
      } elseif (DIR_FS_TEMPLATE_DEFAULT_BOXES != DIR_FS_TEMPLATE_BOXES && file_exists(DIR_FS_TEMPLATE_DEFAULT_BOXES . $configuration['infobox_file_name'])) {
        $found_box = true;
      }
      // updated to only turn off missing infoboxes not ALL boxes - maestro
      if (!$found_box) {
        tep_db_query("UPDATE " . TABLE_INFOBOX_CONFIGURATION . "
                      SET infobox_display = 'no'
                      WHERE infobox_id = " . $configuration['infobox_id'] . " AND template_id = " . $gID);
        $configuration['infobox_display'] = 'no';
      }
    }
    
    $languages = tep_get_languages();
    $configuration_query1 = tep_db_query("select box_heading  from " . TABLE_INFOBOX_HEADING . " where infobox_id = '" . $configuration['infobox_id'] . "' and languages_id = '" . $languages_id . "'");
    while($configuration1 = tep_db_fetch_array($configuration_query1)) {
      $box_heading = $configuration1['box_heading'];
    }
    
    $totInf_boxes++;
    $cfgfname = $configuration['infobox_file_name'];
    $cfgloc = $configuration['location'];
    $cfgValue = $configuration['infobox_display'];
    $cfgcol = $configuration['display_in_column'];
    $cfgtemp = $configuration['box_template'];
    $cfgkey = $configuration['infobox_define'];
    $cfgfont = $configuration['box_heading_font_color'];

    $location1 = $cfgloc - 1;
    $location3 = $cfgloc + 1;

    $res = tep_db_query("select infobox_id from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . $gID . "' and location = ' $location1 '  AND display_in_column ='$cfgcol'");
    $con1 =  tep_db_fetch_array($res);

    $res2 = tep_db_query("select infobox_id from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . $gID . "' and location = ' $location3 '  AND display_in_column ='$cfgcol'");
    $con2 =  tep_db_fetch_array($res2);

    if (($cfgcol == 'left') && ($cfgValue != 'no')) {
      $count_left_active++;
    } elseif (($cfgcol == 'right') && ($cfgValue != 'no')) {
      $count_right_active++;
    }
    if (!isset($infobox_list1)) {
      $infobox_list1 = '';
    }
    $infobox_list1 .= $configuration['infobox_file_name']. ",";

    if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $configuration['infobox_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
      $cfg_extra_query = tep_db_query("select * from " . TABLE_INFOBOX_CONFIGURATION . " where infobox_id = '" . (int)$configuration['infobox_id'] . "'");
      $cfg_extra = tep_db_fetch_array($cfg_extra_query);

      $cInfo_array = array_merge($configuration, $cfg_extra);
      $cInfo = new objectInfo($cInfo_array);
    }


    if ( (isset($cInfo) && is_object($cInfo)) && ($configuration['infobox_id'] == $cInfo->infobox_id) ) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->infobox_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' .   tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $configuration['infobox_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $box_heading; ?></td>
                <td class="dataTableContent"><?php echo  $cfgfname; ?></td>
                <td class="dataTableContent" align="center">
<?php
    if ($configuration['infobox_display'] == 'yes') {
      echo tep_image(DIR_WS_IMAGES . 'accept.png', IMAGE_ICON_STATUS_GREEN, 16, 16) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'action=setflag&flag=no&gID=' . $_GET['gID'] . '&cID=' . $configuration['infobox_id'] ) . '">' . tep_image(DIR_WS_IMAGES . 'cancel-off.png', IMAGE_ICON_STATUS_RED_LIGHT, 16, 16) . '</a>';
    } else {
      echo '<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'action=setflag&flag=yes&gID=' . $_GET['gID'] . '&cID=' . $configuration['infobox_id']) . '">' . tep_image(DIR_WS_IMAGES . 'accept-off.png', IMAGE_ICON_STATUS_GREEN_LIGHT, 16, 16) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'cancel.png', IMAGE_ICON_STATUS_RED, 16, 16);
    }
?>
                </td>
                <td class="dataTableContent" align="center"><?php
    if ($configuration['display_in_column'] == 'left') {
      echo tep_image(DIR_WS_IMAGES . 'icon_infobox_green.gif', IMAGE_INFOBOX_STATUS_GREEN, 14, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'action=setflagcolumn&flag=right&gID=' . $_GET['gID'] . '&cID=' . $configuration['infobox_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_infobox_red_light.gif', IMAGE_INFOBOX_STATUS_RED_LIGHT, 14, 10) . '</a>';
    } else {
      echo '<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'action=setflagcolumn&flag=left&gID=' . $_GET['gID'] . '&cID=' . $configuration['infobox_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_infobox_green_light.gif', IMAGE_INFOBOX_STATUS_GREEN_LIGHT, 14, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_infobox_red.gif', IMAGE_INFOBOX_STATUS_RED, 14, 10);
    }
?>
                </td>
                <td height="30" align="center" valign="middle">
<?php
    if ($con1) {
      echo '<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'action=position_update&loc1=' .$location1.'&loc=' .$cfgloc.'&flag=up&col=' . $cfgcol . '&iID=' .$configuration['infobox_id'] . '&gID=' . $_GET['gID']) . '">' . tep_image(DIR_WS_IMAGES . 'up.gif', IMAGE_ICON_STATUS_UP_LIGHT, 11, 14) . '</a>&nbsp;&nbsp;';
    }
    if ($con2) {
      echo '<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'action=position_update&loc1=' .$location3.'&loc=' .$cfgloc.'&flag=down&col=' . $cfgcol . '&iID=' .$configuration['infobox_id'] . '&gID=' . $_GET['gID']) . '">' . tep_image(DIR_WS_IMAGES . 'down.gif', IMAGE_ICON_STATUS_DOWN_LIGHT, 11, 14) . '</a>';
    }
?>
</td>

                <td class="dataTableContent" align="right"><?php if ( (isset($cInfo)) && (is_object($cInfo)) && ($configuration['infobox_id'] == $cInfo->infobox_id) ) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $configuration['infobox_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();


  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_INFOBOX . '</b>');
      $contents = array('form' => tep_draw_form('infobox_configuration', FILENAME_INFOBOX_CONFIGURATION, tep_get_all_get_params(array('action')) . 'action=insert', 'post', 'onSubmit="return check_form_info();"') . tep_draw_hidden_field('cID', (isset($cInfo->infobox_id)? $cInfo->infobox_id : 1) ));
      $contents[] = array('text' => TEXT_INFO_HEADING_NEW_INFOBOX);
      $contents[] = array('text' => '<font color="red">' . TEXT_NOTE_REQUIRED. '</font>');
      // info boxes can be in one of two directories, read both in
      $unique_boxes = array();
      if ($handle1 = opendir(DIR_FS_TEMPLATE_BOXES)) {
        while (($filename = readdir($handle1)) !== false) {
          if ( ! isset($unique_boxes[$filename]) ) {
            $unique_boxes[$filename] = $filename;
          }
        }
      }
      closedir($handle1);
      if (DIR_FS_TEMPLATE_DEFAULT_BOXES != DIR_FS_TEMPLATE_BOXES) {
        if ($handle1 = opendir(DIR_FS_TEMPLATE_DEFAULT_BOXES)) {
          while (($filename = readdir($handle1)) !== false) {
            if ( ! isset($unique_boxes[$filename]) ) {
              $unique_boxes[$filename] = $filename;
            }
          }
        }
        closedir($handle1);
      }
      
      // build the array of unknown boxes
      foreach ($unique_boxes as $file1) {
        if (stristr($infobox_list1.".,..", $file1) == FALSE){
          $dirs_array1[] = array('id' => $file1,
                                 'text' => $file1);
        }
      }
      if ((isset($cInfo)) && (is_object($cInfo->box_heading_font_color)) ){
        $cInfo->box_heading_font_color ;
      } else {
        $font_color =  '#FFFFFF';
      }
      $contents[] = array('text' => '<br><b>' . TEXT_HEADING_FILENAME . '</b><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=filename&amp;templatename=' . substr( DIR_FS_TEMPLATE_BOXES, strlen(DIR_WS_CATALOG) + 2)) . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> <br>' . tep_draw_pull_down_menu('infobox_file_name',$dirs_array1,'', "style='width:150;'", 'true') );
      $contents[] = array('text' => '<br><b>' . TEXT_HEADING_HEADING . '</b><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=heading') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>' );
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $contents[] = array('text' => ' ' .   $languages[$i]['name']);
        $contents[] = array('text' => ' ' . tep_draw_input_field('box_heading[' . $languages[$i]['id'] . ']','Example','size="30"','true') );
      }
      $contents[] = array('text' => '<br><b>' . TEXT_HEADING_WHICH_TEMPLATE . '</b> <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=template') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a><br> ' . tep_draw_input_field('box_template','infobox','size="25"','true') );
      $contents[] = array('text' => '<br><b>' . TEXT_HEADING_WHICH_COL . '</b><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=column') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a><br> ' . tep_cfg_select_option_infobox(array('left', 'right'),'left','column') . '</b>');
      $contents[] = array('text' => '<br><b>' . TEXT_HEADING_WHAT_POS . '</b><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=position') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a><br> ' . tep_draw_input_field('location',$totInf_boxes,'size=3') );
      $contents[] = array('text' => '<br><b>' . TEXT_HEADING_DEFINE_KEY . '</b> <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=define') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a><br> ' . tep_draw_input_field('infobox_define','BOX_HEADING_EXAMPLE','size="35"','true') );
      $contents[] = array('text' => '<br><b>' . TEXT_HEADING_SET_ACTIVE . '</b> <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=active') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a><br> ' . tep_cfg_select_option_infobox(array('yes', 'no'),'yes','active') . '</b>' );
      $contents[] = array('text' => '<br><b>' . TEXT_HEADING_FONT_COLOR . '</b> <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=color') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> <br>' );
      $contents[] = array('text' => tep_draw_input_field('hexval', $font_color ,'size="10"','true') );
      $contents[] = array('text' => '<span style= "{ border-width: medium ; border-style:solid ; border-color: #000000 ;  padding: .5em ; background-color: ' . $font_color . '; border-color: #000000 ; border-width: thin; padding: .5em ;} ">' .  TEXT_HEADING_FONT_COLOR . '</span><br> ');
      $contents[] = array('text' => '<a href="javascript:TCP.popup(document.forms[\'infobox_configuration\'].elements[\'hexval\'], 1)">' . tep_image_button('button_cancel.gif', TEXT_HEADING_FONT_CHANGE_COLOR));
      $contents[] = array('text' => '<br><center><a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $_GET['gID']) . '">' . tep_image_submit('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_module_install.gif', IMAGE_INSERT) . '</center>');
      break;
      
    case 'edit':
      $count_left_active = 0;
      $count_right_active = 0;
      $totInf_boxes = 1;
      $infobox_list1 = '';

      $configuration_query = tep_db_query("select *  from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . $gID . "' order by display_in_column, location");
      while ($configuration = tep_db_fetch_array($configuration_query)) {
        $languages = tep_get_languages();
        $configuration_query1 = tep_db_query("select box_heading  from " . TABLE_INFOBOX_HEADING . " where infobox_id = '" . $configuration['infobox_id'] . "' and languages_id = '" . $languages_id . "'");
        while($configuration1 = tep_db_fetch_array($configuration_query1)) {
          $box_heading = $configuration1['box_heading'];
        }
        $totInf_boxes++;
        $cfgloc = $configuration['location'];
        $cfgValue = $configuration['infobox_display'];
        $cfgcol = $configuration['display_in_column'];
        $cfgtemp = $configuration['box_template'];
        $cfgkey = $configuration['infobox_define'];
        $cfgfont = $configuration['box_heading_font_color'];
        $cfgfile = $configuration['infobox_file_name'];
        
        $location1 = $cfgloc - 1;
        $location3 = $cfgloc + 1;

        $res = tep_db_query("select infobox_id from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . $gID . "' and location = ' $location1 '  AND display_in_column ='$cfgcol'");
        $con1 =  tep_db_fetch_array($res);

        $res2 = tep_db_query("select infobox_id from " . TABLE_INFOBOX_CONFIGURATION . " where template_id = '" . $gID . "' and location = ' $location3 '  AND display_in_column ='$cfgcol'");
        $con2 =  tep_db_fetch_array($res2);

        if (($cfgcol == 'left') && ($cfgValue != 'no')) {
          $count_left_active++;
        } elseif (($cfgcol == 'right') && ($cfgValue != 'no')) { 
          $count_right_active++;
        }
        $infobox_list1 .= $configuration['infobox_file_name']. ",";

        if ((!isset($cID) || (isset($cID) && ($cID == $configuration['infobox_id']))) && (substr($action, 0, 3) != 'new')) {
          $cfg_extra_query = tep_db_query("select * from " . TABLE_INFOBOX_CONFIGURATION . " where infobox_id = '" . (int)$configuration['infobox_id'] . "'");
          $cfg_extra = tep_db_fetch_array($cfg_extra_query);

          $cInfo_array = array_merge($configuration, $cfg_extra);
          $cInfo = new objectInfo($cInfo_array);
        }
      }

      $heading[] = array('text' => TEXT_INFO_HEADING_UPDATE_INFOBOX . ' -- <font color="' . $cInfo->box_heading_font_color . '">' . $cInfo->box_heading );
      $contents = array('form' => tep_draw_form('infobox_configuration', FILENAME_INFOBOX_CONFIGURATION, tep_get_all_get_params(array('action')) . 'action=save', 'post', 'onSubmit="return check_form_info();"') . tep_draw_hidden_field('cID', $cInfo->infobox_id));
      $contents[] = array('text' => '<font color="red">' . TEXT_NOTE_REQUIRED .'</font>');
      $contents[] = array('text' => '<br><b>' . TEXT_HEADING_FILENAME . '</b><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=filename') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a><br> ' . $cInfo->infobox_file_name . tep_draw_hidden_field('infobox_file_name',$cInfo->infobox_file_name,'size="20"','true'));
      $contents[] = array('text' => '<br><b>' . TEXT_HEADING_HEADING . '</b><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=heading') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>' );
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $contents[] = array('text' => '' . $languages[$i]['name'] .'<br> '. tep_draw_input_field('box_heading[' . $languages[$i]['id'] . ']', tep_get_box_heading($cInfo->infobox_id, $languages[$i]['id']),'size="25"','true'));
      }
      $contents[] = array('text' => '<br><b>' . TEXT_HEADING_WHICH_TEMPLATE . '</b><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=template') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a><br> ' . tep_draw_input_field('box_template',$cInfo->box_template,'size="25"','true'));
      $contents[] = array('text' => '<br><b>' . TEXT_HEADING_WHICH_COL . '</b><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=column') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a><br> ' . tep_cfg_select_option_infobox(array('left', 'right'),$cInfo->display_in_column,'column') . '</b><br>');
      $contents[] = array('text' => '<br><b>' . TEXT_HEADING_WHAT_POS . '</b><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=position') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a><br> ' . tep_draw_input_field('location',$cInfo->location,'size=3'));
      $contents[] = array('text' => '<br><b>' . TEXT_HEADING_DEFINE_KEY .  '</b><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=define') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a><br> ' . tep_draw_input_field('infobox_define',$cInfo->infobox_define,'size="35"','true'));
      $contents[] = array('text' => '<br><b>' . TEXT_HEADING_SET_ACTIVE . '</b><a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=active') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a><br> ' . tep_cfg_select_option_infobox(array('yes', 'no'),$cInfo->infobox_display,'active') . '</b><br><br>');
      $contents[] = array('text' => '<br><b>' . TEXT_HEADING_FONT_COLOR . '</b> <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_INFOBOX_HELP,'action=color') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> <br>');
      $contents[] = array('text' =>  tep_draw_input_field('hexval',$cInfo->box_heading_font_color,'size="10"','false'));
      $contents[] = array('text' => '<span style= "{ border-width: medium ; border-style:solid ; border-color: #000000 ;  padding: .5em ; background-color: ' . $cInfo->box_heading_font_color . '; border-color: #000000 ; border-width: thin; padding: .5em ;} ">' .  TEXT_HEADING_FONT_COLOR . '</span><br>');
      $contents[] = array('text' => '<a href="javascript:TCP.popup(document.forms[\'infobox_configuration\'].elements[\'hexval\'], 1)">' . tep_image_button('button_cancel.gif', TEXT_HEADING_FONT_CHANGE_COLOR));

      $contents[] = array('align' => 'center', 'text' =>'<br><a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->infobox_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_update.gif', IMAGE_UPDATE));
      break;
      
    case 'delete':
      $heading[] = array('text' => TEXT_INFO_HEADING_DELETE_INFOBOX . ' -- ' . $cInfo->box_heading );
      $contents = array('form' => tep_draw_form('configuration', FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $_GET['cID'] . '&action=deleteconfirm'));
      $contents[] = array('align' => 'center', 'text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $_GET['cID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'  . tep_image_submit('button_module_remove.gif', IMAGE_DELETE));
      break;

    default:
      if (is_object($cInfo)) {
        //Get tdefault template info or selected template
        $templates_query = tep_db_query("select template_id, template_name from " . TABLE_TEMPLATE . " where template_id = " . $gID);
        $template = tep_db_fetch_array($templates_query);
        
        $dir_template_boxes_show = '';
        if (file_exists(DIR_FS_TEMPLATE_BOXES . $cInfo_array['infobox_file_name'])) {
          $dir_template_boxes_show = DIR_FS_TEMPLATE_BOXES;
        } elseif (DIR_FS_TEMPLATE_DEFAULT_BOXES != DIR_FS_TEMPLATE_BOXES && file_exists(DIR_FS_TEMPLATE_DEFAULT_BOXES . $cInfo_array['infobox_file_name'])) {
          $dir_template_boxes_show = DIR_FS_TEMPLATE_DEFAULT_BOXES;
        }
        
        // info boxes can be in one of two directories, read both in
        $unique_boxes = array();
        if ($handle1 = opendir(DIR_FS_TEMPLATE_BOXES)) {
          while (($filename = readdir($handle1)) !== false) {
            if ( ! isset($unique_boxes[$filename]) ) {
              $unique_boxes[$filename] = $filename;
            }
          }
        }
        closedir($handle1);
        if (DIR_FS_TEMPLATE_DEFAULT_BOXES != DIR_FS_TEMPLATE_BOXES) {
          if ($handle1 = opendir(DIR_FS_TEMPLATE_DEFAULT_BOXES)) {
            while (($filename = readdir($handle1)) !== false) {
              if ( ! isset($unique_boxes[$filename]) ) {
                $unique_boxes[$filename] = $filename;
              }
            }
          }
          closedir($handle1);
        }
        // build the array of unknown boxes
        $avail_boxes = 0;
        foreach ($unique_boxes as $file1) {
          if (stristr($infobox_list1.".,..", $file1) == FALSE){
            $avail_boxes ++;
          }
        }
        
        if ($dir_template_boxes_show != '') {
          $heading[] = array('text' => '<b>' . $cInfo->infobox_file_name . '</b>');
          $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $_GET['gID'] . '&amp;cID=' . $cInfo->infobox_id . '&amp;action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a>' .
                                         '<a href="' . tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $_GET['gID'] . '&amp;cID=' . $cInfo->infobox_id . '&amp;action=delete') . '">' . tep_image_button('button_module_remove.gif', IMAGE_DELETE) . '</a>' );
        } else {
          $contents[] = array('align' => 'center', 'text' => infobox_error1);
        }
        $contents[] = array('align' => 'center', 'text' => '<br>' . TABLE_HEADING_BOX_DIRECTORY . '<b>' . $dir_template_boxes_show . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<br>' . TEXT_INFO_DATE_ADDED . ' <b>' . tep_date_short($cInfo->date_added) . '</b>');
        if (tep_not_null($cInfo->last_modified)) $contents[] = array('align' => 'center','text' => TEXT_INFO_LAST_MODIFIED . ' <b>' . tep_date_short($cInfo->last_modified) . '</b>');

          if ($cInfo->include_column_left == 'yes' && $count_left_active == 0) {
               $contents[] = array('align' => 'center','text' => '<font color="red" size="4"><?php echo infobox_error2  ; ?></font>');
          }
          if ($cInfo->include_column_right == 'yes' && $count_right_active == 0) {
               $contents[] = array('align' => 'center','text' => '<font color="red" size="4"><?php echo infobox_error3  ; ?></font>');
          }
        $contents[] = array('align' => 'center','text' => TEXT_INFO_MESSAGE_COUNT_1 . '<b>' . $count_left_active . '</b>' . TEXT_INFO_MESSAGE_COUNT_2 . '<b>' . $count_right_active . '</b>' . INFOBOX_ACTIVE_BOXES);
      }
      break;
  }

  if ( (tep_not_null($contents)) ) {
    echo '           <td width="25%" valign="top" align="center">' . "\n";
    $box = new box;
    echo $box->infoBox($heading,$contents);
    echo '            </td> ' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></div></div>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>