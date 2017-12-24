<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////
// make path work from admin
require_once(DIR_FS_CATALOG . 'mailhive/common/classes/configbeez.php');

class config extends configbeez
{

// class constructor
    function config()
    {
        configbeez::configbeez();
        $this->code = 'config';
        $this->module = 'config';
        $this->version = '2.5'; // float value
        $this->title = MAILBEEZ_MAILHIVE_TEXT_TITLE;
        $this->description = MAILBEEZ_MAILHIVE_TEXT_DESCRIPTION;
        $this->description_image = 'icon_big.png';
        $this->icon = 'icon.png';
        $this->sort_order = 0;
        $this->enabled = ((MAILBEEZ_MAILHIVE_STATUS == 'True') ? true : false);
        $this->status_key = 'MAILBEEZ_MAILHIVE_STATUS';
        $this->has_submodules = true;
        $this->admin_action_plugins_path = DIR_FS_CATALOG . 'mailhive/configbeez/'; // default-path to include admin action plugins from
        //$this->admin_action_plugins = 'uninstall.php';

        $this->documentation_key = 'config'; // leave empty if no documentation available
        $this->documentation_root = 'http://www.mailbeez.com/documentation/installation/';

        // update version if necessary
        if (defined('MAILBEEZ_VERSION') && (MAILBEEZ_VERSION < $this->version)) {
            $action = (isset($_GET['action']) ? $_GET['action'] : '');
            if ($action != 'config_update_ok') {
                // avoid loop with config cache installed
                $this->update();
            }
        }
    }

// class methods

