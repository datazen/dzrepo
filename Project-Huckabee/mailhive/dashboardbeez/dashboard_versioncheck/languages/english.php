<?php 
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez
	
	inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('MAILBEEZ_DASHBOARD_VERSIONCHECK_STATUS_TITLE', 'Activate Module?');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_STATUS_DESC', 'Do you want to activate this module');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_SORT_ORDER_TITLE', 'Sort order of display.');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first.');

define('MH_BUTTON_VERSION_CHECK_CLEAR', 'Clear Results');

define('MAILBEEZ_DASHBOARD_VERSIONCHECK_TITLE', 'Version Check');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_DESCRIPTION', 'Check your MailBeez system for updates and new modules');

define('MAILBEEZ_DASHBOARD_VERSIONCHECK_TEXT', 'Last Check: %s');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_RESULT', 'Result<br>');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_RESULT_UPD_CNT', 'Updates available');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_RESULT_NEW_CNT', 'Additional Modules');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_RESULT_UPD_OK', 'All Modules up to date');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_RESULT_NEW_OK', 'All Modules installed');

define('MAILBEEZ_DASHBOARD_VERSIONCHECK_TEXT_NCURL', 'The integrated version check requires the PHP module "CURL" which is not installed on this server.<br>For this reason you can only use the simple version check.');

define('MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_TITLE', 'MailBeez Status');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_TEXT', 'See the current MailBeez Status');


define('MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_SET_TITLE', 'Status');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_SET_PRODUCTION_TEXT', MAILBEEZ_MODE_SET_PRODUCTION_TEXT);
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_SET_SIMULATE_TEXT', MAILBEEZ_MODE_SET_SIMULATE_TEXT);
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_SET_PRODUCTION_DESC', 'Mailbeez is in production mode and live emails will be sent to your customers.');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_SET_SIMULATE_DESC', 'All generated Emails will be sent to <i>' . MAILBEEZ_CONFIG_SIMULATION_EMAIL . '</i>. Make sure you are fully happy before you switch into production mode.');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_SET_SIMULATE_CONFIG', 'Simulation Configuration');

?>