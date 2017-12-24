<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

define('MH_INSTALL_INTRO', 'Please install MailHive by clicking the install-button');
define('MH_INSTALL_SUPPORT', 'If you are having issues with the installation, please read the <a href="http://www.mailbeez.com/documentation/installation/" target="_blank"><b><u>installation manual</u></b></a><br>
									<br>
									After you have done this at least 3x you are allowed to use the <a href="http://www.mailbeez.com/support/" target="_blank"><b><u>MailBeez-Support</u></b></a> ;-)');

define('MH_RATE_TRUSTPILOT_LINK', 'please rate MailBeez on Trustpilot');

define('MH_SECURE_URL', 'Secure Cronjob-URL (will immediately execute all active mailBeez - but respects Mode)');

define('MH_BUTTON_VERSION_CHECK', 'Check for Updates...');
define('MH_BUTTON_BACK_CONFIGURATION', 'Back to Configuration');
define('MH_BUTTON_BACK_DASHBOARD', 'Back to Dashboard');
define('MH_BUTTON_BACK_REPORTS', 'Back to Reports');

define('MH_DASHBOARD_CONFIG', 'config');
define('MH_DASHBOARD_REMOVE', 'x');

define('HEADING_TITLE', 'MailBeez - easy automated email marketing');
define('TEXT_DOCUMENTATION', 'Documentation available');
define('TEXT_VIEW_ONLINE', 'View Online');
define('TEXT_UPGRADE_MAILBEEZ', 'This Module requires MailBeez Version %s or higher. Please update MailBeez.');
define('WARNING_SIMULATE', 'SIMULATION-MODE: no emails are send');
define('WARNING_OFFLINE', 'DISABLED: MailBeez are not processed');

define('MH_NO_MODULE', 'No Modules.');

define('MH_TAB_HOME', 'Home');
define('MH_TAB_MAILBEEZ', 'MailBeez Modules');
define('MH_TAB_CONFIGURATION', 'Configuration');
define('MH_TAB_FILTER', 'Filter &amp; Helper');
define('MH_TAB_REPORT', 'Reports');
define('MH_TAB_ABOUT', 'About');
define('MH_HEADER_DASHBOARD_MODULES', 'Dashboard Modules');
define('MH_MSG_EMPTY_DASHBOARD_AREA', 'Empty Space - add a Dashboard-Module');

define('MH_HOME_ACTIONS', 'Actions');
define('MH_HOME_RESOURCES', 'Resources');

define('MH_DOWNLOAD_LINK_LIST', 'Find more MailBeez Modules...');
define('MH_DASHBOARD_CONFIG_BUTTON', 'Config Dashboard');

