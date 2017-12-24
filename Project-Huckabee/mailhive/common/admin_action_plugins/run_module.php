<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.5
 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////
// button to run module


global $config_module_directory, $file_extension;
mh_load_modules_language_files($config_module_directory, 'config_automatic', $file_extension);


$common_plugin_actions = mb_admin_button(MAILBEEZ_MAILHIVE_URL . 'runconfirm&module=' . mh_urlencode($mInfo->code), MAILBEEZ_BUTTON_RUN_MODULE);

$contents[] = array('text' => '<div class="mb_action_box">' . mh_image(DIR_WS_CATALOG . 'mailhive/common/images/icon_run_32.png', '', '32', '32', 'align="right" style="margin-bottom: 10px; margin-left: 5px; margin-right: 10px;"') . '
        <div class="mb_action_box headline">' . MAILBEEZ_ACTION_RUN_MODULE_HEADLINE . '</div>
        <div class="mb_action_box text">' . MAILBEEZ_ACTION_RUN_MODULE_TEXT . '</div>
        <div class="mb_action_box buttons" style="clear: both"><div align="center">' . $common_plugin_actions . '</div></div>
        ' . ( ( defined('MAILBEEZ_CRON_INSTALLED') ) ? '' : '<div align="left">' . MAILBEEZ_CONFIG_AUTOMATIC_TEXT_DESCRIPTION . '</div>' ) . '
        </div>');
?>