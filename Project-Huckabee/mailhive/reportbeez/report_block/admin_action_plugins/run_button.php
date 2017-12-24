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

	// button to run report

	$common_plugin_actions = mb_admin_button( mh_href_link(FILENAME_MAILBEEZ, 'module=' . $mInfo->code . '&app=load_app&app_path=' . $mInfo->code . '/' . $mInfo->code . '.php'), MAILBEEZ_REPORT_BLOCK_LOG_BUTTON_RUN, '', 'link') ;
	$contents[] = array('text' => '<center>' . $common_plugin_actions . '</center>');

?>