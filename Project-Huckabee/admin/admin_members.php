<?php
/*
  $Id: admin_members.php,v 6.5.4 2017/12/17 01:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2017 Loaded Commerce
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require_once('includes/application_top.php');

$current_boxes = DIR_FS_ADMIN . DIR_WS_BOXES;

if ( ! isset($_GET['action']) ) $_GET['action'] = '';

if ($_GET['action'] != '') {
  switch ($_GET['action']) {
    case 'member_new':
      $check_email_query = tep_db_query("select admin_email_address from " . TABLE_ADMIN . "");
      while ($check_email = tep_db_fetch_array($check_email_query)) {
        $stored_email[] = $check_email['admin_email_address'];
      }

      if (in_array($_POST['admin_email_address'], $stored_email)) {
        tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'error=email&action=new_member' . setPage()));
      } else {
        $makePassword = tep_create_hard_pass();         
        $sql_data_array = array('admin_groups_id' => tep_db_prepare_input($_POST['admin_groups_id']),
                                'admin_firstname' => tep_db_prepare_input($_POST['admin_firstname']),
                                'admin_lastname' => tep_db_prepare_input($_POST['admin_lastname']),
                                'admin_email_address' => tep_db_prepare_input($_POST['admin_email_address']),
                                'admin_password' => tep_encrypt_password($makePassword),
                                'admin_created' => 'now()');

        tep_db_perform(TABLE_ADMIN, $sql_data_array);
        $admin_id = tep_db_insert_id();

        tep_mail($_POST['admin_firstname'] . ' ' . $_POST['admin_lastname'], $_POST['admin_email_address'], ADMIN_EMAIL_SUBJECT, sprintf(ADMIN_EMAIL_TEXT, $_POST['admin_firstname'], HTTP_SERVER . DIR_WS_ADMIN, $_POST['admin_email_address'], $makePassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

        tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'mID=' . $admin_id . setPage()));
      }
      break;
    case 'member_edit':
      $admin_id = tep_db_prepare_input($_POST['admin_id']);
      $hiddenPassword = '-hidden-';
      $stored_email[] = 'NONE';

      $check_email_query = tep_db_query("select admin_email_address from " . TABLE_ADMIN . " where admin_id <> " . $admin_id . "");
      while ($check_email = tep_db_fetch_array($check_email_query)) {
        $stored_email[] = $check_email['admin_email_address'];
      }

      if (in_array($_POST['admin_email_address'], $stored_email)) {
        tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'error=email&action=edit_member&mID=' . $_GET['mID'] . setPage()));
      } else {
        $sql_data_array = array('admin_groups_id' => tep_db_prepare_input($_POST['admin_groups_id']),
                                'admin_firstname' => tep_db_prepare_input($_POST['admin_firstname']),
                                'admin_lastname' => tep_db_prepare_input($_POST['admin_lastname']),
                                'admin_email_address' => tep_db_prepare_input($_POST['admin_email_address']),
                                'admin_modified' => 'now()');

        tep_db_perform(TABLE_ADMIN, $sql_data_array, 'update', 'admin_id = \'' . $admin_id . '\'');

        tep_mail($_POST['admin_firstname'] . ' ' . $_POST['admin_lastname'], $_POST['admin_email_address'], ADMIN_EMAIL_EDIT_SUBJECT, sprintf(ADMIN_EMAIL_EDIT_TEXT, $_POST['admin_firstname'], HTTP_SERVER . DIR_WS_ADMIN, $_POST['admin_email_address'], $hiddenPassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

        tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'mID=' . $admin_id . setPage()));
      }
      break;
    case 'member_delete':
      $admin_id = tep_db_prepare_input($_POST['admin_id']);
      tep_db_query("DELETE FROM " . TABLE_ADMIN . " WHERE admin_id = '" . $admin_id . "'");
      tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, setPage(false)));
      break;
    case 'group_define':
      $selected_checkbox = $_POST['groups_to_boxes'];

      $define_files_query = tep_db_query("select admin_files_id from " . TABLE_ADMIN_FILES . " order by admin_files_id");
      while ($define_files = tep_db_fetch_array($define_files_query)) {
        $admin_files_id = $define_files['admin_files_id'];

        if (in_array ($admin_files_id, $selected_checkbox)) {
          $sql_data_array = array('admin_groups_id' => tep_db_prepare_input($_POST['checked_' . $admin_files_id]));
          //$set_group_id = $_POST['checked_' . $admin_files_id];
        } else {
          $sql_data_array = array('admin_groups_id' => tep_db_prepare_input($_POST['unchecked_' . $admin_files_id]));
          //$set_group_id = $_POST['unchecked_' . $admin_files_id];
        }
        tep_db_perform(TABLE_ADMIN_FILES, $sql_data_array, 'update', 'admin_files_id = \'' . $admin_files_id . '\'');
      }

      tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $_POST['admin_groups_id']));
      break;
    case 'group_delete':
      $set_groups_id = tep_db_prepare_input($_POST['set_groups_id']);
      tep_db_query("delete from " . TABLE_ADMIN_GROUPS . " where admin_groups_id = '" . $_GET['gID'] . "'");
      tep_db_query("alter table " . TABLE_ADMIN_FILES . " change admin_groups_id admin_groups_id set( " . $set_groups_id . " ) NOT NULL DEFAULT '1' ");
      tep_db_query("delete from " . TABLE_ADMIN . " where admin_groups_id = '" . $_GET['gID'] . "'");
      tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=groups'));
      break;
    case 'group_edit':
      $admin_groups_name = ucwords(strtolower(tep_db_prepare_input($_POST['admin_groups_name'])));
      $name_replace = preg_replace ("/ /", "%", $admin_groups_name);

      if (($admin_groups_name == '' || NULL) || (strlen($admin_groups_name) <= 5) ) {
        tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET['gID'] . '&gName=false&action=edit_group'));
      } else {
        $check_groups_name_query = tep_db_query("select admin_groups_name as group_name_edit from " . TABLE_ADMIN_GROUPS . " where admin_groups_id <> " . $_GET['gID'] . " and admin_groups_name like '%" . $name_replace . "%'");
        $check_duplicate = tep_db_num_rows($check_groups_name_query);
        if ($check_duplicate > 0){
          tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET['gID'] . '&gName=used&action=edit_group'));
        } else {
          $admin_groups_id = $_GET['gID'];
          tep_db_query("update " . TABLE_ADMIN_GROUPS . " set admin_groups_name = '" . $admin_groups_name . "' where admin_groups_id = '" . $admin_groups_id . "'");
          tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $admin_groups_id));
        }
      }
      break;
    case 'group_new':
      $admin_groups_name = ucwords(strtolower(tep_db_prepare_input($_POST['admin_groups_name'])));
      $name_replace = preg_replace ("/ /", "%", $admin_groups_name);

      if (($admin_groups_name == '' || NULL) || (strlen($admin_groups_name) <= 5) ) {
        tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET[gID] . '&gName=false&action=new_group'));
      } else {
        $check_groups_name_query = tep_db_query("select admin_groups_name as group_name_new from " . TABLE_ADMIN_GROUPS . " where admin_groups_name like '%" . $name_replace . "%'");
        $check_duplicate = tep_db_num_rows($check_groups_name_query);
        if ($check_duplicate > 0){
          tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET['gID'] . '&gName=used&action=new_group'));
        } else {
          $sql_data_array = array('admin_groups_name' => $admin_groups_name);
          tep_db_perform(TABLE_ADMIN_GROUPS, $sql_data_array);
          $admin_groups_id = tep_db_insert_id();

          $set_groups_id = tep_db_prepare_input($_POST['set_groups_id']);
          $add_group_id = $set_groups_id . ',\'' . $admin_groups_id . '\'';
          tep_db_query("alter table " . TABLE_ADMIN_FILES . " change admin_groups_id admin_groups_id set( " . $add_group_id . ") NOT NULL DEFAULT '1' ");

          tep_redirect(tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $admin_groups_id));
        }
      }
      break;
  }
}

if ( isset($_GET['gID']) ) {
  $heading_title = HEADING_TITLE_GROUPS;
} elseif ( isset($_GET['gPath']) ) {
  $heading_title = HEADING_TITLE_DEFINE;
} else {
  $heading_title = HEADING_TITLE_MEMBERS;
}

include(DIR_WS_INCLUDES . 'html_top.php');
include(DIR_WS_INCLUDES . 'header.php');
include(DIR_WS_INCLUDES . 'column_left.php');
?>
<div id="content" class="content">         
  <h1 class="page-header"><i class="fa fa-laptop"></i> <?php echo $heading_title; ?></h1>
  <div>     
    <!-- begin panel -->
    <div class="panel panel-inverse">
      <!-- body_text //-->
      <table class="w-100">
        <tr>
          <td valign="top">
            <?php
            if ( isset($_GET['gPath']) ) {          -
              $group_name_query = tep_db_query("select admin_groups_name from " . TABLE_ADMIN_GROUPS . " where admin_groups_id = " . $_GET['gPath']);
              $group_name = tep_db_fetch_array($group_name_query);

              if ($_GET['gPath'] == 1) {
                echo tep_draw_form('defineForm', FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET['gPath']);
              } elseif ($_GET['gPath'] != 1) {
                echo tep_draw_form('defineForm', FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET['gPath'] . '&action=group_define', 'post', 'enctype="multipart/form-data"');
                echo tep_draw_hidden_field('admin_groups_id', $_GET['gPath']);
              }
              ?>
              <table class="table w-100">
                <thead>
                  <tr class="tr-dark">
                    <th colspan="2" scope="col" class="text-left"><?php echo TABLE_HEADING_GROUPS_DEFINE; ?></th>
                  </tr>
                </thead>
                <tbody>
                <?php
                $db_boxes_query = tep_db_query("select admin_files_id as admin_boxes_id, admin_files_name as admin_boxes_name, admin_groups_id as boxes_group_id, admin_files_is_boxes from " . TABLE_ADMIN_FILES . " where (admin_files_is_boxes = '1' or  admin_files_is_boxes = '3' ) order by admin_files_name");
                while ($group_boxes = tep_db_fetch_array($db_boxes_query)) {
                  $group_boxes_files_query = tep_db_query("select admin_files_id, admin_files_name, admin_groups_id from " . TABLE_ADMIN_FILES . " where (admin_files_is_boxes = '0'or admin_files_is_boxes = '2') and admin_files_to_boxes = '" . $group_boxes['admin_boxes_id'] . "' order by admin_files_name");

                  $selectedGroups = $group_boxes['boxes_group_id'];
                  $groupsArray = explode(",", $selectedGroups);

                  if (in_array($_GET['gPath'], $groupsArray)) {
                    $del_boxes = array($_GET['gPath']);
                    $result = array_diff ($groupsArray, $del_boxes);
                    sort($result);
                    $checkedBox = $selectedGroups;
                    $uncheckedBox = implode (",", $result);
                    $checked = true;
                  } else {
                    $add_boxes = array($_GET['gPath']);
                    $result = array_merge ($add_boxes, $groupsArray);
                    sort($result);
                    $checkedBox = implode (",", $result);
                    $uncheckedBox = $selectedGroups;
                    $checked = false;
                  }
                ?>
                <tr class="dataTableRowBoxes">
                  <?php
                  if ($group_boxes['admin_files_is_boxes'] == '1') {
                    ?>
                    <td class="" width="23"><?php echo tep_draw_checkbox_field('groups_to_boxes[]', $group_boxes['admin_boxes_id'], $checked, '', 'id="groups_' . $group_boxes['admin_boxes_id'] . '" onClick="checkGroups(this)"'); ?></td>
                    <td class=""><b><?php echo ucwords(substr_replace ($group_boxes['admin_boxes_name'], '', -4)) . ' ' . tep_draw_hidden_field('checked_' . $group_boxes['admin_boxes_id'], $checkedBox) . tep_draw_hidden_field('unchecked_' . $group_boxes['admin_boxes_id'], $uncheckedBox); ?></b></td>
                    <?php 
                  } else { 
                    ?>
                    <td class="" width="23"><?php echo tep_draw_checkbox_field('groups_to_boxes[]', $group_boxes['admin_boxes_id'], $checked, '', 'id="groups_' . $group_boxes['admin_boxes_id'] . '" onClick="checkGroups(this)"'); ?></td>
                    <td class=""><b><?php echo ucwords(substr_replace ($group_boxes['admin_boxes_name'], '', -4)) . ' ' . tep_draw_hidden_field('checked_' . $group_boxes['admin_boxes_id'], $checkedBox) . tep_draw_hidden_field('unchecked_' . $group_boxes['admin_boxes_id'], $uncheckedBox); ?></b></td>
                    <?php 
                  }
                  ?>
                </tr>
                <tr class="dataTableRow">
                  <td class="dataTableContent">&nbsp;</td>
                  <td class="dataTableContent">
                    <table border="0" cellspacing="0" cellpadding="0">
                      <?php
                      //$group_boxes_files_query = tep_db_query("select admin_files_id, admin_files_name, admin_groups_id from " . TABLE_ADMIN_FILES . " where admin_files_is_boxes = '0' and admin_files_to_boxes = '" . $group_boxes['admin_boxes_id'] . "' order by admin_files_name");
                      while($group_boxes_files = tep_db_fetch_array($group_boxes_files_query)) {
                        $selectedGroups = $group_boxes_files['admin_groups_id'];
                        $groupsArray = explode(",", $selectedGroups);  

                        if (in_array($_GET['gPath'], $groupsArray)) {
                          $del_boxes = array($_GET['gPath']);
                          $result = array_diff ($groupsArray, $del_boxes);
                          sort($result);
                          $checkedBox = $selectedGroups;
                          $uncheckedBox = implode (",", $result);
                          $checked = true;
                        } else {
                          $add_boxes = array($_GET['gPath']);
                          $result = array_merge ($add_boxes, $groupsArray);
                          sort($result);
                          $checkedBox = implode (",", $result);
                          $uncheckedBox = $selectedGroups;
                          $checked = false;
                        }
                        ?>
                        <tr>
                          <?php
                          if ($group_boxes['admin_files_is_boxes'] == '1') {
                            ?>
                            <td class="dataTableContent" width="20"><?php echo tep_draw_checkbox_field('groups_to_boxes[]', $group_boxes_files['admin_files_id'], $checked, '', 'id="subgroups_' . $group_boxes['admin_boxes_id'] . '" onClick="checkSub(this)"'); ?></td>
                            <td class="dataTableContent"><?php echo $group_boxes_files['admin_files_name'] . ' ' . tep_draw_hidden_field('checked_' . $group_boxes_files['admin_files_id'], $checkedBox) . tep_draw_hidden_field('unchecked_' . $group_boxes_files['admin_files_id'], $uncheckedBox);?></td>
                            <?php 
                          } else {  
                            ?>
                            <td class="dataTableContentBlue" width="20"><?php echo tep_draw_checkbox_field('groups_to_boxes[]', $group_boxes_files['admin_files_id'], $checked, '', 'id="subgroups_' . $group_boxes['admin_boxes_id'] . '" onClick="checkSub(this)"'); ?></td>
                            <td class="dataTableContentBlue"><?php echo $group_boxes_files['admin_files_name'] . ' ' . tep_draw_hidden_field('checked_' . $group_boxes_files['admin_files_id'], $checkedBox) . tep_draw_hidden_field('unchecked_' . $group_boxes_files['admin_files_id'], $uncheckedBox);?></td>
                            <?php 
                          } 
                          ?>
                        </tr>
                        <?php
                      }
                      ?>
                    </table>
                  </td>
                </tr>
                <?php
                }
                ?>
              </tbody>
              </table></form>
              <?php
            } elseif ( isset($_GET['gID']) ) {
              ?>
              <table class="table table-striped table-hover w-100">
                <thead>
                  <tr class="tr-dark">
                    <th scope="col" class="text-left"><?php echo TABLE_HEADING_GROUPS_NAME; ?></th>
                    <th scope="col" class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                $db_groups_query = tep_db_query("select * from " . TABLE_ADMIN_GROUPS . " order by admin_groups_id");
                $add_groups_prepare = '\'0\'' ;
                $del_groups_prepare = '\'0\'' ;
                $count_groups = 0;
                while ($groups = tep_db_fetch_array($db_groups_query)) {
                  $add_groups_prepare .= ',\'' . $groups['admin_groups_id'] . '\'' ;
                  if ( ( ( ! isset($_GET['gID']) ) || ( isset($_GET['gID']) && $_GET['gID'] == $groups['admin_groups_id'] ) || ( isset($_GET['gID']) && $_GET['gID'] == 'groups') ) && ( ! isset($gInfo) ) ) {
                    $gInfo = new objectInfo($groups);
                  }

                  if ( ( isset($gInfo) && is_object($gInfo) ) && ($groups['admin_groups_id'] == $gInfo->admin_groups_id) ) {
                    echo '<tr class="table-row" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $groups['admin_groups_id'] . '&action=edit_group') . '\'">' . "\n";
                  } else {
                    echo '<tr class="rable-row" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $groups['admin_groups_id']) . '\'">' . "\n";
                    $del_groups_prepare .= ',\'' . $groups['admin_groups_id'] . '\'' ;
                  }
                  ?>
                    <td class="ext-left">&nbsp;<?php echo $groups['admin_groups_name']; ?></td>
                    <td class="text-right"><?php if ( ( isset($gInfo) && is_object($gInfo) ) && ($groups['admin_groups_id'] == $gInfo->admin_groups_id) ) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png'); } else { echo '<a href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $groups['admin_groups_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                  </tr>
                  <?php
                  $count_groups++;
                }
                ?>
                </tbody>
              </table>

              <table class="w-100">
                <tr>
                  <td><table class="w-100">
                    <tr>
                      <td class="smallText align-top pl-2"><?php echo TEXT_COUNT_GROUPS . $count_groups; ?></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td colspan="5" class="align-top text-right">
                    <div class="mr-2 mt-2 mb-2" role="group">
                      <button class="btn btn-primary btn-sm mr-1" onclick="window.location='<?php echo tep_href_link(FILENAME_ADMIN_MEMBERS); ?>'"><?php echo HEADING_TITLE_MEMBERS; ?></button> 
                      <button class="btn btn-success btn-sm mr-1" data-toggle="popover" data-trigger="focus" data-placement="top" title="<?php echo TEXT_UPGRADE_TO_PRO; ?>" data-content="<?php echo TEXT_UPGRADE_ADMIN_GROUPS; ?>"><?php echo IMAGE_NEW_GROUP; ?></button> 
                      <button class="btn btn-primary btn-sm mr-1" onclick="window.location='<?php echo tep_href_link(FILENAME_ADMIN_MEMBERS, 'action=new_group&gID=groups'); ?>'">NG</button>
                    </div>
                  </td>                 
                <tr>
              </table>              
              <div id="popup-message-container" style="display: none;"></div>
              <?php
            } else {
              ?>
              <table class="table table-striped table-hover w-100">
                <thead>
                  <tr class="tr-dark">
                    <th scope="col" class="text-left"><?php echo TABLE_HEADING_NAME; ?></th>
                    <th scope="col" class="text-left"><?php echo TABLE_HEADING_EMAIL; ?></th>
                    <th scope="col" class="text-left"><?php echo TABLE_HEADING_GROUPS; ?></th>
                    <th scope="col" class="text-center"><?php echo TABLE_HEADING_LOGNUM; ?></th>
                    <th scope="col" class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $db_admin_query_raw = "select * from " . TABLE_ADMIN . " order by admin_firstname";
                  $db_admin_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $db_admin_query_raw, $db_admin_query_numrows);
                  $db_admin_query = tep_db_query($db_admin_query_raw);
                  //$db_admin_num_row = tep_db_num_rows($db_admin_query);
                  while ($admin = tep_db_fetch_array($db_admin_query)) {
                    $admin_group_query = tep_db_query("select admin_groups_name from " . TABLE_ADMIN_GROUPS . " where admin_groups_id = '" . $admin['admin_groups_id'] . "'");
                    $admin_group = tep_db_fetch_array ($admin_group_query);
                    if ($admin_group === false) $admin_group = array();
                    if ( ( ( ! isset($_GET['mID']) ) || ( isset($_GET['mID']) && $_GET['mID'] == $admin['admin_id']) ) && ( ! isset($mInfo) ) ) {
                      $mInfo_array = array_merge($admin, $admin_group);
                      $mInfo = new objectInfo($mInfo_array);
                    }                    

                    if ( (is_object($mInfo)) && ($admin['admin_id'] == $mInfo->admin_id) ) {
                      echo '<tr class="table-row" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'action=edit_member' . '&mID=' . $admin['admin_id'] . setPage()) . '\'">' . "\n";
                    } else {
                      echo '<tr class="table-row" onclick="document.location.href=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, '&mID=' . $admin['admin_id'] . setPage()) . '\'">' . "\n";
                    }
                    ?>
                      <td class="text-left">&nbsp;<?php echo $admin['admin_firstname']; ?>&nbsp;<?php echo $admin['admin_lastname']; ?></td>
                      <td class="text-left"><?php echo $admin['admin_email_address']; ?></td>
                      <td class="text-left"><?php echo $admin_group['admin_groups_name']; ?></td>
                      <td class="text-center"><?php echo $admin['admin_lognum']; ?></td>
                      <td class="text-right"><?php echo (isset($mInfo) && $admin['admin_id'] == $mInfo->admin_id) ? tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png') : '<a href="' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'mID=' . $admin['admin_id'] . setPage()) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; ?></td>
                    </tr>
                    <?php
                  }
                  ?>
                </tbody>
              </table>
              <table class="w-100">
                <tr>
                  <td colspan="5"><table class="w-100">
                    <tr>
                      <td class="smallText align-top pl-2"><?php echo $db_admin_split->display_count($db_admin_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_MEMBERS); ?></td>
                      <td class="smallText text-right align-top pr-2"><?php echo $db_admin_split->display_links($db_admin_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td colspan="5" class="align-top text-right">
                    <div class="mr-2 mt-2 mb-2" role="group">
                      <button class="btn btn-success btn-sm mr-1" data-toggle="popover" data-trigger="focus" data-placement="top" title="<?php echo TEXT_UPGRADE_TO_PRO; ?>" data-content="<?php echo TEXT_UPGRADE_ADMIN_GROUPS; ?>"><?php echo IMAGE_NEW_GROUP; ?></button> 
                      <button class="btn btn-success btn-sm" data-toggle="popover" data-trigger="focus" data-placement="top" title="<?php echo TEXT_UPGRADE_TO_PRO; ?>" data-content="<?php echo TEXT_UPGRADE_ADMIN_MEMBERS; ?>"><?php echo IMAGE_NEW_MEMBER; ?></button> 
                      <button class="btn btn-primary btn-sm mr-1" onclick="window.location='<?php echo tep_href_link(FILENAME_ADMIN_MEMBERS, 'action=new_group&gID=groups' . setPage()); ?>'">NG</button>
                      <button class="btn btn-primary btn-sm" onclick="window.location='<?php echo tep_href_link(FILENAME_ADMIN_MEMBERS, 'action=new_member' . setPage()); ?>'">NM</button> 
                    </div>
                  </td>                 
                <tr>
              </table>
              <div id="popup-message-container" style="display: none;"></div>
              <?php
            }
            ?>
          </td>
          <?php
          $heading = array();
          $contents = array();
          switch ($_GET['action']) {
            case 'new_member':
              $heading[] = array('text' => '<div class="sidebar-heading md-dark"><span class="align-middle ml-2">' . TEXT_INFO_HEADING_NEW . '</span></div>');
              $contents = array('form' => tep_draw_form('newmember', FILENAME_ADMIN_MEMBERS, 'action=member_new&mID=' . $_GET['mID'] . setPage(), 'post', 'class="member-form" id="newmember" role="form"'));
              if ( isset($_GET['error']) ) $contents[] = array('text' => '<div class="text-white mt-2 p-4 bg-danger">' . TEXT_INFO_ERROR . '</div>');
              $contents[] = array('text' => '<div class="form-label mt-2">' . TEXT_INFO_FIRSTNAME . '</div><div>' . tep_draw_input_field('admin_firstname', null, 'class="form-control"') . '</div>');
              $contents[] = array('text' => '<div class="form-label mt-2">' . TEXT_INFO_LASTNAME . '</div><div>' . tep_draw_input_field('admin_lastname', null, 'class="form-control"') . '</div>');
              $contents[] = array('text' => '<div class="form-label mt-2">' . TEXT_INFO_EMAIL . '</div><div>' . tep_draw_input_field('admin_email_address', null, 'class="form-control"') . '</div>');
              $groups_array = array(array('id' => '0', 'text' => TEXT_NONE));
              $groups_query = tep_db_query("select admin_groups_id, admin_groups_name from " . TABLE_ADMIN_GROUPS);
              while ($groups = tep_db_fetch_array($groups_query)) {
                $groups_array[] = array('id' => $groups['admin_groups_id'],
                                        'text' => $groups['admin_groups_name']);
              }
              $contents[] = array('text' => '<div class="form-label mt-2">' . TEXT_INFO_GROUP . '</div><div>' . tep_draw_pull_down_menu('admin_groups_id', $groups_array, '0', 'class="form-control"') . '</div>');
              $contents[] = array('align' => 'center', 'text' => '<button class="btn btn-default btn-sm mt-3 mb-3 mr-1" type="button" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'mID=' . $_GET['mID'] . setPage()) . '\'">' . IMAGE_CANCEL . '</button><button class="btn btn-primary btn-sm mt-3 mb-3" type="submit">' . IMAGE_SAVE . '</button>');
              break;
            case 'edit_member':
              $heading[] = array('text' => '<div class="sidebar-heading md-dark"><span class="align-middle ml-2">' . TEXT_INFO_HEADING_EDIT . '</span></div>');
              $contents = array('form' => tep_draw_form('editmember', FILENAME_ADMIN_MEMBERS, 'action=member_edit&&mID=' . $_GET['mID'] . setPage(), 'post', 'class="member-form" id="editmember" role="form"'));
              if ( isset($_GET['error']) ) $contents[] = array('text' => '<div class="text-white mt-2 p-4 bg-danger">' . TEXT_INFO_ERROR . '</div>');
              $contents[] = array('text' => tep_draw_hidden_field('admin_id', $mInfo->admin_id));
              $contents[] = array('text' => '<div class="form-label mt-2">' . TEXT_INFO_FIRSTNAME . '</div><div>' . tep_draw_input_field('admin_firstname', $mInfo->admin_firstname) . '</div>');
              $contents[] = array('text' => '<div class="form-label mt-2">' . TEXT_INFO_LASTNAME . '</div><div>' . tep_draw_input_field('admin_lastname', $mInfo->admin_lastname) . '</div>');
              $contents[] = array('text' => '<div class="form-label mt-2">' . TEXT_INFO_EMAIL . '</div><div>' . tep_draw_input_field('admin_email_address', $mInfo->admin_email_address) . '</div>');
              if ($mInfo->admin_id == $_SESSION['login_id'] || $mInfo->admin_email_address == STORE_OWNER_EMAIL_ADDRESS) {
                $contents[] = array('text' => tep_draw_hidden_field('admin_groups_id', $mInfo->admin_groups_id));
              } else {
                $groups_array = array(array('id' => '0', 'text' => TEXT_NONE));
                $groups_query = tep_db_query("select admin_groups_id, admin_groups_name from " . TABLE_ADMIN_GROUPS);
                while ($groups = tep_db_fetch_array($groups_query)) {
                  $groups_array[] = array('id' => $groups['admin_groups_id'],
                                          'text' => $groups['admin_groups_name']);
                }
                $contents[] = array('text' => '<div class="form-label mt-2">' . TEXT_INFO_GROUP . '</div><div>' . tep_draw_pull_down_menu('admin_groups_id', $groups_array, $mInfo->admin_groups_id) . '</div>');
              }
              $contents[] = array('align' => 'center', 'text' => '<button class="btn btn-default btn-sm mt-3 mb-3 mr-1" type="button" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'mID=' . $_GET['mID'] . setPage()) . '\'">' . IMAGE_CANCEL . '</button><button class="btn btn-primary btn-sm mt-3 mb-3" type="submit">' . IMAGE_UPDATE . '</button>');
              break;
            case 'del_member':
              $heading[] = array('text' => '<div class="sidebar-heading md-dark"><span class="align-middle ml-2">' . TEXT_INFO_HEADING_DELETE . '</span></div>');
              if ($mInfo->admin_id == $_SESSION['login_id'] || $mInfo->admin_email_address == STORE_OWNER_EMAIL_ADDRESS) {
                $contents[] = array('text' => '<div class="mt-2 mb-2">' . TEXT_INFO_DELETE_MAIN_ADMIN . '</div>');                
                $contents[] = array('align' => 'center', 'text' => '<button class="btn btn-primary btn-sm mt-2 mb-2" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'mID=' . $mInfo->admin_id . setPage()) . '\'">' . IMAGE_BACK . '</button>');
              } else {
                $contents = array('form' => tep_draw_form('delete', FILENAME_ADMIN_MEMBERS, 'action=member_delete&mID=' . $mInfo->admin_id . setPage(), 'post'));
                $contents[] = array('text' => tep_draw_hidden_field('admin_id', $mInfo->admin_id));
                $contents[] = array('text' => '<div class="mt-2 mb-2">' . sprintf(TEXT_INFO_DELETE_INTRO, $mInfo->admin_firstname . ' ' . $mInfo->admin_lastname) . '</div>');
                $contents[] = array('align' => 'center', 'text' => '<button type="button" class="btn btn-default btn-sm mr-2 mt-2 mb-4" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'mID=' . $mInfo->admin_id . setPage()) . '\'">' . IMAGE_CANCEL . '</button><button class="btn btn-danger btn-sm mt-2 mb-4" type="submit">' . IMAGE_CONFIRM_DELETE . '</button>');
              }
              break;
            case 'new_group':
              $heading[] = array('text' => '<div class="sidebar-heading md-dark"><span class="align-middle ml-2">' . TEXT_INFO_HEADING_GROUPS . '</span></div>');
              $contents = array('form' => tep_draw_form('newgroup', FILENAME_ADMIN_MEMBERS, 'action=group_new&gID=' . $gInfo->admin_groups_id, 'post', 'id="newgroup" role="form"'));
              if (isset($_GET['gName']) && $_GET['gName'] == 'false') {

                $contents[] = array('text' => '<div class="text-white mt-2 p-4 bg-danger">' . TEXT_INFO_GROUPS_NAME_FALSE . '</div>');
              } elseif (isset($_GET['gName']) && $_GET['gName'] == 'used') {
                $contents[] = array('text' => '<div class="text-white mt-2 p-4 bg-danger">' . TEXT_INFO_GROUPS_NAME_USED . '</div>');
              }
              $contents[] = array('text' => tep_draw_hidden_field('set_groups_id', substr($add_groups_prepare, 4)) );
              $contents[] = array('text' => '<div class="form-label mt-2 mb-2">' . TEXT_INFO_GROUPS_NAME . '</div>');
              $contents[] = array('text' => '<div>' . tep_draw_input_field('admin_groups_name') . '</div>');
              $contents[] = array('align' => 'center', 'text' => '<button class="btn btn-default btn-sm mt-3 mb-3 mr-1" type="button" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $gInfo->admin_groups_id) . '\'">' . IMAGE_CANCEL . '</button><button class="btn btn-primary btn-sm mt-3 mb-3" type="submit">' . IMAGE_SAVE . '</button>');

              break;
            case 'edit_group':
              $heading[] = array('text' => '<div class="sidebar-heading md-dark"><span class="align-middle ml-2">' . TEXT_INFO_HEADING_EDIT_GROUP . '</span></div>');            
              $contents = array('form' => tep_draw_form('editgroup', FILENAME_ADMIN_MEMBERS, 'action=group_edit&gID=' . $_GET['gID'], 'post', 'class="group-form" id="editgroup" role="form"'));
              if (isset($_GET['gName']) && $_GET['gName'] == 'false') {
                $contents[] = array('text' => '<div class="text-white mt-2 p-4 bg-danger">' . TEXT_INFO_GROUPS_NAME_FALSE . '</div>');
              } elseif (isset($_GET['gName']) && $_GET['gName'] == 'used') {
                $contents[] = array('text' => '<div class="text-white mt-2 p-4 bg-danger">' . TEXT_INFO_GROUPS_NAME_USED . '</div>');
              }
              $contents[] = array('text' => '<div class="form-label mt-2 mb-2">' . TEXT_INFO_EDIT_GROUP_INTRO . '</div><div>' . tep_draw_input_field('admin_groups_name', $gInfo->admin_groups_name) . '</div>');
              $contents[] = array('align' => 'center', 'text' => '<button class="btn btn-default btn-sm mt-3 mb-3 mr-2" type="button" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $gInfo->admin_groups_id) . '\'">' . IMAGE_CANCEL . '</button><button class="btn btn-primary btn-sm mt-3 mb-3" type="submit">' . IMAGE_UPDATE . '</button>');              
              break;
            case 'del_group':
              $heading[] = array('text' => '<div class="sidebar-heading md-dark"><span class="align-middle ml-2">' . TEXT_INFO_HEADING_DELETE_GROUPS . '</span></div>');            
              $contents = array('form' => tep_draw_form('deletegroup', FILENAME_ADMIN_MEMBERS, 'action=group_delete&gID=' . $gInfo->admin_groups_id, 'post', 'id="deletegroup" role="form"'));
              if ($gInfo->admin_groups_id == 1) {
                $contents[] = array('text' => '<div class="text-white mt-2 p-4 bg-danger">' . sprintf(TEXT_INFO_DELETE_GROUPS_INTRO_NOT, $gInfo->admin_groups_name) . '</div>');
                $contents[] = array('align' => 'center', 'text' => '<button class="btn btn-default btn-sm mt-3 mb-3 mr-2" type="button" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET['gID']) . '\'">' . IMAGE_BACK . '</button>');            
              } else {
                $contents[] = array('text' => tep_draw_hidden_field('set_groups_id', substr($del_groups_prepare, 4)) );
                $contents[] = array('text' => '<div class="text-white mt-2 p-4 bg-danger">' . sprintf(TEXT_INFO_DELETE_GROUPS_INTRO, $gInfo->admin_groups_name) . '</div>');
                $contents[] = array('align' => 'center', 'text' => '<button class="btn btn-default btn-sm mt-3 mb-3 mr-2" type="button" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET['gID']) . '\'">' . IMAGE_CANCEL . '</button><button class="btn btn-danger btn-sm mt-3 mb-3" type="submit">' . IMAGE_CONFIRM_DELETE . '</button>');                 
              }
              break;
            case 'define_group':
              $heading[] = array('text' => '<div class="sidebar-heading md-dark"><span class="align-middle ml-2">' . TEXT_INFO_HEADING_DEFINE . '</span></div>');            
              if ($_GET['gPath'] == 1) {
                $contents[] = array('text' => '<div class="text-white mt-2 mb-2 p-4 bg-danger">' . sprintf(TEXT_INFO_DEFINE_INTRO_1, $group_name['admin_groups_name']) . '</div>');
                $contents[] = array('text' => '<div>' . TEXT_INFO_DEFINE_TYPE . '</div>');
                $contents[] = array('align' => 'center', 'text' => '<button class="btn btn-default btn-sm mt-3 mb-3 mr-2" type="button" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET['gPath']) . '\'">' . IMAGE_CANCEL . '</button>');                 
              } else {
                $contents[] = array('text' => '<div class="mt-2 mb-2">' . sprintf(TEXT_INFO_DEFINE_INTRO, $group_name['admin_groups_name']) . '</div>');
                $contents[] = array('text' => '<div class="mb-2">' . TEXT_INFO_DEFINE_TYPE . '</div>');
                $contents[] = array('align' => 'center', 'text' => ($_GET['gPath'] != 1) ? '<button class="btn btn-default btn-sm mt-3 mb-3 mr-2" type="button" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET['gPath']) . '\'">' . IMAGE_CANCEL . '</button><button class="btn btn-primary btn-sm mt-3 mb-3" type="submit">' . IMAGE_UPDATE . '</button>' : '<button class="btn btn-default btn-sm mt-3 mb-3 mr-2" type="button" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $_GET['gPath']) . '\'">' . IMAGE_CANCEL . '</button>');                 
             }
              break;
            case 'show_group':
              $heading[] = array('text' => '<div class="sidebar-heading md-dark"><span class="align-middle ml-2">' . TEXT_INFO_HEADING_EDIT_GROUP . '</span></div>');            
              $check_email_query = tep_db_query("SELECT admin_email_address FROM " . TABLE_ADMIN . "");
              //$stored_email[];
              while ($check_email = tep_db_fetch_array($check_email_query)) {
                $stored_email[] = $check_email['admin_email_address'];
              }
              if (in_array($_POST['admin_email_address'], $stored_email)) {
                $checkEmail = "true";
              } else {
                $checkEmail = "false";
              }
              $contents = array('form' => tep_draw_form('show_group', FILENAME_ADMIN_MEMBERS, 'action=show_group&gID=groups', 'post', 'enctype="multipart/form-data"'));
              $contents[] = array('text' => '<div class="form-label mt-2 mb-2">' . $define_files['admin_files_name'] . '</div><div>' . tep_draw_input_field('level_edit', $checkEmail) . '</div>');
              break;
            default:
              if ( isset($mInfo) && is_object($mInfo) ) {              
                $heading[] = array('text' => '<div class="sidebar-heading md-dark"><span class="align-middle ml-2">' . TEXT_INFO_HEADING_DEFAULT . '</span></div>');
                $contents[] = array('align' => 'center', 'text' => '<div class="mt-2 mb-2"><button class="btn btn-primary btn-sm mr-2" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'action=edit_member&mID=' . $mInfo->admin_id . setPage()) . '\'">' . IMAGE_EDIT . '</button><button class="btn btn-danger btn-sm" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'action=del_member&mID=' . $mInfo->admin_id . setPage()) . '\'">' . IMAGE_DELETE . '</button></div>');
                $contents[] = array('text' => TEXT_INFO_FULLNAME . ' <b><br>' . $mInfo->admin_firstname . ' ' . $mInfo->admin_lastname . '</b>');
                $contents[] = array('text' => TEXT_INFO_EMAIL . ' <b><br>' . $mInfo->admin_email_address . '</b>');
                $contents[] = array('text' => TEXT_INFO_GROUP . ' <b><br>' . $mInfo->admin_groups_name . '</b>');
                $contents[] = array('text' => TEXT_INFO_CREATED . ' <b><br>' . $mInfo->admin_created . '</b>');
                $contents[] = array('text' => TEXT_INFO_MODIFIED . ' <b><br>' . $mInfo->admin_modified . '</b>');
                $contents[] = array('text' => TEXT_INFO_LOGDATE . '<b><br>' . $mInfo->admin_logdate . '</b>');
                $contents[] = array('text' => TEXT_INFO_LOGNUM . ' <b><br>' . $mInfo->admin_lognum . '</b>');
                $contents[] = array('text' => '<br>');
              } elseif ( isset($gInfo) && is_object($gInfo) ) {
                $heading[] = array('text' => '<div class="sidebar-heading md-dark"><span class="align-middle ml-2">' . TEXT_INFO_HEADING_DEFAULT_GROUPS . '</span></div>');
                $contents[] = array('align' => 'center', 'text' => '<button class="btn btn-primary btn-sm mr-2 mt-2" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $gInfo->admin_groups_id . '&action=edit_group') . '\'">' . IMAGE_EDIT . '</button><button class="btn btn-danger btn-sm mt-2" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gID=' . $gInfo->admin_groups_id . '&action=del_group') . '\'">' . IMAGE_DELETE . '</button>');
                $contents[] = array('align' => 'center', 'text' => '<button class="btn btn-primary btn-sm mt-2" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_MEMBERS, 'gPath=' . $gInfo->admin_groups_id . '&action=define_group') . '\'">' . IMAGE_FILE_PERMISSIONS . '</button>');
                $contents[] = array('text' => '<div class="mt-2">' . TEXT_INFO_DEFAULT_GROUPS_INTRO . '</div>');
              }
          }
          if ( tep_not_null($contents) ) {
            echo '<td width="25%" valign="top">' . "\n";
            $box = new box;
            echo $box->infoBox($heading, $contents);
            echo '</td>' . "\n";
          }
          ?>
        </tr>
      </table>
      <!-- end body_text //-->
    </div>
    <!-- end panel -->
  </div>
</div>
<script>
$(document).ready(function(){
  $('.member-form').on('submit',function(event){
    // block form submit event
    event.preventDefault();
    // check form input
    var errors = '';
    var isValidEmail = false;
    var emailExists = false;
    var firstname = $(".member-form input[name=admin_firstname]").val();
    var lastname = $(".member-form input[name=admin_lastname]").val();
    var email = $(".member-form input[name=admin_email_address]").val();
    var gID = $(".member-form select[name=admin_groups_id]").val();

    if (firstname.length < 1) errors += '<?php echo JS_ALERT_FIRSTNAME; ?>';
    if (lastname.length < 1) errors += '<?php echo JS_ALERT_LASTNAME; ?>';
    if (email.length < 1) errors += '<?php echo JS_ALERT_EMAIL; ?>';
    if (gID == 0 || gID == null) errors += '<?php echo JS_ALERT_GROUP_LEVEL; ?>';
    
    if (email.length > 1) {
      isValidEmail = isEmail(email);
      if (!isValidEmail) errors += '<?php echo JS_ALERT_EMAIL_FORMAT; ?>';
    }   

    if (errors != '') {
      alert('<?php echo JS_ALERT_INTRO; ?>' + errors);
      return false;
    }  

    event.currentTarget.submit();  
  });  
});   

function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}     
</script>
<?php 
include(DIR_WS_INCLUDES . 'html_bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>