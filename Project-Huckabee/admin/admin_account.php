<?php
/*
  $Id: admin_account.php,v 6.5.4 2017/12/17 01:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2017 Loaded Commerce
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

$current_boxes = DIR_FS_ADMIN . DIR_WS_BOXES;

if (isset($_GET['action']) && $_GET['action']) {
  switch ($_GET['action']) {
    case 'check_password':
      $check_pass_query = tep_db_query("select admin_password as confirm_password from " . TABLE_ADMIN . " where admin_id = '" . $_POST['id_info'] . "'");
      $check_pass = tep_db_fetch_array($check_pass_query);
      
      // Check that password is good
      if (!tep_validate_password($_POST['password_confirmation'], $check_pass['confirm_password'])) {
        tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT, 'action=check_account&error=password'));
      } else {
        //$confirm = 'confirm_account';
        $_SESSION['confirm_account']= true;
        tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT, 'action=edit_process'));
      }
      break;    

    case 'save_account':
      // verify password is hardened password
      if (isset($_POST['admin_password']) && $_POST['admin_password'] != null) {
          $admin_password_length = ( ENTRY_PASSWORD_MIN_LENGTH < 8 ) ? 8 : ENTRY_PASSWORD_MIN_LENGTH;
          if(!preg_match('/^(?=^.{' . $admin_password_length . ',}$)((?=.*[A-Za-z0-9])(?=.*[A-Z])(?=.*[a-z]))^.*$/', $_POST['admin_password'])){
              tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT, 'action=edit_process&error=password'));    
        }
      }
      $admin_id = tep_db_prepare_input($_POST['id_info']);
      $admin_email_address = tep_db_prepare_input($_POST['admin_email_address']);
      $stored_email[] = 'NONE';
      $hiddenPassword = '-hidden-';
      
      $check_email_query = tep_db_query("select admin_email_address from " . TABLE_ADMIN . " where admin_id <> " . $admin_id . "");
      while ($check_email = tep_db_fetch_array($check_email_query)) {
        $stored_email[] = $check_email['admin_email_address'];
      }
      
      if (in_array($_POST['admin_email_address'], $stored_email)) {
        tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT, 'action=edit_process&error=email'));
      } else {
        $sql_data_array = array('admin_firstname' => tep_db_prepare_input($_POST['admin_firstname']),
                                'admin_lastname' => tep_db_prepare_input($_POST['admin_lastname']),
                                'admin_email_address' => tep_db_prepare_input($_POST['admin_email_address']),
                                'admin_password' => tep_encrypt_password(tep_db_prepare_input($_POST['admin_password'])),
                                'admin_modified' => 'now()');
      
        tep_db_perform(TABLE_ADMIN, $sql_data_array, 'update', 'admin_id = \'' . $admin_id . '\'');

        tep_mail($_POST['admin_firstname'] . ' ' . $_POST['admin_lastname'], $_POST['admin_email_address'], ADMIN_EMAIL_SUBJECT, sprintf(ADMIN_EMAIL_TEXT, $_POST['admin_firstname'], HTTP_SERVER . DIR_WS_ADMIN, $_POST['admin_email_address'], $hiddenPassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      
        tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT, 'page=' . $_GET['page'] . '&mID=' . $admin_id));
      }
      break;
  }
}

include(DIR_WS_INCLUDES . 'html_top.php');
include(DIR_WS_INCLUDES . 'header.php');
include(DIR_WS_INCLUDES . 'column_left.php');
?>
<div id="content" class="content">         
  <h1 class="page-header"><i class="fa fa-laptop"></i> <?php echo HEADING_TITLE; ?></h1>
  <div>     
    <!-- begin panel -->
    <div class="panel panel-inverse">
      <!-- body_text //-->    

      <?php 
      if (isset($_GET['action']) && $_GET['action'] == 'edit_process') { 
        echo tep_draw_form('account', FILENAME_ADMIN_ACCOUNT, 'action=save_account', 'post', 'enctype="multipart/form-data"', 'SSL'); 
      } else if (isset($_GET['action']) && $_GET['action'] == 'check_account') { 
        echo tep_draw_form('account', FILENAME_ADMIN_ACCOUNT, 'action=check_password', 'post', 'enctype="multipart/form-data"', 'SSL'); 
      } else { 
        echo tep_draw_form('account', FILENAME_ADMIN_ACCOUNT, 'action=check_account', 'post', 'enctype="multipart/form-data"', 'SSL'); 
      } 
      ?>
      <table class="w-100">
        <tr>
          <td class="align-top">
            <?php
            $my_account_query = tep_db_query ("select a.admin_id, a.admin_firstname, a.admin_lastname, a.admin_email_address, a.admin_created, a.admin_modified, a.admin_logdate, a.admin_lognum, g.admin_groups_name from " . TABLE_ADMIN . " a, " . TABLE_ADMIN_GROUPS . " g where a.admin_id= " . $_SESSION['login_id'] . " and g.admin_groups_id= " . $_SESSION['login_groups_id'] . "");
            $myAccount = tep_db_fetch_array($my_account_query);
            ?>
            <table class="w-100 table">
              <?php
              if ( (isset($_GET['action']) && $_GET['action'] == 'edit_process') && (isset($_SESSION['confirm_account'])) ) {
                ?>
                <tr class="table-row">
                  <td class="form-label"><?php echo TEXT_INFO_FIRSTNAME; ?></td>
                  <td class="text-left"><?php echo tep_draw_input_field('admin_firstname', $myAccount['admin_firstname']); ?></td>
                </tr>
                <tr class="table-row">
                  <td class="dataTableContent"><nobr><?php echo TEXT_INFO_LASTNAME; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                  <td class="dataTableContent"><?php echo tep_draw_input_field('admin_lastname', $myAccount['admin_lastname']); ?></td>
                </tr>
                <tr class="table-row">
                  <td class="dataTableContent"><nobr><?php echo TEXT_INFO_EMAIL; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                  <td class="dataTableContent"><?php if ($_GET['error'] == 'email') { echo tep_draw_input_field('admin_email_address', $myAccount['admin_email_address']) . ' <nobr>' . TEXT_EMAIL_ERROR . '</nobr>'; } else { echo tep_draw_input_field('admin_email_address', $myAccount['admin_email_address']); } ?></td>
                </tr>
                <tr class="table-row">  
                  <td class="dataTableContent"><nobr><?php echo TEXT_INFO_PASSWORD; ?>&nbsp;&nbsp;&nbsp;</nobr></td>                                                     
                  <td class="dataTableContent"><?php if ($_GET['error'] == 'password') { echo tep_draw_password_field('admin_password', $myAccount['admin_password']) . ' <nobr>' . TEXT_PASSWORD_ERROR . '</nobr>'; } else { echo tep_draw_password_field('admin_password', $myAccount['admin_password']); } ?></td>
                </tr>
                <tr class="table-row">
                  <td class="dataTableContent"><nobr><?php echo TEXT_INFO_PASSWORD_CONFIRM; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                  <td class="dataTableContent"><?php echo tep_draw_password_field('admin_password_confirm'); ?></td>
                </tr>
                <?php
              } else {
                if (isset($_SESSION['confirm_account'])) {
                  unset($_SESSION['confirm_account']);
                }
                ?>                        
                <tr class="table-row">
                  <td class="form-label"><?php echo TEXT_INFO_FULLNAME; ?></td>
                  <td class="text-left"><?php echo $myAccount['admin_firstname'] . ' ' . $myAccount['admin_lastname']; ?></td>
                </tr>
                <tr class="table-row">
                  <td class="form-label"><?php echo TEXT_INFO_EMAIL; ?></td>
                  <td class="dataTableContent"><?php echo $myAccount['admin_email_address']; ?></td>
                </tr>
                <tr class="table-row">
                  <td class="form-label"><?php echo TEXT_INFO_PASSWORD; ?></td>
                  <td class="text-left"><?php echo TEXT_INFO_PASSWORD_HIDDEN; ?></td>
                </tr>
                <tr class="table-row table-rowSelected">
                  <td class="form-label"><?php echo TEXT_INFO_GROUP; ?></nobr></td>
                  <td class="text-left"><?php echo $myAccount['admin_groups_name']; ?></td>
                </tr>
                <tr class="table-row">
                  <td class="form-label"><?php echo TEXT_INFO_CREATED; ?></td>
                  <td class="text-left"><?php echo $myAccount['admin_created']; ?></td>
                </tr>
                <tr class="table-row">
                    <td class="form-label"><?php echo TEXT_INFO_LOGNUM; ?></td>
                    <td class="text-left"><?php echo $myAccount['admin_lognum']; ?></td>
                  </tr>
                <tr class="table-row">
                  <td class="form-label"><?php echo TEXT_INFO_LOGDATE; ?></td>
                  <td class="text-left"><?php echo $myAccount['admin_logdate']; ?></td>
                </tr>
                <?php
              }
              ?>                       
            </table>
            <!--table class="w-100">
              <tr>
                <td><table class="w-100">
                  <tr>
                    <td class="smallText" valign="top"><?php echo TEXT_INFO_MODIFIED . $myAccount['admin_modified']; ?></td>
                    <td align="right">
                      <?php 
                      $buttons = '';
                      if (isset($_GET['action']) && $_GET['action'] == 'edit_process') { 
                        echo '<button class="btn btn-danger btn-sm" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_ACCOUNT) . '\'">' . IMAGE_BACK . '</button>';
                        if (isset($_SESSION['confirm_account'])) {
                          echo '<button class="btn btn-primary btn-sm mt-3 mb-3" type="submit">' . IMAGE_SAVE . '</button>';
                        } 
                      } elseif (isset($_GET['action']) && $_GET['action'] == 'check_account') { 
                        echo '&nbsp;'; 
                      } else { 
                        echo '<button class="btn btn-primary btn-sm mt-3 mb-3" type="submit">' . IMAGE_EDIT . '</button>';
                      } ?>                      
                    </td>
                  </tr>
                </table></td>
              </tr>              
            </table -->
          </td>
          <?php
          $heading = array();
          $contents = array();

          if (isset($_GET['action']) && $_GET['action'] == 'edit_process') { 
            $buttons = '<button class="btn btn-default btn-sm" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_ACCOUNT) . '\'">' . IMAGE_BACK . '</button>';
            if (isset($_SESSION['confirm_account'])) {
              $buttons .= '<button class="btn btn-primary btn-sm mt-3 mb-3" type="submit">' . IMAGE_SAVE . '</button>';
            } 
          } elseif (isset($_GET['action']) && $_GET['action'] == 'check_account') { 
            $buttons = '&nbsp;'; 
          } else { 
            $buttons = '<button class="btn btn-primary btn-sm mt-3 mb-3" type="submit">' . IMAGE_EDIT . '</button>';
          } 


          $action = (isset($_GET['action']) ? $_GET['action'] : ''); 
          switch ($action) {
            case 'edit_process':
              $heading[] = array('text' => '<b>&nbsp;' . TEXT_INFO_HEADING_DEFAULT . '</b>');
              
              $contents[] = array('text' => TEXT_INFO_INTRO_EDIT_PROCESS . tep_draw_hidden_field('id_info', $myAccount['admin_id']));
              //$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ADMIN_ACCOUNT) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> ' . tep_image_submit('button_confirm.gif', IMAGE_CONFIRM, 'onClick="validateForm();return document.returnValue"') . '<br>&nbsp');
              break; 
            case 'check_account':
              $heading[] = array('text' => '<div class="sidebar-heading md-dark"><span class="align-middle ml-2">' . TEXT_INFO_HEADING_CONFIRM_PASSWORD . '</span></div>');
              $contents[] = array('text' => '<div class="form-label mb-2 mt-2>' . TEXT_INFO_INTRO_CONFIRM_PASSWORD . '</div><div>' . tep_draw_hidden_field('id_info', $myAccount['admin_id']) . '</div>');
              if ( isset($_GET['error']) ) $contents[] = array('text' => '<div class="text-white mt-2 p-4 bg-danger">' . TEXT_INFO_INTRO_CONFIRM_PASSWORD_ERROR . '</div>');
              $contents[] = array('text' => '<div class="mt-2 mb-2">' . tep_draw_password_field('password_confirmation') . '</div>');             
              $contents[] = array('align' => 'center', 'text' => '<button class="btn btn-default btn-sm mr-1" onclick="window.location=\'' . tep_href_link(FILENAME_ADMIN_ACCOUNT) . '\'">' . IMAGE_BACK . '</button><button class="btn btn-primary btn-sm mt-3 mb-3" type="submit">' . IMAGE_CONFIRM . '</button>');
              break; 
            default:
              $heading[] = array('text' => '<div class="sidebar-heading md-dark"><span class="align-middle ml-2">' . TEXT_INFO_HEADING_DEFAULT . '</span></div>');
              $contents[] = array('align' => 'center', 'text' => $buttons);
              $contents[] = array('text' => '<div class="mb-2 mb-2">' . TEXT_INFO_INTRO_DEFAULT . '</div>');
              $contents[] = array('text' => '<div class="mt-2 mb-2">' . TEXT_INFO_MODIFIED . $myAccount['admin_modified'] . '</div>');

              if ($myAccount['admin_email_address'] == 'admin@localhost') {
                $contents[] = array('text' => sprintf(TEXT_INFO_INTRO_DEFAULT_FIRST, $myAccount['admin_firstname']) . '<br>&nbsp');
              } elseif (($myAccount['admin_modified'] == '0000-00-00 00:00:00') || ($myAccount['admin_logdate'] <= 1) ) {
                $contents[] = array('text' => sprintf(TEXT_INFO_INTRO_DEFAULT_FIRST_TIME, $myAccount['admin_firstname']) . '<br>&nbsp');
              }
              
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
    </table></form>



      <!-- end body_text //-->
    </div>
    <!-- end panel -->
  </div>
</div>
<script>
$(document).ready(function(){

});   
</script>
<?php 
include(DIR_WS_INCLUDES . 'html_bottom.php');
require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>