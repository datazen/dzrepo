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

	$plugin_actions = mb_admin_button( mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=' . 'config/admin_application_plugins/uninstall.php'), 'Uninstall MailBeez', '', 'link') . '<br><br>';
	$contents[] = array('text' => '<br><center>' . $plugin_actions . '</center><hr noshade size="1">');

?>