<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez
	
	inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
	
	v1.0
*/

$plugin_actions = mb_admin_button(mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=' . 'config_simulation/admin_application_plugins/simulation_restart.php'), MAILBEEZ_BUTTON_SIMULATION_RESTART, '', 'link') . '';

$config_simulation_msg = '';

if ($_GET['restart'] == 'ok') {
    $config_simulation_msg = '<b>' . MAILBEEZ_ACTION_SIMULATION_RESTART_OK .'</b><br>';
}

// $count = mh_simulation_info();
// $contents[] = array('text' => '<br><center> Simulation entries' . $count['cnt']  . '</center><hr noshade size="1">');


$contents[] = array('text' => '<div class="mb_action_box">
        <div class="mb_action_box headline">' . MAILBEEZ_ACTION_SIMULATION_RESTART_HEADLINE . '</div>
        <div class="mb_action_box text">' . MAILBEEZ_ACTION_SIMULATION_RESTART_TEXT . '</div>
        <div class="mb_action_box buttons"><div align="center">' . $plugin_actions . '</div></div>
        <div class="mb_action_box text"><div align="center">' . $config_simulation_msg  . '</div></div>
        </div>');


?>  