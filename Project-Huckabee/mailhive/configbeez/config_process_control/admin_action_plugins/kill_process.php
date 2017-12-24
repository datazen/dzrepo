<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 MailBeez
	
	inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
	
	v1.0
*/

$plugin_actions = mb_admin_button(mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=' . 'config_process_control/admin_application_plugins/kill_process.php'), MAILBEEZ_BUTTON_PROCESS_CONTROL_KILL, '', 'link') . '';


if ($_GET['clear'] == 'ok') {
    $config_template_msg = '<b>' . MAILBEEZ_ACTION_PROCESS_CONTROL_KILL_OK . '</b><br>';
}



$contents[] = array('text' => '<div class="mb_action_box">' . mh_image(DIR_WS_CATALOG . 'mailhive/common/images/icon_kill_process_32.png', '', '32', '32', 'align="right" style="margin-bottom: 10px; margin-left: 5px; margin-right: 10px;"') . '
        <div class="mb_action_box headline">' . MAILBEEZ_ACTION_PROCESS_CONTROL_KILL_HEADLINE . '</div>
        <div class="mb_action_box text">' . MAILBEEZ_ACTION_PROCESS_CONTROL_KILL_TEXT . '</div>
        <div class="mb_action_box buttons" style="clear: both"><div align="center">' . $plugin_actions . '</div></div>
        <div class="mb_action_box text"><div align="center">' . $config_template_msg . '</div></div>
        </div>');




?>