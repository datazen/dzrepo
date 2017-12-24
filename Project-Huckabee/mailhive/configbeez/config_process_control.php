<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

require_once(DIR_FS_CATALOG . 'mailhive/common/classes/configbeez.php');

class config_process_control extends configbeez
{

    const KILL_FOLDER = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMPILE_DIR;

// class constructor
    function config_process_control()
    {
        configbeez::configbeez();
        $this->code = 'config_process_control';
        $this->module = 'config_process_control'; // same as folder name
        $this->version = '2.2'; // float value
        $this->title = MAILBEEZ_CONFIG_PROCESS_CONTROL_TEXT_TITLE;
        $this->description = MAILBEEZ_CONFIG_PROCESS_CONTROL_TEXT_DESCRIPTION;
        $this->icon = '../../common/images/icon_module.png';
        $this->removable = false; // can't be removed
        $this->stealth = true; // don't list as an installed module
        $this->display_as_submodule_of = 'config';
        $this->sort_order = 2;
        $this->enabled = ((MAILBEEZ_MAILHIVE_PROCESS_CONTROL == 'True') ? true : false);
        $this->admin_action_plugins_path = DIR_FS_CATALOG . 'mailhive/configbeez/'; // default-path to include admin action plugins from
        $this->admin_action_plugins = ''; //'simulation_restart.php';
        $this->admin_action_plugins_path = DIR_FS_CATALOG . 'mailhive/configbeez/'; // default-path to include admin action plugins from
        $this->admin_action_plugins = 'kill_process.php';

        $this->documentation_key = 'config'; // leave empty if no documentation available
        $this->documentation_root = 'http://www.mailbeez.com/documentation/installation/';
    }

// class methods

    function getAudience()
    {
        return false;
    }

    function check()
    {
        return true;
    }

    function remove()
    {
        return false;
    }

    // installation methods

    function keys()
    {
        return array('MAILBEEZ_MAILHIVE_PROCESS_CONTROL', 'MAILBEEZ_MAILHIVE_PROCESS_CONTROL_LOCK_PERIOD');
    }

    function install()
    {
        return false;
    }

    function set_kill()
    {
        // get latest entry
        $check_query_sql = "select batch_id, date_added
                                from " . TABLE_MAILBEEZ_PROCESS . "
                            order by lock_id desc limit 1,1";

        $check_query = mh_db_query($check_query_sql);

        if (mh_db_num_rows($check_query) > 0) {
            $check = mh_db_fetch_array($check_query);

            config_process_control::gc();
            // make kill file
            $kill_file_path = config_process_control::KILL_FOLDER . 'kill_' . $check['batch_id'] . '.txt';

            if ($fp = @fopen($kill_file_path, 'w')) {
                flock($fp, 2); // LOCK_EX
                fputs($fp, $check['batch_id']);
                flock($fp, 3); // LOCK_UN
                fclose($fp);
            }
        }
    }

    function check_kill($batch_id) {
        return file_exists(config_process_control::KILL_FOLDER . 'kill_' . $batch_id . '.txt');
    }


    function gc()
    {

        if ($dir = @opendir(config_process_control::KILL_FOLDER)) {

            while ($kill_file = readdir($dir)) {
                if (preg_match('/^kill/', $kill_file)) {
                    @unlink(config_process_control::KILL_FOLDER . $kill_file);
                }
            }
            closedir($dir);
        }

    }
}

?>