// config
define('MAILBEEZ_MAILHIVE_TEXT_TITLE', 'MailHive - Basic Configuration');
if (MAILBEEZ_CONFIG_INSTALLED == 'config.php' && MAILBEEZ_INSTALLED == '') {
  define('MAILBEEZ_MAILHIVE_TEXT_DESCRIPTION', 'Basic Configuration for MailHive.');
} else {
  define('MAILBEEZ_MAILHIVE_TEXT_DESCRIPTION', 'Basic Configuration for MailHive. <br>
		<br>To remove this module please uninstall all MailBeez first.');
}

define('MAILBEEZ_MAILHIVE_STATUS_TITLE', 'Let the MailBeez work for you');
define('MAILBEEZ_MAILHIVE_STATUS_DESC', 'activate MailHive and MailBeez');

define('MAILBEEZ_MAILHIVE_COPY_TITLE', 'Send Copy');
define('MAILBEEZ_MAILHIVE_COPY_DESC', 'send a copy of each email to copy-address');

define('MAILBEEZ_MAILHIVE_EMAIL_COPY_TITLE', 'Sent copy to');
define('MAILBEEZ_MAILHIVE_EMAIL_COPY_DESC', 'Send a copy of each email to this address<br>(be careful - configure number below)');

define('MAILBEEZ_MAILHIVE_EMAIL_COPY_MAX_COUNT_TITLE', 'Max. number of copy-emails sent per MailBeez Module');
define('MAILBEEZ_MAILHIVE_EMAIL_COPY_MAX_COUNT_DESC', 'controll the number of copy-emails');

define('MAILBEEZ_MAILHIVE_TOKEN_TITLE', 'Security Token - for internal use only');
define('MAILBEEZ_MAILHIVE_TOKEN_DESC', 'Security Token to protect public mailhive, leave default value or set to what you like');

define('MAILBEEZ_MAILHIVE_POPUP_MODE_TITLE', 'Popup Mode');
define('MAILBEEZ_MAILHIVE_POPUP_MODE_DESC', 'Popup-Mode, please change if you are having compatibility issues with opening the nice CeeBox AJAX Popups.');

define('MAILBEEZ_MAILHIVE_UPDATE_REMINDER_TITLE', 'Remind to run update check');
define('MAILBEEZ_MAILHIVE_UPDATE_REMINDER_DESC', 'Do you want to get reminder to check for updates?');

define('MAILBEEZ_MAILHIVE_EARLY_CHECK_ENABLED_TITLE', 'Enable "Early Check"');
define('MAILBEEZ_MAILHIVE_EARLY_CHECK_ENABLED_DESC', 'Do you want to enable "Early Check"? This will hide all already sent or filtered results - but might confuse by showing "0 recipients".<br>"Early Check" adds a SQL query per item per module (slower)');


// config_dashboard
define('MAILBEEZ_CONFIG_DASHBOARD_TEXT_TITLE', 'Dashboard configuration');
define('MAILBEEZ_CONFIG_DASHBOARD_TEXT_DESCRIPTION', 'Configure how the MailBeez Startscreen should look like');
;
define('MAILBEEZ_CONFIG_DASHBOARD_START_TITLE', 'Start-Tab');
define('MAILBEEZ_CONFIG_DASHBOARD_START_DESC', 'Choose which tab you would like to see when you open MailBeez (default: home)');


// config_googleanalytics
define('MAILBEEZ_CONFIG_GOOGLEANALYTICS_TEXT_TITLE', 'Google Analytics Automatic Campaign Integration');
define('MAILBEEZ_CONFIG_GOOGLEANALYTICS_TEXT_DESCRIPTION', 'Configuration for Google Analytics URL Rewrite.<br><br>
	<img src="' . MH_CATALOG_SERVER . DIR_WS_CATALOG . "/mailhive/common/images/analytics_logo.gif" . '" width="213" height="40" alt="" border="0" align="absmiddle" hspace="1">');

define('MAILBEEZ_MAILHIVE_GA_ENABLED_TITLE', 'Google Analytics Integration');
define('MAILBEEZ_MAILHIVE_GA_ENABLED_DESCRIPTION', 'Globally enable Google Analytics Integration');

define('MAILBEEZ_MAILHIVE_GA_REWRITE_MODE_TITLE', 'Google Analytics URL Rewrite Mode');
define('MAILBEEZ_MAILHIVE_GA_REWRITE_MODE_DESC', 'Globally set Google Analytics URL Rewrite Mode');

define('MAILBEEZ_MAILHIVE_GA_MEDIUM_TITLE', 'Google Analytics "Medium"');
define('MAILBEEZ_MAILHIVE_GA_MEDIUM_DESC', 'Choose how you would like to name the medium (default: email)');

define('MAILBEEZ_MAILHIVE_GA_SOURCE_TITLE', 'Google Analytics "Source"');
define('MAILBEEZ_MAILHIVE_GA_SOURCE_DESC', 'Choose the "Source" for Google Analytics (standard: MailBeez)');

// config_simulation
define('MAILBEEZ_CONFIG_SIMULATION_TEXT_TITLE', 'Simulation');
define('MAILBEEZ_CONFIG_SIMULATION_TEXT_DESCRIPTION', 'Configuration for MailBeez Advanced Simulations.<br>
	<br>Advanced Simulations allows to run complete, realstic simulations including tracking information.
	Emails are NOT send out to customers, but only to the configured email address');

define('MAILBEEZ_MAILHIVE_MODE_TITLE', 'Mode');
define('MAILBEEZ_MAILHIVE_MODE_DESC', 'Please test with "Simulation" until you are happy, in "Production" Emails are send to customers!');

define('MAILBEEZ_CONFIG_SIMULATION_EMAIL_TITLE', 'Sent simulation to');
define('MAILBEEZ_CONFIG_SIMULATION_EMAIL_DESC', 'Email Adress to send simulation emails to - no limitations');

define('MAILBEEZ_CONFIG_SIMULATION_COPY_TITLE', 'Send copy in Simulation mode');
define('MAILBEEZ_CONFIG_SIMULATION_COPY_DESC', 'send a copy of each email to the configured copy-address: ' . MAILBEEZ_MAILHIVE_EMAIL_COPY);

define('MAILBEEZ_CONFIG_SIMULATION_TRACKING_TITLE', 'Enable Tracking in Simulation Mode');
define('MAILBEEZ_CONFIG_SIMULATION_TRACKING_DESC', 'Do you want to enable Tracking in Simulation mode? You can delete the Simulation Tracking with click on "Restart Simulation"');


// config_template_engine
define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_TEXT_TITLE', 'Template Engine');
define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_TEXT_DESCRIPTION', 'Configuration for Smarty Template Engine.<br>
	<br>	<a href="http://www.smarty.net" target="_blank"><img src="' . MH_CATALOG_SERVER . DIR_WS_CATALOG . "/mailhive/common/images/smarty_icon.gif" . '" width="88" height="31" alt="" border="0" align="absmiddle" hspace="1"></a>');

define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMP_MODE_TITLE', 'Compatibility Mode');
define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMP_MODE_DESC', 'Choose True for compatibility with the MailBeez 1.x Template System.');

define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_SMARTY_PATH_TITLE', 'Path to Smarty');
define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_SMARTY_PATH_DESC', 'Path to Smarty Template system /Smarty.class.php<br>located in <br>mailhive/common/classes/');

// config_event_log
define('MAILBEEZ_CONFIG_EVENT_LOG_TEXT_TITLE', 'Event Log');
define('MAILBEEZ_CONFIG_EVENT_LOG_TEXT_DESCRIPTION', 'Settings for logging events while running MailBeez.');

// about
define('MH_ABOUT', '<b style="font-size: 20px; font-weight: bold;">About MailBeez ' . ((defined('MAILBEEZ_VERSION')) ? MAILBEEZ_VERSION : '' ) . '</b><br><br>
	MailBeez Version ' . ((defined('MAILBEEZ_VERSION')) ? MAILBEEZ_VERSION : '' ) . ',	detected platform: <b>' . MH_PLATFORM . '</b><br>
	
	Developed by: Cord F. Rosted <a href="mailto:' . MAILBEEZ_CONTACT_EMAIL . '">' . MAILBEEZ_CONTACT_EMAIL .'</a> <br>
	(contact in English, Deutsch, Dansk)');

define('MH_ABOUT_BUTTONS_FEATURE', 'Request a Feature');
define('MH_ABOUT_BUTTONS_RATE_READ', 'Read User Rating');
define('MH_ABOUT_BUTTONS_RATE_RATE', 'Give Rating');

$trustpilot_evaluate = 'http://www.trustpilot.com/evaluate/www.mailbeez.com';


define('MH_MAILBEEZ_LOVE', 'You like MailBeez?');
define('MH_MAILBEEZ_LOVE_TEXT', 'Did the MailBeez work hard for you to get in touch with old customers?
	And give you more revenue through product ratings?
	<br><br>
	Feel free to say thank you to the MailBeez with a donation - and look forward further developments.');

define('MH_MAILBEEZ_LOVE_BTN', 'btn_donate_EN.gif');


// new with MailBeez V2.1

define('MH_VERSIONCHECK_INFO_DASHBOARD', 'There are updates and/or new dashboard modules. Please check the dashboard configuration');
define('MH_VERSIONCHECK_INFO_NEW', 'These %s Modules are not yet installed:');
define('MH_VERSIONCHECK_INFO_NEW_MORE', 'click for more information');
define('MH_VERSIONCHECK_INFO_NEWVERSION', 'New Version');


// new with MailBeez V2.2
// config_process_control
define('MAILBEEZ_CONFIG_PROCESS_CONTROL_TEXT_TITLE', 'MailHive Process Control');
define('MAILBEEZ_CONFIG_PROCESS_CONTROL_TEXT_DESCRIPTION', 'Process Control Settings - for nerds only.');
define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_TITLE', 'Activate MailHive Process Control');
define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_DESCRIPTION', 'Choose True to activate MailHive Process Control (recommended).');
define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_LOCK_PERIOD_TITLE', 'Lock Period');
define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_LOCK_PERIOD_DESCRIPTION', 'Lock Period in seconds.');


// action plugin view templates
define('MAILBEEZ_ACTION_VIEW_TEMPLATE_HEADLINE', 'Email Templates');
define('MAILBEEZ_ACTION_VIEW_TEMPLATE_TEXT', 'Preview the Templates of this Module:');
define('MAILBEEZ_BUTTON_VIEW_HTML', 'HTML');
define('MAILBEEZ_BUTTON_VIEW_TXT', 'TXT');


// action plugin list recipients
define('MAILBEEZ_ACTION_LIST_RECIPIENTS_HEADLINE', 'Recipients');
define('MAILBEEZ_ACTION_LIST_RECIPIENTS_TEXT', 'View a list of current recipients:');
define('MAILBEEZ_BUTTON_LIST_RECIPIENTS', 'Show');

// action plugin send testmail
define('MAILBEEZ_ACTION_SEND_TESTMAIL_HEADLINE', 'Send Test Email');
define('MAILBEEZ_ACTION_SEND_TESTMAIL_TEXT', 'Send a test email with test data:');
define('MAILBEEZ_BUTTON_SEND_TESTMAIL', 'Send...');

// action plugin run module
define('MAILBEEZ_ACTION_RUN_MODULE_HEADLINE', 'Run this module');
define('MAILBEEZ_ACTION_RUN_MODULE_TEXT', 'Run this module in mode: ' . MAILBEEZ_MAILHIVE_MODE);
define('MAILBEEZ_BUTTON_RUN_MODULE', 'Run...');


// action plugin edit dashboard
define('MAILBEEZ_ACTION_EDIT_DASHBOARD_HEADLINE', 'Dashboard Module');
define('MAILBEEZ_ACTION_EDIT_DASHBOARD_TEXT', 'Add, Remove and Edit Dashboard Modules');
define('MAILBEEZ_BUTTON_EDIT_DASHBOARD', 'Edit...');


// action plugin control simulation
define('MAILBEEZ_ACTION_SIMULATION_RESTART_HEADLINE', 'Simulation');
define('MAILBEEZ_ACTION_SIMULATION_RESTART_TEXT', 'Restart the Simulation - this deletes all recorded Simulation data.');
define('MAILBEEZ_ACTION_SIMULATION_RESTART_OK', 'Simulation restarted.');
define('MAILBEEZ_BUTTON_SIMULATION_RESTART', 'Restart');

// action plugin template engine
define('MAILBEEZ_ACTION_TEMPLATEENGINE_CLEAR_HEADLINE', 'Template System');
define('MAILBEEZ_ACTION_TEMPLATEENGINE_CLEAR_TEXT', 'Clear compiled template files');
define('MAILBEEZ_ACTION_TEMPLATEENGINE_CLEAR_OK', 'template compilation files cleared');
define('MAILBEEZ_ACTION_TEMPLATEENGINE_CLEAR_INFO', 'Number of template compilation files');
define('MAILBEEZ_BUTTON_TEMPLATEENGINE_CLEAR', 'Clear');


define('MAILBEEZ_VERSION_CHECK_MSG_INTRO', 'MailBeez says:');


define('MAILBEEZ_MAILHIVE_RUN_SHOW_EMAIL_TITLE', 'Show Email while Sending');
define('MAILBEEZ_MAILHIVE_RUN_SHOW_EMAIL_DESC', 'Choose True to see the generated Email while sending them.');

define('MAILBEEZ_MAILHIVE_MODE_SWITCH_TEXT', (MAILBEEZ_MAILHIVE_MODE == 'simulate') ? 'switch to "production"' : 'switch to "simulate"' );


// new in MailBeez V2.5 - kill process
// config_process_control
define('MAILBEEZ_ACTION_PROCESS_CONTROL_KILL_HEADLINE', 'Kill Process');
define('MAILBEEZ_ACTION_PROCESS_CONTROL_KILL_TEXT', 'Once triggered the MailHive Process can run several hours (e.g. with throttling active). <br>Click the "Kill" Button to stop the process as soon as possible after sending the next Email.');
define('MAILBEEZ_ACTION_PROCESS_CONTROL_KILL_OK', 'Process Kill initiated');
define('MAILBEEZ_BUTTON_PROCESS_CONTROL_KILL', 'Kill');


// new in MailBeez V2.5 - configure email engine
// config_email_engine
define('MAILBEEZ_CONFIG_EMAIL_ENGINE_TEXT_TITLE', 'Email Engine');
define('MAILBEEZ_CONFIG_EMAIL_ENGINE_TEXT_DESCRIPTION', 'Configure the Email Engine - if everything works nothing is to change here.');


define('MAILBEEZ_MAILHIVE_ZENCART_OVERRIDE_TITLE', 'Override Zencart Email Template System');
define('MAILBEEZ_MAILHIVE_ZENCART_OVERRIDE_DESC', 'Do you want to override Zencarts Email Template System?<br>If set to "False" the generated contented is merged into the template "emails/email_template_default.html" or  "emails/email_template_mailbeez.html" if available ');

define('MAILBEEZ_CONFIG_EMAIL_BUGFIX_1_TITLE', 'Double Dot Bugfix');
define('MAILBEEZ_CONFIG_EMAIL_BUGFIX_1_DESC', 'In rare occasions a Dot in filenames is doubled, e.g. file.php becomes file..php, image.png becomes image..png. Try to fix this Bug?');


define('MAILBEEZ_MODE_SET_SIMULATE_TEXT', 'Simulation');
define('MAILBEEZ_MODE_SET_PRODUCTION_TEXT', 'Production');

?>