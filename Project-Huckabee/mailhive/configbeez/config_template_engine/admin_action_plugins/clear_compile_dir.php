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

$plugin_actions = mb_admin_button(mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=' . 'config_template_engine/admin_application_plugins/clear_compile_dir.php'), MAILBEEZ_BUTTON_TEMPLATEENGINE_CLEAR, '', 'link') . '';



$config_template_msg = '<center>' . MAILBEEZ_ACTION_TEMPLATEENGINE_CLEAR_INFO . ': ' . mh_smarty_compile_dir_info() . '</center><br>';

if ($_GET['clear'] == 'ok') {
    $config_template_msg = '<b>' . MAILBEEZ_ACTION_TEMPLATEENGINE_CLEAR_OK . '</b><br>';
}



$contents[] = array('text' => '<div class="mb_action_box">
        <div class="mb_action_box headline">' . MAILBEEZ_ACTION_TEMPLATEENGINE_CLEAR_HEADLINE . '</div>
        <div class="mb_action_box text">' . MAILBEEZ_ACTION_TEMPLATEENGINE_CLEAR_TEXT . '</div>
        <div class="mb_action_box buttons"><div align="center">' . $plugin_actions . '</div></div>
        <div class="mb_action_box text"><div align="center">' . $config_template_msg . '</div></div>
        </div>');




?>