    function remove()
    {
        $remove_keys = $this->keys();
        if (MH_PLATFORM == 'xtc') {
            // remove additional fields
            $xtc_text_keys = array();
            $keys = $this->keys();
            while (list(, $key_name) = each($keys)) {
                $xtc_text_keys[] = $key_name . '_TITLE';
                $xtc_text_keys[] = $key_name . '_DESC';
            }
            $remove_keys = array_merge($xtc_text_keys, $this->keys());
        }

        $remove_keys[] = 'MAILBEEZ_VERSION';
        $remove_keys[] = 'MAILBEEZ_INSTALLED';
        $remove_keys[] = 'MAILBEEZ_INSTALLED_VERSIONS';
        $remove_keys[] = 'MAILBEEZ_CONFIG_INSTALLED';
        $remove_keys[] = 'MAILBEEZ_CONFIG_INSTALLED_VERSIONS';
        $remove_keys[] = 'MAILBEEZ_FILTER_INSTALLED';
        $remove_keys[] = 'MAILBEEZ_FILTER_INSTALLED_VERSIONS';
        $remove_keys[] = 'MAILBEEZ_REPORT_INSTALLED';
        $remove_keys[] = 'MAILBEEZ_REPORT_INSTALLEDD_VERSIONS';
        $remove_keys[] = 'MAILBEEZ_DASHBOARD_INSTALLED';
        $remove_keys[] = 'MAILBEEZ_DASHBOARD_INSTALLEDD_VERSIONS';
        $remove_keys[] = 'MAILBEEZ_MAILHIVE_GA_ENABLED';
        $remove_keys[] = 'MAILBEEZ_MAILHIVE_GA_REWRITE_MODE';
        $remove_keys[] = 'MAILBEEZ_MAILHIVE_GA_MEDIUM';
        $remove_keys[] = 'MAILBEEZ_MAILHIVE_GA_SOURCE';
        $remove_keys[] = 'MAILBEEZ_MAILHIVE_UPDATE_REMINDER_TIMESTAMP';

        $remove_keys[] = 'MAILBEEZ_CONFIG_TEMPLATE_ENGINE_STATUS';
        $remove_keys[] = 'MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMP_MODE';
        $remove_keys[] = 'MAILBEEZ_CONFIG_DASHBOARD_START';
        $remove_keys[] = 'MAILBEEZ_CONFIG_SIMULATION_EMAIL';
        $remove_keys[] = 'MAILBEEZ_CONFIG_SIMULATION_TRACKING';
        $remove_keys[] = 'MAILBEEZ_MAILHIVE_MODE';
        $remove_keys[] = 'MAILBEEZ_CONFIG_SIMULATION_COPY';

        mh_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $remove_keys) . "')");
        return mh_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key like 'MAILBEEZ_%'");
    }

    // installation methods

    function keys()
    {
        return array('MAILBEEZ_MAILHIVE_STATUS', 'MAILBEEZ_MAILHIVE_RUN_SHOW_EMAIL', 'MAILBEEZ_MAILHIVE_COPY', 'MAILBEEZ_MAILHIVE_EMAIL_COPY', 'MAILBEEZ_MAILHIVE_EMAIL_COPY_MAX_COUNT', 'MAILBEEZ_INSTALLED', 'MAILBEEZ_INSTALLED_VERSIONS', 'MAILBEEZ_MAILHIVE_TOKEN', 'MAILBEEZ_MAILHIVE_POPUP_MODE', 'MAILBEEZ_MAILHIVE_UPDATE_REMINDER', 'MAILBEEZ_MAILHIVE_EARLY_CHECK_ENABLED');
    }

    function install()
    {
        mh_insert_config_value(array('configuration_title' => 'Let the MailBeez work for you',
                                    'configuration_key' => 'MAILBEEZ_MAILHIVE_STATUS',
                                    'configuration_value' => 'True',
                                    'configuration_description' => 'Choose False to deactivated MailHive and MailBeez',
                                    'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                               ));

        mh_insert_config_value(array('configuration_title' => 'Mode',
                                    'configuration_key' => 'MAILBEEZ_MAILHIVE_MODE',
                                    'configuration_value' => 'simulate',
                                    'configuration_description' => 'production: emails are send out, tracking active<br>simulate: emails to copy-address only, tracking configurable',
                                    'set_function' => 'mh_cfg_select_option(array(\'production\', \'simulate\'), '
                               ));

        mh_insert_config_value(array('configuration_title' => 'Send copy',
                                    'configuration_key' => 'MAILBEEZ_MAILHIVE_COPY',
                                    'configuration_value' => 'True',
                                    'configuration_description' => 'send a copy of each email to copy-address',
                                    'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                               ));

        mh_insert_config_value(array('configuration_title' => 'Sent copy to',
                                    'configuration_key' => 'MAILBEEZ_MAILHIVE_EMAIL_COPY',
                                    'configuration_value' => 'copy@localhost',
                                    'configuration_description' => 'Send a copy of each email to this address<br>(be careful - configure number below)',
                                    'set_function' => ''
                               ));

        mh_insert_config_value(array('configuration_title' => 'Max. number of copy-emails sent per MailBeez Module',
                                    'configuration_key' => 'MAILBEEZ_MAILHIVE_EMAIL_COPY_MAX_COUNT',
                                    'configuration_value' => '10',
                                    'configuration_description' => 'controll the number of copy-emails',
                                    'set_function' => ''
                               ));

        mh_insert_config_value(array('configuration_title' => 'Security Token - for internal use only',
                                    'configuration_key' => 'MAILBEEZ_MAILHIVE_TOKEN',
                                    'configuration_value' => md5(time()),
                                    'configuration_description' => 'Security Token to protect public mailhive, leave default value or set to what you like',
                                    'set_function' => ''
                               ));


        mh_insert_config_value(array('configuration_title' => 'Popup mode',
                                    'configuration_key' => 'MAILBEEZ_MAILHIVE_POPUP_MODE',
                                    'configuration_value' => 'CeeBox',
                                    'configuration_description' => 'Popup-Mode, please change if you are having compatibility issues with opening the nice CeeBox AJAX Popups.',
                                    'set_function' => 'mh_cfg_select_option(array(\'off\', \'CeeBox\'), '
                               ));

        mh_insert_config_value(array('configuration_title' => 'Remind to run update check',
                                    'configuration_key' => 'MAILBEEZ_MAILHIVE_UPDATE_REMINDER',
                                    'configuration_value' => 'True',
                                    'configuration_description' => 'Do you want to get reminder to check for updates?',
                                    'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                               ));


        mh_insert_config_value(array('configuration_title' => 'Google Analytics Integration',
                                    'configuration_key' => 'MAILBEEZ_MAILHIVE_GA_ENABLED',
                                    'configuration_value' => 'True',
                                    'configuration_description' => 'Globally enable Google Analytics Integration',
                                    'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                               ));

        mh_insert_config_value(array('configuration_title' => 'Google Analytics URL Rewrite Mode',
                                    'configuration_key' => 'MAILBEEZ_MAILHIVE_GA_REWRITE_MODE',
                                    'configuration_value' => 'all',
                                    'configuration_description' => 'Globally set Google Analytics URL Rewrite Mode',
                                    'set_function' => 'mh_cfg_select_option(array(\'all\', \'shop\'), '
                               ));

        mh_insert_config_value(array('configuration_title' => 'MailBeez Version',
                                    'configuration_key' => 'MAILBEEZ_VERSION',
                                    'configuration_value' => $this->version,
                                    'configuration_description' => 'This is automatically updated. No need to edit.',
                                    'set_function' => ''
                               ));


        mh_db_query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "mailbeez_tracking (
			  autoemail_id int NOT NULL auto_increment,
			  module varchar(255) NOT NULL,
				iteration INT( 11 ) NOT NULL ,
				customers_id INT( 11 ) NOT NULL ,		
				customers_email VARCHAR( 96 ) NOT NULL,
				orders_id INT NOT NULL,	
				date_sent DATETIME NOT NULL ,
				PRIMARY KEY ( autoemail_id ),
				INDEX ( customers_id ) );");

        $this->update();
    }

    function update()
    {
        // MailBeez V1.5
        mh_db_query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "mailbeez_block (
			  autoemail_id int NOT NULL auto_increment,
			  module varchar(255) NOT NULL,
				customers_id INT( 11 ) NOT NULL ,	
				date_block DATETIME NOT NULL,
				PRIMARY KEY ( autoemail_id ),
				INDEX ( customers_id, module ) );");


        // MailBeez V2
        mh_db_query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "mailbeez_event_log (
			  log_id int NOT NULL auto_increment,
				event_type varchar(255) NOT NULL,
				log_entry text,
				batch_id INT( 11 ) NOT NULL ,
				module varchar(255) NOT NULL,
				class varchar(255) NOT NULL,
				result varchar(255) NOT NULL,
				parameters text,
                log_date DATETIME NOT NULL,
				query_string text,
				simulation INT( 11 ) NOT NULL,
				PRIMARY KEY ( log_id ),
				INDEX ( module ) );");

        mh_insert_config_value(array('configuration_title' => 'Google Analytics Medium',
                                    'configuration_key' => 'MAILBEEZ_MAILHIVE_GA_MEDIUM',
                                    'configuration_value' => 'email',
                                    'configuration_description' => 'Choose how you would like to name the medium (default: email)',
                                    'set_function' => ''
                               ));

        mh_insert_config_value(array('configuration_title' => 'Google Analytics Source',
                                    'configuration_key' => 'MAILBEEZ_MAILHIVE_GA_SOURCE',
                                    'configuration_value' => 'MailBeez',
                                    'configuration_description' => 'Choose how you would like to name the source (default: MailBeez)',
                                    'set_function' => ''
                               ));

        mh_insert_config_value(array('configuration_title' => 'Compatibility Mode',
                                    'configuration_key' => 'MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMP_MODE',
                                    'configuration_value' => 'True',
                                    'configuration_description' => 'Choose True for compatibility with the MailBeez 1.x Template System.',
                                    'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                               ));

        mh_insert_config_value(array('configuration_title' => 'Startpage',
                                    'configuration_key' => 'MAILBEEZ_CONFIG_DASHBOARD_START',
                                    'configuration_value' => 'home',
                                    'configuration_description' => 'Choose which tab you would like to see when you open MailBeez',
                                    'set_function' => 'mh_cfg_select_option(array(\'home\', \'mailbeez\', \'config\', \'filter\', \'report\'), '
                               ));

        mh_insert_config_value(array('configuration_title' => 'Sent simulation to',
                                    'configuration_key' => 'MAILBEEZ_CONFIG_SIMULATION_EMAIL',
                                    'configuration_value' => 'copy@localhost',
                                    'configuration_description' => 'Email Adress to send simulation emails to - no limitations',
                                    'set_function' => ''
                               ));

        mh_insert_config_value(array('configuration_title' => 'Send copy in Simulation mode',
                                    'configuration_key' => 'MAILBEEZ_CONFIG_SIMULATION_COPY',
                                    'configuration_value' => 'True',
                                    'configuration_description' => 'send a copy of each email to the configured copy-address',
                                    'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                               ));

        mh_insert_config_value(array('configuration_title' => 'Enable Tracking in Simulation Mode',
                                    'configuration_key' => 'MAILBEEZ_CONFIG_SIMULATION_TRACKING',
                                    'configuration_value' => 'True',
                                    'configuration_description' => 'Do you want to enable Tracking in Simulation mode? You can delete the Simulation Tracking with click on "Restart Simulation"',
                                    'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                               ));


        mh_insert_config_value(array('configuration_title' => 'Enable Early Check',
                                    'configuration_key' => 'MAILBEEZ_MAILHIVE_EARLY_CHECK_ENABLED',
                                    'configuration_value' => 'False',
                                    'configuration_description' => 'Do you want to enable "Early Check"? This will hide all already sent or filtered results - but might confuse by showing "0 recipients".<br>
"Early Check" adds a SQL query per item per module (slower)',
                                    'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                               ));

        mh_insert_config_value(array('configuration_title' => 'Optional',
                                    'configuration_key' => 'MAILBEEZ_CONFIG_EVENT_LOG_LEVEL',
                                    'configuration_value' => '',
                                    'configuration_description' => 'Settings',
                                    'set_function' => 'mh_cfg_select_multioption(array(\'MODULE_INIT\', \'MODULE_SQL\'), '
                               ));


        if (MH_PLATFORM != 'xtc' && MH_PLATFORM != 'gambio') {
            // only for platforms without smarty by default
            mh_insert_config_value(array('configuration_title' => 'Path to Smarty',
                                        'configuration_key' => 'MAILBEEZ_CONFIG_TEMPLATE_ENGINE_SMARTY_PATH',
                                        'configuration_value' => 'Smarty_2.6.26',
                                        'configuration_description' => 'Path to Smarty Template system<br>located in <br>
																		 mailhive/common/classes/',
                                        'set_function' => ''
                                   ));
        }

        // convert field type
        $field_info = mh_db_check_field_exists(TABLE_MAILBEEZ_TRACKING, 'customers_id');
        if ($field_info['Type'] == 'int(11)') {
            mh_db_query("ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " CHANGE customers_id customers_id BIGINT( 20 ) NOT NULL DEFAULT '0'");
        }
        $field_info = mh_db_check_field_exists(TABLE_MAILBEEZ_BLOCK, 'customers_id');
        if ($field_info['Type'] == 'int(11)') {
            mh_db_query("ALTER TABLE " . TABLE_MAILBEEZ_BLOCK . " CHANGE customers_id customers_id BIGINT( 20 ) NOT NULL DEFAULT '0'");
        }

        // advanced simulation
        $sql = array();
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD simulation INT( 11 ) NOT NULL ;";
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD INDEX ( simulation ) ;";
        mh_db_add_field(TABLE_MAILBEEZ_TRACKING, 'simulation', $sql);

        $sql = array();
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_BLOCK . " ADD simulation INT( 11 ) NOT NULL ;";
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_BLOCK . " ADD INDEX ( simulation ) ;";
        mh_db_add_field(TABLE_MAILBEEZ_BLOCK, 'simulation', $sql);

        // event log
        $sql = array();
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD batch_id INT( 11 ) NOT NULL ;";
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD INDEX ( batch_id ) ;";
        mh_db_add_field(TABLE_MAILBEEZ_TRACKING, 'batch_id', $sql);

        // install dashboard default modules, also update
        $default_dashboardbeez = array('dashboard_intro', 'dashboard_latest_news', 'dashboard_actions', 'dashboard_versioncheck', 'dashboard_review_o_meter', 'dashboard_winback_o_meter', 'dashboard_beez_o_graph');
        $installed_modules = array();
        while (list(, $class) = each($default_dashboardbeez)) {
            if (file_exists($GLOBALS['dashboard_module_directory'] . $class . $GLOBALS['file_extension'])) {
                include_once($GLOBALS['dashboard_module_directory'] . $class . $GLOBALS['file_extension']);
                $module = new $class;
                $module->install();
                $installed_modules[] = $class . $GLOBALS['file_extension'];
            }
        }

        mh_insert_config_value(array('configuration_title' => 'Installed Modules',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_INSTALLED',
                                    'configuration_value' => implode(';', $installed_modules),
                                    'configuration_description' => 'This is automatically updated. No need to edit.',
                                    'set_function' => ''
                               ), true);


        // install default reports, also update
        $default_reportbeez = array('report_event_log');
        $installed_modules = array();
        while (list(, $class) = each($default_reportbeez)) {
            if (file_exists($GLOBALS['report_module_directory'] . $class . $GLOBALS['file_extension'])) {
                include_once($GLOBALS['report_module_directory'] . $class . $GLOBALS['file_extension']);
                $module = new $class;
                $module->install();
                $installed_modules[] = $class . $GLOBALS['file_extension'];
            }
        }

        mh_insert_config_value(array('configuration_title' => 'Installed Modules',
                                    'configuration_key' => 'MAILBEEZ_REPORT_INSTALLED',
                                    'configuration_value' => implode(';', $installed_modules),
                                    'configuration_description' => 'This is automatically updated. No need to edit.',
                                    'set_function' => ''
                               ), true);

        if (MH_PLATFORM == 'xtc' || MH_PLATFORM == 'gambio') {
            $query_raw = "select * from " . TABLE_CONFIGURATION . " where configuration_key like 'MAILBEEZ_%_TITLE' or configuration_key like 'MAILBEEZ_%_DESC'";
            $query = mh_db_query($query_raw);
            while ($item = mh_db_fetch_array($query)) {
                $data = array('configuration_key' => $item['configuration_key'] . '_DEPR');
                mh_db_perform(TABLE_CONFIGURATION, $data, 'update', "configuration_key='" . $item['configuration_key'] . "'");
            }
        }

        // update fieldtype of configuration_value to text like in zencart and oscommerce 2.3
        $field_info = mh_db_check_field_exists(TABLE_CONFIGURATION, 'configuration_value');
        if ($field_info['Type'] != 'text') {
            mh_db_query("ALTER TABLE " . TABLE_CONFIGURATION . " CHANGE configuration_value configuration_value text NOT NULL");
        }

        // update fieldtype of set_function to text
        $field_info = mh_db_check_field_exists(TABLE_CONFIGURATION, 'set_function');
        if ($field_info['Type'] != 'text') {
            mh_db_query("ALTER TABLE " . TABLE_CONFIGURATION . " CHANGE set_function set_function text NOT NULL");
        }

        // make platform info available for mailhive.php
        mh_insert_config_value(array('configuration_title' => 'Detected Platform',
                                    'configuration_key' => 'MH_PLATFORM_STATIC',
                                    'configuration_value' => MH_PLATFORM,
                                    'configuration_description' => 'This is automatically updated. No need to edit.',
                                    'set_function' => ''
                               ));

        if (MH_ID != MH_PLATFORM) {
            mh_insert_config_value(array('configuration_title' => 'Platform ID',
                                        'configuration_key' => 'MH_ID',
                                        'configuration_value' => MH_ID,
                                        'configuration_description' => 'This is automatically updated. No need to edit.',
                                        'set_function' => ''
                                   ));
        }


        // introducing: process locking
        mh_db_query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "mailbeez_process (
                        lock_id int(11) NOT NULL auto_increment,
                        lock_key varchar(255) default NULL,
                        lock_value text default NULL,
                        batch_id INT( 11 ) NOT NULL ,
                        date_added datetime NOT NULL default '0000-00-00 00:00:00',
                        PRIMARY KEY  (lock_id),
                        INDEX ( lock_key ) );"
        );


        mh_insert_config_value(array('configuration_title' => 'Activate Process Control',
                                    'configuration_key' => 'MAILBEEZ_MAILHIVE_PROCESS_CONTROL',
                                    'configuration_value' => 'True',
                                    'configuration_description' => 'Choose False to deactivated Process Control',
                                    'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                               ));

        mh_insert_config_value(array('configuration_title' => 'Lock-Period',
                                    'configuration_key' => 'MAILBEEZ_MAILHIVE_PROCESS_CONTROL_LOCK_PERIOD',
                                    'configuration_value' => '60',
                                    'configuration_description' => 'Lock-Period (sec)',
                                    'set_function' => ''
                               ));


        mh_insert_config_value(array('configuration_title' => 'Show Emails when Sending',
                                    'configuration_key' => 'MAILBEEZ_MAILHIVE_RUN_SHOW_EMAIL',
                                    'configuration_value' => 'True',
                                    'configuration_description' => 'Define if you would like to see the generated emails while sending',
                                    'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                               ));

        mh_insert_config_value(array('configuration_title' => 'Double Dot Bugfix',
                                    'configuration_key' => 'MAILBEEZ_CONFIG_EMAIL_BUGFIX_1',
                                    'configuration_value' => 'False',
                                    'configuration_description' => 'In rare occasions a Dot in filenames is doubled, e.g. file.php becomes file..php, image.png becomes image..png. Try to fix this Bug?',
                                    'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                               ));


        if (MH_PLATFORM == 'zencart') {
            mh_insert_config_value(array('configuration_title' => 'Override Zencart Email Template System',
                                        'configuration_key' => 'MAILBEEZ_MAILHIVE_ZENCART_OVERRIDE',
                                        'configuration_value' => 'True',
                                        'configuration_description' => 'Do you want to override Zencarts Email Template System?',
                                        'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                                   ));
        }


        $sql = array();
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_BLOCK . " ADD source INT( 11 ) default 0;";
        mh_db_add_field(TABLE_MAILBEEZ_BLOCK, 'source', $sql);



        // update version - last thing after everything was updated
        mh_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $this->version . "', last_modified = now() where configuration_key = 'MAILBEEZ_VERSION'");

        // redirect
        if (defined('MAILBEEZ_VERSION') && (MAILBEEZ_VERSION < $this->version)) {
            // updated
            mh_redirect(mh_href_link(FILENAME_MAILBEEZ, 'action=config_update_ok&tab=home'));
        } else {
            mh_redirect(mh_href_link(FILENAME_MAILBEEZ, 'tab=home'));
        }
    }

}

?>