<?php
/*
  $Id: cds_backup_restore.php,v 1.1.1.1 2007/01/11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');
$is_62 = (INSTALLED_VERSION_MAJOR == 6 && INSTALLED_VERSION_MINOR == 2) ? true : false;

$action = (isset($_GET['action']) ? $_GET['action'] : '');

if (tep_not_null($action)) {
  switch ($action) {
    case 'forget':
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'DB_LAST_RESTORE'");
      $messageStack->add_session('search', SUCCESS_LAST_RESTORE_CLEARED, 'success');
      tep_redirect(tep_href_link(FILENAME_CDS_BACKUP_RESTORE));
      break;

    case 'backupnow':
      tep_set_time_limit(0);
      $backup_file = 'cds_db_' . DB_DATABASE . '-' . date('YmdHis') . '.sql';
      $fp = fopen(DIR_FS_BACKUP . $backup_file, 'w');
      $schema = '# CRE Loaded, Commercial Open Source E-Commerce' . "\n" .
                '# http://www.creloaded.com' . "\n" .
                '#' . "\n" .
                '# CDS Database Backup For ' . STORE_NAME . "\n" .
                '# Copyright (c) ' . date('Y') . ' ' . STORE_OWNER . "\n" .
                '#' . "\n" .
                '# Database: ' . DB_DATABASE . "\n" .
                '# Database Server: ' . DB_SERVER . "\n" .
                '#' . "\n" .
                '# Backup Date: ' . date(PHP_DATE_TIME_FORMAT) . "\n\n";
                
      $schema .= 'delete from configuration where configuration_group_id = 480 or configuration_group_id = 481;' . "\n";
      fputs($fp, $schema);
            
      // dump only specific configuration data
      $rows_query = tep_db_query("select * from configuration where configuration_group_id = 480 or configuration_group_id = 481");
      while ($rows = tep_db_fetch_array($rows_query)) {     
        $schema = 'insert into configuration values (';
        reset($rows);
              while (list($key, $value) = each($rows)) {
          if (!isset($value)) {
            $schema .= 'NULL, ';
          } elseif (tep_not_null($value)) {
            $row = addslashes($value);
            $row = preg_replace("/\n#/", "\n".'\#', $row);
            $schema .= '\'' . $row . '\', ';
          } else {
            $schema .= '\'\', ';
          }
        }
        $schema = preg_replace('/, $/', '', $schema) . ');' . "\n\n";
        fputs($fp, $schema);
      }               
            
      $table_array = array('pages', 'pages_categories', 'pages_categories_description', 'pages_description', 'pages_to_categories');
      $tables_query = tep_db_query('show tables');
      while ($tables = tep_db_fetch_array($tables_query)) {
        list(,$table) = each($tables);
        if (!in_array($table, $table_array)) {
          continue;
        }
        $schema = 'drop table if exists ' . $table . ';' . "\n" .
                        'create table ' . $table . ' (' . "\n";

        $table_list = array();
        $fields_query = tep_db_query("show fields from " . $table);
        while ($fields = tep_db_fetch_array($fields_query)) {
          $table_list[] = $fields['Field'];
          $schema .= '  ' . $fields['Field'] . ' ' . $fields['Type'];
          if (strlen($fields['Default']) > 0) {
            if ($fields['Default'] == 'CURRENT_TIMESTAMP') {
              $schema .= ' default ' . $fields['Default'] . '';
            } else {
              $schema .= ' default \'' . $fields['Default'] . '\'';
            }
          }
          if ($fields['Null'] != 'YES') $schema .= ' not null';
          if (isset($fields['Extra'])) $schema .= ' ' . $fields['Extra'];
          $schema .= ',' . "\n";
        }
        $schema = preg_replace("/,\n$/", '', $schema);

        // add the keys
        $index = array();
        $keys_query = tep_db_query("show keys from " . $table);
        while ($keys = tep_db_fetch_array($keys_query)) {
          $kname = $keys['Key_name'];
          if (!isset($index[$kname])) {
            $index[$kname] = array('unique' => !$keys['Non_unique'],
                                              'columns' => array());
          }
          $index[$kname]['columns'][] = $keys['Column_name'];
        }
        while (list($kname, $info) = each($index)) {
          $schema .= ',' . "\n";
          $columns = implode($info['columns'], ', ');
          if ($kname == 'PRIMARY') {
            $schema .= '  PRIMARY KEY (' . $columns . ')';
          } elseif ($info['unique']) {
            $schema .= '  UNIQUE ' . $kname . ' (' . $columns . ')';
          } else {
            $schema .= '  KEY ' . $kname . ' (' . $columns . ')';
          }
        }
        $schema .= "\n" . ');' . "\n\n";
        fputs($fp, $schema);

        // dump the data
        $rows_query = tep_db_query("select " . implode(',', $table_list) . " from " . $table);
        while ($rows = tep_db_fetch_array($rows_query)) {
          $schema = 'insert into ' . $table . ' (' . implode(', ', $table_list) . ') values (';
          reset($table_list);
          while (list(,$i) = each($table_list)) {
            if (!isset($rows[$i])) {
              $schema .= 'NULL, ';
            } elseif (tep_not_null($rows[$i])) {
              $row = addslashes($rows[$i]);
              $row = preg_replace("/\n#/", "\n".'\#', $row);
              $schema .= '\'' . $row . '\', ';
            } else {
              $schema .= '\'\', ';
            }
          }
          $schema = preg_replace('/, $/', '', $schema) . ');' . "\n\n";
          fputs($fp, $schema);
        }         
      }
      fclose($fp);

      if (isset($_POST['download']) && ($_POST['download'] == 'yes')) {
        switch ($_POST['compress']) {
          case 'gzip':
            exec(LOCAL_EXE_GZIP . ' ' . DIR_FS_BACKUP . $backup_file);
            $backup_file .= '.gz';
            break;
          case 'zip':
            exec(LOCAL_EXE_ZIP . ' -j ' . DIR_FS_BACKUP . $backup_file . '.zip ' . DIR_FS_BACKUP . $backup_file);
            unlink(DIR_FS_BACKUP . $backup_file);
            $backup_file .= '.zip';
        }
        header('Content-type: application/x-octet-stream');
        header('Content-disposition: attachment; filename=' . $backup_file);
        readfile(DIR_FS_BACKUP . $backup_file);
        unlink(DIR_FS_BACKUP . $backup_file);
        exit;
      } else {
        switch ($_POST['compress']) {
          case 'gzip':
            exec(LOCAL_EXE_GZIP . ' ' . DIR_FS_BACKUP . $backup_file);
            break;
          case 'zip':
            exec(LOCAL_EXE_ZIP . ' -j ' . DIR_FS_BACKUP . $backup_file . '.zip ' . DIR_FS_BACKUP . $backup_file);
            unlink(DIR_FS_BACKUP . $backup_file);
        }
        $messageStack->add_session('search', SUCCESS_DATABASE_SAVED, 'success');
      }
      tep_redirect(tep_href_link(FILENAME_CDS_BACKUP_RESTORE));
      break;

    case 'restorenow':
    case 'restorelocalnow':
      tep_set_time_limit(0);
      if ($action == 'restorenow') {
        $read_from = $_GET['file'];
        if (file_exists(DIR_FS_BACKUP . $_GET['file'])) {
          $restore_file = DIR_FS_BACKUP . $_GET['file'];
          $extension = substr($_GET['file'], -3);
          if ( ($extension == 'sql') || ($extension == '.gz') || ($extension == 'zip') ) {
            switch ($extension) {
              case 'sql':
                $restore_from = $restore_file;
                $remove_raw = false;
                break;
              case '.gz':
                $restore_from = substr($restore_file, 0, -3);
                exec(LOCAL_EXE_GUNZIP . ' ' . $restore_file . ' -c > ' . $restore_from);
                $remove_raw = true;
                break;
              case 'zip':
                $restore_from = substr($restore_file, 0, -4);
                exec(LOCAL_EXE_UNZIP . ' ' . $restore_file . ' -d ' . DIR_FS_BACKUP);
                $remove_raw = true;
            }
            if (isset($restore_from) && file_exists($restore_from) && (filesize($restore_from) > 15000)) {
              $fd = fopen($restore_from, 'rb');
              $restore_query = fread($fd, filesize($restore_from));
              fclose($fd);
            }
          }
        }
      } elseif ($action == 'restorelocalnow') {
        $sql_file = new upload('sql_file');
        if ($sql_file->parse() == true) {
          $restore_query = fread(fopen($sql_file->tmp_filename, 'r'), filesize($sql_file->tmp_filename));
          $read_from = $sql_file->filename;
        }
      }
      if (isset($restore_query)) {
        $sql_array = array();
        $sql_length = strlen($restore_query);
        $pos = strpos($restore_query, ';');
        for ($i=$pos; $i<$sql_length; $i++) {
          if ($restore_query[0] == '#') {
            $restore_query = ltrim(substr($restore_query, strpos($restore_query, "\n")));
            $sql_length = strlen($restore_query);
            $i = strpos($restore_query, ';')-1;
            continue;
          }
          if ($restore_query[($i+1)] == "\n") {
            for ($j=($i+2); $j<$sql_length; $j++) {
              if (trim($restore_query[$j]) != '') {
                $next = substr($restore_query, $j, 6);
                if ($next[0] == '#') {
                  // find out where the break position is so we can remove this line (#comment line)
                  for ($k=$j; $k<$sql_length; $k++) {
                    if ($restore_query[$k] == "\n") break;
                  }
                  $query = substr($restore_query, 0, $i+1);
                  $restore_query = substr($restore_query, $k);
                  // join the query before the comment appeared, with the rest of the dump
                  $restore_query = $query . $restore_query;
                  $sql_length = strlen($restore_query);
                  $i = strpos($restore_query, ';')-1;
                  continue 2;
                }
                break;
              }
            }
            if ($next == '') { // get the last insert query
              $next = 'insert';
            }
            if ( (preg_match('/create/i', $next)) || (preg_match('/insert/i', $next)) || (preg_match('/drop t/i', $next)) ) {
              $next = '';
              $sql_array[] = substr($restore_query, 0, $i);
              $restore_query = ltrim(substr($restore_query, $i+1));
              $sql_length = strlen($restore_query);
              $i = strpos($restore_query, ';')-1;
            }
          }
        }
        for ($i=0, $n=sizeof($sql_array); $i<$n; $i++) {
          tep_db_query($sql_array[$i]);
        }
        if (isset($remove_raw) && ($remove_raw == true)) {
          unlink($restore_from);
        }
        $messageStack->add_session('search', SUCCESS_DATABASE_RESTORED, 'success');
      }
      tep_redirect(tep_href_link(FILENAME_CDS_BACKUP_RESTORE));
      break;

    case 'download':
      $extension = substr($_GET['file'], -3);
      if ( ($extension == 'zip') || ($extension == '.gz') || ($extension == 'sql') ) {
        if ($fp = fopen(DIR_FS_BACKUP . $_GET['file'], 'rb')) {
          $buffer = fread($fp, filesize(DIR_FS_BACKUP . $_GET['file']));
          fclose($fp);
          header('Content-type: application/x-octet-stream');
          header('Content-disposition: attachment; filename=' . $_GET['file']);
          echo $buffer;
          exit;
        }
      } else {
        $messageStack->add('search', ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE, 'error');
      }
      break;

    case 'deleteconfirm':
      if (strstr($_GET['file'], '..')) tep_redirect(tep_href_link(FILENAME_CDS_BACKUP_RESTORE));
      tep_remove(DIR_FS_BACKUP . $_GET['file']);
      if (!$tep_remove_error) {
        $messageStack->add_session('search', SUCCESS_BACKUP_DELETED, 'success');
        tep_redirect(tep_href_link(FILENAME_CDS_BACKUP_RESTORE));
      }
      break;
    }
  }

  // check if the backup directory exists
  $dir_ok = false;
  if (is_dir(DIR_FS_BACKUP)) {
    if (is_writeable(DIR_FS_BACKUP)) {
      $dir_ok = true;
    } else {
      $messageStack->add('search', ERROR_BACKUP_DIRECTORY_NOT_WRITEABLE, 'error');
    }
  } else {
    $messageStack->add('search', ERROR_BACKUP_DIRECTORY_DOES_NOT_EXIST, 'error');
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
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TITLE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_FILE_DATE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_FILE_SIZE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <?php
              if ($dir_ok == true) {
                $dir = dir(DIR_FS_BACKUP);
                $contents = array();
                while ($file = $dir->read()) {
                  if (!is_dir(DIR_FS_BACKUP . $file) && strtolower(substr($file, 0, 4)) == 'cds_') {
                    $contents[] = $file;
                  }
                }
                sort($contents);
                for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
                  $entry = $contents[$i];
                  $check = 0;
                  if ((!isset($_GET['file']) || (isset($_GET['file']) && ($_GET['file'] == $entry))) && !isset($buInfo) && ($action != 'backup') && ($action != 'restorelocal')) {
                    $file_array['file'] = $entry;
                    $file_array['date'] = date(PHP_DATE_TIME_FORMAT, filemtime(DIR_FS_BACKUP . $entry));
                    $file_array['size'] = number_format(filesize(DIR_FS_BACKUP . $entry)) . BYTES;
                    switch (substr($entry, -3)) {
                      case 'zip': $file_array['compression'] = 'ZIP'; break;
                      case '.gz': $file_array['compression'] = 'GZIP'; break;
                      default: $file_array['compression'] = TEXT_NO_EXTENSION; break;
                    }
                    $buInfo = new objectInfo($file_array);
                  }
                  if (isset($buInfo) && is_object($buInfo) && ($entry == $buInfo->file)) {
                    echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
                    $onclick_link = 'file=' . $buInfo->file . '&action=restore';
                  } else {
                     echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
                     $onclick_link = 'file=' . $entry;
                  }
                  ?>
                  <td class="dataTableContent" onclick="document.location.href='<?php echo tep_href_link(FILENAME_CDS_BACKUP_RESTORE, $onclick_link); ?>'"><?php echo '<a href="' . tep_href_link(FILENAME_CDS_BACKUP_RESTORE, 'action=download&file=' . $entry) . '">' . tep_image(DIR_WS_ICONS . 'file_download.gif', ICON_FILE_DOWNLOAD) . '</a>&nbsp;' . $entry; ?></td>
                  <td class="dataTableContent" align="center" onclick="document.location.href='<?php echo tep_href_link(FILENAME_CDS_BACKUP_RESTORE, $onclick_link); ?>'"><?php echo date(PHP_DATE_TIME_FORMAT, filemtime(DIR_FS_BACKUP . $entry)); ?></td>
                  <td class="dataTableContent" align="right" onclick="document.location.href='<?php echo tep_href_link(FILENAME_CDS_BACKUP_RESTORE, $onclick_link); ?>'"><?php echo number_format(filesize(DIR_FS_BACKUP . $entry)); ?> <!-- bytes --><?php echo BYTES;?></td>
                  <td class="dataTableContent" align="right"><?php if (isset($buInfo) && is_object($buInfo) && ($entry == $buInfo->file)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CDS_BACKUP_RESTORE, 'file=' . $entry) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                </tr>
                <?php
                }
                $dir->close();
              }
              ?>
            </table>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
              <tr>
                <td class="smallText" colspan="3"><?php echo TEXT_BACKUP_DIRECTORY . ' ' . DIR_FS_BACKUP; ?></td>
                <td align="right" class="smallText"><?php if ( ($action != 'backup') && (isset($dir)) ) echo '<a href="' . tep_href_link(FILENAME_CDS_BACKUP_RESTORE, 'action=backup') . '">' . tep_image_button('button_backup.gif', IMAGE_BACKUP) . '</a>'; if ( ($action != 'restorelocal') && isset($dir) ) echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CDS_BACKUP_RESTORE, 'action=restorelocal') . '">' . tep_image_button('button_restore.gif', IMAGE_RESTORE) . '</a>'; ?></td>
              </tr>
              <?php
              if (defined('DB_LAST_RESTORE')) {
                ?>
                <tr>
                  <td class="smallText" colspan="4"><?php echo TEXT_LAST_RESTORATION . ' ' . DB_LAST_RESTORE . ' <a href="' . tep_href_link(FILENAME_CDS_BACKUP_RESTORE, 'action=forget') . '">' . TEXT_FORGET . '</a>'; ?></td>
                </tr>
                <?php
              }
              ?>
            </table></td>
            <?php
            $heading = array();
            $contents = array();

            switch ($action) {
              case 'backup':
              $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_BACKUP . '</b>');
              $contents = array('form' => tep_draw_form('backup', FILENAME_CDS_BACKUP_RESTORE, 'action=backupnow'));
              $contents[] = array('text' => TEXT_INFO_NEW_BACKUP);
              $contents[] = array('text' => '<br>' . tep_draw_radio_field('compress', 'no', true) . ' ' . TEXT_INFO_USE_NO_COMPRESSION);
              if (file_exists(LOCAL_EXE_GZIP)) $contents[] = array('text' => '<br>' . tep_draw_radio_field('compress', 'gzip') . ' ' . TEXT_INFO_USE_GZIP);
              if (file_exists(LOCAL_EXE_ZIP)) $contents[] = array('text' => tep_draw_radio_field('compress', 'zip') . ' ' . TEXT_INFO_USE_ZIP);
              if ($dir_ok == true) {
                $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('download', 'yes') . ' ' . TEXT_INFO_DOWNLOAD_ONLY . '*<br><br>*' . TEXT_INFO_BEST_THROUGH_HTTPS);
              } else {
                $contents[] = array('text' => '<br>' . tep_draw_radio_field('download', 'yes', true) . ' ' . TEXT_INFO_DOWNLOAD_ONLY . '*<br><br>*' . TEXT_INFO_BEST_THROUGH_HTTPS);
              }
              $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_backup.gif', IMAGE_BACKUP) . '&nbsp;<a href="' . tep_href_link(FILENAME_CDS_BACKUP_RESTORE) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
              break;

            case 'restore':
              $heading[] = array('text' => '<b>' . $buInfo->date . '</b>');
              $contents[] = array('text' => tep_break_string(sprintf(TEXT_INFO_RESTORE, DIR_FS_BACKUP . (($buInfo->compression != TEXT_NO_EXTENSION) ? substr($buInfo->file, 0, strrpos($buInfo->file, '.')) : $buInfo->file), ($buInfo->compression != TEXT_NO_EXTENSION) ? TEXT_INFO_UNPACK : ''), 35, ' '));
              $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CDS_BACKUP_RESTORE, 'file=' . $buInfo->file . '&action=restorenow') . '">' . tep_image_button('button_restore.gif', IMAGE_RESTORE) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_CDS_BACKUP_RESTORE, 'file=' . $buInfo->file) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
              break;

            case 'restorelocal':
              $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_RESTORE_LOCAL . '</b>');
              $contents = array('form' => tep_draw_form('restore', FILENAME_CDS_BACKUP_RESTORE, 'action=restorelocalnow', 'post', 'enctype="multipart/form-data"'));
              $contents[] = array('text' => TEXT_INFO_RESTORE_LOCAL . '<br><br>' . TEXT_INFO_BEST_THROUGH_HTTPS);
              $contents[] = array('text' => '<br>' . tep_draw_file_field('sql_file'));
              $contents[] = array('text' => TEXT_INFO_RESTORE_LOCAL_RAW_FILE);
              $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_restore.gif', IMAGE_RESTORE) . '&nbsp;<a href="' . tep_href_link(FILENAME_CDS_BACKUP_RESTORE) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
              break;

            case 'delete':
              $heading[] = array('text' => '<b>' . $buInfo->date . '</b>');
              $contents = array('form' => tep_draw_form('delete', FILENAME_CDS_BACKUP_RESTORE, 'file=' . $buInfo->file . '&action=deleteconfirm'));
              $contents[] = array('text' => TEXT_DELETE_INTRO);
              $contents[] = array('text' => '<br><b>' . $buInfo->file . '</b>');
                            $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CDS_BACKUP_RESTORE, 'file=' . $buInfo->file) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                            break;
                        default:
                            if (isset($buInfo) && is_object($buInfo)) {
                                $heading[] = array('text' => '<b>' . $buInfo->date . '</b>');
                
                                $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CDS_BACKUP_RESTORE, 'file=' . $buInfo->file . '&action=restore') . '">' . tep_image_button('button_restore.gif', IMAGE_RESTORE) . '</a> <a href="' . tep_href_link(FILENAME_CDS_BACKUP_RESTORE, 'file=' . $buInfo->file . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
                                $contents[] = array('text' => '<br>' . TEXT_INFO_DATE . ' ' . $buInfo->date);
                                $contents[] = array('text' => TEXT_INFO_SIZE . ' ' . $buInfo->size);
                                $contents[] = array('text' => '<br>' . TEXT_INFO_COMPRESSION . ' ' . $buInfo->compression);
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