<?php
/*
  $Id: admin_files.php,v 1.1.1.1 2004/03/04 23:38:04 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

$current_boxes = DIR_FS_ADMIN . DIR_WS_BOXES;
$current_files = DIR_FS_ADMIN;

if (isset($_GET['cID'])) {
  $cID = $_GET['cID'] ;
} else if (isset($_POST['cID'])) {
  $cID = $_POST['cID'] ;
} else {
  $cID = '' ;
}
if (isset($_GET['action'])) {
  $action = $_GET['action'] ;
} else if (isset($_POST['action'])) {
  $action = $_POST['action'] ;
} else {
  $action = '' ;
}
if (isset($_GET['cPath'])) {
  $cPath = $_GET['cPath'] ;
} else if (isset($_POST['cPath'])) {
  $cPath = $_POST['cPath'] ;
} else {
  $cPath = '' ;
}

if (tep_not_null($action)) {
  switch ($action) {
    case 'box_store':
    $sql_data_array = array('admin_files_name' => tep_db_prepare_input($_GET['box']),
      'admin_files_is_boxes' => '1');
    tep_db_perform(TABLE_ADMIN_FILES, $sql_data_array);
    $admin_boxes_id = tep_db_insert_id();

    tep_redirect(tep_href_link(FILENAME_ADMIN_FILES, 'cID=' . $admin_boxes_id));
    break;
    case 'box_remove':
  // NOTE: ALSO DELETE FILES STORED IN REMOVED BOX //
    $admin_boxes_id = tep_db_prepare_input($_GET['cID']);
    tep_db_query("delete from " . TABLE_ADMIN_FILES . " where admin_files_id = '" . $admin_boxes_id . "' or admin_files_to_boxes = '" . $admin_boxes_id . "'");

    tep_redirect(tep_href_link(FILENAME_ADMIN_FILES));
    break;
    case 'file_store':
    $sql_data_array = array('admin_files_name' => tep_db_prepare_input($_POST['admin_files_name']),
      'admin_files_to_boxes' => tep_db_prepare_input($_POST['admin_files_to_boxes']),
      'admin_files_is_boxes' => '0');
    tep_db_perform(TABLE_ADMIN_FILES, $sql_data_array);
    $admin_files_id = tep_db_insert_id();

    tep_redirect(tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath'] . '&fID=' . $admin_files_id));
    break;
    case 'file_remove':
    $admin_files_id = tep_db_prepare_input($_POST['admin_files_id']);
    tep_db_query("delete from " . TABLE_ADMIN_FILES . " where admin_files_id = '" . $admin_files_id . "'");

    tep_redirect(tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath']));
    break;
  }
}

include(DIR_WS_INCLUDES . 'html_top.php');
include(DIR_WS_INCLUDES . 'header.php');
include(DIR_WS_INCLUDES . 'column_left.php');
?>
<div id="content" class="content p-relative">         
  <h1 class="page-header"><i class="fa fa-laptop"></i> <?php echo HEADING_TITLE; ?></h1>

  <?php if (file_exists(DIR_WS_INCLUDES . 'toolbar.php')) include(DIR_WS_INCLUDES . 'toolbar.php'); ?>
  
  <div class="col">     
    <!-- begin panel -->
    <div class="dark">
      <!-- body_text //-->     
      <div id="table-admin-files" class="table-admin-files">
        <div class="row">
          <div class="col-md-8 col-xl-9 dark panel-left rounded-left">
            <?php
            if (isset($_GET['fID']) || isset($_GET['cPath'])) {
              //$current_box_query_raw = "select admin_files_name as admin_box_name from " . TABLE_ADMIN_FILES . " where admin_files_id = " . $_GET['cPath'] . " ";
              $current_box_query = tep_db_query("select admin_files_name as admin_box_name from " . TABLE_ADMIN_FILES . " where admin_files_id = " . $_GET['cPath']);
              $current_box = tep_db_fetch_array($current_box_query);
              ?>
              <table class="table table-hover w-100 mt-2">
                <thead>
                  <tr class="th-row">
                    <th scope="col" class="th-col dark text-left"><?php echo TABLE_HEADING_FILENAME; ?></th>
                    <th scope="col" class="th-col dark text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $db_file_query_raw = "select * from " . TABLE_ADMIN_FILES . " where admin_files_to_boxes = " . $_GET['cPath'] . " order by admin_files_name";
                  $db_file_query = tep_db_query($db_file_query_raw);
                  $file_count = 0;

                  while ($files = tep_db_fetch_array($db_file_query)) {
                    $file_count++;

                    if ( (!isset($_GET['fID'])) || (isset($_GET['fID']) && ($_GET['fID'] == $files['admin_files_id'])) && (!isset($fInfo)) ) {
                      $fInfo = new objectInfo($files);
                    }

                    $selected = ((isset($fInfo) && is_object($fInfo)) && ($files['admin_files_id'] == $fInfo->admin_files_id)) ? ' selected' : '';                    

                    if ($selected) {
                      echo '<tr class="table-row dark selected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath'] . '&fID=' . $files['admin_files_id'] . '&action=edit_file') . '\'">' . "\n";
                    } else {
                      echo '<tr class="table-row dark" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath'] . '&fID=' . $files['admin_files_id']) . '\'">' . "\n";
                    }
                    $col_selected = ($selected) ? ' selected' : '';
                    ?>
                      <td class="table-col dark text-left<?php echo $col_selected; ?>"><?php echo $files['admin_files_name']; ?></td>

                      <td class="table-col dark text-right<?php echo $col_selected; ?>">
                        <?php echo ($selected) ? '<i class="fa fa-long-arrow-right fa-lg text-success"></i>' : '<a href="' . tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath'] . '&fID=' . $files['admin_files_id']) . '"><i class="fa fa-info-circle fa-lg text-muted"></i></a>'; ?>
                      </td>

                    </tr>
                    <?php
                  }
                  ?>
                </tbody>
              </table>

              <div class="pagination-container">
                <div class="results-left"><?php echo TEXT_COUNT_FILES . $file_count; ?></div>
              </div>

              <div class="float-right mr-2 mt-3 mb-3" role="group">
                <button class="btn btn-default btn-sm mr-2" onclick="document.location='<?php echo tep_href_link(FILENAME_ADMIN_FILES, 'cID=' . $_GET['cPath']); ?>'"><?php echo IMAGE_BACK; ?></button>
                <button class="btn btn-success btn-sm" onclick="document.location='<?php echo tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath'] . '&action=store_file'); ?>'"><?php echo IMAGE_INSERT_FILE; ?></button> 
              </div>

              <?php
            } else {
              ?>
              <table class="table table-hover w-100 mt-2">

                <thead>
                  <tr class="th-row">
                    <th scope="col" class="th-col dark text-left"><?php echo TABLE_HEADING_BOXES; ?></th>
                    <th scope="col" class="th-col dark text-left d-none d-md-table-cell"><?php echo TABLE_HEADING_STATUS; ?></th>
                    <th scope="col" class="th-col dark text-right"><?php echo TABLE_HEADING_ACTION; ?></th>
                  </tr>
                </thead>
                <tbody>            
                  <?php
                  $installed_boxes_query = tep_db_query("select admin_files_name as admin_boxes_name from " . TABLE_ADMIN_FILES . " where admin_files_is_boxes = '1' order by admin_files_name");
                  $installed_boxes = array();
                  while($db_boxes = tep_db_fetch_array($installed_boxes_query)) {
                    $installed_boxes[] = $db_boxes['admin_boxes_name'];
                  }
                  // read directory where boxes are located
                  $none = 0;
                  $boxes = array();
                  $dir = dir(DIR_WS_BOXES);
                  while ($boxes_file = $dir->read()) {
                    if ( (substr("$boxes_file", -4) == '.php') && !(in_array($boxes_file, $installed_boxes))){
                      $boxes[] = array('admin_boxes_name' => $boxes_file,
                                       'admin_boxes_id' => 'b' . $none);
                    } elseif ( (substr("$boxes_file", -4) == '.php') && (in_array($boxes_file, $installed_boxes))) {
                      $db_boxes_id_query = tep_db_query("select admin_files_id as admin_boxes_id from " . TABLE_ADMIN_FILES . " where admin_files_is_boxes = 1 and admin_files_name = '" . $boxes_file . "'");
                      $db_boxes_id = tep_db_fetch_array($db_boxes_id_query);
                      $boxes[] = array('admin_boxes_name' => $boxes_file,
                                       'admin_boxes_id' => $db_boxes_id['admin_boxes_id']);
                    }
                    $none++;
                  }
                  $dir->close();
                  sort($boxes);
                  reset ($boxes);

                  $boxnum = sizeof($boxes);
                  $i = 0;
                  while ($i < $boxnum) {
                    if (((!isset($cID)) || (isset($_GET['none']) && $_GET['none'] == $boxes[$i]['admin_boxes_id']) || (isset($cID) && $cID == $boxes[$i]['admin_boxes_id'])) && (!isset($cInfo)) ) {
                      $cInfo = new objectInfo($boxes[$i]);
                    }
                    $selected = ( isset($cInfo) && (is_object($cInfo)) && ($boxes[$i]['admin_boxes_id'] == $cInfo->admin_boxes_id) ) ? ' selected' : '';
                    if ( $selected ) {
                      if ( substr("$cInfo->admin_boxes_id", 0,1) == 'b') {
                        echo '<tr class="table-row dark selected" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_FILES, 'cID=' . $boxes[$i]['admin_boxes_id']) . '\'">' . "\n";
                      } else {
                        echo '<tr class="table-row dark selected" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $boxes[$i]['admin_boxes_id'] . '&action=store_file') . '\'">' . "\n";
                      }
                    } else {
                      echo '<tr class="table-row dark" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_FILES, 'cID=' . $boxes[$i]['admin_boxes_id']) . '\'">' . "\n";
                    }
                    $col_selected = ($selected) ? ' selected' : '';
                    ?>
                    <td class="table-col dark text-left<?php echo $col_selected; ?>"><?php echo '<i class="fa fa-folder fa-lg text-warning mr-2"></i>' . ucfirst (substr_replace ($boxes[$i]['admin_boxes_name'], '' , -4)); ?></td>
                    <td class="table-col dark text-left<?php echo $col_selected; ?>"><?php
                      if ($selected) {
                        if (substr($boxes[$i]['admin_boxes_id'], 0,1) == 'b') {
                          echo '<i class="fa fa-times-circle fa-lg text-danger"></i><a href="' . tep_href_link(FILENAME_ADMIN_FILES, 'cID=' . $boxes[$i]['admin_boxes_id'] . '&box=' . $boxes[$i]['admin_boxes_name'] . '&action=box_store') . '"><i class="fa fa-check-circle-o fa-lg text-secondary ml-1"></i></a>';
                        } else {
                          echo '<a href="' . tep_href_link(FILENAME_ADMIN_FILES, 'cID=' . $_GET['cID'] . '&action=box_remove') . '"><i class="fa fa-times-circle-o fa-lg text-secondary"></i></a><i class="fa fa-check-circle fa-lg text-success ml-1"></i>';
                        }
                      } else {
                        if (substr($boxes[$i]['admin_boxes_id'], 0,1) == 'b') {
                          echo '<i class="fa fa-times-circle fa-lg text-danger"></i><i class="fa fa-check-circle-o fa-lg text-secondary ml-1"></i>';
                        } else {
                          echo '<i class="fa fa-times-circle-o fa-lg text-secondary"></i><i class="fa fa-check-circle fa-lg text-success ml-1"></i>';
                        }
                      }
                      ?>
                    </td>
                    <td class="table-col dark text-right<?php echo $col_selected; ?>">
                      <?php echo ($selected) ? '<i class="fa fa-long-arrow-right fa-lg text-success"></i>' : '<a href="' . tep_href_link(FILENAME_ADMIN_FILES, 'cID=' . $boxes[$i]['admin_boxes_id']) . '"><i class="fa fa-info-circle fa-lg text-muted"></i></a>'; ?>
                    </td>
                    </tr>
                    <?php
                    $i++;
                  }
                  ?>
                </tbody>
              </table>

              <div class="pagination-container">
                <div class="results-left"><?php echo TEXT_COUNT_BOXES . $boxnum; ?></div>
              </div>
              <?php
            }
            ?>
          </div>
          <div class="col-md-4 col-xl-3 dark panel-right rounded-right">
            <?php
              $heading = array();
              $contents = array();

              switch ($action) {

                case 'store_file':
                  $heading[] = array('text' => TEXT_INFO_HEADING_NEW_FILE);

                  $files_array = array();
                  $file_query = tep_db_query("select admin_files_name from " . TABLE_ADMIN_FILES . " where admin_files_is_boxes = '0' ");
                  while ($fetch_files = tep_db_fetch_array($file_query)) {
                    $files_array[] = $fetch_files['admin_files_name'];
                  }

                  $file_dir = array();
                  $dir = dir(DIR_FS_ADMIN);

                  while ($file = $dir->read()) {
                    if ((substr("$file", -4) == '.php') && $file != FILENAME_DEFAULT && $file != FILENAME_LOGIN && $file != FILENAME_LOGOFF && $file != FILENAME_FORBIDEN && $file != FILENAME_POPUP_IMAGE && $file != FILENAME_PASSWORD_FORGOTTEN && $file != FILENAME_ADMIN_ACCOUNT && $file != 'invoice.php' && $file != 'packingslip.php') {
                        $file_dir[] = $file;
                    }
                  }

                  $result = $file_dir;
                  if (sizeof($files_array) > 0) {
                    $result = array_values (array_diff($file_dir, $files_array));
                  }
                  if(empty($result)){
                   $result = array();
                  }

                  sort ($result);
                  reset ($result);
                  $show = array();
                  while (list ($key, $val) = each ($result)) {
                    $show[] = array('id' => $val,
                                    'text' => $val);
                  }

                  $contents[] = array('form' => tep_draw_form('store_file', FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath'] . '&fID=' . $files['admin_files_id'] . '&action=file_store', 'post', 'enctype="multipart/form-data"'));
                  $contents[] = array('text' => '<div class="row"><div class="col p-0 mt-3 ml-2 mr-2"><div class="note note-info m-0"><p class="mb-0 mt-0">' . TEXT_INFO_NEW_FILE_BOX . '<span class="f-w-600">' . ucfirst(substr_replace ($current_box['admin_box_name'], '', -4)) . '</span></p></div></div></div>');     
                  $contents[] = array('text' => '<div class="row"><div class="col p-0 mt-3 ml-2 mr-2"><div class="note note-info m-0"><p class="mb-0 mt-0">' . TEXT_INFO_NEW_FILE_INTRO . '</p></div></div></div>');     
                  $contents[] = array('align' => 'left', 'text' => tep_draw_pull_down_menu('admin_files_name', $show, $show, 'class="form-control mt-3"'));
                  $contents[] = array('text' => tep_draw_hidden_field('admin_files_to_boxes', $_GET['cPath']));
                  $contents[] = array('align' => 'center', 'text' => '<button type="button" class="btn btn-default btn-sm mr-2 mt-3 mb-4" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath'])  . '\'">' . IMAGE_CANCEL . '</button><button class="btn btn-success btn-sm mt-3 mb-4" type="submit">' . IMAGE_SAVE . '</button>');
                  break;

                case 'remove_file':
                  $heading[] = array('text' => TEXT_INFO_HEADING_DELETE_FILE);
                  $contents[] = array('form' => tep_draw_form('remove_file', FILENAME_ADMIN_FILES, 'action=file_remove&cPath=' . $_GET['cPath'] . '&fID=' . $files['admin_files_id'], 'post', 'enctype="multipart/form-data"'));
                  $contents[] = array('text' => tep_draw_hidden_field('admin_files_id', $_GET['fID']));
                  $contents[] = array('text' => '<div class="row"><div class="col p-0 mt-3 ml-2 mr-2"><div class="note note-danger m-0"><p class="mb-0 mt-0">' . sprintf(TEXT_INFO_DELETE_FILE_INTRO, $fInfo->admin_files_name, ucfirst(substr_replace ($current_box['admin_box_name'], '', -4))) . '</p></div></div></div>');     
                  $contents[] = array('align' => 'center', 'text' => '<button type="button" class="btn btn-default btn-sm mr-2 mt-3 mb-4" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath'] . '&fID=' . $_GET['fID']) . '\'">' . IMAGE_CANCEL . '</button><button class="btn btn-danger btn-sm mt-3 mb-4" type="submit">' . IMAGE_CONFIRM_DELETE . '</button>');
                  break;

                default:
                  if ((isset($cInfo)) && (is_object($cInfo)) ) {
                    $heading[] = array('text' => ucwords(str_replace(".php", "", $cInfo->admin_boxes_name)) . ' ' . TEXT_BOX);
                    if ( substr($cInfo->admin_boxes_id, 0,1) == 'b') {
                      $contents[] = array('text' => '<div class="row"><div class="col p-0 mt-3 ml-2 mr-2"><div class="note note-danger m-0"><p class="mb-0 mt-0"><span class="f-w-600">' . $cInfo->admin_boxes_name . '</span> ' . TEXT_INFO_DEFAULT_BOXES_NOT_INSTALLED . '</p></div></div></div>');     
                      $contents[] = array('text' => '<div class="row"><div class="col p-0 mt-3 ml-2 mr-2"><div class="note note-info m-0"><p class="mb-0 mt-2">' . TEXT_INFO_DEFAULT_BOXES_INTRO . '</p></div></div></div>');     
                      $contents[] = array('text' => '<div class="row"><div class="col p-0 mt-3 ml-2 mr-2"><div class="note note-warning m-0"><h4 class="m-0">' . TEXT_WARNING . '</h4><p class="mb-0 mt-2">' . WARNING_UNINSTALL_TEXT . '</p></div></div></div>');     
                    } else {
                      $contents[] = array('form' => tep_draw_form('newfile', FILENAME_ADMIN_FILES, 'cPath=' . $cInfo->admin_boxes_id . '&action=store_file', 'post', 'enctype="multipart/form-data"'));
                      $contents[] = array('align' => 'center', 'text' => '<div class="mt-3 mb-3"><button class="btn btn-success btn-sm" type="submit">' . IMAGE_INSERT_FILE . '</button></div>');
                      $contents[] = array('text' => tep_draw_hidden_field('this_category', $cInfo->admin_boxes_id));
                      $contents[] = array('text' => '<div class="row"><div class="col p-0 mt-0 ml-2 mr-2"><div class="note note-info m-0"><p class="mb-0 mt-0">' . TEXT_INFO_DEFAULT_BOXES_INTRO . '</p></div></div></div>');     
                      $contents[] = array('text' => '<div class="row"><div class="col p-0 mt-3 ml-2 mr-2"><div class="note note-warning m-0"><h4 class="m-0">' . TEXT_WARNING . '</h4><p class="mb-0 mt-2">' . WARNING_UNINSTALL_TEXT . '</p></div></div></div>');     
                    }
                  }
                  if (isset($fInfo) && is_object($fInfo)) {
                    $heading[] = array('text' => TEXT_INFO_NEW_FILE_BOX .  ucfirst(substr_replace ($current_box['admin_box_name'], '', -4)));
                    $contents[] = array('align' => 'center', 'text' => '<div class="mt-3 mb-0"><button class="btn btn-success btn-sm mr-2" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath'] . '&action=store_file') . '\'">' . IMAGE_INSERT_FILE . '</button><button class="btn btn-danger btn-sm" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_FILES, 'cPath=' . $_GET['cPath'] . '&fID=' . $fInfo->admin_files_id . '&action=remove_file') . '\'">' . IMAGE_DELETE . '</button></div>');
                    $contents[] = array('text' => '<div class="row"><div class="col p-0 mt-3 ml-2 mr-2"><div class="note note-info m-0"><p class="mb-0 mt-0">' .  TEXT_INFO_DEFAULT_FILE_INTRO . '<strong>' . ucfirst(substr_replace ($current_box['admin_box_name'], '', -4)) . '</strong></p></div></div></div>');     
                  }
              }

              if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
                $box = new box;
                echo $box->showSidebar($heading, $contents);
              }
            ?>
          </div>
        </div>
      </div>   
      <!-- end body_text //-->
    </div>
    <!-- end panel -->
  </div>
</div>
<!-- body_eof //-->
<?php 
include(DIR_WS_INCLUDES . 'html_bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>