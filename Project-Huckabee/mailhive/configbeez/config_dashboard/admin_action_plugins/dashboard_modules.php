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

// button for view edit dashbord modules


$common_plugin_actions = mb_admin_button(mh_href_link(FILENAME_MAILBEEZ, 'tab=dashboardbeez'), MAILBEEZ_BUTTON_EDIT_DASHBOARD, '', 'link');


$contents[] = array('text' => '<div class="mb_action_box">
        <div class="mb_action_box headline">' . MAILBEEZ_ACTION_EDIT_DASHBOARD_HEADLINE . '</div>
        <div class="mb_action_box text">' . MAILBEEZ_ACTION_EDIT_DASHBOARD_TEXT . '</div>
        <div class="mb_action_box buttons"><div align="center">' . $common_plugin_actions . '</div></div>
        </div>');



?>