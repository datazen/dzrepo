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

	$plugin_actions = mb_admin_button( mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=' . '../dashboardbeez/dashboard_versioncheck/admin_application_plugins/check.php'), MH_BUTTON_VERSION_CHECK, '', 'link') . '';
	$contents[] = array('text' => '<br><center>' . $plugin_actions . '</center>');

?>