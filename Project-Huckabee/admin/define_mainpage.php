<?php
/*
  $Id: define_mainpage.php,v 1.1 2008/06/11 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top.php');
$_GET['filename'] = 'mainpage.php';
$action = (isset($_GET['action']) ? $_GET['action'] : '');
$lngdir = (isset($_GET['lngdir']) ? $_GET['lngdir'] : '');
switch ($action) {
  case 'save':
    if ( ($lngdir) && ($_GET['filename']) ) {
      //if ($_GET['filename'] == $language . '.php') {
      //  $file = DIR_FS_CATALOG_LANGUAGES . $_GET['filename'];
      //} else {
        $file = DIR_FS_CATALOG_LANGUAGES . $_GET['lngdir'] . '/' . $_GET['filename'];
      //}
      if (file_exists($file)) {
        if (file_exists(DIR_FS_CATALOG_LANGUAGES . $_GET['lngdir'] . '/bak' . $_GET['filename'])) {
          @unlink(DIR_FS_CATALOG_LANGUAGES . $_GET['lngdir'] . '/bak' . $_GET['filename']);
        }
        @rename($file, DIR_FS_CATALOG_LANGUAGES . $_GET['lngdir'] . '/bak' . $_GET['filename']);
        $new_file = fopen($file, 'w');
        $file_contents = stripslashes($_POST['file_contents']);
        fwrite($new_file, $file_contents, strlen($file_contents));
        fclose($new_file);
      }
      tep_redirect(tep_href_link(FILENAME_DEFINE_MAINPAGE, 'lngdir=' . $_GET['lngdir']));
    }
    break;
}
if (!$lngdir) $lngdir = $language;
$languages_array = array();
$languages = tep_get_languages();
$lng_exists = false;
for ($i=0; $i<sizeof($languages); $i++) {
  if ($languages[$i]['directory'] == $lngdir) $lng_exists = true;
  $languages_array[] = array('id' => $languages[$i]['directory'],
                             'text' => $languages[$i]['name']);
}
if (!$lng_exists) $_GET['lngdir'] = $language;
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
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<?php 
echo tep_load_html_editor();
echo tep_insert_html_editor('file_contents');
?>
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
      <h1 class="page-header"><?php echo BOX_CATALOG_DEFINE_MAINPAGE; ?></h1>
      <!-- end page-header -->
      
    <!-- begin panel -->
    <div class="panel panel-inverse"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <?php
          if ( ($lngdir) && ($_GET['filename']) ) {
            if ($_GET['filename'] == $language . '.php') {
              $file = DIR_FS_CATALOG_LANGUAGES . $_GET['filename'];
            } else {
              $file = DIR_FS_CATALOG_LANGUAGES . $lngdir . '/' . $_GET['filename'];
            }
            if (file_exists($file)) {
              $file_array = @file($file);
              $file_contents = @implode('', $file_array);
              $file_writeable = true;
              if (!is_writeable($file)) {
                $file_writeable = false;
                $messageStack->reset();
                $messageStack->add('mainpage', sprintf(ERROR_FILE_NOT_WRITEABLE, $file), 'error');
                if ($messageStack->size('mainpage') > 0) {
                  echo $messageStack->output('mainpage');
                }    
              }
              ?>
              <tr>
                <?php echo tep_draw_form('language', FILENAME_DEFINE_MAINPAGE, 'lngdir=' . $lngdir . '&filename=' . $_GET['filename'] . '&action=save'); ?>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main"><b><?php echo $_GET['filename']; ?></b></td>
                  </tr>
                  <tr>
                    <td class="main"><?php echo tep_draw_textarea_field('file_contents', 'soft', '80', '20', $file_contents,' style="width: 100%" mce_editable="true"', (($file_writeable) ? '' : 'readonly')); ?></td>
                  </tr>
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                  </tr>
                  <tr>
                    <td align="right">
                      <?php 
                      if ($file_writeable) { 
                        echo '<a href="' . tep_href_link(FILENAME_DEFINE_MAINPAGE, 'lngdir=' . $lngdir) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_save.gif', IMAGE_SAVE); 
                      } else { 
                        echo '<a href="' . tep_href_link(FILENAME_DEFINE_MAINPAGE, 'lngdir=' . $lngdir) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; 
                      } 
                      ?>
                    </td>
                  </tr>
                </table></td>
                </form>
              </tr>
              <?php
            } else {
              ?>
              <tr>
                <td class="main"><b><?php echo TEXT_FILE_DOES_NOT_EXIST; ?></b></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td><?php echo '<a href="' . tep_href_link(FILENAME_DEFINE_MAINPAGE, 'lngdir=' . $_GET['lngdir']) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
              </tr>
              <?php
            }
          } else {
            $filename = $lngdir . '.php';
            ?>
            <tr>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="smallText"><a href="<?php echo tep_href_link(FILENAME_DEFINE_MAINPAGE, 'lngdir=' . $_GET['lngdir'] . '&filename=' . $filename); ?>"><b><?php echo $filename; ?></b></a></td>
                  <?php
                  $dir = dir(DIR_FS_CATALOG_LANGUAGES . $lngdir);
                  $left = false;
                  if ($dir) {
                    $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
                    while ($file = $dir->read()) {
                      if (substr($file, strrpos($file, '.')) == $file_extension) {
                        echo '<td class="smallText"><a href="' . tep_href_link(FILENAME_DEFINE_MAINPAGE, 'lngdir=' . $lngdir . '&filename=' . $file) . '">' . $file . '</a></td>' . "\n";
                        if (!$left) {
                          echo '</tr>' . "\n" . '<tr>' . "\n";
                        }
                        $left = !$left;
                      }
                    }
                    $dir->close();
                  }
                  ?>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
              <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_FILE_MANAGER, 'current_path=' . DIR_FS_CATALOG_LANGUAGES . $_GET['lngdir']) . '">' . tep_image_button('button_file_manager.gif', IMAGE_FILE_MANAGER) . '</a>'; ?></td>
            </tr>
            <?php
          }
          ?>
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